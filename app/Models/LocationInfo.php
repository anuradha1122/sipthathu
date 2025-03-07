<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'educationDivisionId',
        'gnDivisionId',
        'active',
    ];
}
