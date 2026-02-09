@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('page-title', 'Kelola Semua Laporan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">Daftar Semua Laporan</h2>
            <div class="mt-2 flex items-center space-x-4">
                <span class="text-sm text-gray-600">
                    Total: {{ $laporans->total() }} laporan
                </span>
                @if(request('tanggal') == 'older')
                    <span class="text-sm text-orange-600 font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Menampilkan laporan lebih dari 1 tahun
                    </span>
                @endif
            </div>
        </div>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select id="kategoriFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                <option value="aspirasi" {{ request('kategori') == 'aspirasi' ? 'selected' : '' }}>Aspirasi</option>
                <option value="kerusakan" {{ request('kategori') == 'kerusakan' ? 'selected' : '' }}>Kerusakan</option>
            </select>
            <select id="tanggalFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Tanggal</option>
                <option value="7" {{ request('tanggal') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="30" {{ request('tanggal') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                <option value="90" {{ request('tanggal') == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                <option value="365" {{ request('tanggal') == '365' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                <option value="older" {{ request('tanggal') == 'older' ? 'selected' : '' }}>Lebih dari 1 Tahun</option>
            </select>
            @if(request('tanggal') == 'older')
                <button onclick="confirmBatchDelete()"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200 font-medium btn-hover">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus Semua
                </button>
            @endif
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($laporans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-4">Judul</th>
                            <th class="text-left py-3 px-4">Kategori</th>
                            <th class="text-left py-3 px-4">Pengirim</th>
                            <th class="text-left py-3 px-4">Tanggal</th>
                            <th class="text-left py-3 px-4">Status</th>
                            <th class="text-left py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $laporan)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <div>
                                        <p class="font-medium">{{ $laporan->judul }}</p>
                                        @if($laporan->lokasi)
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $laporan->lokasi }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($laporan->kategori == 'aspirasi') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($laporan->kategori) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        @if($laporan->user->profile_photo)
                                            <img src="{{ asset('storage/' . $laporan->user->profile_photo) }}"
                                                 alt="Profile"
                                                 class="w-8 h-8 rounded-full mr-2 cursor-pointer hover:opacity-90 transition-opacity"
                                                 onclick="openImageModal(this.src)"
                                                 title="Klik untuk perbesar">
                                        @else
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm mr-2">
                                                {{ strtoupper(substr($laporan->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span>{{ $laporan->user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">{{ $laporan->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <select class="status-select px-2 py-1 text-xs font-medium rounded-full border-0 cursor-pointer
                                        @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                                        @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                                        @else bg-green-200 text-green-800
                                        @endif"
                                        data-laporan-id="{{ $laporan->id }}"
                                        data-current-status="{{ $laporan->status }}">
                                        <option value="menunggu" {{ $laporan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $laporan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.laporan.show', $laporan->id) }}"
                                           class="text-blue-600 hover:text-blue-800 btn-hover">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <button onclick="confirmDelete({{ $laporan->id }}, '{{ $laporan->judul }}')"
                                                class="text-red-600 hover:text-red-800 btn-hover">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $laporans->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Belum ada laporan</p>
            </div>
        @endif
    </div>
</div>

<form id="statusForm" action="{{ route('admin.laporan.status', ':id') }}" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="statusValue">
</form>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 fade-in">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-red-100 rounded-full mr-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus laporan ini?</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <p class="text-sm font-medium text-gray-700">Laporan yang akan dihapus:</p>
            <p id="deleteLaporanTitle" class="font-semibold text-gray-900"></p>
            <p class="text-xs text-red-600 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Tindakan ini tidak dapat dibatalkan. Semua data terkait akan dihapus permanen.
            </p>
        </div>

        <form id="deleteForm" method="POST" class="flex space-x-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 font-medium btn-hover">
                <i class="fas fa-times mr-2"></i> Batal
            </button>
            <button type="submit"
                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200 font-medium btn-hover">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Batch Hapus -->
<div id="batchDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 fade-in">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-red-100 rounded-full mr-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus Massal</h3>
                <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus semua laporan lama?</p>
            </div>
        </div>

        <div class="bg-orange-50 rounded-lg p-4 mb-4">
            <p class="text-sm font-medium text-orange-700">Laporan yang akan dihapus:</p>
            <p class="font-semibold text-orange-900">Semua laporan lebih dari 1 tahun</p>
            <p class="text-xs text-red-600 mt-2">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                <strong>PERINGATAN:</strong> Tindakan ini akan menghapus {{ $laporans->total() }} laporan secara permanen dan tidak dapat dibatalkan!
            </p>
        </div>

        <form id="batchDeleteForm" method="POST" action="{{ route('admin.laporan.batchDestroy') }}" class="flex space-x-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeBatchDeleteModal()"
                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 font-medium btn-hover">
                <i class="fas fa-times mr-2"></i> Batal
            </button>
            <button type="submit"
                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-200 font-medium btn-hover">
                <i class="fas fa-trash-alt mr-2"></i> Hapus Semua
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const laporanId = this.dataset.laporanId;
            const currentStatus = this.dataset.currentStatus;
            const newStatus = this.value;

            if (currentStatus !== newStatus) {
                const form = document.getElementById('statusForm');
                form.action = form.action.replace(':id', laporanId);
                document.getElementById('statusValue').value = newStatus;
                form.submit();
            }
        });
    });

    // Handle filters
    document.getElementById('statusFilter').addEventListener('change', function() {
        applyFilters();
    });

    document.getElementById('kategoriFilter').addEventListener('change', function() {
        applyFilters();
    });

    document.getElementById('tanggalFilter').addEventListener('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const url = new URL(window.location);
        const status = document.getElementById('statusFilter').value;
        const kategori = document.getElementById('kategoriFilter').value;
        const tanggal = document.getElementById('tanggalFilter').value;

        // Clear existing params
        url.searchParams.delete('status');
        url.searchParams.delete('kategori');
        url.searchParams.delete('tanggal');

        // Add new params
        if (status) url.searchParams.set('status', status);
        if (kategori) url.searchParams.set('kategori', kategori);
        if (tanggal) url.searchParams.set('tanggal', tanggal);

        window.location.href = url.toString();
    }
});

// Fungsi untuk konfirmasi hapus
function confirmDelete(laporanId, laporanTitle) {
    document.getElementById('deleteLaporanTitle').textContent = laporanTitle;
    document.getElementById('deleteForm').action = '{{ route('admin.laporan.destroy', ':id') }}'.replace(':id', laporanId);
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

// Fungsi untuk konfirmasi batch hapus
function confirmBatchDelete() {
    document.getElementById('batchDeleteModal').style.display = 'flex';
    document.getElementById('batchDeleteModal').classList.remove('hidden');
    document.getElementById('batchDeleteModal').classList.add('flex');
}

// Fungsi untuk menutup modal
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Fungsi untuk menutup modal batch
function closeBatchDeleteModal() {
    document.getElementById('batchDeleteModal').style.display = 'none';
    document.getElementById('batchDeleteModal').classList.add('hidden');
    document.getElementById('batchDeleteModal').classList.remove('flex');
}

// Close modal saat klik di luar
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('batchDeleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBatchDeleteModal();
    }
});

// Close modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeBatchDeleteModal();
        closeImageModal();
    }
});

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
                     alt="Foto"
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
@endsection
