<?php

namespace App\Http\Controllers\Hotels;
use App\Http\Controllers\Controller;

use App\Models\HotelContractsBuyer;
use App\Models\HotelContractsEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelContractsEntryController extends Controller
{

    public function index()
    {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $organizationId = $user->org_id;
        $buyers = HotelContractsEntry::where('org_id', $organizationId)->get();
        $contracts = HotelContractsBuyer::where('org_id', $organizationId)->get();
        return response()->json([
            'status' => 'success',
            'data' => $buyers,
            'contracts' => $contracts
        ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'hotel_id' => 'required|integer',
            'rooms_needed' => 'nullable|integer',
            'rooms_type' => 'nullable|string',
            'pilgrims' => 'nullable|integer',
            'group_name' => 'nullable|string',
            'nusuk_id' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'hotel_cost' => 'nullable|numeric',
            'room_cost' => 'nullable|numeric',
            'meal_cost' => 'nullable|numeric',
            'days_meals' => 'nullable|numeric',
            'food_cost' => 'nullable|numeric',
            'hotel_cost_1person' => 'nullable|numeric',
            'food_cost_1person' => 'nullable|numeric',
            'org_id' => 'required|integer|exists:organizations,id',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $validatedData = $validator->validated();
        $validatedData['org_id'] = auth()->user()->org_id;
        $hotelContractEntry = HotelContractsEntry::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel contract entry created successfully!',
            'data' => $hotelContractEntry
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hotelContractEntry = HotelContractsEntry::find($id);

        if (!$hotelContractEntry || $hotelContractEntry->org_id != auth()->user()->org_id) {
            return response()->json(['message' => 'Hotel contract entry not found or unauthorized access'], 404);
        }

        return response()->json(['message' => 'Hotel contract entry retrieved successfully', 'data' => $hotelContractEntry], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
    $hotelContractEntry = HotelContractsEntry::find($request->id);

    if (!$hotelContractEntry || $hotelContractEntry->org_id != auth()->user()->org_id) {
        return response()->json(['message' => 'Hotel contract entry not found or unauthorized access'], 404);
    }

    // Validate the request data
    $validatedData = $request->validate([
        'group_name' => 'nullable|string',
        'nusuk_id' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'hotel_cost' => 'nullable|numeric',
        'room_cost' => 'nullable|numeric',
        'meal_cost' => 'nullable|numeric',
        'days_meals' => 'nullable|numeric',
        'food_cost' => 'nullable|numeric',
        'hotel_cost_1person' => 'nullable|numeric',
        'food_cost_1person' => 'nullable|numeric',
        'org_id' => 'required|integer|exists:organizations,id',
        'status' => 'required|string',
    ]);

    // Update the hotel contract entry
    $validatedData['org_id'] = auth()->user()->org_id;
    $hotelContractEntry->update($validatedData);

    return response()->json([
        'status' => 'success',
        'message' => 'Hotel contract entry updated successfully!',
        'data' => $hotelContractEntry
    ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $hotelContractEntry = HotelContractsEntry::find($id);

    if (!$hotelContractEntry) {
        return response()->json(['message' => 'Hotel contract entry not found'], 404);
    }

    if ($hotelContractEntry->org_id != auth()->user()->org_id) {
        return response()->json(['error' => 'Unauthorized access'], 403);
    }

    $hotelContractEntry->delete();

    return response()->json(['message' => 'Hotel contract entry deleted successfully'], 200);
    }
}
