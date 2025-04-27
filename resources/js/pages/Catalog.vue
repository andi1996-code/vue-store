<template>
    <div class="max-w-6xl mx-auto px-4 py-8 flex">
        <div class="flex items-center w-full">
            <img src="/public/assets/icon/logo header.svg" alt="Logo" class="h-12 mr-4" />
            <input type="text" v-model="searchQuery" placeholder="Cari produk..."
                class="flex-grow border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-4"
                @input="handleSearch" />
            <div class="relative" @click="handleCartClickStatus">
                <img src="/public/assets/icon/program.png" alt="Order Status"
                    class="h-12 border-none hover:bg-green-300 rounded-lg shadow-none" />
                <!-- Badge -->
                <span v-if="hasOrderStatus"
                    class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-ping-fast">
                    1
                </span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 relative">
        <h1 class="text-3xl font-bold mb-6 text-center">Katalog Produk</h1>

        <!-- Loading Spinner -->
        <div class="min-h-[200px] flex items-center justify-center" v-if="loading">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>

        <!-- Product Grid -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <ProductCard v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart" />
        </div>

        <!-- Floating Cart Button -->
        <div @click="handleCartClick"
            class="fixed bottom-8 right-8 transition rounded-full hover:bg-blue-600 p-4 shadow-lg hover:shadow-xl cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"
                class="w-16 h-16 fill-blue-600 hover:fill-white transition">
                <path
                    d="M45.3 81.2h78V43.7L35.9 25.4l-3.1-12.9-12.6-4.2c0-.2.1-.3.1-.5 0-4.3-3.5-7.8-7.8-7.8S4.7 3.5 4.7 7.8s3.5 7.8 7.8 7.8c1.8 0 3.4-.6 4.7-1.6l9.4 4.7L39 78l-12.5 9.4V103l5.7 7.1c-1.6 1.9-2.5 4.3-2.5 7 0 6 4.9 10.9 10.9 10.9s10.9-4.9 10.9-10.9-4.9-10.9-10.9-10.9c-.9 0-1.8.1-2.6.3l-2.1-3.4h65.6l3.6 6c-2.2 2-3.6 4.9-3.6 8.1 0 6 4.9 10.9 10.9 10.9s10.9-4.9 10.9-10.9-4.9-10.9-10.9-10.9h-.3l-1.3-3.1h12.5V97H32.8v-6.2l12.5-9.6zm0-6.3-4.6-21.4.6 3L59.8 58l3.8 17H45.3zm21.8 0-3.7-16.7 18.1 1.4 1.4 15.3H67.1zm18.8 0-1.4-15 17 1.3v13.7H85.9zm31.2-15.6v15.6h-12.5V61.5l12.5 1v-3.2l-12.5-1V44.4l12.5 2.4v12.5zM35.9 31.2l65.6 12.6V58l-17.3-1.4-1.5-16.4-3.1-.6 1.6 16.8-18.5-1.5-4.3-19.3-3.7-.7 4.4 19.7-18.5-1.5-4.7-21.9zm76.5 81.2c2.6 0 4.7 2.1 4.7 4.7s-2.1 4.7-4.7 4.7-4.7-2.1-4.7-4.7 2.1-4.7 4.7-4.7zm-71.8 0c2.6 0 4.7 2.1 4.7 4.7s-2.1 4.7-4.7 4.7-4.7-2.1-4.7-4.7 2.1-4.7 4.7-4.7z"
                    fill="currentColor" />
            </svg>
            <span v-if="cart.length"
                    class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center animate-ping-fast">
                    {{cart.reduce((total, item) => total + item.quantity, 0)}}
                </span>
            <!-- <span v-if="cart.length > 0"
                class="absolute top-0 right-0 bg-red-500 text-white text-3xl px-2 rounded-full">
                {{cart.reduce((total, item) => total + item.quantity, 0)}}
            </span> -->
        </div>

        <!-- Customer Form Modal -->
        <AuthCustomer :show="showCustomerForm" :customer="customer" @close="showCustomerForm = false"
            @submit="handleCustomerSubmit" />

        <!-- Order Status Alert -->
        <div v-if="showOrderStatusAlert"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4">Informasi</h2>
                <p class="text-gray-700 mb-6">Halaman Order Status belum memiliki data.</p>
                <div class="flex justify-end">
                    <button @click="closeOrderStatusAlert"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import ProductCard from '../components/ProductCard.vue';
import AuthCustomer from '../components/AuthCustomer.vue';

export default {
    components: { ProductCard, AuthCustomer },
    data() {
        return {
            products: [],
            cart: [],
            showCustomerForm: false,
            showOrderStatusAlert: false,
            customer: {
                name: '',
                phone: '',
            },
            loading: false,
            searchQuery: '',
        };
    },
    computed: {
        hasOrderStatus() {
            return !!localStorage.getItem('lastOrderId');
        },
    },
    methods: {
        async fetchProducts() {
            try {
                this.loading = true;
                const response = await axios.get('/api/products');
                this.products = response.data;
            } catch (error) {
                console.error('Gagal mengambil data produk:', error);
            } finally {
                this.loading = false;
            }
        },
        addToCart(product) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existing = cart.find(item => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            this.updateCart();
        },
        updateCart() {
            this.cart = JSON.parse(localStorage.getItem('cart')) || [];
        },
        handleCartClick() {
            const sessionId = sessionStorage.getItem('customer');
            if (!sessionId) {
                this.showCustomerForm = true;
            } else {
                this.$router.push('/cart');
            }
        },
        handleCartClickStatus() {
            const sessionId = sessionStorage.getItem('customer');
            const lastOrderId = localStorage.getItem('lastOrderId');
            if (!sessionId) {
                this.showCustomerForm = true;
            } else if (!lastOrderId) {
                this.showOrderStatusAlert = true;
            } else {
                this.$router.push({ name: 'order.status', params: { id: lastOrderId } });
            }
        },
        closeOrderStatusAlert() {
            this.showOrderStatusAlert = false;
        },
        handleCustomerSubmit(customerData) {
            sessionStorage.setItem('customer', JSON.stringify(customerData));
            this.showCustomerForm = false;
            this.$router.push('/cart');
        },
        handleSearch() {
            if (this.searchQuery.trim() === '') {
                this.fetchProducts();
            } else {
                this.loading = true;
                axios
                    .get(`/api/products/search?q=${this.searchQuery}`)
                    .then(response => {
                        this.products = response.data;
                    })
                    .catch(error => {
                        console.error('Gagal mencari produk:', error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
    },
    mounted() {
        this.fetchProducts();
        this.updateCart();
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.updateCart();
        });
    },
};
</script>

<style scoped>
@keyframes ping-fast {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(1.3);
        opacity: 0.7;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.animate-ping-fast {
    animation: ping-fast 1s infinite;
}
</style>
