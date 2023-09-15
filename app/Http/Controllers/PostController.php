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
use App\Traits\formateTrait;

class PostController extends Controller
{
    use formateTrait;
    public function index()
    {
        $post= Post::paginate(5);
        $postWithImage=Post::with('images')->get();
        $posts = PostResource::collection($postWithImage);
        return $this->ApiFormate($posts,'',200);
    }
    public function show(PostRequest $request,$id)
    {
        $post_type=$request->post_type;
        $post = Post::find($id)->where('post_type',$post_type)->with('images');
        if($post) {
            return $this->ApiFormate(new PostResource($post),'ok',200);
        }
        return $this->ApiFormate(null,'the post not found',404);
    }
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
        return $this->ApiFormate(new PostResource($post),'ok',200);
    }
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
            return $this->ApiFormate(new PostResource($post),'the post update',200);
        }
        return $this->ApiFormate(null,'the post not found',404);
        }
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post)
        {
            if($post->images)
            {
                foreach($post->images as $image)
                {
                    DeleteOldImages($image);
                    $post->images()->delete();
                }
            }
            $post->delete($id);
            return $this->ApiFormate(null,'the post deleted',200);
        }
        return $this->ApiFormate(null,'the post not found',404);
    }
    public function search($name)
    {
       $result = Post::where("title", "like", "%".$name."%")->get();
       if(count($result))
       {
        return $this->ApiFormate($result,'ok',201);
       }
       else
       {
        return $this->ApiFormate(null,'There is no post title it like'.$name,404);
       }

    }
}
