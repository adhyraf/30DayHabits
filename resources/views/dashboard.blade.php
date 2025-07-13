<!DOCTYPE html>
<html lang="id" class="transition">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100 min-h-screen font-sans transition-colors duration-300">
    <div class="container mx-auto p-6">

        <!-- Tombol Dark Mode di Kanan Atas -->
        <div class="flex justify-end mb-4">
            <button id="toggle-dark" class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                🌙 Dark Mode
            </button>
        </div>

        <h1 class="text-4xl font-bold text-center mb-8 text-blue-700 dark:text-blue-300">🌱 30 Day Habits 🌱</h1>

        <!-- Progress Bar -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-center">📊 Progress Tantangan</h2>
            <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-6 overflow-hidden">
                <div id="progress-bar" class="bg-green-500 h-full text-white text-sm font-bold text-center transition-all duration-500" style="width: 0%">
                    0%
                </div>
            </div>
        </div>

        <!-- Grid Hari -->
        <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4" id="day-list">
            @for ($i = 1; $i <= 30; $i++)
                <li>
                    <a href="{{ url('/day/' . $i) }}"
                       class="day-box block bg-white dark:bg-gray-800 rounded-lg shadow text-center p-4 transition font-semibold text-lg"
                       data-day="{{ $i }}">
                        Hari {{ $i }}
                    </a>
                </li>
            @endfor
        </ul>

        <!-- Tombol Reset & Export di Bawah -->
        <div class="flex flex-col sm:flex-row justify-center mt-10 gap-4 text-center">
            <button id="reset-progress" class="bg-red-500 text-white px-6 py-3 rounded hover:bg-red-600 transition">
                🔁 Reset Progress
            </button>
            <button id="export-refleksi" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600 transition">
                📤 Export Refleksi
            </button>
        </div>

        <div class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
            Dibuat untuk latihan disiplin & persiapan masuk PTN 💪
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const totalDays = 30;
            let completed = 0;

            const dayLinks = document.querySelectorAll('.day-box');

            dayLinks.forEach(link => {
                const day = link.getAttribute('data-day');
                const status = localStorage.getItem('day_' + day);

                if (status === 'true') {
                    link.classList.remove('bg-white', 'dark:bg-gray-800');
                    link.classList.add('bg-green-200', 'text-green-900', 'dark:bg-green-400', 'dark:text-green-900');
                    link.innerHTML += ' ✅';
                    completed++;
                }
            });

            const percent = Math.round((completed / totalDays) * 100);
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.width = percent + '%';
            progressBar.innerText = `${completed}/${totalDays} (${percent}%)`;

            // INIT THEME FROM STORAGE
            const html = document.documentElement;
            const darkToggle = document.getElementById('toggle-dark');

            if (localStorage.getItem('theme') === 'dark') {
                html.classList.add('dark');
                darkToggle.innerText = '☀️ Light Mode';
            } else {
                html.classList.remove('dark');
                darkToggle.innerText = '🌙 Dark Mode';
            }

            // TOGGLE THEME
            darkToggle.addEventListener('click', () => {
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                darkToggle.innerText = isDark ? '☀️ Light Mode' : '🌙 Dark Mode';
            });

            // RESET PROGRESS
            const resetButton = document.getElementById('reset-progress');
            resetButton.addEventListener('click', () => {
                if (confirm('Yakin ingin mereset semua progress dan refleksi?')) {
                    for (let i = 1; i <= totalDays; i++) {
                        localStorage.removeItem('day_' + i);
                        localStorage.removeItem('refleksi_' + i);
                    }
                    localStorage.removeItem('theme');
                    location.reload();
                }
            });

            // EXPORT REFLEKSI
            const exportBtn = document.getElementById('export-refleksi');
            exportBtn.addEventListener('click', () => {
                let content = '';
                for (let i = 1; i <= totalDays; i++) {
                    const ref = localStorage.getItem('refleksi_' + i);
                    if (ref && ref.trim() !== '') {
                        content += `Hari ${i}:\n${ref}\n\n`;
                    }
                }

                if (content.trim() === '') {
                    alert('Belum ada refleksi yang ditulis.');
                    return;
                }

                const blob = new Blob([content], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'refleksi_30_hari.txt';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            });
        });
    </script>
</body>
</html>
