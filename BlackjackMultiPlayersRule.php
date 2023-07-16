<?php

namespace Blackjack;

require_once('BlackjackCalculator.php');
require_once('BlackjackDeck.php');
require_once('BlackjackRule.php');

class BlackjackMultiPlayersRule implements BlackjackRule
{
    public function __construct(private int $numberOfPlayers)
    {
    }

    public function play()
    {
        echo "ブラックジャックを開始します。" . PHP_EOL;
        readline();
        // デッキを用意
        $deckCreator = new BlackjackDeck();
        $deck = $deckCreator->prepareDeck();
        // 手札の初期化
        $hands = [];
        for ($i = 0; $i < $this->numberOfPlayers + 1; $i++) {
            $hands[$i] = [];
        }
        // カードを2枚ずつ引く
        for ($i = 0; $i < 2; $i++){
            for ($j = 0; $j < $this->numberOfPlayers + 1; $j++) {
                $hands[$j][] = array_shift($deck);
            }
        }
        echo "あなたの引いたカードは" . implode("の", $hands[0][0]) . "です。" . PHP_EOL;
        readline();
        echo "あなたの引いたカードは" . implode("の", $hands[0][1]) . "です。" . PHP_EOL;
        readline();
        for ($i = 0; $i < $this->numberOfPlayers - 1; $i++) {
            echo "プレイヤー" . $i + 2 . "の引いたカードは" . implode("の", $hands[$i + 1][0]) . "です。" . PHP_EOL;
            readline();
            echo "プレイヤー" . $i + 2 . "の引いたカードは" . implode("の", $hands[$i + 1][1]) . "です。" . PHP_EOL;
            readline();
        }
        echo "ディーラーの引いたカードは" . implode("の", $hands[$this->numberOfPlayers][0]) . "です。" . PHP_EOL;
        readline();
        echo "ディーラーの引いた2枚目のカードはわかりません。" . PHP_EOL;
        readline();
        // プレイヤー1がカードを引く
        $calculator = new BlackjackCalculator();
        $scores = [];
        $scores[] = $calculator->calculateScore($hands[0]);
        while (true) {
            echo "あなたの現在の得点は" . $scores[0] . "です。カードを引きますか？（Y/N）" . PHP_EOL;
            $input = trim(fgets(STDIN));
            if ($input === "Y") {
                $hands[0][] = array_shift($deck);
                $scores[0] = $calculator->calculateScore($hands[0]);
                $drawnCard = $hands[0][array_key_last($hands[0])];
                echo "あなたの引いたカードは" . implode("の", $drawnCard) . "です。" . PHP_EOL;
                readline();
                if ($scores[0] > 21) {
                    echo "あなたの現在の得点は" . $scores[0] . "です。" . PHP_EOL;
                    readline();
                    echo "得点が21を超えました。あなたの番は終了です。" . PHP_EOL;
                    readline();
                    break;
                }
            } elseif ($input === "N") {
                break;
            } else {
                echo "カードを引く場合はY、引かない場合はNを入力してください。" . PHP_EOL;
                readline();
            }
        }
        // 他プレイヤーがカードを引く
        for ($i = 0; $i < $this->numberOfPlayers - 1; $i++) {
            $scores[$i + 1] = $calculator->calculateScore($hands[$i + 1]);
            while (true) {
                echo "プレイヤー" . $i + 2 . "の現在の得点は" . $scores[$i + 1] . "です。" . PHP_EOL;
                readline();
                if ($scores[$i + 1] < 17) {
                    $hands[$i + 1][] = array_shift($deck);
                    $drawnCard = $hands[$i + 1][array_key_last($hands[$i + 1])];
                    $scores[$i + 1] = $calculator->calculateScore($hands[$i + 1]);
                    echo "プレイヤー2の引いたカードは" . implode("の", $drawnCard) . "です。" . PHP_EOL;
                    readline();
                } elseif ($scores[$i + 1] > 21) {
                    "得点が21を超えました。プレイヤー" . $i + 2 . "の番は終了です。" . PHP_EOL;
                    readline();
                    break;
                } else {
                    echo "プレイヤー" . $i + 2 . "はカードを引きませんでした。" . PHP_EOL;
                    readline();
                    break;
                }
            }
        }
        // ディーラーがカードを引く
        $dealerHand = $hands[$this->numberOfPlayers];
        $dealerScore = $calculator->calculateScore($dealerHand);
        echo "ディーラーの引いた2枚目のカードは" . implode("の", $dealerHand[1]) . "でした。" . PHP_EOL;
        readline();
        while (true) {
            echo "ディーラーの現在の得点は" . $dealerScore . "です。" . PHP_EOL;
            readline();
            if ($dealerScore < 17) {
                $dealerHand[] = array_shift($deck);
                $drawnCard = $dealerHand[array_key_last($dealerHand)];
                $dealerScore = $calculator->calculateScore($dealerHand);
                echo "ディーラーの引いたカードは" . implode("の", $drawnCard) . "です。" . PHP_EOL;
                readline();
            } elseif ($dealerScore > 21) {
                echo "ディーラーの得点が21を超えました。ディーラーの番は終了です。" . PHP_EOL;
                readline();
                break;
            } else {
                echo "ディーラーの番は終了です。" . PHP_EOL;
                readline();
                break;
            }
        }
        // 勝敗の表示
        echo "あなたの得点:" . $scores[0] . PHP_EOL;
        readline();
        // ディーラーがバーストしていない場合
        if ($dealerScore <= 21) {
            if ($scores[0] <= 21 && $scores[0] > $dealerScore) {
                echo "あなたの勝ちです!" . PHP_EOL;
                readline();
            } elseif ($scores[0] <=21 && $scores[0] === $dealerScore) {
                echo "あなたとディーラーは引き分けです。" . PHP_EOL;
                readline();
            } else {
                echo "あなたの負けです!" . PHP_EOL;
                readline();
            }
            for ($i = 0; $i < $this->numberOfPlayers -1; $i++) {
                echo "プレイヤー" . $i + 2 . "の得点:" . $scores[$i + 1] . PHP_EOL;
                readline();
                if ($scores[$i + 1] <= 21 && $scores[$i + 1] > $dealerScore) {
                    echo "プレイヤー" . $i + 2 . "は勝ちました!" . PHP_EOL;
                    readline();
                } elseif ($scores[$i + 1] <= 21 && $scores[$i + 1] === $dealerScore) {
                    echo "プレイヤー" . $i + 2 . "とディーラーは引き分けです。" . PHP_EOL;
                    readline();
                } else {
                    echo "プレイヤー" . $i + 2 . "は負けました!" . PHP_EOL;
                    readline();
                }
            }
            echo "ブラックジャックを終了します。" . PHP_EOL;
            readline();
            exit;
        }
        // ディーラーがバーストしている場合
        if ($dealerScore > 21) {
            if ($scores[0] <= 21) {
                echo "あなた勝ちです!" . PHP_EOL;
                readline();
            } else {
                echo "あなたの負けです!" . PHP_EOL;
                readline();
            }
            for ($i = 0; $i < $this->numberOfPlayers - 1; $i++) {
                echo "プレイヤー" . $i + 2 . "の得点:" . $scores[$i + 1];
                readline();
                if ($scores[$i + 1] <= 21) {
                    echo "プレイヤー" . $i + 2 . "は勝ちました!" . PHP_EOL;
                    readline();
                } else {
                    echo "プレイヤー" . $i + 2 . "は負けました!" . PHP_EOL;
                    readline();
                }
            }
            echo "ブラックジャックを終了します。" . PHP_EOL;
            readline();
            exit;
        }
    }
}
