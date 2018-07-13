<?php

namespace Blackjack;

/**
 * Aiのプレイヤークラス.
 */
class AiPlayer extends Player {

  /**
   * {@inheritdoc}
   */
  public function hits() {
    $sum = Game::calculateSum($this->getCards());
    return $sum < 17;
  }

}