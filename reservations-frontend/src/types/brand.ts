export interface Brand {
  id: number;
  name: string;
  styles?: string;
  shortName: string;
  logo?: string;
  stores: any[];
  backgrounds: Array<{
      id: number;
      image: string;
  }>;
}
