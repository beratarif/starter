<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

switch ($_GET['islem']) {
    case 'dashboard':
        $kullanicilari_getir = $pdo->query('SELECT COUNT(*) FROM kullanicilar');
        $kullanicilar = $kullanicilari_getir->fetchColumn();

        $stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM siparisler 
    WHERE durum NOT IN (?, ?)
");
        $stmt->execute(['Tamamlandı', 'İptal Edildi']);
        $siparisler = $stmt->fetchColumn();


        $gelir_getir = $pdo->prepare("SELECT tutar FROM siparisler WHERE durum = 'Tamamlandı'");
        $gelir_getir->execute();
        $tutarlar = $gelir_getir->fetchAll(PDO::FETCH_ASSOC);

        $gelir = 0;
        foreach ($tutarlar as $tutar) {
            $gelir += $tutar['tutar'];
        }

        echo json_encode(['kullanicilar' => $kullanicilar, 'siparisler' => $siparisler, 'gelir' => $gelir]);
        break;
}
