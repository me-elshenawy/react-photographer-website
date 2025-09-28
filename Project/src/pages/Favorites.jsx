import React from 'react';
import { useCart } from '../context/CartContext';
import { Container, Row, Col, Card, Button, Alert } from 'react-bootstrap';
import { Link } from 'react-router-dom';

function Favorites() {
  const { favorites, removeFromFavorites } = useCart();

  return (
    <>
      
      <Container className="my-5" style={{ minHeight: '407px' }}>
        <h2 className="mb-4">Your Favorites</h2>
        {favorites.length === 0 ? (
          <Alert variant="info">
            You have no favorite items yet. <Link to="/">Explore Products</Link>
          </Alert>
        ) : (
          <Row xs={1} md={2} lg={3} xl={4} className="g-4">
            {favorites.map((product) => (
              <Col key={product.id}>
                <Card className="h-100">
                  <Card.Img variant="top" src={product.image} style={{ height: '200px', objectFit: 'contain', padding: '1rem' }} />
                  <Card.Body>
                    <Card.Title style={{ fontSize: '1rem' }}>{product.title}</Card.Title>
                    <Card.Text as="h5">${product.price.toFixed(2)}</Card.Text>
                  </Card.Body>
                  <Card.Footer className="d-grid gap-2">
                    <Link to={`/products/${product.id}`} className="btn btn-outline-secondary btn-sm">Details</Link>
                    <Button variant="danger" size="sm" onClick={() => removeFromFavorites(product.id)}>
                      Remove
                    </Button>
                  </Card.Footer>
                </Card>
              </Col>
            ))}
          </Row>
        )}
      </Container>
      
    </>
  );
}

export default Favorites;
