export const toastModule = {
    state: () => ({
        visible: false,
        message: '',
    }),
    mutations: {
        setToast(state, message) {
            state.visible = true;
            state.message = message;
        },
        setDone(state) {
            state.visible = false;
            state.message = '';
        },
    }
};
