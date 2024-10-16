<?php
namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HotelContractsBuyer;
use App\Models\HotelContractsEntry;

class PackageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $organizationId = $user->org_id;
        $contracts = HotelContractsBuyer::where('org_id', $organizationId)->get();
        $hotels = HotelContractsEntry::where('org_id', $organizationId)->get();
        $packages = Package::where('org_id', $organizationId)->get();

        $packages->transform(function ($package) {
            $package->makkah = json_decode($package->makkah, true);
            $package->shifting_makkah = json_decode($package->shifting_makkah, true);
            $package->madinah = json_decode($package->madinah, true);
            return $package;
        });

        return response()->json(
            [
                'status' => 'success',
                'package' => $packages,
                'hotels' => $hotels,
                'contracts' => $contracts,
            ],
            200,
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'makkah' => 'required|array',
            'shiftingMakkah' => 'required|array',
            'madinah' => 'required|array',
            'packageId' => 'required|integer',
            'packageNickname' => 'nullable|string',
            'packageNameArabic' => 'nullable|string',
            'packageNameEnglish' => 'nullable|string',
            'packageType' => 'nullable|string',
            'camp' => 'nullable|string',
            'country' => 'nullable|string',
            'descriptionArabic' => 'nullable|string',
            'descriptionEnglish' => 'nullable|string',
            'org_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $package = Package::create([
            'makkah' => json_encode($data['makkah']),
            'shifting_makkah' => json_encode($data['shiftingMakkah']),
            'madinah' => json_encode($data['madinah']),
            'package_id' => $data['packageId'],
            'org_id' => $data['org_id'],
            'package_nickname' => $data['packageNickname'],
            'package_name_arabic' => $data['packageNameArabic'],
            'package_name_english' => $data['packageNameEnglish'],
            'package_type' => $data['packageType'],
            'camp' => $data['camp'],
            'country' => $data['country'],
            'description_arabic' => $data['descriptionArabic'],
            'description_english' => $data['descriptionEnglish'],
        ]);

        $package->makkah = json_decode($package->makkah, true);
        $package->shifting_makkah = json_decode($package->shifting_makkah, true);
        $package->madinah = json_decode($package->madinah, true);

        return response()->json(['message' => 'Package created successfully!', 'package' => $package], 201);
    }

    public function update(Request $request, $id)
    {
        // Find the package by its ID and check if it belongs to the current user's organization
        $package = Package::find($id);

        if (!$package || $package->org_id != auth()->user()->org_id) {
            return response()->json(['error' => 'Package not found or unauthorized access'], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'makkah' => 'required|array',
            'shiftingMakkah' => 'required|array',
            'madinah' => 'required|array',
            'packageId' => 'required|integer',
            'packageNickname' => 'nullable|string',
            'packageNameArabic' => 'nullable|string',
            'packageNameEnglish' => 'nullable|string',
            'packageType' => 'nullable|string',
            'camp' => 'nullable|string',
            'country' => 'nullable|string',
            'descriptionArabic' => 'nullable|string',
            'descriptionEnglish' => 'nullable|string',
            'org_id' => 'required|integer|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Update the package fields
        $package->update([
            'makkah' => json_encode($data['makkah']),
            'shifting_makkah' => json_encode($data['shiftingMakkah']),
            'madinah' => json_encode($data['madinah']),
            'package_id' => $data['packageId'],
            'org_id' => $data['org_id'],

            'package_nickname' => $data['packageNickname'],
            'package_name_arabic' => $data['packageNameArabic'],
            'package_name_english' => $data['packageNameEnglish'],
            'package_type' => $data['packageType'],
            'camp' => $data['camp'],
            'country' => $data['country'],
            'description_arabic' => $data['descriptionArabic'],
            'description_english' => $data['descriptionEnglish'],
        ]);

        $package->makkah = json_decode($package->makkah, true);
        $package->shifting_makkah = json_decode($package->shifting_makkah, true);
        $package->madinah = json_decode($package->madinah, true);

        return response()->json(['message' => 'Package updated successfully!', 'package' => $package], 200);
    }
    public function show($id)
    {
            $package = Package::find($id);

            if (!$package || $package->org_id != auth()->user()->org_id) {
            return response()->json(['message' => 'Package not found or unauthorized access'], 404);
            }

            $package->makkah = json_decode($package->makkah, true);
            $package->shifting_makkah = json_decode($package->shifting_makkah, true);
            $package->madinah = json_decode($package->madinah, true);

            return response()->json(['message' => 'Package retrieved successfully', 'package' => $package], 200);
    }
    public function destroy($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        if ($package->org_id == auth()->user()->org_id) {
            $package->delete();
        } else {
            return response()->json(['error' => 'Package not found or unauthorized access'], 404);
        }
        return response()->json(['message' => 'Package deleted successfully'], 200);
    }
}
