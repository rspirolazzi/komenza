import Base from './Base';

class StoresService extends Base {
    getStores(): Promise<any> {
        try {
            return this.get('stores')
                .then(resp => resp);
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
}

export default new StoresService();
