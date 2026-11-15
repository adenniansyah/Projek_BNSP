<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // PENTING: Untuk upload & hapus file

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm() {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Arahkan berdasarkan role
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Tampilkan form register
    public function showRegisterForm() {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'role' => 'user', // Default user baru adalah 'user'
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ✅ METHOD BARU: Menampilkan halaman edit profile
    public function showProfile() {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    // ✅ METHOD BARU: Memproses update profile & upload foto
    public function updateProfile(Request $request) {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        // Logika Upload Foto Profil
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada (dan bukan foto default)
            if ($user->avatar && !str_contains($user->avatar, 'default-avatar.png')) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Update data user di database
        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}