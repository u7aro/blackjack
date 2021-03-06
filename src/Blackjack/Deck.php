<?php

namespace Blackjack;

/**
 * カードのデッキを制御するためのクラス.
 */
class Deck {

  /**
   * デッキが保持するカード.
   *
   * @var array
   */
  private $cards = [];

  /**
   * Deck オブジェクトを構築する.
   *
   * @param int $num_packs
   *   生成するデッキの数.
   */
  public function __construct($num_packs = 1) {
    $this->cards = Deck::generateCards($num_packs);
    shuffle($this->cards);
  }

  /**
   * 指定された数だけカードデッキを生成する.
   */
  public static function generateCards($num_packs = 1) {
    $cards = [];
    for ($num_packs_i = 0; $num_packs_i < $num_packs; $num_packs_i++) {
      // 各アルファベットの意味:
      //   S: スペード, D: ダイヤ, H: ハート, C: クラブ
      foreach (['S', 'D', 'H', 'C'] as $suit) {
        for ($number_i = 1; $number_i <= 13; $number_i++) {
          $cards[] = new Card($suit, $number_i);
        }
      }
    }
    return $cards;
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