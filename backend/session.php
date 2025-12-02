<?php
session_start();

function GirisYapSession($kullanici)
{
    $_SESSION['giris_yapildi'] = true;
    $_SESSION['kullanici'] = $kullanici;

    header('location: ../index.php');
}

function GirisYapAdminSession($admin)
{
    $_SESSION['yetkili_giris_yapildi'] = true;
    $_SESSION['yetkili'] = $admin;

    header('location: ../admin/dashboard.php');
}

function CikisYap()
{
    unset($_SESSION['giris_yapildi']);
    unset($_SESSION['kullanici']);

    header("location: ../index.php");
}

function CikisYapYetkili()
{
    unset($_SESSION['yetkili_giris_yapildi']);
    unset($_SESSION['yetkili']);

    header("location: ../admin/index.php");
}
