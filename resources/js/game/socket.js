export default function GameSocket() {
    const HOST = 'ws://localhost:8776';

    const callbacks = [];

    let connection = null;

    const onmessage = function (e) {
        for (const item of callbacks) {
            if (item) {
                item(e);
            }
        }
    };
    const connect = function () {
        connection = new WebSocket(HOST);

        connection.onopen = (e) => {
            console.log('Соединение установлено');
            connection.send(JSON.stringify({cmd: 'state'}));
        }

        connection.onmessage = (e) => {
            onmessage(e);
        }

        connection.onclose = (e) => {
            console.log('connection closed');
            setTimeout(connect, 2000);
        }
    };

    connect();

    return {
        addEventListener(callback) {
            callbacks.push(callback);
        },
        send(data) {
            if (connection && connection.readyState === 1) {
                connection.send(JSON.stringify(data));
            }
        },
    }
};
