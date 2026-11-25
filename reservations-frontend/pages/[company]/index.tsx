import React, { useEffect } from "react";
import Brands from "@/components/Brands";
import { useDispatch, useSelector } from "react-redux";
import { fetchBrands, selectBrand } from "@/store/general/thunks";
import { RootState } from "@/store/store";
import { useRouter } from "next/router";
import { Brand } from "@/types/brand";
import { brandNameToClass } from "@/utils/products";

const HomePage: React.FC = () => {
  const dispatch = useDispatch();
  const router = useRouter();
  const { company } = router.query as { company?: string };
  const brands = useSelector((state: RootState) => state.general?.brands ?? []);
  const isFetching = useSelector((state: RootState) => state.general?.isFetching ?? false);

  useEffect(() => {
    if(company){
      dispatch(fetchBrands(String(company)) as any);
    }
  }, [dispatch, company]);

  const handleSelect = (brand: Brand) => {
    dispatch(selectBrand(brand.id) as any);
    router.push(`/${company}/reserva/${brandNameToClass(brand.name)}`);
  };

  if (isFetching) return <div>Cargando marcas...</div>;

  return <Brands brands={brands} onSelect={handleSelect} />;
};

export default HomePage;
