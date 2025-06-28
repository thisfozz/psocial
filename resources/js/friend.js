document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.friend-actions-friends-dropdown').forEach(function(drop) {
        const btn = drop.querySelector('.friend-actions-friends');
        const menu = drop.querySelector('.friend-dropdown-friends');

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.querySelectorAll('.friend-dropdown-friends').forEach(function(otherMenu) {
                if (otherMenu !== menu) otherMenu.style.display = 'none';
            });
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.friend-dropdown-friends').forEach(function(menu) {
            menu.style.display = 'none';
        });
    });
});