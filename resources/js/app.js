import $ from 'jquery';
global.$ = global.jQuery = $;

import Game from './game.js';
import Debug from "./debug";
import SoundSetting from "./profile/SoundSetting";
import EventListener from "./game/EventListener";
import AudioEventListener from "./game/AudioEventListener";

global.game = new Game();
global.Debug = Debug;
global.SoundSetting = SoundSetting;
global.EventListener = EventListener;
global.AudioEventListener = AudioEventListener;
require('./tournament/Tournament');
require('bootstrap');
require('./config');
require('jquery-confirm');
