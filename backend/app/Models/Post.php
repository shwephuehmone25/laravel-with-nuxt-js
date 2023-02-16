<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
    ];

    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function categories()
    {

        return $this->belongsToMany(Category::class, 'category_posts');
    }

    public function scopeFilter($query, $search)
    {

        return $query->when($search ?? false, function ($query, $search) {
            $query->where('title', 'LIKE', '%' . $search . '%');
        });
    }
}