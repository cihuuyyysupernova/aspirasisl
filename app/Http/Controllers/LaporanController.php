<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\Feedback;

/**
 * Controller untuk mengelola laporan aspirasi dan kerusakan
 * Menangani CRUD laporan untuk siswa dan admin
 */
class LaporanController extends Controller
{
    /**
     * Menampilkan halaman form pembuatan laporan baru
     * Hanya bisa diakses oleh siswa
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('siswa.laporan-create');
    }

    /**
     * Menyimpan laporan baru yang dibuat oleh siswa
     * Melakukan validasi input dan upload foto jika ada
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:aspirasi,kerusakan',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil semua data dari request
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'menunggu';

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('laporan-foto', 'public');
            $data['foto'] = $path;
        }

        // Simpan laporan baru ke database
        Laporan::create($data);

        // Redirect ke halaman daftar laporan dengan pesan sukses
        return redirect()->route('siswa.laporan.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    /**
     * Menampilkan daftar semua laporan untuk siswa
     * Menampilkan laporan milik siswa yang sedang login
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            // Ambil laporan dengan relasi user, diurutkan dari yang terbaru
            $laporans = Laporan::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('siswa.laporan-index', compact('laporans'));
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Error in LaporanController@index: ' . $e->getMessage());
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar laporan.');
        }
    }

    /**
     * Menampilkan detail laporan untuk siswa
     * Menampilkan informasi lengkap laporan beserta feedback
     *
     * @param int $id ID laporan
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            // Load laporan dengan relasi user saja (tanpa feedback untuk menghindari error)
            $laporan = Laporan::with('user')->findOrFail($id);

            return view('siswa.laporan-show', compact('laporan'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@show: ' . $e->getMessage());
            return redirect()->route('siswa.laporan.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail laporan. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan detail laporan untuk admin
     * Menampilkan informasi lengkap laporan untuk admin
     *
     * @param int $id ID laporan
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function adminShow($id)
    {
        try {
            // Load laporan dengan relasi user saja (tanpa feedback untuk menghindari error)
            $laporan = Laporan::with('user')->findOrFail($id);

            return view('admin.laporan-show', compact('laporan'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@adminShow: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail laporan.');
        }
    }

    // ========== ADMIN METHODS ==========

    /**
     * Menampilkan daftar semua laporan untuk admin
     * Mendukung filter berdasarkan status, kategori, dan tanggal
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function adminIndex(Request $request)
    {
        try {
            // Mulai query untuk mengambil laporan dengan relasi user
            $query = Laporan::with('user');

            // Filter berdasarkan status jika dipilih
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan kategori jika dipilih
            if ($request->has('kategori') && $request->kategori != '') {
                $query->where('kategori', $request->kategori);
            }

            // Filter berdasarkan tanggal jika dipilih
            if ($request->has('tanggal') && $request->tanggal != '') {
                $tanggal = $request->tanggal;
                if ($tanggal === 'older') {
                    // Filter laporan lebih dari 1 tahun
                    $query->where('created_at', '<', now()->subYear());
                } else {
                    // Filter laporan N hari terakhir
                    $query->where('created_at', '>=', now()->subDays($tanggal));
                }
            }

            // Eksekusi query dengan pagination
            $laporans = $query->orderBy('created_at', 'desc')->paginate(10);

            return view('admin.laporan-index', compact('laporans'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@adminIndex: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar laporan.');
        }
    }

    /**
     * Menambahkan feedback dari admin untuk laporan
     * Membuat feedback baru dan update status laporan
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID laporan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFeedback(Request $request, $id)
    {
        try {
            // Validasi input feedback
            $request->validate([
                'komentar' => 'required|string',
                'status' => 'required|in:menunggu,diproses,selesai',
            ]);

            // Cari laporan berdasarkan ID
            $laporan = Laporan::findOrFail($id);

            // Debug logging untuk tracking
            Log::info('Attempting to create feedback', [
                'laporan_id' => $laporan->id,
                'admin_id' => Auth::id(),
                'komentar' => $request->komentar,
                'status' => $request->status,
            ]);

            // Buat feedback baru
            $feedback = Feedback::create([
                'komentar' => $request->komentar,
                'status_sebelumnya' => $laporan->status,
                'status_setelahnya' => $request->status,
                'laporan_id' => $laporan->id,
                'admin_id' => Auth::id(),
            ]);

            Log::info('Feedback created successfully', ['feedback_id' => $feedback->id]);

            // Update status laporan
            $laporan->update(['status' => $request->status]);

            return redirect()->back()
                ->with('success', 'Feedback berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@addFeedback: ' . $e->getMessage());
            Log::error('Exception details: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim feedback. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus laporan individual
     * Menghapus laporan beserta feedback dan foto terkait
     *
     * @param int $id ID laporan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Cari laporan yang akan dihapus
            $laporan = Laporan::findOrFail($id);

            // Log untuk audit trail
            Log::info('Admin deleted laporan', [
                'laporan_id' => $laporan->id,
                'judul' => $laporan->judul,
                'admin_id' => Auth::id(),
                'user_id' => $laporan->user_id,
            ]);

            // Hapus foto jika ada
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }

            // Hapus semua feedback terkait
            $laporan->feedbacks()->delete();

            // Hapus laporan
            $laporan->delete();

            return redirect()->route('admin.laporan.index')
                ->with('success', 'Laporan berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@destroy: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus laporan. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus laporan lama secara massal
     * Menghapus semua laporan lebih dari 1 tahun
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchDestroy()
    {
        try {
            // Hapus semua laporan lebih dari 1 tahun
            $oldLaporans = Laporan::where('created_at', '<', now()->subYear())->get();

            if ($oldLaporans->isEmpty()) {
                return redirect()->route('admin.laporan.index')
                    ->with('error', 'Tidak ada laporan lama yang dapat dihapus.');
            }

            $deletedCount = 0;
            $deletedFiles = [];

            // Proses penghapusan setiap laporan
            foreach ($oldLaporans as $laporan) {
                // Hapus foto jika ada
                if ($laporan->foto) {
                    $deletedFiles[] = $laporan->foto;
                    Storage::disk('public')->delete($laporan->foto);
                }

                // Hapus feedback terkait
                $laporan->feedbacks()->delete();

                // Hapus laporan
                $laporan->delete();
                $deletedCount++;
            }

            // Log untuk audit trail
            Log::info('Admin batch deleted old laporans', [
                'admin_id' => Auth::id(),
                'deleted_count' => $deletedCount,
                'deleted_files' => $deletedFiles,
                'cutoff_date' => now()->subYear()->toDateTimeString(),
            ]);

            return redirect()->route('admin.laporan.index')
                ->with('success', "Berhasil menghapus {$deletedCount} laporan lama secara permanen.");
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@batchDestroy: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus laporan lama. Silakan coba lagi.');
        }
    }

    /**
     * Update status laporan
     * Mengubah status laporan tanpa feedback
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id ID laporan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
        ]);

        // Cari dan update laporan
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status laporan berhasil diperbarui!');
    }
}
