@extends('layouts.terminal')
@section('content')
<div class="terminal-container terminal-container-register">
    <div class="terminal-bg-grid"></div>
    <div class="terminal-window-register">
        <div class="terminal-header-register">
            <div class="window-controls-register">
                <span class="control-register close"></span>
                <span class="control-register minimize"></span>
                <span class="control-register zoom"></span>
            </div>
        </div>
        <form method="POST" action="{{ route('register') }}" class="terminal-form-register">
            @csrf
            <div class="terminal-body-register">
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="first_name" required placeholder="Имя" value="{{ old('first_name') }}" class="input-register @error('first_name') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="last_name" required placeholder="Фамилия" value="{{ old('last_name') }}" class="input-register @error('last_name') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="username" required placeholder="Имя пользователя" value="{{ old('username') }}" class="input-register @error('username') input-error-register @enderror" />
                    @error('username')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="email" name="email" required placeholder="Email" value="{{ old('email') }}" class="input-register @error('email') input-error-register @enderror" />
                    @error('email')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="tel" name="phone_number" required placeholder="Номер телефона" value="{{ old('phone_number') }}" class="input-register @error('phone_number') input-error-register @enderror" />
                    @error('phone_number')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="password" name="password" required placeholder="Пароль" class="input-register @error('password') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="password" name="password_confirmation" required placeholder="Подтверждение пароля" class="input-register @error('password') input-error-register @enderror" />
                    @error('password')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="button-row-register">
                    <button type="submit" class="terminal-submit-register">[ Регистрация ]</button>
                </div>
                <div class="link-row-register">
                    <span class="prompt">Уже есть аккаунт?</span>
                    <a href="{{ route('login') }}" class="terminal-link-register">Вход</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection