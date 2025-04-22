import axios from 'axios';

// Buat instance axios dengan konfigurasi default
const apiClient = axios.create({
    baseURL: 'http://localhost:8000/api', // Ganti dengan base URL API Anda
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

// Tambahkan interceptor jika diperlukan (opsional)
apiClient.interceptors.request.use(
    (config) => {
        // Tambahkan token atau konfigurasi lain jika diperlukan
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default apiClient;
