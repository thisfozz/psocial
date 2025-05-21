@extends('layouts.terminal')
@section('content')

    <div class="terminal-container terminal-container-chat">
        <div class="terminal-bg-grid"></div>
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
                                            {{ $contact['lastMessage'] ? $contact['lastMessage']->content : '' }}
                                        </span>
                                        <span class="contact-lastmsg-time">
                                            {{ $contact['lastMessage'] ? $contact['lastMessage']->created_at->format('H:i') : '' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="display: flex; padding: 2rem; text-align: center; color: var(--text-tertiary); justify-content: center; align-items: center;">
                                No contacts
                            </div>
                        @endif
                    </div>
                </div>

                <div class="terminal-chat-content">
                    <div class="chat-current-header">
                        @if(isset($friend))
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
                        @endif
                    </div>

                    <div class="messages chat-messages" id="chat-messages">
                        @if(isset($messages) && count($messages))
                        @foreach($messages as $message)
                            @include('messages.receive', [
                                'message' => $message,
                                'authId' => auth()->id()
                            ])
                        @endforeach
                        @else
                            <div style="display: flex; padding: 2rem; text-align: center; color: var(--text-tertiary); justify-content: center; align-items: center;">
                                No messages
                            </div>
                        @endif
                    </div>

                    <form id="chat-form" class="chat-input-container">
                        <div class="prompt-line-chat">
                            <span class="user-chat">{{ $user->username }}</span>
                            <span class="host-chat">@psocial</span>
                            <span class="path-chat">:~/chat$</span>
                        </div>
                        <input type="text" class="terminal-input-chat" id="message" placeholder="Message" autocomplete="off">
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
            
            <div class="terminal-status-chat">
                @if(isset($friend))
                    [PSocial v1.3.3] [Connected] [Chat with {{ $friend->username }}] [EN] [UTF-8]
                @else
                    [PSocial v1.3.3] [Connected] [EN] [UTF-8]
                @endif
            </div>
        </div>
    </div>
<script>
    window.chatConfig = {
        authId: {{ auth()->id() }},
        friendId: {{ $friend->id }},
        dialogId: {{ $dialog->id }},
        csrfToken: '{{ csrf_token() }}'
    };
</script>
@endsection