<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Traits\formateTrait;

class TagController extends Controller
{
    use formateTrait;
    public function index(){
      $tags = TagResource::collection(Tag::get());
      return $this->ApiFormate($tags,'',200);
    }
    public function show($id)
    {
        $tag = Tag::find($id);
        if($tag){
            return $this->ApiFormate(new TagResource($tag),'ok',200);
        }
        return $this->ApiFormate(null,'the tag not found',404);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails())
        {
            return $this->ApiFormate(null,$validator->errors(),400);
        }
        $tag = Tag::create($request->all());
        if($tag) {
            return $this->ApiFormate(new TagResource($tag),'ok',201);
        }
    }
    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails())
        {
            return $this->ApiFormate(null,$validator->errors(),400);
        }
        $tag = Tag::find($id);
        if($tag) {
            $tag->update($request->all());
            return $this->ApiFormate(new TagResource($tag),'the tag update successfuly',201);
        }
        return $this->ApiFormate(null,'the tag not found',404);
    }
    public function destroy( $id)
    {
        $tag = Tag::find($id);
        if($tag) {
            $tag->delete($id);
            return $this->ApiFormate(null,'the tag deleted successfuly',200);
        }
        return $this->ApiFormate(null,'the tag not found',400);
    }
}
