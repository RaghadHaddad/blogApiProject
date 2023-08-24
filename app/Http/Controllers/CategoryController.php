<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index(){
        //fetch all categories from database and store in $categories
      $categories = CategoryResource::collection(Category::get());
        return response()->json([
            'data'=>$categories,
            'message'=>' ',
        ],200);
    }
    public function show($id){

        $category = Category::find($id);
        if( $category){
            return response()->json([
                'data'=>new CategoryResource($category),
                'message'=>'ok',
            ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the category not found',
        ],404);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
              'data'=>null,
              'message'=> $validator->errors(),
            ],400);
        }
        $category = Category::create($request->all());

        if($category) {
            return response()->json([
                'data'=>new CategoryResource($category),
                'message'=>'ok'
            ],201);
        }
    }
    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return response()->json([
                'data' =>null,
                'message' => $validator->errors(),
            ],400);
        }
        $category = Category::find($id);
        if($category) {
            $category->update($request->all());
            return response()->json([
                'data' =>new CategoryResource($category),
                'message' => 'the category update successfuly',
            ],201);
        }
        return response()->json([
            'data' =>null,
            'message' => 'the category not found',
        ],400);

    }
    public function destroy( $id){
        $category = Category::find($id);
        if($category) {
            $category->delete($id);
            return response()->json([
                'data' =>null,
                'message' => 'the category deleted successfully',
            ],200);
        }
        return response()->json([
            'data' =>null,
            'message' => 'the category not found',
        ],400);
    }
}
