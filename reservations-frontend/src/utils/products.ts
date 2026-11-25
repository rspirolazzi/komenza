import _ from 'lodash';

export const transformProducts = (products: any[]): Record<string, any[]> => {
    return _.groupBy(products, 'family.id');
};

export const brandNameToClass = (name: string): string => {
    return _.kebabCase(name);
};

export const filterStoreByBrand = (stores: any[], brandId: string | number): any[] => {
    let result: any[] = [];
    if (stores && stores.length > 0) {
        result = stores.filter(store => store.brand_id == brandId);
    }
    return result;
};
