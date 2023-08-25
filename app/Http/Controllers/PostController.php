<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {

        $post= Post::paginate(5);
        $posts = PostResource::collection( $post);
        // return $this->apiResponse($posts, '', 200);
        return response()->json([
            'data'=>$posts,
            'message'=>'',
        ],200);
    }

    public function show($id)
    {
        $post = Post::find($id);
        if($post) {
            // return $this->apiResponse(new PostResource($post), 'ok', 200);
            return response()->json([
                'data'=>new PostResource($post),
                'message'=>'ok',
            ],200);

        }
        // return $this->apiResponse(null, 'the post not found', 404);
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
        $post->user_id = Auth::user()->id;
        $post->category_id = $request->category_id;
        $post->save();
        if($post) {
            // return $this->apiResponse(new PostResource($post), 'ok', 201);
            return response()->json([
                'data'=>new PostResource($post),
                'message'=>'ok',
            ],201);
        }

    }
    //update
    public function update(PostRequest $request, $id)
    {

        $post = Post::find($id);

        if($post) {
            $post = new Post();
            $post->title = $request->title;
            $post->content =$request->content;
            $post->slug =$request->slug;
            $post->user_id = Auth::user()->id;
            $post->category_id = $request->category_id;
            $post->post_type = $request->post_type;
            $post->save();
            // return $this->apiResponse(new PostResource($post), 'the post update', 201);
            return response()->json([
                'data'=>new PostResource($post),
                'message'=>'the post update',
            ],201);
        }
        // return $this->apiResponse(null, 'the post not found', 404);
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

            $post->delete($id);
            // return $this->apiResponse(null, 'the post deleted', 200);
            return response()->json([
                'data'=>null,
                'message'=>'the post deleted',
            ],200);
        }

        // return $this->apiResponse(null, 'the post not found', 404);
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
        // return $this->apiResponse($result, 'ok', 201);
        return response()->json([
            'data'=>$result,
            'message'=>'ok',
        ],201);
       }else{
        // return $this->apiResponse(null, 'There is no post title it like '.$name , 404);
        return response()->json([
            'data'=>null,
            'message'=>'There is no post title it like'.$name,
        ],404);
       }

    }
}
