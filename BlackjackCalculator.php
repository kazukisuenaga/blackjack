<?php

namespace Blackjack;

class BlackjackCalculator
{
    public function calculateScore(array $hands): int
    {
        $handNumbers = [];
        foreach ($hands as $hand) {
            $handNumbers[] = $hand[1];
        }
        $score = 0;
        foreach ($handNumbers as $number) {
            $score += $this->convertToRank($number);
        }
        if (in_array('A', $handNumbers) && $score > 21) {
            $score -= 10;
        }
        return $score;
    }

    public function convertToRank(string $number): int
    {
        if ($number === "A") {
            return 11;
        } elseif ($number === "J" || $number === "Q" || $number === "K") {
            return 10;
        } else {
            return intval($number);
        }
    }
}
