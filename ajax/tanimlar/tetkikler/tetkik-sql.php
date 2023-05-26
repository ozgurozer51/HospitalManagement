<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];

if($islem=="tetkik-grup-duzenle-islem"){

    $ID=$_POST['id'];

    $sql = direktguncelle("process_group","id",$ID,$_POST);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Güncelleme Başarısız');
        </script>

    <?php } }

if($islem=="tetkik-grup-sil"){
    $detay=$_POST["cancel_detail"];

    $id=$_POST["id"];
    $tgrupsil=canceldetail("process_group","id",$id,$detay);
    if($tgrupsil){
        ?>
        <script>
            alertify.set('notifier','delay',8);
            alertify.success('Silme İşlemi Başarılı')
        </script>
        <?php }else{ ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Silme İşlemi Başarısız')
        </script>
        <?php }
}

if($islem=="tetkik-grup-ekle-islem"){

    var_dump($_POST);

    $sql = direktekle("process_group",$_POST);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Ekleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Ekleme Başarısız');
        </script>
    <?php } }

if($islem=="tetkik-detay-ekle-islem"){

    $sql = direktekle("process",$_POST);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Ekleme Başarılı');
        </script>
    <?php }
    else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Ekleme Başarısız');
        </script>

    <?php } }

if($islem=="tetkik-detay-duzenle-islem"){

    $_POST['update_datetime'] = $simdikitarih;
    $_POST['update_userid'] = $KULLANICI_ID;
    $ID=$_POST['id'];

    unset($_POST['id']);
    unset($_POST["transaction_id"]);
    $td = direktguncelle("process","id",$ID,$_POST);

    if ($td) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Güncelleme Başarısız');
        </script>

    <?php } }

if($islem=="tetkik-detay-sil"){
    echo "islemde";
    $detay=$_POST["cancel_detail"];
    $tarih=$simdikitarih;
    $silen_kisi=$KULLANICI_ID;
    $id=$_POST["id"];
    $tdetaysil=canceldetail("process","id",$id,$detay,$silen_kisi,$tarih);
    var_dump($tdetaysil);
    if($tdetaysil){
        ?>
        <script>
            alertify.set('notifier','delay',8);
            alertify.success('Tetkik Silindi')
        </script>
        <?php
    }else{
        ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Silme İşlemi Başarısız')
        </script>
        <?php
    }
}

