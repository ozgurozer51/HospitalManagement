<?php
include "../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();

//var_dump($_POST);
if($_GET["islem"]=="kayitguncelle"){

    $myimg = $_POST['photo'];
    unset($_POST["photo"]);
    $hastavarmi = singular("patients", "tc_id", $_POST['tc_id']);
//    var_dump($hastavarmi);
    if($hastavarmi["tc_id"]!=""){

        $sql = direktguncelle("patients","tc_id",$_POST['tc_id'],$_POST);
        if($sql){
            unset($_POST);
            if($myimg) {  
                $_POST["photo"]=$myimg;
                $_POST["patients_id"]=$hastavarmi["id"];

                $yeniresimkle = direktekle("patients_photo",$_POST);
//                var_dump($yeniresimkle);
            }
            echo "1";
        }
    }else{
        echo "Kayıtlı hasta bulunamadığı için sistem bu talebinizi yerine getiremedi";
    }
    }
    
    
if($_GET["islem"]=="yenikayit"){
if($_POST["tc_id"]){
if($_POST["patient_name"]){
    $myimg = $_POST['photo'];
    unset($_POST["photo"]);
$hastavarmi = singular("patients", "tc_id", $_POST['tc_id']);
//var_dump($hastavarmi);
if($hastavarmi["tc_id"]==""){

$sql = direktekle("patients",$_POST);
$sonkayit=islemtanimsoneklenen("patients");
if($sql){

    if($myimg) {
        unset($_POST); 
$_POST["photo"]=$myimg;
$_POST["patients_id"]=$sonkayit;

        $yeniresimkle = direktekle("patients_photo",$_POST);
//        var_dump($yeniresimkle);
    }
    echo "1";
}
}else{
    echo "Bu tc kimlik numarası daha önce kayıt oluşturulmuştur";
}
}else{
    echo "Adı Kısmını Boş Bırakmayınız!";
}
}else{
    echo "TC Numarasını Yazınız!";
}
}