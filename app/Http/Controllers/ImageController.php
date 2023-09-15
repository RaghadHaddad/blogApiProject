<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Facades\File;
use App\Models\{Image,Post,User};

class ImageController extends Controller
{
    public function index(){

        $images = ImageResource::collection(Image::get());
          return response()->json([
            'data'=>$images,
            'message'=>'',
          ],200);
      }
      public function show($id)
      {
          $image =Image::find($id);
          if($image){
            return response()->json([
                'data'=>new ImageResource($image),
                'message'=>'ok',
              ],200);
          }
        return response()->json([
            'data'=>null,
            'message'=>'the image not found',
          ],404);
      }
       public function store(ImageRequest $request)
       {
        $image = new Image();
        $image->type = $request->type;
          if($request->has('fileName')){

              $file = $request->fileName;
              $destination='images/posts';
              $uploadImagePath=uploadImage($file,$destination);
              $image-
              $image->save();

            //   return $this->apiResponse(new ImageResource($image), 'Image Uploaded Successfuly', 201);
            return response()->json([
                'data'=>new ImageResource($image),
                'message'=>'Image Uploaded Successfuly',
              ],201);
          }
      }
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

      public function showImageUser($id)
      {
        $user=User::findOrFail($id);
        $imageAll=$user->images;
        if($imageAll)
        {
            return response()->json([
                'data'=>new ImageResource($imageAll),
                'message'=>'ok',
              ],200);
        }
        return response()->json([
            'data'=>null,
            'message'=>'the images for user'.$id.' not found',
          ],404);
      }
      public function AddImageUser(ImageRequest $request,$id)
      {
        $user=User::findOrFail($id);
        $imageAll=$user->images;
        if(!$imageAll)
        {
           $imageAll=$imageAll->images()->create([
            'fileName'=>$request->fileName,
            'type'=>$request->type
           ]);
        }
        return response()->json([
            'data'=>new ImageResource($imageAll),
            'message'=>'the images for user'.$id.'already found',
          ],404);
      }
 }

