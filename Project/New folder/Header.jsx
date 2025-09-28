import React from 'react';
import { Navbar, Nav, Container, Badge, Button } from 'react-bootstrap'; 
import { FaShoppingCart, FaHeart } from 'react-icons/fa';
import { Link, useLocation} from 'react-router-dom'; 
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext'; 

function Header() {
  const location = useLocation();
  const { cart, favorites } = useCart();
  const { user } = useAuth(); 


  return (
    <Navbar bg="black" variant="dark" expand="lg">
      <Container>
        <Navbar.Brand as={Link} to="/" className="text-white fs-2 fw-bold">
          Online Store
        </Navbar.Brand>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mx-auto">
            {/* <Nav.Link as={Link} to="/" className={`fw-medium mx-2 ${location.pathname === '/' ? 'text-warning' : 'text-white'}`}>HOME</Nav.Link> */}
            
          </Nav>
          <Nav>
            <Nav.Link as={Link} to="/favorites" className="text-white fs-5 mx-2 position-relative">
              <FaHeart />
              {favorites.length > 0 && (
                <Badge pill bg="danger" style={{ position: 'absolute', top: '10', right: '0', transform: 'translate(25%, -25%)', fontSize: '10px', padding: '4px 6px' }}>
                  {favorites.length}
                </Badge>
              )}
            </Nav.Link>
            <Nav.Link as={Link} to="/cart" className="text-white fs-5 mx-2 position-relative">
              <FaShoppingCart />
              {cart.length > 0 && (
                <Badge pill bg="warning" text="dark" style={{ position: 'absolute', top: '10', right: '0', transform: 'translate(25%, -25%)', fontSize: '10px', padding: '4px 6px' }}>
                  {cart.length}
                </Badge>
              )}
            </Nav.Link>
            

            {user && (
              <>
                <Nav.Link as={Link} to="/profile"  className={`fs-5 mx-2 ${location.pathname === '/profile' ? 'text-warning' : 'text-white'}`}>
                  {user.name}
                </Nav.Link>
              </>
            )}
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
}

export default Header;