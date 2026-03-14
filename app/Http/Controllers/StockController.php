<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        return view('stock.index');
    }

}
