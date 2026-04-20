/* Custom Styles for Trenmart */
body { 
    font-family: 'Poppins', sans-serif; 
    background-color: #fcfcfc; 
}

/* --- NAVBAR STYLING --- */
.navbar-brand img {
    max-height: 40px;
    width: auto;
}

.nav-link {
    font-size: 15px;
    color: #333 !important; /* Warna default menu lain */
    transition: 0.3s;
    font-weight: 400;
}

/* Kunci utama: Saat menu AKTIF (sedang dibuka) */
.nav-link.active {
    color: #800000 !important; /* Berubah Merah Maroon */
    font-weight: 700 !important; /* Berubah Bold */
}

.search-bar { 
    border-radius: 20px 0 0 20px; 
    background-color: #f3f3f3; 
    border: none; 
}

.btn-search { 
    border-radius: 0 20px 20px 0; 
    background-color: #800000; 
    color: white; 
    border: none;
}

/* --- BANNER CAROUSEL --- */
#heroCarousel .carousel-item img { 
    height: 400px; 
    object-fit: cover; 
    border-radius: 20px; 
}

/* --- CARD PRODUK --- */
.card-produk { 
    border: none; 
    border-radius: 15px; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
    transition: 0.3s;
}

.btn-tambah { 
    border-radius: 20px; 
    background-color: #800000; 
    color: white;
    font-size: 12px;
    width: 85%;
    border: none;
    padding: 6px 0;
}

.text-maroon { color: #800000 !important; }
.nav-link.active { color: #800000 !important; font-weight: bold; }
.btn-tambah { background-color: #800000; color: white; border-radius: 20px; }
.btn-tambah:hover { background-color: #600000; color: white; }
.card-produk { transition: 0.3s; }
.card-produk:hover { transform: translateY(-5px); }