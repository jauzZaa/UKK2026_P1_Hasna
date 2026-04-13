<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function tampil()
    {
        $data = User::with('detail')
            ->when(request('role'), function ($query) {
                $query->where('role', request('role'));
            })
            ->get();
        return view('user.tampil', compact('data'));
    }

    public function tambah()
    {
        return view('user.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'        => 'required|string|unique:user_details,nik',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'no_hp'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'password'   => 'required|min:6',
            'role'       => 'required|in:Admin,Employee,User',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        UserDetail::create([
            'nik'        => $request->nik,
            'user_id'    => $user->id,
            'name'       => $request->name,
            'no_hp'      => $request->no_hp,
            'address'    => $request->address,
            'birth_date' => $request->birth_date,
        ]);

        return redirect()->route('user.tampil')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::with('detail')->findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik'        => 'nullable|string|unique:user_details,nik,' . ($user->detail->nik ?? 'NULL') . ',nik',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'no_hp'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'role'       => 'required|in:Admin,Employee,User',
        ]);

        $user->update([
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $detail = UserDetail::where('user_id', $user->id)->first();

        if ($detail) {
            $detail->update([
                'name'       => $request->name,
                'no_hp'      => $request->no_hp,
                'address'    => $request->address,
                'birth_date' => $request->birth_date,
            ]);
        } else {
            UserDetail::create([
                'nik'        => $request->nik,
                'user_id'    => $user->id,
                'name'       => $request->name,
                'no_hp'      => $request->no_hp,
                'address'    => $request->address,
                'birth_date' => $request->birth_date,
            ]);
        }

        return redirect()->route('user.tampil')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        UserDetail::where('user_id', $user->id)->delete();
        $user->delete();
        return redirect()->route('user.tampil')->with('success', 'User berhasil dihapus!');
    }
}
