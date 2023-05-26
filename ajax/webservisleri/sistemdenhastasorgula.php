<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
if($_GET["tc_id"]) {
    $gelendeger = sistemdentcsorgula($_GET["tc_id"]);
    var_dump($gelendeger);
    $ihsan=arasinial($gelendeger,"<TCKimlikNo>","<TCKimlikNo>",1);
    if($ihsan!=""){
        echo  $gelendeger;
    } else{
        echo "0";
    }
}
?>
