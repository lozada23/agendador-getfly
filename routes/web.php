<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\CourseController as ClientCourseController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\RoomController;

// =================== INICIO ===================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// =================== AUTENTICACIÓN ===================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// =================== REDIRECCIÓN AUTOMÁTICA POST-LOGIN ===================
Route::get('/redirect', function () {
    $role = auth()->user()->role->name ?? null;

    return match ($role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'pilot'   => redirect()->route('pilot.dashboard'),
        'company' => redirect()->route('company.dashboard'),
        default   => redirect()->route('login')->withErrors(['email' => 'Rol no válido']),
    };
})->middleware('auth')->name('redirect');

// =================== WEBHOOK EPAYCO ===================
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');

// =================== RUTAS PARA PILOTO ===================
Route::middleware(['auth', 'role:pilot'])->prefix('pilot')->name('pilot.')->group(function () {
    Route::get('/dashboard', fn() => view('pilot.dashboard'))->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cursos
    Route::get('/courses', [ClientCourseController::class, 'index'])->name('courses');
    Route::get('/courses/{course}', [ClientCourseController::class, 'show'])->name('courses.show');

    // Agendamiento
    Route::get('/booking/{course}', [ClientBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [ClientBookingController::class, 'store'])->name('booking.store');

    // Reservas
    Route::get('/bookings', [ClientBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [ClientBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/pdf', [ClientBookingController::class, 'downloadPdf'])->name('bookings.pdf');

    // Pagos
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{booking}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{booking}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// =================== RUTAS PARA ADMINISTRADOR ===================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('courses', AdminCourseController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('locations.rooms', RoomController::class);

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');

    Route::get('/export', [ExportController::class, 'index'])->name('export.index');
    Route::post('/export/bookings', [ExportController::class, 'bookings'])->name('export.bookings');
});

// =================== RUTAS PARA EMPRESA/INSTITUTO ===================
Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', fn() => view('company.dashboard'))->name('dashboard');

    Route::get('/students', [CompanyController::class, 'students'])->name('students.index');
    Route::get('/students/create', [CompanyController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [CompanyController::class, 'storeStudent'])->name('students.store');
    Route::get('/bookings', [CompanyController::class, 'bookings'])->name('bookings');
});
