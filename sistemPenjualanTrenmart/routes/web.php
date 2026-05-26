<?php
use App\Models\User;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\KatalogController; 
use App\Http\Controllers\TentangController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\BundlingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminShippingSettingController;
use App\Http\Controllers\StorageProxyController;

// --- 1. HALAMAN PUBLIK ---
Route::get('/', [ProdukController::class, 'index'])->name('beranda');

// Proxy route to serve storage/app/public files when public/storage is missing
Route::get('/storage-proxy/{path}', [StorageProxyController::class, 'show'])->where('path', '.*');
Route::get('/bundling/{id}', [BundlingController::class, 'show'])->name('bundling.show'); 
Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');
Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang');

// --- 2. SISTEM AUTENTIKASI GUEST (LOGIN CUSTOMER) ---
Route::middleware(['guest'])->group(function () {
    // Jalur Customer
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Lupa Password untuk customer umum/langganan
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // JALUR KHUSUS ADMIN (Alias ke login utama)
    Route::get('/internal-trenmart-admin', function () { return redirect()->route('login'); })->name('admin.login');
    Route::post('/internal-trenmart-admin', [AuthController::class, 'login']);

    // Google Login
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// --- 3. SISTEM AUTH (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- ALUR PROFIL SETELAH LOGIN GOOGLE ---
    Route::get('/lengkapi-profil/umum', [AuthController::class, 'formUmum'])->name('form.umum');
    Route::post('/update-profil-awal', [AuthController::class, 'updateProfileAfterGoogle'])->name('profile.initial.update');

    // --- FITUR PELANGGAN ---
    Route::get('/dashboard', function () {
        if (Auth::check() && Auth::user()->isOwner()) {
            return app(\App\Http\Controllers\AdminUserController::class)->index();
        }

        return redirect()->route('beranda');
    })->name('dashboard');
    Route::get('/pesanan', [\App\Http\Controllers\OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/selesai', [\App\Http\Controllers\OrderController::class, 'markAsCompleted'])->name('pesanan.complete');
    Route::post('/pesanan/{order}/message', [\App\Http\Controllers\OrderMessageController::class, 'store'])->name('orders.messages.store');
    Route::post('/notifikasi/tandai-semua', [NotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profile.edit');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profil/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
    

    // --- FITUR KERANJANG ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
    Route::put('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'destroy'])->name('cart.remove');
        Route::delete('/keranjang/hapus-semua', [KeranjangController::class, 'clearAll'])->name('cart.clear');
    Route::get('/cart/sidebar-content', [ProdukController::class, 'getSidebarContent'])->name('cart.sidebar.content');
    Route::post('/keranjang/tambah/{id}/{type?}', [KeranjangController::class, 'store'])->name('cart.add');

    // --- CHECKOUT & PEMBAYARAN ---
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/shipping-quote', [\App\Http\Controllers\CheckoutController::class, 'shippingQuote'])->name('checkout.shipping_quote');
    Route::post('/checkout/place-order', [\App\Http\Controllers\CheckoutController::class, 'placeOrder'])->name('checkout.place_order');
    Route::get('/checkout/{order}/upload-proof', [\App\Http\Controllers\CheckoutController::class, 'uploadProof'])->name('checkout.upload_proof');
    Route::post('/checkout/{order}/store-proof', [\App\Http\Controllers\CheckoutController::class, 'storeProof'])->name('checkout.store_proof');
    Route::get('/checkout/{order}/waiting', [\App\Http\Controllers\CheckoutController::class, 'waiting'])->name('checkout.waiting');
    

    // --- 4. GRUP KHUSUS ADMIN (Hanya Bisa Diakses Role Admin) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', function () {
            abort_unless(Auth::check() && Auth::user()->isOwner(), 403);

            return app(\App\Http\Controllers\AdminUserController::class)->index();
        })->name('admin.dashboard');
        Route::get('/users/internal', [\App\Http\Controllers\AdminUserController::class, 'internalUsers'])->name('admin.users.internal');
        
        // Pengaturan Tampilan & Search
        Route::get('/search-produk-ajax', [ProdukController::class, 'searchAjax'])->name('admin.produk.search_ajax');

        // Manajemen Produk
        Route::get('/produk', [ProdukController::class, 'produkIndex'])->name('produk.index');
        Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/edit/{kd_produk}', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/update/{kd_produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/hapus/{kd_produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        // Manajemen Kategori & Merk
        Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
        Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        Route::post('/merk/simpan', [MerkController::class, 'store'])->name('merk.store');
        Route::delete('/merk/hapus/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');
        Route::post('/satuan/simpan', [\App\Http\Controllers\SatuanController::class, 'store'])->name('satuan.store');
        Route::delete('/satuan/hapus/{id}', [\App\Http\Controllers\SatuanController::class, 'destroy'])->name('satuan.destroy');
        Route::put('/tentang/update', [TentangController::class, 'update'])->name('admin.tentang.update');
        Route::post('/produk/update-status', [ProdukController::class, 'updateStatus'])->name('produk.updateStatus');
        Route::get('/', [KatalogController::class, 'index'])->name('admin.beranda');

        // Ubah Banner
        Route::post('/banner/update', [AuthController::class, 'updateBanner'])->name('admin.banner.update');

        // Admin - Metode Pembayaran
        Route::get('/payment-methods', [\App\Http\Controllers\AdminPaymentMethodController::class, 'index'])->name('admin.payment_methods.index');
        Route::post('/payment-methods', [\App\Http\Controllers\AdminPaymentMethodController::class, 'store'])->name('admin.payment_methods.store');
        Route::delete('/payment-methods/{id}', [\App\Http\Controllers\AdminPaymentMethodController::class, 'destroy'])->name('admin.payment_methods.destroy');

        // Admin - Orders
        Route::get('/orders', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/confirm', [\App\Http\Controllers\AdminOrderController::class, 'confirmPayment'])->name('admin.orders.confirm');
        Route::post('/orders/{id}/reject', [\App\Http\Controllers\AdminOrderController::class, 'rejectPayment'])->name('admin.orders.reject');
        Route::post('/orders/{id}/status', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');

        // Admin - Users & Customers
        Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [\App\Http\Controllers\AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [\App\Http\Controllers\AdminUserController::class, 'store'])->name('admin.users.store');
        Route::post('/users/{id}/toggle-active', [\App\Http\Controllers\AdminUserController::class, 'toggleInternalUserActiveState'])->name('admin.users.toggle_active');
        Route::get('/customers', [\App\Http\Controllers\AdminUserController::class, 'customers'])->name('admin.customers.index');

        // Admin - Create Admin Account
        Route::get('/admins/create', [\App\Http\Controllers\AdminUserController::class, 'createAdmin'])->name('admin.admins.create');
        Route::post('/admins', [\App\Http\Controllers\AdminUserController::class, 'storeAdmin'])->name('admin.admins.store');

        // Admin - Bundling
        Route::get('/admin/manage-bundling', [BundlingController::class, 'create'])->name('bundling.create');
        Route::post('/admin/manage-bundling', [BundlingController::class, 'store'])->name('bundling.store');
        Route::get('/bundling/search-ajax', [BundlingController::class, 'searchAjax'])->name('bundling.search_ajax');

        // Admin - Reports (weekly/monthly)
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/monthly', [\App\Http\Controllers\ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/weekly', [\App\Http\Controllers\ReportController::class, 'weekly'])->name('reports.weekly');
        
        // Printable and PDF exports
        Route::get('/reports/monthly/print', [\App\Http\Controllers\ReportController::class, 'printMonthly'])->name('reports.monthly.print');
        Route::get('/reports/monthly/pdf', [\App\Http\Controllers\ReportController::class, 'pdfMonthly'])->name('reports.monthly.pdf');
        Route::get('/reports/weekly/print', [\App\Http\Controllers\ReportController::class, 'printWeekly'])->name('reports.weekly.print');
        Route::get('/reports/weekly/pdf', [\App\Http\Controllers\ReportController::class, 'pdfWeekly'])->name('reports.weekly.pdf');
        Route::get('/logs', [AdminLogController::class, 'index'])->name('admin.logs.index');

        // Toggle hidden/unhide
        Route::post('/kategori/toggle-hidden', [KategoriController::class, 'toggleHidden'])->name('kategori.toggle');
        Route::post('/merk/toggle-hidden', [MerkController::class, 'toggleHidden'])->name('merk.toggle');
        Route::post('/satuan/toggle-hidden', [SatuanController::class, 'toggleHidden'])->name('satuan.toggle');

        // Shipping Settings
        Route::get('/shipping', [AdminShippingSettingController::class, 'edit'])->name('admin.shipping.edit');
        Route::post('/shipping', [AdminShippingSettingController::class, 'update'])->name('admin.shipping.update');
    });
});
