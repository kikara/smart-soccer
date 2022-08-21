import $ from 'jquery';
global.$ = global.jQuery = $;
require('bootstrap');
import './bootstrap';
import Game from './game.js';
window.game = new Game();
require('./tournament/Tournament');
window.$ = window.jQuery = $;
// window.WS_HOST = 'ws://192.168.1.30:8080';
window.WS_HOST = 'ws://192.168.133.86:8080';

