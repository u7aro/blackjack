# Blackjack

コマンドライン上でブラックジャックが遊べるプログラムです。

## 必須条件

- PHP 5.6 以上のみで動作します。
- ライブラリの管理に [Composer](https://getcomposer.org/) を利用しているため、プログラムの実行前にインストール作業が必要です。

## インストール方法

Composer を使って必要なライブラリをインストールしてください。

```shell
composer install
```

## 遊び方

ターミナルから次のコマンドで起動してください。

```shell
php blackjack.php
```

## 自動テスト

本プログラムは [PHPUnit](https://phpunit.de/) 用の自動テストのコードが含まれています。次のコマンドで自動テストを実行することができます。

```shell
./vendor/bin/phpunit
```