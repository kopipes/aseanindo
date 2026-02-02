@extends('layout-landing.master')

@section('title')
    <title>Solusi Contact Center Software - Kontakami</title>
@endsection()

@section('seo')
    <meta property="og:title" content="Kontakami - Solusi Terbaik untuk Menghubungkan Pelanggan dengan Bisnis Anda" />
    <meta property="og:description"
        content="Kontakami menyediakan solusi praktis untuk mempermudah komunikasi antara bisnis dan pelanggan melalui berbagai kanal. Tingkatkan interaksi dan kepuasan pelanggan dengan layanan kami yang andal dan efisien." />
    <meta name="description"
        content="Kontakami memberikan solusi contact center software yang praktis. Memastikan bisnis Anda berjalan efektif dan efisien.">
    <link rel="canonical" href="https://kontakami.com/" />
@endsection()

@section('content')
    <div class="bg-cover bg-center h-screen flex items-center justify-center bg-mobile"
        style="background-image: url('/img/rebranding/home-banner.webp');">
        <div class="w-full max-w-5xl mx-auto">
            <!-- Content -->
            <div class="max-w-lg px-4 text-white animate-fade-in-left-bounce">
                <p class="text-xs md:text-sm font-semibold mb-2 font-montserrat">Solusi Komunikasi Efektif</p>
                <h1 class="text-3xl md:text-6xl font-bold mb-4 font-montserrat">Layanan Contact Center Paling Praktis</h1>
                <p class="text-xs md:text-base mb-6 font-ubuntu">
                    Kontakami memberikan solusi komunikasi yang efektif sebagai layanan contact center yang praktis.
                    Platform ini dirancang untuk memastikan komunikasi yang lancar, cepat, dan dapat diakses dari mana saja
                    tanpa kerumitan.
                </p>

                <!-- Buttons -->
                <div class="flex flex-row gap-5 font-ubuntu">
                    <a href="/contact"
                        class="px-4 py-3 bg-orange text-white rounded-full text-sm font-medium hover:bg-white hover:border hover:border-orange hover:text-orange transition">Butuh
                        Bantuan?</a>
                    <a href="/product"
                        class="px-4 py-3 text-white text-sm font-medium underline transition hover:text-orange hover:font-medium">Pelajari
                        Lebih
                        Lanjut ></a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-20 px-4">
        <div class="myTinySlider flex justify-center items-center">
            <div class="flex justify-center">
                <img src="/icon/about/client-hsbc.svg" alt="HSBC" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/Green-Academy.svg" alt="Green Academy" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/mncbank.svg" alt="MNC Bank" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/dsf.svg" alt="DSF" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/Bureau-Veritas.svg" alt="Bureau Veritas" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/ciputra-life.webp" alt="Ciputra Life" class="w-auto h-28">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/bni.svg" alt="BNI" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/axa-financial.svg" alt="AXA Financial" class="h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/CAR.svg" alt="CAR" class="mx-auto h-12">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/Wall-Street.svg" alt="Wall Street" class="mx-auto h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/bumiputera.svg" alt="Bumi Putera" class="mx-auto h-20">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/indoexpathousing.svg" alt="Indo Expat Housing" class="mx-auto h-20">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/cigna.svg" alt="Cigna" class="mx-auto h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/artha-madani.svg" alt="Artha Madani" class="mx-auto h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/sali.svg" alt="Sali" class="mx-auto h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/mitratelecom.svg" alt="Mitratelecom" class="mx-auto h-24">
            </div>
            <div class="flex justify-center">
                <img src="/icon/about/aseanindo.svg" alt="Asean Indo" class="mx-auto h-12">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/indodax.png" alt="Indodax" class="mx-auto h-6">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/otto2.png" alt="Otto" class="mx-auto h-6">
            </div>
            <div class="flex justify-center">
                <img src="/icon/home/padma_legian.png" alt="Padma Legian" class="mx-auto h-20">
            </div>
        </div>
    </div>

    </div>

    <div class="max-w-5xl mx-auto py-20 px-4 md:px-8 flex flex-col lg:flex-row items-center justify-between">
        <div class="w-full lg:w-1/2 lg:pr-12 animate-fade-in-left-bounce">
            <p class="text-indigo-600 font-semibold mb-2 font-ubuntu">Produk</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-4 font-montserrat">AI QA Scoring</h2>
            <p class="text-gray-600 mb-6 font-ubuntu">
                AI QA Scoring membantu menganalisis percakapan telepon antara pelanggan dan agen secara objektif, konsisten,
                dan
                real-time; sehingga tim QA dapat fokus pada analisis, pelatihan, dan keputusan berbasis data.
            </p>
            <p class="text-orange font-semibold text-lg font-ubuntu">"Analisis Interaksi Pelanggan 100% Otomatis dan
                Real-Time"</p>
        </div>

        <div class="w-full lg:w-1/2 mb-8 lg:mb-0 relative animate-fade-in-right-bounce">
            <img src="/img/rebranding/ai-qa-scoring/human-ai-synergy.png" alt="Sinergi Manusia + AI"
                class="rounded-lg shadow-lg w-full">
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-20 px-4 md:px-8 flex flex-col lg:flex-row items-center justify-between" id="content">
        <div class="w-full lg:w-1/2 mb-8 lg:mb-0 relative animate-fade-in-left-bounce">
            <img src="/img/rebranding/komunikasi-efisien.gif" alt="Product Information" class="rounded-lg shadow-lg">

            <div class="absolute bottom-0 right-0 rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="83" height="80" viewBox="0 0 83 80" fill="none">
                    <g style="mix-blend-mode:hard-light">
                        <path
                            d="M0.180122 14.3577L0.18012 71.6903C0.180119 76.2582 3.93744 79.9944 8.53104 79.9944L63.8394 79.9944C66.3602 79.9232 68.6122 78.7233 70.0996 76.8878C69.9025 76.0562 69.3171 75.1118 68.3135 74.1079L27.1265 32.9676L6.07595 11.9401C4.81554 10.6808 3.64474 10.0749 2.68301 10.0749C1.17172 10.0749 0.180122 11.5659 0.180122 14.3577Z"
                            fill="#424EA1" />
                    </g>
                    <path
                        d="M77.072 -8.9931e-08L9.75594 -1.29403e-06C4.39405 -1.38994e-06 8.38452e-05 4.36641 8.37499e-05 9.6954L8.26014e-05 73.9013C0.0488268 75.5335 1.30922 76.9843 2.21448 78.2328C3.4679 79.9557 5.25056 80.7299 6.91483 79.0908L55.2205 31.2764L79.9131 6.84258C81.3893 5.37781 82.0996 4.01765 82.0996 2.90164C82.0996 1.15088 80.3518 -3.12641e-08 77.072 -8.9931e-08Z"
                        fill="#F57F20" />
                </svg>
            </div>
        </div>

        <div class="w-full lg:w-1/2 lg:pl-12 animate-fade-in-right-bounce">
            <p class="text-indigo-600 font-semibold mb-2 font-ubuntu">Komunikasi Efisien</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-4 font-montserrat">Maksimalkan Komunikasi dengan Internet</h2>
            <p class="text-gray-600 mb-6 font-ubuntu">
                Kini, pelanggan dapat dengan mudah berkomunikasi dengan banyak orang melalui internet. Dengan platform kami,
                interaksi menjadi lebih efisien, cepat, dan terjangkau, memungkinkan Anda untuk terhubung dengan siapa pun,
                kapan pun, tanpa batasan.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto py-16 px-4 flex flex-col-reverse lg:flex-row gap-10 items-center justify-between">

        <div class="lg:w-1/2 space-y-4 animate-fade-in-left-bounce">
            <p class="text-secblue font-semibold font-ubuntu">Benefit Penggunaan</p>
            <h2 class="text-4xl font-bold text-darkblue font-montserrat">Benefit Dalam Menggunakan Kontakami</h2>
            <ul class="space-y-4">

                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Panggilan Gratis:</span>
                            Hubungi contact center tanpa biaya (dengan koneksi internet).</p>
                    </div>
                </li>


                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Chat Fleksibel:</span>
                            Komunikasi langsung melalui chat, jika Anda tidak ingin menelepon.</p>
                    </div>
                </li>


                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Panggilan Balik:</span>
                            Jika customer service sedang sibuk, kami akan menghubungi Anda kembali..</p>
                    </div>
                </li>


                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Berbagi Lokasi:</span>
                            Mudah berbagi lokasi Anda melalui chat.</p>
                    </div>
                </li>

                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Akses Produk:</span>
                            Jelajahi produk dan layanan favorit Anda di KontaKami.</p>
                    </div>
                </li>

                <li class="flex items-center">
                    <span class="mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_942_5122)">
                                <path
                                    d="M8.5 0C3.81291 0 0 3.81291 0 8.5C0 13.1871 3.81291 17 8.5 17C13.1871 17 17 13.1871 17 8.5C17 3.81291 13.1871 0 8.5 0Z"
                                    fill="#F57F20" />
                                <path
                                    d="M12.807 6.69882L8.20277 11.3029C8.06464 11.441 7.88332 11.5106 7.702 11.5106C7.52068 11.5106 7.33936 11.441 7.20123 11.3029L4.89919 9.00086C4.62215 8.72395 4.62215 8.27623 4.89919 7.99932C5.17609 7.72228 5.62369 7.72228 5.90073 7.99932L7.702 9.80059L11.8054 5.69728C12.0823 5.42024 12.5299 5.42024 12.807 5.69728C13.0839 5.97419 13.0839 6.42178 12.807 6.69882Z"
                                    fill="#FAFAFA" />
                            </g>
                            <defs>
                                <clipPath id="clip0_942_5122">
                                    <rect width="17" height="17" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                    <div class="max-w-md">
                        <p class="text-lg text-gray-600"><span class="font-bold text-slate-900">Koneksi Cepat:</span>
                            Terhubung langsung dengan customer service tanpa penundaan.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="lg:w-1/2 flex justify-center md:justify-end animate-fade-in-right-bounce">
            <div class="relative">
                <img src="img/rebranding/home-benefit.webp" alt="Rectangle" class="rounded-lg w-full">

            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-16 px-4 md:px-8 flex flex-col justify-center items-center">
        <!-- Heading -->
        <div class="text-center mb-12 max-w-4xl animate-bounce-in">
            <h2 class="text-3xl font-bold text-gray-900 font-montserrat">Solusi Digital yang Tepat untuk Anda</h2>
            <p class="text-gray-600 mt-4 font-ubuntu">
                Kontakami menyediakan platform contact center yang dirancang khusus untuk memenuhi kebutuhan bisnis Anda.
                Dengan fleksibilitas dan fitur lengkap, kami membantu Anda meningkatkan efisiensi komunikasi dan kepuasan
                pelanggan.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 animate-bounce-in max-w-5xl">
            <!-- Card 1: Inbound Voice -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/inbound-voice.svg" alt="Inbound Voice Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Inbound Voice</h2>
                <p class="text-gray-600 font-ubuntu">
                    Terhubung langsung dengan pelanggan melalui panggilan suara, web call, dan PSTN untuk layanan yang
                    responsif.
                </p>
            </div>

            <!-- Card 2: Inbound Live Chat & Bot -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/inbound-live-chat.svg" alt="Live Chat Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Inbound Live Chat & Bot</h2>
                <p class="text-gray-600 font-ubuntu">
                    Menyediakan opsi live chat dan bot di berbagai platform, mempermudah interaksi pelanggan di mana saja.
                </p>
            </div>

            <!-- Card 3: Outbound Voice -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/outbound-voice.svg" alt="Outbound Voice Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Outbound Voice</h2>
                <p class="text-gray-600 font-ubuntu">
                    Jalankan kampanye pemasaran yang terintegrasi dengan panggilan keluar untuk meningkatkan engagement dan
                    penjualan.
                </p>
            </div>

            <!-- Card 4: Outbound Chat (WA) -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/outbound-chat.svg" alt="WA Chat Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Outbound Chat (WA)</h2>
                <p class="text-gray-600 font-ubuntu">
                    Kirim pesan terarah dan kampanye pemasaran massal melalui WhatsApp untuk menjangkau lebih banyak
                    pelanggan.
                </p>
            </div>

            <!-- Card 5: Escalation Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/escalation-management.svg" alt="Escalation Management Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Escalation Management</h2>
                <p class="text-gray-600 font-ubuntu">
                    Pantau dan kelola eskalasi dengan perjanjian layanan yang terukur, memastikan KPI terpenuhi dengan baik.
                </p>
            </div>

            <!-- Card 6: Omnichannel -->
            <div class="bg-white rounded-lg shadow-lg p-6 text-left hover:shadow-2xl transition-all duration-200">
                <div class="flex justify-start md:justify-center mb-4">
                    <img src="/icon/home/omnichannel.svg" alt="Omnichannel Icon" class="h-10">
                </div>
                <h2 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Omnichannel</h2>
                <p class="text-gray-600 font-ubuntu">
                    Kelola komunikasi pelanggan dari Facebook, Email, WhatsApp, dan Instagram dalam satu platform untuk
                    respons yang lebih efisien.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-16 px-4 md:px-8 flex flex-col justify-center items-center">
        <!-- Heading -->
        <div class="text-center mb-12 max-w-4xl">
            <h2 class="text-3xl font-bold text-gray-900 font-montserrat">Kenapa Pilih Kontakami?</h2>
            <p class="text-gray-600 mt-4 font-ubuntu">
                Kontakami menawarkan solusi contact center yang fleksibel dan efisien dengan berbagai fitur unggulan.
                Semua fitur ada dalam satu platform yang mudah digunakan tanpa perlu aplikasi tambahan.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8 max-w-5xl">
            <!-- Feature 1: Multiple Access -->
            <div class="flex items-start">
                <img src="/icon/home/escalation-management.svg" alt="Multiple Access Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Multiple Access</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Banyak cara untuk terhubung ke <a
                            href="https://kontakami.com/blog/contact-center-pengertian-manfaat-dan-rekomendasi-untuk-bisnis"><span
                                class="text-lightblue">contact center</span></a>, termasuk melalui berbagai platform
                        digital.
                    </p>
                </div>
            </div>

            <!-- Feature 2: Dukungan B2B dan B2C -->
            <div class="flex items-start">
                <img src="/icon/home/dukungan-b2b-b2c.svg" alt="Dukungan B2B dan B2C Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Dukungan B2B dan B2C</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Memudahkan komunikasi dengan corporate clients, pelanggan, serta kantor cabang tanpa nomor telepon.
                    </p>
                </div>
            </div>

            <!-- Feature 3: Form Dinamis -->
            <div class="flex items-start">
                <img src="/icon/home/form-dynamic.svg" alt="Form Dinamis Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Form Dinamis</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Formulir dapat disesuaikan sendiri sesuai kebutuhan pengguna.
                    </p>
                </div>
            </div>

            <!-- Feature 4: Opsi Live Agent -->
            <div class="flex items-start">
                <img src="/icon/home/opsi-live-agent.svg" alt="Opsi Live Agent Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Opsi Live Agent</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Terhubung langsung dengan live agent jika diperlukan, tidak hanya melalui chat atau bot.
                    </p>
                </div>
            </div>

            <!-- Feature 5: All-in-One Platform -->
            <div class="flex items-start">
                <img src="/icon/home/all-in-one-platform.svg" alt="All-in-One Platform Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">All-in-One Platform</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Mengelola komunikasi inbound, outbound, dan <a
                            href="https://kontakami.com/blog/omnichannel-contact-center-pengertian-manfaat-dan-cara-memilih"><span
                                class="text-lightblue">omnichannel</span></a> dalam satu sistem tanpa aplikasi tambahan.
                    </p>
                </div>
            </div>

            <!-- Feature 6: Call Back -->
            <div class="flex items-start">
                <img src="/icon/home/call-back.svg" alt="Call Back Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Call Back</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Pelanggan dapat meninggalkan pesan dan akan dihubungi kembali oleh live agent.
                    </p>
                </div>
            </div>

            <!-- Feature 7: Manajemen Eskalasi -->
            <div class="flex items-start">
                <img src="/icon/home/escalation-management.svg" alt="Manajemen Eskalasi Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Manajemen Eskalasi</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Menjamin KPI terpenuhi melalui eskalasi yang terukur dengan SLA dan OLA.
                    </p>
                </div>
            </div>

            <!-- Feature 8: Hemat Biaya Langganan -->
            <div class="flex items-start">
                <img src="/icon/home/hemat-biaya.svg" alt="Hemat Biaya Icon" class="h-16 mr-4">
                <div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2 font-montserrat">Hemat Biaya Langganan</h3>
                    <p class="text-gray-600 font-ubuntu">
                        Biaya telco dan CRM yang lebih terjangkau dapat meningkatkan efisiensi operasional perusahaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto py-16 px-4 md:px-8 flex flex-col justify-center items-center">
        <!-- Heading -->
        <div class="text-center mb-12 max-w-4xl">
            <h2 class="text-3xl font-bold text-gray-900 font-montserrat">Bisnis Kami</h2>
            <p class="text-gray-600 mt-4 font-ubuntu">
                Pelanggan Anda dapat terhubung dengan <a
                    href="https://kontakami.com/blog/rekomendasi-aplikasi-customer-service-untuk-solusi-digital-bisnis-anda"><span
                        class="text-lightblue">customer service</span></a> melalui web call dan live chat di situs. Kami
                siap
                mendukung bisnis Anda dengan layanan real-time yang responsif untuk kebutuhan pelanggan.
            </p>
        </div>

        <!-- Cards Section -->
        <div class="flex flex-col items-center justify-center gap-8 max-w-5xl">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col md:flex-row gap-4 justify-center items-center">
                <img src="/img/rebranding/call.webp" alt="Live Call" class="rounded-md mb-6">
                <div>
                    <span class="text-orange font-bold font-ubuntu">Live Call</span>
                    <h2 class="text-lg font-bold text-gray-900 mb-4 font-montserrat">Koneksi Real-Time dengan Live Call
                    </h2>
                    <p class="text-gray-600 mb-4 font-ubuntu">
                        Pelanggan Anda dapat terhubung langsung dengan customer service melalui panggilan suara di situs web
                        bisnis Anda. Kami siap mendukung interaksi pelanggan secara real-time untuk menjawab setiap
                        pertanyaan
                        atau kebutuhan mereka.
                    </p>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white rounded-lg shadow-lg p-6 flex flex-col md:flex-row-reverse gap-4 justify-center items-center">
                <img src="/img/rebranding/chat.webp" alt="Live Chat" class="rounded-md mb-6">
                <div>
                    <span class="text-orange font-bold font-ubuntu">Live Chat</span>
                    <h2 class="text-lg font-bold text-gray-900 mb-4 font-montserrat">Bantuan Cepat & Responsif melalui Live
                        Chat</h2>
                    <p class="text-gray-600 mb-4 font-ubuntu">
                        Pelanggan Anda dapat terhubung dengan bisnis melalui fitur <a
                            href="https://kontakami.com/blog/live-chat-customer-service-untuk-meningkatkan-pengalaman-pelanggan"><span
                                class="text-lightblue">live chat customer service</span></a> di situs web bisnis Anda. Kami
                        menyediakan bantuan cepat dan responsif untuk memastikan pengalaman pelanggan tetap lancar dan
                        efisien.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col md:flex-row gap-4 justify-center items-center">
                <img src="/img/rebranding/callback.webp" alt="Call Back" class="rounded-md mb-6">
                <div>
                    <span class="text-orange font-bold font-ubuntu">Call Back</span>
                    <h2 class="text-lg font-bold text-gray-900 mb-4 font-montserrat">Cepat Terhubung Kembali dengan Call
                        Back
                    </h2>
                    <p class="text-gray-600 mb-4 font-ubuntu">
                        Layanan Call Back memungkinkan pelanggan meninggalkan pesan untuk dihubungi kembali oleh agent saat
                        mereka sedang sibuk atau di luar jam operasional.
                    </p>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white rounded-lg shadow-lg p-6 flex flex-col md:flex-row-reverse gap-4 justify-center items-center">
                <img src="/img/rebranding/whatsapp-broadcast.webp" alt="WhatsApp Broadcast" class="rounded-md mb-6">
                <div>
                    <span class="text-orange font-bold font-ubuntu">WhatsApp Broadcast</span>
                    <h2 class="text-lg font-bold text-gray-900 mb-4 font-montserrat">Jangkau Pelanggan Lebih Mudah via
                        WhatsApp
                    </h2>
                    <p class="text-gray-600 mb-4 font-ubuntu">
                        Kirimkan promosi dan informasi produk langsung ke banyak pelanggan dalam sekali kirim melalui
                        WhatsApp
                        Broadcast dengan respons yang lebih cepat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="relative bg-cover bg-center h-80 flex items-center justify-center"
        style="background-image: url('/img/rebranding/footer-banner.webp');">
        <div class="relative z-10 text-center text-white px-4 max-w-3xl">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-montserrat">Optimalkan Layanan Pelanggan Anda</h2>
            <p class="text-sm md:text-base mb-6 font-ubuntu">
                Tingkatkan efisiensi dan keterlibatan pelanggan dengan platform digital contact center yang lengkap.
                Nikmati kemudahan akses, integrasi omnichannel, dan manajemen layanan terbaik dengan Kontakami.
            </p>
            <a href="/contact"
                class="inline-block px-6 py-3 bg-orange text-white rounded-xl font-bold text-sm hover:bg-white hover:border hover:border-orange hover:text-orange transition font-montserrat">
                Mulai Sekarang
            </a>
        </div>
    </div>
@endsection()

@section('scripts')
    <!-- Tiny Slider JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js"></script>
    <script>
        var slider = tns({
            container: '.myTinySlider',
            items: 3,
            slideBy: 'page',
            autoplay: true,
            autoplayTimeout: 2500,
            autoplayButtonOutput: false,
            controls: false,
            loop: true,
            mouseDrag: true,

            gutter: 30,
            responsive: {
                640: {
                    items: 2,
                    gutter: 20
                },
                768: {
                    items: 6,
                    gutter: 30
                },
                1024: {
                    items: 8,
                    gutter: 30
                }
            }
        });
    </script>
@endsection()