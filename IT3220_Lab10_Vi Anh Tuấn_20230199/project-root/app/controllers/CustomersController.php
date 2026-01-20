<?php
// app/controllers/CustomersController.php
declare(strict_types=1);

class CustomersController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new CustomerRepository(getPDO());
    }

    public function indexAction()
    {
        try {
            $customers = $this->repo->getAll();
            $this->render('customers/index', [
                'customers' => $customers,
                'flash_success' => $this->getFlash('success'),
                'flash_error' => $this->getFlash('error'),
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi lấy danh sách khách hàng.');
            $this->render('customers/index', ['customers' => []]);
        }
    }

    public function createAction()
    {
        $this->render('customers/create', [
            'flash_error' => $this->getFlash('error'),
            'flash_success' => $this->getFlash('success'),
        ]);
    }

    public function storeAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=customers&a=index');
        }

        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];
        if ($full_name === '') $errors[] = 'Tên không được để trống';
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';

        if ($errors) {
            $this->setFlash('error', implode('. ', $errors));
            $this->redirect('index.php?c=customers&a=create');
        }

        try {
            $this->repo->create([
                'full_name' => $full_name,
                'email' => $email !== '' ? $email : null,
                'phone' => $phone !== '' ? $phone : null,
            ]);
            $this->setFlash('success', 'Thêm khách hàng thành công.');
            $this->redirect('index.php?c=customers&a=index');
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->setFlash('error', 'Lỗi khi thêm khách hàng.');
            $this->redirect('index.php?c=customers&a=create');
        }
    }
}