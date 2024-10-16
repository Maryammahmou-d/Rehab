<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hotels;
use Illuminate\Http\Request;
use App\Models\MissedHotel;
use App\Http\Controllers\Controller;

class HotelsController extends Controller
{
    /**
     * index.
     */
    public function index()
    {
        return response()->json(['hotels' => Hotels::all(), 'missed_hotels' => MissedHotel::all()]);

    }



    /**
     * store new.
     */
    public function store(Request $request)
    {
        $hotel = new Hotels;
        $hotel->hotel_name = $request->hotel_name;
        $hotel->hotel_company = $request->hotel_company;
        $hotel->hotel_city = $request->hotel_city;
        $hotel->hotel_category = $request->hotel_category;
        $hotel->hotel_zone = $request->hotel_zone;
        $hotel->save();
        return response()->json(["Message" => "Hotel saved successfully"]);
    }

    /**
     * edit.
     */
    public function show($id)
    {
    $hotel = Hotels::find($id);
    if (!$hotel) {
        return response()->json(['message' => 'Hotel not found'], 404);
    }
    return response()->json($hotel);
    }



    /**
     * Update .
     */
    public function update(Request $request, $id)
    {
    $hotel = Hotels::find($id);
    if (!$hotel) {
        return response()->json(['message' => 'Hotel not found'], 404);
    }

    $validatedData = $request->validate([
        'hotel_name' => 'required|string',
        'hotel_company' => 'nullable|string',
        'hotel_city' => 'required|string|max:255',
        'hotel_category' => 'nullable|string|max:255',
        'hotel_zone' => 'nullable|string|max:255',
    ]);

    $hotel->update($validatedData);

    return response()->json(['message' => 'Hotel updated successfully']);
    }

    /**
     * Delete.
     */
    public function destroy($id)
    {
    $hotel = Hotels::find($id);
    if (!$hotel) {
        return response()->json(['message' => 'Hotel not found'], 404);
    }
    $hotel->delete();
    return response()->json(['message' => 'Hotel deleted successfully']);
    }
}
