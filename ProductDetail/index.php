<?php require_once '../backend/kullanici.php'; ?>


<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ürün Detay</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  <style>
    footer {
      background: #212529;
      color: #ccc;
      padding: 2rem 0;
      text-align: center;
      margin-top: 4rem;
    }

    .product-img-big {
      width: 55%;
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, .15);
    }

    .product-title {
      font-size: 2.2rem;
      font-weight: 700;
    }

    .product-price {
      font-size: 1.9rem;
      font-weight: 800;
      color: #28a745;
    }

    .other-card img {
      height: 170px;
      object-fit: cover;
      border-radius: 10px;
    }

    .other-card {
      transition: .25s ease;
      border-radius: 12px !important;
      overflow: hidden;
    }

    .other-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
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

    .out-of-stock {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
  </style>
</head>

<body class="bg-light">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="../index.php">Mağazam</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Anasayfa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Product/product.php?sayfa=1&kategori=yok">Ürünler</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Sepet/basket.php">Sepet</a>
          </li>

          <?php if ($giris_yapildi): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-uppercase fw-semibold" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($kullanici['ad_soyad']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-primary" href="../Profile/index.php">Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="../backend/logout.php?hangi_cikis=normal">Çıkış
                    Yap</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="../LoginRegister/index.php" class="nav-link">Giriş</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ÜRÜN DETAY -->
  <div class="container my-5">


    <div class="row g-5" id="urun-detay" data-id="<?php echo $_GET['id'] ?>">
      <div class="col-md-6 d-flex justify-content-center align-items-center">
        <img src="../img/pillow.jpeg" class="product-img-big">
      </div>

      <div class="col-md-6">
        <h1 class="product-title">Ürün İsmi Buraya</h1>
        <p class="text-muted mb-3 product-description">Bu ürünün açıklaması lorem ipsum tarzı bir şeyler buraya gelecek.
        </p>

        <div class="product-price mb-4 product-price">999₺</div>

        <p class="mb-2 fw-bold price">Ürün Stoğu: 12 Adet</p>
        <button class="btn btn-success btn-lg px-4 sepete-ekle">
          <i class="fa fa-solid fa-cart-shopping me-2"></i> Sepete Ekle
        </button>
      </div>

    </div>
  </div>


  <!-- BENZER ÜRÜNLER -->
  <section id="products" class="container my-5">
    <div class="container my-5">
      <h2 class="mb-4">Diğer Ürünler</h2>
      <div id="product-holder" class="row g-4">
        <!-- products are here -->
      </div>
    </div>

  </section>


  <!-- FOOTER -->
  <footer>
    <p>© 2025 Mağazam. Tüm hakları saklıdır.</p>
  </footer>

  <script>
    async function urunGetir() {
      const product_holder = document.getElementById("product-holder");

      try {
        const response = await fetch(`../backend/urun.php?islem=urunler&sayfa=1&kategori=yok`);

        for (const u of await response.json()) {
          if (u.stok > 0) {
            product_holder.innerHTML += `
        <div class="col-md-4 col-sm-6">
          <div class="card h-100 shadow-sm product-card" data-id="${u.urun_id}" style="cursor:pointer;">
            <img src="../${u.gorsel}" class="card-img-top" alt="Ürün Görseli"> 
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
            <img src="../${u.gorsel}" class="card-img-top" alt="Ürün Görseli"> 
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
        window.location.href = `../backend/sepet.php?islem=ekle&id=${id}`;
      } else {
        window.location.href = `../LoginRegister/index.php`;
      }
    }

    async function urunDetayGetir() {
      const urun_detay = document.getElementById("urun-detay");

      const urun_gorsel = urun_detay.querySelector(".product-img-big");
      const urun_baslik = urun_detay.querySelector(".product-title");
      const urun_aciklama = urun_detay.querySelector(".product-description");
      const urun_fiyat = urun_detay.querySelector(".product-price");

      const urun_id = urun_detay.dataset.id;

      try {
        const sonuc = await fetch(`../backend/urun.php?islem=urun_detay&id=${urun_id}`);
        const sonucJson = await sonuc.json();

        urun_gorsel.src = `../${sonucJson.gorsel}`;
        urun_baslik.innerHTML = sonucJson.ad;
        urun_aciklama.innerHTML = sonucJson.aciklama;
        urun_fiyat.innerHTML = `${sonucJson.fiyat}₺`;

        urun_detay.querySelector(".sepete-ekle").onclick = () => {
          sepeteEkle(urun_detay.dataset.id);
        };
      } catch (err) {
        console.error(`hata: ${err}`);
      }
    }

    document.addEventListener("DOMContentLoaded", async () => {
      await urunDetayGetir();
      await urunGetir();
    });

    document.getElementById("product-holder").addEventListener("click", (e) => {
      if (e.target.closest(".btn")) return;
      const card = e.target.closest(".product-card");

      if (card) {
        window.location.href = `index.php?id=${card.dataset.id}`;
      }
    });
  </script>
</body>

</html>