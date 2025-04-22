<template>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <button @click="$router.push('/katalog')"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-[2rem] ml-4 lg:text-2xl font-bold flex-1">Keranjang Belanja</h1>
        </div>

        <!-- Tampilkan item di keranjang -->
        <div v-if="cartItems.length > 0">
            <div v-for="item in cartItems" :key="item.id" class="flex gap-4 mb-6 border-b pb-4">
                <img :src="item.images[0]?.url || 'https://via.placeholder.com/150'" alt="Product" class="w-40 h-40 object-cover rounded" />
                <div class="flex-1">
                    <h2 class="text-[2rem] lg:text-xl font-semibold">{{ item.name }}</h2>
                    <p class="text-2xl text-gray-500">Rp {{ item.price.toLocaleString() }}</p>
                    <p class="text-2xl text-gray-600">Qty: {{ item.quantity }}</p>
                    <p class="text-2xl text-green-600 font-medium">Total: Rp {{ (item.price * item.quantity).toLocaleString() }}</p>
                </div>
                <button
                    @click="removeItem(item.id)"
                    class="px-4 py-2 bg-red-500 lg:text-xl text-white text-2xl font-semibold rounded-md hover:bg-red-600 transition">
                    Hapus
                </button>
            </div>

            <!-- Total dan tombol checkout -->
            <div class="text-right mt-8">
                <p class="text-[2rem] lg:text-xl font-bold">Total: Rp {{ total.toLocaleString() }}</p>
                <button
                    @click="$router.push('/pembayaran')"
                    class="mt-4 w-full bg-blue-600 text-white py-3 lg:text-xl rounded-lg text-[2.5rem] hover:bg-blue-700 transition"
                >
                    Checkout
                </button>
            </div>
        </div>

        <!-- Tampilkan pesan jika keranjang kosong -->
        <div v-else class="text-center text-2xl font-semibold text-gray-500">
            Keranjang belanja kosong.
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';

// Ambil data keranjang dari LocalStorage
const cartItems = ref(JSON.parse(localStorage.getItem('cart')) || []);

// Fungsi untuk menyimpan data keranjang ke LocalStorage
const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cartItems.value));
};

// Pantau perubahan pada cartItems dan simpan ke LocalStorage
watch(cartItems, (newCart) => {
    localStorage.setItem('cart', JSON.stringify(newCart));
}, { deep: true });

// Fungsi untuk menghapus item dari keranjang
const removeItem = (id) => {
    cartItems.value = cartItems.value.filter((item) => item.id !== id);
    saveCart();
};

// Hitung total harga semua item di keranjang
const total = computed(() =>
    cartItems.value.reduce((acc, item) => acc + item.price * item.quantity, 0)
);

// Fungsi untuk melakukan checkout
const checkout = async () => {
    try {
        const response = await axios.post('/api/checkout', { items: cartItems.value });
        alert('Checkout berhasil!');
        cartItems.value = []; // Kosongkan keranjang setelah checkout
        saveCart(); // Simpan perubahan ke LocalStorage
    } catch (error) {
        console.error('Checkout gagal:', error.response?.data?.message || error.message);
        alert('Checkout gagal. Silakan coba lagi.');
    }
};
</script>

<style scoped>
/* Tambahkan styling jika diperlukan */
</style>
