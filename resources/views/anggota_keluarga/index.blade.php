<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Anggota Keluarga - {{ $kk->no_kk }} ({{ $kk->kepala_keluarga }})
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <a href="{{ route('kartu-keluarga.index') }}" class="text-blue-600 hover:underline dark:text-blue-400">←
                    Kembali ke Daftar KK</a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-2 text-gray-800 dark:text-white">Daftar Anggota</h3>

                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-2 py-1">NIK</th>
                            <th class="px-2 py-1">Nama</th>
                            <th class="px-2 py-1">Jenis Kelamin</th>
                            <th class="px-2 py-1">Hubungan</th>
                            <th class="px-2 py-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kk->anggota as $item)
                            <tr>
                                <td class="px-2 py-1">{{ $item->nik }}</td>
                                <td class="px-2 py-1">{{ $item->nama }}</td>
                                <td class="px-2 py-1">{{ $item->jenis_kelamin }}</td>
                                <td class="px-2 py-1">{{ $item->hubungan }}</td>
                                <td class="px-2 py-1">
                                    <button onclick="showEditModal(this)"
                                        class="text-blue-500 hover:underline">Edit</button>
                                    <button onclick="showDeleteModal(...)"
                                        class="text-red-500 hover:underline">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-2">Belum ada anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
