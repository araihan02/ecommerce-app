<template>
  <div class="content-start transition homepage">
    <div v-if="!isAdmin" class="container-fluid dashboard">
      <div class="content-header">
        <h1>Our Products</h1>
        <div v-if="loading" class="loading">Loading products...</div>
        <div v-if="error" class="error">{{ error }}</div>
        <div v-if="!loading && !error" class="product-grid"></div>
        <p></p>
      </div>
      <div class="row">
        <ProductCard v-for="product in products" :key="product.id" :product="product" />
      </div>
    </div>
    <div v-if="isAdmin" class="container-fluid dashboard">
      <div class="content-header">
        <h1>Welcome..</h1>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, inject } from 'vue';
import axios from 'axios';
import ProductCard from '@/components/ProductCard.vue';
const isAdmin = inject('isAdmin');

const products = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (err) {
    console.error("Error fetching products:", err);
    error.value = 'Failed to load products. Please try again later.';
  } finally {
    loading.value = false;
  }
};

onMounted(fetchProducts);
</script>

<style scoped>
.homepage {
  padding: 20px;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.error {
  color: red;
  text-align: center;
  font-size: 1.2em;
  margin-top: 20px;
}

.loading {
  color: #7c8db5;
  text-align: center;
  font-size: 1.2em;
  padding: 20px;
}
</style>