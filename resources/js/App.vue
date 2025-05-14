<template>
  <div style="display: flex;">
    <div v-if="isAdmin" class="navcomp">
      <div class="sidebar transition overlay-scrollbars animate__animated  animate__slideInLeft" id="main-content">
        <div class="sidebar-content">
          <div id="sidebar">
            <div class="logo">
              <h2 class="mb-0">
                <!-- <img src="assets/images/logo.png"> -->
                Animate
              </h2>
            </div>
            <ul class="side-menu">
              <li>
                <router-link to="/" class=""><i class='bx bxs-dashboard icon'></i> Home</router-link>
              </li>
              <li class="divider" data-text="MANAGE">Manage</li>
              <li>
                <router-link to="/admin/products" class=""><i class='bx bx-shopping-bag icon'> </i>Admin
                  Products</router-link>
              </li>
            </ul>
            <div class="ads">
              <div class="wrapper">
                <div class="help-icon"><i class="fa fa-circle-question fa-3x"></i></div>
                <p>Need Help with <strong>Animate</strong>?</p>
                <a href="docs/" class="btn-upgrade">Documentation</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar-overlay"></div>
    </div>
    <div style="flex-grow: 1;">
      <div v-if="!isAdmin" id="main-content">
        <nav>
          <h2 class="mb-0" style="color: #fff;">Animate</h2>
          <router-link to="/">Home</router-link> |
          <router-link to="/cart"><i class="fa fa-shopping-cart size-icon-0.5"></i> <span
              class="badge bg-danger notif">{{
                cartItemCount }}</span></router-link>
          <!-- <router-link to="/admin/login">Admin Login</router-link> |
          <router-link v-if="isAdmin" to="/admin/products">Admin Products</router-link>
          <button v-if="isAdmin" @click="adminLogout">Admin Logout</button> -->
        </nav>
      </div>
      <div v-if="isAdmin" class="topbar transition">
        <div class="bars">
          <button type="button" class="btn transition" id="sidebar-toggle">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <div class="menu">
          <ul>
            <li class="nav-item dropdown">
              <button class="nav-link" v-if="isAdmin" @click="adminLogout"><i
                  class="fa fa-sign-out-alt  size-icon-1"></i>
                <span>Logout</span></button>
            </li>
          </ul>
        </div>
      </div>
      <router-view />
    </div>
  </div>

</template>

<script setup>
import { ref, computed, provide, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';

const cart = ref([]);
const isAdmin = ref(!!localStorage.getItem('admin_token'));
const router = useRouter();

onMounted(() => {
  const savedCart = localStorage.getItem('shoppingCart');
  if (savedCart) {
    cart.value = JSON.parse(savedCart);
  }
});

watch(cart, (newCart) => {
  localStorage.setItem('shoppingCart', JSON.stringify(newCart));
}, { deep: true });


const addItemToCart = (product) => {
  const existingItem = cart.value.find(item => item.id === product.id);
  if (existingItem) {
    existingItem.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }
};

const removeItemFromCart = (productId) => {
  cart.value = cart.value.filter(item => item.id !== productId);
};

const updateItemQuantity = (productId, quantity) => {
  const item = cart.value.find(item => item.id === productId);
  if (item) {
    item.quantity = Math.max(1, quantity);
  }
};

const clearCart = () => {
  cart.value = [];
};

const cartItemCount = computed(() => {
  return cart.value.reduce((total, item) => total + item.quantity, 0);
});

const AdminLogin = () => {
  isAdmin.value = true;
}

provide('cart', cart);
provide('addItemToCart', addItemToCart);
provide('removeItemFromCart', removeItemFromCart);
provide('updateItemQuantity', updateItemQuantity);
provide('clearCart', clearCart);
provide('AdminLogin', AdminLogin);
provide('isAdmin', isAdmin);

watch(() => localStorage.getItem('admin_token'), (newToken) => {
  isAdmin.value = !!newToken;
});


const adminLogout = () => {
  localStorage.removeItem('admin_token');

  isAdmin.value = false;
  router.push({ name: 'AdminLogin' });
};

</script>

<style>
nav {
  padding: 10px;
  background-color: #1B2B65;
  margin-bottom: 20px;
}

nav a,
nav button {
  margin-right: 10px;
  text-decoration: none;
  color: #ffffff;
}

nav button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  font: inherit;
}

nav a.router-link-exact-active {
  font-weight: bold;
  color: #007bff;
}

#sidebar .side-menu>li>a.router-link-exact-active,
#sidebar .side-menu>li>a.router-link-exact-active:hover {
  background: #1B2B65;
  color: #ffffff;
}
</style>