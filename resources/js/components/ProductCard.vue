<template>
    <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition duration-300 flex flex-col">
        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" />

        <div class="p-4 flex-1 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold lg:text-lg text-[2rem] mb-3">
                    {{ product.name }}
                </h3>
                <p class="text-blue-600 font-bold lg:text-base text-2xl">
                    Rp {{ product.price.toLocaleString('id-ID') }}
                </p>
            </div>
            <div class="mt-4 flex lg:flex-col flex-row gap-3">
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
        product: Object
    },
    methods: {
        lihatDetail(product) {
            alert(`Lihat detail: ${product.name}`);
        },
        addToCart() {
            this.$emit("add-to-cart", this.product);
        },
    }
};
</script>
