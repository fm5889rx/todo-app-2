# Todoアプリ（中級）

## 開発環境構築

旧教材の通りに`git clone`で構築すると、M4 Macでは環境構築に失敗するので、<br>
`Docker compose`を使わずに`Larsavel Sail`で開発することとした。

### 使用コマンド

- Laravelプロジェクトの作成
```
cd laravel
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    composer create-project laravel/laravel:^10.0 todo-app-2
```

- Laravel Sailをインストール
```
cd todo-app-2
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    composer require laravel/sail --dev
```

- Sailの設定ファイルをパブリッシュ（MySQLを選択）
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    -e COMPOSER_CACHE_DIR=/tmp/composer_cache \
    laravelsail/php82-composer:latest \
    php artisan sail:install --with=mysql
```

- `.env`ファイルの確認
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

- phpMyAdminの追加
`compose.yaml`ファイル中の`mysql`の記述の下に以下を追加。<br>
**インデントレベルに注意！**
```php
    phpmyadmin:
        image: 'phpmyadmin:latest'
        ports:
            - '${FORWARD_PHPMYADMIN_PORT:-8080}:80'
        environment:
            PMA_HOST: mysql
            PMA_USER: '${DB_USERNAME}'
            PMA_PASSWORD: '${DB_PASSWORD}'
        networks:
            - sail
        depends_on:
            - mysql
```

- Sailの起動<br>
※以下、エイリアス登録済みとして記述
```
sail up -d
```

- アプリケーションキーの登録
```
sail artisan key:generate
```

### 動作確認

- Laravelの動作確認
ブラウザで`http://localhost`にアクセスし、Laravelのウェルカム画面が表示されることを確認。

- phpMyAdninの動作確認
ブラウザで`http://localhost:8080`にアクセスし、phpMyAdminnが表示されていることを確認。<br>
⚠️旧教材ではMySQLのバージョンが古くて、M4 Macではうまく構築されず、phpMyAdminが接続エラーになるため。



## 提供ファイルのインポート

今回、フロントエンドプログラムもblade/CSSを作成するので、以降は旧教材に従って各機能を実装。


## 教材からの変更点

### 1. TodoContoller
`TodoController`内で使用する`Update`メソッドで、`TodoRequest`モデルを使用するとモデルで定義してある`category_id`がバリデーションに引っ掛かるため、ただの`Request`モデルに変更した。<br>

【変更前】
```php
    public function update(TodoRequest $request)
```
【変更後】
```php
    public function update(Request $request)
```

### 2.CategoryController
変更なし。

### 3.TodoRequest
変更なし。

### 4.CategoryRequest
変更なし。

### 5.ルーティング
`TodoController`のTodo_id渡しをURL埋め込みから`index.blade.php`内の`hidden`属性を持ったテキストボックス渡しに変更。<br>
これにより、ルーティングの記述を一部変更。<br>

【変更前】
```php
Route::patch('/todos/{todo}', [TodoController::class, 'update']);
Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
```
【変更後】
```php
Route::patch('/todos/update', [TodoController::class, 'update']);
Route::delete('/todos/delete', [TodoController::class, 'destroy']);
```

### 6.index.blade.php
1. `todo_id`渡し用のテキストボックスを追加（隠し属性）。
```php
    <div class="update-form__button">
+       <input type="hidden" name="id" value="{{ $todo['id'] }}" />
        <button class="update-form__button-submit" type="submit">
            更新
        </button>
    </div>
```
```php
    <div class="delete-form__button">
+       <input type="hidden" name="id" value="{{ $todo['id'] }}" />
        <button class="delete-form__button-submit" type="submit">
            削除
        </button>
    </div>
```

2. Todo検索用`form`に`action`属性と`method`属性を追加。<br>
本来必要がないはずだが、ルートを明確にするためにあえて追加。<br>
【変更前】
```php
    <form class="search-form">
```
【変更後】
```php
    <form class="search-form" action="/todos/search" method="GET">
```

### 7.category.blade.php
変更なし。

### 8.`layout/app.blade.php`
アプリのタイトル名を「Todo」から「Todoアプリ」に変更。
【変更前】
```php
    <title>Todo</title>
```
【変更後】
```php
    <title>Todoアプリ</title>
```

### 9.CSSファイル群
いずれも変更なし。
- index.css
- category.css
- common.css
- sanitize.css
