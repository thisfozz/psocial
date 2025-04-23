function typeText(elementId, text, speed = 50, callback = null) {
    let i = 0;
    function typing() {
        if (i < text.length) {
            document.getElementById(elementId).innerHTML += text.charAt(i);
            i++;
            setTimeout(typing, speed);
        } else if (callback) {
            callback();
        }
    }
    typing();
}
document.addEventListener("DOMContentLoaded", function() {
    typeText("typed-welcome", "Welcome to PSocial - a terminal social network.", 50, function() {
        typeText("typed-desc", "Inspired by the atmosphere of Mr. Robot.", 50);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById('terminal-input');
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const val = input.value.trim();
            if (val) {
                const history = document.createElement('div');
                history.className = 'command-line-home';
                history.innerHTML = `<span class="prompt-home">PSocial@home:~$</span><span class="terminal-text-home" style="color:#fff;">${val}</span>`;
                input.closest('.terminal-body-home').insertBefore(history, input.closest('.terminal-input-row'));
                input.value = '';
            }
        }
    });
});