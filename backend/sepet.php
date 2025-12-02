<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';
require_once 'kullanici.php';

$kullanici_id = $kullanici['id'];

switch ($_GET['islem']) {
    case 'ekle':
        $urun_id = $_GET['id'];
        $eklenecek_adet = 1;

        // 1) Ürünün stok bilgisini çek
        $stok_sorgu = $pdo->prepare('SELECT stok FROM urunler WHERE urun_id = :urun_id LIMIT 1');
        $stok_sorgu->execute([':urun_id' => $urun_id]);
        $urun = $stok_sorgu->fetch(PDO::FETCH_ASSOC);

        if (!$urun) {
            die("Ürün bulunamadı!");
        }

        $stok = $urun['stok'];

        // 2) Sepette bu ürün var mı bak
        $sepet_getir = $pdo->prepare('SELECT * FROM sepetler WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id LIMIT 1');
        $sepet_getir->execute([
            ':urun_id' => $urun_id,
            ':kullanici_id' => $kullanici_id
        ]);

        $sepet = $sepet_getir->fetch(PDO::FETCH_ASSOC);
        $mevcut_adet = $sepet ? $sepet['adet'] : 0;

        // 3) Stok kontrolü
        // Stok dolduysa frontend'e mesaj gönder
        if ($mevcut_adet + $eklenecek_adet > $stok) {
            echo json_encode([
                "status" => "stock_limit",
                "message" => "{$urun_ad} için en fazla {$stok} adet ekleyebilirsin."
            ]);
            exit;
        }

        // Eğer sepette varsa güncelle
        if ($sepet) {
            $sepet_guncelle = $pdo->prepare('UPDATE sepetler SET adet = :yeni_adet WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id');
            $sepet_guncelle->execute([
                ':yeni_adet' => $mevcut_adet + $eklenecek_adet,
                ':kullanici_id' => $kullanici_id,
                ':urun_id' => $urun_id
            ]);
        } else {
            // Yoksa yeni ekle
            $sepet_ekle = $pdo->prepare('INSERT INTO sepetler (kullanici_id, urun_id, adet) VALUES (:kullanici_id, :urun_id, :adet)');
            $sepet_ekle->execute([
                ':kullanici_id' => $kullanici_id,
                ':urun_id' => $urun_id,
                ':adet' => $eklenecek_adet
            ]);
        }
        echo json_encode(["status" => "success", "message" => "Ürün sepete eklendi"]);
        header("location: ../Sepet/basket.php");
        break;

    case 'cikar':
        $urun_id = $_GET['id'];
        $tum_urunler = $_GET['tum_urunler'];

        if ($tum_urunler == 'true') {
            $tum_urunler_sil = $pdo->prepare('DELETE FROM sepetler WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id');
            $tum_urunler_sil->execute([
                ':urun_id' => $urun_id,
                ':kullanici_id' => $kullanici_id
            ]);
        } else {
            $adet_getir = $pdo->prepare('SELECT adet FROM sepetler WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id');
            $adet_getir->execute([
                ':urun_id' => $urun_id,
                ':kullanici_id' => $kullanici_id
            ]);

            $yeniAdet = $adet_getir->fetch()['adet'] - 1;

            if ($yeniAdet < 1) {
                $tum_urunler_sil = $pdo->prepare('DELETE FROM sepetler WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id');
                $tum_urunler_sil->execute([
                    ':urun_id' => $urun_id,
                    ':kullanici_id' => $kullanici_id
                ]);
            } else {
                $adet_guncelle = $pdo->prepare('UPDATE sepetler SET adet = :yeni_adet WHERE kullanici_id = :kullanici_id AND urun_id = :urun_id');
                $adet_guncelle->execute([
                    ':urun_id' => $urun_id,
                    ':kullanici_id' => $kullanici_id,
                    ':yeni_adet' => $yeniAdet
                ]);
            }
        }

        header("location: ../Sepet/basket.php");
        break;
    case 'getir':
        $sepet_getir = $pdo->prepare(
            '
            SELECT u.urun_id, u.ad, u.aciklama, u.fiyat, u.gorsel, u.stok , s.adet FROM sepetler s INNER JOIN urunler u ON s.urun_id = u.urun_id WHERE s.kullanici_id = :kullanici_id'
        );

        $sepet_getir->execute([':kullanici_id' => $kullanici_id]);
        $satirlar = $sepet_getir->fetchAll(PDO::FETCH_ASSOC);

        $sonuc = [];

        foreach ($satirlar as $satir) {
            $sonuc[] = [
                'adet' => $satir['adet'],
                'stok' => $satir['stok'],
                'urun' => [
                    'id' => $satir["urun_id"],
                    'ad' => $satir['ad'],
                    'aciklama' => $satir['aciklama'],
                    'fiyat' => $satir['fiyat'],
                    'gorsel' => $satir['gorsel']
                ]
            ];
        }

        echo json_encode($sonuc);
        break;
    case 'sepet_bosalt':
        $sepet_urunler_getir = $pdo->prepare('SELECT urun_id FROM siparisler WHERE kullanici_id = :kullanici_id');



        $sepet_hesapla = $pdo->prepare('SELECT u.fiyat, s.adet FROM sepetler s INNER JOIN urunler u ON s.urun_id = u.urun_id WHERE s.kullanici_id = :kullanici_id');
        $sepet_hesapla->execute([':kullanici_id' => $kullanici_id]);

        $satirlar = $sepet_hesapla->fetchAll(PDO::FETCH_ASSOC);

        $tutar = 50;
        foreach ($satirlar as $satir) {
            $tutar += $satir['fiyat'] * $satir['adet'];
        }

        $siparis_olustur = $pdo->prepare('INSERT INTO siparisler (kullanici_id, tutar) VALUES (:kullanici_id, :tutar)');
        $siparis_olustur->execute([
            ':kullanici_id' => $kullanici_id,
            'tutar' => $tutar
        ]);

        $sepet_bosalt = $pdo->prepare("DELETE FROM sepetler WHERE kullanici_id = :kullanici_id");
        $sepet_bosalt->execute([':kullanici_id' => $kullanici_id]);
        break;
}
