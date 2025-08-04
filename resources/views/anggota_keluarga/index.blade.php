<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anggota Keluarga') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <h3 class="text-lg font-bold">Kepala Keluarga: {{ $kk->kepala_keluarga }}</h3>
                <p class="text-sm text-gray-600">No KK: {{ $kk->no_kk }}</p>
            </div>

            @if (auth()->user()->role !== 'admin')
                <button onclick="toggleAddModal(true)"
                    class="mb-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Tambah Anggota
                </button>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm border">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-3 py-2 border">NIK</th>
                            <th class="px-3 py-2 border">Nama</th>
                            <th class="px-3 py-2 border">Jenis Kelamin</th>
                            <th class="px-3 py-2 border">Tempat, Tgl Lahir</th>
                            <th class="px-3 py-2 border">Hubungan</th>
                            <th class="px-3 py-2 border">Agama</th>
                            <th class="px-3 py-2 border">Pendidikan</th>
                            <th class="px-3 py-2 border">Pekerjaan</th>
                            <th class="px-3 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $item)
                            <tr>
                                <td class="border px-3 py-2">{{ $item->nik }}</td>
                                <td class="border px-3 py-2">{{ $item->nama }}</td>
                                <td class="border px-3 py-2">{{ $item->jenis_kelamin }}</td>
                                <td class="border px-3 py-2">
                                    {{ getWilayahNama($item->tempat_lahir, 'kabupaten') }},
                                    {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}
                                </td>
                                <td class="border px-3 py-2">{{ $item->hubungan }}</td>
                                <td class="border px-3 py-2">{{ $item->agama }}</td>
                                <td class="border px-3 py-2">{{ $item->pendidikan }}</td>
                                <td class="border px-3 py-2">{{ $item->pekerjaan }}</td>
                                <td class="border px-3 py-2">
                                    @if (auth()->user()->role !== 'admin')
                                        <button class="text-blue-600 hover:underline">✏️</button>
                                        <button class="text-red-600 hover:underline">🗑️</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-3 text-gray-500">Belum ada anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal tambah nanti disisipkan di sini --}}
</x-app-layout>
