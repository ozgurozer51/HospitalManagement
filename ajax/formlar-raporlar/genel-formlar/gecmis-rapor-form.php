<?php include "../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem = $_GET['islem'];

if ($islem == "hasta-gecmis-islemleri") {
$sql = sql_select("select patients_reports_forms.protocol_no,
       patients_reports_forms.patient_id,
       patients_reports_forms.insert_datetime,
       users.name_surname,
       patients_reports_forms.id,
       patients_reports_forms.form_id
from patients_reports_forms
         inner join users on users.id = patients_reports_forms.insert_userid
where patients_reports_forms.referance_table_name = '$_GET[referance_table_name]'
  and patients_reports_forms.patient_id = $_GET[patient_id]
  and patients_reports_forms.status=true");

echo json_encode($sql);

} if($islem == "hasta-gecmis-form-getir"){

    $name = $_GET['name'];
    $sql = sql_select("select * from $name where id=$_GET[id] and status='true'");

    $sql_r =  str_replace(array('[', ']'), '', htmlspecialchars(json_encode($sql), ENT_NOQUOTES));
    echo $sql_r;

} ?>