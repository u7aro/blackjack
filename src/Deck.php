<?php

namespace Blackjack;

/**
 * カードのデッキを制御するためのクラス.
 */
class Deck {
  private $cards = [];

  /**
   * Deck オブジェクトを構築します.
   *
   * @param int $num_decks
   *   生成するデッキの数.
   */
  public function __construct($num_decks = 1) {
    $this->generateCards($num_decks);
    shuffle($this->cards);
  }

  /**
   * 指定された数のカードデッキ(ジョーカーを除いた52枚のカード)を生成します.
   *
   * @param int $num_decks
   *   生成するデッキの数.
   */
  public function generateCards($num_decks) {
    for ($num_decks_i = 0; $num_decks_i < $num_decks; $num_decks_i++) {
      // 各アルファベットの意味:
      //   S: スペード, D: ダイヤ, H: ハート, C: クラブ
      foreach (['S', 'D', 'H', 'C'] as $suit) {
        for ($number_i = 1; $number_i <= 13; $number_i++) {
          $this->cards[] = new Card($suit, $number_i);
        }
      }
    }
  }

  /**
   * デッキの先頭からカードを一枚抜いて返す.
   *
   * @return string `A-11` の様なハイフンで区切ったシンボルと数値の組み合わせ.
   */
  public function pullCard() {
    return array_shift($this->cards);
  }

  /**
   * デッキに残っているカードを全て返す.
   *
   * @return array デッキに残っているカード全ての配列.
   */
  public function getCards() {
    return $this->cards;
  }
}