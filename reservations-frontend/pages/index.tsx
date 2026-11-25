import React from "react";
import Head from "next/head";
import { Container, Row, Col, Card } from "react-bootstrap";

const AboutPage: React.FC = () => (
  <>
    <Head>
      <title>Komenza - Todo comienza en la mesa</title>
      <meta name="description" content="Komenza: reservá tu mesa de manera fácil y rápida." />
    </Head>
    <Container className="min-vh-100 d-flex align-items-center">
      <Row className="justify-content-center w-100 mx-0">
        <Col md={8} lg={6}>
          <Card className="welcome-card p-3">
            <Card.Body>
              <img className="welcome-card__logo" src="/brand/a-isologo-color.svg" alt="Komenza" />
              <h1>Todo comienza en la mesa.</h1>
              <p>Con Komenza reservás tu mesa de manera sencilla y rápida.</p>
              <ul>
                <li>Elegí tu restaurante preferido.</li>
                <li>Elegí la sucursal y el horario que más te convenga.</li>
                <li>Completá tus datos y confirmá tu reserva.</li>
                <li>Vas a recibir una confirmación y vas a poder modificar o cancelar la reserva si lo necesitás.</li>
              </ul>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  </>
);

export default AboutPage;
