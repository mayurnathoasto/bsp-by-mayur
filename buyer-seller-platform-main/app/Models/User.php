<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Product;
use App\Models\City;
use App\Models\MarketplaceNotificationRecipient;
use App\Models\SupportTicket;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Add constants:
    public const SELLER_STATUS_NONE = 'none';
    public const SELLER_STATUS_PENDING = 'pending';
    public const SELLER_STATUS_APPROVED = 'approved';
    public const SELLER_STATUS_REJECTED = 'rejected';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'login_identifier',
        'country_code',
        'city_id',
        'pincode',
        'password',
        // Add to $fillable:
        'seller_status',
        'seller_submitted_at',
        'seller_verified_at',
        'seller_rejection_reason',
        'company_name',
        'company_phone',
        'company_address',
        'tax_id_type',
        'tax_id_number',
        'alternate_email',
        'office_contact',
        'alternate_mobile',
        'whatsapp_number',
        'designation',
        'profile_photo_path',
        'google_id',


    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'seller_submitted_at' => 'datetime',
            'seller_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the products for the user (supplier).
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Add helper:
    public function isSellerApproved(): bool
    {
        return $this->seller_status === self::SELLER_STATUS_APPROVED;
    }

    public function businessProfile()
    {
        return $this->hasOne(\App\Models\UserBusinessProfile::class);
    }

    public function bankDetail()
    {
        return $this->hasOne(\App\Models\UserBankDetail::class);
    }

    /**
     * Get the city associated with the user.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function notificationRecipients()
    {
        return $this->hasMany(MarketplaceNotificationRecipient::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function assignedReports()
    {
        return $this->hasMany(SupportTicket::class, 'target_user_id');
    }

    /**
     * Calculate profile completeness percentage.
     */
    public function getProfileCompletenessAttribute(): int
    {
        $fields = [
            'name',
            'email',
            'phone',
            'city_id',
            'pincode',
            'company_name',
            'company_phone',
            'company_address',
            'tax_id_type',
            'tax_id_number',
            'designation'
        ];

        $filledCount = 0;
        foreach ($fields as $field) {
            if (!empty($this->{$field})) {
                $filledCount++;
            }
        }

        // Check relationships
        if ($this->businessProfile()->exists()) {
            $filledCount++;
            // Check additional info
            if (!empty($this->businessProfile->additional_info)) {
                $filledCount++;
            }
        }

        if ($this->bankDetail()->exists()) {
            $filledCount++;
        }

        $totalFields = count($fields) + 3; // 11 fields + 3 extra checks
        return (int) (($filledCount / $totalFields) * 100);
    }
}
