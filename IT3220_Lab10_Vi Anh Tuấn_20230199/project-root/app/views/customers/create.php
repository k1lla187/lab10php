<?php
// app/views/customers/create.php
?>
<h3>Thêm khách hàng</h3>
<form method="post" action="index.php?c=customers&a=store">
  <div class="mb-3">
    <label class="form-label">Full name</label>
    <input name="full_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" class="form-control" type="email">
  </div>
  <div class="mb-3">
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control">
  </div>
  <button class="btn btn-primary">Lưu</button>
  <a class="btn btn-secondary" href="index.php?c=customers&a=index">Hủy</a>
</form>