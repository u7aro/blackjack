<?php

namespace Blackjack;

abstract class Player {
  private $isFinished = FALSE;
  private $cards = [];
  private $sum = 0;

  function receiveCard($card) {
    $this->cards[] = $card;
    $this->calculateSum();
  }

  function getCards() {
    return $this->cards;
  }

  function getSum() {
    return $this->sum;
  }

  function calculateSum() {
    // 手札の合計値.
    $card_sum = 0;

    $num_ace = 0;
    foreach ($this->cards as $card) {
      list($suit, $number) = explode('-', $card);
      // エース(1)の計算は複雑なので後回しにして計算する.
      if ($number == 1) {
        $num_ace++;
      }
      // ジャック(11)・クイーン(12)・キング(13)は10として数える.
      else if (10 < $number) {
        $card_sum += 10;
      }
      else {
        $card_sum += $number;
      }
    }

    // エースがある場合は手札の合計に応じて加算する数値を変動.
    for ($num_ace_i = 0; $num_ace_i < $num_ace; $num_ace_i++) {
      if ($card_sum <= 10) {
        $card_sum += 11;
      }
      else {
        $card_sum += 1;
      }
    }

    $this->sum = $card_sum;
  }

  function isContinue() {
    return FALSE;
  }

  function isFinished() {
    return $this->isFinished;
  }

  function setFinished() {
    $this->isFinished = TRUE;
  }
}
