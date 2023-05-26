<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();

$kullanici_id = $_SESSION['id'];

if($islem=="borclu-hastalar-listesi") {
    
$sql_sorgu ="select patients.patient_name      as hasta_soyadi,
       patients.patient_surname                as hasta_adi,
       patient_registration.protocol_number    as hasta_protokol_no,
       patient_prompts.patient_tc              as hasta_tc_numarasi,
       units.department_name                   as birim_adi,
       patients.social_assurance               as hasta_sosyal_guvencesi,
       transaction_definitions.definition_name as hasta_sosyal_guvence_adi
from patient_prompts
         inner join patients on patients.tc_id = patient_prompts.patient_tc
         inner join patient_registration on patient_registration.protocol_number = patient_prompts.protocol_number
         inner join units on units.id = patient_registration.outpatient_id
         inner join transaction_definitions on patients.social_assurance = transaction_definitions.definition_code
where patient_prompts.payment_completed = '0'";

    $where = [];
    $order = ['id', 'desc'];
    $column = $_POST['order'][0]['column'];
    $columnname = $_POST['columns'][$column]['data'];
    $columnorder = $_POST['order'][0]['dir'];

    if (isset($columnname) && !empty($columnname) && isset($columnorder) && !empty($columnorder)) {
        $order[0] = $columnname;
        $order[1] = $columnorder;
    }

    if(!empty($_POST['tc_no_ara'])){
             $where[] =  "patients.tc_id" . " like ('%".$_POST["tc_no_ara"]."%') " ;
    }

    if($_POST['bitis_tarihi'] > 1 && $_POST['baslangic_tarihi'] > 1){
        $where[] =  "patient_prompts.request_date between " . "'$_POST[baslangic_tarihi]'" . " and " . "'$_POST[bitis_tarihi]'" ;
    }

    if(count($where) > 0 ){
        $sql_sorgu .= ' and ' . implode(' or ' , $where);
    }

    $sql_sorgu .=  ' group by patient_registration.id,patient_prompts.patient_tc,units.department_name,patients.patient_surname,patients.patient_name,patients.social_assurance,transaction_definitions.definition_name ' . 'order by ' . $order[0] . ' ' . $order[1] . ' ' ;

    if($_POST['length'] != -1) {
        $sql_sorgu .= 'offset ' . $_POST['start'] . ' rows' . ' ';
        $sql_sorgu .= 'fetch next ' . $_POST['length'] . ' rows only' . ' ';
                               }

//
//    echo $sql_sorgu;
//    exit();

    $sql_sonuc = verilericoklucek($sql_sorgu);

    $response = [];
    $response['data'] = [];
    $response['recordsTotal'] = count($sql_sonuc);
    $response['recordsFiltered'] = count($sql_sonuc);

    foreach ($sql_sonuc as $item){
        $response['data'][] = [
            'hasta_tc_numarasi' => $item['hasta_tc_numarasi'],
            'hasta_protokol_no' => $item['hasta_protokol_no'],
            'hasta_sosyal_guvence_adi' => $item['hasta_sosyal_guvence_adi'],
            'birim_adi' => $item['birim_adi'],
            'toplam_borc' => hastaborcsorgula($item['hasta_protokol_no']),
            'hasta_adi' =>  $item['hasta_soyadi'] . ' ' . $item['hasta_adi']   ,
            'DT_RowClass' => "SECIM_YAP ",
        ];
    }

    echo json_encode($response);

}
