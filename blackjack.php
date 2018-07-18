<?php

/**
 * @file
 * Blackjack を開始するためのメインファイル.
 */

namespace Blackjack;

require 'vendor/autoload.php';

// コンソールオプションの設定.
$strict = in_array('--strict', $_SERVER['argv']);
$arguments = new \cli\Arguments(compact('strict'));
$arguments->addFlag(['help', 'h'], 'Show this help screen');
$arguments->addOption(['name', 'n'], [
  'default' => 'You',
  'description' => 'あなたの名前を設定します',
]);
$arguments->addOption(['wait', 'w'], [
  'default' => 500000,
  'description' => '処理を実行する時の待ち時間(ms)を設定します',
]);
$arguments->addOption(['num-ai', 'a'], [
  'default' => 0,
  'description' => 'AIプレイヤーの数を設定します',
]);
$arguments->addOption(['pack', 'p'], [
  'default'     => 1,
  'description' => '使用するカードのパック数を設定します',
]);
$arguments->parse();

if ($arguments['help']) {
	echo $arguments->getHelpScreen();
  echo "\n\n";
  exit;
}

$game = New Game($arguments['pack']);
$human = New Human($arguments['name']);
$game->addPlayer($human);
if ($arguments['num-ai']) {
  for ($ai_serial = 1; $ai_serial <= $arguments['num-ai']; $ai_serial++) {
    $name = 'AI Player ' . $ai_serial;
    $game->addPlayer(New AiPlayer($name));
  }
}
$game->setWaitingTime($arguments['wait']);
$game->start();
