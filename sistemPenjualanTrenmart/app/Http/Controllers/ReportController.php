<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReportController extends Controller
{
    private function resolveMonthlyRange(Request $request): array
    {
        $startInput = $request->query('start');
        $endInput = $request->query('end');

        if ($startInput) {
            $start = Carbon::parse($startInput)->startOfDay();
            $end = $endInput
                ? Carbon::parse($endInput)->endOfDay()
                : (clone $start)->endOfWeek();

            $periodLabel = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');

            return [$start, $end, $periodLabel];
        }

        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        return [$start, $end, $start->translatedFormat('F Y')];
    }

    private function completedOrdersQuery(Carbon $start, Carbon $end, string $type = 'all')
    {
        $query = Order::with(['user', 'items.produk', 'paymentMethod'])
            ->where('order_status', 'completed')
            ->whereBetween(DB::raw('COALESCE(completed_at, stock_deducted_at, updated_at)'), [$start, $end]);

        if ($type === 'langganan') {
            $query->whereHas('user', fn($q) => $q->where('customer_type', 'langganan'));
        } elseif ($type === 'umum') {
            $query->whereHas('user', fn($q) => $q->where(function($qq){ $qq->where('customer_type', '!=', 'langganan')->orWhereNull('customer_type'); }));
        }

        return $query->orderBy('completed_at', 'asc')->orderBy('created_at', 'asc');
    }

    public function index(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        return view('reports.index');
    }
    // Return monthly sales summary (totals and counts) split by customer_type
    public function monthly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $query = Order::whereBetween('created_at', [$start, $end])->where('payment_status', '!=', 'rejected');

        $totalCombined = (float) $query->sum('total');

        $totalLangganan = (float) $query->whereHas('user', fn($q) => $q->where('customer_type', 'langganan'))->sum('total');
        $countLangganan = (int) $query->whereHas('user', fn($q) => $q->where('customer_type', 'langganan'))->count();

        $totalUmum = (float) $query->whereHas('user', fn($q) => $q->where(function($qq){ $qq->where('customer_type', '!=', 'langganan')->orWhereNull('customer_type'); }))->sum('total');
        $countUmum = (int) $query->whereHas('user', fn($q) => $q->where(function($qq){ $qq->where('customer_type', '!=', 'langganan')->orWhereNull('customer_type'); }))->count();

        return response()->json([
            'period' => $start->format('Y-m'),
            'total' => $totalCombined,
            'langganan' => [
                'total' => $totalLangganan,
                'count' => $countLangganan,
            ],
            'umum' => [
                'total' => $totalUmum,
                'count' => $countUmum,
            ],
        ]);
    }

    // Return weekly sales summary
    public function weekly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfWeek() : Carbon::now()->startOfWeek();
        $end = (clone $start)->endOfWeek();

        $query = Order::whereBetween('created_at', [$start, $end])->where('payment_status', '!=', 'rejected');

        $totalCombined = (float) $query->sum('total');

        $totalLangganan = (float) $query->whereHas('user', fn($q) => $q->where('customer_type', 'langganan'))->sum('total');
        $countLangganan = (int) $query->whereHas('user', fn($q) => $q->where('customer_type', 'langganan'))->count();

        $totalUmum = (float) $query->whereHas('user', fn($q) => $q->where(function($qq){ $qq->where('customer_type', '!=', 'langganan')->orWhereNull('customer_type'); }))->sum('total');
        $countUmum = (int) $query->whereHas('user', fn($q) => $q->where(function($qq){ $qq->where('customer_type', '!=', 'langganan')->orWhereNull('customer_type'); }))->count();

        return response()->json([
            'period' => $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d'),
            'total' => $totalCombined,
            'langganan' => [
                'total' => $totalLangganan,
                'count' => $countLangganan,
            ],
            'umum' => [
                'total' => $totalUmum,
                'count' => $countUmum,
            ],
        ]);
    }

    // Printable HTML view (Word-like) for monthly
    public function printMonthly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        [$start, $end, $periodLabel] = $this->resolveMonthlyRange($request);

        $type = $request->query('type', 'all');

        $orders = $this->completedOrdersQuery($start, $end, $type)->get();

        $data = [
            'title' => 'Laporan Penjualan Bulanan',
            'period' => $periodLabel,
            'orders' => $orders,
            'generated_at' => now(),
        ];

        return view('reports.print', $data);
    }

    public function pdfMonthly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        [$start, $end, $periodLabel] = $this->resolveMonthlyRange($request);

        $type = $request->query('type', 'all');

        $orders = $this->completedOrdersQuery($start, $end, $type)->get();

        $data = [
            'title' => 'Laporan Penjualan Bulanan',
            'period' => $periodLabel,
            'orders' => $orders,
            'generated_at' => now(),
        ];

        $pdf = PDF::loadView('reports.print', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan_bulanan_' . $start->format('Y_m') . ($type !== 'all' ? "_{$type}" : '') . '.pdf');
    }

    // Printable and PDF for weekly
    public function printWeekly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfWeek() : Carbon::now()->startOfWeek();
        $end = (clone $start)->endOfWeek();

        $type = $request->query('type', 'all');

        $orders = $this->completedOrdersQuery($start, $end, $type)->get();

        $data = [
            'title' => 'Laporan Penjualan Mingguan',
            'period' => $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d'),
            'orders' => $orders,
            'generated_at' => now(),
        ];

        return view('reports.print', $data);
    }

    public function pdfWeekly(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isOwner()), 403);

        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfWeek() : Carbon::now()->startOfWeek();
        $end = (clone $start)->endOfWeek();

        $type = $request->query('type', 'all');

        $orders = $this->completedOrdersQuery($start, $end, $type)->get();

        $data = [
            'title' => 'Laporan Penjualan Mingguan',
            'period' => $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d'),
            'orders' => $orders,
            'generated_at' => now(),
        ];

        $pdf = PDF::loadView('reports.print', $data)->setPaper('a4', 'landscape');
        return $pdf->download('laporan_mingguan_' . $start->format('Y_m_d') . ($type !== 'all' ? "_{$type}" : '') . '.pdf');
    }
}
