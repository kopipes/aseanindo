@extends('en.layout-landing.master')

@section('description')
<meta name="description" content="{{ $meta['meta_description'] }}">
@endsection

@section('title')
<title>{{ $meta['meta_title'] }}</title>
@endsection()

@section('seo')
<meta property="og:title" content="{{ $meta['meta_title'] }}" />
<meta property="og:description" content="{{ $meta['meta_description'] }}" />
<meta property="og:type" content="article" />
<meta name="keywords" content="{{ $meta['keywords'] }}">
<link rel="canonical" href="{{ $meta['canonical'] }}" />
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <section>
        <div class="flex justify-between items-center p-4 max-w-4xl mx-auto mt-[10rem]">
            <!-- Kembali Button -->
            <a href="/blog" class="flex items-center text-blue-600 hover:underline">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </a>

            <!-- Bagikan Button -->
            <button id="shareButton" class="flex items-center px-4 py-2 bg-orange text-white rounded-full hover:bg-orange transition focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 12v4a1 1 0 001 1h3m10-5v5a1 1 0 01-1 1h-3m-7-4h4m-6-6h6m0-4v6"></path>
                </svg>
                Share
            </button>
        </div>
        <div class="markdown-content w-full bg-white flex flex-col max-w-4xl mx-auto px-4">
            {!! $content !!}
        </div>
    </section>
</div>

<!-- Modal -->
<div id="shareModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-80">
        <h3 class="text-lg font-semibold mb-4">Share This Article</h3>
        <div class="space-y-3">
            <!-- Copy to Clipboard (Facebook) -->
            <button onclick="copyToClipboard('{{ url()->current() }}')" class="flex items-center text-blue-600 hover:underline">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2.5A5.5 5.5 0 0 0 6.5 8v1H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H9V8a3 3 0 0 1 6 0v1h-1.5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-2.5V8a5.5 5.5 0 0 0-5.5-5.5z"></path>
                </svg>
                Copy Facebook Link
            </button>

            <!-- Copy to Clipboard (Twitter) -->
            <button onclick="copyToClipboard('{{ url()->current() }}')" class="flex items-center text-blue-400 hover:underline">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.29 20c7.55 0 11.67-6.3 11.67-11.77v-.54A8.4 8.4 0 0 0 22 5.92a8.2 8.2 0 0 1-2.35.66 4.1 4.1 0 0 0 1.8-2.27 8.34 8.34 0 0 1-2.6.99A4.16 4.16 0 0 0 12.18 9a11.81 11.81 0 0 1-8.6-4.35 4.15 4.15 0 0 0 1.3 5.53 4.07 4.07 0 0 1-1.88-.52v.05a4.15 4.15 0 0 0 3.33 4.08 4.07 4.07 0 0 1-1.87.07 4.16 4.16 0 0 0 3.88 2.9 8.38 8.38 0 0 1-5.17 1.78 7.88 7.88 0 0 1-.99-.06 11.78 11.78 0 0 0 6.39 1.88"></path>
                </svg>
                Copy Twitter Link
            </button>

            <!-- Copy to Clipboard (WhatsApp) -->
            <button onclick="copyToClipboard('{{ url()->current() }}')" class="flex items-center text-green-500 hover:underline">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.571 3h2.858C8.95 3 10 4.05 10 5.286v2.858C10 9.95 8.95 11 7.714 11H4.57C3.336 11 2.286 9.95 2.286 8.714V5.286C2.286 4.05 3.336 3 4.571 3zm7.429 0h2.857c1.236 0 2.286 1.05 2.286 2.286v2.858C17.143 9.95 16.093 11 14.857 11h-2.857C10.764 11 9.714 9.95 9.714 8.714V5.286C9.714 4.05 10.764 3 12 3zm-7.43 10h2.858C8.95 13 10 14.05 10 15.286v2.857C10 19.95 8.95 21 7.714 21H4.57C3.336 21 2.286 19.95 2.286 18.143v-2.857C2.286 14.05 3.336 13 4.571 13zm7.429 0h2.857c1.236 0 2.286 1.05 2.286 2.286v2.857C17.143 19.95 16.093 21 14.857 21h-2.857C10.764 21 9.714 19.95 9.714 18.143v-2.857C9.714 14.05 10.764 13 12 13z"></path>
                </svg>
                Copy WhatsApp Link
            </button>
        </div>
        <div class="mt-4 flex justify-end">
            <button id="closeModal" class="text-sm text-gray-600 hover:underline">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const shareButton = document.getElementById('shareButton');
    const shareModal = document.getElementById('shareModal');
    const closeModal = document.getElementById('closeModal');

    shareButton.addEventListener('click', () => {
        shareModal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
        shareModal.classList.add('hidden');
    });

    function copyToClipboard(url) {
        // Salin link ke clipboard
        navigator.clipboard.writeText(url).then(function() {
            alert('Link telah disalin ke clipboard');
        }, function(err) {
            console.error('Gagal menyalin link: ', err);
        });
    }
</script>
@endsection
