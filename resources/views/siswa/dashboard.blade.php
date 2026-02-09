@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('page-title', 'Dashboard Siswa')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow card-hover">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full pulse-on-hover">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Laporan</p>
                    <p class="text-2xl font-semibold">{{ Auth::user()->laporans()->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow card-hover">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full pulse-on-hover">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Menunggu Proses</p>
                    <p class="text-2xl font-semibold">{{ Auth::user()->laporans()->where('status', 'menunggu')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow card-hover">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full pulse-on-hover">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-semibold">{{ Auth::user()->laporans()->where('status', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow card-hover">
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold">Laporan Terbaru Saya</h2>
    </div>
    <div class="p-6">
        @php
            $recentLaporans = Auth::user()->laporans()->latest()->take(5)->get();
        @endphp
        @if($recentLaporans->count() > 0)
            <div class="space-y-4">
                @foreach($recentLaporans as $laporan)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg card-hover">
                        <div>
                            <h3 class="font-medium">{{ $laporan->judul }}</h3>
                            <p class="text-sm text-gray-600">{{ $laporan->kategori }} • {{ $laporan->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="status-badge px-3 py-1 text-xs font-medium rounded-full
                                @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                                @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                                @else bg-green-200 text-green-800
                                @endif">
                                {{ ucfirst($laporan->status) }}
                            </span>
                            <a href="{{ route('siswa.laporan.show', $laporan->id) }}"
                               class="text-blue-600 hover:text-blue-800 btn-hover">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('siswa.laporan-saya') }}" class="text-blue-600 hover:text-blue-800">
                    Lihat Semua Laporan Saya →
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Belum ada laporan</p>
                <a href="{{ route('siswa.laporan.create') }}"
                   class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 btn-hover">
                    Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
