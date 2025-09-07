<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserServiceAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userServiceId', 'workPlaceId', 'appointedDate', 'releasedDate',
        'reason', 'appointmentType', 'current'
    ];

    public function workPlace()
    {
        return $this->belongsTo(WorkPlace::class, 'workPlaceId');
    }

    public function currentPosition()
    {
        return $this->hasOne(UserServiceAppointmentPosition::class, 'userServiceAppId')
            ->where('current', 1)
            ->where('active', 1);
    }

}
