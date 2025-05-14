<template>
  <div class="content-start transition product-detail-page">
    <div class="container-fluid">
      <div class="content-header">
        <div v-if="loading" class="loading">Loading product details...</div>
        <div v-if="error" class="error">{{ error }}</div>
        <p></p>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="card">
            <div v-if="product && !loading && !error" class="product-content">
              <img :src="product.image_url || 'https://via.placeholder.com/400'" :alt="product.name"
                class="product-image-large" />
              <div class="product-info">
                <h1>{{ product.name }}</h1>
                <p class="description">{{ product.description }}</p>
                <p class="price">Price: Rp {{ product.price }}</p>
                <button @click="addToCart(product)" class="add-to-cart-btn-large">Add to Cart</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps, inject } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const props = defineProps({
  id: { // Diterima dari route params
    type: [String, Number],
    required: true
  }
});

const product = ref(null);
const loading = ref(true);
const error = ref(null);
const route = useRoute(); // Alternatif jika tidak menggunakan props dari router

const addItemToCart = inject('addItemToCart');

const fetchProductDetail = async () => {
  try {
    // const productId = route.params.id; // Jika tidak pakai props
    const response = await axios.get(`/api/products/${props.id}`);
    product.value = response.data;
  } catch (err) {
    console.error("Error fetching product detail:", err);
    error.value = 'Failed to load product details.';
  } finally {
    loading.value = false;
  }
};

const addToCart = (product) => {
  if (addItemToCart) {
    addItemToCart(product);
    alert(`${product.name} added to cart!`);
  }
};

onMounted(fetchProductDetail);
</script>

<style scoped>
.product-detail-page {
  padding: 20px;
  /* max-width: 900px;
  margin: 0 auto; */
}

.product-content {
  display: flex;
  gap: 30px;
}

.product-image-large {
  max-width: 400px;
  height: auto;
  border: 1px solid #eee;
  border-radius: 8px;
}

.product-info {
  flex: 1;
}

.product-info h1 {
  margin-top: 0;
}

.description {
  margin: 15px 0;
  line-height: 1.6;
}

.price {
  font-size: 1.5em;
  font-weight: bold;
  color: #007bff;
  margin-bottom: 20px;
}

.add-to-cart-btn-large {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1.1em;
  cursor: pointer;
  border-radius: 5px;
}

.add-to-cart-btn-large:hover {
  background-color: #218838;
}

.error {
  color: red;
  text-align: center;
}

.loading {
  color: #7c8db5;
  text-align: center;
  font-size: 1.2em;
  padding: 20px;
}
</style>