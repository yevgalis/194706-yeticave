-- INSERT Categories
INSERT INTO categories (name, class)
VALUES
	('Доски и лыжи', 'boards'),
	('Крепления', 'attachment'),
	('Ботинки', 'boots'),
	('Одежда', 'clothing'),
	('Инструменты', 'tools'),
	('Разное', 'other');

-- INSERT Users
INSERT INTO users (registration_date, email, username, password, avatar, contacts, lots, bets)
VALUES
  (CURRENT_TIMESTAMP(), 'user1@gmail.com', 'Ivan Petrov', '123456', NULL, 'Moscow 123-45-67', NULL, NULL),
  (CURRENT_TIMESTAMP(), 'user2@gmail.com', 'John Doe', 'qwerty', NULL, 'New York 3456789876', NULL, NULL);

-- INSERT lots
INSERT INTO lots (creation_date, name, description, image, start_price, end_date, step, author_id, winner_id, category_id)
VALUES
  (CURRENT_TIMESTAMP(), '2014 Rossignol District Snowboard', 'Snowboard', 'lot-1.jpg', 10999, CURRENT_TIMESTAMP() + 100, 1000, 1, NULL, 1),
  (CURRENT_TIMESTAMP(), 'DC Ply Mens 2016/2017 Snowboard', 'Snowboard', 'lot-2.jpg', 159999, CURRENT_TIMESTAMP() + 8, 5000, 2, NULL, 1),
  (CURRENT_TIMESTAMP(), 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 'lot-3.jpg', 8000, CURRENT_TIMESTAMP() + 10, 500, 2, 1, 2),
  (CURRENT_TIMESTAMP(), 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', 'lot-4.jpg', 10999, CURRENT_TIMESTAMP() + 45, 700, 1, NULL, 3),
  (CURRENT_TIMESTAMP(), 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка', 'lot-5.jpg', 7500, CURRENT_TIMESTAMP() + 200, 500, 2, NULL, 4),
  (CURRENT_TIMESTAMP(), 'Маска Oakley Canopy', 'Маска', 'lot-6.jpg', 7500, CURRENT_TIMESTAMP() + 123, 400, 1, 2, 6);

-- INSERT bets
INSERT INTO bets (bet_date, amount, user_id, lot_id) VALUES
  (CURRENT_TIMESTAMP(), 8000, 1, 3),
  (CURRENT_TIMESTAMP(), 7500, 2, 6);


-- SELECT all categories
SELECT * FROM categories;

-- SELECT opened lots
SELECT 	l.name AS title, l.start_price, l.image,
		CASE
			WHEN (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id) IS NULL THEN l.start_price
			ELSE (SELECT max(b.amount) FROM bets b WHERE b.lot_id = l.lot_id)
		END price, c.name AS category
FROM lots l
	INNER JOIN categories c USING(category_id)
WHERE l.end_date > NOW()
ORDER BY l.creation_date DESC;

-- SELECT lots by ID
SELECT l.*, c.name FROM lots l
	INNER JOIN categories c USING(category_id)
WHERE l.lot_id = 3;

-- UPDATE lot by ID
UPDATE lots SET name = 'DC Ply Mens 2016/2017 SNOWBOARD' WHERE lot_id = 2;

-- SELECT bets by lot ID
SELECT * FROM bets WHERE lot_id = 6;
