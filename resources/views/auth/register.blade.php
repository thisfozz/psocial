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
                        <span class="prompt-register">PSocial@register:~$first_name</span>
                        <div class="input-wrapper-register">
                            <input type="text" class="input-field-register @error('first_name') input-error-register @enderror" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$last_name</span>
                        <div class="input-wrapper-register">
                            <input type="text" class="input-field-register @error('last_name') input-error-register @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$username</span>
                        <div class="input-wrapper-register">
                            <input type="text" class="input-field-register @error('username') input-error-register @enderror" name="username" value="{{ old('username') }}" placeholder="Enter username">
                            @error('username')
                                <div class="terminal-error-register">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$email</span>
                        <div class="input-wrapper-register">
                            <input type="email" class="input-field-register @error('email') input-error-register @enderror" name="email" value="{{ old('email') }}" placeholder="Enter email">
                            @error('email')
                                <div class="terminal-error-register">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$phone_number</span>
                        <div class="input-wrapper-register">
                            <input type="tel" class="input-field-register @error('phone_number') input-error-register @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter phone number">
                            @error('phone_number')
                                <div class="terminal-error-register">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$password</span>
                        <div class="input-wrapper-register">
                            <input type="password" class="input-field-register @error('password') input-error-register @enderror" name="password" placeholder="Enter password">
                        </div>
                    </div>
                    <div class="command-line-register input-row">
                        <span class="prompt-register">PSocial@register:~$confirm_password</span>
                        <div class="input-wrapper-register">
                            <input type="password" class="input-field-register @error('password') input-error-register @enderror" name="password_confirmation" placeholder="Repeat password">
                            @error('password')
                                <div class="terminal-error-register">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="command-line-register button-row">
                        <button type="submit" class="terminal-submit-register">[ Register ]</button>
                    </div>
                    <div class="command-line-register link-row">
                        <span class="prompt-register">Already have an account?</span>
                    </div>
                    <div class="command-line-register link-row">
                        <a href="{{ route('login') }}" class="terminal-link-register">Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection