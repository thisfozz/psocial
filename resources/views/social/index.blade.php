@extends('layouts.terminal')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/social.css') }}">
    <div class="terminal-window-social">
        <div class="terminal-window-bar-social">
            <span class="terminal-window-btn-social close"></span>
            <span class="terminal-window-btn-social minimize"></span>
            <span class="terminal-window-btn-social zoom"></span>
            <span class="terminal-title-social">
                        <svg class="terminal-icon-home" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 17l6-6-6-6M12 19h8"></path>
                        </svg>
                        PSocial@home:~$
                    </span>
        </div>
        <div class="terminal-center-social">
            <div class="terminal-card-social">
                <div class="terminal-search-form-social" style="display: flex; align-items: center; justify-content: space-between;">
                    <form method="GET" action="#" style="display: flex; align-items: center; gap: 18px; flex: 1;">
                        <input type="text" class="terminal-search-input-social" name="search" placeholder="Search...">
                        <button type="submit" class="terminal-search-btn-social">Search</button>
                    </form>
                    @auth
                    <form method="POST" action="{{ route('logout') }}" style="margin-left: 18px;">
                        @csrf
                        <button type="submit" class="terminal-logout-btn-social">Logout</button>
                    </form>
                    @endauth
                </div>
                <div class="terminal-profile-section-social">
                    <div class="terminal-profile-avatar-social">
                        @php
                            $email = strtolower(trim($user->email));
                            $hash = md5($email);
                            $gravatar = "https://www.gravatar.com/avatar/$hash?s=160&d=404";
                            $uiavatars = 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name) . '&background=000000&color=fff&rounded=true&size=160';

                            $headers = @get_headers($gravatar);
                            if ($headers && strpos($headers[0], '200') !== false) {
                                $avatar = $gravatar;
                            } else {
                                $avatar = $uiavatars;
                            }
                        @endphp
                        <img 
                            src="{{ $avatar }}"
                            alt="avatar" 
                            class="terminal-avatar-social"
                        >
                    </div>
                    <div class="terminal-profile-info-social">
                        <div class="terminal-profile-name-social">
                            {{ $user->first_name}} {{ $user->last_name }}
                        </div>
                        <div class="terminal-profile-status-social">
                            {{ $user->status ?? '[No status]' }}
                        </div>
                        <div class="terminal-learn-more-social">
                        <a href="#" class="terminal-learn-more-link-social" id="openModalBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12" y2="8"/>
                            </svg>
                            Learn more
                        </a>
                    </div>
                        <div id="modalOverlay" class="terminal-modal-overlay" style="display: none;">
                            <div class="terminal-modal-window">
                                <span class="terminal-modal-close" id="closeModalBtn">&times;</span>
                                <div class="terminal-modal-content">
                                    <h3>Additional info</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(auth()->check() && auth()->user()->id == $user->id)
                    <div class="terminal-profile-edit-btn-wrap">
                        <a href="#" class="terminal-profile-edit-btn-social">Edit profile</a>
                    </div>
                    @endif
                    @if(auth()->check() && auth()->user()->id != $user->id)
                        @if($isFriend)
                            {{-- Кнопки не показываем --}}
                        @elseif($hasIncomingRequest)
                        <button type="button" class="terminal-profile-follow-btn-social" id="acceptFriendRequestBtn" data-request-id="{{ $incomingRequestId }}">Принять</button>
                            <form method="POST" action="{{ route('friend-request.decline', ['requestId' => $incomingRequestId]) }}">
                                @csrf
                                <button type="submit" class="terminal-profile-follow-btn-social" id="declineFriendRequestBtn" data-request-id="{{ $incomingRequestId }}">Отклонить</button>
                            </form>
                        @elseif($isRequested)
                            <button type="button" class="terminal-profile-follow-btn-social" disabled>Ожидание</button>
                        @else
                            <form method="POST" action="{{ route('friend-request.send', ['toUserId' => $user->id]) }}">
                                @csrf
                                <button type="submit" class="terminal-profile-follow-btn-social">Follow</button>
                            </form>
                        @endif
                    @endif
                    @if(auth()->check() && auth()->user()->id != $user->id && $isFriend)
                        <a href="#" class="terminal-profile-edit-btn-social">Send message</a>
                    @endif
                </div>
                <div class="terminal-friends-section-social">
                    @if(count($friends ?? []) > 0)
                        <a href="#" class="terminal-friends-title-social" style="text-decoration: underline; cursor: pointer;">Friends ({{ count($friends ?? []) }})</a>
                    @else
                        <div class="terminal-friends-title-social">Friends (0)</div>
                    @endif
                    <div class="terminal-friends-list-social">
                        @if(count($friends ?? []) > 0)
                            @foreach($friends as $friend)
                                @php
                                    $email = strtolower(trim($friend->email));
                                    $hash = md5($email);
                                    $gravatar = "https://www.gravatar.com/avatar/$hash?s=40&d=404";
                                    $uiavatars = 'https://ui-avatars.com/api/?name=' . urlencode($friend->first_name . ' ' . $friend->last_name) . '&background=000000&color=fff&rounded=true&size=40';

                                    $headers = @get_headers($gravatar);
                                    if ($headers && strpos($headers[0], '200') !== false) {
                                        $avatar = $gravatar;
                                    } else {
                                        $avatar = $uiavatars;
                                    }
                                @endphp
                                <div class="terminal-friend-social">
                                    <a href="{{ route('social.show', ['id' => $friend->id]) }}" style="text-decoration: none;">
                                        <img src="{{ $avatar }}" class="terminal-friend-avatar-social">
                                    </a>
                                    <a href="{{ route('social.show', ['id' => $friend->id]) }}" style="text-decoration: none;">
                                        <span style="margin-top: 8px; color: #00e676;">{{ $friend->first_name }} {{ $friend->last_name }}</span>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('openModalBtn').onclick = function(e) {
            e.preventDefault();
            document.getElementById('modalOverlay').style.display = 'flex';
        };
        document.getElementById('closeModalBtn').onclick = function() {
            document.getElementById('modalOverlay').style.display = 'none';
        };
        document.getElementById('modalOverlay').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        document.addEventListener('DOMContentLoaded', function() {
            const acceptBtn = document.getElementById('acceptFriendRequestBtn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    fetch(`${window.location.protocol}//${window.location.host}/friend-request/accept/${requestId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .catch(error => {
                        alert('Ошибка при обработке запроса');
                    });
                });
            }
        });
    </script>
@endsection