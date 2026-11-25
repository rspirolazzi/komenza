import React from 'react';
import { Col, Container, Row } from 'react-bootstrap';
import { Brand } from '@/types/brand';
import Footer from '@/components/Footer';
import BrandCard from './BrandCard';

interface BrandsProps {
  brands: Brand[];
  onSelect: (brand: Brand) => void;
}

const Brands: React.FC<BrandsProps> = ({ brands, onSelect }) => (
  <div className="brand-page">
    <Container>
      <h1>¿Dónde querés reservar?</h1>
      <Row className="justify-content-center g-4" xs={1} sm={2} md={3} lg={4}>
        {brands.map((brand) => (
          <Col key={brand.id}>
            <BrandCard brand={brand} onSelect={onSelect} />
          </Col>
        ))}
      </Row>
    </Container>
    <Footer />
  </div>
);

export default Brands;
