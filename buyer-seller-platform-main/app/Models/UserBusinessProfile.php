<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBusinessProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_logo_path',
        'company_name',
        'company_phone',
        'fax_number',
        'ownership_type',
        'annual_turnover_currency',
        'annual_turnover_amount',
        'annual_turnover_unit',
        'year_established',
        'employee_count',
        'address_line',
        'city_id',
        'city_name',
        'state_name',
        'country_code',
        'pincode',
        'place_id',
        'formatted_address',
        'lat',
        'lng',
        'tax_ids_json',
        'documents_json',
        'additional_info',
        'logo',
        'company_photos',
        'video_url',
        'video_title',
        'video_category',
        'payment_modes',
        'excluded_cities',
        'working_days_from',
        'working_days_to',
        'working_time_from',
        'working_time_to',
        'preferred_cities',
        'preferred_states',
        'company_description',
        'standard_certification',
        'membership_affiliations',
        'award_recognition',
        'certifications',
        'awards',
    ];

    protected $casts = [
        'tax_ids_json' => 'array',
        'documents_json' => 'array',
        'additional_info' => 'array',
        'company_photos' => 'array',
        'payment_modes' => 'array',
        'certifications' => 'array',
        'awards' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'annual_turnover_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}