# Blackjack

コマンドライン上でブラックジャックが遊べるプログラムです。

社内でプログラミングコンペをした時に作ったものです。

## 必須条件

- PHP 5.6 以上のみで動作します。`php` コマンドを実行できるようにしてください。
- ライブラリの管理に [Composer](https://getcomposer.org/) を利用していますので、`composer` コマンドを実行できるようにしてください。

## インストール方法

プログラムを起動するために Composer を使って必要なライブラリをインストールします。

PHPUnit や PHPDocumentor などの開発用のアプリケーションも入れる場合:

```shell
composer install
```

開発用のライブラリを入れない場合:

```shell
composer install --no-dev
```

## 遊び方

ターミナルから次のコマンドで起動してください。

```shell
php blackjack.php
```

## 起動オプション

本プログラムにはお好みに応じてゲームの設定を変更できるオプションが用意されています。コマンドライン上から `--help` オプションを付けて実行することで、オプションの解説が表示されます。

```shell
php blackjack.php --help
```

## 自動テスト

本プログラムは [PHPUnit](https://phpunit.de/) 用の自動テストのコードが含まれています。次のコマンドで自動テストを実行することができます。

```shell
./vendor/bin/phpunit
```

## PHPDocumentor を使ったドキュメントの生成方法

本ファイルのソースコードは [PHPDocumentor](https://www.phpdoc.org/) を使ってドキュメントを自動生成することが可能です。

次のコマンドを実行することでドキュメント群が生成されます。

```shell
./vendor/bin/phpdoc
````

ブラウザで `./phpdoc/index.html` のファイルを開くことでドキュメントを閲覧することができます。

クラス相関図も表示したい場合は、ドキュメントを生成する前に Graphviz をインストールしておく必要があります。OS X で Homebrew が入っている環境では次のコマンドでインストールすることが可能です。

```shell
brew install Graphviz
```

## 実装の解説

- PHP のインタフェースを使って Game クラスと Player クラスに必要なメソッドを定義しました。`GameCommunication.php` ファイルが設計書の代わりとなり、開発者が AI などを作る際にどんなメソッドが必要なのかを知ることができます。
- Player クラスは継承専用の抽象クラス (abstract) としています。
- Composer のオートローダーを使って、必要なライブラリを読み込んでいます。
  - 依存関係や require などを意識することなくファイルを分離することができています。
- WordPress CLI に付随する [PHP Command Line Tools](https://github.com/wp-cli/php-cli-tools) と言うライブラリを利用し、標準出力・入力に関する所謂車輪の再開発を阻止しました。
