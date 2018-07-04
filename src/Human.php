<?php

namespace Blackjack;

/**
 * 人が Hit/Stand を入力して操作するプレイヤー用のクラス.
 */
class Human extends Player {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'あなた';
  }

  /**
   * {@inheritdoc}
   */
  public function addCard($card) {
    parent::addCard($card);
    print $card->getString() . "を引きました\n";

    // バーストした場合は出力して伝える.
    $sum = Game::calculateSum($this->getCards());
    if (21 < $sum) {
      print '合計が' . $sum . "になりバーストしました\n";
    }
  }

  /**
   * {@inheritdoc}
   */
  public function hits() {
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