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
            'user' => $user,
            'message' => 'User successfully registered'
        ], 201);
    }
    public function profile()
    {
        return response()->json(auth()->user());
    }
    public function logout()
         {
             auth()->logout();
             return response()->json(['message'=>'successfully logout']);
        }
        public function updateProfile(Request $request,$id)
        {
            $input = $request->all();
            $user = User::find($id);
            $validator = FacadesValidator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100',
                'role' => 'required|string',
                'check_plan' => 'required|string',
                'password' => 'nullable|string|min:8',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            if((!empty($input['password'])) && (!empty($input['role'])) && (!empty($input['check_plan'])))
            {
                $input['password'] = $input['password'] ;
                $input['role'] = $input['role'] ;
                $input['check_plan'] = $input['check_plan'] ;
                }else{
                $input = array_merge($input, array('password','role','check_plan'));
                }
                $user->update($input);

                return response()->json([
                    'message' => 'User Updated registered',
                    'user' => $user
                ], 201);

        }
        public function SoftDelete($id)
        {
            $user=User::find($id);
            $user->delete();
            return response()->json([
                'message'=>'deleted successfully'
            ],200);
        }
        public function NotDeleteForEver()
        {
            $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
            return response()->json([
                'data' => $users ,
                'message' => 'ok',
            ], 201);
        }
        public function restore($id)
        {
            if(User::withTrashed()->find($id))
            {
                User::withTrashed()->where('id', $id)->restore();
                return response()->json([
                    'message'=>'restored successfully'
                ],200);
            }
            return response()->json([
                'message'=>'not found'
            ],404);

        }
        public function forceDeleted($id)
        {
        //   $user=User::find($id);
        //     if($user)
        //     {
        //         $user->destroy($id);
        //         return response()->json([
        //             'message'=>'deleted successfully'
        //         ],200);
        //     }
        //     return response()->json([
        //         'message'=>'not found'
        //     ],404);
        $user = User::onlyTrashed()->find($id);
        if($user){
            $user->forceDelete();
            return response()->json([ 'message' => 'user deleted successfully',], 201);

        }
        return response()->json([ 'message' => 'user not  found',], 404);
    }
        public function check(Request $request,$id)
        {
            $user=User::find($id);
            if($user)
            {
                $user->check_plan=$request->check_plan;
                $user->save();
                return response()->json([
                    'date'=>$user->check_plan,
                    'message'=>'successfully'
                ],200);
            }
            return response()->json([
                'data'=>null,
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
