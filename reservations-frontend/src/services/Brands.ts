import Base from './Base';

class BrandsService extends Base {
    getBrands(company?: string): Promise<any> {
        try {
            const params = company ? { company } : {};
            return this.get('brands', params)
                .then(resp => resp);
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
}

export default new BrandsService();
