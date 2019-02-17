CREATE DATABASE yeticave_194706
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave_194706;

CREATE TABLE categories (
	category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    class VARCHAR(30));

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    username VARCHAR(40) NOT NULL,
    password VARCHAR(64) NOT NULL,
    avatar VARCHAR(50),
    contacts VARCHAR(60) NOT NULL,
    lots VARCHAR(200),
    bets VARCHAR(200)
);

CREATE TABLE lots (
    lot_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(200) NOT NULL,
    image VARCHAR(50) NOT NULL,
    start_price INT UNSIGNED NOT NULL,
    end_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    step INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    winner_id INT UNSIGNED,
    category_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (author_id)
      REFERENCES users(user_id),
	FOREIGN KEY (winner_id)
      REFERENCES users(user_id),
	FOREIGN KEY (category_id)
      REFERENCES categories(category_id)
);

CREATE TABLE bets (
    bet_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bet_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    lot_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
    FOREIGN KEY (lot_id)
        REFERENCES lots(lot_id)
);

CREATE INDEX lot_enddate ON lots(end_date);
CREATE INDEX lot_author ON lots(author_id);
CREATE INDEX lot_category ON lots(category_id);
CREATE INDEX bet_user ON bets(user_id);
CREATE INDEX bet_lot ON bets(lot_id);
