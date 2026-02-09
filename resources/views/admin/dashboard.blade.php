@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Siswa</p>
                <p class="text-2xl font-semibold">{{ $totalSiswa }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full">
                <i class="fas fa-file-alt text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Laporan</p>
                <p class="text-2xl font-semibold">{{ $totalLaporans }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Menunggu</p>
                <p class="text-2xl font-semibold">{{ $menungguLaporans }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-semibold">{{ $selesaiLaporans }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Statistik Laporan</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Menunggu Proses</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $totalLaporans > 0 ? ($menungguLaporans / $totalLaporans * 100) : 0 }}%"></div>
                        </div>
                        <span class="font-medium">{{ $menungguLaporans }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Diproses</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalLaporans > 0 ? ($diprosesLaporans / $totalLaporans * 100) : 0 }}%"></div>
                        </div>
                        <span class="font-medium">{{ $diprosesLaporans }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Selesai</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalLaporans > 0 ? ($selesaiLaporans / $totalLaporans * 100) : 0 }}%"></div>
                        </div>
                        <span class="font-medium">{{ $selesaiLaporans }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Laporan Terbaru</h2>
        </div>
        <div class="p-6">
            @if($recentLaporans->count() > 0)
                <div class="space-y-4">
                    @foreach($recentLaporans as $laporan)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-sm">{{ $laporan->judul }}</h4>
                                <p class="text-xs text-gray-600">{{ $laporan->user->name }} • {{ $laporan->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                                    @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                                    @else bg-green-200 text-green-800
                                    @endif">
                                    {{ ucfirst($laporan->status) }}
                                </span>
                                <a href="{{ route('admin.laporan.show', $laporan->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.laporan.index') }}" class="text-blue-600 hover:text-blue-800">
                        Lihat Semua Laporan →
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-600 text-sm">Belum ada laporan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
