<?php

namespace Blackjack;

class BlackjackDeck
{
    public function prepareDeck(): array
    {
        $suits = ['スペード', 'ハート', 'ダイヤ', 'クラブ'];
        $numbers = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $deck = [];
        foreach ($suits as $suit) {
            foreach ($numbers as $number) {
                $deck[] = [$suit, $number];
            }
        }
        shuffle($deck);
        return $deck;
    }
}
