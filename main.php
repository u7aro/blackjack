<?php

require_once 'Deck.inc';
require_once 'Player.inc';
require_once 'Human.inc';
require_once 'Cpu.inc';

$deck = new Deck;

$players = [];
$players[0] = new Human;
$players[1] = new Cpu;

// 各プレイヤーに2枚ずつカードを配る.
for ($num_card_i = 0; $num_card_i < 2; $num_card_i++) {
  for ($player_i = 0; $player_i < 2; $player_i++) {
    $card = $deck->pullCard();
    $players[$player_i]->receiveCard($card);
  }
}

do {
  for ($player_i = 0; $player_i < 2; $player_i++) {
    // ソースコードを見やすくするためオブジェクト参照を参照している変数を $player にする.
    $player = $players[$player_i];

    if (!$player->isFinished()) {
      if ($player->isContinue()) {
        $card = $deck->pullCard();
        $player->receiveCard($card);
        // 21が成立した場合、バーストした場合は強制的に終了.
        if (21 < $player->getSum()) {
          $player->setFinished();
        }
      }
      else {
        $player->setFinished();
      }
    }
  }
} while (!$players[0]->isFinished() || !$players[1]->isFinished());

print "RESULT -- \n";
for ($player_i = 0; $player_i < 2; $player_i++) {
  print_r($players[$player_i]->getCards());
  var_dump($players[$player_i]->getSum());
}