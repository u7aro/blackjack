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
    $message = $this->getName() . ': ';
    $message .= Game::getHandScoreText($this->getCards());
    cli\line($message);
    $question = 'カードを引きますか';
    $default_choice = (Game::calculateSum($this->getCards()) < 17) ? 'y' : 'n';
    return cli\choose($question, 'yn', $default_choice) == 'y';
  }

}