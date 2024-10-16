<?php

namespace App\Http\Controllers;

use App\Models\organizations;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrganizationsController extends Controller
{

    /**
     *
     * Auth for Org
     *
     */


     public function login(Request $request)
     {
         $credentials = $request->only('email', 'password');

         if (Auth::guard('org')->attempt($credentials)) {
             // Authentication was successful
             return response()->json(["Done"]);
         }

         // Authentication failed
         return redirect()->back()->withErrors([
             'email' => 'The provided credentials do not match our records.',
         ]);
     }


    /**
     * Register a new organization.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:organizations,domain',
            'email' => 'required|string|email|max:255|unique:organizations,email',
            'phone' => 'required|string|max:20',
            'logo' => 'nullable|file|image|max:1024',
            'status' => 'required|string|max:10',
        ]);

        $organization = organizations::create([
            'title' => $validatedData['title'],
            'domain' => $validatedData['domain'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'logo' => $validatedData['logo'] ? $validatedData['logo']->store('organizations', 'public') : null,
            'status' => $validatedData['status'],
        ]);

        return response()->json($organization, 201);
    }
    /**
     * Display all users belong to this ORG.
     */
    public function team($id)
    {
        $orgs = organizations::find($id);
        if ($orgs) {
            $users = User::where('org_id' , $id)->get();
            return response()->json($users);
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
