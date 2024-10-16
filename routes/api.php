<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\Hotels\HotelContractsBuyerController;
use App\Http\Controllers\Hotels\MissedHotelController;
use App\Http\Controllers\Hotels\HotelContractsEntryController;
use App\Models\HotelContractsEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\RolesPermissonsController;
use App\Http\Controllers\AirlinesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CompaniesController;
use App\Http\Controllers\Admin\HotelsController;
use App\Http\Controllers\PackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::post('/auth/register', [AuthApiController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(['checkIfAdmin'])
        ->prefix('admin')
        ->group(function () {
            Route::get('/roles', [RolesPermissonsController::class, 'index']);
            Route::get('/role/{id}/edit', [RolesPermissonsController::class, 'edit']);
            Route::get('/role/{id}/delete', [RolesPermissonsController::class, 'destroy']);
            Route::post('/role/create', [RolesPermissonsController::class, 'store_role']);
            Route::post('/assign/role', [RolesPermissonsController::class, 'assign_role']);
            Route::post('/role/{id}/update', [RolesPermissonsController::class, 'update']);

            // permssions
            Route::get('/permission/{id}/edit', [RolesPermissonsController::class, 'edit_permission']);
            Route::get('/permission/{id}/delete', [RolesPermissonsController::class, 'destroy_permission']);
            Route::post('/permission/create', [RolesPermissonsController::class, 'store_permission']);
            Route::post('/assign/permission', [RolesPermissonsController::class, 'assign_permission']);
            Route::post('/permission/{id}/update', [RolesPermissonsController::class, 'update_permission']);

            // Hotels
            Route::get('/hotels', [HotelsController::class, 'index']);
            Route::get('/hotel/{id}/view', [HotelsController::class, 'show']);
            Route::post('/hotel/store', [HotelsController::class, 'store']);
            Route::post('/hotel/{id}/update', [HotelsController::class, 'update']);
            Route::get('/hotel/{id}/delete', [HotelsController::class, 'destroy']);

            // Users
            Route::get('/users', [UsersController::class, 'index']);
            Route::get('/user/{id}/view', [UsersController::class, 'show']);
            Route::post('/user/{id}/update', [UsersController::class, 'update']);
            Route::get('/user/{id}/delete', [UsersController::class, 'destroy']);
            Route::post('/user/store', [UsersController::class, 'store']);

            // companies
            Route::get('/companies', [CompaniesController::class, 'index']);
            Route::get('/company/{id}/view', [CompaniesController::class, 'show']);
            Route::post('/company/store', [CompaniesController::class, 'store']);
            Route::post('/company/{id}/update', [CompaniesController::class, 'update']);
            Route::get('/company/{id}/delete', [CompaniesController::class, 'destroy']);



        });

    Route::post('/organization/login', [OrganizationsController::class, 'login']);
    Route::middleware(['checkIfOrganizationAdmin'])->prefix('organization')->group(function () {
    Route::get('/index', [OrganizationsController::class, 'index']);
    Route::get('/team/{id}/all', [OrganizationsController::class, 'team']);
    });

    Route::middleware(['checkIfHotelBuyer'])->group(function () {
        Route::get('/hotel-contracts-buyers', [HotelContractsBuyerController::class, 'index']);
        Route::post('/hotel-contracts-buyers/store', [HotelContractsBuyerController::class, 'store']);
        Route::get('/hotel-contracts-buyers/{id}/get', [HotelContractsBuyerController::class, 'show']);
        Route::post('/hotel-contracts-buyers/{id}/update', [HotelContractsBuyerController::class, 'update']);
        Route::get('/hotel-contracts-buyers/{id}/delete', [HotelContractsBuyerController::class, 'destroy']);
        Route::post('/hotel-contracts-buyers/{id}/split', [HotelContractsBuyerController::class, 'split']);
        Route::post('/missed-hotel/store', [MissedHotelController::class, 'store']);

    });
    Route::middleware(['checkIfHotelEntry'])->group(function () {
        Route::get('/hotel-contracts-entry', [HotelContractsEntryController::class, 'index']);
        Route::get('/hotel-contracts-entry/{id}/view', [HotelContractsEntryController::class, 'show']);
        Route::get('/hotel-contracts-entry/{id}/delete', [HotelContractsEntryController::class, 'destroy']);
        Route::post('/hotel-contracts-entry/{id}/update', [HotelContractsEntryController::class, 'update']);
        Route::post('/hotel-contracts-entry/store', [HotelContractsEntryController::class, 'store']);
    });


    Route::middleware(['checkIfAirline'])->group(function () {

    Route::get('/airlines-booking', [AirlinesController::class, 'index']);
    Route::post('/airlines-booking/store', [AirlinesController::class, 'store']);
    Route::get('/airlines-booking/{id}/view', [AirlinesController::class, 'edit']);
    Route::post('/airlines-booking/{id}/update', [AirlinesController::class, 'update']);
    Route::get('/airlines-booking/{id}/delete', [AirlinesController::class, 'destroy']);
    });

    // Route::middleware(['checkIfAdmin'])->prefix('admin')->group(function () {


    // });
    Route::middleware(['checkIfPackageMaker'])->group(function () {
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}/view', [PackageController::class, 'show']);
    Route::post('/packages/store', [PackageController::class, 'store']);
    Route::post('/packages/{id}/update', [PackageController::class, 'update']);
    Route::get('/packages/{id}/delete', [PackageController::class, 'destroy']);
});

});
