<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'total'       => Ticket::count(),
                'open'        => Ticket::where('status', 'open')->count(),
                'in_progress' => Ticket::where('status', 'in_progress')->count(),
                'resolved'    => Ticket::where('status', 'resolved')->count(),
                'overdue'     => Ticket::where('sla_breached', true)->whereNotIn('status', ['resolved', 'closed'])->count(),
                'total_users' => User::count(),
                'total_agents' => User::role('agent')->count(),
            ];
            $recentTickets = Ticket::with(['requester', 'agent', 'category'])
                ->latest()->limit(10)->get();
            $ticketsByStatus = Ticket::selectRaw('status, count(*) as total')
                ->groupBy('status')->pluck('total', 'status');
            $ticketsByPriority = Ticket::selectRaw('priority, count(*) as total')
                ->groupBy('priority')->pluck('total', 'priority');

            return view('dashboard.admin', compact('stats', 'recentTickets', 'ticketsByStatus', 'ticketsByPriority'));
        }

        if ($user->isAgent()) {
            $myTickets = Ticket::where('agent_id', $user->id)
                ->whereNotIn('status', ['closed'])->with(['requester', 'category'])->latest()->get();
            $stats = [
                'assigned'    => Ticket::where('agent_id', $user->id)->count(),
                'open'        => Ticket::where('agent_id', $user->id)->where('status', 'open')->count(),
                'in_progress' => Ticket::where('agent_id', $user->id)->where('status', 'in_progress')->count(),
                'resolved'    => Ticket::where('agent_id', $user->id)->where('status', 'resolved')->count(),
            ];
            return view('dashboard.agent', compact('stats', 'myTickets'));
        }

        // End User
        $myTickets = Ticket::where('requester_id', $user->id)->with(['category', 'agent'])->latest()->get();
        $stats = [
            'total'    => $myTickets->count(),
            'open'     => $myTickets->where('status', 'open')->count(),
            'resolved' => $myTickets->where('status', 'resolved')->count(),
        ];
        return view('dashboard.user', compact('stats', 'myTickets'));
    }
}
