let gamers = [];

let readyState = false;
export function reset() {
    gamers = [];
    readyState = false;
}

function play(path) {
    (new Audio(path)).play();
}

function findByEvent(event, userEventSounds) {

    for (const eventSound of userEventSounds) {
        if (isParamsMatch(event.parameters, eventSound.parameters)) {
            play(eventSound.path);
            return true;
        }
    }
    return false;
}

function formEvent(state) {
    return {
        'event': 'goal',
        'user_id': state['events']['goal_scored'],
        'parameters': {
            'continuity': state['events']['goal_count'],
            'goal-count': state['events']['goal_scored_count'],
            'opponent-score': state['events']['opponent_score'],
        }
    };
}

function isParamsMatch(eventParam, userEventParameters) {
    for (const code of userEventParameters) {
        if (parseInt(eventParam[code.code]) !== parseInt(code.value)) {
            return false;
        }
    }
    return true;
}

function onGoal(state) {
    if (!readyState) {
        return;
    }

    const event = formEvent(state);

    const userSounds = gamers.find(gamer => event.user_id === gamer.id);

    if (findByEvent(formEvent(state), userSounds.events)) {
        return;
    }

    if (userSounds.audios.length > 0) {
        const audio = userSounds.audios[Math.floor(Math.random() * userSounds.audios.length)];
        play(audio.path);
        return;
    }

    play('/audio/goal.mp3');
}

function onNewRound(state) {
    if (state.current_round === 1) {
        play('/audio/round_2_fight.mp3')
    }

    if (state.current_round === 2) {
        play('/audio/final_round_fight.mp3');
    }
}

async function fetchGamersAudio() {

    for (const item of gamers) {
        await axios.get('/api/users/' + item.id + '/audios')
            .then(response => {
                item.audios = response.data.data;
            });
    }

    for (const item of gamers) {
        await axios.get('/api/users/' + item.id + '/events/audios')
            .then(response => {
                item.events = response.data.data;
            });
    }

    readyState = true;
}

function gameOver(state) {
    const winedGamer = state.game_winner_id;

    const gamer = gamers.find(gamer => winedGamer === gamer.id);

    if (gamer.events.length === 0) {
        play('/audio/winner.mp3');
        return;
    }

    const winnerEvents = gamer.events.filter(event => {
        const parameters = event.parameters.map(parameter => {
            return parameter.code;
        });
        return parameters.includes('winner');
    });

    const event = formEvent(state);

    event.parameters.winner = 1;

    if (findByEvent(event, winnerEvents)) {
        return;
    }

    play('/audio/winner.mp3');
}

export async function booked(state) {

    for (const item of Object.values(state.round.gamers)) {
        gamers.push({id: item.user_id});
    }

    fetchGamersAudio();
}

export function handleEvent(event, state) {
    switch (event) {
        case 'started':

            if (state.current_round === 0) {
                play('/audio/round_1_fight.mp3');
            }

            break;
        case 'new_round':
            onNewRound(state);
            break;

        case 'goal':
            onGoal(state);
            break;

        case 'game_over':
            gameOver(state);
            reset();
            break;
    }
}
