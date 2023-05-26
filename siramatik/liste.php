<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
ob_start();
session_start();
$BIRIM_ID=$_SESSION["birim_id"];
//var_dump($BIRIM_ID);
?>

<?php $saybakalim=kayitsayisigetir("patient_registration","doctor",$_SESSION["id"]); ?>
<div id="Repeat_Grid_1"><marquee scrollamount="8"  width="100%" direction="up"  style=" margin-top: -<?php echo $saybakalim*20; ?>px;height: 1570px;">
        <?php  $ilk=0;
        $hastalistgetir = "SELECT patient_registration.*,
                           patients.*,
                           units.*,
                           users.*,
                           patient_registration.id AS HASTAKAYITID,
                           patients.patient_name as hasta_adi,
                           patients.patient_surname as hasta_soyadi
                    FROM patient_registration
                             left JOIN patients ON patient_registration.tc_id = patients.tc_id
                             left JOIN users ON users.id = patient_registration.doctor
                             left JOIN units ON units.id = patient_registration.outpatient_id  $yetkilioldugupoliklinikler AND patient_registration.outpatient_id='$BIRIM_ID'
                    ORDER BY HASTAKAYITID ASC";

        $sql=verilericoklucek($hastalistgetir);
        foreach ($sql as $rowa) { ?>

            <div id="Group_1" class="Group_1"  style=" top: <?php echo $ilk; ?>px; ">
                <svg class="Rectangle_12">
                    <rect id="Rectangle_12" rx="3" ry="3" x="0" y="0" width="100%" height="100%">
                    </rect>
                </svg>
                <div id="SIRADA_HASTALAR">
                    <span><?php echo $rowa["row_number"] ?>- <?php echo $rowa["hasta_adi"]." ".$rowa["hasta_soyadi"] ?></span>
                </div>
            </div>

            <?php $ilk=$ilk+75; } ?>
    </marquee>
</div>