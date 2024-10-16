<?php

namespace App\Http\Controllers\Hotels;
use App\Http\Controllers\Controller;
use App\Models\HotelContractsBuyer;
use App\Models\HotelContractsEntry;

use App\Models\MissedHotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class HotelContractsBuyerController extends Controller
{
    /**
     * All data.
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
        } else {
            $contracts = HotelContractsBuyer::where('org_id', $organizationId)->get();
            $hotels = MissedHotel::where('org_id', $organizationId)->get();
            $contractsEntry = HotelContractsEntry::where('org_id', $organizationId)->get();
        }
        return response()->json([
            'message' => 'Contracts retrieved successfully',
            'data' => $contracts,
            'hotels' => $hotels,
            'entry' => $contractsEntry,
        ]);
    }

    /**
     * Store new contract.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'org_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Store the file in the public directory
        $path = $request->file('file')->move(public_path('files/contracts'), $request->file('file')->getClientOriginalName());
        $fullUrl = url('contracts/' . $request->file('file')->getClientOriginalName());

        // Create a new HotelContractsBuyer instance and save the data
        $contractBuyer = new HotelContractsBuyer();
        $contractBuyer->title = $request->title;
        $contractBuyer->file_path = $fullUrl;
        $contractBuyer->org_id = $user->org_id;

        $contractBuyer->save();

        // Return a response indicating success
        return response()->json([
            'message' => 'Contract Buyer created successfully!',
            'data' => $contractBuyer,
        ]);
    }

    /**
     * Split contract into a new contract
     */

    public function split(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_start_date' => 'required|date_format:Y-m-d',
            'new_end_date' => 'required|date_format:Y-m-d|after_or_equal:new_start_date',
            'org_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $originalContract = HotelContractsEntry::find($id)->where('org_id', $request->org_id);

        if ($originalContract === null) {
            return response()->json('Original Contract Not Found', 404);
        }

        $newStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validator->validated()['new_start_date']);
        $newEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $validator->validated()['new_end_date']);

        $originalStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $originalContract->start_date);
        $originalEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $originalContract->end_date);

        // Check if original dates are logically consistent
        if ($originalStartDate->gt($originalEndDate)) {
            return response()->json('Original contract dates are inconsistent (start date is after end date)', 422);
        }

        // Check if new dates are within the original contract dates
        if ($newStartDate->lt($originalStartDate) || $newEndDate->gt($originalEndDate)) {
            return response()->json('New dates must be within the range of the original contract dates', 422);
        }

        // Adjust the original contract dates
        if ($newStartDate->gt($originalStartDate)) {
            $originalContract->end_date = $newStartDate->subDay()->format('Y-m-d'); // Set the end date to the day before the new start date
        }
        if ($newEndDate->lt($originalEndDate)) {
            $originalContract->start_date = $newEndDate->addDay()->format('Y-m-d'); // Set the start date to the day after the new end date
        }
        $originalContract->save();

        // Create a new contract with the new dates
        $newContract = $originalContract->replicate();
        $newContract->start_date = $newStartDate->format('Y-m-d');
        $newContract->end_date = $newEndDate->format('Y-m-d');
        $newContract->save();

        return response()->json([
            'message' => 'New contract created successfully from the original contract!',
            'data' => $newContract,
        ]);
    }
    /**
     * Display hotel contract.
     */
    public function show($id)
    {
        // Attempt to find the hotel contract buyer by ID and check if it belongs to the current user's organization
        $contract = HotelContractsBuyer::where('id', $id)
            ->where('org_id', auth()->user()->org_id)
            ->first();

        // Check if the hotel contract buyer was found and belongs to the current user's organization
        if ($contract === null) {
            // Return a JSON response indicating that the hotel contract buyer was not found or unauthorized access
            return response()->json('Hotel Contract Buyer Not Found or Unauthorized Access', 404);
        }

        // Return a JSON response containing the found hotel contract buyer
        return response()->json($contract);
    }

    /**
     * Store missed hotel contract buyer.
     */

    public function missing(Request $request)
    {
        try {
            $user = auth()->user();
            $new = new MissedHotel();
            $new->hotel_name = $request->hotel_name;
            $new->hotel_location = $request->hotel_location;
            $new->hotel_company = $request->hotel_company;
            $new->hotel_city = $request->hotel_city;
            $new->hotel_category = $request->hotel_category;
            $new->hotel_zone = $request->hotel_zone;
            $new->hotel_phone = $request->hotel_phone;
            $new->org_id = $user->org_id;
            $new->save();
            return response()->json('Hotel Added Successfully');
        } catch (\Throwable $th) {
            return response()->json('Hotel Not Added : ' . $th->getMessage());
        }
    }

    /**
     * Update hotel contract .
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'file' => 'sometimes|required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the existing HotelContractsBuyer
        $contractBuyer = HotelContractsBuyer::find($id);
        if (!$contractBuyer) {
            return response()->json(['message' => 'Contract Buyer not found'], 404);
        }

        // Check if the contract buyer belongs to the current user's organization
        if ($contractBuyer->org_id != auth()->user()->org_id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Update the title if provided
        if ($request->has('title')) {
            $contractBuyer->title = $request->title;
        }

        // Update the file and store in the storage if provided
        if ($request->hasFile('file')) {
            $path = $request->file('file')->move(public_path('contracts'), $request->file('file')->getClientOriginalName());
            $fullUrl = url('contracts/' . $request->file('file')->getClientOriginalName());
            $contractBuyer->file_path = $fullUrl;
        }
        // Save the updated contract buyer
        $contractBuyer->save();

        // Return a response indicating success
        return response()->json([
            'message' => 'Contract Buyer updated successfully!',
            'data' => $contractBuyer,
        ]);
    }

    /**
     * Delete Contract.

     */
    public function destroy($id)
    {
        $hotelContractBuyer = HotelContractsBuyer::find($id);
        if (!$hotelContractBuyer) {
            return response()->json(['message' => 'Hotel Contract Buyer Not Found'], 404);
        }

        if ($hotelContractBuyer->org_id != auth()->user()->org_id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        $hotelContractBuyer->delete();
        return response()->json(['message' => 'Hotel Contract Buyer Deleted Successfully'], 200);
    }
}
