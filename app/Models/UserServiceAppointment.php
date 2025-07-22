<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userServiceId',
        'workPlaceId',
        'appointedDate',
        'releasedDate',
        'reason',
        'appointmentType',
        'current',
    ];
}
