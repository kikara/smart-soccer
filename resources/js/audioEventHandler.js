export default function AudioEventHandler() {
    let This = this;

    this.handleEvent = function (state) {
        This.state = state;
        This.events = state['events'];
        This.onTableBusy();
        This.onNewRound();
        This.onGoalScored();
        This.onGoalMissing();
    }

    this.onTableBusy = function () {
        if (This.events['is_table_busy']) {
            console.log('table occupied but not started');
        }
    }

    this.onNewRound = function () {
        if (This.events['is_new_round']) {
            This.onRoundOne();
            This.onRoundTwo();
            This.onRoundThree();
        }
    }

    this.onRoundOne = function () {
        if (This.state['current_round'] === 0) {
            let audio = document.getElementById('js-first-round');
            audio.play();
        }
    }

    this.onRoundTwo = function () {
        if (This.state['current_round'] === 1) {
            let audio = document.getElementById('js-second-round');
            audio.play();
        }
    }

    this.onRoundThree = function () {
        if (This.state['current_round'] === 2) {
            let audio = document.getElementById('js-final-round');
            audio.play();
        }
    }

    this.onGoalScored = function () {
        let userID = This.events['goal_scored'];
        let count = This.events['goal_count'];
        console.log(userID + 'scored goal with continiously: ' + count);
    }

    this.onGoalMissing = function () {
        let userID = This.events['goal_missed'];
        let count = This.events['goal_count'];
        console.log(userID + 'scored goal with continiously: ' + count);
    }
}
