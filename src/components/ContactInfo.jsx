import React from 'react';

function ContactInfo() {
  return (
    <div>
      <div className="mb-4">
        <h5 className="text-white fw-bold">Address</h5>
        <p className="text-white">
          203 Fake St. Mountain View, San Francisco, California, USA
        </p>
      </div>
      <div className="mb-4">
        <h5 className="text-white fw-bold">Phone</h5>
        <p className="text-white">+1 232 3235 324</p>
      </div>
      <div>
        <h5 className="text-white fw-bold">Email Address</h5>
        
        <p className="text-success fw-medium">youremail@domain.com</p>
      </div>
    </div>
  );
}

export default ContactInfo;