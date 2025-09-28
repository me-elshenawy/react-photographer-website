import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import Button from 'react-bootstrap/Button';
import axios from "axios"; 
import "../css/register.css";


export default function Register() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  const validate = () => {
    const e = {};
    if (!name.trim()) e.name = "Name is required.";
    if (!/^\S+@\S+\.\S+$/.test(email)) e.email = "Enter a valid email.";
    if (password.length < 6) e.password = "Password must be at least 6 characters.";
    if (password !== confirm) e.confirm = "Passwords do not match.";
    return e;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const validationErrors = validate();
    setErrors(validationErrors);

    if (Object.keys(validationErrors).length > 0) {
      return;
    }

    try {
      const res = await axios.get(`http://localhost:3005/users?email=${email}`);
      if (res.data.length > 0) {
        setErrors({ email: "This email is already registered." });
        return;
      }

      await axios.post("http://localhost:3005/users", {
        name,
        email,
        password, 
      });
      
      alert("Registered successfully! Please login.");
      navigate("/login");

    } catch (error) {
      console.error("Registration error:", error);
      setErrors({ form: "Something went wrong. Please try again." });
    }
  };

  return (
    <div className="form-container">
      <h2 className="form-title">Register</h2>
      {errors.form && <p className="error-text">{errors.form}</p>}
      <form onSubmit={handleSubmit} className="form-box">
        <input
          type="text"
          placeholder="Enter Name"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        {errors.name && <p className="error-text">{errors.name}</p>}

        <input
          type="email"
          placeholder="Enter Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        {errors.email && <p className="error-text">{errors.email}</p>}

        <input
          type="password"
          placeholder="Enter Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        {errors.password && <p className="error-text">{errors.password}</p>}

        <input
          type="password"
          placeholder="Confirm Password"
          value={confirm}
          onChange={(e) => setConfirm(e.target.value)}
        />
        {errors.confirm && <p className="error-text">{errors.confirm}</p>}

        <Button type="submit" variant="primary">Register</Button>
        <p className="switch-text">
          Already have an account? <Link to="/login">Login</Link>
        </p>
      </form>
    </div>
  );
}