<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempLog extends Model
{
    use HasFactory;

    // Allow mass assignment of these fields.
    protected $fillable = [
        'user_id',
        'subcategory_id',
        'clock_in',
        'clock_out',
        'minutes',
        'notes',
    ];

    public function user()
    {
        // Sets up relationship with User model.
        // "A temp log belongs to a user."
        return $this->belongsTo(User::class);
    }

    public function subcategory()
    {
        // Sets up relationship with Subcategory model.
        // "A temp log belongs to a subcategory."
        return $this->belongsTo(Subcategory::class);
    }
}
