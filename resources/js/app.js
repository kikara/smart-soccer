import $ from 'jquery';
global.$ = global.jQuery = $;

import Game from './game.js';
import SoundSetting from "./profile/SoundSetting";

global.game = new Game();
global.SoundSetting = SoundSetting;
require('./tournament/Tournament');
require('bootstrap');
require('./config');
require('jquery-confirm');
