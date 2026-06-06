<?php
namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $barangTerjual = DB::table('detail_transaksis')
            ->sum('jumlah');
        $keuntunganBulanan = Transaksi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total');
        $totalBarang = Barang::count();
        $grafikBulanan = Transaksi::selectRaw('MONTH(tanggal) as bulan, SUM(total) as total')
            ->whereYear('tanggal', now()->year)
            ->groupByRaw('MONTH(tanggal)')
            ->orderByRaw('MONTH(tanggal)')
            ->get();
        $labels = [];
        $data = [];
        $namaBulan = [
            1  => 'Jan',
            2  => 'Feb',
            3  => 'Mar',
            4  => 'Apr',
            5  => 'Mei',
            6  => 'Jun',
            7  => 'Jul',
            8  => 'Ags',
            9  => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
        ];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $namaBulan[$i];
            $bulan = $grafikBulanan->firstWhere('bulan', $i);
            $data[] = $bulan ? $bulan->total : 0;
        }
        $grafikHarian = Transaksi::selectRaw('DATE(tanggal) as tanggal, SUM(total) as total')
            ->whereDate('tanggal', '>=', now()->subDays(30))
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();
        $labelsHarian = $grafikHarian
            ->pluck('tanggal')
            ->map(function ($tanggal) {
                return Carbon::parse($tanggal)->format('d M');
            })
            ->toArray();
        $dataHarian = $grafikHarian
            ->pluck('total')
            ->toArray();
        return view('dashboard', compact(
            'totalBarang',
            'barangTerjual',
            'keuntunganBulanan',
            'labels',
            'data',
            'labelsHarian',
            'dataHarian'
        ));
    }
}
