@extends('layouts.terminal')
@section('content')
    <div class="terminal-center-register">
        <div class="terminal-card-register">
            <form class="terminal-form-register" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="terminal-header-register">
                    <span class="terminal-title-register">
                        <svg class="terminal-icon-register" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 17l6-6-6-6M12 19h8"></path>
                        </svg>
                        PSocial@register:~$
                    </span>
                </div>
                <div class="terminal-body-register">
                    <div class="command-line-register input-row">
                        <span class="prompt-register">first_name:</span>
                        <div class="input-wrapper-register">
                            <input required type="text" class="input-field-register" name="first_name" value="{{ old('first_name') }}" placeholder="Введите имя">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">last_name:</span>
                        <div class="input-wrapper-register">
                            <input required type="text" class="input-field-register" name="last_name" value="{{ old('last_name') }}" placeholder="Введите фамилию">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">username:</span>
                        <div class="input-wrapper-register">
                            <input required type="text" class="input-field-register" name="username" value="{{ old('username') }}" placeholder="Введите никнейм">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">email:</span>
                        <div class="input-wrapper-register">
                            <input required type="email" class="input-field-register" name="email" value="{{ old('email') }}" placeholder="Введите email">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">phone_number:</span>
                        <div class="input-wrapper-register">
                            <input required type="tel" class="input-field-register" name="phone_number" value="{{ old('phone_number') }}" placeholder="Введите телефон">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">password:</span>
                        <div class="input-wrapper-register">
                            <input required type="password" class="input-field-register" name="password" placeholder="Введите пароль">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">confirm_password:</span>
                        <div class="input-wrapper-register">
                            <input required type="password" class="input-field-register" name="password_confirmation" placeholder="Повторите пароль">
                        </div>
                    </div>
                    <div class="command-line-register button-row">
                        <button type="submit" class="terminal-submit-register">[ Регистрация ]</button>
                    </div>
                    <div class="command-line-register link-row">
                        <span class="prompt-register">Уже есть аккаунт?</span>
                    </div>
                    <div class="command-line-register link-row">
                        <a href="{{ route('login') }}" class="terminal-link-register">Войти</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection