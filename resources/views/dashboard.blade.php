<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($role === 'bendahara')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <div class="p-4 text-gray-900 dark:text-white">
                        <div class="grid md:grid-cols-4 gap-4">
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
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <div class="p-4 text-gray-900 dark:text-white">
                        <div class="bg-white dark:bg-gray-800 rounded shadow p-4">
                            {{-- Header rapih --}}
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <div class="font-bold">Setoran Terbaru</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Update terakhir:
                                        {{ now()->translatedFormat('d M Y, H:i') }}</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label for="latestLimit"
                                        class="text-xs text-gray-500 dark:text-gray-400">Tampilkan</label>
                                    <select id="latestLimit"
                                        class="text-sm rounded border px-2 py-1 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>
                            </div>

                            {{-- List --}}
                            <ul id="latestList">
                                @php $__i=0; @endphp
                                @forelse($setoran_terbaru as $s)
                                    <li class="item-setoran border-b py-2 flex flex-col md:flex-row md:items-center md:justify-between gap-1"
                                        data-idx="{{ $__i++ }}">
                                        <span class="font-medium">
                                            {{ $s->kartuKeluarga->kepala_keluarga ?? '-' }}
                                            ({{ $s->kartuKeluarga->no_kk ?? '-' }})
                                        </span>
                                        <div class="flex items-center justify-between md:justify-end gap-3">
                                            <span class="font-bold">Rp
                                                {{ number_format($s->nominal_dibayar, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($s->tanggal_setor)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-2 text-gray-400">Belum ada setoran</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <div class="p-4 text-gray-900 dark:text-white">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold">Tren Setoran</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Periode: 12 bulan terakhir</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="chartType" class="text-xs text-gray-500 dark:text-gray-400">Jenis</label>
                                <select id="chartType"
                                    class="text-sm rounded border px-2 py-1 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <option value="line" selected>Line</option>
                                    <option value="bar">Bar</option>
                                </select>
                                <label for="chartRange"
                                    class="text-xs text-gray-500 dark:text-gray-400 ml-2">Rentang</label>
                                <select id="chartRange"
                                    class="text-sm rounded border px-2 py-1 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <option value="6">6 Bulan</option>
                                    <option value="12" selected>12 Bulan</option>
                                </select>
                            </div>
                        </div>
                        <canvas id="chartSetoran" height="70"></canvas>
                    </div>
                </div>

            @endif

            @if ($role === 'bendahara')
            @endif
            {{-- Role lain bisa ditambah disini --}}
        </div>
    </div>

    @if ($role === 'bendahara')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // ----- KONFIG DASAR -----
                const isDark = document.documentElement.classList.contains('dark');
                const axisTextColor = isDark ? '#fff' : '#222';
                const gridColor = isDark ? '#374151' : '#e5e7eb';

                // ====== 1) SETORAN TERBARU: LIMIT TAMPIL ======
                const latestLimitEl = document.getElementById('latestLimit');
                const latestItems = [...document.querySelectorAll('.item-setoran')];

                const applyLatestLimit = (n) => {
                    latestItems.forEach((li, idx) => {
                        li.style.display = idx < n ? 'flex' :
                        'none'; // pakai flex agar sesuai class Tailwind
                    });
                };

                if (latestLimitEl && latestItems.length) {
                    // set default dari option terpilih
                    const initial = parseInt(latestLimitEl.value || 10, 10);
                    applyLatestLimit(initial);
                    latestLimitEl.addEventListener('change', (e) => {
                        const n = parseInt(e.target.value || 10, 10);
                        applyLatestLimit(n);
                    });
                }

                // ====== 2) CHART TREN SETORAN ======
                const allLabels = @json($bulan);
                const allData = @json($nominal_per_bulan);

                const ctxEl = document.getElementById('chartSetoran');
                if (!ctxEl) {
                    return;
                }
                const ctx = ctxEl.getContext('2d');

                const baseDataset = () => {
                    return {
                        label: 'Setoran',
                        data: [],
                        fill: true,
                        borderColor: isDark ? '#38bdf8' : '#2563eb',
                        backgroundColor: isDark ? 'rgba(56,189,248,0.15)' : 'rgba(37,99,235,0.10)',
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: isDark ? '#38bdf8' : '#2563eb',
                        pointBorderColor: '#fff',
                    };
                };

                let chart = null;
                const buildChart = (type = 'line', range = 12) => {
                    const r = Math.max(1, Math.min(range, allLabels.length));
                    const labels = allLabels.slice(-r);
                    const data = allData.slice(-r);

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type,
                        data: {
                            labels,
                            datasets: [{
                                ...baseDataset(),
                                data
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
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: axisTextColor,
                                        callback: (v) => 'Rp ' + v.toLocaleString('id-ID')
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                }
                            }
                        }
                    });
                };

                const chartTypeEl = document.getElementById('chartType');
                const chartRangeEl = document.getElementById('chartRange');

                // inisialisasi pertama
                const initialType = chartTypeEl?.value || 'line';
                const initialRange = parseInt(chartRangeEl?.value || 12, 10);
                buildChart(initialType, initialRange);

                // event perubahan kontrol
                chartTypeEl?.addEventListener('change', (e) => {
                    buildChart(e.target.value, parseInt(chartRangeEl?.value || 12, 10));
                });
                chartRangeEl?.addEventListener('change', (e) => {
                    buildChart(chartTypeEl?.value || 'line', parseInt(e.target.value || 12, 10));
                });

                // reload saat theme OS berubah (optional)
                if (window.matchMedia('(prefers-color-scheme: dark)').media !== 'not all') {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                        location.reload();
                    });
                }
            });
        </script>
    @endif

</x-app-layout>
