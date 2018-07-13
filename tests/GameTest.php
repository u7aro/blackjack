<?php

/**
 * ゲームクラスのテストを行うクラス.
 */
class GameTest extends PHPUnit\Framework\TestCase {

  static function forTestCalculateSum() {
    return [
      '2, 3 の組み合わせで 5 として計算されるか' => [
      5,
        [new Blackjack\Card('S', 2), new Blackjack\Card('H', 3)],
      ],
      '1, 10 の組み合わせで 21 として計算される' => [
        21,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 10)],
      ],
      '1, 10, 10 の組み合わせで 21 として計算される' => [
        21,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 10), new Blackjack\Card('D', 10)],
      ],
      '1, 1, 1, 1 の組み合わせで 14 として計算される' => [
        14,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 1), new Blackjack\Card('D', 1), new Blackjack\Card('C', 1)],
      ],
    ];
  }

  /**
   * ポイント計算が正しく行われているかテスト.
   * @dataProvider forTestCalculateSum
   */
  public function testCalculateSum($excepted, $cards) {
    $this->assertEquals($excepted, Blackjack\Game::calculateSum($cards));
  }

  static function forTestCalculateMinSum() {
    return [
      '2, 3 の組み合わせで 5 として計算されるか' => [
      5,
        [new Blackjack\Card('S', 2), new Blackjack\Card('H', 3)],
      ],
      '1, 10 の組み合わせで 11 として計算される' => [
        11,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 10)],
      ],
      '1, 10, 10 の組み合わせで 21 として計算される' => [
        21,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 10), new Blackjack\Card('D', 10)],
      ],
      '1, 1, 1, 1 の組み合わせで 4 として計算される' => [
        4,
        [new Blackjack\Card('S', 1), new Blackjack\Card('H', 1), new Blackjack\Card('D', 1), new Blackjack\Card('C', 1)],
      ],
    ];
  }

  /**
   * ポイント計算が期待通りかテスト.
   * @dataProvider forTestCalculateMinSum
   */
  public function testCalculateMinSum($excepted, $cards) {
    $this->assertEquals($excepted, Blackjack\Game::calculateMinSum($cards));
  }

}
