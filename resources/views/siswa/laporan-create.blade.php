@extends('layouts.app')

@section('title', 'Kirim Aspirasi/Laporan')

@section('page-title', 'Kirim Aspirasi/Laporan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Form Aspirasi/Laporan</h2>
        </div>

        <form action="{{ route('siswa.laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="judul"
                           name="judul"
                           required
                           value="{{ old('judul') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan judul laporan">
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori"
                            name="kategori"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <option value="aspirasi" {{ old('kategori') == 'aspirasi' ? 'selected' : '' }}>Aspirasi</option>
                        <option value="kerusakan" {{ old('kategori') == 'kerusakan' ? 'selected' : '' }}>Kerusakan Fasilitas</option>
                    </select>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="deskripsi"
                              name="deskripsi"
                              required
                              rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Jelaskan detail aspirasi atau kerusakan yang dilaporkan">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi (Opsional)
                    </label>
                    <input type="text"
                           id="lokasi"
                           name="lokasi"
                           value="{{ old('lokasi') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Lab Komputer, Ruang Kelas XI-2, Lapangan Basket">
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                        Foto (Opsional)
                    </label>
                    <input type="file"
                           id="foto"
                           name="foto"
                           accept="image/jpeg,image/png,image/jpg"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Maksimal 2MB. Format: JPEG, PNG, JPG</p>
                </div>

                <div class="flex space-x-4">
                    <button type="submit"
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium btn-hover">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan
                    </button>
                    <a href="{{ route('siswa.dashboard') }}"
                       class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200 font-medium text-center btn-hover">
                        <i class="fas fa-arrow-left mr-2"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
