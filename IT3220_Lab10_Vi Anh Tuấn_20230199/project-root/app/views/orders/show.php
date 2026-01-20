<?php
// app/views/orders/show.php
?>
<h3>Chi tiết đơn hàng #<?= e($order['id']) ?></h3>
<p><strong>Customer:</strong> <?= e($order['customer_name']) ?> (<?= e($order['email']) ?>)</p>
<p><strong>Order date:</strong> <?= e($order['order_date']) ?></p>
<p><strong>Total:</strong> <?= e($order['total']) ?></p>

<table class="table table-bordered bg-white">
  <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Line total</th></tr></thead>
  <tbody>
    <?php foreach ($order['items'] as $it): ?>
    <tr>
      <td><?= e($it['name']) ?></td>
      <td><?= e($it['price']) ?></td>
      <td><?= e($it['qty']) ?></td>
      <td><?= number_format($it['price'] * $it['qty'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>