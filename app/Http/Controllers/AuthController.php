<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Http\Resources\UserResource;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
     $validator = FacadesValidator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    public function profile()
    {
        return response()->json([
            auth()->user()
        ]);
    }
    public function logout()
         {
             auth()->logout();
             return response()->json(['message'=>'successfully logout']);
        }
        public function updateProfile(Request $request,$id)
        {

            $user = User::find($id);

            if($user) {
                $user = new User();
                $user->name = $request->name;
                $user->email =$request->email;
                $user->password =$request->password;
                $user->save();

                return response()->json([
                    'data'=>new UserResource($user),
                    'message'=>'the user update',
                ],201);
            }
            return response()->json([
                'data'=>null,
                'message'=>'the user not found',
            ],404);
        }
        public function SoftDelete($id)
        {
            $user=User::find($id);
            $user->delete();
            return response()->json([
                'message'=>'deleted successfully'
            ],200);
        }
        public function restore($id)
        {
            $user=User::find($id);
            if($user)
            {
                $user=User::where('id',$id)->first()->restore();
                return response()->json([
                    'message'=>'restored successfully'
                ],200);
            }
            return response()->json([
                'message'=>'not found'
            ],400);
        }
        public function forceDeleted($id)
        {
            $user=User::onlyTrashed()->find($id);
            if($user)
            {
                $user->forceDeleted();
                return response()->json([
                    'message'=>'deleted successfully'
                ],200);
            }
            return response()->json([
                'message'=>'not found'
            ],400);
        }
        //Create New Token
        protected function createNewToken($token){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                //Token Expired
                'expires_in' => auth()->factory()->getTTL()*60,
                'user' => auth()->user()
            ]);
        }
}
