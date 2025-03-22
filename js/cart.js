const cart = {
    addToCart: function (productId, name, price) {
      let cartData = JSON.parse(localStorage.getItem('cart')) || {};
  
      if (cartData[productId]) {
        cartData[productId].quantity++;
      } else {
        cartData[productId] = {
          name: name,
          price: price,
          quantity: 1
        };
      }
  
      localStorage.setItem('cart', JSON.stringify(cartData));
      this.updateCartCount();
    },
  
    updateCartCount: function () {
      let cartData = JSON.parse(localStorage.getItem('cart')) || {};
      let itemCount = 0;
  
      for (let productId in cartData) {
        itemCount += cartData[productId].quantity;
      }
  
      document.getElementById('cart-count').textContent = itemCount;
    }
  };
  
  document.addEventListener("DOMContentLoaded", function () {
    cart.updateCartCount();
  });
  