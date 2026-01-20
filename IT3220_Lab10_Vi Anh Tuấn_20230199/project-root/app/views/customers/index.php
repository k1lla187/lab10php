<?php
// app/views/customers/index.php
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Khách hàng</h3>
  <a class="btn btn-primary" href="index.php?c=customers&a=create">Thêm khách hàng</a>
</div>

<table class="table table-bordered bg-white">
  <thead><tr><th>#</th><th>Full name</th><th>Email</th><th>Phone</th></tr></thead>
  <tbody>
    <?php foreach ($customers as $c): ?>
    <tr>
      <td><?= e($c['id']) ?></td>
      <td><?= e($c['full_name']) ?></td>
      <td><?= e($c['email']) ?></td>
      <td><?= e($c['phone']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>