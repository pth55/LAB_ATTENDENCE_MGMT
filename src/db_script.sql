CREATE DATABASE IF NOT EXISTS user_management;

USE user_management;

-- Table to store user information
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(10) NOT NULL,
    password VARCHAR(18) NOT NULL
);

-- Table to store user login timestamps
CREATE TABLE IF NOT EXISTS user_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(10) NOT NULL,
    login_time DATETIME NOT NULL
);

INSERT INTO users (roll_number, password) VALUES ("228W5A1201", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1202", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1203", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1204", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1205", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1206", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1207", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1208", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1209", "welcome123");
INSERT INTO users (roll_number, password) VALUES ("228W5A1210", "welcome123");
-- like wise insert all students details
