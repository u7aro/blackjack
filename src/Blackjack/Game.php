<?php

namespace Blackjack;

use cli;

/**
 * プレイヤーへのカードの受け渡しや勝敗の判定などゲーム全体のコントロールを行う.
 */
class Game {

  /**
   * 次の処理を実行する待ち時間. 単位はミリセコンド(100万分の1秒).
   */
  private $waitingTime;

  /**
   * ディーラークラスのインスタンスオブジェクト.
   *
   * @var object
   */
  private $dealer;

  /**
   * 参加するプレイヤークラスを継承したインスタンスオブジェクトの配列.
   *
   * @var array
   */
  private $players = [];

  /**
   * 使用するデッキの数.
   *
   * @var int
   */
  private $num_packs;

  /**
   * ゲームで使用する Deck クラスのインスタンスオブジェクト.
   *
   * @var object
   */
  protected $deck;

  /**
   * Game オブジェクトの組み立て.
   *
   * @param int $num_packs
   *   使用するカードのパック数.
   */
  public function __construct($num_packs) {
    $this->num_packs = $num_packs;

    // 規定のインターフェイス(GameCommunication)を持ったプレイヤーをゲームに追加.
    $this->addDealer(New Dealer('ディーラー'));
  }

  /**
   * ロゴを出力する.
   */
  public function printLogo() {
    cli\line();
    cli\line("===============================================================================================");
    cli\out("%y");
    cli\line(" ______   _____          _        ______  ___  ____      _____     _        ______  ___  ____  ");
    cli\line("|_   _ \ |_   _|        / \     .' ___  ||_  ||_  _|    |_   _|   / \     .' ___  ||_  ||_  _| ");
    cli\line("  | |_) |  | |         / _ \   / .'   \_|  | |_/ /        | |    / _ \   / .'   \_|  | |_/ /   ");
    cli\line("  |  __'.  | |   _    / ___ \  | |         |  __'.    _   | |   / ___ \  | |         |  __'.   ");
    cli\line(" _| |__) |_| |__/ | _/ /   \ \_\ `.___.'\ _| |  \ \_ | |__' | _/ /   \ \_\ `.___.'\ _| |  \ \_ ");
    cli\line("|_______/|________||____| |____|`.____ .'|____||____|`.____.'|____| |____|`.____ .'|____||____|");
    cli\line("%n");
    cli\line("===============================================================================================");
  }

  /**
   * ロゴを出力する.
   */
  public function printEndingMessage() {
    cli\line();
    cli\out("%y");
    cli\line('   ∧＿∧　　 ／￣￣￣￣￣');
    cli\line('（　´∀｀）＜　また遊んでね！');
    cli\line('（　　　　）＼＿＿＿＿＿');
    cli\line(' ｜ ｜　|');
    cli\line('（_＿）＿）');
    cli\line("%n");
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
  public static function getPoints(array $cards) {
    $num_aces = 0;
    $points = 0;
    foreach ($cards as $card) {
      // エース(1)の計算は複雑なので後回しにして計算する.
      $card_points = $card->getPoint();
      if ($card_points == 1) {
        $num_aces++;
      }
      else {
        $points += $card_points;
      }
    }

    // エースがある場合は手札の合計に応じて加算する数値を変動.
    for ($num_aces_i = 0; $num_aces_i < $num_aces; $num_aces_i++) {
      // 手札の合計が11未満の場合は11として数え、それ以上の場合は1として数える.
      $points += ($points < 11) ? 11 : 1;
    }

    return $points;
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
  public static function getMinPoints(array $cards) {
    $points = 0;
    foreach ($cards as $card) {
      $points += $card->getPoint();
    }
    return $points;
  }

  /**
   * プレイヤーをゲームに追加.
   *
   * @param object $player
   *   GameCommunication インターフェイスを実装したクラスのインスタンスオブジェクト.
   */
  public function addPlayer(Player $player) {
    $this->players[] = $player;
  }

  /**
   * ディーラーをゲームに追加する.
   *
   * @param object $dealer
   *   ディーラー用クラスのインスタンスオブジェクト.
   */
  public function addDealer(Dealer $dealer) {
    $this->dealer = $dealer;
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

  public static function formatHand(array $cards, $first_card_hides = FALSE) {
    $string = '';
    foreach ($cards as $card) {
      if ($first_card_hides && empty($string)) {
        $string .= '[???]';
      }
      else {
        $string .= '[' . $card->getString() . ']';
      }
    }

    return $string;
  }

  public function printAllHands($is_players_turn = FALSE) {
    $message = $this->dealer->getName() . ': '
      . Game::formatHand($this->dealer->getCards(), $first_card_hides = $is_players_turn);
    if (!$is_players_turn) {
      $message .= ' (' . Game::formatCardsPoint($this->dealer->getCards()) . ')';
    }
    cli\line($message);

    foreach ($this->players as $player) {
      cli\line($player->getName() . ': '
        . Game::formatHand($player->getCards())
        . Game::formatHandScore($player->getCards()));
    }
  }

  public function isPlayerWin(Player $player) {
    $dealer_points = Game::getPoints($this->dealer->getCards());
    $player_points = Game::getPoints($player->getCards());

    // プレイヤーがバストした場合またはディーラーのポイントを下回る場合は負け.
    if (21 < $player_points || ($dealer_points <= 21 && $player_points < $dealer_points)) {
      return 'lose';
    }
    // ディーラーがバストした場合またはディーラーのポイントを上回った場合は勝ち.
    elseif (21 < $dealer_points || $dealer_points < $player_points) {
      return 'win';
    }

    return 'draw';
  }

  public function formatHandScore($cards) {
    $min_points = Game::getMinPoints($cards);
    $points = Game::getPoints($cards);
    $output_points = ($min_points == $points) ? $points : $min_points . '/' . $points;
    $output = "(%m$output_points%n)";

    if ($points == 21) {
      $output .= ' %y%FBlackjack%n';
    }
    elseif (21 < $points) {
      $output .= ' %rBust!%n';
    }

    return $output;
  }

  /**
   * Player インスタンスにカードを引くか判断させる.
   */
  public function askDeal(Player $player) {
    if ($player->needsOneMoreCard()) {
      cli\line($player->getName() . ': %gHit%n');
      $player->takeCard($this->deck->pullCard());

      $this->wait();
      cli\out($player->getName() . ': '
        . Game::formatHand($player->getCards())
        . Game::formatHandScore($player->getCards()));

      $points = Game::getPoints($player->getCards());
      // ブラックジャック(21)とバストした場合は強制的に終了.
      if (21 <= $points) {
        $player->setStanding();
      }
      cli\line();
      $this->wait();
    }
    else {
      $player->setStanding();
      cli\line($player->getName() . ': %cStand%n');
      $this->wait();
    }
  }

  /**
   * デッキの状態を確認し、必要に応じてデッキを新しく準備する.
   */
  public function prepareDeck() {
    // 残りのカード枚数が `(参加プレイヤー + ディーラー) x 5枚以下` になったらデッキをリセット.
    $num_cards_deck_reset_limit = (count($this->players) + 1) * 5;
    if (!isset($this->deck) || count($this->deck->getCards()) < $num_cards_deck_reset_limit) {
      $this->deck = new Deck($this->num_packs);
      cli\line($this->num_packs . '組のカードを使って新しくデッキを生成しました');
      // TODO:
      // 参加プレイヤーにデッキをリセットしたことを伝える(AI用).
    }
  }

  /**
   * 最初のカードを各プレイヤーに2枚ずつ配る.
   */
  public function dealInitCards() {
    // ディーラーとプレイヤーの状態を初期化してカードを2枚ずつ配る.
    // Note: ディーラーとプレイヤーは同じ抽象クラスを継承しているため、同じメソッドが
    // 利用できる.
    $participants = array_merge([$this->dealer], $this->players);
    foreach ($participants as $participant) {
      $participant->initRound();
      for ($num_card_i = 0; $num_card_i < 2; $num_card_i++) {
        $card = $this->deck->pullCard();
        $participant->takeCard($card);
        // TODO:
        // カードを受け取っていない他のプレイヤーにも場に出たカードの情報を伝える(AI用).
      }
    }
  }

  /**
   * 勝敗を表示する.
   */
  public function showRoundResults() {
    cli\line("\n-- RESULT --");
    $message = $this->dealer->getName() . ': ' . Game::formatHand($this->dealer->getCards());
    cli\line($message);
    foreach ($this->players as $player) {
      $status = $this->isPlayerWin($player);
      cli\line($player->getName() . ' ... ' . $status);
    }
  }

  /**
   * 全員がスタンド（カードを引くのをやめた）状態になるまで繰り返しカードを引かせる.
   */
  public function doPlayersTurn() {
    do {
      foreach ($this->players as $player) {
        if (!$player->isStanding()) {
          $this->askDeal($player);
        }
      }
    } while (!$this->isEveryPlayerStanding());
  }

  /**
   *  ディーラーのターン.
   */
  public function doDealerTurn() {
    do {
      $this->askDeal($this->dealer);
    } while (!$this->dealer->isStanding());
  }

  /**
   * ゲームを開始して、ゲーム全体の流れを組み立てる.
   */
  public function start() {
    $this->printLogo();
    do {
      $round = isset($round) ? ($round + 1) : 1;
      cli\line('Round ' . $round . ' スタート');
      $this->wait();
      $this->prepareDeck();
      $this->dealInitCards();
      $this->printAllHands($is_players_turn = TRUE);
      $this->wait();
      $this->doPlayersTurn();
      $this->doDealerTurn();
      $this->showRoundResults();
      cli\line('Round ' . $round . ' 終了');
      $continue = cli\choose("--\nゲームを続行しますか", 'yn', 'y') == 'y';
    } while($continue);
    $this->wait();
    $this->printEndingMessage();
  }

  /**
   * 待ち時間をセットする.
   *
   * @param int $time
   *   待ち時間のミリセコンド秒.
   */
  public function setWaitingTime($time) {
    $this->waitingTime = $time;
  }

  /**
   * 設定されている時間だけ待つ.
   */
  public function wait() {
    usleep($this->waitingTime);
  }

}