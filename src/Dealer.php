<?php

namespace Blackjack;

use cli;

/**
 * ディーラーのプレイヤークラス.
 */
class Dealer extends Player {

  /**
   * {@inheritdoc}
   */
  public function hits() {
    $sum = Game::calculateSum($this->getCards());
    if ($sum < 17) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}