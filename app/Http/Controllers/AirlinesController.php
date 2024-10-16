<?php

namespace App\Http\Controllers;

use App\Models\Airlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirlinesController extends Controller
{
    /**
     * index
     */
    public function index()
    {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $organizationId = $user->org_id;
    if (!$organizationId) {
        return response()->json(['message' => 'No organization ID provided'], 400);
    }else{
    $airlines = Airlines::where('org_id', $organizationId)->get();
}
    return response()->json([
        'message' => 'Airlines retrieved successfully',
        'data' => $airlines,
    ]);
    }


    /**
     * Store
     */
    public function store(Request $request)
    {
    $airlines = new Airlines;
    $validator = Validator::make($request->all(), [
        'pnr_no' => 'required|string|max:255',
        'no_of_tickets' => 'required|integer',
        'outbound_departure_date' => 'nullable|date',
        'outbound_arrival_date' => 'nullable|date',
        'outbound_departure' => 'nullable|string',
        'outbound_arrival' => 'nullable|string',
        'return_departure' => 'nullable|string',
        'return_arrival' => 'nullable|string',
        'airline_provider' => 'required|string|max:255',
        'pnr_total_ticket_price' => 'required|numeric',
        'one_person_price_for_ticket' => 'required|numeric',
        'org_id' => 'required|integer|exists:organizations,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $validatedData = $validator->validated();

    $airlines->pnr_no = $validatedData['pnr_no'];
    $airlines->no_of_tickets = $validatedData['no_of_tickets'];
    $airlines->outbound_departure_date = $validatedData['outbound_departure_date'];
    $airlines->outbound_arrival_date = $validatedData['outbound_arrival_date'];
    $airlines->outbound_departure = $validatedData['outbound_departure'];
    $airlines->outbound_arrival = $validatedData['outbound_arrival'];
    $airlines->return_departure = $validatedData['return_departure'];
    $airlines->return_arrival = $validatedData['return_arrival'];
    $airlines->airline_provider = $validatedData['airline_provider'];
    $airlines->pnr_total_ticket_price = $validatedData['pnr_total_ticket_price'];
    $airlines->one_person_price_for_ticket = $validatedData['one_person_price_for_ticket'];
    $airlines->org_id = $validatedData['org_id'];

    $airlines->save();

    return response()->json([
        'message' => 'Airline booking created successfully!',
        'data' => $airlines,
    ]);
    }



    /**
     * Show
     */
    public function edit($id)
    {
    $airline = Airlines::find($id);
    if (!$airline) {
        return response()->json(['message' => 'Airline not found'], 404);
    }

    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    if ($airline->org_id !== $user->org_id) {
        return response()->json(['message' => 'Unauthorized access to airline'], 403);
    }

    return response()->json([
        'message' => 'Airline found',
        'data' => $airline,
    ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
    $airline = Airlines::find($id);
    if (!$airline) {
        return response()->json(['message' => 'Airline not found'], 404);
    }

    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    if ($airline->org_id !== $user->org_id) {
        return response()->json(['message' => 'Unauthorized access to airline'], 403);
    }

    $validatedData = $request->validate([
        'outbound_departure' => 'required|string',
        'outbound_arrival' => 'required|string',
        'outbound_arrival_date' => 'required|date',
        'return_departure' => 'required|string',
        'return_arrival' => 'required|string',
        'airline_provider' => 'required|string',
        'pnr_total_ticket_price' => 'required|numeric',
        'one_person_price_for_ticket' => 'required|numeric',
        'pnr_no' => 'required|string',
        'no_of_tickets' => 'required|integer',
        'outbound_departure_date' => 'required|date',
    ]);

    $airline->update($validatedData);

    return response()->json([
        'message' => 'Airline updated successfully!',
        'data' => $airline,
    ]);
    }

    /**
     * Delete
     */
    public function destroy($id)
    {

    $airline = Airlines::find($id);
    if (!$airline) {
        return response()->json(['message' => 'Airline not found'], 404);
    }

    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    if ($airline->org_id !== $user->org_id) {
        return response()->json(['message' => 'Unauthorized access to airline'], 403);
    }

    $airline->delete();

    return response()->json([
        'message' => 'Airline deleted successfully!',
    ]);
}
}
