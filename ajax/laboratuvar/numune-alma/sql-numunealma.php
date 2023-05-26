<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$tarih = date("Y-m-d");
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];

if ($islem == "sql_numune_alma_hasta_tup") {
    $git = $_GET['barkod_no'];
    $barkod_no = trim($git);

    $barkodu_kontrol_et = tek("select service_requests_bardoce from patient_prompts where service_requests_bardoce='$barkod_no'");
    if ($barkodu_kontrol_et > 0){
        $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.service_request_approval_date,
                ltg.group_name,
                pp.sampling_confirmation,
                pp.sample_date,
                pp.sample_rejection_detail,
                lb.definition_name as ret,
                pp.sample_rejection_cancellation_date,
                pp.sample_rejection_cancel_userid,
                u.name_surname

from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_test_groups as ltg on ltg.id = la.test_group
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         left join users as u on u.id=pp.sample_rejection_cancel_userid
         left join lab_definitions as lb on lb.id=pp.sample_rejection_detail
where pp.service_requests_bardoce = '$barkod_no' and pp.prompts_type = 1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce,pp.service_request_approval_date,ltg.group_name,pp.sampling_confirmation,
         pp.sample_date,pp.sample_rejection_detail,lb.definition_name,pp.sample_rejection_cancellation_date,pp.sample_rejection_cancel_userid,u.name_surname");

        if ($sql > 0) {
            echo json_encode($sql);
        } else {
            echo 2;
        }
    }else{
        echo 404;
    }

}

else if ($islem == "sql_numune_alma_tup_tetkik") {
    $git = $_GET['barkod_no'];
    $barkod_no_tetkik = trim($git);
    $sql = verilericoklucek("select * from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
where pp.service_requests_bardoce = '$barkod_no_tetkik' and pp.prompts_type = 1");


    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }

}

else if ($islem == "sql_numune_alma_diger_tup") {
    $git = $_GET['barkod_no'];
    $barkod_nolari = trim($git);

    $patient_prompts=tek("select distinct pp.service_requests_bardoce,pp.service_requestsid from  patient_prompts as pp where pp.service_requests_bardoce='$barkod_nolari'
group by service_requests_bardoce,pp.service_requestsid");
    $service_requests = $patient_prompts["service_requestsid"];



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

else if ($islem == "sql_numune_alim_listesi") {
    $git = $_GET['barkod_no'];
    $barkod_numara = trim($git);

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sampling_unit,
                pp.sampler_userid,
                pp.sample_date,
                u.department_name,
                users.name_surname
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
    inner join units as u on u.id=pp.sampling_unit
    inner join users on users.id=pp.sampler_userid
where pp.service_requests_bardoce = '$barkod_numara' and pp.prompts_type = 1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce,pp.sampling_unit,pp.sampler_userid,pp.sample_date,u.department_name,users.name_surname");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

else if($islem=="sql_otomatik_alim_checkbox"){
    $git = $_POST['barkod_no'];
    $barkod_numara = trim($git);
    unset($_POST['barkod_no']);
    $kontrol_et=tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_numara'");

    if ($kontrol_et["sampling_confirmation"]==0){
        $user=tek("select * from users where users.id='$kullanici_id'");
        $birim_id=$user["department"];
        $unit=tek("select * from units where units.id='$birim_id'");
        $kullanici_birim = $unit["id"];

        $_POST["sample_date"] = $simdikitarih;
        $_POST["sampler_userid"] = $kullanici_id;
        $_POST["sampling_unit"] = $kullanici_birim;
        $_POST["sampling_confirmation"] = 1;

        $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
        var_dump($sql);
        unset($_POST["sample_date"]);
        unset($_POST["sampler_userid"]);
        unset($_POST["sampling_unit"]);
        unset($_POST["sampling_confirmation"]);

        if ($sql==1){?>

            <script>
                alertify.success('Numune Alma İşlemi Başarılı.');
            </script>

        <?php }
        else {?>
            <script>
                alertify.error('Numune Alma İşlemi Başarısız.');
            </script>
        <?php }
    } else { ?>
        <script>
            alertify.warning('Numune Daha Önce Alınmıştır');
        </script>
    <?php }
}


else if ($islem=="sql_btn_numune_al"){
    $git = $_POST['barkod_no'];
    $barkod_numara = trim($git);
    unset($_POST['barkod_no']);

    $user=tek("select * from users where users.id='$kullanici_id'");
    $birim_id=$user["department"];
    $unit=tek("select * from units where units.id='$birim_id'");
    $kullanici_birim = $unit["id"];

    $_POST["sample_date"] = $simdikitarih;
    $_POST["sampler_userid"] = $kullanici_id;
    $_POST["sampling_unit"] = $kullanici_birim;
    $_POST["sampling_confirmation"] = 1;

    $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
    var_dump($sql);
    unset($_POST["sample_date"]);
    unset($_POST["sampler_userid"]);
    unset($_POST["sampling_unit"]);
    unset($_POST["sampling_confirmation"]);

    if ($sql==1){?>

        <script>
            alertify.success('Numune Alma İşlemi Başarılı.');
        </script>

    <?php }
    else {?>
        <script>
            alertify.error('Numune Alma İşlemi Başarısız.');
        </script>
    <?php }
}

else if ($islem=="sql_btn_numune_alim_iptal"){
    $git = $_POST['barkod_no'];
    $barkod_numara = trim($git);
    unset($_POST['barkod_no']);

    $user=tek("select * from users where users.id='$kullanici_id'");
    $birim_id=$user["department"];
    $unit=tek("select * from units where units.id='$birim_id'");
    $kullanici_birim = $unit["id"];

    $_POST["sample_date"] = null;
    $_POST["sampler_userid"] = null;
    $_POST["sampling_unit"] = null;
    $_POST["sampling_confirmation"] = 0;

    $_POST["sampling_cancellation_userid"] = $kullanici_id;
    $_POST["sampling_cancellation_date"] = $simdikitarih;

    $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
    var_dump($sql);
    unset($_POST["sample_date"]);
    unset($_POST["sampler_userid"]);
    unset($_POST["sampling_unit"]);
    unset($_POST["sampling_confirmation"]);
    unset($_POST["sampling_cancellation_userid"]);
    unset($_POST["sampling_cancellation_date"]);

    if ($sql==1){?>

        <script>
            alertify.success('Numune Alma İşlemi İptal Edildi.');
        </script>

    <?php }
    else {?>
        <script>
            alertify.error('Numune Alma İşlemi İptal Edilemedi.');
        </script>
    <?php }
}

else if($islem=="sql_ret_nedenleri"){
    $sql = verilericoklucek("select * from lab_definitions where definition_type='HAZIR_DEGER'");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
}

else if($islem=="sql_numune_reddet"){

    $git = $_POST['barkod_no'];
    $barkod_numara = trim($git);
    unset($_POST['barkod_no']);

    $_POST["sample_rejection_date"] = $simdikitarih;
    $_POST["sample_rejection_userid"] = $kullanici_id;
    $_POST["sampling_confirmation"] = 2;

    $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
    var_dump($sql);

    unset($_POST["sample_rejection_date"]);
    unset($_POST["sample_rejection_userid"]);
    unset($_POST["sampling_confirmation"]);

    if ($sql==1){?>

        <script>
            alertify.success('Numune Reddedildi.');
        </script>

    <?php }
    else {?>
        <script>
            alertify.error('Numune Ret İşlemi Başarısız.');
        </script>
    <?php }

}

else if($islem=="sql_numune_reddet_iptal"){

    $git = $_POST['barkod_no'];
    $barkod_numara = trim($git);
    unset($_POST['barkod_no']);


    $kontrol_et=tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_numara'");

    if ($kontrol_et["sampler_userid"]!=null){

        $_POST["sample_rejection_cancellation_date"] = $simdikitarih;
        $_POST["sample_rejection_cancel_userid"] = $kullanici_id;
        $_POST["sampling_confirmation"] = 1;

        $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
        var_dump($sql);

        unset($_POST["sample_rejection_cancellation_date"]);
        unset($_POST["sample_rejection_cancel_userid"]);
        unset($_POST["sampling_confirmation"]);

        if ($sql==1){?>

            <script>
                alertify.success('Numune Ret İptal İşlemi Başarılı.');
            </script>

        <?php }
        else {?>
            <script>
                alertify.error('Numune Ret İptal İşlemi Başarısız.');
            </script>
        <?php }

    }else{

        $_POST["sample_rejection_cancellation_date"] = $simdikitarih;
        $_POST["sample_rejection_cancel_userid"] = $kullanici_id;
        $_POST["sampling_confirmation"] = 0;

        $sql=direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_numara, $_POST);
        var_dump($sql);

        unset($_POST["sample_rejection_cancellation_date"]);
        unset($_POST["sample_rejection_cancel_userid"]);
        unset($_POST["sampling_confirmation"]);

        if ($sql==1){?>

            <script>
                alertify.success('Numune Ret İptal İşlemi Başarılı.');
            </script>

        <?php }
        else {?>
            <script>
                alertify.error('Numune Ret İptal İşlemi Başarısız.');
            </script>
        <?php }

    }
}

else if($islem=="sql_hastanin_gecmis_numune_listesi"){

     $git= $_GET['barkod_no'];
    $barkod_num = trim($git);

    $patient_prompts=tek("select * from patient_prompts where service_requests_bardoce='$barkod_num'");
    $patient_id=$patient_prompts["patient_id"];



    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sampling_unit,
                pp.sampler_userid,
                pp.sample_date,
                u.department_name,
                users.name_surname
from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_test_groups as ltg on ltg.id = la.test_group
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         inner join units as u on u.id=pp.sampling_unit
         left join users on users.id=pp.sampler_userid
where pp.patient_id = '$patient_id' and pp.sampling_confirmation = 1 and pp.prompts_type= 1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce,pp.sampling_unit,pp.sampler_userid,pp.sample_date,u.department_name,users.name_surname");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }

}


else if($islem=="sql_hasta_gesmi_numune_filtre"){

    $git = $_GET['barkod_no'];
    $barkod_num = trim($git);
    $baslangic_tarihi = $_GET['baslangic_tarihi'];
    $bitis_tarihi = $_GET['bitis_tarihi'];

    $patient_prompts=tek("select * from patient_prompts where service_requests_bardoce='$barkod_num'");
    $patient_id=$patient_prompts["patient_id"];



    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sampling_unit,
                pp.sampler_userid,
                pp.sample_date,
                u.department_name,
                users.name_surname
from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_test_groups as ltg on ltg.id = la.test_group
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         inner join units as u on u.id=pp.sampling_unit
         inner join users on users.id=pp.sampler_userid
where DATE(pp.sample_date) between '$baslangic_tarihi' and '$bitis_tarihi' and  pp.patient_id = '$patient_id' and pp.sampling_confirmation = 1 and pp.prompts_type= 1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce,pp.sampling_unit,pp.sampler_userid,pp.sample_date,u.department_name,users.name_surname");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }

}
?>