-- INSERT Categories
INSERT INTO categories (name, class) VALUES ('Доски и лыжи', 'boards');
INSERT INTO categories (name, class) VALUES ('Крепления', 'attachment');
INSERT INTO categories (name, class) VALUES ('Ботинки', 'boots');
INSERT INTO categories (name, class) VALUES ('Одежда', 'clothing');
INSERT INTO categories (name, class) VALUES ('Инструменты', 'tools');
INSERT INTO categories (name, class) VALUES ('Разное', 'other');

-- INSERT Users
INSERT INTO users (registration_date, email, username, password, avatar, contacts, lots, bets)
	VALUES (CURRENT_TIMESTAMP(), 'user1@gmail.com', 'Ivan Petrov', '123456', NULL, 'Moscow 123-45-67', NULL, NULL);
INSERT INTO users (registration_date, email, username, password, avatar, contacts, lots, bets)
	VALUES (CURRENT_TIMESTAMP(), 'user2@gmail.com', 'John Doe', 'qwerty', NULL, 'New York 3456789876', NULL, NULL);

-- INSERT lots
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), '2014 Rossignol District Snowboard', 'Snowboard', 'img/lot-1.jpg', 10999, CURRENT_TIMESTAMP() + 100, 1000, 1, NULL, 1);
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), 'DC Ply Mens 2016/2017 Snowboard', 'Snowboard', 'img/lot-2.jpg', 159999, CURRENT_TIMESTAMP() + 8, 5000, 2, NULL, 1);
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 'img/lot-3.jpg', 8000, CURRENT_TIMESTAMP() + 10, 500, 2, 1, 2);
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', 'img/lot-4.jpg', 10999, CURRENT_TIMESTAMP() + 45, 700, 1, NULL, 3);
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка', 'img/lot-5.jpg', 7500, CURRENT_TIMESTAMP() + 200, 500, 2, NULL, 4);
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
	VALUES (CURRENT_TIMESTAMP(), 'Маска Oakley Canopy', 'Маска', 'img/lot-6.jpg', 7500, CURRENT_TIMESTAMP() + 123, 400, 1, 2, 6);

-- INSERT bets
INSERT INTO bets (bet_date, amount, user_id, lot_id) VALUES (CURRENT_TIMESTAMP(), 8000, 1, 3);
INSERT INTO bets (bet_date, amount, user_id, lot_id) VALUES (CURRENT_TIMESTAMP(), 7500, 2, 6);


-- SELECT all categories
SELECT * FROM categories;

-- SELECT opened lots
SELECT l.name, l.start_price, l.image, c.name FROM lots l
	INNER JOIN categories c USING(category_id)
WHERE l.winner_id IS NULL
	AND l.end_date > NOW();

-- SELECT lots by ID
SELECT l.*, c.name FROM lots l
	INNER JOIN categories c USING(category_id)
WHERE l.lot_id = 3;

-- UPDATE lot by ID
UPDATE lots SET name = 'DC Ply Mens 2016/2017 SNOWBOARD' WHERE lot_id = 2;

-- SELECT bets by lot ID
SELECT * FROM bets WHERE lot_id = 6;
