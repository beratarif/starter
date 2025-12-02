<?php
require_once '../backend/admin_page.php';

if (!$giris_yapildi) {
  header("location: index.html");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>

    <div class="ms-auto d-flex align-items-center gap-2">
      <button onclick="window.location.href='dashboard.php'" class="btn btn-outline-light btn-sm">
        Dashboard
      </button>
      <button onclick="window.location.href='products.php'" class="btn btn-outline-light btn-sm">
        Ürünler
      </button>
      <button onclick="window.location.href='orders.php'" class="btn btn-outline-light btn-sm">
        Sipariş Durumu
      </button>
      <?php if ($giris_yapildi): ?>
        <div class="dropdown">
          <button
            class="btn btn-outline-light btn-sm dropdown-toggle text-uppercase fw-semibold"
            type="button"
            id="userDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php echo htmlspecialchars($yetkili['ad_soyad']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item text-danger" href="../backend/logout.php?hangi_cikis=yetkili">Çıkış Yap</a></li>
          </ul>
        </div>
      <?php else: ?>
        <button
          onclick="window.location.href='./index.html'"
          class="btn btn-outline-light btn-sm">
          Giriş Yap
        </button>
      <?php endif; ?>
    </div>
  </nav>

  <div class="container mt-4">
    <h3>Siparişler</h3>
    <table class="table table-bordered align-middle mt-3">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Müşteri</th>
          <th>Tutar</th>
          <th>İşlem</th>
          <th>Durum</th>
        </tr>
      </thead>
      <tbody id="ordersTableBody">

      </tbody>


    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/orders.js"></script>
</body>

</html>