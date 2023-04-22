import {createStore} from "vuex";
import {userModule} from "./userModule";
import {toastModule} from "./toastModule";

export default createStore({
    modules: {
        user: userModule,
        toast: toastModule,
    }
});
