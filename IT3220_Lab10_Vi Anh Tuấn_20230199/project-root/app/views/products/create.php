<?php
// app/views/products/create.php
?>
<h3>Thêm sản phẩm</h3>
<form method="post" action="index.php?c=products&a=store">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">SKU</label>
    <input name="sku" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Price</label>
    <input name="price" type="number" step="0.01" class="form-control" value="0">
  </div>
  <div class="mb-3">
    <label class="form-label">Stock</label>
    <input name="stock" type="number" class="form-control" value="0">
  </div>
  <button class="btn btn-primary">Lưu</button>
  <a class="btn btn-secondary" href="index.php?c=products&a=index">Hủy</a>
</form>