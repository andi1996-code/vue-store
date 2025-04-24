<template>
    <div class="max-w-6xl mx-auto px-4 py-8 relative">
        <h1 class="text-3xl font-bold mb-6 text-center">Katalog Produk</h1>

        <!-- Grid Produk -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <ProductCard v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart" />
        </div>

        <!-- Keranjang -->
        <div @click="handleCartClick"
            class="fixed bottom-8 right-8 transition rounded-full hover:bg-blue-600 p-4 shadow-lg hover:shadow-xl cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"
                class="w-16 h-16 fill-blue-600 hover:fill-white transition">
                <path
                    d="M45.3 81.2h78V43.7L35.9 25.4l-3.1-12.9-12.6-4.2c0-.2.1-.3.1-.5 0-4.3-3.5-7.8-7.8-7.8S4.7 3.5 4.7 7.8s3.5 7.8 7.8 7.8c1.8 0 3.4-.6 4.7-1.6l9.4 4.7L39 78l-12.5 9.4V103l5.7 7.1c-1.6 1.9-2.5 4.3-2.5 7 0 6 4.9 10.9 10.9 10.9s10.9-4.9 10.9-10.9-4.9-10.9-10.9-10.9c-.9 0-1.8.1-2.6.3l-2.1-3.4h65.6l3.6 6c-2.2 2-3.6 4.9-3.6 8.1 0 6 4.9 10.9 10.9 10.9s10.9-4.9 10.9-10.9-4.9-10.9-10.9-10.9h-.3l-1.3-3.1h12.5V97H32.8v-6.2l12.5-9.6zm0-6.3-4.6-21.4.6 3L59.8 58l3.8 17H45.3zm21.8 0-3.7-16.7 18.1 1.4 1.4 15.3H67.1zm18.8 0-1.4-15 17 1.3v13.7H85.9zm31.2-15.6v15.6h-12.5V61.5l12.5 1v-3.2l-12.5-1V44.4l12.5 2.4v12.5zM35.9 31.2l65.6 12.6V58l-17.3-1.4-1.5-16.4-3.1-.6 1.6 16.8-18.5-1.5-4.3-19.3-3.7-.7 4.4 19.7-18.5-1.5-4.7-21.9zm76.5 81.2c2.6 0 4.7 2.1 4.7 4.7s-2.1 4.7-4.7 4.7-4.7-2.1-4.7-4.7 2.1-4.7 4.7-4.7zm-71.8 0c2.6 0 4.7 2.1 4.7 4.7s-2.1 4.7-4.7 4.7-4.7-2.1-4.7-4.7 2.1-4.7 4.7-4.7z"
                    fill="currentColor" />
            </svg>

            <!-- Badge Cart -->
            <span v-if="cart.length > 0"
                class="absolute top-0 right-0 bg-red-500 text-white text-3xl px-2 rounded-full">
                {{cart.reduce((total, item) => total + item.quantity, 0)}}
            </span>
        </div>

        <!-- Form Customer -->
        <AuthCustomer :show="showCustomerForm" :customer="customer" @close="showCustomerForm = false"
            @submit="handleCustomerSubmit" />
    </div>
</template>

<script>
import axios from 'axios';
import ProductCard from '../components/ProductCard.vue';
import AuthCustomer from '../components/AuthCustomer.vue';
import Header from '../components/Header.vue';

export default {
    components: { ProductCard, AuthCustomer, Header },
    data() {
        return {
            products: [], // Data produk akan diambil dari API
            cart: [], // Keranjang belanja
            showCustomerForm: false, // Kontrol tampilan form customer
            customer: {
                name: '',
                phone: '',
            },
        };
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get('/api/products'); // Panggil API produk
                this.products = response.data; // Simpan data produk ke state
            } catch (error) {
                console.error('Gagal mengambil data produk:', error);
            }
        },
        addToCart(product) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existingProduct = cart.find((item) => item.id === product.id);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            this.updateCart();
        },
        updateCart() {
            this.cart = JSON.parse(localStorage.getItem('cart')) || [];
        },
        handleCustomerSubmit(customerData) {
            sessionStorage.setItem('customer', JSON.stringify(customerData));
            this.showCustomerForm = false;
            this.$router.push('/cart');
        },
        checkCustomer() {
            const savedCustomer = JSON.parse(sessionStorage.getItem('customer'));
            if (savedCustomer) {
                this.customer = savedCustomer;
                this.showCustomerForm = false;
            } else {
                this.showCustomerForm = true;
            }
        },
        handleCartClick() {
            const sessionId = sessionStorage.getItem('customer'); // Periksa ID sesi
            if (!sessionId) {
                // Jika sesi kosong, tampilkan formulir pelanggan
                this.showCustomerForm = true;
            } else {
                // Jika sesi ada, arahkan ke halaman keranjang
                this.$router.push('/cart');
            }
        }

    },
    mounted() {
        this.fetchProducts();
        this.updateCart();
        // this.checkCustomer();
    },
    beforeRouteEnter(to, from, next) {
        next((vm) => {
            vm.updateCart(); // Perbarui state keranjang saat kembali ke halaman ini
        });
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
