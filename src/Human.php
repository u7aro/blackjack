<?php

namespace Blackjack;

/**
 * 人が Hit/Stand を入力して操作するプレイヤー用のクラス.
 */
class Human extends Player {

  /**
   * {@inheritdoc}
   */
  public function addCard(Card $card) {
    parent::addCard($card);

    // バーストした場合は出力して伝える.
    $sum = Game::calculateSum($this->getCards());
    if (21 < $sum) {
      print '合計が' . $sum . "になりバストしました\n";
    }
  }

  /**
   * {@inheritdoc}
   */
  public function hits() {
    print "\n";
    do {
      print $this->getName() . 'はカードを引きますか? (y/n): ';

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