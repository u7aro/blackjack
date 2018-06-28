<?php

namespace Blackjack;

interface GameCommunication {
  /**
   * 「ヒット」を宣言して追加のカードを貰うか判定する.
   *
   * @return bool
   *   追加のカードを望む場合は TRUE、望まない場合は FALSE.
   */
  public function hits();

  /**
   * カードを受け取る.
   */
  public function addCard();
}