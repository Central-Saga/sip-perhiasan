<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body class="min-h-screen antialiased">
        <div class="min-h-svh flex flex-col md:flex-row">
            <!-- Decorative Side Panel -->
            <div class="hidden md:block md:w-1/2 relative overflow-hidden">
                <!-- HD Background Image -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1600003014755-ba31aa59c4b6?auto=format&fit=crop&q=90" 
                         alt="Silver Jewelry Display" 
                         class="w-full h-full object-cover object-center brightness-[0.9] contrast-[1.1]">
                    <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/80 via-slate-800/50 to-indigo-900/60"></div>
                </div>
                
                <div class="relative z-10 h-full flex flex-col justify-between p-8">
                    <!-- Logo Area -->
                    <div class="flex items-center gap-2">
                        <div class="flex items-center">
                            <span class="text-slate-300 flex items-center justify-center">
                                <i class="fa-solid fa-gem text-2xl"></i>
                            </span>
                            <span class="text-xl font-bold text-white ml-2">Bliss Silversmith</span>
                        </div>
                    </div>
                    
                    <!-- Center Content -->
                    <div class="flex flex-col items-center justify-center text-center">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                            Sistem Informasi Penjualan<br>
                            <span class="text-indigo-200">Perhiasan Premium</span>
                        </h1>
                        <p class="text-slate-200 mb-8 max-w-md">Koleksi perhiasan silver eksklusif dengan desain modern dan elegan.</p>
                        
                        <!-- Showcase Images -->
                        <div class="flex flex-col space-y-4 items-center max-w-xs">
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg border border-white/20">
                                <div class="flex items-center space-x-4">
                                    <img src="https://images.unsplash.com/photo-1590548784585-643d2b9f2925?auto=format&fit=crop&w=200&q=80" alt="Silver Ring" class="w-16 h-16 object-cover rounded-lg">
                                    <div class="text-left">
                                        <h3 class="text-white font-medium">Cincin Silver Premium</h3>
                                        <p class="text-indigo-200 text-sm">Elegan dan modern</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bottom text -->
                    <div class="text-slate-300 text-sm">
                        <p>UMKM Bliss Silversmith &copy; {{ date('Y') }}</p>
                        <p>Perhiasan berkualitas tinggi dengan design elegan</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Area -->
            <div class="flex-1 flex flex-col items-center justify-center p-6 md:p-10 bg-white dark:bg-slate-900">
                <div class="w-full max-w-sm">
                    <div class="mb-6 md:hidden flex flex-col items-center">
                        <div class="flex items-center mb-2">
                            <span class="text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <i class="fa-solid fa-gem text-2xl"></i>
                            </span>
                            <h1 class="text-xl font-bold text-slate-800 dark:text-white ml-2">Bliss Silversmith</h1>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Sistem Informasi Penjualan Perhiasan</p>
                    </div>
                    
                    <div class="bg-white/90 dark:bg-slate-800/90 rounded-xl p-6 shadow-lg border border-slate-100 dark:border-slate-700 backdrop-blur-md">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
