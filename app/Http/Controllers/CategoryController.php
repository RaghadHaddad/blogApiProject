<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Traits\formateTrait;

class CategoryController extends Controller
{
    use formateTrait;
    public function index()
    {
      $categories = CategoryResource::collection(Category::get());
      return $this->ApiFormate($categories,' ',200);
    }
    public function show($id)
    {
        $category = Category::find($id);
        if( $category){
            return $this->ApiFormate(new CategoryResource($category),'ok',200);
        }
        return $this->ApiFormate(null,'the category not found',404);
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
        $category = Category::create($request->all());
        if($category) {
            return $this->ApiFormate(new CategoryResource($category),'ok',201);
        }
    }
    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return $this->ApiFormate(null,$validator->errors(),400);
        }
        $category = Category::find($id);
        if($category) {
            $category->update($request->all());
            return $this->ApiFormate(new CategoryResource($category),'the category update successfuly',201);
        }
        return $this->ApiFormate(null,'the category not found',400);
    }
    public function destroy( $id)
    {
        $category = Category::find($id);
        if($category) {
            $category->delete($id);
            return $this->ApiFormate(null,'the category deleted successfully',200);
        }
        return $this->ApiFormate(null,'the category not found',400);
    }
}
