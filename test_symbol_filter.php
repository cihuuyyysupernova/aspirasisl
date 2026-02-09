<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Filter Simbol</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">🧪 Test Filter Simbol</h1>
        
        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold mb-2">Test Input Simbol:</h2>
                <textarea id="testInput" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                          rows="4"
                          placeholder="Coba ketik simbol seperti: 🙂 😊 🤣 🤔 🎃 🙃 😉 😊 😇 🙂 😉 😌 😍 🥰 😘 😗 😙 😚 🙃 🙂 🤗 🤩 🥲 🥹 😋 😛 😜 🤪 😝 🤨 🧐 🤯 😶 😐 😑 😒 🙁 😞 😟 😕 🙁 ☹️ 😣 😖 😫 😩 🥺 😢 😭 😮 😯 😲 😿 😦 😧 😨 😰 😥 😪 🫣 🫤 🫥 🫦 🫧 🫨 🫩 🫪 🫰 🫱 🫲 🫳 🫴 🫵 🫶 🫷 🫸 🫹 🫺 🫻 🫼 🫽 🫿"></textarea>
                <button onclick="testFilter()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Test Filter
                </button>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Hasil Filter:</h2>
                <div id="result" class="bg-gray-50 p-4 rounded-lg min-h-[100px] font-mono text-sm"></div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Simbol yang Difilter:</h2>
                <div id="filteredSymbols" class="bg-red-50 p-4 rounded-lg min-h-[100px] text-sm"></div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Simbol yang Dihapus:</h2>
                <div id="removedSymbols" class="bg-orange-50 p-4 rounded-lg min-h-[100px] text-sm"></div>
            </div>
        </div>
    </div>

    <script>
        // Daftar simbol yang tidak diinginkan
        const forbiddenSymbols = ['🙂', '😊', '😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🙂', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '🙃', '🙂', '🤗', '🤩', '🥲', '🥹', '😋', '😛', '😜', '🤪', '😝', '🤨', '🧐', '🤯', '😶', '😐', '😑', '😒', '🙁', '😞', '😟', '😕', '🙁', '☹️', '😣', '😖', '😫', '😩', '🥺', '😢', '😭', '😮', '😯', '😲', '😿', '😦', '😧', '😨', '😰', '😥', '😪', '🫣', '🫤', '🫥', '🫦', '🫧', '🫨', '🫩', '🫪', '🫰', '🫱', '🫲', '🫳', '🫴', '🫵', '🫶', '🫷', '🫸', '🫹', '🫺', '🫻', '🫼', '🫽', '🫿'];

        function testFilter() {
            const input = document.getElementById('testInput').value;
            const result = document.getElementById('result');
            const filteredSymbols = document.getElementById('filteredSymbols');
            const removedSymbols = document.getElementById('removedSymbols');
            
            // Hapus simbol yang tidak diinginkan
            let filteredText = input;
            let removedText = '';
            
            forbiddenSymbols.forEach(symbol => {
                const regex = new RegExp(symbol.replace(/[.*+?^${}()[]/g, '\\$&'));
                const before = filteredText;
                filteredText = filteredText.replace(regex, '');
                
                if (before !== filteredText) {
                    removedText += symbol + ' ';
                }
            });
            
            // Hapus multiple simbol beruntun
            const beforeMultiple = filteredText;
            filteredText = filteredText.replace(/([^\w\s\.,\-\n\r])\1{2,}/g, '$1');
            
            if (beforeMultiple !== filteredText) {
                removedText += 'Multiple symbols ';
            }
            
            // Hapus karakter khusus yang berlebihan
            const beforeSpecial = filteredText;
            filteredText = filteredText.replace(/[^\w\s\.,\-\n\r]/g, '');
            
            if (beforeSpecial !== filteredText) {
                removedText += 'Special chars ';
            }
            
            // Tampilkan hasil
            result.textContent = filteredText;
            result.className = filteredText ? 'text-green-600' : 'text-gray-600';
            
            filteredSymbols.textContent = removedText || 'Tidak ada simbol yang difilter';
            filteredSymbols.className = removedText ? 'text-red-600' : 'text-gray-600';
            
            removedSymbols.textContent = removedText || 'Tidak ada simbol yang dihapus';
            removedSymbols.className = removedText ? 'text-orange-600' : 'text-gray-600';
        }
    </script>
</body>
</html>
