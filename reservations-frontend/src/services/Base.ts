import axios, { AxiosPromise } from "axios";

if (!process.env.NEXT_PUBLIC_API_URL)
    throw new Error('undefined API');

export const ACCESS_TOKEN = 'ACCESS_TOKEN_SESSION';

export default class Base {
    _URL: string;
    constructor() {
        if (!process.env.NEXT_PUBLIC_API_URL)
            throw new Error('undefined API');
        this._URL = process.env.NEXT_PUBLIC_API_URL;
    }

    postWithJWT(uri: string, params: Record<string, any> = {}): Promise<any> {
        const url = this._URL + uri;
        params.token = this.getJWT();
        return this.processResponse(axios.post(url, params));
    }
    putWithJWT(uri: string, params: Record<string, any> = {}): Promise<any> {
        const url = this._URL + uri;
        params.token = this.getJWT();
        return this.processResponse(axios.put(url, params));
    }
    getWithJWT(uri: string, params: Record<string, any> = {}): Promise<any> {
        const url = this._URL + uri;
        params.token = this.getJWT();
        return this.processResponse(axios.get(url, { params }));
    }
    post<T>(uri: string, params: Record<string, any> = {}): Promise<T> {
        const url = this._URL + uri;
        return this.processResponse<T>(axios.post(url, params));
    }
    get(uri: string, params: Record<string, any> = {}): Promise<any> {
        const url = this._URL + uri;
        return this.processResponse(axios.get(url, { params }));
    }
    processResponse<T>(axiosPromise: AxiosPromise): Promise<T> {
        return axiosPromise.then(resp => {
            if (resp.status === 200 && resp.data && resp.data.success === true) {
                return resp.data;
            } else {
                throw resp.data;
            }
        });
    }
    // Dummy implementations for JWT methods
    getJWT(): string | null {
        if (typeof window !== 'undefined') {
            return window.sessionStorage.getItem(ACCESS_TOKEN);
        }
        return null;
    }
    setJWT(token: string): void {
        if (typeof window !== 'undefined') {
            window.sessionStorage.setItem(ACCESS_TOKEN, token);
        }
    }
    delJWT(): void {
        if (typeof window !== 'undefined') {
            window.sessionStorage.removeItem(ACCESS_TOKEN);
        }
    }
}
