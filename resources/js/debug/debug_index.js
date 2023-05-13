import $ from 'jquery';
import '../game/socket.js';
import {handle} from "../game/events";

window.$ = window.jQuery = $;

const $container = $('#debug-container');

rws.onmessage = (e) => {
    const message = JSON.parse(e.data);

    $container.find('#board').html(
        JSON.stringify(message, null, 4)
    );

    handle(message);
}

function send(message) {
    rws.send(JSON.stringify(message));
}

function prepareGame(context) {
    const users = [];

    $container.find('select').each((k, v) => {
        const option = v.options[v.selectedIndex];
        const chatId = $(option).data('chat-id');

        if (users.includes(chatId)) {
            return;
        }

        users.push(chatId);
    });
    if (users.length === 1) {
        return;
    }

    send({
        cmd: "prepare",
        f: users[0],
        r: users[1],
        t_id: 1
    });
}

function stateUpdate() {
    send({cmd: "state"});
}

function start() {
    send({cmd: "start"});
}

function count(context) {
    const value = context.dataset.side;
    send({
        cmd: "count",
        value: value
    })
}

function resetLastGoal() {
    send({cmd: "resetLastGoal"});
}

function gameOver() {
    send({cmd: 'gameOver'});
}

send({cmd: 'state'});

$container.on('click', 'button', function (event) {
    const action = event.target.dataset.action;

    switch (action) {
        case 'stateUpdate':
            stateUpdate();
            break;
        case 'prepareGame':
            prepareGame();
            break;
        case 'start':
            start();
            break;
        case 'count':
            count(event.target);
            break;
        case 'resetLastGoal':
            resetLastGoal();
            break;
    }
});


