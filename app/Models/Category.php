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

    public function categories()
    {
        // Sets up relationship with Subcategory model.
        // "An category can have many subcategories."
        return $this->hasMany(Subcategory::class)->orderBy('name');
    }
}
