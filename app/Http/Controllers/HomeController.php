<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class HomeController extends Controller
{
    //
    public function index()
    {
        $produks = Barang::all();
        return view('home', compact('produks'));
    }
}
