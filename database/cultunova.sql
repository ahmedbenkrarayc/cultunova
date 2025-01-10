/*CREATE DATABASE cultunova;
USE cultunova;

CREATE TABLE user(
	id INT PRIMARY KEY AUTO_INCREMENT,
	fname VARCHAR(50),
	lname VARCHAR(50),
	email VARCHAR(100) UNIQUE,
	password VARCHAR(250),
	role ENUM('admin', 'author', 'visitor'),
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW()
);

CREATE TABLE author_details(
	author_id INT PRIMARY KEY,
	picture TEXT,
	cover TEXT,
	deleted TINYINT(1) DEFAULT 0,
	FOREIGN KEY(author_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE category(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW()
);

CREATE TABLE article(
	id INT PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(50) NOT NULL,
	description TEXT,
	content TEXT,
	cover TEXT,
	status ENUM('accepted', 'in review', 'rejected') DEFAULT 'in review',
	category_id INT,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW(),
	FOREIGN KEY(category_id) REFERENCES category(id) ON DELETE CASCADE
);

#Trouver le nombre total d'articles publiés par catégorie
SELECT category_id, COUNT(*) AS total FROM article WHERE STATUS = 'accepted' GROUP BY category_id;

#Identifier les auteurs les plus actifs en fonction du nombre d'articles publiés.
#SELECT CONCAT(a.fname, ' ', a.lname) AS NAME, COUNT(*) AS Narticle FROM article ar, user a WHERE ar.author_id = a.id AND STATUS = 'accepted' GROUP BY a.id ORDER BY Narticle DESC;

#Calculer la moyenne d'articles publiés par catégorie
SELECT AVG(arcount) AS moyenne FROM (
	SELECT COUNT(*) as arcount FROM article GROUP BY category_id  
) AS tb;


#Créer une vue affichant les derniers articles publiés dans les 30 derniers jours.
CREATE VIEW pub30 AS
SELECT * 
FROM article 
WHERE status = 'accepted' AND DATEDIFF(NOW(), createdAt) <= 30;
SELECT * FROM pub30;

#Trouver les catégories qui n'ont aucun article associé
SELECT * FROM category c LEFT JOIN article a ON c.id = a.category_id WHERE a.category_id IS NULL;
*/

CREATE DATABASE cultunovav2;
USE cultunovav2;

CREATE TABLE user(
	id INT PRIMARY KEY AUTO_INCREMENT,
	fname VARCHAR(50),
	lname VARCHAR(50),
	email VARCHAR(100) UNIQUE,
	password VARCHAR(250),
	role ENUM('admin', 'author', 'visitor'),
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW()
);

CREATE TABLE author_details(
	author_id INT PRIMARY KEY,
	picture TEXT,
	cover TEXT,
	deleted TINYINT(1) DEFAULT 0,
	FOREIGN KEY(author_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE category(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW()
);

CREATE TABLE tag(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW()
);

CREATE TABLE article(
	id INT PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(50) NOT NULL,
	description TEXT,
	content TEXT,
	cover TEXT,
	status ENUM('accepted', 'in review', 'rejected') DEFAULT 'in review',
	category_id INT,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW(),
	FOREIGN KEY(category_id) REFERENCES category(id) ON DELETE CASCADE
);

CREATE TABLE articletag(
	article_id INT,
	tag_id INT,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW(),
	FOREIGN KEY(article_id) REFERENCES article(id) ON DELETE CASCADE,
	FOREIGN KEY(tag_id) REFERENCES tag(id) ON DELETE CASCADE,
	PRIMARY KEY(article_id, tag_id)
);

CREATE TABLE likes(
	article_id INT,
	visitor_id INT,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW(),
	FOREIGN KEY(article_id) REFERENCES article(id) ON DELETE CASCADE,
	FOREIGN KEY(visitor_id) REFERENCES user(id) ON DELETE CASCADE,
	PRIMARY KEY(article_id, visitor_id)
);

CREATE TABLE comment(
	id INT PRIMARY KEY AUTO_INCREMENT,
	content TEXT,
	article_id INT,
	visitor_id INT,
	createdAt DATETIME DEFAULT NOW(),
	updatedAt DATETIME DEFAULT NOW() ON UPDATE NOW(),
	FOREIGN KEY(article_id) REFERENCES article(id) ON DELETE CASCADE,
	FOREIGN KEY(visitor_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE VIEW most_liked_articles AS
SELECT 
    a.id AS article_id,
    a.title AS article_title,
    COUNT(l.article_id) AS likes_count,
    c.name AS category_name
FROM article a
LEFT JOIN likes l ON a.id = l.article_id
LEFT JOIN category c ON a.category_id = c.id
GROUP BY a.id, a.title, c.name
ORDER BY likes_count DESC;


DELIMITER //

CREATE PROCEDURE ban_user(IN userId INT)
BEGIN
    UPDATE author_details 
    SET deleted = 1
    WHERE author_id = userId;
END //

DELIMITER ;


SELECT 
    t.name AS tag_name,
    COUNT(at.tag_id) AS associations_count
FROM tag t
JOIN articletag at ON t.id = at.tag_id
JOIN article a ON at.article_id = a.id
WHERE DATEDIFF(NOW(), a.createdAt) <= 30
GROUP BY t.name
ORDER BY associations_count DESC;
