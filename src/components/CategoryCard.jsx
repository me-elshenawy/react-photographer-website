import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from 'react-bootstrap';
import '../css/CategoryCard.css'; 

function CategoryCard({ title, imageUrl, linkUrl }) {
  const cardStyle = {
    backgroundImage: `url(${imageUrl})`,
  };

  return (
    <div className="category-card-container">
      <Link to={linkUrl} className="category-card" style={cardStyle}>
        <div className="card-overlay">
          <div className="card-content text-center">
            <h2 className="text-white fw-bold">{title}</h2>
            <Button variant="outline-light" size="sm">MORE PHOTOS</Button>
          </div>
        </div>
      </Link>
    </div>
  );
}

export default CategoryCard;