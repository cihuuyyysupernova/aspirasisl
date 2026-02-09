@extends('layouts.app')

@section('title', 'Semua Laporan')

@section('page-title', 'Semua Laporan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Daftar Semua Laporan</h2>
        <a href="{{ route('siswa.laporan.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Buat Laporan
        </a>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($laporans) && $laporans->count() > 0)
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
                                        @if($laporan->user && $laporan->user->profile_photo)
                                            <img src="{{ asset('storage/' . $laporan->user->profile_photo) }}"
                                                 alt="Profile" class="w-8 h-8 rounded-full mr-2">
                                        @else
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm mr-2">
                                                {{ $laporan->user ? strtoupper(substr($laporan->user->name, 0, 1)) : '?' }}
                                            </div>
                                        @endif
                                        <span>{{ $laporan->user ? $laporan->user->name : 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">{{ $laporan->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                                        @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                                        @else bg-green-200 text-green-800
                                        @endif">
                                        {{ ucfirst($laporan->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('siswa.laporan.show', $laporan->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
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
                <a href="{{ route('siswa.laporan.create') }}"
                   class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
