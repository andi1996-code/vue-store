<template>
    <div class="min-h-screen bg-gray-100 p-4">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-4">Status Pesanan</h1>

            <div v-if="order" class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Nomor Pesanan:</span>
                    <span class="font-medium text-gray-800">{{ order.order_number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tanggal Pemesanan:</span>
                    <span class="font-medium text-gray-800">{{ order.order_date }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span :class="statusClass">{{ order.status }}</span>
                </div>
            </div>

            <div v-else class="text-center text-gray-600">
                Memuat data pesanan...
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "OrderStatus",
    data() {
        return {
            order: null, // Data pesanan
        };
    },
    computed: {
        statusClass() {
            // Tambahkan kelas berdasarkan status
            return this.order?.status === "Selesai"
                ? "font-medium text-green-600"
                : "font-medium text-yellow-600";
        },
    },
    methods: {
        async fetchOrder() {
            try {
                const response = await axios.get(`/api/orders/${this.$route.params.id}`);
                this.order = response.data;
            } catch (error) {
                console.error("Gagal memuat data pesanan:", error);
            }
        },
    },
    created() {
        this.fetchOrder(); // Ambil data saat komponen dibuat
    },
};
</script>

<style scoped>
/* Tambahkan styling tambahan jika diperlukan */
</style>
