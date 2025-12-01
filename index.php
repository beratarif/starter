<?php require_once 'backend/kullanici.php'; ?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mağazam</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }

    .hero {
      background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
        url("img/banner.jpg") center/cover;
      color: #fff;
      text-align: center;
      padding: 6rem 1rem;
      border-radius: 1rem;
    }

    .category-btn {
      border-radius: 25px;
      transition: 0.2s;
    }

    .category-btn:hover {
      background-color: #0d6efd;
      color: #fff;
      transform: scale(1.05);
    }

    footer {
      background: #212529;
      color: #ccc;
      padding: 2rem 0;
      text-align: center;
      margin-top: 4rem;
    }

    .navbar .nav-link.dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 4px;
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
    }

    .card-img-top {
      height: 200px;
      object-fit: contain;
      /* resim orantılı küçülür, boşluk kalabilir */
      background-color: #f8f9fa;
      /* arka plan boş kalırsa hoş durur */
    }

    .product-info {
      display: flex;
      align-items: center;
      /* dikey olarak ortalamak için */
    }

    .price {
      font-weight: bold;
    }

    .stock {
      color: gray;
      padding-left: 15rem;
    }

    .stock-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #198754;
      color: #fff;
      padding: 4px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .out-of-stock {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">Mağazam</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Anasayfa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Product/product.php?sayfa=1&kategori=yok">Ürünler</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Sepet/basket.php">Sepet</a>
          </li>

          <?php if ($giris_yapildi): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-uppercase fw-semibold" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($kullanici['ad_soyad']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-primary" href="Profile/index.php">Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="backend/logout.php?hangi_cikis=normal">Çıkış
                    Yap</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="LoginRegister/index.php" class="nav-link">Giriş</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Hero -->
  <section class="hero my-4">
    <div class="container">
      <h1 class="display-4 fw-bold">Yeni Sezon Ürünleri</h1>
      <p class="lead mb-4">
        Modern, uygun fiyatlı ve kaliteli ürünlerle tanış.
      </p>
      <a href="#products" class="btn btn-primary btn-lg px-4">Alışverişe Başla</a>
    </div>
  </section>

  <!-- Kategoriler -->
  <section class="container text-center my-5">
    <h2 class="mb-4 fw-bold">Kategoriler</h2>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="Product/product.php?sayfa=1&kategori=yok" class="btn btn-outline-primary category-btn">Tümü</a>
      <a href="Product/product.php?sayfa=1&kategori=elektronik"
        class="btn btn-outline-primary category-btn">Elektronik</a>
      <a href="Product/product.php?sayfa=1&kategori=giyim" class="btn btn-outline-primary category-btn">Giyim</a>
      <a href="Product/product.php?sayfa=1&kategori=aksesuar" class="btn btn-outline-primary category-btn">Aksesuar</a>
      <a href="Product/product.php?sayfa=1&kategori=ev_yasam" class="btn btn-outline-primary category-btn">Ev &
        Yaşam</a>
    </div>
  </section>

  <!-- Yeni Ürünler -->
  <section id="products" class="container my-5">
    <div class="container my-5">
      <h2 class="mb-4">Yeni Ürünler</h2>
      <div id="product-holder" class="row g-4">
        <!-- products are here -->
      </div>
    </div>

  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 Mağazam. Tüm hakları saklıdır.</p>
  </footer>

  <script>
    async function urunGetir() {
      const product_holder = document.getElementById("product-holder");

      try {
        const response = await fetch(`./backend/urun.php?islem=anasayfa`);

        for (const u of await response.json()) {
          if (u.stok > 0) {
            product_holder.innerHTML += `
        <div class="col-md-4 col-sm-6">
          <div class="card h-100 shadow-sm product-card" data-id="${u.urun_id}" style="cursor:pointer;">
            <img src="${u.gorsel}" class="card-img-top" alt="Ürün Görseli"> 
            <div class="card-body">
              <h5 class="card-title">${u.ad}</h5>
              <p class="card-text text-muted">${u.aciklama}</p>
                            <div class="product-info">
              <p class="price fw-bold fs-5 mb-1">₺${u.fiyat}</p>
              </div>
        <p class="mb-1 small">Stok: ${u.stok}</p>

        <button class="btn btn-primary w-100 sepete-ekle">Sepete Ekle</button>
            </div>
          </div>
        </div>
            `;
          } else {
            product_holder.innerHTML += `
        <div class="col-md-4 col-sm-6 out-of-stock">
          <div class="card h-100 shadow-sm product-card" data-id="${u.urun_id}" style="cursor:pointer;">
            <img src="${u.gorsel}" class="card-img-top" alt="Ürün Görseli"> 
            <div class="card-body">
              <h5 class="card-title">${u.ad}</h5>
              <p class="card-text text-muted">${u.aciklama}</p>
                            <div class="product-info">
              <p class="price fw-bold fs-5 mb-1">₺${u.fiyat}</p>
              </div>
        <p class="mb-1 small">Tükendi</p>

        <button class="btn btn-primary w-100 sepete-ekle disabled">Sepete Ekle</button>
            </div>
          </div>
        </div>
            `;
          }
        }

        for (const urunKart of document.querySelectorAll(".product-card")) {
          urunKart.querySelector(".sepete-ekle").onclick = () => {
            sepeteEkle(urunKart.dataset.id);
          };
        }
      } catch (err) {
        console.error(`hata: ${err}`);
      }
    }

    function sepeteEkle(id) {
      if (<?php echo $giris_yapildi ? 'true' : 'false' ?>) {
        window.location.href = `backend/sepet.php?islem=ekle&id=${id}`;
      } else {
        window.location.href = `./LoginRegister/index.php`;
      }
    }

    document.addEventListener("DOMContentLoaded", async () => {
      await urunGetir();
    });

    document.getElementById("product-holder").addEventListener("click", (e) => {
      if (e.target.closest(".btn"))
        return;

      const card = e.target.closest(".product-card");

      if (card) {
        window.location.href = `ProductDetail/index.php?id=${card.dataset.id}`;
      }
    });
  </script>

</body>

</html>