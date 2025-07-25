@extends('layouts.terminal')
@section('content')

    <div class="terminal-container terminal-container-chat">
        <div class="terminal-bg-grid"></div>
        <div class="terminal-container-main-message">
            <div class="terminal-sidebar-message">
                <nav class="terminal-nav-message">
                    <a href="{{ route('social.show', ['id' => auth()->id()]) }}" class="nav-item-message">
                        <svg class="nav-icon-message" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                        <span>Профиль</span>
                    </a>
                    <a href="{{ route('messages.index') }}" class="nav-item-message">
                        <svg class="nav-icon-message" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        <span>Сообщения</span>
                    </a>
                    <a href="{{ route('social.friends') }}" class="nav-item-message">
                        <svg class="nav-icon-message" viewBox="0 0 24 24"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        <span>Друзья</span>
                    </a>
                </nav>
            </div>
                <div class="terminal-window-chat">
                <div class="terminal-header">
                    <div class="window-controls">
                        <span class="control close"></span>
                        <span class="control minimize"></span>
                        <span class="control zoom"></span>
                    </div>
                </div>

                <div class="terminal-main-chat-container">
                    <div class="terminal-chat-sidebar">
                        <div class="chat-search-container">
                            <input type="text" class="chat-search-input" placeholder="Search...">
                        </div>
                        <div class="chat-contacts-list">
                            @if(isset($contacts) && count($contacts))
                                @foreach($contacts as $contact)
                                    <div class="chat-contact" onclick="window.location.href='{{ route('messages.index', ['dialog_id' => $contact['id']]) }}'">
                                        <div class="contact-avatar">
                                            @if($contact['avatar'])
                                                <img src="{{ $contact['avatar'] }}" alt="avatar" class="contact-avatar-img">
                                            @endif
                                        </div>
                                        <div class="contact-info">
                                            <span class="contact-name">{{ $contact['name'] }}</span>
                                            <span class="contact-lastmsg">
                                                @if($contact['lastMessage'])
                                                    @if($contact['lastMessage']->video)
                                                        <span class="contact-lastmsg-video">Видео</span>
                                                    @elseif(count($contact['lastMessage']->images) > 0)
                                                        <span class="contact-lastmsg-images">Изобаржение</span>
                                                    @else
                                                        {{ $contact['lastMessage']->content }}
                                                    @endif
                                                @endif
                                            </span>
                                            <span class="contact-lastmsg-time">
                                                {{ $contact['lastMessage'] ? $contact['lastMessage']->created_at->format('H:i') : '' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div style="display: flex; padding: 2rem; text-align: center; color: var(--text-tertiary); justify-content: center; align-items: center;">
                                    Нет чатов
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="terminal-chat-content">
                        <div class="chat-current-header">
                            @if(isset($friend))
                                <div class="current-contact" onclick="window.location.href='{{ route('social.show', $friend->id) }}'">
                                    <div class="contact-avatar" style="position: relative;">
                                        @if($friend->avatar_path)
                                            <img src="{{ $friend->avatar_path }}" alt="avatar" class="contact-avatar-img">
                                        @endif
                                        <span class="status-dot" style="position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; border: 2px solid #121619; border-radius: 50%; background: {{ $friend->lastSeen() ? '#00e676' : '#757575' }};"></span>
                                    </div>
                                    <div class="contact-name">{{ $friend->first_name }} {{ $friend->last_name }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="messages chat-messages" id="chat-messages">
                            @if(isset($messages) && count($messages))
                            @php $prevDate = null; @endphp
                            @foreach($messages as $message)
                                @php
                                    $msgDate = $message->created_at->format('Y-m-d');
                                @endphp
                                @if($prevDate !== $msgDate)
                                    <div class="date-divider">
                                        <span class="date-divider-line"></span>
                                        <span class="date-divider-text">
                                            @if($msgDate === now()->format('Y-m-d')) Сегодня
                                            @elseif($msgDate === now()->subDay()->format('Y-m-d')) Вчера
                                            @else {{ \Carbon\Carbon::parse($msgDate)->translatedFormat('d F Y') }}
                                            @endif
                                        </span>
                                        <span class="date-divider-line"></span>
                                    </div>
                                    @php $prevDate = $msgDate; @endphp
                                @endif
                                @include('messages.receive', [
                                    'message' => $message,
                                    'authId' => auth()->id()
                                ])
                            @endforeach
                            @else
                                <div style="display: flex; padding: 2rem; text-align: center; color: var(--text-tertiary); justify-content: center; align-items: center;">
                                    Выберите чат
                                </div>
                            @endif
                        </div>

                        <form id="chat-form" class="chat-input-container">
                            <div class="prompt-line-chat">
                                <span class="user-chat">{{ $user->username }}</span>
                                <span class="host-chat">@psocial</span>
                                <span class="path-chat">:~/chat$</span>
                            </div>
                            <div id="edit-indicator" style="display:none; color: #00e676; margin-bottom: 4px;">
                                Редактирование сообщения
                                <span id="cancel-edit" style="cursor:pointer; color:#ff5252; margin-left:10px;">[отмена]</span>
                            </div>
                            <div id="reply-preview" style="display:none; margin-bottom: 6px;"></div>
                            <input type="text" class="terminal-input-chat" id="message" placeholder="Сообщение" autocomplete="off">
                            <button type="button" class="attach-button" onclick="document.getElementById('chat-images').click()" title="Прикрепить изображение">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="11" fill="none" stroke="#00e676" stroke-width="2"/>
                                    <path d="M12 6V18" stroke="#00e676" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M6 12H18" stroke="#00e676" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                            <input type="file" name="images[]" id="chat-images" multiple accept="image/*" style="display: none;">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="message-context-menu" style="display:none; position:absolute; z-index:1000; background:#222; color:#fff; border-radius:6px; min-width:120px; box-shadow:0 2px 8px #0008;">
        <button type="button" class="menu-item" data-action="reply">Ответить</button>
        <button type="button" class="menu-item" data-action="edit">Редактировать</button>
        <button type="button" class="menu-item" data-action="delete">Удалить</button>
    </div>
<script>
    window.chatConfig = {
        authId: {{ auth()->id() }},
        @isset($friend)
        friendId: {{ $friend->id }},
        @endisset
        @isset($dialog)
        dialogId: {{ $dialog->id }},
        @endisset
        csrfToken: '{{ csrf_token() }}'
    };
</script>
@endsection