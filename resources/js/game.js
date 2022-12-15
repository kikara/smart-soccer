import EventListener from "./game/EventListener";
import AudioEventListener from "./game/AudioEventListener";
export default function Game() {
    let This = this;

    this.init = function () {
        This.$container = $('.js-container');
        This.eventListenerInit();
        This.audioEventListenerInit();
        This.startTime = 0;
        This.contentLoaded = false;
        socketInit();
    }

    this.eventListenerInit = function () {
        This.eventListener = new EventListener();
        This.eventListener.addEventListener('tableOccupied', This.onTableOccupied);
        This.eventListener.addEventListener('gameStarted', This.onGameStarted);
        This.eventListener.addEventListener('newRound', This.onRoundEndSideChange);
        This.eventListener.addEventListener('gameOver', This.onGameOver);
        This.eventListener.addEventListener('count_changed', This.onGoal);
    }

    this.audioEventListenerInit = function () {
        This.audioEventListener = new AudioEventListener();
        This.audioEventListener.init();
        This.eventListener.addEventListener('tableOccupied', This.audioEventListener.gamersAudioInit);
        This.eventListener.addEventListener('goal', This.audioEventListener.onGoal);
        This.eventListener.addEventListener('newRound', This.audioEventListener.onNewRound);
        This.eventListener.addEventListener('gameStarted', This.audioEventListener.onRoundOne);
        This.eventListener.addEventListener('gameOver', This.audioEventListener.onGameOver);
    }

    const socketInit = () => {
        This.tryToConnect();
        This.onOpen();
        This.onMessage();
        This.onClose();
    }

    this.tryToConnect = function () {
        This.conn = new WebSocket(WS_HOST);
    }

    this.onOpen = function () {
        This.conn.onopen = function (e) {
            console.log('Соединение установлено');
            This.getGameState();
        }
    }

    this.onMessage = function () {
        This.conn.onmessage = function (e) {
            const json = JSON.parse(e.data);
            This.eventListener.handle(json);
            // This.onGoal(json);
        }
    }

    this.onRoundEndSideChange = function (json) {
        if (json['is_side_change']) {
            This.updateContent(json);
        } else {
            This.updateRoundCounts(json);
        }
        This.$container.find('.js-blue-count').html('0');
        This.$container.find('.js-red-count').html('0');
    }

    this.onClose = function () {
        This.conn.onclose = function (e) {
            console.log('connection closed');
            setTimeout(socketInit, 2000);
        }
    }

    this.onTableOccupied = function (json) {
        if (! This.contentLoaded) {
            This.updateContent(json);
        }
    }

    this.onGameStarted = function (json) {
        if (! This.timerOn) {
            This.timeOn = true;
            This.startTime = parseInt(json['start_time']);
            This.updatedInterval = setInterval(This.updateTime, 1000);
        }
    }

    this.onGoal = function (json) {
        if (This.contentLoaded) {
            This.$container.find('.js-blue-count').html(json['round']['blue_count']);
            This.$container.find('.js-red-count').html(json['round']['red_count']);
        }
    }

    This.onGameOver = function (json) {
            if (This.updatedInterval) {
                clearInterval(This.updatedInterval);
                This.updatedInterval = null;
                This.timerOn = false;
            }
            $.post(
                '/api/getNewTableLayout',
                {},
                function (response) {
                    This.$container.html($(response).children());
            });
            This.contentLoaded = false;
    }

    This.updateTime = function () {
        let now = parseInt(new Date().valueOf() / 1000);
        let currentTimeSec = now - This.startTime;
        let minutes = parseInt(currentTimeSec / 60);
        let seconds = currentTimeSec - minutes * 60;
        if (seconds < 10) {
            seconds = '0' + seconds;
        }
        This.$container.find('.js-time').html(minutes + ':' + seconds);
    }

    this.getGameState = function () {
        This.conn.send(JSON.stringify({'cmd': 'state'}));
    }

    this.updateContent = function (json) {
        $.post(
            '/api/getMainLayout',
            json,
            function (response) {
                This.$container.html($(response).children());
                This.contentLoaded = true;
            });
    }

    this.updateRoundCounts = function (json) {
        for (let item of json['rounds']) {
            if (item['winner_id']) {
                let card = This.$container.find('div[data-user='+ item['winner_id'] + ']');
                card.find('.js-round-count').removeAttr('hidden');
            }
        }
    }
}
