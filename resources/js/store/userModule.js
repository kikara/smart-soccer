export const userModule = {
    state: () => ({
        is_auth: false,
        user: {
            id: 0,
            avatar_path: 0,
            login: ''
        }
    }),
    mutations: {
        setAuthenticatedUser(state, user) {
            state.user = user;
        },
        setAuthenticated(state) {
            state.is_auth = true;
        },
        setUserId(state, id) {
            state.user.id = id;
        },
    }
};
