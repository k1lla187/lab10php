-- sql/schema.sql
CREATE DATABASE IF NOT EXISTS lab10_sales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab10_sales;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  sku VARCHAR(50) UNIQUE,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120),
  phone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_date DATE NOT NULL,
  total DECIMAL(12,2) NOT NULL DEFAULT 0,
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS order_items (
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  PRIMARY KEY(order_id, product_id),
  FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- sample data
INSERT INTO products (name, sku, price, stock) VALUES
('Sách A', 'SKU-A', 100000.00, 10),
('Sách B', 'SKU-B', 150000.00, 5),
('Bút', 'SKU-PEN', 5000.00, 100);

INSERT INTO customers (full_name, email, phone) VALUES
('Nguyễn Văn A', 'a@example.com', '0901000001'),
('Trần Thị B', 'b@example.com', '0901000002');