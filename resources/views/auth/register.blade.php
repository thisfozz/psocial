@extends('layouts.terminal')
@section('content')
<div class="terminal-container terminal-container-register">
    <div class="terminal-bg-grid"></div>
    <div class="terminal-window-register">
        <div class="terminal-header-register">
            <div class="window-controls-register">
                <span class="control-register close"></span>
                <span class="control-register minimize"></span>
                <span class="control-register zoom"></span>
            </div>
            <div class="terminal-title-register">
                <svg class="terminal-icon-register" viewBox="0 0 24 24">
                    <path d="M4 17l6-6-6-6M12 19h8"/>
                </svg>
                PSocial@register:~$
            </div>
        </div>
        <form method="POST" action="{{ route('register') }}" class="terminal-form-register">
            @csrf
            <div class="terminal-body-register">
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="first_name" required placeholder="First name" value="{{ old('first_name') }}" class="input-register @error('first_name') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="last_name" required placeholder="Last name" value="{{ old('last_name') }}" class="input-register @error('last_name') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="text" name="username" required placeholder="Username" value="{{ old('username') }}" class="input-register @error('username') input-error-register @enderror" />
                    @error('username')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="email" name="email" required placeholder="Email" value="{{ old('email') }}" class="input-register @error('email') input-error-register @enderror" />
                    @error('email')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="tel" name="phone_number" required placeholder="Phone number" value="{{ old('phone_number') }}" class="input-register @error('phone_number') input-error-register @enderror" />
                    @error('phone_number')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="password" name="password" required placeholder="Password" class="input-register @error('password') input-error-register @enderror" />
                </div>
                <div class="input-container-register">
                    <p class="bash-text-register">
                        <span class="user">user</span><span class="vm">@psocial</span>:<span class="char">~$</span>
                    </p>
                    <input type="password" name="password_confirmation" required placeholder="Confirm password" class="input-register @error('password') input-error-register @enderror" />
                    @error('password')
                        <div class="error-row-register">{{ $message }}</div>
                    @enderror
                </div>
                <div class="button-row-register">
                    <button type="submit" class="terminal-submit-register">[ Register ]</button>
                </div>
                <div class="link-row-register">
                    <span class="prompt">Already have an account?</span>
                    <a href="{{ route('login') }}" class="terminal-link-register">Login</a>
                </div>
            </div>
            <div class="terminal-status-register">
                [PSocial v1.3.3] [Connected] [Register] [EN] [UTF-8]
            </div>
        </form>
    </div>
</div>
@endsection