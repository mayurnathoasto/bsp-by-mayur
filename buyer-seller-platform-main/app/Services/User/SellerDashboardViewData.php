<?php

namespace App\Services\User;

use App\Models\Product;
use App\Models\ProductInquiry;
use Illuminate\Support\Facades\DB;

class SellerDashboardViewData
{
    public function build($user, string $period = 'month', ?int $selectedMonth = null, ?int $selectedYear = null): array
    {
        $user = $user->load([
            'businessProfile',
            'bankDetail',
            'city',
        ]);

        if (!in_array($period, ['day', 'week', 'month'], true)) {
            $period = 'month';
        }

        $selectedMonth = (int) ($selectedMonth ?: now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }

        $selectedYear = (int) ($selectedYear ?: now()->year);
        if ($selectedYear < 2020 || $selectedYear > (now()->year + 1)) {
            $selectedYear = now()->year;
        }

        $productStats = Product::where('user_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as live
            ", [Product::STATUS_PENDING, Product::STATUS_LIVE])
            ->first();

        $inquiryStats = ProductInquiry::where('seller_user_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as unread,
                SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
            ")
            ->first();

        $stats = [
            'products_count' => (int) ($productStats->total ?? 0),
            'pending_products_count' => (int) ($productStats->pending ?? 0),
            'live_products_count' => (int) ($productStats->live ?? 0),

            'inquiries_count' => (int) ($inquiryStats->total ?? 0),
            'new_inquiries_count' => (int) ($inquiryStats->unread ?? 0),
            'contacted_inquiries_count' => (int) ($inquiryStats->contacted ?? 0),
            'closed_inquiries_count' => (int) ($inquiryStats->closed ?? 0),
        ];

        $recentProducts = Product::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentInquiries = ProductInquiry::with(['product'])
            ->where('seller_user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $displayCity = $this->resolveDisplayCity($user);
        $displayState = $this->resolveDisplayState($user);
        $displayCountry = $this->resolveDisplayCountry($user);

        $chart = $this->buildInquiryChartData($user->id, $period, $selectedMonth, $selectedYear);
        $years = range(now()->year - 3, now()->year + 1);

        return [
            'user' => $user,
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentInquiries' => $recentInquiries,
            'displayCity' => $displayCity,
            'displayState' => $displayState,
            'displayCountry' => $displayCountry,
            'period' => $period,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'years' => $years,
            'chart' => $chart,
        ];
    }

    protected function buildInquiryChartData(int $sellerUserId, string $period, int $selectedMonth, int $selectedYear): array
    {
        if ($period === 'day') {
            $start = now()->copy()->subDays(6)->startOfDay();
            $end = now()->copy()->endOfDay();

            $rows = ProductInquiry::query()
                ->selectRaw(DB::getDriverName() === 'sqlite' 
                    ? 'DATE(created_at) as bucket, COUNT(*) as total' 
                    : 'DATE(created_at) as bucket, COUNT(*) as total')
                ->where('seller_user_id', $sellerUserId)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy(DB::raw(DB::getDriverName() === 'sqlite' ? 'DATE(created_at)' : 'DATE(created_at)'))
                ->pluck('total', 'bucket')
                ->toArray();

            $labels = [];
            $series = [];

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $key = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $series[] = (int) ($rows[$key] ?? 0);
            }

            return [
                'title' => 'Last 7 Days',
                'labels' => $labels,
                'series' => $series,
            ];
        }

        if ($period === 'week') {
            $start = now()->copy()->startOfWeek()->subWeeks(7);
            $labels = [];
            $series = [];

            for ($i = 0; $i < 8; $i++) {
                $weekStart = $start->copy()->addWeeks($i);
                $weekEnd = $weekStart->copy()->endOfWeek();

                $count = ProductInquiry::query()
                    ->where('seller_user_id', $sellerUserId)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->count();

                $labels[] = $weekStart->format('d M');
                $series[] = $count;
            }

            return [
                'title' => 'Last 8 Weeks',
                'labels' => $labels,
                'series' => $series,
            ];
        }

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $selectRaw = "CAST(strftime('%m', created_at) AS INTEGER) as month_num, COUNT(*) as total";
            $groupBy = "strftime('%m', created_at)";
        } else {
            $selectRaw = "MONTH(created_at) as month_num, COUNT(*) as total";
            $groupBy = "MONTH(created_at)";
        }

        $monthlyRows = ProductInquiry::query()
            ->selectRaw($selectRaw)
            ->where('seller_user_id', $sellerUserId)
            ->whereYear('created_at', $selectedYear)
            ->groupBy(DB::raw($groupBy))
            ->pluck('total', 'month_num')
            ->toArray();

        $monthNames = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        $labels = [];
        $series = [];

        foreach ($monthNames as $monthNum => $monthName) {
            $labels[] = $monthName;
            $series[] = (int) ($monthlyRows[$monthNum] ?? 0);
        }

        return [
            'title' => 'Monthly Inquiries (' . $selectedYear . ')',
            'labels' => $labels,
            'series' => $series,
        ];
    }

    protected function resolveDisplayCity($user): ?string
    {
        if (!empty($user->businessProfile?->city_name)) {
            return $user->businessProfile->city_name;
        }

        if (!empty($user->city?->name)) {
            return $user->city->name;
        }

        return null;
    }

    protected function resolveDisplayState($user): ?string
    {
        if (!empty($user->businessProfile?->state_name)) {
            return $user->businessProfile->state_name;
        }

        if (!empty($user->city?->state_name)) {
            return $user->city->state_name;
        }

        return null;
    }

    protected function resolveDisplayCountry($user): ?string
    {
        if (!empty($user->businessProfile?->country_code)) {
            return strtoupper($user->businessProfile->country_code);
        }

        if (!empty($user->country_code)) {
            return strtoupper($user->country_code);
        }

        return strtoupper(session('country', config('countries.default_country', 'IN')));
    }
}
