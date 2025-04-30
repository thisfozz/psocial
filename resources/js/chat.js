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
                message: data
            })
            .done(function(res) {
                $(".messages").append(res);
                $(document).scrollTop($(".messages").height());
            });
        });

    $("#chat-form").submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: '/broadcast',
            type: 'POST',
            headers: {
                'X-Socket-Id': window.Echo.socketId()
            },
            data: {
                _token: window.chatConfig.csrfToken,
                message: $('#chat-form #message').val(),
                receiver_id: window.chatConfig.friendId
            }
        })
        .done(function(res) {
            $('.messages > .message').last().after(res);
            $('#chat-form #message').val('');
            $(document).scrollTop($('.messages').height());
        })
    });
});