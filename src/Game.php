<?php

namespace Blackjack;

/**
 * プレイヤーへのカードの受け渡しや勝敗の判定などゲーム全体のコントロールを行う.
 */
class Game {

  /**
   * ディーラークラスのインスタンスオブジェクト.
   */
  private $dealer;

  /**
   * 参加するプレイヤークラスを継承したインスタンスオブジェクトの配列.
   */
  private $players = [];

  /**
   * ゲームで使用する Deck クラスのインスタンスオブジェクト.
   */
  protected $deck;

  public function __construct($num_decks) {
    $this->deck = new Deck($num_decks);
  }

  /**
   * ロゴを出力する.
   */
  public function printLogo() {
    print "\n";
    print "===================================================================================================\n";
    print "   ______   _____          _        ______  ___  ____      _____     _        ______  ___  ____    \n";
    print "  |_   _ \ |_   _|        / \     .' ___  ||_  ||_  _|    |_   _|   / \     .' ___  ||_  ||_  _|   \n";
    print "    | |_) |  | |         / _ \   / .'   \_|  | |_/ /        | |    / _ \   / .'   \_|  | |_/ /     \n";
    print "    |  __'.  | |   _    / ___ \  | |         |  __'.    _   | |   / ___ \  | |         |  __'.     \n";
    print "   _| |__) |_| |__/ | _/ /   \ \_\ `.___.'\ _| |  \ \_ | |__' | _/ /   \ \_\ `.___.'\ _| |  \ \_   \n";
    print "  |_______/|________||____| |____|`.____ .'|____||____|`.____.'|____| |____|`.____ .'|____||____|  \n";
    print "                                                                                                   \n";
    print "===================================================================================================\n";
  }

  /**
   * 渡された手札から合計値を計算して返す. エースを含む場合は21に近い役で数値を返す.
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
   * プレイヤーをゲームに追加.
   *
   * @param object $player
   *   GameCommunication インターフェイスを実装したクラスのインスタンスオブジェクト.
   */
  public function addPlayer($player) {
    $this->players[] = $player;
  }

  /**
   * ディーラーをゲームに追加する.
   *
   * @param object $dealer
   *   ディーラー用クラスのインスタンスオブジェクト.
   */
  public function addDealer($dealer) {
    $this->dealer = $dealer;
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
    // ゲームに参加しているプレイヤーが一人でもゲームを続行している状態であれば、
    // 即時 FALSE を返す.
    foreach ($this->players as $player) {
      if (!$player->isStanding()) {
        return FALSE;
      }
    }

    // 誰もゲームを続行していなければループを抜けて TRUE を返す.
    return TRUE;
  }

  public function formatPlayerHands($cards, $first_card_hides = FALSE) {
    $string = '';
    foreach ($cards as $card) {
      if ($first_card_hides && empty($string)) {
        $string .= '[??]';
      }
      else {
        $string .= '[' . $card->getString() . ']';
      }
    }

    return $string;
  }

  public function formatCardsPoint($cards) {
    $min_sum = Game::calculateMinSum($cards);
    $max_sum = Game::calculateSum($cards);
    if ($min_sum == $max_sum) {
      $point = $max_sum;
    }
    else {
      $point = $min_sum . '/' . $max_sum;
    }
    return $point;
  }

  public function printAllHands($is_players_turn = FALSE) {
    print "\n";
    print $this->dealer->getName() . ': ';
    print game::formatPlayerHands($this->dealer->getCards(), $first_card_hides = $is_players_turn);
    if (!$is_players_turn) {
      print ' (' . game::formatCardsPoint($this->dealer->getCards()) . ')';
    }
    print "\n";

    foreach ($this->players as $player) {
      print $player->getName() . ': '
        . game::formatPlayerHands($player->getCards())
      . ' (' . game::formatCardsPoint($player->getCards()) . ')'
      . "\n";
    }
  }

  /**
   * ゲームを開始する.
   */
  public function start() {
    $this->printLogo();

    // ディーラーとプレイヤーにカードを2枚ずつ配る.
    // Note: ディーラーとプレイヤーは同じ抽象クラスを継承しているため、同じメソッドが
    // 利用できる.
    $participants = array_merge([$this->dealer], $this->players);
    for ($num_card_i = 0; $num_card_i < 2; $num_card_i++) {
      foreach ($participants as $participant) {
        $card = $this->deck->pullCard();
        $participant->addCard($card);
      }
    }

    // 全員がスタンド（カードを引くのをやめた）状態になるまで繰り返しカードを引かせる.
    do {
      foreach ($this->players as $player) {
        $this->printAllHands($is_players_turn = TRUE);
        if (!$player->isStanding()) {
          if ($player->hits()) {
            $card = $this->deck->pullCard();
            $player->addCard($card);
            // バストした場合は強制的に終了.
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

    // ディーラーのターン.
    print "\n" . $this->dealer->getName() . "のターンです\n";
    while ($this->dealer->hits()) {
      $this->printAllHands();
      $card = $this->deck->pullCard();
      $this->dealer->addCard($card);
    }

    print "\n-- RESULT --\n";
    $this->printAllHands();
    print "\n";
  }

}