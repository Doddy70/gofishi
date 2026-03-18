<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReview extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'room_id',
        'hotel_id',
        'rating',
        'review',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userInfo()
    {
        return $this->user();
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function hotelRoom()
    {
        return $this->room();
    }
}
