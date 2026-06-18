<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Tampilkan daftar pengguna dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.pengguna.index', compact('users', 'search'));
    }

    /**
     * Tampilkan form tambah pengguna.
     */
    public function create()
    {
        return view('pages.pengguna.create');
    }

    /**
     * Simpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,bk,bkk',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 4 karakter.',
            'role.required' => 'Peran wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail pengguna.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.pengguna.show', compact('user'));
    }

    /**
     * Tampilkan form ubah pengguna.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.pengguna.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna di database.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:4',
            'role' => 'required|in:admin,bk,bkk',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal terdiri dari 4 karakter.',
            'role.required' => 'Peran wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id() && $validated['status'] === 'nonaktif') {
            return back()->withErrors(['status' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'])->withInput();
        }

        $user->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna dari database.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
