export default function EventListener() {

    const callbacks = [];

    return {
        currentState: null,

        previousState: null,

        subscribers: {},

        addListener(callback) {
            callbacks.push(callback);
        },

        handle(data) {
            this.currentState = data;

            this.onTableOccupied();
            this.gameStarted();
            this.onGoal();
            this.onNewRound();
            this.onGameOver();

            this.previousState = this.currentState;
        },

        stateChanged(property) {
            if (this.previousState === null) {
                return true;
            }
            return this.currentState[property] !== this.previousState[property];
        },

        onTableOccupied() {
            if (this.currentState['is_busy'] && this.stateChanged('is_busy')) {
                this.notify('booked');
            }
        },

        gameStarted() {
            if (this.currentState['game_started'] && this.stateChanged('game_started')) {
                this.notify('started');
            }
        },

        onGoal() {
            if (!this.currentState['game_started']) {
                return;
            }

            let isChanged = false;

            for (const [key, value] of Object.entries(this.currentState.round.gamers)) {

                let checkedScore = 0;
                if (this.previousState) {
                    checkedScore = Number(this.previousState.round.gamers[key]['score']);
                }

                if (Number(value['score']) !== checkedScore) {
                    isChanged = true;
                    break;
                }
            }
            if (isChanged && !this.currentState['game_over']) {
                // this.notify('countChanged');
                if (!this.currentState['events']['is_new_round']) {
                    this.notify('goal');
                }
            }

        },

        onNewRound() {
            if (this.previousState
                && this.currentState['game_started']
                && this.stateChanged('current_round')
            ) {
                this.notify('new_round');
            }
        },

        onGameOver() {
            if (this.currentState['game_over']) {
                this.notify('game_over');
                this.previousState = null;
            }
        },

        notify(event) {
            for (const item of callbacks) {
                item(event, this.currentState);
            }
        }
    };
}
