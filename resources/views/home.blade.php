@extends('layouts.terminal')
@section('content')
<div class="terminal-container">
    <div class="terminal-bg-grid"></div>
    <div class="terminal-window">
        <div class="terminal-header">
            <div class="window-controls">
                <span class="control close"></span>
                <span class="control minimize"></span>
                <span class="control zoom"></span>
            </div>
            <div class="terminal-title">
                <svg class="terminal-icon" viewBox="0 0 24 24">
                    <path d="M4 17l6-6-6-6M12 19h8"/>
                </svg>
                PSocial@home:~$
            </div>
        </div>

        <div class="terminal-output" id="terminal-output">
            
        </div>

        <div class="input-container">
            <div class="prompt-line">
                <span class="user">guest</span>
                <span class="host">@psocial</span>
                <span class="path">:~$</span>
            </div>
            <input type="text" class="terminal-input" id="terminal-input" placeholder="Type command..." autocomplete="off" spellcheck="false">
        </div>

        <div class="terminal-links">
            <a href="{{ route('login') }}" class="terminal-link">[ Login ]</a>
            <a href="{{ route('register') }}" class="terminal-link">[ Register ]</a>
        </div>

        <div class="terminal-status">
            [PSocial v1.0.0] [Connected] [EN] [UTF-8]
        </div>
    </div>
</div>
@endsection