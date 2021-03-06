<?php

namespace Blackjack;

/**
 * カードを制御するためのクラス.
 */
class Card {

  /**
   * カードの絵柄.
   *
   * @var string
   */
  private $suit;

  /**
   * string カードの番号.
   *
   * @var int
   */
  private $number;

  /**
   * Card オブジェクトを構築します.
   *
   * @param string $suit
   *   カードの絵柄.
   *
   * @param int $number
   *   カードの数値. ジャック、クイーン、キングはそれぞれ 11, 12, 13 とする.
   */
  public function __construct($suit, $number) {
    $this->suit = $suit;
    $this->number = $number;
  }

  /**
   * ポイントを返す. J, Q, K は10として数える.
   *
   * エース(1)は`11`としても数えられるが、それは手札の計算の時に計算する.
   *
   * @return int
   *   カード番号に応じた1から10の整数.
   */
  public function getPoint() {
    if (10 < $this->number) {
      $point = 10;
    }
    else {
      $point = $this->number;
    }

    return $point;
  }

  /**
   * 絵柄と数字のカード情報を表した文字列を返す.
   *
   * @return string
   *   返す文字列の例:
   *   - 'S11' スペードの11
   *   - 'D5' ダイヤの5
   */
  public function getString() {
    switch ($this->suit) {
      case 'S': $suit = '♠ '; break;
      case 'D': $suit = '♦ '; break;
      case 'H': $suit = '♥ '; break;
      case 'C': $suit = '♣ '; break;
    }

    switch ($this->number) {
      case '1':  $number = 'A'; break;
      case '11': $number = 'J'; break;
      case '12': $number = 'Q'; break;
      case '13': $number = 'K'; break;
      default:   $number = $this->number;
    }

    return $suit . str_pad($number, 2, ' ', STR_PAD_LEFT);
  }

  /**
   * カードの絵柄を表すアルファベットを返す.
   *
   * @return string
   *   カードの絵柄.
   */
  public function getSuit() {
    return $this->suit;
  }

  /**
   * カードの番号を返す.
   * @return int
   *   カードの番号. Aは1、J〜Kは11〜13になる.
   */
  public function getNumber() {
    return $this->number;
  }

}