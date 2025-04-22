<template>
    <div class="max-w-lg mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Test Ongkir</h1>
        <form @submit.prevent="checkOngkir" class="space-y-4">
            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Origin City ID:</label>
                <input type="text" v-model="origin" id="origin" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="destination" class="block text-sm font-medium text-gray-700">Destination City ID:</label>
                <input type="text" v-model="destination" id="destination" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (grams):</label>
                <input type="number" v-model="weight" id="weight" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            </div>
            <div>
                <label for="courier" class="block text-sm font-medium text-gray-700">Courier:</label>
                <select v-model="courier" id="courier" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="jne">JNE</option>
                    <option value="pos">POS</option>
                    <option value="tiki">TIKI</option>
                </select>
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 text-white font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Check Ongkir
            </button>
        </form>

        <div v-if="results" class="mt-6 p-4 bg-white rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Results:</h2>
            <ul class="space-y-3">
                <li v-for="(result, index) in results" :key="index"
                    class="p-3 bg-gray-50 rounded-md shadow-sm">
                    <p><strong>Service:</strong> {{ result.service }}</p>
                    <p><strong>Cost:</strong> {{ result.cost[0].value }}</p>
                    <p><strong>Estimated:</strong> {{ result.cost[0].etd }} days</p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            origin: "",
            destination: "",
            weight: "",
            courier: "jne",
            results: null,
        };
    },
    methods: {
        async checkOngkir() {
            try {
                const response = await axios.post("/api/shipping-cost", {
                    origin: this.origin,
                    destination: this.destination,
                    weight: this.weight,
                    courier: this.courier,
                });
                this.results = response.data.results;
            } catch (error) {
                console.error("Error fetching ongkir:", error);
                alert("Failed to fetch ongkir. Please try again.");
            }
        },
    },
};
</script>

<style scoped>
/* Tidak diperlukan styling tambahan karena sudah menggunakan Tailwind */
</style>
