<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'org_id',
    ];

    public function organization()
    {
        // Sets up relationship with Organization model.
        // "A category has one organization."
        return $this->hasOne(Organization::class);
    }

    public function subcategory()
    {
        // Sets up relationship with Subcategory model.
        // "A category can have many subcategories."
        return $this->hasMany(Subcategory::class)->orderBy('name');
    }

    public function users()
    {
        // Sets up relationship with User model.
        // "A category can have many users."
        return $this->belongsToMany(User::class, 'user_category', 'category_id', 'user_id');
    }
}
