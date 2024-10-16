<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\organizations;

class CompaniesController extends Controller
{
    /**
     * index.
     */
    public function index()
    {
        $org = organizations::all();
        return response()->json($org);
    }


    /**
     * Store .
     */
    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'website' => 'nullable|url',
        'address' => 'nullable|string',
    ]);

    $company = organizations::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'website' => $validatedData['website'],
        'address' => $validatedData['address'],
    ]);

    return response()->json($company, 201);
    }

    /**
     * Display.
     */
    public function show(string $id)
    {
        $company = organizations::find($id);
        if ($company) {
            return response()->json($company);
        }

        return response()->json(['message' => 'Company not found'], 404);
    }



    /**
     * Update.
     */
    public function update(Request $request,$id)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'website' => 'nullable|url',
        'address' => 'nullable|string',
    ]);

    $company = organizations::find($id);
    if (!$company) {
        return response()->json(['message' => 'Company not found'], 404);
    }

    $company->update([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'website' => $validatedData['website'],
        'address' => $validatedData['address'],
    ]);

    return response()->json($company);
    }

    /**
     * Delete.
     */
    public function destroy( $id)
    {
    $company = organizations::find($id);
    if (!$company) {
        return response()->json(['message' => 'Company not found'], 404);
    }

    $company->delete();

    return response()->json(['message' => 'Company deleted successfully'], 200);
    }
}
