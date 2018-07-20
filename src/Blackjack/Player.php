<?php

namespace Blackjack;

/**
 * プレイヤー用の抽象クラス.
 */
abstract class Player implements GameCommunication {

  /**
   * プレイヤーがスタンドしたか判断するフラグ.
   *
   * @var bool
   */
  private $isStanding = FALSE;

  /**
   * プレイヤーの手札.
   *
   * 配列の要素は Card クラスのインスタンス.
   *
   * @var array
   */
  private $cards = [];

  /**
   * プレイヤーの名前.
   *
   * @var string
   */
  protected $name = '';

  /**
   * ゲーム全体を通して勝った回数.
   *
   * @var int
   */
  private $wins = 0;

  /**
   * ゲーム全体を通して引き分けになった回数.
   *
   * @var int
   */
  private $draws = 0;

  /**
   * ゲーム全体を通して負けた回数.
   *
   * @var int
   */
  private $losses = 0;

  /**
   * Player オブジェクトの組み立て.
   *
   * @param string $name
   *   プレイヤーの名前.
   */
  public function __construct($name = 'Player') {
    $this->name = $name;
  }

  /**
   * プレイヤーの名前を返す.
   *
   * @param string
   *   プレイヤー名の文字列.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * カードを受け取る.
   *
   * @param object $card
   *   Card クラスのインスタンスオブジェクト.
   */
  public function takeCard(Card $card) {
    $this->cards[] = $card;
  }

  /**
   * 手札を返す.
   *
   * @return array
   *   Card クラスで生成したインスタンスオブジェクトの配列.
   */
  final public function getCards() {
    return $this->cards;
  }

  /**
   * スタンド状態であるか真偽値で返す.
   *
   * @return bool
   *   スタンド状態であれば TRUE、そうでなければ FALSE.
   */
  final public function isStanding() {
    return $this->isStanding;
  }

  /**
   * スタンド状態にする.
   */
  final public function setStanding() {
    $this->isStanding = TRUE;
  }

  /**
   * プレイヤーの状態を初期化する.
   *
   * @see Game::resetRound()
   */
  final public function resetRound() {
    $this->cards = [];
    $this->isStanding = FALSE;
  }

  /**
   * ゲーム中にオープンしたカードの情報を記憶する.
   *
   * 場に公開状態のカードが出るたびに呼び出されるメソッド. AI の計算などに利用する想定.
   * 自分の手札やディーラーが公開した手札も含まれる.
   *
   * @param object $card
   *   Card クラスのインスタンス.
   * @param bool $is_dealers_first
   *   ディーラーが公開した一枚目のカードである場合は TRUE、そうではない場合は FALSE.
   *
   * @see Game::dealInitialCards()
   */
  public function lookOpenedCard($card, $is_dealers_first) {
  }

  /**
   * デッキが新しく生成された時に通知を受け取る AI 用のフック.
   *
   * @param int $num_card_packs
   *   デッキに使用されるカードのパック数.
   *
   * @see Game::prepareDeck()
   */
  public function notifyResetDeck($num_card_packs) {
  }

  /**
   * ゲームの結果を回数として記録する.
   *
   * @param string $result
   *   勝ち `win`, 分け `draw`, 負け `lose` のいずれかの文字列.
   */
  public function addResult($result) {
    switch ($result) {
      case 'win':  $this->wins++;   break;
      case 'draw': $this->draws++;  break;
      case 'lose': $this->losses++; break;
    }
  }

  /**
   * 全てのゲームの戦績を配列で返す.
   *
   * @return array
   *   `wins`, `draws`, `losses` をキーとしてカウントした配列.
   *
   *   配列の構造: `[ 'wins' => COUNT, 'draws' => COUNT, 'losses' => COUNT ]`
   */
  public function getStats() {
    return [
      'wins'   => $this->wins,
      'draws'  => $this->draws,
      'losses' => $this->losses,
    ];
  }

}
