<?php

namespace Blackjack;

class Cpu extends Player {
  function hits() {
    if ($this->getSum() < 18) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}