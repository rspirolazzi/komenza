import { BaseService } from './BaseService';

class BrandsService extends BaseService {
  async getBrands() {
    return this.get('brands').then(resp => resp.data);
  }
}

export default new BrandsService();
