import {createStore} from "vuex";
import {userModule} from "./userModule";
import {toastModule} from "./toastModule";
import {gameModule} from "./gameModule";

export default createStore({
    modules: {
        user: userModule,
        toast: toastModule,
        game: gameModule
    }
});
