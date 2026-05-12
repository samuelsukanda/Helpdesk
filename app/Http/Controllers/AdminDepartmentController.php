<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('users')->latest()->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        Department::create([...$request->only(['name', 'code', 'description']), 'is_active' => true]);
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:10|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $department->update([
            ...$request->only(['name', 'code', 'description']),
            'is_active' => $request->boolean('is_active'),
        ]);
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return back()->with('error', 'Departemen tidak dapat dihapus karena masih memiliki anggota.');
        }
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
