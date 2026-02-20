CREATE DATABASE IF NOT EXISTS db_english CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_english;

CREATE TABLE tb_english (
    id INT AUTO_INCREMENT PRIMARY KEY,
    english VARCHAR(255) NOT NULL,
    japanese VARCHAR(255) NOT NULL
);
