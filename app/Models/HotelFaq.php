<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelFaq extends Model
{
    use HasFactory;
    
    protected $table = 'hotel_faqs';

    protected $fillable = [
        'hotel_id',
        'language_id',
        'question',
        'answer',
        'serial_number'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
