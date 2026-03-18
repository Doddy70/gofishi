<?php

use Illuminate\Support\Facades\Route;

Route::get('/set-locale', 'FrontEnd\MiscellaneousController@setLanguage')->name('change_language');

Route::middleware(['change.lang'])->group(function () {
  Route::get('/', 'FrontEnd\HomeController@index')->name('index');
  Route::get('/about-us', 'FrontEnd\HomeController@about')->name('about_us');
  Route::get('/contact', 'FrontEnd\ContactController@contact')->name('contact');
  Route::post('/contact/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail');

  // Subscriber Route
  Route::post('/store-subscriber', 'FrontEnd\SubscriberController@store')->name('store_subscriber');
  
  // User Auth
  Route::prefix('/user')->group(function () {
    Route::get('/login', 'FrontEnd\UserController@login')->name('user.login');
    Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit');
    Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');
    Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit');
    Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');

    // Authenticated User Routes
    Route::middleware(['auth:web'])->group(function () {
      Route::get('/dashboard', 'FrontEnd\UserController@dashboard')->name('user.dashboard');
      Route::post('/profile/update', 'FrontEnd\UserController@updateProfile')->name('user.update_profile');
      Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout');
      
      // Wishlist
      Route::get('/wishlist/perahu', 'FrontEnd\WishlistController@roomWishlist')->name('user.wishlist.perahu');
      Route::get('/wishlist/lokasi', 'FrontEnd\WishlistController@hotelWishlist')->name('user.wishlist.lokasi');
      
      // Settings
      Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');
      Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');
      Route::get('/support-ticket', 'FrontEnd\UserController@supportTicket')->name('user.support_ticket');
      Route::get('/perahu-bookings', 'FrontEnd\UserController@roomBookings')->name('user.perahu_bookings');
      Route::get('/perahu-booking-details/{id}', 'FrontEnd\UserController@roomBookingDetails')->name('user.perahu_booking_details');
    });

    // Socialite Login Routes
    Route::get('/login/{provider}', 'FrontEnd\SocialLoginController@redirectToProvider')->name('user.login.provider');
    Route::get('/login/{provider}/callback', 'FrontEnd\SocialLoginController@handleProviderCallback')->name('user.login.callback');
  });

  // Perahu
  Route::get('/perahu/ai-search', 'FrontEnd\PerahuController@aiSearch')->name('frontend.perahu.ai_search');
  Route::post('/perahu/ai-search/chat', 'FrontEnd\PerahuController@aiSearchChat')->name('frontend.perahu.ai_search_chat');
  Route::get('/perahu', 'FrontEnd\PerahuController@index')->name('frontend.perahu');
  Route::get('/perahu/{slug}/{id}', 'FrontEnd\PerahuController@details')->name('frontend.perahu.details');
  
  // Perahu Booking & Checkout Routes
  Route::post('/perahu/go-checkout', 'FrontEnd\BookingController@checkCheckout')->name('frontend.perahu.go.checkout');
  Route::get('/perahu/checkout', 'FrontEnd\BookingController@checkout')->name('frontend.perahu.checkout');
  Route::post('/perahu/booking', 'FrontEnd\BookingPayment\BookingController@index')->name('frontend.perahu.booking');
  
  // Midtrans Routes
  Route::get('/perahu/booking/midtrans/notify', 'FrontEnd\BookingPayment\MidtransController@creditCardNotify')->name('frontend.perahu.booking.midtrans.notify');
  Route::post('/perahu/booking/midtrans/webhook', 'FrontEnd\BookingPayment\MidtransController@webhook')->name('frontend.perahu.booking.midtrans.webhook');

  // Xendit Routes
  Route::get('/perahu/booking/xendit/notify', 'FrontEnd\BookingPayment\XenditController@notify')->name('frontend.perahu.booking.xendit.notify');
  Route::post('/perahu/booking/xendit/webhook', 'FrontEnd\BookingPayment\XenditController@callback')->name('frontend.perahu.booking.xendit.webhook');

  // PayPal Routes
  Route::get('/perahu/booking/paypal/notify', 'FrontEnd\BookingPayment\PayPalController@notify')->name('frontend.perahu.booking.paypal.notify');

  // Wishlist Routes
  Route::get('/add-to-wishlist/{id}', 'FrontEnd\WishlistController@add')->name('frontend.perahu.add_wishlist');
  Route::get('/remove-wishlist/{id}', 'FrontEnd\WishlistController@remove')->name('frontend.perahu.remove_wishlist');

  // Blog Routes
  Route::get('/blogs', 'FrontEnd\BlogController@index')->name('frontend.blogs');
  Route::get('/blog/{slug}/{id}', 'FrontEnd\BlogController@details')->name('frontend.blog_details');

  // Vendor Routes
  Route::get('/vendors', 'FrontEnd\VendorController@index')->name('frontend.vendors');
  // Lokasi & Destinasi
  Route::get('/lokasi', 'FrontEnd\LokasiController@index')->name('frontend.lokasi');
  Route::get('/lokasi/{slug}/{id}', 'FrontEnd\LokasiController@details')->name('frontend.lokasi.details');
  Route::get('/destinasi/{slug}', 'FrontEnd\PerahuController@index')->name('frontend.destinasi');
});

// Admin Auth (Outside global middleware to avoid loop)
Route::prefix('/admin')->middleware('guest:admin')->group(function () {
  Route::get('/', 'Admin\AdminController@login')->name('admin.login');
  Route::post('/auth', 'Admin\AdminController@authentication')->name('admin.auth');
  Route::get('/forget-password', 'Admin\AdminController@forgetPassword')->name('admin.forget_password');
  Route::post('/mail-for-forget-password', 'Admin\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
});

require base_path('routes/admin.php');
require base_path('routes/vendor.php');

// MUST be LAST: wildcard vendor profile (catches /vendor/{username} for frontend)
Route::get('/vendor/{username}', 'FrontEnd\VendorController@details')
    ->name('frontend.vendor.details')
    ->middleware('change.lang');



Route::fallback(function () {  return view('errors.404');
})->middleware('change.lang');
Route::get('/faq', 'FrontEnd\MiscellaneousController@faq')->name('faq');
