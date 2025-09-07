<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceAppointmentPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'userServiceAppId', 'positionId', 'positionedDate', 'releasedDate', 'current', 'active'
    ];

    public function appointment()
    {
        return $this->belongsTo(UserServiceAppointment::class, 'userServiceAppId');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'positionId');
    }
}
