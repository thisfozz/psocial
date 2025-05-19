@extends('layouts.terminal')
@section('content')

<div class="terminal-container terminal-container-social">
<div class="terminal-bg-grid"></div>
    <div class="terminal-window-social">
        <div class="terminal-header-social">
                <div class="window-controls-social">
                    <span class="control-social close-social"></span>
                    <span class="control-social minimize-social"></span>
                    <span class="control-social zoom-social"></span>
                </div>
                <div class="terminal-title-social">
                    <svg class="terminal-icon-social" viewBox="0 0 24 24">
                        <path d="M4 17l6-6-6-6M12 19h8"/>
                    </svg>
                    PSocial@&#6155;{{ $user->username }}:~$
                </div>
            </div>
            <div class="terminal-center-social">
                <div class="terminal-card-social">
                    <div class="terminal-search-form-social">
                        <form method="GET" action="{{ route('search-users') }}" style="display: flex; align-items: center; gap: 18px; flex: 1; position: relative;">
                            <input type="text" class="terminal-search-input-social" name="search" placeholder="Search...">
                            <div id="search-results" class="terminal-search-dropdown-social" style="display: none;"></div>
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
                            <img 
                                src="{{ $user->avatar_path }}$size=160"
                                alt="avatar" 
                                class="terminal-avatar-social"
                                style="border-color: {{ $user->lastSeen() ? '#00e676' : '#ff0000' }}">
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
                                <form method="POST" action="{{ route('messages.setFriend') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                    <button type="submit" class="terminal-profile-edit-btn-social">Message</button>
                                </form>
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
                            <a href="#" class="terminal-friends-title-social">Friends ({{ count($friends ?? []) }})</a>
                        @else
                            <div class="terminal-friends-title-social">Friends (0)</div>
                        @endif
                        <div class="terminal-friends-list-social">
                            @if(count($friends ?? []) > 0)
                                @foreach($friends as $friend)
                                    <div class="terminal-friend-social">
                                        <a href="{{ route('social.show', ['id' => $friend->id]) }}" style="text-decoration: none;">
                                            <img src="{{ $friend->avatar_path }}$size=40" class="terminal-friend-avatar-social">
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
                            <form method="POST" action="{{ route('post-publish') }}" class="terminal-post-form-social" enctype="multipart/form-data" style="display: flex; flex-direction: row; align-items: flex-start; justify-content: space-between; gap: 10px;">
                                @csrf
                                <input type="hidden" name="wall_id" value="{{ $user->id }}">
                                <textarea name="content" class="terminal-post-input-social" placeholder="What's new?" rows="3" style="flex-grow: 1; min-width: 200px; max-width: 100%;"></textarea>
                                <div style="display: flex; flex-direction: column; gap: 10px; margin-right: 10px;">
                                    <button type="submit" class="terminal-post-circle-btn-social" title="Отправить">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    </button>
                                    <button type="button" class="terminal-post-circle-btn-social" onclick="document.getElementById('post-images').click()" title="Прикрепить изображение">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                    </button>
                                    <input type="file" name="images[]" id="post-images" multiple accept="image/*" style="display: none;">
                                </div>
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
                                        <a href="{{ route('social.show', ['id' => $post->author->id]) }}" style="text-decoration: none; display: flex; align-items: center;">
                                            <img src="{{ $post->author->avatar_path }}$size=32" alt="avatar" class="terminal-friend-avatar-social" style="width:32px; height:32px; margin: 0;">
                                            <span class="terminal-post-author-social" style="margin-left: 20px;">{{ $post->author->first_name }} {{ $post->author->last_name }}</span>
                                            <span class="terminal-post-date-social" style="margin-left: 20px; font-size: 0.8em; color: #666;">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                        </a>
                                    </div>
                                        @if((auth()->check() && auth()->user()->id == $post->wall_id) || (auth()->check() && auth()->user()->id == $post->author->id && $isFriend))
                                            <form method="POST" action="{{ route('post.destroy', $post->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="terminal-post-delete-btn-social">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="terminal-post-content-social">
                                        {{ $post->content }}
                                    </div>
                                    @if($post->images->count() > 0)
                                        <div class="terminal-post-images-social">
                                            @foreach($post->images as $image)
                                                <img src="{{ $image->image_url }}" alt="Post image">
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($post->video)
                                        <div class="terminal-post-video-preview-social">
                                            <img src="{{ $post->video->thumbnail_url }}" alt="Video thumbnail">
                                            <iframe src="{{ $post->video->embed_code }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                    <div class="terminal-post-actions-social">
                                        <form method="POST" action="{{ route('post.toggleLike', $post->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="terminal-like-btn-social" id="likeBtn{{ $post->id }}">
                                                <svg class="terminal-like-icon" width="20" height="20" viewBox="0 0 24 24" 
                                                    fill="{{ $post->hasLikeFrom(auth()->user()) ? '#00e676' : 'none' }}" 
                                                    stroke="currentColor" 
                                                    stroke-width="2" 
                                                    stroke-linecap="round" 
                                                    stroke-linejoin="round">
                                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                </svg>
                                                <span class="terminal-like-count">{{ $post->likes_count ?? 0 }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="terminal-posts-empty-social">It's still quiet here...</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="terminal-status-social">
                        [PSocial v1.3.3] [Connected] [{{ $user->username }}] [EN] [UTF-8]
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection