<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceAppointmentPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'userServiceAppointmentId',
        'positionId',
        'positionedDate',
        'releasedDate',
        'current',
        'active'
    ];
}
