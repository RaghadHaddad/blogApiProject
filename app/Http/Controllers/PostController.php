<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostRequest;
use App\Models\Image;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {

        $post= Post::paginate(5);
        $postWithImage=Post::with('images')->get();
        $posts = PostResource::collection($postWithImage);
        return response()->json([
            'data'=>$posts,
            'message'=>'',
        ],200);
    }
/* public function showImagePost($id)
      {
        $post=Post::findOrFail($id);
        $imageAll=$post->with('images')->get();
        if($imageAll)
        {
            return response()->json([
                'data'=>new ImageResource($imageAll),
                'message'=>'ok',
              ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the images for post'.$id.' not found',
          ],404);
      }*/


    public function show(PostRequest $request,$id)
    {

        $post_type=$request->post_type;
        $post = Post::find($id)->where('post_type',$post_type)->with('images');
        if($post) {
            return response()->json([
                'data'=>new PostResource($post),
                'message'=>'ok',
            ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the post not found',
        ],404);
    }
    //store
    public function store(PostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->content =$request->content;
        $post->slug =$request->slug;
        $post->post_type =$request->post_type;
        $post->user_id = Auth::user()->id;
        $post->category_id = $request->category_id;
         if($request->hasFile('fileName'))
         {
            foreach($request->file('images') as $image)
            {
                $type=$request->type;
                $destination='images/posts';
                $uploadImagePath=uploadImage($image,$destination);
                $post->images()->create([
                    'fileName'=>$uploadImagePath,
                    'type'=>$type
                ]);
            }
           
        }
        $post->save();
        return response()->json([
            'data'=>new PostResource($post),
            'message'=>'ok',
        ],200);
    }


    //update
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
        if($post)
        {
            if($request->hasFile('fileName'))
             {
            foreach($post->images as $image)
            {
                DeleteOldImages($image);
            }
            foreach($request->file('images') as $image)
            {
                $type=$request->type;
                $destination='images/posts';
                $uploadImagePath=uploadImage($image,$destination);
                $post->images()->create([
                    'path'=>$uploadImagePath,
                    'type'=>$type
                ]);
            }
          }
            $post->title = $request->title;
            $post->content =$request->content;
            $post->slug =$request->slug;
            $post->user_id = Auth::user()->id;
            $post->category_id = $request->category_id;
            $post->post_type = $request->post_type;
            $post->save();
            return response()->json([
                'data'=>new PostResource($post),
                'message'=>'the post update',
            ],201);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the post not found',
        ],404);
        }
    //delete
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post) {
            if($post->images)
            {
                foreach($post->images as $image)
                {
                    DeleteOldImages($image);
                    $post->images()->delete();
                }
            }
            $post->delete($id);
            return response()->json([
                'data'=>null,
                'message'=>'the post deleted',
            ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the post not found',
        ],404);

    }

    //search
    public function search($name)
    {
       $result = Post::where("title", "like", "%".$name."%")->get();
       if(count($result)){
        return response()->json([
            'data'=>$result,
            'message'=>'ok',
        ],201);
       }else{
        return response()->json([
            'data'=>null,
            'message'=>'There is no post title it like'.$name,
        ],404);
       }

    }
}
