<?php
// app/views/orders/index.php
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Đơn hàng</h3>
  <a class="btn btn-primary" href="index.php?c=orders&a=create">Tạo đơn hàng</a>
</div>

<table class="table table-bordered bg-white">
  <thead><tr><th>#</th><th>Customer</th><th>Order date</th><th>Total</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($orders as $o): ?>
    <tr>
      <td><?= e($o['id']) ?></td>
      <td><?= e($o['customer_name']) ?></td>
      <td><?= e($o['order_date']) ?></td>
      <td><?= e($o['total']) ?></td>
      <td><a class="btn btn-sm btn-outline-primary" href="index.php?c=orders&a=show&id=<?= e($o['id']) ?>">Xem</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>