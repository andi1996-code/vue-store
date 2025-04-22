<template>
    <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition duration-300 flex flex-col">
        <!-- Gambar Produk -->
        <img :src="product.images[0]?.url || 'https://via.placeholder.com/150'" :alt="product.name" class="lg:w-full lg:h-full w-72 h-72 object-cover mx-auto my-4" />

        <!-- Informasi Produk -->
        <div class="p-4 flex-1 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold lg:text-lg text-[2rem] mb-3">
                    {{ (product.name || 'Nama Produk Tidak Tersedia').split(' ').slice(0, 5).join(' ') }}
                    <span v-if="product.name && product.name.split(' ').length > 5">...</span>
                </h3>
                <p class="text-blue-600 font-bold lg:text-base text-3xl">
                    Rp {{ product.price ? product.price.toLocaleString('id-ID') : '0' }}
                </p>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-4 flex flex-col gap-3">
                <router-link :to="`/produk/${product.id}`"
                    class="bg-green-500 hover:bg-green-600 text-white lg:text-base text-[1.5rem] px-4 py-2 rounded-md text-center w-full">
                    Lihat Detail
                </router-link>
                <button
                    class="bg-yellow-500 hover:bg-yellow-600 text-white lg:text-base text-[1.5rem] px-4 py-2 rounded-md w-full"
                    @click="addToCart">
                    + Keranjang
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    methods: {
        addToCart() {
            this.$emit("add-to-cart", this.product); // Emit event ke parent
        },
    },
};
</script>
