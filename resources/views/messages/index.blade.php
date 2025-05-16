@extends('layouts.terminal')
@section('content')

    <div class="terminal-container terminal-container-chat">
        <div class="terminal-bg-grid terminal-bg-grid-chat"></div>
        <div class="terminal-window-chat">
            <div class="terminal-header-chat">
                <div class="window-controls-chat">
                    <span class="control-chat close-chat"></span>
                    <span class="control-chat minimize-chat"></span>
                    <span class="control-chat zoom-chat"></span>
                </div>
                <div class="terminal-title-chat">
                    <svg class="terminal-icon-chat" viewBox="0 0 24 24">
                        <path d="M4 17l6-6-6-6M12 19h8"/>
                    </svg>
                    PSocial@chat:~$
                </div>
            </div>

            <!-- ВСЕ ОКНО чата + Блок с поиском + Блок с контактами. Внутри него все остальное. Т.е это самый основной блок -->
            <div class="terminal-main-chat-container">
                <div class="terminal-chat-sidebar">
                    <div class="chat-search-container">
                        <input type="text" class="chat-search-input" placeholder="Search...">
                    </div>
                    <div class="chat-contacts-list">
                        @if(isset($contacts) && count($contacts))
                            @foreach($contacts as $contact)
                                <div class="chat-contact" onclick="window.location.href='{{ route('messages.with', $contact->id) }}'">
                                    <div class="contact-avatar">
                                        @if($contact->avatar_path)
                                            <img src="{{ $contact->avatar_path }}" alt="avatar" class="contact-avatar-img">
                                        @endif
                                    </div>
                                    <div class="contact-info">
                                        <span class="contact-name">{{ $contact->first_name }} {{ $contact->last_name }}</span>
                                        <span class="contact-lastmsg">{{ $contact->last_message }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="chat-contact-empty" style="padding: 2rem; text-align: center; color: var(--text-tertiary);">
                                Нет контактов
                            </div>
                        @endif
                    </div>
                </div>

                <!-- САМ ЧАТ -->
                <div class="terminal-chat-content">
                    <div class="chat-current-header">
                        <div class="current-contact" onclick="window.location.href='{{ route('social.show', $friend->id) }}'">
                            <div class="contact-avatar">
                                @if($friend->avatar_path)
                                    <img src="{{ $friend->avatar_path }}" alt="avatar" class="contact-avatar-img">
                                @endif
                            </div>
                            <span class="contact-name">{{ $friend->first_name }} {{ $friend->last_name }}</span>
                            <span class="contact-status {{ $friend->lastSeen() ? 'online' : 'offline' }}">
                                {{ $friend->lastSeen() ? 'online' : 'offline' }}
                            </span>
                        </div>
                    </div>

                    <div class="messages chat-messages" id="chat-messages">
                        @foreach($messages as $message)
                            @include('messages.receive', [
                                'message' => $message,
                                'authId' => auth()->id()
                            ])
                        @endforeach
                    </div>

                    <form id="chat-form" class="chat-input-container">
                        <div class="prompt-line-chat">
                            <span class="user-chat">{{ $friend->username }}</span>
                            <span class="host-chat">@psocial</span>
                            <span class="path-chat">:~/chat$</span>
                        </div>
                        <input type="text" class="terminal-input-chat" id="message" placeholder="type your message..." autocomplete="off">
                        <button type="submit" class="terminal-button-chat">[ Send ]</button>
                    </form>
                </div>
            </div>
            
            <div class="terminal-status-chat">
                [PSocial v1.0.0] [Connected] [Chat with {{ $friend->username }}] [EN] [UTF-8]
            </div>
        </div>
    </div>

<script>
    window.chatConfig = {
        authId: {{ auth()->id() }},
        friendId: {{ $friend->id }},
        csrfToken: '{{ csrf_token() }}'
    };
</script>
@endsection