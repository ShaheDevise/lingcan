<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\StripePaymentController;

// =============== All Routes ==============

// index route
Route::get("/", [MainController::class, "index"])->name("index");

// about route
Route::get("/pages/about", [MainController::class, "about"])->name("about");

// shop route
Route::get("/pages/shop", [MainController::class, "shop"])->name("shop");

// shop details route
Route::get("/pages/shop/product/{id}/view", [MainController::class, "singleView"])->name("single-view");

// cart route
Route::get("/pages/cart", [MainController::class, "cart"])->name("cart");

// checkout route
Route::get("/pages/cart/checkout", [MainController::class, "checkout"])->name("checkout");

// login route
Route::get("/login", [MainController::class, "login"])->name("login");

// loginUser route
Route::post("/login/user", [MainController::class, "loginUser"])->name("loginUser");

// register route
Route::get("/register", [MainController::class, "register"])->name("register");

// registerUser route
Route::post("/register/user", [MainController::class, "registerUser"])->name("registerUser");

// logout route
Route::get("/logout", [MainController::class, "logout"])->name("logout");

// add to cart route
Route::post("/pages/addToCart", [MainController::class, "addToCart"])->name("addToCart");

// updateCart route
Route::post("/pages/updateCart", [MainController::class, "updateCart"])->name("updateCart");

// delete cart item route
Route::get("/pages/cart/{id}/delete", [MainController::class, "deleteCartItem"])->name("deleteCartItem");


// stripe
Route::controller(StripePaymentController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');
});
