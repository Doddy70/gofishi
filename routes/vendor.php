<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | vendor Interface Routes
 |--------------------------------------------------------------------------
 */

Route::prefix('vendor')->middleware('change.lang')->group(function () {
  Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
  Route::get('/signup-success', 'Vendor\VendorController@signupSuccess')->name('vendor.signup_success');
  Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit')->middleware('Demo');
  Route::get('/login/{provider}', 'FrontEnd\SocialLoginController@redirectToProvider')->name('vendor.login.provider');
  Route::get('/login/{provider}/callback', 'FrontEnd\SocialLoginController@handleProviderCallback')->name('vendor.login.callback');
  Route::get('/login', 'Vendor\VendorController@login')->name('vendor.login');
  Route::post('/login/submit', 'Vendor\VendorController@authentication')->name('vendor.login_submit');
  Route::get('/admin', function() {
      return redirect()->route('vendor.dashboard');
  });

  Route::get('/email/verify', 'Vendor\VendorController@confirm_email');

  Route::get('/forget-password', 'Vendor\VendorController@forget_passord')->name('vendor.forget.password');
  Route::post('/send-forget-mail', 'Vendor\VendorController@forget_mail')->name('vendor.forget.mail')->middleware('Demo');
  Route::get('/reset-password', 'Vendor\VendorController@reset_password')->name('vendor.reset.password');
  Route::post('/update-forget-password', 'Vendor\VendorController@update_password')->name('vendor.update-forget-password')->middleware('Demo');
});

Route::get('/set-locale-vendor', 'Vendor\VendorController@setLocaleAdmin')->name('set-locale-vendor');
Route::prefix('vendor')->middleware('auth:vendor', 'Demo', 'Deactive', 'email.verify', 'vendorLang')->group(function () {

  //========Lokasi Management//
  Route::prefix('lokasi-management')->middleware('document.verified')->group(function () {

      Route::get('/', 'Vendor\LokasiController@index')->name('vendor.lokasi_management.lokasi');

      Route::prefix('/purchase-feature')->group(function () {

          Route::post('', 'Vendor\LokasiFeature\LokasiFeatureController@index')->name('vendor.lokasi_management.lokasi.purchase_feature');

          Route::get('/paypal/notify', 'Vendor\LokasiFeature\PayPalController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.paypal.notify');
          Route::get('/instamojo/notify', 'Vendor\LokasiFeature\InstamojoController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.instamojo.notify');
          Route::get('/flutterwave/notify', 'Vendor\LokasiFeature\FlutterwaveController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.flutterwave.notify');
          Route::post('/razorpay/notify', 'Vendor\LokasiFeature\RazorpayController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.razorpay.notify');
          Route::get('/mollie/notify', 'Vendor\LokasiFeature\MollieController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.mollie.notify');
          Route::get('/yoco/notify', 'Vendor\LokasiFeature\YocoController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.yoco.notify');
          Route::post('/paytabs/notify', 'Vendor\LokasiFeature\PaytabsController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.paytabs.notify');
          Route::get('/toyyibpay/notify', 'Vendor\LokasiFeature\ToyyibpayController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.toyyibpay.notify');
          Route::get('/midtrans/notify', 'Vendor\LokasiFeature\MidtransController@creditCardNotify')->name('vendor.lokasi_management.lokasi.purchase_feature.midtrans.notify');
          Route::get('/paystack/notify', 'Vendor\LokasiFeature\PaystackController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.paystack.notify');
          Route::post('/iyzico/notify', 'Vendor\LokasiFeature\IyzicoController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.iyzico.notify');
          Route::get('/iyzico/cancle', 'Vendor\LokasiFeature\IyzicoController@iyzicoCancle')->name('vendor.lokasi_management.lokasi.purchase_feature.iyzico.cancle');
          Route::get('/mercadopago/notify', 'Vendor\LokasiFeature\MercadoPagoController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.mercadopago.notify');
          Route::post('/paytm/notify', 'Vendor\LokasiFeature\PaytmController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.paytm.notify');
          Route::get('/xendit/notify', 'Vendor\LokasiFeature\XenditController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.xendit.notify');
          Route::get('/perfect-money/notify', 'Vendor\LokasiFeature\PerfectMoneyController@notify')->name('vendor.lokasi_management.lokasi.purchase_feature.perfect_money.notify');
          Route::any('/phonepe/notify', 'Vendor\LokasiFeature\PhonepeController@notify')->name('vendor.lokasi_management.lokasi.phonepe.notify');


          //featured service payment success message
          Route::get('/online/success', 'Vendor\LokasiFeature\LokasiFeatureController@onlineSuccess')->name('featured.lokasi.online.success.page');

          Route::get('/offline/success', 'Vendor\LokasiFeature\LokasiFeatureController@offlineSuccess')->name('vendor.lokasi_management.lokasi.purchase_feature.offline.success');
        }
        );

        Route::post('/get-state', 'Vendor\LokasiController@getState')->name('vendor.lokasi_management.get-state');
        Route::post('/get-city', 'Vendor\LokasiController@getCity')->name('vendor.lokasi_management.get-city');

        Route::get('/create', 'Vendor\LokasiController@create')->name('vendor.lokasi_management.create_lokasi');
        Route::post('store', 'Vendor\LokasiController@store')->name('vendor.lokasi_management.store_lokasi')->middleware('packageLimitsCheck:hotel,store');

        Route::get('edit-lokasi/{id}', 'Vendor\LokasiController@edit')->name('vendor.lokasi_management.edit_lokasi');
        Route::post('update/{id}', 'Vendor\LokasiController@update')->name('vendor.lokasi_management.update_lokasi')->middleware('packageLimitsCheck:hotel,update');

        Route::post('delete/{id}', 'Vendor\LokasiController@delete')->name('vendor.lokasi_management.delete_lokasi');
        Route::post('bulk_delete', 'Vendor\LokasiController@bulkDelete')->name('vendor.lokasi_management.bulk_delete.lokasi');

        Route::post('update-status', 'Vendor\LokasiController@updateStatus')->name('vendor.lokasi_management.update_lokasi_status');

        Route::get('manage-counter-section/{id}', 'Vendor\LokasiController@manageCounterInformation')->name('vendor.lokasi_management.manage_counter_section');
        Route::post('update-counter-section/{id}', 'Vendor\LokasiController@updateCounterInformation')->name('vendor.lokasi_management.update_counter_section')->middleware('packageLimitsCheck:hotel,update');
        Route::post('counter/delete', 'Vendor\LokasiController@CounterDelete')->name('vendor.lokasi_management.delete_counter');

        Route::post('aminitie/cng', 'Vendor\LokasiController@amenitiesUpdate')->name('vendor.lokasi_management.update_amenities');


        //#==========Lokasi slider image
        Route::post('/img-store', 'Vendor\LokasiController@imagesstore')->name('vendor.lokasi_management.lokasi.imagesstore');
        Route::post('/img-remove', 'Vendor\LokasiController@imagermv')->name('vendor.lokasi_management.lokasi.imagermv');
        Route::post('/img-db-remove', 'Vendor\LokasiController@imagedbrmv')->name('vendor.lokasi_management.lokasi.imgdbrmv');
        //#==========Lokasi slider image End
    
        // holiday route
        Route::prefix('holiday')->group(function () {
          Route::get('/', 'Vendor\HolidayController@index')->name('vendor.lokasi_management.lokasi.holiday');
          Route::post('/store', 'Vendor\HolidayController@store')->name('vendor.lokasi_management.lokasi.holiday.store')->middleware('packageLimitsCheck:hotel,update');
          Route::post('/delete/{id}', 'Vendor\HolidayController@destroy')->name('vendor.lokasi_management.lokasi.holiday.delete');
          Route::post('/bulk-destory', 'Vendor\HolidayController@blukDestroy')->name('vendor.global.holiday.bluk-destroy');
        }
        );
      }
      );
      //=====Lokasi Management END============
    
      //========PERAHU MANAGEMENT//
      Route::prefix('perahu-management')->middleware(['sanitize.html', 'document.verified'])->group(function () {

      // coupon route
      Route::prefix('coupons')->group(function () {
          Route::get('/', 'Vendor\CouponController@index')->name('vendor.perahu_management.coupons');
          Route::post('/store', 'Vendor\CouponController@store')->name('vendor.perahu_management.coupon.store');
          Route::post('/update', 'Vendor\CouponController@update')->name('vendor.perahu_management.coupon.update');
          Route::post('/delete/{id}', 'Vendor\CouponController@destroy')->name('vendor.perahu_management.coupon.delete');
          Route::post('/bulk-delete', 'Vendor\CouponController@bulkDestroy')->name('vendor.perahu_management.coupon.bulk_delete');
        }
        );


        Route::get('/', 'Vendor\PerahuController@index')->name('vendor.perahu_management.perahu');

        Route::prefix('/purchase-feature')->group(function () {

          Route::post('', 'Vendor\PerahuFeature\PerahuFeatureController@index')->name('vendor.perahu_management.perahu.purchase_feature');

          Route::get('/paypal/notify', 'Vendor\PerahuFeature\PayPalController@notify')->name('vendor.perahu_management.perahu.purchase_feature.paypal.notify');
          Route::get('/instamojo/notify', 'Vendor\PerahuFeature\InstamojoController@notify')->name('vendor.perahu_management.perahu.purchase_feature.instamojo.notify');
          Route::get('/flutterwave/notify', 'Vendor\PerahuFeature\FlutterwaveController@notify')->name('vendor.perahu_management.perahu.purchase_feature.flutterwave.notify');
          Route::post('/razorpay/notify', 'Vendor\PerahuFeature\RazorpayController@notify')->name('vendor.perahu_management.perahu.purchase_feature.razorpay.notify');
          Route::get('/mollie/notify', 'Vendor\PerahuFeature\MollieController@notify')->name('vendor.perahu_management.perahu.purchase_feature.mollie.notify');
          Route::get('/yoco/notify', 'Vendor\PerahuFeature\YocoController@notify')->name('vendor.perahu_management.perahu.purchase_feature.yoco.notify');
          Route::post('/paytabs/notify', 'Vendor\PerahuFeature\PaytabsController@notify')->name('vendor.perahu_management.perahu.purchase_feature.paytabs.notify');
          Route::get('/toyyibpay/notify', 'Vendor\PerahuFeature\ToyyibpayController@notify')->name('vendor.perahu_management.perahu.purchase_feature.toyyibpay.notify');
          Route::get('/midtrans/notify', 'Vendor\PerahuFeature\MidtransController@creditCardNotify')->name('vendor.perahu_management.perahu.purchase_feature.midtrans.notify');
          Route::get('/paystack/notify', 'Vendor\PerahuFeature\PaystackController@notify')->name('vendor.perahu_management.perahu.purchase_feature.paystack.notify');
          Route::post('/iyzico/notify', 'Vendor\PerahuFeature\IyzicoController@notify')->name('vendor.perahu_management.perahu.purchase_feature.iyzico.notify');
          Route::get('/iyzico/cancle', 'Vendor\PerahuFeature\IyzicoController@iyzicoCancle')->name('vendor.perahu_management.perahu.purchase_feature.iyzico.cancle');
          Route::get('/mercadopago/notify', 'Vendor\PerahuFeature\MercadoPagoController@notify')->name('vendor.perahu_management.perahu.purchase_feature.mercadopago.notify');
          Route::post('/paytm/notify', 'Vendor\PerahuFeature\PaytmController@notify')->name('vendor.perahu_management.perahu.purchase_feature.paytm.notify');
          Route::get('/xendit/notify', 'Vendor\PerahuFeature\XenditController@notify')->name('vendor.perahu_management.perahu.purchase_feature.xendit.notify');
          Route::get('/perfect-money/notify', 'Vendor\PerahuFeature\PerfectMoneyController@notify')->name('vendor.perahu_management.perahu.purchase_feature.perfect_money.notify');
          Route::any('/phonepe/notify', 'Vendor\PerahuFeature\PhonepeController@notify')->name('vendor.perahu_management.perahu.phonepe.notify');



          //featured service payment success message
          Route::get('/online/success', 'Vendor\PerahuFeature\PerahuFeatureController@onlineSuccess')->name('featured.service.online.success.page');

          Route::get('/offline/success', 'Vendor\PerahuFeature\PerahuFeatureController@offlineSuccess')->name('vendor.perahu_management.perahu.purchase_feature.offline.success');
        }
        );


        Route::get('/create', 'Vendor\PerahuController@create')->name('vendor.perahu_management.create_perahu');
        Route::post('store', 'Vendor\PerahuController@store')->name('vendor.perahu_management.store_perahu')->middleware('packageLimitsCheck:room,store');

        Route::get('edit-perahu/{id}', 'Vendor\PerahuController@edit')->name('vendor.perahu_management.edit_perahu');
        Route::post('update/{id}', 'Vendor\PerahuController@update')->name('vendor.perahu_management.update_perahu')->middleware('packageLimitsCheck:room,update');

        Route::post('delete/{id}', 'Vendor\PerahuController@delete')->name('vendor.perahu_management.delete_perahu');
        Route::post('bulk_delete', 'Vendor\PerahuController@bulkDelete')->name('vendor.perahu_management.bulk_delete.perahu');
        Route::post('update-status', 'Vendor\PerahuController@updateStatus')->name('vendor.perahu_management.update_perahu_status');
        Route::get('manage-additional-service/{id}', 'Vendor\PerahuController@manageAdditionalService')->name('vendor.perahu_management.manage_additional_service');
        Route::post('update-additional-service/{id}', 'Vendor\PerahuController@updateAdditionalService')->name('vendor.perahu_management.update_additional_service')->middleware('packageLimitsCheck:hotel,update');
        Route::post('aminitie/cng', 'Vendor\PerahuController@amenitiesUpdate')->name('vendor.perahu_management.update_amenities');

        // Boat Packages Routes
        Route::prefix('perahu/{perahu_id}')->name('vendor.perahu.')->group(function () {
            Route::resource('packages', 'Vendor\BoatPackageController');
        });

        //#==========PERAHU slider image
        Route::post('/img-store', 'Vendor\PerahuController@imagesstore')->name('vendor.perahu_management.perahu.imagesstore');
        Route::post('/img-remove', 'Vendor\PerahuController@imagermv')->name('vendor.perahu_management.perahu.imagermv');
        Route::post('/img-db-remove', 'Vendor\PerahuController@imagedbrmv')->name('vendor.perahu_management.perahu.imgdbrmv');
      //#==========PERAHU slider image End
      }
      );
      //=====Perahu MANAGEMENT END============
    
      // Perahu Bookings Routes
      Route::prefix('perahu-bookings')->group(function () {

      Route::get('/all-bookings', 'Vendor\PerahuBookingController@index')->name('vendor.perahu_bookings.all_bookings');

      Route::get('/paid-bookings', 'Vendor\PerahuBookingController@index')->name('vendor.perahu_bookings.paid_bookings');

      Route::get('/unpaid-bookings', 'Vendor\PerahuBookingController@index')->name('vendor.perahu_bookings.unpaid_bookings');

      Route::post('/update-order-status', 'Vendor\PerahuBookingController@updateOrderStatus')->name('vendor.perahu_bookings.update_order_status');

      Route::get('/booking-details-and-edit/{id}', 'Vendor\PerahuBookingController@editBookingDetails')->name('vendor.perahu_bookings.booking_details_and_edit');

      Route::get('/booking-details/{id}', 'Vendor\PerahuBookingController@details')->name('vendor.perahu_bookings.booking_details');

      Route::post('/update-booking', 'Vendor\PerahuBookingController@updateBooking')->name('vendor.perahu_bookings.update_booking')->middleware('packageLimitsCheck:hotel,update');

      Route::post('/send-mail', 'Vendor\PerahuBookingController@sendMail')->name('vendor.perahu_bookings.send_mail');

      Route::get('/get-booked-dates', 'Vendor\PerahuBookingController@bookedDates')->name('vendor.perahu_bookings.get_booked_dates');

      Route::get('/{slug}/{id}/get-hourly-price', 'Vendor\PerahuBookingController@getPrice')->name('vendor.perahu_bookings.get_hourly_price');

      Route::get('/{slug}/{id}/get-hourly-price-edit', 'Vendor\PerahuBookingController@getPriceForEdit')->name('vendor.perahu_bookings.get_hourly_price_edit');

      Route::get('/booking-form', 'Vendor\PerahuBookingController@bookingForm')->name('vendor.perahu_bookings.booking_form');

      Route::post('/make-booking', 'Vendor\PerahuBookingController@makeBooking')->name('vendor.perahu_bookings.make_booking')->middleware('packageLimitsCheck:hotel,update');

      // report route
      Route::get('/report', 'Vendor\PerahuBookingController@report')->name('vendor.perahu_bookings.report');
      Route::get('/export-report', 'Vendor\PerahuBookingController@exportReport')->name('vendor.perahu_bookings.export_report');
    }
    );

    // Room Bookings Routes END
  
    //MAil set for recived Mail
    Route::get('/mail-to-vendor', 'Vendor\MAilSetController@mailToVendor')->name('vendor.email_setting.mail_to_admin');
    Route::post('/update-mail-to-vendor', 'Vendor\MAilSetController@updateMailToVendor')->name('vendor.update_mail_to_vendor')->middleware('packageLimitsCheck:hotel,update');

    //profile
    Route::get('dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard');
    
    // Captain Gallery (Big Catch)
    Route::prefix('captain-gallery')->group(function () {
        Route::get('/', 'Vendor\CaptainGalleryController@index')->name('vendor.captain_gallery.index');
        Route::post('/store', 'Vendor\CaptainGalleryController@store')->name('vendor.captain_gallery.store');
        Route::post('/update', 'Vendor\CaptainGalleryController@update')->name('vendor.captain_gallery.update');
        Route::post('/delete/{id}', 'Vendor\CaptainGalleryController@destroy')->name('vendor.captain_gallery.delete');
    });

    Route::get('/change-password', 'Vendor\VendorController@change_password')->name('vendor.change_password');
    Route::post('/update-password', 'Vendor\VendorController@updated_password')->name('vendor.update_password');
    Route::get('/edit-profile', 'Vendor\VendorController@editProfile')->name('vendor.edit.profile');
    Route::post('/profile/update', 'Vendor\VendorController@updateProfile')->name('vendor.update_profile');
    
    // Vendor Verification Wizard Routes
    Route::get('/verify-identity', 'Vendor\VendorController@verifyIdentityWizard')->name('vendor.verify.identity');
    Route::post('/verify-identity/submit', 'Vendor\VendorController@submitVerifyIdentity')->name('vendor.verify.identity.submit');
    Route::get('/logout', 'Vendor\VendorController@logout')->name('vendor.logout');

    // change admin-panel theme (dark/light) route
    Route::post('/change-theme', 'Vendor\VendorController@changeTheme')->name('vendor.change_theme')->withoutMiddleware('Demo');
    /*
    Route::get('/subscription-log', 'Vendor\VendorController@subscriptionLog')->name('vendor.payment_log');

    //vendor package extend route
    Route::get('/package-list', 'Vendor\BuyPlanController@index')->name('vendor.plan.extend.index');
    Route::get('/package/checkout/{package_id}', 'Vendor\BuyPlanController@checkout')->name('vendor.plan.extend.checkout');
    Route::post('/package/checkout', 'Vendor\VendorCheckoutController@checkout')->name('vendor.plan.checkout');

    Route::post('/payment/instructions', 'Vendor\VendorCheckoutController@paymentInstruction')->name('vendor.payment.instructions');


    //checkout payment gateway routes
    Route::prefix('membership')->group(function () {
      Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
      Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
      Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
      Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
      Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
      Route::post('mercadopago/cancel', 'Payment\MercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');

      Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
      Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
      Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
      Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
      Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
      Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');
      Route::post('flutterwave/cancel', 'Payment\FlutterWaveController@cancelPayment')->name('membership.flutterwave.cancel');
      Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
      Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');
      Route::get('anet/cancel', 'Payment\AuthorizeController@cancelPayment')->name('membership.anet.cancel');
      Route::post('/iyzico/notify', 'Payment\IyzicoController@notify')->name('membership.iyzico.notify');
      Route::get('/iyzico/cancle', 'Payment\IyzicoController@iyzicoCancle')->name('membership.iyzico.cancle');
      Route::get('/midtrans/notify', 'Payment\MidtransController@creditCardNotify')->name('membership.midtrans.notify');
      Route::any('/phonepe/notify', 'Payment\PhonepeController@notify')->name('membership.phonepe.notify');
      Route::get('/yoco/notify', 'Payment\YocoController@notify')->name('membership.yoco.notify');
      Route::get('/toyyibpay/notify', 'Payment\ToyyibpayController@notify')->name('membership.toyyibpay.notify');
      Route::post('/paytabs/notify', 'Payment\PaytabsController@notify')->name('membership.paytabs.notify');
      Route::get('/paytabs/cancel', 'Payment\PaytabsController@cancel')->name('membership.paytabs.cancel');
      Route::get('/perfect-money/notify', 'Payment\PerfectMoneyController@notify')->name('membership.perfect_money.notify');
      Route::get('/xendit/notify', 'Payment\XenditController@notify')->name('membership.xendit.notify');

      Route::get('/cancel', 'Vendor\VendorCheckoutController@cancel')->name('membership.cancel');
      Route::get('/offline/success', 'FrontEnd\CheckoutController@offlineSuccess')->name('membership.offline.success');
      Route::get('/trial/success', 'FrontEnd\CheckoutController@trialSuccess')->name('membership.trial.success');
      Route::get('/online/success', 'Vendor\VendorCheckoutController@onlineSuccess')->name('success.page');
    }
    );
    */

    // ====================== withdraw =================
  
    Route::prefix('withdraw')->group(function () {
      Route::get('/', 'Vendor\VendorWithdrawController@index')->name('vendor.withdraw');
      Route::get('/create', 'Vendor\VendorWithdrawController@create')->name('vendor.withdraw.create');
      Route::get('/get-method/input/{id}', 'Vendor\VendorWithdrawController@get_inputs');

      Route::get('/balance-calculation/{method}/{amount}', 'Vendor\VendorWithdrawController@balance_calculation');

      Route::post('/send-request', 'Vendor\VendorWithdrawController@send_request')->name('vendor.withdraw.send-request')->middleware('packageLimitsCheck:hotel,update');
      Route::post('/witdraw/bulk-delete', 'Vendor\VendorWithdrawController@bulkDelete')->name('vendor.witdraw.bulk_delete_withdraw');
      Route::post('/witdraw/delete', 'Vendor\VendorWithdrawController@Delete')->name('vendor.witdraw.delete_withdraw');
    }
    );

    Route::get('/transcation', 'Vendor\VendorController@transcation')->name('vendor.transcation');

    #====support tickets ============
    Route::get('support/ticket/create', 'Vendor\SupportTicketController@create')->name('vendor.support_ticket.create');
    Route::post('support/ticket/store', 'Vendor\SupportTicketController@store')->name('vendor.support_ticket.store');
    Route::get('support/tickets', 'Vendor\SupportTicketController@index')->name('vendor.support_tickets');
    Route::get('support/message/{id}', 'Vendor\SupportTicketController@message')->name('vendor.support_tickets.message');
    Route::post('support-ticket/reply/{id}', 'Vendor\SupportTicketController@ticketreply')->name('vendor.support_ticket.reply');

    Route::post('support-ticket/delete/{id}', 'Vendor\SupportTicketController@delete')->name('vendor.support_tickets.delete');

    // Perahu Review Routes
    Route::get('/perahu/reviews', 'Vendor\ReviewController@index')->name('vendor.perahu.reviews');
    Route::post('/perahu/reviews/analyze-sentiment', 'Vendor\ReviewController@analyzeSentiment')->name('vendor.perahu.review.analyze');
    Route::post('/perahu/review/{id}/reply', 'Vendor\ReviewController@replyToRoomReview')->name('vendor.perahu.review.reply');

    // Secure Document Access
    Route::get('/documents/{filename}', 'Vendor\VendorController@showDocument')->name('vendor.document.show');

    // Collaborator Management
    // Route::get('/collaborators', 'Vendor\CollaboratorController@index')->name('vendor.collaborators.index');
    // Route::post('/collaborators/store', 'Vendor\CollaboratorController@store')->name('vendor.collaborators.store');
    // Route::post('/collaborators/{id}/destroy', 'Vendor\CollaboratorController@destroy')->name('vendor.collaborators.destroy');

    // Live Chat Routes for Vendor
    Route::get('/chat/inbox', 'Vendor\VendorChatController@vendorInbox')->name('vendor.chat.inbox');
    Route::get('/chat/messages/{chat_id}', 'Vendor\VendorChatController@fetchMessages')->name('vendor.chat.messages');
    Route::post('/chat/send-message/{chat_id}', 'Vendor\VendorChatController@sendMessage')->name('vendor.chat.send_message');

    // User Review Routes for Vendor
    Route::post('/review/user/store', 'Vendor\ReviewController@storeUserReview')->name('vendor.review.user.store');

    // Smart AI Integration Routes
    Route::prefix('smart-ai')->group(function () {
        Route::post('/generate-description', 'SmartAiController@generateDescription')->name('vendor.smart_ai.generate_description');
        Route::post('/generate-reply', 'SmartAiController@generateReply')->name('vendor.smart_ai.generate_reply');
    });
  });