CREATE DATABASE IF NOT EXISTS ecopecas;
USE ecopecas;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  birthdate DATE,
  address TEXT
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  description TEXT,
  year INT,
  condition_state VARCHAR(50),
  price DECIMAL(10,2)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2),
  address TEXT,
  created_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  qty INT,
  price DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO products (name, description, year, condition_state, price) VALUES
('Roda 16"', 'Roda usada em bom estado', 2020, 'Usada', 50.00),
('Suspensão Dianteira', 'Peça semi-nova de suspensão', 2019, 'Semi-nova', 120.00),
('Disco de Travão', 'Disco usado mas funcional', 2018, 'Usada', 30.00);
