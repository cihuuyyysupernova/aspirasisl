<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan;

/**
 * Controller untuk mengelola halaman admin
 * Menangani dashboard, manajemen siswa, dan profile admin
 */
class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     * Menampilkan statistik laporan dan laporan terbaru
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Hitung statistik laporan
        $totalLaporans = Laporan::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $menungguLaporans = Laporan::where('status', 'menunggu')->count();
        $diprosesLaporans = Laporan::where('status', 'diproses')->count();
        $selesaiLaporans = Laporan::where('status', 'selesai')->count();

        // Ambil 5 laporan terbaru dengan relasi user
        $recentLaporans = Laporan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalLaporans',
            'totalSiswa',
            'menungguLaporans',
            'diprosesLaporans',
            'selesaiLaporans',
            'recentLaporans'
        ));
    }

    // ========== SISWA MANAGEMENT ==========

    /**
     * Menampilkan daftar semua siswa
     * Menampilkan list siswa dengan pagination
     *
     * @return \Illuminate\View\View
     */
    public function siswaIndex()
    {
        // Ambil semua siswa, diurutkan berdasarkan nama
        $siswas = User::where('role', 'siswa')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.siswa-index', compact('siswas'));
    }

    /**
     * Menampilkan form pembuatan siswa baru
     * Menampilkan form untuk menambah siswa
     *
     * @return \Illuminate\View\View
     */
    public function siswaCreate()
    {
        return view('admin.siswa-create');
    }

    /**
     * Menyimpan siswa baru
     * Membuat akun siswa baru dengan validasi
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function siswaStore(Request $request)
    {
        // Validasi input siswa
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'identifier' => 'required|string|max:255|unique:users,identifier',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data dan set role sebagai siswa
        $data = $request->all();
        $data['role'] = 'siswa';

        // Handle upload foto profile jika ada
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        // Buat siswa baru
        User::create($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit siswa
     * Menampilkan form untuk mengedit data siswa
     *
     * @param int $id ID siswa
     * @return \Illuminate\View\View
     */
    public function siswaEdit($id)
    {
        // Cari siswa berdasarkan ID
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        return view('admin.siswa-edit', compact('siswa'));
    }

    /**
     * Mengupdate data siswa
     * Mengupdate informasi siswa yang sudah ada
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function siswaUpdate(Request $request, $id)
    {
        // Validasi input update siswa
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'identifier' => 'required|string|max:255|unique:users,identifier,' . $id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cari siswa yang akan diupdate
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $data = $request->only(['name', 'email', 'identifier']);

        // Handle upload foto profile baru jika ada
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($siswa->profile_photo) {
                Storage::disk('public')->delete($siswa->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus siswa
     * Menghapus siswa beserta foto profile terkait
     *
     * @param int $id ID siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function siswaDestroy($id)
    {
        // Cari siswa yang akan dihapus
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        // Hapus foto jika ada
        if ($siswa->profile_photo) {
            Storage::disk('public')->delete($siswa->profile_photo);
        }

        // Hapus siswa
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    // ========== PROFILE MANAGEMENT ==========

    /**
     * Menampilkan halaman profile admin
     * Menampilkan informasi profile dan form edit
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('admin.profile');
    }

    /**
     * Mengupdate profile admin
     * Menangani update nama, email, dan foto profile admin
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            // Validasi input profile
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Ambil admin yang sedang login
            $user = Auth::user();

            // Update data profile
            $user->name = $request->name;
            $user->email = $request->email;

            // Handle upload foto profile jika ada
            if ($request->hasFile('profile_photo')) {
                // Hapus foto lama jika ada
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Upload foto baru
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo = $path;
            }

            $user->save();

            return redirect()->route('admin.profile')
                ->with('success', 'Profile berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile')
                ->with('error', 'Terjadi kesalahan saat memperbarui profile. Silakan coba lagi.');
        }
    }
}
