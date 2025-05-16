@php
    $authId = $authId ?? request('authId');
    $isSent = ($message['sender_id'] ?? $message->sender_id ?? null) == $authId;
@endphp
<div class="message-row {{ $isSent ? 'sent' : 'received' }}">
    <div class="message-content" style="max-width: 350px;">
        <div class="message-body">
            @if(!$isSent && $message->sender->avatar_path)
                <div class="contact-message-avatar" onclick="window.location.href='{{ route('social.show', $message->sender->id) }}'">
                    <img src="{{ $message->sender->avatar_path }}" alt="avatar" class="contact-message-avatar-img">
                </div>
            @endif
            <div class="message-main">
                <div class="message-text">{{ $message['content'] ?? $message->content }}</div>
                <div class="message-time">
                    {{ isset($message['created_at']) ? \Carbon\Carbon::parse($message['created_at'])->format('H:i') : ($message->created_at->format('H:i') ?? '') }}
                </div>
            </div>
        </div>
    </div>
</div>  