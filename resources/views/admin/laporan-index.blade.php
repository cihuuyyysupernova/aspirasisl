@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('page-title', 'Kelola Semua Laporan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Daftar Semua Laporan</h2>
        <div class="flex space-x-2">
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
            </select>
            <select id="kategoriFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                <option value="aspirasi">Aspirasi</option>
                <option value="kerusakan">Kerusakan</option>
            </select>
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
                                                 alt="Profile" class="w-8 h-8 rounded-full mr-2">
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
                                    <a href="{{ route('admin.laporan.show', $laporan->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 mr-2">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
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
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set('status', this.value);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    });

    document.getElementById('kategoriFilter').addEventListener('change', function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set('kategori', this.value);
        } else {
            url.searchParams.delete('kategori');
        }
        window.location.href = url.toString();
    });
});
</script>
@endsection
