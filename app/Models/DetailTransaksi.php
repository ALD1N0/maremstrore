<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'jumlah',
        'subtotal'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class, 'id_barang', 'id_barang');
    }
    public function riwayat()
    {
        $transaksis = \App\Models\Transaksi::with(['details.barang'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        return view('riwayat.index', compact('transaksis'));
    }
}
