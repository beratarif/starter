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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
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
          <button class="btn btn-outline-light btn-sm dropdown-toggle text-uppercase fw-semibold" type="button"
            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo htmlspecialchars($yetkili['ad_soyad']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item text-danger" href="../backend/logout.php?hangi_cikis=yetkili">Çıkış Yap</a></li>
          </ul>
        </div>
      <?php else: ?>
        <button onclick="window.location.href='./index.html'" class="btn btn-outline-light btn-sm">
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
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="productForm">
            <input type="hidden" id="productIndex" />
            <div class="mb-3">
              <label class="form-label">Ürün Görseli</label>
              <input class="form-control" type="file" name="image" multiple accept="image/*" id="productImage">
              <img id="currentPreview" src="../${f.gorsel}" width="100" style>
            </div>
            <div class="mb-3">
              <label class="form-label">Ürün İsmi</label>
              <input type="text" class="form-control" id="productName" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Açıklama</label>
              <input class="form-control" type="text" name="Aciklama" id="productDesc">
            </div>
            <div class="mb-3">
              <label class="form-label">Fiyat</label>
              <input type="number" class="form-control" id="productPrice" required />
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
            <button type="submit" style="display: flex;" id="confirm" class="btn btn-primary" value="ekle">Kaydet</button>
            <button type="submit" style="display: flex;" id="update" class="btn btn-primary" value="guncelle">Güncelle</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const productsTableBody = document.getElementById('productsTableBody');

    const productModalEl = document.getElementById("productModal");
    const productModal = new bootstrap.Modal(productModalEl);

    const productForm = document.getElementById('productForm');

    async function renderProducts() {
      const yanit = await fetch(`../backend/urun.php?islem=admin_icin_urunler`);
      const tum_urunler_json = await yanit.json();

      productsTableBody.innerHTML = "";
      tum_urunler_json.forEach(f => {
        productsTableBody.innerHTML += `
          <tr>
            <td><img id="prewiew" src="../${f.gorsel}" width="100"></td>
            <td>${f.ad}</td>
            <td>${f.aciklama}</td>
            <td>${f.fiyat} ₺</td>
            <td>${f.kategori}</td>
            <td>
              <button class="btn btn-warning btn-sm me-1" id="edit" onclick="editProduct(${f.urun_id})">Düzenle</button>
              <button class="btn btn-danger btn-sm" onclick="deleteProduct(${f.urun_id})">Sil</button>
            </td>
          </tr>
        `;
      });
      document.getElementById('edit').addEventListener("click", () => {
        productForm.reset();
        document.getElementById('confirm').style.display = "none";
        document.getElementById('update').style.display = "flex";
        document.getElementById('productIndex').value = "";
        document.getElementById('modalTitle').textContent = "Ürünü Güncelle";
        productModal.show();
      });
    }


    async function editProduct(index) {
      const yanit = await fetch(`../backend/urun.php?islem=getir1&urun_id=${index}`);
      const yanit1Tane = await yanit.json();
      const currentPreview = document.getElementById('currentPreview');
      document.getElementById('modalTitle').textContent = "Ürün Düzenle";
      document.getElementById('productIndex').value = index;
      document.getElementById('productName').value = yanit1Tane.ad;
      currentPreview.src = `../${yanit1Tane.gorsel}`;
      document.getElementById('productDesc').value = yanit1Tane.aciklama;
      document.getElementById('productPrice').value = yanit1Tane.fiyat;
      document.getElementById('productCategory').value = yanit1Tane.kategori;
      productModal.show();
    }

    async function deleteProduct(index) {
      if (confirm("Silinsin mi?")) {
        await fetch(`../backend/urun.php?islem=sil&urun_id=${index}`);
        renderProducts();
      }
    }

    document.getElementById('addProductBtn').addEventListener('click', () => {
      productForm.reset();
      document.getElementById('update').style.display = "none";
      document.getElementById('confirm').style.display = "flex";
      document.getElementById('productIndex').value = "";
      document.getElementById('modalTitle').textContent = "Ürün Ekle";
      productModal.show();
    });

    // document.getElementById('edit').addEventListener("click", () => {

    // });

    productForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const clickedBtn = e.submitter.value;
      const fileInput = document.getElementById('productImage');

      const form_data = new FormData();

      switch (clickedBtn) {
        case 'ekle':

          form_data.append('islem', 'ekle');
          form_data.append('ad', document.getElementById('productName').value);
          form_data.append('aciklama', document.getElementById('productDesc').value);
          form_data.append('fiyat', document.getElementById('productPrice').value);
          form_data.append('kategori', document.getElementById('productCategory').value);



          if (fileInput.files.length > 0) {
            form_data.append('gorsel', fileInput.files[0]);
          }

          break;

        case 'guncelle':
          form_data.append('islem', 'guncelle');

          const product_id = document.getElementById("productIndex").value;
          form_data.append('urun_id', product_id);
          form_data.append('ad', document.getElementById('productName').value);
          form_data.append('aciklama', document.getElementById('productDesc').value);
          form_data.append('fiyat', document.getElementById('productPrice').value);
          form_data.append('kategori', document.getElementById('productCategory').value);

          if (fileInput.files.length > 0) {
            form_data.append('gorsel', fileInput.files[0]);
          }
          break;
      }

      await fetch("../backend/urun.php", {
        method: "POST",
        body: form_data
      });

      productModal.hide();
      renderProducts();
    });
    renderProducts();
  </script>

</body>

</html>