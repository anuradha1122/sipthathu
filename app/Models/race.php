<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Race extends Model
{
    use HasFactory, HasUuids;
    public $incrementing = false;   // UUIDs are not auto-incrementing
    protected $keyType = 'string'; // UUID is stored as string
}
