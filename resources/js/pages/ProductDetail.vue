<template>
    <div class="p-12 mb-14 lg:p-12 rounded-md max-w-6xl mx-auto bg-slate-100">
        <div class="flex justify-between items-center mb-6">
            <button @click="$router.push('/katalog')"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-[2rem] font-bold ml-4 flex-1">Detail Produk</h1>
        </div>
        <!-- Tampilkan loading -->
        <div v-if="loading" class="text-center text-2xl font-semibold">
            Memuat data produk...
        </div>

        <!-- Tampilkan error -->
        <div v-else-if="error" class="text-center text-red-500 text-2xl font-semibold">
            {{ error }}
        </div>
        <!-- Tombol Kembali -->

        <!-- Tampilkan detail produk -->
        <div v-else class="flex flex-col lg:flex-row gap-8">
            <!-- Bagian Gambar -->
            <div class="w-full lg:w-1/2">
                <Splide :options="{ type: 'loop', perPage: 1, autoplay: true }">
                    <SplideSlide v-for="(img, index) in product.images" :key="index">
                        <img :src="img.url" alt="Gambar produk"
                            class="lg:w-full lg:h-full w-7/12 h-full object-cover rounded-lg shadow-lg border border-gray-200" />
                    </SplideSlide>
                </Splide>
            </div>

            <!-- Bagian Detail Produk -->
            <div class="w-full lg:w-1/2 flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl lg:text-2xl font-bold mb-4 text-gray-800">{{ product.name }}</h1>
                    <p class="text-2xl lg:text-3xl font-semibold text-green-600 mt-3">
                        Rp {{ product.price.toLocaleString('id-ID') }}
                    </p>
                    <div class="mt-4 text-2xl lg:text-lg text-gray-600 leading-relaxed prose prose-sm lg:prose-lg max-w-none"
                        v-html="product.description"></div>
                </div>
                <div class="mt-6">
                    <button
                        class="w-full py-4 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105 shadow-lg text-xl font-semibold">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import apiClient from '../axios'; // Impor instance axios

const route = useRoute();
const product = ref(null);
const loading = ref(true);
const error = ref(null);

// Fungsi untuk mengambil data produk dari API
const fetchProduct = async () => {
    try {
        const response = await apiClient.get(`/products/${route.params.id}`); // Gunakan instance axios
        product.value = response.data;
    } catch (err) {
        console.error('Gagal mengambil data produk:', err);
        error.value = 'Gagal memuat data produk.';
    } finally {
        loading.value = false;
    }
};

// Ambil data produk saat komponen dimuat
onMounted(() => {
    fetchProduct();
});
</script>

<style>
/* Import style Splide */
@import '@splidejs/splide/dist/css/splide.min.css';

/* Tambahkan animasi hover */
button:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Improve typography */
h1 {
    color: #1a202c;
}

p {
    line-height: 1.6;
}

/* Add border and shadow to images */
img {
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

img:hover {
    transform: scale(1.05);
}
</style>
