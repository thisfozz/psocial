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
            let tag = '';
            let valid = true;


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

            if (val.split(' ')[0] === 'help') {
                terminalInput.value = '';

                // Выводим команду help
                const help = document.createElement('div');
                help.className = 'command-line-home';
                help.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">${val}</span>`;
                terminalOutput.appendChild(help);

                // Терминальный список команд
                const helpBlock = document.createElement('pre');
                helpBlock.className = 'terminal-help-block';
                helpBlock.innerHTML = `
goto login        Перейти на страницу входа
goto register     Перейти на страницу регистрации
clear             Очистить терминал
motd [--tag <тема цитаты>]      Показать рандомную цитату (опционально с тегом: --tech, --age, ...)
tr <текст> [--lang <код_языка>] — перевести текст на указанный язык.
`;

                if(val.split(' ')[1] == '--motd') {
                    helpBlock.innerHTML = `
motd [--tag <тема цитаты>]      Показать рандомную цитату (опционально с тегом: --tech, --age, ...)

--tech            Технологии
--age             Возраст
--business        Бизнес
--change          Изменения
--character       Персонаж
--time            Время
--social          Социальная справедливость
--science         Наука
--truth           Истина
`;
                }

                if(val.split(' ')[1] == '--tr') {
                    helpBlock.innerHTML = `
tr <текст> [--lang <код_языка>] — перевести текст на указанный язык.
Если ключ --lang не указан, перевод будет выполнен на русский язык (ru) по умолчанию.

Примеры:
  tr Hello, world!                # перевод на русский
  tr Hello, world! --lang de      # перевод на немецкий
  tr Привет, мир! --lang ja       # перевод на японский
`;
                }
                terminalOutput.appendChild(helpBlock);

                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                return;
            }

            if (val.split(' ')[0] === 'motd') {
                if(val.split(' ')[1] == '--tech') {
                    tag = 'technology';
                } else if(val.split(' ')[1] == '--age') {
                    tag = 'age';
                } else if(val.split(' ')[1] == '--business') {
                    tag = 'business';
                } else if(val.split(' ')[1] == '--change') {
                    tag = 'change';
                } else if(val.split(' ')[1] == '--character') {
                    tag = 'character';
                } else if(val.split(' ')[1] == '--time') {
                    tag = 'time';
                } else if(val.split(' ')[1] == '--social') {
                    tag = 'social-justice';
                } else if(val.split(' ')[1] == '--science') {
                    tag = 'science';
                } else if(val.split(' ')[1] == '--truth') {
                    tag = 'truth';
                }
                else if(val.split(' ').length > 1) {
                    valid = false;
                }

                const history = document.createElement('div');
                history.className = 'command-line-home';
                history.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">${val}</span>`;
                terminalOutput.appendChild(history);

                if (!valid) {
                    const errorDiv = document.createElement('div');
                    const helpDiv = document.createElement('div');
                    errorDiv.textContent = `Ошибка: неизвестный ключ '${val.split(' ')[1]}'`;
                    helpDiv.className = 'terminal-error-line';
                    helpDiv.textContent = `Введите help для получения списка команд`;
                    errorDiv.className = 'terminal-error-line';
                    terminalOutput.appendChild(errorDiv);
                    terminalOutput.appendChild(helpDiv);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';
                    return;
                }

                (async () => {
                    const response = await fetch(`https://api.quotable.io/quotes/random?tags=${tag}`);
                    const quoteData = await response.json();
                    const quote = quoteData[0].content;
                    const author = quoteData[0].author;

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
                })();

                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';
                return;
            }
            let arr = val.split(' ');
            let idx = arr.indexOf('--lang');
            let targetLanguageCode = '';
            let text = '';

            if(idx != -1) {
                targetLanguageCode = arr[idx+1];
                text = arr.slice(1, idx).join(' ');
            } else{
                text = arr.slice(1).join(' ');
                targetLanguageCode = 'ru';
            }

            if(text == ''){
                const errorDiv = document.createElement('div');
                const helpDiv = document.createElement('div');
                errorDiv.textContent = `Ошибка: неизвестный ключ '${val.split(' ')[2]}'`;
                helpDiv.className = 'terminal-error-line';
                helpDiv.textContent = `Введите help для получения списка команд`;
                errorDiv.className = 'terminal-error-line';
                terminalOutput.appendChild(errorDiv);
                terminalOutput.appendChild(helpDiv);
                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';
                return;
            }

            fetch('http://92.255.174.145:3000/api/translate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ text: text, to: targetLanguageCode })
            })
            .then(response => response.json())
            .then(data => {
                const translated = data.text || text;
                const motd = document.createElement('div');
                motd.className = 'terminal-output-line';
                motd.innerHTML = `${translated}`;
                terminalOutput.appendChild(motd);
                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';
            });

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