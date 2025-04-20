<template>
    <div class="max-w-full mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <button @click="$router.push('/katalog')"
            class="px-4 py-2 bg-gray-200 rounded text-gray-700 hover:bg-gray-300 transition">
            ←
            </button>
            <h1 class="text-[2rem] font-bold text-center flex-1">Keranjang Belanja</h1>
        </div>

        <div v-for="item in cartItems" :key="item.id" class="flex gap-4 mb-6 border-b pb-4">
            <img :src="item.image" alt="Product" class="w-60 h-60 object-cover rounded" />
            <div class="flex-1">
                <h2 class="text-[2rem] font-semibold">{{ item.name }}</h2>
                <p class="text-2xl text-gray-500">Rp {{ item.price.toLocaleString() }}</p>
                <p class="text-2xl text-gray-600">Qty: {{ item.quantity }}</p>
                <p class="text-2xl text-green-600 font-medium">Total: Rp {{ (item.price *
                    item.quantity).toLocaleString()
                    }}</p>
            </div>
            <button class="text-red-500 text-3xl font-semibold hover:underline">Hapus</button>
        </div>

        <div class="text-right mt-8">
            <p class="text-[2rem] font-bold">Total: Rp {{ total.toLocaleString() }}</p>
            <button
                class="mt-4 w-full bg-blue-600 text-white py-3 rounded-lg text-[2.5rem] hover:bg-blue-700 transition">
                Checkout
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const cartItems = ref([
    {
        id: 1,
        name: 'Produk A',
        price: 75000,
        quantity: 2,
        image: 'https://down-id.img.susercontent.com/file/id-11134207-7r98z-lmpjl2n465xobd.webp',
    },
    {
        id: 2,
        name: 'Produk B',
        price: 120000,
        quantity: 1,
        image: 'https://down-id.img.susercontent.com/file/id-11134207-7r98v-lmpjl2n4d6rw7f.webp',
    },
])

const total = computed(() =>
    cartItems.value.reduce((acc, item) => acc + item.price * item.quantity, 0)
)
</script>
