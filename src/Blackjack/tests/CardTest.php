<?php

namespace Blackjack\Tests;

use Blackjack\Card;

/**
 * Card クラスのテストを行うクラス.
 */
class CardTest extends \PHPUnit\Framework\TestCase {

  /**
   * testGetPoint() のデータプロバイダ.
   */
  static function forTestGetPoint() {
    return [
      '1 は 1 として数える' => [1, 1],
      '10 は 10 として数える' => [10, 10],
      '11 は 10 として数える' => [10, 11],
      '12 は 10 として数える' => [10, 12],
      '13 は 10 として数える' => [10, 13],
    ];
  }

  /**
   * getPoint() メソッドのテスト.
   * @dataProvider forTestGetPoint
   */
  public function testGetPoint($excepted, $number) {
    // 絵柄は何でも良いので D に固定.
    $card = new Card('D', $number);
    $this->assertEquals($excepted, $card->getPoint());
  }

}