<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You</title>    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<section class="bg-white dark:bg-gray-900 ">
    <div class="flex items-center justify-center h-screen">
        <div>
            <div class="flex flex-col items-center space-y-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-green-600 w-40 h-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-5xl font-bold">Thank You !</h1>
            <p class="text-lg">Your order #{{$order_number}} has been placed!.</p>
            <p class="text-lg">We sent an email to {{Auth::user()->email}} with your order confirmation and order detail.</p>
            <a href="{{route('my.order')}}" class="w-1/2 px-6 py-3 text-md flex items-center justify-center gap-x-2 tracking-wide text-white transition-colors duration-200 bg-indigo-500 rounded-lg shrink-0 sm:w-auto hover:bg-indigo-600 dark:hover:bg-indigo-500 dark:bg-indigo-600">
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