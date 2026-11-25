import Base from './Base';

class UserService extends Base {
    login({ email, password }: { email: string; password: string }): Promise<any> {
        try {
            const thiz = this;
            return this.post('login', { email, password })
                .then(resp => {
                    thiz.setJWT(resp.data.token);
                    return resp;
                });
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
    getUser(): Promise<any> {
        try {
            return this.getWithJWT('user')
                .then(resp => resp.data);
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
    logout(): Promise<any> {
        try {
            const thiz = this;
            return this.getWithJWT('logout')
                .then(resp => {
                    thiz.delJWT();
                    return resp;
                });
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
}

export default new UserService();
