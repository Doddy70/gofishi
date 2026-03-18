<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model implements AuthenticatableContract
{
    use HasApiTokens, HasFactory, Authenticatable;

    // Status Constants
    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Document Verification Constants
    const DOC_PENDING = 0;
    const DOC_VERIFIED = 1;
    const DOC_REJECTED = 2;

    protected $fillable = [
        'photo',
        'email',
        'dob',
        'to_mail',
        'phone',
        'username',
        'password',
        'status',
        'amount',
        'facebook',
        'twitter',
        'linkedin',
        'avg_rating',
        'email_verified_at',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
        'lang_code',
        'code',
        'ktp_file',
        'boat_ownership_file',
        'driving_license_file',
        'self_photo_file',
        'document_verified',
        'rejection_reason',
    ];

    public function vendor_infos()
    {
        return $this->hasMany(VendorInfo::class , 'vendor_id', 'id');
    }
    public function vendor_info()
    {
        return $this->hasOne(VendorInfo::class);
    }

    //support ticket
    public function support_ticket()
    {
        return $this->hasMany(SupportTicket::class , 'vendor_id', 'id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class , 'vendor_id', 'id');
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class);
    }
}