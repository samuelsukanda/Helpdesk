<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeArticle extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'category_id', 'author_id', 'status', 'views', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
