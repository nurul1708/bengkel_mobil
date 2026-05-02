<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\Admin\AdminChatController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
   



Route::prefix('customer')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('customer.home');
    Route::get('/about', [App\Http\Controllers\AboutController::class, 'index']);
    Route::get('/services', [App\Http\Controllers\ServicesController::class, 'index']);
    Route::get('/bookings', [App\Http\Controllers\BookingsController::class, 'index'])->name('customer.bookings.index');
    Route::post('/bookings', [App\Http\Controllers\BookingsController::class, 'store'])->name('customer.bookings.store');
    Route::get('/vehicle-brands/{brand}/models', [App\Http\Controllers\VehicleModelController::class, 'byBrand'])->name('customer.vehicle-models.by-brand');
    Route::get('/team', [App\Http\Controllers\TeamController::class, 'index']);
    Route::get('/testimonial', [App\Http\Controllers\TestimonialController::class, 'index']);
    Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index']);
   Route::get('/register', [RegisterController::class, 'registerForm'])->name('client.register');
Route::post('/register', [RegisterController::class, 'clientStore'])->name('client.register.store');
Route::get('/verify-otp', [RegisterController::class, 'verifyOTPForm'])->name('client.verifyOTPForm');
Route::post('/verify-otp', [RegisterController::class, 'verifyOTP'])->name('client.verifyOTP');
Route::post('/resend-otp', [RegisterController::class, 'resendOTP'])->name('client.resendOTP');
Route::get('/login', [ClientAuthController::class, 'LoginForm'])->name('client.loginForm');
Route::post('/login', [ClientAuthController::class, 'Login'])->name('client.login');
Route::post('/logout', [ClientAuthController::class, 'Logout'])->name('client.logout');

Route::middleware('client.auth')->group(function () {
Route::post('/profile/', [ClientController::class, 'update'])->name('client.profile.update');
    Route::get('/profile/', [ClientController::class, 'show'])->name('client.profile.show');
Route::get('/history', [ClientController::class, 'history'])->name('client.history.index');
Route::get('/transactions', [ClientController::class, 'transactions'])->name('client.transactions.index');
Route::post('/testimonials/{id}', [ClientController::class, 'testimonialStore'])->name('client.testimonial.store');
Route::get('/payments/{id}', [ClientController::class, 'paymentShow'])->name('client.payment.show');
Route::get('/payments/{id}/invoice', [ClientController::class, 'paymentInvoice'])->name('client.payment.invoice');
Route::post('/payments/{id}', [ClientController::class, 'paymentStore'])->name('client.payment.store');
Route::post('/payments/{id}/midtrans/snap', [ClientController::class, 'paymentMidtransSnap'])->name('client.payment.midtrans.snap');
Route::post('/payments/{id}/midtrans/check', [ClientController::class, 'paymentMidtransCheck'])->name('client.payment.midtrans.check');
Route::get('/chat', [UserChatController::class, 'index'])->name('chat.index');
Route::post('/chat', [UserChatController::class, 'store'])->name('chat.store');
});




}
);
Route::post('/midtrans/notification', [ClientController::class, 'midtransNotification'])->name('midtrans.notification');
Route::prefix('admin')->group(function () {
    # Authentication Routes
    Route::get('/', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login');
    Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login']);
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'authenticate']);
    Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware('admin.auth')->group(function () {
        #chat Untuk Admin
       Route::get('/chat', [AdminChatController::class, 'index'])->name('admin.chat.index')->middleware('role:admin');
    Route::get('/chat/{id}', [AdminChatController::class, 'show'])->name('admin.chat.show')->middleware('role:admin');
    // Contoh rute untuk mengirim chat dari sisi ADMIN
Route::post('/chat/send', [App\Http\Controllers\Admin\AdminChatController::class, 'send'])->name('chat.send')->middleware('role:admin');
        });

        # umum backend
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->middleware('role:admin,owner');
        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->middleware('role:admin,owner,kasir,mekanik');
        Route::post('/profile/update', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->middleware('role:admin,owner,kasir,mekanik');

        # booking
        Route::get('/booking', [App\Http\Controllers\Admin\BookingController::class, 'index'])->middleware('role:admin,owner,kasir,mekanik');
        Route::get('/booking/{id}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->middleware('role:admin,owner,kasir,mekanik');
        Route::post('/booking/{id}/verifikasi', [App\Http\Controllers\Admin\BookingController::class, 'verifikasi'])->middleware('role:admin');
        Route::post('/booking/{id}/proses', [App\Http\Controllers\Admin\BookingController::class,'proses'])->middleware('role:admin,mekanik');
        Route::post('/booking/{id}/selesai', [App\Http\Controllers\Admin\BookingController::class,'selesai'])->middleware('role:admin,mekanik');

        # service
        Route::get('/service', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->middleware('role:admin,mekanik')->name('service.index');
        Route::get('/service/create', [App\Http\Controllers\Admin\ServiceController::class, 'create'])->middleware('role:admin')->name('service.create');
        Route::post('/service', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->middleware('role:admin')->name('service.store');
        Route::get('/service/{service}/edit', [App\Http\Controllers\Admin\ServiceController::class, 'edit'])->middleware('role:admin')->name('service.edit');
        Route::put('/service/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->middleware('role:admin')->name('service.update');
        Route::delete('/service/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->middleware('role:admin')->name('service.destroy');

        # vehicle
        Route::get('/vehicle', [App\Http\Controllers\Admin\VehicleController::class, 'index'])->middleware('role:admin,mekanik');
        Route::get('/vehicle/create', [App\Http\Controllers\Admin\VehicleController::class, 'create'])->middleware('role:admin');
        Route::post('/vehicle', [App\Http\Controllers\Admin\VehicleController::class, 'store'])->middleware('role:admin');
        Route::get('/vehicle/{id}/edit', [App\Http\Controllers\Admin\VehicleController::class, 'edit'])->middleware('role:admin');
        Route::get('/vehicle/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'show'])->middleware('role:admin,mekanik');
        Route::put('/vehicle/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'update'])->middleware('role:admin');
        Route::delete('/vehicle/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'destroy'])->middleware('role:admin');

        # laporan dan transaksi
        Route::get('/laporan', [App\Http\Controllers\Admin\AdminController::class, 'laporan'])
            ->name('admin.laporan')
            ->middleware('role:admin,owner');
        Route::get('/laporan/export/excel', [App\Http\Controllers\Admin\AdminController::class, 'exportLaporanExcel'])
            ->name('admin.laporan.export.excel')
            ->middleware('role:admin,owner');
        Route::get('/laporan/export/pdf', [App\Http\Controllers\Admin\AdminController::class, 'exportLaporanPdf'])
            ->name('admin.laporan.export.pdf')
            ->middleware('role:admin,owner');
        Route::get('/transaksi/create', [App\Http\Controllers\Admin\TransactionController::class, 'create'])->middleware('role:admin,kasir');
        Route::post('/transaksi/bayar', [App\Http\Controllers\Admin\TransactionController::class, 'store'])->middleware('role:admin,kasir');
        Route::post('/transaksi/{id}/bayar', [App\Http\Controllers\Admin\TransactionController::class, 'bayar'])->middleware('role:admin,kasir');
        Route::post('/transaksi/{id}/midtrans/snap', [App\Http\Controllers\Admin\TransactionController::class, 'midtransSnap'])->middleware('role:admin,kasir')->name('admin.transaksi.midtrans.snap');
        Route::post('/transaksi/{id}/midtrans/check', [App\Http\Controllers\Admin\TransactionController::class, 'midtransCheck'])->middleware('role:admin,kasir')->name('admin.transaksi.midtrans.check');
        Route::get('/transaksi/{id}/invoice', [App\Http\Controllers\Admin\TransactionController::class, 'invoice'])->middleware('role:admin,owner,kasir');
        Route::get('/transaksi/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->middleware('role:admin,owner,kasir');
        Route::get('/transaksi', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->middleware('role:admin,owner,kasir');

        # testimonial review
        Route::get('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'index'])->middleware('role:admin,owner');
        Route::post('/testimonials/{id}/approve', [App\Http\Controllers\Admin\TestimonialController::class, 'approve'])->middleware('role:admin,owner');
        Route::post('/testimonials/{id}/reject', [App\Http\Controllers\Admin\TestimonialController::class, 'reject'])->middleware('role:admin,owner');

        # sparepart
        Route::get('/spareparts', [App\Http\Controllers\Admin\SparepartController::class, 'index'])
            ->name('spareparts.index')
            ->middleware('role:admin,mekanik,kasir');
        Route::get('/spareparts/create', [App\Http\Controllers\Admin\SparepartController::class, 'create'])
            ->name('spareparts.create')
            ->middleware('role:admin,mekanik');
        Route::post('/spareparts', [App\Http\Controllers\Admin\SparepartController::class, 'store'])
            ->name('spareparts.store')
            ->middleware('role:admin,mekanik');
        Route::get('/spareparts/{sparepart}', [App\Http\Controllers\Admin\SparepartController::class, 'show'])
            ->name('spareparts.show')
            ->middleware('role:admin,mekanik,kasir');
        Route::get('/spareparts/{sparepart}/edit', [App\Http\Controllers\Admin\SparepartController::class, 'edit'])
            ->name('spareparts.edit')
            ->middleware('role:admin,mekanik');
        Route::put('/spareparts/{sparepart}', [App\Http\Controllers\Admin\SparepartController::class, 'update'])
            ->name('spareparts.update')
            ->middleware('role:admin,mekanik');
        Route::patch('/spareparts/{sparepart}', [App\Http\Controllers\Admin\SparepartController::class, 'update'])
            ->middleware('role:admin,mekanik');
        Route::delete('/spareparts/{sparepart}', [App\Http\Controllers\Admin\SparepartController::class, 'destroy'])
            ->name('spareparts.destroy')
            ->middleware('role:admin,mekanik');
        

        # users
        Route::get('/users', [App\Http\Controllers\Admin\UsersController::class, 'index'])->middleware('role:admin,owner')->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UsersController::class, 'create'])->middleware('role:admin,owner')->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UsersController::class, 'store'])->middleware('role:admin,owner')->name('users.store');
        Route::get('/users/{id}', [App\Http\Controllers\Admin\UsersController::class, 'show'])->middleware('role:admin,owner')->name('users.show');
        Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UsersController::class, 'edit'])->middleware('role:admin,owner')->name('users.name');
        Route::put('/users/{id}', [App\Http\Controllers\Admin\UsersController::class, 'update'])->middleware('role:admin,owner')->name('users.update');
        Route::delete('/users/{id}', [App\Http\Controllers\Admin\UsersController::class, 'destroy'])->middleware('role:admin,owner')->name('users.destroy');
    });
