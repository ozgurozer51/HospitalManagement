<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();


if($_GET["islem"]=="cagrilanhastayigetir"){

    $veriyisorgula=tek("select * from patient_registration where doctor='{$_SESSION["id"]}' and outpatient_id='{$_SESSION["birim_id"]}' and call_from_screen='1'");

if(isset($veriyisorgula["tc_id"])){

   $hastabilgileri= singular("patients","tc_id",$veriyisorgula["tc_id"]);
    echo $veriyisorgula["row_number"];
    echo ",";
    echo $hastabilgileri["patient_name"]." ".$hastabilgileri["patient_surname"];

    guncelle("update patient_registration set call_from_screen='2' where tc_id='{$veriyisorgula["tc_id"]}' and  call_from_screen='1' ");
}

}else if($_GET["islem"]=="hastayicagir") {

    //doktorun muayene saati başlamış ancak bitişi yapılmamış kaydı var mı diye sorguluyoruz.

    $muayenebitissaatisorgula=tek("select * from patient_registration where doctor='{$_SESSION["id"]}' and examination_start_time like '$gunceldate%' and examination_finish_time is null");

    if ($muayenebitissaatisorgula["id"] != "") {
        $guncelle = guncelle("update patient_registration set examination_finish_time='$gunceltarih' where id='{$muayenebitissaatisorgula["id"]}'  ");
    }

    $_POST["tc_id"] = $_GET["tcno"];
    $_POST["call_date"] = $gunceldate;
    $_POST["call_time"] = $guncelsaat;
    $_POST["call_userid"] = $_SESSION["id"];
    $_POST["call_unit"] = $_GET["poliklinikid"];
    $_POST["call_protocol_number"] = $_GET["protokolno"];
    $sayiyaekle = direktekle("patient_registration_call_history", $_POST);

    $islem = guncelle("update patient_registration set call_from_screen='1' where protocol_number='{$_GET["protokolno"]}' and outpatient_id='{$_GET["poliklinikid"]}'  ");

    $sonuc = kayitsayisigetir("patient_registration_call_history", "tc_id", $_GET["tcno"], "and call_date='$gunceldate' and call_unit='{$_GET["poliklinikid"]}' and call_protocol_number='{$_GET["protokolno"]}'");
//    var_dump($sonuc);
    

    echo preg_replace('~[\r\n]~', '', $sonuc);

}  ?>