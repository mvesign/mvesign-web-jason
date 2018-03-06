CREATE TABLE jason.transactions (
	id INT AUTO_INCREMENT NOT NULL,
    amount DECIMAL(6,2) NOT NULL,
    created_on DATETIME NOT NULL,
    PRIMARY KEY (id)
);