# PostgreSQL для 1С — Сборка из исходников на Debian

## Главная причина ошибки при прошлой сборке

Скорее всего при сборке просто не были установлены нужные `-dev` библиотеки **до** запуска `./configure`. `configure` не ругается жёстко на их отсутствие — он просто молча отключает эти модули. В итоге `make` проходит, всё устанавливается, но без нужных расширений.

---

## Правильный порядок действий — пошагово

### Шаг 1 — Сначала ставим ВСЕ зависимости

Это самое важное. Делать это нужно **до** `./configure`:

```bash
sudo apt-get update

sudo apt-get install -y \
    build-essential \
    libreadline-dev \
    zlib1g-dev \
    libssl-dev \
    libpam0g-dev \
    libldap2-dev \
    libxml2-dev \
    libxslt1-dev \
    libperl-dev \
    python3-dev \
    tcl-dev \
    libgss-dev \
    libkrb5-dev \
    bison \
    flex \
    pkg-config \
    uuid-dev \
    libicu-dev
```

> Обрати внимание — нужны именно пакеты с суффиксом `-dev`. Без них заголовочные файлы недоступны и модули не собираются.

---

### Шаг 2 — Распаковываем исходники и применяем патч

```bash
# Распаковываем исходники PostgreSQL
tar -xf postgresql-15.X.tar.gz
cd postgresql-15.X

# Применяем патч от 1С
patch -p1 < /путь/до/файла/patch_1c.patch
```

После `patch` должно быть много строк `patching file ...` и **никаких** `FAILED` или `Hunk FAILED`.

---

### Шаг 3 — configure с полными флагами

```bash
./configure \
    --prefix=/usr/lib/postgresql/15 \
    --bindir=/usr/lib/postgresql/15/bin \
    --with-openssl \
    --with-pam \
    --with-ldap \
    --with-libxml \
    --with-libxslt \
    --with-python \
    --with-perl \
    --with-tcl \
    --with-gssapi \
    --with-uuid=e2fs \
    --with-icu \
    --enable-integer-datetimes \
    --enable-thread-safety
```

> Ключевые флаги для 1С — это `--with-openssl`, `--with-libxml` и `--with-uuid=e2fs`. Именно они дают `uuid-ossp` и SSL-подключение.

---

### Шаг 4 — Проверяем вывод configure

После `./configure` в самом конце будет сводка. Убедись что там написано `yes` напротив нужных модулей:

```
OpenSSL support:            yes
PAM support:                yes
LDAP support:               yes
XML support:                yes
UUID support:               yes
```

Если что-то `no` — значит не хватает соответствующего `-dev` пакета, возвращаешься к шагу 1.

---

### Шаг 5 — Сборка и установка

```bash
make -j$(nproc)
sudo make install
```

> Флаг `-j$(nproc)` использует все ядра процессора — сборка пройдёт быстрее.

---

### Шаг 6 — Установка contrib модулей

Это **обязательный** шаг который часто пропускают. Именно здесь собирается `uuid-ossp` и другие расширения:

```bash
cd contrib
sudo make install
```

---

### Шаг 7 — Создаём пользователя, инициализируем кластер

```bash
sudo useradd -m postgres 2>/dev/null || true

sudo -u postgres /usr/lib/postgresql/15/bin/initdb \
    -D /var/lib/postgresql/15/main \
    --locale=ru_RU.UTF-8 \
    -E UTF8

sudo -u postgres /usr/lib/postgresql/15/bin/pg_ctl \
    -D /var/lib/postgresql/15/main start
```

---

### Шаг 8 — Проверка uuid-ossp

```bash
sudo -u postgres /usr/lib/postgresql/15/bin/psql -c "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";"
sudo -u postgres /usr/lib/postgresql/15/bin/psql -c "SELECT uuid_generate_v4();"
```

Если возвращает UUID — всё собралось правильно.

---

## Частые ошибки

| Симптом | Причина |
|---|---|
| `uuid-ossp` не создаётся | Забыл `cd contrib && make install` |
| SSL ошибка при подключении | Не было `--with-openssl` в configure |
| `FATAL: password authentication failed` | Не настроен `pg_hba.conf` |
| 1С не видит сервер БД | PostgreSQL слушает только `localhost`, нужно поменять `listen_addresses` в `postgresql.conf` |

---

## Используемые символы Markdown

| Символ | Что делает | Пример |
|---|---|---|
| `#` | Заголовок 1 уровня | `# Заголовок` |
| `##` | Заголовок 2 уровня | `## Раздел` |
| `###` | Заголовок 3 уровня | `### Подраздел` |
| `**текст**` | **Жирный текст** | `**важно**` |
| `` `текст` `` | `Моноширинный` (инлайн-код) | `` `./configure` `` |
| ` ```bash ` | Блок кода с подсветкой | см. примеры выше |
| `---` | Горизонтальная линия | — |
| `>` | Цитата / примечание | `> Обрати внимание` |
| `\|col\|col\|` | Таблица | см. таблицы выше |
| `\|---\|---\|` | Разделитель заголовка таблицы | — |
