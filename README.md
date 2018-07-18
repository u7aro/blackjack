# Blackjack

コマンドライン上でブラックジャックが遊べるプログラムです。

## 必須条件

- PHP 5.6 以上のみで動作します。
- ライブラリの管理に [Composer](https://getcomposer.org/) を利用しているため、プログラムの実行前にインストール作業が必要です。

## インストール方法

Composer を使って必要なライブラリをインストールしてください。

PHPUnit などの開発用のライブラリを入れる場合:

```shell
composer install
```

開発用のライブラリを入れない場合

```shell
composer install --no-dev
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

## 実装の解説

- PHP のインタフェースを使って Game クラスと Player クラスに必要なメソッドを定義しました。`GameCommunication.php` ファイルが設計書の代わりとなり、開発者が AI などを作る際にどんなメソッドが必要なのかを知ることができます。
- Composer のオートローダーを使って、必要なライブラリを読み込んでいます。
  - 依存関係や require などを意識することなくファイルを分離することができています。
- WordPress CLI に付随する [PHP Command Line Tools](https://github.com/wp-cli/php-cli-tools) と言うライブラリを利用し、標準出力・入力に関する所謂車輪の再開発を阻止しました。