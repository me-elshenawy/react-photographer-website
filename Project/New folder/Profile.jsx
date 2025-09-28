import React from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { Card, Button, Container, Row, Col } from "react-bootstrap";

export default function Profile() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  if (!user) {
    return <p>Loading user data...</p>;
  }

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  return (
    <Container className="mt-5" style={{ minHeight: "407px" }}>
      <Row className="justify-content-md-center">
        <Col md={6}>
          <Card>
            <Card.Header as="h2" className="text-center">User Profile</Card.Header>
            <Card.Body>
              <Card.Text>
                <strong>Name:</strong> {user.name}
              </Card.Text>
              <Card.Text>
                <strong>Email:</strong> {user.email}
              </Card.Text>
              <div className="d-grid gap-2">
                <Button variant="danger" onClick={handleLogout}>
                  Logout
                </Button>
              </div>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
}