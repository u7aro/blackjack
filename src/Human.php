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

    $default_choice = (Game::calculateSum($this->getCards()) < 17) ? 'y' : 'n';
    $question = $this->getName() . ': カードを引きますか';
    return \cli\choose($question, 'yn', $default_choice) == 'y';
  }

}