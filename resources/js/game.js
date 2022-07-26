export default function Game() {
    let This = this;

    this.init = function () {
        This.$container = $('.js-container');
        This.gameStarted = false;
        This.contentLoaded = false;
        This.seconds = 0;
        This.minutes = 0;
        This.timerOn = false;
        This.tryToConnect();
        This.onOpen();
        This.onMessage();
        This.onClose();
        This.onStartGameButton();
        This.onResetButton();
    }

    this.tryToConnect = function () {
        try {
            This.conn = new WebSocket('ws://192.168.133.86:8081');
        } catch (e) {
            console.log('Соединение не установлено');
        }
    }

    this.onStartGameButton = function () {
        This.$container.on('click', '.js-start', function () {
            if (This.conn.readyState === 1) {
                $.confirm({
                    title: 'Начать игру',
                    content: function () {
                        let self = this;
                        $.post(
                            '/api/popup/getStartForm',
                            {},
                            function (response) {
                                if (response) {
                                    self.setContent(response);
                                }
                        });
                    },
                    buttons: {
                        accept: {
                            text: 'Старт',
                            btnClass: 'btn btn-green',
                            action: function () {
                                if (This.conn.readyState === 1) {
                                    let self = this;
                                    let serialized = self.$content.find('form').serializeArray();
                                    let data = {};
                                    $.each(serialized, function (k, v) {
                                        data[v.name] = Number(v.value);
                                    });
                                    if (data['blue-gamer'] === 0 || data['red-gamer'] === 0) {
                                        This.alertMessage('Не выбран игрок');
                                        return false;
                                    }
                                    if (data['blue-gamer'] === data['red-gamer']) {
                                        This.alertMessage('Выбран один и тот же игрок');
                                        return false;
                                    }
                                    data['start'] = true;
                                    This.conn.send(JSON.stringify(data));
                                    self.close();
                                } else {
                                    This.alertMessage('Соединение потеряно');
                                }
                                return false;
                            }
                        },
                        cancel: {
                            text: 'Отмена',
                        }
                    }
                })

            } else {
                This.alertMessage('Нет соединения с сервером');
            }
        })
    }

    this.onResetButton = function () {
        This.$container.on('click', '.js-reset', function () {
            if (This.conn.readyState === 1) {
                let data = {'reset': true};
                This.conn.send(JSON.stringify(data));
            }
        });
    }

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
        if (json.game_started) {
            if (This.contentLoaded) {
                $.post(
                    '/api/getMainInfo',
                    json,
                    function (response) {
                        This.$container.find('.js-blue-count').html(json.blue);
                        This.$container.find('.js-red-count').html(json.red);
                });
            } else {
                $.post(
                    '/api/getMainLayout',
                    json,
                    function (response) {
                        This.$container.html($(response).children());
                        This.contentLoaded = true;
                });
            }
            if (! This.timerOn) {
                This.timerOn = true;
                setInterval(This.updateTime, 1000);
            }
        }
    }

    This.updateTime = function () {
        This.seconds += 1;
        if (This.seconds >= 60) {
            This.minutes += 1;
            This.seconds = 0;
        }
        This.$container.find('.js-time').html(This.minutes + ':' + This.seconds);
    }

    this.getGameState = function () {
        This.conn.send('state');
    }

    This.alertMessage = function (msg) {
        $.alert({
            title: 'Ошибка',
            content: msg,
            type: 'red',
        });
    }
}