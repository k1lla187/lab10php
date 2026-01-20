<?php
// app/views/orders/create.php
?>
<h3>Tạo đơn hàng</h3>
<form method="post" action="index.php?c=orders&a=store">
  <div class="mb-3">
    <label class="form-label">Khách hàng</label>
    <select name="customer_id" class="form-select" required>
      <option value="">-- chọn --</option>
      <?php foreach ($customers as $c): ?>
        <option value="<?= e($c['id']) ?>"><?= e($c['full_name']) ?> (<?= e($c['email']) ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>

  <h5>Sản phẩm</h5>
  <table class="table table-sm">
    <thead><tr><th>Chọn</th><th>Product</th><th>Price</th><th>Stock</th><th>Qty</th></tr></thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><input type="checkbox" name="items[<?= e($p['id']) ?>]" value="1" class="item-check" data-id="<?= e($p['id']) ?>"></td>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['price']) ?></td>
        <td><?= e($p['stock']) ?></td>
        <td><input type="number" name="items[<?= e($p['id']) ?>]" min="0" value="0" class="form-control qty-input" style="width:90px"></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p class="text-muted">Ghi chú: tích chọn sản phẩm và đặt qty > 0.</p>

  <button class="btn btn-primary">Tạo đơn</button>
  <a class="btn btn-secondary" href="index.php?c=orders&a=index">Hủy</a>
</form>