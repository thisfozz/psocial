-- Таблица семейного положения (family_status)
CREATE TABLE family_status (
    id SERIAL PRIMARY KEY,
    status VARCHAR(50) NOT NULL
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

-- Таблица городов (cities)
CREATE TABLE cities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    country_id INT NOT NULL,
    CONSTRAINT fk_cities_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

-- Стандартная таблица пользователей Laravel с дополнительными полями
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    avatar_path VARCHAR(255),
    family_status_id INT,
    cities_id INT,
    date_of_birth DATE,
    about_me TEXT,
    status VARCHAR(50),
    is_deleted BOOLEAN DEFAULT FALSE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_family_status FOREIGN KEY (family_status_id) REFERENCES family_status(id) ON DELETE SET NULL,
    CONSTRAINT fk_users_cities FOREIGN KEY (cities_id) REFERENCES cities(id) ON DELETE SET NULL
);

-- Таблица архивных пользователей
CREATE TABLE archived_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(255) UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    nickname VARCHAR(15) UNIQUE,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица интересов (user_interests)
CREATE TABLE user_interests (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    quotes VARCHAR(1000),
    activity VARCHAR(500),
    favorite_music VARCHAR(1000),
    favorite_books VARCHAR(1000),
    favorite_games VARCHAR(1000),
    CONSTRAINT fk_user_interests_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица запросов в друзья (friend_requests)
CREATE TABLE friend_requests (
    id SERIAL PRIMARY KEY,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    status VARCHAR(20) DEFAULT 'в ожидании' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица друзей (friends)
CREATE TABLE friends (
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, friend_id),
    
    CONSTRAINT fk_friends_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_friends_friend_id FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT check_different_users CHECK (user_id <> friend_id)
);

-- Таблица постов (posts)
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_posts_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица лайков к постам (post_likes)
CREATE TABLE post_likes (
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (post_id, user_id),

    CONSTRAINT fk_post_likes_post_id FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_post_likes_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Сохранение изрбражений в S3
CREATE TABLE post_images (
    id SERIAL PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_post_images_post_id FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_post_images_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE post_videos (
    id SERIAL PRIMARY KEY,
    post_id INT NOT NULL,
    platform VARCHAR(50) NOT NULL,
    video_id VARCHAR(255) NOT NULL,
    embed_code TEXT NOT NULL,
    thumbnail_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_post_videos_post_id FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Таблица связи пользователей и языков (user_languages)
CREATE TABLE user_languages (
    user_id INT NOT NULL,
    language_id INT NOT NULL,
    PRIMARY KEY (user_id, language_id),
    
    CONSTRAINT fk_user_languages_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_languages_language FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);

-- Таблица сообщений (messages)
CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_receiver FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Таблица диалогов (чатов) между пользователями
CREATE TABLE dialogs (
    id SERIAL PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (user1_id, user2_id),
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE messages ADD COLUMN dialog_id INT;
ALTER TABLE messages ADD CONSTRAINT fk_messages_dialog FOREIGN KEY (dialog_id) REFERENCES dialogs(id) ON DELETE CASCADE;


-- Таблица видео в сообщениях
CREATE TABLE message_videos (
    id SERIAL PRIMARY KEY,
    message_id INT NOT NULL,
    dialog_id INT NOT NULL,
    platform VARCHAR(50) NOT NULL,
    video_id VARCHAR(255) NOT NULL,
    embed_code TEXT NOT NULL,
    thumbnail_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_message_videos_message_id FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    CONSTRAINT fk_message_videos_dialog_id FOREIGN KEY (dialog_id) REFERENCES dialogs(id) ON DELETE CASCADE
);