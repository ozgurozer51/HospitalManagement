<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$simdikitarih2 = date('Y/m/d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];


$islem = $_GET['islem'];

if ($islem == "yatisinsert") {
    if ($_POST) {
        $patient_id = $_POST["patient_id"];
        $_POST["insert_datetime"] = $tarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $yatak_id= $_POST["bed_id"];

        $yatissekle = direktekle("patient_registration", $_POST);
        var_dump($yatissekle);
        if ($yatissekle==1) {
           $sor=tek("select protocol_number from patient_registration where patient_id='$patient_id'   order by protocol_number desc");
           $sorislem=$sor['protocol_number'];
            $yatisislem = guncelle("UPDATE patient_registration SET hospitalization_info='Y' WHERE protocol_number='$sorislem'");
            var_dump($yatisislem);
            if ($yatisislem==1) {
                if ($yatak_id!=''){
                    $bedislem = guncelle("UPDATE hospital_bed SET full_status=3 WHERE id='$yatak_id'");
                    var_dump($bedislem);?>
                    <script>
                        alertify.success('Yatiş Talep işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.success('Yatiş Talep işlemi Başarili');
                    </script>
                <?php }
                }else{
                ?>
                <script>
                    alertify.success('Yatiş Talep işlemi Başarili');
                </script>
                <?php
                }

        } else {  ?>
            <script>
                alertify.danger('Yatiş Talep işlemi Başarisiz');
            </script>
        <?php }

    }
} elseif ($islem == "yatiskabul") {
    if ($_POST) {
        $_POST["yatisyap"]["hospitalized_accepted_datetime"] = $simdikitarih;
        $_POST["yatisyap"]["admission_start_date"] = $simdikitarih;
        $id = $_POST["yatisyap"]['id'];
        $hastakayityatakoda = singularactive("patient_registration", "protocol_number", $id);
        unset($_POST["yatisyap"]['id']);
        $_POST["yatisyap"]['hospitalization_demand']=2;


        $yatissekle = direktgroupguncelle("patient_registration", "protocol_number", $id, $_POST["yatisyap"], "yatisyap");
        var_dump('sonuc1:'.$yatissekle."<br>");
//        echo "<br/>";
        if ($yatissekle) {
            $hastakayit = singularactive("patient_registration", "protocol_number", $id);
//            echo "<pre>";
//            print_r($hastakayit);
//            echo "<br>";
            $yid = $hastakayit['bed_id'];
            $oid = $hastakayit['room_id'];
            $yid2 = $hastakayityatakoda['bed_id'];
            $oid2= $hastakayityatakoda['room_id'];
            $ihsan = guncelle("UPDATE hospital_bed SET full_status='1'  WHERE id='$yid'");
            var_dump('sonuc2:'.$ihsan."<br>");
            if ($yid!=$yid2 && $yid2!=''){
                $ihsany = guncelle("UPDATE hospital_bed SET full_status='0'  WHERE id='$yid2'");
                var_dump('sonuc3:'.$ihsany."<br>");
            }if ($oid!=$oid2 && $oid2!=''){
                $ihsano=guncelle("UPDATE hospital_room SET availability='2'  WHERE id='$oid2'");
                var_dump('sonuc4:'.$ihsano."<br>");
            }

//            echo "<br/>";
            $yataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND status='1'");
            $doluyataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND full_status='1' AND status='1'");

            if ($yataksayi == $doluyataksayi) {
                $islemsonuc=guncelle("UPDATE hospital_room SET availability='1'  WHERE id='$oid'");
                var_dump('sonuc5:'.$islemsonuc."<br>");
//                echo "<br/>";
            }
            $protokolno = $hastakayit['hospitalization_protocol'];
            $birimid = $hastakayit['service_id'];
            $birim = singularactive("units", "id", $birimid);
            $yatakislemid = $birim['bed_list_transferred_auto'];
            $islemdetay = singularactive("transaction_detail", "id", $yatakislemid);
            $_POST["hastaistem"]["request_date"] = $simdikitarih;
            $_POST["hastaistem"]["action_doer_userid"] = $_SESSION["id"];
            $_POST["hastaistem"]["request_userid"] = $hastakayit['service_doctor'];
            $_POST["hastaistem"]["patient_id"] = $hastakayit['patient_id'];
            $_POST["hastaistem"]["request_name"] = $birim['department_name'] . "_yatis";
            $_POST["hastaistem"]["piece"] = 1;
            $_POST["hastaistem"]["fee"] = $islemdetay['transaction_cost'];
            $_POST["hastaistem"]["doctor_id"] = $hastakayit['service_doctor'];
            $_POST["hastaistem"]["study_id"] = $hastakayit['id'];
            $_POST["hastaistem"]["protocol_number"] = $protokolno;

            $hasistem = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");
            var_dump('sonuc6:'.$hasistem."<br>");
            if ($hasistem){
                $yatakturid = $hastakayit["bed_type"];
                unset($_POST["hastaistem"]["request_name"]);
                unset($_POST["hastaistem"]["fee"]);
                $islemtanim = singularactive("transaction_definitions", "id", $yatakturid);
                $_POST["hastaistem"]["request_name"] = $birim['department_name'] . "_yatak_ucret";
                $_POST["hastaistem"]["fee"] = $islemtanim['definition_price'];
                $hasistem1 = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");
                var_dump('sonuc7:'.$hasistem1."<br>");
                if ($hasistem1) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('işlemi Başarisiz1');
                    </script>

                <?php }


            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz2');
                </script>
            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz3');
            </script>
        <?php }

    }
}

elseif ($islem == "figur_ekle") {
    if ($_GET['figurtur']==1){

        $figur=tek("select definition_name as figur_adi,definition_supplement as figur_adres from transaction_definitions where definition_type='figurler' and definition_code=1");
        $_POST["figur"]['protocol_number']=$_GET["protocol_number"];
        $_POST["figur"]['figure_name']=$figur["figur_adi"];
        $_POST["figur"]['figure_file']=$figur["figur_adres"];

        $figur = groupdirektekle("patient_figure", $_POST['figur'],"", "figur");
    }elseif ($_GET['figurtur']==2){
        $figur=tek("select definition_name as figur_adi,definition_supplement as figur_adres from transaction_definitions where definition_type='figurler' and definition_code=2");
        $_POST["figur"]['protocol_number']=$_GET["protocol_number"];
        $_POST["figur"]['figure_name']=$figur["figur_adi"];
        $_POST["figur"]['figure_file']=$figur["figur_adres"];

        $figur = groupdirektekle("patient_figure", $_POST['figur'],"", "figur");
    }elseif ($_GET['figurtur']==3){
        $figur=tek("select definition_name as figur_adi,definition_supplement as figur_adres from transaction_definitions where definition_type='figurler' and definition_code=3");
        $_POST["figur"]['protocol_number']=$_GET["protocol_number"];
        $_POST["figur"]['figure_name']=$figur["figur_adi"];
        $_POST["figur"]['figure_file']=$figur["figur_adres"];

        $figur = groupdirektekle("patient_figure", $_POST['figur'],"", "figur");
    }

    var_dump($figur);
    if ($figur) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php } else { ?>
        <script>
            alertify.error('işlemi Başarisiz');
        </script>

    <?php }
}

elseif ($islem == "transferyatiskabul") {
    if ($_POST) {

        $_POST["yatisyap"]["hospitalized_accepted_datetime"] = $simdikitarih;
        $_POST["yatisyap"]["hospitalized_accepted_userid"] = $_SESSION["id"];
        $id = $_POST["yatisyap"]['id'];
        unset($_POST["yatisyap"]['id']);
        $_POST["yatisyap"]['hospitalization_demand'] =2;
//oda yatak başlangiç
        $yatis2 = singularactive("patient_registration", "protocol_number", $id);
        $yatisp=$yatis2['hospitalization_protocol'];
        $yatis = singularactive("patient_registration", "protocol_number", $yatisp);

        $yid = $yatis['bed_id'];
        $oid = $yatis['room_id'];
        $yataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id='$oid' AND status='1'");
        $doluyataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id='$oid' AND full_status='1' AND status='1'");

        if ($yataksayi == $doluyataksayi) {
            $odabosalt=guncelle("UPDATE hospital_room SET availability=0  WHERE id='{$oid}'");
            var_dump("hasta oda boşaltma: ".$odabosalt);
            echo '<br>';
            if ($odabosalt==1) { ?>
                <script>
                    alertify.success('Oda boşaltma işlemi Başarili');
                </script>
            <?php } else {
                ?>
                <script>
                    alertify.error('Oda boşaltma işlemi Başarisiz');
                </script>
            <?php }
        }

        $ihsan = guncelle("UPDATE hospital_bed SET full_status='0'  WHERE id='{$yid}'");
       var_dump("hasta yatak boşaltma: ".$ihsan);
        echo '<br>';
        if ($ihsan==1) { ?>
            <script>
                alertify.success('Yatak boşaltma işlemi Başarili');
            </script>
        <?php } else {
            ?>
            <script>
                alertify.error('Yatak boşaltma işlemi Başarisiz');
            </script>
        <?php }
        $ihsan1 = guncelle("UPDATE patient_registration SET transfer_status='2',transfer_datetime='',admission_end_date='$tarih',hospitalization_demand=3 WHERE id='{$yatis['hospitalization_protocol']}'");
        var_dump("hasta transfer kabul: ".$ihsan1);
        echo '<br>';
        if ($ihsan1==1) { ?>
            <script>
                alertify.success('Yatak bitiş işlemi Başarili');
            </script>
        <?php } else {
            ?>
            <script>
                alertify.error('Yatak bitiş işlemi Başarisiz');
            </script>
        <?php }
        ///oda yatak bitiş
        $yatissekle = direktgroupguncelle("patient_registration", "protocol_number", $id, $_POST["yatisyap"], "yatisyap");
        var_dump("hasta kabul: ".$yatissekle);
        echo '<br>';
        //var_dump("yatis guncelle: ".$yatissekle."<br/>");
       // var_dump($_POST);
       // echo "<br/>";

        if ($yatissekle==1) { ?>
            <script>
                alertify.success('Yatak  işlemi Başarili');
            </script>
        <?php } else {
            ?>
            <script>
                alertify.error('Yatak  işlemi Başarisiz');
            </script>
        <?php }
        if ($yatissekle) {
            $yatisid = singularactive("patient_registration", "protocol_number", $id);
            $yid = $yatisid['bed_id'];
            $oid = $yatisid['room_id'];
            $ihsan = guncelle("UPDATE hospital_bed SET full_status='1'  WHERE id='{$yid}'");
            var_dump("yatak doldurma : ".$ihsan);
            echo '<br>';
            if ($ihsan==1) { ?>
                <script>
                    alertify.success('Yatak doldurma işlemi Başarili');
                </script>
            <?php } else {
                ?>
                <script>
                    alertify.error('Yatak doldurma işlemi Başarisiz');
                </script>
            <?php }

            $yataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND status='1'");
            $doluyataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND full_status='1' AND status='1'");
            $yatis = singular("patient_registration", "protocol_number", $id);
            if ($yataksayi == $doluyataksayi) {
                $ihsan1=guncelle("UPDATE hospital_room SET availability='1'  WHERE id='{$yatisid['room_id']}'");
               var_dump("oda doldurma : ".$ihsan);
                echo '<br>';
                if ($ihsan1==1) { ?>
                    <script>
                        alertify.success('oda doldurma işlemi Başarili');
                    </script>
                <?php } else {
                    ?>
                    <script>
                        alertify.error('oda doldurma işlemi Başarisiz');
                    </script>
                <?php }
            }

//            $baslangic = $yatis['YATiS_BASLANGiC_TARiHi'];
//            $bitis = $yatis['YATiS_BiTiS_TARiHi'];

            $hastakayit = singularactive("patient_registration", "protocol_number", $id);
            $birimid = $hastakayit['service_id'];
            $birim = singularactive("units","id",$birimid);

            $yatakislemid = $birim['bed_list_transferred_auto'];
            $islemdetay = singularactive("transaction_detail", "id", $yatakislemid);

            $_POST["hastaistem"]["request_date"] = $simdikitarih;
            $_POST["hastaistem"]["action_doer_userid"] = $_SESSION["id"];
            $_POST["hastaistem"]["request_userid"] = $hastakayit['doctor'];
            $_POST["hastaistem"]["patient_id"] = $hastakayit['patient_id'];
            $_POST["hastaistem"]["request_name"] = $birim['depatrment_name'] . "_yatis";
//            $baslangicTarihi = strtotime($baslangic);
//            $bitisTarihi = strtotime($bitis);
//            $fark = ($bitisTarihi - $baslangicTarihi) / 86400;
            //var_dump($islemdetay);
            $_POST["hastaistem"]["piece"] = 1;
            $_POST["hastaistem"]["fee"] = $islemdetay['transaction_cost'];
            $_POST["hastaistem"]["doctor_id"] = $hastakayit['doctor'];
            $_POST["hastaistem"]["study_id"] = $id;
            $_POST["hastaistem"]["protocol_number"] = $id;

            $hasistem = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");
           var_dump("hasta istem : ".$hasistem."<br/>");
            echo '<br>';
             //var_dump($_POST);
             //echo "<br/>";
            if ($hasistem==1) {

                $yatakturid = $_POST["yatisyap"]["bed_type"];
                $islemtanim = singular("transaction_definitions", "id", $yatakturid);


                $_POST["hastaistem"]["request_date"] = $simdikitarih;
                $_POST["hastaistem"]["action_doer_userid"] = $_SESSION["id"];
                $_POST["hastaistem"]["request_userid"] = $hastakayit['doctor'];
                $_POST["hastaistem"]["patient_id"] = $hastakayit['tc_id'];
                $_POST["hastaistem"]["request_name"] = $birim['depatrment_name'] . "_yatak_ucret";
//                $baslangicTarihi = strtotime($baslangic);
//
//                $bitisTarihi = strtotime($bitis);
//
//                $fark = ($bitisTarihi - $baslangicTarihi) / 86400;
                $_POST["hastaistem"]["piece"] = 1;
                $_POST["hastaistem"]["fee"] = $islemtanim['definition_price'];
                $_POST["hastaistem"]["doctor_id"] = $hastakayit['doctor'];
                $_POST["hastaistem"]["study_id"] = $id;
                $_POST["hastaistem"]["protocol_number"] = $id;
//var_dump($yatakturid);
//echo '<br/>';
                $hasistem1 = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");
               var_dump("<br/>"."hasta istem2 : ".$hasistem1);
                echo '<br>';
                if ($hasistem1==1) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else {
                     ?>
                    <script>
                        alertify.error('işlemi Başarisiz');
                    </script>

                <?php }


            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz');
                </script>
            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>


        <?php }

    }
}

elseif ($islem == "refakatinsert") {
    if ($_POST) {
        $_POST["refakatci"]["insert_datetime"] = $simdikitarih;
        $_POST["refakatci"]["insert_userid"] = $_SESSION["id"];
//        $tel=trim($_POST["refakatci"]["companion_phone"]);
       unset($_POST["refakatci"]["id"]);
//        $_POST["refakatci"]["companion_phone"]=$tel;

        $yatissekle = groupdirektekle("patient_companion", $_POST['refakatci'], "", "refakatci");
        var_dump($yatissekle);
        if ($yatissekle) {
            $yatisprotokol = $_POST["refakatci"]["patient_protocol_number"];
            $hastakayit = singular("patient_registration", "protocol_number", $yatisprotokol);
            $protokol_no = $hastakayit['protocol_number'];

            $birimid = $hastakayit['service_id'];
            $birim = singular("units", "id", $birimid);
            $yatakislemid = $birim['auto_transfer_accompanying'];
            $islemdetay = singular("transaction_detail", "id", $yatakislemid);

            $_POST["hastaistem"]["request_date"] = $simdikitarih;
            $_POST["hastaistem"]["action_doer_userid"] = $_SESSION["id"];
            $_POST["hastaistem"]["request_userid"] = $hastakayit['service_doctor'];
            $_POST["hastaistem"]["patient_id"] = $hastakayit['patient_id'];
            $_POST["hastaistem"]["request_name"] = $birim['department_name'] . "_patient_companion";
            $_POST["hastaistem"]["piece"] = 1;
            $_POST["hastaistem"]["fee"] = $islemdetay['transaction_cost'];
            $_POST["hastaistem"]["doctor_id"] = $hastakayit['service_doctor'];
            $_POST["hastaistem"]["study_id"] = $islemdetay['id'];
            $_POST["hastaistem"]["protocol_number"] = $protokol_no;

            $hasistem = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");
            var_dump($hasistem);
            if ($hasistem) { ?>
                <script>
                    alertify.success('Refakatçi ekleme işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.error('Refakatçi ekleme işlemi Başarisiz');
                </script>
            <?php } ?>


        <?php } else { ?>
            <script>
                alertify.error('Refakatçi ekleme işlemi Başarisiz');
            </script>
        <?php }

    }
}

elseif ($islem == "izininsert") {
    if ($_POST) {

        $yatissekle = groupdirektekle("patient_permission", $_POST['izin'], "", "izin");
        var_dump($yatissekle);
            if ($yatissekle){
                $protokol_no=$_POST['izin']['protocol_number'];
                $sonuc=guncelle("UPDATE patient_registration SET hospitalization_demand='5'  WHERE protocol_number={$protokol_no}");
                var_dump($sonuc);
                if ($sonuc) { ?>
                    <script>
                        alertify.success('izin ekleme işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('izin ekleme işlemi Başarisiz');
                    </script>
                <?php }
            }else { ?>
                <script>
                    alertify.error('izin ekleme işlemi Başarisiz');
                </script>
            <?php }


    }

}

elseif ($islem == "temizlikinsert") {
    if ($_POST) {

        $yatissekle = groupdirektekle("bed_cleaning", $_POST['temizlik'], "", "temizlik");
        var_dump($yatissekle);

        if ($yatissekle) { ?>
            <script>
                alertify.success(' ekleme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error(' ekleme işlemi Başarisiz');
            </script>
        <?php }

    }

}

elseif ($islem == "refakatciExit") {
        $sonuc=guncelle("UPDATE patient_companion SET exit_datetime='$simdikitarih',companion_status='1'  WHERE id={$_GET['refakatciid']}");
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('izin ekleme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('izin ekleme işlemi Başarisiz');
            </script>
        <?php }

}


elseif ($islem == "yatisislemup") {
    if ($_POST) {
        $id = $_POST['yatisyap']['id'];
        unset($_POST['yatisyap']['id']);
        $yatis = singularactive("patient_registration", "id", $id);

        $ihsan = guncelle("UPDATE hospital_bed SET full_status='0'  WHERE id='{$yatis['bed_id']}'");
        var_dump($ihsan);
        if ($ihsan==1) {
             ?>
            <script>
                alertify.success('Yatak Boşaltma işlemi Başarili');
            </script>
        <?php } else {
            ?>
            <script>
                alertify.error('Yatak Boşaltma işlem Başarisiz');
            </script>

        <?php }

        $yataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id={$yatis['room_id']} AND status='1'");
        $doluyataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id={$yatis['room_id']} AND full_status='1' AND status='1'");
        $hastakayit = singularactive("patient_registration", "id", $id);
        if ($yataksayi == $doluyataksayi) {
            $ihsan12= guncelle("UPDATE hospital_room SET availability='0'  WHERE id='{$yatis['room_id']}'");
            var_dump($ihsan12);
            if ($ihsan12==1) {
                ?>
                <script>
                    alertify.success('Oda Boşaltma işlemi Başarili');
                </script>
            <?php } else {
                ?>
                <script>
                    alertify.error('Oda Boşaltma işlem Başarisiz');
                </script>

            <?php }
        }

        $sonuc = direktgroupguncelle("patient_registration", "id", $id, $_POST["yatisyap"], "yatisyap");
        var_dump($sonuc);
        if ($sonuc==1) {
            $yatisid = singularactive("patient_registration", "id", $id);
            $yid = $yatisid['bed_id'];
            $oid = $yatisid['room_id'];
            $ihsan = guncelle("UPDATE hospital_bed SET full_status='1'  WHERE id='{$yatisid['bed_id']}'");
            var_dump($ihsan);
            if ($ihsan==1) { ?>
                <script>
                    alertify.success('yatak doldurma işlemi Başarili');
                </script>
            <?php } else {
                ?>
                <script>
                    alertify.error('yatak doldurma işlem Başarisiz');
                </script>

            <?php }

            $yataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND status='1'");
            $doluyataksayi = tek("SELECT COUNT(id) AS yataksayisi FROM hospital_bed WHERE room_id=$oid AND full_status='1' AND status='1'");
            $yatis = singularactive("patient_registration", "id", $id);
            if ($yataksayi == $doluyataksayi) {
                $sonuc=guncelle("UPDATE hospital_room SET availability='1'  WHERE id='{$yatisid['room_id']}'");
                var_dump($sonuc);
                if ($sonuc==1) { ?>
                    <script>
                        alertify.success('Oda doldurma işlemi Başarili');
                    </script>
                <?php } else {
                    ?>
                    <script>
                        alertify.error('Oda doldurma işlem Başarisiz');
                    </script>

                <?php }

            }

            ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else {
            var_dump($_POST['yatis']); ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "servisdoktorgetir") {
    $birimid = $_POST['birimid'];
    echo " <option value=''>Doktor seçiniz.." . "</option>";

    $hello = verilericoklucek('select * from users WHERE department=' . $birimid . 'ORDER BY name_surname');
    foreach ($hello as $value) {

        echo '<option value="' . $value['id'] . '">' . $value['name_surname'] . '</option>';

    }
} elseif ($islem == "btnevrakdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $evrak = singular("patients_document", "id", $id);
        $dosyayolu = '../' . $evrak['document_file_path'];
        $vezneguncelle = canceldetail("patients_document", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
} elseif ($islem == "evrakinsert") {

    for ($i = 0; $i < count($_FILES['hastaevrak']['name']); $i++) {
        $uzanti = $_FILES['hastaevrak']['type'][$i];
        if ($uzanti == "image/jpeg" || $uzanti == "image/jpg" || $uzanti == "image/png" || $uzanti == "application/pdf") {
            if ($_FILES['hastaevrak']['size'][$i] > 0) {
                if ($_FILES['hastaevrak']['size'][$i] > 1048576) {
                    echo '
                        <script>  alertify.error("Eklemeye çaliştiğiniz dosyanin boyutu sistemin izin verdiğinden daha büyük olduğu için ekleme işleminizi yapamadim. ");</script>';

                } else {
                   echo  $id = $_POST['protocol_id'];
                    echo '<br/>';
                    if (file_exists('../../hastaevraklari/'.$id)) {
                        //echo 'klasor oluşturmaya gerek yok';
                    } else {
                        $sonuc = mkdir('../../hastaevraklari/' . $id);
                    }

                    $uploads_dir = 'hastaevraklari/' . $id;
                    @$tmp_name = $_FILES['hastaevrak']["tmp_name"][$i];
                    @$name = $_FILES['hastaevrak']["name"][$i];

                    //resmin isminin benzersiz olmasi
                    $benzersizad = date('dmYHis');

                    $refimgyol = "../" . $uploads_dir . "/";

                    $yeniyol = $uploads_dir . "/";

                    if (move_uploaded_file($_FILES['hastaevrak']["tmp_name"][$i], "../../hastaevraklari/" . $id . "/" . $id . $benzersizad . $benzersizad . $name)) {
                        $_POST["document_file_path"] = $uploads_dir . '/' . $id . $benzersizad . $benzersizad . $name;
                        $_POST["status"] = "1";
                        $_POST["insert_datetime"] = date('Y-m-d H:i:s');


                        $kontrol = direktekle("patients_document", $_POST);
                        //var_dump($kontrol);
                        if ($kontrol) { ?>
                            <script>
                                alertify.success('işlemi Başarili');
                            </script>
                        <?php } else { ?>
                            <script>
                                alertify.error('işlemi Başarisiz1');
                            </script>

                        <?php }

                    } else { ?>
                        <script>
                            alertify.error('işlemi Başarisiz2');
                        </script>
                    <?php }
                    unset($_FILES['resimyol']);
                }

            }
        } else {
            echo "format hatasi..";
            var_dump($uzanti);
            echo ' <script>  alertify.error("Eklemeye çaliştiğiniz format sistem tarafindan desteklenmemektedir. Desteklenen formatlar PDF ,PNG  , JPG. Sizin eklemeye çaliştiğiniz format ' . $uzanti[1] . '");</script>';

        }


    }
} elseif ($islem == "odayatak") {
    $odaid = $_POST['odaid'];
    echo " <option value=''>" . "Yatak seçiniz.." . "</option>";

    $bolumgetir = 'select * from hospital_bed WHERE room_id=' . $odaid . 'AND status=1  ORDER BY bed_name';
     $hello=verilericoklucek($bolumgetir);
    foreach ($hello as $value){
     ?>
        <option value="<?php echo $value['id'] ?>" <?php if ($value['full_status'] == 1) {
            echo "disabled";
        } ?> > <?php echo $value['bed_name'] ?><?php if ($value['full_status'] == 1) {
                echo " (DOLU)";
            } ?> </option>';

    <?php }
} elseif ($islem == "taburcuhazirlikinsert") {
    if ($_POST) {

        $odaid = $_POST['room_id'];
        $_POST['discharge_status'] = 1;
        $yatakid = $_POST['bed_id'];
        unset($_POST['bed_id']);
        unset($_POST['transfer_date']);
        $yatisid = $_POST['yatisid'];
        unset($_POST['room_id']);
        unset($_POST['yatisid']);
        unset($_POST['id']);

        $yatissekle = direktekle("patient_discharge", $_POST);
        var_dump($yatissekle);
        if ($yatissekle) {
            //$ihsan = guncelle("UPDATE hospital_bed SET DOLULUK='1'  WHERE iD='{$yatisid['YATAK_KODU']}'");
           $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand='3',room_id='$odaid'  WHERE protocol_number='$yatisid'");
            var_dump($ihsan);
            if ($ihsan==1) { ?>
                <script>
                    alertify.success('işlemi Başarili');

                </script>
            <?php } else { ?>

            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz2');
            </script>
        <?php }

    }
} elseif ($islem == "transferinsert") {
    if ($_POST) {

        $odaid = $_POST['room_id'];
        $_POST['discharge_status'] = 1;
        $yatisid = $_POST['yatisid'];
        unset($_POST['room_id']);
        unset($_POST['yatisid']);
        unset($_POST['id']);
        $yatakid = $_POST['bed_id'];
        unset($_POST['bed_id']);


        $yatissekle = direktekle("patient_discharge", $_POST);
        var_dump("taburcuekle :".$yatissekle);
        if ($yatissekle) {

            $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand='6',room_id='{$odaid}',bed_id='{$yatakid}',admission_end_date='{$simdikitarih2}' WHERE protocol_number='{$yatisid}'");
            var_dump("yatisguncelle :".$ihsan);
            echo '<br>';
            echo 'veriler: '.$odaid.' '.$yatakid.' '.$simdikitarih2.'  '.$yatisid;
            echo '<br>';
            if ($ihsan) {
                $yatisprotokol = $_POST['admission_protocol'];
//                $dizi = explode("-", $yatisprotokol);
//                $sirasay = intval($dizi[1]) + 1;
//                $ytsprotokol = $dizi[0] . '-' .strval($sirasay);
//                $yatis = singular("yatis", "yatis_protokol", $yatisprotokol);
                $hastakayit = singular("patient_registration", "protocol_number", $yatisprotokol);

                $_POST["hastakayitlar"]["admission_start_date"] =$simdikitarih;
                $_POST["hastakayitlar"]["service_id"] = $_POST['transfer_service'];
                $_POST["hastakayitlar"]["service_doctor"] =$_POST['transfer_doctor'];
                $_POST["hastakayitlar"]["hospitalization_protocol"] =$yatisprotokol;
                $_POST["hastakayitlar"]["transfer_service"] =$_POST['inbound_transfer_service'];
                $_POST["hastakayitlar"]["transfer_doctor"] =$_POST['inbound_transfer_doctor'];

                $_POST["hastakayitlar"]["outpatient_id"] =$_POST['transfer_service'];
                $_POST["hastakayitlar"]["row_number"] = $hastakayit['row_number'];
                $_POST["hastakayitlar"]["provision_number"] =$hastakayit['provision_number'];
                $_POST["hastakayitlar"]["doctor"] =$_POST['transfer_doctor'];
                $_POST["hastakayitlar"]["patient_id"] =$hastakayit['patient_id'];
                $_POST["hastakayitlar"]["hospitalization_info"] ='Y';
                $_POST["hastakayitlar"]["hospitalization_demand"] ='1';
                $hasta_kayit_say =tek("select  MAX(protocol_number) as protokol  from patient_registration  ");
                $_POST["hastakayitlar"]["protocol_number"] =intval($hasta_kayit_say['protokol'])+1;

                    $ekle1 =groupdirektekle("patient_registration", $_POST['hastakayitlar'], "", "hastakayitlar");
                    var_dump("hastakayıtekle :".$ekle1);
                    if ($ekle1) {
                        $ihsan1 = guncelle("UPDATE patient_registration SET transfer_status='1',transfer_datetime='$tarih' WHERE id='{$hastakayit['id']}'");
                        var_dump("yatisguncelle2 :".$ihsan1);
                        if ($ihsan1) { ?>
                            <script>
                                alertify.success('işlemi Başarili');
                            </script>
                        <?php } else { ?>
                            <script>
                                alertify.error('işlemi Başarisiz1');
                            </script>

                        <?php }
                    } else { ?>
                        <script>
                            alertify.error('işlemi Başarisiz2');
                        </script>
                    <?php }

            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz4');
                </script>
            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz5');
            </script>
        <?php }

    }
} elseif ($islem == "taburcukaydetinsert") {
    if ($_POST) {
        $odaid = $_POST['room_id'];
        $_POST['discharge_status'] = 1;
        $yatisid = $_POST['yatisid'];
        $id = $_POST['id'];
        unset($_POST['room_id']);
        unset($_POST['transfer_date']);
        unset($_POST['yatisid']);
        unset($_POST['id']);
        $yatakid = $_POST['bed_id'];
        unset($_POST['bed_id']);

        $yatissekle = direktekle("patient_discharge", $_POST);
var_dump('ekleme_islem: '.$yatissekle);
        if ($yatissekle==1) {
            //$ihsan = guncelle("UPDATE hospital_bed SET DOLULUK='1'  WHERE iD='{$yatisid['YATAK_KODU']}'");
            $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand=4,room_id='$odaid',bed_id='$yatakid',admission_end_date='$simdikitarih'  WHERE protocol_number='$yatisid'");
            var_dump('durum_islem: '.$ihsan);
            if ($ihsan==1) {
                $ihsan1 = guncelle("UPDATE hospital_bed SET full_status='0'  WHERE id=$yatakid");
                var_dump('yatak_islem: '.$ihsan);
                if ($ihsan1==1) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('işlemi Başarisiz');
                    </script>

                <?php }
            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz');
                </script>
            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>
        <?php }

    }
} elseif ($islem == "taburcugerialma") {

    $yatakid = $_POST['bed_id'];
    unset($_POST['bed_id']);
    $odaid = $_POST['room_id'];
    $yatisid = $_POST['yatisid'];
    $user=$_SESSION['id'];
    $delete_detail=$_POST['delete_detail'];
    $yatisprotokol = $_POST['admission_protocol'];
    unset($_POST);
    $yatissekle = guncelle("UPDATE patient_discharge SET status='2',delete_detail='$delete_detail',delete_userid='$user',delete_datetime='$simdikitarih' WHERE admission_protocol='$yatisprotokol'");
var_dump($yatissekle);
    if ($yatissekle) {
        //$ihsan = guncelle("UPDATE hospital_bed SET DOLULUK='1'  WHERE iD='{$yatisid['YATAK_KODU']}'");
        $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand='2',room_id=$odaid,bed_id=$yatakid,admission_end_date=''  WHERE protocol_number=$yatisid");
        var_dump($ihsan);
        if ($ihsan) {
            $ihsan1 = guncelle("UPDATE hospital_bed SET full_status='1'  WHERE id=$yatakid");
            var_dump($ihsan1);
            if ($ihsan1) { ?>
                <script>
                    alertify.success('işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz1');
                </script>

            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz2');
            </script>
        <?php }
    } else { ?>
        <script>
            alertify.error('işlemi Başarisiz3');
        </script>
    <?php }

} elseif ($islem == "taburcuhazirlikkesinlestir") {
    if ($_POST) {
        $odaid = $_POST['room_id'];
        $yatisid = $_POST['yatisid'];
        $id = $_POST['id'];
        unset($_POST['room_id']);
        unset($_POST['yatisid']);
        unset($_POST['id']);
        unset($_POST['transfer_service']);
        unset($_POST['transfer_date']);
        unset($_POST['inbound_transfer_service']);
        unset($_POST['inbound_transfer_doctor']);
        $_POST['discharge_status'] = 1;
        $yatakid = $_POST['bed_id'];
        unset($_POST['bed_id']);
        $yatissekle = direktguncelle("patient_discharge", "id", $id, $_POST);
        var_dump($yatissekle);
        echo "<br/>";
        if ($yatissekle) {
            $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand='4',room_id='$odaid',bed_id='$yatakid',
                                admission_end_date='$simdikitarih'  WHERE id='$yatisid'");
            var_dump($ihsan);
            echo "<br/>";
            if ($ihsan) {
                $ihsan1 = guncelle("UPDATE hospital_bed SET full_status='0'  WHERE id=$odaid");
                var_dump($ihsan1);
                echo "<br/>";
                if ($ihsan1) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('işlemi Başarisiz');
                    </script>

                <?php }
            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz');
                </script>
            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>
        <?php }

    }
} elseif ($islem == "taburcuhazirlikgerial") {

    $yatakid = $_POST['bed_id'];
    $odaid = $_POST['room_id'];
    $yatisid = $_POST['yatisid'];
    $yatisprotokol = $_POST['admission_protocol'];
    unset($_POST);


    $yatissekle = guncelle("UPDATE patient_discharge SET status='0'  WHERE admission_protocol='$yatisprotokol'");

    if ($yatissekle) {
        //$ihsan = guncelle("UPDATE hospital_bed SET DOLULUK='1'  WHERE iD='{$yatisid['YATAK_KODU']}'");
       $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand=2,room_id=$odaid,bed_id=$yatakid  WHERE protocol_number=$yatisid");
        if ($ihsan) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    } else { ?>
        <script>
            alertify.error('işlemi Başarisiz');
        </script>


    <?php }

} elseif ($islem == "transfergerial") {

    $yatakid = $_POST['bed_id'];
    unset($_POST['bed_id']);
    $odaid = $_POST['room_id'];
    $yatisid = $_POST['yatisid'];
    $yatisprotokol = $_POST['admission_protocol'];
    unset($_POST);
    $hastakayit = singular("patient_registration", "protocol_number", $yatisid);

    $yatissekle = guncelle("UPDATE patient_discharge SET status='0'  WHERE admission_protocol='$yatisprotokol'");
    var_dump('islem2:  '.$yatissekle);
    if ($yatissekle==1) {
        //$ihsan = guncelle("UPDATE hospital_bed SET DOLULUK='1'  WHERE iD='{$yatisid['YATAK_KODU']}'");
        $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand='1',room_id='$odaid',bed_id='$yatakid',transfer_status='3',admission_end_date='<null>'  WHERE protocol_number='$yatisid'");
        var_dump('islem3: '.$ihsan);
        if ($ihsan==1) {
            $kullanici = $_SESSION['id'];
//            $parcala = explode('-', $yatisprotokol);
//            $numberal = intval($parcala[1]);
//            $numberbirarti = $numberal + 1;
//            $stral = strval($numberbirarti);
//            $yeniyatisprotokol = $parcala[0] . '-' . $stral;
            $yenihastaid=$hastakayit['protocol_number'];
            $ihsan1 = guncelle("UPDATE patient_registration SET status='0',delete_userid='$kullanici',delete_datetime='$simdikitarih',delete_detail='Yatan hastada transfer işlemi iptal edildi.'  WHERE hospitalization_protocol='$yenihastaid'");
            var_dump('iptal: '.$ihsan1);
            if ($ihsan1==1) { ?>
                <script>
                    alertify.success('işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz');
                </script>

            <?php }
        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    } else { ?>
        <script>
            alertify.error('işlemi Başarisiz');
        </script>
    <?php }

} else if ($islem == "olumililce") {
    $il = $_POST['ilid']; ?>
    <option value="">ilçe seçiniz</option>
    <?php $bolumgetir = "select * from district WHERE province_number=$il";
    $hello=verilericoklucek($bolumgetir);
    foreach ($hello as $value){ ?>

        <option value="<?php echo $value["id"]; ?>"><?php echo $value['district_name']; ?></option>

    <?php } ?>


<?php } else if ($islem == "olumililcemahalle") {

    $ilce = $_POST['ilceid']; ?>
    <option value="">Mahalle seçiniz</option>
    <?php $bolumgetir = "select * from neighborhood WHERE district_id=$ilce";
    $hello=verilericoklucek($bolumgetir);
    foreach ((array) $hello as $value){ ?>

        <option value="<?php echo $value["id"]; ?>"><?php echo $value['neighborhood_name']; ?></option>

    <?php } ?>


<?php } elseif ($islem == "taburcuekranihayir") {

    $id = $_GET['getir'];
    $ekleyen = $_SESSION["id"];
    $hastakayit = singularactive("patient_registration", "protocol_number", $_GET["getir"]);
    $servisid = $hastakayit['service_id'];
    $servisdoktorid = $hastakayit['service_doctor'];
    $islemtanimid=singularactive("transaction_definitions", "definition_name",'Ex');
    $exid=$islemtanimid['id'];
    $ihsan = guncelle("UPDATE patient_registration SET hospitalization_demand=3  WHERE protocol_number='$id'");
    if ($ihsan) {
        var_dump($ihsan);
        $ihsan1 = guncelle("INSERT INTO patient_discharge 
    (discharge_type,admission_protocol,discharge_service,discharge_doctor,discharge_date,insert_userid,insert_datetime) VALUES 
    ('$exid','$id','$servisid','$servisdoktorid','$simdikitarih','$ekleyen','$simdikitarih')");

        if ($ihsan1) { var_dump($ihsan1);?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    } else { ?>
        <script>
            alertify.error('işlemi Başarisiz');
        </script>
    <?php }


} elseif ($islem == "direktifinsert") {
    if ($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $checked_count = count($_POST['directive_id']);
        $say = 0;
        foreach ($_POST['directive_id'] as $selected) {
            $directive_id .= $selected;
            $say++;
            if ($say < $checked_count) {
                $directive_id .= ',';
            }

        }
        unset($_POST['directive_id']);
        $_POST['directive_id'] = $directive_id;

        $yatissekle = direktekle("patient_order", $_POST);

        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
elseif ($islem == "btnepikrizdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("epicrisis", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "yatisiptal") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("patient_registration", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}
elseif ($islem == "btnsevkdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("patient_reference", "id", $id, $detay, $silme, $tarih);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}
elseif($islem=="btnolumdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $olum = singular("death_form", "id", $id);
        $yid = $olum['hospitalization_protocol'];
        $vezneguncelle = canceldetail("death_form", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        echo '--------------';
        var_dump($_POST);
        if ($vezneguncelle) {
            $ihsan1 = guncelle("UPDATE patient_registration SET hospitalization_demand='2'  WHERE protocol_number='$yid'");
            if ($ihsan1) {
                $ihsan = guncelle("UPDATE patient_discharge SET status='2'  WHERE admission_protocol='$yid'");
                if ($ihsan) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('işlemi Başarisiz1');
                    </script>

                <?php }
            } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz2');
                </script>
            <?php }
        }
        else { ?>
            <script>
                alertify.error('işlemi Başarisiz3');
            </script>
        <?php }
    }
}
elseif ($islem == "epikrizinsert") {
    if ($_POST) {
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("epicrisis", $_POST);
        var_dump($yatissekle);
        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "sevkinsert") {
    if ($_POST) {
        $_POST["insert_datetime"] =$simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("patient_reference",$_POST);
var_dump($yatissekle);
        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "oluminsert") {
    if ($_POST) {

        $yatissekle = direktekle("death_form", $_POST);
        var_dump($yatissekle);
        if ($yatissekle==1) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }


    }
} elseif ($islem == "epikrizupdate") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("epicrisis", "id", $id, $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "sevkupdate") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];
        if ($_POST["province_dispatch"]==''){
            $_POST["province_dispatch"]=0;
        }

        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("patient_reference", "id", $id, $_POST);
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "olumupdate") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("death_form", "id", $id, $_POST);
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }


    }
} elseif ($islem == "yatisemanetonayla") {


    $id = $_GET['emanetid'];

    $teslimtarih = $_GET['teslimtarih'];


    $vezneguncelle = guncelle("UPDATE patient_trust SET trust_status=1,trust_delivery_datetime='$teslimtarih' WHERE id='$id'");
    if ($vezneguncelle) { ?>
        <script>
            alertify.success('işlemi Başarili');
        </script>
    <?php } else { ?>
        <script>
            alertify.error('işlemi Başarisiz');
        </script>

    <?php }

} elseif ($islem == "emanetsilme") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("patient_trust", "id", $id, $detay, $silme, $tarih);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "refakatdelet") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $sil = canceldetail("patient_companion", "id", $id, $detay, $silme, $tarih);
        var_dump($_POST);
        if ($sil) { ?>
            <script>
                alertify.success('Silme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('Silme işlemi Başarisiz');
            </script>
        <?php }

    }
}

elseif ($islem == "izindelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $order = singular("patient_permission", "id", $id);
        $protokol_no=$order['protocol_number'];
        $sil = canceldetail("patient_permission", "id", $id, $detay, $silme, $tarih);
        var_dump($sil);
        if ($sil){
            $sonuc=guncelle("UPDATE patient_registration SET hospitalization_demand='2'  WHERE protocol_number={$protokol_no}");
            if ($sonuc) { ?>
                <script>
                    alertify.success('Silme işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.error('Silme işlemi Başarisiz');
                </script>
            <?php }
        }else { ?>
            <script>
                alertify.error('Silme işlemi Başarisiz');
            </script>
        <?php }
    }
}

elseif ($islem == "temizlikdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $sil = canceldetail("bed_cleaning", "id", $id, $detay, $silme, $tarih);
        var_dump($sil);
        if ($sil){?>
            <script>
                alertify.success('Silme işlemi Başarili');
            </script>
        <? }else { ?>
            <script>
                alertify.error('Silme işlemi Başarisiz');
            </script>
        <?php }
    }
}


elseif ($islem == "hastaemanetekle") {
    if ($_POST) {
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $_POST["patient_type"] = "0";
        $_POST["trust_status"] = "0";
        if ($_POST['trust_delivery_datetime']==''){
            unset($_POST['trust_delivery_datetime']);
        }

        $yatissekle = direktekle("patient_trust", $_POST);
var_dump($yatissekle);
        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "hastaemanetguncelle") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];
        $id = $_POST['id'];
        unset($_POST['id']);
        if ($_POST['trust_delivery_datetime']==''){
            unset($_POST['trust_delivery_datetime']);
        }

        $sonuc = direktguncelle("patient_trust", "id", $id, $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
elseif ($islem == "refakatupdate") {
    if ($_POST) {
        $id = $_POST["refakatci"]['id'];
        unset($_POST['id']);

//        $tel=trim($_POST["refakatci"]["companion_phone"]);
//        unset($_POST["refakatci"]["companion_phone"]);
//        $_POST["refakatci"]["companion_phone"]=$tel;

        $sonuc = direktgroupguncelle("patient_companion", "id", $id, $_POST["refakatci"], "refakatci");
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('Refakatçi Güncelleme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('Refakatçi Güncelleme işlemi Başarisiz');
            </script>

        <?php }


    }
}
elseif ($islem == "izinupdate") {
    if ($_POST) {
        $id = $_POST["izin"]['id'];
        unset($_POST['id']);

        $sonuc = direktgroupguncelle("patient_permission", "id", $id, $_POST["izin"], "izin");
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('İzin Güncelleme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('İzin Güncelleme işlemi Başarisiz');
            </script>

        <?php }


    }
}

elseif ($islem == "temizlikupdate") {
    if ($_POST) {
        $id = $_POST["temizlik"]['id'];
        unset($_POST['id']);

        $sonuc = direktgroupguncelle("bed_cleaning", "id", $id, $_POST["temizlik"], "temizlik");
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('Güncelleme işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('Güncelleme işlemi Başarisiz');
            </script>

        <?php }


    }
}

elseif ($islem == "temizledi") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = $islem2 = guncelle("UPDATE bed_cleaning SET bed_status='1',employee_userid={$_SESSION['id']} WHERE id=$id");
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }


    }
}


elseif ($islem == "btnorderdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $order = singular("patient_order", "id", $id);

        $ordersilme = canceldetail("patient_order", "id", $id, $detay, $silme, $tarih);
        if ($ordersilme) {
            if ($order['operation_type'] == "29161") {
                echo $istekidler = $order['request_id'];
                echo $cardidler = $order['medicine_id'];
                $movestok = tek("SELECT * FROM stock_request_move WHERE stock_requestid='$istekidler' AND wanted_stock_cardid='$cardidler'");
                $moveid = $movestok['id'];
                $istem = canceldetail("stock_request_move", "id", $moveid, $detay, $silme, $tarih);
                if ($istem) { ?>
                    <script>
                        alertify.success('işlemi Başarili');
                    </script>
                <?php } else { ?>
                    <script>
                        alertify.error('işlemi Başarisiz1');
                    </script>

                <?php }
            } else { ?>
                <script>
                    alertify.success('işlemi Başarili');
                </script>

            <?php }

        } else { ?>
            <script>
                alertify.error('işlemi Başarisiz3');
            </script>

        <?php }
    }
} elseif ($islem == "tedaviuygulama") {
    if ($_POST) {
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $sonuc = direktekle("order_application", $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "order_insert") {
    $stok_kart_id_dizi = count($_POST['stok_kart_id_dizi']);
//    $KAYiT=$_POST;
    for ($i = 0; $i < $stok_kart_id_dizi; $i++) {
        $_POST["orderdetay"]["patient_protokol"] = $_POST['yatisprotokol'];
        $_POST["orderdetay"]["transaction_date"] = $simdikitarih;
        $_POST["orderdetay"]["operation_type"] = 29161;
        $_POST["orderdetay"]["dose"] = $_POST['doz_dizi'][$i];
        $_POST["orderdetay"]["drug_use"] = $_POST['kullanim_sekli_dizi'][$i];
        $_POST["orderdetay"]["type_of_treatment"] = $_POST['tedavi_turu_dizi'][$i];
        $_POST["orderdetay"]["starting_date"] = $_POST['baslangic_tarihi_dizi'][$i];
        $_POST["orderdetay"]["end_date"] = $_POST['bitis_tarihi_dizi'][$i];
        $_POST["orderdetay"]["approval_status"] = 0;
        $_POST["orderdetay"]["transaction_hour"] = $_POST['ilac_saat'][$i];
        $_POST["orderdetay"]["medicine_id"] = $_POST['stok_kart_id_dizi'][$i];
        $_POST["orderdetay"]["drug_disclosure"] = $_POST['aciklama_dizi'][$i];
        $_POST["orderdetay"]["type_of_drug_use"] = $_POST['ilac_kullanim_tipi'][$i];
        $_POST["orderdetay"]["request_id"] = $_POST['request_id'];

        $_POST["orderdetay"]["insert_userid"] = $_SESSION["id"];
        $_POST["orderdetay"]["insert_datetime"] = $simdikitarih;

        $orderinsert = groupdirektekle("patient_order",$_POST['orderdetay'],"", "orderdetay");

    }
    //var_dump($orderinsert);
    //echo '<br/>';
    if ($orderinsert) {
        ?>
        <script type="text/javascript">
            alertify.success("Kayit başarili");
        </script>
        <?php
    } else {

        ?>
        <script type="text/javascript">
            alertify.alert("Kayit başarisiz1.");
        </script>
        <?php
    }

} elseif ($islem == "multi_request_insert") {

    echo $stock_r_count = count($_POST['wanted_stock_cardid']);
    echo '<br/>';
    $kayit=$_POST;
    for ($i = 0; $i < $stock_r_count; $i++) {

        $_POST["wanted_stock_cardid"] = $kayit["wanted_stock_cardid"][$i];
        $_POST["wanted_medicine_generic_code"] = $kayit["wanted_medicine_generic_code"][$i];
        $_POST["requested_amount"] = $kayit["requested_amount"][$i];
        $_POST["insert_userid"] =$_SESSION['id'];
        $_POST["insert_datetime"] = $simdikitarih;

        $multirequestmove = direktekle("stock_request_move", $_POST);


    }
    var_dump($multirequestmove);
    echo '<br/>';
    var_dump($_POST);
    if ($multirequestmove) {

        ?>
        <script type="text/javascript">
            alertify.success("Kayit başarili");

        </script>
        <?php
    } else {

        ?>
        <script type="text/javascript">
            alertify.alert("Kayit başarisiz2");

        </script>
        <?php
    }
} elseif ($islem == "stock-request-save") {

    $stockType = $_POST["hastaistem"]["stock_type"];

    $requestAdd = groupdirektekle("stock_request", $_POST['order'],"", "order");

    if ($requestAdd) {
        $sonucyaz = islemtanimsoneklenen("stock_request","insert_userid", $_SESSION['id']);
        if ($sonucyaz) {
         print_r($sonucyaz);
         } else { ?>
            <script>
                alertify.error('işlemi Başarisiz123');
            </script>
        <?php }
    } else {
        ?>
        <script>
            alertify.error('işlemi Başarisiz3');
        </script>
        <?php
    }
}
 ?>
