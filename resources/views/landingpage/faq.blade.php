@extends('layout-landing.master')

@section('title')
    <title>FAQ (Frequently Asked Questions) - Kontakami</title>
@endsection()
@section('seo')
    <meta name="description" content="FAQ">
    <link rel="canonical" href="https://kontakami.com/faq" />
@endsection()

@section('content')
    <div class="relative bg-cover bg-center h-96" style="background-image: url('/img/rebranding/faq-banner.webp');">
        <div class="absolute inset-0 bg-darkblue opacity-80"></div>
        <div class="relative max-w-7xl mx-auto h-full flex items-center justify-center text-center px-4 md:px-8">
            <div class="text-white animate-fade-in-left-bounce">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 font-montserrat">FAQ</h1>
                <ul class="decoration-none flex flex-row gap-2 font-ubuntu">
                    <li><a href="/" class="hover:text-slate-200">Home</a></li>
                    <li>
                        <p>></p>
                    </li>
                    <li><a href="/faq" class="hover:text-slate-200">Support</a></li>
                </ul>
            </div>
        </div>
        <div class="py-2 bg-orange">
        </div>
    </div>

    <div class="py-16">
        <div class="text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-darkblue font-montserrat">Temukan Seluruh Informasi yang Anda Butuhkan Seputar
                Kontakami</h2>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-2 px-4">
        <div class="flex justify-between items-center mb-8">
            <p class="bg-orange text-white py-2 px-8 rounded-xl">FAQ</p>
            <div class="relative">
                <input id="faq-search" type="text" placeholder="Search..."
                    class="border rounded-lg py-2 px-4 w-64 focus:outline-none focus:border-indigo-500 font-ubuntu">
            </div>
        </div>

        <div class="py-6">
            <h3 class="text-xl font-semibold text-secblue font-montserrat">Hal Yang Paling Sering Ditanyakan</h3>
        </div>
        <p id="no-result" class="text-center text-red-500 text-sm hidden font-ubuntu">Kata tersebut tidak ditemukan di FAQ</p>
        <div id="faq-list" class="font-ubuntu">
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Apa itu Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Aplikasi Help Desk Management untuk Inbound dan Outbound yang
                    dapat membantu perusahaan terhubung secara B2B dan B2C dalam satu platform, seperti layanan Customer
                    Service, Telemarketing, dan Media Sosial.</div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Service apa yang disediakan Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <ul>
                        <li>1. Inbound Voice: Panggilan masuk dari Pelanggan ke <span class="text-lightblue"><a href="https://kontakami.com/blog/contact-center-pengertian-manfaat-dan-rekomendasi-untuk-bisnis">Contact Center</a></span>, seperti: PSTN dan Web Call.
                        </li>
                        <li>2. Inbound Chat: Pesan masuk dari Pelanggan ke Contact Center yang dilayani oleh agent, seperti:
                            Web Live Chat dan WA Live Chat.</li>
                        <li>3. Inbound Chatbot: Pesan masuk dari Pelanggan ke Contact Center yang dilayani oleh bot,
                            seperti: Web bot dan WA Chatbot.</li>
                        <li>4. Outbound Voice: Panggilan keluar dari Contact Center ke Pelanggan, seperti:
                            Telemarketing/Marketing Campaign dan Call Back.</li>
                        <li>5. Outbound Chat: Pesan keluar dari Contact Center ke Pelanggan, seperti: WhatsApp Broadcast.
                        </li>
                        <li>6. Omnichannel: Facebook, Instagram, Email, WhatsApp Business API</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Apa saja fitur Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <ul>
                        <li>1. User Management</li>
                        <li>2. Product Management</li>
                        <li>3. Ticket Management</li>
                        <li>4. Data Customer, Voice, Chat</li>
                        <li>5. SLA Setting</li>
                        <li>6. Subject Catalog</li>
                        <li>7. Escalation Management</li>
                        <li>8. Notification By Email</li>
                        <li>9. Internal Team Communication</li>
                        <li>10. Report</li>
                        <li>11. Live Dashboard Report</li>
                        <li>12. <span class="text-lightblue"><a href="https://kontakami.com/blog/omnichannel-contact-center-pengertian-manfaat-dan-cara-memilih">Omnichannel</a></span></li>
                        <li>13. Integration</li>
                        <li>14. Content Management</li>
                        <li>15. FAQ</li>
                        <li>16. BOT</li>
                        <li>17. CSAT</li>
                        <li>18. Campaign Management</li>
                        <li>19. Marketing Campaign Rollout</li>
                        <li>20. Quality Assurance Tool & Monitoring</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Apakah perlu mengunduh aplikasi?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Tidak perlu, karena Kontakami adalah aplikasi berbasis web.
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Siapa saja yang dapat menggunakan Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Aplikasi Kontakami dapat digunakan oleh semua jenis industri
                    bisnis yang ingin meningkatkan kualitas layanan pelanggan.</div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Bagaimana cara berlangganan aplikasi Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Silakan menghubungi kami di <a href="https://kontakami.com/"><span class="text-secblue">kontakami.com</span></a></div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Untuk Perusahaan yang berlangganan Kontakami, Bagaimana Pelanggan B2C mengakses Contact Centernya?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <ul>
                        <li>1. Panggilan Telepon PSTN (021xxx, 1500xxx, 0809xxx, 1400xx dan lainnya)</li>
                        <li>2. Pesan dari media sosial (Facebook dan Instagram)</li>
                        <li>3. Akses tanpa nomor telepon (Contact Widget)</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Apa yang dimaksud dengan Akses Tanpa Nomor Telepon?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <p>Pelanggan B2C dapat mengakses tanpa nomor telepon melalui Contact Widgetdimana disitu dapat
                        melakukan:</p>
                    <ul>
                        <li>1. Call via Web Call (internet)</li>
                        <li>2. Chat via live Web Chat</li>
                        <li>3. Chat via live WhatsApp (Fitur tambahan)</li>
                        <li>4. Chat via Web Bot dan WhatsApp Bot (Fitur tambahan)</li>
                        <li>5. Email</li>
                        <li>6. Melihat display e-catalog</li>
                        <li>7. Layanan mandiri FAQ (Fitur tambahan)</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Bagaimana Pelanggan B2C mengakses Contact Widget (Akses Tanpa Nomor Telepon)?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <p>Pelanggan B2C dapat mengakses Contact Widget dengan cara:</p>
                    <ul>
                        <li>1. Mengklik tombol widget di website perusahaan</li>
                        <li>2. Scan QR Code , misalnya dari brosur atau flyer</li>
                        <li>3. Klik Link pada profile media sosial atau menu di aplikasi perusahaan </li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Bagaimana Pelanggan B2B mengakses Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Pelanggan B2B dapat mengakses Kontakami dengan login
                    di business.kontakami.com</div>
            </div>
        </div>
    </div>
@endsection()

@section('scripts')
    <script>
        function toggleAccordion(element) {
            const faqList = document.querySelectorAll('#faq-list > div');
            const content = element.nextElementSibling;
            const icon = element.querySelector('span');

            faqList.forEach(function(item) {
                const currentContent = item.querySelector('div');
                const currentIcon = item.querySelector('span');
                if (currentContent !== content) {
                    currentContent.classList.add('hidden');
                    currentIcon.innerHTML = '&#x25BC;';
                }
            });

            content.classList.toggle('hidden');
            icon.innerHTML = content.classList.contains('hidden') ? '&#x25BC;' : '&#x25B2;';
        }

        document.getElementById('faq-search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const faqItems = document.querySelectorAll('#faq-list > div');
            let found = false;

            faqItems.forEach(function(item) {
                const question = item.querySelector('button').textContent.toLowerCase();
                const content = item.querySelector('div').textContent.toLowerCase();

                if (question.includes(query) || content.includes(query)) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('no-result').classList.toggle('hidden', found);
        });
    </script>
@endsection()
