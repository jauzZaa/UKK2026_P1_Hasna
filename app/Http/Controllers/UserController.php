<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function tampil()
    {
        $data = User::all();
        return view('user.tampil', compact('data'));
    }

    // ← TAMBAH INI
    public function tambah()
    {
        return view('user.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
            'tanggal_lahir' => 'nullable|date',
            'password'      => 'required|min:6',
            'role'          => 'required|in:admin,petugas,peminjam',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
        ]);

        return redirect()->route('user.tampil')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.tampil')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('user.tampil')->with('success', 'User berhasil dihapus!');
    }
}
