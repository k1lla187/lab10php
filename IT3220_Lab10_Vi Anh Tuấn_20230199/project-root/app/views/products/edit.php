<?php
// app/views/products/edit.php
?>
<h3>Chỉnh sửa sản phẩm</h3>
<form method="post" action="index.php?c=products&a=update">
  <input type="hidden" name="id" value="<?= e($product['id']) ?>">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="<?= e($product['name']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">SKU</label>
    <input name="sku" class="form-control" value="<?= e($product['sku']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Price</label>
    <input name="price" type="number" step="0.01" class="form-control" value="<?= e($product['price']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input name="stock" type="number" class="form-control" value="<?= e($product['stock']) ?>">
  </div>
  <button class="btn btn-primary">Cập nhật</button>
  <a class="btn btn-secondary" href="index.php?c=products&a=index">Hủy</a>
</form>