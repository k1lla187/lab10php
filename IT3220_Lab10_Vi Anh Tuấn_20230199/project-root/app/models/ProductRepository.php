<?php
// app/models/ProductRepository.php
declare(strict_types=1);

class ProductRepository
{
    protected $pdo;
    protected $allowedSort = ['name','price','stock','created_at'];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // $opts: q, sort, dir
    public function getAll(array $opts = []): array
    {
        $q = $opts['q'] ?? '';
        $sort = $opts['sort'] ?? 'created_at';
        $dir = strtolower($opts['dir'] ?? 'desc');
        if (!in_array($sort, $this->allowedSort, true)) $sort = 'created_at';
        $dir = $dir === 'asc' ? 'ASC' : 'DESC';

        $sql = "SELECT * FROM products";
        $params = [];
        if ($q !== '') {
            $sql .= " WHERE name LIKE :kw OR sku LIKE :kw";
            $params[':kw'] = '%' . $q . '%';
        }
        $sql .= " ORDER BY {$sort} {$dir}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO products (name, sku, price, stock) VALUES (:name, :sku, :price, :stock)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':sku' => $data['sku'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET name = :name, sku = :sku, price = :price, stock = :stock WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':sku' => $data['sku'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // used by OrderRepository
    public function decreaseStock(int $productId, int $qty): bool
    {
        $stmt = $this->pdo->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty");
        return $stmt->execute([':qty' => $qty, ':id' => $productId]);
    }
}