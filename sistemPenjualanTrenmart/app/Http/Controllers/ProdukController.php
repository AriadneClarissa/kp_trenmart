<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\BerandaSetting;
use App\Models\User;
use App\Models\Bundling;
use App\Models\Order;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ActivityLog;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda utama
     */
    public function index()
    {
        $settings = BerandaSetting::all()->pluck('value', 'key'); 
        $produk_terbaru = Produk::where('status', 'aktif')
        ->whereHas('kategori', function($q) { $q->where('is_hidden', 0); })
        ->whereHas('merk', function($q) { $q->where('is_hidden', 0); })
        ->whereHas('satuan', function($q) { $q->where('is_hidden', 0); })
        ->latest()
        ->take(8)
        ->get();
        
        foreach ($produk_terbaru as $item) { 
            $this->setHargaTampil($item); 
        }

        $kategori = Kategori::all();
        $merk = Merk::all();

        // Mengambil banner dari user yang sedang login agar hasil upload langsung terlihat
        $admin = Auth::check() && Auth::user()->isAdmin()
            ? Auth::user()
            : User::where('role', 'admin')->first();
        
        // Eager loading untuk optimasi database
        $bundling = Bundling::with(['items.produk.merk'])
            ->activePromo()
            ->latest()
            ->get();

        // Inisialisasi collection kosong
        $bundling_warnings = collect();

        // Inisialisasi chart data
        $chartLabels = [];
        $chartData = [];
        $totalRevenue = 0;
        $totalOrders = 0;
        $averageOrderValue = 0;
        $statusBreakdown = collect();

        if (Auth::check() && Auth::user()->isAdmin()) {
            $bundling_warnings = $bundling->filter(function($b) {
                return $b->hasPriceDivergence();
            });

            // Jika owner, tampilkan grafik penjualan
            if (Auth::user()->isOwner()) {
                // Fetch sales data for the last 30 days
                $thirtyDaysAgo = Carbon::now()->subDays(30);
                $salesData = Order::where('created_at', '>=', $thirtyDaysAgo)
                    ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as order_count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Convert to format for chart
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i)->format('Y-m-d');
                    $chartLabels[] = Carbon::now()->subDays($i)->format('d/m');
                    $revenue = $salesData->firstWhere('date', $date)?->revenue ?? 0;
                    $chartData[] = floatval($revenue);
                }

                // Calculate metrics
                $totalRevenue = Order::where('created_at', '>=', $thirtyDaysAgo)->sum('total');
                $totalOrders = Order::where('created_at', '>=', $thirtyDaysAgo)->count();
                $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

                // Order status breakdown
                $statusBreakdown = Order::selectRaw('order_status, COUNT(*) as count')
                    ->groupBy('order_status')
                    ->pluck('count', 'order_status');
            }
        }

        return view('beranda', compact(
            'settings', 
            'produk_terbaru', 
            'kategori', 
            'merk', 
            'admin', 
            'bundling', 
            'bundling_warnings',
            'chartLabels',
            'chartData',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'statusBreakdown'
        ));
    }
            

    //Menampilkan Halaman Katalog dengan Filter Pencarian

    public function katalog(Request $request)
    {
        $kategori = Kategori::where('is_hidden', 0)->get();
        $merk = Merk::where('is_hidden', 0)->get(); 

         $query = Produk::query()
        ->where('status', 'aktif')
        ->whereHas('kategori', function($q) { $q->where('is_hidden', 0); })
        ->whereHas('merk', function($q) { $q->where('is_hidden', 0); })
        ->whereHas('satuan', function($q) { $q->where('is_hidden', 0); });

        // Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        // Filter Merk
        if ($request->filled('merk')) {
            $query->where('kd_merk', $request->merk);
        }

        $produk = $query->latest()->get();

        // PENTING: Memproses harga agar tidak muncul Rp 0 di halaman katalog
        foreach ($produk as $item) {
            $this->setHargaTampil($item);
        }

        return view('katalog', compact('produk', 'kategori', 'merk'));
    }

    /**
     * Helper untuk menentukan harga berdasarkan tipe customer (Umum/Langganan)
     */
    private function setHargaTampil($item)
    {
        // Default harga menggunakan harga jual umum
        $item->harga_tampil = $item->harga_jual_umum ?? 0;

        // Jika user login dan tipenya 'langganan', gunakan harga langganan
        if (Auth::check() && Auth::user()->customer_type === 'langganan') {
            $item->harga_tampil = $item->harga_jual_langganan ?? $item->harga_jual_umum;
        }
    }

    private function resolveStokMinimalBySatuan(Request $request): int
    {
        if ($request->filled('stok_minimal')) {
            return (int) $request->stok_minimal;
        }

        $satuanModel = null;

        if ($request->filled('kd_satuan')) {
            $satuanModel = Satuan::find($request->kd_satuan);
        }

        if ($satuanModel && $satuanModel->stok_minimal !== null) {
            return (int) $satuanModel->stok_minimal;
        }

        $satuanName = strtolower((string) ($request->satuan ?? $satuanModel?->nama_satuan ?? ''));

        if (str_contains($satuanName, 'pcs')) {
            return 250;
        }

        if (str_contains($satuanName, 'lusin') || str_contains($satuanName, 'dozen')) {
            return 10;
        }

        return 0;
    }

    // --- Bagian Manajemen Admin ---

    public function createBeranda() { return $this->createForm('beranda'); }
    public function create() { abort_unless(Auth::check() && Auth::user()->isAdmin(), 403); return $this->createForm('layar_produk'); }

    private function createForm($source)
    {
        return view('admin.tambah_produk', [
            'source' => $source,
            'kategoris' => Kategori::all(),
            'merks' => Merk::all(),
            'satuan' => \App\Models\Satuan::all()
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        // 1. Validasi Input
        $request->validate([
            'kd_produk'       => 'required|unique:produk,kd_produk',
            'nama_produk'     => 'required|string|max:255',
            'harga_jual_umum' => 'required|numeric',
            'stok_tersedia'   => 'required|numeric',
            'stok_minimal'    => 'nullable|numeric',
            'files.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Siapkan data awal
        $stok_minimal = $this->resolveStokMinimalBySatuan($request);

        $data = [
            'kd_produk'            => $request->kd_produk,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'kd_satuan'            => $request->kd_satuan,
            'satuan'               => $request->satuan ?? (isset($request->kd_satuan) ? Satuan::find($request->kd_satuan)?->nama_satuan : null),
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
            'stok_minimal'         => $stok_minimal,
            'status'               => 'aktif', // default status
        ];

        // 3. Logika Simpan Banyak Foto (Maksimal 3)
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            
            // Pemetaan urutan file ke kolom database Trenmart
            $columns = [0 => 'gambar', 1 => 'foto_2', 2 => 'foto_3'];

            foreach ($files as $index => $file) {
                if (isset($columns[$index])) {
                    $columnName = $columns[$index];
                    
                    // Simpan file ke storage/app/public/produk
                    $path = $file->store('produk', 'public');
                    $data[$columnName] = $path;
                }
            }
        }

        // 4. Eksekusi Simpan ke Database
        $produk = Produk::create($data);

        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'create_product',
                'details' => 'Produk: ' . ($produk->nama_produk ?? '') . ' (kd: ' . ($produk->kd_produk ?? '') . ')',
                'ip_address' => $request->ip(),
                'subject_type' => 'produk',
                'subject_id' => $produk->kd_produk,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        // 5. Redirect sesuai origin (Beranda atau Index Produk)
        $route = ($request->origin == 'beranda') ? 'beranda' : 'produk.index';
        return redirect()->route($route)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkIndex(Request $request)
    {
        // Join satuan to allow ordering by satuan.stok_minimal when produk.stok_minimal is not set
        $query = Produk::with(['merk', 'kategori', 'satuan'])
             ->leftJoin('satuan', 'produk.kd_satuan', '=', 'satuan.kd_satuan')
             ->select('produk.*');

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        if ($request->filled('merk')) {
            $query->where('kd_merk', $request->merk);
        }

        // Untuk halaman tabel manajemen stok admin
        // Prioritaskan produk yang stoknya di bawah stok_minimal agar muncul paling atas
        $produk = $query->orderByRaw("(produk.stok_tersedia <= COALESCE(produk.stok_minimal, satuan.stok_minimal, 0)) DESC")
             ->orderBy('produk.created_at', 'desc')
             ->get();
        foreach ($produk as $item) {
            $this->setHargaTampil($item);
        }
        $kategori = Kategori::all();
        $merk = Merk::all();
        $satuan = \App\Models\Satuan::all();

        return view('admin.edit_katalog', compact('produk', 'kategori', 'merk', 'satuan'));
    }

    public function edit($kd_produk)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        $kategoris = Kategori::all();
        $merks = Merk::all();
        $satuan = \App\Models\Satuan::all();
        return view('admin.edit_produk', compact('produk', 'kategoris', 'merks', 'satuan'));
    }

    public function update(Request $request, $kd_produk)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $request->validate([
            'nama_produk'          => 'required|string|max:255',
            'harga_jual_umum'      => 'required|numeric',
            'stok_tersedia'        => 'required|numeric',
            'stok_minimal'         => 'nullable|numeric',
            'files.*'              => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        
        $stok_minimal = $this->resolveStokMinimalBySatuan($request);

        $updateData = [
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'kd_satuan'            => $request->kd_satuan,
            'satuan'               => $request->satuan ?? (isset($request->kd_satuan) ? Satuan::find($request->kd_satuan)?->nama_satuan : null),
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
            'stok_minimal'         => $stok_minimal,
        ];

        // Logika Multiple Upload
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $columns = [0 => 'gambar', 1 => 'foto_2', 2 => 'foto_3'];

            foreach ($files as $index => $file) {
                if (isset($columns[$index])) {
                    $columnName = $columns[$index];

                    // Hapus gambar lama jika ada
                    if ($produk->$columnName && Storage::disk('public')->exists($produk->$columnName)) {
                        Storage::disk('public')->delete($produk->$columnName);
                    }

                    // Simpan file baru ke folder 'produk'
                    $path = $file->store('produk', 'public');
                    $updateData[$columnName] = $path;
                }
            }
        }

        $produk->update($updateData);

        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'update_product',
                'details' => 'Updated produk ' . $produk->nama_produk . ' (kd: ' . $produk->kd_produk . ')',
                'ip_address' => $request->ip(),
                'subject_type' => 'produk',
                'subject_id' => $produk->kd_produk,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Request $request, $kd_produk)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        
        // Hapus file gambar
        if ($produk->gambar && File::exists(public_path('storage/' . $produk->gambar))) {
            File::delete(public_path('storage/' . $produk->gambar));
        }

        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'delete_product',
                'details' => 'Deleted produk ' . $produk->nama_produk . ' (kd: ' . $produk->kd_produk . ')',
                'ip_address' => $request->ip(),
                'subject_type' => 'produk',
                'subject_id' => $produk->kd_produk,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        $query = Produk::where('kd_produk', $id);
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            $query->where('status', 'aktif');
        }
        $produk = $query->firstOrFail();
        
        $this->setHargaTampil($produk);

        return view('produk.detail', compact('produk'))->with('is_bundling', false);
    }

    public function searchAjax(Request $request)
    {
    $cari = $request->q; // 'q' adalah parameter default dari Select2

    $produk = \App\Models\Produk::where('nama_produk', 'LIKE', "%$cari%")
                ->select('kd_produk as id', 'nama_produk as text') // SANGAT PENTING: id dan text
                ->limit(20)
                ->get();

    return response()->json($produk);
    }

    public function updateStatus(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        // 1. Validasi dengan menangkap pesan error agar tidak langsung 500
        $request->validate([
            // Sesuaikan 'produk' dengan nama tabel asli di SQL Server Anda
            'id' => 'required|exists:produk,kd_produk', 
            'status' => 'required|in:aktif,nonaktif'
        ]);

        try {
            // 2. Gunakan where karena primary key Anda adalah kd_produk, bukan id (integer)
            $produk = \App\Models\Produk::where('kd_produk', $request->id)->firstOrFail();
            
            $produk->status = $request->status;
            $produk->save();

            return response()->json([
            'success' => true,
            'message' => 'Status ' . $produk->nama_produk . ' berhasil diperbarui!' 
        ]);

        } catch (\Exception $e) {
            // 3. Jika terjadi error database, kirimkan pesan yang jelas ke AJAX
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui database: ' . $e->getMessage()
            ], 500);
        }
    }
}