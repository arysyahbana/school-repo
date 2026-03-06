<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('myPages.user.index', compact('users'));
    }

    public function create()
    {
        return view('myPages.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => 'required|confirmed|min:8',
            'jabatan'  => ['required', 'in:admin, guru, kepala_madrasah, kaur, tu, wakil'],
            'foto'     => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Upload foto (jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_user', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan'  => $request->jabatan,
            'foto'     => $fotoPath, // bisa null
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('myPages.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan'  => 'required|in:admin,guru,kepala_madrasah,kaur,tu,wakil',
            'password' => 'nullable|confirmed|min:8',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        // Data dasar
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'jabatan' => $request->jabatan,
        ];

        // Jika password diisi → update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('foto_user', 'public');
        }

        // Update user
        $user->update($data);

        return redirect()
            ->route('user.index')
            ->with('success', 'Data user berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Hapus user
        $user->delete();

        return redirect()
            ->route('user.index')->with('success', 'Data user berhasil dihapus');
    }
}
