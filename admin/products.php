<?php
require_once '../backend/admin_page.php';

if (!$giris_yapildi) {
  header("location: index.html");
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel - Ürünler</title>
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
    <h3>Ürünler</h3>
    <button class="btn btn-success mb-3" id="addProductBtn">Ürün Ekle</button>

    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Görsel</th>
          <th>İsim</th>
          <th>Açıklama</th>
          <th>Fiyat</th>
          <th>Kategori</th>
          <th>İşlemler</th>
        </tr>
      </thead>
      <tbody id="productsTableBody">
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Ürün Ekle</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="productForm">
            <input type="hidden" id="productIndex" />
            <div class="mb-3">
              <label class="form-label">Ürün Görseli</label>
              <input class="form-control" type="file" name="image" multiple accept="image/*" id="productImage">

            </div>
            <div class="mb-3">
              <label class="form-label">Ürün İsmi</label>
              <input
                type="text"
                class="form-control"
                id="productName"
                required />
            </div>
            <div class="mb-3">
              <label class="form-label">Açıklama</label>
              <input class="form-control" type="text" name="Aciklama" id="productDesc">
            </div>
            <div class="mb-3">
              <label class="form-label">Fiyat</label>
              <input
                type="number"
                class="form-control"
                id="productPrice"
                required />
            </div>
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select id="productCategory" class="form-select">
                <option value="">Kategori Seç</option>
                <option value="elektronik">Elektronik</option>
                <option value="giyim">Giyim</option>
                <option value="ev_yasam">Ev & Yaşam</option>
                <option value="aksesuar">Aksesuar</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Kaydet</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mock ürünler
    let products = [{
        image: "../img/",
        name: "Ürün 1",
        desc: "deneme card",
        price: 100,
        category: "Kategori A"
      },
      {
        image: "../img/mug.jpeg",
        name: "Ürün 2",
        des: "deneme",
        price: 200,
        category: "Kategori B"
      }
    ];


    const productsTableBody = document.getElementById('productsTableBody');

    const productModalEl = document.getElementById("productModal");
    const productModal = new bootstrap.Modal(productModalEl);

    const productForm = document.getElementById('productForm');

    function renderProducts() {
      productsTableBody.innerHTML = "";
      products.forEach((p, i) => {
        productsTableBody.innerHTML += `
          <tr>
            <td><img src="${p.image}" width="100"></td>
            <td>${p.name}</td>
            <td>${p.desc}</td>
            <td>${p.price} ₺</td>
            <td>${p.category}</td>
            <td>
              <button class="btn btn-warning btn-sm me-1" onclick="editProduct(${i})">Düzenle</button>
              <button class="btn btn-danger btn-sm" onclick="deleteProduct(${i})">Sil</button>
            </td>
          </tr>
        `;
      });
    }

    function editProduct(index) {
      const p = products[index];
      document.getElementById('modalTitle').textContent = "Ürün Düzenle";
      document.getElementById('productIndex').value = index;
      document.getElementById('productImage').value;
      document.getElementById('productName').value = p.name;
      document.getElementById('productDesc').value = p.desc;
      document.getElementById('productPrice').value = p.price;
      document.getElementById('productCategory').value = p.category;
      productModal.show();
    }

    function deleteProduct(index) {
      if (confirm("Silinsin mi?")) {
        products.splice(index, 1);
        renderProducts();
      }
    }

    document.getElementById('addProductBtn').addEventListener('click', () => {
      productForm.reset();
      document.getElementById('productIndex').value = "";
      document.getElementById('modalTitle').textContent = "Ürün Ekle";
      productModal.show();
    });

    productForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const index = document.getElementById('productIndex').value;

      const fileInput = document.getElementById('productImage');
      let image = "https://via.placeholder.com/60";

      if (fileInput.files.length > 0) {
        imageURL = URL.createObjectURL(fileInput.files[0]);
      }

      const newProduct = {
        image: imageURL,
        name: document.getElementById('productName').value,
        desc: document.getElementById('productDesc').value,
        price: document.getElementById('productPrice').value,
        category: document.getElementById('productCategory').value,
      };

      if (index === "") {
        products.push(newProduct);
      } else {
        products[index] = newProduct;
      }

      productModal.hide();
      renderProducts();
    });

    // Sayfa açıldığında listele
    renderProducts();
  </script>

</body>

</html>