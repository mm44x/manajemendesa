<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anggota Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                <div class="p-4">
                    <h3 class="text-lg font-bold">Kepala Keluarga: {{ $kk->kepala_keluarga }}</h3>
                    <p class="text-sm text-gray-600">No KK: {{ $kk->no_kk }}</p>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                <div class="p-4 text-gray-900 flex flex-wrap items-center gap-2">
                    <a href="{{ route('kartu-keluarga.index') }}"
                        class="px-4 py-2 rounded text-sm font-medium bg-gray-300 text-gray-800 hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                        ⬅️ Kembali ke Manajemen KK
                    </a>

                    @if (auth()->user()->role !== 'admin')
                        <button onclick="toggleAddModal(true)"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            + Tambah Anggota
                        </button>
                    @endif
                </div>
                <hr>
                <div class="p-4 text-gray-900">
                    <form method="GET" class="mb-1 flex flex-wrap items-center gap-2">
                        <input type="text" name="nik" value="{{ request('nik') }}" placeholder="Cari NIK"
                            class="px-4 py-2 rounded text-sm font-medium border min-w-[12rem] bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama"
                            class="px-4 py-2 rounded text-sm font-medium border min-w-[12rem] bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <button type="submit"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            🔍 Filter
                        </button>

                        @if (request()->hasAny(['nik', 'nama']))
                            <a href="{{ route('anggota-keluarga.index', ['id' => $kk->id]) }}"
                                class="px-4 py-2 rounded text-sm font-medium bg-gray-300 text-gray-800 hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                                🔁 Reset
                            </a>
                        @endif
                    </form>
                </div>

            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 ">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm dark:bg-gray-900 dark:text-white">
                            <thead class="bg-gray-200 text-left">
                                <tr>
                                    <th class="px-3 py-2 whitespace-nowrap border">NIK</th>
                                    <th class="px-3 py-2 whitespace-nowrap border">Nama</th>
                                    <th class="px-3 py-2 whitespace-nowrap border">Hubungan</th>
                                    @if (auth()->user()->role !== 'admin')
                                        <th class="px-3 py-2 whitespace-nowrap border">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($anggota as $item)
                                    <tr>
                                        <td class="border px-3 py-2 whitespace-nowrap">{{ $item->nik }}</td>
                                        <td class="border px-3 py-2 whitespace-nowrap">{{ $item->nama }}</td>
                                        <td class="border px-3 py-2 whitespace-nowrap">{{ $item->hubungan }}</td>
                                        @if (auth()->user()->role !== 'admin')
                                            <td class="border px-3 py-2 whitespace-nowrap">
                                                <button onclick="showEditModal(this)" data-id="{{ $item->id }}"
                                                    data-nik="{{ $item->nik }}" data-nama="{{ $item->nama }}"
                                                    data-jenis_kelamin="{{ $item->jenis_kelamin }}"
                                                    data-tempat_lahir="{{ $item->tempat_lahir }}"
                                                    data-tanggal_lahir="{{ $item->tanggal_lahir }}"
                                                    data-hubungan="{{ $item->hubungan }}"
                                                    data-agama="{{ $item->agama }}"
                                                    data-pendidikan="{{ $item->pendidikan }}"
                                                    data-pekerjaan="{{ $item->pekerjaan }}"
                                                    class="text-blue-600 hover:underline cursor-pointer">
                                                    ✏️ [Lihat/Edit]
                                                </button>
                                                <button onclick="showDeleteModal(this)" data-id="{{ $item->id }}"
                                                    data-nama="{{ $item->nama }}"
                                                    class="text-red-600 hover:underline cursor-pointer ml-2">
                                                    🗑️ [Hapus]
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-3 text-gray-500">Belum ada anggota.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $anggota->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Tambah Anggota --}}
    <div id="anggotaModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto px-4 py-8">
        <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg w-full">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-semibold">Tambah Anggota Kartu Keluarga</h3>
                <button onclick="toggleAddModal(false)"
                    class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
            </div>
            <form action="{{ route('anggota-keluarga.store') }}" method="POST"
                class="p-4 max-h-[90vh] overflow-y-auto">
                @csrf
                <input type="hidden" name="kartu_keluarga_id" value="{{ $kk->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block">NIK</label>
                        <input type="text" name="nik" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block">Nama</label>
                        <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1">Tempat Lahir</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700">Provinsi</label>
                                <select id="provTL" class="w-full border rounded px-3 py-2" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700">Kabupaten/Kota</label>
                                <select name="tempat_lahir" id="kabTL" class="w-full border rounded px-3 py-2"
                                    required>
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block">Hubungan</label>
                        <select name="hubungan" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Saudara">Saudara</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Agama</label>
                        <select name="agama" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Pendidikan</label>
                        <input list="list_pendidikan" name="pendidikan" class="w-full border rounded px-3 py-2">
                        <datalist id="list_pendidikan">
                            <option value="SD">
                            <option value="SMP">
                            <option value="SMA">
                            <option value="D3">
                            <option value="S1">
                            <option value="S2">
                            <option value="S3">
                        </datalist>
                    </div>
                    <div>
                        <label class="block">Pekerjaan</label>
                        <input list="list_pekerjaan" name="pekerjaan" class="w-full border rounded px-3 py-2">
                        <datalist id="list_pekerjaan">
                            <option value="Pelajar">
                            <option value="Mahasiswa">
                            <option value="Wiraswasta">
                            <option value="PNS">
                            <option value="Petani">
                            <option value="Nelayan">
                        </datalist>
                    </div>
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" onclick="toggleAddModal(false)"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Anggota --}}
    <div id="anggotaEditModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto px-4 py-8">
        <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg w-full">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-semibold">Edit Anggota Keluarga</h3>
                <button onclick="toggleEditModal(false)"
                    class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
            </div>

            <form id="editAnggotaForm" method="POST" class="p-4 max-h-[90vh] overflow-y-auto">
                @csrf
                @method('PUT')
                <input type="hidden" name="kartu_keluarga_id" value="{{ $kk->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block">NIK</label>
                        <input type="text" name="nik" id="edit_nik" class="w-full border rounded px-3 py-2"
                            required>
                    </div>
                    <div>
                        <label class="block">Nama</label>
                        <input type="text" name="nama" id="edit_nama" class="w-full border rounded px-3 py-2"
                            required>
                    </div>
                    <div>
                        <label class="block">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit_jenis_kelamin" class="w-full border rounded px-3 py-2"
                            required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block mb-1">Tempat Lahir</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm">Provinsi</label>
                                <select id="edit_provTL" class="w-full border rounded px-3 py-2" required></select>
                            </div>
                            <div>
                                <label class="text-sm">Kabupaten/Kota</label>
                                <select name="tempat_lahir" id="edit_kabTL" class="w-full border rounded px-3 py-2"
                                    required></select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block">Hubungan</label>
                        <select name="hubungan" id="edit_hubungan" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Istri">Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Saudara">Saudara</option>
                            <option value="Lainnya">Lainnya</option>
                            <option value="Kepala Keluarga">Kepala Keluarga</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Agama</label>
                        <select name="agama" id="edit_agama" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Pendidikan</label>
                        <input list="list_pendidikan" name="pendidikan" id="edit_pendidikan"
                            class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block">Pekerjaan</label>
                        <input list="list_pekerjaan" name="pekerjaan" id="edit_pekerjaan"
                            class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" onclick="toggleEditModal(false)"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Hapus Anggota --}}
    <div id="anggotaDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden px-4 py-8">
        <div class="bg-white rounded-lg w-full max-w-md shadow-lg p-6">
            <div class="text-lg font-semibold mb-4">Konfirmasi Hapus</div>
            <p class="mb-4 text-sm text-gray-700">
                Yakin ingin menghapus anggota keluarga <span id="hapusNama" class="font-bold text-red-600"></span>?
            </p>
            <form id="deleteAnggotaForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="toggleDeleteModal(false)"
                        class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Start Script Modal Tambah Anggota --}}
    <script>
        function toggleAddModal(show) {
            const modal = document.getElementById('anggotaModal');
            modal.classList.toggle('hidden', !show);
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Load semua provinsi
            fetch('/wilayah/provinsi')
                .then(res => res.json())
                .then(data => {
                    const provTL = document.getElementById('provTL');
                    data.forEach(item => {
                        provTL.innerHTML += `<option value="${item.kode}">${item.nama}</option>`;
                    });
                });

            // Saat provinsi diubah → load kabupaten
            document.getElementById('provTL').addEventListener('change', function() {
                fetch(`/wilayah/kabupaten?kode_provinsi=${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        const kabTL = document.getElementById('kabTL');
                        kabTL.innerHTML = `<option value="">-- Pilih Kabupaten/Kota --</option>`;
                        data.forEach(item => {
                            kabTL.innerHTML +=
                                `<option value="${item.kode}">${item.nama}</option>`;
                        });
                    });
            });
        });
    </script>
    {{-- End Script Modal Tambah Anggota --}}

    {{-- Start Script Modal Edit Anggota --}}
    <script>
        function toggleEditModal(show) {
            document.getElementById('anggotaEditModal').classList.toggle('hidden', !show);
        }

        function showEditModal(el) {
            const id = el.dataset.id;

            const form = document.getElementById('editAnggotaForm');
            form.action = `/anggota-keluarga/${id}`; // Route ke update

            document.getElementById('edit_nik').value = el.dataset.nik;
            document.getElementById('edit_nama').value = el.dataset.nama;
            document.getElementById('edit_jenis_kelamin').value = el.dataset.jenis_kelamin;
            document.getElementById('edit_tanggal_lahir').value = el.dataset.tanggal_lahir;
            document.getElementById('edit_hubungan').value = el.dataset.hubungan;
            document.getElementById('edit_agama').value = el.dataset.agama;
            document.getElementById('edit_pendidikan').value = el.dataset.pendidikan;
            document.getElementById('edit_pekerjaan').value = el.dataset.pekerjaan;

            // Isi dropdown provinsi + kabupaten (berjenjang)
            const kodeKab = el.dataset.tempat_lahir;

            fetch(`/wilayah/kabupaten?kode_provinsi=${kodeKab.slice(0,2)}`)
                .then(res => res.json())
                .then(kabList => {
                    const kabSelect = document.getElementById('edit_kabTL');
                    kabSelect.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
                    kabList.forEach(item => {
                        kabSelect.innerHTML +=
                            `<option value="${item.kode}" ${item.kode === kodeKab ? 'selected' : ''}>${item.nama}</option>`;
                    });

                    return fetch(`/wilayah/provinsi`);
                })
                .then(res => res.json())
                .then(provList => {
                    const provSelect = document.getElementById('edit_provTL');
                    provSelect.innerHTML = `<option value="">-- Pilih Provinsi --</option>`;
                    provList.forEach(item => {
                        provSelect.innerHTML +=
                            `<option value="${item.kode}" ${item.kode === kodeKab.slice(0, 2) ? 'selected' : ''}>${item.nama}</option>`;
                    });
                });

            toggleEditModal(true);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const provTL = document.getElementById('edit_provTL');
            if (provTL) {
                provTL.addEventListener('change', function() {
                    fetch(`/wilayah/kabupaten?kode_provinsi=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            const kabupatenSelect = document.getElementById('edit_kabTL');
                            kabupatenSelect.innerHTML =
                                `<option value="">-- Pilih Kabupaten/Kota --</option>`;
                            data.forEach(item => {
                                kabupatenSelect.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });
                        });
                });
            }
        });
    </script>
    {{-- End Script Modal Edit Anggota --}}

    {{-- Start Script Modal Hapus Anggota --}}
    <script>
        function toggleDeleteModal(show) {
            document.getElementById('anggotaDeleteModal').classList.toggle('hidden', !show);
        }

        function showDeleteModal(el) {
            const id = el.dataset.id;
            const nama = el.dataset.nama;

            const form = document.getElementById('deleteAnggotaForm');
            form.action = `/anggota-keluarga/${id}`;

            document.getElementById('hapusNama').innerText = nama;

            toggleDeleteModal(true);
        }
    </script>
    {{-- End Script Modal Hapus Anggota --}}

    {{-- Start Toast --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2 items-end pr-4"></div>

    <script>
        function showToast(type, message) {
            const colors = {
                success: {
                    bg: 'bg-green-100',
                    border: 'border-green-400',
                    text: 'text-green-800',
                    icon: '✅'
                },
                error: {
                    bg: 'bg-red-100',
                    border: 'border-red-400',
                    text: 'text-red-800',
                    icon: '❌'
                }
            };

            const toast = document.createElement('div');
            toast.className =
                `w-[300px] max-w-full px-4 py-3 rounded shadow-lg border ${colors[type].bg} ${colors[type].border} ${colors[type].text} flex items-start gap-3 animate-slidein`;
            toast.innerHTML = `
        <span class="text-xl">${colors[type].icon}</span>
        <div class="flex-1 text-sm font-medium">${message}</div>
        <button onclick="this.parentElement.remove()" class="font-bold text-lg leading-none ml-2 hover:text-black">&times;</button>
    `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
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
    {{-- End Toast --}}
</x-app-layout>
