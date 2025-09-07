<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PersonalInfo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'userId',
        'profilePicture',
        'raceId',
        'religionId',
        'civilStatusId',
        'genderId',
        'birthDay',
        'bloodGroupId',
        'illnessId',
        'active',
    ];
}
