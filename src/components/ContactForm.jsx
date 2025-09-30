import React from 'react';
import { Form, Button, Row, Col } from 'react-bootstrap';

function ContactForm() {
  return (
    <Form>
      <Row className="mb-3">
        <Form.Group as={Col} md="6" controlId="formFirstName">
          <Form.Label className="text-white fw-medium">First Name</Form.Label>
          <Form.Control type="text" />
        </Form.Group>
        <Form.Group as={Col} md="6" controlId="formLastName" className="mt-3 mt-md-0">
          <Form.Label className="text-white fw-medium">Last Name</Form.Label>
          <Form.Control type="text" />
        </Form.Group>
      </Row>

      <Form.Group className="mb-3" controlId="formEmail">
        <Form.Label className="text-white fw-medium">Email</Form.Label>
        <Form.Control type="email" />
      </Form.Group>

      <Form.Group className="mb-3" controlId="formSubject">
        <Form.Label className="text-white fw-medium">Subject</Form.Label>
        <Form.Control type="text" />
      </Form.Group>

      <Form.Group className="mb-4" controlId="formMessage">
        <Form.Label className="text-white fw-medium">Message</Form.Label>
        <Form.Control
          as="textarea"
          rows={7}
          placeholder="Write your notes or questions here..."
        />
      </Form.Group>

      
      <Button variant="success" type="submit" className="py-2 px-4 fw-bold">
        Send Message
      </Button>
    </Form>
  );
}

export default ContactForm;