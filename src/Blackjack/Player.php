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
   */
  final public function initRound() {
    $this->cards = [];
    $this->isStanding = FALSE;
  }

}
