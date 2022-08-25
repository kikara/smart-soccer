import $ from 'jquery';
global.$ = global.jQuery = $;

import Game from './game.js';
import Debug from "./debug";

window.game = new Game();
window.Debug = Debug;
require('./tournament/Tournament');
require('bootstrap');
require('./config');
