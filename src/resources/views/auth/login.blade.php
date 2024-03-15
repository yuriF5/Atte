
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
        <div class="header-utilities">
            <a class="header__logo" href="/">
            Atte
            </a>
    </header>
    <main>
    <div class="login__content">
    <div class="login-form__heading">
        <h2>ログイン</h2>
    </div>
    <form class="form" action="/login" method="post">
        @csrf
        <div class="form__group">
        <div class="form__group-content">
            <div class="form__input--text">
            <input type="email" name="email" value="{{ old('email') }}"placeholder="メールアドレス" />
            </div>
            <div class="form__error">
            @error('email')
            {{ $message }}
            @enderror
            </div>
        </div>
        </div>
        <div class="form__group">
        <div class="form__group-content">
            <div class="form__input--text">
            <input type="password" name="password" placeholder="パスワード"/>
            </div>
            <div class="form__error">
            @error('password')
            {{ $message }}
            @enderror
            </div>
        </div>
        </div>
        <div class="form__button">
        <button class="form__button-submit" type="submit">ログイン</button>
        </div>
    </form>
    
    <div class="register__link">
        <p>アカウントをお持ちでない方はこちらから</p>
        <a class="register__button-submit" href="/register">会員登録</a>
    </div>
    </div>
</main>
<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
</body>
</html>