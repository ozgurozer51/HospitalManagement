<?php
include "../../controller/fonksiyonlar.php";
$islem= $_GET['islem'];

session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$kullanicininidsi=$_SESSION["id"];
$poliklinikbilgisigetir = singular("patient_registration", "id", $_GET["deger"]);
$hastabilgisigetir = singular("patients", "id", $poliklinikbilgisigetir["patient_id"]);

$protokol_no =  $poliklinikbilgisigetir["protocol_number"];

if($islem=="epikriz-ekle"){
    unset($_GET['islem']);
    $sql = direktekle("epicrisis",$_GET);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Epikriz Ekleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Epikriz Ekleme Başarısız');
        </script>
    <?php }

    exit();

}if($islem=="epikriz-guncelle"){
    unset($_GET['islem']);
    $id = $_GET['id'];
    unset($_GET['id']);
    $sql = direktguncelle("epicrisis","id",$id,$_GET);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Epikriz Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Epikriz Güncelleme Başarısız');
        </script>
    <?php }

    exit();
}  ?>