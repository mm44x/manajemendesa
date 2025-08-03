<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kartu Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('kartu-keluarga.create') }}"
                class="mb-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah KK
            </a>
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
</x-app-layout>
