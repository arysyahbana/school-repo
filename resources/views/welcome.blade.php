<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Arsip MTsN 9 TD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  </head>
  <body>
    <div class="h-full bg-slate-50">
        {{-- Navbar --}}
        <nav id="navbar" class="fixed w-full z-20 top-0 start-0 transition-all duration-300">
            <div class="container max-w-screen flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="#" class="space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('dist/assets/img/logo.png') }}" class="w-20" alt="logo">
                </a>
                <div class="flex items-center gap-8 md:order-2">
                    <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    @auth
                        <a href="{{ route('dashboard') }}" type="button"
                        class="inline-flex items-center gap-2 focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 transition duration-300">

                            <!-- SVG Dashboard -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 13h8V3H3v10zm10 8h8v-6h-8v6zM3 21h8v-6H3v6zm10-8h8V3h-8v10z"/>
                            </svg>

                            Dashboard
                        </a>
                    @endauth


                    @guest
                        <a href="{{ route('login') }}" type="button"
                        class="inline-flex items-center gap-2 focus:outline-none text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 transition duration-300">

                            <!-- SVG Login -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12H3m0 0l4-4m-4 4l4 4m6 4h5a2 2 0 002-2V6a2 2 0 00-2-2h-5"/>
                            </svg>

                            Login
                        </a>
                    @endguest
                    {{-- <button data-collapse-toggle="navbar-sticky" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        aria-controls="navbar-sticky" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button> --}}
                </div>
                {{-- <a href="#" class="block py-2 px-3 text-white rounded md:hover:text-slate-900 md:p-0">Panduan Pengguna</a>
                <a href="#" class="block py-2 px-3 text-white rounded md:hover:text-slate-900 md:p-0">Kontak Admin</a> --}}
                </div>
            </div>
        </nav>


        <section id="banner" class="relative overflow-hidden md:min-h-[65vh]">
            <img
                src="{{ asset('dist/assets/img/backgrounds/Hero5.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover"
                alt=""
            />

            <div class="absolute inset-0 bg-black/50"></div>

            <div class="relative container mx-auto pt-16 pb-12 md:pt-48">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="text-center md:text-start">
                        <p class="inline-flex items-center gap-2
                                text-teal-300
                                font-semibold
                                uppercase
                                tracking-[0.25em]
                                text-sm
                                mb-4">

                            <span class="w-8 h-[2px] bg-teal-400"></span>

                            MTsN 9 Tanah Datar

                        </p>
                        <h1 class="text-white text-5xl md:text-7xl font-bold mb-4">
                            Sistem <span class="text-teal-500 [-webkit-text-stroke:0.8px_white]">Arsip</span> <br> <span class="text-teal-500 [-webkit-text-stroke:0.8px_white]">Digital</span> Terpadu
                        </h1>

                        <p class="text-white text-lg md:text-2xl font-medium mb-4">
                            Mengelola dokumen dan file secara <br>
                            terstruktur, aman, dan mudah diakses saja.
                        </p>
                        <div class="flex gap-2 justify-center md:justify-start">
                            <a href="{{ route('login') }}" type="button"
                            class="inline-flex items-center gap-2 focus:outline-none text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-500 font-medium rounded-xl text-sm px-5 py-2.5 transition duration-300">

                                <!-- SVG Login -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H3m0 0l4-4m-4 4l4 4m6 4h5a2 2 0 002-2V6a2 2 0 00-2-2h-5"/>
                                </svg>

                                Masuk Sistem
                            </a>

                            <a href="{{ route('register') }}" type="button"
                            class="inline-flex items-center gap-2 focus:outline-none text-white bg-slate-500 hover:bg-slate-600 focus:ring-4 focus:ring-slate-500 font-medium rounded-xl text-sm px-5 py-2.5 transition duration-300">

                                <!-- SVG Register -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v6m3-3h-6M16 21H8a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h2a2 2 0 012 2v4"/>
                                </svg>

                                Daftar Sistem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="konten" class="mt-16">
            {{-- Alasan --}}
            <div class="mx-auto container mb-24">
                <div class="max-w-6xl mx-auto px-6 text-center">

                    <!-- Heading -->
                    <h2 class="text-4xl font-bold mb-2 text-green-600">
                        Mengapa Sistem Ini Dibangun?
                    </h2>
                    <p class="text-slate-600 max-w-2xl mx-auto mb-8 leading-relaxed">
                        Dengan adanya file penting yang berantakan dan tersebar di berbagai tempat,
                        MTsN 9 Tanah Datar menghadirkan solusi arsip digital terpadu untuk
                        mengelola semua arsip sekolah secara lebih mudah dan terpusat.
                    </p>

                    <!-- Cards -->
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-8">

                        <!-- CARD 1 -->
                        <div class="bg-white rounded-3xl shadow-md p-6 text-left hover:shadow-lg transition">
                        <div class="mb-6">
                            <img src="{{ asset('dist/assets/img/berantakan.jpg') }}" alt="" class="rounded-2xl max-h-[200px] w-full object-cover">
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="bg-yellow-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a4 4 0 00-4 4v3H5a3 3 0 000 6h10a3 3 0 000-6h-1V6a4 4 0 00-4-4z"/>
                            </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                            Dokumen Berantakan
                            </h3>
                        </div>
                        </div>

                        <!-- CARD 2 -->
                        <div class="bg-white rounded-3xl shadow-md p-6 text-left hover:shadow-lg transition">
                        <div class="mb-6">
                            <img src="{{ asset('dist/assets/img/tersebar.jpg') }}" alt="" class="rounded-2xl max-h-[200px] w-full object-cover">
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="bg-purple-100 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 15a4 4 0 011-7.874A5 5 0 1116 13H5a2 2 0 01-2-2z"/>
                            </svg>
                            </div>
                            <div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                File Tersebar
                            </h3>
                            <p class="text-sm text-gray-500">Login Guru</p>
                            </div>
                        </div>
                        </div>

                        <!-- CARD 3 -->
                        <div class="bg-white rounded-3xl shadow-md p-8 text-left hover:shadow-lg transition">
                        <div class="mb-6 text-center">
                            <img src="{{ asset('dist/assets/img/logo.png') }}" alt="" class="w-24 mx-auto rounded-2xl">
                        </div>

                        <h3 class="text-2xl font-bold text-yellow-400 mb-3">
                            Solusi Arsip Digital
                        </h3>
                        <p class="text-slate-600 leading-relaxed">
                            Mengelola arsip secara terpusat dan rapi, meningkatkan efisiensi
                            administrasi sekolah.
                        </p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Fitur --}}
            <div class="mx-auto container mb-24">
                <div class="flex flex-col items-center text-center">
                    <h2 class="text-4xl font-bold mb-2 text-green-600">Fitur Unggulan</h2>
                    <p class="text-slate-600">Semua yang Anda butuhkan untuk tetap terorganisir</p>
                </div>
                <div class="flex flex-wrap gap-8 mt-8 md:items-center justify-center">
                    <div class="bg-white shadow rounded-xl p-4 w-1/4 min-h-[105px] hover:shadow-lg transition duration-300 cursor-default">
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="min-w-20 min-h-20 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-.75 0h10.5a.75.75 0 01.75.75v7.5a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75v-7.5a.75.75 0 01.75-.75z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-semibold text-slate-800">Secure Storage</h3>
                                <p class="text-slate-600">Your files are stored securely with top-notch encryption</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow rounded-xl p-4 w-1/4 min-h-[105px] hover:shadow-lg transition duration-300 cursor-default">
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="min-w-20 min-h-20 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6a2.25 2.25 0 012.25-2.25h3.379a2.25 2.25 0 011.59.659l1.122 1.122a2.25 2.25 0 001.59.659H18A2.25 2.25 0 0120.25 8.25v7.5A2.25 2.25 0 0118 18H6a2.25 2.25 0 01-2.25-2.25V6z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-semibold text-slate-800">Easy Organization</h3>
                                <p class="text-slate-600">Create folders and categorize your files easily.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-8 mt-8 md:items-center justify-center">
                    <div class="bg-white shadow rounded-xl p-4 w-1/4 min-h-[105px] hover:shadow-lg transition duration-300 cursor-default">
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="min-w-20 min-h-20 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-purple-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 12a2.25 2.25 0 104.5 0 2.25 2.25 0 00-4.5 0zM15.75 6.75a2.25 2.25 0 110 4.5 2.25 2.25 0 010-4.5zM15.75 12.75a2.25 2.25 0 110 4.5 2.25 2.25 0 010-4.5zM9.557 10.693l4.386-2.536M9.557 13.307l4.386 2.536" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-semibold text-slate-800">Quick Sharing</h3>
                                <p class="text-slate-600">Share document with a single click</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow rounded-xl p-4 w-1/4 min-h-[105px] hover:shadow-lg transition duration-300 cursor-default">
                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="min-w-20 min-h-20 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-orange-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3.75a8.25 8.25 0 100 16.5 8.25 8.25 0 000-16.5zM3.75 12h16.5M12 3.75c2.25 2.25 2.25 14.25 0 16.5M12 3.75c-2.25 2.25-2.25 14.25 0 16.5" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-md font-semibold text-slate-800">Access Anywhere</h3>
                                <p class="text-slate-600">Access your files from any device, anytime and anywhere</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pengguna --}}
            <div class="mx-auto container mb-24">
                <div class="flex flex-col items-center text-center">
                    <h2 class="text-4xl font-bold mb-2 text-green-600">Pengguna</h2>
                    <p class="text-slate-600">Dipercaya oleh para profesional seperti Anda.</p>
                </div>

                @php
                    $chunks = $users->chunk(6);
                @endphp
                <div class="swiper userSwiper">

                    <div class="swiper-wrapper">

                        @foreach($chunks as $group)

                            <div class="swiper-slide">

                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 py-12">

                                    @foreach($group as $user)

                                        {{-- CARD USER --}}
                                        <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                            <div class="h-12
                                                @if($user->jabatan == 'kepala_madrasah')
                                                    bg-blue-500
                                                @elseif($user->jabatan == 'wakil')
                                                    bg-indigo-500
                                                @elseif($user->jabatan == 'guru')
                                                    bg-green-500
                                                @elseif($user->jabatan == 'kaur')
                                                    bg-amber-500
                                                @elseif($user->jabatan == 'tu')
                                                    bg-slate-500
                                                @else
                                                    bg-red-500
                                                @endif">
                                            </div>

                                            <div class="px-5 pb-5 text-center">

                                                <div class="-mt-10 mb-3 flex justify-center">

                                                    <img
                                                        src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.$user->name }}"
                                                        class="w-20 h-20 rounded-full border-4 border-white shadow object-cover">

                                                </div>

                                                <h3 class="font-bold text-slate-800 line-clamp-1">
                                                    {{ $user->name }}
                                                </h3>

                                                <p class="text-sm text-slate-500 mb-3">
                                                    {{ ucwords(str_replace('_',' ', $user->jabatan)) }}
                                                </p>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <div class="swiper-pagination mt-8"></div>

                </div>
            </div>
        </section>

        <footer class="bg-green-50 border-t py-6">
            <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">

                <div class="flex items-center gap-3">
                    <img src="{{ asset('dist/assets/img/logo2.png') }}" alt="" class="max-h-12">
                    <div>
                        <p class="font-bold text-green-700">MTsN 9 Tanah Datar</p>
                        <p class="text-sm text-gray-600">Jl. Padang Panjang – Batusangkar KM 9</p>
                    </div>
                </div>

                <a href="{{ route('login') }}" type="button"
                class="inline-flex items-center gap-2 focus:outline-none text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 transition duration-300">

                    <!-- SVG Login -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12H3m0 0l4-4m-4 4l4 4m6 4h5a2 2 0 002-2V6a2 2 0 00-2-2h-5"/>
                    </svg>

                    Login
                </a>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  </body>

    <script>
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-black/50', 'backdrop-blur-md', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-black/50', 'backdrop-blur-md', 'shadow-lg');
            }
        });
    </script>

    <script>
new Swiper(".userSwiper", {

    loop: true,

    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },

    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    speed: 800,
});
</script>

</html>
