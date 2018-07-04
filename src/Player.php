<?php

namespace Blackjack;

/**
 * プレイヤー用の抽象クラス.
 */
abstract class Player implements GameCommunication {

  private $isStanding = FALSE;
  private $cards = [];

  /**
   * カードを追加.
   *
   * @param object $card
   *   Card クラスのインスタンスオブジェクト.
   */
  public function addCard($card) {
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

}
