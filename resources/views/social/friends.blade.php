@extends('layouts.terminal')
@section('content')
<link rel="stylesheet" href="{{ asset('css/friends.css') }}">
<div class="terminal-container terminal-container-social">
    <div class="terminal-bg-grid"></div>
    <div class="terminal-container-main-social">
        <div class="terminal-sidebar-social">
            <nav class="terminal-nav-social">
                <a href="{{ route('social.show', ['id' => auth()->id()]) }}" class="nav-item-social">
                    <svg class="nav-icon-social" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                    <span>Профиль</span>
                </a>
                <a href="{{ route('messages.index') }}" class="nav-item-social">
                    <svg class="nav-icon-social" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    <span>Сообщения</span>
                </a>
                <a href="{{ route('social.friends') }}" class="nav-item-social active">
                    <svg class="nav-icon-social" viewBox="0 0 24 24"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <span>Друзья</span>
                </a>
            </nav>
        </div>
        <div class="terminal-window-social">
            <div class="terminal-header">
                <div class="window-controls">
                    <span class="control close"></span>
                    <span class="control minimize"></span>
                    <span class="control zoom"></span>
                </div>
            </div>
            <div class="terminal-center-social">
                <div class="terminal-card-social">
                    <div class="terminal-search-form-social">
                        <input type="text" class="terminal-search-input-social" placeholder="Поиск по друзьям...">
                    </div>
                    <div class="friends-list-row-friends">
                        @forelse($friends as $friend)
                            <div class="friend-row-friends" style="display: flex; align-items: center; gap: 18px; padding: 14px 0; border-bottom: 1px solid var(--border-dark, #23282c); position: relative;">
                                <a href="{{ route('social.show', ['id' => $friend->id]) }}" class="friend-avatar-link-friends" style="display: flex; align-items: center; position: relative;">
                                    <img src="{{ $friend->avatar_path ?? '/images/default-avatar.png' }}" alt="avatar" class="friend-avatar-img-large-friends" style="width: 56px; height: 56px;">
                                    <span class="status-indicator {{ $friend->lastSeen() ? 'online' : 'offline' }}" style="position: absolute; bottom: 4px; right: 4px;"></span>
                                </a>
                                <div style="display: flex; flex-direction: column; flex: 1; min-width: 0;">
                                    <a href="{{ route('social.show', ['id' => $friend->id]) }}" class="friend-name-friends" style="color: var(--text-primary, #fff); text-decoration: none; font-size: 1.08rem; font-family: 'Fira Mono', monospace;">
                                        {{ $friend->first_name }} {{ $friend->last_name }}
                                    </a>
                                    <form method="POST" action="{{ route('messages.setFriend') }}" style="margin: 0; padding: 0; display: inline;">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{ $friend->id }}">
                                        <button type="submit" class="friend-message-link-friends" style="color: #2196f3; background: none; border: none; padding: 0; font-size: 1.01rem; cursor: pointer; text-align: left; margin-top: 6px;">Сообщение</button>
                                    </form>
                                </div>
                                <div class="friend-actions-friends-dropdown" style="position: relative;">
                                    <a href="#" class="friend-actions-friends" style="color: #888; text-decoration: none; font-size: 1.5rem; margin-left: 8px; cursor: pointer;">&#8942;</a>
                                    <div class="friend-dropdown-friends" style="display: none; position: absolute; right: 0; top: 28px; background: #23282c; border: 1px solid #333; border-radius: 8px; min-width: 160px; box-shadow: 0 4px 16px rgba(0,0,0,0.18); z-index: 10;">
                                        <a href="{{ route('social.show', ['id' => $friend->id]) }}" style="display: block; padding: 10px 18px; color: #fff; text-decoration: none; font-size: 1rem;">Посмотреть профиль</a>
                                        <a href="{{ route('friends.remove', ['id' => $friend->id]) }}" style="display: block; padding: 10px 18px; color: #ff5252; text-decoration: none; font-size: 1rem;">Удалить из друзей</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="color: #888; font-style: italic; margin: 24px;">У вас пока нет друзей.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
