@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('page-title', 'Detail Laporan')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold">{{ $laporan->judul }}</h2>
                    <p class="text-gray-600 mt-1">ID: #{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <select class="px-3 py-1 text-sm font-medium rounded-full border-0 cursor-pointer
                    @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                    @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                    @else bg-green-200 text-green-800
                    @endif"
                    onchange="updateStatus(this.value)">
                    <option value="menunggu" {{ $laporan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $laporan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </div>

        <div class="p-6">
            <!-- Informasi Laporan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold mb-3">Informasi Laporan</h3>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-24">Kategori:</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($laporan->kategori == 'aspirasi') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($laporan->kategori) }}
                            </span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-24">Pengirim:</span>
                            <div class="flex items-center">
                                @if($laporan->user->profile_photo)
                                    <img src="{{ asset('storage/' . $laporan->user->profile_photo) }}"
                                         alt="Profile" class="w-6 h-6 rounded-full mr-2">
                                @else
                                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-xs mr-2">
                                        {{ strtoupper(substr($laporan->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ $laporan->user->name }}</span>
                            </div>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-24">NISN:</span>
                            <span>{{ $laporan->user->identifier }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-24">Tanggal:</span>
                            <span>{{ $laporan->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($laporan->lokasi)
                            <div class="flex">
                                <span class="text-gray-600 w-24">Lokasi:</span>
                                <span>{{ $laporan->lokasi }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($laporan->foto)
                    <div>
                        <h3 class="font-semibold mb-3">Foto</h3>
                        <img src="{{ asset('storage/' . $laporan->foto) }}"
                             alt="Foto Laporan"
                             class="w-full max-w-sm rounded-lg shadow cursor-pointer hover:opacity-90 transition-opacity"
                             onclick="openImageModal(this.src)">
                    </div>
                @endif
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <h3 class="font-semibold mb-3">Deskripsi</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="whitespace-pre-wrap">{{ $laporan->deskripsi }}</p>
                </div>
            </div>

            <!-- Feedback Admin -->
            <div class="mb-6">
                <h3 class="font-semibold mb-3">Riwayat Feedback</h3>
                @php
                    $feedbacks = \App\Models\Feedback::with('admin')
                        ->where('laporan_id', $laporan->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                @if($feedbacks->count() > 0)
                    <div class="space-y-4">
                        @foreach($feedbacks as $feedback)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-medium">{{ $feedback->admin ? $feedback->admin->name : 'Admin' }}</span>
                                        <span class="text-sm text-gray-600 ml-2">
                                            {{ $feedback->created_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">
                                            {{ ucfirst($feedback->status_sebelumnya) }}
                                        </span>
                                        <i class="fas fa-arrow-right text-gray-400"></i>
                                        <span class="text-sm font-medium">
                                            {{ ucfirst($feedback->status_setelahnya) }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-gray-700">{{ $feedback->komentar }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <i class="fas fa-comment-slash text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-600">Belum ada feedback</p>
                    </div>
                @endif
            </div>

            <!-- Tambah Feedback -->
            <div class="border-t pt-6">
                <h3 class="font-semibold mb-3">Tambah Feedback</h3>
                <form action="{{ route('admin.laporan.feedback', $laporan->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">
                                Komentar <span class="text-red-500">*</span>
                            </label>
                            <textarea id="komentar"
                                      name="komentar"
                                      required
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Masukkan komentar atau tanggapan untuk laporan ini"
                                      oninput="filterSymbols(this)"
                                      onpaste="setTimeout(() => filterSymbols(this), 10)"></textarea>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Status</option>
                                <option value="menunggu" {{ $laporan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $laporan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Feedback
                            </button>
                            <a href="{{ route('admin.laporan.index') }}"
                               class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="statusForm" action="{{ route('admin.laporan.status', $laporan->id) }}" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusValue">
</form>

<script>
// Daftar simbol yang tidak diinginkan
const forbiddenSymbols = ['🙂', '😊', '😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🙂', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '🙃', '🙂', '🤗', '🤩', '🥲', '🥹', '😋', '😛', '😜', '🤪', '😝', '🤨', '🧐', '🤯', '😶', '😐', '😑', '😒', '🙁', '😞', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫', '😩', '🥺', '😢', '😭', '😮', '😯', '😲', '😿', '😦', '😧', '😨', '😰', '😥', '😪', '🫣', '🫤', '🫥', '🫦', '🫧', '🫨', '🫩', '🫪', '🫰', '🫱', '🫲', '🫳', '🫴', '🫵', '🫶', '🫷', '🫸', '🫹', '🫺', '🫻', '🫼', '🫽', '🫿'];

function filterSymbols(element) {
    let text = element.value;

    // Hapus simbol yang tidak diinginkan
    forbiddenSymbols.forEach(symbol => {
        const regex = new RegExp(symbol.replace(/[.*+?^${}()[]/g, '\\$&'));
        text = text.replace(regex, '');
    });

    // Hapus multiple simbol beruntun
    text = text.replace(/([^\w\s\.,\-\n\r])\1{2,}/g, '$1');

    // Hapus karakter khusus yang berlebihan
    text = text.replace(/[^\w\s\.,\-\n\r]/g, '');

    element.value = text;
}

document.addEventListener('DOMContentLoaded', function() {
    // Filter semua field saat halaman dimuat
    const fields = ['komentar'];
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            filterSymbols(field);

            // Filter saat user mengetik
            field.addEventListener('input', function() {
                filterSymbols(this);
            });

            // Filter saat user paste
            field.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const filteredData = pastedData.replace(/[^\w\s\.,\-\n\r]/g, '');
                document.execCommand('insertText', false, filteredData);
            });
        }
    });
});

function updateStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status laporan ini?')) {
        document.getElementById('statusValue').value = status;
        document.getElementById('statusForm').submit();
    }
}

/**
 * Fungsi untuk membuka modal foto
 * Menampilkan foto dalam modal overlay dengan tombol close
 *
 * @param {string} imageSrc - Source URL gambar yang akan ditampilkan
 */
function openImageModal(imageSrc) {
    // Buat modal element jika belum ada
    let modal = document.getElementById('imageModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <!-- Tombol Close -->
                <button onclick="closeImageModal()"
                        class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors"
                        title="Tutup (ESC)">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Gambar -->
                <img id="modalImage"
                     src=""
                     alt="Foto Laporan"
                     class="max-w-full max-h-full rounded-lg shadow-2xl">
            </div>
        `;

        // Tambahkan event listener untuk ESC key
        modal.addEventListener('click', function(e) {
            // Tutup modal jika klik di luar gambar
            if (e.target === modal) {
                closeImageModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            // Tutup modal dengan ESC key
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        document.body.appendChild(modal);
    }

    // Set source gambar dan tampilkan modal
    document.getElementById('modalImage').src = imageSrc;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent scroll
}

/**
 * Fungsi untuk menutup modal foto
 * Menghapus modal dan mengembalikan scroll
 */
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scroll
    }
}
</script>

<!-- Modal untuk foto (akan ditambahkan secara dinamis) -->
@endsection
