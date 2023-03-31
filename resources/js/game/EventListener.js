export default function EventListener() {
    return {
        currentState: null,

        previousState: null,

        subscribers: {},

        callbacks: [],

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
                this.notify('tableOccupied');
            }
        },

        gameStarted() {
            if (this.currentState['game_started'] && this.stateChanged('game_started')) {
                this.notify('gameStarted');
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
                this.notify('newRound');
            }
        },

        onGameOver() {
            if (this.currentState['game_over']) {
                this.notify('gameOver');
                this.previousState = null;
            }
        },

        addEventListener(event, callback) {
            const callbacks = this.subscribers[event] ?? [];
            callbacks.push(callback);
            this.subscribers[event] = callbacks;
        },

        addListener(callback) {
            this.callbacks.push(callback);
        },

        notify(event) {
            const callbacks = this.subscribers[event] ?? [];
            for (const callback of callbacks) {
                callback(this.currentState);
            }

            for (const item of this.callbacks) {
                item(event, this.currentState);
            }
        }
    };
    // let This = this;
    // let round = 0;
    // let blueCount = 0;
    // let redCount = 0;
    // let isGameStarted = false;
    // // let isGameOver = false;
    // let tableOccupied = false;
    //
    // let subscribers = {};
    //
    // /**
    //  * tableOccupied - стол заняли
    //  * gameStarted - игра началась
    //  * newRound - новый раунд
    //  * goal - гол
    //  * gameOver - игра закончилась
    //  */
    //
    // this.handle = function (json) {
    //     This.onTableOccupied(json);
    //     This.onGameStarted(json);
    //     This.onGoal(json);
    //     This.onNewRound(json);
    //     This.onGameOver(json);
    // }
    //
    // this.onTableOccupied = function (json) {
    //     if (json['is_busy'] && json['is_busy'] !== tableOccupied) {
    //         This.notify('tableOccupied', json);
    //     }
    //     tableOccupied = json['is_busy'];
    // };
    //
    // this.onGameStarted = function (json) {
    //     if (json['game_started'] && json['game_started'] !== isGameStarted) {
    //         This.notify('gameStarted', json);
    //     }
    //     isGameStarted = json['game_started'];
    // };
    //
    // this.onGoal = function (json) {
    //     const blue = parseInt(json['round']['blue_count']);
    //     const red = parseInt(json['round']['red_count']);
    //
    //     if ((blue !== blueCount || red !== redCount)) {
    //         if (!json['game_over']) {
    //             if (!json['events']['is_new_round']) {
    //                 This.notify('goal', json);
    //             }
    //             This.notify('count_changed', json);
    //         }
    //     }
    //     blueCount = blue;
    //     redCount = red;
    // }
    //
    // this.onNewRound = function (json) {
    //     let currentRound = parseInt(json['current_round']);
    //     if (currentRound !== round) {
    //         This.notify('newRound', json);
    //     }
    //     round = currentRound;
    // };
    //
    // this.onGameOver = function (json) {
    //     if (json['game_over']) {
    //         This.notify('gameOver', json);
    //         isGameStarted = false;
    //         tableOccupied = false;
    //         blueCount = 0;
    //         redCount = 0;
    //         round = 0;
    //     }
    // };
    //
    // this.addEventListener = function (event, callback) {
    //     let callbacks = subscribers[event] ?? [];
    //     callbacks.push(callback);
    //     subscribers[event] = callbacks;
    // }
    //
    // this.notify = function (event, json) {
    //     console.log('this is ', event);
    //     let callbacks = subscribers[event] ?? [];
    //     for (let callback of callbacks) {
    //         callback(json);
    //     }
    // }
}
