<?php include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$KULLANICI_ID=$_SESSION["id"];
$islem=$_GET["islem"];

$random_isim = uniqid();

//REÇETE VARMI SORGULAYARAK REÇETE İLAÇ EKLE BAŞLANGIÇ

//REÇETE VARSA DİREKT REÇETEYE EKELEME İŞLEMİ BAŞLANGIÇ
if($islem=="sql_recete_ekle") {
    $serial_number = $_POST['serial_number'];
    $recipe_time = $_POST['recipe_time'];
    $recipe_detail = $_POST['recipe_detail'];
    $recipe_tour = $_POST['recipe_tour'];
    $recipe_sub_tour = $_POST['recipe_sub_tour'];
    $patient_id = $_POST['patient_id'];

    $PROTOKOL = $_POST['patient_referenceid'];
    $kullanimsekli = $_POST["drug_use_form"];
    $kullanimtipi = $_POST["drug_use_type"] ;
    $kullanimperiyodu = $_POST["drug_use_period"];
    $kutuadet = $_POST["box_pieces"];
    $ilacaciklama = $_POST["drug_description"];
    $ilacid = $_POST["drug_id"];

    $recete = tek("select * from prescriptions where prescriptions.patient_referenceid='$PROTOKOL'");
    if ($recete["patient_referenceid"] == $PROTOKOL) { echo "recetevar"; echo "<br>";
        unset($_POST['patient_referenceid']);


        $_POST["recipe_id"] = $recete["id"];


        $recete_ilac_ekle = direktekle("prescription_medicine", $_POST, $PROTOKOL);
        if ($recete_ilac_ekle == 1) {?>
            <script>
                alertify.success('İlaç Ekleme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('İlaç Ekleme Başarısız');
            </script>
        <?php }
//REÇETE VARSA DİREKT REÇETEYE EKELEME İŞLEMİ BİTİŞ

//REÇETE DAHA ÖNCE OLUŞTURULMAMIŞSA REÇETE EKLEYİP İLAÇ EKLEDİĞİ İŞLEM BAŞLANGIÇ
    } else { echo "receteyok"; echo "<br>";
        unset($_POST);
        $_POST['patient_referenceid'] = $PROTOKOL;

        $_POST['serial_number'] = $serial_number;
        $_POST['recipe_detail'] = $recipe_detail;
        $_POST['recipe_tour'] = $recipe_tour;
        $_POST['recipe_sub_tour'] = $recipe_sub_tour;
        $_POST['patient_id'] = $patient_id;

        $_POST['recipe_time'] = $recipe_time;
        $_POST['physician_id'] = $KULLANICI_ID;

        $recete_ekle = direktekle("prescriptions", $_POST);
        if ($recete_ekle == 1) {
            echo "İşlem Başarılı";
            unset($_POST);

            $recetekontrol = tek("select * from prescriptions where prescriptions.patient_referenceid='$PROTOKOL'");
            $_POST["recipe_id"] = $recetekontrol["id"];
            $_POST["drug_use_form"] = $kullanimsekli;
            $_POST["drug_use_type"] = $kullanimtipi;
            $_POST["drug_use_period"] = $kullanimperiyodu;
            $_POST["box_pieces"] = $kutuadet;
            $_POST["drug_description"] = $ilacaciklama;
            $_POST["drug_id"] = $ilacid;

            $recete_ilac_ekle = direktekle("prescription_medicine", $_POST, $PROTOKOL);
            if ($recete_ilac_ekle == 1) {?>
                <script>
                    alertify.success('İlaç Ekleme Başarılı');
                </script>
            <?php   } else { ?>
                <script>
                    alertify.error('İlaç Ekleme Başarısız');
                </script>

            <?php }
        } else {?>
            <script>
                alertify.error('HATA İlaç Ekleme Başarısız');
            </script>

        <?php }
    }
}

else if ($islem=="sql_recete_ilac_duzenle"){
    $drug_id= $_POST["drug_id"];
    unset($_POST["drug_id"]);

    $recete_ilac_tablo=direktguncelle("prescription_medicine","id", $drug_id,  $_POST);
    if ($recete_ilac_tablo==1){ ?>
        <script>
            alertify.success('İlaç Düzenleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('İlaç Düzenleme Başarısız');
        </script>

    <?php }
}

else if($islem=="sql_recete_ilac_sil") {
    $drug_id= $_POST["drug_id"];

    $detay = $_POST['delete_detail'];
    $tanimlama_sil = canceldetail("prescription_medicine", "id", $drug_id, $detay);
    if ($tanimlama_sil == 1) { ?>
        <script>
            alertify.success('İlaç Silme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('İlaç Silme Başarısız');
        </script>

    <?php }


}if($islem=="recete-sablonu-ekle"){

    $sql = direktekle("receipt_templates",$_POST);

    if ($sql==1){ ?>
        <script>
            alertify.success('İşlem Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('İşlem Başarısız');
        </script>
    <?php }


}if($islem=="recete-sablonu-sil"){

    $id = $_POST['id'];
    $detay = $_POST['delete_detail'];
    $sql = canceldetail("receipt_templates", "id", $id, $detay);

    if ($sql==1){ ?>
        <script>
            alertify.success('İşlem Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('İşlem Başarısız');
        </script>
    <?php } }

if($islem=="seçilen-sablonu-hastaya-ekle"){

    $kontrol_et = tek("select * from prescriptions where patient_referenceid = $_GET[protocol] ");

    if(isset($kontrol_et['patient_referenceid'])){
        //echo "reçete var";
        $sql1 = verilericoklucek("select * from prescription_medicine where recipe_id = $_GET[recete_id] and status=1");

        $sql_2 = verilericoklucek("select drug_id from prescription_medicine where recipe_id ='$kontrol_et[id]' and status=1");


        foreach ($sql1 as $item) {
            $k = 0;
            $_POST['recipe_id'] = $kontrol_et['id'];
            $_POST['dose_unit'] = $item['dose_unit'];
            $_POST['box_pieces'] = $item['box_pieces'];
            $_POST['drug_use_form'] = $item['drug_use_form'];
            $_POST['drug_use_dose'] = $item['drug_use_dose'];
            $_POST['drug_use_period'] = $item['drug_use_period'];
            $_POST['drug_description'] = $item['drug_description'];
            $_POST['drug_use_type'] = $item['drug_use_type'];
            $_POST['drug_id'] = $item['drug_id'];

            for ($j=0;$j<15;$j++){
              if($item['drug_id']==$sql_2[$j]['drug_id']){
                  $k = 1;
              } }
            if($k==0){
                $sql2 = direktekle("prescription_medicine", $_POST);
            }
        }

        if ($sql2==1){ ?>
            <script>
                alertify.success('Şablon Ekleme Başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('İşlem Başarısız');
            </script>
        <?php }


    }else{
        unset($_POST);
        $_POST['patient_referenceid'] = $_GET['protocol'];

        $recete_ekle = direktekle("prescriptions", $_POST);
        $sql_701 = tek("select * from prescriptions where patient_referenceid=$_GET[protocol]");

        $sql1 = verilericoklucek("select * from prescription_medicine where recipe_id = $_GET[recete_id] where status=1");

        unset($_POST);
        foreach ($sql1  as $item) {
            $_POST['recipe_id'] = $sql_701['id'];
            $_POST['dose_unit'] = $item['dose_unit'];
            $_POST['box_pieces'] = $item['box_pieces'];
            $_POST['drug_use_form'] = $item['drug_use_form'];
            $_POST['drug_use_dose'] = $item['drug_use_dose'];
            $_POST['drug_use_period'] = $item['drug_use_period'];
            $_POST['drug_description'] = $item['drug_description'];
            $_POST['drug_use_type'] = $item['drug_use_type'];
            $_POST['drug_id'] = $item['drug_id'];
            $sql2 = direktekle("prescription_medicine", $_POST);
        }

        if ($sql2==1){ ?>
            <script>
                alertify.success('Şablon Ekleme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('İşlem Başarısız');
            </script>
        <?php }

    }


}if($islem=="grup-ilac-sil"){

  $count =  count($_POST['ilac_id']);
   for($i=0;$i<$count;$i++) {
       $sql = canceldetail("prescription_medicine", "id", $_POST['ilac_id'][$i] , $_POST['delete_detail']);
   }

    if ($sql==1){ ?>
    <script>
        alertify.success('İşlem Başarılı');
    </script>
<?php   } else { ?>
    <script>
        alertify.error('İşlem Başarısız');
    </script>
<?php }

  }if($islem =="ialc-json-getir"){

    $sql = verilercoklucek("select * from prescription_drugs");
    echo json_encode($sql);


}if($islem=='favoriyi-kaldir'){

    $fav_id = $_POST['fav_id'];
    unset($_POST['fav_id']);
    $_POST['status'] = 2;
    $sql = direktguncelle('user_drug_favorite' , 'id' , $fav_id , $_POST);


    if ($sql==1){ ?>
        <script>
            alertify.success('Fovori Kaldırma İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('Fovori Kaldırma İşlemi Başarısız');
        </script>
    <?php }

}if($islem=='favoriyi-ekle'){

    $ilac_id = $_POST['drug_id'];


    $control = sql_select("select * from user_drug_favorite where drug_id =$ilac_id and user_id =$KULLANICI_ID ");

    if($control){

        $fav_id = $control[0]['id'];
        unset($_POST);
        $_POST['status'] = 1;
        $sql = direktguncelle('user_drug_favorite' , 'id' , $fav_id , $_POST);
    }else{

        $_POST['user_id']  = $_SESSION['id'];
        $sql = direktekle('user_drug_favorite' , $_POST);

    }


    if ($sql==1){ ?>
        <script>
            alertify.success('Fovori Ekleme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('Fovori ekleme İşlemi Başarısız');
        </script>
    <?php }

}

