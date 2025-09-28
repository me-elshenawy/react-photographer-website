import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';


function Footer() {
  return (
    <footer className="bg-dark text-white pt-4 pb-3 mt-5">
      <Container >
        <Row className="text-center text-md-start"> 
          
          <Col md={4} className="mb-3 mb-md-0"> 
            <h5 className="text-uppercase mb-3 font-weight-bold text-warning">Online Store</h5>
            <p className="text-muted">
              Providing excellent services and solutions since 2020.
              We are committed to quality and customer satisfaction.
            </p>
          </Col>

          
          <Col md={4} className="mb-3 mb-md-0">
            <h5 className="text-uppercase mb-3 font-weight-bold text-warning">Quick Links</h5>
            <Nav className="flex-column">
              <Nav.Link href="#home" className="text-white py-1">Home</Nav.Link>
              <Nav.Link href="#about" className="text-white py-1">About Us</Nav.Link>
              <Nav.Link href="#services" className="text-white py-1">Services</Nav.Link>
              <Nav.Link href="#contact" className="text-white py-1">Contact</Nav.Link>
            </Nav>
          </Col>

          
          <Col md={4} className="mb-3 mb-md-0">
            <h5 className="text-uppercase mb-3 font-weight-bold text-warning">Connect With Us</h5>
            <p><i className="fas fa-envelope me-2"></i> info@onlinestore.com</p>
            <p><i className="fas fa-phone me-2"></i> +1 234 567 890</p>
          </Col>
        </Row>
      </Container>
    </footer>
  );
}

export default Footer;