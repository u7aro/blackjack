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
    $points = Game::getPoints($this->getCards());
    if ($points < 17) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}