<?php

namespace Blackjack;

/**
 * 人が Hit/Stand を入力して操作するプレイヤー用のクラス.
 */
class Human extends Player {

  function hits() {
    do {
      Game::showHand($this->getCards());
      print 'カードを引きますか? (y/n): ';

      $input_string = rtrim(fgets(STDIN), "\n");
      if ($input_string == 'y' || $input_string == 'Y') {
        $hit = TRUE;
      }
      elseif ($input_string == 'n' || $input_string == 'N') {
        $hit = FALSE;
      }
    } while (!isset($hit));

    return $hit;
  }

}