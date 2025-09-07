<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserInService extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId', 'serviceId', 'appointedDate', 'releasedDate', 'current'
    ];

    public function currentAppointment()
    {
        return $this->hasOne(UserServiceAppointment::class, 'userServiceId')
            ->where('appointmentType', 1)
            ->whereNull('releasedDate')
            ->where('active', 1);
    }


}
