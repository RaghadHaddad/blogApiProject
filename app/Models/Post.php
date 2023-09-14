<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable=['user_id' , 'category_id' , 'title' , ' content' , 'slug'];
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }
    public function images()
    {
        return $this->morphMany(Images::class,'imageable');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class , 'category_id' , 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'user_id', 'id');
    }
}
