@extends('en.layout-landing.master')

@section('title')
    <title>Information on Customer Service and Business - Kontakami </title>
@endsection()
@section('seo')
    <meta name="description" content="A blog featuring discussions on customer service and business tips from Kontakami.">
    <link rel="canonical" href="https://kontakami.com/en/blog" />
@endsection()

@section('content')
    <div class="flex flex-col md:flex-row gap-4 max-w-6xl mx-auto mt-[10rem] md:mt-32">
        <div class="px-4">
            <div class="max-w-xs mx-auto animate-fade-in-left-bounce">
                <div class="mb-8">
                    <input type="text" id="search-input" placeholder="Search..."
                        class="w-full p-3 border rounded-md focus:outline-none focus:ring focus:border-blue-300 text-gray-700 placeholder-gray-400" />
                </div>

                <div>
                    <h3 class="text-lg font-bold text-blue-800 mb-4 font-montserrat">Categories</h3>
                    <ul class="space-y-2">
                        <li><button onclick="filterByCategory('all')"
                                class="text-blue-700 font-medium hover:underline font-ubuntu">All</button></li>
                        <li><button onclick="filterByCategory('Informasi')"
                                class="text-blue-700 font-medium hover:underline font-ubuntu">Information</button></li>
                        <li><button onclick="filterByCategory('Berita')"
                                class="text-blue-700 font-medium hover:underline font-ubuntu">News</button></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="w-full font-ubuntu">
            <div id="latest-article" class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden flex p-6 mb-8 md:flex-row flex-col gap-4">
                <div class="flex-shrink-0">
                    <img src="https://placehold.co/400x180" alt="Article Image" class="rounded-md">
                </div>
                <div class="ml-6 flex flex-col items-start justify-between">
                    <span
                        class="inline-block bg-orange text-white text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide mb-2 font-ubuntu">Latest Articles</span>
                    <div>
                        <h2 class="text-xl font-bold text-indigo-900 mb-2 font-montserrat">Latest Article Title</h2>
                        <p class="text-gray-600 font-ubuntu">Latest article short description goes here.</p>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                        </svg>
                        <span class="font-ubuntu">12 Januari 2020</span>
                    </div>
                </div>
            </div>

            <div id="articles-grid" class="max-w-5xl mx-auto grid gap-6 md:grid-cols-2 lg:grid-cols-3"></div>
            <div class="flex justify-between items-center space-x-8 py-6">
                <button id="prev-page"
                    class="flex items-center px-4 py-2 bg-white text-gray-600 border border-gray-300 rounded-full shadow-sm hover:bg-gray-100 focus:outline-none font-ubuntu">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Previous
                </button>

                <button id="next-page"
                    class="flex items-center px-4 py-2 bg-white text-gray-600 border border-gray-300 rounded-full shadow-sm hover:bg-gray-100 focus:outline-none font-ubuntu">
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection()

@section('scripts')
<script>
let currentPage = 1;
const articlesPerPage = 6;
let articles = [];
let filteredCategory = "all";
let searchQuery = "";

// dynamic fetch article
async function fetchArticles() {
    try {
        const response = await fetch('/blogs/articles-en.json');
        articles = await response.json();
        renderArticles();
    } catch (error) {
        console.error('Error fetching articles:', error);
    }
}

// shorter
function sortByDateDescending(a, b) {
    return new Date(b.date) - new Date(a.date);
}

// render article
function renderArticles() {
    const articlesGrid = document.getElementById("articles-grid");
    articlesGrid.innerHTML = "";

    let filteredArticles = articles.filter(article => {
        return (filteredCategory === "all" || article.category === filteredCategory) &&
            (article.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
                article.description.toLowerCase().includes(searchQuery.toLowerCase()));
    });

    filteredArticles.sort(sortByDateDescending);

    const startIndex = (currentPage - 1) * articlesPerPage;
    const paginatedArticles = filteredArticles.slice(startIndex, startIndex + articlesPerPage);

    paginatedArticles.forEach(article => {
        articlesGrid.innerHTML += `
            <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6 animate-fade-in-left-bounce">
                <img src="${article.image}" alt="Article Image" class="h-40 w-full object-cover mb-4 rounded-md hover:scale-90 transition">
                <a href="${article.link}"><h3 class="text-xl font-semibold text-indigo-900 hover:text-indigo-700 mb-2 transition">${article.title}</h3></a>
                <p class="text-gray-600 mb-4">${article.description}</p>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    </svg>
                    <span>${formatDate(article.date)}</span>
                </div>
            </div>
        `;
    });

// component for latest article
    if (filteredArticles.length > 0) {
        const latestArticle = filteredArticles[0];
        document.getElementById("latest-article").innerHTML = `
            <div class="flex-shrink-0 md:w-1/2">
                <a href="${latestArticle.link}"><img src="${latestArticle.image}" alt="Article Image" class="rounded-md hover:scale-90 transition animate-fade-in-left-bounce"></a>
            </div>
            <div class="ml-6 md:w-1/2 flex flex-col items-start justify-between animate-fade-in-left-bounce">
                <span class="inline-block bg-orange text-white text-xs px-3 py-1 rounded-full uppercase font-semibold tracking-wide mb-2">Latest Articles</span>
                <div>
                    <a href="${latestArticle.link}"><h2 class="text-xl font-bold text-indigo-900 mb-2">${latestArticle.title}</h2></a>
                    <p class="text-gray-600">${latestArticle.description}</p>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    </svg>
                    <span>${formatDate(latestArticle.date)}</span>
                </div>
            </div>
        `;
    }
}

function formatDate(dateString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', options);
}

function filterByCategory(category) {
    filteredCategory = category;
    currentPage = 1;
    renderArticles();
}

document.getElementById("search-input").addEventListener("input", function () {
    searchQuery = this.value;
    currentPage = 1;
    renderArticles();
});

document.getElementById("prev-page").addEventListener("click", function () {
    if (currentPage > 1) {
        currentPage--;
        renderArticles();
    }
});

document.getElementById("next-page").addEventListener("click", function () {
    const totalPages = Math.ceil(articles.length / articlesPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderArticles();
    }
});

// cronjob article pengecekan
function startArticleFetchInterval() {
    fetchArticles();
    setInterval(fetchArticles, 300000);
}

startArticleFetchInterval();
</script>
@endsection()
