<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthApiController extends Controller

{

   public function register(Request $request)
{
    // dd($request->all());
    $request->validate([
        'fname' => 'required|string',
        'lname' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'image' => 'required',
        'password' => 'required|string',
        'phone' => 'required|numeric', // Corrected 'number' to 'numeric'
        'role' => 'required',
        'org_id' => 'required|integer|exists:organizations,id',

    ]);

    $imageName = time().'.'.$request->image->extension();
    $request->image->move(public_path('/images/profiles'), $imageName);

    $user = new User;
    $user->fname = $request->fname;
    $user->lname = $request->lname;
    $user->email = $request->email;
    $user->profile_photo_path = $imageName; // Assigning the image name instead of the image itself
    $user->phone = $request->phone; // Corrected from $request->image
    $user->org_id = $request->org_id;
    $user->password = bcrypt($request->password);

    if ($user->save()) {
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    } else {
        return response()->json(['error' => 'Provide proper details']);
    }
}




    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 0,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = $request->user();

        // Retrieve user roles
        $roles = $user->getRoleNames();
        // Retrieve user permissions
        $permissions = $user->getAllPermissions()->pluck('name');

        // Continue with generating and returning the token
        $tokenResult = $user->createToken('Personal Access Token')  ;
        // return $tokenResult;
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'status' => 1,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id,
            'org_id' => $user->org_id,
            'roles' => $roles,
            'permissions' => $permissions,

        ]);
    }


}
