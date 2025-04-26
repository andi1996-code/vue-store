<template>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <button @click="$router.push('/cart')"
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 transition"
                aria-label="Kembali ke keranjang">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h1 class="text-2xl md:text-3xl font-bold ml-4 flex-1">Pembayaran & Pengiriman</h1>
        </div>

        <!-- Data Customer -->
        <div class="bg-white shadow-md p-6 rounded-lg mb-6 border border-gray-200">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Data Customer</h2>
            <div class="space-y-2">
                <p class="text-gray-600 text-lg">
                    <strong>Nama: </strong>
                    <span>{{ customer.name }}</span>
                </p>
                <p class="text-gray-600 text-lg">
                    <strong>Nomor HP: </strong>
                    <span>{{ customer.phone }}</span>
                </p>
            </div>
        </div>

        <!-- Form Pengiriman -->
        <form @submit.prevent="handleSubmit">
            <div class="bg-white shadow-md p-6 rounded-lg mb-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Alamat Pengiriman</h2>

                <!-- Alamat Lengkap -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-600 font-medium mb-2">Harap Alamat Lengkap</label>
                    <textarea id="address" v-model="address"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kec. Kota Baru" rows="4"
                        required></textarea>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="bg-white shadow-md p-6 rounded-lg mb-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Metode Pembayaran</h2>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" v-model="paymentMethod" value="transfer" class="mr-2">
                        Transfer Bank
                    </label>
                    <label class="flex items-center">
                        <input type="radio" v-model="paymentMethod" value="cod" class="mr-2">
                        Cash on Delivery (COD)
                    </label>
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="bg-white shadow-md p-6 rounded-lg mb-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Detail Produk</h2>
                <div class="divide-y">
                    <div v-for="item in cartItems" :key="item.id" class="py-4 first:pt-0 last:pb-0">
                        <h3 class="font-medium text-gray-800">{{ item.name }}</h3>
                        <div class="flex justify-between mt-1 text-gray-600">
                            <span>{{ item.quantity }} × Rp {{ formatCurrency(item.price) }}</span>
                            <span>Rp {{ formatCurrency(item.price * item.quantity) }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between font-medium text-gray-700">
                        <span>Subtotal:</span>
                        <span>Rp {{ formatCurrency(subtotal) }}</span>
                    </div>
                    <div class="flex justify-between mt-4 text-lg font-bold text-gray-900">
                        <span>Total Pembayaran:</span>
                        <span>Rp {{ formatCurrency(subtotal) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="text-right">
                <button type="submit" :disabled="isSubmitting"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md disabled:bg-blue-400 disabled:cursor-not-allowed">
                    <span v-if="!isSubmitting">Lanjutkan Pembayaran</span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '../axios';

const router = useRouter()

// Data customer
const customer = ref({
    name: '',
    phone: ''
})

// Form data
const address = ref('');
const paymentMethod = ref('transfer'); // Default ke transfer
const cartItems = ref([]);
const isSubmitting = ref(false);

// Computed properties
const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID').format(value)
}

// Handle form submission
const handleSubmit = async () => {
    if (!address.value.trim()) {
        alert('Harap isi alamat lengkap pengiriman');
        return;
    }

    // Simpan alamat ke localStorage
    localStorage.setItem('address', address.value);


    // Lanjutkan proses pembayaran...
    if (!paymentMethod.value) {
        alert('Harap pilih metode pembayaran');
        return;
    }

    isSubmitting.value = true;

    try {
        // 1. Cari atau buat customer
        const customerRes = await apiClient.post('/customers/find-or-create', {
            name: customer.value.name,
            phone: customer.value.phone,
        });

        const customerId = customerRes.data.id;

        // 2. Buat order
        const orderData = {
            customer_id: customerId,
            address: address.value,
            payment_method: paymentMethod.value, // Tambahkan metode pembayaran
            items: cartItems.value.map(item => ({
                id: item.id,
                quantity: item.quantity,
            })),
        };

        const response = await apiClient.post('/orders', orderData);
        console.log('Response dari /orders:', response.data);

        // Ambil ID pesanan dari respons
        const orderId = response.data.order_id;

        localStorage.setItem('lastOrderId', orderId);
        localStorage.removeItem('cart');
        cartItems.value = [];
        // Redirect ke halaman OrderStatus dengan ID pesanan
        router.push({ name: 'order.status', params: { id: orderId } });
    } catch (error) {
        console.error('Gagal memproses pembayaran:', error.response?.data || error.message);
        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
    } finally {
        isSubmitting.value = false;
    }
};


// Initialize component
onMounted(async () => {
    const savedCustomer = JSON.parse(localStorage.getItem('customer'));

    if (savedCustomer?.phone) {
        try {
            // Ambil customer dari backend
            const { data } = await apiClient.get(`/customers/lookup?phone=${savedCustomer.phone}`);
            customer.value = data; // Harusnya berisi: { id, name, phone }
        } catch (error) {
            alert('Customer tidak ditemukan di database. Silakan isi ulang.');
            router.push('/checkout');
        }
    } else {
        alert('Data customer tidak ditemukan. Silakan isi data terlebih dahulu.');
        router.push('/checkout');
    }

    const savedCart = JSON.parse(localStorage.getItem('cart'));
    if (savedCart?.length) {
        cartItems.value = savedCart;
    } else {
        alert('Keranjang belanja kosong. Silakan tambahkan produk terlebih dahulu.');
        router.push('/products');
    }
});

</script>

<style scoped>
/* Custom styles */
.bg-blue-50 {
    background-color: #eff6ff;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .text-lg {
        font-size: 1rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-8 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
}

/* Animation for button loading */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
