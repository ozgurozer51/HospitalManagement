<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$tarih = date("Y-m-d");
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];

if ($islem == "numune_kabul_numune_tup_listesi") {

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sample_date,
                pp.sample_acceptance_status,
                pp.sample_acceptance_date,
                pp.sample_accepting_userid,
                pp.sample_acceptance_rejection_date,
                pp.sample_acceptance_rejection_detail,
                pp.sample_acceptance_rejection_userid,
                pp.protocol_number,
                us.name_surname as ret_eden_kullanici,
                users.name_surname as kabul_eden_kullanici,
                u_ser.name_surname as numune_alan_kullanici,
                ld_ret.definition_name as ret_nedeni,
                pp.sample_conditional_acceptance_description as sartli_kabul_aciklama,
                us_er.name_surname as sartli_kabul_eden_kullanici
from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_test_groups as ltg on ltg.id = la.test_group
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         inner join units as u on u.id=pp.sampling_unit
         inner join users as u_ser on u_ser.id=pp.sampler_userid
         left join users as us_er on us_er.id=pp.sample_conditional_admission_userid
         left join users as us on us.id=pp.sample_acceptance_rejection_userid
         left join users on users.id=pp.sample_accepting_userid
         left join lab_definitions as ld_ret on ld_ret.id = pp.sample_acceptance_rejection_detail
where pp.sampling_confirmation = 1 and pp.prompts_type= 1 and DATE(pp.sample_date)='$tarih' and pp.status=1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce, pp.sample_date, pp.sample_acceptance_status, pp.sample_acceptance_date, pp.sample_accepting_userid, pp.sample_acceptance_rejection_date, pp.sample_acceptance_rejection_userid,pp.protocol_number, pp.sample_acceptance_rejection_detail, us.name_surname, users.name_surname, u_ser.name_surname, ld_ret.definition_name, ret_nedeni,sartli_kabul_aciklama,sartli_kabul_eden_kullanici");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "numune_kabul_numune_tup_tarih_filtre") {

    $tarih1 = $_GET['tarih1'];
    $tarih2 = $_GET['tarih2'];

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sample_date,
                pp.sample_acceptance_status,
                pp.sample_acceptance_date,
                pp.sample_accepting_userid,
                pp.sample_acceptance_rejection_date,
                pp.sample_acceptance_rejection_detail,
                pp.sample_acceptance_rejection_userid,
                pp.protocol_number,
                us.name_surname as ret_eden_kullanici,
                users.name_surname as kabul_eden_kullanici,
                u_ser.name_surname as numune_alan_kullanici,
                ld_ret.definition_name as ret_nedeni,
                pp.sample_conditional_acceptance_description as sartli_kabul_aciklama,
                us_er.name_surname as sartli_kabul_eden_kullanici
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
    inner join units as u on u.id=pp.sampling_unit
    inner join users as u_ser on u_ser.id=pp.sampler_userid
    left join users as us_er on us_er.id=pp.sample_conditional_admission_userid
    left join users as us on us.id=pp.sample_acceptance_rejection_userid
    left join users on users.id=pp.sample_accepting_userid
    left join lab_definitions as ld_ret on ld_ret.id = pp.sample_acceptance_rejection_detail
where DATE(pp.sample_date) between '$tarih1' and '$tarih2' and pp.sampling_confirmation = 1 and pp.prompts_type= 1  and pp.status=1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce, pp.sample_date, pp.sample_acceptance_status, pp.sample_acceptance_date, pp.sample_accepting_userid, pp.sample_acceptance_rejection_date, pp.sample_acceptance_rejection_userid,pp.protocol_number, pp.sample_acceptance_rejection_detail, us.name_surname, users.name_surname, u_ser.name_surname,ret_nedeni,sartli_kabul_aciklama,sartli_kabul_eden_kullanici");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "numune_kabul_numune_tup_durum_filtre") {

    $kabul_durum = $_GET['kabul_durum'];
    $tarih1 = $_GET['tarih1'];
    $tarih2 = $_GET['tarih2'];

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sample_date,
                pp.sample_acceptance_status,
                pp.sample_acceptance_date,
                pp.sample_accepting_userid,
                pp.sample_acceptance_rejection_date,
                pp.sample_acceptance_rejection_detail,
                pp.sample_acceptance_rejection_userid,
                pp.protocol_number,
                us.name_surname as ret_eden_kullanici,
                users.name_surname as kabul_eden_kullanici,
                u_ser.name_surname as numune_alan_kullanici,
                ld_ret.definition_name as ret_nedeni,
                pp.sample_conditional_acceptance_description as sartli_kabul_aciklama,
                us_er.name_surname as sartli_kabul_eden_kullanici
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
    inner join units as u on u.id=pp.sampling_unit
    inner join users as u_ser on u_ser.id=pp.sampler_userid
    left join users as us_er on us_er.id=pp.sample_conditional_admission_userid
    left join users as us on us.id=pp.sample_acceptance_rejection_userid
    left join users on users.id=pp.sample_accepting_userid
    left join lab_definitions as ld_ret on ld_ret.id = pp.sample_acceptance_rejection_detail
where DATE(pp.sample_date) between '$tarih1' and '$tarih2' and pp.sample_acceptance_status='$kabul_durum' and pp.sampling_confirmation = 1 and pp.prompts_type= 1  and pp.status=1
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce, pp.sample_date, pp.sample_acceptance_status, pp.sample_acceptance_date, pp.sample_accepting_userid, pp.sample_acceptance_rejection_date, pp.sample_acceptance_rejection_userid,pp.protocol_number, pp.sample_acceptance_rejection_detail, us.name_surname, users.name_surname, u_ser.name_surname,ret_nedeni,sartli_kabul_aciklama,sartli_kabul_eden_kullanici");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "numune_kabul_numune_tup_barkoda_gore") {

    $barkod = $_GET['barkod_num'];
    unset($_GET['barkod_num']);

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name,
                pp.service_requests_bardoce,
                pp.sample_date,
                pp.sample_acceptance_status,
                pp.sample_acceptance_date,
                pp.sample_accepting_userid,
                pp.sample_acceptance_rejection_date,
                pp.sample_acceptance_rejection_detail,
                pp.sample_acceptance_rejection_userid,
                pp.protocol_number,
                us.name_surname                              as ret_eden_kullanici,
                users.name_surname                           as kabul_eden_kullanici,
                u_ser.name_surname                           as numune_alan_kullanici,
                ld_ret.definition_name                       as ret_nedeni,
                pp.sample_conditional_acceptance_description as sartli_kabul_aciklama,
                us_er.name_surname                           as sartli_kabul_eden_kullanici
from patient_prompts as pp
         inner join lab_analysis as la on la.sut_code = pp.request_code
         inner join lab_test_groups as ltg on ltg.id = la.test_group
         inner join lab_definitions as ld on ld.id = la.tube_container_type
         inner join users as u_ser on u_ser.id = pp.sampler_userid
         left join users as us on us.id = pp.sample_acceptance_rejection_userid
         left join users as us_er on us_er.id = pp.sample_conditional_admission_userid
         left join users on users.id = pp.sample_accepting_userid
         left join lab_definitions as ld_ret on ld_ret.id = pp.sample_acceptance_rejection_detail
where pp.sampling_confirmation = 1
  and pp.prompts_type = 1
  and pp.status = 1
  and pp.service_requests_bardoce = '$barkod'
group by la.tube_container_type, ld.definition_name, pp.service_requests_bardoce, pp.sample_date,
         pp.sample_acceptance_status, pp.sample_acceptance_date, pp.sample_accepting_userid,
         pp.sample_acceptance_rejection_date, pp.sample_acceptance_rejection_userid, pp.protocol_number,
         pp.sample_acceptance_rejection_detail, us.name_surname, users.name_surname, u_ser.name_surname, ret_nedeni,
         sartli_kabul_aciklama, sartli_kabul_eden_kullanici");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "sql_numune_kabul_tetkik") {
    $barkod_no = $_GET['barkod_num'];

    $sql = verilericoklucek("select * from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
where pp.service_requests_bardoce = '$barkod_no' and pp.prompts_type = 1");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "sql_numune_kabul") {

    $git = $_POST['barkod_num'];
    $barkod_num = trim($git);
    unset($_POST['barkod_num']);

    $kontrol_et = tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_num'");

    if ($kontrol_et > 0) {

        if ($kontrol_et["sample_acceptance_status"] == 0) {

            $_POST["sample_acceptance_date"] = $simdikitarih;
            $_POST["sample_accepting_userid"] = $kullanici_id;
            $_POST["sample_acceptance_status"] = 1;
            $_POST["sample_acceptance_cancellation_date"] = null;
            $_POST["sample_acceptance_cancel_userid"] = null;

            $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);
            var_dump($sql);
            unset($_POST["sample_acceptance_date"]);
            unset($_POST["sample_accepting_userid"]);
            unset($_POST["sample_acceptance_status"]);
            unset($_POST["sample_acceptance_cancellation_date"]);
            unset($_POST["sample_acceptance_cancel_userid"]);

            if ($sql == 1) {
                ?>

                <script>
                    alertify.success('Numune Kabul İşlemi Başarılı.');
                </script>

            <?php } else { ?>

                <script>
                    alertify.error('Numune Kabul İşlemi Başarısız.');
                </script>

            <?php }
        } else { ?>

            <script>
                alertify.warning('Numune Daha Önce Kabul Edilmiştir');
            </script>

        <?php }
    } else {
        ?>


    <?php }

} else if ($islem == "sql_numune_kabul_iptal") {

    $barkod_num = $_POST['barkod_num'];
    unset($_POST['barkod_num']);

    $kontrol_et = tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_num'");

    if ($kontrol_et["sample_acceptance_status"] == 1) {

        $_POST["sample_acceptance_cancellation_date"] = $simdikitarih;
        $_POST["sample_acceptance_cancel_userid"] = $kullanici_id;
        $_POST["sample_acceptance_date"] = null;
        $_POST["sample_accepting_userid"] = null;
        $_POST["sample_conditional_admission_userid"] = null;
        $_POST["sample_conditional_acceptance_description"] = null;
        $_POST["sample_acceptance_status"] = 0;

        $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);
        var_dump($sql);
        unset($_POST["sample_acceptance_cancellation_date"]);
        unset($_POST["sample_acceptance_cancel_userid"]);
        unset($_POST["sample_acceptance_date"]);
        unset($_POST["sample_accepting_userid"]);
        unset($_POST["sample_acceptance_status"]);
        unset($_POST["sample_conditional_admission_userid"]);
        unset($_POST["sample_conditional_acceptance_description"]);

        if ($sql == 1) {
            ?>

            <script>
                alertify.success('Numune Kabul İptal İşlemi Başarılı.');
            </script>

        <?php } else { ?>

            <script>
                alertify.error('Numune Kabul İptal İşlemi Başarısız.');
            </script>

        <?php }

    } else { ?>

        <script>
            alertify.warning('Numune Daha Önce Kabul Edilmiştir');
        </script>

    <?php }

} else if ($islem == "sql_ret_nedeni") {
    $sql = verilericoklucek("select * from lab_definitions where definition_type='HAZIR_DEGER'");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
} else if ($islem == "sql_numune_kabul_tup_bilgi") {
    $barkod_num = $_GET['tup_barkod_numarasi'];
    unset($_POST['tup_barkod_numarasi']);

    $sql = verilericoklucek("select distinct la.tube_container_type,
                ld.definition_name as tup_adi,
                pp.service_requests_bardoce as tup_barkod,
                pp.insert_datetime as istem_tarihi,
                ltg.group_name as tup_grubu,
                u.name_surname as istem_yapan_kullanici
from patient_prompts as pp
    inner join lab_analysis as la on la.sut_code = pp.request_code
    inner join lab_test_groups as ltg on ltg.id = la.test_group
    inner join lab_definitions as ld on ld.id = la.tube_container_type
    inner join users as u on u.id = pp.request_userid
where pp.service_requests_bardoce = '$barkod_num' and pp.prompts_type = 1
group by la.tube_container_type, tup_barkod, tup_adi, istem_tarihi, tup_grubu, istem_yapan_kullanici");

    if ($sql > 0) {
        echo json_encode($sql);
    } else {
        echo 2;
    }
} else if ($islem == "sql_numune_kabul_reddet") {

    $barkod_num = $_POST['barkod_num'];
    unset($_POST['barkod_num']);

    $_POST["sample_acceptance_rejection_date"] = $simdikitarih;
    $_POST["sample_acceptance_rejection_userid"] = $kullanici_id;
    $_POST["sample_acceptance_status"] = 2;

    $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);
    var_dump($sql);

    unset($_POST["sample_acceptance_rejection_date"]);
    unset($_POST["sample_acceptance_rejection_userid"]);
    unset($_POST["sample_acceptance_status"]);

    if ($sql == 1) {
        ?>

        <script>
            alertify.success('Numune Reddedildi.');
        </script>

    <?php } else { ?>

        <script>
            alertify.error('Numune Ret İşlemi Başarısız.');
        </script>

    <?php }

} else if ($islem == "sql_numune_kabul_ret_iptal") {

    $barkod_num = $_POST['barkod_num'];
    unset($_POST['barkod_num']);

    $kontrol_et = tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_num'");

    if ($kontrol_et["sample_accepting_userid"] != null) {

        $_POST["sample_acceptance_rejection_cancellation_date"] = $simdikitarih;
        $_POST["sample_accept_rejection_cancel_userid"] = $kullanici_id;
        $_POST["sample_acceptance_status"] = 1;
        $_POST["sample_acceptance_rejection_date"] = null;
        $_POST["sample_acceptance_rejection_userid"] = null;
        $_POST["sample_acceptance_rejection_detail"] = null;

        $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);

        unset($_POST["sample_acceptance_rejection_cancellation_date"]);
        unset($_POST["sample_accept_rejection_cancel_userid"]);
        unset($_POST["sample_acceptance_status"]);
        unset($_POST["sample_acceptance_rejection_date"]);
        unset($_POST["sample_acceptance_rejection_userid"]);
        unset($_POST["sample_acceptance_rejection_detail"]);

        if ($sql == 1) {
            ?>

            <script>
                alertify.success('Numune Reddedildi.');
            </script>

        <?php } else { ?>

            <script>
                alertify.error('Numune Ret İşlemi Başarısız.');
            </script>

        <?php }

    } else {

        $_POST["sample_acceptance_rejection_cancellation_date"] = $simdikitarih;
        $_POST["sample_accept_rejection_cancel_userid"] = $kullanici_id;
        $_POST["sample_acceptance_status"] = 0;
        $_POST["sample_acceptance_rejection_date"] = null;
        $_POST["sample_acceptance_rejection_userid"] = null;
        $_POST["sample_acceptance_rejection_detail"] = null;

        $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);

        unset($_POST["sample_acceptance_rejection_cancellation_date"]);
        unset($_POST["sample_accept_rejection_cancel_userid"]);
        unset($_POST["sample_acceptance_status"]);
        unset($_POST["sample_acceptance_rejection_date"]);
        unset($_POST["sample_acceptance_rejection_userid"]);
        unset($_POST["sample_acceptance_rejection_detail"]);

        if ($sql == 1) {
            ?>

            <script>
                alertify.success('Numune Ret İptal İşlemi Başarılı');
            </script>

        <?php } else { ?>

            <script>
                alertify.error('Numune Ret İptal İşlemi Başarısız.');
            </script>

        <?php }

    }

} else if ($islem == "numune_sartli_kabul_doktor") {

    $sql = verilericoklucek("select u.id,
       u.name_surname
from users as u
                  inner join authority_of_pool as aop on  u.id=aop.userid
                  inner join authority_definition as ad on aop.authority_id=ad.id
where aop.status=1 and ad.authority='laboratuvar_numune_kabul'
group by u.id,u.name_surname, u.id");

    if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }

} else if ($islem == "sql_numune_sartli_kabul_et") {

    $barkod_num = $_POST['barkod_num'];
    $sartli_kabul_aciklama = $_POST['sample_conditional_acceptance_description'];
    $sartli_kabul_kullanici_id = $_POST['sample_conditional_admission_userid'];
    unset($_POST['barkod_num']);
    unset($_POST['sample_conditional_admission_userid']);
    unset($_POST['sample_conditional_acceptance_description']);

    $kontrol_et = tek("select * from patient_prompts as pp where pp.service_requests_bardoce='$barkod_num'");

    if ($kontrol_et["sample_acceptance_status"] == 0) {

        $_POST["sample_acceptance_date"] = $simdikitarih;
        $_POST["sample_conditional_admission_userid"] = $sartli_kabul_kullanici_id;
        $_POST["sample_conditional_acceptance_description"] = $sartli_kabul_aciklama;
        $_POST["sample_acceptance_status"] = 1;
        $_POST["sample_acceptance_cancellation_date"] = null;
        $_POST["sample_acceptance_cancel_userid"] = null;

        $sql = direktguncelle("patient_prompts", "service_requests_bardoce", $barkod_num, $_POST);
        var_dump($sql);
        unset($_POST["sample_acceptance_date"]);
        unset($_POST["sample_conditional_admission_userid"]);
        unset($_POST["sample_conditional_acceptance_description"]);
        unset($_POST["sample_acceptance_status"]);
        unset($_POST["sample_acceptance_cancellation_date"]);
        unset($_POST["sample_acceptance_cancel_userid"]);

        if ($sql == 1) {
            ?>

            <script>
                alertify.success('Şartlı Kabul İşlemi Başarılı.');
            </script>

        <?php } else { ?>

            <script>
                alertify.error('Şartlı Kabul İşlemi Başarısız.');
            </script>

        <?php }
    } else { ?>

        <script>
            alertify.warning('Numune Daha Önce Kabul Edilmiştir');
        </script>

    <?php }

}

?>