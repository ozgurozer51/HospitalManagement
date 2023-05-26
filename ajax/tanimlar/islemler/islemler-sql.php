<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islem=$_GET["islem"];
$KULLANICI_ID = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');



if($islem=="islem-grubu-ekle") {
    $_POST["definition_type"]="ISLEM_GRUBU";
    $sql = direktekle("transaction_definitions", $_POST, "islemkaydet","id");
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('İşlem Grubu Ekleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('İşlem Grubu Ekleme Başarısız');
        </script>

    <?php }
}


else if($islem=="islem-grubu-guncelle") {
    $sql = direktguncelle("transaction_definitions","id", $_POST["id"],$_POST,"id");
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Güncelleme İşlemi Başarısız');
        </script>
    <?php }

}else if($_POST["islemdetayguncelle"]) {
    $islemguncelle = direktguncelle("transaction_detail","id", $_POST["id"],$_POST, "islemdetayguncelle", "id");
    if($islemguncelle){
        uyari("güncelleme i̇şlemi başarılı","1");
    } else{
        uyari("güncelleme i̇şlemi başarısız","0");
    }

}else if($_POST["islemdetaykaydet"]) {
    $islemtanimla = direktekle("transaction_detail", $_POST, "islemdetaykaydet","id");
    if($islemtanimla){
        uyari("kayıt i̇şlemi başarılı","1");
    } else{
        uyari("kayıt i̇şlemi başarısız","0");
    }

}else if($islem == "islem-grup-sil") {
    $islemid=$_GET["islemid"];
    $id = $_POST['id'];
    $detay = $_POST['delete_detail'];
    $tarih = $simdikitarih;
    $silme = $KULLANICI_ID;

    $sql=canceldetail("transaction_definitions","id",$id,$detay,$silme,$tarih);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Silme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Silme İşlemi Başarısız');
        </script>
    <?php }
}

if($islem=="islem-grubu-json-getir"){
  $sql =  verilericoklucek("select * from transaction_definitions where id='$_GET[id]'");
   echo json_encode($sql);
}

?>