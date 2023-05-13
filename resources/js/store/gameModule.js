export const gameModule = {
    state: () => ({
        gamers: [],
    }),
    mutations: {
        pushGamer(state, gamer) {
            state.gamers.push(gamer);
        },
        reset(state) {
            state.gamers = [];
        },
        update(state, gamerOptions) {
            state.gamers = state.gamers.map(gamer => {

                if (gamer.id === gamerOptions.id) {

                    for (const [option, value] of Object.entries(gamerOptions.options)) {
                        gamer[option] = value;
                    }
                }

                return gamer;
            });
        },
    },
};
