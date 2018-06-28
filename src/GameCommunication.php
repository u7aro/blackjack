<?php

namespace Blackjack;

interface GameCommunication {
  public function hits();
  public function addCard();
}