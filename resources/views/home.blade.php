@extends('layouts.terminal')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <div class="terminal-window-home">
        <div class="terminal-window-bar-home">
            <span class="terminal-window-btn-home close"></span>
            <span class="terminal-window-btn-home minimize"></span>
            <span class="terminal-window-btn-home zoom"></span>
            <span class="terminal-title-home">
                <svg class="terminal-icon-home" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 17l6-6-6-6M12 19h8"></path>
                </svg>
                PSocial@home:~$
            </span>
        </div>
        <div class="terminal-center-home">
            <div class="terminal-card-home">
                <div class="terminal-body-home">
                    <div class="command-line-home">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <div class="terminal-text-home typed-text-home" id="typed-welcome"></div>
                    </div>
                    <div class="command-line-home">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <div class="terminal-text-home typed-text-home" id="typed-desc"></div>
                    </div>
                    <div class="command-line-home">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <div>
                            <a href="{{ route('login') }}" class="terminal-link-home">[ Войти ]</a>
                            <a href="{{ route('register') }}" class="terminal-link-home" style="margin-left: 16px;">[ Регистрация ]</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function typeText(elementId, text, speed = 35, callback = null) {
            let i = 0;
            function typing() {
                if (i < text.length) {
                    document.getElementById(elementId).innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typing, speed);
                } else if (callback) {
                    callback();
                }
            }
            typing();
        }
        document.addEventListener("DOMContentLoaded", function() {
            typeText("typed-welcome", "Добро пожаловать в PSocial — терминальную социальную сеть.", 35, function() {
                typeText("typed-desc", "Вдохновлено атмосферой Mr. Robot.", 35);
            });
        });
    </script>
@endsection