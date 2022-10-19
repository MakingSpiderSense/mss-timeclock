<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    public function subscribers()
    {
        // Sets up relationship with User model.
        // "An organization can have many users that subscribe to it."
        return $this->belongsToMany(User::class);
    }
}
