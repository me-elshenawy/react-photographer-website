import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { Container, Row, Col, Card, Button, Spinner, Alert } from 'react-bootstrap';
import { useCart } from '../context/CartContext'; 
import '../css/ProductDetails.css'; 

function ProductDetails() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { addToCart } = useCart(); 

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const response = await fetch(`https://fakestoreapi.com/products/${id}`);
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        setProduct(data);
      } catch (error) {
        setError("Failed to fetch product data. Please try again later.");
        console.error("Error fetching product:", error);
      } finally {
        setLoading(false);
      }
    };
    fetchProduct();
  }, [id]);

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ height: '80vh' }}>
        <Spinner animation="border" role="status">
          <span className="visually-hidden">Loading...</span>
        </Spinner>
      </div>
    );
  }

  if (error) {
    return (
      <Container className="my-5">
        <Alert variant="danger">{error}</Alert>
      </Container>
    );
  }

  if (!product) {
    return null;
  }

  return (
    <>
      <Container className="my-5">
        <Row>
          <Col lg={10} xl={9} className="mx-auto">
            <Card className="p-4 shadow-sm">
              <Row className="g-0">
                <Col md={6} className="d-flex justify-content-center align-items-center p-3">
                  <div className="product-image-container">
                    <Card.Img
                      className="product-detail-image"
                      src={product.image}
                      alt={product.title}
                    />
                  </div>
                </Col>
                <Col md={6} className="d-flex flex-column justify-content-center p-3">
                  <Card.Body>
                    <Card.Subtitle className="mb-2 text-muted text-capitalize">{product.category}</Card.Subtitle>
                    <Card.Title as="h1" className="mb-3">{product.title}</Card.Title>
                    <Card.Text className="lead mb-4">{product.description}</Card.Text>
                    <div className="d-flex align-items-center">
                      <h2 className="price-text me-4 mb-0">${product.price}</h2>
                      <Button variant="warning" size="lg" onClick={() => addToCart(product)}>Add to Cart</Button>
                    </div>
                  </Card.Body>
                </Col>
              </Row>
            </Card>
          </Col>
        </Row>
      </Container>
    </>
  );
}

export default ProductDetails;