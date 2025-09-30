import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import ServiceCard from '../components/ServiceCard'; 

import { BsCamera, BsPersonSquare, BsCameraVideo, BsEasel } from 'react-icons/bs';
import { FaPaw, FaPlane } from 'react-icons/fa';

const servicesData = [
  {
    icon: BsCamera,
    title: 'Camera',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '29',
  },
  {
    icon: BsEasel, 
    title: 'Wedding Photography',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '46',
  },
  {
    icon: FaPaw,
    title: 'Animal',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '24',
  },
  {
    icon: BsPersonSquare,
    title: 'Portrait',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '40',
  },
  {
    icon: FaPlane,
    title: 'Travel',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '35',
  },
  {
    icon: BsCameraVideo,
    title: 'Video Editing',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum exercitationem quae id dolorum debitis.',
    price: '15',
  },
];

function AboutPage() {
  return (
    <Container className="py-5 text-white">
      <h1 className="text-center display-4 fw-bold my-5">My Services</h1>

      <Row xs={1} md={2} lg={3} className="g-4">
        {servicesData.map((service, index) => (
          <Col key={index}>
            <ServiceCard
              icon={service.icon}
              title={service.title}
              description={service.description}
              price={service.price}
            />
          </Col>
        ))}
      </Row>
    </Container>
  );
}

export default AboutPage;