<?php

require 'vendor/autoload.php';

$deck = new Blackjack\Deck;

$players = [];
$players[0] = new Blackjack\Human;
$players[1] = new Blackjack\Cpu;

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

    if (!$player->isStanding()) {
      if ($player->hits()) {
        $card = $deck->pullCard();
        $player->receiveCard($card);
        // 21が成立した場合、バーストした場合は強制的に終了.
        if (21 < $player->getSum()) {
          $player->setStanding();
        }
      }
      else {
        $player->setStanding();
      }
    }
  }
} while (!$players[0]->isStanding() || !$players[1]->isStanding());

print "RESULT -- \n";
for ($player_i = 0; $player_i < 2; $player_i++) {
  print_r($players[$player_i]->getCards());
  var_dump($players[$player_i]->getSum());
}