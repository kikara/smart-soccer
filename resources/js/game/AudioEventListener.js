import GamerAudio from "./GamerAudio";

export default function AudioEventListener () {
    let This = this;
    this.usersAudio = {};

    this.init = function () {
    }

    this.gamersAudioInit = function (json) {
        let gamers = [json['round']['blue_gamer_id'], json['round']['red_gamer_id']];
        $.post(
            '/api/getGamersAudio',
            {
                'gamers': gamers,
            },
            function (response) {
                if (response && response['result']) {
                    for (let [userId, value] of Object.entries(response['sounds'])) {
                        let gamerAudio = new GamerAudio();
                        for (let item of value['random_sounds']) {
                            gamerAudio.randomGoalSounds.push(item['path']);
                        }
                        for (let item of value['event_sounds']) {
                            let obj = JSON.parse(item['parameters']);
                            gamerAudio.userEventSounds.push({
                                'parameters': obj,
                                'path': item['path'],
                            });
                        }
                        This.usersAudio[userId] = gamerAudio;
                    }
                }
            }, 'json');
    }

    this.onGoal = function (json) {
        let eventAudioPath = This.searchEventAudioPath(json);
        let audioPath = eventAudioPath ? eventAudioPath : This.randomAudioPath(json);
        audioPath = audioPath ? '/storage/' + audioPath : This.systemAudioPath();
        if (audioPath) {
            This.play(audioPath);
        }
    }

    this.searchEventAudioPath = function (json) {
        let formEvent = This.formEvent(json);
        let userId = formEvent['user_id'];
        if (Object.keys(This.usersAudio).length !== 0) {
            let target = This.usersAudio[userId];
            if (! target) {
                return false;
            }
            let eventSounds = This.usersAudio[userId]['userEventSounds'] ?? [];
            let params = formEvent['parameters'];
            for (let item of eventSounds) {
                if (This.paramsCompare(params, item['parameters'])) {
                    return item['path'];
                }
            }
        }
        return false;
    }

    this.randomAudioPath = function (json) {
        let userId = json['events']['goal_scored'];
        if (Object.keys(This.usersAudio).length !== 0) {
            let target = This.usersAudio[userId];
            if (! target) {
                return false;
            }
            let randoms = This.usersAudio[userId]['randomGoalSounds'];
            if (randoms.length === 0) {
                return false;
            }
            if (randoms.length === 1) {
                return randoms[0];
            }
            return randoms[Math.floor(Math.random() * randoms.length)];
        }
        return false;
    }

    this.systemAudioPath = function () {
        return '/audio/goal.mp3';
    }

    this.paramsCompare = function (params, userParams) {
        for (let key in userParams) {
            if (parseInt(params[key]) !== parseInt(userParams[key])) {
                return false;
            }
        }
        return true;
    };

    this.formEvent = function (json) {
        return {
            'event': 'goal',
            'user_id': json['events']['goal_scored'],
            'parameters': {
                'continuity': json['events']['goal_count'],
                'goal-count': json['events']['goal_scored_count'],
                'opponent-score': json['events']['opponent-score'],
            }
        };
    }

    this.onNewRound = function (json) {
        This.onRoundTwo(json);
        This.onRoundThree(json);
    }

    this.onRoundOne = function (json) {
        if (json['current_round'] === 0) {
            This.play('/audio/round_1_fight.mp3');
        }
    }

    this.onRoundTwo = function (json) {
        if (json['current_round'] === 1) {
            This.play('/audio/round_2_fight.mp3');
        }
    }

    this.onRoundThree = function (json) {
        if (json['current_round'] === 2) {
            This.play('/audio/final_round_fight.mp3');
        }
    }

    this.play = (audioPath) => {
        let audio = new Audio();
        audio.addEventListener('loadeddata', function () {
            audio.play();
        }, false);
        audio.src = audioPath;
    }
}
