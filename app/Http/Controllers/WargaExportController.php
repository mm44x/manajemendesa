<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class WargaExportController extends Controller
{
    public function export()
    {
        $data = DB::table('anggota_keluargas')
            ->join('kartu_keluargas', 'anggota_keluargas.kartu_keluarga_id', '=', 'kartu_keluargas.id')
            ->leftJoin('wilayah', 'anggota_keluargas.tempat_lahir', '=', 'wilayah.kode') // FIX DI SINI
            ->select(
                'kartu_keluargas.no_kk',
                'anggota_keluargas.nik',
                'anggota_keluargas.nama',
                'anggota_keluargas.jenis_kelamin',
                'wilayah.nama as tempat_lahir',
                'anggota_keluargas.tanggal_lahir',
                'anggota_keluargas.hubungan',
                'anggota_keluargas.agama',
                'anggota_keluargas.pendidikan',
                'anggota_keluargas.pekerjaan'
            )
            ->orderBy('kartu_keluargas.no_kk')
            ->orderBy('anggota_keluargas.id')
            ->get()
            ->groupBy('no_kk');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['No KK', 'NIK', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Hubungan', 'Agama', 'Pendidikan', 'Pekerjaan'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        foreach ($data as $noKK => $anggotaList) {
            $startRow = $row;
            foreach ($anggotaList as $anggota) {
                $sheet->setCellValue("B{$row}", $anggota->nik);
                $sheet->setCellValue("C{$row}", $anggota->nama);
                $sheet->setCellValue("D{$row}", $anggota->jenis_kelamin);
                $sheet->setCellValue("E{$row}", $anggota->tempat_lahir);
                $sheet->setCellValue("F{$row}", $anggota->tanggal_lahir);
                $sheet->setCellValue("G{$row}", $anggota->hubungan);
                $sheet->setCellValue("H{$row}", $anggota->agama);
                $sheet->setCellValue("I{$row}", $anggota->pendidikan);
                $sheet->setCellValue("J{$row}", $anggota->pekerjaan);
                $row++;
            }
            $endRow = $row - 1;
            $sheet->mergeCells("A{$startRow}:A{$endRow}");
            $sheet->setCellValue("A{$startRow}", $noKK);
            $sheet->getStyle("A{$startRow}:A{$endRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'data_warga_' . Carbon::now()->format('d-m-Y') . '.xlsx';

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
