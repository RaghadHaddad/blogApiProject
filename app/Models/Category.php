<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    use HasFactory;

   /**
    * Get all of the posts for the Catergory
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function posts(): HasMany
   {
       return $this->hasMany(Post::class);
   }
}
