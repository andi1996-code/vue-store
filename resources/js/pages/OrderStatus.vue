<template>
    <div class="min-h-screen flex flex-col items-center justify-center p-4 bg-gray-50">
        <div v-if="loading" class="flex flex-col items-center">
            <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-gray-600 text-sm">Memuat data pesanan...</p>
        </div>

        <div v-else-if="order" class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Status Pesanan</h1>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Nama Customer:</p>
                <p class="text-lg font-medium">{{ order.customer?.name }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Nomor HP:</p>
                <p class="text-lg font-medium">{{ order.customer?.phone }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Alamat Pengiriman:</p>
                <p class="text-lg font-medium">{{ order.address || 'Alamat tidak tersedia' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Metode Pembayaran:</p>
                <p class="text-lg font-medium capitalize">{{ order.payment_method }}</p>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 text-sm">Status:</p>
                <p :class="statusClass" class="text-lg font-bold">{{ order.status }}</p>
            </div>

            <button @click="backToHome"
                class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Beranda
            </button>
        </div>

        <div v-else class="text-gray-600">
            Data pesanan tidak ditemukan.
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "OrderStatus",
    data() {
        return {
            order: {
                address: null, // Pastikan address sudah ada
            },
            loading: true,
        };
    },
    computed: {
        statusClass() {
            if (!this.order?.status) return "";
            switch (this.order.status.toLowerCase()) {
                case "selesai":
                case "terima":
                    return "text-green-600 font-bold";
                case "tolak":
                    return "text-red-600 font-bold";
                case "menunggu":
                    return "text-orange-500 font-bold";
                default:
                    return "text-gray-600 font-bold";
            }
        },
    },
    methods: {
        async fetchOrder() {
            try {
                const response = await axios.get(`/api/orders/${this.$route.params.id}`);
                this.order = response.data;
            } catch (error) {
                console.error("Gagal memuat data pesanan:", error);
            } finally {
                this.loading = false;
            }
        },
        backToHome() {
            this.$router.push('/');
        },
    },
    created() {
        this.fetchOrder();

        const savedAddress = localStorage.getItem('address');
        if (savedAddress && !this.order?.address) {
            this.order.address = savedAddress;
        }
    },
    watch: {
        order(newOrder) {
            if (!newOrder.address) {
                const savedAddress = localStorage.getItem('address');
                if (savedAddress) {
                    this.order.address = savedAddress;
                }
            }
        }
    }
};
</script>

<style scoped>
/* Bisa tambah style custom disini kalau mau */
</style>
