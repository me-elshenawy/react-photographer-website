import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import axios from "axios"; 
import { useAuth } from "../context/AuthContext"; 
import Button from 'react-bootstrap/Button';
// import "../css/login.css";


export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const { login } = useAuth(); 

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!email || !password) {
      setError("All fields are required.");
      return;
    }

    try {
      const res = await axios.get(
        `http://localhost:3005/users?email=${email}&password=${password}`
      );
      
      if (res.data.length > 0) {
        setError("");
        login(res.data[0]); 
        navigate("/profile");
      } else {
        setError("Invalid email or password.");
      }
    } catch (err) {
      console.error("Login error:", err);
      setError("Failed to login. Please check the server.");
    }
  };

  return (
    <div className="form-container">
      <h2 className="form-title">Login</h2>
      <form onSubmit={handleSubmit} className="form-box">
        <input
          type="email"
          placeholder="Enter Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />

        <input
          type="password"
          placeholder="Enter Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />

        {error && <p className="error-text">{error}</p>}

        <Button type="submit" variant="primary">Login</Button>
        <p className="switch-text">
          Don’t have an account? <Link to="/register">Register</Link>
        </p>
      </form>
    </div>
  );
}