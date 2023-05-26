<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
yetkisorgula($_SESSION["username"],"islemsil");
$degerler=$_GET["getir"];

$verivarmisorgula=tek("select * from transaction_detail where transaction_id='$degerler' and durum!='2'");

if($verivarmisorgula){  ?>
<div class="alert alert-danger" role="alert">
    silme işlemi gerçekleştirilmemiştir. silmek istediğiniz işlemin içinde yer alan işlem tanımları mevcut. lütfen bilgi işlem departmanıyla iletişime geçiniz
</div>
<?php }else {
    $id = $_POST['id'];
    $detay = $_POST['detay'];
    $silme = $_POST['silme'];
    $tarih = $_POST['tarih'];
    $sql=canceldetail("transaction_definitions","id",$id,$detay,$silme,$tarih);
   ?>
    <div class="alert alert-success" role="alert">silme i̇şlemi tamamlandı</div>
<?php } ?>