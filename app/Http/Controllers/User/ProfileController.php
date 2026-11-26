<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Validasi
        $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:20',
            'gender'           => 'nullable|string',
            'birth_date'       => 'nullable|date',
            'occupation'       => 'nullable|string',
            'domicile_address' => 'nullable|string', // Sesuai DB
            'profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Ambil data
        $data = $request->only([
            'name',
            'email',
            'phone',
            'gender',
            'birth_date',
            'occupation',
            'domicile_address' // Sesuai DB
        ]);

        // 3. Handle Upload Foto
        if ($request->hasFile('profile_photo')) {
            $oldPhoto = $user->photo ?? $user->profile_photo;
            if (!empty($oldPhoto) && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }
            // Simpan ke kolom 'profile_photo' sesuai database
            $data['profile_photo'] = $request->file('profile_photo')->store('profile', 'public');
        }

        // 4. Simpan ke database
        $user->update($data);

        // 5. Kembalikan ke halaman profil dengan pesan sukses
        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $oldPhoto = $user->photo ?? $user->profile_photo;
        if (!empty($oldPhoto) && Storage::disk('public')->exists($oldPhoto)) {
            Storage::disk('public')->delete($oldPhoto);
        }

        $path = $request->file('photo')->store('profile', 'public');
        $user->update(['profile_photo' => $path]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Kata sandi berhasil diubah!');
    }
}