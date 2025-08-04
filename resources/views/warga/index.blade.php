<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Data Semua Warga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                <div class="p-4 flex flex-wrap justify-between items-center gap-2">
                    <form method="GET" class="mb-1 flex flex-wrap items-center gap-2">
                        <input type="text" name="no_kk" value="{{ request('no_kk') }}" placeholder="Cari No KK"
                            class="px-4 py-2 rounded text-sm font-medium min-w-[12rem] border bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <input type="text" name="nik" value="{{ request('nik') }}" placeholder="Cari NIK"
                            class="px-4 py-2 rounded text-sm font-medium min-w-[12rem] border bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama"
                            class="px-4 py-2 rounded text-sm font-medium min-w-[12rem] border bg-white text-black dark:bg-gray-800 dark:text-white dark:border-gray-600" />

                        <button type="submit"
                            class="px-4 py-2 rounded text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            🔍 Filter
                        </button>

                        @if (request()->hasAny(['nik', 'nama', 'no_kk']))
                            <a href="{{ route('semua-warga.index') }}"
                                class="px-4 py-2 rounded text-sm font-medium bg-gray-300 text-gray-800 hover:bg-gray-400 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                                🔁 Reset
                            </a>
                        @endif
                    </form>

                    <a href="{{ route('warga.export') }}"
                        class="px-4 py-2 rounded text-sm font-medium bg-green-600 text-white hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 whitespace-nowrap">
                        📤 Export Excel
                    </a>
                </div>

            </div>

            <div
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm text-left">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white">
                                <tr>
                                    <th class="border px-4 py-2">No KK</th>
                                    <th class="border px-4 py-2">NIK</th>
                                    <th class="border px-4 py-2">Nama</th>
                                    <th class="border px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = $anggota->groupBy('kartu_keluarga_id');
                                @endphp

                                @forelse ($grouped as $kkId => $anggotaList)
                                    @foreach ($anggotaList as $index => $item)
                                        <tr>
                                            @if ($index === 0)
                                                <td class="border px-4 py-2 text-center align-middle"
                                                    rowspan="{{ count($anggotaList) }}">
                                                    {{ $item->kartuKeluarga->no_kk ?? '-' }}
                                                </td>
                                            @endif
                                            <td class="border px-4 py-2">{{ $item->nik }}</td>
                                            <td class="border px-4 py-2">{{ $item->nama }}</td>
                                            <td class="border px-4 py-2">
                                                <a href="#"
                                                    class="text-blue-600 hover:underline dark:text-blue-400"
                                                    onclick="showDetail(this)" data-nik="{{ $item->nik }}"
                                                    data-nama="{{ $item->nama }}"
                                                    data-jk="{{ $item->jenis_kelamin }}"
                                                    data-tempat="{{ $item->nama_tempat_lahir }}"
                                                    data-tanggal="{{ $item->tanggal_lahir }}"
                                                    data-agama="{{ $item->agama }}"
                                                    data-pendidikan="{{ $item->pendidikan }}"
                                                    data-pekerjaan="{{ $item->pekerjaan }}"
                                                    data-hubungan="{{ $item->hubungan }}"
                                                    data-nokk="{{ $item->kartuKeluarga->no_kk ?? '-' }}">
                                                    👁️ Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-2">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                        {{-- Pagination --}}
                        <div class="mt-4">
                            {{ $anggota->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Modal Detail Warga --}}
    {{-- Modal Detail Warga --}}
    <div id="modalDetailWarga"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden overflow-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-3xl shadow-lg">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detail Warga</h3>
                <button onclick="toggleDetailModal(false)"
                    class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
            </div>

            <div class="p-4 max-h-[90vh] overflow-y-auto text-sm text-gray-700 dark:text-white">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">NIK</label>
                        <div id="detail_nik" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700"></div>
                    </div>
                    <div>
                        <label class="block font-medium">Nama</label>
                        <div id="detail_nama" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700"></div>
                    </div>
                    <div>
                        <label class="block font-medium">Jenis Kelamin</label>
                        <div id="detail_jk" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700"></div>
                    </div>
                    <div>
                        <label class="block font-medium">Tempat, Tanggal Lahir</label>
                        <div id="detail_lahir" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium">Agama</label>
                        <div id="detail_agama" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium">Pendidikan</label>
                        <div id="detail_pendidikan" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium">Pekerjaan</label>
                        <div id="detail_pekerjaan" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="block font-medium">Hubungan dalam KK</label>
                        <div id="detail_hubungan" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-medium">No Kartu Keluarga</label>
                        <div id="detail_nokk" class="mt-1 px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="toggleDetailModal(false)"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDetailModal(show) {
            const modal = document.getElementById('modalDetailWarga');
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex'); // ✅ tambahkan flex
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex'); // ✅ hapus flex saat tutup
            }
        }

        function showDetail(el) {
            document.getElementById('detail_nik').innerText = el.dataset.nik ?? '-';
            document.getElementById('detail_nama').innerText = el.dataset.nama ?? '-';
            document.getElementById('detail_jk').innerText = el.dataset.jk ?? '-';
            document.getElementById('detail_lahir').innerText = `${el.dataset.tempat ?? '-'}, ${el.dataset.tanggal ?? '-'}`;
            document.getElementById('detail_agama').innerText = el.dataset.agama ?? '-';
            document.getElementById('detail_pendidikan').innerText = el.dataset.pendidikan ?? '-';
            document.getElementById('detail_pekerjaan').innerText = el.dataset.pekerjaan ?? '-';
            document.getElementById('detail_hubungan').innerText = el.dataset.hubungan ?? '-';
            document.getElementById('detail_nokk').innerText = el.dataset.nokk ?? '-';

            toggleDetailModal(true);
        }
    </script>



</x-app-layout>
