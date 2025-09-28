import React from 'react';
import { useCart } from '../context/CartContext';
import { Container, Row, Col, Card, ListGroup, Button, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';

function Cart() {
  // Get the new functions from the context
  const { cart, removeFromCart, clearCart, increaseQuantity, decreaseQuantity } = useCart();

  // Update total calculation to account for quantity
  const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

  return (
    <>
      <Container className="my-5" style={{ minHeight: '407px' }}>
        <h2 className="mb-4">Your Shopping Cart</h2>
        {cart.length === 0 ? (
          <Alert variant="info">
            Your cart is empty. <Link to="/">Go Shopping</Link>
          </Alert>
        ) : (
          <Row>
            <Col md={8}>
              <ListGroup>
                {cart.map((item) => (
                  <ListGroup.Item key={item.id} className="d-flex justify-content-between align-items-center">
                    <img src={item.image} alt={item.title} style={{ width: '50px', marginRight: '15px' }} />
                    <div className="flex-grow-1">
                      {item.title}
                      <br />
                      <small className="text-muted">${item.price.toFixed(2)}</small>
                    </div>
                    
                    {/* Quantity Controls */}
                    <div className="d-flex align-items-center">
                      <Button variant="outline-secondary" size="sm" onClick={() => decreaseQuantity(item.id)}>-</Button>
                      <span className="mx-2">{item.quantity}</span>
                      <Button variant="outline-secondary" size="sm" onClick={() => increaseQuantity(item.id)}>+</Button>
                    </div>

                    <Button variant="danger" size="sm" onClick={() => removeFromCart(item.id)} className="ms-3">
                      Remove
                    </Button>
                  </ListGroup.Item>
                ))}
              </ListGroup>
            </Col>
            <Col md={4}>
              <Card>
                <Card.Body>
                  <Card.Title>Order Summary</Card.Title>
                  <ListGroup variant="flush">
                    <ListGroup.Item className="d-flex justify-content-between">
                      <strong>Total</strong>
                      <strong>${total.toFixed(2)}</strong>
                    </ListGroup.Item>
                  </ListGroup>
                  <div className="d-grid gap-2 mt-3">
                    <Link to="/checkout" className="btn btn-warning" onClick={clearCart}>Proceed to Checkout</Link>
                    <Button variant="outline-secondary" onClick={clearCart}>Clear Cart</Button>
                  </div>
                </Card.Body>
              </Card>
            </Col>
          </Row>
        )}
      </Container>
    </>
  );
}

export default Cart;