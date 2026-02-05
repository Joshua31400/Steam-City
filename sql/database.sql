CREATE
DATABASE IF NOT EXISTS steam_city;
USE
steam_city;

CREATE TABLE users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(255) UNIQUE NOT NULL,
    password   VARCHAR(255)        NOT NULL,
    username   VARCHAR(100)        NOT NULL,
    role       ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE games
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    type        VARCHAR(100), -- FPS, TPS ...
    image_url   VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_games
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    game_id    INT NOT NULL,
    start_date DATE,
    play_time  INT       DEFAULT 0,
    death_date DATE,
    added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE
);

CREATE TABLE achievements
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    game_id     INT          NOT NULL,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    icon_url    VARCHAR(255),
    FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE
);

CREATE TABLE user_achievements
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements (id) ON DELETE CASCADE
);

