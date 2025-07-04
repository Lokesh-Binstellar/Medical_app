<?php

use App\Events\MyEvent;
use App\Http\Controllers\AddMedicineController;
use App\Http\Controllers\AppRatingController;
use App\Http\Controllers\AddLabTestController;
use App\Http\Controllers\JanaushadhiController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\LabPackageAndTestDetailsController;
use App\Http\Controllers\LabSlotController;
use App\Http\Controllers\MyOrderController;
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
use App\Http\Controllers\CmsController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MedicineBannerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PopularLabTestController;
use App\Http\Controllers\LabtestController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RequestQuoteController;
use App\Http\Controllers\LabRequestQuoteController;
use App\Http\Controllers\ZipCodeViceDeliveryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthTokenController::class, 'login']);

//fetch apis
Route::middleware('check.api.key')->group(function () {
    Route::post('/getAllPharmacy', [PharmaciesController::class, 'getPharmacy'])->name('pharmacy.getPharmacy');
    Route::post('/popularpharmacydetails/{id}', [PharmaciesController::class, 'popularpharmacydetails'])->name('pharmacy.popularpharmacydetails');
    Route::get('/filterbyorgan', [LabPackageAndTestDetailsController::class, 'getpackageandtestbyorgan'])->name('filterbyorgan');
    Route::post('/getPacakgesAndTestByOrgan/{id}', [LabPackageAndTestDetailsController::class, 'getPacakgesAndTestByOrgan']);
    Route::post('/invoice/{id}', [MedicineSearchController::class, 'saveInvoice']);

    Route::get('/medicines/search', [MedicineController::class, 'search']);
    // Route::get('/medicines/searchById', [MedicineController::class, 'medicineByProductId']);
    Route::get('/medicines/{id}', [MedicineController::class, 'medicineByProductId']);
    Route::get('/getAllPopularBrand', [PopularBrandController::class, 'getBrand'])->name('popular.get_brand');
    Route::get('/getAllPopularCategory', [PopularCategoryController::class, 'getCategory'])->name('popular.getCategory');

    Route::get('/medicine-by-salt', [MedicineController::class, 'medicineBySaltComposition']);
    Route::get('/popular-lab-tests', [PopularLabTestController::class, 'getAll']);
    Route::post('/listlabtest/{test_id}', [PopularLabTestController::class, 'listLabTest']);

    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    // with jwt token
    Route::middleware('authTest')->group(function () {
        Route::post('/bookpackageorlabtest', [LabPackageAndTestDetailsController::class, 'bookpackageorlabtest']);
        Route::post('/customer/address', [CustomerAddressController::class, 'saveAddress']);
        Route::put('/customer/address/update', [CustomerAddressController::class, 'updateAddress']);
        Route::get('/customerAddressFetch', [CustomerAddressController::class, 'getAddress']);
        Route::delete('/deleteAddress', [CustomerAddressController::class, 'deleteAddress']);
        Route::put('/customerDetails', [AuthController::class, 'update']);
        Route::get('/getcustomerdetails', [AuthController::class, 'getCustomerDetails']);

        Route::post('/upload-file', [FileUploadController::class, 'upload']);

        //medicine Cart
        Route::post('/add-to-cart', [AddMedicineController::class, 'frontendAddToCart']);
        Route::post('/replace-cart', [AddMedicineController::class, 'replaceCart']);

        Route::get('/getUserCart', [AddMedicineController::class, 'getAddToCart'])->name('getUserCart.getAddToCart');
        Route::post('/requestAQuote', [RequestQuoteController::class, 'requestAQuote']);

        Route::delete('/medicineCart/remove-product/{id}', [AddMedicineController::class, 'removeCartProduct']);
        Route::post('/labRequestAQuote', [LabRequestQuoteController::class, 'searchlabs']);

        //lab Cart
        Route::get('/getUserLabCart', [AddLabTestController::class, 'getUserlabcart'])->name('userCart.getUserLabCart');
        Route::delete('/deleteTestFromLabCart/{id}', [AddLabTestController::class, 'deleteTestFromLabCart'])->name('userCart.deleteTestFromLabCart');

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
        Route::get('/zipcodes', [ZipCodeViceDeliveryController::class, 'getZipcodes']);
        // Route::post('/ratings', [RatingController::class, 'store']);
        Route::get('/allPharmacyRequests', [MedicineSearchController::class, 'allPharmacyRequests']);

        // Orders
        Route::post('/placeOrder', [OrderController::class, 'placeOrder']);

        // MyOrders
        Route::get('/pharmacyMyOrders', [MyOrderController::class, 'getUserPharmacyOrders']);
        Route::post('/cancelOrder/{id}', [MyOrderController::class, 'cancelOrder']);
        Route::post('/returnOrder/{id}', [MyOrderController::class, 'returnOrder']);

        // Route::post('/broadcasting/auth', function (Request $request) {
        //     return Broadcast::auth($request);
        // });
        Route::post('/broadcasting/auth', [PusherController::class, 'authenticate']);
        Route::get('/trigger-call', [PusherController::class, 'triggerCall']);






        Route::post('/book-slot', [LabSlotController::class, 'book']);
    });
    Route::get('/about-us', [CmsController::class, 'aboutUs']);
    Route::get('/contact-us', [CmsController::class, 'contactUs']);
    Route::get('/faqs', [CmsController::class, 'faqs']);
    Route::get('/terms', [CmsController::class, 'terms']);
    Route::get('/return-policy', [CmsController::class, 'returnPolicy']);
    Route::get('/privacy-policy', [CmsController::class, 'privacyPolicy']);

    //labTest
    Route::get('/LabTestDetails', [LabtestController::class, 'labTestDetails']);

    Route::get('/productListByCategory/{categoryName}', [OtcController::class, 'productListByCategory']);
    Route::get('/productListByBrand/{brandName}', [PopularBrandController::class, 'productListByBrand']);

    Route::get('/homebanners', [HomeBannerController::class, 'getAllBanners']);
    Route::get('/medicinebanners', [MedicineBannerController::class, 'getAllBanners']);

    Route::post('/join-us', [JoinUsController::class, 'store']);

    Route::get('/placeOrder', [MedicineSearchController::class, 'placeOrder']);
    Route::post('/brand/filter', [PopularBrandController::class, 'getFilters']);
    Route::post('/category/filter', [OtcController::class, 'getFilters']);

    //    Laboratory

    Route::post('/getAllLaboratory', [LaboratoriesController::class, 'getAllLaboratory']);
    Route::get('/getLaboratoryDetailsById/{laboratorie_id}', [LaboratoriesController::class, 'getLaboratoryDetailsById']);
    Route::get('/getTestDetailsById/{test_id}', [LaboratoriesController::class, 'getTestDetailsById']);
    Route::get('/getPackageDetailsByName/{packagename}', [LaboratoriesController::class, 'getPackageDetailsByName']);

    //general Address
    Route::post('/getUserSelectLocation', [CustomerAddressController::class, 'getUserSelectLocation']);
    Route::get('/place-autocomplete', [CustomerAddressController::class, 'placeAutocomplete']);
    Route::post('/get-city-state', [CustomerAddressController::class, 'getCityStateFromPostalCode']);


    //lab slots api
    Route::post('/getUpcomingLabSlots', [LabSlotController::class, 'getUpcomingSlots']);
    // Route::post('/calendar/slot-booking-details', [LabSlotController::class, 'slotBookingDetails']);
    Route::get('/calendar/slot-users', [LabSlotController::class, 'getSlotCustomers']);

    Route::prefix('janaushadhi')->group(function () {
        Route::get('getJanaushadhiAll', [JanaushadhiController::class, 'getJanaushadhiAll']);
        Route::post('getJanaushadhiByDrugCode/{id}', [JanaushadhiController::class, 'getJanaushadhiByDrugCode']);
    });

});
