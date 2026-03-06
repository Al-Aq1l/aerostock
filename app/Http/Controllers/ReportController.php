<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $sales = Sale::with('items.product')->latest()->paginate(20);
        return view('reports.index', compact('sales'));
    }
}
