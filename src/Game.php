<?php

namespace Blackjack;

/**
 * ゲームを行う.
 */
class Game {

  /**
   * 参加するプレイヤークラスを継承したインスタンスオブジェクトの配列.
   */
  private $players = [];

  /**
   * デッキの数.
   */
  private $num_decks = 1;

  /**
   * 渡された手札から合計値を計算して返す. エースを含む場合は21に近い数値を返す.
   *
   * @param array $cards
   *   Card クラスで生成されたインスタンスオブジェクトの配列.
   *
   * @return int
   *   手札の合計値.
   */
  public function calculateSum($cards) {
    $num_aces = 0;
    $point_sum = 0;
    foreach ($cards as $card) {
      // エース(1)の計算は複雑なので後回しにして計算する.
      $point = $card->getPoint();
      if ($point == 1) {
        $num_aces++;
      }
      else {
        $point_sum += $point;
      }
    }

    // エースがある場合は手札の合計に応じて加算する数値を変動.
    for ($num_aces_i = 0; $num_aces_i < $num_aces; $num_aces_i++) {
      // 手札の合計が11未満の場合は11として数え、それ以上の場合は1として数える.
      $point_sum += ($point_sum < 11) ? 11 : 1;
    }

    return $point_sum;
  }

  /**
   * 手札の合計値を返す. エースは1としてカウントする.
   *
   * @param array $cards
   *   Card クラスで生成されたインスタンスオブジェクトの配列.
   *
   * @return int
   *   手札の合計値.
   */
  public function calculateMinSum($cards) {
    $point_sum = 0;
    foreach ($cards as $card) {
      $point_sum += $card->getPoint();
    }
    return $point_sum;
  }

  /**
   * 渡されたカード配列を見やすくフォーマットして出力する.
   *
   * @param array
   *   Card クラスのインスタンスオブジェクトが格納された配列.
   */
  public function showHand($cards) {
    foreach ($cards as $card) {
      print $card->getString() . ' ';
    }

    $min_sum = Game::calculateMinSum($cards);
    $max_sum = Game::calculateSum($cards);
    if ($min_sum == $max_sum) {
      $point = $max_sum;
    }
    else {
      $point = $min_sum . '/' . $max_sum;
    }

    print ': (' . $point . ")\n";
  }

  /**
   * プレイヤーをゲームに追加.
   *
   * @param object $player
   *   GameCommunication インターフェイスを実装したクラスのインスタンスオブジェクト.
   */
  public function addPlayer($player) {
    $this->players[] = $player;
  }

  /**
   * 生成するデッキの数を設定.
   *
   * @param int $num_decks
   *   生成するデッキの数.
   */
  public function setNumDecks($num_decks) {
    $this->num_decks = $num_decks;
  }

  /**
   * 全てのプレイヤーがスタンド状態であることを確認して真偽値で返す.
   *
   * @return bool
   *   全てのプレイヤーがスタンド状態であれば TRUE 、そうではない場合は FALSE.
   */
  private function isEveryPlayerStanding() {
    // 一人でもゲームを続行している状態であれば、即時 FALSE を返す.
    foreach ($this->players as $player) {
      if (!$player->isStanding()) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * ゲームを開始する.
   */
  public function start() {
    $deck = new Deck($this->num_decks);

    // 各プレイヤーに2枚ずつカードを配る.
    for ($num_card_i = 0; $num_card_i < 2; $num_card_i++) {
      foreach ($this->players as $player) {
        $card = $deck->pullCard();
        $player->addCard($card);
      }
    }

    do {
      foreach ($this->players as $player) {
        if (!$player->isStanding()) {
          if ($player->hits()) {
            $card = $deck->pullCard();
            $player->addCard($card);
            // 21が成立した場合、バーストした場合は強制的に終了.
            // if (21 < $player->getSum()) {
            if (21 < Game::calculateSum($player->getCards())) {
              $player->setStanding();
            }
          }
          else {
            $player->setStanding();
          }
        }
      }
    } while (!$this->isEveryPlayerStanding());

    print "RESULT -- \n";
    foreach ($this->players as $player) {
      Game::showHand($player->getCards());
    }
  }

}