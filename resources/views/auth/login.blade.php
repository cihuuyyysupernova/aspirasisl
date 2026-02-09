<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aspirasi Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md fade-in">
        <div class="text-center mb-8">
            <i class="fas fa-graduation-cap text-6xl text-blue-600 mb-4 bounce-on-hover"></i>
            <h1 class="text-3xl font-bold text-gray-800">Aspirasi Sekolah</h1>
            <p class="text-gray-600 mt-2">Sistem Aspirasi & Pelaporan Kerusakan</p>
        </div>

        <!-- Pilihan Role -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Login Sebagai:</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative">
                    <input type="radio" name="role" value="siswa" class="peer sr-only" checked>
                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 card-hover">
                        <i class="fas fa-user-graduate text-2xl mb-2 text-gray-600 peer-checked:text-blue-600"></i>
                        <p class="font-medium text-gray-700 peer-checked:text-blue-700">Siswa</p>
                        <p class="text-xs text-gray-500 mt-1">Gunakan NISN</p>
                    </div>
                </label>
                <label class="relative">
                    <input type="radio" name="role" value="admin" class="peer sr-only">
                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-gray-300 card-hover">
                        <i class="fas fa-user-shield text-2xl mb-2 text-gray-600 peer-checked:text-blue-600"></i>
                        <p class="font-medium text-gray-700 peer-checked:text-blue-700">Admin</p>
                        <p class="text-xs text-gray-500 mt-1">Gunakan Email</p>
                    </div>
                </label>
            </div>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded fade-in">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1"></i>
                    <span id="label-text">NISN / Username</span>
                </label>
                <input type="text"
                       id="identifier"
                       name="identifier"
                       required
                       value="{{ old('identifier') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent btn-hover"
                       placeholder="Masukkan NISN atau Username">
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span id="info-text">
                        <strong>Siswa:</strong> Gunakan NISN atau Username yang terdaftar
                    </span>
                </p>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium btn-hover">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Tidak perlu password, cukup masukkan identifier Anda
            </p>
        </div>

        <!-- Info Login Demo -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4 card-hover">
            <p class="text-xs font-medium text-gray-700 mb-2">🔑 Akun Demo:</p>
            <div class="text-xs text-gray-600 space-y-1">
                <p><strong>Admin:</strong> admin@sekolah.sch.id</p>
                <p><strong>Siswa:</strong> 1234567890 (Ahmad Rizki)</p>
                <p><strong>Siswa:</strong> 0987654321 (Siti Nurhaliza)</p>
            </div>
        </div>
    </div>

    <script>
        // Handle role selection
        document.querySelectorAll('input[name="role"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const labelText = document.getElementById('label-text');
                const infoText = document.getElementById('info-text');
                const identifierInput = document.getElementById('identifier');

                if (this.value === 'admin') {
                    labelText.textContent = 'Email Admin';
                    infoText.innerHTML = '<strong>Admin:</strong> Gunakan email yang terdaftar';
                    identifierInput.placeholder = 'admin@sekolah.sch.id';
                } else {
                    labelText.textContent = 'NISN / Username';
                    infoText.innerHTML = '<strong>Siswa:</strong> Gunakan NISN atau Username yang terdaftar';
                    identifierInput.placeholder = 'Masukkan NISN atau Username';
                }
            });
        });

        // Add input focus animations
        const inputs = document.querySelectorAll('input[type="text"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });
    </script>
</body>
</html>
