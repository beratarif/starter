<?php
require_once 'db.php';
require_once 'session.php';
try {
    $eposta = $_POST['eposta'];
    $sifre = $_POST['sifre'];

    // yetkili getirme işlemi (şifre ve yetki kontrolü için)
    $kullanici_getir = $pdo->prepare('SELECT * FROM kullanicilar WHERE eposta = :eposta');
    $kullanici_getir->execute([':eposta' => $eposta]);
    $kullanici = $kullanici_getir->fetch(PDO::FETCH_ASSOC);

    // yetkili kontrolü
    if (!$kullanici)
        die('yetkili bulunamadı');

    if (!password_verify($sifre, $kullanici['sifre']))
        die("şifre yanlış");

    if ($kullanici['yetki'] == 'normal') {
        die('yetkili olan bir hesap ile deneyin');
    }

    GirisYapAdminSession(['ad_soyad' => $kullanici['ad_soyad']]);
}
catch (PDOException $ex) {
    die('admin girişyap hatası: ' . $ex->getMessage());
}
?>