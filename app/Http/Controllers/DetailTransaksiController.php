<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class DetailTransaksiController extends Controller
{
    //
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            if (!$request->items) {
                throw new \Exception("Keranjang kosong");
            }

            $transaksi = Transaksi::create([
                'id_user' => 1,
                'tanggal' => now(),
                'total' => $request->total
            ]);

            foreach ($request->items as $item) {

                $barang = Barang::find($item['id_barang']);

                if (!$barang) {
                    throw new \Exception("Barang tidak ditemukan");
                }

                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok tidak cukup");
                }

                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal']
                ]);

                //  kurangi stok
                $barang->stok -= $item['jumlah'];
                $barang->save();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function riwayat(Request $request)
    {
        $query = Transaksi::with(['details.barang']);

        // FILTER BULAN
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // FILTER TANGGAL
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        $transaksis = $query->orderBy('id_transaksi', 'desc')->get();

        //  MODE GRAFIK
        $mode = $request->mode ?? 'harian';

        $grafikQuery = Transaksi::query();

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $grafikQuery->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        if ($mode == 'harian') {

            $grafik = $grafikQuery->select(
                DB::raw('DATE(tanggal) as label'),
                DB::raw('SUM(total) as total')
            )
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } elseif ($mode == 'bulanan') {

            $grafik = $grafikQuery->select(
                DB::raw('MONTH(tanggal) as label'),
                DB::raw('SUM(total) as total')
            )
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } else {

            $grafik = $grafikQuery->select(
                DB::raw('YEAR(tanggal) as label'),
                DB::raw('SUM(total) as total')
            )
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        }

        $labels = [];
        $data = [];

        foreach ($grafik as $g) {
            $labels[] = $g->label;
            $data[] = $g->total;
        }

        // KEUNTUNGAN BULANAN
        $keuntungan = Transaksi::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(total) as total')
        )
            ->groupBy('bulan')
            ->get();

        return view('riwayat.index', compact(
            'transaksis',
            'labels',
            'data',
            'mode',
            'keuntungan'
        ));
    }
}
