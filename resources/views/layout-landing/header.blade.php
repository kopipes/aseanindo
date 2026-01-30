<nav class="bg-white shadow-md fixed top-0 w-full z-50 font-ubuntu">
    <div class="container mx-auto px-4 lg:px-8 flex justify-between items-center py-4">
        <div class="flex items-center space-x-2">
            <a href="/"><img src="{{ asset('img/rebranding/logokontakami.svg') }}" alt="logo" class="w-full h-7"></a>
        </div>

        <div class="lg:hidden">
            <button id="mobile-menu-btn" class="text-gray-900 focus:outline-none" aria-label="mobile menu">
                <i class="fa fa-bars btn-bar"></i>
            </button>
        </div>

        <!-- Desktop Menu -->
        <ul class="hidden lg:flex space-x-8 items-center">
            <li>
                <a href="{{ localized_url() }}"
                    class="{{ Request::is('/') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }}">
                    Beranda
                </a>
            </li>

            <li class="relative group">
                <a href="/product"
                    class="{{ Request::is('product*') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }} relative"
                    id="dropdown-link-produk">
                    Produk <i class="fas fa-chevron-down"></i>
                </a>

                <!-- After effect for the dropdown -->
                <style>
                    #dropdown-link-produk::after {
                        content: '';
                        position: absolute;
                        bottom: -2px;
                        left: 0;
                        right: 0;
                        height: 2px;
                        background-color: transparent;
                        transition: background-color 0.3s ease;
                    }
                </style>

                <!-- Dropdown Menu for Products -->
                <div id="dropdown-menu-produk"
                    class="absolute -left-5 mt-2 bg-white shadow-lg rounded-lg p-8 opacity-0 invisible transition-opacity duration-200 z-10 min-w-[300px] w-auto group-hover:opacity-100 group-hover:visible">
                    <ul class="space-y-4">
                        <li><a href="/product/contact-center-software"
                                class="{{ Request::is('product/contact-center-software') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Contact
                                Center Software</a></li>
                        <li><a href="/product/omnichannel-contact-center"
                                class="{{ Request::is('product/omnichannel-contact-center') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Omnichannel
                                Contact Center</a></li>
                        <li><a href="/product/self-service-contact-center"
                                class="{{ Request::is('product/self-service-contact-center') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Self
                                Service Contact Center</a></li>
                        <li><a href="/product/ticketing-management-system"
                                class="{{ Request::is('product/ticketing-management-system') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Ticketing
                                Management System</a></li>
                        <li><a href="/product/features-and-benefits"
                                class="{{ Request::is('product/features-and-benefits') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Feature
                                and Benefits</a></li>
                        <li><a href="/product/automation-customer-service"
                                class="{{ Request::is('product/automation-customer-service') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Customer Service Automation</a></li>
                        <li><a href="/product/workforce-performance"
                                class="{{ Request::is('product/workforce-performance') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">Workforce
                                Performance</a></li>
                        <li><a href="/product/sdm-outsourcing"
                                class="{{ Request::is('product/sdm-outsourcing') ? 'font-semibold' : 'text-darkblue font-medium hover:font-semibold' }} relative">SDM
                                Outsourcing</a></li>
                    </ul>
                </div>
            </li>

            <li>
                <a href="/about"
                    class="{{ Request::is('about') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }}">
                    Perusahaan
                </a>
            </li>

            <li>
                <a href="/blog"
                    class="{{ Request::is('blog') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }}">
                    Artikel
                </a>
            </li>
            <li>
                <a href="/contact"
                    class="{{ Request::is('contact') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }}">
                    Hubungi
                </a>
            </li>
            <li>
                <a href="/faq"
                    class="{{ Request::is('faq') ? 'text-darkblue font-semibold underline' : 'text-gray-600 hover:text-darkblue hover:underline font-medium' }}">
                    Bantuan
                </a>
            </li>
        </ul>

        <div class="hidden lg:flex items-center space-x-4">
            <div class="hidden lg:flex space-x-2 items-center">
                <a href="{{ route('callnchat.lang', ['locale' => 'id', 'slug' => $otherSlug ?? null]) }}" class="text-gray-600 hover:text-gray-900" aria-label="language id">ID</a>
                <span class="text-gray-600">|</span>
                <a href="{{ route('callnchat.lang', ['locale' => 'en', 'slug' => $otherSlug ?? null]) }}" class="text-gray-600 hover:text-gray-900" aria-label="language en">EN</a>
            </div>
        </div>
        
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden menu-hidden bg-white p-4 shadow-md">
        <ul class="space-y-4">
            <li><a href="/" class="{{ Request::is('/') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Beranda</a></li>
            <li class="relative">
                <button id="mobile-product-btn"
                    class="w-full text-left text-gray-600 hover:text-gray-900 font-medium flex items-center justify-between">
                    <a href="/product" class="{{ Request::is('product*') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Produk</a> <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="mobile-product-menu" class="hidden space-y-2 mt-2">
                    <li><a href="/product/contact-center-software" class="{{ Request::is('product/contact-center-software') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Contact
                            Center Software</a></li>
                    <li><a href="/product/omnichannel-contact-center"
                        class="{{ Request::is('product/omnichannel-contact-center') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Omnichannel Contact Center</a></li>
                    <li><a href="/product/self-service-contact-center" class="{{ Request::is('product/self-service-contact-center') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Self
                            Service Contact Center</a></li>
                    <li><a href="/product/ticketing-management-system"
                        class="{{ Request::is('product/ticketing-management-system') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Ticketing Management System</a></li>
                    <li><a href="/product/features-and-benefits" class="{{ Request::is('product/features-and-benefits') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Feature and
                            Benefits</a></li>
                    <li><a href="/product/automation-customer-service" class="{{ Request::is('product/automation-customer-service') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Customer Service Automation</a></li>
                    <li><a href="/product/workforce-performance" class="{{ Request::is('product/workforce-performance') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Workforce
                            Performance</a></li>
                    <li><a href="/product/sdm-outsourcing" class="{{ Request::is('product/sdm-outsourcing') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">SDM
                            Outsourcing</a></li>
                </ul>
            </li>
            <li><a href="/about" class="{{ Request::is('about') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Perusahaan</a></li>
            <li><a href="/blog" class="{{ Request::is('blog') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Artikel</a></li>
            <li><a href="/contact" class="{{ Request::is('contact') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Hubungi kami</a></li>
            <li><a href="/faq" class="{{ Request::is('faq') ? 'text-darkblue font-semibold' : 'text-gray-600 hover:text-darkblue hover:font-semibold font-medium' }}">Bantuan</a></li>
            <li class="flex justify-center space-x-4">
                <a href="{{ route('callnchat.lang', ['locale' => 'id', 'slug' => $otherSlug ?? null]) }}" class="text-gray-600 hover:text-gray-900" aria-label="language id">ID</a>
                <span class="text-gray-600">|</span>
                <a href="{{ route('callnchat.lang', ['locale' => 'en', 'slug' => $otherSlug ?? null]) }}" class="text-gray-600 hover:text-gray-900" aria-label="language en">EN</a>
            </li>

        </ul>
    </div>
</nav>
