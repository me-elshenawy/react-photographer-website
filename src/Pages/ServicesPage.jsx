import React from 'react';
import { Container, Row, Col, Image } from 'react-bootstrap';
import TeamMember from '../components/TeamMember';


function ServicesPage() {
  const placeholderImg = "https://preview.colorlib.com/theme/photosen/images/img_2.jpg";
  const placeholderPerson = "https://preview.colorlib.com/theme/photosen/images/person_4.jpg";

  return (
    <Container className="py-5 text-white">
      <h1 className="text-center display-4 fw-bold my-5">My Services</h1>

      <Row className="align-items-center mb-5 pb-5">
        <Col lg={7} className="mb-4 mb-lg-0">
          <Image src={placeholderImg} fluid />
        </Col>
        <Col lg={5}>
          <h2 className="fw-bold mb-4">My Mission</h2>
          <p className="text-white-50">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Possimus vel tenetur explicabo necessitatibus, ad soluta consectetur illo qui voluptatem. Quis soluta maiores eum. Iste modi voluptatum in repudiandae fugiat suscipit dolorem harum, porro voluptate quis? Alias repudiandae dicta doloremque voluptates soluta repellendus, unde laborum quo nam, ullam facere voluptatem similique.
          </p>
          <p className="text-white-50">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor architecto excepturi aliquid minus nostrum dicta labore iusto obcaecati fugit cupiditate.
          </p>
        </Col>
      </Row>

      <Row className="text-center">
        <Col md={4} className="mb-5 mb-md-0">
          <TeamMember
            imageSrc={placeholderPerson}
            description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur ab quas facilis obcaecati non ea, est odit repellat distinctio incidunt, quia aliquam eveniet quod deleniti impedit sapiente atque tenetur porro?"
          />
        </Col>
        <Col md={4} className="mb-5 mb-md-0">
          <TeamMember
            imageSrc={placeholderPerson}
            description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur ab quas facilis obcaecati non ea, est odit repellat distinctio incidunt, quia aliquam eveniet quod deleniti impedit sapiente atque tenetur porro?"
          />
        </Col>
        <Col md={4}>
          <TeamMember
            imageSrc={placeholderPerson}
            description="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur ab quas facilis obcaecati non ea, est odit repellat distinctio incidunt, quia aliquam eveniet quod deleniti impedit sapiente atque tenetur porro?"
          />
        </Col>
      </Row>
    </Container>
  );
}

export default ServicesPage;