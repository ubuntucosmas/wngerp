<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- GSAP for smooth animations -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
        
        <!-- Page Loader Styles -->
        <style>
            .page-loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #0C2D48;
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.5s ease-out;
            }
            .loader-logo {
                width: 100px;
                height: 100px;
                opacity: 0;
                transform: translateY(20px);
            }
            .page-content {
                opacity: 0;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Page Loader -->
        <div class="page-loader" id="pageLoader">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="loader-logo" id="loaderLogo">
        </div>

        <div class="page-content min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Animate loader
                gsap.to('#loaderLogo', {
                    duration: 0.8,
                    opacity: 1,
                    y: 0,
                    ease: 'power2.out'
                });

                // Hide loader and show content
                setTimeout(() => {
                    gsap.to('#pageLoader', {
                        duration: 0.5,
                        opacity: 0,
                        display: 'none',
                        ease: 'power2.inOut',
                        onComplete: () => {
                            document.getElementById('pageLoader').style.display = 'none';
                            // Animate in the page content
                            gsap.to('.page-content', {
                                duration: 0.8,
                                opacity: 1,
                                ease: 'power2.out'
                            });
                            // Animate sidebar items
                            gsap.from('.nav-item', {
                                duration: 0.6,
                                x: -20,
                                opacity: 0,
                                stagger: 0.1,
                                ease: 'power2.out',
                                delay: 0.3
                            });
                        }
                    });
                }, 1000);
            });
        </script>
    </body>
</html>
