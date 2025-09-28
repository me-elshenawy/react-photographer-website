import React from 'react';
import { Container, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';

function Checkout() {
  return (
    <>
      
      <Container className="my-5 text-center" style={{ minHeight: '407px' }}>
        <Alert variant="success">
          <Alert.Heading>Order Confirmed!</Alert.Heading>
          <p>
            Thank you for your purchase. Your order will be shipped in 2-3 business days.
          </p>
          <hr />
          <Link to="/" className="btn btn-primary">Continue Shopping</Link>
        </Alert>
      </Container>
      
    </>
  );
}

export default Checkout;
