<?php

namespace App\Http\Controllers;

use App\Models\SlaPolicy;
use Illuminate\Http\Request;

class AdminSlaPolicyController extends Controller
{
    public function index()
    {
        $policies = SlaPolicy::orderByRaw("FIELD(priority, 'low','medium','high','critical')")->get();
        return view('admin.sla-policies.index', compact('policies'));
    }

    public function edit(SlaPolicy $slaPolicy)
    {
        return view('admin.sla-policies.edit', compact('slaPolicy'));
    }

    public function update(Request $request, SlaPolicy $slaPolicy)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'response_time_hours'   => 'required|integer|min:1',
            'resolution_time_hours' => 'required|integer|min:1',
            'is_active'             => 'boolean',
        ]);

        $slaPolicy->update([
            'name'                  => $request->name,
            'response_time_hours'   => $request->response_time_hours,
            'resolution_time_hours' => $request->resolution_time_hours,
            'is_active'             => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.sla.index')->with('success', 'SLA Policy berhasil diperbarui.');
    }
}
