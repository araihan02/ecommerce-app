<template>
  <div class="admin-login-page">
    <h2>Admin Login</h2>
    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="credentials.email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="credentials.password" required>
      </div>
      <div v-if="error" class="error-message">{{ error }}</div>
      <button type="submit" :disabled="loading">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, inject } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
const AdminLogin = inject('AdminLogin');

const credentials = ref({
  email: '',
  password: '',
});
const loading = ref(false);
const error = ref(null);
const router = useRouter();

const handleLogin = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await axios.post('/api/admin/login', credentials.value);
    if (response.data.token) {
      localStorage.setItem('admin_token', response.data.token);
      AdminLogin()
      // Mungkin juga simpan data user jika perlu
      // localStorage.setItem('admin_user', JSON.stringify(response.data.user));
      // Trigger update isAdmin di App.vue (jika tidak pakai watch)
      // Atau redirect saja, nanti App.vue akan cek localStorage
      router.push({ name: 'AdminProductList' });
    } else {
      // Seharusnya tidak terjadi jika API mengembalikan token
      error.value = 'Login failed. No token received.';
    }
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      error.value = err.response.data.message;
    } else {
      error.value = 'An unexpected error occurred.';
    }
    console.error("Login error:", err);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.admin-login-page {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
  color: #7c8db5;
}

.error-message {
  color: red;
  margin-bottom: 10px;
}

button {
  padding: 10px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.1em;
}

button:disabled {
  background-color: #aaa;
}
</style>