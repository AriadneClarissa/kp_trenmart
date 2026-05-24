<?php $__env->startPush('styles'); ?>
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
        --text-accent: #800000;
    }
    /* Background & Font */
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    .main-container { padding-top: 15px !important; }

    .card-custom { border-radius: 15px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.04); background: white; margin-bottom: 20px; }

    /* UI Transfer Bank */
    .payment-option {
        border: 1.5px solid #eee;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
        background: #fff;
        margin-bottom: 12px;
    }

    .payment-option.active {
        border-color: var(--maroon-trenmart);
        background: #fffafa;
    }

    .bank-logo-wrapper {
        width: 45px;
        height: 45px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--maroon-trenmart);
    }

    /* Panel Nomor Rekening */
    .account-details {
        display: none;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        border: 1px solid #eee;
    }

    .payment-option.active .account-details {
        display: block;
    }

    .dashed-line {
        border-top: 1px dashed #ddd;
        margin: 12px 0;
    }

    .copy-btn {
        background: var(--maroon-trenmart);
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: 0.2s;
    }

    .custom-radio-dot {
        width: 22px;
        height: 22px;
        border: 2px solid #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-option.active .custom-radio-dot { border-color: var(--maroon-trenmart); }
    .payment-option.active .custom-radio-dot::after {
        content: "";
        width: 12px;
        height: 12px;
        background: var(--maroon-trenmart);
        border-radius: 50%;
    }

    .delivery-option {
        border: 1.5px solid #eee;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background: #fff;
        margin-bottom: 12px;
    }

    .delivery-option.active {
        border-color: var(--maroon-trenmart);
        background: #fffafa;
    }

    .address-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
    }

    .address-autocomplete-wrap {
        position: relative;
    }

    .address-suggestion-list {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        z-index: 99999 !important;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        max-height: 240px;
        overflow-y: auto;
        display: none;
    }

    .address-suggestion-item {
        width: 100%;
        border: 0;
        background: transparent;
        text-align: left;
        padding: 10px 12px;
        font-size: 13px;
        color: #111827;
        border-bottom: 1px solid #f1f5f9;
    }

    .address-suggestion-item:hover {
        background: #f8fafc;
    }

    .address-suggestion-item:last-child {
        border-bottom: 0;
    }

    .geolocation-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--maroon-trenmart);
        cursor: pointer;
        font-size: 1.2rem;
        z-index: 20;
        padding: 6px;
        transition: all 0.2s;
    }

    .search-btn {
        position: absolute;
        right: 44px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--maroon-trenmart);
        cursor: pointer;
        font-size: 1.05rem;
        z-index: 20;
        padding: 6px;
        transition: all 0.2s;
    }

    .search-btn.loading {
        animation: spin 1s linear infinite;
    }

    .geolocation-btn:hover {
        transform: translateY(-50%) scale(1.15);
        color: #600000;
    }

    .geolocation-btn.loading {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: translateY(-50%) rotate(0deg); }
        to { transform: translateY(-50%) rotate(360deg); }
    }

    .geolocation-btn.success {
        color: #28a745;
    }

    .geolocation-status {
        font-size: 11px;
        color: #6b7280;
        margin-top: 4px;
        display: none;
    }

    .geolocation-status.show {
        display: block;
    }

    .address-input-wrapper {
        position: relative;
    }

    /* Sticky Sidebar */
    .summary-card { 
        background: white; 
        border-radius: 18px; 
        padding: 24px; 
        position: sticky; 
        top: 20px; 
        border: none; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .btn-checkout-custom { 
        background: var(--maroon-trenmart); 
        color: white !important; 
        border-radius: 12px; 
        padding: 16px; 
        width: 100%; 
        font-weight: 700; 
        border: none; 
        transition: 0.3s; 
        display: flex; 
        justify-content: center; 
        align-items: center;
        text-decoration: none;
    }
    .btn-checkout-custom:hover { background: #600000; transform: translateY(-2px); }
    .text-accent { color: var(--maroon-trenmart); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container main-container pb-5">
    
    <div class="mb-4">
        <a href="<?php echo e(route('cart.index')); ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Keranjang
        </a>
        <h3 class="fw-bold mt-2"><i class="bi bi-wallet2 me-2"></i>Pembayaran</h3>
    </div>

    <div class="row g-4">
        
        
        <div class="col-lg-8">
            <form action="<?php echo e(route('checkout.place_order')); ?>" method="POST" id="payment-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="shipping_cost" id="shipping_cost" value="<?php echo e($shippingPreview['shipping_cost'] ?? 0); ?>">
                <input type="hidden" name="shipping_distance_km" id="shipping_distance_km" value="<?php echo e($shippingPreview['distance_km'] ?? ''); ?>">
                <input type="hidden" name="shipping_lat" id="shipping_lat" value="">
                <input type="hidden" name="shipping_lon" id="shipping_lon" value="">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold m-0">Pilih Metode Transfer Bank</h6>
                    </div>
                    
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="payment-option <?php echo e($loop->first ? 'active' : ''); ?>" onclick="selectBank(this, 'pm<?php echo e($method->id); ?>')">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bank-logo-wrapper me-3">
                                    <?php echo e(strtoupper(substr($method->name, 0, 3))); ?>

                                </div>
                                <div>
                                    <div class="fw-bold small">Transfer <?php echo e($method->name); ?></div>
                                </div>
                            </div>
                            
                            <input type="radio" name="payment_method_id" id="pm<?php echo e($method->id); ?>" value="<?php echo e($method->id); ?>" 
                                   class="d-none" <?php echo e($loop->first ? 'checked' : ''); ?>>
                            
                            <div class="custom-radio-dot"></div>
                        </div>

                        <div class="account-details">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Nomor Rekening:</div>
                                    <div class="fw-bold text-dark fs-5" id="num-<?php echo e($method->id); ?>" style="letter-spacing: 1px;">
                                        <?php echo e($method->account_number); ?>

                                    </div>
                                    <div class="small text-muted mt-1">
                                        a/n <?php echo e($method->account_name ?? 'Data tidak tersedia'); ?>

                                    </div>
                                </div>
                                <button type="button" class="copy-btn" onclick="copyText('num-<?php echo e($method->id); ?>', this)">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="card card-custom p-4 mt-3">
                    <h6 class="fw-bold mb-3">Metode Pengiriman</h6>
                    <div class="delivery-option active d-flex align-items-center justify-content-between mb-2" data-method="delivery">
                        <div>
                            <div class="fw-bold">Diantar ke Alamat</div>
                            <div class="small text-muted">Ongkir dihitung berdasarkan jarak dari toko</div>
                        </div>
                        <input type="radio" name="pickup_method" value="delivery" checked>
                    </div>
                    <div class="delivery-option d-flex align-items-center justify-content-between" data-method="pickup">
                        <div>
                            <div class="fw-bold">Ambil di Toko</div>
                            <div class="small text-muted">Tanpa ongkir</div>
                        </div>
                        <input type="radio" name="pickup_method" value="pickup">
                    </div>

                    <div class="mt-3" id="address-box">
                        <label class="form-label small fw-bold text-muted">Alamat Pengiriman</label>
                        <div class="address-input-wrapper">
                            <div class="address-autocomplete-wrap">
                                <input type="text" name="shipping_address" id="shipping_address" class="form-control" autocomplete="off" placeholder="Masukkan alamat lengkap rumah / tujuan pengiriman" value="<?php echo e(old('shipping_address', $customerAddress ?? auth()->user()->home_address)); ?>">
                                <button type="button" id="search-address-btn" class="search-btn" title="Cari alamat yang diketik">
                                    <i class="bi bi-search"></i>
                                </button>
                                <button type="button" id="geolocation-btn" class="geolocation-btn" title="Gunakan lokasi saat ini">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </button>
                                <div id="address_suggestion_list" class="address-suggestion-list"></div>
                            </div>
                            <div class="geolocation-status" id="geolocation-status"></div>
                        </div>
                        <div class="address-hint" id="address-hint">Gunakan tombol lokasi <i class="bi bi-geo-alt-fill"></i> atau ketik minimal 3 huruf untuk rekomendasi alamat.</div>
                        <small class="text-muted d-block mt-2">Alamat toko: <?php echo e($storeAddress); ?></small>
                        <small class="text-muted d-block">Jika jarak di bawah 5 km, ongkir gratis.</small>
                    </div>
                </div>
            </form>
        </div>

        
        <div class="col-lg-4">
            <div class="summary-card">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                
                <div class="cart-items-preview mb-3">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Tentukan nama dan harga berdasarkan tipe (Reguler/Bundling)
                            $namaProduk = $item->bundling_id ? $item->bundling->name : ($item->produk->nama_produk ?? 'Produk');
                            $hargaSatuan = $item->bundling_id ? $item->bundling->bundling_price : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
                        ?>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span class="text-truncate" style="max-width: 180px;"><?php echo e($namaProduk); ?> ×<?php echo e($item->jumlah); ?></span>
                            <span>Rp <?php echo e(number_format($hargaSatuan * $item->jumlah, 0, ',', '.')); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-muted">
                    <span>Ongkos Kirim</span>
                    <span class="fw-bold text-dark" id="shipping-cost-label">Rp <?php echo e(number_format($shippingPreview['shipping_cost'] ?? 0, 0, ',', '.')); ?></span>
                </div>
                <div class="mb-3">
                    <div class="small text-muted" id="shipping-distance-text">Jarak: <?php echo e(isset($shippingPreview['distance_km']) && $shippingPreview['distance_km'] !== null ? number_format($shippingPreview['distance_km'], 2, ',', '.') . ' km' : '-'); ?></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0" id="total-pay-label">Rp <?php echo e(number_format($total + ($shippingPreview['shipping_cost'] ?? 0), 0, ',', '.')); ?></h4>
                </div>

                <button type="button" onclick="submitPaymentForm()" class="btn-checkout-custom shadow-sm">
                    Konfirmasi Pesanan <i class="bi bi-chevron-right ms-2"></i>
                </button>
                
                <div class="mt-3 text-center">
                    <p class="donation-text mb-0"><i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terverifikasi</p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    console.log('✅ Script loaded');
    
    // ===== GLOBAL VARS =====
    let storeCoordinates = null;
    let userCoordinates = null;
    let autocompleteTimer;
    let lastSuggestions = [];

    // ===== SELECT BANK =====
    function selectBank(element, inputId) {
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
        element.classList.add('active');
        document.getElementById(inputId).checked = true;
    }

    // ===== SIMPLE FETCH ADDRESS SUGGESTIONS =====
    async function fetchAddressSuggestions(query) {
        console.log('🔍 fetchAddressSuggestions called with:', query);
        
        if (!query || query.trim().length < 3) {
            console.log('⚠️ Query too short');
            hideSuggestionList();
            return;
        }

        try {
            const url = `<?php echo e(route('checkout.address_suggestions')); ?>?q=${encodeURIComponent(query)}`;
            console.log('📡 Fetching server suggestions:', url);

            const response = await fetch(url);
            const data = await response.json();

            console.log('✅ Server response:', data);

            if (data.success && Array.isArray(data.suggestions) && data.suggestions.length > 0) {
                console.log('📍 Got', data.suggestions.length, 'server suggestions');
                renderSuggestions(data.suggestions);
                return data.suggestions;
            }

            console.warn('⚠️ Server returned no suggestions, trying client-side Nominatim fallback');

            // Client-side fallback directly to Nominatim (helps when server-side fails or is blocked)
            const fallbackUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=jsonv2&limit=6&countrycodes=id`;
            try {
                const fbResp = await fetch(fallbackUrl);
                const fbData = await fbResp.json();
                const fbSuggestions = (fbData || []).map(item => ({ label: item.display_name || '', lat: item.lat || null, lon: item.lon || null }));
                if (fbSuggestions.length > 0) {
                    console.log('📡 Fallback got', fbSuggestions.length, 'suggestions');
                    renderSuggestions(fbSuggestions);
                    return fbSuggestions;
                }
            } catch (fbErr) {
                console.warn('Fallback Nominatim error:', fbErr);
            }

            hideSuggestionList();
            return [];
        } catch (error) {
            console.error('❌ Error fetching server suggestions:', error);

            // Try direct client-side Nominatim as last resort
            try {
                const fallbackUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=jsonv2&limit=6&countrycodes=id`;
                const fbResp = await fetch(fallbackUrl);
                const fbData = await fbResp.json();
                const fbSuggestions = (fbData || []).map(item => ({ label: item.display_name || '', lat: item.lat || null, lon: item.lon || null }));
                if (fbSuggestions.length > 0) {
                    renderSuggestions(fbSuggestions);
                    return fbSuggestions;
                }
            } catch (fbErr) {
                console.error('Fallback Nominatim error after server failure:', fbErr);
            }

            hideSuggestionList();
            return [];
        }
    }

    function setShippingCoordinates(lat, lon) {
        const latInput = document.getElementById('shipping_lat');
        const lonInput = document.getElementById('shipping_lon');

        if (latInput) latInput.value = lat ?? '';
        if (lonInput) lonInput.value = lon ?? '';
    }

    // ===== RENDER SUGGESTIONS DROPDOWN =====
    function renderSuggestions(items) {
        const list = document.getElementById('address_suggestion_list');
        const input = document.getElementById('shipping_address');

        if (!list || !input) {
            console.error('❌ DOM elements not found for suggestions');
            return;
        }

        list.innerHTML = '';

        if (!items || items.length === 0) {
            list.style.display = 'none';
            return;
        }

        items.slice(0, 6).forEach((item) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'address-suggestion-item';
            btn.textContent = item.label || 'Alamat';

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                input.value = item.label;
                setShippingCoordinates(item.lat, item.lon);
                hideSuggestionList();
                refreshShippingQuote();
            });

            list.appendChild(btn);
        });

        // Position suggestion box fixed relative to viewport to avoid being clipped
        try {
            const rect = input.getBoundingClientRect();
            list.style.position = 'fixed';
            list.style.left = rect.left + 'px';
            list.style.top = (rect.bottom + 6) + 'px';
            list.style.width = rect.width + 'px';
            list.style.display = 'block';
            list.style.zIndex = '99999';
        } catch (e) {
            // fallback
            list.style.position = 'absolute';
            list.style.left = '0';
            list.style.top = '100%';
            list.style.width = '100%';
            list.style.display = 'block';
            list.style.zIndex = '99999';
        }
    }

    // ===== HIDE SUGGESTIONS =====
    function hideSuggestionList() {
        const list = document.getElementById('address_suggestion_list');
        if (list) {
            list.style.display = 'none';
            list.innerHTML = '';
        }
    }

    // ===== REFRESH SHIPPING QUOTE =====
    async function refreshShippingQuote() {
        const method = document.querySelector('input[name="pickup_method"]:checked')?.value || 'delivery';
        const address = document.getElementById('shipping_address')?.value || '';
        const shippingLat = document.getElementById('shipping_lat')?.value || '';
        const shippingLon = document.getElementById('shipping_lon')?.value || '';
        const shippingLabel = document.getElementById('shipping-cost-label');
        const totalLabel = document.getElementById('total-pay-label');
        const hiddenShipping = document.getElementById('shipping_cost');
        const hiddenDistance = document.getElementById('shipping_distance_km');
        const subtotal = <?php echo e($total); ?>;

        console.log('💰 refreshShippingQuote:', method, 'address:', address.substring(0, 30));

        if (method === 'pickup') {
            shippingLabel.innerText = 'Rp 0';
            totalLabel.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            hiddenShipping.value = 0;
            hiddenDistance.value = '';
                const distTextEl = document.getElementById('shipping-distance-text');
                if (distTextEl) distTextEl.innerText = 'Jarak: -';
            return;
        }

        if (!address.trim()) {
            shippingLabel.innerText = 'Isi alamat dulu';
            totalLabel.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            hiddenShipping.value = 0;
            return;
        }

        try {
            const params = new URLSearchParams({
                pickup_method: method,
                shipping_address: address,
            });

            if (shippingLat && shippingLon) {
                params.set('shipping_lat', shippingLat);
                params.set('shipping_lon', shippingLon);
            }

            const url = `<?php echo e(route('checkout.shipping_quote')); ?>?${params.toString()}`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                const cost = data.shipping_cost || 0;
                const distance = data.distance_km ?? '';
                const total = subtotal + cost;
                shippingLabel.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(cost);
                totalLabel.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                hiddenShipping.value = cost;
                hiddenDistance.value = distance;
                const distTextEl = document.getElementById('shipping-distance-text');
                if (distTextEl) {
                    if (distance !== '' && distance !== null) {
                        // show with 2 decimals and Indonesian decimal separator
                        distTextEl.innerText = 'Jarak: ' + Number(distance).toFixed(2).replace('.', ',') + ' km';
                    } else {
                        distTextEl.innerText = 'Jarak: -';
                    }
                }
            }
        } catch (error) {
            console.error('Shipping quote error:', error);
        }
    }

    // ===== DETECT USER LOCATION =====
    async function detectUserLocation() {
        const btn = document.getElementById('geolocation-btn');
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }
        // Check secure context: geolocation requires HTTPS except on localhost
        const isLocal = location.hostname === 'localhost' || location.hostname === '127.0.0.1';
        if (!location.protocol.startsWith('https') && !isLocal) {
            alert('Geolocation hanya tersedia pada koneksi HTTPS. Silakan buka aplikasi lewat HTTPS atau gunakan localhost.');
            return;
        }

        const statusEl = document.getElementById('geolocation-status');
        if (statusEl) {
            statusEl.classList.add('show');
            statusEl.innerText = 'Mencari lokasi...';
        }

        btn.classList.add('loading');

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                console.log('📍 Got coordinates:', lat, lon);

                // Reverse geocode
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`,
                        { headers: { 'User-Agent': 'TrenmartApp/1.0' } }
                    );
                    const data = await response.json();
                    const address = data.display_name || '';

                    if (address) {
                        document.getElementById('shipping_address').value = address;
                        setShippingCoordinates(lat, lon);
                        if (statusEl) statusEl.innerText = 'Lokasi terdeteksi';
                        btn.classList.remove('loading');
                        btn.classList.add('success');
                        refreshShippingQuote();
                        setTimeout(() => btn.classList.remove('success'), 2000);
                        setTimeout(() => { if (statusEl) statusEl.classList.remove('show'); }, 2500);
                    } else {
                        console.warn('Reverse geocode returned no address');
                        if (statusEl) statusEl.innerText = 'Gagal menentukan alamat dari koordinat';
                        btn.classList.remove('loading');
                    }
                } catch (e) {
                    console.error('Reverse geocode error:', e);
                    if (statusEl) statusEl.innerText = 'Gagal reverse geocode';
                    btn.classList.remove('loading');
                }
            },
            (error) => {
                console.error('Geolocation error:', error);
                btn.classList.remove('loading');
                if (statusEl) {
                    statusEl.innerText = error && error.message ? error.message : 'Gagal mendapatkan lokasi';
                }
                // Provide user-friendly messages for common error codes
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert('Akses lokasi ditolak. Izinkan penggunaan lokasi di browser Anda untuk fitur ini.');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert('Lokasi tidak tersedia. Coba lagi atau gunakan pencarian alamat.');
                        break;
                    case error.TIMEOUT:
                        alert('Permintaan lokasi memakan waktu terlalu lama. Coba lagi.');
                        break;
                    default:
                        alert('Gagal mendapatkan lokasi');
                }
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    // ===== SUBMIT FORM =====
    function submitPaymentForm() {
        const form = document.getElementById('payment-form');
        const selectedBank = document.querySelector('input[name="payment_method_id"]:checked');
        const selectedMethod = document.querySelector('input[name="pickup_method"]:checked')?.value;
        const shippingAddress = document.getElementById('shipping_address')?.value || '';
        
        if (!selectedBank) {
            alert("Pilih metode transfer bank!");
            return;
        }

        if (selectedMethod === 'delivery' && !shippingAddress.trim()) {
            alert('Isi alamat pengiriman!');
            return;
        }

        form.submit();
    }

    // ===== COPY TEXT =====
    function copyText(elementId, btn) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin';
            btn.style.background = '#28a745';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Salin';
                btn.style.background = '';
            }, 2000);
        });
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 DOMContentLoaded - Initializing checkout');
        
        const shippingAddress = document.getElementById('shipping_address');
        const geoBtn = document.getElementById('geolocation-btn');
        const deliveryOptions = document.querySelectorAll('.delivery-option');
        const addressBox = document.getElementById('address-box');

        console.log('Elements found:');
        console.log('  - shippingAddress:', !!shippingAddress);
        console.log('  - geoBtn:', !!geoBtn);
        console.log('  - deliveryOptions:', deliveryOptions.length);
        console.log('  - addressBox:', !!addressBox);

        // Geolocation button
        if (geoBtn) {
            geoBtn.addEventListener('click', (e) => {
                e.preventDefault();
                detectUserLocation();
            });
        }

        // Search button - lookup the typed address and pick the first suggestion
        const searchBtn = document.getElementById('search-address-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                console.log('🔎 search-address-btn clicked');
                const query = shippingAddress.value || '';
                if (!query || query.trim().length < 3) {
                    alert('Ketik minimal 3 huruf alamat untuk mencari.');
                    return;
                }

                searchBtn.classList.add('loading');
                try {
                    // Fetch suggestions and if any, use the first one
                    const suggestions = await fetchAddressSuggestions(query);
                    console.log('🔎 suggestions length:', Array.isArray(suggestions) ? suggestions.length : 'no-array');
                    if (Array.isArray(suggestions) && suggestions.length > 0) {
                        const first = suggestions[0];
                        shippingAddress.value = first.label || query;
                        hideSuggestionList();
                        refreshShippingQuote();
                    } else {
                        alert('Tidak menemukan alamat yang cocok. Coba ubah kata kunci.');
                    }
                } catch (err) {
                    console.error('Error during address search click handler:', err);
                    alert('Terjadi kesalahan saat mencari alamat. Cek console untuk detail.');
                } finally {
                    searchBtn.classList.remove('loading');
                }
            });
        }

        // Allow Enter to trigger search
        if (shippingAddress) {
            shippingAddress.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const btn = document.getElementById('search-address-btn');
                    if (btn) btn.click();
                }
            });

            shippingAddress.addEventListener('input', () => {
                // If user changes the text manually after picking a suggestion, clear stale coordinates.
                setShippingCoordinates('', '');
            }, { passive: true });
        }

        // Delivery option toggles
        deliveryOptions.forEach(option => {
            option.addEventListener('click', function() {
                deliveryOptions.forEach(el => el.classList.remove('active'));
                this.classList.add('active');
                
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                if (addressBox) {
                    addressBox.style.display = radio.value === 'delivery' ? 'block' : 'none';
                }
                
                if (radio.value !== 'delivery') {
                    hideSuggestionList();
                }
                
                refreshShippingQuote();
            });
        });

        // Input field - address autocomplete
        if (shippingAddress) {
            shippingAddress.addEventListener('input', () => {
                clearTimeout(autocompleteTimer);
                const query = shippingAddress.value;
                
                if (query.trim().length >= 3) {
                    autocompleteTimer = setTimeout(() => {
                        fetchAddressSuggestions(query);
                    }, 300);
                } else {
                    hideSuggestionList();
                }

                refreshShippingQuote();
            });

            shippingAddress.addEventListener('focus', () => {
                if (shippingAddress.value.trim().length >= 3) {
                    fetchAddressSuggestions(shippingAddress.value);
                }
            });
        }

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            const wrap = document.querySelector('.address-autocomplete-wrap');
            if (wrap && !wrap.contains(e.target)) {
                hideSuggestionList();
            }
        });

        refreshShippingQuote();
        console.log('✅ Checkout initialized');
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/checkout/select_payment.blade.php ENDPATH**/ ?>