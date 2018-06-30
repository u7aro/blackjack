<?php

/**
 * @file
 * Blackjack を開始するためのメインファイル.
 *
 * CLI上から次のようにコマンドを実行して起動する.
 *
 * php main.php
 */

namespace Blackjack;

require 'vendor/autoload.php';

$game = New Game;

// 規定のインターフェイス(GameCommunication)を持ったプレイヤーをゲームに追加.
$dealer = New Dealer;
$game->addPlayer($dealer);
$human = New Human;
$game->addPlayer($human);

$game->setNumDecks(1);

$game->start();