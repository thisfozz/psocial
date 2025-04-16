@extends('layouts.terminal')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <div class="terminal-window-login">
        <div class="terminal-window-bar-login">
            <span class="terminal-window-btn-login close"></span>
            <span class="terminal-window-btn-login minimize"></span>
            <span class="terminal-window-btn-login zoom"></span>
            <span class="terminal-title-login">
                <svg class="terminal-icon-login" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 17l6-6-6-6M12 19h8"></path>
                </svg>
                PSocial@login:~$
             </span>
        </div>
        <div class="terminal-center-login">
            <div class="terminal-card-login">
                <form class="terminal-form-login" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="terminal-body-login">
                        @if ($errors->has('identifier'))
                            <div class="command-line-login error-row">
                                <span class="prompt-login" style="color:#ff5252;">PSocial@login:~$</span>
                                <span class="terminal-error-login">{{ $errors->first('identifier') }}</span>
                            </div>
                        @endif
                        <div class="command-line-login input-row">
                            <span class="prompt-login">PSocial@login:~$identifier</span>
                            <div class="input-wrapper-login">
                                <input required type="text" class="input-field-login" name="identifier" value="{{ old('identifier') }}" placeholder="Email or username">
                            </div>
                        </div>
                        <div class="command-line-login input-row">
                            <span class="prompt-login">PSocial@login:~$password</span>
                            <div class="input-wrapper-login">
                                <input required type="password" class="input-field-login" name="password" placeholder="Enter password">
                            </div>
                        </div>
                        <div class="command-line-login button-row">
                            <button type="submit" class="terminal-submit-login">[ Login ]</button>
                        </div>
                        <div class="command-line-login link-row">
                            <span class="prompt-login">No account?</span>
                        </div>
                        <div class="command-line-login link-row">
                            <a href="{{ route('register') }}" class="terminal-link-login">Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection