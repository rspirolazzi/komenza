import { BaseService } from './BaseService';

class StoresService extends BaseService {
  async getStores() {
    return this.get('stores').then(resp => resp.data);
  }
}

export default new StoresService();
