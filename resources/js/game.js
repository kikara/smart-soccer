export default function Game() {
    let This = this;

    this.init = function () {
        // This.audioEventHandler = new AudioEventHandler();
        This.$container = $('.js-container');
        This.eventListenerInit();
        This.startTime = 0;
        This.contentLoaded = false;
        This.tryToConnect();
        This.onOpen();
        This.onMessage();
        This.onClose();
    }

    this.eventListenerInit = function () {
        This.eventListener = new EventListener();
        This.eventListener.addEventListener('tableOccupied', This.onTableOccupied)
        This.eventListener.addEventListener('gameStarted', This.onGameStarted)
        This.eventListener.addEventListener('newRound', This.onRoundEndSideChange)
        This.eventListener.addEventListener('gameOver', This.onGameOver)
        This.eventListener.addEventListener('goal', This.onGoal);
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
            let json = JSON.parse(e.data);
            This.eventListener.handle(json);
        }
    }

    this.onRoundEndSideChange = function (json) {
        if (json['is_side_change']) {
            This.updateContent(json);
        } else {
            This.updateRoundCounts(json);
        }
    }

    this.onClose = function () {
        This.conn.onclose = function (e) {
            console.log('connection closed');
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
            // This.sendToNewGame();
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

    // this.sendToNewGame = function () {
    //     This.conn.send(JSON.stringify({'cmd': 'new_game'}));
    // }

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

    // this.goalAudioPlay = function (json) {
    //     let round = json['round'];
    //     let currentBlueCount = parseInt(This.$container.find('.js-blue-count').html());
    //     let currentRedCount = parseInt(This.$container.find('.js-red-count').html());
    //     if (currentRedCount !== parseInt(round['red_count']) || currentBlueCount !== parseInt(round['blue_count'])) {
    //         let audio = new Audio(This.getRandomAudioFile());
    //         audio.addEventListener('loadeddata', function () {
    //             audio.play();
    //         }, false);
    //     }
    // }

    // this.getRandomAudioFile = function () {
    //     let files = [
    //         'goal.mp3', 'icq.mp3', 'finish-him.mp3',
    //         'liu-kang-kick.mp3', 'delo-sdelano.mp3', 'meme-de-creditos-finales.mp3',
    //         'cr_suuu.mp3', 'mario-meme.mp3', 'hallelujahshort.mp3', 'that_was_easy.mp3'];
    //     let file = files[Math.floor(Math.random() * files.length)];
    //     return '/audio/' + file;
    // }
}
