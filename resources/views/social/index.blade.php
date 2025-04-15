@extends('layouts.terminal')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/social.css') }}">
    <div class="terminal-center-social">
        <div class="terminal-card-social">
            <div class="terminal-header-social">
                <span class="terminal-title-social">
                    <svg class="terminal-icon-social" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 17l6-6-6-6M12 19h8"></path>
                    </svg>
                    PSocial@profile:~$
                </span>
            </div>
            <div class="terminal-body-social">
                @php
                    $gravatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=80&d=mp';
                @endphp

                <div class="command-line-social avatar-block">
                    <img src="{{ $gravatar }}" alt="avatar" class="terminal-avatar-social">
                    <span class="terminal-status-social">
                        {{ $user->status ?? '[Нет статуса]' }}
                    </span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Имя:</span>
                    <span class="terminal-text-social">{{ $user->first_name ?? '[имя]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Фамилия:</span>
                    <span class="terminal-text-social">{{ $user->last_name ?? '[фамилия]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Email:</span>
                    <span class="terminal-text-social">{{ $user->email ?? '[email]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Телефон:</span>
                    <span class="terminal-text-social">{{ $user->phone_number ?? '[телефон]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Город:</span>
                    <span class="terminal-text-social">{{ $user->city->name ?? '[город]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Семейное положение:</span>
                    <span class="terminal-text-social">{{ $user->family_status_id ?? '[статус]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">О себе:</span>
                    <span class="terminal-text-social">{{ $user->about_me ?? '[о себе]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Интересы:</span>
                    <span class="terminal-text-social">{{ $user->interests ?? '[интересы]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Друзья:</span>
                    <span class="terminal-text-social">{{ $user->friends ?? '[список друзей]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Языки:</span>
                    <span class="terminal-text-social">{{ $user->languages ?? '[языки]' }}</span>
                </div>
                <div class="command-line-social">
                    <span class="prompt-social">Посты:</span>
                    <span class="terminal-text-social">{{ $user->posts ?? '[список постов]' }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection