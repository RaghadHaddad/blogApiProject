<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\formateTrait;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    use formateTrait;
    public function addTags(Request $request , $id){

        $post = Post::find($id);
        $post->tags()->syncWithoutDetaching($request->tags);
        $post_tag = $post->load('tags');
        return $this->ApiFormate($post_tag,'ok',201);
    }
    public function deleteTag(Request $request , $id)
    {
        $post = Post::find($id);
        $post->tags()->detach($request->tags);
        // return $this->ApiFormate( null,'the tag deleted successfuly', 200);
    }
    public function show($id)
    {
       $post = Post::find($id);
       return  $post->load('tags');
    }
}
