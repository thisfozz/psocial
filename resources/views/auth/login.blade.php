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
                PSocial@login:~$
            </div>
        </div>
        <form method="POST" action="{{ route('login') }}" class="terminal-form">
            @csrf
            <div class="terminal-body">
                <div class="input-container">
                    <p class="bash-text">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="identifier" required placeholder="Email or username" autocomplete="username" class="input" />
                </div>
                <div class="input-container">
                    <p class="bash-text">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="password" name="password" required placeholder="Password" autocomplete="current-password" class="input" />
                </div>
                <div class="button-row">
                    <button type="submit" class="terminal-submit">[ Login ]</button>
                </div>
                <div class="link-row">
                    <span class="prompt">No account?</span>
                    <a href="{{ route('register') }}" class="terminal-link">Register</a>
                </div>
            </div>
            <div class="terminal-status">
                [PSocial v1.0.0] [Connected] [EN] [UTF-8]
            </div>
        </form>
    </div>
</div>
@endsection