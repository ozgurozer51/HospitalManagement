<?php
include "../../controller/fonksiyonlar.php";
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$islem=$_GET["islem"];

//SQL KAN TALEP KARŞILA
if ($islem=="sqlkantalepkarsila") {
    if ($_POST) {
        $_POST['insert_userid'] = $kullanici_id;
        $kantalepkarsila = direktekle("blood_out", $_POST);
        if ($kantalepkarsila == 1) {
            $kancikisid=$_POST["blood_demandid"];
            $kanstokid=$_POST["blood_stockid"];
            unset($_POST);
            $id=$kancikisid;
            $stokid=$kanstokid;
            $_POST["blood_demand_status"]=28862;
            $_POST['update_userid'] = $kullanici_id;
            $kantalepdurumguncelle=direktguncelle("blood_demand","blood_demandid",$id,$_POST);
            unset($_POST['update_userid']);
            if ($kantalepdurumguncelle == 1) {
                unset($_POST);
                $_POST["blood_stock_status"]=28973;
                $_POST["blood_demandid"]=$id;
                $_POST['insert_userid'] = $kullanici_id;
                $kanstokdurumguncelle=direktguncelle("blood_stock","id",$stokid,$_POST);
                if ($kanstokdurumguncelle == 1) {
                }
            }
            ?>
            <script>
                alertify.success('Talep İçin Karşılama İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Talep İçin Karşılama İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN TALEP REZERVE ET
else if ($islem=="sqlkantaleprezerveet") {
    if ($_POST) {
        $kan_talep_id = $_POST['blood_demandid'];
        $_POST['insert_userid'] = $kullanici_id;
        $kantaleprezerveet = direktekle("blood_out", $_POST);
        unset($_POST['insert_userid']);
        if ($kantaleprezerveet == 1) {
            $kancikisid=$_POST["blood_demandid"];
            $kanstokid=$_POST["blood_stockid"];
            unset($_POST);
            $id=$kancikisid;
            $stokid=$kanstokid;
            $_POST["blood_demand_status"]=28861;
            $_POST['insert_userid'] = $kullanici_id;
            $kantalepdurumguncelle=direktguncelle("blood_demand","blood_demandid",$id,$_POST);
            unset($_POST['insert_userid']);
            if ($kantalepdurumguncelle == 1) {
                unset($_POST);
                $_POST['blood_demandid'] = $kan_talep_id;
                $_POST["blood_stock_status"]=28972;
                $_POST['insert_userid'] = $kullanici_id;
                $kanstokdurumguncelle=direktguncelle("blood_stock","id",$stokid,$_POST);
                if ($kanstokdurumguncelle == 1) {
                }
            }
            ?>
            <script>
                alertify.success('Talep İçin Rezerve İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Talep İçin Rezerve Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN TALEP REDDET
else if ($islem=="sqlkantalepreddet") {
    if ($_POST) {
        $_POST['insert_userid'] = $kullanici_id;
        $kantalepreddet = direktekle("blood_demand_detail", $_POST);
        if ($kantalepreddet == 1) {
            $kancikisid=$_POST["blood_demandid"];
            unset($_POST);
            $id=$kancikisid;
            $_POST["blood_demand_status"]=28863;
            $_POST['update_userid'] = $kullanici_id;
            $kantalepdurumguncelle=direktguncelle("blood_demand","blood_demandid",$id,$_POST);
            if ($kantalepdurumguncelle == 1) {

            }
            ?>
            <script>
                alertify.success('Talep Reddetme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Talep Reddetme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN TALEP KARŞILA GERİ AL
else if($islem=="sqlkantalepkarsilagerial") {
    if ($_POST) {
        $_POST["delete_datetime"]=$simdikitarih;
        $tarih = $_POST["delete_datetime"];
        $Id = $_POST["id"];
        $_POST['delete_userid']=$kullanici_id;
        $kullanici = $_POST['delete_userid'];
        $detay = $_POST['delete_detail'];
        $_POST["blood_out_time"]=NULL;
        $kantalepkarsilamagerial = canceldetail("blood_out", "id", $Id, $detay, $kullanici, $tarih);
        if ($kantalepkarsilamagerial == 1) {
            $kancikisid = $_POST["blood_demandid"];
            $kanstokid=$_POST["blood_stockid"];
            unset($_POST);
            $ID = $kancikisid;
            $stokid=$kanstokid;
            $_POST["blood_demand_status"] = 28831;
            $_POST['update_userid'] = $kullanici_id;
            $kantalepdurumguncelle = direktguncelle("blood_demand", "blood_demandid", $ID, $_POST);
            unset($_POST['update_userid']);
            if ($kantalepdurumguncelle == 1) {
                unset($_POST);
                $_POST["blood_stock_status"]=28970;
                $_POST["blood_demandid"]=NULL;
                $_POST['update_userid'] = $kullanici_id;
                $kanstokdurumguncelle=direktguncelle("blood_stock","id",$stokid,$_POST);
                unset($_POST['update_userid']);
                if ($kanstokdurumguncelle == 1) {
                }
            }
            ?>
            <script>
                alertify.success('Talep Karşılama İşlemi Geri Alındı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Talep Karşılama İşlemi Geri Alınamadı');
            </script>
        <?php }
    }
}

//SQL KAN TALEP REZERVE GERİ AL
else if($islem=="sqlkantaleprezervegerial") {
    if ($_POST) {
        $id = $_POST["id"];
        $tarih = $_POST["delete_datetime"]=$simdikitarih;
        $kullanici = $_POST['delete_userid']=$kullanici_id;
        $detay = $_POST['delete_detail'];
        $kantaleprezervegerial = canceldetail("blood_out", "id", $id, $detay, $kullanici, $tarih);
        if ($kantaleprezervegerial == '1') {
            $kancikisid = $_POST["blood_demandid"];
            $kanstokid=$_POST["blood_stockid"];
            unset($_POST);
            $id = $kancikisid;
            $stokid=$kanstokid;
            $_POST["blood_demand_status"] = 28831;
            $_POST["update_datetime"] = $simdikitarih;
            $_POST['update_userid'] = $kullanici_id;
            $kantalepdurumguncelle = direktguncelle("blood_demand", "blood_demandid", $id, $_POST);
            unset($_POST['update_userid']);
            if ($kantalepdurumguncelle == 1) {
                unset($_POST);
                $_POST["blood_stock_status"]=28970;
                $_POST["blood_demandid"]=NULL;

                $_POST['update_userid'] = $kullanici_id;
                $kanstokdurumguncelle=direktguncelle("blood_stock","id",$stokid,$_POST);
                unset($_POST['update_userid']);
                if ($kanstokdurumguncelle == 1) {
                }
            }
            ?>
            <script>
                alertify.success('Talep Rezerve İşlemi Geri Alındı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Talep Rezerve İşlemi Geri Adınamadı');
            </script>
        <?php }
    }
}

//SQL KAN TALEP REDDET GERİ AL
else if($islem=="sqlkantalepreddetgerial") {
    if ($_POST) {
        $id = $_POST["id"];
        $tarih = $_POST["delete_datetime"]=$simdikitarih;
        $kullanici = $_POST['delete_userid']=$kullanici_id; //session oturumunda olan kişinin id si eşitlenecek
        $detay = $_POST['delete_detail'];
        $kantalepreddetgerial = canceldetail("blood_demand_detail", "id", $id, $detay, $kullanici, $tarih);
        if ($kantalepreddetgerial == 1) {
            $kancikisid = $_POST["blood_demandid"];
            unset($_POST);
            $id = $kancikisid;
            $_POST["blood_demand_status"] = 28831;
            $_POST['update_userid'] = $kullanici_id;
            $kantalepdurumguncelle = direktguncelle("blood_demand", "blood_demandid", $id, $_POST);
            unset($_POST['update_userid']);
            if ($kantalepdurumguncelle == 1) { } ?>
            <script>
                alertify.success('Reddedilen Talep Geri Alındı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Reddedilen Talep Geri Alındı');
            </script>
        <?php }
    }
}

//SQL KAN TALEP REZERVE İŞLEMİNİ KARŞILA
else if ($islem=="sqlkantaleprezervekarsila"){
    $kancikisid=$_POST["blood_demandid"];
    unset($_POST["blood_demandid"]);
    $sql=direktguncelle("blood_out","blood_demandid",$kancikisid,$_POST);
    if ($sql==1){
        $kanstokid=$_POST["blood_stockid"];
        unset($_POST);
        $stokid=$kanstokid;
        $_POST["blood_demand_status"]=28862;
        $kantalepdurumguncelle=direktguncelle("blood_demand","blood_demandid",$kancikisid,$_POST);
        if ($kantalepdurumguncelle == 1) {
            unset($_POST);
            $_POST["blood_stock_status"]=28973;
            $_POST["blood_demandid"]=$kancikisid;
            $kanstokdurumguncelle=direktguncelle("blood_stock","id",$stokid,$_POST);
            if ($kanstokdurumguncelle == 1) {
            }
        }
        ?>
            <script>
                alertify.success('Rezerve İşlemini Karşılama Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Rezerve İşlemini Karşılama Başarısız');
            </script>
        <?php }
}


//SQL TANIMLAMA EKLE
else if ($islem=="sql_tanimlama_ekle") {
    $_POST['insert_userid'] = $kullanici_id;
    $tanimlama_ekle= direktekle("transaction_definitions",$_POST);
    unset($_POST['insert_userid']);
    if ($tanimlama_ekle==1){?>
        <script>
            alertify.success('Tanımlama Ekleme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('Tanımlama Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

//SQL TANIMLAMA DÜZENLE
else if ($islem=="sql_tanimlama_duzenle") {
    if ($_POST) {

        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $tanimlama_duzenle = direktguncelle("transaction_definitions", "id", $id, $_POST);
        unset($_POST['update_userid']);
        if ($tanimlama_duzenle == 1) {?>
            <script>
                alertify.success('Tanımlama Düzenleme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Tanımlama Düzenleme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL TANIMLAMA SİL
else if($islem=="sql_tanimlama_sil") {
    if ($_POST) {
        $tarih = $_POST["delete_datetime"]= $simdikitarih;
        $id = $_POST["id"];
        $kullanici = $_POST['delete_userid'] = $kullanici_id;
        $detay = $_POST['delete_detail'];

        $tanimlama_sil = canceldetail("transaction_definitions", "id", $id, $detay, $kullanici, $tarih);

        if ($tanimlama_sil == 1) {?>
            <script>
                alertify.success('Tanımlama Silme İşlemi Başarılı');
            </script>
        <?php  } else { ?>
            <script>
                alertify.error('Tanımlama Silme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR EKLE TC KİMLİK ARAMA JSON GETİR
else if($islem=="tc_kimlik_ara"){
    $tc_id=$_POST['tc_id'];

    $donor_kayit=tek("select * from blood_donor_registration where tc_id='$tc_id'");

    $donor_kayit_json = json_encode($donor_kayit);
    echo $donor_kayit_json;
}

//$_POST["yatisyap"]['hospitalization_demand']=2;
//$yatissekle = direktgroupguncelle("patient_registration", "protocol_number", $id, $_POST["yatisyap"], "yatisyap");
//$hasistem = groupdirektekle("patient_prompts", $_POST['hastaistem'], "", "hastaistem");

//SQL DONÖR EKLE
if($islem=="sql_donor_ekle") {
    $tc_id=$_POST["kayit"]['tc_id'];

    $sql = tek("select * from blood_donor_registration where blood_donor_registration.tc_id='$tc_id'");
    $sorgu_tc=$sql["tc_id"];
    if ($sorgu_tc == $tc_id) {
        echo "Kayıtlı Donör"; echo "<br>";
        $_POST["donor"]["blood_group"] =$_POST["kayit"]["blood_group"];
        $donor_ekle = direktekle("blood_donor", $_POST['donor']);
        var_dump($donor_ekle);
        echo "<br>";
        if ($donor_ekle == 1) {?>
            <script>
                alertify.success('Donör Ekleme İşlemşi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Ekleme İşlemşi Başarısız');
            </script>
        <?php }
    } else {
        echo "Donör Kayıtlı Değil"; echo "<br>";

        $donor_kayit_ekle = groupdirektekle("blood_donor_registration", $_POST['kayit'], "", "kayit");
        var_dump($donor_kayit_ekle);
        echo "<br>";
        if ($donor_kayit_ekle == 1) {
            echo "İşlem Başarılı";

            $donor_kontrol = tek("select * from blood_donor_registration where blood_donor_registration.tc_id='$tc_id'");
            $_POST["donor"]["donor_registrationid"] = $donor_kontrol["id"];

            $_POST["donor"]['blood_group'] =$_POST["kayit"]['blood_group'];
            $donor_ekle = direktekle("blood_donor", $_POST["donor"]);
            if ($donor_ekle == 1) {?>
                <script>
                    alertify.success('Donör Ekleme İşlemşi Başarılı');
                </script>
            <?php   } else { ?>
                <script>
                    alertify.error('Donör Ekleme İşlemşi Başarısız');
                </script>
            <?php }
        } else {?>
            <script>
                alertify.error('HATA Donör Ekleme Başarısız');
            </script>
        <?php }
    }
}


//SQL DONÖR KABUL ET
else if($islem=="sql_donor_kabul_et"){
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $tanimlama_duzenle = direktguncelle("blood_donor", "id", $id, $_POST);
        unset($_POST['update_userid']);
        if ($tanimlama_duzenle == 1) {?>
            <script>
                alertify.success('Donör Kabul Eildi');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Kabul Etme Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR REDDET
else if($islem=="sql_donor_reddet"){
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $tanimlama_duzenle = direktguncelle("blood_donor", "id", $id, $_POST);
        unset($_POST['update_userid']);
        if ($tanimlama_duzenle == 1) {?>
            <script>
                alertify.success('Donör Reddedildi');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Reddetme Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR SİL
else if($islem=="sql_donor_sil") {
    if ($_POST) {
        $tarih = $_POST["delete_datetime"] = $simdikitarih;
        $id = $_POST["id"];
        $kullanici = $kullanici_id;
        $detay = $_POST['delete_detail'];
        $tanimlama_sil = canceldetail("blood_donor", "id", $id, $detay, $kullanici, $tarih);
        var_dump($tanimlama_sil);
        if ($tanimlama_sil == 1) {?>
            <script>
                alertify.success('Donör Silme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Silme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR REZERVE ET
else if ($islem=="sql_donor_rezerve"){
    if ($_POST) {
        $kantaleprezerveet = direktekle("blood_out", $_POST,"donorrezervetablo_length");
        if ($kantaleprezerveet == 1) {
            $kancikisid=$_POST["blood_demandid"];
            unset($_POST);
            $ID=$kancikisid;
            $_POST["blood_demand_status"]=28861;
            $kantalepdurumguncelle=direktguncelle("blood_demand","blood_demandid",$ID,$_POST);
            if ($kantalepdurumguncelle == 1) {

            } ?>
            <script>
                alertify.success('Rezerve İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Rezerve İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR KABUL GERİ AL
else if($islem=="sql_donor_kabul_geri_al"){
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $tanimlama_duzenle = direktguncelle("blood_donor", "id", $id, $_POST);
        unset($_POST['update_userid']);
        if ($tanimlama_duzenle == 1) {?>
            <script>
                alertify.success('Donör Kabul İşlemini Geri Alma Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Kabul İşlemini Geri Alma Başarısız');
            </script>
        <?php }
    }
}

//SQL DONÖR RET GERİ AL
else if($islem=="sql_donor_ret_geri_al"){
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $tanimlama_duzenle = direktguncelle("blood_donor", "id", $id, $_POST);
        unset($_POST['update_userid']);
        if ($tanimlama_duzenle == 1) {?>
            <script>
                alertify.success('Donör Ret İşlemini Geri Alma Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Donör Ret İşlemini Geri Alma Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN STOK EKLE
else if ($islem=="sql_kan_stok_ekle") {
    if ($_POST) {
        $_POST['insert_datetime'] = $simdikitarih;
        $_POST['insert_userid'] = $kullanici_id;
        $tanimlama_ekle = direktekle("blood_stock", $_POST);
        unset($_POST['insert_userid']);
        if ($tanimlama_ekle == 1) {?>
            <script>
                alertify.success('Stoğa Kan Ekleme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Stoğa Kan Ekleme İşlemi Başarısız');
            </script>
        <?php }

    }
}

//SQL KAN STOK DÜZENLE
else if($islem=="sql_kan_stok_duzenle"){
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $_POST['update_userid'] = $kullanici_id;
        $kan_stok_duzenle = direktguncelle("blood_stock", "id", $id, $_POST);
        if ($kan_stok_duzenle == 1) {?>
            <script>
                alertify.success('Güncelleme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Güncelleme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN STOK SİL
else if($islem=="sql_kan_stok_sil") {
    if ($_POST) {
        $id = $_POST["id"];
        $tarih = $_POST["delete_datetime"] = $simdikitarih;
        $kullanici = $_POST["delete_userid"] = $kullanici_id;
        $detay = $_POST['delete_detail'];
        $kan_stok_sil = canceldetail("blood_stock", "id", $id, $detay, $kullanici, $tarih);
        if ($kan_stok_sil == 1) {?>
            <script>
                alertify.success('Silme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Silme İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN ÇIKIŞ KURUM
elseif ($islem=="sql_kan_cikis_kurum") {
    if ($_POST) {
        $kan_cikis_kurum = direktekle("blood_exit_institution", $_POST);
        if ($kan_cikis_kurum == 1) {
            $kanstokid = $_POST["blood_stockid"];
            unset($_POST);
            $stokid = $kanstokid;
            $_POST["blood_stock_status"] = 28977;
            $_POST["update_userid"] = $kullanici_id;
            $_POST["update_datetime"] = $simdikitarih;

            $kantalepdurumguncelle = direktguncelle("blood_stock", "id", $stokid, $_POST);
            if ($kantalepdurumguncelle == 1) {
            } ?>
            <script>
                alertify.success('Kuruma Kan Çıkış İşlemi Başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('Kuruma Kan Çıkış İşlemi Başarısız');
            </script>
        <?php }
    }
}

//SQL KAN ÇIKIL KURUM SİL
else if($islem=="sql_kan_cikis_kurum_sil") {
    if ($_POST) {
        $id = $_POST["id"];
        $tarih = $_POST["delete_datetime"] = $simdikitarih;
        $kullanici = $_POST['delete_userid'] = $kullanici_id;
        $detay = $_POST['delete_detail'];

        $sql = canceldetail("blood_exit_institution", "id", $id, $detay, $kullanici, $tarih);
        if($sql == 1){
            $kanstokid = $_POST["blood_stockid"];
            unset($_POST);
            $stokid = $kanstokid;
            $_POST["blood_stock_status"] = 28970;
            $_POST["update_userid"] = $kullanici_id;
            $kantalepdurumguncelle = direktguncelle("blood_stock", "id", $stokid, $_POST);
            var_dump($kantalepdurumguncelle);
            if ($kantalepdurumguncelle == 1) {
            }
        }?>
            <script>
                alertify.success('Kuruma Çıkış İşlemini Geri Alma Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Kuruma Çıkış İşlemini Geri Alma Başarısız');
            </script>
        <?php }
}

//SQL PROTOKOL ARA JSON GETİR
//else if($islem=="sql_protokol_ara"){
//    $protokol=$_POST['protokol'];
//
//    $protokol=verilericoklucek("select hasta_kayit.id as hastakayitid as hasta_kayit.tc_kimlik as tckimlikno ,hastalar.*,hasta_kayit.*,kullanicilar.*,kan_talep.*,kullanicilar.name_surname as doktorunadi , kan_talep.hasta_protokol_no as protokolno from hasta_kayit inner join hastalar on hasta_kayit.tc_kimlik=hastalar.tc_kimlik  inner join kullanicilar on hasta_kayit.hekim=kullanicilar.id inner join kan_talep on hasta_kayit.id=kan_talep.hasta_protokol_no where kan_talep.kan_talep_durum='28831'and kan_talep.durum='1' and hasta_kayıt.id='$protokol'");
//    $protokol_json = json_encode($protokol);
//    echo $protokol_json;
//}


//SQL HASTA KAN TALEP
else if ($islem=="ameliyat_kan_talep_bilgileri_kayit"){
    $_POST['add_date']=$simdikitarih;
    $_POST['blood_demand_time']=$simdikitarih;
    $_POST['insert_userid'] = $kullanici_id;
    $_POST['requester_physicianid']=$kullanici_id;


    unset($_POST['istenilen_kan_turu']);
    unset($_POST['kan_kalep_listesi_length']);

    unset($_POST['blood_demandid']);
    $kan_talep_olustur = direktekle("blood_demand",$_POST);

    if($kan_talep_olustur==1){ ?>
        <script>
            alertify.set('notifier','delay', 7);
            alertify.success('Kan talep kaydı oluşturuldu!!');
        </script>
    <?php  }else{ ?>
        <script>
            alertify.set('notifier','delay', 7);
            alertify.error('Kayıt oluşturulamadı!!!');
        </script>
    <?php }

}

//SQL HASTA KAN TALEP ONAY DURUM TEYİT
else if ($islem=="hasta_kan_talep_onaylanmasını_teyit_et"){
    $_POST['service_confirmation_date']=$simdikitarih;
    $_POST['service_confirming_user'] = $kullanici_id;
    $id=$_POST['blood_demandid'];

    unset($_POST['blood_demandid']);
    $sonuc = direktguncelle("blood_demand","blood_demandid",$id,$_POST);

    if ($sonuc == 1) { ?>

        <script>
            alertify.set('notifier','delay', 7);
            alertify.success('Kan talep onay teyit başarılı');
        </script>

    <?php } else { ?>

        <script>
            alertify.set('notifier','delay', 7);
            alertify.danger('Kan talep onay teyit başarısız');
        </script>

    <?php } }

//SQL KAN TALEP SİL
else if ($islem=="kan_talep_sil"){

    $id=$_POST['id'];
    $detay=$_POST['delete_detail'];
    $silme=$kullanici_id;
    $tarih=date('Y/m/d H:i:s');

    $ameliyatsil=canceldetail("blood_demand","blood_demandid",$id,$detay,$silme,$tarih);

    if ($ameliyatsil == 1){
        echo "Kan talep silme i̇şlemi başarılı";
    } else {
        echo "Kan talep silme i̇şlemi başarısız..";
    }

}

//SQL KAN DÜZENELE JSON OLARAK ÇAĞIR
else if ($islem=="kan_talep_düzenle_json_getir"){
    $kan_talep_id = $_POST['kan_talep_id'];
    $array_kan_talep = verilericoklucek("select * from blood_demand where blood_demandid='$kan_talep_id'");
    $json_kan_talep = json_encode($array_kan_talep);
    echo $json_kan_talep;
}

//SQL KAN TALEBİ GUNCELLE
else if ($islem=="kan_talep_guncelle"){

    $id=$_POST['kan_talep_id'];

    unset($_POST['kan_kalep_listesi_length']);
    unset($_POST['blood_demandid']);
    unset($_POST['kan_talep_id']);

    $sql = direktguncelle("blood_demand","blood_demandid",$id,$_POST);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Kan Talep Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Kan Talep Güncelleme Başarısız');
        </script>

    <?php }}



?>


