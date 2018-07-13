<?php

/**
 * Dealer クラスのテストを行うクラス.
 */
class DealerTest extends PHPUnit\Framework\TestCase {

  static function forTestHits() {
    return [
      '合計が 12 の時は TRUE' => [
        TRUE,
        [new Blackjack\Card('D', 10), new Blackjack\Card('D', 2)],
      ],
      '合計が 16 の時は TRUE' => [
        TRUE,
        [new Blackjack\Card('D', 10), new Blackjack\Card('D', 6)],
      ],
      '合計が 17 の時は FALSE' => [
        FALSE,
        [new Blackjack\Card('D', 10), new Blackjack\Card('D', 7)],
      ],
      '合計が 21 の時は FALSE' => [
        FALSE,
        [new Blackjack\Card('D', 10), new Blackjack\Card('D', 1)],
      ],
    ];
  }

  /**
   * hits() メソッドのテスト.
   * @dataProvider forTestHits
   */
  public function testHits($excepted, $cards) {
    $dealer = new Blackjack\Dealer();
    foreach ($cards as $card) {
      $dealer->addCard($card);
    }
    $this->assertEquals($excepted, $dealer->hits());
  }

}