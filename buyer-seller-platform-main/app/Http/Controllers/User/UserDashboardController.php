<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\SellerDashboardViewData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        $data = $dashboardViewData->build(
            Auth::user(),
            (string) $request->get('period', 'month'),
            $request->get('month'),
            $request->get('year')
        );

        return view('user.dashboard', $data);
    }
}