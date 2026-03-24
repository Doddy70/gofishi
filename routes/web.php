<?php

use Illuminate\Support\Facades\Route;

Route::get('/set-locale', 'FrontEnd\MiscellaneousController@setLanguage')->name('change_language');

Route::get('/agent-update-blogs', function () {
    $blogs = \App\Models\Journal\Blog::orderBy('id', 'asc')->take(3)->get();
    if($blogs->count() < 1) return response()->json(['error' => 'No blogs found']);

    $data = [
        [
            'image' => 'fishing_spot.png',
            'title' => 'Rahasia Spot Mancing Terbaik di Kepulauan Seribu',
            'slug' => \Illuminate\Support\Str::slug('Rahasia Spot Mancing Terbaik di Kepulauan Seribu'),
            'content' => '<p class="lead" style="font-size: 1.1rem; color: #555;">Jangan buang waktu Anda mencoba spot yang tidak pasti. Kepulauan Seribu menyembunyikan surga memancing yang hanya diketahui oleh kapten lokal berpengalaman.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">Mengapa Pemancing Pro Memilih Kepulauan Seribu?</h3>
<p>Dari terumbu karang dangkal hingga perairan dalam yang kaya akan ikan pelagis, Kepulauan Seribu menawarkan variasi tarikan yang mendebarkan. Anda bisa menargetkan Giant Trevally (GT), Tenggiri, hingga Kerapu monster.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">Fasilitas Kapal Pancing yang Wajib Ada</h3>
<ul style="list-style-type: disc; margin-left: 20px;">
<li style="margin-bottom: 8px;"><strong>Fish Finder &amp; GPS:</strong> Kapal modern di Gofishi dilengkapi sonar untuk mendeteksi pergerakan ikan di bawah laut.</li>
<li style="margin-bottom: 8px;"><strong>Rod Holder &amp; Live Bait Tank:</strong> Kenyamanan bertarung dengan ikan besar sangat bergantung pada tata letak kapal.</li>
<li style="margin-bottom: 8px;"><strong>Kapten Berpengalaman:</strong> Memahami arus dan jadwal pasang surut adalah kunci.</li>
</ul>
<blockquote style="border-left: 4px solid #FF385C; padding-left: 15px; font-style: italic; margin-top: 20px;">"Pengalaman memancing yang sesungguhnya bukan hanya soal alat, tapi soal bersama siapa Anda pergi."</blockquote>
<h3 class="mt-5 mb-2" style="font-weight:bold;">Siap Memecahkan Rekor Tangkapan Anda?</h3>
<p>Jangan biarkan akhir pekan Anda berlalu tanpa tarikan. Kami telah mengkurasi kapal pancing terbaik di Jakarta dengan kapten yang hafal seluk-beluk Kepulauan Seribu.</p>
<div style="margin-top: 25px;"><a href="/perahu" style="background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">Lihat Kapal Pancing Tersedia</a></div>'
        ],
        [
            'image' => 'luxury_yacht.png',
            'title' => 'Cara Cerdas Menyewa Yacht Mewah di Jakarta untuk Acara Spesial',
            'slug' => \Illuminate\Support\Str::slug('Cara Cerdas Menyewa Yacht Mewah di Jakarta untuk Acara Spesial'),
            'content' => '<p class="lead" style="font-size: 1.1rem; color: #555;">Bosan dengan perayaan di hotel bintang lima? Menyewa yacht pribadi menyajikan level eksklusivitas yang tidak bisa didapatkan di tempat lain. Baik untuk ulang tahun, <i>anniversary</i>, maupun pesta perusahaan.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">Keistimewaan Merayakan di Atas Yacht</h3>
<p>Ketenangan laut dipasangkan dengan kemewahan fasilitas modern. Dari dek berjemur (sundeck) yang luas, interior ber-AC, hingga layanan katering kelas premium. Anda akan menikmati pemandangan matahari terbenam (sunset) terbaik di Teluk Jakarta secara privat.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">3 Tips Memilih Yacht yang Tepat</h3>
<ol style="list-style-type: decimal; margin-left: 20px;">
<li style="margin-bottom: 8px;"><strong>Sesuaikan dengan Jumlah Tamu:</strong> Pastikan ruang gerak nyaman. Yacht yang terlalu padat akan mengurangi kesan mewah.</li>
<li style="margin-bottom: 8px;"><strong>Cek Fasilitas Entertainment:</strong> Apakah ada sound system premium? Bagaimana dengan dapur dan toiletnya?</li>
<li style="margin-bottom: 8px;"><strong>Pilih Kru Profesional:</strong> Hospitality dari kru akan sangat menentukan kesuksesan acara Anda.</li>
</ol>
<p>Di Gofishi, kami memastikan setiap yacht yang terdaftar telah melewati standar kelayakan dan pelayanan eksklusif.</p>
<h3 class="mt-5 mb-2" style="font-weight:bold;">Wujudkan Momen Tak Terlupakan Anda</h3>
<p>Amankan tanggal Anda sebelum kehabisan. Yacht mewah sangat diminati saat menjelang akhir pekan dan libur panjang.</p>
<div style="margin-top: 25px;"><a href="/perahu" style="background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">Cek Harga Sewa Yacht</a></div>'
        ],
        [
            'image' => 'speedboat.png',
            'title' => 'Sensasi Speedboat Manta Ray: Cepat, Aman, dan Mengguncang Adrenalin',
            'slug' => \Illuminate\Support\Str::slug('Sensasi Speedboat Manta Ray: Cepat, Aman dan Mengguncang Adrenalin'),
            'content' => '<p class="lead" style="font-size: 1.1rem; color: #555;">Waktu liburan Anda terlalu berharga untuk dihabiskan dalam perjalanan yang lambat. Speedboat bermesin ganda kami siap memotong waktu tempuh Anda ke pulau impian hingga separuhnya.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">Mengapa Memilih Speedboat Modern?</h3>
<p>Dirancang untuk membelah ombak dengan mulus, speedboat kelas atas ini menggunakan mesin berkekuatan ganda yang stabil. Sangat cocok bagi Anda (dan keluarga) yang mudah mabuk laut, karena waktu tempuh yang super singkat.</p>
<h3 class="mt-4 mb-2" style="font-weight:bold;">Spesifikasi yang Akan Anda Cintai:</h3>
<ul style="list-style-type: disc; margin-left: 20px;">
<li style="margin-bottom: 8px;"><strong>Kecepatan Maksimal:</strong> Menghemat hingga 40% waktu perjalanan dibanding kapal reguler.</li>
<li style="margin-bottom: 8px;"><strong>Kabin Nyaman:</strong> Dilengkapi tempat duduk ergonomis yang mengurangi benturan ombak.</li>
<li style="margin-bottom: 8px;"><strong>Standar Keselamatan Internasional:</strong> Navigasi modern, radio komunikasi vhf, dan <i>life jacket</i> berkualitas untuk setiap penumpang.</li>
</ul>
<h3 class="mt-5 mb-2" style="font-weight:bold;">Jangan Mau Menunggu Lama di Dermaga</h3>
<p>Rencanakan perjalanan <i>island hopping</i> Anda berikutnya dengan penuh gaya dan kecepatan maksimal. Hindari antrean panjang kapal feri umum.</p>
<div style="margin-top: 25px;"><a href="/perahu" style="background-color: #FF385C; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block;">Sewa Speedboat Sekarang</a></div>'
        ]
    ];

    $updated = [];
    foreach($blogs as $index => $blog) {
        if (!isset($data[$index])) break;
        
        $blog->update(['image' => $data[$index]['image']]);
        
        \App\Models\Journal\BlogInformation::where('blog_id', $blog->id)->update([
            'title' => $data[$index]['title'],
            'slug' => $data[$index]['slug'],
            'content' => $data[$index]['content']
        ]);
        
        $updated[] = $data[$index]['title'];
    }

    return response()->json(['success' => true, 'updated_blogs' => $updated]);
});

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

      // Pay Later Routes
      Route::get('/booking/{id}/pay-later/midtrans', 'FrontEnd\User\PayLaterController@midtrans')->name('user.perahu_booking.pay_later.midtrans');
      Route::get('/booking/pay-later/midtrans/notify', 'FrontEnd\User\PayLaterController@midtransNotify')->name('user.perahu_booking.pay_later.midtrans.notify');
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
  Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');
  
  // Custom Pages (Must be at the end to avoid clashing with other top-level routes)
  Route::get('/{slug}', 'FrontEnd\PageController@page')->name('frontend.custom_page');
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

Route::fallback(function () {
    return view('errors.404');
})->middleware(['web', 'change.lang']);
