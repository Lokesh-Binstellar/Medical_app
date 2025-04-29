<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\LabtestController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineSearchController;
use App\Http\Controllers\OtcController;
use App\Http\Controllers\PharmaciesController;
use App\Http\Controllers\PopularBrandController;
use App\Http\Controllers\PopularCategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified', 'permission:pharmacies,create']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

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
            Route::get('{id}/edit', [PopularCategoryController::class, 'edit'])->name('popular_category.edit');   // GET /popularCategory/{id}/edit
            Route::put('{id}', [PopularCategoryController::class, 'update'])->name('popular_category.update');
        });
        Route::group(['middleware' => 'permission:PopularCategory,delete'], function () {
            Route::delete('{id}', [PopularCategoryController::class, 'destroy'])->name('popular_category.destroy'); // DELETE /popularCategory/{id}
        });
    });

    Route::prefix('otcmedicine')->group(function () {
        Route::get('', [OtcController::class, 'index'])->name('otcmedicine.index');
        Route::post('import', [OtcController::class, 'import'])->name('otcmedicine.import');
        Route::get('/{id}', [OtcController::class, 'show'])->name('otcmedicine.show');
    });

    Route::prefix('popular')->group(function () {
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






// // Route to show Add Medicine page
// Route::get('/add-medicine', [MedicineSearchController::class, 'index'])->name('add.medicine');

// // Route for the search functionality
// Route::get('/search-medicine', [MedicineSearchController::class, 'search'])->name('search.medicine');

// // Route to store added medicines
// Route::post('/add-medicine/store', [MedicineSearchController::class, 'store'])->name('add.medicine.store');
Route::get('/pharmacist/add-medicine', [MedicineSearchController::class, 'index'])->name('add.medicine');
Route::get('/search-medicine', [MedicineSearchController::class, 'search'])->name('search.medicine');
Route::post('/add-medicine', [MedicineSearchController::class, 'store'])->name('add.medicine.store');
Route::post('/medicines/store', [MedicineSearchController::class, 'store'])->name('medicines.store');


});

require __DIR__ . '/auth.php';


