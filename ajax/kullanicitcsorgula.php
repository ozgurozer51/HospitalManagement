<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$tc_kimlik=$_GET["tc_kimlik"];
$kayitvarmi=tek("select * from users where tc_id='$tc_kimlik'");
//var_dump($kayitvarmi);
echo $kayitvarmi["id"];
?>