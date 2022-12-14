<?php

namespace App\Console\Commands;

use App\Ratchet\Round;
use Illuminate\Console\Command;

class DebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:round';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $round = new Round();
        $round->setGamers(1, 2);
        $round->incrementBlue();
        $round->incrementRed();
        $round->incrementRed();
        $round->incrementBlue();
        $round->deleteLastGoal();
        $round->deleteLastGoal();
        $round->deleteLastGoal();
        $round->deleteLastGoal();
        $round->deleteLastGoal();
        var_dump($round->getState());
        var_dump($round->goalTrack->getGoalCount());
        var_dump($round->goalTrack->getGoalScoredUserId());
        return 0;
    }
}
