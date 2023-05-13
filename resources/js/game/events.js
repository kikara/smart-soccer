
const callbacks = [];

let currentState = null;

let previousState = null;

function stateChanged(property) {
    if (previousState === null) {
        return true;
    }
    return currentState[property] !== previousState[property];
}

function notify(event) {
    for (const item of callbacks) {
        item(event, currentState);
    }
}

function onGoal() {
    if (!currentState['game_started']) {
        return;
    }

    let isChanged = false;

    for (const [key, value] of Object.entries(currentState.round.gamers)) {

        let checkedScore = 0;
        if (previousState) {
            checkedScore = Number(previousState.round.gamers[key]['score']);
        }

        if (Number(value['score']) !== checkedScore) {
            isChanged = true;
            break;
        }
    }
    if (isChanged && !currentState['game_over']) {
        // this.notify('countChanged');
        if (!currentState['events']['is_new_round']) {
            notify('goal');
        }
    }
}
function onNewRound() {
    if (previousState
        && currentState['game_started']
        && stateChanged('current_round')
    ) {
        notify('new_round');
    }
}
function onTableOccupied() {
    if (currentState['is_busy'] && stateChanged('is_busy')) {
        notify('booked');
    }
}

function gameStarted() {
    if (currentState['game_started'] && stateChanged('game_started')) {
        notify('started');
    }
}

function onGameOver() {
    if (currentState['game_over']) {
        notify('game_over');
        previousState = null;
    }
}

export function handle(data) {
    currentState = data;

    onTableOccupied();
    gameStarted();
    onGoal();
    onNewRound();
    onGameOver();

    notify('updated');

    previousState = currentState;
}

export function addListener(callback) {
    callbacks.push(callback);
}

export function getCurrentState() {
    return currentState;
}
















