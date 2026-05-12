<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'roles']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('employee_id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role'))       $query->role($request->role);
        if ($request->filled('department')) $query->where('department_id', $request->department);
        if ($request->filled('status'))     $query->where('is_active', $request->status === 'active');

        $users       = $query->latest()->paginate(15)->withQueryString();
        $roles       = Role::all();
        $departments = Department::active()->get();

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_count' => User::role('admin')->count(),
            'agent_count' => User::role('agent')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'departments', 'stats'));
    }

    public function create()
    {
        $roles       = Role::all();
        $departments = Department::active()->get();
        return view('admin.users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|exists:roles,name',
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:20',
            'employee_id'   => 'nullable|string|max:50|unique:users,employee_id',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'department_id' => $request->department_id,
            'phone'         => $request->phone,
            'employee_id'   => $request->employee_id,
            'is_active'     => true,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles       = Role::all();
        $departments = Department::active()->get();
        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|exists:roles,name',
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:20',
            'employee_id'   => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'is_active'     => 'boolean',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'department_id' => $request->department_id,
            'phone'         => $request->phone,
            'employee_id'   => $request->employee_id,
            'is_active'     => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
