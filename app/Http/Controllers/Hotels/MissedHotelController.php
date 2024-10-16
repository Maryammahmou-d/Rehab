<?php

namespace App\Http\Controllers\Hotels;
use App\Http\Controllers\Controller;
use App\Models\MissedHotel;
use Illuminate\Http\Request;

class MissedHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    // Validate incoming request data
    $validatedData = $request->validate([
        'hotel_name' => 'required|string',
        'hotel_location' => 'required|string',
        'hotel_company' => 'required|string',
        'hotel_city' => 'required|string|max:255',
        'hotel_category' => 'required|string|max:255',
        'hotel_zone' => 'required|string|max:255',
        'hotel_phone' => 'required|string|max:20',
    ]);

    // If validation fails, return error response
    if ($validatedData->fails()) {
        return response()->json($validatedData->errors(), 422);
    }

    // Create new MissedHotel instance
    $hotel = new MissedHotel;

    // Assign validated data to the hotel instance
    $hotel->hotel_name = $validatedData['hotel_name'];
    $hotel->hotel_location = $validatedData['hotel_location'];
    $hotel->hotel_company = $validatedData['hotel_company'];
    $hotel->hotel_city = $validatedData['hotel_city'];
    $hotel->hotel_category = $validatedData['hotel_category'];
    $hotel->hotel_zone = $validatedData['hotel_zone'];
    $hotel->hotel_phone = $validatedData['hotel_phone'];
    $hotel->org_id = auth()->user()->org_id;

    // Save the hotel record
    $hotel->save();

    // Return a JSON response indicating success
    return response()->json(["Message" => "Saved"]);
}



    /**
     * Display the specified resource.
     */
    public function show(MissedHotel $missedHotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MissedHotel $missedHotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MissedHotel $missedHotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MissedHotel $missedHotel)
    {
        //
    }
}
