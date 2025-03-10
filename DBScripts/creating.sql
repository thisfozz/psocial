-- Таблица аккаунтов (accounts) - для аутентификации
CREATE TABLE accounts (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) UNIQUE NOT NULL,
    phone_number VARCHAR(15) UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP
);

-- Таблица профилей пользователей (user_profiles) - для информации о пользователе
CREATE TABLE user_profiles (
    account_id BIGINT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    nickname VARCHAR(15) UNIQUE,
    avatar VARCHAR(255),
    family_status_id BIGINT,
    cities_id BIGINT,
    date_of_birth DATE,
    about_me TEXT,
    status VARCHAR(50),
    CONSTRAINT fk_user_profiles_account FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_profiles_family_status FOREIGN KEY (family_status_id) REFERENCES family_status(id) ON DELETE SET NULL,
    CONSTRAINT fk_user_profiles_cities FOREIGN KEY (cities_id) REFERENCES cities(id) ON DELETE SET NULL
);

-- Таблица семейного положения (family_status)
CREATE TABLE family_status (
    id SERIAL PRIMARY KEY,
    status VARCHAR(50) NOT NULL
);

-- Таблица городов (cities)
CREATE TABLE cities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    country_id INT NOT NULL,
    CONSTRAINT fk_cities_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

-- Таблица стран (countries)
CREATE TABLE countries (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Таблица языков (languages)
CREATE TABLE languages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    language_code VARCHAR(2) NOT NULL UNIQUE
);

-- Таблица интересов (user_interests)
CREATE TABLE user_interests (
    id SERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    quotes TEXT,
    activity TEXT,
    favorite_music TEXT,
    favorite_books TEXT,
    favorite_games TEXT,
    CONSTRAINT fk_user_interests_user FOREIGN KEY (user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE
);

-- Таблица запросов в друзья (friend_requests)
CREATE TABLE friend_requests (
    id SERIAL PRIMARY KEY,
    from_user_id BIGINT NOT NULL,
    to_user_id BIGINT NOT NULL,
    status VARCHAR(20) DEFAULT 'в ожидании' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (from_user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE
);

-- Таблица друзей (friends)
CREATE TABLE friends (
    user_id BIGINT NOT NULL,
    friend_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, friend_id),
    
    CONSTRAINT fk_friends_user_id FOREIGN KEY (user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE,
    CONSTRAINT fk_friends_friend_id FOREIGN KEY (friend_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE,
    CONSTRAINT check_different_users CHECK (user_id <> friend_id)
);

-- Таблица постов (posts)
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE
);

-- Таблица лайков к постам (post_likes)
CREATE TABLE post_likes (
    post_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (post_id, user_id),
    CONSTRAINT fk_post_likes_post_id FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_post_likes_user_id FOREIGN KEY (user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE
);

-- Таблица связи пользователей и языков (user_languages)
CREATE TABLE user_languages (
    user_id BIGINT NOT NULL,
    language_id INT NOT NULL,
    PRIMARY KEY (user_id, language_id),
    CONSTRAINT fk_user_languages_user FOREIGN KEY (user_id) REFERENCES user_profiles(account_id) ON DELETE CASCADE,
    CONSTRAINT fk_user_languages_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);