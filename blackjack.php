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

$num_decks = 1;
$game = New Game($num_decks);

// 規定のインターフェイス(GameCommunication)を持ったプレイヤーをゲームに追加.
$dealer = New Dealer('ディーラー');
$game->addDealer($dealer);

$human = New Human('あなた');
$game->addPlayer($human);

// 2人以上のプレイヤーを追加することも可能.
$ai_player = New AiPlayer('AI Player');
$game->addPlayer($ai_player);

$game->start();
