<template>
  <div class="col-md-6 col-lg-3">
    <div class="product-card card">
      <router-link :to="{ name: 'ProductDetail', params: { id: product.id } }">
        <img :src="product.image_url || 'https://via.placeholder.com/250'" :alt="product.name" class="product-image" />
        <h3 class="product-name">{{ product.name }}</h3>
        <p class="product-price">Rp {{ product.price }}</p>
      </router-link>
      <button @click="addToCart(product)" class="add-to-cart-btn">Add to Cart</button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, inject } from 'vue'; // inject untuk keranjang

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

// Inject fungsi addToCart dari App.vue atau store Pinia
const addItemToCart = inject('addItemToCart');

const addToCart = (product) => {
  if (addItemToCart) {
    addItemToCart(product);
    alert(`${product.name} added to cart!`);
  } else {
    console.warn('addItemToCart function not provided via inject');
    // Fallback atau error handling jika fungsi tidak di-provide
  }
};
</script>

<style scoped>
.product-card {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: center;
  border-radius: 8px;
}

.product-image {
  max-width: 100%;
  height: 300px;
  object-fit: cover;
  margin-bottom: 10px;
}

.product-name {
  font-size: 1.1em;
  margin: 10px 0;
}

.product-price {
  color: #333;
  font-weight: bold;
}

.add-to-cart-btn {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 5px;
  margin-top: 10px;
  font-size: 1.1em;
}

.add-to-cart-btn:hover {
  background-color: #218838;
}
</style>