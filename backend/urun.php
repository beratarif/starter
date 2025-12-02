<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch ($_POST['islem']) {
            case 'ekle':
                $stock = $_POST['stok'];
                $ad = $_POST['ad'];
                $aciklama = $_POST['aciklama'];
                $fiyat = $_POST['fiyat'];
                $kategori = $_POST['kategori'];

                $kayitYolu = "";

                $uploads_dir = "../img/";
                $tmp_name = $_FILES["gorsel"]["tmp_name"];
                $uzanti = pathinfo($_FILES["gorsel"]["name"], PATHINFO_EXTENSION);

                $yeniIsim = $ad . '.' . $uzanti;

                if (move_uploaded_file($tmp_name, $uploads_dir . $yeniIsim)) {
                    $kayitYolu = "img/" . $yeniIsim;

                    $urun_ekle = $pdo->prepare('INSERT INTO urunler (stok , ad, aciklama, kategori, fiyat, gorsel, aktiflik) VALUES (:stok,:ad, :aciklama, :kategori, :fiyat, :gorsel, :aktiflik)');
                    $urun_ekle->execute([
                        ':stok' => $stock,
                        ':ad' => $ad,
                        ':aciklama' => $aciklama,
                        ':kategori' => $kategori,
                        ':fiyat' => $fiyat,
                        ':gorsel' => $kayitYolu,
                        ':aktiflik' => 1
                    ]);
                } else {
                    die("Dosya yüklenemedi");
                }

                break;
            case 'guncelle':
                $urun_id = $_POST['urun_id'];
                $urun_stock = $_POST['stok'];
                $urun_ad = $_POST['ad'];
                $urun_aciklama = $_POST['aciklama'];
                $urun_fiyat = $_POST['fiyat'];
                $urun_kategori = $_POST['kategori'];

                $gorsel_sql = '';
                $params = [
                    'urun_id' => $urun_id,
                    'urun_stok' => $urun_stock,
                    'urun_ad' => $urun_ad,
                    'urun_aciklama' => $urun_aciklama,
                    'urun_fiyat' => $urun_fiyat,
                    'urun_kategori' => $urun_kategori
                ];

                // Resim varsa ekle
                if (isset($_FILES['gorsel']) && $_FILES['gorsel']['tmp_name'] != '') {
                    $uploads_dir = "../img/";
                    $tmp_name = $_FILES["gorsel"]["tmp_name"];
                    $uzanti = pathinfo($_FILES["gorsel"]["name"], PATHINFO_EXTENSION);
                    $yeniIsim = $urun_ad . '.' . $uzanti;

                    if (move_uploaded_file($tmp_name, $uploads_dir . $yeniIsim)) {
                        $gorsel_sql = ', gorsel = :gorsel';
                        $params['gorsel'] = 'img/' . $yeniIsim;
                    }
                }

                $urun_guncelle = $pdo->prepare('UPDATE urunler SET stok = :urun_stok, ad = :urun_ad, aciklama = :urun_aciklama, fiyat = :urun_fiyat, kategori = :urun_kategori' . $gorsel_sql . ' WHERE urun_id = :urun_id');
                $urun_guncelle->execute($params);

                break;
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        switch ($_GET['islem']) {
            case 'anasayfa':
                $urun_sayisi = 9;

                $urun_getir_biraz = $pdo->prepare('SELECT * FROM urunler WHERE aktiflik = 1 LIMIT :urun_sayisi');
                $urun_getir_biraz->bindValue(':urun_sayisi', $urun_sayisi, PDO::PARAM_INT);

                $urun_getir_biraz->execute();

                echo json_encode($urun_getir_biraz->fetchAll(PDO::FETCH_ASSOC));
                break;
            case 'urunler':
                $sayfa_basina_urun_sayisi = 9;

                $sayfa = $_GET['sayfa'];
                $kategori = $_GET['kategori'];

                $offset = $sayfa_basina_urun_sayisi * ($sayfa - 1);

                if ($kategori == 'yok') {
                    $sayfa_getir = $pdo->prepare('SELECT * FROM urunler WHERE aktiflik = 1 ORDER BY stok desc LIMIT :sayfa_basina_urun_sayisi OFFSET :offset');
                } else {
                    $sayfa_getir = $pdo->prepare('SELECT * FROM urunler WHERE aktiflik = 1 AND kategori = :kategori ORDER BY stok desc LIMIT :sayfa_basina_urun_sayisi OFFSET :offset');
                    $sayfa_getir->bindValue(':kategori', $kategori, PDO::PARAM_STR);
                }
                $sayfa_getir->bindValue(':sayfa_basina_urun_sayisi', $sayfa_basina_urun_sayisi, PDO::PARAM_INT);
                $sayfa_getir->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sayfa_getir->execute();
                echo json_encode($sayfa_getir->fetchAll(PDO::FETCH_ASSOC));
                break;
            case 'urun_detay':
                $urun_id = $_GET['id'];

                $detay_getir = $pdo->prepare('SELECT * FROM urunler WHERE aktiflik = 1 AND urun_id = :urun_id ORDER BY stok desc LIMIT 1');
                $detay_getir->execute([':urun_id' => $urun_id]);

                echo json_encode($detay_getir->fetch(PDO::FETCH_ASSOC));
                break;
            case 'admin_icin_urunler':
                $tum_urunler_getir = $pdo->prepare('SELECT * FROM urunler');
                $tum_urunler_getir->execute();

                echo json_encode($tum_urunler_getir->fetchAll(PDO::FETCH_ASSOC));
                break;
            case 'sil':
                $urun_id = $_GET['urun_id'];
                $urun_sil = $pdo->prepare('DELETE FROM urunler WHERE urun_id = :urun_id');
                $urun_sil->execute([
                    'urun_id' => $urun_id
                ]);
                break;
            case 'getir1':
                $urun_id = $_GET['urun_id'];

                $urun_getir1 = $pdo->prepare('SELECT * FROM urunler WHERE urun_id = :urun_id LIMIT 1');
                $urun_getir1->execute([
                    'urun_id' => $urun_id
                ]);
                echo json_encode($urun_getir1->fetch(PDO::FETCH_ASSOC));
                break;
        }
    }
} catch (PDOException $ex) {
    die('ürün hatası: ' . $ex->getMessage());
}
