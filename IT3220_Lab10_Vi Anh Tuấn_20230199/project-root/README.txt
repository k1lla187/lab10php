README - Mini Sales (Products, Customers, Orders)
------------------------------------------------

Yêu cầu:
- PHP 8.x, PDO MySQL
- Tạo database và user DB không dùng root
- Import sql/schema.sql

Cách chạy:
1. Tạo DB và user:
   - Dùng file sql/schema.sql (import qua phpMyAdmin hoặc mysql CLI)
2. Cập nhật app/config/db.php:
   - Đặt DB_HOST, DB_NAME (lab10_sales), DB_USER, DB_PASS chính xác
3. Đưa project v��o folder served bởi Apache (ví dụ htdocs) hoặc cấu hình VirtualHost:
   - public/ là document root. Truy cập: http://localhost/<project>/public/index.php
   - Routing sử dụng query string, ví dụ:
     - products list: index.php?c=products&a=index
     - create product: index.php?c=products&a=create (GET), index.php?c=products&a=store (POST)
     - edit: index.php?c=products&a=edit&id=1 (GET), update: index.php?c=products&a=update (POST)
     - delete: index.php?c=products&a=delete (POST)
     - customers list/create: index.php?c=customers&a=index / &a=create / &a=store
     - orders: index.php?c=orders&a=create, &a=store, &a=index, &a=show&id=...

Bảo mật & thực hành:
- Mọi truy vấn đều dùng prepared statements (PDO->prepare + execute).
- ID từ URL/FORM ép kiểu (int) trước khi sử dụng.
- Sort/dir được whitelist trong controller/repository.
- Các thao tác POST dùng Post/Redirect/Get để tránh re-post.
- Khi tạo order, repository dùng transaction và SELECT ... FOR UPDATE để kiểm tra tồn kho, nếu stock không đủ sẽ rollBack và ném lỗi nghiệp vụ.
- Lỗi SQL/Exception được ghi vào logs bằng error_log() — không hiển thị lỗi SQL chi tiết lên giao diện.

Mini-report chống SQLi (ví dụ test):
- Thử chuỗi tìm kiếm: q=' OR 1=1 --
  Kết quả: ứng dụng vẫn an toàn vì search dùng:
    WHERE name LIKE :kw OR sku LIKE :kw
  và param :kw được bind với '%...%' -> không làm thay đổi logic SQL.

Ghi chú:
- Đây là scaffold mẫu để nộp bài. Bạn có thể bổ sung xác thực, phân trang, hoặc tối ưu UX.
- Nếu dùng Apache, bạn có thể thêm .htaccess để route mọi request tới public/index.php.