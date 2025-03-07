<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClassStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'schoolClassId',
        'studentId',
        'current',
        'active',
    ];
}
