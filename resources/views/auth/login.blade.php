@extends('layouts.terminal')
@section('content')
<div class="terminal-container terminal-container-login">
    <div class="terminal-bg-grid terminal-bg-grid-login"></div>
    <div class="terminal-window-login">
        <div class="terminal-header-login">
            <div class="window-controls-login">
                <span class="control-login close-login"></span>
                <span class="control-login minimize-login"></span>
                <span class="control-login zoom-login"></span>
            </div>
            <div class="terminal-title-login">
                <svg class="terminal-icon-login" viewBox="0 0 24 24">
                    <path d="M4 17l6-6-6-6M12 19h8"/>
                </svg>
                PSocial@login:~$
            </div>
        </div>
        <form method="POST" action="{{ route('login') }}" class="terminal-form-login">
            @csrf
            <div class="terminal-body-login">
                <div class="input-container-login">
                    <p class="bash-text-login">
                        <span class="user-login">user</span><span class="vm-login">@psocial</span>:<span class="char-login">~$</span>
                    </p>
                    <input type="text" name="identifier" required placeholder="Email or username" autocomplete="username" class="input-login" />
                </div>
                <div class="input-container-login">
                    <p class="bash-text-login">
                        <span class="user-login">user</span><span class="vm-login">@psocial</span>:<span class="char-login">~$</span>
                    </p>
                    <input type="password" name="password" required placeholder="Password" autocomplete="current-password" class="input-login" />
                </div>
                <div class="button-row-login">
                    <button type="submit" class="terminal-submit-login">[ Login ]</button>
                </div>
                <div class="link-row-login">
                    <span class="prompt-login">No account?</span>
                    <a href="{{ route('register') }}" class="terminal-link-login">Register</a>
                </div>
            </div>
            <div class="terminal-status-login">
                [PSocial v1.0.0] [Connected] [EN] [UTF-8]
            </div>
        </form>
    </div>
</div>
@endsection