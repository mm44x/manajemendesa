@foreach ($peserta as $idx => $kk)
    @php
        $setoran = $kk->setoranIurans->first();
        $sudah_setor = $setoran ? true : false;
        $nominal_setor = $sudah_setor
            ? $setoran->nominal_dibayar
            : ($iuran->jenis_setoran === 'tetap'
                ? $iuran->nominal
                : old('nominal.' . $kk->id, ''));
    @endphp
    <tr class="{{ $sudah_setor ? 'bg-green-100 dark:bg-green-900' : '' }}">
        <td class="border px-2 py-2 whitespace-nowrap">{{ $idx + 1 }}</td>
        <td class="border px-2 py-2 whitespace-nowrap">{{ $kk->no_kk }}</td>
        <td class="border px-2 py-2 whitespace-nowrap">{{ $kk->kepala_keluarga }}</td>
        <td class="border px-2 py-2 whitespace-nowrap">
            @if ($sudah_setor)
                <span class="text-green-700 font-bold">Sudah Setor</span>
            @else
                <span class="text-red-700 font-bold">Belum Setor</span>
            @endif
        </td>
        <td class="border px-2 py-2 whitespace-nowrap">
            @if ($iuran->jenis_setoran === 'tetap')
                <input type="number" min="0" name="nominal[{{ $kk->id }}]" value="{{ $nominal_setor }}"
                    class="rounded px-2 py-1 border w-24 bg-white text-gray-900 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    {{ $sudah_setor ? 'readonly' : '' }}>
            @else
                <input type="number" min="1" name="nominal[{{ $kk->id }}]" value="{{ $nominal_setor }}"
                    class="rounded px-2 py-1 border w-24 bg-white text-gray-900 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                    {{ $sudah_setor ? 'readonly' : '' }}>
            @endif
        </td>
        <td class="border px-2 py-2 whitespace-nowrap text-center">
            @if (!$sudah_setor)
                <input type="checkbox" name="setor_kk[]" value="{{ $kk->id }}">
            @else
                <span>-</span>
            @endif
        </td>
    </tr>
@endforeach
