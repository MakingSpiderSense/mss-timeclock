<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'last_used',
    ];

    public function category()
    {
        // Sets up relationship with Category model.
        // "A subcategory has one category."
        return $this->hasOne(Category::class);
    }

    public function temp_logs()
    {
        // Sets up relationship with TempLog model.
        // "A subcategory has many temp logs."
        return $this->hasMany(TempLog::class);
    }
}
