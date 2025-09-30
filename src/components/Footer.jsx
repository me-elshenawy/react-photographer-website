import React from 'react';
import { Container } from 'react-bootstrap';

function Footer() {
  return (
    <footer className="bg-black py-4">
      <Container className="text-center">
        
        <p className="m-0 text-secondary">Copyright ©2025 All rights reserved | This template is made By
          <a href="#" target="_blank" rel="noopener noreferrer" className="text-success fw-medium text-decoration-none"> Mahmoud Elshenawy.</a>
        </p>
      </Container>
    </footer>
  );
}

export default Footer;