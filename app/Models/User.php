<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'nickname',
        'avatar_url',
        'active_org_id',
        'set_combined_org',
        'goal_hours',
        'global_rate',
        'simple_tax_rate',
        'display_before_tax',
        'recent_subcategories',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptions()
    {
        // Sets up relationship with Organization model.
        // "A user can subscribe to many organizations."
        return $this->belongsToMany(Organization::class, 'user_org_subscriptions', 'user_id', 'org_id');
    }

    public function tempLogs()
    {
        // Sets up relationship with TempLog model.
        // "A user can have many temp logs."
        return $this->hasMany(TempLog::class);
    }

    public function categories()
    {
        // Sets up relationship with Category model.
        // "A user belong to many categories."
        return $this->belongsToMany(Category::class, 'user_category', 'user_id', 'category_id');
    }
}
