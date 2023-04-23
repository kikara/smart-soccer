import * as events from "events";

export default function AudioEvent() {

    return {

        /**
         * Array of gamers|users id
         * @type [{id: Number, random: Array, event: Object}, {}]
         */
        gamers: [],

        /**
         * Flag for gamers audios are fetched
         * @type {boolean}
         */
        readyState: false,

        reset() {
            this.gamers = [];
            this.readyState = false;
        },

        /**
         * Fetch user|gamer audio
         */
        async fetchGamersAudio() {
            for (const item of this.gamers) {
                await axios.get('/api/users/' + item.id + '/audios')
                    .then(response => {
                        item.audios = response.data.data;
                    });
            }

            for (const item of this.gamers) {
                await axios.get('/api/users/' + item.id + '/events/audios')
                    .then(response => {
                        item.events = response.data.data;
                    });
            }
        },

        handleEvent(event, state) {
            const callbacks = {
                booked: 'booked',
                started: 'gameStarted',
                new_round: 'onNewRound',
                goal: 'onGoal',
                game_over: 'onGameOver',
            };

            const callback = callbacks[event];

            if (callback) {
                this[callback](state);
            }
        },

        async booked(state) {

            for (const item of Object.values(state.round.gamers)) {
                this.gamers.push({id: item.user_id});
            }

            await this.fetchGamersAudio();

            this.readyState = true;
        },

        gameStarted(state) {
            if (state.current_round === 0) {
                this.play('/audio/round_1_fight.mp3');
            }
        },

        onGoal(state) {
            if (!this.readyState) {
                return;
            }

            const event = this.formEvent(state);

            const userSounds = this.gamers.find(gamer => event.user_id === gamer.id);

            if (this.findByEvent(state, userSounds.events)) {
                return;
            }

            if (userSounds.audios.length > 0) {
                const audio =  userSounds.audios[Math.floor(Math.random() * userSounds.audios.length)];
                this.play(audio.path);
                return;
            }

            this.play('/audio/goal.mp3');
        },

        onNewRound(state) {
            if (state.current_round === 1) {
                this.play('/audio/round_2_fight.mp3')
            }

            if (state.current_round === 2) {
                this.play('/audio/final_round_fight.mp3');
            }
        },

        onGameOver(state) {
            this.reset();
        },

        play(path) {
            (new Audio(path)).play();
        },

        findByEvent(state, userEventSounds) {
            const event = this.formEvent(state);



            for (const eventSound of userEventSounds) {
                if (this.isParamsMatch(event.parameters, eventSound.parameters)) {
                    this.play(eventSound.path);
                    return true;
                }
            }
            return false;
        },

        formEvent(state) {
            return {
                'event': 'goal',
                'user_id': state['events']['goal_scored'],
                'parameters': {
                    'continuity': state['events']['goal_count'],
                    'goal-count': state['events']['goal_scored_count'],
                    'opponent-score': state['events']['opponent_score'],
                }
            };
        },

        isParamsMatch(eventParam, userEventParameters) {
            for (const code of userEventParameters) {
                if (parseInt(eventParam[code.code]) !== parseInt(code.value)) {
                    return false;
                }
            }
            return true;
        },
    }
}
