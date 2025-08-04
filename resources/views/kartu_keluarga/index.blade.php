<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Data Kartu Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                @php $role = auth()->user()->role; @endphp

                @if ($role !== 'admin')
                    <div class="p-4 text-gray-900">
                        <button onclick="toggleModal(true)"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            + Tambah KK
                        </button>
                    </div>
                    <hr>
                @endif

                <div class="p-4 text-gray-900">
                    <form method="GET" class="mb-1 flex flex-wrap items-center gap-2">
                        <select name="sort_no_kk"
                            class="px-4 py-2 border rounded text-sm font-medium min-w-[12rem] bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600">
                            <option value="">Urutkan</option>
                            <option value="asc" {{ request('sort_no_kk') === 'asc' ? 'selected' : '' }}>No KK ASC
                            </option>
                            <option value="desc" {{ request('sort_no_kk') === 'desc' ? 'selected' : '' }}>No KK DESC
                            </option>
                        </select>

                        <input type="text" name="no_kk" value="{{ request('no_kk') }}" placeholder="Cari No KK"
                            class="px-4 py-2 border rounded text-sm font-medium min-w-[12rem] bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <input type="text" name="cari" value="{{ request('cari') }}"
                            placeholder="Cari Kepala Keluarga"
                            class="px-4 py-2 border rounded text-sm font-medium min-w-[12rem] bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <button type="submit"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            🔍 Filter
                        </button>

                        @if (request()->hasAny(['cari', 'no_kk', 'sort_no_kk']))
                            <a href="{{ route('kartu-keluarga.index') }}"
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
                            <thead class="bg-gray-200 dark:bg-gray-900 dark:text-white">
                                <tr>
                                    <th class="border px-2 whitespace-nowrap">No KK</th>
                                    <th class="border px-2 whitespace-nowrap">Kepala Keluarga</th>
                                    @if ($role == 'admin')
                                        <th class="border px-2 whitespace-nowrap">Alamat</th>
                                        <th class="border px-2 whitespace-nowrap">RT/RW</th>
                                        <th class="border px-2 whitespace-nowrap">Provinsi</th>
                                        <th class="border px-2 whitespace-nowrap">Kabupaten/Kota</th>
                                        <th class="border px-2 whitespace-nowrap">Kecamatan</th>
                                        <th class="border px-2 whitespace-nowrap">Desa</th>
                                        <th class="border px-2 whitespace-nowrap">Kode Pos</th>
                                        <th class="border px-2 whitespace-nowrap">Tanggal Terbit</th>
                                    @endif
                                    @if ($role !== 'admin')
                                        <th class="border px-2 whitespace-nowrap">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $kk)
                                    <tr>
                                        <td class="border px-2 whitespace-nowrap">{{ $kk->no_kk }} <a
                                                href="{{ route('anggota-keluarga.index', $kk->id) }}"
                                                class="text-indigo-600 hover:underline cursor-pointer"> - 👥 [Lihat
                                                Anggota]</a></td>
                                        <td class="border px-2 whitespace-nowrap">{{ $kk->kepala_keluarga }}</td>
                                        @if ($role == 'admin')
                                            <td class="border px-2 whitespace-nowrap">{{ $kk->alamat }}</td>
                                            <td class="border px-2 whitespace-nowrap">
                                                {{ $kk->rt }}/{{ $kk->rw }}</td>
                                            <td class="border px-2 whitespace-nowrap">
                                                {{ getWilayahNama($kk->desa->kode ?? '', 'provinsi') }}
                                            </td>
                                            <td class="border px-2 whitespace-nowrap">
                                                {{ getWilayahNama($kk->desa->kode ?? '', 'kabupaten') }}
                                            </td>
                                            <td class="border px-2 whitespace-nowrap">
                                                {{ getWilayahNama($kk->desa->kode ?? '', 'kecamatan') }}
                                            </td>
                                            <td class="border px-2 whitespace-nowrap">{{ $kk->desa->nama }}</td>
                                            <td class="border px-2 whitespace-nowrap">{{ $kk->kode_pos }}</td>
                                            <td class="border px-2 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($kk->tanggal_terbit)->format('d-m-Y') }}</td>
                                        @endif
                                        @if ($role !== 'admin')
                                            <td class="border px-2 whitespace-nowrap">
                                                <a onclick="showEditModal(this)" data-id="{{ $kk->id }}"
                                                    data-no_kk="{{ $kk->no_kk }}"
                                                    data-kepala_keluarga="{{ $kk->kepala_keluarga }}"
                                                    data-alamat="{{ $kk->alamat }}" data-rt="{{ $kk->rt }}"
                                                    data-rw="{{ $kk->rw }}" data-desa_id="{{ $kk->desa_id }}"
                                                    data-kode_pos="{{ $kk->kode_pos }}"
                                                    data-tanggal_terbit="{{ $kk->tanggal_terbit }}"
                                                    class="text-blue-600 hover:underline cursor-pointer">
                                                    ✏️ [Lihat/Edit]
                                                </a>
                                                <a onclick="confirmDelete({{ $kk->id }}, '{{ $kk->no_kk }}')"
                                                    class="text-red-600 hover:underline cursor-pointer">🗑️ [Hapus]</a>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-gray-500 py-2">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $data->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @if ($role !== 'admin')
        {{-- Modal Edit KK --}}
        <div id="kkEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
            <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg w-full">

                <div class="flex justify-between items-center border-b p-4">
                    <h3 class="text-lg font-semibold">Edit Kartu Keluarga</h3>
                    <button onclick="toggleEditModal(false)"
                        class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
                </div>
                <form id="editKKForm" method="POST" class="p-4 max-h-[90vh] overflow-y-auto">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block">No KK</label>
                            <input type="text" name="no_kk" id="edit_no_kk" class="w-full border rounded px-3 py-2"
                                required>
                        </div>
                        <div>
                            <label class="block">Kepala Keluarga</label>
                            <input type="text" name="kepala_keluarga" id="edit_kepala_keluarga"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block">Alamat</label>
                            <textarea name="alamat" id="edit_alamat" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
                        </div>
                        <div>
                            <label class="block">RT</label>
                            <input type="text" name="rt" id="edit_rt"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">RW</label>
                            <input type="text" name="rw" id="edit_rw"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">Provinsi</label>
                            <select id="edit_provinsi" class="w-full border rounded px-3 py-2" required></select>
                        </div>
                        <div>
                            <label class="block">Kabupaten/Kota</label>
                            <select id="edit_kabupaten" class="w-full border rounded px-3 py-2" required></select>
                        </div>
                        <div>
                            <label class="block">Kecamatan</label>
                            <select id="edit_kecamatan" class="w-full border rounded px-3 py-2" required></select>
                        </div>
                        <div>
                            <label class="block">Desa</label>
                            <select name="desa_id" id="edit_desa" class="w-full border rounded px-3 py-2"
                                required></select>
                        </div>
                        <div>
                            <label class="block">Kode Pos</label>
                            <input type="text" name="kode_pos" id="edit_kode_pos"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" id="edit_tanggal_terbit"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <button type="button" onclick="toggleEditModal(false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus KK --}}
        <div id="deleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
            <div class="bg-white rounded-lg w-full max-w-md shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">
                    Konfirmasi Hapus (<span id="deleteNoKK" class="text-red-600 font-bold"></span>)
                </h3>
                <p class="mb-4">Apakah kamu yakin ingin menghapus data Kartu Keluarga ini?</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="toggleDeleteModal(false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Ya,
                            Hapus</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Modal Tambah KK --}}
        <div id="kkModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 overflow-y-auto px-4 py-8 hidden">
            <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg w-full">
                <div class="flex justify-between items-center border-b p-4">
                    <h3 class="text-lg font-semibold">Tambah Kartu Keluarga</h3>
                    <button onclick="toggleModal(false)"
                        class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
                </div>
                <form action="{{ route('kartu-keluarga.store') }}" method="POST"
                    class="p-4 max-h-[90vh] overflow-y-auto">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block">No KK</label>
                            <input type="text" name="no_kk" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">Kepala Keluarga</label>
                            <input type="text" name="kepala_keluarga" class="w-full border rounded px-3 py-2"
                                required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block">Alamat</label>
                            <textarea name="alamat" class="w-full border rounded px-3 py-2" rows="2" required></textarea>
                        </div>
                        <div>
                            <label class="block">RT</label>
                            <input type="text" name="rt" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">RW</label>
                            <input type="text" name="rw" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">Provinsi</label>
                            <select id="provinsi" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block">Kabupaten/Kota</label>
                            <select id="kabupaten" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Kabupaten --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block">Kecamatan</label>
                            <select id="kecamatan" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block">Desa</label>
                            <select name="desa_id" id="desa" class="w-full border rounded px-3 py-2" required>
                                <option value="">-- Pilih Desa --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block">Kode Pos</label>
                            <input type="text" name="kode_pos" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" class="w-full border rounded px-3 py-2"
                                required>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 gap-2">
                        <button type="button" onclick="toggleModal(false)"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Fungsi Hapus KK --}}
        <script>
            function confirmDelete(id, noKK) {
                const form = document.getElementById('deleteForm');
                form.action = `/kartu-keluarga/${id}`;

                // tampilkan no KK
                document.getElementById('deleteNoKK').innerText = noKK;

                toggleDeleteModal(true);
            }


            function toggleDeleteModal(show) {
                const modal = document.getElementById('deleteModal');
                modal.classList.toggle('hidden', !show);
            }
        </script>

        {{-- Fungsi Edit KK --}}
        <script>
            function showEditModal(el) {
                console.log("DESA ID SAAT EDIT:", el.dataset.desa_id);

                const id = el.dataset.id;
                const desaKode = el.dataset.desa_id;

                // Set action form
                const form = document.getElementById('editKKForm');
                form.action = `/kartu-keluarga/${id}`;

                // Set nilai field
                document.getElementById('edit_no_kk').value = el.dataset.no_kk;
                document.getElementById('edit_kepala_keluarga').value = el.dataset.kepala_keluarga;
                document.getElementById('edit_alamat').value = el.dataset.alamat;
                document.getElementById('edit_rt').value = el.dataset.rt;
                document.getElementById('edit_rw').value = el.dataset.rw;
                document.getElementById('edit_kode_pos').value = el.dataset.kode_pos;
                document.getElementById('edit_tanggal_terbit').value = el.dataset.tanggal_terbit;

                // Mulai fetch dropdown wilayah (dinamis bertingkat)
                fetch(`/wilayah/desa?kode_kecamatan=${desaKode.slice(0, 8)}`)
                    .then(res => res.json())
                    .then(desaList => {
                        const desaSelect = document.getElementById('edit_desa');
                        desaSelect.innerHTML = `<option value="">-- Pilih Desa --</option>`;
                        desaList.forEach(item => {
                            desaSelect.innerHTML +=
                                `<option value="${item.kode}" ${item.kode === desaKode ? 'selected' : ''}>${item.nama}</option>`;
                        });

                        return fetch(`/wilayah/kecamatan?kode_kabupaten=${desaKode.slice(0, 5)}`);
                    })
                    .then(res => res.json())
                    .then(kecamatanList => {
                        const kecSelect = document.getElementById('edit_kecamatan');
                        kecSelect.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
                        kecamatanList.forEach(item => {
                            const kodeKec = desaKode.slice(0, 8);
                            kecSelect.innerHTML +=
                                `<option value="${item.kode}" ${item.kode === kodeKec ? 'selected' : ''}>${item.nama}</option>`;
                        });

                        return fetch(`/wilayah/kabupaten?kode_provinsi=${desaKode.slice(0, 2)}`);
                    })
                    .then(res => res.json())
                    .then(kabupatenList => {
                        const kabSelect = document.getElementById('edit_kabupaten');
                        kabSelect.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
                        kabupatenList.forEach(item => {
                            const kodeKab = desaKode.slice(0, 5);
                            kabSelect.innerHTML +=
                                `<option value="${item.kode}" ${item.kode === kodeKab ? 'selected' : ''}>${item.nama}</option>`;
                        });

                        return fetch(`/wilayah/provinsi`);
                    })
                    .then(res => res.json())
                    .then(provinsiList => {
                        const provSelect = document.getElementById('edit_provinsi');
                        provSelect.innerHTML = `<option value="">-- Pilih Provinsi --</option>`;
                        provinsiList.forEach(item => {
                            provSelect.innerHTML +=
                                `<option value="${item.kode}" ${item.kode === desaKode.slice(0, 2) ? 'selected' : ''}>${item.nama}</option>`;
                        });
                    });

                // Terakhir, tampilkan modal
                toggleEditModal(true);
            }

            function toggleEditModal(show) {
                const modal = document.getElementById('kkEditModal');
                modal.classList.toggle('hidden', !show);
            }
        </script>
        {{-- Fungsi Tambah KK --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // === MODAL EDIT KK ===
                document.getElementById('edit_provinsi').addEventListener('change', function() {
                    fetch(`/wilayah/kabupaten?kode_provinsi=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let kabupaten = document.getElementById('edit_kabupaten');
                            kabupaten.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
                            data.forEach(item => {
                                kabupaten.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });

                            // reset kecamatan & desa
                            document.getElementById('edit_kecamatan').innerHTML =
                                `<option value="">-- Pilih Kecamatan --</option>`;
                            document.getElementById('edit_desa').innerHTML =
                                `<option value="">-- Pilih Desa --</option>`;
                        });
                });

                document.getElementById('edit_kabupaten').addEventListener('change', function() {
                    fetch(`/wilayah/kecamatan?kode_kabupaten=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let kecamatan = document.getElementById('edit_kecamatan');
                            kecamatan.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
                            data.forEach(item => {
                                kecamatan.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });

                            // reset desa
                            document.getElementById('edit_desa').innerHTML =
                                `<option value="">-- Pilih Desa --</option>`;
                        });
                });

                document.getElementById('edit_kecamatan').addEventListener('change', function() {
                    fetch(`/wilayah/desa?kode_kecamatan=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let desa = document.getElementById('edit_desa');
                            desa.innerHTML = `<option value="">-- Pilih Desa --</option>`;
                            data.forEach(item => {
                                desa.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });
                        });
                });
            });
        </script>

        <script>
            function toggleModal(show) {
                const modal = document.getElementById('kkModal');
                modal.classList.toggle('hidden', !show);
            }
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                fetch('/wilayah/provinsi')
                    .then(res => res.json())
                    .then(data => {
                        const provinsi = document.getElementById('provinsi');
                        data.forEach(item => {
                            provinsi.innerHTML += `<option value="${item.kode}">${item.nama}</option>`;
                        });
                    });

                document.getElementById('provinsi').addEventListener('change', function() {
                    fetch(`/wilayah/kabupaten?kode_provinsi=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let kabupaten = document.getElementById('kabupaten');
                            kabupaten.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
                            data.forEach(item => {
                                kabupaten.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });

                            // reset kecamatan & desa
                            document.getElementById('kecamatan').innerHTML =
                                `<option value="">-- Pilih Kecamatan --</option>`;
                            document.getElementById('desa').innerHTML =
                                `<option value="">-- Pilih Desa --</option>`;
                        });
                });

                document.getElementById('kabupaten').addEventListener('change', function() {
                    fetch(`/wilayah/kecamatan?kode_kabupaten=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let kecamatan = document.getElementById('kecamatan');
                            kecamatan.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
                            data.forEach(item => {
                                kecamatan.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });

                            // reset desa
                            document.getElementById('desa').innerHTML =
                                `<option value="">-- Pilih Desa --</option>`;
                        });
                });

                document.getElementById('kecamatan').addEventListener('change', function() {
                    fetch(`/wilayah/desa?kode_kecamatan=${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            let desa = document.getElementById('desa');
                            desa.innerHTML = `<option value="">-- Pilih Desa --</option>`;
                            data.forEach(item => {
                                desa.innerHTML +=
                                    `<option value="${item.kode}">${item.nama}</option>`;
                            });
                        });
                });
            });
        </script>

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
