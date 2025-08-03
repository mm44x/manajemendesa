<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kartu Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button onclick="toggleModal(true)"
    class="mb-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    + Tambah KK
</button>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-2">No KK</th>
                                <th class="border px-2">Kepala Keluarga</th>
                                <th class="border px-2">Alamat</th>
                                <th class="border px-2">RT/RW</th>
                                <th class="border px-2">Desa</th>
                                <th class="border px-2">Kecamatan</th>
                                <th class="border px-2">Kabupaten</th>
                                <th class="border px-2">Kode Pos</th>
                                <th class="border px-2">Tanggal Terbit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $kk)
                                <tr>
                                    <td class="border px-2">{{ $kk->no_kk }}</td>
                                    <td class="border px-2">{{ $kk->kepala_keluarga }}</td>
                                    <td class="border px-2">{{ $kk->alamat }}</td>
                                    <td class="border px-2">{{ $kk->rt }}/{{ $kk->rw }}</td>
                                    <td class="border px-2">{{ $kk->desa }}</td>
                                    <td class="border px-2">{{ $kk->kecamatan }}</td>
                                    <td class="border px-2">{{ $kk->kabupaten }}</td>
                                    <td class="border px-2">{{ $kk->kode_pos }}</td>
                                    <td class="border px-2">{{ $kk->tanggal_terbit }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-gray-500 py-2">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="kkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-3xl shadow-lg">
        <div class="flex justify-between items-center border-b p-4">
            <h3 class="text-lg font-semibold">Tambah Kartu Keluarga</h3>
            <button onclick="toggleModal(false)" class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
        </div>
        <form action="{{ route('kartu-keluarga.store') }}" method="POST" class="p-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block">No KK</label>
                    <input type="text" name="no_kk" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block">Kepala Keluarga</label>
                    <input type="text" name="kepala_keluarga" class="w-full border rounded px-3 py-2" required>
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
                    <label class="block">Desa</label>
                    <input type="text" name="desa" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block">Kecamatan</label>
                    <input type="text" name="kecamatan" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block">Kabupaten</label>
                    <input type="text" name="kabupaten" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block">Kode Pos</label>
                    <input type="text" name="kode_pos" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="flex justify-end mt-4 gap-2">
                <button type="button" onclick="toggleModal(false)"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</button>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
<script>
    function toggleModal(show) {
        const modal = document.getElementById('kkModal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
</script>

    </div>
</div>

</x-app-layout>
