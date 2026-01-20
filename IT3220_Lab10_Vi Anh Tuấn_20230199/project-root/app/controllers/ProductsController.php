<?php
// app/controllers/ProductsController.php
declare(strict_types=1);

class ProductsController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new ProductRepository(getPDO());
    }

    public function indexAction()
    {
        // whitelist sort/dir
        $allowedSort = ['name','price','stock','created_at'];
        $allowedDir = ['asc','desc'];

        $q = trim((string)($_GET['q'] ?? ''));

        // Read params into local variables with defaults to avoid undefined index warnings
        $sortParam = isset($_GET['sort']) ? (string)$_GET['sort'] : 'created_at';
        $dirParam  = isset($_GET['dir'])  ? (string)$_GET['dir']  : 'desc';

        // Normalize and validate against whitelist
        $sort = in_array($sortParam, $allowedSort, true) ? $sortParam : 'created_at';
        $dirLower = strtolower($dirParam);
        $dir = in_array($dirLower, $allowedDir, true) ? $dirLower : 'desc';

        try {
            $products = $this->repo->getAll(['q' => $q, 'sort' => $sort, 'dir' => $dir]);
            $this->render('products/index', [
                'products' => $products,
                'q' => $q,
                'sort' => $sort,
                'dir' => $dir,
                'flash_success' => $this->getFlash('success'),
                'flash_error' => $this->getFlash('error'),
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi lấy danh sách sản phẩm.');
            $this->render('products/index', ['products' => [], 'q' => $q, 'sort'=>$sort, 'dir'=>$dir]);
        }
    }

    public function createAction()
    {
        $this->render('products/create', [
            'flash_error' => $this->getFlash('error'),
            'flash_success' => $this->getFlash('success'),
        ]);
    }

    public function storeAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=products&a=index');
        }

        $name = trim($_POST['name'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';

        // validate
        $errors = [];
        if ($name === '') $errors[] = 'Tên không được để trống';
        if (!is_numeric($price) || (float)$price < 0) $errors[] = 'Giá phải >= 0';
        if (!ctype_digit((string)$stock) || (int)$stock < 0) $errors[] = 'Stock phải là số nguyên >= 0';

        if ($errors) {
            $this->setFlash('error', implode('. ', $errors));
            $this->redirect('index.php?c=products&a=create');
        }

        try {
            $this->repo->create([
                'name' => $name,
                'sku' => $sku !== '' ? $sku : null,
                'price' => (float)$price,
                'stock' => (int)$stock,
            ]);
            $this->setFlash('success', 'Thêm sản phẩm thành công.');
            $this->redirect('index.php?c=products&a=index');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi thêm sản phẩm (xem log).');
            $this->redirect('index.php?c=products&a=create');
        }
    }

    public function editAction()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ.');
            $this->redirect('index.php?c=products&a=index');
        }

        $product = $this->repo->find($id);
        if (!$product) {
            $this->setFlash('error', 'Sản phẩm không tồn tại.');
            $this->redirect('index.php?c=products&a=index');
        }

        $this->render('products/edit', [
            'product' => $product,
            'flash_error' => $this->getFlash('error'),
            'flash_success' => $this->getFlash('success'),
        ]);
    }

    public function updateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=products&a=index');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ.');
            $this->redirect('index.php?c=products&a=index');
        }

        $name = trim($_POST['name'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $price = $_POST['price'] ?? '';
        $stock = $_POST['stock'] ?? '';

        $errors = [];
        if ($name === '') $errors[] = 'Tên không được để trống';
        if (!is_numeric($price) || (float)$price < 0) $errors[] = 'Giá phải >= 0';
        if (!ctype_digit((string)$stock) || (int)$stock < 0) $errors[] = 'Stock phải là số nguyên >= 0';

        if ($errors) {
            $this->setFlash('error', implode('. ', $errors));
            $this->redirect("index.php?c=products&a=edit&id={$id}");
        }

        try {
            $this->repo->update($id, [
                'name' => $name,
                'sku' => $sku !== '' ? $sku : null,
                'price' => (float)$price,
                'stock' => (int)$stock,
            ]);
            $this->setFlash('success', 'Cập nhật thành công.');
            $this->redirect('index.php?c=products&a=index');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi cập nhật (xem log).');
            $this->redirect("index.php?c=products&a=edit&id={$id}");
        }
    }

    public function deleteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=products&a=index');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ.');
            $this->redirect('index.php?c=products&a=index');
        }
        try {
            $this->repo->delete($id);
            $this->setFlash('success', 'Xóa thành công.');
            $this->redirect('index.php?c=products&a=index');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi xóa (xem log).');
            $this->redirect('index.php?c=products&a=index');
        }
    }
}