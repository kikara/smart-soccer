export default function EventListener() {
    let This = this;
    let round = 0;
    let blueCount = 0;
    let redCount = 0;
    let isGameStarted = false;
    let isGameOver = false;
    let tableOccupied = false;

    let subscribers = {};

    /**
     * tableOccupied - стол заняли
     * gameStarted - игра началась
     * newRound - новый раунд
     * goal - гол
     */

    this.init = function () {

    }

    this.handle = function (json) {
        This.onTableOccupied(json);
        This.onGameStarted(json);
        This.onGoal(json);
        This.onNewRound(json);
        This.onGameOver(json);
    }

    this.onTableOccupied = function (json) {
        if (json['is_busy'] && json['is_busy'] !== tableOccupied) {
            This.notify('tableOccupied', json);
        }
        tableOccupied = json['is_busy'];
    };

    this.onGameStarted = function (json) {
        if (json['game_started'] && json['game_started'] !== isGameStarted) {
            This.notify('gameStarted', json);
        }
        isGameStarted = json['game_started'];
    };

    this.onGoal = function (json) {
        let blue = parseInt(json['round']['blue_count']);
        let red = parseInt(json['round']['red_count']);
        if ((blue !== blueCount || red !== redCount) && ! json['events']['is_new_round']) {
            This.notify('goal', json);
        }
        blueCount = blue;
        redCount = red;
    }

    this.onNewRound = function (json) {
        let currentRound = parseInt(json['current_round']);
        if (currentRound !== round) {
            This.notify('newRound', json);
        }
        round = currentRound;
    };

    this.onGameOver = function (json) {
        if (json['game_over']) {
            This.notify('gameOver', json);
            blueCount = 0;
            redCount = 0;
            round = 0;
        }
    };

    this.addEventListener = function (event, callback) {
        let callbacks = subscribers[event] ?? [];
        callbacks.push(callback);
        subscribers[event] = callbacks;
    }

    this.notify = function (event, json) {
        let callbacks = subscribers[event] ?? [];
        for (let callback of callbacks) {
            callback(json);
        }
    }
}
