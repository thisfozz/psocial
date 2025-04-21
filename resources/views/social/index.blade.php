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
                    @if(auth()->check() && auth()->user()->id != $user->id && !$isFriend)
                    <div class="terminal-profile-follow-btn-wrap">
                        <button type="button" class="terminal-profile-follow-btn-social">Follow</button>
                    </div>
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
                                <div class="terminal-friend-social">
                                    <img src="{{ $friend->avatar }}" class="terminal-friend-avatar-social">
                                    <span>{{ $friend->name }}</span>
                                </div>
                            @endforeach
                        @else
                            <div style="display: flex; gap: 18px; justify-content: flex-start; align-items: flex-start; padding: 32px 0;">
                                @for($i=0; $i<7; $i++)
                                    <div class="terminal-friend-social" style="flex-direction: column; align-items: center; min-width: 80px; padding: 8px 0; background: none; border: none;">
                                        <div class="terminal-friend-avatar-social" style="background: #23282c; border: 1.5px solid #00e676; width: 40px; height: 40px;"></div>
                                        <span style="color:#00e676; font-size:13px; margin-top:8px;">Friend</span>
                                    </div>
                                @endfor
                            </div>
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
    </script>
@endsection