import { createRouter, createWebHistory } from 'vue-router';
import Landing from '../pages/Landing.vue';
import Catalog from '../pages/Catalog.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Cart from '../pages/Cart.vue';
import PaymentForm from '../pages/PaymentForm.vue';
import TestOngkir from '../pages/TestOngkir.vue';

const routes = [
    { path: '/', component: Catalog },
    {
        path: '/katalog',
        name: 'katalog',
        component: Catalog
    },
    {
        path: '/produk/:id',
        name: 'product.detail',
        component: ProductDetail
    },
    {
        path: '/cart',
        name: 'cart',
        component: Cart
    },
    {
        path: '/pembayaran',
        name: 'payment',
        component: PaymentForm
    },
    {
        path: '/tes-ongkir',
        name: 'tes.ongkir',
        component: TestOngkir
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
