<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';
require_once 'kullanici.php';

$kullanici_id = $kullanici['id'];

switch ($_GET['islem']) {
    case 'getir':
        $siparisler_getir = $pdo->prepare('SELECT s.siparis_id, k.ad_soyad, s.tutar, s.durum FROM siparisler s INNER JOIN kullanicilar k ON s.kullanici_id = k.kullanici_id ORDER BY durum');
        $siparisler_getir->execute();

        echo json_encode($siparisler_getir->fetchAll(PDO::FETCH_ASSOC));
        break;
    case 'durum_degistir':
        $siparis_id = $_GET['siparis_id'];
        $yeni_durum = $_GET['yeni_durum'];

        $durum_degistir = $pdo->prepare('UPDATE siparisler SET durum = :yeni_durum WHERE siparis_id = :siparis_id');
        $durum_degistir->execute([':yeni_durum' => $yeni_durum, 'siparis_id' => $siparis_id]);
        break;
}
