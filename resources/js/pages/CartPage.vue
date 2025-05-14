<template>
  <div class="content-start transition cart-page">
    <div class="container-fluid">
      <div class="content-header">
        <h1>Your Shopping Cart</h1>
        <p></p>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12">
          <div class="card">
            <div v-if="cartItems.length === 0" class="empty-cart">
              Your cart is empty. <router-link to="/">Go shopping!</router-link>
            </div>
            <div v-else>
              <div v-for="item in cartItems" :key="item.id" class="cart-item">
                <img :src="item.image_url || 'https://via.placeholder.com/100'" :alt="item.name" class="item-image" />
                <div class="item-details">
                  <h3>{{ item.name }}</h3>
                  <p>Price: Rp {{ item.price }}</p>
                  <div class="quantity-controls">
                    Quantity:
                    <input type="number" :value="item.quantity"
                      @input="updateQuantity(item.id, parseInt($event.target.value))" min="1">
                  </div>
                  <p>Subtotal: Rp {{ (item.price * item.quantity).toFixed(2) }}</p>
                </div>
                <button @click="removeFromCart(item.id)" class="remove-btn">Remove</button>
              </div>
              <div class="cart-summary">
                <h2>Total: Rp {{ totalPrice.toFixed(2) }}</h2>
                <button @click="simulateCheckout" class="checkout-btn">Simulate Checkout</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, inject } from 'vue';

// Inject state dan fungsi keranjang
const cartItems = inject('cart'); // Ini adalah ref, jadi akses .value jika perlu
const removeItemFromCart = inject('removeItemFromCart');
const updateItemQuantity = inject('updateItemQuantity');
const clearCart = inject('clearCart');

const totalPrice = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
});

const updateQuantity = (id, quantity) => {
  updateItemQuantity(id, quantity);
};

const removeFromCart = (id) => {
  removeItemFromCart(id);
};

const simulateCheckout = () => {
  if (cartItems.value.length === 0) {
    alert("Your cart is empty.");
    return;
  }
  // Di sini Anda bisa mengirim data keranjang ke backend untuk proses order
  console.log("Simulating checkout with items:", cartItems.value);
  alert(`Checkout successful! Total: $${totalPrice.value.toFixed(2)}. Your cart will be cleared.`);
  clearCart();
  // Idealnya redirect ke halaman konfirmasi order
};
</script>

<style scoped>
.cart-page {
  padding: 20px;
  /* max-width: 800px;
  margin: 0 auto; */
}

.empty-cart {
  color: #7c8db5;
  text-align: center;
  font-size: 1.2em;
  padding: 20px;
}

.cart-item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #7c8db5;
  padding: 15px 0;
  gap: 15px;
  color: #7c8db5;
}

.item-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 4px;
}

.item-details {
  flex-grow: 1;
}

.item-details h3 {
  margin: 0 0 5px 0;
}

.quantity-controls input {
  width: 50px;
  text-align: center;
  margin-left: 5px;
}

.remove-btn {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  border-radius: 4px;
}

.cart-summary {
  margin-top: 20px;
  text-align: right;
}

.cart-summary h2 {
  margin-bottom: 15px;
}

.checkout-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1.1em;
  cursor: pointer;
  border-radius: 5px;
}
</style>