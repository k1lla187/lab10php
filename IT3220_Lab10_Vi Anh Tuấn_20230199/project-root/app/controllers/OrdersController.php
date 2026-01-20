<?php
// app/controllers/OrdersController.php
declare(strict_types=1);

class OrdersController extends Controller
{
    protected $repo;
    protected $productRepo;
    protected $customerRepo;

    public function __construct()
    {
        $pdo = getPDO();
        $this->repo = new OrderRepository($pdo);
        $this->productRepo = new ProductRepository($pdo);
        $this->customerRepo = new CustomerRepository($pdo);
    }

    public function indexAction()
    {
        try {
            $orders = $this->repo->getAll();
            $this->render('orders/index', [
                'orders' => $orders,
                'flash_success' => $this->getFlash('success'),
                'flash_error' => $this->getFlash('error'),
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi lấy danh sách đơn hàng.');
            $this->render('orders/index', ['orders' => []]);
        }
    }

    public function createAction()
    {
        try {
            $customers = $this->customerRepo->getAll();
            $products = $this->productRepo->getAll(['q'=>'','sort'=>'created_at','dir'=>'desc']);
            $this->render('orders/create', [
                'customers' => $customers,
                'products' => $products,
                'flash_error' => $this->getFlash('error'),
                'flash_success' => $this->getFlash('success'),
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi chuẩn bị tạo đơn.');
            $this->redirect('index.php?c=orders&a=index');
        }
    }

    public function storeAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=orders&a=index');
        }

        $customer_id = (int)($_POST['customer_id'] ?? 0);
        $items = $_POST['items'] ?? []; // expected: ['product_id' => qty, ...]

        if ($customer_id <= 0) {
            $this->setFlash('error', 'Chọn khách hàng hợp lệ.');
            $this->redirect('index.php?c=orders&a=create');
        }

        // Validate items: ensure at least one and qty > 0
        $orderItems = [];
        foreach ($items as $product_id => $qty) {
            $pid = (int)$product_id;
            $q = (int)$qty;
            if ($pid <= 0) continue;
            if ($q <= 0) continue;
            $orderItems[] = ['product_id' => $pid, 'qty' => $q];
        }
        if (empty($orderItems)) {
            $this->setFlash('error', 'Đơn hàng phải có ít nhất 1 sản phẩm với số lượng > 0.');
            $this->redirect('index.php?c=orders&a=create');
        }

        try {
            // create order; repository handles transaction, stock check, and total calc
            $orderId = $this->repo->createOrder($customer_id, $orderItems);
            $this->setFlash('success', 'Tạo đơn hàng thành công.');
            $this->redirect("index.php?c=orders&a=show&id={$orderId}");
        } catch (RuntimeException $e) {
            // business error like insufficient stock
            error_log($e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('index.php?c=orders&a=create');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi tạo đơn hàng (xem log).');
            $this->redirect('index.php?c=orders&a=create');
        }
    }

    public function showAction()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('error', 'ID không hợp lệ.');
            $this->redirect('index.php?c=orders&a=index');
        }
        try {
            $order = $this->repo->find($id);
            if (!$order) {
                $this->setFlash('error', 'Đơn hàng không tồn tại.');
                $this->redirect('index.php?c=orders&a=index');
            }
            $this->render('orders/show', ['order' => $order]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi lấy thông tin đơn hàng.');
            $this->redirect('index.php?c=orders&a=index');
        }
    }
}