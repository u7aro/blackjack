<?php

namespace Blackjack;

/**
 * Aiのプレイヤークラス.
 */
class AiPlayer extends Player {

  /**
   * {@inheritdoc}
   */
  public function needsOneMoreCard() {
    $sum = Game::getPoints($this->getCards());
    return $sum < 17;
  }

}