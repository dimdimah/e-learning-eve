<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index()
    {
        $users = User::with('roles')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (isset($data['role_ids'])) {
            $user->syncRoles($data['role_ids']);
        }

        return redirect(route('admin.users.index'))
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Simpan perubahan user.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (isset($data['role_ids'])) {
            $user->syncRoles($data['role_ids']);
        } elseif (! isset($data['password'])) {
            // Jika password tidak diubah, pastikan tidak dihapus
            $user->save();
        }

        return redirect(route('admin.users.index'))
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        // Jangan izinkan hapus user terakhir dengan role admin
        if ($user->hasRole('admin') && User::where('id', '!=', $user->id)->whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->count() <= 0) {
            return redirect(route('admin.users.index'))
                ->with('error', 'Tidak bisa menghapus user admin terakhir.');
        }

        $user->delete();

        return redirect(route('admin.users.index'))
            ->with('success', 'User berhasil dihapus.');
    }
}
