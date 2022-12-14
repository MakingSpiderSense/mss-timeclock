<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function subscribers()
    {
        // Sets up relationship with User model.
        // "An organization can have many users that subscribe to it."
        return $this->belongsToMany(User::class, 'user_org_subscriptions', 'org_id', 'user_id');
    }

    public function organizations()
    {
        // Sets up relationship with Category model.
        // "An organization can have many categories."
        return $this->hasMany(Category::class)->orderBy('name');
    }
}
