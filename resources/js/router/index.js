import { createRouter, createWebHistory } from 'vue-router';
import Landing from '../pages/Landing.vue';
import Catalog from '../pages/Catalog.vue';
import ProductDetail from '../pages/ProductDetail.vue';
import Cart from '../pages/Cart.vue';

const routes = [
    { path: '/', component: Landing },
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
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
