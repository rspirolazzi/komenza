import React from "react";
import Congrats from "@/containers/Congrats";
import { useRouter } from "next/router";

const CongratsPage: React.FC = () => {
  const router = useRouter();
  const { token } = router.query;
  return <Congrats token={token} />;
};

export default CongratsPage;
