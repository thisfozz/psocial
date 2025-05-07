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

                return;
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

            if (val === 'goto login') {
                window.location.href = '/login';

                return;
            }

            if (val === 'goto register') {
                window.location.href = '/register';

                return;
            }

            if (val.split(' ')[0] === 'help') {
                terminalInput.value = '';

                const help = document.createElement('div');

                help.className = 'command-line-home';
                help.innerHTML = `<span class=\"prompt-home\">guest@psocial:~$</span><span class=\"terminal-text-home\">${val}</span>`;
                terminalOutput.appendChild(help);

                const helpBlock = document.createElement('pre');

                helpBlock.className = 'terminal-help-block';
                helpBlock.innerHTML = `
goto login        Перейти на страницу входа
goto register     Перейти на страницу регистрации
clear             Очистить терминал
quote || qo [--tag <тема цитаты>]      Показать рандомную цитату (опционально с тегом: --tech, --age, ...)
tr || translate <текст> [--lang <код_языка>] — перевести текст на указанный язык.
wttr <город>                  Показать погоду в указанном городе
ip                            Показать IP-адрес
pass || password [--l <длина>]             Сгенерировать пароль (опционально с длиной: --l 16)
`;

                const key = val.split(' ')[1];
                if (key !== undefined && key !== '--quote' && key !== '--qo' && key !== '--tr' && key !== '--translate' && key !== '--wttr' && key !== '--pass' && key !== '--password' && key !== '--ip') {
                    const errorDiv = document.createElement('div');
                    const helpDiv = document.createElement('div');

                    errorDiv.textContent = `Ошибка: неизвестный ключ '${key}'`;
                    helpDiv.className = 'terminal-error-line';
                    helpDiv.textContent = `Введите help для получения списка команд`;
                    errorDiv.className = 'terminal-error-line';
                    terminalOutput.appendChild(errorDiv);
                    terminalOutput.appendChild(helpDiv);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                }

                if(key === '--quote' || key === '--qo') {
                    helpBlock.innerHTML = `
quote || qo [--tag <тема цитаты>]      Показать рандомную цитату (опционально с тегом: --tech, --age, ...)

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

                if(key === '--tr' || key === '--translate') {
                    helpBlock.innerHTML = `
tr <текст> [--lang <код_языка>] — перевести текст на указанный язык.
Если ключ --lang не указан, перевод будет выполнен на русский язык (ru) по умолчанию.

Примеры:
  tr Hello, world!                # перевод на русский
  translate Hello, world! --lang de      # перевод на немецкий
  tr Привет, мир! --lang ja       # перевод на японский
`;
                }

                if(key === '--wttr') {
                    helpBlock.innerHTML = `
wttr <город>        Показать погоду в указанном городе

Примеры:
  wttr Москва       # показать погоду в Москве
  wttr Тюмень        # показать погоду в Тюмени
`;
                }

                if(key === '--ip') {
                    helpBlock.innerHTML = `
ip                  Показать IP-адрес
`;
                }

                if(key === '--pass' || key === '--password') {
                    helpBlock.innerHTML = `
pass || password [--l <длина>]             Сгенерировать пароль
Если ключ --l не указан, пароль будет сгенерирован с длиной 16 символов по умолчанию.

Примеры:
  password          # сгенерировать пароль с длиной 16 символов
  pass --l 18       # сгенерировать пароль с длиной 18 символов
`;
                }

                terminalOutput.appendChild(helpBlock);
                terminalOutput.scrollTop = terminalOutput.scrollHeight;

                return;
            }

            if (val.split(' ')[0] === 'quote' || val.split(' ')[0] === 'qo') {
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
                    const timeout = 5000;
                    
                    const fetchWithTimeout = async (url, options = {}) => {
                        const controller = new AbortController();
                        const id = setTimeout(() => controller.abort(), timeout);
                        
                        try {
                            const response = await fetch(url, {
                                ...options,
                                signal: controller.signal
                            });
                            clearTimeout(id);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            return response;
                        } catch (error) {
                            clearTimeout(id);
                            if (error.name === 'AbortError') {
                                throw new Error('Превышено время ожидания ответа');
                            } else if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                                throw new Error('Ошибка сети: проверьте подключение к интернету');
                            } else if (error.message.includes('HTTP error')) {
                                throw new Error(`Сервис недоступен (код ошибки: ${error.message.split(':')[1]})`);
                            }
                            throw error;
                        }
                    };

                    try {
                        const response = await fetchWithTimeout(`http://api.quotable.io/quotes/random?tags=${tag}`);
                        const quoteData = await response.json();
                        const quote = quoteData[0].content;
                        const author = quoteData[0].author;

                        try {
                            const translateResponse = await fetchWithTimeout('http://92.255.174.145:3000/api/translate', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ text: quote, to: 'ru' })
                            });
                            
                            const data = await translateResponse.json();
                            const translated = data.text || quote;
                            const quoteElement = document.createElement('div');

                            quoteElement.className = 'terminal-output-line';
                            quoteElement.innerHTML = `${translated} <span style="color:#6ee7b7;">— ${author}</span>`;
                            terminalOutput.appendChild(quoteElement);
                        } catch (error) {
                            const errorDiv = document.createElement('div');

                            errorDiv.className = 'terminal-error-line';
                            errorDiv.textContent = `Ошибка перевода: ${error.message}`;
                            terminalOutput.appendChild(errorDiv);
                        }
                    } catch (error) {
                        const errorDiv = document.createElement('div');

                        errorDiv.className = 'terminal-error-line';
                        errorDiv.textContent = `Ошибка получения цитаты: ${error.message}`;
                        terminalOutput.appendChild(errorDiv);
                    }

                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                })();

                terminalOutput.scrollTop = terminalOutput.scrollHeight;
                terminalInput.value = '';

                return;
            }

            if (val.split(' ')[0] === 'wttr') {
                let city = val.split(' ')[1];
                if (!city) {
                    const errorDiv = document.createElement('div');
                    const helpDiv = document.createElement('div');

                    errorDiv.textContent = `Ошибка: укажите город после wttr`;
                    helpDiv.className = 'terminal-error-line';
                    helpDiv.textContent = `Пример: wttr Москва`;
                    errorDiv.className = 'terminal-error-line';
                    terminalOutput.appendChild(errorDiv);
                    terminalOutput.appendChild(helpDiv);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                }
                fetch(`https://wttr.in/${city}?format=3`)
                .then(response => response.text())
                .then(data => {
                    let lines = data.split('\n');
                    let weatherLine = lines.find(line => line.includes('°C') || line.includes('°F'));

                    if (weatherLine) {
                        const wttr = document.createElement('div');
                        wttr.className = 'terminal-output-line';
                        wttr.innerHTML = weatherLine;
                        terminalOutput.appendChild(wttr);
                    }
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                });

                return;
            }

            if (val.split(' ')[0] === 'tr' || val.split(' ')[0] === 'translate') {
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
                if(!text || text.replace(/[^\wа-яА-ЯёЁ]/gi, '').length === 0) {
                    const errorDiv = document.createElement('div');
                    const helpDiv = document.createElement('div');
                    errorDiv.textContent = `Ошибка: введите текст для перевода`;
                    helpDiv.className = 'terminal-error-line';
                    helpDiv.textContent = `Пример: tr Hello --lang en`;
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
                    const translate = document.createElement('div');

                    translate.className = 'terminal-output-line';
                    translate.innerHTML = `${translated}`;
                    terminalOutput.appendChild(translate);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                });

                return;
            }

            if(val.split(' ')[0] === 'ip') {
                let length = val.split(' ')[1];
                if(!length) {
                    length = 16;
                }
                fetch('https://api.ipify.org/?format=json', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ length: length })
                })
                .then(response => response.json())
                .then(data => {
                    const ip = document.createElement('div');

                    ip.className = 'terminal-output-line';
                    ip.innerHTML = `Your IP: ${data.ip}`;
                    terminalOutput.appendChild(ip);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                });

                return;
            }

            if(val.split(' ')[0] === 'password' || val.split(' ')[0] === 'pass') {
                let length = 16;
                const arg = val.split(' ')[1];
                const allowedKeys = ['--l', '--length'];

                if(arg) {
                    if(allowedKeys.includes(arg)) {
                        length = val.split(' ')[2];
                    } else {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'terminal-error-line';
                        errorDiv.textContent = `Ошибка: неизвестный ключ '${arg}'`;
                        terminalOutput.appendChild(errorDiv);
                        terminalOutput.scrollTop = terminalOutput.scrollHeight;
                        terminalInput.value = '';

                        return;
                    }
                }
                fetch(`http://92.255.174.145:3001/api/password?length=${length}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    const password = document.createElement('div');

                    password.className = 'terminal-output-line';
                    password.innerHTML = `Сгенерированный пароль: ${data.random_password}`;
                    terminalOutput.appendChild(password);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                })
                .catch(error => {
                    const errorDiv = document.createElement('div');

                    errorDiv.className = 'terminal-error-line';
                    errorDiv.textContent = `Ошибка: ${error.message}`;
                    terminalOutput.appendChild(errorDiv);
                    terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    terminalInput.value = '';

                    return;
                });

                return;
            }

            const errorDiv = document.createElement('div');
            const helpDiv = document.createElement('div');
            
            errorDiv.textContent = `Ошибка: неизвестная команда '${val.split(' ')[0]}'`;
            helpDiv.className = 'terminal-error-line';
            helpDiv.textContent = `Введите help для получения списка команд`;
            errorDiv.className = 'terminal-error-line';
            terminalOutput.appendChild(errorDiv);
            terminalOutput.appendChild(helpDiv);
            terminalOutput.scrollTop = terminalOutput.scrollHeight;
            terminalInput.value = '';

            return;
        }
    });
}