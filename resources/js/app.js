import $ from 'jquery';
global.$ = global.jQuery = $;

require('bootstrap');

import './bootstrap';

import Game from './game.js';
window.game = new Game();

import {Tournament} from "./tournament/Tournament";
new Tournament();

