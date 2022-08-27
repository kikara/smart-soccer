import $ from 'jquery';
global.$ = global.jQuery = $;

import Game from './game.js';
import Debug from "./debug";
import SoundSetting from "./profile/SoundSetting";

global.game = new Game();
global.Debug = Debug;
global.SoundSetting = SoundSetting;
require('./tournament/Tournament');
require('bootstrap');
require('./config');
