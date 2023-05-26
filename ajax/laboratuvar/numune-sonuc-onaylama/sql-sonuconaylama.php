<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$tarih = date("Y-m-d");
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];

if ($islem == "sql_sonuc_onaylama_numune_listesi") {

    $sql = verilericoklucek("select distinct pp.service_requestsid,
                p.tc_id,
                p.patient_name,
                p.patient_surname,
                pp.protocol_number,
                pp.patient_id
from patient_prompts as pp
    inner join patients as p on p.id=pp.patient_id
where pp.sampling_confirmation = 1 and pp.prompts_type= 1 and DATE(pp.sample_date)='$tarih' and pp.status=1 and pp.sample_acceptance_status=1
group by p.tc_id, p.patient_name, p.patient_surname, pp.service_requestsid, pp.protocol_number,patient_id");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if ($islem == "sql_sonuc_onaylama_numune_listesi_icerik") {
    $service_requestsid = $_GET['service_requestsid'];
    unset($_GET['service_requestsid']);

    $sql = verilericoklucek("select distinct la.tube_container_type                       as tup_kap_tipi,
                ld.definition_name                           as tup_kap_adi,
                pp.service_requests_bardoce                  as barkod_numarasi,
                pp.sample_date                               as numune_alim_tarihi,
                u_ser.name_surname                           as numune_alan_kullanici,
                pp.sample_acceptance_date                    as numune_kabul_tarihi,
                users.name_surname                           as kabul_eden_kullanici,
                us_er.name_surname                           as sartli_kabul_eden_kullanici,
                pp.sample_conditional_acceptance_description as sartli_kabul_aciklama,
                pp.service_requestsid

from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         inner join units as u on u.id = pp.sampling_unit
         inner join users as u_ser on u_ser.id = pp.sampler_userid
         left join users as us_er on us_er.id = pp.sample_conditional_admission_userid
         left join users on users.id = pp.sample_accepting_userid

where pp.sampling_confirmation = 1
  and pp.prompts_type = 1
  and pp.sample_acceptance_status = 1
  and DATE(pp.sample_date) = '$tarih'
  and pp.status = 1
  and pp.service_requestsid = '$service_requestsid'
group by tup_kap_tipi, tup_kap_adi, barkod_numarasi, numune_alim_tarihi, numune_alan_kullanici, numune_kabul_tarihi,
         kabul_eden_kullanici, sartli_kabul_eden_kullanici, sartli_kabul_aciklama, pp.service_requestsid");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if ($islem == "sql_sonuc_onaylama_hasta_tani_bilgileri") {

    $protocol_number = $_GET['protocol_number'];
    unset($_GET['protocol_number']);

    $sql = verilericoklucek("select d.diagnoses_name
from patient_record_diagnoses as prd
    inner join diagnoses as d on d.id=prd.diagnosis_id
where prd.protocol_number='$protocol_number'
group by d.diagnoses_name");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if ($islem == "sql_sonuc_onay_numune_listesi_detay") {

    $service_requests = $_GET['service_requestsid'];
    $ID = $_GET['ID'];

    $count = count($ID);

    if ($count > 0) {

        $sql_sorgu = "";
//
//        $sql_sorgu .= ' la.tube_container_type=' . $ID[0];
//
//        if ($count > 1) {
//
//            for ($i = 0; $i < $count; $i++) {
//
//                $sql_sorgu .= ' or la.tube_container_type=' . $ID[$i];
//
//            }
//
//        }

        $metin = '';
        $say = 0;

        if ($count > 0) {
            foreach ($ID as $idler) {
                $say++;
                $metin .= $idler;
                if ($say != $count) {
                    $metin .= ',';
                }
            }
            $sql_sorgu .= 'la.tube_container_type in(' . $metin . ')';
        }

        $sql = verilericoklucek("select pp.id                        as idsi,
       request_name                 as tetkik_adi,
       analysis_result_status       as tetkik_sonuc_durum,
       sample_date                  as sonuc_tarihi,
       lab_technician_approval_date as laborant_onay_tarihi,
       lab_technician_approval      as onaylayan_laborantid,
       us.name_surname              as onaylayan_laborant,
       lab_specialist_approval_date as lab_uzman_onay_tarihi,
       lab_specialist_approval      as onaylayan_uzmanid,
       use.name_surname             as onaylayan_uzman,
       ld.device_name               as cihaz_adi,
       ltp.analysis_id              as parametre_tetkikid,
       result_notification          as sonuc_aciklama,
       service_requestsid           as servis_istek_id

from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_device_defined_assays as ldda on ldda.analysis_id = la.id
         inner join lab_devices as ld on ld.id = ldda.device_id
         inner join lab_test_parameter as ltp on ltp.analysis_id = la.id
         left join lab_definitions as lde on lde.id = ltp.unit
         left join users as us on us.id = pp.lab_technician_approval
         left join users as use on use.id = pp.lab_specialist_approval
where pp.service_requestsid = $service_requests
    and pp.sampling_confirmation = 1
    and pp.sample_acceptance_status = 1
    and pp.status = 1
    and pp.prompts_type = 1
    and ltp.status = 1
    and $sql_sorgu
group by tetkik_adi, tetkik_sonuc_durum, sonuc_tarihi,
         laborant_onay_tarihi, onaylayan_laborantid, lab_uzman_onay_tarihi, onaylayan_uzmanid, onaylayan_uzman,
         cihaz_adi, onaylayan_laborant, idsi, parametre_tetkikid, sonuc_aciklama, servis_istek_id");

//        echo $testet;

        if ($sql > 0) {

            echo json_encode($sql);

        } else {

            echo 2;

        }

    } else {

        $sql = verilericoklucek("select pp.id                        as idsi,
       request_name                 as tetkik_adi,
       analysis_result_status       as tetkik_sonuc_durum,
       sample_date                  as sonuc_tarihi,
       lab_technician_approval_date as laborant_onay_tarihi,
       lab_technician_approval      as onaylayan_laborantid,
       us.name_surname              as onaylayan_laborant,
       lab_specialist_approval_date as lab_uzman_onay_tarihi,
       lab_specialist_approval      as onaylayan_uzmanid,
       use.name_surname             as onaylayan_uzman,
       ld.device_name               as cihaz_adi,
       ltp.analysis_id              as parametre_tetkikid,
       result_notification as sonuc_aciklama,
       service_requestsid as servis_istek_id

from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_device_defined_assays as ldda on ldda.analysis_id = la.id
         inner join lab_devices as ld on ld.id = ldda.device_id
         inner join lab_test_parameter as ltp on ltp.analysis_id = la.id
         left join lab_definitions as lde on lde.id = ltp.unit
         left join users as us on us.id = pp.lab_technician_approval
         left join users as use on use.id = pp.lab_specialist_approval
where pp.service_requestsid = '$service_requests'
  and pp.sampling_confirmation = 1
  and pp.sample_acceptance_status = 1
  and pp.status = 1
  and pp.prompts_type = 1
  and ltp.status = 1
group by tetkik_adi, tetkik_sonuc_durum, sonuc_tarihi,
         laborant_onay_tarihi, onaylayan_laborantid, lab_uzman_onay_tarihi, onaylayan_uzmanid, onaylayan_uzman,
         cihaz_adi, onaylayan_laborant, idsi, parametre_tetkikid, sonuc_aciklama, servis_istek_id");

        if ($sql > 0) {

            echo json_encode($sql);

        } else {

            echo 2;

        }

    }

}

else if ($islem == "sql_sonuc_onay_numune_listesi_detay_parametreler") {

    $parametre_tetkikid = $_GET['parametre_tetkikid'];
    $service_requestsid = $_GET['service_requestsid'];
    $patient_promptsid = $_GET['patient_promptsid'];
    unset($_GET['parametre_tetkikid']);
    unset($_GET['service_requestsid']);
    unset($_GET['patient_promptsid']);

    $sql = verilericoklucek("select ltp.id                  parametre_id,
       ltp.parameter_name      parametre_adi,
       ld.definition_name      birimadi,
       ltp.sub_limit_numeric   alt_limit,
       ltp.up_limit_numeric    ust_limit,
       lar.lab_analysis_result tetkik_sonucu,
       lar.number_of_reworks   tektrar_calisma_sayisi,
       lar.result_notification sonuc_aciklama,
       lar.reason_for_rework yeniden_calisilma_nedeni,
       pp.id as ppid
       

from patient_prompts pp
         inner join lab_analysis la on la.sut_code = pp.request_code
         inner join lab_test_parameter ltp on ltp.analysis_id = la.id
         inner join lab_definitions ld on ld.id = ltp.unit
         left join lab_analysis_results lar on pp.id = lar.patient_promptsid
where ltp.id = lar.assay_parameterid
  and lar.insert_datetime in
      (select MAX(lar.insert_datetime) from lab_analysis_results as lar where lar.patient_promptsid = $patient_promptsid)
order by lar.id");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }
}

else if ($islem == "sql_tetkik_sonuc_gir_table") {

    $pp_id = $_GET['pp_id'];


    $sql = verilericoklucek("select ltp.id                parametre_id,
       ltp.parameter_name    parametre_adi,
       ld.definition_name    birimadi,
       ltp.sub_limit_numeric alt_limit,
       ltp.up_limit_numeric  ust_limit,
       pp.id ppid

from patient_prompts pp
         inner join lab_analysis la on la.sut_code = pp.request_code
         inner join lab_test_parameter ltp on ltp.analysis_id = la.id
         inner join lab_definitions ld on ld.id = ltp.unit
where pp.id = $pp_id");
    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }
}

else if ($islem=="sql_tetkik_sonuc_gir"){

    $COUNT = count($_POST["lab_analysis_result"]);
    $DEGERLER = $_POST;

    print_r($DEGERLER);
    for ($i = 0 ; $i < $COUNT; $i++){
        $_POST["assay_parameterid"] = $DEGERLER["assay_parameterid"][$i];
        $_POST["patient_promptsid"] = $DEGERLER["patient_promptsid"][$i];
        $_POST["lab_analysis_result"] = $DEGERLER["lab_analysis_result"][$i];
        $_POST["number_of_reworks"] = $DEGERLER["number_of_reworks"][$i];
        $_POST["result_notification"] = $DEGERLER["result_notification"][$i];
        $sql= direktekle("lab_analysis_results",$_POST);
    }

    if ($sql == 1) {?>
        <script>
            alertify.success('Sonuç Girme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Sonuç Girme İşlemi Başarısız');
        </script>
    <?php }

}

else if ($islem=="sql_tetkik_laborant_onay"){
    $patient_promptsid = $_POST['patient_promptsid'];
    unset($_POST['patient_promptsid']);

    $_POST["lab_technician_approval_date"] = $simdikitarih;
    $_POST["lab_technician_approval"] = $kullanici_id;
    $_POST["analysis_result_status"] = 1;

    $sql = direktguncelle("patient_prompts", "id", $patient_promptsid, $_POST);

    if ($sql == 1) {?>
        <script>
            alertify.success('Onaylama İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Onaylama İşlemi Başarısız');
        </script>
    <?php }

}

else if ($islem=="sql_tetkik_laborant_onay_iptal"){
    $patient_promptsid = $_POST['patient_promptsid'];
    unset($_POST['patient_promptsid']);

    $_POST["lab_technician_approval_date"] = null;
    $_POST["lab_technician_approval"] = null;
    $_POST["analysis_result_status"] = 0;

    $sql = direktguncelle("patient_prompts", "id", $patient_promptsid, $_POST);

    if ($sql == 1) {?>
        <script>
            alertify.success('Onay İptal Edildi');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('İptal İşlemi Başarısız');
        </script>
    <?php }

}

else if ($islem=="sql_tetkik_sonuc_uzman_onay"){
    $patient_promptsid = $_POST['patient_promptsid'];
    unset($_POST['patient_promptsid']);

    $_POST["lab_specialist_approval_date"] = $simdikitarih;
    $_POST["lab_specialist_approval"] = $kullanici_id;
    $_POST["analysis_result_status"] = 2;

    $sql = direktguncelle("patient_prompts", "id", $patient_promptsid, $_POST);

    if ($sql == 1) {?>
        <script>
            alertify.success('Onaylama İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Onaylama İşlemi Başarısız');
        </script>
    <?php }

}

else if ($islem=="sql_tetkik_sonuc_uzman_onay_iptal"){
    $patient_promptsid = $_POST['patient_promptsid'];
    unset($_POST['patient_promptsid']);


    $kontrol = tek("select * from patient_prompts where id=$patient_promptsid");

    if ($kontrol['lab_technician_approval']==null){

        $_POST["lab_specialist_approval_date"] = null;
        $_POST["lab_specialist_approval"] = null;
        $_POST["analysis_result_status"] = 0;

        $sql = direktguncelle("patient_prompts", "id", $patient_promptsid, $_POST);

        if ($sql == 1) {?>
            <script>
                alertify.success('Onay İptal Edildi');
            </script>
        <?php   } else {?>
            <script>
                alertify.error('İptal İşlemi Başarısız');
            </script>
        <?php }

    } else{

        $_POST["lab_specialist_approval_date"] = null;
        $_POST["lab_specialist_approval"] = null;
        $_POST["analysis_result_status"] = 1;

        $sql = direktguncelle("patient_prompts", "id", $patient_promptsid, $_POST);

        if ($sql == 1) {?>
            <script>
                alertify.success('Onay İptal Edildi');
            </script>
        <?php   } else {?>
            <script>
                alertify.error('İptal İşlemi Başarısız');
            </script>
        <?php }

    }
}

else if ($islem=="hasta_gecmis_tetkik_istemler"){
    $patient_id= $_GET['patient_id'];

    $sql = verilericoklucek("select distinct service_requestsid istem_id,
                p.tc_id            tc_id,
                p.patient_name     hasta_adi,
                p.patient_surname  hasta_soyadi,
                pp.protocol_number,
                pp.request_date    istek_tarihi,
                u.department_name birim
from patient_prompts pp
         inner join patients  p on p.id = pp.patient_id
         inner join units  u on u.id = pp.unit_id
where pp.sampling_confirmation = 1
  and pp.prompts_type = 1
  and pp.status = 1
  and pp.sample_acceptance_status = 1
  and analysis_result_status=2
  and pp.patient_id = $patient_id");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }
}

else if ($islem=="sql_tetkik_sonucu_tekrar_gir"){

    $COUNT = count($_POST["lab_analysis_result"]);
    $DEGERLER = $_POST;

    print_r($DEGERLER);
    for ($i = 0 ; $i < $COUNT; $i++){
        $_POST["assay_parameterid"] = $DEGERLER["assay_parameterid"][$i];
        $_POST["patient_promptsid"] = $DEGERLER["patient_promptsid"][$i];
        $_POST["lab_analysis_result"] = $DEGERLER["lab_analysis_result"][$i];
        $_POST["reason_for_rework"] = $DEGERLER["reason_for_rework"][$i];
        $assay_parameterid = $DEGERLER["assay_parameterid"][$i];
        $patient_promptsid = $DEGERLER["patient_promptsid"][$i];
        $tek = verilericoklucek("select number_of_reworks from lab_analysis_results where assay_parameterid='$assay_parameterid' and patient_promptsid='$patient_promptsid' order by insert_datetime DESC  LIMIT 1");
        $number_of_reworks = 0;
        foreach ($tek as $veriler){
            $number_of_reworks = $veriler["number_of_reworks"] + 1;
        }

        $_POST["number_of_reworks"] = $number_of_reworks;

        $sql= direktekle("lab_analysis_results",$_POST);
    }

    if ($sql == 1) {?>
        <script>
            alertify.success('Sonuç Girme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Sonuç Girme İşlemi Başarısız');
        </script>
    <?php }

}

else if($islem=="sql_tup_listesi"){

    $sql = verilericoklucek("select id tup_id,
    definition_name tup_adi
from lab_definitions
where definition_type = 'TUP_TANIM'
  and status = '1'");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if($islem=="sql_cihaz_listesi"){

    $sql = verilericoklucek("select id cihaz_id,
       device_name cihaz_adi
from lab_devices
where status = '1'");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if($islem=="sql_tetkik_listesi"){

    $sql = verilericoklucek("select id          tetkik_id,
       analysis_name tetkik_adi
from lab_analysis
where status = '1'
");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if($islem=="sql_doktor_listesi"){

    $sql = verilericoklucek("select u.id          kullanici_id,
       u.name_surname kullanici_adi,
       td.definition_name unvani
from users u
inner join transaction_definitions td on td.id = u.title
where u.status = '1' and u.title='32' or u.title='2068'");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if($islem=="sql_birim_listesi"){

    $sql = verilericoklucek("select id              birim_id,
       department_name birim_adi
from units
where status = '1'");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if($islem=="sql_laboratuvar_listesi"){

    $sql = verilericoklucek("select id lab_id,
definition_name lab_adi
from lab_definitions
where definition_type = 'LABORATUVAR_TANIM' and status='1'");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}

else if ($islem=="sql_kriter_paneli_filtre"){
    $sonuc_onay_bas_tarih = $_GET['sonuc_onay_bas_tarih'];
    $sonuc_onay_bit_tarih = $_GET['sonuc_onay_bit_tarih'];
    $tup_barkod_numarasi = $_GET['tup_barkod_numarasi'];
    $protokol_no = $_GET['protokol_no'];
    $tup_id = $_GET['tup_id'];
    $cihaz_id = $_GET['cihaz_id'];
    $sonuc_durumu = $_GET['sonuc_durumu'];
    $tetkik_id = $_GET['tetkik_id'];
    $doktor_id = $_GET['doktor_id'];
    $birim_id = $_GET['birim_id'];
    $istem_no = $_GET['istem_no'];
    $hasta_turu = $_GET['hasta_turu'];
    $lab_id = $_GET['lab_id'];

//    print_r($sonuc_onay_bas_tarih.' '.$sonuc_onay_bit_tarih.' '.$tup_barkod_numarasi.' '.$protokol_no.' '.$tup_id.' '.$cihaz_id.' '.$sonuc_durumu.' '.$tetkik_id.' '.$doktor_id.' '.$birim_id.' '.$istem_no.' '.$hasta_turu.' '.$lab_id);

    $sqk_ekle='';

    if ($tup_barkod_numarasi!=''){
        $sqk_ekle.="  and pp.service_requests_bardoce=$tup_barkod_numarasi ";
    }
    if ($protokol_no!=''){
        $sqk_ekle.="  and pp.protocol_number=$protokol_no ";
    }
    if ($tup_id!=''){
        $sqk_ekle.="  and la.tube_container_type=$tup_id ";
    }
//    if ($cihaz_id!=''){
//        $sqk_ekle.="  and  ";
//    }
    if ($sonuc_durumu!=''){
        $sqk_ekle.="  and pp.analysis_result_status=$sonuc_durumu ";
    }
    if ($tetkik_id!=''){
        $sqk_ekle.="  and pp.study_id=$tetkik_id ";
    }
    if ($doktor_id!=''){
        $sqk_ekle.="  and pp.doctor_id=$doktor_id ";
    }
    if ($birim_id!=''){
        $sqk_ekle.="  and pp.unit_id=$birim_id  ";
    }
    if ($istem_no!=''){
        $sqk_ekle.="  and pp.service_requestsid=$istem_no ";
    }
    if ($hasta_turu!=''){
        $sqk_ekle.="  and pr.patient_type=$hasta_turu ";
    }
//    if ($lab_id!=''){
//        $sqk_ekle.="  and   ";
//    }

    $sql_metin="select distinct pp.service_requestsid,
                p.tc_id,
                p.patient_name,
                p.patient_surname,
                pp.protocol_number,
                pp.patient_id,
                pp.sample_date
from patient_prompts pp
         inner join patients p on p.id = pp.patient_id
         inner join patient_registration pr on p.id = pr.patient_id
         inner join users u on u.id = pp.doctor_id
         inner join lab_analysis la on la.id = pp.study_id
         inner join units on units.id = pp.unit_id
where DATE(pp.sample_date) between '$sonuc_onay_bas_tarih' and '$sonuc_onay_bit_tarih'
  and pp.status = 1
  and pp.sampling_confirmation = 1
  and pp.prompts_type = 1
  and pp.sample_acceptance_status = 1 $sqk_ekle";

    $sql=verilericoklucek($sql_metin);

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

}
?>