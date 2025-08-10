---
# 🏘️ Manajemen Warga Desa

Aplikasi Laravel 10 (Blade + Tailwind + Bootstrap via Blade) untuk mengelola KK, Anggota, dan Iuran—ringkas, modern, dan ramah bendahara.
---

## ✨ Fitur Utama

-   **KK & Anggota**

    -   CRUD KK & Anggota (modal dinamis pada satu halaman, konsisten dengan Tailwind Breeze).
    -   Dropdown wilayah bertingkat via **AJAX** (Provinsi → Kab/Kota → Kecamatan → Desa).
    -   Validasi ketat: `no_kk`, `nik`, tanggal/tempat lahir, dll.

-   **Data Semua Warga**

    -   Tabel gabungan KK+Anggota (read-only untuk semua role).
    -   Pencarian (No KK/NIK/Nama), sort, pagination.
    -   **Export Excel** (PhpSpreadsheet) dengan **merge No KK** seperti tampilan web.
    -   Filename: `data_warga_YYYY-MM-DD.xlsx`.

-   **Iuran Warga**

    -   Tipe: **sekali / mingguan / bulanan**; jenis setoran: **tetap / bebas**.
    -   Pilih peserta **per-KK** (multi-select) saat tambah/edit iuran.
    -   **Input Setoran per Periode**:

        -   Dropdown periode + opsi **Tambah Periode Baru**.
        -   **Auto suggestions (datalist)**: 5 periode ke depan (mingguan/bulanan).
        -   **AJAX search** KK di tabel setoran (tanpa refresh).
        -   Nominal **bebas** akan tampil setelah disetor (read-only).

    -   Toast notifikasi **konsisten** (dark/light).

-   **Dashboard**

    -   **Bendahara**: ringkasan setoran bulan ini/tunggakan/KK sudah setor/iuran aktif, **Setoran Terbaru**, **grafik tren 12 bulan** (Chart.js) dengan opsi rentang & tipe grafik.
    -   **Sekretaris**: ringkasan data kependudukan (total KK, total Anggota, dsb).
    -   **Admin**: ringkasan sistem (pengguna per role), total entitas, setoran bulan ini, aktivitas terbaru (KK & Iuran).

-   **Akses & Mode**

    -   Role: `admin`, `sekretaris`, `bendahara` (middleware role manual di routes).
    -   Dark/Light mode siap pakai (Breeze).

---

## 🧰 Teknologi

| Komponen  | Detail                                         |
| --------- | ---------------------------------------------- |
| Framework | Laravel **10.x**                               |
| PHP       | **8.0.30** (XAMPP v3.3.0, Windows)             |
| Frontend  | Blade + Tailwind (Breeze), Bootstrap via Blade |
| Auth      | Laravel Breeze (stack: `blade`)                |
| DB        | MySQL (XAMPP)                                  |
| Export    | `phpoffice/phpspreadsheet`                     |
| JS        | Vanilla JS (+ Chart.js untuk dashboard)        |

---

## ⚙️ Prasyarat

-   **Windows + XAMPP** (MySQL & Apache aktif).
-   **Composer**, **Node.js/NPM**.
-   Database MySQL kosong (mis. `manajemendesa`).

---

## 🚀 Instalasi (Windows/XAMPP)

```bash
git clone https://github.com/mm44x/manajemendesa.git
cd manajemendesa

composer install
npm install

# Salin env & generate key
cp .env.example .env
php artisan key:generate
```

1. **Atur `.env`** (contoh):

```
APP_NAME="Manajemen Warga Desa"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemendesa
DB_USERNAME=root
DB_PASSWORD=

# Kontrol registrasi Breeze (lihat bagian Registrasi)
ALLOW_REGISTRATION=false
```

2. **Migrasi & Seed (opsional)**

> Pastikan tabel `wilayah` sudah tersedia bila ingin seeding KK acak per desa.

```bash
php artisan migrate
# contoh seeder KK+Kepala Keluarga:
php artisan db:seed --class=KartuKeluargaSeeder
```

3. **Build asset & run**

```bash
npm run build   # atau: npm run dev
php artisan serve  # http://localhost:8000
```

> Jika pakai Apache XAMPP port 80, Anda bisa arahkan VirtualHost ke `public/` atau tetap jalankan `php artisan serve` pada port 8000.

---

## 👤 Role & Akses

| Role           | Akses Utama                                                            |
| -------------- | ---------------------------------------------------------------------- |
| **Admin**      | Semua modul + dashboard admin                                          |
| **Sekretaris** | CRUD KK & Anggota + dashboard sekretaris                               |
| **Bendahara**  | Modul Iuran & Setoran + dashboard bendahara + lihat “Data Semua Warga” |

---

## 🗂️ Modul & Alur

### 1) Kartu Keluarga (KK)

-   CRUD via modal; filter wilayah AJAX.
-   Validasi `no_kk` unik, numeric, panjang tetap.

### 2) Anggota Keluarga

-   Relasi `kartu_keluarga_id`.
-   Validasi `nik` unik, numeric; tempat lahir dari dropdown wilayah.
-   Kepala Keluarga juga terdaftar di anggota (hubungan = “Kepala Keluarga”).

### 3) Data Semua Warga

-   Read-only untuk semua role; search/sort/paginate.
-   **Export Excel**: kolom No KK, NIK, Nama, JK, Tempat Lahir (kab/kota), Tgl Lahir, Hubungan, Agama, Pendidikan, Pekerjaan—**merge No KK**.

### 4) Iuran & Setoran

-   **Iuran**: nama, deskripsi, tipe (sekali/mingguan/bulanan), jenis setoran (tetap/bebas), nominal (jika tetap), peserta (multi KK).
-   **Setoran**:

    -   Pilih iuran → pilih/perbarui **periode** (dropdown + tambah periode baru).
    -   **Datalist** 5 periode ke depan (mingguan/bulanan) untuk input cepat.
    -   Tabel peserta dengan **AJAX search** (No KK / Nama KK).
    -   Nominal **bebas** tampil setelah disetor (non-editable).
    -   Cegah duplikat setoran per iuran+KK+periode.

---

## 📊 Dashboard

-   **Bendahara**

    -   Kartu ringkasan: total setoran bulan ini, estimasi tunggakan, % KK setor, iuran aktif.
    -   **Setoran Terbaru** (daftar).
    -   **Grafik tren 12 bulan** (Chart.js) dengan opsi jenis grafik & rentang.

-   **Sekretaris**

    -   Ringkasan: total KK, total Anggota, highlight data kependudukan (dapat dikembangkan).

-   **Admin**

    -   Total pengguna & **per role** (admin/sekretaris/bendahara).
    -   Total KK/Anggota/Iuran, **setoran bulan ini**.
    -   Aktivitas terbaru (KK & Iuran) + tautan aksi cepat.

---

## 🧭 Navigasi & Route (ringkas)

-   `routes/web.php` menggunakan grouping `auth` dan middleware `role:...`.
-   Modul Iuran:

    -   `Route::resource('iuran', IuranController::class)`
    -   `GET  iuran/{iuran}/setoran` → input setoran (periode + tabel peserta)
    -   `POST iuran/{iuran}/setoran` → simpan setoran
    -   `GET  iuran/{iuran}/input-setoran-ajax` → **AJAX** filter KK

---

## 🔐 Registrasi Breeze (ON/OFF)

Registrasi user bisa dinyalakan/dimatikan via ENV:

1. `config/auth.php`

```php
'allow_registration'=>env('ALLOW_REGISTRATION',false),
```

2. `routes/auth.php`

```php
use App\Http\Controllers\Auth\RegisteredUserController;

if(config('auth.allow_registration')){
    Route::middleware('guest')->group(function(){
        Route::get('/register',[RegisteredUserController::class,'create'])->name('register');
        Route::post('/register',[RegisteredUserController::class,'store']);
    });
}
```

3. `.env`

```
ALLOW_REGISTRATION=false
```

4. Clear cache:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

> Dengan `ALLOW_REGISTRATION=false`, `/register` tertutup (404). Untuk sementara membuka pendaftaran, set ke `true` lalu `php artisan config:clear`.

---

## 🧪 Seeder

-   **KartuKeluargaSeeder**: membuat ratusan KK acak dengan Kepala Keluarga dan 1 anggota minimal (kepala).

```bash
php artisan db:seed --class=KartuKeluargaSeeder
```

-   User awal: buat manual (tinker) atau aktifkan registrasi sementara (`ALLOW_REGISTRATION=true`), lalu ubah `role` pada tabel `users`.

---

## 🧱 Struktur Singkat

```
app/
 ├─ Http/Controllers/
 │   ├─ KartuKeluargaController.php
 │   ├─ AnggotaKeluargaController.php
 │   ├─ IuranController.php
 │   └─ DashboardController.php
 ├─ Models/
 │   ├─ KartuKeluarga.php  (relasi: anggota(), anggotaKeluargas(), setoranIurans())
 │   ├─ AnggotaKeluarga.php
 │   ├─ Iuran.php
 │   └─ SetoranIuran.php
resources/views/
 ├─ kartu_keluarga/
 ├─ iuran/  (index, input-setoran, _table-setoran.blade.php)
 ├─ warga/
 ├─ layouts/ (app.blade.php + footer kredit)
 └─ dashboard.blade.php
routes/
 ├─ web.php
 └─ auth.php
```

---

## 🧠 Panduan Dev

-   **Style**: arrow `(param)=>{}`, object `{key:value}` tanpa spasi.
-   **Blade**: minim JS tambahan; fokus AJAX di modul yang perlu (wilayah, setoran).
-   **Toast**: komponen toast sederhana konsisten (dark/light).
-   **Performa**: gunakan `with()/withCount()` untuk eager loading pada listing.

---

## 📦 Export Excel

Pastikan paket terpasang:

```bash
composer require phpoffice/phpspreadsheet
```

---

## 📸 Screenshot

_(tambahkan nanti: Dashboard, KK, Data Semua Warga, Iuran & Input Setoran)_

---

## 📝 Roadmap

-   [ ] Export & laporan iuran per periode (Excel).
-   [ ] Notifikasi (opsional).
-   [ ] Laporan gabungan per keluarga/periode.
-   [ ] Dashboard statistik kependudukan (opsional).
-   [ ] Import KK+Anggota via Excel.
-   [ ] Audit log & backup otomatis.

---

**Kredit**
© {{ date('Y') }} — Dibuat oleh **Bedjo** · Repo: `mm44x/manajemendesa` 🙌

---
