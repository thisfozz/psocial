<x-app-layout>
    <div class="terminal-center">
        <div class="terminal-card">
            <form class="terminal-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="terminal-header">
                    <span class="terminal-title">
                        <svg class="terminal-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 17l6-6-6-6M12 19h8"></path>
                        </svg>
                        PSocial@register:~$
                    </span>
                </div>
                <div class="terminal-body">
                    <div class="command-line">
                        <span class="prompt">first_name:</span>
                        <div class="input-wrapper">
                            <input required type="text" class="input-field" name="first_name" value="{{ old('first_name') }}" placeholder="Введите имя">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">last_name:</span>
                        <div class="input-wrapper">
                            <input required type="text" class="input-field" name="last_name" value="{{ old('last_name') }}" placeholder="Введите фамилию">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">username:</span>
                        <div class="input-wrapper">
                            <input required type="text" class="input-field" name="username" value="{{ old('username') }}" placeholder="Введите никнейм">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">email:</span>
                        <div class="input-wrapper">
                            <input required type="email" class="input-field" name="email" value="{{ old('email') }}" placeholder="Введите email">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">phone_number:</span>
                        <div class="input-wrapper">
                            <input required type="tel" class="input-field" name="phone_number" value="{{ old('phone_number') }}" placeholder="Введите телефон">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">password:</span>
                        <div class="input-wrapper">
                            <input required type="password" class="input-field" name="password" placeholder="Введите пароль">
                        </div>
                    </div>
                    <div class="command-line">
                        <span class="prompt">confirm_password:</span>
                        <div class="input-wrapper">
                            <input required type="password" class="input-field" name="password_confirmation" placeholder="Повторите пароль">
                        </div>
                    </div>
                    <div class="command-line">
                        <button type="submit" class="terminal-submit">[ Регистрация ]</button>
                    </div>
                    <div class="command-line">
                        <span class="prompt">Уже есть аккаунт?</span>
                        <a href="{{ route('login') }}" class="terminal-link">Войти</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>