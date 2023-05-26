<?php
include "../../controller/fonksiyonlar.php";
$islem = $_GET['islem'];
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d h:i:s');

$now_datetime_start = date('Y-m-d') . " 00:00:00";
$now_datetime_end = date('Y-m-d') . " 23:59:59";

$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanic_id=$_SESSION['id'];

    $sql_user_info = sql_select("select * from users where id=$kullanic_id");

if ($islem=="muayene-hasta-listesi"){

    $sql['data'] = array();

    $sql_istem = sql_select("select id , protocol_number from patient_record_diagnoses where status=1 and insert_datetime >='$now_datetime_start' and insert_datetime <= '$now_datetime_end'");
    $sql['data_istem'][] = $sql_istem;

    $ek_sorgu = "";
    if ($_POST['birim_filt']){
       $ek_sorgu .=  " and units.department_name like '%$_POST[birim_filt]%' ";
    }
    if ($_POST['doktor_filt']){
       $ek_sorgu .=  " and patient_registration.doctor = $_POST[doktor_filt] ";
    }
    if ($_POST['soyad_filt']){
       $ek_sorgu .=  " and patients.patient_surname like '%$_POST[soyad_filt]%' ";
    }
    if ($_POST['ad_filt']){
       $ek_sorgu .=  " and patients.patient_name like '%$_POST[soyad_filt]%' ";
    }
    if ($_POST['kimlik_filt']){
       $ek_sorgu .=" and patients.tc_id like  '%$_POST[kimlik_filt]%' ";
    }

    if ($_POST['protokol_no_filt']){
       $ek_sorgu .=" and patient_registration.protocol_number =  $_POST[protokol_no_filt] ";
    }

    if ($_POST['ilk_tarih_filt']){
       $ek_sorgu .=" and (patient_registration.insert_datetime >= '$_POST[ilk_tarih_filt]' and patient_registration.insert_datetime <= '$_POST[son_tarih_filt]' ) ";
    }else{
        $ek_sorgu .= "and patient_registration.insert_datetime >='$now_datetime_start'";
    }

    if ($_POST['triaj_filt'] && $_POST['triaj_filt'] != 1 ){
        $ek_sorgu .=" and patient_registration.trage_code = $_POST[triaj_filt]  ";
    }

    if ($_POST['kabul_filt']){
        if($_POST['kabul_filt'] == 2){
            $ek_sorgu .=" and patient_record_diagnoses.id > 0 ";
        }elseif ($_POST['kabul_filt'] == 3){
            $ek_sorgu .=" and patient_record_diagnoses.id IS NULL ";
        }
    }

    if ($_POST['kabul_sekli_filt'] != 1){
        $ek_sorgu .=" and patient_registration.patient_admission_type = $_POST[kabul_sekli_filt] ";
    }

    if($sql_user_info[0]['personnel_type']!=1){
        $sistem_kullanicisi_degil  = "and users_outhorized_units.userid = '$kullanic_id' and users_outhorized_units.status = 1 ";
    }

    $sql['data'][] = sql_select("SELECT DISTINCT patient_registration.*,
                     patient_registration.status as status,
                     patients.patient_name,
                     patients.patient_surname,
                     users.name_surname,
                     patient_registration.call_from_screen as hasta_cagirildimi,
                     patient_registration.id               AS hastakayitid,
                     patient_registration.protocol_number  as protokol_no,
                     patient_registration.id               as hasta_kayit_id,
                     patient_registration.insert_datetime  AS muracat_tarihi,
                     units.department_name                 as polikilinik,
                     patients.tc_id                        as kimlik_no,
                     users.id                              as doktor_id,
                     consultation.id                       as consultation_id
              FROM patient_registration
                       left JOIN patients ON patient_registration.tc_id = patients.tc_id
                       left JOIN users ON users.id = patient_registration.doctor
                       left JOIN units ON units.id = patient_registration.outpatient_id
                       left join branch on branch.branch_code = users.dr_bras_code
                       left join users_outhorized_units on users_outhorized_units.unit_id = patient_registration.outpatient_id
                       left join consultation on consultation.protocol_number = patient_registration.protocol_number
                           where patient_registration.examination_start_time IS NULL
                             $sistem_kullanicisi_degil  $ek_sorgu");

    echo json_encode($sql);

}if($islem =="doktor-listesi"){

    $ek_sorgu_2 = "";
    if($_POST['birim_filt']) {

        $count_birimler = count($_POST['birim_filt']);

        if($count_birimler == 1){

           $birim_id = $_POST['birim_filt'][0];
            $ek_sorgu_2 .= " and users.department='$birim_id' ";

        }else {

            $count = 1;
            foreach ($_POST['birim_filt'] as $item) {
                if ($count == 1) {
                    $ek_sorgu_2 .= " and ( users.department='$item' ";
                } else if($count == $count_birimler) {
                    $ek_sorgu_2 .= " or users.department='$item') ";
                }else{
                    $ek_sorgu_2 .= " or users.department='$item' ";
                }
                $count +=1;
            }
        }

    }
    
   $sql = sql_select("select users.insert_datetime as kayit_tarihi ,users.id as doktor_id , * from users inner join units on units.id = users.department where users.status=1 and users.name_surname !='' $ek_sorgu_2");

//    echo $sql;
//    exit();

   echo json_encode($sql);

}if($islem =="birim-listesi"){

   $sql = sql_select("select units.department_name, units.id
                      from units
                               inner join users_outhorized_units on users_outhorized_units.unit_id = units.id
                      where users_outhorized_units.status = 1
                        and units.status = 1
                        and users_outhorized_units.userid = $_SESSION[id] and units.unit_type = 0");
   echo json_encode($sql);
}