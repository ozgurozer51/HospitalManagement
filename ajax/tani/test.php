<?php  include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$islem = $_GET['islem'];
$random_name = uniqid();


if($islem=="tani-listesi") {

    $sql = verilericoklucek("select * from patient_record_diagnoses inner join diagnoses on  diagnoses.id = patient_record_diagnoses.diagnosis_id where patient_record_diagnoses.protocol_number = 27 and patient_record_diagnoses.status = 1");
    echo json_encode($sql);

}
