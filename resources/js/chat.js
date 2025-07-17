import $ from 'jquery';

let editingMessageId = null;

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

function renderAllMessageTimes() {
    document.querySelectorAll('.message-time').forEach(function(el) {
        const isoTime = el.dataset.time;
        const isEdited = el.dataset.edited === '1';
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
        if (isEdited) {
            const span = document.createElement('span');
            span.className = 'edited-mark';
            span.textContent = ' (изменено)';
            el.appendChild(span);
        }
    });
}

function setupContextMenu() {
    const menu = document.getElementById('message-context-menu');
    let currentMessageId = null;

    function hideMenu(e) {
        if (!menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    }

    document.querySelectorAll('.message-row').forEach(row => {
        row.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            currentMessageId = this.dataset.messageId;
            menu.style.display = 'block';
            menu.style.left = `${e.pageX}px`;
            menu.style.top = `${e.pageY}px`;

            setTimeout(() => {
                document.addEventListener('click', hideMenu, { once: true });
            }, 0);
        });
    });

    document.addEventListener('click', function(e) {
        if (!menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    menu.addEventListener('click', function(e) {
        e.stopPropagation();
        if (e.target.classList.contains('menu-item')) {
            const action = e.target.dataset.action;
            
            if (action === 'delete') {
                handleDeleteMessage(currentMessageId);
            } else if (action === 'edit') {
                handleEditMessage(currentMessageId);
            } else {
                console.log(`Action: ${action} for message ${currentMessageId}`);
            }
            
            menu.style.display = 'none';
        }
    });
}

function handleDeleteMessage(messageId) {
    if (!messageId) return;
    
    $.ajax({
        url: '/messages/delete',
        type: 'POST',
        data: {
            _token: window.chatConfig.csrfToken,
            message_id: messageId
        },
        success: function(res) {
            if (res.status === 'ok') {
                $(`[data-message-id="${messageId}"]`).remove();
            }
        },
        error: function(xhr) {
            console.error('Delete error:', xhr.responseText);
        }
    });
}

function handleEditMessage(messageId) {
    if (!messageId) return;
    
    const messageText = document.querySelector(`[data-message-id="${messageId}"] .message-text`);
    if (messageText) {
        $('#message').val(messageText.textContent.trim()).focus();
        editingMessageId = messageId;
        $('#edit-indicator').show();
    }
}

function setupChatForm() {
    $('#chat-form').on('submit', function(event) {
        event.preventDefault();
        const messageText = $('#message').val().trim();
        const files = $('#chat-images')[0].files;

        if (editingMessageId) {
            editMessage(messageText);
            return;
        }

        if (!messageText && (!files || files.length === 0)) return;
        sendNewMessage(messageText, files);
    });

    $('#chat-images').on('change', function() {
        if (this.files && this.files.length > 0) {
            $('#chat-form').trigger('submit');
        }
    });

    $('#message').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#chat-form').trigger('submit');
        }
    });

    $('#cancel-edit').on('click', function() {
        cancelEdit();
    });
}

function editMessage(content) {
    if (!editingMessageId) return;

    $.ajax({
        url: '/messages/edit',
        type: 'POST',
        data: {
            _token: window.chatConfig.csrfToken,
            message_id: editingMessageId,
            content: content
        },
        success: function(res) {
            if (res.status === 'ok') {
                cancelEdit();
            }
        },
        error: function(xhr) {
            console.error('Edit error:', xhr.responseText);
        }
    });
}

function cancelEdit() {
    editingMessageId = null;
    $('#edit-indicator').hide();
    $('#message').val('').focus();
}

function setupWebSocket() {
    if (!window.Echo || !window.chatConfig) return;
    if (!window.chatConfig.authId || !window.chatConfig.friendId) return;

    const ids = [window.chatConfig.authId, window.chatConfig.friendId].sort((a, b) => a - b);
    const channelName = `chat.${ids[0]}.${ids[1]}`;

    window.Echo.private(channelName)
        .listen('.chat', function(data) {
            $.post('/receive', {
                _token: window.chatConfig.csrfToken,
                message: data,
                authId: window.chatConfig.authId
            }).done(function(res) {
                $(".messages").append(res);
                scrollToBottom();
                renderAllMessageTimes();
            });
        })
        .listen('.message.deleted', function(data) {
            $(`[data-message-id="${data.id}"]`).remove();
        })
        .listen('.message.edited', function(data) {
            const message = document.querySelector(`[data-message-id="${data.id}"] .message-text`);
            if (message) {
                message.textContent = data.content;
                const timeElement = message.closest('.message-content').querySelector('.message-time');
                if (timeElement && !timeElement.querySelector('.edited-mark')) {
                    timeElement.innerHTML += ' <span class="edited-mark">(изменено)</span>';
                }
            }
        });
}

function sendNewMessage(content, files) {
    const tempId = `temp-${Date.now()}`;
    
    if (content || (files && files.length > 0)) {
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
                    ${content ? `<div class="message-text">${$('<div>').text(content).html()}</div>` : ''}
                    <div class="message-time">${new Date().toLocaleTimeString().slice(0,5)}</div>
                </div>
            </div>
        `);
        $('#message').val('');
        scrollToBottom();
    }

    const formData = new FormData();
    formData.append('_token', window.chatConfig.csrfToken);
    formData.append('content', content);
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
        error: function(xhr) {
            console.error('Send error:', xhr.responseText);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom(true);
    setupContextMenu();
    setupChatForm();
    setupWebSocket();
    renderAllMessageTimes();
});