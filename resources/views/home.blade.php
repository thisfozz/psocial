@extends('layouts.terminal')
@section('content')
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
                    </div>
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
                        <span class="terminal-text-home" style="color:#00e676;">rm -rf /</span>
                    </div>
                    <div class="command-line-home">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <span class="terminal-text-home" style="color:#fff;">Fri Jun 7 13:37:00 UTC 2024</span>
                    </div>
                    <div class="command-line-home">
                        <span class="prompt-home" style="visibility:hidden;">PSocial@home:~$</span>
                        <span class="terminal-text-home" style="color:#ff5555;">Permission denied: you are not root.</span>
                    </div>
                    <div class="command-line-home">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <div>
                            <a href="{{ route('login') }}" class="terminal-link-home">[ Login ]</a>
                            <a href="{{ route('register') }}" class="terminal-link-home" style="margin-left: 16px;">[ Register ]</a>
                        </div>
                    </div>
                    <div class="command-line-home terminal-input-row">
                        <span class="prompt-home">PSocial@home:~$</span>
                        <input type="text" id="terminal-input" class="terminal-input-home" autocomplete="off" spellcheck="false" placeholder="Type a command..." />
                    </div>
                </div>
                <div class="terminal-status-home">
                    [PSocial v0.4.1] [Connected] [EN] [UTF-8]
                </div>
            </div>
        </div>
    </div>
@endsection