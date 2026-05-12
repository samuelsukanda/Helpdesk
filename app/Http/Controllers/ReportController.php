<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Exports\TicketsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['requester', 'agent', 'category']);

        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))   $query->whereDate('created_at', '<=', $request->to);

        $tickets = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'by_status'   => Ticket::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'by_priority' => Ticket::selectRaw('priority, count(*) as total')->groupBy('priority')->pluck('total', 'priority'),
            'by_category' => Ticket::with('category')->selectRaw('category_id, count(*) as total')->groupBy('category_id')->get(),
        ];

        return view('reports.index', compact('tickets', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new TicketsExport($request->all()), 'laporan-tiket-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $tickets = Ticket::with(['requester', 'agent', 'category'])->latest()->get();
        $pdf     = Pdf::loadView('reports.pdf', compact('tickets'));
        return $pdf->download('laporan-tiket-' . now()->format('Y-m-d') . '.pdf');
    }
}
