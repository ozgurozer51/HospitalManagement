
<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$islem = $_GET['islem'];



if($islem == "taniekle") {

    $_POST["tc_id"] = $_GET["hastalarid"];
    $_POST["protocol_number"] = $_GET["protokolno"];
    $_POST["diagnosis_code"] = $_GET["tanikodu"];
    $_POST["diagnosis_id"] = $_GET["tani_id"];
    $_POST["insert_userid"] = $_GET["taniekleyendoktor"];
    $_POST["diagnosis_type"] = $_GET["tip"];
    $_POST["diagnosis_modul"] = $_GET["diagnosis_modul"];
    $_POST["diagnosis_unit"] = $_GET["birim"];

    if ($_GET["islems"] != "sil") {
        $TANiSOR = tek("SELECT * FROM patient_record_diagnoses WHERE protocol_number='{$_GET["protokolno"]}' and diagnosis_type='{$_GET["tip"]}' and diagnosis_id='{$_GET["tani_id"]}' AND status='1'");
        if ($TANiSOR["id"] == "") {

            $sadasd = $_GET["protokolno"];
            $sorgula = tek("SELECT * FROM patient_registration WHERE protocol_number='$sadasd'");

            if ($sorgula["examination_start_time"] == "") {
                //guncelle("UPDATE patient_registration SET MUAYENE_BASLAMA_ZAMANi='$simdikitarih' WHERE iD='$sadasd'");
            }
           $eklemeislemi= direktekle("patient_record_diagnoses", $_POST);
           
            if ($eklemeislemi){ ?>
                <script>
                    alertify.success('işlemi Başarılı');
                </script>
            <?php }else{ ?>
                <script>
                    alertify.error('işlemi Başarısız');
                </script>
            <?php   }
        } else {
            ?>
            <script>  alertify.error("Eklemeye çaliştiğiniz taniyi daha önce eklediğiniz için eklenmemiştir");</script>
            <?php
        }
    }
    else if ($_GET["islecm"] == "anatanidegistir") {

        $islem1 = guncelle("UPDATE patient_record_diagnoses SET main_diagnosis='0' 
            WHERE tc_id='{$_GET["hastalarid"]}' AND protocol_number='{$_GET["protokolno"]}' AND main_diagnosis='{$_GET["tanituru"]}'");
        echo '<br/>';  echo 'islem1:'; var_dump($islem1); echo '<br/>';
        if ($islem1) {

            $islem2 = guncelle("UPDATE patient_record_diagnoses SET main_diagnosis='{$_GET["tanituru"]}' WHERE id='{$_GET["tani_id"]}' AND tc_id='{$_GET["hastalarid"]}' AND protocol_number='{$_GET["protokolno"]}'");
            echo '<br/>';  echo 'islem2:'; var_dump($islem2); echo '<br/>';
            if ($islem2){ ?>
                <script>
                    alertify.success('işlemi Başarılı');
                </script>
            <?php   }else{ ?>
                <script>
                    alertify.error('işlemi Başarısız');
                </script>
            <?php   }
        } else {?>
            <script>
                alertify.error('işlemi Başarısız');
            </script>
        <?php   }

    } else {

        $hisan = silme("patient_record_diagnoses", "id", $_GET["tani_id"]);
        if ($hisan){ ?>
            <script>
                alertify.success('işlemi Başarılı');
            </script>
     <?php   }else{ ?>
            <script>
                alertify.error('işlemi Başarısız');
            </script>
     <?php   } } ?>
<?php }
if($islem =="tani-sil"){

  canceldetail("patient_record_diagnoses", "id" ,"$_GET[special_id]" );

} if($islem =="tani-adi-guncelle"){

    $id =$_POST['tani_id'];
    unset($_POST['tani_id']);
    $poliklinik_protokol=$_POST['poliklinik_protokol'];
    $klinik_protokol=$_POST['klinik_protokol'];
    unset($_POST['klinik_protokol']);
    unset($_POST['poliklinik_protokol']);
    if ($klinik_protokol==''){
        $protokoller='AND protocol_number='.$poliklinik_protokol;
    }else{
        $protokoller='AND protocol_number in ('.$poliklinik_protokol.','.$klinik_protokol.')';
    }


    direktguncelle("patient_record_diagnoses", "id" , $id , $_POST );
    if ($_POST['main_diagnosis']==1){
        $sql_metin="UPDATE patient_record_diagnoses SET main_diagnosis='0' WHERE  main_diagnosis='{$_POST['main_diagnosis']}' $protokoller";
       guncelle($sql_metin);
        direktguncelle("patient_record_diagnoses", "id" , $id , $_POST );
//        var_dump($sql_metin);
    }else{

        direktguncelle("patient_record_diagnoses", "id" , $id , $_POST );
    }


}if ($islem == "tani-sil-2"){

    tek("UPDATE patient_record_diagnoses SET status='2' WHERE insert_userid='$_SESSION[id]' and id='$_GET[tani_id]'");
}






