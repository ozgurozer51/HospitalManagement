<?php
include "../../controller/fonksiyonlar.php";

$islem = $_GET['islem'];


if($islem=="muayene-bekleyen-hasta-iptali"){
    $sql=canceldetail("patient_registration","id",$_POST['hasta_kayit_id'],$_POST['silme_detay']);
}

if ($sql) { ?>
    <script>
        alertify.success('Success notification message.');
    </script>
<?php }
