<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $fillable = [
        'hotel_id', 'vendor_id', 'feature_image', 'nama_km', 'captain_name', 'average_rating', 
        'latitude', 'longitude', 'status', 'booking_type', 'deposit_type', 
        'deposit_amount', 'bed', 'min_price', 'max_price', 'adult', 'children', 
        'bathroom', 'bedroom_count', 'toilet_count', 'number_of_rooms_of_this_same_type', 
        'preparation_time', 'area', 'prices', 'perahu_area_text', 'daily_price', 
        'price_day_1', 'price_day_2', 'price_day_3', 'meet_time_day_1', 
        'return_time_day_1', 'area_day_1', 'meet_time_day_2', 'return_time_day_2', 
        'area_day_2', 'meet_time_day_3', 'return_time_day_3', 'area_day_3', 
        'additional_service', 'boat_length', 'boat_width', 'crew_count', 
        'engine_1', 'engine_2', 'bait', 'fishing_gear', 'life_jacket', 
        'breakfast', 'lunch', 'dinner', 'mineral_water', 'ac', 'wifi', 
        'electricity', 'stove', 'refrigerator', 'other_features', 'video_url'
    ];

    protected $casts = [
        'additional_service' => 'array',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function room_content()
    {
        return $this->hasMany(RoomContent::class, 'room_id', 'id');
    }
    public function room_galleries()
    {
        return $this->hasMany(RoomImage::class, 'room_id', 'id');
    }
    public function room_prices()
    {
        return $this->hasMany(HourlyRoomPrice::class, 'room_id', 'id');
    }
    public function room_feature()
    {
        return $this->hasOne(RoomFeature::class, 'room_id', 'id');
    }
    public function packages()
    {
        return $this->hasMany(BoatPackage::class, 'room_id', 'id');
    }
}
