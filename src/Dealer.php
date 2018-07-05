<?php

namespace Blackjack;

/**
 * ディーラーのプレイヤークラス.
 */
class Dealer extends Player {

  /**
   * ディーラーに発言(標準出力)させる.
   *
   * @param string $string
   *   発言する内容のテキスト.
   */
  public function say($string) {
    \cli\line($this->getName() . ': ' . $string);
  }

  /**
   * {@inheritdoc}
   */
  public function hits() {
    \cli\line();
    $sum = Game::calculateSum($this->getCards());
    sleep(1);
    if ($sum < 17) {
      $this->say('Hit');
      return TRUE;
    }
    else {
      $this->say('Stand');
      return FALSE;
    }
  }

}