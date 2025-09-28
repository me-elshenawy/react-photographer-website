import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import InputGroup from 'react-bootstrap/InputGroup';
import { MdFavoriteBorder } from "react-icons/md";
import { useCart } from '../context/CartContext'; 


const ProductCard = ({ product, maxLength = 100 }) => {
  const [showFullDescription, setShowFullDescription] = useState(false);

  const { addToCart, addToFavorites } = useCart();

  const isLongDescription = product.description.length > maxLength;

  const displayedDescription = showFullDescription
    ? product.description
    : product.description.slice(0, maxLength) + (isLongDescription ? '...' : '');

  const toggleShowMoreLess = () => {
    setShowFullDescription(!showFullDescription);
  };

  return (
    <Card style={{ width: '18rem', height: '100%' }}>
      <Card.Img variant="top" src={product.image} alt={product.title} style={{ width: '100%', height: '210px', objectFit: 'contain' }} className='p-3'/>
      <Card.Body className="d-flex flex-column">
        <Card.Title>{product.title}</Card.Title>
        <Card.Subtitle className="mb-2 text-muted">{product.category}</Card.Subtitle>
        <Card.Text className="flex-grow-1">
          {displayedDescription}
          {isLongDescription && (
            <span
              onClick={toggleShowMoreLess}
              style={{ color: 'blue', cursor: 'pointer', marginLeft: '5px' }}
            >
              {showFullDescription ? 'Show Less' : 'Show More'}
            </span>
          )}
        </Card.Text>
        <Card.Title>${product.price}</Card.Title>
        <div className="d-flex justify-content-between align-items-center">
            <div className="d-flex gap-2">
               
                <Button variant="warning" onClick={() => addToCart(product)}>Add to Cart</Button>
                <Link to={`/products/${product.id}`} className="btn btn-outline-secondary btn-sm">Details</Link>
              </div>
            <Button variant="link" onClick={() => addToFavorites(product)}><MdFavoriteBorder size={30} color="red"/></Button>
        </div>
      </Card.Body>
    </Card>
  );
};


function Products() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState('All');
  const [searchQuery, setSearchQuery] = useState('');
  const [minPrice, setMinPrice] = useState('');
  const [maxPrice, setMaxPrice] = useState('');

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await fetch('https://fakestoreapi.com/products');
        const data = await response.json();
        setProducts(data);

        const uniqueCategories = ['All', ...new Set(data.map(product => product.category))];
        setCategories(uniqueCategories);

      } catch (error) {
        console.error("Error fetching products:", error);
      }
    };
    fetchProducts();
  }, []);

  const handleCategoryChange = (category) => {
    setSelectedCategory(category);
  };

  const filteredProducts = products
    .filter(product => {
      if (selectedCategory !== 'All' && product.category !== selectedCategory) {
        return false;
      }
      if (searchQuery && !product.title.toLowerCase().includes(searchQuery.toLowerCase())) {
        return false;
      }
      const price = parseFloat(product.price);
      const min = parseFloat(minPrice);
      const max = parseFloat(maxPrice);
      if (!isNaN(min) && price < min) {
        return false;
      }
      if (!isNaN(max) && price > max) {
        return false;
      }
      return true;
    });

  return (
    <Container className="my-4">
      <Row className="mb-4">
        <Col md={6}>
          <Form.Control
            type="text"
            placeholder="Search for products..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
          />
        </Col>
        <Col md={3}>
          <InputGroup>
            <InputGroup.Text>Min Price</InputGroup.Text>
            <Form.Control
              type="number"
              placeholder="0"
              value={minPrice}
              onChange={(e) => setMinPrice(e.target.value)}
            />
          </InputGroup>
        </Col>
        <Col md={3}>
          <InputGroup>
            <InputGroup.Text>Max Price</InputGroup.Text>
            <Form.Control
              type="number"
              placeholder="Any"
              value={maxPrice}
              onChange={(e) => setMaxPrice(e.target.value)}
            />
          </InputGroup>
        </Col>
      </Row>

      <div className="d-flex justify-content-center mb-4 gap-2 flex-wrap">
        {categories.map(category => (
          <Button
            key={category}
            variant={selectedCategory === category ? 'primary' : 'outline-primary'}
            onClick={() => handleCategoryChange(category)}
          >
            {category}
          </Button>
        ))}
      </div>

      <Row xs={1} md={2} lg={3} xl={4} className="g-4 justify-content-center">
        {products.length === 0 ? (
          <Col><p>Loading products...</p></Col>
        ) : filteredProducts.length > 0 ? (
          filteredProducts.map(product => (
            <Col key={product.id} className="d-flex">
              <ProductCard product={product} maxLength={110} />
            </Col>
          ))
        ) : (
          <Col><p>No products found.</p></Col>
        )}
      </Row>
    </Container>
  );
}

export default Products;