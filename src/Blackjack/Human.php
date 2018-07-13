<?php

namespace Blackjack;

use cli;

/**
 * 人が Hit/Stand を入力して操作するプレイヤー用のクラス.
 */
class Human extends Player {

  /**
   * {@inheritdoc}
   */
  public function hits() {
    $question = $this->getName() . 'はカードを引きますか';
    $default_choice = (Game::calculateSum($this->getCards()) < 17) ? 'y' : 'n';
    return cli\choose($question, 'yn', $default_choice) == 'y';
  }

}