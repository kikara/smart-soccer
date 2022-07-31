export default function Game() {
    let This = this;

    this.init = function () {
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
            This.conn = new WebSocket('ws://192.168.1.30:8080');
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
        This.onNewRound(json);
    }

    this.onRoundEndSideChange = function (json) {
        let currentRound = parseInt(json['current_round']);
        if (currentRound !== This.round && json['is_side_change']) {
            let $firstCard = This.$container.find('.js-card-color-primary');
            let $secCard = This.$container.find('.js-card-color-danger');
            if ($firstCard.hasClass('bg-primary')) {
                $firstCard.removeClass('bg-primary').addClass('bg-danger');
            } else {
                $firstCard.removeClass('bg-danger').addClass('bg-primary');
            }
            if ($secCard.hasClass('bg-danger')) {
                $secCard.removeClass('bg-danger').addClass('bg-primary');
            } else {
                $secCard.removeClass('bg-primary').addClass('bg-danger');
            }
            let $blueCount = This.$container.find('.js-blue-count');
            let $redCount = This.$container.find('.js-red-count');
            $blueCount.removeClass('js-blue-count').addClass('js-red-count');
            $redCount.removeClass('js-red-count').addClass('js-blue-count');
            This.round = currentRound;
        }
    }

    this.onNewRound = function (json) {
        if (json['events']['is_new_round']) {
            let $audio = $('.js-audio-container').find('.js-audio');
            $audio.trigger('play')
            console.log($audio);
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
            if (This.contentLoaded) {
                This.$container.find('.js-blue-count').html(json['round']['blue_count']);
                This.$container.find('.js-red-count').html(json['round']['red_count']);
            } else {
                $.post(
                    '/api/getMainLayout',
                    json,
                    function (response) {
                        This.$container.html($(response).children());
                        This.contentLoaded = true;
                        This.onNewRound(json);
                });
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
                '/api/saveGame',
                json,
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
        let jsonData = {'cmd': 'state'};
        This.conn.send(JSON.stringify(jsonData));
    }

    this.sendToNewGame = function () {
        let jsonData = {'cmd': 'new_game'};
        This.conn.send(JSON.stringify(jsonData));
    }

    This.alertMessage = function (msg) {
        $.alert({
            title: 'Ошибка',
            content: msg,
            type: 'red',
        });
    }
}
