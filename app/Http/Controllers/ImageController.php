<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\File;
use App\Models\Image;

class ImageController extends Controller
{
    public function index(){

        $images = ImageResource::collection(Image::get());
        //   return $this->apiResponse($images ,'' , 200);
          return response()->json([
            'data'=>$images,
            'message'=>'',
          ],200);
      }

      public function show($id){

          $image = Image::find($id);
          if( $image){
            //   return $this->apiResponse(new ImageResource($image) ,'ok' , 200);
            return response()->json([
                'data'=>new ImageResource($image),
                'message'=>'ok',
              ],200);
          }
        //   return $this->apiResponse(null,'the image not found' , 404);
        return response()->json([
            'data'=>null,
            'message'=>'the image not found',
          ],404);
      }


       //store
       public function store(ImageRequest $request){


          if($request->has('fileName')){
              $image = new Image();
              $file = $request->fileName;
              $name = time().'.'.$file->getClientOriginalExtension();
              $path = public_path().'/uploads';
              $image->fileName =$name ;
              $file->move( $path , $name );
              $image->post_id = $request->post_id;
              $image->save();

            //   return $this->apiResponse(new ImageResource($image), 'Image Uploaded Successfuly', 201);
            return response()->json([
                'data'=>new ImageResource($image),
                'message'=>'Image Uploaded Successfuly',
              ],201);
          }
      }

      //update
      public function update(ImageRequest $request , $id){

          $image = Image::find($id);

              if($image) {
                  $oldImage= $image->fileName;
                  $file = $request->fileName;
                  $name = time().'.'.$file->getClientOriginalExtension();
                  $path = public_path().'/uploads';
                  $image->fileName =$name ;
                  $file->move($path, $name);
                  $image->post_id = $request->post_id;
                  $image->save();
                  File::delete(public_path().'/uploads/'.$oldImage);
                //   return $this->apiResponse(new ImageResource($image), 'the image update', 201);
                return response()->json([
                    'data'=>new ImageResource($image),
                    'message'=>'the image update',
                  ],201);
              }

        //   return $this->apiResponse(null, 'the image not found', 404);
        return response()->json([
            'data'=>null,
            'message'=>'the image not found',
          ],404);
      }

      //delete
      public function destroy( $id)
      {
          $image = Image::findOrFail($id);
          if($image) {
              File::delete(public_path().'/uploads/'.$image->fileName);
              $image->delete($id);
            //   return $this->apiResponse(null ,'the image deleted', 200);
            return response()->json([
                'data'=>null,
                'message'=>'the image deleted',
              ],200);
          }

        //   return $this->apiResponse(null, 'the image not found', 404);
        return response()->json([
            'data'=>null,
            'message'=>'the image not found',
          ],404);
      }
}
