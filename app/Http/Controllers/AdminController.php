<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalLaporans = Laporan::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $menungguLaporans = Laporan::where('status', 'menunggu')->count();
        $diprosesLaporans = Laporan::where('status', 'diproses')->count();
        $selesaiLaporans = Laporan::where('status', 'selesai')->count();

        $recentLaporans = Laporan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLaporans',
            'totalSiswa',
            'menungguLaporans',
            'diprosesLaporans',
            'selesaiLaporans',
            'recentLaporans'
        ));
    }

    // Siswa Management
    public function siswaIndex()
    {
        $siswas = User::where('role', 'siswa')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.siswa-index', compact('siswas'));
    }

    public function siswaCreate()
    {
        return view('admin.siswa-create');
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'identifier' => 'required|string|max:255|unique:users,identifier',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['role'] = 'siswa';

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function siswaEdit($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        return view('admin.siswa-edit', compact('siswa'));
    }

    public function siswaUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'identifier' => 'required|string|max:255|unique:users,identifier,' . $id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $data = $request->only(['name', 'email', 'identifier']);

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

    public function siswaDestroy($id)
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        // Hapus foto jika ada
        if ($siswa->profile_photo) {
            Storage::disk('public')->delete($siswa->profile_photo);
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }

    // Profile Management
    public function profile()
    {
        return view('admin.profile');
    }

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
