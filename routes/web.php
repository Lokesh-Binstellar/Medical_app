<?php

use App\Events\MyEvent;
use App\Http\Controllers\CustomerDetailsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\LabSlotController;
use App\Http\Controllers\LabtestController;
use App\Http\Controllers\AddLabTestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineSearchController;
use App\Http\Controllers\AddMedicineController;
use App\Http\Controllers\AdditionalchargesController;
use App\Http\Controllers\AppRatingController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\CommissionDataController;
use App\Http\Controllers\DeliveryPersonController;
use App\Http\Controllers\OtcController;
use App\Http\Controllers\packageCategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\FilteredOrderController;
use App\Http\Controllers\FreeDeliveryChargeController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MedicineBannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhlebotomistController;
use App\Http\Controllers\PopularLabTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\RequestQuoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadQRControlle;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZipCodeViceDeliveryController;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Broadcast;
use App\Models\HomeBanner;
use App\Models\MedicineBanner;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified', 'permission:pharmacies,create']);

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckSession::class])->group(function () {
    Route::middleware(['auth', 'verified', 'checkSession.auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/get-dashboard-graph-data', [DashboardController::class, 'getAllGraphData'])->name('dashboard.graph.data');
        Route::get('/orders-data', [DashboardController::class, 'getOrdersData'])->name('dashboard.orders.data');
        Route::get('/pending-quotes-data', [DashboardController::class, 'pendingQuotesData'])->name('pending.quotes');
        Route::get('/fetch-ratings', [DashboardController::class, 'fetchRatings']);
        Route::get('/top-pharmacies', [DashboardController::class, 'getTopPharmaciesData']);
    });
    Route::get('/free-delivery-charge', [FreeDeliveryChargeController::class, 'freedeliveryindex'])->name('free_delivery_charge');
    Route::post('/free-delivery-charge', [FreeDeliveryChargeController::class, 'storeOrUpdate'])->name('delivery_charges.store');
    //calendar

    Route::get('/calendar', [LabSlotController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/save-slot', [LabSlotController::class, 'store'])->name('calendar.store');
    Route::get('/calendar/slots-by-date', [LabSlotController::class, 'getSlotsByDate'])->name('calendar.slotsByDate');
    Route::get('/calendar/fetch', [LabSlotController::class, 'fetch'])->name('calendar.fetch');
    Route::post('/calendar/disable', [LabSlotController::class, 'disable'])->name('calendar.disable');
    Route::get('/lab-slots/bookings-by-date', [LabSlotController::class, 'viewBookingsByDate'])->name('lab-slots.bookings.by.date');


    // web.php


    Auth::routes();
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.custom');

        Route::prefix('roles')->group(function () {
            Route::get('', [RoleController::class, 'index'])->name('roles.index');
            Route::group(['middleware' => 'permission:roles,create'], function () {
                Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
                Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
            });
            Route::group(['middleware' => 'permission:roles,update'], function () {
                Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
                Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
            });
            Route::group(['middleware' => 'permission:roles,delete'], function () {
                Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            });
            Route::group(['middleware' => 'permission:roles,read'], function () {
                Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
            });
        });

        // About Us Routes
        Route::prefix('cms/about-us')
            ->name('cms.about-us.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'aboutIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'aboutCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'aboutStore'])->name('store');
                Route::get('/{aboutUs}', [CmsController::class, 'aboutShow'])->name('show');
                Route::get('/{aboutUs}/edit', [CmsController::class, 'aboutEdit'])->name('edit');
                Route::put('/{aboutUs}', [CmsController::class, 'aboutUpdate'])->name('update');
                Route::delete('/{aboutUs}', [CmsController::class, 'aboutDestroy'])->name('destroy');
            });

        // Contact Us Routes
        Route::prefix('cms/contact-us')
            ->name('cms.contact-us.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'contactIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'contactCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'contactStore'])->name('store');
                Route::get('/{contactUs}', [CmsController::class, 'contactShow'])->name('show');
                Route::get('/{contactUs}/edit', [CmsController::class, 'contactEdit'])->name('edit');
                Route::put('/{contactUs}', [CmsController::class, 'contactUpdate'])->name('update');
                Route::delete('/{contactUs}', [CmsController::class, 'contactDestroy'])->name('destroy');
            });

        // FAQs Routes
        Route::prefix('cms/faqs')
            ->name('cms.faqs.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'faqsIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'faqsCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'faqsStore'])->name('store');
                Route::get('/{faq}', [CmsController::class, 'faqsShow'])->name('show');
                Route::get('/{faq}/edit', [CmsController::class, 'faqsEdit'])->name('edit');
                Route::put('/{faq}', [CmsController::class, 'faqsUpdate'])->name('update');
                Route::delete('/{faq}', [CmsController::class, 'faqsDestroy'])->name('destroy');
            });

        // Return Policies Routes
        Route::prefix('cms/return-policies')
            ->name('cms.return-policies.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'returnPoliciesIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'returnPoliciesCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'returnPoliciesStore'])->name('store');
                Route::get('/{returnPolicy}', [CmsController::class, 'returnPoliciesShow'])->name('show');
                Route::get('/{returnPolicy}/edit', [CmsController::class, 'returnPoliciesEdit'])->name('edit');
                Route::put('/{returnPolicy}', [CmsController::class, 'returnPoliciesUpdate'])->name('update');
                Route::delete('/{returnPolicy}', [CmsController::class, 'returnPoliciesDestroy'])->name('destroy');
            });

        // Privacy Policies Routes
        Route::prefix('cms/privacy-policies')
            ->name('cms.privacy-policies.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'privacyPoliciesIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'privacyPoliciesCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'privacyPoliciesStore'])->name('store');
                Route::get('/{privacyPolicy}', [CmsController::class, 'privacyPoliciesShow'])->name('show');
                Route::get('/{privacyPolicy}/edit', [CmsController::class, 'privacyPoliciesEdit'])->name('edit');
                Route::put('/{privacyPolicy}', [CmsController::class, 'privacyPoliciesUpdate'])->name('update');
                Route::delete('/{privacyPolicy}', [CmsController::class, 'privacyPoliciesDestroy'])->name('destroy');
            });

        // Terms & Conditions Routes
        Route::prefix('cms/terms')
            ->name('cms.terms-and-conditions.')
            ->group(function () {
                Route::get('/', [CmsController::class, 'termsIndex'])->name('index');
                Route::get('/create', [CmsController::class, 'termsCreate'])->name('create');
                Route::post('/store', [CmsController::class, 'termsStore'])->name('store');
                Route::get('/{termsAndCondition}', [CmsController::class, 'termsShow'])->name('show');
                Route::get('/{termsAndCondition}/edit', [CmsController::class, 'termsEdit'])->name('edit');
                Route::put('/{termsAndCondition}', [CmsController::class, 'termsUpdate'])->name('update');
                Route::delete('/{termsAndCondition}', [CmsController::class, 'termsDestroy'])->name('destroy');
            });

        Auth::routes();
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.custom');



            //User
            Route::prefix('users')->group(function () {
                Route::get('', [UserController::class, 'index'])->name('user.index');
                Route::group(['middleware' => 'permission:Users,create'], function () {
                    Route::get('/create', [UserController::class, 'create'])->name('user.create');
                    Route::post('/store', [UserController::class, 'store'])->name('user.store');
                });
                Route::group(['middleware' => 'permission:Users,update'], function () {
                    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
                    Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
                });
                Route::group(['middleware' => 'permission:Users,delete'], function () {
                    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
                });
            });
        });

        //Pharmacist
        Route::prefix('pharmacy')->group(function () {
            Route::get('/', [PharmaciesController::class, 'index'])->name('pharmacist.index');
            Route::group(['middleware' => 'permission:Pharmacies,create'], function () {
                Route::get('/create', [PharmaciesController::class, 'create'])->name('pharmacist.create');
                Route::post('/store', [PharmaciesController::class, 'store'])->name('pharmacist.store');
            });
            Route::group(['middleware' => 'permission:Pharmacies,update'], function () {
                Route::get('{id}/edit', [PharmaciesController::class, 'edit'])->name('pharmacist.edit');
                Route::put('/{id}', [PharmaciesController::class, 'update'])->name('pharmacist.update');
            });
            Route::group(['middleware' => 'permission:Pharmacies,delete'], function () {
                Route::delete('/{id}', [PharmaciesController::class, 'destroy'])->name('pharmacist.destroy');
            });
            Route::group(['middleware' => 'permission:Pharmacies,read'], function () {
                Route::get('/{id}', [PharmaciesController::class, 'show'])->name('pharmacist.show');
            });
        });

        //laboratories
        Route::prefix('laboratory')->group(function () {
            Route::get('', [LaboratoriesController::class, 'index'])->name('laboratorie.index');
            Route::group(['middleware' => 'permission:Laboratories,create'], function () {
                Route::get('/create', [LaboratoriesController::class, 'create'])->name('laboratorie.create');
                Route::post('/store', [LaboratoriesController::class, 'store'])->name('laboratorie.store');
            });
            Route::group(['middleware' => 'permission:Laboratories,update'], function () {
                Route::get('/{id}/edit', [LaboratoriesController::class, 'edit'])->name('laboratorie.edit');
                Route::put('/{id}', [LaboratoriesController::class, 'update'])->name('laboratorie.update');
            });
            Route::group(['middleware' => 'permission:Laboratories,delete'], function () {
                Route::delete('/{id}', [LaboratoriesController::class, 'destroy'])->name('laboratorie.destroy');
            });
            Route::group(['middleware' => 'permission:Laboratories,read'], function () {
                Route::get('/{id}', [LaboratoriesController::class, 'show'])->name('laboratorie.show');
            });
        });

        //DeliveryPerson
        Route::prefix('DeliveryPerson')->group(function () {
            Route::get('/', [DeliveryPersonController::class, 'index'])->name('delivery_person.index');
            Route::get('/create', [DeliveryPersonController::class, 'create'])->name('delivery_person.create');
            Route::post('/store', [DeliveryPersonController::class, 'store'])->name('delivery_person.store');
            Route::get('{id}/edit', [DeliveryPersonController::class, 'edit'])->name('delivery_person.edit');
            Route::put('/{id}', [DeliveryPersonController::class, 'update'])->name('delivery_person.update');
            Route::delete('/{id}', [DeliveryPersonController::class, 'destroy'])->name('delivery_person.destroy');
            Route::get('/{id}', [DeliveryPersonController::class, 'show'])->name('delivery_person.show');
        });

        //Medicine
        Route::prefix('medicine')->group(function () {
            Route::get('', [MedicineController::class, 'index'])->name('medicine.index');
            Route::post('import', [MedicineController::class, 'import'])->name('medicine.import');
            Route::group(['middleware' => 'permission:PopularBrand,read'], function () {
                Route::get('/{id}', [MedicineController::class, 'show'])->name('medicine.show');
            });
        });

        //PopularCategory
        Route::prefix('popularCategory')->group(function () {
            Route::get('/', [PopularCategoryController::class, 'index'])->name('popular_category.index');
            Route::group(['middleware' => 'permission:PopularCategory,create'], function () {
                Route::post('/store', [PopularCategoryController::class, 'store'])->name('popular_category.store');
            });
            Route::group(['middleware' => 'permission:PopularCategory,update'], function () {
                Route::get('{id}/edit', [PopularCategoryController::class, 'edit'])->name('popular_category.edit');
                Route::put('{id}', [PopularCategoryController::class, 'update'])->name('popular_category.update');
            });
            Route::group(['middleware' => 'permission:PopularCategory,delete'], function () {
                Route::delete('{id}', [PopularCategoryController::class, 'destroy'])->name('popular_category.destroy');
            });
        });

        //Otc Medicine
        Route::prefix('otcmedicine')->group(function () {
            Route::get('', [OtcController::class, 'index'])->name('otcmedicine.index');
            Route::post('import', [OtcController::class, 'import'])->name('otcmedicine.import');
            Route::get('/{id}', [OtcController::class, 'show'])->name('otcmedicine.show');
        });

        //Popular Brand
        Route::prefix('popularBrand')->group(function () {
            Route::get('/', [PopularBrandController::class, 'index'])->name('popular.index');
            Route::group(['middleware' => 'permission:PopularBrand,create'], function () {
                Route::post('/store', [PopularBrandController::class, 'store'])->name('popular.store');
            });
            Route::group(['middleware' => 'permission:PopularBrand,update'], function () {
                Route::get('/{id}/edit', [PopularBrandController::class, 'edit'])->name('popular.edit');
                Route::put('/{id}', [PopularBrandController::class, 'update'])->name('popular.update');
            });
            Route::group(['middleware' => 'permission:PopularBrand,delete'], function () {
                Route::delete('/{id}', [PopularBrandController::class, 'destroy'])->name('popular.destroy');
            });
        });

        // Lab Test
        Route::prefix('labtest')->group(function () {
            Route::get('', [LabtestController::class, 'index'])->name('labtest.index');
            Route::post('import', [LabtestController::class, 'import'])->name('labtest.import');
            Route::get('/{id}', [LabtestController::class, 'show'])->name('labtest.show');
        });

        //Package Category
        Route::prefix('packageCategory')->group(function () {
            Route::get('', [packageCategoryController::class, 'index'])->name('packageCategory.index');
            Route::get('/create', [packageCategoryController::class, 'create'])->name('packageCategory.create');
            Route::post('/', [packageCategoryController::class, 'store'])->name('packageCategory.store');
            Route::get('/{id}/edit', [packageCategoryController::class, 'edit'])->name('packageCategory.edit');
            Route::put('/{id}', [packageCategoryController::class, 'update'])->name('packageCategory.update');
            Route::delete('/{id}', [packageCategoryController::class, 'destroy'])->name('packageCategory.destroy');
        });

        //Add Lab Packages
        Route::prefix('labPackage')->group(function () {
            Route::get('', [PackageController::class, 'index'])->name('labPackage.index');
            Route::post('/', [PackageController::class, 'store'])->name('labPackage.store');
            Route::get('/create', [PackageController::class, 'create'])->name('labPackage.create');
            Route::post('import', [PackageController::class, 'import'])->name('labPackage.import');
            Route::get('/{id}/edit', [PackageController::class, 'edit'])->name('labPackage.edit');
            Route::put('/{id}', [PackageController::class, 'update'])->name('labPackage.update');
            Route::get('/{id}', [PackageController::class, 'show'])->name('labPackage.show');
            Route::delete('/{id}', [PackageController::class, 'destroy'])->name('labPackage.destroy');
        });

        //Add Medicine
        Route::prefix('addMedicine')->group(function () {
            Route::get('', [AddMedicineController::class, 'index'])->name('addMedicine.index');

            Route::post('', [AddMedicineController::class, 'store'])->name('addMedicine.store');
            // web.php or api.php (depending on where you're calling it from)
            Route::get('/get-medicine-strip', [AddMedicineController::class, 'getMedicineStrip'])->name('medicine.strip');
            Route::get('/search-medicines', [AddMedicineController::class, 'searchMedicines'])->name('medicines.search');
            Route::get('/prescription/select', [AddMedicineController::class, 'prescriptionSelect'])->name('prescription.select');
            Route::get('/prescriptions/{customerId}/prescriptions', [AddMedicineController::class, 'getPrescriptionsByCustomer']);
            //fetch-customer-cart
            Route::get('/fetch-customer-cart', [AddMedicineController::class, 'fetchCustomerCart']);
            Route::get('/fetch-prescription-files', [AddMedicineController::class, 'fetchPrescriptionFiles']);
        });
        Route::prefix('search-medicine')->group(function () {
            Route::get('/pharmacist/add-medicine', [MedicineSearchController::class, 'index'])->name('add.medicine');
            Route::get('/pharmacist/order-details', [MedicineSearchController::class, 'orderdetails'])->name('orderdetails');
            Route::put('/pharmacy/orders/{id}/status', [MedicineSearchController::class, 'updateOrderStatus'])->name('pharmacy.updateOrderStatus');
            Route::get('/orders/{id}/medicines', [MedicineSearchController::class, 'showMedicines'])->name('orders.medicines');
            //Route::post('/orders/{order}/assign-delivery', [MedicineSearchController::class, 'assignDeliveryPerson'])->name('orders.assignDeliveryPerson');
            Route::post('/orders/assign-delivery-person', [MedicineSearchController::class, 'assignDeliveryPerson'])->name('orders.assignDeliveryPerson');

            Route::get('/search-medicine/invoice/download/{id}', [MedicineSearchController::class, 'downloadInvoice'])->name('invoice.download');


            Route::get('/search-medicine', [MedicineSearchController::class, 'search'])->name('search.medicine');
            Route::post('/add-medicine', [MedicineSearchController::class, 'store'])->name('add.medicine.store');
            Route::post('/medicines/store', [MedicineSearchController::class, 'store'])->name('medicines.store');
            Route::get('/customers/select', [MedicineSearchController::class, 'customerSelect'])->name('customers.select');
            // Only one route definition for get-substitute-medicines, no duplicates
            Route::get('/get-substitute-medicines', [MedicineSearchController::class, 'fetchSubstituteBySalt'])->name('get-substitute-medicines');

            Route::get('/get-medicine-salt', [MedicineSearchController::class, 'getSalt'])->name('get-medicine-salt');


            Route::get('/fetch-cart-by-customer', [MedicineSearchController::class, 'fetchCartByCustomer']);
            Route::get('/fetch-prescription-files', [MedicineSearchController::class, 'fetchPrescriptionFiles'])->name('search.prescription');



            Route::get('/orders-filter', [FilteredOrderController::class, 'index'])->name('filtered.orders');
            Route::get('/orders-filter/sales-data', [FilteredOrderController::class, 'salesData'])->name('orders.salesData');
            Route::get('/orders-filter/pharmacy-order-stats', [FilteredOrderController::class, 'getPharmacyOrderStats'])->name('orders.stats');
            Route::get('/orders-filter/pharmacy-order-response', [FilteredOrderController::class, 'getPharmacyOrderResponce'])->name('orders.response');
            Route::get('/orders-filter/pharmacy-top-order', [FilteredOrderController::class, 'getTopPharmacyStats'])->name('orders.top');
            Route::get('/orders-filter/pharmacy-Repeat-Orders', [FilteredOrderController::class, 'getRepeatCustomerStats'])->name('orders.repeat');
        });
        Route::get('customer-list', [CustomerDetailsController::class, 'index'])->name('customer.list');
        Route::get('/customers/{id}', [CustomerDetailsController::class, 'show'])->name('customer.show');





        // Route::post('/add-medicine/store', [MedicineSearchController::class, 'store'])->name('add.medicine.store');

        // rating
        Route::get('/app-ratings', [AppRatingController::class, 'index'])->name('app_ratings.index');

        // zip_code_vise_delivery
        Route::get('/zipcodes', [ZipCodeViceDeliveryController::class, 'index'])->name('zip_code_vise_delivery.index');
        Route::post('/zipcodes/upload', [ZipCodeViceDeliveryController::class, 'uploadZipcodes'])->name('zip_code_vise_delivery.upload');
        Route::delete('/zipcodes/delete-all', [ZipCodeViceDeliveryController::class, 'deleteAll'])->name('zip_code_vise_delivery.deleteAll');

        Route::get('/addLabTest', [AddLabTestController::class, 'index'])->name('addLabTest.index');
        Route::post('/store', [AddLabTestController::class, 'store'])->name('addLabTest.store');
        Route::get('/addLabTestSearch', [AddLabTestController::class, 'search'])->name('addLabTest.search');
        Route::get('/get-contains', [AddLabTestController::class, 'getContains'])->name('addLabTest.contains');
    });
});
require __DIR__ . '/auth.php';

// phlebotomist routes group with resource controller style
Route::prefix('phlebotomist')->group(function () {
    Route::get('/', [PhlebotomistController::class, 'index'])->name('phlebotomist.index');
    Route::get('/create', [PhlebotomistController::class, 'create'])->name('phlebotomist.create');
    Route::post('/store', [PhlebotomistController::class, 'store'])->name('phlebotomist.store');
    Route::get('/{id}/edit', [PhlebotomistController::class, 'edit'])->name('phlebotomist.edit');
    Route::put('/{id}', [PhlebotomistController::class, 'update'])->name('phlebotomist.update');
    Route::delete('/{id}', [PhlebotomistController::class, 'destroy'])->name('phlebotomist.destroy');
});
Route::prefix('CommissionData')->group(function () {
    Route::get('/', [CommissionDataController::class, 'index'])->name('commission_data.index');
    Route::get('/create', [CommissionDataController::class, 'create'])->name('commission_data.create');
    Route::post('/store', [CommissionDataController::class, 'store'])->name('commission_data.store');
    Route::get('/{id}/edit', [CommissionDataController::class, 'edit'])->name('commission_data.edit');
    Route::put('/{id}', [CommissionDataController::class, 'update'])->name('commission_data.update');
    Route::delete('/{id}', [CommissionDataController::class, 'destroy'])->name('commission_data.destroy');
});
Route::get('/prescriptions', [FileUploadController::class, 'index'])->name('prescriptions.index');
Route::get('/uploadprescription', [FileUploadController::class, 'uploadprescription'])->name('prescriptions.upload');
// Route::get('/customers/search', [FileUploadController::class, 'search']);
Route::get('/customers/search', [FileUploadController::class, 'search'])->name('customers.search');
Route::post('/admin/store/prescriptions', [FileUploadController::class, 'store'])->name('admin.prescription.store');

Route::post('/prescriptions/update-status/{id}', [FileUploadController::class, 'updateStatus']);

//popular Lab test

Route::prefix('popular-lab-tests')->group(function () {
    Route::get('/', [PopularLabTestController::class, 'index'])->name('popular_lab_test.index');
    Route::post('/store', [PopularLabTestController::class, 'store'])->name('popular_lab_test.store');
    Route::delete('/{id}', [PopularLabTestController::class, 'destroy'])->name('popular_lab_test.destroy');
});

//HomeBanners
Route::prefix('homebanners')->group(function () {
    Route::get('', [HomeBannerController::class, 'index'])->name('homebanner.index');

    Route::group(['middleware' => 'permission:homebanners,create'], function () {
        Route::post('/store', [HomeBannerController::class, 'store'])->name('homebanner.store');
    });

    Route::group(['middleware' => 'permission:homebanners,update'], function () {
        Route::get('/{id}/edit', [HomeBannerController::class, 'edit'])->name('homebanner.edit');
        Route::put('/{id}', [HomeBannerController::class, 'update'])->name('homebanner.update');
    });

    Route::group(['middleware' => 'permission:homebanners,delete'], function () {
        Route::delete('/{id}', [HomeBannerController::class, 'destroy'])->name('homebanner.destroy');
    });

    Route::group(['middleware' => 'permission:homebanners,read'], function () {
        Route::get('/{id}', [HomeBannerController::class, 'show'])->name('homebanner.show');
    });
});

//Medicine Banners

Route::prefix('medicinebanners')->group(function () {
    Route::get('', [MedicineBannerController::class, 'index'])->name('medicinebanner.index');

    Route::group(['middleware' => 'permission:medicinebanners,create'], function () {
        Route::post('/store', [MedicineBannerController::class, 'store'])->name('medicinebanner.store');
    });

    Route::group(['middleware' => 'permission:medicinebanners,update'], function () {
        Route::get('/{id}/edit', [MedicineBannerController::class, 'edit'])->name('medicinebanner.edit');
        Route::put('/{id}', [MedicineBannerController::class, 'update'])->name('medicinebanner.update');
    });

    Route::group(['middleware' => 'permission:medicinebanners,delete'], function () {
        Route::delete('/{id}', [MedicineBannerController::class, 'destroy'])->name('medicinebanner.destroy');
    });

    Route::group(['middleware' => 'permission:medicinebanners,read'], function () {
        Route::get('/{id}', [MedicineBannerController::class, 'show'])->name('medicinebanner.show');
    });
});

//Joins uS
Route::prefix('join-us')->group(function () {
    Route::get('', [JoinUsController::class, 'index'])->name('joinus.index');
    Route::post('/store', [JoinUsController::class, 'store'])->name('joinus.store');
    Route::delete('/{id}', [JoinUsController::class, 'destroy'])->name('joinus.destroy');
    Route::get('/settings', [JoinUsController::class, 'edit'])->name('joinus');
    // Route::post('/settings', [JoinUsController::class, 'update'])->name('joinus.update');
    Route::post('/settings/update-emails', [JoinUsController::class, 'updateEmails'])->name('joinus.updateEmails');
});

//notification
Route::prefix('notifications')->group(function () {
    Route::get('/', [RequestQuoteController::class, 'index'])->name('notification.index');
    Route::post('/read/{id}', [RequestQuoteController::class, 'markAsRead'])->name('notifications.read');
});
Route::get('/additionalcharges', [AdditionalchargesController::class, 'showForm'])->name('additionalcharges');
Route::post('/platform-fee', [AdditionalchargesController::class, 'storeOrUpdate'])->name('platform-fee.store');


// Route::get('/platform-fee/{id?}', [AdditionalchargesController::class, 'showForm'])->name('platform-fee.form');
Route::post('/pharmacy/toggle-status', [PharmaciesController::class, 'toggleStatus'])->name('pharmacy.toggleStatus');
Route::get('/delivery-info/{id}/{orderId}', [MedicineSearchController::class, 'showDeliveryInfo'])->name('delivery.showDeliveryInfo');

Route::prefix('/webpage')->group(function () {
    Route::view('/home', 'webpage.home');
    // Route::view('/about', 'webpage.about');
    // Route::view('/contact', 'webpage.contact');
    // Route::get('/home', [CmsController::class, 'homeweb'])->name('webpage.home');
    Route::get('/about', [CmsController::class, 'aboutweb'])->name('webpage.about');
    Route::get('/contact', [CmsController::class, 'contactweb'])->name('webpage.contact');
    // Route::view('/webpage/privacy-policy', 'webpage.privacy-policy');
    // Route::view('/webpage/terms', 'webpage.terms');
    Route::get('/terms', [CmsController::class, 'termsweb'])->name('webpage.terms');
    Route::get('/privacy-policy', [CmsController::class, 'privacyPolicyweb'])->name('webpage.privacy-policy');
});

//upload_qr
Route::prefix('qrcode')->group(function () {
    Route::get('', [UploadQRControlle::class, 'index'])->name('upload_qr.index');

    Route::group(['middleware' => 'permission:Upload QR,create'], function () {
        Route::post('/store', [UploadQRControlle::class, 'store'])->name('upload_qr.store');
    });

    Route::group(['middleware' => 'permission:Upload QR,update'], function () {
        Route::get('/{id}/edit', [UploadQRControlle::class, 'edit'])->name('upload_qr.edit');
        Route::put('/{id}', [UploadQRControlle::class, 'update'])->name('upload_qr.update');
    });

    Route::group(['middleware' => 'permission:Upload QR,delete'], function () {
        Route::delete('/{id}', [UploadQRControlle::class, 'destroy'])->name('upload_qr.destroy');
    });

    Route::group(['middleware' => 'permission:Upload QR,read'], function () {
        Route::get('/{id}', [UploadQRControlle::class, 'show'])->name('upload_qr.show');
    });
});


Route::get('/trigger-event', [PusherController::class, 'trigger']);
