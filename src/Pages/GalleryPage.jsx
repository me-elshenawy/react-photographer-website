import React, { useState } from 'react';
import { Container, Row, Col, Image } from 'react-bootstrap';
import Lightbox from 'yet-another-react-lightbox';
import 'yet-another-react-lightbox/styles.css';


const imageUrls = [
  'https://preview.colorlib.com/theme/photosen/images/nature_small_1.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_2.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_3.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_4.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_5.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_6.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_7.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_8.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_9.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_1.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_2.jpg',
  'https://preview.colorlib.com/theme/photosen/images/nature_small_3.jpg',
];


const slides = imageUrls.map(url => ({ src: url }));

function GalleryPage() {
  const [index, setIndex] = useState(-1);

  return (
    <Container fluid className="py-5 px-0">
      <h1 className="text-center text-white display-4 fw-bold my-5">Portrait Gallery</h1>

      <Row xs={1} sm={2} md={3} lg={4} className="g-0">
        {imageUrls.map((url, i) => (
          <Col key={i} style={{ cursor: 'pointer' , overflow: 'hidden', position: 'relative',margin: '10px auto', padding: '0 10px'}}>
            <Image
              src={url}
              alt={`Gallery image ${i + 1}`}
              fluid
              onClick={() => setIndex(i)}
              className="w-100 h-100"
              style={{ objectFit: 'cover' }}
            />
          </Col>
        ))}
      </Row>

      <Lightbox
        open={index >= 0}
        close={() => setIndex(-1)}
        index={index}
        slides={slides}
      />
    </Container>
  );
}

export default GalleryPage;