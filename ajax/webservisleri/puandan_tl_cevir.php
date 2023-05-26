<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$kullanicininidsi=$_SESSION["id"];

function puandan_tl_cevir($tarih,$ekleyen_user_id){
$hello = verilericoklucek("select id,CAST((point_coefficient*0.593) AS INT) as  islem from processes order by id");

    foreach ($hello as $rowa) {
        //echo  $rowa['id']." ".$rowa['islem'];
        $_POST["insert_datetime"] = $tarih;
        $_POST["insert_userid"] =$ekleyen_user_id;
        $_POST["processes_id"] =$rowa['id'];
        $_POST["price"] =$rowa['islem'];

        $sonuc=direktekle("processes_price",$_POST);

    }
    var_dump($sonuc);
}
//puandan_tl_cevir($simdikitarih,$kullanicininidsi);

?>


