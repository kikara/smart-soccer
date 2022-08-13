<?php

namespace App\Ratchet;

/**
 * Отслеживание кто забил, кто пропустил, и сколько подряд забил или пропустил
 */
class GoalTrack
{
    private int $goalScoredUserID = 0;
    private int $goalMissedUserID = 0;

//    private int $goalsScoredCount = 0;
//    private int $goalsMissedCount = 0;
    private int $goalCount = 0;

    public function updateScore(int $scoredUserID, int $missedUserID)
    {
        if ($scoredUserID === $this->goalScoredUserID) {
            $this->goalCount++;
        } else {
            $this->goalScoredUserID = $scoredUserID;
//            $this->goalsScoredCount = 1;
            $this->goalCount = 1;
        }

        if ($missedUserID !== $this->goalMissedUserID) {
            $this->goalMissedUserID = $missedUserID;
        }
    }

    public function getGoalScored()
    {
        return $this->goalScoredUserID;
    }

    public function getGoalMissed()
    {
        return $this->goalMissedUserID;
    }

    public function getGoalCount()
    {
        return $this->goalCount;
    }
}