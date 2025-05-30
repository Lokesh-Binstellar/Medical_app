<?php

use App\Events\MyEvent;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\LabtestController;
use App\Http\Controllers\AddLabTestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineSearchController;
use App\Http\Controllers\AddMedicineController;
use App\Http\Controllers\AdditionalchargesController;
use App\Http\Controllers\AppRatingController;
use App\Http\Controllers\DeliveryPersonController;
use App\Http\Controllers\OtcController;
use App\Http\Controllers\packageCategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeBannerController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MedicineBannerController;
use App\Http\Controllers\PhlebotomistController;
use App\Http\Controllers\PopularLabTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestQuoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZipCodeViceDeliveryController;
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
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified', 'permission:pharmacies,create']);

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth', 'verified']);



    Route::get('get-dashboard-graph-data', [DashboardController::class, 'getAllGraphData'])->name('dashboard.graph.data');
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
        Route::post('/orders/{order}/assign-delivery', [MedicineSearchController::class, 'assignDeliveryPerson'])->name('orders.assignDeliveryPerson');
        Route::get('/search-medicine/invoice/download/{id}', [MedicineSearchController::class, 'downloadInvoice'])->name('invoice.download');


        Route::get('/search-medicine', [MedicineSearchController::class, 'search'])->name('search.medicine');
        Route::post('/add-medicine', [MedicineSearchController::class, 'store'])->name('add.medicine.store');
        Route::post('/medicines/store', [MedicineSearchController::class, 'store'])->name('medicines.store');
        Route::get('/customers/select', [MedicineSearchController::class, 'customerSelect'])->name('customers.select');

        Route::get('/fetch-cart-by-customer', [MedicineSearchController::class, 'fetchCartByCustomer']);
        Route::get('/fetch-prescription-files', [MedicineSearchController::class, 'fetchPrescriptionFiles'])->name('search.prescription');
    });

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

Route::get('/prescriptions', [FileUploadController::class, 'index'])->name('prescriptions.index');
Route::get('/uploadprescription', [FileUploadController::class, 'uploadprescription'])->name('prescriptions.upload');
Route::get('/customers/search', [FileUploadController::class, 'search']);
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
