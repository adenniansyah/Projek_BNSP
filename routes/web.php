<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {
    
    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Books Public
    Route::get('/books', [BookController::class, 'userIndex'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // KHUSUS USER
    Route::middleware('user')->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
        Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        
        // Checkout & Payment (PERBAIKAN NAMA ROUTE)
        Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.show');
        Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
        Route::get('/payment/{order}', [OrderController::class, 'showPaymentPage'])->name('payment.page');
        Route::get('/payment/success/{order}', [OrderController::class, 'paymentSuccess'])->name('payment.success');
        
        // Orders User
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        
        // Contact User
        Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
        Route::get('/my-messages', [ContactController::class, 'myMessages'])->name('user.messages');
    });
});

// WEBHOOK MIDTRANS (Tanpa Auth)
Route::post('/api/midtrans/notification', [OrderController::class, 'handleMidtransNotification'])->name('midtrans.notification');

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);
    
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    
    // Admin Orders Management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verify');
    Route::patch('/orders/{order}/update-payment', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    
    // Admin Contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/reply', [ContactController::class, 'updateStatus'])->name('contacts.reply');
});