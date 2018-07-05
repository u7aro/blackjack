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

// 3人以上のプレイヤーを追加することも可能.
// $ai = New Ai;
// $game->addPlayer($ai);

$game->setNumDecks(1);

$game->start();
