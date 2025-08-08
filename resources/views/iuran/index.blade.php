<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Data Iuran') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                <div class="p-4 text-gray-900 dark:text-white">
                    <button onclick="toggleModal(true)"
                        class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                        + Tambah Iuran
                    </button>
                </div>
                <hr class="border-gray-300 dark:border-gray-700">

                <div class="p-4 text-gray-900 dark:text-white">
                    <form method="GET" class="mb-1 flex flex-wrap items-center gap-3 sm:gap-4">
                        {{-- Input Nama Iuran --}}
                        <input type="text" name="nama_iuran" value="{{ request('nama_iuran') }}"
                            placeholder="Cari Nama Iuran"
                            class="px-3 py-2 rounded border text-sm font-medium min-w-[12rem] bg-white text-black border-gray-300
                   dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        {{-- Tombol Filter --}}
                        <button type="submit"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700
                   dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            🔍 Filter
                        </button>

                        {{-- Tombol Reset --}}
                        @if (request()->hasAny(['nama_iuran']))
                            <a href="{{ route('iuran.index') }}"
                                class="px-4 py-2 rounded text-sm font-medium bg-gray-300 text-gray-800 hover:bg-gray-400
                       dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                🔁 Reset
                            </a>
                        @endif
                    </form>
                </div>

            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm dark:bg-gray-900 dark:text-white">
                            <thead class="bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-white">
                                <tr>
                                    <th class="border px-2 py-2 whitespace-nowrap">Nama Iuran</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Deskripsi</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Tipe</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Jenis Setoran</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Nominal</th>
                                    <th class="border px-2 py-2 whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($iurans as $iuran)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <td class="border px-2 py-2 whitespace-nowrap">{{ $iuran->nama_iuran }}</td>
                                        <td class="border px-2 py-2 whitespace-nowrap">{{ $iuran->deskripsi }}</td>
                                        <td class="border px-2 py-2 whitespace-nowrap">{{ $iuran->tipe }}</td>
                                        <td class="border px-2 py-2 whitespace-nowrap">{{ $iuran->jenis_setoran }}</td>
                                        <td class="border px-2 py-2 whitespace-nowrap">
                                            {{ $iuran->nominal ? 'Rp ' . number_format($iuran->nominal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="border px-2 py-2 whitespace-nowrap space-x-2">
                                            <a href="{{ route('iuran.setoran.input', $iuran->id) }}"
                                                class="text-green-600 hover:underline">💲 [Setor]</a>
                                            <a onclick="showEditModal(this)" data-id="{{ $iuran->id }}"
                                                data-nama_iuran="{{ $iuran->nama_iuran }}"
                                                data-deskripsi="{{ $iuran->deskripsi }}"
                                                data-tipe="{{ $iuran->tipe }}"
                                                data-jenis_setoran="{{ $iuran->jenis_setoran }}"
                                                data-nominal="{{ $iuran->nominal }}"
                                                data-peserta="{{ $iuran->kartuKeluargas->pluck('id') }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                                                ✏️ [Lihat/Edit]
                                            </a>
                                            <a onclick="confirmDelete({{ $iuran->id }}, '{{ $iuran->nama_iuran }}')"
                                                class="text-red-600 dark:text-red-400 hover:underline cursor-pointer">
                                                🗑️ [Hapus]
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 dark:text-gray-400 py-2">
                                            Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $iurans->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Tambah Iuran --}}
    <div id="iuranModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-3xl shadow-lg">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Tambah Iuran</h3>
                <button onclick="toggleModal(false)"
                    class="text-gray-500 hover:text-red-600 text-xl dark:hover:text-red-400">&times;</button>
            </div>

            {{-- Form --}}
            <form action="{{ route('iuran.store') }}" method="POST" class="p-4 max-h-[90vh] overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-white">
                    <div>
                        <label class="block mb-1">Nama Iuran</label>
                        <input type="text" name="nama_iuran"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                    </div>
                    <div>
                        <label class="block mb-1">Tipe</label>
                        <select name="tipe"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            <option value="sekali">Sekali</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="2"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1">Jenis Setoran</label>
                        <select name="jenis_setoran"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            <option value="tetap">Tetap</option>
                            <option value="bebas">Bebas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Nominal (jika tetap)</label>
                        <input type="number" name="nominal"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1">Peserta (KK)</label>
                        <select name="peserta[]" multiple
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            @foreach ($kks as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->kepala_keluarga }} (No.
                                    {{ $kk->no_kk }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" onclick="toggleModal(false)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Iuran --}}
    <div id="iuranEditModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-3xl shadow-lg">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Iuran</h3>
                <button onclick="toggleEditModal(false)"
                    class="text-gray-500 hover:text-red-600 text-xl dark:hover:text-red-400">&times;</button>
            </div>

            {{-- Form --}}
            <form id="editIuranForm" method="POST" class="p-4 max-h-[90vh] overflow-y-auto">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-white">
                    <div>
                        <label class="block mb-1">Nama Iuran</label>
                        <input type="text" name="nama_iuran" id="edit_nama_iuran"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                    </div>
                    <div>
                        <label class="block mb-1">Tipe</label>
                        <select name="tipe" id="edit_tipe"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            <option value="sekali">Sekali</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="2"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label class="block mb-1">Jenis Setoran</label>
                        <select name="jenis_setoran" id="edit_jenis_setoran"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            <option value="tetap">Tetap</option>
                            <option value="bebas">Bebas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Nominal (jika tetap)</label>
                        <input type="number" name="nominal" id="edit_nominal"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-1">Peserta (KK)</label>
                        <select name="peserta[]" id="edit_peserta" multiple
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            @foreach ($kks as $kk)
                                <option value="{{ $kk->id }}">{{ $kk->kepala_keluarga }} (No.
                                    {{ $kk->no_kk }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" onclick="toggleEditModal(false)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus Iuran --}}
    <div id="deleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
        <div class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-lg w-full max-w-md shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4">
                Konfirmasi Hapus (<span id="deleteNamaIuran" class="text-red-600 dark:text-red-400 font-bold"></span>)
            </h3>
            <p class="mb-4">Apakah kamu yakin ingin menghapus data iuran ini?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleDeleteModal(false)"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(show) {
            const modal = document.getElementById('iuranModal');
            modal.classList.toggle('hidden', !show);
        }

        function toggleEditModal(show) {
            const modal = document.getElementById('iuranEditModal');
            modal.classList.toggle('hidden', !show);
        }

        function toggleDeleteModal(show) {
            const modal = document.getElementById('deleteModal');
            modal.classList.toggle('hidden', !show);
        }

        function showEditModal(el) {
            const id = el.dataset.id;
            const form = document.getElementById('editIuranForm');
            form.action = `/iuran/${id}`;

            document.getElementById('edit_nama_iuran').value = el.dataset.nama_iuran;
            document.getElementById('edit_deskripsi').value = el.dataset.deskripsi;
            document.getElementById('edit_tipe').value = el.dataset.tipe;
            document.getElementById('edit_jenis_setoran').value = el.dataset.jenis_setoran;
            document.getElementById('edit_nominal').value = el.dataset.nominal;

            const peserta = JSON.parse(el.dataset.peserta);
            const selectPeserta = document.getElementById('edit_peserta');
            for (let option of selectPeserta.options) {
                option.selected = peserta.includes(parseInt(option.value));
            }

            toggleEditModal(true);
        }

        function confirmDelete(id, namaIuran) {
            const form = document.getElementById('deleteForm');
            form.action = `/iuran/${id}`;
            document.getElementById('deleteNamaIuran').innerText = namaIuran;
            toggleDeleteModal(true);
        }
    </script>

    {{-- Toast Notification (Copy-paste dari KK) --}}
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
    @if (session('error'))
        <script>
            showToast('error', @json(session('error')));
        </script>
    @endif
    @if ($errors->any())
        <script>
            showToast('error', @json($errors->first()));
        </script>
    @endif


    <style>
        .pagination .page-link {
            @apply text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-900;
        }

        .pagination .active .page-link {
            @apply bg-blue-600 text-white border-blue-600;
        }

        .dark .pagination .page-link {
            @apply text-white bg-gray-800 border-gray-700 hover:bg-gray-700 hover:text-white;
        }

        .dark .pagination .active .page-link {
            @apply bg-blue-500 text-white border-blue-500;
        }
    </style>
</x-app-layout>
