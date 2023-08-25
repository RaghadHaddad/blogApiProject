<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;


class TagController extends Controller
{
    public function index(){
      $tags = TagResource::collection(Tag::get());
        return response()->json([
            'data'=>$tags,
            'message'=>'',
        ],200);
    }
    public function show($id){
        $tag = Tag::find($id);
        if($tag){
            return response()->json([
                'data'=>new TagResource($tag),
                'message'=>'ok',
            ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the tag not found',
        ],404);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'data'=>null,
                'message'=>$validator->errors(),
            ],400);
        }
        $tag = Tag::create($request->all());
        if($tag) {
            return response()->json([
                'data'=>new TagResource($tag),
                'message'=>'ok',
            ],201);
        }
    }
    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'data'=>null,
                'message'=>$validator->errors(),
            ],400);
        }
        $tag = Tag::find($id);
        if($tag) {
            $tag->update($request->all());
            return response()->json([
                'data'=>new TagResource($tag),
                'message'=>'the tag update successfuly',
            ],201);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the tag not found',
        ],404);
    }
    public function destroy( $id)
    {
        $tag = Tag::find($id);
        if($tag) {

            $tag->delete($id);
            return response()->json([
                'data'=>null,
                'message'=>'the tag deleted successfuly',
            ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the tag not found',
        ],404);
    }
}
