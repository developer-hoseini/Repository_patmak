<?php

use App\Http\Controllers\Admin\AdminAuthentication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Application;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Payment;
use App\Http\Controllers\PaymentValidation;
use App\Http\Middleware\CheckApplicationSession;
use App\Http\Controllers\Admin\Application as AdminApplication;
use App\Http\Controllers\Admin\Manage;
use App\Http\Middleware\AdminAuthentication as MiddlewareAdminAuthentication;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Home::class, 'index'])->name('index');
Route::get('/i1', [Home::class, 'testSms'])->name('index1');
Route::get('/testSms', [TestController::class, 'index'])->name('testindex');
Route::get('/newhome', [Home::class, 'newhome'])->name('newhome');
Route::get('/authenticate', [Authentication::class, 'index']);
Route::post('/login-attempt', [Authentication::class, 'attemp']);
Route::post('/login-verify', [Authentication::class, 'verify']);
Route::get('/access/real', [Authentication::class, 'ssoReal']);
Route::get('/access/legal', [Authentication::class, 'ssoComp']);

Route::post('/login-verify', [Authentication::class, 'verify']);
Route::get('/logout', [Authentication::class, 'logout']);
Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');

// Application management
Route::get('/application/list', [Application::class, 'list']);
Route::get('/application/get_success', [Application::class, 'successApplications']);
// New Application Routes
Route::get('/application/create', [Application::class, 'create']);
Route::post('/application/cost', [Application::class, 'cost']);
Route::post('/application/primary-info-submit', [Application::class, 'primaryInfoSubmit']);
Route::post('/application/legal-person-submit', [Application::class, 'legalPersonSubmit']);
Route::post('/application/personal-info-submit', [Application::class, 'personalInfoSubmit']);
Route::post('/application/contact-info-submit', [Application::class, 'contactInfoSubmit']);
Route::post('/application/insurance-info-submit', [Application::class, 'InsuranceInfoSubmit']);
Route::post('/application/education-info-submit', [Application::class, 'educationInfoSubmit']);
Route::post('/application/request-info-submit', [Application::class, 'requestInfoSubmit']);
Route::post('/application/previous-license-info-submit', [Application::class, 'previousLicenseInfoSubmit']);
Route::post('/application/previous-license-image-upload', [Application::class, 'previousLicenseImageUpload']);
Route::get('/application/get-preview', [Application::class, 'getPreview']);
Route::get('/application/get-file', [Application::class, 'getFile']);
Route::post('/application/preview-submit', [Application::class, 'previewSubmit']);
// Payment
Route::get('/application/{application_id}/pay', [Payment::class, 'index']);
Route::post('/application/{application_id}/request-payment', [Payment::class, 'requestPayment']);
Route::post('/order/{order_id}/bank-return', [Payment::class, 'bankReturn']);
// Show Tracking code
Route::get('/application/{application_id}/tracking-code', [Application::class, 'getTrackingCode']);
Route::get('/application/{application_id}/receipt', [Application::class, 'getReceipt']);
//
Route::get('/application/cities', [Application::class, 'getCities']);
Route::get('/application/study-fields', [Application::class, 'getStydyFields']);
Route::get('/application/license-basis-auth-options/{license_type_id}', [Application::class, 'getLicenseBasisAuthOptions']);
Route::get('/application/lastest-application-data', [Application::class, 'getLatestApplicationData']);
Route::get('/application/price-list', [Application::class, 'priceList']);

// payment-validation routes
Route::get('/payment-validation', [PaymentValidation::class, 'index']);
// Route::get('/temp', [PaymentValidation::class, 'temp']);
Route::post('/payment-validation', [PaymentValidation::class, 'submit']);

// admin routes
Route::get('/admin/authentication', [AdminAuthentication::class, 'index'])->name('admin-login');
Route::post('/admin/authentication', [AdminAuthentication::class, 'attempt']);
Route::get('/admin/logout', [AdminAuthentication::class, 'logout']);
Route::get('/admin/application', [AdminApplication::class, 'index'])->name('admin-applications');
Route::post('/admin/application', [AdminApplication::class, 'getRecords']);
Route::get('/admin/application/get-file', [Application::class, 'getFile']);
Route::get('/admin/application/{application_id}', [AdminApplication::class, 'view']);
Route::get('/admin/application/{application_id}/transactions', [Manage::class, 'transactions']);
Route::get('/admin/transaction-check/{transaction_id}/{order_number}', [Manage::class, 'recheckPayment']);
Route::get('/admin/report', [AdminApplication::class, 'report']);
Route::get('/admin/test', [Manage::class, 'sabteAhvalCheck']);

Route::get('/jobs', function () {
    $exitCode = Artisan::call('payment:update');
    // $exitCode = Artisan::call('company:update');
    return '<h1>Jobs Run</h1>';
});