import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '@/pages/HomePage.vue';
import ProductDetailPage from '@/pages/ProductDetailPage.vue';
import CartPage from '@/pages/CartPage.vue';
import AdminLoginPage from '@/pages/admin/AdminLoginPage.vue';
import AdminProductList from '@/pages/admin/AdminProductList.vue';
import AdminProductCreate from '@/pages/admin/AdminProductCreate.vue';
import AdminProductEdit from '@/pages/admin/AdminProductEdit.vue';

const routes = [
  { path: '/', name: 'Home', component: HomePage },
  { path: '/product/:id', name: 'ProductDetail', component: ProductDetailPage, props: true },
  { path: '/cart', name: 'Cart', component: CartPage },
  { path: '/admin/login', name: 'AdminLogin', component: AdminLoginPage },
  {
    path: '/admin/products',
    name: 'AdminProductList',
    component: AdminProductList,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/products/create',
    name: 'AdminProductCreate',
    component: AdminProductCreate,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/products/:id/edit',
    name: 'AdminProductEdit',
    component: AdminProductEdit,
    props: true,
    meta: { requiresAuth: true }
  },
 
  // { path: '/:pathMatch(.*)*', redirect: '/' } 
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAdminLoggedIn = !!localStorage.getItem('admin_token');
  if (to.matched.some(record => record.meta.requiresAuth) && !isAdminLoggedIn) {
    next({ name: 'AdminLogin' });
  } else {
    next();
  }
});

export default router;