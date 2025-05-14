import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api/admin',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'multipart/form-data'
        // 'Content-Type': 'application/json'
    }
});

// Interceptor untuk menambahkan token ke setiap request
apiClient.interceptors.request.use(config => {
    const token = localStorage.getItem('admin_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Interceptor untuk response (opsional, bisa untuk handle error 401 global)
apiClient.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response && error.response.status === 401) {
        // Token tidak valid atau expired
        localStorage.removeItem('admin_token');
        // Redirect ke halaman login
        window.location.href = '/admin/login'; 
    }
    return Promise.reject(error);
});

export default apiClient;