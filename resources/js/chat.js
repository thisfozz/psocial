import $ from 'jquery';

function scrollToBottom(smooth = false) {
    const chatMessages = document.getElementById("chat-messages");
    if (chatMessages) {
        setTimeout(() => {
            if (smooth) {
                chatMessages.scrollTo({
                    top: chatMessages.scrollHeight,
                    behavior: "smooth"
                });
            } else {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }, 100);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('chat-form');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }

    if (!window.Echo || !window.chatConfig) return;

    if (!window.chatConfig.authId || !window.chatConfig.friendId) {
        console.log('No authId or friendId');
        return;
    }

    const ids = [window.chatConfig.authId, window.chatConfig.friendId].sort((a, b) => a - b);
    const channelName = 'chat.' + ids[0] + '.' + ids[1];

    renderAllMessageTimes();

    window.Echo.private(channelName)
        .listen('.chat', function(data) {
            $.post('/receive', {
                _token: window.chatConfig.csrfToken,
                message: data,
                authId: window.chatConfig.authId
            })
            .done(function(res) {
                $(".messages").append(res);
                scrollToBottom();
                renderAllMessageTimes();
            });
        });

    $("#chat-form").submit(function(event) {
        event.preventDefault();

        const messageText = $('#chat-form #message').val();
        const files = $('#chat-images')[0].files;

        if (!messageText.trim() && (!files || files.length === 0)) return;

        const tempId = 'temp-' + Date.now();

        if (messageText.trim() || (files && files.length > 0 || video)) {
            let imagesHtml = '';
            if (files && files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const url = URL.createObjectURL(files[i]);
                    imagesHtml += `<img src="${url}" alt="attachment" class="message-image">`;
                }
            }

            $(".messages").append(`
                <div class="message-row sent optimistic" data-temp-id="${tempId}">
                    <div class="message-content">
                        ${messageText.trim() ? `<div class="message-text">${$('<div>').text(messageText).html()}</div>` : ''}
                        <div class="message-time">${new Date().toLocaleTimeString().slice(0,5)}</div>
                    </div>
                </div>
            `);
            $('#chat-form #message').val('');
            scrollToBottom();
        }

        const formData = new FormData();
        formData.append('_token', window.chatConfig.csrfToken);
        formData.append('content', messageText);
        formData.append('receiver_id', window.chatConfig.friendId);
        formData.append('client_id', tempId);
        formData.append('dialog_id', window.chatConfig.dialogId);

        if (files && files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }
            $('#chat-images').val('');
        }

        $.ajax({
            url: '/broadcast',
            type: 'POST',
            headers: {
                'X-Socket-Id': window.Echo.socketId()
            },
            data: formData,
            processData: false,
            contentType: false,
        });
    });

    $('#chat-images').on('change', function() {
        const files = this.files;
        if (files && files.length > 0) {
            $('#chat-form').submit();
        }
    });
});

function renderAllMessageTimes() {
    document.querySelectorAll('.message-time').forEach(function (el) {
        const isoTime = el.dataset.time;
        if (!isoTime) return;

        const date = new Date(isoTime);
        if (isNaN(date)) {
            el.textContent = '';
            return;
        }

        el.textContent = date.toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    scrollToBottom(true);
});