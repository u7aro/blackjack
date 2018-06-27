<?php

namespace Blackjack;

class Human extends Player {
  function isContinue() {
    do {
      print_r($this->getCards());
      print "Current point: " . $this->getSum() . "\n";
      print 'カードを引きますか? (y/n): ';

      $input_string = rtrim(fgets(STDIN), "\n");
      if ($input_string == 'y' || $input_string == 'Y') {
        $continue = TRUE;
      }
      elseif ($input_string == 'n' || $input_string == 'N') {
        $continue = FALSE;
      }
    } while (!isset($continue));

    return $continue;
  }
}