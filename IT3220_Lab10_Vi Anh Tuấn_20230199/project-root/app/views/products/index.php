<?php
// app/views/products/index.php
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Sản phẩm</h3>
  <a class="btn btn-primary" href="index.php?c=products&a=create">Thêm sản phẩm</a>
</div>

<form class="row g-2 mb-3" method="get" action="index.php">
  <input type="hidden" name="c" value="products">
  <input type="hidden" name="a" value="index">
  <div class="col-auto">
    <input type="text" name="q" class="form-control" placeholder="Tìm theo tên/SKU" value="<?= e($q ?? '') ?>">
  </div>
  <div class="col-auto">
    <select name="sort" class="form-select">
      <option value="created_at" <?= ($sort==='created_at')?'selected':''?>>Mới nhất</option>
      <option value="name" <?= ($sort==='name')?'selected':''?>>Name</option>
      <option value="price" <?= ($sort==='price')?'selected':''?>>Price</option>
      <option value="stock" <?= ($sort==='stock')?'selected':''?>>Stock</option>
    </select>
  </div>
  <div class="col-auto">
    <select name="dir" class="form-select">
      <option value="desc" <?= ($dir==='desc')?'selected':''?>>Desc</option>
      <option value="asc" <?= ($dir==='asc')?'selected':''?>>Asc</option>
    </select>
  </div>
  <div class="col-auto">
    <button class="btn btn-secondary">Tìm</button>
  </div>
</form>

<table class="table table-bordered bg-white">
  <thead>
    <tr>
      <th>#</th><th>Name</th><th>SKU</th><th>Price</th><th>Stock</th><th>Created</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
    <tr>
      <td><?= e($p['id']) ?></td>
      <td><?= e($p['name']) ?></td>
      <td><?= e($p['sku']) ?></td>
      <td><?= e($p['price']) ?></td>
      <td><?= e($p['stock']) ?></td>
      <td><?= e($p['created_at']) ?></td>
      <td>
        <a class="btn btn-sm btn-outline-primary" href="index.php?c=products&a=edit&id=<?= e($p['id']) ?>">Edit</a>
        <form style="display:inline" method="post" action="index.php?c=products&a=delete" onsubmit="return confirm('Xác nhận xóa?')">
          <input type="hidden" name="id" value="<?= e($p['id']) ?>">
          <button class="btn btn-sm btn-outline-danger">Delete</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>