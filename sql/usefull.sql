-- Insert new achievement into the database
INSERT INTO `steam_city`.`achievements`
(`game_id`, `name`, `description`, `icon_url`)
VALUES
(6, 'Premier pas', 'Terminer le tutoriel', '/assets/images/achievements/filename.jpg'),;


-- Insert new game into the database
INSERT INTO `steam_city`.`games`
(`name`, `description`, `type`, `image_url`)
VALUES
(`Minecraft`, `Open world game ...`, `SandBox`, `/assets/images/games/filename.jpg`),;


-- Insert new user into the database
INSERT INTO `steam_city`.`users`
(`email`, `password`, `username`, `role`)
VALUES
(`john@example.com`, `hashed_password`, `JohnDoe`, `user`),;

-- Insert new user-game relationship into the database
INSERT INTO `steam_city`.`user_games`
(`user_id`, `game_id`, `start_date`, `play_time`, `death_date`)
VALUES
(1, 6, '2024-01-01', 120, NULL),

-- Insert new user-achievement relationship into the database
INSERT INTO `steam_city`.`user_achievements`
(`user_id`, `achievement_id`, `unlocked_at`)
VALUES
(1, 1, '2024-01-15 10:00:00');