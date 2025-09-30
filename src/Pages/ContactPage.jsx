import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import ContactForm from '../components/ContactForm'; 
import ContactInfo from '../components/ContactInfo'; 

function ContactPage() {
  return (
    <Container className="py-5">
      <h1 className="text-center text-white display-4 fw-bold my-5">Contact</h1>
      <Row>
        <Col md={7} className="mb-5 mb-md-0">
          <ContactForm />
        </Col>
        <Col md={5}>
          <ContactInfo />
        </Col>
      </Row>
    </Container>
  );
}

export default ContactPage;