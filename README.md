---
# 🏘️ Aplikasi Manajemen Warga Desa

Sebuah aplikasi berbasis Laravel + Bootstrap untuk membantu pengelolaan data Kartu Keluarga (KK), Anggota Keluarga, dan seluruh data warga desa secara modern dan efisien.
---

## 📌 Fitur Utama

-   Manajemen Kartu Keluarga (CRUD)
-   Manajemen Anggota Keluarga (CRUD)
-   Dropdown wilayah bertingkat berbasis AJAX (Provinsi → Kota/Kabupaten → Kecamatan → Desa)
-   Modal dinamis untuk tambah/edit anggota
-   Tampilan adaptif dark/light mode
-   Hak akses berdasarkan role (`admin`, `sekretaris`, `bendahara`)
-   Halaman "Data Semua Warga" untuk akses publik semua role
-   Pencarian, filter, dan pagination
-   Export ke Excel

---

## 🧑‍💻 Teknologi yang Digunakan

| Teknologi      | Versi / Info               |
| -------------- | -------------------------- |
| Laravel        | 10.x                       |
| PHP            | 8.0.30 (XAMPP v3.3.0)      |
| Bootstrap      | via Laravel Breeze + Blade |
| Tailwind CSS   | via Laravel Breeze         |
| Laravel Breeze | stack: `blade`             |
| Database       | MySQL via XAMPP            |
| JavaScript     | Vanilla JS + AJAX          |

---

## 🚀 Instalasi Lokal

1. Clone repo:

    ```bash
    git clone https://github.com/mm44x/manajemendesa.git
    cd manajemendesa
    ```

2. Install dependensi:

    ```bash
    composer install
    npm install && npm run build
    ```

3. Salin `.env` dan atur database:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Buat database dan jalankan migrasi:

    ```bash
    php artisan migrate --seed
    ```

5. Jalankan server:

    ```bash
    php artisan serve
    ```

6. Login awal:

    - Email: `admin@example.com`
    - Password: `password`

---

## 🧭 Panduan Pengguna

### 👤 Role & Akses

| Role       | Akses Fitur                                 |
| ---------- | ------------------------------------------- |
| Admin      | Semua fitur (KK, Anggota, Semua Warga)      |
| Sekretaris | CRUD KK, CRUD Anggota                       |
| Bendahara  | Hanya bisa lihat data di "Data Semua Warga" |

### 📂 Manajemen KK

-   Tambah KK melalui tombol “+ Tambah KK”
-   Gunakan dropdown wilayah bertingkat untuk memilih desa
-   Simpan data, lalu tambah anggota

### 👨‍👩‍👧‍👦 Manajemen Anggota Keluarga

-   Klik “+ Tambah Anggota” pada detail KK
-   Pilih tempat lahir menggunakan dropdown wilayah
-   Edit atau hapus anggota lewat tombol aksi
-   Role `admin` dapat melihat semua data detail

### 👁️ Data Semua Warga

-   Diakses dari sidebar oleh semua role
-   Fitur:

    -   Tabel grup berdasarkan KK
    -   Kolom: No KK, NIK, Nama
    -   Pencarian (NIK, Nama, No KK)
    -   Export ke Excel
    -   Tombol lihat detail warga

---

## 📝 To-Do List & Saran Pengembangan

### 📋 To-Do List (Tahap Lanjut)

-   [ ] Sistem verifikasi email pengguna baru
-   [ ] Import KK + Anggota via Excel
-   [ ] Manajemen mutasi penduduk (pindah/mati)
-   [ ] Role tambahan: RW/RT untuk filter warga
-   [ ] Cetak KK (PDF)
-   [ ] Sistem notifikasi (misal: ulang tahun, peringatan data)
-   [ ] Audit log (riwayat perubahan data)

### 💡 Saran Lain

-   Penambahan dashboard statistik (grafik total warga, jenis kelamin, agama, dll)
-   Integrasi dengan API Dukcapil lokal (jika tersedia)
-   Fitur backup data otomatis (Excel + SQL)
-   Modul surat pengantar berbasis warga

---

## 🖼️ Screenshots

_(Later)_

---
