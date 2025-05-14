<template>
  <div class="content-start transition admin-product-create">
    <div class="container-fluid">
      <div class="content-header">
        <h2>Create New Product</h2>
        <p></p>
      </div>
      <div class="row">
        <div class="col-md-6">
          <ProductForm :is-submitting="submitting" :errors="formErrors" @submit="handleCreateProduct" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import ProductForm from '@/components/admin/ProductForm.vue';
import apiClient from '@/axiosAdmin';

const router = useRouter();
const submitting = ref(false);
const formErrors = ref({});

const handleCreateProduct = async (formDataPayload) => {
  submitting.value = true;
  formErrors.value = {};
  console.log(formDataPayload.get('image'))
  try {
    await apiClient.post('/products', formDataPayload);
    alert('Product created successfully!');
    router.push({ name: 'AdminProductList' });
  } catch (err) {
    if (err.response && err.response.status === 422 && err.response.data.errors) {
      formErrors.value = err.response.data.errors;
    } else {
      console.error("Error creating product:", err);
      alert('Failed to create product. Check console for details.');
    }
  } finally {
    submitting.value = false;
  }
};
</script>

<style scoped>
.admin-product-create {
  padding: 20px;
}
</style>