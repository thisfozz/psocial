@extends('layouts.terminal')
@section('content')
<div class="terminal-container terminal-container-login">
    <div class="terminal-bg-grid"></div>
    <div class="terminal-window-login">
        <div class="terminal-header-login">
            <div class="window-controls-login">
                <span class="control-login close-login"></span>
                <span class="control-login minimize-login"></span>
                <span class="control-login zoom-login"></span>
            </div>
        </div>
        <form method="POST" action="{{ route('login') }}" class="terminal-form-login">
            @csrf
            <div class="terminal-body-login">
                <div class="input-container-login">
                    <p class="bash-text-login">
                        <span class="user-login">user</span><span class="vm-login">@psocial</span>:<span class="char-login">~$</span>
                    </p>
                    <input type="text" name="identifier" required placeholder="Email или username" autocomplete="username" class="input-login" />
                </div>
                <div class="input-container-login">
                    <p class="bash-text-login">
                        <span class="user-login">user</span><span class="vm-login">@psocial</span>:<span class="char-login">~$</span>
                    </p>
                    <input type="password" name="password" required placeholder="Пароль" autocomplete="current-password" class="input-login" />
                </div>
                <div class="button-row-login">
                    <button type="submit" class="terminal-submit-login">[ Вход ]</button>
                </div>
                <div class="link-row-login">
                    <span class="prompt-login">Нет аккаунта?</span>
                    <a href="{{ route('register') }}" class="terminal-link-login">Регистрация</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection