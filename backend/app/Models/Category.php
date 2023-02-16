<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The post that belong to the categories.
     */
    public function posts()
    {

        return $this->belongsToMany(Post::class, 'category_posts');
    }

    public function scopeFilter($query, $search)
    {

        return $query->when($search ?? false, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
    }
}