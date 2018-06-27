<?php

namespace Blackjack;

class Cpu extends Player {
  function isContinue() {
    if ($this->getSum() < 18) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}