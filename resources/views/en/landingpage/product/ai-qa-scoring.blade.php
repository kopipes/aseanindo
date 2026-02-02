@extends('en.layout-landing.master')

@section('title')
    <title>AI QA Scoring - Kontakami</title>
@endsection()

@section('seo')
    <meta name="description"
        content="Automated Quality Assurance Scoring powered by AI to enhance customer service evaluation and efficiency.">
    <link rel="canonical" href="https://kontakami.com/en/product/ai-qa-scoring" />
@endsection()

@section('content')
    <div
        class="max-w-7xl mx-auto py-16 px-4 md:px-8 flex flex-col lg:flex-row items-center justify-between mt-[4rem] md:mt-32">
        <div class="w-full lg:w-1/2 mb-8 lg:mb-0 relative">
            <img src="/img/dashboard_ai_scoring.png" alt="AI QA Scoring" class="rounded-lg shadow-lg">
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
        <div class="w-full lg:w-1/2 lg:pl-12">
            <h1 class="text-3xl font-bold text-darkblue mb-6 font-montserrat">AI QA Scoring</h1>
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
                        <p class="text-lg text-black"><span class="font-bold text-slate-900">Operational Efficiency:</span>
                            Our AI QA Scoring will evaluate Agent and Customer conversations, so the team can focus on
                            in-depth Analysis and Agent training.</p>
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
                        <p class="text-lg text-black"><span class="font-bold text-slate-900">Early Risk Detection:</span>
                            Identify potential service violations, mis-selling, and sensitive issues
                            before they develop into major problems.</p>
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
                        <p class="text-lg text-black"><span class="font-bold text-slate-900">Agent Performance
                                Optimization:</span> Helping management understand agent strength and weakness patterns for
                            more targeted development.</p>
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
                        <p class="text-lg text-black"><span class="font-bold text-slate-900">Data-Based Insights:</span>
                            Providing strategic insights for improving service quality and business performance.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-16 px-4 md:px-8">
        <div class="text-center mb-12">
            <p class="text-lightblue font-semibold">Product</p>
            <h2 class="text-3xl font-bold text-darkblue font-montserrat">AI QA Scoring</h2>
            <p class="text-black mt-4 mb-6">
                AI QA Scoring helps analyze phone conversations between customers and agents objectively,
                consistently and in real-time; so the QA team can focus on analysis, coaching, and data-driven decisions.
            </p>
            <p class="text-orange font-semibold text-lg">"100% Automated and Real-Time Customer Interaction Analysis"
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 flex flex-col items-center">
                <img src="/img/rebranding/ai-qa-scoring/indonesian-context.png" alt="Indonesian Context"
                    class="rounded-2xl mb-6">
                <h2 class="text-lg font-bold text-secblue mb-4 text-center">Advantage of Understanding Indonesian Context
                </h2>
                <p class="text-black text-center">
                    Our AI understands informal Indonesian speech that is not too formal. Our system is specifically trained
                    for
                    Indonesian language, dialects, and local slang.
                </p>
            </div>
            <div class="p-6 flex flex-col items-center">
                <img src="/img/rebranding/ai-qa-scoring/human-ai-synergy.png" alt="Human + AI Synergy"
                    class="rounded-2xl mb-6">
                <h2 class="text-lg font-bold text-secblue mb-4 text-center">Human + AI Synergy is a Superpower</h2>
                <p class="text-black text-center">
                    We don't replace your QA team. We empower them with AI QA Scoring.
                </p>
            </div>
            <div class="p-6 flex flex-col items-center">
                <img src="/img/rebranding/ai-qa-scoring/investment-risk.png" alt="Right Investment"
                    class="rounded-2xl mb-6">
                <h2 class="text-lg font-bold text-secblue mb-4 text-center">Right Investment, Lower Risk</h2>
                <p class="text-black text-center">
                    System costs are lower compared to potential losses from service errors, customer churn,
                    and legal risks.
                </p>
            </div>
        </div>
    </div>

    <div class="relative bg-cover bg-center h-80 flex items-center justify-center"
        style="background-image: url('/img/rebranding/footer-banner.webp');">
        <div class="relative z-10 text-center text-white px-4 max-w-3xl">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-montserrat">Improve Your Service Strategy</h2>
            <p class="text-sm md:text-base mb-6 font-ubuntu">
                Use AI QA Scoring to ensure every customer gets the best experience. Try it now and
                feel the difference.
            </p>
            <a href="/en/contact"
                class="inline-block px-6 py-3 bg-orange text-white rounded-xl font-bold text-sm hover:bg-white hover:border hover:border-orange hover:text-orange transition font-montserrat">
                Start Now
            </a>
        </div>
    </div>
@endsection()