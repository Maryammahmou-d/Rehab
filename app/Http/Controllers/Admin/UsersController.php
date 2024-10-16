<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\organizations;
use Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('organization:id,title')->get();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        $org = organizations::all();
        if ($user) {
            return response()->json(['user' => $user, 'org' => $org]);
        }

        return response()->json(['message' => 'User not found'], 404);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->fname = $request->input('fname', $user->fname);
            $user->lname = $request->input('lname', $user->lname);
            $user->email = $request->input('email', $user->email);
            $user->org_id = $request->input('org_id', $user->org_id);
            $user->status = $request->input('status', $user->status);
            $user->email_verified = $request->input('email_verified', $user->email_verified);
            $user->sms_verified = $request->input('sms_verified', $user->sms_verified);
            if ($request->has('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->phone = $request->input('phone', $user->phone);
            $user->profile_photo_path = $request->input('profile_photo_path', $user->profile_photo_path);
            $user->updated_at = now();
            $user->save();
            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        }

        return response()->json(['message' => 'User not found'], 404);
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
