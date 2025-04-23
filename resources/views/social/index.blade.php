@extends('layouts.terminal')
@section('content')
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
                        <div id="modalOverlayMoreInfo" class="terminal-modal-overlay" style="display: none;">
                            <div class="terminal-modal-window">
                                <span class="terminal-modal-close" id="closeModalBtn">&times;</span>
                                <div class="terminal-modal-content">
                                    <h3>Additional info</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(auth()->check() && auth()->user()->id == $user->id)
                    <div class="terminal-profile-edit-btn-wrap" style="display: flex; flex-direction: column; align-items: flex-start; margin-left: auto;">
                        <a href="#" class="terminal-profile-edit-btn-social">Edit profile</a>
                    </div>
                    @endif
                    @if(auth()->check() && auth()->user()->id != $user->id)
                        @if($isFriend)
                        @elseif($hasIncomingRequest)
                        <div id="requsetActions1">
                            <button type="button" class="terminal-profile-follow-btn-social" id="acceptFriendRequestBtn" data-request-id="{{ $incomingRequestId }}">Accept</button>
                            <button type="submit" class="terminal-profile-follow-btn-social" id="declineFriendRequestBtn" data-request-id="{{ $incomingRequestId }}">Decline</button>
                        </div>
                        @elseif($isRequested)
                            <div id="requsetActions" style="display: flex; flex-direction: column; align-items: flex-start; margin-top: 8px;">
                                <button type="button" class="terminal-profile-cancel-btn-social" id="cancelRequestBtn" data-request-id="{{ $outgoingRequestId }}" data-user-id="{{ $user->id }}">Cancel request</button>
                            </div>
                        @else
                        <button type="submit" class="terminal-profile-follow-btn-social" id="followBtn" data-user-id="{{ $user->id }}">Follow</button>
                        @endif
                    @endif
                    @if(auth()->check() && auth()->user()->id != $user->id && $isFriend)
                        <div class="terminal-profile-edit-btn-wrap" style="display: flex; flex-direction: column; align-items: flex-start; margin-left: auto;">
                            <a href="#" class="terminal-profile-edit-btn-social">Message</a>
                            <button type="button" class="terminal-profile-cancel-btn-social" id="unfriendBtn" data-user-id="{{ $user->id }}">
                                Unfriend
                            </button>
                        </div>
                        <div id="unfriendModal" class="terminal-modal-overlay" style="display: none;">
                            <div class="terminal-modal-window">
                                <span class="terminal-modal-close" id="closeUnfriendModalBtn">&times;</span>
                                <div class="terminal-modal-content">
                                    <h3>Delete friend?</h3>
                                    <p>Are you sure you want to delete this user from friends?</p>
                                    <button id="confirmUnfriendBtn" class="terminal-profile-cancel-btn-social">Yes, delete</button>
                                    <button id="cancelUnfriendBtn" class="terminal-profile-edit-btn-social">Cancel</button>
                                </div>
                            </div>
                        </div>
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
                <div class="terminal-posts-section-social">
                    @if(auth()->check() && (auth()->id() == $user->id || $isFriend))
                        <form method="POST" action="{{ route('post-publish') }}" class="terminal-post-form-social">
                            @csrf
                            <input type="hidden" name="wall_id" value="{{ $user->id }}">
                            <textarea name="content" class="terminal-post-input-social" placeholder="What's new?" rows="3" required></textarea>
                            <button type="submit" class="terminal-post-btn-social">Publish</button>
                        </form>
                    @elseif(auth()->check())
                        <div class="terminal-post-form-social">
                            <textarea name="content" class="terminal-post-input-social" placeholder="You must be friends to publish a post" rows="3" disabled></textarea>
                        </div>
                    @endif

                    <div class="terminal-posts-list-social">
                        @forelse($posts as $post)
                            <div class="terminal-post-social">
                                <div class="terminal-post-header-social" style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        @php
                                            $email = strtolower(trim($post->author->email));
                                            $hash = md5($email);
                                            $gravatar = "https://www.gravatar.com/avatar/$hash?s=32&d=404";
                                            $uiavatars = 'https://ui-avatars.com/api/?name=' . urlencode($post->author->first_name . ' ' . $post->author->last_name) . '&background=000000&color=fff&rounded=true&size=32';

                                            $headers = @get_headers($gravatar);
                                            if ($headers && strpos($headers[0], '200') !== false) {
                                                $avatar = $gravatar;
                                            } else {
                                                $avatar = $uiavatars;
                                            }
                                        @endphp
                                        <img src="{{ $avatar }}" alt="avatar" class="terminal-friend-avatar-social" style="width:32px; height:32px; margin-right: 6px;">
                                        <span class="terminal-post-author-social">{{ $post->author->first_name }} {{ $post->author->last_name }}</span>
                                        <span class="terminal-post-date-social">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    @if((auth()->check() && auth()->user()->id == $post->wall_id) || (auth()->check() && auth()->user()->id == $post->author->id && $isFriend))
                                        <form method="POST" action="#" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="terminal-post-delete-btn-social">Delete</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="terminal-post-content-social">
                                    {{ $post->content }}
                                </div>
                            </div>
                        @empty
                            <div class="terminal-posts-empty-social">It's still quiet here...</div>
                        @endforelse
                    </div>
                </div>
                <div class="terminal-status-social">
                    [PSocial v0.4.1] [Connected] [EN] [UTF-8]
                </div>
            </div>
        </div>
    </div>
@endsection