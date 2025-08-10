{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Warga Desa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    {{-- NAVBAR --}}
    <header
        class="sticky top-0 z-40 backdrop-blur border-b border-gray-200/60 dark:border-gray-800/60 bg-white/70 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span
                    class="inline-flex h-8 w-8 rounded-xl bg-blue-600 text-white items-center justify-center font-bold">MD</span>
                <span class="font-semibold">Manajemen Warga Desa</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="#fitur" class="hover:text-blue-600 dark:hover:text-blue-400">Fitur</a>
                <a href="#tentang" class="hover:text-blue-600 dark:hover:text-blue-400">Tentang</a>
                <a href="#kontak" class="hover:text-blue-600 dark:hover:text-blue-400">Kontak</a>
            </nav>
            <div class="hidden md:flex items-center gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-sm">Masuk</a>
                        {{-- tombol Register disembunyikan --}}
                    @endauth
                @endif
            </div>

            <button id="menuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                aria-label="Toggle menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 dark:border-gray-800">
            <nav class="px-4 py-3 flex flex-col gap-3 text-sm">
                <a href="#fitur" class="hover:text-blue-600 dark:hover:text-blue-400">Fitur</a>
                <a href="#tentang" class="hover:text-blue-600 dark:hover:text-blue-400">Tentang</a>
                <a href="#kontak" class="hover:text-blue-600 dark:hover:text-blue-400">Kontak</a>
                <div class="pt-2 border-t border-gray-200 dark:border-gray-800">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="block px-3 py-2 rounded-lg bg-blue-600 text-white text-center">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2 rounded-lg border text-center border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">Masuk</a>
                            {{-- tombol Register disembunyikan --}}
                        @endauth
                    @endif
                </div>

            </nav>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-blue-50 to-transparent dark:from-blue-900/20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 text-xs mb-4">
                        <span>✅ Laravel 10 + Breeze</span><span>•</span><span>Tailwind + Dark
                            Mode</span><span>•</span><span>Export Excel</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight">
                        Aplikasi Manajemen Warga Desa
                    </h1>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        Kelola data Kartu Keluarga, Anggota, dan Iuran dengan cepat, aman, dan terstruktur. Dirancang
                        khusus untuk admin desa, sekretaris, dan bendahara.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Buka Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Masuk Aplikasi</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">Daftar
                                        Akun</a>
                                @endif
                            @endauth
                        @endif
                        <a href="#fitur"
                            class="px-5 py-3 rounded-lg border border-transparent hover:border-gray-300 dark:hover:border-gray-700">Lihat
                            Fitur</a>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 shadow-xl p-4">
                        {{-- Ilustrasi / Preview UI sederhana --}}
                        <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="h-10 bg-gray-100 dark:bg-gray-700 flex items-center gap-1 px-3">
                                <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
                                <span class="h-3 w-3 rounded-full bg-green-400"></span>
                            </div>
                            <div class="p-4">
                                <div class="h-4 w-36 bg-gray-200 dark:bg-gray-700 rounded mb-3"></div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div
                                        class="h-24 rounded bg-gray-100 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700">
                                    </div>
                                    <div
                                        class="h-24 rounded bg-gray-100 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700">
                                    </div>
                                    <div
                                        class="h-24 rounded bg-gray-100 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700">
                                    </div>
                                </div>
                                <div
                                    class="h-32 mt-4 rounded bg-gray-100 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 blur-2xl opacity-30 pointer-events-none">
                        <div class="h-24 w-24 rounded-full bg-blue-400"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FITUR --}}
    <section id="fitur" class="py-14 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Fitur Utama</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
                    <div class="h-10 w-10 rounded-lg bg-blue-600 text-white flex items-center justify-center mb-3">
                        {{-- icon KK --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 3a3 3 0 00-3 3v9h4v-1a3 3 0 016 0v1h8V6a3 3 0 00-3-3H6z" />
                            <path d="M7 21a1 1 0 01-1-1v-4H3v4a3 3 0 003 3h14a3 3 0 003-3v-4h-3v4a1 1 0 01-1 1H7z" />
                        </svg>
                    </div>
                    <div class="font-semibold mb-1">Manajemen KK</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">CRUD Kartu Keluarga dengan filter wilayah AJAX &
                        validasi.</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
                    <div class="h-10 w-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center mb-3">
                        {{-- icon anggota --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 8a4 4 0 118 0 4 4 0 01-8 0z" />
                            <path fill-rule="evenodd"
                                d="M.458 20.042A10.003 10.003 0 0112 13a10.003 10.003 0 0111.542 7.042A1 1 0 0122.583 22H1.417a1 1 0 01-.959-1.958z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="font-semibold mb-1">Data Anggota</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Relasi anggota-ke-KK, dropdown statik/dinamis,
                        validasi NIK.</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
                    <div class="h-10 w-10 rounded-lg bg-orange-500 text-white flex items-center justify-center mb-3">
                        {{-- icon iuran --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 1.5a1 1 0 01.894.553l1.618 3.236 3.571.519a1 1 0 01.554 1.705l-2.584 2.52.61 3.555a1 1 0 01-1.451 1.054L12 12.75l-3.212 1.69a1 1 0 01-1.451-1.054l.61-3.556L5.363 6.99a1 1 0 01.554-1.705l3.571-.519L11.382 2.053A1 1 0 0112 1.5z" />
                            <path d="M3 19.5A1.5 1.5 0 004.5 21h15a1.5 1.5 0 001.5-1.5V12h-2.25v6.75h-12V12H3v7.5z" />
                        </svg>
                    </div>
                    <div class="font-semibold mb-1">Iuran & Setoran</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Iuran sekali/berulang, tracking periode, input
                        AJAX, export.</p>
                </div>
                <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
                    <div class="h-10 w-10 rounded-lg bg-purple-600 text-white flex items-center justify-center mb-3">
                        {{-- icon export --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M3 3a2 2 0 00-2 2v11a2 2 0 002 2h6v-2H3V5h18v11h-6v2h6a2 2 0 002-2V5a2 2 0 00-2-2H3z" />
                            <path d="M12 21l-4-4h3v-6h2v6h3l-4 4z" />
                        </svg>
                    </div>
                    <div class="font-semibold mb-1">Export Excel</div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Export data warga/iuran rapi sesuai kolom yang
                        ditentukan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-xl font-bold mb-2">Kenapa aplikasi ini?</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Fokus pada kecepatan input dan akurasi data, role-based access sederhana tanpa paket eksternal,
                        serta pengalaman bendahara saat input setoran yang efisien.
                    </p>
                    <div class="mt-4 flex gap-3">
                        <a href="https://github.com/mm44x/manajemendesa" target="_blank"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-sm">Lihat
                            Source</a>
                        <a href="{{ route('semua-warga.index') }}"
                            class="px-4 py-2 rounded-lg bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 hover:opacity-90 text-sm">Lihat
                            Data Warga</a>
                    </div>
                </div>
                <div
                    class="rounded-xl bg-gradient-to-br from-gray-100 to-white dark:from-gray-800 dark:to-gray-900 border border-gray-200 dark:border-gray-800 p-6">
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3"><span
                                class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span><span>Laravel 10, PHP 8.0.30
                                (XAMPP), MySQL</span></li>
                        <li class="flex items-start gap-3"><span
                                class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span><span>Breeze Auth
                                (Login/Register), Tailwind, komponen Blade</span></li>
                        <li class="flex items-start gap-3"><span
                                class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span><span>Excel Export
                                (PhpSpreadsheet), AJAX filtering</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTAK / CTA --}}
    <section id="kontak" class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-2xl border border-gray-200 dark:border-gray-800 p-6 bg-white dark:bg-gray-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <div class="text-lg font-semibold">Siap mencoba di lingkungan Anda?</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">Login untuk mulai mengelola data warga &
                        iuran sekarang.</div>
                </div>
                <div class="flex gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Masuk</a>
                            {{-- tombol Register disembunyikan --}}
                        @endauth
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="mt-10 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 dark:text-gray-400">
            <hr class="mb-3 border-gray-200 dark:border-gray-800">
            &copy; {{ date('Y') }} Aplikasi Manajemen Warga Desa — Dibuat oleh <span
                class="font-semibold text-blue-600 dark:text-blue-400">Pratama Ardy Prayoga</span>. Sumber kode: <a
                href="https://github.com/mm44x/manajemendesa" class="underline">mm44x/manajemendesa</a>.
        </div>
    </footer>

    <script>
        // Toggle mobile menu (ikuti style: (param)=>{} dan {key:value})
        const $ = (s) => document.querySelector(s);
        const menuBtn = $('#menuBtn');
        const mobileMenu = $('#mobileMenu');
        menuBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
