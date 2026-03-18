<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'description',
        'price',
        'duration_days',
        'meeting_time',
        'return_time',
        'area',
        'status',
    ];

    /**
     * Get the room that owns the package.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
