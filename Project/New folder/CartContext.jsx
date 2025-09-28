import React, { createContext, useContext, useState } from "react";

const CartContext = createContext();

export function useCart() {
  return useContext(CartContext);
}

export function CartProvider({ children }) {
  const [cart, setCart] = useState([]);
  const [favorites, setFavorites] = useState([]);

  const addToCart = (product) => {
    setCart((prevCart) => [...prevCart, product]);
    console.log("Added to cart:", product);
  };

  const removeFromCart = (productId) => {
    setCart((prevCart) => prevCart.filter((item) => item.id !== productId));
  };

  const clearCart = () => {
    setCart([]);
  };

  const addToFavorites = (product) => {
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
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}
