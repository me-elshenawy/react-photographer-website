import React from 'react';
import { Card } from 'react-bootstrap';

function ServiceCard({ icon, title, description, price }) {
  const IconComponent = icon; 

  return (
    
    <Card className="text-center h-100 p-4" style={{ backgroundColor: '#212529', border: '1px solid #444' }}>
      <Card.Body>
        
        <div className="mb-4">
          <IconComponent className="text-success" size={50} />
        </div>
        
        <Card.Title as="h4" className="text-white fw-bold mb-3">{title}</Card.Title>
        
        <Card.Text className="text-white-50 mb-4">
          {description}
        </Card.Text>
        
        <p className="text-success fw-bold fs-4">${price}</p>
      </Card.Body>
    </Card>
  );
}

export default ServiceCard;