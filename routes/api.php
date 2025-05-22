<?php

use App\Events\MyEvent;
use App\Http\Controllers\AddMedicineController;
use App\Http\Controllers\AppRatingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineSearchController;
use App\Http\Controllers\OtcController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MedicineBannerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PopularLabTestController;
use App\Http\Controllers\LabtestController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RequestQuoteController;
use App\Http\Controllers\ZipCodeViceDeliveryController;
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

    Route::get('/medicine-by-salt', [MedicineController::class, 'medicineBySaltComposition']);
    Route::get('/popular-lab-tests', [PopularLabTestController::class, 'getAll']);

    
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    // with jwt token
    Route::middleware('authTest')->group(function () {
        Route::post('/customer/address', [CustomerAddressController::class, 'store']);
        Route::get('/customerAddressFetch', [CustomerAddressController::class, 'getAddress']);
        Route::put('/customerDetails', [AuthController::class, 'update']);
        Route::post('/upload-file', [FileUploadController::class, 'upload']);
        Route::post('/add-to-cart', [AddMedicineController::class, 'frontendAddToCart']);
        Route::get('/getUserCart', [AddMedicineController::class, 'getAddToCart'])->name('getUserCart.getAddToCart');
        Route::post('/requestAQuote', [RequestQuoteController::class, 'requestAQuote']);
        Route::delete('/medicineCart/remove-product/{id}', [AddMedicineController::class, 'removeCartProduct']);
        
        // rating
        Route::post('/ratings', [RatingController::class, 'store']);
        Route::post('/rate-app', [AppRatingController::class, 'store']);
        Route::get('/popular-pharmacies', [RatingController::class, 'popularPharmacies']);
        Route::get('/popular-labs', [RatingController::class, 'popularLaboratories']);

        // Patient
        Route::get('/patients', [PatientController::class, 'index']);
        Route::post('/patients', [PatientController::class, 'store']);
        Route::put('/patients/{id}', [PatientController::class, 'update']);

        // zip_code_vise_delivery
        Route::post('/ratings', [RatingController::class, 'store']);
        Route::post('/placeOrder', [OrderController::class, 'placeOrder']);
        Route::get('/allPharmacyRequests', [MedicineSearchController::class, 'allPharmacyRequests']); 
    });


    //labTest
    Route::get('/LabTestDetails', [LabtestController::class, 'labTestDetails']);


    
    Route::get('/productListByCategory/{categoryName}', [OtcController::class, 'productListByCategory']);
    Route::get('/productListByBrand/{brandName}', [PopularBrandController::class, 'productListByBrand']);

    Route::get('/homebanners', [HomeBannerController::class, 'getAllBanners']);
    Route::get('/medicinebanners', [MedicineBannerController::class, 'getAllBanners']);

    Route::post('/join-us', [JoinUsController::class, 'store']);

    Route::get('/placeOrder', [MedicineSearchController::class, 'placeOrder']);
});
