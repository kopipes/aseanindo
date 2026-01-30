@extends('layout-landing.master')

@section('title')
    <title>Kontakami - Tentang Kami</title>
@endsection
@section('seo')
    <meta name="description"
        content="Kontakami adalah solusi contact center digital untuk layanan customer service yang dirancang untuk memberikan setup yang cepat dan mudah, dengan biaya yang efisien dan terjangkau.">
    <link rel="canonical" href="https://kontakami.com/about" />
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <section>
            <div class="w-full bg-white flex flex-col justify-center items-center mt-[10rem] md:mt-32">
                <section id="hero" class="max-w-7xl h-[400px] flex justify-center items-center bg-white mb-10 md:mb-0">
                    <div
                        class="max-w-6xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 items-center px-6 py-6 bg-white">
                        <div class="flex flex-col space-y-4 animate-fade-in-left-bounce">
                            <h3 class="text-lg font-ubuntu text-secblue font-semibold">Tentang Kami</h3>
                            <h1 class="text-4xl font-montserrat font-bold text-darkblue leading-tight">
                                Platform Digital <br> Contact Center
                            </h1>
                            <p class="text-black font-ubuntu text-base">
                                Kontakami adalah solusi contact center digital untuk layanan customer service yang dirancang
                                untuk
                                memberikan setup yang cepat dan mudah, dengan biaya yang efisien dan terjangkau.
                            </p>
                        </div>

                        <div class="relative animate-fade-in-right-bounce">
                            <img src="/img/rebranding/about-banner.webp" alt="Contact Center Image" class="rounded-lg">
                            <div class="absolute bottom-0 right-0 rotate-180">
                                <svg xmlns="http://www.w3.org/2000/svg" width="83" height="80" viewBox="0 0 83 80"
                                    fill="none">
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
                    </div>
                </section>

                <section id="vision-mission" class="max-w-7xl bg-white flex flex-col items-center pt-[6rem] mb-10 md:mb-20">
                    <div class="max-w-7xl w-full mx-auto text-center">
                        <h3 class="text-lg font-ubuntu text-secblue font-semibold mb-2">Visi & Misi</h3>
                        <h2 class="text-4xl font-montserrat font-bold text-darkblue md:mb-10">
                            Visi dan Misi dari Kontakami
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto p-8">
                        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-start">
                            <div class="flex items-center mb-4">
                                <img src="/icon/about/visi.svg" alt="Icon" class="mr-2">
                                <h3 class="text-lg font-bold text-secblue font-montserrat">Visi Kami</h3>
                            </div>
                            <p class="text-black font-ubuntu">Memberikan solusi customer engagement yang lebih baik dan
                                efektif
                                melalui teknologi digital contact center yang inovatif.</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-start">
                            <div class="flex items-center mb-4">
                                <img src="/icon/about/misi.svg" alt="Icon" class="mr-2">
                                <h3 class="text-lg font-bold text-secblue font-montserrat">Misi Kami</h3>
                            </div>
                            <p class="text-black font-ubuntu">Menjadi pilihan utama dalam menyediakan solusi Digital Contact
                                Center yang andal dan inovatif.</p>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <section id="service"
            class="max-w-7xl bg-white py-4 px-4 flex flex-col mx-auto items-center justify-center mb-16">
            <div class="max-w-5xl w-full mx-auto text-center mb-12">
                <h2 class="text-4xl mx-auto font-montserrat font-bold text-darkblue mb-4">Solusi Lengkap Manajemen
                    Komunikasi</h2>
                <p class="text-lg font-ubuntu text-black">
                    Kontakami menghadirkan fitur lengkap untuk mengelola komunikasi inbound dan outbound, didukung oleh
                    teknologi <a
                        href="https://kontakami.com/blog/omnichannel-contact-center-pengertian-manfaat-dan-cara-memilih"><span
                            class="text-secblue">omnichannel</span></a> dan manajemen eskalasi yang memastikan efisiensi
                    layanan pelanggan Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl w-full mx-auto items-center">
                <div class="space-y-8">
                    <div class="flex items-center justify-between gap-5 flex-row-reverse md:flex-row">
                        <div class="text-start md:text-end">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Inbound Voice</h4>
                            <p class="font-ubuntu text-base text-black">
                                Terhubung langsung dengan pelanggan melalui panggilan suara, baik melalui web call maupun
                                PSTN.
                            </p>
                        </div>
                        <div class="text-right">
                            <img src="/icon/about/inbound.svg" alt="Icon" class="h-24 mr-4">
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-5 flex-row-reverse md:flex-row">
                        <div class="text-start md:text-end">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Inbound Live Chat & Bot</h4>
                            <p class="font-ubuntu text-base text-black">
                                Menyediakan opsi live chat dan bot yang dapat diakses melalui web, WhatsApp, dan saluran
                                lainnya.
                            </p>
                        </div>
                        <div class="text-right">
                            <img src="/icon/about/inbound-live-chat.svg" alt=" Icon" class="h-24 mr-4">
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-5 flex-row-reverse md:flex-row">
                        <div class="text-start md:text-end">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Escalation Management</h4>
                            <p class="font-ubuntu text-base text-black">
                                Pantau dan kelola eskalasi sesuai dengan SLA (Service Level Agreement) yang terukur.
                            </p>
                        </div>
                        <div class="text-right">
                            <img src="/icon/about/escalation-management.svg" alt=" Icon" class="h-24 mr-4">
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <img src="/img/rebranding/solusi-lengkap.webp" alt="Customer Service"
                        class="rounded-lg w-full md:w-[300px] md:h-[400px] object-cover">
                </div>

                <div class="space-y-8">
                    <div class="flex items-center justify-between gap-5">
                        <div class="text-left">
                            <img src="/icon/about/outbound.svg" alt=" Icon" class="h-24 mr-4">
                        </div>
                        <div class="text-start">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Outbound Voice</h4>
                            <p class="font-ubuntu text-base text-black">
                                Jalankan kampanye pemasaran dan follow-up pelanggan melalui panggilan keluar yang
                                terintegrasi.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-5">
                        <div class="text-left">
                            <img src="/icon/about/outbound-chat.svg" alt=" Icon" class="h-24 mr-4">
                        </div>
                        <div class="text-start">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Outbound Chat</h4>
                            <p class="font-ubuntu text-base text-black">
                                Kirim pesan terarah dan kampanye pemasaran melalui WhatsApp untuk meningkatkan engagement.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-5">
                        <div class="text-left">
                            <img src="/icon/about/omnichannel.svg" alt=" Icon" class="h-24 mr-4">
                        </div>
                        <div class="text-start">
                            <h4 class="text-lg font-montserrat font-bold text-secblue">Omnichannel</h4>
                            <p class="font-ubuntu text-base text-black">
                                Atur komunikasi lintas platform dari satu sistem terpusat, memastikan respons cepat dan
                                konsisten.
                            </p>
                        </div>
                    </div>
                </div>
            </div>



        </section>

        <section id="solutions" class="max-w-7xl bg-white py-16">
            <div class="max-w-7xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center px-6">
                <div class="relative">
                    <img src="/img/rebranding/about-solution.webp" alt="Solutions Image"
                        class="rounded-lg w-full object-cover">
                </div>

                <div>
                    <h3 class="text-lg font-ubuntu text-secblue font-semibold mb-2">Layanan Call Center Terintegrasi dan
                        Fleksibel</h3>
                    <h2 class="text-4xl font-montserrat font-bold text-darkblue mb-4">
                        Solusi Terpadu untuk Operasional Call Center Anda
                    </h2>
                    <p class="text-base font-ubuntu text-black leading-relaxed">
                        Kontakami menawarkan solusi call center terintegrasi dan fleksibel, dirancang untuk memenuhi
                        kebutuhan
                        bisnis Anda dengan setup cepat dan prosedur yang mudah. Dengan fitur-fitur unggulan seperti
                        broadcast
                        message untuk promosi, kampanye pemasaran melalui outgoing call, dan layanan booking untuk industri
                        seperti rumah sakit atau restoran, Kontakami memastikan operasional yang efisien. Selain itu,
                        layanan
                        call center ini memungkinkan customer service bekerja dari mana saja, didukung oleh voice recording
                        otomatis, report kinerja lengkap, dan CRM untuk pengelolaan pelanggan yang lebih baik.
                    </p>
                </div>
            </div>
        </section>

        <section id="history" class="max-w-7xl px-4 bg-white py-16">
            <div class="max-w-4xl w-full mx-auto text-center">
                <h2 class="text-4xl font-montserrat font-bold text-darkblue mb-6">
                    Bagian dari Aseanindo
                </h2>
                <p class="text-lg font-ubuntu text-black leading-relaxed">
                    Sejak 2009, Aseanindo telah berpengalaman dalam memberikan solusi layanan call center dan teknologi
                    untuk
                    berbagai industri seperti perbankan dan asuransi. Menggabungkan pengalaman bertahun-tahun dengan
                    kebutuhan
                    klien besar, Aseanindo mengembangkan Kontakami, sebuah platform digital
                    <a href="https://kontakami.com/blog/contact-center-pengertian-manfaat-dan-rekomendasi-untuk-bisnis"><span
                            class="text-secblue">contact
                            center</span></a> dengan
                    fitur-fitur
                    unggulan yang lebih lengkap dan inovatif. Kontakami hadir sebagai hasil dari perjalanan panjang
                    Aseanindo
                    dalam menyediakan solusi yang efisien, fleksibel, dan mampu memenuhi tuntutan bisnis modern.
                </p>
            </div>
        </section>
    </div>
    <div>
        <section id="achievements" class="bg-[#3943B7] py-16"
            style="background-image: url('/img/rebranding/achievement.webp');">
            <div class="max-w-5xl mx-auto text-center">
                <h2 class="text-4xl font-montserrat font-bold text-white mb-12">Pencapaian Kami</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg py-6 px-8 shadow-lg mx-4">
                        <h3 class="text-3xl font-montserrat font-bold text-secblue">15+ Tahun</h3>
                        <p class="text-lg font-ubuntu text-orange mt-2">Pengalaman</p>
                    </div>
                    <div class="bg-white rounded-lg py-6 px-8 shadow-lg mx-4">
                        <h3 class="text-3xl font-montserrat font-bold text-secblue">50+ Juta Detik</h3>
                        <p class="text-lg font-ubuntu text-orange mt-2">Percakapan dalam 1 bulan</p>
                    </div>
                    <div class="bg-white rounded-lg py-6 px-8 shadow-lg mx-4">
                        <h3 class="text-3xl font-montserrat font-bold text-secblue">2000+ Workstation</h3>
                        <p class="text-lg font-ubuntu text-orange mt-2">Telah Dilayani</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="py-2 bg-orange">
        </div>
    </div>
    <div class="max-w-7xl mx-auto">
        <section id="clients" class="max-w-7xl bg-white py-16">
            <div class="max-w-5xl mx-auto text-center">
                <h3 class="text-lg font-ubuntu text-secblue font-semibold mb-2">Our Client</h3>
                <h2 class="text-4xl font-montserrat font-bold text-darkblue mb-12">Klien Kami</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center mb-8">
                    <img src="/icon/about/client-hsbc.svg" alt="HSBC" class="mx-auto h-12">
                    <img src="/icon/about/BNI.svg" alt="BNI" class="mx-auto h-12">
                    <img src="/icon/about/CAR.svg" alt="CAR" class="mx-auto h-12">
                    <img src="/icon/about/ciputra-life.webp" alt="Ciputra life" class="mx-auto h-36">
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center mb-8">
                    <img src="/icon/about/Wall-Street.svg" alt="Wall Street" class="mx-auto h-24">
                    <img src="/icon/about/bumiputera.svg" alt="Bumi Putera" class="mx-auto h-20">
                    <img src="/icon/about/ciputra-life.webp" alt="Ciputra life" class="mx-auto h-36">
                    <img src="/icon/about/indoexpathousing.svg" alt="Indoexpathousing" class="mx-auto h-20">
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center mb-8">
                    <img src="/icon/about/cigna.svg" alt="cigna" class="mx-auto h-24">
                    <img src="/icon/about/artha-madani.svg" alt="artha madani" class="mx-auto h-24">
                    <img src="/icon/about/dsf.svg" alt="dsf" class="mx-auto h-24">
                    <img src="/icon/about/sali.svg" alt="sali" class="mx-auto h-24">
                    <img src="/icon/about/mitratelecom.svg" alt="mitratelecom" class="mx-auto h-24">
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 items-center mb-8">
                    <img src="/icon/about/bureau.svg" alt="bureau" class="mx-auto h-24">
                    <img src="/icon/about/axa.svg" alt="axa" class="mx-auto h-20">
                    <img src="/icon/about/aseanindo.svg" alt="aseanindo" class="mx-auto h-12">
                </div>
                <div class="grid grid-cols-2 gap-8 items-center">
                    <img src="/icon/about/mnc-bank.svg" alt="mnc bank" class="mx-auto h-12">
                    <img src="/icon/about/green-academy.svg" alt="green academy" class="mx-auto h-24">
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto py-16 px-4 md:px-8 flex flex-col justify-center items-center mb-10 md:mb-18">
            <!-- Heading -->
            <div class="text-center mb-12 max-w-4xl">
                <h2 class="text-3xl font-bold text-gray-900 font-montserrat">Kenapa Pilih Kontakami?</h2>
                <p class="text-gray-600 mt-4 font-ubuntu">
                    Kontakami menawarkan solusi <span class="text-lightblue"><a href="https://kontakami.com/blog/contact-center-pengertian-manfaat-dan-rekomendasi-untuk-bisnis">contact center</a></span> yang fleksibel dan efisien dengan berbagai fitur unggulan.
                    Semua fitur ada dalam satu platform yang mudah digunakan tanpa perlu aplikasi tambahan.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8 max-w-5xl">
                <!-- Feature 1: Multiple Access -->
                <div class="flex items-start">
                    <img src="/icon/home/escalation-management.svg" alt="Multiple Access Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Multiple Access</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Banyak cara untuk terhubung ke contact center, termasuk melalui berbagai platform digital.
                        </p>
                    </div>
                </div>

                <!-- Feature 2: Dukungan B2B dan B2C -->
                <div class="flex items-start">
                    <img src="/icon/home/dukungan-b2b-b2c.svg" alt="Dukungan B2B dan B2C Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Dukungan B2B dan B2C</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Memudahkan komunikasi dengan corporate clients, pelanggan, serta kantor cabang tanpa nomor
                            telepon.
                        </p>
                    </div>
                </div>

                <!-- Feature 3: Form Dinamis -->
                <div class="flex items-start">
                    <img src="/icon/home/form-dynamic.svg" alt="Form Dinamis Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Form Dinamis</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Formulir dapat disesuaikan sendiri sesuai kebutuhan pengguna.
                        </p>
                    </div>
                </div>

                <!-- Feature 4: Opsi Live Agent -->
                <div class="flex items-start">
                    <img src="/icon/home/opsi-live-agent.svg" alt="Opsi Live Agent Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Opsi Live Agent</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Terhubung langsung dengan live agent jika diperlukan, tidak hanya melalui chat atau bot.
                        </p>
                    </div>
                </div>

                <!-- Feature 5: All-in-One Platform -->
                <div class="flex items-start">
                    <img src="/icon/home/all-in-one-platform.svg" alt="All-in-One Platform Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">All-in-One Platform</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Mengelola komunikasi inbound, outbound, dan omnichannel dalam satu sistem tanpa aplikasi
                            tambahan.
                        </p>
                    </div>
                </div>

                <!-- Feature 6: Call Back -->
                <div class="flex items-start">
                    <img src="/icon/home/call-back.svg" alt="Call Back Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Call Back</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Pelanggan dapat meninggalkan pesan dan akan dihubungi kembali oleh live agent.
                        </p>
                    </div>
                </div>

                <!-- Feature 7: Manajemen Eskalasi -->
                <div class="flex items-start">
                    <img src="/icon/home/escalation-management.svg" alt="Manajemen Eskalasi Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Manajemen Eskalasi</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Menjamin KPI terpenuhi melalui eskalasi yang terukur dengan SLA dan OLA.
                        </p>
                    </div>
                </div>

                <!-- Feature 8: Hemat Biaya Langganan -->
                <div class="flex items-start">
                    <img src="/icon/home/hemat-biaya.svg" alt="Hemat Biaya Icon" class="h-16 mr-4">
                    <div>
                        <h3 class="text-lg font-bold text-secblue mb-2 font-montserrat">Hemat Biaya Langganan</h3>
                        <p class="text-gray-600 font-ubuntu">
                            Biaya telco dan CRM yang lebih terjangkau dapat meningkatkan efisiensi operasional perusahaan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </section>
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
