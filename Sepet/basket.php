<?php require_once '../backend/kullanici.php'; ?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sepetim</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .card-img-top {
      height: 200px;
      object-fit: contain;
      /* resim orantılı küçülür, boşluk kalabilir */
      background-color: #f8f9fa;
      /* arka plan boş kalırsa hoş durur */
    }

    footer {
      background: #212529;
      color: #ccc;
      padding: 2rem 0;
      text-align: center;
      margin-top: 4rem;
    }

    .sepet {
      cursor: pointer;
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
</head>

<body>
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
            <a class="nav-link" href="basket.php">Sepet</a>
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
  <!-- SEPET -->

  <div class="container my-5">
    <h2 class="mb-4">🛒 Sepetim</h2>

    <div class="row g-4">
      <div class="col-lg-8" id="basket-holder">





      </div>

      <div class="col-lg-4">
        <div class="card shadow-sm">


          <div class="card-body toplam_tutar">
            <h5 class="card-title">Sipariş Özeti</h5>
            <p class="card-text d-flex justify-content-between ara_toplam">
              <span>Ara Toplam:</span> <span id="subtotal">₺0.00</span>
            </p>
            <p class="card-text d-flex justify-content-between kargo_ucreti">
              <span>Kargo:</span> <span id="shipping">₺0.00</span>
            </p>
            <hr />
            <p class="card-text d-flex justify-content-between fw-bold">
              <span>Toplam:</span> <span id="total">₺0.00</span>
            </p>
            <button onclick="window.location.href='payment/payment.php';"
              class="btn btn-success w-100 mt-3 siparis-onayla">Siparişi Onayla</button>
          </div>
        </div>
      </div>

    </div>
  </div>
  <section id="products" class="container my-5">
    <div class="container my-5">
      <h2 class="mb-4">Diğer Ürünler</h2>
      <div id="product-holder" class="row g-4">
        <!-- products are here -->
      </div>
    </div>

  </section>

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

    async function sepetGetir() {
      const basket_holder = document.getElementById('basket-holder');

      try {
        const sonuc = await fetch(`../backend/sepet.php?islem=getir`);

        let ara_toplam = 0;
        const kargo_ucreti = 50;

        if (<?php echo $giris_yapildi ? 'true' : 'false' ?>) {
          const sonucJson = await sonuc.json();
          if (sonucJson.length > 0) {
            for (const s of sonucJson) {
              basket_holder.innerHTML +=
                `
          <div class="card mb-3 sepet" data-id="${s.urun.id}">
            <div class="row g-0 align-items-center">
              <div class="col-md-3">
                <img
                  src="../${s.urun.gorsel}"
                  class="img-fluid rounded-start" alt="Ürün 2" />
              </div>
              <div class="col-md-6">
                <div class="card-body">
                  <h5 class="card-title mb-1">${s.urun.ad}</h5>
                  <p class="text-muted small mb-2">
                    ${s.urun.aciklama}
                  </p>
                  <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary btn-sm sepet_cikar">-</button>
                    <span class="mx-2">${s.adet}</span>
                    <button class="btn btn-outline-secondary btn-sm sepete_ekle">+</button>
                  </div>
                </div>
              </div>
              <div class="col-md-3 text-center">
                <p class="fw-semibold mb-1">₺${s.urun.fiyat}</p>
                <button class="btn btn-outline-danger btn-sm sepet_tum_urunler_cikar">Kaldır</button>
              </div>
            </div>
          </div>
        </div>
            `;

              ara_toplam += s.urun.fiyat * s.adet;
            }
          } else {
            document.querySelector(".siparis-onayla").classList.add("disabled");
          }
        } else {
          document.querySelector(".siparis-onayla").classList.add("disabled");
        }

        document.getElementById("subtotal").innerHTML = `₺${ara_toplam.toFixed(2)}`;

        if (ara_toplam > 0) {
          document.getElementById("shipping").innerHTML = `₺${kargo_ucreti.toFixed(2)}`;
          document.getElementById("total").innerHTML = `₺${(ara_toplam + kargo_ucreti).toFixed(2)}`;
        } else {
          document.getElementById("shipping").innerHTML = `₺${ara_toplam.toFixed(2)}`;
          document.getElementById("total").innerHTML = `₺${ara_toplam.toFixed(2)}`;
        }

        document.querySelectorAll(".sepet").forEach(card => {
          card.addEventListener("click", function(e) {
            if (e.target.tagName === "BUTTON") return;
            window.location.href = "../ProductDetail/index.php?id=" + this.dataset.id;
          });
        });

        for (const sepet of document.querySelectorAll(".sepet")) {
          sepet.querySelector(".sepete_ekle").onclick = () => {
            sepeteEkle(sepet.dataset.id);
          };
          sepet.querySelector(".sepet_cikar").onclick = () => {
            sepetCikar(sepet.dataset.id, false);
          };
          sepet.querySelector(".sepet_tum_urunler_cikar").onclick = () => {
            sepetCikar(sepet.dataset.id, true);
          };
        }
      } catch (err) {
        console.error(err);
      }
    }

    function sepeteEkle(id) {
      if (<?php echo $giris_yapildi ? 'true' : 'false' ?>) {
        window.location.href = `../backend/sepet.php?islem=ekle&id=${id}`;
      } else {
        window.location.href = `../LoginRegister/index.php`;
      }
    }

    function sepetCikar(id, tumUrunler) {
      if (<?php echo $giris_yapildi ? 'true' : 'false' ?>) {
        window.location.href = `../backend/sepet.php?islem=cikar&id=${id}&tum_urunler=${tumUrunler}`;
      } else {
        window.location.href = `../LoginRegister/index.php`;
      }
    }

    document.addEventListener("DOMContentLoaded", async () => {
      await sepetGetir();
      await urunGetir();
    });
    document.getElementById("product-holder").addEventListener("click", (e) => {
      if (e.target.closest(".btn")) return;
      const card = e.target.closest(".product-card");

      if (card) {
        window.location.href = `../ProductDetail/index.php?id=${card.dataset.id}`;
      }
    });
  </script>
</body>

</html>