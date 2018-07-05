<?php

namespace Blackjack;

/**
 * プレイヤー用の抽象クラス.
 */
abstract class Player implements GameCommunication {

  private $isStanding = FALSE;
  private $cards = [];
  protected $name = '';

  public function __construct($name = 'Player') {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  /**
   * カードを追加.
   *
   * @param object $card
   *   Card クラスのインスタンスオブジェクト.
   */
  public function addCard(Card $card) {
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
  final public function init() {
    $this->cards = [];
    $this->isStanding = FALSE;
  }

}
