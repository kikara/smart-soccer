import $ from 'jquery';
global.$ = global.jQuery = $;

require('bootstrap');

import './bootstrap';

import Game from './game.js';
window.game = new Game();

require('./tournament/Tournament');

