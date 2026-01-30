<!doctype html>
<html class="scroll-smooth" lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('title')
    @yield('description')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets_callnchat/font-awesome/css/all.css') }}">

    {{-- tinyslider --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css">
    @vite('resources/css/app.css')

    {{-- SEO --}}
    @yield('seo')
    <meta name="keywords"
        content="contact center, omnichannel platform, aplikasi customer service, contact us via live chat, outsourcing contact center, live chat customer service, customer service chatbot, software customer service, omnichannel contact center, omnichannel customer service, call center digital, contact center digital, solusi call center, chatbot for customer service, live chat support" />
    <meta name="google-site-verification" content="ADIWRBNJSv7tHfKuoorZlRvipmMJVf837MBjGG5g1yA" />

    <meta property="og:url" content="https://kontakami.com" />
    <meta property="og:site_name" content="kontakami" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image"
        content="https://opengraph.b-cdn.net/production/images/90f53a09-7b77-4948-8df9-acd5ffecbd1c.png?token=4quvazM_DPf16kjM9rhPSsnGHlayGC7pVvNgT-ISOi8&height=458&width=935&expires=33263250112" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@username" />
    <meta name="twitter:creator" content="@username" />
    <meta name="twitter:title" content="Kontakami - Solusi Terbaik untuk Menghubungkan Pelanggan dengan Bisnis Anda" />
    <meta name="twitter:description"
        content="Kontakami menyediakan solusi praktis untuk mempermudah komunikasi antara bisnis dan pelanggan melalui berbagai kanal. Tingkatkan interaksi dan kepuasan pelanggan dengan layanan kami yang andal dan efisien." />
    <meta name="twitter:image:type" content="image/png" />
    <meta name="twitter:image"
        content="https://opengraph.b-cdn.net/production/images/90f53a09-7b77-4948-8df9-acd5ffecbd1c.png?token=4quvazM_DPf16kjM9rhPSsnGHlayGC7pVvNgT-ISOi8&height=458&width=935&expires=33263250112" />
    <meta name="twitter:image:width" content="1200" />
    <meta name="twitter:image:height" content="630" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    {{-- END --}}
    <script>
        console.error = function() {}; // Suppresses all errors in the console

        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MT4NQB2B');
    </script>

    <style>
        /* Custom styles for mobile dropdown functionality */
        .menu-hidden {
            display: none;
        }

        .menu-visible {
            display: block;
        }
    </style>
</head>

<body>
    <div id="loading" class="flex items-center justify-center h-screen bg-white">
        <div class="text-center">
            <div class="loader animate-spin rounded-full h-24 w-24 border-t-4 border-blue-500 mx-auto mb-4"></div>
            <p class="text-lg font-ubuntu font-semibold text-gray-700">Loading... Please wait</p>
        </div>
    </div>
    <div class="hidden" id="content">
        <div>
            @include('en.layout-landing.header')
        </div>
        <div id="main" class="py-0 my-0 font-ubuntu scroll-smooth">
            @yield('content')
        </div>
        <div>
            @include('en.layout-landing.footer')
        </div>
    </div>
</body>

<script>
    const dropdownLinkProduk = document.getElementById('dropdown-link-produk');
    const dropdownMenuProduk = document.getElementById('dropdown-menu-produk');

    dropdownLinkProduk.addEventListener('mouseenter', () => {
        dropdownMenuProduk.classList.remove('opacity-0', 'invisible');
        dropdownMenuProduk.classList.add('opacity-100', 'visible');
    });

    dropdownMenuProduk.addEventListener('mouseleave', () => {
        dropdownMenuProduk.classList.remove('opacity-100', 'visible');
        dropdownMenuProduk.classList.add('opacity-0', 'invisible');
    });

    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('menu-hidden');
        mobileMenu.classList.toggle('menu-visible');
    });

    const mobileProductBtn = document.getElementById('mobile-product-btn');
    const mobileProductMenu = document.getElementById('mobile-product-menu');

    mobileProductBtn.addEventListener('click', () => {
        mobileProductMenu.classList.toggle('hidden');
    });
</script>
@yield('scripts')
<script>
    setTimeout(function() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    }, 200);
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BRKH8TZ6Z1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-BRKH8TZ6Z1');
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
        // Function to enable the submit button once CAPTCHA is verified
        function enableSubmit() {
            document.getElementById('submitButton').disabled = false;
        }
</script>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT4NQB2B" height="0" width="0"
        style="display:none;visibility:hidden"></iframe></noscript>
<script src="https://kontakami.com/9uf4keeB7kefcn5Joozp/9a8e167e-e49b-403e-8a00-421cd13ab24b" async type="text/javascript"></script>
</html>
