import {WS_HOST} from "../config";
import ReconnectingWebSocket from 'reconnecting-websocket';

const rws = new ReconnectingWebSocket(WS_HOST);

window.rws = rws;
