import Home from "@/containers/Home";
import React from "react";
import BrandDynamicStyles from '@/components/BrandDynamicStyles';
import { useBrandFromRoute } from '@/hooks/useBrandFromRoute';

const HomePage: React.FC = () => {
  const { brand, notFound } = useBrandFromRoute();

  if (notFound) return <p role="alert">No encontramos el restaurante.</p>;
  if (!brand) return <p role="status">Cargando...</p>;

  return (
    <>
      <BrandDynamicStyles />
      <Home />
    </>
  );
};

export default HomePage;
