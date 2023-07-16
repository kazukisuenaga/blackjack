<?php

namespace Blackjack;

require_once('BlackjackCalculator.php');
require_once('BlackjackDeck.php');
require_once('BlackjackRule.php');

class BlackjackOnePlayerRule implements BlackjackRule
{
    public function play()
    {
        echo "ブラックジャックを開始します。" . PHP_EOL;
        readline();
        // カードを2枚ずつ引く
        $playerHands = [];
        $dealerHands = [];
        $deckCreator = new BlackjackDeck();
        $deck = $deckCreator->prepareDeck();
        for ($i = 0; $i < 2; $i++) {
            $playerHands[] = array_shift($deck);
            $dealerHands[] = array_shift($deck);
        }
        echo "あなたの引いたカードは" . implode("の", $playerHands[0]) . "です。" . PHP_EOL;
        readline();
        echo "あなたの引いたカードは" . implode("の", $playerHands[1]) . "です。" . PHP_EOL;
        readline();
        echo "ディーラーの引いたカードは" . implode("の", $dealerHands[0]) . "です。" . PHP_EOL;
        readline();
        echo "ディーラーの引いた2枚目のカードはわかりません。" . PHP_EOL;
        readline();
        // 得点を計算してカードを引くか確認する
        $calculator = new BlackjackCalculator();
        $playerScore = $calculator->calculateScore($playerHands);
        while (true) {
            echo "あなたの現在の得点は" . $playerScore . "です。カードを引きますか？（Y/N）" . PHP_EOL;
            $input = trim(fgets(STDIN));
            if ($input === "Y") {
                $playerHands[] = array_shift($deck);
                $playerScore = $calculator->calculateScore($playerHands);
                $drawnCard = $playerHands[array_key_last($playerHands)];
                echo "あなたの引いたカードは" . implode("の", $drawnCard) . "です。" . PHP_EOL;
                readline();
                if ($playerScore > 21) {
                    echo "得点が21を超えました。あなたの負けです!" . PHP_EOL;
                    readline();
                    echo "ブラックジャックゲームを終了します。" . PHP_EOL;
                    readline();
                    exit;
                }
            } elseif ($input === "N") {
                break;
            } else {
                echo "カードを引く場合はY、引かない場合はNを入力してください。" . PHP_EOL;
            }
        }
        // プレイヤーがカードを引かない場合
        echo "ディーラーの引いた2枚目のカードは" . implode("の", $dealerHands[1]) . "でした。" . PHP_EOL;
        readline();
        $dealerScore = $calculator->calculateScore($dealerHands);
        while (true) {
            echo "ディーラーの現在の得点は" . $dealerScore . "です。" . PHP_EOL;
            readline();
            if ($dealerScore < 17) {
                $dealerHands[] = array_shift($deck);
                $drawnCard = $dealerHands[array_key_last($dealerHands)];
                $dealerScore = $calculator->calculateScore($dealerHands);
                echo "ディーラーの引いたカードは" . implode("の", $drawnCard) . "です。" . PHP_EOL;
                readline();
            } elseif ($dealerScore < $playerScore || $dealerScore > 21) {
                echo "あなたの得点は" . $playerScore . "です。" . PHP_EOL;
                readline();
                echo "あなたの勝ちです!" . PHP_EOL;
                readline();
                echo "ブラックジャックゲームを終了します。" . PHP_EOL;
                readline();
                exit;
            } elseif ($dealerScore > $playerScore) {
                echo "あなたの得点は" . $playerScore . "です。" . PHP_EOL;
                readline();
                echo "あなたの負けです!" . PHP_EOL;
                readline();
                echo "ブラックジャックゲームを終了します。" . PHP_EOL;
                readline();
                exit;
            } elseif ($dealerScore = $playerScore) {
                echo "あなたの得点は" . $playerScore . "です。" . PHP_EOL;
                readline();
                echo "今回は引き分けです!" . PHP_EOL;
                readline();
                echo "ブラックジャックゲームを終了します。" . PHP_EOL;
                readline();
                exit;
            }
        }
    }
}
