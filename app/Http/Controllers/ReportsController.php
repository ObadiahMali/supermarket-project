<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $sales = Sale::with(['user', 'items'])
            ->when($from, fn($q) => $q->where('timestamp', '>=', $from))
            ->when($to, fn($q) => $q->where('timestamp', '<=', $to))
            ->orderByDesc('timestamp')
            ->get();

        $chartLabels = $sales->pluck('timestamp')->map(fn($t) => $t->format('M d'))->toArray();
        $chartData = $sales->pluck('total')->toArray();

        return view('reports.index', compact('sales', 'chartLabels', 'chartData'));
    }
}