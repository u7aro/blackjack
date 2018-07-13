<?php

use Blackjack\Game;
use Blackjack\Card;

/**
 * ゲームクラスのテストを行うクラス.
 */
class GameTest extends PHPUnit\Framework\TestCase {

  static function forTestCalculateSum() {
    return [
      '2, 3 の組み合わせで 5 として計算されるか' => [
        5,
        [new Card('S', 2), new Card('H', 3)],
      ],
      '1, 10 の組み合わせで 21 として計算される' => [
        21,
        [new Card('S', 1), new Card('H', 10)],
      ],
      '1, 10, 10 の組み合わせで 21 として計算される' => [
        21,
        [new Card('S', 1), new Card('H', 10), new Card('D', 10)],
      ],
      '1, 1, 1, 1 の組み合わせで 14 として計算される' => [
        14,
        [new Card('S', 1), new Card('H', 1), new Card('D', 1), new Card('C', 1)],
      ],
    ];
  }

  /**
   * ポイント計算が正しく行われているかテスト.
   * @dataProvider forTestCalculateSum
   */
  public function testCalculateSum($excepted, $cards) {
    $this->assertEquals($excepted, Game::calculateSum($cards));
  }

  static function forTestCalculateMinSum() {
    return [
      '2, 11 の組み合わせは 5 として計算' => [
        12, [new Card('S', 2), new Card('H', 11)],
      ],
      '1, 11 の組み合わせは 11 として計算' => [
        11, [new Card('S', 1), new Card('H', 11)],
      ],
      '1, 10, 11 の組み合わせは 21 として計算' => [
        21, [new Card('S', 1), new Card('H', 10), new Card('D', 11)],
      ],
      '1, 10, 11, 12 の組み合わせは 31 として計算' => [
        31, [new Card('S', 1), new Card('H', 10), new Card('D', 11), new Card('C', 12)],
      ],
      '1, 1 の組み合わせは 2 として計算' => [
        2, [new Card('S', 1), new Card('H', 1)],
      ],
      '1, 1, 1 の組み合わせは 3 として計算' => [
        3, [new Card('S', 1), new Card('H', 1), new Card('D', 1)],
      ],
      '1, 1, 1, 1 の組み合わせは 4 として計算' => [
        4, [new Card('S', 1), new Card('H', 1), new Card('D', 1), new Card('C', 1)],
      ],
    ];
  }

  /**
   * ポイント計算が期待通りかテスト.
   * @dataProvider forTestCalculateMinSum
   */
  public function testCalculateMinSum($excepted, $cards) {
    $this->assertEquals($excepted, Game::calculateMinSum($cards));
  }

}
