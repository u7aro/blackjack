<?php

namespace Blackjack;

/**
 * Game クラスのゲームに必要な情報をやり取りするためのインターフェイス.
 */
interface GameCommunication {

  /**
   * 追加のカードが必要か真偽値で返す.
   *
   * @return bool
   *   追加のカードを望む場合は TRUE、望まない場合は FALSE.
   */
  public function needsOneMoreCard();

  /**
   * ゲームで配られたカードを受け取る.
   *
   * @param object $card
   *   Card クラスのインスタンスオブジェクト.
   */
  public function takeCard(Card $card);

  /**
   * プレイヤーの名前を返す.
   *
   * @return string
   *   プレイヤー名の文字列.
   */
  public function getName();

  /**
   * プレイヤーがスタンド状態であるか真偽値で返す.
   *
   * @return bool
   *   スタンド状態であれば TRUE、そうでなければ FALSE.
   */
  public function isStanding();

  /**
   * プレイヤーをスタンド状態にする.
   */
  public function setStanding();

  /**
   * プレイヤーの状態を初期化する.
   */
  public function resetRound();

  /**
   * ゲーム中にオープンしたカードの情報を記憶する.
   *
   * 場に公開状態のカードが出るたびに呼び出されるメソッド. AI の計算などに利用する想定.
   * 自分の手札やディーラーの公開された手札も含まれる.
   *
   * @param object $card
   *   Card クラスのインスタンス.
   * @param bool $is_dealers_first
   *   ディーラーが公開した一枚目のカードである場合は TRUE、そうではない場合は FALSE.
   *
   * @see Game::dealInitialCards()
   */
  public function lookOpenedCard($card, $is_dealers_first);

  /**
   * デッキが新しく生成された場合に通知を受け取る.AI用のフック.
   *
   * @param int $num_card_packs
   *   デッキに使用されるカードのパック数.
   *
   * @see Game::prepareDeck()
   */
  public function notifyResetDeck($num_card_packs);

}