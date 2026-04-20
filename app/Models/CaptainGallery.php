<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaptainGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'image',
        'title',
        'weight',
        'serial_number'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
