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

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold">{{ $laporan->judul }}</h2>
                    <p class="text-gray-600 mt-1">ID: #{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                    @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                    @else bg-green-200 text-green-800
                    @endif">
                    {{ ucfirst($laporan->status) }}
                </span>
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
                            <span>{{ $laporan->user->name }}</span>
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
                             class="w-full max-w-sm rounded-lg shadow">
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
            <div>
                <h3 class="font-semibold mb-3">Feedback Admin</h3>
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
                        <p class="text-gray-600">Belum ada feedback dari admin</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('siswa.laporan.index') }}"
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                @if($laporan->user_id === Auth::id())
                    <a href="{{ route('siswa.laporan.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Buat Laporan Baru
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
