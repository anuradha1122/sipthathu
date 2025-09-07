<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPlace extends Model
{
    use HasFactory;

    public function school()
    {
        return $this->hasOne(School::class, 'workPlaceId');
    }

    public function office()
    {
        return $this->hasOne(Office::class, 'workPlaceId');
    }

    public function ministry()
    {
        return $this->hasOne(Ministry::class, 'workPlaceId');
    }

}
