<template>
  <form @submit.prevent="submitForm" enctype="multipart/form-data">
    <div class="form-group">
      <label for="name">Product Name:</label>
      <input type="text" id="name" v-model="formData.name" required>
      <div v-if="errors.name" class="error-text">{{ errors.name.join(', ') }}</div>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <textarea id="description" v-model="formData.description"></textarea>
      <div v-if="errors.description" class="error-text">{{ errors.description.join(', ') }}</div>
    </div>

    <div class="form-group">
      <label for="price">Price:</label>
      <input type="number" id="price" v-model.number="formData.price" step="0.01" required min="0">
      <div v-if="errors.price" class="error-text">{{ errors.price.join(', ') }}</div>
    </div>

    <div class="form-group">
      <label for="image">Product Image:</label>
      <input type="file" id="image" name="image" @change="handleImageUpload">
      <div v-if="formData.current_image_url" class="current-image-preview">
        <p>Current Image:</p>
        <img :src="formData.current_image_url" alt="Current product image" style="max-width: 150px; margin-top: 5px;">
        <label>
          <input type="checkbox" v-model="removeImageFlag"> Remove current image
        </label>
      </div>
      <div v-if="imagePreviewUrl" class="image-preview">
         <p>New Image Preview:</p>
        <img :src="imagePreviewUrl" alt="New image preview" style="max-width: 150px; margin-top: 5px;">
      </div>
      <div v-if="errors.image" class="error-text">{{ errors.image.join(', ') }}</div>
    </div>

    <button type="submit" :disabled="isSubmitting"  class="btn-submit">
      {{ isSubmitting ? 'Saving...' : (isEditMode ? 'Update Product' : 'Create Product') }}
    </button>
    <router-link :to="{ name: 'AdminProductList' }" class="btn-cancel">Cancel</router-link>
  </form>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits, onMounted } from 'vue';

const props = defineProps({
  initialData: {
    type: Object,
    default: () => ({ name: '', description: '', price: 0, image_url: null })
  },
  isEditMode: {
    type: Boolean,
    default: false
  },
  isSubmitting: {
    type: Boolean,
    default: false
  },
  errors: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['submit']);

const formData = ref({
  name: '',
  description: '',
  price: 0,
  image: null, // File object
  current_image_url: null // URL dari gambar yang sudah ada (untuk edit)
});
const imagePreviewUrl = ref(null);
const removeImageFlag = ref(false); // Untuk menandai penghapusan gambar saat edit

// Inisialisasi form data ketika props berubah atau komponen dimuat
const initializeFormData = (data) => {
  formData.value.name = data.name || '';
  formData.value.description = data.description || '';
  formData.value.price = data.price || 0;
  formData.value.image = null; // Reset file input
  formData.value.current_image_url = data.image_url || null;
  imagePreviewUrl.value = null;
  removeImageFlag.value = false;
};

onMounted(() => {
  initializeFormData(props.initialData);
});

watch(() => props.initialData, (newData) => {
  initializeFormData(newData);
}, { deep: true });


const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    formData.value.image = file;
    // Buat URL preview untuk gambar baru
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreviewUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    formData.value.image = null;
    imagePreviewUrl.value = null;
  }
};

const submitForm = () => {
  // Kita perlu mengirim FormData karena ada file upload
  const payload = new FormData();
  payload.append('name', formData.value.name);
  payload.append('description', formData.value.description);
  payload.append('price', formData.value.price);
  if (formData.value.image) {
      payload.append('image', formData.value.image);
    }
  // Untuk mode update, kita perlu mengirim _method PUT
  if (props.isEditMode) {
    payload.append('_method', 'PUT'); // Laravel akan handle ini untuk form-data
    if (removeImageFlag.value && !formData.value.image) { // Jika hapus dicentang dan tidak ada gambar baru
        payload.append('remove_image', '1');
    }
  }
  emit('submit', payload);
};
</script>

<style scoped>
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; }
.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea,
.form-group input[type="file"] {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
  color:#7c8db5;
}
.error-text { color: red; font-size: 0.9em; margin-top: 3px; }
.btn-cancel { margin-left: 10px; text-decoration: none; color: #555; }
.current-image-preview img, .image-preview img {
  display: block;
  max-width: 150px;
  margin-top: 10px;
  border: 1px solid #ddd;
  color:#7c8db5;
}
.btn-submit {
  display: inline-block;
  margin-bottom: 20px;
  padding: 10px 15px;
  background-color: #28a745;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-size: 1em;
}
</style>