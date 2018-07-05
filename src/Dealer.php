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
    print "\033[0;31m" . $this->getName() . ': ' . $string . "\033[0m\n";
  }

  /**
   * {@inheritdoc}
   */
  public function hits() {
    print "\n";
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