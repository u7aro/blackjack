<?php

namespace Blackjack;

/**
 * Game クラスのゲームに必要な情報をやり取りするためのインターフェイス.
 */
interface GameCommunication {

  /**
   * 「ヒット」を宣言して追加のカードが必要か真偽値で返す.
   *
   * @return bool
   *   追加のカードを望む場合は TRUE、望まない場合は FALSE.
   */
  public function hits();

  /**
   * カードを受け取る.
   *
   * @param object $card
   *   Card クラスのインスタンスオブジェクト.
   */
  public function addCard($card);

  /**
   * プレイヤーの名前を返す.
   *
   * @return string
   *   プレイヤー名の文字列.
   */
  public function getName();

}