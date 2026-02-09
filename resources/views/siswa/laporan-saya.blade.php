@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('page-title', 'Laporan Saya')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Daftar Laporan Saya</h2>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($laporans as $laporan)
                    <div class="border rounded-lg hover:shadow-lg transition-shadow">
                        @if($laporan->foto)
                            <img src="{{ asset('storage/' . $laporan->foto) }}"
                                 alt="{{ $laporan->judul }}"
                                 class="w-full h-48 object-cover rounded-t-lg">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg">{{ $laporan->judul }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($laporan->status == 'menunggu') bg-gray-200 text-gray-800
                                    @elseif($laporan->status == 'diproses') bg-yellow-200 text-yellow-800
                                    @else bg-green-200 text-green-800
                                    @endif">
                                    {{ ucfirst($laporan->status) }}
                                </span>
                            </div>

                            <div class="space-y-1 mb-3">
                                <p class="text-sm text-gray-600">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($laporan->kategori == 'aspirasi') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($laporan->kategori) }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $laporan->created_at->format('d M Y') }}
                                </p>
                                @if($laporan->lokasi)
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $laporan->lokasi }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $laporan->deskripsi }}</p>
                                <a href="{{ route('siswa.laporan.show', $laporan->id) }}"
                                   class="text-blue-600 hover:text-blue-800 ml-2">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            @php
                                $feedbackCount = \App\Models\Feedback::where('laporan_id', $laporan->id)->count();
                            @endphp
                            @if($feedbackCount > 0)
                                <div class="mt-3 pt-3 border-t">
                                    <p class="text-sm text-green-600">
                                        <i class="fas fa-comment-check mr-1"></i>
                                        {{ $feedbackCount }} feedback
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $laporans->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Anda belum membuat laporan apapun</p>
                <a href="{{ route('siswa.laporan.create') }}"
                   class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
