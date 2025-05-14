<template>
  <div class="content-start transition admin-product-list">
    <div class="container-fluid">
      <div class="content-header">
        <h2>Manage Products</h2>
        <p></p>
        <router-link :to="{ name: 'AdminProductCreate' }" class="btn-add-product">
          Add New Product
        </router-link>

        <div v-if="loading" class="loading">Loading products...</div>
        <div v-if="error" class="error">{{ error }}</div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div v-if="!loading && !error && products.length > 0" class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in products" :key="product.id">
                      <td>
                        <img :src="product.image_url || 'https://via.placeholder.com/50'" alt="product.name"
                          class="product-thumb" />
                      </td>
                      <td>{{ product.name }}</td>
                      <td>${{ product.price }}</td>
                      <td>
                        <router-link :to="{ name: 'AdminProductEdit', params: { id: product.id } }" class="btn-edit">
                          Edit
                        </router-link>
                        <button @click="deleteProduct(product.id)" class="btn-delete">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div v-if="!loading && !error && products.length === 0">
            No products found.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/axiosAdmin';

const products = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchProducts = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await apiClient.get('/products');
    products.value = response.data;
  } catch (err) {
    console.error("Error fetching admin products:", err);
    error.value = 'Failed to load products.';
  } finally {
    loading.value = false;
  }
};

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) {
    return;
  }
  try {
    await apiClient.delete(`/products/${id}`);
    products.value = products.value.filter(p => p.id !== id);
    alert('Product deleted successfully!');
  } catch (err) {
    console.error("Error deleting product:", err);
    alert('Failed to delete product.');
  }
};

onMounted(fetchProducts);
</script>

<style scoped>
.admin-product-list {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.btn-add-product {
  display: inline-block;
  margin-bottom: 20px;
  padding: 10px 15px;
  background-color: #28a745;
  color: white;
  text-decoration: none;
  border-radius: 5px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

/* th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  color:#7c8db5;
}
th {
  background-color: #eee;
} */
.product-thumb {
  width: 50px;
  height: 50px;
  object-fit: cover;
}

.btn-edit,
.btn-delete {
  padding: 5px 10px;
  margin-right: 5px;
  border-radius: 3px;
  cursor: pointer;
  text-decoration: none;
  font-size: 1em;
}

.btn-edit {
  background-color: #ffc107;
  color: black;
}

.btn-delete {
  background-color: #dc3545;
  color: white;
  border: none;
}

.error {
  color: red;
}

.loading {
  color: #7c8db5;
  text-align: center;
  font-size: 1.2em;
  padding: 20px;
}
</style>