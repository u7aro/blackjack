<?php

namespace Blackjack;

/**
 * ディーラーのプレイヤークラス.
 */
class Dealer extends Player {

  /**
   * 名前を返す.
   */
  public function getName() {
    return 'ディーラー';
  }

  /**
   * ディーラーに発言(標準出力)させる.
   *
   * @param string $string
   *   発言する内容のテキスト.
   */
  public function say($string) {
    print "\033[0;31m" . $this->getName() . ': ' . $string . "\033[0m\n";
    sleep(1);
  }

  /**
   * {@inheritdoc}
   */
  public function addCard($card) {
    // 1名目の場合は手札を公開(出力)する.
    if (empty($this->getCards())) {
      $this->say('1枚目のカードは' . $card->getString() . 'です');
    }
    parent::addCard($card);
  }

  /**
   * {@inheritdoc}
   */
  public function hits() {
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