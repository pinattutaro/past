CREATE DATABASE IF NOT EXISTS db_member CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_member;

CREATE TABLE tb_member (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender VARCHAR(10),
    mail VARCHAR(100) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    state VARCHAR(20) DEFAULT '解除'
);
