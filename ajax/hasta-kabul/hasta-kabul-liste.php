<?php
include "../../controller/fonksiyonlar.php";
$islem = $_GET['islem'];



if ($islem=="muayene-hasta-listesi"){

    $sql = sql_select("SELECT patient_registration.*,
       patient_registration.status          as status,
       patients.*,
       units.*,
       users.*,
       branch.*,
       patient_registration.id              AS hastakayitid,
       patient_registration.protocol_number as protokol_no,
       patients.tc_id                       as kimlik_no,
       users.id                             as doktor_id
FROM patient_registration
         inner JOIN patients ON patient_registration.tc_id = patients.tc_id
         inner JOIN users ON users.id = patient_registration.doctor
         inner JOIN units ON units.id = patient_registration.outpatient_id
         inner join branch on branch.branch_code = users.dr_bras_code $yetkilioldugupoliklinikler AND
                             patient_registration.insert_datetime like '%$nettarih%' AND
                             patient_registration.examination_start_time IS NULL AND patient_registration.status='1'");

    echo json_encode($sql);

}