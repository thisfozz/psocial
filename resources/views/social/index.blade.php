@extends('layouts.terminal')
@section('content')

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
                <a href="#" class="nav-item-social">
                    <svg class="nav-icon-social" viewBox="0 0 24 24"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <span>Друзья</span>
                </a>
            </nav>
        </div>
        <div class="terminal-window-social">
                <div class="terminal-header-social">
                    <div class="window-controls-social">
                        <span class="control-social close-social"></span>
                        <span class="control-social minimize-social"></span>
                        <span class="control-social zoom-social"></span>
                    </div>
                </div>
                <div class="terminal-center-social">
                    <div class="terminal-card-social">
                        <div class="terminal-search-form-social">
                            <form method="GET" action="{{ route('search-users') }}" style="display: flex; align-items: center; gap: 18px; flex: 1; position: relative;">
                                <input type="text" class="terminal-search-input-social" name="search" placeholder="Поиск..." id="search-input">
                                <div id="search-results" class="terminal-search-dropdown-social" style="display: none;"></div>
                            </form>
                            @auth
                            <form method="POST" action="{{ route('logout') }}" style="margin-left: 18px;">
                                @csrf
                                <button type="submit" class="terminal-logout-btn-social">Выйти</button>
                            </form>
                            @endauth
                        </div>
                        
                        <!-- Profile section -->
                        <div class="terminal-profile-section-social" style="position: relative;">
                            <div class="terminal-profile-avatar-social">
                                <a href="#" id="openAvatarModal">
                                    <div class="avatar-status-wrap-large">
                                        <img 
                                            src="{{ $user->avatar_path }}$size=160"
                                            alt="avatar" 
                                            class="terminal-avatar-social"
                                            style="border-color: {{ $user->lastSeen() ? '#00e676' : '#ff0000' }}">
                                        <span class="status-indicator-large {{ $user->lastSeen() ? 'online' : 'offline' }}"></span>
                                    </div>
                                </a>
                            </div>
                            <div class="terminal-profile-info-social">
                                <div class="terminal-profile-name-social">
                                    {{ $user->first_name}} {{ $user->last_name }}
                                </div>
                                <div class="terminal-profile-status-social">
                                    <span class="profile-status-icon" title="Статус"></span>
                                    <span>{{ $user->status ?? '[Нет статуса]' }}</span>
                                </div>
                                <div class="terminal-learn-more-social">
                                    <a href="#" class="terminal-learn-more-link-social learn-more-btn" id="openModalBtn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 6px;">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="16" x2="12" y2="12"/>
                                            <line x1="12" y1="8" x2="12" y2="8"/>
                                        </svg>
                                        Подробнее
                                    </a>
                                </div>
                                <div id="modalOverlayMoreInfo" class="terminal-modal-overlay" style="display: none;">
                                    <div class="terminal-modal-window">
                                        <span class="terminal-modal-close" id="closeModalBtn">&times;</span>
                                        <div class="terminal-modal-content">
                                            <h3>Подробнее</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-actions-top">
                                @if(auth()->check() && auth()->user()->id != $user->id)
                                    @if($isFriend)
                                        <form method="POST" action="{{ route('messages.setFriend') }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="friend_id" value="{{ $user->id }}">
                                            <button type="submit" class="profile-action-btn-top" title="Message">Сообщение</button>
                                        </form>
                                        <button type="button" class="profile-action-btn-top" id="unfriendBtn" data-user-id="{{ $user->id }}" title="Unfriend">Убрать из друзей</button>
                                    @elseif($hasIncomingRequest)
                                        <button type="button" class="profile-action-btn-top" id="acceptFriendRequestBtn" data-request-id="{{ $incomingRequestId }}" title="Принять заявку">Принять</button>
                                        <button type="submit" class="profile-action-btn-top" id="declineFriendRequestBtn" data-request-id="{{ $incomingRequestId }}" title="Отклонить заявку">Отклонить</button>
                                    @elseif($isRequested)
                                        <button type="button" class="profile-action-btn-top" id="cancelRequestBtn" data-request-id="{{ $outgoingRequestId }}" data-user-id="{{ $user->id }}" title="Отменить заявку">❌ Отменить</button>
                                    @else
                                        <button type="submit" class="profile-action-btn-top" id="followBtn" data-user-id="{{ $user->id }}" title="Добавить в друзья"> Добавить в друзья</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- Friends section -->
                        <div class="terminal-friends-section-social">
                            @if($friendsOnline->count() > 0)
                                <div class="friends-online-title">Друзья онлайн ({{ $friendsOnline->count() }})</div>
                                <div class="friends-list-row">
                                    @foreach($friendsOnline as $friend)
                                        <div class="friend-avatar-tooltip">
                                            <a href="{{ route('social.show', ['id' => $friend->id]) }}">
                                                <div class="avatar-status-wrap">
                                                    <img src="{{ $friend->avatar_path }}$size=32" class="friend-avatar-img">
                                                    <span class="status-indicator online"></span>
                                                </div>
                                                <span class="tooltip">{{ $friend->first_name }} {{ $friend->last_name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                            @endif
                            <div class="friends-title">Друзья ({{ $friendsCount }})</div>
                            <div class="friends-list-row">
                                @foreach($friends as $friend)
                                    <div class="friend-avatar-tooltip">
                                        <a href="{{ route('social.show', ['id' => $friend->id]) }}">
                                            <div class="avatar-status-wrap">
                                                <img src="{{ $friend->avatar_path }}$size=32" class="friend-avatar-img">
                                                <span class="status-indicator {{ $friend->lastSeen() ? 'online' : 'offline' }}"></span>
                                            </div>
                                            <span class="tooltip">{{ $friend->first_name }} {{ $friend->last_name }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Posts section -->
                        <div class="terminal-posts-section-social">
                            @if(auth()->check() && (auth()->id() == $user->id || $isFriend))
                                <form method="POST" action="{{ route('post-publish') }}" class="terminal-post-form-social" enctype="multipart/form-data" style="display: flex; flex-direction: row; align-items: flex-start; justify-content: space-between; gap: 10px;">
                                    @csrf
                                    <input type="hidden" name="wall_id" value="{{ $user->id }}">
                                    <textarea name="content" class="terminal-post-input-social" placeholder="Что нового?" rows="3" style="flex-grow: 1; min-width: 200px; max-width: 100%;"></textarea>
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
                                    <textarea name="content" class="terminal-post-input-social" placeholder="Вы должны быть друзьями, чтобы опубликовать пост" rows="3" disabled></textarea>
                                </div>
                            @endif

                            <div class="terminal-posts-list-social">
                                @forelse($posts as $post)
                                    <div class="terminal-post-social">
                                        <div class="terminal-post-header-social" style="display: flex; align-items: center; gap: 10px;">
                                            <a href="{{ route('social.show', ['id' => $post->author->id]) }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                                                <img src="{{ $post->author->avatar_path }}$size=28" alt="avatar" style="width:28px; height:28px; border-radius:50%; object-fit:cover;">
                                                <span class="terminal-post-author-social" style="font-weight:bold; color:var(--terminal-cyan);">{{ $post->author->first_name }} {{ $post->author->last_name }}</span>
                                                <span class="terminal-post-date-social" style="font-size:12px; color:#888; margin-left:8px;">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                            </a>
                                            @if((auth()->check() && auth()->user()->id == $post->wall_id) || (auth()->check() && auth()->user()->id == $post->author->id && $isFriend))
                                                <form method="POST" action="{{ route('post.destroy', $post->id) }}" style="display:inline; margin-left:auto;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="terminal-post-delete-btn-social" title="Удалить пост">
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="delete-icon">
                                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                                        </svg>
                                                    </button>
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
                            [PSocial v1.7.0] [Connected] [{{ $user->username }}] [EN] [UTF-8]
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection