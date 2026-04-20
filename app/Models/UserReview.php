<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class , 'vendor_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class , 'booking_id');
    }
}