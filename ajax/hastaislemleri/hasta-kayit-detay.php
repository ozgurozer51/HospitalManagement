<?php
include "../../controller/fonksiyonlar.php";
$islem = $_GET['islem'];
$now_datetime_start = date('Y-m-d') . " 00:00:00";
$now_datetime_end = date('Y-m-d') . " 23:59:59";

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d h:i:s');

$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanic_id = $_SESSION['id'];


if($islem == "hastaya-ait-randevular") {

    $tc_no = $_POST['tc_no'];
    if ($tc_no) {
        echo json_encode(sql_select("SELECT * , patient_appointments.id as randevu_id , patient_appointments.status as randevu_durumu
                     FROM patient_appointments
                     inner join units on units.id = patient_appointments.unit_code
                     where patient_appointments.appointment_time >= '$now_datetime_start'
                       AND patient_appointments.appointment_time <= '$now_datetime_end'
                       AND patient_appointments.status!=4
                       AND patient_appointments.tc_id='$tc_no'
                     order by patient_appointments.appointment_time DESC"));
    }}

if($islem=="hasta-gecmis-muayeneleri"){

    $tc_no = $_POST['tc_no'];
    echo json_encode(sql_select("
       select patient_registration.id              as hastakayitid,
       patient_registration.insert_datetime as hasta_kayit_tarihi,
       patient_registration.protocol_number as protokol_no,
       users.name_surname                   as doktor_adi,
       units.department_name as bolum_adi
from patient_registration
         inner join users on users.id = patient_registration.doctor
         inner join units on units.id = patient_registration.outpatient_id
where patient_registration.tc_id = '$tc_no'
  and patient_registration.baby = '0'
order by patient_registration.id desc
    "));

}

if ($islem == "randevu-iptali"){

        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['status']  = 2;
        $sql = direktguncelle("patient_appointments" , 'id' , $id ,  $_POST);
               if ($sql == 1) { ?>
               <script>
                   alertify.set('notifier','delay', 8);
                   alertify.success('Randevu İptali Başarılı');
               </script>
           <?php   } else { ?>
               <script>
                   alertify.set('notifier','delay', 8);
                   alertify.error('Randevu İptali Başarısız');
               </script>
           <?php }

}if ($islem == "gelmedi") {

    $id = $_POST['id'];
    unset($_POST['id']);
    $_POST['status'] = 3;
    $sql = direktguncelle("patient_appointments", 'id', $id, $_POST);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.success('Hasta Gelmedi Olarak İşaretlendi');
        </script>

    <?php } else { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.error('İşlem Başarısız');
        </script>
    <?php }

}if ($islem == "randevu-iptalini-geri-al") {

    $id = $_POST['id'];
    unset($_POST['id']);
    $_POST['status'] = 1;
    $sql = direktguncelle("patient_appointments", 'id', $id, $_POST);

    if ($sql == 1) { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.success('Randevu İptali Geri Alındı');
        </script>

    <?php } else { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.error('İşlem Başarısız');
        </script>
    <?php }

}if($islem=="hastayi-sistemde-sorgula"){

   $sql =  sql_select("
                select *
           from patients
                    left join patients_photo on patients_photo.patients_id = patients.id
           where patients.tc_id = '$_GET[tc_no]' ");

    $sql_r =  str_replace(array('[', ']'), '', htmlspecialchars(json_encode($sql), ENT_NOQUOTES));
    echo $sql_r;

}if($islem=='polikliniklere-kayitli-doktorlar'){

    echo json_encode(sql_select("select * from users where users.department=$_GET[poliklinik_id]"));

}if($islem=="ilceleri-getir"){

    echo json_encode(sql_select("select * from district where province_number=$_GET[province_id]"));


}if($islem=="kayitli-hasta-listesi"){

    $sql_user_info = sql_select("select * from users where id=$kullanic_id");

    if($sql_user_info[0]['personnel_type']!=1){
        $sistem_kullanicisi_degil  = "and users_outhorized_units.userid = '$kullanic_id' and users_outhorized_units.status = 1 ";
    }

    $ek_sorgu = '';
    if ($_POST['ilk_tarih_filt']){
        $ek_sorgu .=" and (patient_registration.insert_datetime >= '$_POST[ilk_tarih_filt]' and patient_registration.insert_datetime <= '$_POST[son_tarih_filt]' ) ";
    }else{
        $ek_sorgu .= "and patient_registration.insert_datetime >='$now_datetime_start'";
    }

    $sql = sql_select("SELECT DISTINCT patient_registration.*,
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
                     consultation.id                       as consultation_id,  
                     patients.id                           as hasta_id,
                     patient_registration.id               as deger
              FROM patient_registration
                       left JOIN patients ON patient_registration.tc_id = patients.tc_id
                       left JOIN users ON users.id = patient_registration.doctor
                       left JOIN units ON units.id = patient_registration.outpatient_id
                       left join branch on branch.branch_code = users.dr_bras_code
                       left join users_outhorized_units on users_outhorized_units.unit_id = patient_registration.outpatient_id
                       left join consultation on consultation.protocol_number = patient_registration.protocol_number
                           where patient_registration.examination_start_time IS NULL
                             $sistem_kullanicisi_degil $ek_sorgu");

    echo json_encode($sql);







}

