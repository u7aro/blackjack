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
    $points = Game::getPoints($this->getCards());
    return $points < 17;
  }

}