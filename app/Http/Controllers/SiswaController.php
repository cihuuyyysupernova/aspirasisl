<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Laporan;

/**
 * Controller untuk mengelola halaman siswa
 * Menangani dashboard, laporan, dan profile siswa
 */
class SiswaController extends Controller
{
    /**
     * Menampilkan halaman dashboard siswa
     * Menampilkan statistik dan laporan terbaru
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('siswa.dashboard');
    }

    /**
     * Menampilkan daftar laporan milik siswa yang sedang login
     * Menampilkan semua laporan yang dibuat oleh siswa tersebut
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function laporanSaya()
    {
        try {
            // Ambil laporan milik user yang sedang login, diurutkan dari yang terbaru
            $laporans = Auth::user()->laporans()
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('siswa.laporan-saya', compact('laporans'));
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Error in SiswaController@laporanSaya: ' . $e->getMessage());
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat laporan Anda.');
        }
    }

    /**
     * Menampilkan halaman profile siswa
     * Menampilkan informasi profile dan form edit
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('siswa.profile');
    }

    /**
     * Mengupdate profile siswa
     * Menangani update nama, email, dan foto profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'email']);

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->back()
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
