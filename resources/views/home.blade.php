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
            <div class="terminal-title-home">
                <svg class="terminal-icon-home" viewBox="0 0 24 24">
                    <path d="M4 17l6-6-6-6M12 19h8"/>
                </svg>
                PSocial@home:~$
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
            <a href="{{ route('login') }}" class="terminal-link-home">[ Login ]</a>
            <a href="{{ route('register') }}" class="terminal-link-home">[ Register ]</a>
        </div>

        <div class="terminal-status-home">
            [PSocial v1.6.5] [Connected] [Home] [EN] [UTF-8]
        </div>
    </div>
</div>
@endsection