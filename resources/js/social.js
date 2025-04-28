const openModalBtn = document.getElementById('openModalBtn');
if(openModalBtn){
    openModalBtn.onclick = function(e) {
        e.preventDefault();
        document.getElementById('modalOverlayMoreInfo').style.display = 'flex';
    };
    const closeModalBtn = document.getElementById('closeModalBtn');
    if(closeModalBtn){
        closeModalBtn.onclick = function() {
            document.getElementById('modalOverlayMoreInfo').style.display = 'none';
        };
        document.getElementById('modalOverlayMoreInfo').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    }
}

const followBtn = document.getElementById('followBtn');
document.addEventListener('DOMContentLoaded', function() {
    if (followBtn) {
        followBtn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
            fetch(`/friend-request/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                alert('Ошибка при отправке заявки');
            });
        });
    }
});

const acceptBtn = document.getElementById('acceptFriendRequestBtn');
document.addEventListener('DOMContentLoaded', function() {
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/friend-request/accept/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                alert('Ошибка при обработке запроса');
            });
        });
    }

    const declinedBtn = document.getElementById('declineFriendRequestBtn');
    if(declinedBtn){
        declinedBtn.addEventListener('click', function(){
            const requestId = this.getAttribute('data-request-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/friend-request/decline/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                alert('Ошибка при отклонении запроса');
            });
        });
    }
});

const cancelRequestBtn = document.getElementById('cancelRequestBtn');
document.addEventListener('DOMContentLoaded', function() {
    if (cancelRequestBtn) {
        cancelRequestBtn.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const userId = this.getAttribute('data-user-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/friend-request/cancel/${requestId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Typpe': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при отмене запроса');
                return response.json();
            })
            .then(data => {
                const parent = document.getElementById('requsetActions');
                parent.innerHTML = `
                <form method="POST" action="/friend/request/send/${userId}">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <button type="submit" class="terminal-profile-follow-btn-social">Follow</button>
                </form>`;
            })
            .catch(error => {
                alert('Ошибка при отмене запроса');
            });
        });
    }
});

const unfriendBtn = document.getElementById('unfriendBtn');
document.addEventListener('DOMContentLoaded', function() {
    if (unfriendBtn) {
        unfriendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('unfriendModal').style.display = 'flex';
        });
    }

    const closeUnfriendModalBtn = document.getElementById('closeUnfriendModalBtn');
    if (closeUnfriendModalBtn) {
        closeUnfriendModalBtn.addEventListener('click', function() {
            document.getElementById('unfriendModal').style.display = 'none';
        });
    }
    const cancelUnfriendBtn = document.getElementById('cancelUnfriendBtn');
    if (cancelUnfriendBtn) {
        cancelUnfriendBtn.addEventListener('click', function() {
            document.getElementById('unfriendModal').style.display = 'none';
        });
    }
    const unfriendModal = document.getElementById('unfriendModal');
    if (unfriendModal) {
        unfriendModal.addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });
    }

    const confirmUnfriendBtn = document.getElementById('confirmUnfriendBtn');
    if (confirmUnfriendBtn) {
        confirmUnfriendBtn.addEventListener('click', function() {
            const userId = document.getElementById('unfriendBtn').getAttribute('data-user-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/friends/remove/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    alert('Ошибка при удалении из друзей');
                }
                return response.json();
            })
            .then(data => {
                location.reload();
            })
            .catch(error => {
                alert('Ошибка при удалении из друзей');
            });
        });
    }
});

let timer;
document.querySelector('.terminal-search-input-social').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('search-results');
    if (query.length < 2) {
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
        clearTimeout(timer);
        return;
    }
    clearTimeout(timer);
    timer = setTimeout(() => {
        fetch(`/search-users?search=${encodeURIComponent(query)}`)
            .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                resultsDiv.innerHTML = '<div class="dropdown-item">Ничего не найдено</div>';
            } else {
                resultsDiv.innerHTML = data.map(user =>
                    `<div class="dropdown-item">
                        <img src="${user.avatar_path}$size=40" alt="avatar">
                        <span>${user.first_name} ${user.last_name}</span>
                    </div>`
                ).join('');
            }
            resultsDiv.style.display = 'block';
        });
    }, 400);
});