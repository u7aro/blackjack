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
    $this->addDealer(New Dealer('ディーラー'));
  }

  /**
   * ゲーム開始時用のロゴを出力する.
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
   * ゲーム終了時用のメッセージを出力する.
   */
  public function printEndingMessage() {
    cli\line();
    cli\line('   ∧＿∧　　 ／￣￣￣￣￣');
    cli\line('（　´∀｀）＜　%yまた遊んでね！%n');
    cli\line('（　　　　）＼＿＿＿＿＿');
    cli\line(' ｜ ｜　|');
    cli\line('（_＿）＿）');
    cli\line('');
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
      // エースが含まれている場合は数だけ数えて計算を後回しにする.
      $card_points = $card->getPoint();
      if ($card_points == 1) {
        $num_aces++;
      }
      else {
        $points += $card_points;
      }
    }

    if ($num_aces) {
      // 1枚だけエースを 11 として数えて計算した時に、合計が 21 を超えなければ
      // その数値を使う。超えてしまう場合はポイントにエースの枚数を加える.
      $elevened_sum = $points + 11 + ($num_aces - 1);
      if ($elevened_sum <= 21) {
        $points = $elevened_sum;
      }
      else {
        $points += $num_aces;
      }
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
   * 手札のポイントを見やすいように整形して返す
   *
   * @param array $cards
   *   Card クラスのインスタンスの配列.
   *
   * @return string
   *   エースが含まれるかつ場合に最小値・最大値どちらでも計算できる時に `2|12` の様に
   *   整形した文字列.
   */
  protected static function formatPoints(array $cards) {
    $string = '';
    $min_points = self::getMinPoints($cards);
    $points = self::getPoints($cards);
    $output_points = ($min_points == $points) ? $points : $min_points . '|' . $points;
    $string .= "$output_points";

    return $string;
  }

  /**
   * 手札を見やすい文字列に整形して返す.
   *
   * @param array $cards
   *   Card インスタンスの配列.
   * @param bool $show_points
   *   手札のポイントを表示するかのフラグ.
   * @param bool $hides_first_card
   *   手札の一枚目のカードを非表示にするかのフラグ.
   *
   * @return string
   *   カードを模した文字列.
   */
  public static function formatHand(array $cards, $hides_first_card = FALSE) {
    $string = '';
    foreach ($cards as $card) {
      if ($hides_first_card && empty($card_string)) {
        $card_string = '????';
      }
      else {
        $card_string = $card->getString();
      }
      $string .= "[$card_string]";
    }

    return $string;
  }

  /**
   * プレイヤーの勝敗判定を行う.
   *
   * @param object $player
   *   Player クラスのインスタンス.
   */
  public function getResult(Player $player) {
    $dealer_points = self::getPoints($this->dealer->getCards());
    $player_points = self::getPoints($player->getCards());

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

  /**
   * デッキの状態を確認し、必要に応じてデッキを新しく準備する.
   */
  public function prepareDeck() {
    // 残りのカード枚数が `(参加プレイヤー + ディーラー) x 5枚以下` になったらデッキをリセット.
    $num_cards_deck_reset_limit = (count($this->players) + 1) * 5;
    if (!isset($this->deck) || count($this->deck->getCards()) < $num_cards_deck_reset_limit) {
      $this->deck = new Deck($this->num_packs);
      cli\line('%s組のカードを使って新しくデッキを生成しました', $this->num_packs);
      // 参加プレイヤー全員にデッキをリセットしたことを伝える(AI用).
      foreach ($this->players as $player){
        $player->notifyResetDeck($this->num_packs);
      }
    }
  }

  /**
   * 場に出たカードの情報を全てのプレイヤーインスタンスに伝える. AI 用のフック.
   *
   * @param object $card
   *   Card クラスのインスタンス. 安全性を考えると渡されるインスタンスはクローンされた
   *   ものがが望ましい.
   * @param bool $is_dealers
   *   ディーラーの手札の場合は TRUE.
   */
  private function showCardAllPlayers($card, $is_dealers = FALSE) {
    foreach ($this->players as $player) {
      $player->lookOpenedCard(clone $card, $is_dealers);
    }
  }

  /**
   * ディーラーとプレイヤーがラウンドで使用したカードと状態を初期値に戻す.
   */
  private function resetRound() {
    $participants = array_merge([$this->dealer], $this->players);
    foreach ($participants as $participant) {
      $participant->resetRound();
    }
  }

  /**
   * 最初のカードを各プレイヤーに2枚ずつ配り、画面に出力する.
   */
  private function dealInitialCards() {
    $participants = array_merge([$this->dealer], $this->players);
    for ($deal_card_round = 1; $deal_card_round <= 2; $deal_card_round++) {
      foreach ($participants as $participant_key => $participant) {
        $is_dealer = ($participant_key == 0);
        $card = $this->deck->pullCard();
        // ディーラーでは無い場合またはカード配りが2週目の場合.
        if (!$is_dealer || $deal_card_round === 2) {
          $this->showCardAllPlayers(clone $card, $is_dealer);
        }
        $participant->takeCard($card);
      }
    }

    // 配られたカードを画面出力する.
    $headers = ['Name', 'Hand', 'Points'];
    $data = [];
    $data[] = [
      $this->dealer->getName(),
      self::formatHand($this->dealer->getCards(), TRUE),
      '?',
    ];
    foreach ($this->players as $player) {
      $data[] = [
        $player->getName(),
        self::formatHand($player->getCards()),
        self::formatPoints($player->getCards()),
      ];
    }

    $table = new \cli\Table();
    $table->setHeaders($headers);
    $table->setRows($data);
    $table->setRenderer(new \cli\table\Ascii([30, 6, 6, 6]));
    $table->display();
  }

  /**
   * 勝敗結果を装飾して返す.
   *
   * @param string $result
   *   `win`, `draw`, `lose` のいずれかの文字列.
   *
   * @return string|null
   *   勝敗の結果を装飾した文字列.
   */
  public static function formatResult($result) {
    switch ($result) {
      case 'win':  return '%gWin%n';
      case 'draw': return '%yDraw%n';
      case 'lose': return '%rLose%n';
    }
  }

  /**
   * 勝敗を表示する.
   */
  public function printRoundResults() {
    cli\line("\n[ Round Result ]");

    $headers = ['Name', 'Hand', 'Points', 'Result'];
    $data = [];
    $data[] = [
      $this->dealer->getName(),
      self::formatHand($this->dealer->getCards()),
      self::getPoints($this->dealer->getCards()),
      '',
    ];
    foreach ($this->players as $player) {
      $data[] = [
        $player->getName(),
        self::formatHand($player->getCards()),
        self::getPoints($player->getCards()),
        self::formatResult($this->getResult($player)),
      ];
    }

    $table = new \cli\Table();
    $table->setHeaders($headers);
    $table->setRows($data);
    $table->setRenderer(new \cli\table\Ascii([30, 6, 6, 6]));
    $table->display();
 }

  /**
   * ゲームを開始する.
   */
  public function play() {
    // プレイヤーとディーラーは同じインターフェイスを持っていて共通のロジックで
    // ゲームを行うことができるため、同じループ処理でゲームを実行する.
    $participants = array_merge($this->players, [$this->dealer]);
    foreach ($participants as $participant) {
      $this->wait();
      cli\line("\n[ %sのターン ]", $participant->getName());
      $last_taken_cards = $participant->getCards();

      do {
        $this->wait();
        cli\out('{:last_taken_cards} ({:points}): ', [
          'last_taken_cards' => self::formatHand($last_taken_cards),
          'points' => self::formatPoints($participant->getCards()),
        ]);
        $this->wait();

        // ブラックジャック(21)を達成した時とバストした場合は、強制的にスタンド
        // 状態にしてループを抜け終了.
        $points = self::getPoints($participant->getCards());
        if (21 <= $points) {
          $participant->setStanding();
          if ($points == 21) {
            cli\line('%y%FBlackjack%n');
          }
          elseif (21 < $points) {
            cli\line('%rBust!%n');
          }
          break;
        }

        if ($participant->needsOneMoreCard()) {
          cli\line('%gHit%n');
          $card = $this->deck->pullCard();
          $participant->takeCard($card);
          $last_taken_cards = [$card];
          $this->showCardAllPlayers(clone $card);
        }
        else {
          $participant->setStanding();
          cli\line('%cStand%n');
        }
      } while(!$participant->isStanding());
    }
  }

  /**
   * 全プレイヤーにゲームの結果を追加する.
   */
  private function addResult() {
    foreach ($this->players as $player) {
      $result = $this->getResult($player);
      $player->addResult($result);
    }
  }

  /**
   * ゲーム全体のプレイヤーの戦績を表示する.
   */
  private function printGameStats() {
    cli\line("\n[ Game Statistics ]");

    $headers = ['Name', 'Wins', 'Draws', 'Losses'];
    $data = [];
    foreach ($this->players as $player) {
      // \cli\table で使用するデータ配列は、連想配列では正常に動作しないため、配列を
      // 結合して array_values() で添字を消す.
      $data[] = array_values(([$player->getName()] + $player->getStats()));
    }

    $table = new \cli\Table();
    $table->setHeaders($headers);
    $table->setRows($data);
    $table->setRenderer(new \cli\table\Ascii([30, 6, 6, 6]));
    $table->display();
  }

  /**
   * ゲームを開始して、ゲーム全体の流れを組み立てる.
   */
  public function start() {
    $this->printLogo();
    do {
      $round = isset($round) ? ($round + 1) : 1;
      cli\line("\n-- Round %s スタート --\n", $round);
      $this->wait();
      $this->resetRound();
      $this->prepareDeck();
      $this->dealInitialCards();
      $this->play();
      cli\line("\n-- Round %s 終了 --", $round);
      $this->printRoundResults();
      $this->addResult();
      $this->printGameStats();
      $continue = cli\choose("\nゲームを続行しますか", 'yn', 'y') == 'y';
    } while($continue);
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