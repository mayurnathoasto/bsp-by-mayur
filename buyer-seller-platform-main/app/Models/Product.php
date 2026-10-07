<?php

namespace App\Models;

use App\Services\Currency\ExchangeRateService;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_LIVE = 'live';
    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'name',
        'description',
        'price',
        'price_to',
        'currency',
        'country_code',
        'min_order_quantity',
        'lead_time_value',
        'lead_time_unit',
        'shipping_options',
        'payment_options',
        'sample_moq',
        'warranty',
        'support_option',
        'images',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'shipping_options' => 'array',
        'payment_options' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(\App\Models\Subcategory::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function specifications()
    {
        return $this->hasMany(\App\Models\ProductSpecification::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopePublicVisible($query)
    {
        return $query
            ->where('status', self::STATUS_LIVE)
            ->whereHas('user', function ($q) {
                $q->where('seller_status', \App\Models\User::SELLER_STATUS_APPROVED);
            });
    }

    public function getLeadTimeDisplayAttribute(): ?string
    {
        if (!$this->lead_time_value || !$this->lead_time_unit) {
            return null;
        }

        return $this->lead_time_value . ' ' . $this->lead_time_unit;
    }

    public function getShippingDisplayAttribute(): ?string
    {
        $items = array_values(array_filter($this->shipping_options ?? []));
        return empty($items) ? null : implode(' / ', $items);
    }

    public function getPaymentDisplayAttribute(): ?string
    {
        $items = array_values(array_filter($this->payment_options ?? []));
        return empty($items) ? null : implode(', ', $items);
    }

    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function getDisplayCurrencyAttribute(): string
    {
        return strtoupper(
            session('currency', $this->currency ?: config('countries.default_currency', 'INR'))
        );
    }

    public function getDisplayPriceAttribute(): ?float
    {
        if ($this->price === null) {
            return null;
        }

        return app(ExchangeRateService::class)->convert(
            (float) $this->price,
            $this->currency ?: config('countries.default_currency', 'INR'),
            $this->display_currency
        );
    }

    public function getDisplayPriceToAttribute(): ?float
    {
        if ($this->price_to === null) {
            return null;
        }

        return app(ExchangeRateService::class)->convert(
            (float) $this->price_to,
            $this->currency ?: config('countries.default_currency', 'INR'),
            $this->display_currency
        );
    }

    public function getDisplayPriceFormattedAttribute(): ?string
    {
        if ($this->display_price === null) {
            return null;
        }

        $minFormatted = app(ExchangeRateService::class)->format(
            $this->display_price,
            $this->display_currency
        );

        if ($this->display_price_to !== null) {
            $maxFormatted = number_format((float) $this->display_price_to, 2);
            return $minFormatted . ' - ' . $maxFormatted;
        }

        return $minFormatted;
    }
}
