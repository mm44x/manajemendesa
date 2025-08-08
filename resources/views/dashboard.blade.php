<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($role === 'bendahara')
                <div class="grid md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-green-50 dark:bg-green-900 rounded shadow p-4 text-center">
                        <div class="text-xl font-bold text-green-700 dark:text-green-300">Rp
                            {{ number_format($summary['nominal_setoran_bulan_ini'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-gray-500 dark:text-gray-300">Setoran Bulan Ini</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900 rounded shadow p-4 text-center">
                        <div class="text-xl font-bold text-red-700 dark:text-red-300">Rp
                            {{ number_format($summary['tunggakan_bulan_ini'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-gray-500 dark:text-gray-300">Tunggakan Bulan Ini</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900 rounded shadow p-4 text-center">
                        <div class="text-xl font-bold text-blue-700 dark:text-blue-300">
                            {{ $summary['persen_setor'] ?? 0 }}%</div>
                        <div class="text-gray-500 dark:text-gray-300">KK Sudah Setor</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900 rounded shadow p-4 text-center">
                        <div class="text-xl font-bold text-yellow-700 dark:text-yellow-300">
                            {{ $summary['iuran_aktif'] ?? 0 }}</div>
                        <div class="text-gray-500 dark:text-gray-300">Iuran Aktif</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
                    <div class="font-bold mb-2">Setoran Terbaru</div>
                    <ul>
                        @forelse($setoran_terbaru as $s)
                            <li class="border-b py-2 flex justify-between items-center">
                                <span>{{ $s->kartuKeluarga->kepala_keluarga ?? '-' }}
                                    ({{ $s->kartuKeluarga->no_kk ?? '-' }})
                                </span>
                                <span class="font-bold">Rp {{ number_format($s->nominal_dibayar, 0, ',', '.') }}</span>
                                <span
                                    class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($s->tanggal_setor)->translatedFormat('d M Y') }}</span>
                            </li>
                        @empty
                            <li class="py-2 text-gray-400">Belum ada setoran</li>
                        @endforelse
                    </ul>
                </div>
                <div class="mb-8">
                    <div class="font-bold mb-2">Tren Setoran 12 Bulan Terakhir</div>
                    <canvas id="chartSetoran" height="70"></canvas>
                </div>
            @endif
            {{-- Role lain bisa ditambah disini --}}
        </div>
    </div>

    @if ($role === 'bendahara')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const bulan = @json($bulan);
            const nominal = @json($nominal_per_bulan);

            // Cek dark mode
            const isDark = document.documentElement.classList.contains('dark');

            const axisTextColor = isDark ? '#fff' : '#222'; // dark: putih, light: abu tua
            const gridColor = isDark ? '#374151' : '#e5e7eb'; // dark: gray-700, light: gray-200

            const ctx = document.getElementById('chartSetoran').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: bulan,
                    datasets: [{
                        label: 'Setoran',
                        data: nominal,
                        fill: true,
                        borderColor: isDark ? '#38bdf8' : '#2563eb',
                        backgroundColor: isDark ? 'rgba(56,189,248,0.15)' : 'rgba(37,99,235,0.10)',
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: isDark ? '#38bdf8' : '#2563eb',
                        pointBorderColor: '#fff',
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: axisTextColor
                            },
                            grid: {
                                color: gridColor
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: axisTextColor,
                                callback: val => 'Rp ' + val.toLocaleString('id-ID')
                            },
                            grid: {
                                color: gridColor
                            },
                        }
                    }
                }
            });

            // Auto reload chart on theme switch
            if (window.matchMedia('(prefers-color-scheme: dark)').media !== 'not all') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                    location.reload();
                });
            }
        </script>
    @endif

</x-app-layout>
