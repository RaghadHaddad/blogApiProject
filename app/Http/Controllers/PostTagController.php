<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostTagController extends Controller
{

    public function addTags(Request $request , $id){

        $post = Post::find($id);
        $post->tags()->syncWithoutDetaching($request->tags);
        $post_tag = $post->load('tags');
        return response()->json([
            'data'=>$post_tag,
            'message'=>'ok',
        ],201);
    }
         /*     $tags = $request->get('tags');

    if (!empty($tags)) {
        $tagList = array_filter(explode(",", $tags));

        // Loop through the tag array that we just created
        foreach ($tagList as $tags) {
            $tag = Tag::firstOrCreate(['name' => $tags, 'slug' => str_slug($tags)]);
        }

        $tags = Tag::whereIn('name', $tagList)->get()->pluck('id');

        $article->tags()->sync($tags);
    }  */


    public function deleteTag(Request $request , $id){
        //store post's tags
        $post = Post::find($id);
        //detach : delete tag from post
        $post->tags()->detach($request->tags);

        // return $this->apiResponse( null,'the tag deleted successfuly', 200);
        return response()->json([
            'data'=>null,
            'message'=>'the tag deleted successfuly',
        ],200);
    }

    public function show($id){

        $post = Post::find($id);
       return  $post->load('tags');
    }
}
