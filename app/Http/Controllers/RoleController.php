<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Tampilkan daftar role.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Tampilkan form tambah role.
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Simpan role baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
            'permission_ids' => 'array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($data);

        // sync selalu jalan, kalau tidak ada centang berarti aksesnya kosong
        $role->syncPermissions($data['permission_ids'] ?? []);

        return redirect(route('admin.roles.index'))
            ->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Tampilkan form ubah role (cuma rename, akses di halaman daftar).
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Simpan perubahan role (cuma rename, akses diatur lewat updatePermissions).
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'guard_name' => 'required|string|max:255',
        ]);

        $role->update($data);

        return redirect(route('admin.roles.index'))
            ->with('success', 'Role berhasil diupdate.');
    }

    /**
     * Simpan centang akses dari halaman daftar role (langsung on/off).
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permission_ids' => 'array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        // checkbox yang dicentang saja yang dikirim, jadi sync langsung berlaku
        $role->syncPermissions($data['permission_ids'] ?? []);

        return redirect(route('admin.roles.index'))
            ->with('success', 'Akses role "'.$role->name.'" berhasil diupdate.');
    }

    /**
     * Hapus role.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect(route('admin.roles.index'))
            ->with('success', 'Role berhasil dihapus.');
    }
}
