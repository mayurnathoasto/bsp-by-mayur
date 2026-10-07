<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'voice_recording',
        'financing',
        'gst_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}