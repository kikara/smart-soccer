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
    }

    this.buttons = function () {
        // This.connect();
        This.onPrepareGame();
        This.onStartGame();
        This.onRedGoalButton();
        This.onBlueGoalButton();
        onResetButton();
        onDoneButton();
        onResetLastGoal();
        // This.onTestGoalButton();
    }

    this.tryToConnect = () => This.conn = new WebSocket(WS_HOST);

    this.onOpen = function () {
        This.conn.onopen = function () {
            console.log('connection is open');
        }
    }

    this.onMessage = function () {
        This.conn.onmessage = function (e) {
            const message = JSON.parse(e.data);
            console.log(message);
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
            setTimeout(This.socketInit, 2000);
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

    const onResetButton = () => {
        This.$container.on('click', '.js-reset', () => {
            This.send({cmd: 'reset'});
        });
    }

    const onDoneButton = () => {
        This.$container.on('click', '.js-done', () => {
            console.log('done button');
        });
    }

    const onResetLastGoal = () => {
        This.$container.on('click', '.js-reset-last', function () {
            This.send({cmd: 'reset_last_goal'});
        });
    }

    // this.onTestGoalButton = function () {
    //     This.$container.on('click', '.js-test', function () {
    //         let data = {'cmd': 'test'}
    //         This.send(data);
    //     });
    // }

    // this.connect = function () {
    //     This.$container.on('click', '.js-connect', function () {
    //         console.log('try to connect');
    //         This.socketInit();
    //     });
    // }

    this.send = function (data) {
        if (This.isOpen()) {
            This.conn.send(JSON.stringify(data));
        }
    }

    this.isOpen = function () {
        return This.conn.readyState === 1;
    }
}
