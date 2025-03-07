<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInService extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'serviceId',
        'appointedDate',
        'releasedDate',
        'current',
    ];
}
