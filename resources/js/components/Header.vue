<template>
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex items-center justify-between">
            <!-- Logo Toko -->
            <a href="/" class="text-2xl font-bold text-blue-500">
                <img src="/public/assets/icon/logo header.svg" alt="Logo Toko" class="h-20" />
            </a>

            <!-- Search Bar -->
            <div class="flex-1 mx-4">
                <input type="text" placeholder="Cari produk..."
                    class="w-full lg:h-14 h-20 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Ikon Keranjang dan Akun Pengguna -->
            <div class="flex items-center space-x-4">
                <!-- Keranjang -->
                <a href="/cart" class="relative text-gray-600 hover:text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lg:h-12 lg:w-12 h-16" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 5m12-5l2 5m-6-5v5m-4-5v5" />
                    </svg>
                    <span class="absolute top-0 right-0 bg-red-500 text-white lg:text-sm rounded-full px-1">
                        3
                    </span>
                </a>

                <!-- Akun Pengguna
                <a href="/akun" class="text-gray-600 hover:text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A9 9 0 1118.879 6.196M12 12v.01" />
                    </svg>
                </a> -->
            </div>
        </div>
    </header>
</template>

<style>
/* Tambahkan styling tambahan jika diperlukan */
</style>
