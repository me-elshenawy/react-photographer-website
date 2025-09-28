import React, { createContext, useContext, useState } from "react";

const CartContext = createContext();

export function useCart() {
  return useContext(CartContext);
}

export function CartProvider({ children }) {
  const [cart, setCart] = useState([]);
  const [favorites, setFavorites] = useState([]);

  const addToCart = (product) => {
    setCart((prevCart) => {
      const existingProduct = prevCart.find((item) => item.id === product.id);
      if (existingProduct) {
        // If product exists, map over the cart and update the quantity
        return prevCart.map((item) =>
          item.id === product.id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      } else {
        // If product does not exist, add it with quantity 1
        return [...prevCart, { ...product, quantity: 1 }];
      }
    });
  };

  const removeFromCart = (productId) => {
    setCart((prevCart) => prevCart.filter((item) => item.id !== productId));
  };

  const clearCart = () => {
    setCart([]);
  };

  // New function to increase quantity
  const increaseQuantity = (productId) => {
    setCart((prevCart) =>
      prevCart.map((item) =>
        item.id === productId ? { ...item, quantity: item.quantity + 1 } : item
      )
    );
  };

  // New function to decrease quantity
  const decreaseQuantity = (productId) => {
    setCart((prevCart) => {
      const existingProduct = prevCart.find((item) => item.id === productId);

      // If quantity is 1, decreasing it removes the item
      if (existingProduct && existingProduct.quantity === 1) {
        return prevCart.filter((item) => item.id !== productId);
      }
      
      // Otherwise, just decrease the quantity
      return prevCart.map((item) =>
        item.id === productId ? { ...item, quantity: item.quantity - 1 } : item
      );
    });
  };

  const addToFavorites = (product) => {
    // This logic is correct and already prevents duplicates. No changes needed here.
    if (!favorites.some((fav) => fav.id === product.id)) {
      setFavorites((prevFavorites) => [...prevFavorites, product]);
      console.log("Added to favorites:", product);
    }
  };

  const removeFromFavorites = (productId) => {
    setFavorites((prevFavorites) =>
      prevFavorites.filter((item) => item.id !== productId)
    );
  };

  const value = {
    cart,
    favorites,
    addToCart,
    removeFromCart,
    clearCart,
    addToFavorites,
    removeFromFavorites,
    increaseQuantity, // Expose the new function
    decreaseQuantity, // Expose the new function
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}