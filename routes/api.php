<?php

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthTokenController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('/login', [AuthTokenController::class, 'login']);

Route::middleware(['tokenValidation'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/pharmacy', [PharmaciesController::class, 'getPharmacy'])->name('pharmacy.getPharmacy');
    Route::get('/medicines/search', [MedicineController::class, 'search']);
    Route::get('/medicines/searchID', [MedicineController::class, 'medicineByProductId']);
    Route::get('/medicines/{productId}', [MedicineController::class, 'medicineByProductId']);
    Route::get('/popular/brand', [PopularBrandController::class, 'getBrand'])->name('popular.get_brand');
    Route::get('/popular/category', [PopularCategoryController::class, 'getCategory'])->name('popular.getCategory');
});






// // Public routes - No token required
// Route::get('/medicines/search', [MedicineController::class, 'search']);
// Route::get('/medicines/searchID', [MedicineController::class, 'medicineByProductId']);
// Route::get('/popular/brand', [PopularBrandController::class, 'getBrand'])->name('popular.get_brand');
// Route::get('/popular/category', [PopularCategoryController::class, 'getCategory'])->name('popular.getCategory');

// // Protected routes - Token required
// Route::middleware(['tokenValidation'])->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::get('/pharmacy', [PharmaciesController::class, 'getPharmacy'])->name('pharmacy.getPharmacy');
//     Route::get('/medicines/{productId}', [MedicineController::class, 'medicineByProductId']);
// });

