-- sql/sample_data.sql
-- Thêm dữ liệu mẫu cho lab10_sales
-- Chạy sau khi import schema.sql và khi DB đang ở USE lab10_sales

USE lab10_sales;

-- Sản phẩm (1 bản ghi / INSERT để có LAST_INSERT_ID())
INSERT INTO products (name, sku, price, stock) VALUES
('Laptop ABC', 'SKU-LAP-001', 15000000.00, 5);
SET @p1 = LAST_INSERT_ID();

INSERT INTO products (name, sku, price, stock) VALUES
('Điện thoại XYZ', 'SKU-PHN-002', 7000000.00, 10);
SET @p2 = LAST_INSERT_ID();

INSERT INTO products (name, sku, price, stock) VALUES
('Tai nghe Bluetooth', 'SKU-AUD-003', 750000.00, 25);
SET @p3 = LAST_INSERT_ID();

INSERT INTO products (name, sku, price, stock) VALUES
('Bàn phím cơ', 'SKU-KBD-004', 1200000.00, 15);
SET @p4 = LAST_INSERT_ID();

INSERT INTO products (name, sku, price, stock) VALUES
('Chuột không dây', 'SKU-MSE-005', 350000.00, 40);
SET @p5 = LAST_INSERT_ID();

-- Khách hàng
INSERT INTO customers (full_name, email, phone) VALUES
('Nguyễn Văn A', 'nva@example.com', '0901000001');
SET @c1 = LAST_INSERT_ID();

INSERT INTO customers (full_name, email, phone) VALUES
('Trần Thị B', 'ttb@example.com', '0901000002');
SET @c2 = LAST_INSERT_ID();

INSERT INTO customers (full_name, email, phone) VALUES
('Lê Văn C', 'lvc@example.com', '0901000003');
SET @c3 = LAST_INSERT_ID();

-- Tạo đơn hàng 1 (khách @c1)
-- BEGIN transaction-like behaviour in SQL script (optional)
-- Tạo order
INSERT INTO orders (customer_id, order_date, total) VALUES (@c1, '2026-01-15', 0.00);
SET @o1 = LAST_INSERT_ID();

-- Thêm items cho order1
-- Item 1: 1 x Laptop ABC (@p1)
SELECT price INTO @price_p1 FROM products WHERE id = @p1;
INSERT INTO order_items (order_id, product_id, qty, price) VALUES (@o1, @p1, 1, @price_p1);
UPDATE products SET stock = stock - 1 WHERE id = @p1;

-- Item 2: 2 x Chuột không dây (@p5)
SELECT price INTO @price_p5 FROM products WHERE id = @p5;
INSERT INTO order_items (order_id, product_id, qty, price) VALUES (@o1, @p5, 2, @price_p5);
UPDATE products SET stock = stock - 2 WHERE id = @p5;

-- Cập nhật total cho order1
UPDATE orders SET total = (
  SELECT IFNULL(SUM(qty * price), 0) FROM order_items WHERE order_id = @o1
) WHERE id = @o1;

-- Tạo đơn hàng 2 (khách @c2)
INSERT INTO orders (customer_id, order_date, total) VALUES (@c2, '2026-01-16', 0.00);
SET @o2 = LAST_INSERT_ID();

-- Item 1: 3 x Tai nghe Bluetooth (@p3)
SELECT price INTO @price_p3 FROM products WHERE id = @p3;
INSERT INTO order_items (order_id, product_id, qty, price) VALUES (@o2, @p3, 3, @price_p3);
UPDATE products SET stock = stock - 3 WHERE id = @p3;

-- Item 2: 1 x Bàn phím cơ (@p4)
SELECT price INTO @price_p4 FROM products WHERE id = @p4;
INSERT INTO order_items (order_id, product_id, qty, price) VALUES (@o2, @p4, 1, @price_p4);
UPDATE products SET stock = stock - 1 WHERE id = @p4;

-- Cập nhật total cho order2
UPDATE orders SET total = (
  SELECT IFNULL(SUM(qty * price), 0) FROM order_items WHERE order_id = @o2
) WHERE id = @o2;

-- Một vài bản ghi bổ sung để thử search/sort
INSERT INTO products (name, sku, price, stock) VALUES
('Sạc dự phòng', 'SKU-PSU-006', 250000.00, 30),
('Cáp sạc Type-C', 'SKU-CBL-007', 80000.00, 100);

-- Kiểm tra dữ liệu hiện trạng (tuỳ chọn)
-- SELECT * FROM products;
-- SELECT * FROM customers;
-- SELECT * FROM orders;
-- SELECT * FROM order_items;