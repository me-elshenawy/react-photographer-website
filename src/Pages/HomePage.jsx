import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import CategoryCard from '../components/CategoryCard';



const categories = [
  { title: 'Nature', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_1.jpg.webp', linkUrl: '/gallery/nature' },
  { title: 'Portrait', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_2.jpg.webp', linkUrl: '/gallery' },
  { title: 'People', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_3.jpg.webp', linkUrl: '/gallery/people' },
  { title: 'Architecture', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_4.jpg.webp', linkUrl: '/gallery/architecture' },
  { title: 'Animals', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_5.jpg.webp', linkUrl: '/gallery/animals' },
  { title: 'Sports', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_6.jpg.webp', linkUrl: '/gallery/sports' },
  { title: 'Travel', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_7.jpg.webp', linkUrl: '/gallery/travel' },
  { title: 'People', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_3.jpg.webp', linkUrl: '/gallery/people' },
  { title: 'Architecture', imageUrl: 'https://preview.colorlib.com/theme/photosen/images/img_4.jpg.webp', linkUrl: '/gallery/architecture' },
];

function HomePage() {
  return (
    
    <Container fluid className="px-0">
      <Row xs={1} md={2} lg={3} className="g-0">
        {categories.map((category, index) => (
          <Col key={index} style={{ cursor: 'pointer' , overflow: 'hidden', position: 'relative',margin: '20px auto', padding: '0 20px'}}>
            <CategoryCard
              title={category.title}
              imageUrl={category.imageUrl}
              linkUrl={category.linkUrl}
            />
          </Col>
        ))}
      </Row>
    </Container>
  );
}

export default HomePage;