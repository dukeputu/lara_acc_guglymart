<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberLoginController;
use App\Http\Controllers\UserAppLoginController;
use App\Http\Controllers\DualLoginController;
use Illuminate\Support\Facades\Route;

// 🔐 Default page → Login form
// Default Dashboard Page
// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/admin-login', [MemberLoginController::class, 'showLoginForm'])->name('member.login.form');
Route::get('/user-login', [UserAppLoginController::class, 'showLoginForm'])->name('userLogin.app');

Route::get('/login', [MemberLoginController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [MemberLoginController::class, 'login'])->name('member.login');

Route::post('/login-user-app', [UserAppLoginController::class, 'login'])->name('loginUserApp.userApp');

Route::get('/logout-user-app', [UserAppLoginController::class, 'logout'])->name('logoutUserApp.userApp');
// 🧍‍♂️ User app register
Route::get('/register-user-app', function () {
    return view('userApp.userAppView.userRegister');
})->name('userRegister.app');

Route::post('/register-user-app', [MemberController::class, 'registerUserApp'])->name('registerUserApp.userApp');
// 🧍‍♂️ User app login
// User App Login Routes
Route::get('/admin-login-as-user/{userId}', [MemberController::class, 'adminLoginAsUser'])->name('admin.loginAsUser');
// 🚪 Logout route
Route::get('/logout', [MemberLoginController::class, 'logout'])->name('member.logout');
// 🔄 AJAX: Introducer data
Route::get('/get-introducer/{id}', [MemberController::class, 'getIntroducer']);



// 🔒 Protected Routes for Logged-in Members
Route::middleware(['auth.member'])->group(function () {

    // Dashboard
    Route::get('/', [MemberController::class, 'index'])
        ->name('admin.dashboard');

    // 🧾 Member Join
    Route::get('/member-join', [MemberController::class, 'adminCreate']);
    Route::post('/member-join', [MemberController::class, 'adminStore'])->name('fun.memberJoin');

    // **************All Logic App Auth URLs Start******************************

    Route::get('/add-company', [MemberController::class, 'adminCreate'])->name('addAdmin.adminCreate');


    Route::get('/add-user', function () { return view('admin.logicApp.addAppUsers');})->name('addCompany.User');


    Route::post('/add-company', [MemberController::class, 'adminStore'])->name('addAdmin.adminStore');
    Route::get('/edit-company/{id}', [MemberController::class, 'adminEdit'])->name('addAdmin.adminEdit');
    Route::post('/edit-company/{id}', [MemberController::class, 'adminUpdate'])->name('addAdmin.adminUpdate');





    Route::get('/view-admins-list', [MemberController::class, 'viewAdminsList'])->name('viewAdmins.list');
    // Handle delete

    Route::get('/package-master-store', [MemberController::class, 'packageMasterGet'])->name('packageMaster.list');
    Route::post('/package-master-store', [MemberController::class, 'packageMasterStore'])->name('packageMaster.store');

    Route::put('/package-master-update/{id}', [MemberController::class, 'packageMasterUpdate'])->name('packageMaster.update');
    Route::get('/package-master-edit/{id}', [MemberController::class, 'packageMasterEdit'])->name('packageMaster.edit');

    Route::get('/app-banners', [MemberController::class, 'appBannerView'])->name('appBannerView.list');
    Route::post('/app-banners', [MemberController::class, 'appBannerPost'])->name('appBannerView.store');

    Route::get('/app-users-list-admin-panel', [MemberController::class, 'appUsersAdminPanelList'])->name('appUsers.listAdminPanel');

    Route::get('/add-balance-request-list', [MemberController::class, 'appUsersAdminPanelList'])->name('addBalanceRequest.list');

    Route::post('/add-balance-request-list/{id}', [MemberController::class, 'addBalanceTrafer'])->name('addBalanceTrafer.list');

    Route::get('/withdrawal-request-list', [MemberController::class, 'appUsersAdminPanelList'])->name('withdrawalRequest.list');

    Route::post('/withdrawal-request-list/{id}', [MemberController::class, 'withdrawalScrenshortUpload'])->name('withdrawalScrenshortUpload.list');

    Route::get('/package-buying-request-list', [MemberController::class, 'showPackageBuyingRequests'])->name('packageBuyingRequest.list');

    // **************All Logic App Auth URLs end******************************

    // ******************************************************

    // 🧍‍♂️ KYC Update (static)
    /*    Route::get('/kyc-update', function () {
        return view('admin.kyc_update');
    }); */

    // Main MLM Tree View
    Route::get('/mlm/member-tree', [MemberController::class, 'adminMemberTree'])
        ->name('admin.mlm.tree');

    // Export to Excel
    Route::get('/mlm/export', [MemberController::class, 'exportToExcel'])
        ->name('admin.mlm.export');

    // Individual User Report
    Route::get('/mlm/user-report/{userId}', [MemberController::class, 'getUserReport'])
        ->name('admin.mlm.userReport');

    // Search Users
    Route::get('/mlm/search', [MemberController::class, 'searchUsers'])
        ->name('admin.mlm.search');

    // Level Statistics
    Route::get('/mlm/level-statistics', [MemberController::class, 'getLevelStatistics'])
        ->name('admin.mlm.levelStats');

    // Recalculate All Levels (Admin Action)
    Route::post('/mlm/recalculate-levels', [MemberController::class, 'recalculateAllLevels'])
        ->name('admin.mlm.recalculate');

});

// *****************************************************************************

// Protected App User Routes
Route::middleware(['auth.userapp'])->group(function () {

    // ==========================================
   // Add to routes/api.php or routes/web.php
   // ==========================================
   
   // Route::get('/api/user-income-details/{userId}', [MemberController::class, 'getUserIncomeDetails']);
   
   // User Downline Tree View
       Route::get('/member/downlines-tree', [MemberController::class, 'downlinesTree'])->name('member.downlinesTree');
   
   // API Route for Income Details Modal
       Route::get('/api/user-income-details/{userId}', [MemberController::class, 'getUserIncomeDetails'])->name('api.userIncomeDetails');
   
       Route::get('/user-income-details/{userId}', [MemberController::class, 'getUserIncomeDetails']);
   
   // ==========================================
   // Add to MemberController
   // ==========================================
   
       Route::get('/user', function () {
           return view('userApp.userAppView.dashboard');
       })->name('userAppSettings.userApp');
   
       // 🧍‍♂️ User app test
       // Route::get('/user-app-dashboard', [MemberController::class, 'userAppDashboard'])->name('dashboard.app');
   
       // Route::get('/user-app-dashboard', [MemberController::class, 'adminMemberTree'])->name('dashboard.app');
   
       Route::get('/user-app-dashboard', [MemberController::class, 'userAppDashboardUpdate'])->name('dashboard.app');
   
       Route::post('/user-app-dashboard', [MemberController::class, 'withdrawMoneyUserApp'])->name('withdrawMoney.userApp');
   
       Route::post('/buy-package', [MemberController::class, 'buyPackage'])->name('package.buy');
   
       Route::get('/add-balance-user-app', [MemberController::class, 'userAppDashboard'])->name('addBalance.userApp');
   
       Route::post('/add-balance-user-app', [MemberController::class, 'userAddBalance'])->name('userAddBalance.userApp');
   
       Route::get('/all-transactions-user-app', [MemberController::class, 'allTransactionsUserApp'])->name('allTransactions.userApp');
   
       Route::get('/my-packages-list', [MemberController::class, 'myPackagesList'])->name('myPackagesList.userApp');
   
       Route::get('/down-line-tree', [MemberController::class, 'downlinesTree'])->name('downlines.userApp');
   
       Route::get('/api/get-downline-income/{userId}', [MemberController::class, 'getDownlineIncome']);
   
       Route::post('/user/update-password', [MemberController::class, 'updatePassword'])->name('user.password.update');
   
       Route::get('/user/profile-data', [MemberController::class, 'getUserData']);
   
       Route::get('/pin-list', [MemberController::class, 'userPINsList'])->name('userPINsList.userApp');
   
       Route::post('/user-pin/activate', [MemberController::class, 'activateUserPin'])->name('userPin.activate');
   
       Route::post('/user-pins/activate-by-count', [MemberController::class, 'activatePinsByCount'])->name('userPin.activateByCount');
   
       Route::post('/transfer-pin', [MemberController::class, 'transferPins'])->name('transfer.pin');
   
       Route::get('/admin/toggle-user-status/{userId}', [MemberController::class, 'toggleUserStatus'])->name('admin.toggleUserStatus');

});




Route::get('/user-app-settings', function () {
    return view('userApp.userAppView.userAppSettings');
})->name('userAppSettings.userApp');

// **************** route For Logic service

Route::get('/wallet-transaction-list', function () {
    return view('admin.logicApp.walletTransaction');
})->name('walletTransaction.list');

// ************************************************

// Route::get('/delete/{table}/{id}', [MemberController::class, 'deleteFromTable'])->name('generic.delete');
Route::post('/delete/{table}/{id}', [MemberController::class, 'deleteFromTable'])->name('generic.delete');

// ************************************************
