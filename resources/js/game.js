import AudioEventHandler from "./audioEventHandler";

export default function Game() {
    let This = this;

    this.init = function () {
        This.audioEventHandler = new AudioEventHandler();
        This.$container = $('.js-container');
        This.startTime = 0;
        This.round = 0;
        This.gameStarted = false;
        This.contentLoaded = false;
        This.tryToConnect();
        This.onOpen();
        This.onMessage();
        This.onClose();
    }

    this.tryToConnect = function () {
        try {
            This.conn = new WebSocket(WS_HOST);
        } catch (e) {
            console.log('Соединение не установлено');
        }
    }

    // this.onResetButton = function () {
    //     This.$container.on('click', '.js-reset', function () {
    //         if (This.conn.readyState === 1) {
    //             let data = {'reset': true};
    //             This.conn.send(JSON.stringify(data));
    //         }
    //     });
    // }

    this.onOpen = function () {
        This.conn.onopen = function (e) {
            console.log('Соединение установлено');
            This.getGameState();
        }
    }

    this.onMessage = function () {
        This.conn.onmessage = function (e) {
            This.messageHandler(e.data);
        }
    }

    this.messageHandler = function (msg) {
        let json = JSON.parse(msg);
        This.onGameStarted(json);
        This.onRoundEndSideChange(json);
        This.onGameOver(json);
        if (This.contentLoaded) {
            This.audioEventHandler.handleEvent(json);
        }
    }

    this.onRoundEndSideChange = function (json) {
        let currentRound = parseInt(json['current_round']);
        if (currentRound !== This.round) {
            if (json['is_side_change']) {
                This.updateContent(json);
            } else {
                This.updateRoundCounts(json);
            }
            This.round = currentRound;
        }
    }

    this.onClose = function () {
        This.conn.onclose = function (e) {
            console.log('connection closed');
        }
    }

    this.onError = function () {
        This.conn.onerror = function (e) {
            console.log('Не удачная попытка соединения с сервером');
        }
    }

    this.onGameStarted = function (json) {
        if (json['is_busy']) {
            let audio = document.getElementById('js-goal');
            if (This.contentLoaded) {
                This.$container.find('.js-blue-count').html(json['round']['blue_count']);
                This.$container.find('.js-red-count').html(json['round']['red_count']);
                audio.play();
            } else {
                This.updateContent(json)
            }
        }
        if (json['game_started'] && ! json['game_over']) {
            if (! This.timerOn) {
                This.timerOn = true;
                This.startTime = parseInt(json['start_time']);
                This.updatedInterval = setInterval(This.updateTime, 1000);
            }
        }
    }

    This.onGameOver = function (json) {
        if (json['game_over']) {
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
            This.sendToNewGame();
            This.contentLoaded = false;
            This.startTime = 0;
            This.round = 0;
        }
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

    this.sendToNewGame = function () {
        This.conn.send(JSON.stringify({'cmd': 'new_game'}));
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
