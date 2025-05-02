<?php

use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthTokenController::class, 'login']);

//fetch apis
Route::middleware('check.api.key')->group(function () {
    Route::get('/pharmacy', [PharmaciesController::class, 'getPharmacy'])->name('pharmacy.getPharmacy');
    Route::get('/medicines/search', [MedicineController::class, 'search']);
    Route::get('/medicines/searchID', [MedicineController::class, 'medicineByProductId']);
    Route::get('/medicines/{productId}', [MedicineController::class, 'medicineByProductId']);
    Route::get('/popular/brand', [PopularBrandController::class, 'getBrand'])->name('popular.get_brand');
    Route::get('/popular/category', [PopularCategoryController::class, 'getCategory'])->name('popular.getCategory');
   


});

// Customers apis
Route::middleware('check.api.key')->group(function () {
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::put('/customer/{id}', [AuthController::class, 'update']);

// routes/api.php



});

Route::middleware('authTest')->group(function () {
    Route::post('/customer/address', [CustomerAddressController::class, 'store']);
});

