<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    private const SORTABLE = [
        'name', 'nama_lengkap', 'email', 'role', 'is_active', 'created_at',
    ];

    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%{$kw}%")->orWhere('email','like',"%{$kw}%")->orWhere('nama_lengkap','like',"%{$kw}%"));
        }
        if ($request->filled('role')) $query->where('role', $request->role);

        $sort      = $request->get('sort', 'nama_lengkap');
        $direction = $request->get('direction', 'asc');
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if (in_array($sort, self::SORTABLE)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('nama_lengkap', 'asc');
        }

        $users = $query->paginate(15)->withQueryString();
        return view('user.index', compact('users', 'sort', 'direction'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['nullable', 'string', 'max:50', 'unique:users,name', 'regex:/^[a-z0-9._]+$/'],
            'nama_lengkap' => ['required', 'string', 'max:200'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'telepon'      => ['nullable', 'string', 'max:20'],
            'role'         => ['required', 'in:admin,staff'],
            'password'     => ['required', Password::defaults(), 'confirmed'],
        ], [
            'name.regex' => 'Username hanya boleh huruf kecil, angka, titik, dan underscore.',
        ]);

        // Auto-generate username jika kosong
        if (empty($validated['name'])) {
            $validated['name'] = $this->generateUsername($validated['nama_lengkap'], $validated['role']);
        }

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:200'],
            'email'        => ['required', 'email', Rule::unique('users','email')->ignore($user->id_users)],
            'telepon'      => ['nullable', 'string', 'max:20'],
            'role'         => ['required', 'in:admin,staff'],
            'is_active'    => ['boolean'],
        ]);

        $user->update($validated);
        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);
        return redirect()->route('user.index')->with('success', 'Password user berhasil direset.');
    }

    public function destroy(User $user)
    {
        if ($user->id_users === auth()->user()->id_users) {
            return redirect()->route('user.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        if ($user->peminjamans()->whereIn('status', ['dipinjam', 'terlambat', 'menunggu'])->count() > 0) {
            return redirect()->route('user.index')->with('error', 'User masih memiliki peminjaman aktif atau menunggu persetujuan.');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Generate username otomatis dari nama lengkap.
     * Format: namapertama.staff (atau namapertama.admin untuk admin)
     * Jika sudah ada → tambahkan angka: namapertama2.staff, namapertama3.staff, dst.
     */
    private function generateUsername(string $namaLengkap, string $role): string
    {
        // Ambil kata pertama, bersihkan karakter non-alphanumeric, jadikan lowercase
        $namaDepan = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', trim($namaLengkap))[0]));
        if (empty($namaDepan)) $namaDepan = 'user';

        $base     = $namaDepan . '.' . $role;
        $username = $base;
        $counter  = 2;

        while (User::where('name', $username)->exists()) {
            $username = $namaDepan . $counter . '.' . $role;
            $counter++;
        }

        return $username;
    }
}
