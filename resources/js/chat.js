import $ from 'jquery';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('chat-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }

    if (!window.Echo || !window.chatConfig) return;

    const ids = [window.chatConfig.authId, window.chatConfig.friendId].sort((a, b) => a - b);
    const channelName = 'chat.' + ids[0] + '.' + ids[1];

    window.Echo.private(channelName)
        .listen('.chat', function(data) {
            $.post('/receive', {
                _token: window.chatConfig.csrfToken,
                message: data,
                authId: window.chatConfig.authId
            })
            .done(function(res) {
                if(data.sender_id == window.chatConfig.authId && data.temp_id == tempId) {
                    $optimistic.replaceWith(res);
                    $('.messages').scrollTop($('.messages')[0].scrollHeight);
                    return;
                }
                $(".messages").append(res);
                $('.messages').scrollTop($('.messages')[0].scrollHeight);
            });
        });

    $("#chat-form").submit(function(event) {
        event.preventDefault();

        const messageText = $('#chat-form #message').val();
        if (!messageText.trim()) return;

        const tempId = 'temp-' + Date.now();

        $(".messages").append(`
            <div class="message-row sent optimistic" data-temp-id="${tempId}">
                <div class="message-content">
                    <div class="message-text">${$('<div>').text(messageText).html()}</div>
                    <div class="message-time">
                        ${new Date().toLocaleTimeString().slice(0,5)}
                    </div>
                </div>
            </div>
        `);
        $('#chat-form #message').val('');
        $('.messages').scrollTop($('.messages')[0].scrollHeight);

        $.ajax({
            url: '/broadcast',
            type: 'POST',
            headers: {
                'X-Socket-Id': window.Echo.socketId()
            },
            data: {
                _token: window.chatConfig.csrfToken,
                message: messageText,
                receiver_id: window.chatConfig.friendId,
                client_id: tempId
            }
        });
    });
});