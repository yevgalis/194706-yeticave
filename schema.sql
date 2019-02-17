CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
	id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    class VARCHAR(30));

CREATE TABLE lot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(200) NOT NULL,
    image VARCHAR(50) NOT NULL,
    start_price INT NOT NULL,
    end_date DATETIME,
    step INT NOT NULL,
    author_id INT NOT NULL,
    winner_id INT,
    category_id INT NOT NULL
);

CREATE TABLE bet (
	id INT AUTO_INCREMENT PRIMARY KEY,
    bet_date DATETIME,
    amount INT NOT NULL,
    user_id INT NOT NULL,
    lot_id INT  NOT NULL);

CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(64),
    avatar VARCHAR(50),
    contacts VARCHAR(60),
    lots VARCHAR(200),
    bets VARCHAR(200));
