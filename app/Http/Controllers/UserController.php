<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->latest()
            ->get();

        $stats = [
            'total' => $users->count(),
            'aktif' => $users->where('status', 'aktif')->count(),
            'nonaktif' => $users->where('status', 'nonaktif')->count(),
            'admin' => $users->whereIn('role', ['Super Admin', 'Admin'])->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['Super Admin', 'Admin', 'Tekpol', 'Verifikator', 'Pemohon'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif', 'pending'])],
            'instansi' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['Super Admin', 'Admin', 'Tekpol', 'Verifikator', 'Pemohon'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif', 'pending'])],
            'instansi' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
