<?php

namespace Blackjack;

require_once('BlackjackOnePlayerRule.php');
require_once('BlackjackMultiPlayersRule.php');

class BlackjackPlayer
{
    public function __construct(private $numberOfPlayers)
    {
    }

    public function playGame()
    {
        $rule = new BlackjackOnePlayerRule;
        if ($this->numberOfPlayers >= 2) {
            $rule = new BlackjackMultiPlayersRule($this->numberOfPlayers);
        }
        return $rule->play();
    }
}
