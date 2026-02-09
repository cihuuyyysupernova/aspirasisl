@extends('layouts.app')

@section('title', 'Data Siswa')

@section('page-title', 'Data Siswa')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Daftar Siswa</h2>
        <a href="{{ route('admin.siswa.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Siswa
        </a>
    </div>

    <div class="p-6">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($siswas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-4">Foto</th>
                            <th class="text-left py-3 px-4">Nama</th>
                            <th class="text-left py-3 px-4">Email</th>
                            <th class="text-left py-3 px-4">NISN</th>
                            <th class="text-left py-3 px-4">Total Laporan</th>
                            <th class="text-left py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $siswa)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    @if($siswa->profile_photo)
                                        <img src="{{ asset('storage/' . $siswa->profile_photo) }}" 
                                             alt="Profile" class="w-10 h-10 rounded-full">
                                    @else
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($siswa->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-medium">{{ $siswa->name }}</td>
                                <td class="py-3 px-4">{{ $siswa->email }}</td>
                                <td class="py-3 px-4">{{ $siswa->identifier }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $siswa->laporans()->count() }} laporan
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $siswas->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">Belum ada data siswa</p>
                <a href="{{ route('admin.siswa.create') }}" 
                   class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Tambah Siswa Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
