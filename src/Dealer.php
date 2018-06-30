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
  function say($string) {
    print 'Dealer say: ' . $string . "\n";
    sleep(1);
  }

  function addCard($card) {
    // 1名目の場合は手札を公開(出力)する.
    if (empty($this->getCards())) {
      $this->say('ディーラーの1枚目のカードは' . $card->getString() . 'です');
    }
    parent::addCard($card);
  }

  function hits() {
    $sum = Game::calculateSum($this->getCards());

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