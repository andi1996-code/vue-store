<template>
    <div class="p-6 lg:max-w-4xl mx-auto flex flex-col lg:flex-row gap-8">
        <!-- Bagian Gambar -->
        <div class="lg:w-1/2">
            <Splide :options="{ type: 'loop', perPage: 1, autoplay: true }">
                <SplideSlide v-for="(img, index) in product.images" :key="index">
                    <img :src="img" alt="Gambar produk" class="w-full h-full object-cover rounded-lg shadow-lg" />
                </SplideSlide>
            </Splide>
        </div>

        <!-- Bagian Detail Produk -->
        <div class="lg:w-1/2 flex flex-col justify-between">
            <div>
                <h1 class="lg:text-3xl text-[4rem] font-bold mb-4">{{ product.name }}</h1>
                <p class="lg:text-xl text-[3rem] font-semibold text-green-600 mt-3">
                    Rp {{ product.price }}
                </p>
                <p class="mt-4 lg:text-base text-[2rem] text-gray-600 text-justify">
                    {{ product.description }}
                </p>
            </div>
            <div class="mt-6">
                <button
                    class="lg:px-6 lg:py-3 py-8 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105 shadow-lg w-full lg:text-xl text-[2.5rem] font-semibold">
                    Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { Splide, SplideSlide } from '@splidejs/vue-splide'

const route = useRoute()

// Simulasi data produk
const product = ref({
    id: route.params.id,
    name: 'Produk Dummy ' + route.params.id,
    description: 'BAS square rak troli rak dapur rak serbaguna 4 tingkat dengan rodaDeskripsi Produk :- Material Plastik- Tersedia warna Hitam dan Putih- Terdiri dari 4 susun rak- Ada 4 roda- Cocok digunakan di ruang tamu , ruang makan , kamar tidur , dapur , kantor dll- Size 35x20x85 Cm ( Diukur setelah semua komponen terpasang )',
    price: 75000,
    images: [
        'https://down-id.img.susercontent.com/file/id-11134207-7r98z-lmpjl2n465xobd.webp',
        'https://down-id.img.susercontent.com/file/id-11134207-7r98q-lmpjl2n47ki487.webp',
        'https://down-id.img.susercontent.com/file/id-11134207-7r98v-lmpjl2n4d6rw7f.webp',
    ],
})
</script>

<style>
/* Import style Splide */
@import '@splidejs/splide/dist/css/splide.min.css';

/* Tambahkan animasi hover */
button:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}
</style>
