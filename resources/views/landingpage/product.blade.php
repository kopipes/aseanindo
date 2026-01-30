@extends('layout-landing.master')

@section('title')
    <title>Produk Layanan Lengkap Untuk Bisnis Anda - Kontakami</title>
@endsection()

@section('seo')
    <meta name="description" content="Berbagai produk dan layanan untuk meningkatkan layanan pelanggan bisnis Anda. Konsultasikan sesuai kebutuhan Anda.">
    <link rel="canonical" href="https://kontakami.com/product" />
@endsection()

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row gap-5 items-center justify-between mt-20">
        <div class="w-full md:w-1/2 space-y-4">
            <p class="text-secblue font-semibold font-ubuntu">Produk Kami</p>
            <h1 class="text-4xl font-bold text-darkblue font-montserrat">Produk-Produk Yang Kami Tawarkan</h1>
            <p class="text-black font-ubuntu">
                Kami menyediakan rangkaian produk unggulan yang dirancang untuk meningkatkan kualitas layanan pelanggan
                Anda.
            </p>
            <a href="#produk"
                class="inline-block px-6 py-3 bg-orange text-white rounded-full text-sm font-semibold hover:bg-white hover:border hover:border-orange hover:text-orange transition font-ubuntu">
                Selengkapnya
            </a>
        </div>
        <div class="w-full md:w-1/2 flex justify-center md:justify-end">
            <div class="relative">
                <img src="/img/rebranding/product/product-top.webp" alt="Person Image Man" class="rounded-lg w-full">
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto py-16 px-4 flex flex-col justify-center items-center align-middle" id="produk">
        <div class="text-center mb-12">
            <p class="text-lightblue font-semibold font-ubuntu">Produk Kami</p>
            <h2 class="text-4xl font-bold text-darkblue font-montserrat">Produk-Produk Yang Kami Tawarkan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl">
            <div class="bg-white p-4">
                <a href="/product/contact-center-software"><img src="/img/rebranding/product/contact.webp" alt="Contact Center Software"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/contact-center-software">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Contact
                        Center Software</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Solusi <span class="text-lightblue"><a href="https://kontakami.com/blog/contact-center-pengertian-manfaat-dan-rekomendasi-untuk-bisnis">contact center</a></span> terpadu untuk mengelola komunikasi pelanggan melalui berbagai saluran seperti telepon, email, dan
                    chat, membantu meningkatkan efisiensi operasional.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/omnichannel-contact-center"><img src="/img/rebranding/product/omnichannel.webp" alt="Omnichannel Contact Center"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/omnichannel-contact-center">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Omnichannel Contact Center</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Platform <span class="text-lightblue"><a href="https://kontakami.com/blog/omnichannel-contact-center-pengertian-manfaat-dan-cara-memilih">Omnichannel contact center</a></span> yang menghubungkan bisnis dengan pelanggan melalui WhatsApp, email, media sosial, dan telepon,
                    dengan pengalaman yang konsisten.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/self-service-contact-center"><img src="/img/rebranding/product/self-service.webp" alt="Self Service Contact Center"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/self-service-contact-center">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Self
                        Service Contact Center</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Platform yang mengelola dan merespons permintaan bantuan dari pelanggan secara efektif, memastikan
                    penyelesaian masalah dengan cepat dan tepat.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/ticketing-management-system"><img src="/img/rebranding/product/ticketing.webp"
                        alt="Ticketing Management System" class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/ticketing-management-system">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Ticketing Management System</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Sistem yang mencatat, melacak, dan mengelola tiket atau permintaan layanan pelanggan, memastikan semua
                    masalah ditangani dengan efisien.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/features-and-benefits"><img src="/img/rebranding/product/features.webp" alt="Features and Benefits"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/features-and-benefits">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Features
                        and Benefits</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Berbagai fitur seperti pelacakan real-time, otomatisasi tugas, analitik kinerja, yang memberikan manfaat
                    berupa peningkatan produktivitas dan kepuasan pelanggan.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/automation-customer-service"><img src="/img/rebranding/product/automation.webp"
                        alt="Automation Customer Services" class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/automation-customer-service">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Automation Customer Services</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Otomatisasi layanan pelanggan melalui chatbot dan sistem otomatis yang membantu merespons permintaan
                    pelanggan dengan cepat dan efisien.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/workforce-performance"><img src="/img/rebranding/product/workforce.webp" alt="Workforce Performance"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/workforce-performance">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Workforce Performance</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Dashboard untuk memantau dan meningkatkan kinerja tim, memungkinkan manajemen yang lebih baik melalui
                    metrik kinerja dan pelaporan detail.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/product/sdm-outsourcing"><img src="/img/rebranding/product/sdm.webp" alt="SDM Outsourcing"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/product/sdm-outsourcing">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">SDM Outsourcing</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Perencanaan proses tenaga kerja untuk memenuhi kapasitas turnover, termasuk seleksi, pelatihan, dan
                    penempatan.
                </p>
            </div>
        </div>
    </div>
    <div class="max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row gap-10 items-center justify-between">
        <div class="md:w-1/2 space-y-4">
            <p class="text-secblue font-semibold font-ubuntu">Benefit Penggunaan</p>
            <h2 class="text-4xl font-bold text-darkblue font-montserrat">Benefit Dalam Menggunakan Kontakami</h2>
            <ul class="font-ubuntu">
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Panggilan Gratis:</span> Hubungi contact center tanpa biaya (dengan koneksi
                        internet).</p>
                </li>
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Chat Fleksibel:</span> Komunikasi langsung melalui chat, jika Anda tidak
                        ingin menelepon.</p>
                </li>
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Panggilan Balik:</span> Jika customer service sedang sibuk, kami akan
                        menghubungi Anda kembali.</p>
                </li>
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Berbagi Lokasi:</span> Mudah berbagi lokasi Anda melalui chat.</p>
                </li>
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Akses Produk:</span> Jelajahi produk dan layanan favorit Anda di KontaKami.
                    </p>
                </li>
                <li class="flex flex-row gap-2 py-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                        fill="none">
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
                    <p><span class="font-bold">Koneksi Cepat:</span> Terhubung langsung dengan customer service tanpa
                        penundaan.</p>
                </li>
            </ul>
        </div>
        <div class="md:w-1/2 flex justify-center md:justify-end">
            <div class="relative">
                <img src="/img/rebranding/product/product-bottom.webp" alt="Person Image Woman" class="rounded-lg w-full">
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
