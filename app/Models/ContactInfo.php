<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ContactInfo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'userId',
        'permAddressLine1',
        'permAddressLine2',
        'permAddressLine3',
        'tempAddressLine1',
        'tempAddressLine2',
        'tempAddressLine3',
        'mobile1',
        'mobile2',
        'active',
    ];
}
