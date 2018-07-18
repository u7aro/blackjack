<?php

namespace Blackjack;

/**
 * ディーラーのプレイヤークラス.
 */
class Dealer extends Player {

  /**
   * {@inheritdoc}
   */
  public function needsOneMoreCard() {
    $sum = Game::getPoints($this->getCards());
    if ($sum < 17) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}