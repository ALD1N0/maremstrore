<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Barang extends Model
{
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'nama',
        'stok',
        'harga',
        'gambar'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barang) {
            $barang->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang');
    }
}
