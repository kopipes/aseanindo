@extends('en.layout-landing.master')

@section('title')
    <title>Contact Us - Kontakami</title>
@endsection()
@section('seo')
    <meta name="description" content="Contact us now and get a free consultation on contact center products, call center solutions, and platforms to enhance customer service.">
    <link rel="canonical" href="https://kontakami.com/en/contact" />
@endsection()

@section('content')
<div class="relative bg-cover bg-center h-96" style="background-image: url('/img/rebranding/contact-banner.webp');">
    <div class="relative max-w-7xl mx-auto h-full flex items-center justify-center text-center px-4 md:px-8">
        <div class="text-white animate-fade-in-left-bounce">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 font-montserrat">Contact Us</h1>
            <ul class="decoration-none flex flex-row items-center justify-center gap-2 font-ubuntu">
                <li><a href="/en" class="hover:text-slate-200">Home</a></li>
                <li>
                    <p>></p>
                </li>
                <li><a href="/en/contact" class="hover:text-slate-200">Contact Us</a></li>
            </ul>
        </div>
    </div>
    <div class="py-2 bg-orange">
    </div>
</div>

<div class="max-w-7xl mx-auto py-16 px-4 md:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div class="bg-gray-100 rounded-2xl shadow-lg p-8 mt-8">
            <div class="flex justify-center mb-6">
                <div class="p-4 rounded-full flex flex-col items-center justify-center">
                    <img src="/icon/contact/phone.svg" alt="Phone Icon" class="w-20 h-20 absolute -mt-20">
                </div>
            </div>
            <h3 class="text-lg font-bold text-darkblue mb-2 font-montserrat">Phone</h3>
            <p class="text-black font-ubuntu hover:text-blue-700"><a href="https://wa.me/6281399447797">+62 813-9944-7797</a></p>
        </div>
        <div class="bg-gray-100 rounded-2xl shadow-lg p-8 mt-8">
            <div class="flex justify-center mb-6">
                <div class="p-4 rounded-full flex flex-col items-center justify-center">
                    <img src="/icon/contact/location.svg" alt="Location Icon" class="w-20 h-20 absolute -mt-20">
                </div>
            </div>
            <h3 class="text-lg font-bold text-darkblue mb-2 font-montserrat">Location</h3>
            <p class="text-black font-ubuntu hover:text-blue-700"><a href="https://maps.app.goo.gl/TzPCuoQWUbKWgcsv5">Setiabudi Building II 6th Floor Suite 602 Jl. H. R. Rasuna Said Kav. 62 Jakarta</a></p>
        </div>
        <div class="bg-gray-100 rounded-2xl shadow-lg p-8 mt-8">
            <div class="flex justify-center mb-6">
                <div class="p-4 rounded-full flex flex-col items-center justify-center">
                    <img src="/icon/contact/email.svg" alt="Email Icon" class="w-20 h-20 absolute -mt-20">
                </div>
            </div>
            <h3 class="text-lg font-bold text-darkblue mb-2 font-montserrat">Email</h3>
            <p class="text-black font-ubuntu hover:text-blue-700"><a href="mailto:sales@kontakami.com">sales@kontakami.com</a></p>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto text-center py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-4 font-montserrat">Contact Us Here</h2>
    <p class="text-gray-600 font-ubuntu">We are here to help you! For questions, support, or further information, please contact us through the available communication channels.</p>
</div>

<div class="max-w-4xl mx-auto py-12 font-ubuntu px-4">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Message Sent!</strong>
            <span class="block sm:inline">Thank you for your message. Our team will reach you as soon as possible</span>
        </div>
    @endif

    <form action="{{ route('contact.send') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <!-- Nama -->
        <input type="text" name="name" placeholder="Your Name" class="p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange" required />

        <!-- Email -->
        <input type="email" name="email" placeholder="Your Email" class="p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange" required />

        <!-- Kode Negara -->
        <input type="text" name="country_code" placeholder="Country Code" class="p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange" />

        <!-- Nomor Telepon -->
        <input type="text" name="number" placeholder="Your Number" class="p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange" />

        <!-- Perusahaan -->
        <input type="text" name="company" placeholder="Your Company" class="md:col-span-2 p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange" />

        <textarea name="message" placeholder="Write a message" rows="5" maxlength="200"
                class="md:col-span-2 p-4 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange"
                required
                oninput="checkLength(this)">
        </textarea>

        <div id="error-message" class="text-red-500 mt-2 hidden">
            Teks terlalu panjang. Maksimal 200 karakter.
        </div>

        <!-- Tombol Kirim -->
        <div class="md:col-span-2 flex flex-col md:flex-row gap-4 justify-between">
            <!-- reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LeCaGgqAAAAACyWFWAK_sD69ylnNsShtoRYsIoZ" data-callback="enableSubmit"></div>

            <!-- Submit Button initially disabled -->
            <button id="submitButton" type="submit" class="bg-orange text-white font-semibold py-3 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange hover:bg-white hover:border hover:border-orange hover:text-orange transition" disabled>
                Send Message
            </button>
        </div>
    </form>
</div>
@endsection()

@section('scripts')
<script>
    function checkLength(textarea) {
        const maxLength = 200;
        const errorMessage = document.getElementById('error-message');

        if (textarea.value.length > maxLength) {
            errorMessage.classList.remove('hidden');
        } else {
            errorMessage.classList.add('hidden');
        }
    }
</script>
@endsection()


