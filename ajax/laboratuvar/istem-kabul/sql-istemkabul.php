<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$tarih = date("Y-m-d");
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];


//İSTEM LİSTESİ

if ($islem == "sql_lab_istem_hasta") {

    $sql = verilericoklucek("select psr.id,
       psr.insert_datetime,
       psr.patient_queue,
       psr.protocol_id,
       units.department_name,
       psr.prompt_status,
       patients.tc_id,
       patients.patient_name,
       patients.patient_surname

from patient_service_requests as psr
         inner join patients on patients.id=psr.patient_id
         inner join patient_prompts as pp on pp.service_requestsid=psr.id
         inner join units on units.id=pp.unit_id
where psr.service_request_type='1' and psr.status!='0' and DATE(psr.insert_datetime)='$tarih'
group by psr.insert_datetime,psr.prompt_status, units.department_name, patients.tc_id,patients.patient_name,
         patients.patient_surname,psr.protocol_id,psr.patient_queue,psr.id");


    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

//İSTEM TARİH VE DURUMA GÖRE FİLTRE
else if ($islem == "sql_lab_istem_hasta_filtre") {
    $durum = $_GET['durum'];
    $tarih2 = $_GET['tarih'];
    unset($_GET["durum"]);
    unset($_GET["tarih"]);

        $sql = verilericoklucek("select psr.id,
       psr.insert_datetime,
       psr.patient_queue,
       psr.protocol_id,
       units.department_name,
       psr.prompt_status,
       patients.tc_id,
       patients.patient_name,
       patients.patient_surname

from patient_service_requests as psr
         inner join patients on patients.id=psr.patient_id
         inner join patient_prompts as pp on pp.service_requestsid=psr.id
         inner join units on units.id=pp.unit_id
where psr.service_request_type='1' and psr.status!='0' and psr.prompt_status='$durum' and  DATE(psr.insert_datetime)='$tarih2'
group by psr.insert_datetime,psr.prompt_status, units.department_name, patients.tc_id,patients.patient_name,
         patients.patient_surname,psr.protocol_id,psr.patient_queue,psr.id");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

//İSTEM TARİHE GÖRE FİLTRE
else if ($islem == "sql_lab_istem_hasta_tarih_filtre") {
    $tarih = $_GET['tarih'];
    unset($_GET["tarih"]);

        $sql = verilericoklucek("select psr.id,
       psr.insert_datetime,
       psr.patient_queue,
       psr.protocol_id,
       units.department_name,
       psr.prompt_status,
       patients.tc_id,
       patients.patient_name,
       patients.patient_surname

from patient_service_requests as psr
         inner join patients on patients.id=psr.patient_id
         inner join patient_prompts as pp on pp.service_requestsid=psr.id
         inner join units on units.id=pp.unit_id
where psr.service_request_type='1' and psr.status!='0'  and  DATE(psr.insert_datetime)='$tarih'
group by psr.insert_datetime,psr.prompt_status, units.department_name, patients.tc_id,patients.patient_name,
         patients.patient_surname,psr.protocol_id,psr.patient_queue,psr.id");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

//İSTEM HASTA TÜP TABLO
else if ($islem == "sql_lab_istem_hasta_tup") {
    $service_requests = $_GET['service_requests'];

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.service_request_approval_date,
                ltg.group_name
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
where pp.service_requestsid = '$service_requests' and pp.prompts_type = 1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce,pp.service_request_approval_date, ltg.group_name");


    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }

}

//İSTEM TÜPE TANIMLI İSTENİLEN TETKİK
else if ($islem == "sql_lab_istem_hasta_tup_tetkik") {

    $service_requests = $_GET['service_requests'];
    $ID = $_GET['ID'];

    if ($ID > 0) {

        $sql_sorgu = "";

        $ID = explode(',', $ID);

        $count = count($ID);

        $sql_sorgu .= ' la.tube_container_type=' . $ID[0];

        if ($count > 1) {
            for ($i = 0; $i < $count; $i++) {

                $sql_sorgu .= ' or la.tube_container_type=' . $ID[$i];

            }
        }

        $sql = verilericoklucek("select * from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type 
where pp.service_requestsid = '$service_requests' and pp.prompts_type = 1 and ($sql_sorgu)");

        echo json_encode($sql);

    } else {

        $sql = verilericoklucek("select * from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
where pp.service_requestsid = '$service_requests' and pp.prompts_type = 1");

        echo json_encode($sql);
    }
}

//İSTEM KABUL ET
else if ($islem == "sql_istem_kabulet") {
    $id = $_POST['id'];
    unset($_POST['id']);

    $sorgu=tek("select max(patient_queue) from patient_service_requests where service_request_type='1' and DATE(insert_datetime)='$tarih'");
    $sira_no = $sorgu["max"];
    if ($sira_no == null || $sira_no == ""){
        $sira_no = 0;
        $sira_no +=1;
    }else{
        $sira_no +=1;
    }

    $_POST["patient_queue"]=$sira_no;
    $_POST["prompt_status"]=1;
    $_POST["service_request_approval_date"]=$simdikitarih;

    $sql=direktguncelle("patient_service_requests", "id", $id, $_POST);
    unset( $_POST["patient_queue"]);
    unset( $_POST["prompt_status"]);
    unset( $_POST["service_request_approval_date"]);
    if ($sql == 1){

        $sql_1=sql_select("select distinct la.tube_container_type
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
where pp.service_requestsid = '$id' and pp.prompts_type = 1
group by la.tube_container_type");

        foreach ($sql_1 as $item) {
            $uniq_number= substr(hexdec(uniqid()),-8);
            $barcode_id = "33".$uniq_number;

            $tube_id = $item['tube_container_type'];

            $sql_2=sql_select("select lab_analysis.tube_container_type, 
       patient_prompts.id as istem_id
from patient_prompts
    inner join lab_analysis on lab_analysis.sut_code = patient_prompts.request_code
where patient_prompts.service_requestsid = '$id' and lab_analysis.tube_container_type = $tube_id");

            foreach ($sql_2 as $item2){
                $id_2 = $item2['istem_id'];
                $sql_3 = tek("UPDATE patient_prompts SET service_requests_bardoce = $barcode_id WHERE id = $item2[istem_id]");
            }
        }

        $_POST["result_status"]=1;
        $_POST["service_request_approval_date"]=$simdikitarih;
        $sql=direktguncelle("patient_prompts", "service_requestsid", $id, $_POST);
        unset($_POST["result_status"]);
        unset($_POST["service_request_approval_date"]);
        if ($sql == 1){?>
            <script>
                alertify.success('Kabul Etme İşlemi Başarılı.');
            </script>
        <?php }

    }else{ ?>
        <script>
            alertify.error('Kabul Etme İşlemi Başarısız.');
        </script>
    <?php }
}

//İSTEM REDDET
else if($islem=="sql_istem_reddet"){
    $id = $_POST['id'];
    unset($_POST['id']);

    $_POST["patient_queue"]=null;
    $_POST["prompt_status"]=0;
    $_POST["service_request_approval_date"]=null;

    $sql=direktguncelle("patient_service_requests", "id", $id, $_POST);
    unset( $_POST["patient_queue"]);
    unset( $_POST["prompt_status"]);
    unset( $_POST["service_request_approval_date"]);
    if ($sql == 1){

        $_POST["result_status"]=0;
        $_POST["service_requests_bardoce"]=null;
        $_POST["service_request_approval_date"]=null;
        $sql=direktguncelle("patient_prompts", "service_requestsid", $id, $_POST);
        unset($_POST["result_status"]);
        unset($_POST["service_requests_bardoce"]);
        unset($_POST["service_request_approval_date"]);
        if ($sql == 1){?>
            <script>
                alertify.success('Reddetme İşlemi Başarılı.');
            </script>

            <?php  }

    } else{ ?>
        <script>
            alertify.error('Reddetme İşlemi Başarısız.');
        </script>
    <?php }
}

//RANDEVU MODAL İSTEM LİSTESİ
else if ($islem == "sql_lab_istem_hasta_listesi") {

    $sql = verilericoklucek("select psr.id,
       psr.insert_datetime,
       psr.patient_queue,
       psr.protocol_id,
       units.department_name,
       psr.prompt_status,
       patients.tc_id,
       patients.patient_name,
       patients.patient_surname,
       psr.appointment_datetime

from patient_service_requests as psr
         inner join patients on patients.id=psr.patient_id
         inner join patient_prompts as pp on pp.service_requestsid=psr.id
         inner join units on units.id=pp.unit_id
where psr.service_request_type='1' and psr.status!='0' and psr.prompt_status!='1'
group by psr.insert_datetime,psr.prompt_status, units.department_name, patients.tc_id,patients.patient_name,
         patients.patient_surname,psr.protocol_id,psr.patient_queue,psr.id,psr.appointment_datetime");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

//CALENDAR DA GÖZÜKECEK BİLGİLER
else if ($islem == "sql_lab_istem_hasta_randevu_calendar") {

    $sql = verilericoklucek("select  CONCAT(patients.patient_name,' ',patients.patient_surname,' ,Randevu Tarihi:',psr.appointment_datetime,' ,TC No:',patients.tc_id,' ,Telefon:',patients.phone_number,' ,Birim:',units.department_name) as title,
         psr.insert_datetime,
       psr.appointment_datetime as start

from patient_service_requests as psr
         inner join patients on patients.id=psr.patient_id
         inner join patient_prompts as pp on pp.service_requestsid=psr.id
         inner join units on units.id=pp.unit_id
where psr.appointment_datetime is not null and psr.service_request_type='1'
group by psr.insert_datetime, units.department_name,patients.patient_name,
         patients.patient_surname,psr.appointment_datetime,patients.phone_number,patients.tc_id");

    echo json_encode($sql);
}

//RANDEVU TANIMLA
else if ($islem=="sql_randevu_tanimla"){
    $id= $_POST["id"];
    unset($_POST["id"]);


    $_POST["prompt_status"]=2;
    $sql=direktguncelle("patient_service_requests","id", $id,  $_POST);
    unset( $_POST["prompt_status"]);
    if ($sql==1){?>
        <script>
            alertify.success('Randevu Tanımlama İşlemi Başarılı.');
        </script>
        <?php
    }
    else{?>
        <script>
            alertify.error('Randevu Tanımlama İşlemi Başarısız.');
        </script>
    <?php }
}

//RANDEVU DÜZENLE
else if ($islem=="sql_randevu_duzenle"){
    $id= $_POST["id"];
    unset($_POST["id"]);


    $_POST["prompt_status"]=2;
    $sql=direktguncelle("patient_service_requests","id", $id,  $_POST);
    unset( $_POST["prompt_status"]);
    if ($sql==1){?>
        <script>
            alertify.success('Randevu Düzenleme İşlemi Başarılı.');
        </script>
        <?php
    }
    else{?>
        <script>
            alertify.error('Randevu Düzenleme İşlemi Başarısız.');
        </script>
    <?php }
}

//RANDEVU SİL
else if ($islem=="sql_randevu_sil"){
    $id= $_POST["id"];
    unset($_POST["id"]);


    $_POST["prompt_status"]=0;
    $_POST["appointment_datetime"]=null;
    $_POST["appointment_deletion_datetime"]=$simdikitarih;
    $_POST["delete_appointment_userid"]=$kullanici_id;
    $sql=direktguncelle("patient_service_requests","id", $id,  $_POST);
    unset( $_POST["prompt_status"]);
    unset( $_POST["appointment_datetime"]);
    unset( $_POST["appointment_deletion_datetime"]);
    unset( $_POST["delete_appointment_userid"]);
    if ($sql==1){?>
        <script>
            alertify.success('Randevu Silme İşlemi Başarılı.');
        </script>
        <?php
    }
    else{?>
        <script>
            alertify.error('Randevu Silme İşlemi Başarısız.');
        </script>
    <?php }
}
?>