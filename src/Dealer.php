<?php

namespace Blackjack;

use cli;

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
    cli\line($this->getName() . ': ' . $string);
  }

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