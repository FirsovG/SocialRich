-- Wenn es eine Datenbank mit diesen Namen gibt, wird die gelöscht
-- Sonst kann man in der .pdf Datei die Struktur sehen
DROP DATABASE IF EXISTS db_social_rich;
CREATE DATABASE db_social_rich;

CREATE TABLE `tbl_users`
(
  `user_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_email` char(32) NOT NULL UNIQUE,
  `user_username` char(32) NOT NULL UNIQUE,
  `user_password` char(64) NOT NULL,
  `user_firstname` char(32) NOT NULL,
  `user_surname` char(64) NOT NULL,
  `user_image` text,
  `user_birthday` date,
  `user_status` text,
  `user_employement_id` int
);

CREATE TABLE `tbl_employements`
(
  `employement_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employement_name` varchar(80) NOT NULL UNIQUE
);

CREATE TABLE `tbl_chats`
(
  `chat_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chat_user_1` int NOT NULL,
  `chat_user_2` int NOT NULL
);

CREATE TABLE `tbl_messages`
(
  `chat_id` int NOT NULL,
  `message_id` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `message_sender_id` int NOT NULL,
  `message_text` text NOT NULL,
  UNIQUE KEY (`chat_id`, `message_id`)
);

ALTER TABLE `tbl_users` ADD FOREIGN KEY (`user_employement_id`) REFERENCES `tbl_employements` (`employement_id`);

ALTER TABLE `tbl_chats` ADD FOREIGN KEY (`chat_user_1`) REFERENCES `tbl_users` (`user_id`);

ALTER TABLE `tbl_chats` ADD FOREIGN KEY (`chat_user_2`) REFERENCES `tbl_users` (`user_id`);

ALTER TABLE `tbl_messages` ADD FOREIGN KEY (`chat_id`) REFERENCES `tbl_chats` (`chat_id`);

INSERT INTO tbl_employements(`employement_name`)
VALUES ("Business"),
	     ("IT"),
       ("Managament"),
       ("Banking");