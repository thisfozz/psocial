@php
    $authId = $authId ?? request('authId');
    $isSent = ($message['sender_id'] ?? $message->sender_id ?? null) == $authId;
    $images = $message->images ?? [];
    $hasOnlyImage = count($images) && empty(trim($message['content'] ?? $message->content));
    $hasVideo = $message->video;
@endphp
<div class="message-row {{ $isSent ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
    <div class="message-content{{ $hasOnlyImage ? ' no-border' : '' }}{{ $hasVideo ? ' no-border wide-video' : '' }}">
        <div class="message-body">
            <div class="message-main">
                @if($message->replyTo)
                    <div class="message-reply-preview" style="border-left: 3px solid #00e676; padding-left: 8px; margin-bottom: 4px; color: #888;">
                        <div class="reply-author" style="font-weight: bold;">
                            {{ $message->replyTo->sender->first_name ?? '...' }}
                        </div>
                        <div class="reply-content" style="font-size: 0.95em;">
                            {{ \Illuminate\Support\Str::limit($message->replyTo->content, 100) }}
                        </div>
                    </div>
                @endif
                <div class="message-text">{{ $message['content'] ?? $message->content }}</div>
                @if(count($images))
                    <div class="message-images" style="margin-top: 0.5rem;">
                        @foreach($message->images as $img)
                            <img src="{{ $img->image_url }}" alt="attachment">
                        @endforeach
                    </div>
                @endif
                @if($message->video)
                    <div class="message-video-embed-wrap">
                        <iframe
                            class="message-video-iframe"
                            src="{{ $message->video->embed_code }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            title="Встроенное видео"
                            style="width:100%;height:100%;"
                        ></iframe>
                    </div>
                @endif
                <div class="message-time" 
                     data-time="{{ $message->created_at->toIso8601String() }}"
                     data-edited="{{ $message->created_at != $message->updated_at ? '1' : '0' }}">
                    {{ $message->created_at->format('H:i') }}
                    @if($message->created_at != $message->updated_at)
                        <span class="edited-mark">(изменено)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>