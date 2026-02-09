<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\Feedback;

class LaporanController extends Controller
{
    // Siswa methods
    public function create()
    {
        return view('siswa.laporan-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:aspirasi,kerusakan',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'menunggu';

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('laporan-foto', 'public');
            $data['foto'] = $path;
        }

        Laporan::create($data);

        return redirect()->route('siswa.laporan.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    public function index()
    {
        try {
            $laporans = Laporan::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('siswa.laporan-index', compact('laporans'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@index: ' . $e->getMessage());
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar laporan.');
        }
    }

    public function show($id)
    {
        try {
            // Load laporan dengan relasi user saja
            $laporan = Laporan::with('user')->findOrFail($id);

            return view('siswa.laporan-show', compact('laporan'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@show: ' . $e->getMessage());
            return redirect()->route('siswa.laporan.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail laporan. Silakan coba lagi.');
        }
    }

    public function adminShow($id)
    {
        try {
            // Load laporan dengan relasi user saja
            $laporan = Laporan::with('user')->findOrFail($id);

            return view('admin.laporan-show', compact('laporan'));
        } catch (\Exception $e) {
            Log::error('Error in LaporanController@adminShow: ' . $e->getMessage());
            return redirect()->route('admin.laporan.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail laporan.');
        }
    }

    // Admin methods
    public function adminIndex()
    {
        $laporans = Laporan::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.laporan-index', compact('laporans'));
    }

    public function addFeedback(Request $request, $id)
    {
        try {
            $request->validate([
                'komentar' => 'required|string',
                'status' => 'required|in:menunggu,diproses,selesai',
            ]);

            $laporan = Laporan::findOrFail($id);

            // Debug logging
            Log::info('Attempting to create feedback', [
                'laporan_id' => $laporan->id,
                'admin_id' => Auth::id(),
                'komentar' => $request->komentar,
                'status' => $request->status,
            ]);

            $feedback = Feedback::create([
                'komentar' => $request->komentar,
                'status_sebelumnya' => $laporan->status,
                'status_setelahnya' => $request->status,
                'laporan_id' => $laporan->id,
                'admin_id' => Auth::id(),
            ]);

            Log::info('Feedback created successfully', ['feedback_id' => $feedback->id]);

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status laporan berhasil diperbarui!');
    }
}
