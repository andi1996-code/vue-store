<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Sebelum Belanja, yuk isi dulu.</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium">Nama</label>
                    <input id="name" v-model="customer.name" type="text"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium">Nomor Telepon</label>
                    <input id="phone" v-model="customer.phone" type="text"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @input="validatePhone" required />
                    <p v-if="customer.phone && !isPhoneValid" class="text-red-500 text-sm mt-1">
                        Nomor telepon harus diawali 0 dan terdiri dari 10–13 angka.
                    </p>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" @click="close"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!formIsValid">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import axios from 'axios'
import { defineProps, defineEmits } from 'vue'


const props = defineProps({
    show: Boolean,
    customer: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['close', 'submit'])

const validatePhone = () => {
    // Hapus semua karakter non-angka
    props.customer.phone = props.customer.phone.replace(/[^0-9]/g, '')
    // Pastikan tetap diawali 0
    if (props.customer.phone.length > 0 && props.customer.phone[0] !== '0') {
        props.customer.phone = '0' + props.customer.phone.slice(1)
    }
}

const isPhoneValid = computed(() => {
    return /^0\d{9,12}$/.test(props.customer.phone)
})

const formIsValid = computed(() => {
    return props.customer.name.trim() !== '' && isPhoneValid.value
})

const submit = async () => {
    if (!props.customer.name || !props.customer.phone) {
        alert('Harap isi data customer.');
        return;
    }

    if (props.customer.phone.length < 10 || props.customer.phone.length > 13) {
        alert('Nomor telepon harus terdiri dari 10-13 angka.');
        return;
    }

    // Simpan data customer ke LocalStorage
    localStorage.setItem('customer', JSON.stringify(props.customer));

    try {
        // Kirim data customer ke server
        const response = await axios.post('/api/customers', props.customer);

        // Pastikan respons berhasil
        if (response.status >= 200 && response.status < 300) {
            // Tampilkan animasi loading berputar
            const loadingSpinner = document.createElement('div');
            loadingSpinner.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="text-lg font-medium">Memproses...</span>
            </div>
            </div>
            `;
            document.body.appendChild(loadingSpinner);

            // Ganti ke ceklis setelah 2 detik
            setTimeout(() => {
            document.body.removeChild(loadingSpinner);

            const loadingCheck = document.createElement('div');
            loadingCheck.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-lg font-medium">Berhasil!</span>
                </div>
            </div>
            `;
            document.body.appendChild(loadingCheck);

            // Hapus loading ceklis setelah 2 detik
            setTimeout(() => {
                document.body.removeChild(loadingCheck);
            }, 2000);
            }, 2000);
        } else {
            console.error('Unexpected response:', response);
            // Hapus loading spinner jika ada
            const existingSpinner = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
            if (existingSpinner) {
                document.body.removeChild(existingSpinner);
            }

            // Tampilkan animasi X merah
            const loadingError = document.createElement('div');
            loadingError.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="text-lg font-medium">Gagal menyimpan data!</span>
                </div>
            </div>
            `;
            document.body.appendChild(loadingError);

            // Hapus animasi X merah setelah 2 detik
            setTimeout(() => {
                document.body.removeChild(loadingError);
            }, 2000);
        }
    } catch (error) {
        console.error('Error saat menyimpan data ke database:', error);
        alert('Terjadi kesalahan saat menyimpan data ke database.');
    }

    // Emit data customer setelah validasi
    emit('submit', props.customer);
};
const close = () => {
    emit('close')
}
</script>
