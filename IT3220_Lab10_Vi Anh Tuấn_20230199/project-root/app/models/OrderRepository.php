<?php
// app/models/OrderRepository.php
declare(strict_types=1);

class OrderRepository
{
    protected $pdo;
    protected $productRepo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->productRepo = new ProductRepository($pdo);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT o.*, c.full_name as customer_name FROM orders o JOIN customers c ON o.customer_id = c.id ORDER BY o.id DESC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT o.*, c.full_name AS customer_name, c.email, c.phone FROM orders o JOIN customers c ON o.customer_id = c.id WHERE o.id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch();
        if (!$order) return null;
        $stmt2 = $this->pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $stmt2->execute([':order_id' => $id]);
        $items = $stmt2->fetchAll();
        $order['items'] = $items;
        return $order;
    }

    // $items: array of ['product_id'=>int, 'qty'=>int]
    // Returns order id or throws exception on business error
    public function createOrder(int $customerId, array $items): int
    {
        try {
            $this->pdo->beginTransaction();

            // Compute total and check stock
            $total = 0.0;
            foreach ($items as $it) {
                $pid = $it['product_id'];
                $qty = $it['qty'];

                // Lock row and get current price/stock (SELECT FOR UPDATE)
                $stmt = $this->pdo->prepare("SELECT price, stock FROM products WHERE id = :id FOR UPDATE");
                $stmt->execute([':id' => $pid]);
                $row = $stmt->fetch();
                if (!$row) {
                    throw new RuntimeException("Sản phẩm (id={$pid}) không tồn t��i.");
                }
                $price = (float)$row['price'];
                $stock = (int)$row['stock'];
                if ($stock < $qty) {
                    throw new RuntimeException("Sản phẩm ID {$pid} không đủ tồn kho (còn {$stock}).");
                }
                $total += $price * $qty;
            }

            // Insert order
            $stmtOrd = $this->pdo->prepare("INSERT INTO orders (customer_id, order_date, total) VALUES (:customer_id, :order_date, :total)");
            $stmtOrd->execute([
                ':customer_id' => $customerId,
                ':order_date' => date('Y-m-d'),
                ':total' => $total,
            ]);
            $orderId = (int)$this->pdo->lastInsertId();

            // Insert items and decrease stock
            $stmtItem = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (:order_id, :product_id, :qty, :price)");
            $stmtDec = $this->pdo->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id");

            foreach ($items as $it) {
                $pid = $it['product_id'];
                $qty = $it['qty'];

                // get price (we locked earlier but re-select to be safe)
                $stmt = $this->pdo->prepare("SELECT price FROM products WHERE id = :id");
                $stmt->execute([':id' => $pid]);
                $row = $stmt->fetch();
                $price = (float)$row['price'];

                $stmtItem->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $pid,
                    ':qty' => $qty,
                    ':price' => $price,
                ]);

                $stmtDec->execute([':qty' => $qty, ':id' => $pid]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}