import React from 'react';
import { Image, Nav } from 'react-bootstrap';
import { FaTwitter, FaInstagram, FaFacebookF } from 'react-icons/fa';

function TeamMember({ imageSrc, description }) {
  return (
    <div>
      <Image src={imageSrc} roundedCircle fluid className="mb-4" style={{ maxWidth: '150px' }} />
      <p className="text-white-50 px-3">
        {description}
      </p>
      <Nav className="justify-content-center">
        <Nav.Link href="#" className="text-success fs-5 mx-1"><FaTwitter /></Nav.Link>
        <Nav.Link href="#" className="text-success fs-5 mx-1"><FaInstagram /></Nav.Link>
        <Nav.Link href="#" className="text-success fs-5 mx-1"><FaFacebookF /></Nav.Link>
      </Nav>
    </div>
  );
}

export default TeamMember;