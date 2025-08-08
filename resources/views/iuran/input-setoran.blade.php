<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            Input Setoran: {{ $iuran->nama_iuran }}
        </h2>
    </x-slot>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">

                {{-- Pilih/ubah periode --}}
                <form method="GET" class="mb-6 flex flex-wrap gap-2 items-center">
                    <input type="hidden" name="iuran_id" value="{{ $iuran->id }}">
                    <label class="font-bold text-sm flex items-center h-[42px]">Periode:</label>
                    @if ($iuran->tipe === 'sekali')
                        <input type="text" name="periode_label" value="Sekali Bayar"
                            class="rounded border px-3 py-2 bg-gray-200 text-gray-700" readonly>
                    @else
                        {{-- Dropdown Periode --}}
                        <select name="periode_label" id="periode_select"
                            class="rounded border px-3 py-2 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 min-w-[180px]"
                            onchange="periodeCustomInput(this)">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($daftar_periode as $periode)
                                <option value="{{ $periode }}" {{ $periode_label === $periode ? 'selected' : '' }}>
                                    {{ $periode }}
                                </option>
                            @endforeach
                            <option value="_custom"
                                {{ !in_array($periode_label, $daftar_periode->toArray()) && $periode_label ? 'selected' : '' }}>
                                + Tambah Periode Baru
                            </option>
                        </select>
                        <input type="text" id="periode_label_custom" name="periode_label_custom"
                            list="periode_datalist"
                            value="{{ !in_array($periode_label, $daftar_periode->toArray()) && $periode_label ? $periode_label : '' }}"
                            class="rounded border px-3 py-2 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 min-w-[180px]"
                            placeholder="Masukkan periode baru"
                            style="{{ !in_array($periode_label, $daftar_periode->toArray()) && $periode_label ? '' : 'display:none' }}">
                        <datalist id="periode_datalist">
                            @foreach ($periode_predict as $periode_opt)
                                <option value="{{ $periode_opt }}">
                            @endforeach
                        </datalist>
                        <script>
                            function periodeCustomInput(select) {
                                const custom = document.getElementById('periode_label_custom');
                                const submitBtn = document.getElementById('periode-submit-btn');
                                if (select.value === '_custom') {
                                    custom.style.display = '';
                                    custom.required = true;
                                    submitBtn.textContent = 'Tambah Periode';
                                } else {
                                    custom.style.display = 'none';
                                    custom.required = false;
                                    submitBtn.textContent = 'Lihat Data';
                                }
                            }
                            document.addEventListener('DOMContentLoaded', function() {
                                periodeCustomInput(document.getElementById('periode_select'));
                            });
                        </script>
                    @endif

                    <button id="periode-submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded"
                        type="submit">
                        Lihat Data
                    </button>

                    <input type="text" id="filter-kk"
                        class="rounded px-3 py-2 border bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 ml-auto"
                        placeholder="Cari No KK / Nama KK...">
                </form>


                {{-- Tabel Peserta --}}
                <form method="POST" action="{{ route('iuran.setoran.store', $iuran->id) }}">
                    @csrf
                    <input type="hidden" name="periode_label" value="{{ $periode_label }}">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm dark:bg-gray-900 dark:text-white">
                            <thead class="bbg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white">
                                <tr>
                                    <th class="border px-2 py-2 whitespace-nowrap">No</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">No KK</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Kepala Keluarga</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Status</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Nominal</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Setor?</th>
                                </tr>
                            </thead>
                            <tbody id="table-peserta-body">
                                @include('iuran._table-setoran', [
                                    'peserta' => $peserta,
                                    'iuran' => $iuran,
                                    'periode_label' => $periode_label,
                                ])
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <a href="{{ route('iuran.index') }}"
                            class="px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-600">Kembali</a>
                        <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white font-bold">
                            Simpan Setoran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 items-end pr-4"></div>
    <script>
        function showToast(type, message) {
            const colors = {
                success: {
                    bg: 'bg-green-600 text-white',
                    icon: '✅'
                },
                error: {
                    bg: 'bg-red-600 text-white',
                    icon: '❌'
                }
            };
            const toast = document.createElement('div');
            toast.className =
                `min-w-[250px] max-w-xs px-4 py-2 rounded shadow-lg mb-2 flex items-center gap-3 ${colors[type].bg} animate-slidein`;
            toast.innerHTML = `
                <span class="text-xl">${colors[type].icon}</span>
                <div class="flex-1 text-sm font-medium">${message}</div>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg leading-none ml-2 hover:text-black">&times;</button>
            `;
            document.getElementById('toast-container').appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 400);
            }, 2200);
        }
    </script>
    <style>
        @keyframes slidein {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slidein {
            animation: slidein 0.3s ease-out;
        }
    </style>
    @if (session('success'))
        <script>
            showToast('success', @json(session('success')));
        </script>
    @endif
    @if ($errors->any())
        <script>
            showToast('error', @json($errors->first()));
        </script>
    @endif

    <script>
        function periodeCustomInput(select) {
            const custom = document.getElementById('periode_label_custom');
            const submitBtn = document.getElementById('periode-submit-btn');
            if (select.value === '_custom') {
                custom.style.display = '';
                custom.required = true;
                // Ganti label tombol
                submitBtn.textContent = 'Tambah Periode';
            } else {
                custom.style.display = 'none';
                custom.required = false;
                // Kembalikan label tombol
                submitBtn.textContent = 'Lihat Data';
            }
        }
        // Trigger on load agar label tombol benar saat reload page
        document.addEventListener('DOMContentLoaded', function() {
            periodeCustomInput(document.getElementById('periode_select'));
        });
    </script>

    <script>
        let timeout = null;
        document.getElementById('filter-kk').addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const q = this.value;
                const periode_label = "{{ $periode_label }}";
                fetch(
                        `{{ route('iuran.setoran.input.ajax', $iuran->id) }}?q=${encodeURIComponent(q)}&periode_label=${encodeURIComponent(periode_label)}`
                    )
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('table-peserta-body').innerHTML = data.html;
                    });
            }, 300);
        });
    </script>

</x-app-layout>
