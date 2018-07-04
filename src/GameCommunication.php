<?php

namespace Blackjack;

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
   * @param sting $card
   *   `A-11` のフォーマットで生成されたテキストのカード文字列.
   */
  public function addCard($card);

  /**
   * プレイヤーの名前を返す.
   */
  public function getName();
}