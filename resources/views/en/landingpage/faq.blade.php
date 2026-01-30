@extends('en.layout-landing.master')

@section('title')
    <title>Kontakami - FAQ</title>
@endsection()
@section('seo')
    <meta name="description" content="FAQ">
    <link rel="canonical" href="https://kontakami.com/en/faq" />
@endsection()

@section('content')
    <div class="relative bg-cover bg-center h-96" style="background-image: url('/img/rebranding/faq-banner.webp');">
        <div class="absolute inset-0 bg-darkblue opacity-80"></div>
        <div class="relative max-w-7xl mx-auto h-full flex items-center justify-center text-center px-4 md:px-8">
            <div class="text-white animate-fade-in-left-bounce">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 font-montserrat">FAQ</h1>
                <ul class="decoration-none flex flex-row gap-2 font-ubuntu">
                    <li><a href="/en" class="hover:text-slate-200">Home</a></li>
                    <li>
                        <p>></p>
                    </li>
                    <li><a href="/en/faq" class="hover:text-slate-200">Support</a></li>
                </ul>
            </div>
        </div>
        <div class="py-2 bg-orange">
        </div>
    </div>

    <div class="py-16">
        <div class="text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-darkblue font-montserrat">Find All the Information You Need About Kontakami</h2>
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
            <h3 class="text-xl font-semibold text-secblue font-montserrat">Frequently Asked Questions</h3>
        </div>
        <p id="no-result" class="text-center text-red-500 text-sm hidden font-ubuntu">Kata tersebut tidak ditemukan di FAQ</p>
        <div id="faq-list" class="font-ubuntu">
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    What is Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Help Desk Management application for Inbound and Outbound that can help companies connect B2B and B2C in one platform, such as Customer Service, Telemarketing, and Social Media.</div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    What services does Kontakami provide?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <ul>
                        <li>1. Inbound Voice: Inbound calls from customers to the Contact Center, such as: PSTN and Web Call.
                        </li>
                        <li>2. Inbound Chat: Inbound messages from customers to the Contact Center that are served by agents, such as: Web Live Chat and WA Live Chat.</li>
                        <li>3. Inbound Chatbot: Inbound messages from customers to the Contact Center that are served by bots, such as: Web bot and WA Chatbot.</li>
                        <li>4. Outbound Voice: Outbound calls from Contact Center to Customers, such as: Telemarketing/Marketing Campaign and Call Back.</li>
                        <li>5. Outbound Chat: Outbound messages from Contact Center to Customers, such as: WhatsApp Broadcast.
                        </li>
                        <li>6. Omnichannel: Facebook, Instagram, Email, WhatsApp Business API</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    What are the features of Kontakami?
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
                        <li>12. Omnichannel</li>
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
                    Is it necessary to download an app?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">No need, as Kontakami is a web-based application.
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    Who can use Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">The Kontakami app can be used by any type of business industry that wants to improve the quality of customer service.</div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    How to subscribe to the Kontakami app?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">Please feel free to contact us at kontakami.com/kontakami</div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    For Companies that subscribe to Kontakami, How do B2C Customers access their Contact Center?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <ul>
                        <li>1. PSTN phone calls (021xxx, 1500xxx, 0809xxx, 1400xx and more)</li>
                        <li>2. Social media messages (Facebook and Instagram)</li>
                        <li>3. Access without phone number (Contact Widget)</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    What is Access Without Phone Number?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <p>B2C customers can access without a phone number through the Contact Widget where they can do:</p>
                    <ul>
                        <li>1. Call via Web Call (internet)</li>
                        <li>2. Chat via live Web Chat</li>
                        <li>3. Chat via live WhatsApp (Fitur tambahan)</li>
                        <li>4. Chat via Web Bot dan WhatsApp Bot (Fitur tambahan)</li>
                        <li>5. Email</li>
                        <li>6. View e-catalog display</li>
                        <li>7. FAQ self-service (Additional features)</li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    How do B2C Customers access the Contact Widget (Access Without Phone Number)?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">
                    <p>B2C customers can access the Contact Widget by:</p>
                    <ul>
                        <li>1. Clicking the widget button on the company website</li>
                        <li>2. Scan QR Code, for example from a brochure or fyer</li>
                        <li>3. Click the link on the social media profile or menu in the company application  </li>
                    </ul>
                </div>
            </div>
            <div class="border-b py-4">
                <button
                    class="flex justify-between items-center w-full text-left text-darkblue font-medium focus:outline-none"
                    onclick="toggleAccordion(this)">
                    How do B2B Customers access Kontakami?
                    <span class="text-orange">&#x25BC;</span>
                </button>
                <div class="mt-2 text-sm text-gray-600 hidden">B2B customers can access Kontakami by logging in at business.kontakami.com</div>
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
