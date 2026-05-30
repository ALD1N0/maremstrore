<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

class ModelGrafikController extends Controller
{
    public function Grafik(Request $request)
    {
        $mode = $request->mode ?? 'harian';

        $query = Transaksi::query();

        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        if ($mode == 'harian') {

            $grafik = $query
                ->selectRaw('tanggal as label, SUM(total) as total')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
        } elseif ($mode == 'bulanan') {

            $grafik = $query
                ->selectRaw('MONTH(tanggal) as label, SUM(total) as total')
                ->groupByRaw('MONTH(tanggal)')
                ->orderByRaw('MONTH(tanggal)')
                ->get();
        } else {

            $grafik = $query
                ->selectRaw('YEAR(tanggal) as label, SUM(total) as total')
                ->groupByRaw('YEAR(tanggal)')
                ->orderByRaw('YEAR(tanggal)')
                ->get();
        }

        $labels = $grafik->pluck('label')->toArray();
        $data   = $grafik->pluck('total')->toArray();

        // DEBUG CEK DATA
        // dd($labels, $data);

        return view('profile.index', compact(
            'labels',
            'data',
            'mode'
        ));
    }
}
