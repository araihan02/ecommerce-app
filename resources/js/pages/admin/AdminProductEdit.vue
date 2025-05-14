<template>
  <div class="content-start transition admin-product-edit">
    <div class="container-fluid">
      <div class="content-header">
        <h2>Edit Product</h2>
        <div v-if="loading" class="loading">Loading product data...</div>
        <div v-if="fetchError" class="error">{{ fetchError }}</div>
        <p></p>
      </div>
      <div class="row">
        <div class="col-md-6">
          <ProductForm v-if="!loading && !fetchError && productData" :initial-data="productData" :is-edit-mode="true"
            :is-submitting="submitting" :errors="formErrors" @submit="handleUpdateProduct" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import { useRouter } from 'vue-router';
import ProductForm from '@/components/admin/ProductForm.vue';
import apiClient from '@/axiosAdmin';

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
});

const router = useRouter();
const productData = ref(null);
const loading = ref(true);
const fetchError = ref(null);
const submitting = ref(false);
const formErrors = ref({});

const fetchProduct = async () => {
  loading.value = true;
  fetchError.value = null;
  try {
    const response = await apiClient.get(`/products/${props.id}`);
    productData.value = response.data;
  } catch (err) {
    console.error("Error fetching product for edit:", err);
    fetchError.value = 'Failed to load product data.';
  } finally {
    loading.value = false;
  }
};

const handleUpdateProduct = async (formDataPayload) => {
  submitting.value = true;
  formErrors.value = {};
  try {
    // apiClient akan mengirim _method=PUT di dalam FormData
    // Jadi kita tetap menggunakan .post() di sini untuk FormData
    await apiClient.post(`/products/${props.id}`, formDataPayload, {
      headers: {
        // Biarkan axios/browser yang handle Content-Type untuk FormData
      }
    });
    alert('Product updated successfully!');
    router.push({ name: 'AdminProductList' });
  } catch (err) {
    if (err.response && err.response.status === 422 && err.response.data.errors) {
      formErrors.value = err.response.data.errors;
    } else {
      console.error("Error updating product:", err);
      alert('Failed to update product. Check console for details.');
    }
  } finally {
    submitting.value = false;
  }
};

onMounted(fetchProduct);
</script>

<style scoped>
.admin-product-edit {
  padding: 20px;
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