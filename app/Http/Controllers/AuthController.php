<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginUsers;
use App\Http\Requests\RegisterUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{



    public function register(RegisterUsers $request)
    {
         $validator = $request->validated();
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|between:2,100',
        //     'email' => 'required|string|email|max:100|unique:users',
        //     'password' => 'required|string|min:6',
            
        // ]);
        // if($validator->fails()){
        //     return response()->json([
            
        //         'status' =>'failure', 
        //           'data'  =>  'faillll'
        //     ]);
        // }

        $user = User::create(array_merge(
                    [
                     'name' => $validator['name'],
                     'email' => $validator['email'],
                      'password' => bcrypt($validator['password']),

                    ]
                ));
                $token = $user->createToken('authtoken');

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'token' => $token->plainTextToken
        ]);


    }


    public function login(LoginUsers $request)
    {
        $validator = $request->validated();
    // $validator = Validator::make($request->all(), [
    //     'email' => 'required|email',
    //     'password' => 'required|string|min:6',
        
    // ]);
    // if ($validator->fails()) {
    //     return response()->json($validator->errors(), 422);
    // }


        $user = User::where('email', $validator['email'])->first();
        if ($user && Hash::check($validator['password'], $user->password)) {

            $token = $user->createToken('authtoken');


       return response()->json(
           [
              
               'status' => 'success',   
               'data'  =>  $user,
               'token' => $token->plainTextToken

           ]);
        }
        else{
            
            return response()->json([

              'status' =>'failure',
                'data'  =>  'user not found'
          ]);
        }
   
    }

     public function logout(Request $request)
    {
       
        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
            
        );

    }

      
}
