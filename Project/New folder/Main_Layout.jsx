import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, Outlet } from 'react-router-dom';
import { CartProvider } from '../context/CartContext';
import { AuthProvider, useAuth } from '../context/AuthContext'; 


import Home from '../pages/Home';
import ProductDetails from '../pages/ProductDetails';
import Cart from '../pages/Cart';
import Favorites from '../pages/Favorites';
import Checkout from '../pages/Checkout';
import Login from '../pages/Login';       
import Register from '../pages/Register'; 
import Profile from '../pages/Profile';   

import Header from '../components/Header';
import Footer from '../components/Footer';
import ScrollToTop from '../components/ScrollToTop';

const ProtectedLayout = () => {
  const { user } = useAuth();

  if (!user) {
    return <Navigate to="/login" />;
  }

  return (
    <div>
      <ScrollToTop />
      <Header />
      <Outlet /> 
      <Footer />
    </div>
  );
};


const PublicOnlyLayout = () => {
    const { user } = useAuth();
    return user ? <Navigate to="/" /> : <Outlet />;
}

function Main_Layout() {
  return (
    <Router>
      
      <AuthProvider>
        <CartProvider>
          <Routes>
            <Route element={<PublicOnlyLayout />}>
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
            </Route>

            <Route element={<ProtectedLayout />}>
              <Route path="/" element={<Home />} />
              <Route path="/products/:id" element={<ProductDetails />} />
              <Route path="/cart" element={<Cart />} />
              <Route path="/favorites" element={<Favorites />} />
              <Route path="/checkout" element={<Checkout />} />
              <Route path="/profile" element={<Profile />} />
            </Route>
            
          </Routes>
        </CartProvider>
      </AuthProvider>
    </Router>
  );
}

export default Main_Layout;