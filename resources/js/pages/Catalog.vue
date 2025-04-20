<template>
    <div class="max-w-6xl mx-auto px-4 py-8 relative">
        <h1 class="text-3xl font-bold mb-6 text-center">Katalog Produk</h1>

        <!-- Grid Produk -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <ProductCard v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart" />
        </div>

        <!-- Keranjang -->
        <router-link to="/cart">
            <div
                class="fixed bottom-8 right-8 text-white p-8 rounded-full shadow-lg hover:bg-blue-700 transition">
                <img src="/public/assets/icon/shopping-cart.png" alt="Cart Icon" class="w-16 h-16" />
                <span v-if="cart.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white text-3xl px-2 rounded-full">
                    {{ cart.length }}
                </span>
            </div>
        </router-link>
    </div>
</template>

<script>
import ProductCard from '../components/ProductCard.vue'

export default {
    components: { ProductCard },
    data() {
        return {
            products: [
                {
                    id: 1,
                    name: 'Rak Serbaguna',
                    price: 35000,
                    image: 'https://down-id.img.susercontent.com/file/id-11134207-7r98z-lmpjl2n465xobd.webp',
                },
                {
                    id: 2,
                    name: 'Blender Portable',
                    price: 65000,
                    image: 'https://down-id.img.susercontent.com/file/id-11134207-7ras9-m28zvojkl2lf8e.webp',
                },
                {
                    id: 3,
                    name: 'Tempat Bumbu Dapur',
                    price: 25000,
                    image: 'https://down-id.img.susercontent.com/file/2dce63f5b58b15abd062b5b3f8072a86.webp',
                },
                {
                    id: 4,
                    name: 'Pisau Set Dapur',
                    price: 25000,
                    image: 'https://down-id.img.susercontent.com/file/id-11134207-7r992-lw5hny1s18jxbe.webp',
                },
                {
                    id: 5,
                    name: 'Cutlery Holder- Tempat Sendok Garpu Dengan Tirisan wadah peralatan makan',
                    price: 25000,
                    image: 'https://down-id.img.susercontent.com/file/15625ce61b9b8bd3af19c706c9e30a5c.webp',
                },
            ],
            cart: [],  // Keranjang belanja
        };
    },
    methods: {
        addToCart(product) {
            // Tambahkan produk ke keranjang
            this.cart.push(product);
        },
    },
};
</script>

<style scoped>
/* Styling untuk tombol keranjang melayang */
.fixed {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
}
</style>
