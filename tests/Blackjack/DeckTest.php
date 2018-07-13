<?php

namespace Blackjack\Tests;

use Blackjack\Deck;

/**
 * カードクラスのテストを行うクラス.
 */
class DeckTest extends \PHPUnit\Framework\TestCase {

  /**
   * generateCards() メソッドのテスト.
   */
  public function testGenerateCards() {
    // 指定されたデッキ数だけカードが生成されているか.
    $cards = Deck::generateCards(1);
    $this->assertCount(52, $cards);
    $cards = Deck::generateCards(2);
    $this->assertCount(104, $cards);

    $deck = [
      'H' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
      'D' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
      'C' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
      'S' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
    ];

    // 全てのカードが生成されているか.
    $cards = Deck::generateCards(1);
    foreach ($cards as $card) {
      $suit = $card->getSuit();
      $number = $card->getNumber();
      $key = array_search($number, $deck[$suit]);
      $existsCard = isset($deck[$suit][$key]);
      $this->assertTrue($existsCard);
      if ($existsCard) {
        unset($deck[$suit][$key]);
      }
    }
    $deck = array_filter($deck);
    $this->assertCount(0, $deck);
  }

}