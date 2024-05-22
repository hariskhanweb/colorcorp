<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found</title>    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<section class="bg-white dark:bg-gray-900 ">
    <div class="container min-h-screen px-6 py-12 mx-auto lg:flex lg:items-center lg:gap-12 justify-center">
        <div class="w-full flex justify-center flex-col items-center">
            <p class="text-2xl font-medium text-blue-500 dark:text-blue-400 md:text-5xl">404 Error</p>
            <div class="relative w-full h-80 mt-12 lg:w-1/2 lg:mt-0">
                <img class="absolute h-full w-full object-cover" src="{{ url('img/error.gif') }}" alt="Error 404">
            </div>
            <h1 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white md:text-3xl">Page not found</h1>
            <p class="mt-4 text-gray-500 dark:text-gray-400">Sorry, the page you are looking for doesn't exist.</p>

            <div class="flex items-center mt-6 gap-x-3">
                <a href="{{route('home')}}" class="w-1/2 px-6 py-3 text-md flex items-center justify-center gap-x-2 tracking-wide text-white transition-colors duration-200 bg-indigo-500 rounded-lg shrink-0 sm:w-auto hover:bg-indigo-600 dark:hover:bg-indigo-500 dark:bg-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                    <span>Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</section>
</body>
</html>