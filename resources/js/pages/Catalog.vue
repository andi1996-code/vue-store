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

        <!-- Pop-Up Alert -->
        <div v-if="showOrderStatusAlert"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4">Informasi</h2>
                <p class="text-gray-700 mb-6">Halaman OrderStatus belum memiliki data.</p>
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
import Header from '../components/Header.vue';

export default {
    components: { ProductCard, AuthCustomer, Header },
    data() {
        return {
            products: [], // Data produk akan diambil dari API
            cart: [], // Keranjang belanja
            showCustomerForm: false,
            showOrderStatusAlert: false, // Kontrol tampilan form customer
            customer: {
                name: '',
                phone: '',
            },
            showOrderStatusAlert: false, // Kontrol tampilan pop-up OrderStatus
        };
    },
    computed: {
        hasOrderStatus() {
            const lastOrderId = localStorage.getItem('lastOrderId');
            return !!lastOrderId; // true kalau ada order id
        }
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
        },
        handleCartClickStatus() {
            const sessionId = sessionStorage.getItem('customer'); // Cek session customer
            if (!sessionId) {
                this.showCustomerForm = true;
            } else {
                const lastOrderId = localStorage.getItem('lastOrderId'); // Ambil ID order terakhir
                if (!lastOrderId) {
                    this.showOrderStatusAlert = true; // Belum ada order
                } else {
                    this.$router.push({ name: 'order.status', params: { id: lastOrderId } }); // Redirect ke /order/:id
                }
            }
        },
        closeOrderStatusAlert() {
            this.showOrderStatusAlert = false;
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
    z-index: 10;
    /* Pastikan ikon cart tetap terlihat di atas elemen lain */
}

/* Styling untuk pop-up */
.fixed.inset-0 {
    z-index: 50;
    /* Pop-up memiliki z-index lebih tinggi dari elemen lain */
}

.bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}

.bg-white {
    background-color: #ffffff;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.shadow-lg {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

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
