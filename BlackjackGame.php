<?php

namespace Blackjack;

require_once('BlackjackPlayer.php');

class BlackjackGame
{
    public function __construct(private int $numberOfPlayers)
    {
    }

    public function start()
    {
        $player = new BlackjackPlayer($this->numberOfPlayers);
        $player->playGame();
    }
}

echo "プレイ人数を入力してください。(1/2/3)" . PHP_EOL;
while (true) {
    $numberOfPlayers = trim(fgets(STDIN));
    if ($numberOfPlayers === '1' || $numberOfPlayers === '2' || $numberOfPlayers === '3') {
        $BlackjackGame = new BlackjackGame($numberOfPlayers);
        $BlackjackGame->start();
    } else {
        echo "プレイ人数を1〜3の間で入力してください。(1/2/3)" . PHP_EOL;
    }
}
