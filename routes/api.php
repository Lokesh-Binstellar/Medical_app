<?php

use App\Http\Controllers\AddMedicineController;
use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PopularLabTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthTokenController::class, 'login']);

//fetch apis
Route::middleware('check.api.key')->group(function () {
    Route::get('/getAllPharmacy', [PharmaciesController::class, 'getPharmacy'])->name('pharmacy.getPharmacy');
    Route::get('/medicines/search', [MedicineController::class, 'search']);
    // Route::get('/medicines/searchById', [MedicineController::class, 'medicineByProductId']);
    Route::get('/medicines/{id}', [MedicineController::class, 'medicineByProductId']);
    Route::get('/getAllPopularBrand', [PopularBrandController::class, 'getBrand'])->name('popular.get_brand');
    Route::get('/getAllPopularCategory', [PopularCategoryController::class, 'getCategory'])->name('popular.getCategory');

    
    Route::get('/popular-lab-tests', [PopularLabTestController::class, 'getAll']);
    
    
    Route::delete('/cart/{cartId}/product/{productId}', [AddMedicineController::class, 'removeProduct']);
    
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    
    // with jwt token
    Route::middleware('authTest')->group(function () {
        Route::post('/customer/address', [CustomerAddressController::class, 'store']);
        Route::put('/customerDetails', [AuthController::class, 'update']);
        Route::post('/upload-file', [FileUploadController::class, 'upload']);
        Route::post('/add-to-cart', [AddMedicineController::class, 'frontendAddToCart']);
        Route::get('/getUserCart', [AddMedicineController::class, 'getAddToCart'])->name('getUserCart.getAddToCart');
    });
    


});









