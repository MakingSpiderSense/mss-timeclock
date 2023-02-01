<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermLog extends Model
{
    use HasFactory;

    // Allow mass assignment of these fields.
    protected $fillable = [
        'user_id',
        'subcategory_id',
        'minutes',
        'month',
    ];
}
