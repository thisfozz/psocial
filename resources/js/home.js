document.addEventListener("DOMContentLoaded", function() {
    const terminalInput = document.getElementById('terminal-input');
    const terminalOutput = document.getElementById('terminal-output');

    terminalInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const input = terminalInput.value.trim();
            if (!input) return;

            const history = document.createElement('div');
            history.className = 'command-line-home';
            history.innerHTML = `<span class="prompt-home">guest@psocial:~$</span><span class="terminal-text-home">${input}</span>`;
            terminalOutput.appendChild(history);
            terminalInput.value = '';
            
            handleCommand(input.toLowerCase(), terminalOutput);
            
            terminalOutput.scrollTop = terminalOutput.scrollHeight;
        }
    });
});

function handleCommand(input, output) {
    const parts = input.split(' ');
    const command = parts[0];
    const args = parts.slice(1);

    switch(command) {
        case 'clear':
        case 'cls':
            output.innerHTML = '';
            break;
            
        case 'goto':
            if (args[0] === 'login') {
                window.location.href = '/login';
            } else if (args[0] === 'register') {
                window.location.href = '/register';
            } else {
                showError(output, `Неизвестная цель для goto: ${args[0]}`);
            }
            break;
            
        case 'quote':
        case 'qo':
            handleQuoteCommand(args, output);
            break;
            
        case 'wttr':
            handleWeatherCommand(args, output);
            break;
            
        case 'tr':
        case 'translate':
            handleTranslateCommand(args, output);
            break;
            
        case 'password':
        case 'pass':
            handlePasswordCommand(args, output);
            break;
            
        case 'help':
            handleHelpCommand(args, output);
            break;
            
        default:
            showError(output, `Неизвестная команда: ${command}`);
    }
}

async function handleQuoteCommand(args, output) {
    let tag = '';
    let valid = true;
    
    const tagMap = {
        '--tech': 'technology',
        '--age': 'age',
        '--business': 'business',
        '--change': 'change',
        '--character': 'character',
        '--time': 'time',
        '--social': 'social-justice',
        '--science': 'science',
        '--truth': 'truth'
    };

    if (args[0] && tagMap[args[0]]) {
        tag = tagMap[args[0]];
    } else if (args.length > 0) {
        valid = false;
    }

    if (!valid) {
        showError(output, `Неизвестный ключ '${args[0]}'`);
        return;
    }

    const fetchWithTimeout = async (url, options = {}) => {
        const timeout = 5000;
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
            quoteElement.className = 'terminal-output-line-home';
            quoteElement.innerHTML = `${translated} <span style="color:#6ee7b7;">— ${author}</span>`;
            output.appendChild(quoteElement);
            
        } catch (translateError) {
            showError(output, `Ошибка перевода: ${translateError.message}`);
        }
    } catch (fetchError) {
        showError(output, `Ошибка получения цитаты: ${fetchError.message}`);
    }
}

function handleWeatherCommand(args, output) {
    if (args.length === 0) {
        showError(output, 'Укажите город после wttr', 'Пример: wttr Москва');
        return;
    }
    
    const city = args.join(' ');
    fetch(`https://wttr.in/${city}?format=3`)
        .then(response => response.text())
        .then(data => {
            let lines = data.split('\n');
            let weatherLine = lines.find(line => line.includes('°C') || line.includes('°F'));

            if (weatherLine) {
                const wttr = document.createElement('div');
                wttr.className = 'terminal-output-line';
                wttr.textContent = weatherLine;
                output.appendChild(wttr);
            }
        })
        .catch(error => {
            showError(output, 'Ошибка при получении данных о погоде');
        });
}

function handleTranslateCommand(args, output) {
    if (args.length === 0) {
        showError(output, 'Укажите текст для перевода', 'Пример: tr Hello --lang en');
        return;
    }

    let langIndex = args.findIndex(arg => arg === '--lang' || arg.startsWith('--lang=') || arg === '--l' || arg.startsWith('--l='));
    let targetLanguageCode = 'ru';
    let textParts = args;

    if (langIndex !== -1) {
        if ((args[langIndex] === '--lang' && args.length > langIndex + 1) || (args[langIndex] === '--l' && args.length > langIndex + 1)) {
            targetLanguageCode = args[langIndex + 1];
            textParts = args.slice(0, langIndex);
        } 
        else if (args[langIndex].startsWith('--lang=')) {
            targetLanguageCode = args[langIndex].split('=')[1];
            textParts = args.slice(0, langIndex);
        }
    }

    const text = textParts.join(' ');

    if (!text || text.replace(/[^\wа-яА-ЯёЁ]/gi, '').length === 0) {
        showError(output, 'Текст для перевода не может быть пустым', 'Пример: tr Hello --lang en');
        return;
    }

    fetch('http://92.255.174.145:3000/api/translate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            text: text, 
            to: targetLanguageCode 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const translated = data.text || text;
        const translate = document.createElement('div');
        translate.className = 'terminal-output-line';
        translate.textContent = translated;
        output.appendChild(translate);
    })
    .catch(error => {
        showError(output, `Ошибка перевода: ${error.message}`);
    })
    .finally(() => {
        output.scrollTop = output.scrollHeight;
        document.getElementById('terminal-input').value = '';
    });
}

function handlePasswordCommand(args, output) {
    const defaultLength = 16;
    let length = defaultLength;

    if (args.length > 0) {
        const lengthArgIndex = args.findIndex(arg => 
            arg === '--l' || 
            arg === '--length' || 
            arg.startsWith('--l=') || 
            arg.startsWith('--length=')
        );

        if (lengthArgIndex !== -1) {
            let lengthValue;
            
            if (args[lengthArgIndex].includes('=')) {
                lengthValue = args[lengthArgIndex].split('=')[1];
            } else if (args.length > lengthArgIndex + 1) {
                lengthValue = args[lengthArgIndex + 1];
            }

            const parsedLength = parseInt(lengthValue);
            if (!isNaN(parsedLength)) {
                length = parsedLength;
            } else {
                showError(output, 'Неверное значение длины пароля', 'Пример: password --l 12');
                return;
            }

            if (length < 4 || length > 64) {
                showError(output, 'Длина пароля должна быть от 4 до 64 символов');
                return;
            }
        } else {
            showError(output, 'Неизвестный аргумент', 'Допустимые аргументы: --l или --length');
            return;
        }
    }

    fetch(`http://92.255.174.145:3001/api/password?length=${length}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Ошибка сервера: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.random_password) {
                throw new Error('Некорректный ответ сервера');
            }

            const resultDiv = document.createElement('div');
            resultDiv.className = 'terminal-output-line';
            resultDiv.innerHTML = `
                <strong>Сгенерированный пароль (${length} симв.):</strong><br>
                <span style="color: #6ee7b7">${data.random_password}</span>
            `;
            output.appendChild(resultDiv);
        })
        .catch(error => {
            showError(output, `Ошибка при генерации пароля: ${error.message}`);
        })
        .finally(() => {
            output.scrollTop = output.scrollHeight;
            document.getElementById('terminal-input').value = '';
        });
}

function handleHelpCommand(args, output) {
    const validTags = [
        '--login',
        '--register',
        '--clear',
        '--quote',
        '--qo',
        '--tr',
        '--translate',
        '--wttr',
        '--pass',
        '--password'
    ];

    if (args.length === 0) {
        const helpBlock = document.createElement('pre');
        helpBlock.className = 'terminal-help-block';
        helpBlock.innerHTML = `
goto login        Перейти на страницу входа
goto register     Перейти на страницу регистрации
clear             Очистить терминал
quote || qo [--tag <тема цитаты>]      Показать рандомную цитату (опционально с тегом: --tech, --age, ...)
tr || translate <текст> [--lang <код_языка>] — перевести текст на указанный язык.
wttr <город>                  Показать погоду в указанном городе
pass || password [--l <длина>]             Сгенерировать пароль (опционально с длиной: --l 16)
    `;
        output.appendChild(helpBlock);
        return;
    }

    if (args.length > 0) {
        if (args[0] === '--quote' || args[0] === '--qo') {
            const helpBlock = document.createElement('pre');
            helpBlock.className = 'terminal-help-block';
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
        if (args[0] === '--tr' || args[0] === '--translate') {
            const helpBlock = document.createElement('pre');
            helpBlock.className = 'terminal-help-block';
            helpBlock.innerHTML = `
tr <текст> [--lang <код_языка>] — перевести текст на указанный язык.
Если ключ --lang не указан, перевод будет выполнен на русский язык (ru) по умолчанию.
            
Примеры:
tr Hello, world!                       # перевод на русский
translate Hello, world! --lang de      # перевод на немецкий
tr Привет, мир! --lang ja              # перевод на японский
`;
            output.appendChild(helpBlock);
        }

        if (args[0] === '--wttr') {
            const helpBlock = document.createElement('pre');
            helpBlock.className = 'terminal-help-block';
            helpBlock.innerHTML = `   
wttr <город>      Показать погоду в указанном городе
    
Примеры:
wttr Москва       # показать погоду в Москве
wttr Тюмень       # показать погоду в Тюмени
`;
            output.appendChild(helpBlock);
        }

        if (args[0] === '--pass' || args[0] === '--password') {
            const helpBlock = document.createElement('pre');
            helpBlock.className = 'terminal-help-block';
            helpBlock.innerHTML = `
pass || password [--l <длина>]             Сгенерировать пароль
Если ключ --l не указан, пароль будет сгенерирован с длиной 16 символов по умолчанию.
            
Примеры:
password          # сгенерировать пароль с длиной 16 символов
pass --l 18       # сгенерировать пароль с длиной 18 символов
`;
            output.appendChild(helpBlock);
        }
    }
}

function showError(output, message, help = 'Введите help для получения списка команд') {
    const errorDiv = document.createElement('div');
    const helpDiv = document.createElement('div');

    errorDiv.textContent = `Ошибка: ${message}`;
    helpDiv.className = 'terminal-error-line-home';
    helpDiv.textContent = help;
    errorDiv.className = 'terminal-error-line-home';
    output.appendChild(errorDiv);
    output.appendChild(helpDiv);
}