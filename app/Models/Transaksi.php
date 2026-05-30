<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_user',
        'tanggal',
        'total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
    public function details()
    {
        return $this->hasMany(\App\Models\DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
