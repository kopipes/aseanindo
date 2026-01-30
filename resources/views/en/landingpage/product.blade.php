@extends('en.layout-landing.master')

@section('title')
    <title>Full Service Products For Your Business - Kontakami</title>
@endsection()

@section('seo')
    <meta name="description" content="A range of products and services to improve your business' customer service. Consult according to your needs.">
    <link rel="canonical" href="https://kontakami.com/en/product" />
@endsection()

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row gap-5 items-center justify-between mt-20">
        <div class="w-full md:w-1/2 space-y-4">
            <p class="text-secblue font-semibold font-ubuntu">Our Product</p>
            <h1 class="text-4xl font-bold text-darkblue font-montserrat">The Products We Offer</h1>
            <p class="text-black font-ubuntu">
                We provide a range of premium products designed to enhance the quality of your customer service.
            </p>
            <a href="#product"
                class="inline-block px-6 py-3 bg-orange text-white rounded-full text-sm font-semibold hover:bg-white hover:border hover:border-orange hover:text-orange transition font-ubuntu">
                Read More
            </a>
        </div>
        <div class="w-full md:w-1/2 flex justify-center md:justify-end">
            <div class="relative">
                <img src="/img/rebranding/product/product-top.webp" alt="Person Image Man" class="rounded-lg w-full">
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto py-16 px-4 flex flex-col justify-center items-center align-middle" id="product">
        <div class="text-center mb-12">
            <p class="text-lightblue font-semibold font-ubuntu">Our Product</p>
            <h2 class="text-4xl font-bold text-darkblue font-montserrat">The Products We Offer</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl">
            <div class="bg-white p-4">
                <a href="/en/product/contact-center-software"><img src="/img/rebranding/product/contact.webp" alt="Contact Center Software"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/contact-center-software">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Contact
                        Center Software</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    An integrated solution to manage customer communication through various channels such as phone, email, and chat, helping to enhance operational efficiency.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/omnichannel-contact-center"><img src="/img/rebranding/product/omnichannel.webp" alt="Omnichannel Contact Center"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/omnichannel-contact-center">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Omnichannel Contact Center</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    A platform that connects businesses with customers through WhatsApp, email, social media, and phone, providing a consistent experience.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/self-service-contact-center"><img src="/img/rebranding/product/self-service.webp" alt="Self Service Contact Center"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/self-service-contact-center">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Self Service Contact Center</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    A platform that effectively manages and responds to customer support requests, ensuring issues are resolved quickly and accurately.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/ticketing-management-system"><img src="/img/rebranding/product/ticketing.webp"
                        alt="Ticketing Management System" class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/ticketing-management-system">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Ticketing Management System</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    A system that records, tracks, and manages customer service tickets or requests, ensuring all issues are addressed efficiently.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/features-and-benefits"><img src="/img/rebranding/product/features.webp" alt="Features and Benefits"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/features-and-benefits">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">Features
                        and Benefits</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Various features such as real-time tracking, task automation, and performance analytics, providing benefits in the form of increased productivity and customer satisfaction.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/automation-customer-service"><img src="/img/rebranding/product/automation.webp"
                        alt="Automation Customer Services" class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/automation-customer-service">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Customer Service Automation</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Customer service automation through chatbots and automated systems that help respond to customer requests quickly and efficiently.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/workforce-performance"><img src="/img/rebranding/product/workforce.webp" alt="Workforce Performance"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/workforce-performance">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">
                        Workforce Performance</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    A dashboard to monitor and improve team performance, enabling better management through performance metrics and detailed reporting.
                </p>
            </div>

            <div class="bg-white p-4">
                <a href="/en/product/sdm-outsourcing"><img src="/img/rebranding/product/sdm.webp" alt="SDM Outsourcing"
                        class="rounded-md mb-4 hover:scale-90 transition"></a>
                <a href="/en/product/sdm-outsourcing">
                    <h2 class="text-lg font-bold text-darkblue mb-2 hover:text-blue-700 transition font-montserrat">SDM Outsourcing</h2>
                </a>
                <p class="text-black text-sm font-ubuntu">
                    Workforce process planning to meet turnover capacity, including selection, training, and placement.
                </p>
            </div>
        </div>
    </div>
    <div class="max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row gap-10 items-center justify-between">
        <div class="md:w-1/2 space-y-4">
            <p class="text-secblue font-semibold font-ubuntu">Benefits of Use</p>
            <h2 class="text-4xl font-bold text-darkblue font-montserrat">Benefits of Using Kontakami</h2>
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
                    <p><span class="font-bold">Free Calls:</span> Call the contact center without any cost (with internet connection).</p>
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
                    <p><span class="font-bold">Flexible Chat:</span> Communicate directly through chat if you prefer not to call.</p>
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
                    <p><span class="font-bold">Callback:</span> If customer service is busy, we will get back to you.</p>
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
                    <p><span class="font-bold">Location Sharing:</span> Easily share your location through chat.</p>
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
                    <p><span class="font-bold">Access Products:</span> Explore your favorite products and services on KontaKami.
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
                    <p><span class="font-bold">Fast Connection:</span> Connect directly with customer service without delay.</p>
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
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-montserrat">Optimize Your Customer Service</h2>
            <p class="text-sm md:text-base mb-6 font-ubuntu">
                Enhance efficiency and customer engagement with a comprehensive digital contact center platform. Enjoy easy access, omnichannel integration, and top-notch service management with Kontakami.
            </p>
            <a href="/en/contact"
                class="inline-block px-6 py-3 bg-orange text-white rounded-xl font-bold text-sm hover:bg-white hover:border hover:border-orange hover:text-orange transition font-montserrat">
                Get Started Now
            </a>
        </div>
    </div>
@endsection()
