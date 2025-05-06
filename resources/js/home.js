document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById('terminal-input');
    const output = document.getElementById('terminal-output');
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const val = input.value.trim();
            if (val) {
                const history = document.createElement('div');
                history.className = 'command-line-home';
                history.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">${val}</span>`;
                output.appendChild(history);
                input.value = '';
                output.scrollTop = output.scrollHeight;
            }
        }
    });
});

const terminalInput = document.getElementById('terminal-input');
const terminalOutput = document.getElementById('terminal-output');
if (terminalInput) {
    terminalInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = terminalInput.value.trim();

            if (val === 'clear') {
                terminalOutput.innerHTML = '';
                terminalInput.value = '';
                return;
            }

            if(val == 'goto login') {
                window.location.href = '/login';
                return;
            }

            if(val == 'goto register') {
                window.location.href = '/register';
                return;
            }

            if (val == 'help') {
                terminalInput.value = '';

                // 1. Выводим команду help
                const help = document.createElement('div');
                help.className = 'command-line-home';
                help.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">help</span>`;
                terminalOutput.appendChild(help);

                // 2. Выводим "Commands:"
                const commands = document.createElement('div');
                commands.className = 'command-line-home';
                commands.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">Commands:</span>`;
                terminalOutput.appendChild(commands);

                // 3. Выводим описание команды goto login
                const help2 = document.createElement('div');
                help2.className = 'command-line-home';
                help2.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">goto login --goto the login page</span>`;
                terminalOutput.appendChild(help2);

                // 4. Выводим описание команды goto register
                const help3 = document.createElement('div');
                help3.className = 'command-line-home';
                help3.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">goto register --goto the register page</span>`;
                terminalOutput.appendChild(help3);

                // 5. Выводим описание команды clear
                const help4 = document.createElement('div');
                help4.className = 'command-line-home';
                help4.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">clear --clear the terminal</span>`;
                terminalOutput.appendChild(help4);

                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                return;
            }

            if (val === 'motd') {
                const history = document.createElement('div');
                history.className = 'command-line-home';
                history.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">motd</span>`;
                terminalOutput.appendChild(history);

                (async () => {
                    try {
                        const response = await fetch('https://api.quotable.io/random');
                        const quoteData = await response.json();
                        const quote = quoteData.content;
                        const author = quoteData.author;

                        fetch('http://92.255.174.145:3000/api/translate', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ text: quote, to: 'ru' })
                        })
                        .then(response => response.json())
                        .then(data => {
                            const translated = data.text || quote;
                            const motd = document.createElement('div');
                            motd.className = 'terminal-output-line';
                            motd.innerHTML = `${translated} <span style="color:#6ee7b7;">— ${author}</span>`;
                            terminalOutput.appendChild(motd);
                            terminalOutput.scrollTop = terminalOutput.scrollHeight;
                            terminalInput.value = '';
                        });
                    } catch (error) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'terminal-output-line';
                        errorDiv.textContent = 'Ошибка получения цитаты.';
                        terminalOutput.appendChild(errorDiv);
                        terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    }
                })();

                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';
                return;
            }

            if (val) {
                const history = document.createElement('div');
                history.className = 'command-line-home';
                history.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">${val}</span>`;
                terminalOutput.appendChild(history);
                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';
            }
        }
    });
}