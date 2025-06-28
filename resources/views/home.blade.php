@extends('layouts.terminal')
@section('content')
<div class="terminal-container terminal-container-home">
    <div class="terminal-bg-grid terminal-bg-grid-home"></div>
    <div class="terminal-window-home">
        <div class="terminal-header-home">
            <div class="window-controls-home">
                <span class="control-home close-home"></span>
                <span class="control-home minimize-home"></span>
                <span class="control-home zoom-home"></span>
            </div>
        </div>

        <div class="terminal-output-home" id="terminal-output">
            <!-- Терминальный вывод -->
        </div>

        <div class="input-container-home">
            <div class="prompt-line-home">
                <span class="user-home">guest</span>
                <span class="host-home">@psocial</span>
                <span class="path-home">:~$</span>
            </div>
            <input type="text" class="terminal-input-home" id="terminal-input" placeholder="Type command..." autocomplete="off" spellcheck="false">
        </div>

        <div class="terminal-links-home">
            <a href="{{ route('login') }}" class="terminal-link-home">[ Вход ]</a>
            <a href="{{ route('register') }}" class="terminal-link-home">[ Регистрация ]</a>
        </div>

        <div class="terminal-status-home">
            [PSocial v1.8.0] [Connected] [Home] [EN] [UTF-8]
        </div>
    </div>
</div>
@endsection