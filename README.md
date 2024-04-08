# 勤怠打刻アプリケーション

概要：ログイン機能と勤怠打刻機能

![初級案件アプリ画面](https://github.com/yuriF5/Atte/assets/152612024/610da898-907b-4aa1-9fbb-3e7aecfa869b)

## 作成した目的

ある企業の勤怠管理システムであり、人事評価の為作成してます

## 当アプリケーションの URL と開発環境

- https://github.com/yuriF5/Atte

  ※PC：Chrome/Firefox/Safari 最新バージョンを使用対象の為不足していると意図した画面が表示されない可能性があります

- 開発環境：http://localhost/
- phpMyAdmin:http://localhost:8080/

## 環境構築の為使用したリポジトリ

git clone git@github.com:estra-inc/confirmation-test-contact-form.git

## 機能一覧

- ログイン機能

名前とメールアドレス、任意の 8 文字以上のパスワード設定し登録を行う（初回のみ）

メールアドレスとパスワードを入力し勤怠打刻画面へログインする

- 勤怠機能

勤怠打刻画面にて勤怠の開始終了打刻（日を跨げ 1 回）と休憩時間の開始終了打刻（何度も打刻可能）ができる

勤怠打刻画面の日時一覧より、名前、勤怠、休憩時間の実績一覧画面を表示する

## 使用技術（実行環境）

- PHP 8.1
- Laravel 8.0
- MySQL 10.6
- OS windows 11

## テーブル設計

![初級案件テーブル](https://github.com/yuriF5/Atte/assets/152612024/3368df99-6550-461d-aa15-81e763577f30)

## ER 図

![初級模擬](https://github.com/yuriF5/Atte/assets/152612024/15efaedf-dfa1-477c-b39b-53bbded0cd36)

## Laravel 環境構築

docker-compose exec php bash

composer install

「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
.env に以下の環境変数を追加

DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass

アプリケーションキーの作成

php artisan key:generate

マイグレーションの実行

php artisan migrate

シーディングの実行

php artisan migrate

Fortifyのログイン認証の作成

composer require laravel/fortify

php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"

日時をDBへ保存する為 carbon を作成

composer require nesbot/carbon
