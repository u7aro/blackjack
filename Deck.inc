<?php

class Deck {
  private $cards = [];

  function __construct() {
    $this->generateCards();
    shuffle($this->cards);
  }

  /**
   * ジョーカー以外のカードのデッキを指定された数だけ生成する.
   *
   * @param integer $num_decks
   *   生成するデッキの数.
   */
  function generateCards($num_decks = 1) {
    // Symbol meaning
    // S: Spade, D: Diamond, H: Heart, C: Club
    $suits = ['S', 'D', 'H', 'C'];
    foreach ($suits as $suit) {
      for ($card_i = 1; $card_i <= 13; $card_i++) {
        $card = $suit . '-' . $card_i;
        $this->cards[] = $card;
      }
    }
  }

  /**
   * デッキの先頭からカードを一枚抜いて返す.
   *
   * @return string `A-11` の様なハイフンで区切ったシンボルと数値の組み合わせ.
   */
  function pullCard() {
    return array_shift($this->cards);
  }

  /**
   * デッキに残っているカードを全て返す.
   *
   * @return array デッキに残っているカード全ての配列.
   */
  function getCards() {
    return $this->cards;
  }
}