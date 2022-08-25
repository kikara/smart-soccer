export default function Debug () {
    let This = this;

    this.init = function () {
        This.$container = $('.js-container');
        This.socketInit();
        This.buttons();
    }

    this.socketInit = function () {
        This.tryToConnect();
        This.onOpen();
        This.onMessage();
        This.onError();
        This.onClose();
        This.connect();
    }

    this.buttons = function () {
        This.onPrepareGame();
        This.onStartGame();
        This.onRedGoalButton();
        This.onBlueGoalButton();
        This.onTestGoalButton();
    }

    this.tryToConnect = function () {
        try {
            This.conn = new WebSocket(WS_HOST);
        } catch (e) {
            console.log('Соединение не установлено');
        }
    }

    this.onOpen = function () {
        This.conn.onopen = function () {
            console.log('connection is open');
        }
    }

    this.onMessage = function () {
        This.conn.onmessage = function (e) {
            console.log(e.data);
        }
    }

    this.onError = function () {
        this.conn.onerror = function () {
            console.log('connection error');
        }
    }

    this.onClose = function () {
        this.conn.onclose = function () {
            console.log('connection closed');
        }
    }

    this.onPrepareGame = function () {
        This.$container.on('click', '.js-prepare-game', function () {
            let data = {
                'cmd': 'prepare',
                'f': '828357419',
                'id': '1',
                'd': true,
                'r': '5422355155',
                't_id': '1',
            }
            This.send(data);
        });
    }

    this.onStartGame = function () {
        This.$container.on('click', '.js-start', function () {
            let data = {'cmd': 'start'}
            This.send(data);
        });
    }

    this.onBlueGoalButton = function () {
        This.$container.on('click', '.js-blue', function () {
            let data = {
                'cmd': 'count',
                'value': 'blue',
            }
            This.send(data);
        });
    }

    this.onRedGoalButton = function () {
        This.$container.on('click', '.js-red', function () {
            let data = {
                'cmd': 'count',
                'value': 'red'
            }
            This.send(data);
        });
    }

    this.onTestGoalButton = function () {
        This.$container.on('click', '.js-test', function () {
            let data = {'cmd': 'test'}
            This.send(data);
        });
    }

    this.connect = function () {
        This.$container.on('click', '.js-connect', function () {
            console.log('try to connect');
            This.tryToConnect();
        });
    }

    this.isOpen = function () {
        return This.conn.readyState === 1;
    }

    this.send = function (data) {
        if (This.isOpen()) {
            This.conn.send(JSON.stringify(data));
        }
    }
}