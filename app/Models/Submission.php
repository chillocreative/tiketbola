<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'name',
        'ic_number',
        'phone',
        'address',
        'status',
        'category',
    ];
}
