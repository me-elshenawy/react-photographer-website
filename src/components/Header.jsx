import React from 'react';
import { Navbar, Nav, Container } from 'react-bootstrap';
import { FaFacebookF, FaTwitter, FaInstagram, FaYoutube } from 'react-icons/fa';
import { Link, useLocation } from 'react-router-dom';

function Header() {
  const location = useLocation(); 

  return (
    <Navbar bg="black" variant="dark" expand="lg" >
      <Container>
        <Navbar.Brand as={Link} to="/" className="text-white fs-2 fw-bold">
          Photosen
        </Navbar.Brand>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mx-auto">
            <Nav.Link as={Link} to="/" className={`fw-medium mx-2 ${location.pathname === '/' || location.pathname === '/home' ? 'text-success' : 'text-white'}`}>HOME</Nav.Link>
            <Nav.Link as={Link} to="/gallery" className={`fw-medium mx-2 ${location.pathname === '/gallery' ? 'text-success' : 'text-white'}`}>GALLERY</Nav.Link>
            <Nav.Link as={Link} to="/services" className={`fw-medium mx-2 ${location.pathname === '/services' ? 'text-success' : 'text-white'}`}>SERVICES</Nav.Link>
            <Nav.Link as={Link} to="/about" className={`fw-medium mx-2 ${location.pathname === '/about' ? 'text-success' : 'text-white'}`}>ABOUT</Nav.Link>
            <Nav.Link as={Link} to="/contact" className={`fw-medium mx-2 ${location.pathname === '/contact' ? 'text-success' : 'text-white'}`}>CONTACT</Nav.Link>
          </Nav>
          <Nav>
            <Nav.Link href="https://facebook.com" className="text-white fs-5 mx-1"><FaFacebookF /></Nav.Link>
            <Nav.Link href="https://twitter.com" className="text-white fs-5 mx-1"><FaTwitter /></Nav.Link>
            <Nav.Link href="https://instagram.com" className="text-white fs-5 mx-1"><FaInstagram /></Nav.Link>
            <Nav.Link href="https://youtube.com" className="text-white fs-5 mx-1"><FaYoutube /></Nav.Link>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
}

export default Header;