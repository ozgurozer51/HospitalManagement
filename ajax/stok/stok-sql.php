<?php
include '../../controller/fonksiyonlar.php';
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

session_start();
ob_start();
$islem = $_GET["islem"];


if ($islem == "depo-stok-sil") {
    $id = $_POST["id"];
    $delete_detail = $_POST["delete_detail"];
    $kullanici = $_SESSION["id"];
    $tanimlama_sil = canceldetail("stock_receipt_move", "id", $id, $delete_detail);

    if ($tanimlama_sil == 1) {
        echo "İşlem Başarılı";
    } else {
        echo "İşlem Başarısız";
    }
}
if ($islem == "depodaki-urunu-guncelle") {
    $id = $_POST["id"];

    $update = direktguncelle("stock_receipt_move", "id", $id, $_POST);
    if ($update == 1) {
        echo "Güncelleme Başarılı";
    } else {
        echo "Bilinmeyen Bir Hata İle Karşılaşıldı";
    }
}
if ($islem == "sql-firma-sil") {
    $id = $_POST["id"];
    $delete_detail = $_POST["delete_detail"];
    $kullanici = $_SESSION["id"];
    $firma_sil = canceldetail("companies", "id", $id, $delete_detail);

    if ($firma_sil == 1) {
        echo "Silme İşlemi Başarılı";
    } else {
        echo "Bilinmeyen Bir Hata İle Karşılaşıldı";
    }
}
if ($islem == "stok-karti-sil") {
    $id = $_POST["id"];
    $delete_detail = $_POST["delete_detail"];
    $kullanici = $_SESSION["id"];
    $stok_sil = canceldetail("stock_card", "id", $id, $delete_detail);

    if ($stok_sil == 1) {
        echo "İşlem Başarılı";
    } else {
        echo "İşlem Başarısız";
    }
}
if ($islem == "stok-kart-guncelle") {
    $id = $_POST["id"];

    $update = direktguncelle("stock_card", "id", $id, $_POST);
    if ($update == 1) {
        $stock_type_arr = [
                'stock_type' => $_POST["stock_type"],
                ];
        $istekleri_guncelle = direktguncelle("stock_request_move","given_stock_cardid",$id,$stock_type_arr);
        if ($istekleri_guncelle == 1){
            $fatura_guncelle = direktguncelle("stock_invoice_pen","stock_cardid",$id,$stock_type_arr);
            if ($fatura_guncelle == 1){
                echo "<script>alertify.success('İşlem Başarılı')</script>";
            }else{
                echo "<script>alertify.warning('Beklenmeyen Bir Hata İle Karşılaşıldı')</script>";
            }
        }else{
            echo "<script>alertify.warning('Beklenmeyen Bir Hata İle Karşılaşıldı')</script>";
        }
    } else {
        echo "<script>alertify.error('Bilinmeyen Bir Hata İle Karşılaşıldı')</script>";
    }
}
if ($islem == "yeni-stok-ekle") {
    $stok_kart_ekle = direktekle("stock_card", $_POST);
    if ($stok_kart_ekle == 1) {
        echo "<script>alertify.success('Kayıt Başarılı')</script>";
    } else {
        echo "<script>alertify.warning('Bilinmeyen Bir Hata İle Karşılaşıldı')</script>";
        var_dump($stok_kart_ekle);
    }
}
if ($islem == "add-new-companies") {
    $company_code = $_POST["company_code"];

    $varmi = tek(" SELECT * FROM companies WHERE company_code='$company_code' AND status=1");

    if ($varmi == true) {
        echo "<script>alertify.error('Bu Firma Daha Önce Eklenmiştir')</script>";
    } else {
        $stok_kart_ekle = direktekle("companies", $_POST);
        if ($stok_kart_ekle == 1) {
            echo "<script>alertify.success('Kayıt Başarı İle Oluşturuldu')</script>";
        } else {
            echo "<script>alertify.warning('Bilinmeyen Bir Hata İle Karşılaşıldı')</script>";
        }
    }
}
if ($islem == "depo-guncelle") {
    $id = $_POST["id"];

    $update = direktguncelle("warehouses", "id", $id, $_POST);
    if ($update == 1) {
        echo "İşlem Başarılı";
    } else {
        echo "Bilinmeyen Bir Hata İle Karşılaşıldı";
    }
}
if ($islem == "delete-warehouse") {
    $id = $_POST["id"];
    $delete_detail = $_POST["delete_detail"];
    $kullanici = $_SESSION["id"];

    $stok_sil = canceldetail("warehouses", "id", $id, $delete_detail);

    if ($stok_sil == 1) {
        echo "İşlem Başarılı";
    } else {
        echo "İşlem Başarısız";
    }

}
if ($islem == "add-new-warehouse") {
    $mkys_code = $_POST["mkys_code"];

    $varmi = tek(" SELECT * FROM warehouses WHERE mkys_code='$mkys_code' AND status=1");

    if ($varmi == true) {
        echo 3;
    } else {
        $stok_kart_ekle = direktekle("warehouses", $_POST);
        if ($stok_kart_ekle == 1) {
            echo 1;
        } else {
            echo 2;
        }
    }
}

if ($islem == "depo-ismi-getir") {
    $warehouse_id = $_GET["warehouse_id"];
    $depo_adi = tek(" SELECT * FROM warehouses WHERE id='$warehouse_id' AND status=1");

    $veriler_json = json_encode($depo_adi);
    echo $veriler_json;
}

if ($islem == "depodaki-urunu-getir") {
    $depo_id = $_POST["id"];
    $verileri_getir = tek(" SELECT * FROM stock_receipt_move WHERE id='$depo_id' AND status=1");

    $veriler_json = json_encode($verileri_getir);
    echo $veriler_json;
}


if ($islem == "depodaki-urunu-sil") {
    $id = $_POST["id"];
    $delete_detail = $_POST["delete_detail"];
    $stok_sil = canceldetail("stock_receipt_move", "id", $id, $delete_detail);

    if ($stok_sil == 1) {
        echo "İşlem Başarılı";
    } else {
        echo "İşlem Başarısız";
    }
}
if ($islem == "transfer-stock") {

    $alinan_depo_id = $_POST["alinacak_depo"];
    $aktarilan_depo_id = $_POST["aktarilacak_depo"];
    $urun_id = $_POST["urun_id"];
    $miktar = $_POST["miktar"];

    $depoda_varmi = tek("select * from stock_receipt_move where status=1 and warehouse_id='$alinan_depo_id' and stock_cardid='$urun_id'");
    if ($depoda_varmi > 0){
        $aktarilacak_depoda_varmi = tek(" select * from stock_receipt_move where status=1 warehouse_id='$aktarilan_depo_id' and stock_cardid='$urun_id'");
        if ($aktarilacak_depoda_varmi > 0){
                $alinan_depo_azalt = $depoda_varmi["stock_amount"];
                if ($alinan_depo_azalt < $miktar){
                    echo 300; // burada aktarilan degerin depodaki miktardan fazla olduğunu anlatıyoruz
                }else{
                $toplam_azalt = $alinan_depo_azalt - $miktar;
                $deger_arr = [
                        "stock_amount" => $toplam_azalt
                        ];
                $ilk_depo_guncelle = tek(" UPDATE stock_receipt_move SET stock_amount='$toplam_azalt' WHERE stock_cardid='$urun_id' AND warehouse_id='$alinan_depo_id'");
            if ($ilk_depo_guncelle == 1){
            $aktarilan_urun_adedi = $aktarilacak_depoda_varmi["stock_amount"];
            $toplam = $aktarilan_urun_adedi + $miktar;
            $deger_arr = [
                    'stock_amount' => $toplam
                    ];
            $depodaki_miktari_guncelle = direktguncelle("stock_receipt_move","stock_cardid",$urun_id,$deger_arr);
            if ($depodaki_miktari_guncelle == 1){
                echo 1;
            }else{
                echo 2; // burası veritabanı hatası kodu
            }
            }else{
                echo 3; // burası veritabanı hatası kodu
            }
                }
        }else{
            $alinan_depo_azalt = $depoda_varmi["stock_amount"];
            if ($alinan_depo_azalt < $miktar){
                echo 300;
            }else{
                $toplam_azalt = $alinan_depo_azalt - $miktar;
//                $deger_arr = [
//                        "stock_amount" => $toplam_azalt
//                        ];

                $ilk_depo_guncelle = tek(" UPDATE stock_receipt_move SET stock_amount='$toplam_azalt' WHERE stock_cardid='$urun_id' AND warehouse_id='$alinan_depo_id'");
                if ($ilk_depo_guncelle == 1){
                    $ekle = direktekle("stock_receipt_move",$depoda_varmi);
                    if ($ekle == 1){
                        echo 1;
                    }else{
                        echo 4;
                    }
                }else{
                    echo 5;
                    print_r($ilk_depo_guncelle);
                }
            }
        }
    }else{
        echo 404; // burası kayıt veritabanında bulunamadı kodu
        print_r($depoda_varmi);
    }
}
if ($islem == "fatura-olustur") {
    $ekle = direktekle("stock_receipt", $_POST);
    if ($ekle == 1) {
        $id = islemtanimsoneklenen("stock_receipt");
        echo trim($id);
    } else {
        echo '<script>alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
    }
}


if ($islem == "fis-getir") {
    $id = $_GET["id"];
    $sorgu = tek(" SELECT stock_cardid,companyid,warehouseid,stock_type FROM stock_receipt WHERE id='$id' AND move_type = 1 AND status=1");
    if ($sorgu["stock_cardid"] > 0) {
        $ids = $sorgu["stock_cardid"];
        $barkod_ver = verilericoklucek(" SELECT barcode FROM stock_card WHERE id IN($ids) AND status=1");

        $json = json_encode($barkod_ver);
        echo $json;
    } else {
        $nullArray = [
            'barcode' => 'Bulunamadı',
            'discount_percent' => "Bulunamadı",
            'sale_unit_price' => 'Bulunamadı',
            'stock_name' => 'Bulunamadı',
            'id' => 'Bulunamadı'
        ];
        $nullJson = json_encode($nullArray);
        echo $nullJson;
    }

}
if ($islem == "fis-bilgileri") {
    $id = $_GET["id"];
    $sorgu = tek("
    SELECT warehouses.warehouse_name as depo_adi,companies.company_name as firma_adi, stock_type as stok_tur,
           warehouses.id as warehouse_id, companies.id as company_id
    FROM stock_receipt
    INNER JOIN warehouses on stock_receipt.warehouseid = warehouses.id
    INNER JOIN companies on stock_receipt.companyid = companies.id
    WHERE stock_receipt.id='$id' AND stock_receipt.move_type=1");

    if ($sorgu > 0) {
        $company_name = $sorgu["firma_adi"];
        $company_id = $sorgu["company_id"];
        $warehouse_name = $sorgu["depo_adi"];
        $warehouse_id = $sorgu["warehouse_id"];
        $stock_type = islemtanimgetirid($sorgu["stok_tur"]);
        $stock_type_number = $sorgu["stok_tur"];

        $json_arr = [
            'stock_type_number' => $stock_type_number,
            'company_name' => $company_name,
            'warehouse_name' => $warehouse_name,
            'stock_type' => $stock_type,
            'company_id' => $company_id,
            'warehouse_id' => $warehouse_id
        ];
        $json = json_encode($json_arr);
        echo $json;
    } else {
        $new_arr = [
            'company_name' => 'Bulunamadı',
            'warehouse_name' => 'Bulunamadı',
            'stock_type' => 'Bulunamadı'
        ];
        $err_json = json_encode($new_arr);
        echo $err_json;
    }
}
if ($islem == "barkod-stok-getir") {
    $id = $_GET["barcode"];

    $veriler = tek(" SELECT * FROM stock_card  WHERE barcode='$id' AND status=1");
    if ($veriler > 0) {
        $json = json_encode($veriler);
        echo $json;
    } else {
        $data = [
            'stock_name' => 'Bilinmiyor',
            // BURAYA EKLENECEK KEYLER VAR // BURAYA İHTİYAÇ KALMADI ARTIK
        ];
        $er_json = json_encode($data);
        echo $er_json;
    }
}
if ($islem == "sql-barkod-bilgisi") {
    $barcode = $_GET["barcode"];
    $tekli_sorgu = tek(" SELECT stock_sut_code,movable_code,sale_unit_price FROM stock_card WHERE barcode='$barcode' AND status=1");

    if ($tekli_sorgu > 0) {
        $json = json_encode($tekli_sorgu);
        echo $json;
    } else {
        $new_arr = [
            'stock_sut_code' => 'Bulunamadı',
            'movable_code' => 'Bulunamadı',
            'sale_unit_price' => 'Bulunamadı'
        ];
        $err_json = json_encode($new_arr);
        echo $err_json;
    }

}
if ($islem == "bayi-no-getir") {
    $id = $_GET["company_id"];

    $sorgu = tek("SELECT dealership_number FROM companies WHERE id='$id' AND status=1");
    $explode = explode(',', $sorgu["dealership_number"]);

    $json = json_encode($explode);
    echo $json;

}
if ($islem == "add-stock-from-warehouses") {
    $barcode = $_POST["barcode"];

    $stock_amount = $_POST["stock_amount"];

    $tek_sorgu = tek(" SELECT * FROM stock_receipt_move WHERE barcode='$barcode' AND status=1");
    if ($tek_sorgu == true) {
        $id = $tek_sorgu["id"];
        $yeni_deger = $stock_amount + $tek_sorgu["stock_amount"];

        $eklenecek_arr = [
            'stock_amount' => $yeni_deger
        ];
        $update = direktguncelle("stock_receipt_move", "id", $id, $eklenecek_arr);

        if ($update == 1) {
            echo "<script>alertify.success('İşlem Başarılı')</script>";
        } else {
            echo "<script>alertify.success('Bilinmeyen Bir Hata İle Karşılaşıldı...')</script>";
        }
    } else {
        $ekle = direktekle("stock_receipt_move", $_POST);
        if ($ekle == 1) {
            echo "<script>alertify.success('İşlem Başarılı')</script>";
        } else {
            echo "<script>alertify.success('Bilinmeyen Bir Hata İle Karşılaşıldı...')</script>";
        }
    }
}
if ($islem == "deponun-urunleri") {
    $id = $_GET["warehouse_id"];
    $sorgu = verilericoklucek(" SELECT stock_name FROM stock_receipt_move WHERE warehouse_id='$id' AND status=1");

    $json = json_encode($sorgu);
    echo $json;
}
if ($islem == "fis-ile-barkod-getir") {
    $id = $_GET["id"];
    $sorgu = tek(" SELECT stock_cardid,companyid,warehouseid,stock_type FROM stock_receipt WHERE id='$id' AND move_type = 2 AND status=1");
    if ($sorgu["stock_cardid"] > 0) {
        $ids = $sorgu["stock_cardid"];
        $barkod_ver = verilericoklucek(" SELECT barcode FROM stock_card WHERE id IN($ids) AND status=1");
        $json = json_encode($barkod_ver);
        echo $json;
    } else {
        return null;
    }

}
if ($islem == "depodan-cikis") {
    $receipt_id = $_POST["stock_receiptid"];
    $stock_amount = $_POST["stock_amount"];
    $barcode = $_POST["barcode"];

    $sorgu = tek(" SELECT * FROM stock_receipt_move WHERE barcode='$barcode' AND status=1");
    if ($sorgu == true) {

        $new_stock_amount = $sorgu["stock_amount"] - $stock_amount;
        $add_arr = [
            'stock_amount' => $new_stock_amount
        ];
        $update_stock_amount = direktguncelle("stock_receipt_move", "barcode", $barcode, $add_arr);
        if ($update_stock_amount == 1) {

            $stok_son_hali = tek("SELECT stock_amount as stok_durum FROM stock_receipt_move WHERE barcode='$barcode' AND status=1");
            $kritik_sorgu = tek("
            SELECT stock_card.critical_stock_amount as kritik_stok
            FROM stock_receipt_move 
            INNER JOIN stock_card ON
            stock_receipt_move.barcode = stock_card.barcode
            WHERE stock_card.barcode='$barcode' AND stock_receipt_move.status=1 AND stock_card.status=1");

            if ($kritik_sorgu["kritik_stok"] >= $stok_son_hali["stok_durum"]) {
                echo '<script>alertify.warning("Bu Ürün Kritik Stokta Acilen İstek Oluşturun")';
            }
        } else {
            echo '<script>alertify.danger("Bilinmeyen Bir Hata İle Karşılaşıdlı")</script>';
        }
    }
}
if ($islem == "add-new-request-from-units") {

    $MALZEMELER = $_POST;
    $MALZEME_COUNT = count($_POST['material_info']);
    $topla = array();
    $KAYIT = $_POST;

    for ($i = 0; $i < $MALZEME_COUNT; $i++) {
        $_POST['doctorid'] = $KAYIT['doctorid'];
        $_POST['stock_request_unitid'] = $KAYIT['stock_request_unitid'];
        $_POST['given_stock_cardid'] = $KAYIT['given_stock_cardid'][$i];
        $_POST['stock_type'] = $KAYIT['stock_type'][$i];
        $_POST['material_info'] = $KAYIT['material_info'][$i];
        $_POST['request_note'] = $KAYIT['request_note'][$i];
        $_POST['requested_amount'] = $KAYIT['requested_amount'][$i];
        $_POST['urgency_status'] = $KAYIT['urgency_status'][$i];
        $_POST['description'] = $KAYIT['description'][$i];

        $ekle = direktekle("stock_request_move", $_POST);
    }
    if ($ekle == 1) {
        echo '<script>alertify.success("İstek Başarıyla Oluşturldu")</script>';
    } else {
        echo '<script>alertify.warning("İstek Kaydı Oluşturulamadı")</script>';
    }

}
if ($islem == "istek-karsila-sql") {
    $id = $_POST["id"];
    $stock_cardid = $_POST["given_stock_cardid"];
    $warehouse_id = $_POST["given_from_warehouse"];
    $verilen_miktar = $_POST["given_amount_from_warehouse"];

    $sorgu_arr = tek("select request_rejection_status,requested_amount from stock_request_move where id='$id'");
    $guncelleme_sorgusu = tek("SELECT stock_cardid,warehouse_id,stock_amount FROM stock_receipt_move WHERE warehouse_id='$warehouse_id' AND stock_cardid='$stock_cardid'");
    if ($sorgu_arr["request_rejection_status"] == 1) {
        echo '<script>alertify.error("Bu İstek Zaten Karşılandı");</script>';
    } else {
        if ($verilen_miktar < $sorgu_arr["requested_amount"]) {
            $requset_deger = $sorgu_arr["requested_amount"];
            $aktarilacak_deger = $requset_deger - $verilen_miktar;
            $guncelle_arr = [
                'requested_amount' => $aktarilacak_deger,
                'given_stock_cardid' => $stock_cardid,
                'given_from_warehouse' => $warehouse_id,
                'given_amount_from_warehouse' => $verilen_miktar,
                'request_rejection_status' => 0
            ];
            $istek_guncelle = direktguncelle("stock_request_move", "id", $id, $guncelle_arr);
            if ($istek_guncelle == 1) {
                $son_deger = $guncelleme_sorgusu["stock_amount"];
                $aktarilacak_deger = $son_deger - $verilen_miktar;
                $aktarilacak_arr = [
                    "stock_amount" => $aktarilacak_deger
                ];
                $stok_guncelle = direktguncelle("stock_receipt_move", "stock_cardid", $stock_cardid, $aktarilacak_arr);
                if ($stok_guncelle == 1) {
                    echo '<script>alertify.success("İstek Karşılama İşlemi Başarı İle Gerçekleşti");</script>';
                } else {
                    echo '<script>alertify.error("İstek Karşılama Başarısız Oldu");</script>';
                }
            }
        } else {
            $guncelle_arr = [
                'given_stock_cardid' => $stock_cardid,
                'given_from_warehouse' => $warehouse_id,
                'given_amount_from_warehouse' => $verilen_miktar,
                'request_rejection_status' => 1
            ];
            $istek_guncelle = direktguncelle("stock_request_move", "id", $id, $guncelle_arr);
            if ($istek_guncelle == 1) {
                $son_deger = $guncelleme_sorgusu["stock_amount"];
                $aktarilacak_deger = $son_deger - $verilen_miktar;
                $aktarilacak_arr = [
                    "stock_amount" => $aktarilacak_deger
                ];
                $stok_guncelle = direktguncelle("stock_receipt_move", "stock_cardid", $stock_cardid, $aktarilacak_arr);
                if ($stok_guncelle == 1) {
                    echo '<script>alertify.success("İstek Karşılama İşlemi Başarı İle Gerçekleşti");</script>';
                } else {
                    echo '<script>alertify.error("İstek Karşılama Başarısız Oldu");</script>';
                }
            }
        }
    }
}

if ($islem == "istek-red-sql") {
    $id = $_POST["id"];
    $stock_request_rejection_cause = $_POST["stock_request_rejection_cause"];
    $tek = tek(" SELECT request_rejection_status FROM stock_request_move WHERE id='$id'");
    if ($tek["request_rejection_status"] == 2) {
        echo '<script>alertify.error("Bu İstek Zaten Reddedilmiştir")</script>';
    } else {
        $kullanici = $_SESSION["id"];
        $gören_dizi = [
            'request_rejection_status' => 2,
            'stock_request_rejection_cause' => $stock_request_rejection_cause,
            'stock_request_rejection_userid' => $kullanici,
            'stock_request_rejection_time' => $simdikitarih
        ];
        $update = direktguncelle("stock_request_move", "id", $id, $gören_dizi);
        if ($update == 1) {
            echo '<script>alertify.success("Red İşlemi Başarıyla Gerçekleşti");</script>';
        } else {
            echo '<script>alertify.success("Bilinmeyen Bir Hata İle Karşılaşıldı");</script>';
        }
    }
}
if ($islem == "iade-al-sql") {
    $id = $_POST["id"];
    $given_id = $_POST["given_stock_id"];
    $requested_amount = $_POST["requested_amount"];
    $number_of_return = $_POST["number_of_return"];
    $reason_for_return = $_POST["reason_for_return"];
    $son_gonderilen = $requested_amount - $number_of_return;

    $guncel_array = [
        'given_amount_from_warehouse' => $son_gonderilen,
        'number_of_return' => $number_of_return,
        'reason_for_return' => $reason_for_return
    ];
    $first_update = direktguncelle("stock_request_move", "id", $id, $guncel_array);
    if ($first_update == 1) {
        $tek = tek(" SELECT stock_amount,stock_cardid FROM stock_receipt_move WHERE stock_cardid='$given_id'");

        $guncellenecek_toplam = $number_of_return + $tek["stock_amount"];
        $depo_arr = [
            'stock_amount' => $guncellenecek_toplam
        ];
        $second_update = direktguncelle("stock_receipt_move", "stock_cardid", $given_id, $depo_arr);
        if ($second_update == 1) {
            echo '<script>alertify.success("İade Alma İşlemi Başarılı");</script>';
        } else {
            echo '<script>alertify.success("İade İşleminizi Şu An İçin Gerçekleştiremiyoruz");</script>';
        }
    } else {
        echo '<script>alertify.success("Bilinmeyen Bir Hata İle Karşılaşıldı");</script>';
    }
}

if ($islem == "iade-et-sql") {
    $MALZEMELER = $_POST;
    $MALZEME_COUNT = count($_POST['stock_cardid']);
    $topla = array();
    $KAYIT = $_POST;

    for ($i = 0; $i < $MALZEME_COUNT; $i++) {
        $_POST['stock_cardid'] = $KAYIT['stock_cardid'][$i];
        $_POST['stock_barcode'] = $KAYIT['stock_barcode'][$i];
        $barcode = $KAYIT["stock_barcode"][$i];
        $_POST['warehouse_id'] = $KAYIT['warehouse_id'][$i];
        $_POST['company_id'] = $KAYIT['company_id'][$i];
        $_POST['dealership_number'] = $KAYIT['dealership_number'][$i];
        $_POST['number_of_returns'] = $KAYIT['number_of_returns'][$i];
        $iade_miktar = $KAYIT["number_of_returns"][$i];
        $_POST['reason_of_returns'] = $KAYIT['reason_of_returns'][$i];

        $ekle = direktekle("stock_returns", $_POST);
        $dusulecek_sorgu = tek("SELECT stock_amount FROM stock_receipt_move WHERE barcode='$barcode'");
        $yeni_miktar = $dusulecek_sorgu["stock_amount"] - $iade_miktar;
        $arr = [
            'stock_amount' => $yeni_miktar
        ];
        $yeni_deger_guncelle = direktguncelle("stock_receipt_move", "barcode", $barcode, $arr);
    }
    if ($ekle == 1) {
        echo '<script>alertify.success("Kayıt Oluşturuldu")</script>';
    } else {
        echo '<script>alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
    }
}
if ($islem == "muadil-ekle-sql") {
    $muadil = $_POST["stock_muadil_id"];
    $stock_cardid = $_POST["stock_id"];

    $var_sorgusu = tek("select 
       stock_muadil_id,
       stock_id 
from 
     stock_muadil
where
      status=1
AND 
      stock_muadil_id='$muadil' 
AND 
      stock_id='$stock_cardid'");
    if ($muadil == $stock_cardid) {
        echo '<script>alertify.warning("İlacın Muadili Kendisi Olamaz");</script>';
    } else {
        if ($var_sorgusu > 0) {
            echo '<script>alertify.warning("Eklemek İstediğiniz Muadil Daha Önceden Eklenmiştir");</script>';
        } else {
            $ekle = direktekle("stock_muadil", $_POST);
            if ($ekle == 1) {
                echo '<script>alertify.success("Muadil Eklendi");</script>';
            } else {
                echo '<script>alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı");</script>';
            }
        }
    }
}
if ($islem == "get-islem-muadil") {
    $id = $_GET["stock_id"];
    ?>

    <table class="table table-sm  table-bordered table-hover px-2 nowrap display w-100 display nowrap" id="muadil-table"
           style="background: white; width: 100%;">
        <thead class="table-light">
        <tr>
            <th>İlaç Adı</th>
            <th>Barkod No</th>
            <th>Muadil İlaç</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $last_muadil = tek("SELECT id,stock_muadilid,stock_name,barcode FROM stock_card WHERE status=1 AND id='$id' ");
        $muadil_id = $last_muadil["stock_muadilid"];
        $singular_muadil = singular("stock_card", "id", $muadil_id);
        ?>
        <tr id="<?php echo $last_muadil["id"]; ?>" class="change_muadil">
            <td><?php echo $last_muadil["stock_name"] ?></td>
            <td><?php echo $last_muadil["barcode"] ?></td>
            <td><?php echo $singular_muadil["stock_name"] ?></td>
        </tr>
        </tbody>
    </table>
    <script>
        $(".change_muadil").dblclick(function () {
            var id = $(this).attr("id");
            $(".change_muadil").css("background-color", "rgb(255,255,255)");
            $(this).css("background-color", "#60b3abad");
            $.get("ajax/stok/stok-modal.php?islem=change-muadil", {id: id}, function (getModal) {
                $("#get-modals").html(getModal);
            });
        })
    </script>
<?php }

if ($islem == "detayli-filtrele"){

$malzeme_adi = $_GET["malzeme_adi"];
$ehu_onay = $_GET["ehu_onay_durum"];
$mkys_kodu = $_GET["mkys_kodu"];
$istek_durum = $_GET["istek_durum"];
$tasinir_no = $_GET["tasinir_no"];
$malzeme_tipi = $_GET["malzeme_tipi"];
$malzeme_turu = $_GET["malzeme_turu"];
$baslangic_tarih = $_GET["baslangic_tarih"];
$bitis_tarih = $_GET["bitis_tarih"];

$sql = "
SELECT
       s_card.stock_name as stok_adi,
       s_card.stock_type as stok_tur,
       s_card.barcode as barkod,
       s_card.mkys_stock_code as mkys_kodu,
       s_card.movable_code as tasinir_kodu,
       s_card.prescription_type as malzeme_turu,
       sr_move.insert_datetime as depoya_giris_zamani,
       sr_move.stock_amount as depodaki_miktar,
       w.warehouse_name as depo_adi,
       srq_move.insert_datetime as istek_zamani,
       srq_move.request_rejection_status as istek_durumu,
       s_return.insert_datetime as iade_edilen_zaman,
       s_return.number_of_returns as iade_miktar,
       s_return.reason_of_returns as iade_sebep,
       s_card.ehu_confirmation_state as ehu_onay_durum,
       c.company_name as firma_adi
FROM stock_card as s_card
INNER JOIN stock_receipt_move as sr_move ON sr_move.stock_cardid = s_card.id
INNER JOIN stock_request_move as srq_move ON srq_move.given_stock_cardid = s_card.id
INNER JOIN stock_returns as s_return ON s_return.stock_cardid = s_card.id
INNER JOIN warehouses as w ON w.id=sr_move.warehouse_id
INNER JOIN companies as c ON c.id=sr_move.company_id
WHERE sr_move.status=1 AND srq_move.status=1 AND s_return.status=1 AND w.status=1";

if (isset($malzeme_adi) && $malzeme_adi != "" || $malzeme_adi != null) {
    $sql .= " AND s_card.stock_name='$malzeme_adi'";
}

if (isset($ehu_onay) && $ehu_onay != "Seçiniz...") {
    $sql .= " AND s_card.ehu_confirmation_state='$ehu_onay'";
}

if (isset($mkys_kodu) && $mkys_kodu != "" || $mkys_kodu != null) {
    $sql .= "AND s_card.mkys_stock_code='$mkys_kodu'";
}

if (isset($istek_durum) && $istek_durum != "Seçiniz...") {
    $sql .= " AND srq_move.request_rejection_status='$istek_durum' ";
}

if (isset($tasinir_no) && $tasinir_no != "" || $tasinir_no != null) {
    $sql .= " AND s_card.movable_code='$tasinir_no'";
}

if (isset($malzeme_tipi) && $malzeme_tipi != "Seçiniz..." && $malzeme_tipi != null) {
    $sql .= " AND s_card.stock_type='$malzeme_tipi'";
}

if (isset($malzeme_turu) && $malzeme_turu != "Seçiniz..." && $malzeme_turu != null) {
    $sql .= " AND s_card.prescription_type='$malzeme_turu'";
}


$aranacak_tarih = tredate($baslangic_tarih);
$aranacak_tarih2 = tredate($bitis_tarih);

if ($baslangic_tarih != null && $bitis_tarih != null || $baslangic_tarih != "" && $baslangic_tarih != "") {
    $sql .= " AND sr_move.insert_datetime BETWEEN '$aranacak_tarih' AND '$aranacak_tarih2' 
        OR s_return.insert_datetime BETWEEN '$aranacak_tarih' AND '$aranacak_tarih2'
         OR   srq_move.insert_datetime BETWEEN '$aranacak_tarih' AND '$aranacak_tarih2' ";
}

$veriler = verilericoklucek($sql);
?>
<script>
    $(document).ready(function () {
        $('.fiter-detail').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
    });
</script>

<font size="2">
    <table class="table table-bordered table-sm table-hover px-2 fiter-detail" style="background: white; width: 100%;">
        <thead class="table-light">
        <tr>
            <th>Ürün Adı</th>
            <th>Tür</th>
            <th>Barkod</th>
            <th>MKYS Kod</th>
            <th>T. Kod</th>
            <th>Reçete Türü</th>
            <th>D.G. Zaman</th>
            <th>D.Adı</th>
            <th>İstek Zamanı</th>
            <th>İstek</th>
            <th>D.Ç Zaman</th>
            <th>İade Miktarı</th>
            <th>Ehu Durum</th>
            <th>Firma</th>
            <th>Adet</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($veriler as $veri) {
            if ($veri["ehu_onay_durumu"] == 1) {
                $onay = "Onaylandı";
            } else {
                $onay = "Onaylanmadı";
            }
            if ($veri["istek_durumu"] == 1) {
                $son_deger = "Karşılandı";
            }
            if ($veri["istek_durumu"] == 2) {
                $son_deger = "Rededildi";
            }
            if ($veri["istek_durumu"] == 0) {
                $son_deger = "Bekliyor";
            }
            ?>
            <tr>
                <td><?php echo $veri["stok_adi"] ?></td>
                <td><?php echo islemtanimgetirid($veri["stok_tur"]) ?></td>
                <td><?php echo $veri["barkod"] ?></td>
                <td><?php echo $veri["mkys_kodu"] ?></td>
                <td><?php echo $veri["tasinir_kodu"] ?></td>
                <td><?php echo $veri["malzeme_turu"] ?></td>
                <td><?php echo $veri["depoya_giris_zamani"] ?></td>
                <td><?php echo $veri["depo_adi"] ?></td>
                <td><?php echo $veri["istek_zamani"] ?></td>
                <td><?php echo $son_deger ?></td>
                <td><?php echo $veri["iade_edilen_zaman"] ?></td>
                <td><?php echo $veri["iade_miktar"] ?></td>
                <td><?php echo $onay ?></td>
                <td><?php echo $veri["firma_adi"] ?></td>
                <td><?php echo $veri["depodaki_miktar"] ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <?php }

        if ($islem == "depo-filtre"){
        $ilac_adi = $_GET["ilac_adi"];
        $barkod = $_GET["barkod"];
        $depo_adi = $_GET["depo_id"];
        $firma_adi = $_GET["firma_id"];
        $stok_tur = $_GET["stok_tur"];

        $sql = "
SELECT
    srm.stock_name as urun_adi,
    srm.barcode as barkod,
    srm.lot_number as lot_no,
    srm.expiration_date as son_kullanma_tarih,
    srm.dealership_number as bayi_no,
    srm.insert_datetime as giris_tarihi,
    srm.stock_amount as urun_miktari,
    srm.stock_type as stok_turu,
    depo.warehouse_name as depo_adi,
    c.company_name as firma_ad
FROM stock_receipt_move as srm
         INNER JOIN warehouses as depo ON srm.warehouse_id = depo.id
         INNER JOIN companies c on srm.company_id = c.id
WHERE srm.status=1";

        if ($ilac_adi != "" && $ilac_adi != null) {
            $sql .= " AND srm.stock_name='$ilac_adi'";
        }
        if ($barkod != "" && $barkod != null) {
            $sql .= " AND srm.barcode='$barkod'";
        }
        if ($depo_adi != null && $depo_adi != "Seçiniz..." && $depo_adi != "") {
            $sql .= " AND depo.id='$depo_adi'";
        }
        if ($firma_adi != null && $firma_adi != "Seçiniz...") {
            $sql .= " AND c.id='$firma_adi'";
        }
        if ($stok_tur != "Seçiniz..." && $stok_tur != "" && $stok_tur != null) {
            $sql .= " AND srm.stock_type='$stok_tur'";
        }

        ?>
        <script>
            $(document).ready(function () {
                $('.fiter-detail').DataTable({
                    "responsive": true,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },
                });
            });
        </script>
        <table class="table table-bordered table-sm table-hover px-2 fiter-detail"
               style="background: white; width: 100%;">
            <thead class="table-light">
            <tr>
                <th>Ürün Adı</th>
                <th>Barkod</th>
                <th>Depo Adı</th>
                <th>Depoya Giriş Tarihi</th>
                <th>Getiren Firma</th>
                <th>Getiren Bayi</th>
                <th>Anlık Ürün Miktarı</th>
                <th>Lot No</th>
                <th>Stok Türü</th>
                <th>Son Kullanma Tarihi</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $veriler = verilericoklucek($sql);
            foreach ($veriler as $veri) {
                ?>
                <tr>

                <tr id="<?php echo $veri["id"] ?>" class="stock-id">
                    <td><?php echo $veri["urun_adi"] ?></td>
                    <td><?php echo $veri["barkod"] ?></td>
                    <td><?php echo $veri["depo_adi"] ?></td>
                    <td><?php echo $veri["giris_tarihi"] ?></td>
                    <td><?php echo $veri["firma_ad"] ?></td>
                    <td><?php echo $veri["bayi_no"] ?></td>
                    <td><?php echo $veri["urun_miktari"] ?></td>
                    <td><?php echo $veri["lot_no"] ?></td>
                    <td><?php echo islemtanimgetirid($veri["stok_turu"]) ?></td>
                    <td><?php echo $veri["son_kullanma_tarih"] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <?php }

            if ($islem == "iade-detay-getir") {

                $malzeme_adi = $_GET["malzeme_adi"];
                $birim_adi = $_GET["birim_adi"];
                $bas_tarih = $_GET["bas_tarih"];
                $bit_tarih = $_GET["bit_tarih"];

                $sql = "
                SELECT warehouses.warehouse_name as warehouse_name
                ,companies.company_name as company_name
                ,stock_card.stock_name as stock_name
                ,units.department_name
                ,units.id     
                ,stock_returns.* FROM
                    stock_returns
                        INNER JOIN warehouses on stock_returns.warehouse_id = warehouses.id
                        INNER JOIN companies on stock_returns.company_id = companies.id
                        INNER JOIN stock_card on stock_returns.stock_cardid=stock_card.id
                        INNER JOIN units on units.status = stock_returns.status
                        INNER JOIN stock_request_move AS srm ON srm.stock_request_unitid = units.id
                WHERE  stock_returns.status=1";

                if (isset($malzeme_adi) && $malzeme_adi != "" && $malzeme_adi != null) {
                    $sql .= " AND stock_card.stock_name='$malzeme_adi'";
                }

                if (isset($birim_adi) && $birim_adi != "Seçiniz..." && $birim_adi != null) {
                    $sql .= " AND units.id='$birim_adi'";
                }

                if (isset($bas_tarih) && isset($bit_tarih) && $bas_tarih != null && $bit_tarih != null && $bas_tarih != "" && $bit_tarih != "") {
                    $sql .= " AND stock_returns.insert_datetime BETWEEN '$bas_tarih' AND '$bit_tarih' ";
                }
                ?>
                <script>
                    $(document).ready(function () {
                        $("#iade-detay-filtre-tablosu").DataTable({
                            "responsive": true,
                            "language": {
                                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                            }
                        });
                    });
                </script>
                <br>
                <table class="table table-bordered  table-hover px-2 nowrap display w-100"
                       id="iade-detay-filtre-tablosu">
                    <thead class="table-light">
                    <tr>
                        <th>Mazleme Adı</th>
                        <th>Barkod</th>
                        <th>Depo Adi</th>
                        <th>Firma Ad</th>
                        <th>Bayi No</th>
                        <th>İade Edilen Miktar</th>
                        <th>İade Sebepleri</th>
                        <th>İade Edildiği Zaman</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $istekler = verilericoklucek($sql);
                    foreach ($istekler as $item) {
                        ?>
                        <tr id="<?php echo $item["id"] ?>">
                            <td><?php echo $item["stock_name"] ?></td>
                            <td><?php echo $item["stock_barcode"] ?></td>
                            <td><?php echo $item["warehouse_name"] ?></td>
                            <td><?php echo $item["company_name"] ?></td>
                            <td><?php echo $item["dealership_number"] ?></td>
                            <td><?php echo $item["number_of_returns"] ?></td>
                            <td><?php echo $item["reason_of_returns"] ?></td>
                            <td><?php echo $item["insert_datetime"] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php }

            if ($islem == "sil-sql-muadil") {
                $muadil_id = $_POST["stock_muadil_id"];
                $stock_id = $_POST["stock_id"];
                $delete_detail = $_POST["delete_detail"];


                $muadil_id = tek("select id from stock_muadil where stock_muadil_id='$muadil_id' AND stock_id ='$stock_id' AND status=1");
                $id = $muadil_id["id"];
                
                $delete = canceldetail("stock_muadil", "id", $id, $delete_detail);
                if ($delete == 1) {
                    echo '<script>alertify.success("Muadil Silindi")</script>';
                } else {
                    echo '<script>alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
                }
            }

            if ($islem == "faturaya-kalem-ekle") {
                $stok_id = $_POST["stock_cardid"];
                $stock_type = $_POST["stock_type"];
                $receipt_id = $_POST["stock_receiptid"];

                $sorgu_varmi = tek("
SELECT
       stock_amount,stock_cardid,stock_receiptid
FROM 
     stock_invoice_pen
WHERE
      stock_cardid='$stok_id' 
  AND
      stock_receiptid='$receipt_id' 
  AND 
      status=1");
                $hareket_turu = $_POST["move_type"];
                $stock_receiptid = $_POST["stock_receiptid"];
                $stock_cardid = $_POST["stock_cardid"];
                $barcode = $_POST["barcode"];
                $stock_name = $_POST["stock_name"];
                $sale_price = $_POST["sale_price"];
                $stock_amount = $_POST["stock_amount"];
                $unit = $_POST["unit"];
                $lot_number = $_POST["lot_number"];
                $ats_number = $_POST["ats_number"];
                $expiration_date = $_POST["expiration_date"];
                $depo_id = $_POST["warehouse_id"];
                $company_id = $_POST["company_id"];

                if ($sorgu_varmi > 0) {
                    $veritabanindaki_miktar = $sorgu_varmi["stock_amount"];
                    $post_edilen_miktar = $_POST["stock_amount"];
                    $toplam = $veritabanindaki_miktar + $post_edilen_miktar;
                    $guncelle_arr = [
                        "stock_amount" => $toplam
                    ];
                    $guncelle = direktguncelle("stock_invoice_pen", "stock_receiptid", $receipt_id, $guncelle_arr);
                    if ($guncelle == 1) {
                        if ($hareket_turu == 1) {
                            $depodaki_miktar_sorgu = tek("select stock_cardid,stock_amount from stock_receipt_move where stock_cardid='$stok_id' AND status=1 AND warehouse_id='$depo_id'");
                            if ($depodaki_miktar_sorgu > 0) {
                                $depodaki_miktar = $depodaki_miktar_sorgu["stock_amount"];
                                $post_edilen_miktar = $stock_amount;
                                $deponun_yeni_toplami = $depodaki_miktar + $post_edilen_miktar;
                                echo "ilk if";
                                $depo_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deponun_yeni_toplami' WHERE stock_cardid='$stok_id' AND stock_receiptid='$stock_receiptid' AND warehouse_id='$depo_id'");
                                if ($depo_guncelle == 1) {
                                    echo 1;
                                } else {
                                    echo 2;
                                }
                            } else {
                                $depoya_eklenecek_dizi = [
                                    'stock_cardid' => $stock_cardid,
                                    'stock_receiptid' => $stock_receiptid,
                                    'barcode' => $barcode,
                                    'lot_number' => $lot_number,
                                    'sale_unit_price' => $sale_price,
                                    'expiration_date' => $expiration_date,
                                    'ats_query_number' => $ats_number,
                                    'unit' => $unit,
                                    'stock_amount' => $stock_amount,
                                    'stock_name' => $stock_name,
                                    'warehouse_id' => $depo_id,
                                    'company_id' => $company_id,
                                    'stock_type' => $stock_type,
                                ];

                                $depo_guncelle = direktekle("stock_receipt_move", $depoya_eklenecek_dizi);
                                if ($depo_guncelle == 1) {
                                    echo 1;
                                } else {
                                    echo 2;
                                }
                            }
                        }
                        if ($hareket_turu == 2) {
                            $depodaki_miktar_sorgu = tek("select stock_cardid,stock_amount from stock_receipt_move where stock_cardid='$stok_id' AND status=1 AND warehouse_id='$depo_id'");
                            if ($depodaki_miktar_sorgu > 0) {
                                $depodaki_miktar = $depodaki_miktar_sorgu["stock_amount"];
                                $post_edilen_miktar = $_POST["stock_amount"];
                                $deponun_yeni_toplami = $depodaki_miktar - $post_edilen_miktar;
                                echo "2 if";
                                $depo_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deponun_yeni_toplami' WHERE stock_cardid='$stok_id' AND stock_receiptid='$stock_receiptid' AND warehouse_id='$depo_id'");
                                if ($depo_guncelle == 1) {
                                    echo 1;
                                } else {
                                    echo 2;
                                }
                            } else {
                                echo 'bulunamadı';
                                echo 1;
                            }
                        }
                    }
                } else {
                    $fatura_kalem_ekle = direktekle("stock_invoice_pen", $_POST);
                    if ($fatura_kalem_ekle == 1) {
                        if ($hareket_turu == 1) {
                            $depodaki_miktar_sorgu = tek("select stock_cardid,stock_amount from stock_receipt_move where stock_cardid='$stok_id' AND status=1 AND warehouse_id='$depo_id'");
                            if ($depodaki_miktar_sorgu > 0) {
                                $depodaki_miktar = $depodaki_miktar_sorgu["stock_amount"];
                                $post_edilen_miktar = $_POST["stock_amount"];
                                $deponun_yeni_toplami = $depodaki_miktar + $post_edilen_miktar;
                                $depo_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deponun_yeni_toplami' WHERE stock_cardid='$stok_id' AND stock_receiptid='$stock_receiptid' AND warehouse_id='$depo_id'");
                                if ($depo_guncelle == 1) {
                                    echo "<script>alertify.success('mesaj1')";
                                } else {
                                    echo "<script>alertify.error('hata5')</script>";
                                }
                            } else {
                                $depoya_eklenecek_dizi = [
                                    'stock_cardid' => $stock_cardid,
                                    'stock_receiptid' => $stock_receiptid,
                                    'barcode' => $barcode,
                                    'lot_number' => $lot_number,
                                    'sale_unit_price' => $sale_price,
                                    'expiration_date' => $expiration_date,
                                    'ats_query_number' => $ats_number,
                                    'unit' => $unit,
                                    'stock_amount' => $stock_amount,
                                    'stock_name' => $stock_name,
                                    'warehouse_id' => $depo_id,
                                    'company_id' => $company_id,
                                    'stock_type' => $stock_type
                                ];

                                $depo_guncelle = direktekle("stock_receipt_move", $depoya_eklenecek_dizi);
                                if ($depo_guncelle == 1) {
                                    echo "<script>alertify.success('mesaj2')";
                                } else {
                                    echo "<script>alertify.error('hata4')</script>";
                                }
                            }
                        }
                        if ($hareket_turu == 2) {
                            $depodaki_miktar_sorgu = tek("select stock_cardid,stock_amount from stock_receipt_move where stock_cardid='$stok_id' AND status=1 AND warehouse_id='$depo_id'");
                            if ($depodaki_miktar_sorgu > 0) {
                                $depodaki_miktar = $depodaki_miktar_sorgu["stock_amount"];
                                $post_edilen_miktar = $_POST["stock_amount"];
                                $deponun_yeni_toplami = $depodaki_miktar - $post_edilen_miktar;
                                echo "son if";
                                $depo_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deponun_yeni_toplami' WHERE stock_cardid='$stok_id' AND stock_receiptid='$stock_receiptid' AND warehouse_id='$depo_id'");
                                if ($depo_guncelle == 1) {
                                    echo "<script>alertify.success('mesaj3')";
                                } else {
                                    echo "<script>alertify.error('hata3')</script>";
                                }
                            } else {
                                echo "<script>alertify.error('Silmek İstediğiniz Ürün Depoda Yok')</script>";
                            }
                        }
                    } else {
                        echo "<script>alertify.error('hata1')</script>";
                    }
                }
            }
            if ($islem == "faturadan-sil") {
                $stokID = $_POST["stock_id"];
                $warehouse_id = $_POST["warehouse_id"];
                $receipt_id = $_POST["receipt_id"];
                $move_type = $_POST["move_type"];
                $adedi = $_POST["stock_amount"];
                $invoice_pen_query = tek("
                    SELECT
                        *
                    FROM
                        stock_invoice_pen
                    WHERE
                        stock_cardid='$stokID'
                    AND 
                        stock_receiptid='$receipt_id'
                    AND 
                        status=1
                ");
                $delete_detail = "Fatura Tanımlarken Silinmiştir..";
                $penID = $invoice_pen_query["id"];
                if ($invoice_pen_query["status"] == 0){
                    echo 2;
                }else{
                $sil = canceldetail("stock_invoice_pen","id",$penID,$delete_detail);

                if ($sil == 1){
                    $tek_sorgu = tek(" SELECT * FROM stock_receipt_move WHERE status=1 AND stock_cardid='$stokID' AND stock_receiptid='$receipt_id'");
                    $id = $tek_sorgu["id"];
                    $depodaki_miktar = $tek_sorgu["stock_amount"];
                    $snc = $depodaki_miktar - $adedi;
                    $snc_arr = [
                            'stock_amount' => $snc
                    ];
                    $guncelle = direktguncelle("stock_receipt_move","id",$id,$snc_arr);
                    if ($guncelle == 1){
                        echo 'başarılı';
                    }else{
                        echo 'başarısız';
                    }
                }else{
                    $tek_sorgu = tek(" SELECT * FROM stock_receipt_move WHERE status=1 AND stock_cardid='$stokID' AND stock_receiptid='$receipt_id'");
                    $id = $tek_sorgu["id"];
                    $depodaki_miktar = $tek_sorgu["stock_amount"];
                    $snc = $depodaki_miktar - $adedi;
                    $snc_arr = [
                        'stock_amount' => $snc
                    ];
                    $guncelle = direktguncelle("stock_receipt_move","id",$id,$snc_arr);
                    if ($guncelle == 1){
                        echo 'başarılı';
                    }else{
                        echo 'başarısız';
                    }
                }
                }
            }

            if ($islem == "faturayı-onayla"){
                $id = $_POST["stock_receiptid"];
                unset($_POST["stock_receiptid"]);
                $guncelle = direktguncelle("stock_receipt","id",$id,$_POST);

                if ($guncelle == 1){
                    echo '<script>alertify.success("İşlem Başarılı")</script>';
                }else{
                    echo '<script>alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
                }
            }

            if ($islem == "fatura-vazgec"){
                $depo_id = $_POST["warehouse_id"];
                $receipt_id = $_POST["stock_receiptid"];
                $delete_detail = "Fatura Oluşturulmaktan Vazgeçti";

                $receipt_delete = canceldetail("stock_receipt","id",$receipt_id,$delete_detail);
                if ($receipt_delete == 1){
                 $vazgec = canceldetail("stock_invoice_pen","stock_receiptid",$receipt_id,$delete_detail);
                if ($vazgec == 1){
                    $cok_veri = verilericoklucek("
                    SELECT
                        sip.stock_cardid as stok_idsi,  
                        sip.stock_amount as kalem_miktar,
                        sip.stock_receiptid as kalem_receipt,
                        sip.move_type as kalem_hareket,
                        srm.stock_amount as depo_miktar,
                        srm.stock_receiptid as depo_receipt
                    FROM
                        stock_invoice_pen as sip
                    INNER JOIN stock_receipt_move as srm ON srm.stock_receiptid=sip.stock_receiptid
                    WHERE
                        srm.stock_receiptid='$receipt_id'
                    AND
                         srm.status=1
                    AND
                        sip.stock_receiptid='$receipt_id'
                    AND 
                        sip.warehouse_id='$depo_id'
                        ");

                    for ($i = 0 ; $i < count($cok_veri) ; $i++){
                    $kalem_hareket = $cok_veri[$i]["kalem_hareket"];
                        $stock_id = $cok_veri[$i]["stok_idsi"];
                        if ($kalem_hareket == 1){
                        $deger = $cok_veri[$i]["depo_miktar"] - $cok_veri[$i]["kalem_miktar"];
                        $veri_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deger' WHERE stock_receiptid='$receipt_id' AND warehouse_id='$depo_id' AND stock_cardid='$stock_id'");
                        }else{
                           $deger = $cok_veri[$i]["depo_miktar"] + $cok_veri[$i]["kalem_miktar"];
                           $veri_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$deger' WHERE stock_receiptid='$receipt_id' AND warehouse_id='$depo_id' AND stock_cardid='$stock_id'");
                        }
                    }
                }else{
                    echo '<script>alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
                }
                }else{
                    echo '<script>alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı")</script>';
                }
            }

            if ($islem == "fatura-filtre"){

                $firma_id = $_GET["firma_id"];
                $depo_id = $_GET["depo_id"];
                $alim_yontem = $_GET["alim_yontem"];
                $hareket_tur = $_GET["hareket_tur"];
                $bas_tarih = $_GET["bas_tarih"];
                $bit_tarih = $_GET["bit_tarih"];
                $tedarik_tur = $_GET["tedarik_turu"];
                $urun_adi = $_GET["urun_adi"];

                $sql = "
               SELECT
                    c.company_name as firma_adi,
                    w.warehouse_name as depo_adi,
                    sr.id as receipt_id,
                    sr.supply_type as tedarik_tur,
                    sr.buying_method as alim_yontem,
                    sr.identifier_no as f_tanimlayici,
                    sr.tender_date as ihale_tarih,  
                    sr.bill_date as fatura_tarihi,
                    sr.delivery_person_info as teslim_eden_kisi,
                    sr.move_type as hareket_tur,
                    c.id,
                    w.id,
                    sr.move_type as hareket_tur,
                    sr.total_price as toplam_fiyat,
                    sip.stock_name as stok_adi  
               FROM
                    stock_receipt as sr
               INNER JOIN warehouses as w on sr.warehouse_id = w.id
               INNER JOIN companies as  c on sr.companyid = c.id
               INNER JOIN stock_invoice_pen as sip ON sip.stock_receiptid = sr.id
               WHERE
                    sr.status=1
                ";

                if (isset($firma_id) && $firma_id != ""){
                    $sql .= " AND c.id='$firma_id'";
                }
                if (isset($depo_id) && $depo_id != ""){
                    $sql .= " AND w.id='$depo_id'";
                }
                if (isset($tedarik_tur) && $tedarik_tur != "Seçiniz..."){
                    $sql .= " AND sr.supply_type='$tedarik_tur'";
                }
                if (isset($alim_yontem) && $alim_yontem != "Seçiniz..."){
                    $sql .= " AND sr.buying_method='$alim_yontem'";
                }
                if (isset($hareket_tur) && $hareket_tur != "Seçiniz..."){
                    $sql .= " AND sr.move_type='$hareket_tur'";
                }
                if (isset($bas_tarih) && $bas_tarih != "" || isset($bit_tarih) && $bit_tarih != ""){
                    $sql .= " AND sr.tender_date BETWEEN '$bas_tarih' AND '$bit_tarih' ";
                }
                if (isset($urun_adi) && $urun_adi != ""){
                    $sql .= " AND sip.stock_name='$urun_adi'";
                }

                $veriyi_cek = verilericoklucek($sql);

                if ($veriyi_cek > 0){
                    $json = json_encode($veriyi_cek);
                    echo $json;
                }else{
                    echo 2;
                }
            }
            if ($islem == "update-receipt-from-detail"){
                $id = $_POST["id"];
                $stock_amount = $_POST["stock_amount"];
                $kdv_price_applied = $_POST["kdv_price_applied"];
                $discount_price_applied = $_POST["discount_price_applied"];
                $kdv_total = $_POST["kdv_total"];
                $discount_total = $_POST["discount_total"];
                
                $faturadaki_degerler = tek("
                    SELECT
                        kdv_price_applied,
                        stock_receiptid,
                        stock_amount,
                        discount_total,
                        kdv_total,
                        discount_price_applied
                    FROM
                        stock_invoice_pen
                    WHERE
                        status=1
                    AND
                        id=$id");

                $receiptid = $faturadaki_degerler["stock_receiptid"];
                $faturadaki_kdv = $faturadaki_degerler["kdv_price_applied"];
                $faturadaki_iskonto = $faturadaki_degerler["discount_price_applied"];
                $faturadaki_adet = $faturadaki_degerler["stock_amount"];
                $faturadaki_genel_toplam = $faturadaki_degerler["kdv_total"];
                $faturadaki_toplam_ucret = $faturadaki_degerler["discount_total"];
                $receipt_totaller = tek(" SELECT total_amount,total_kdv,total_discount,total_free_wat,total_price FROM stock_receipt WHERE id='$receiptid'");

                $total_amount = $receipt_totaller["total_amount"];
                $total_kdv = $receipt_totaller["total_kdv"];
                $total_discount = $receipt_totaller["total_discount"];
                $total_free_wat = $receipt_totaller["total_free_wat"];
                $total_price = $receipt_totaller["total_price"];

                $cikacak_kdv = 0;
                $cikacak_iskonto = 0;
                $cikacak_adet = 0;
                $genel_toplam = 0;
                $cikacak_toplam_ucret = 0;

                if ($faturadaki_kdv > $kdv_price_applied){
                    $cikacak_kdv = $faturadaki_kdv -$kdv_price_applied;
                    $total_kdv -= $cikacak_kdv;
                }else if ($faturadaki_kdv < $kdv_price_applied){
                    $cikacak_kdv = $kdv_price_applied - $faturadaki_kdv ;
                    $total_kdv += $cikacak_kdv;
                }
                if ($faturadaki_iskonto > $discount_price_applied){
                    $cikacak_iskonto = $faturadaki_iskonto - $discount_price_applied;
                    $total_discount -= $cikacak_iskonto;
                }else if ($faturadaki_iskonto < $discount_price_applied){
                    $cikacak_iskonto = $discount_price_applied - $faturadaki_iskonto;
                    $total_discount += $cikacak_iskonto;
                }
                if ($faturadaki_adet > $stock_amount){
                    $cikacak_adet = $faturadaki_adet - $stock_amount;
                    $total_amount -= $cikacak_adet;
                }else if ($faturadaki_adet < $stock_amount){
                    $cikacak_adet =  $stock_amount - $faturadaki_adet ;
                    $total_amount += $cikacak_adet;
                }
                if ($faturadaki_toplam_ucret > $discount_total){
                    $cikacak_toplam_ucret = $faturadaki_toplam_ucret - $discount_total;
                    $total_free_wat -= $cikacak_toplam_ucret;
                }else if ($faturadaki_toplam_ucret < $discount_total){
                    $cikacak_toplam_ucret = $discount_total - $faturadaki_toplam_ucret;
                    $total_free_wat += $cikacak_toplam_ucret;
                }
                if ($faturadaki_genel_toplam > $kdv_total){
                    $genel_toplam = $faturadaki_genel_toplam - $kdv_total;
                    $total_price -= $genel_toplam;
                }else if ($faturadaki_genel_toplam < $kdv_total){
                    $genel_toplam = $kdv_total - $faturadaki_genel_toplam;
                    $total_price += $genel_toplam;
                }
                $receipt_update_arr = [
                        "total_kdv" => $total_kdv,
                        "total_discount" => $total_discount,
                        "total_amount" => $total_amount,
                        "total_free_wat" => $total_free_wat,
                        "total_price" => $total_price
                        ];

                $fatura_guncelle = direktguncelle("stock_receipt","id",$receiptid,$receipt_update_arr);
                if ($fatura_guncelle == 1){
                 $direkt_guncelle_ve_gonder = direktguncelle("stock_invoice_pen","id",$id,$_POST);
                if ($direkt_guncelle_ve_gonder == 1){
                    $tek = tek("select stock_receiptid from stock_invoice_pen where id='$id'");
                    $arr =[
                            'id'=>  $tek["stock_receiptid"]
                            ];
                    $json = json_encode($arr);
                    echo $json;
                }else{
                    echo 2;
                }
                }else{
                    echo 3;
                }
            }

            if ($islem == "fatura-guncelle-total"){
                $id = $_POST["id"];
                $warehouse_id = $_POST["warehouse_id"];
                $faturayi_guncelle = direktguncelle("stock_receipt","id",$id,$_POST);
                if ($faturayi_guncelle == 1){
                    $sql = " select warehouse_id from stock_invoice_pen where status=1 and stock_receiptid='$id'";
                    $tek = verilericoklucek($sql);
                    foreach ($tek as $veri){
                        if ($veri["warehouse_id"] != $warehouse_id){
                            $guncelle_arr_fatura_kalem  =[
                                    'warehouse_id' => $warehouse_id
                                    ];
                            $kalemi_guncelle_sonra_devam = direktguncelle("stock_invoice_pen","stock_receiptid",$id,$guncelle_arr_fatura_kalem);
                            if ($kalemi_guncelle_sonra_devam == 1){
                            $veri_warehouseid = $veri["warehouse_id"];
                            $cok_veri = verilericoklucek(" 
                                    select 
                                            sip.stock_cardid,
                                            sip.warehouse_id,
                                            sip.company_id,
                                            srm.id as depodaki_id
                                    from
                                            stock_invoice_pen as sip
                                    INNER JOIN stock_receipt_move srm ON srm.stock_cardid=sip.stock_cardid
                                    where 
                                          srm.warehouse_id='$veri_warehouseid' 
                                    and 
                                          sip.status=1");
                            foreach ($cok_veri as $guncelle){
                                $guncelle_id = $guncelle["depodaki_id"];
                                $guncelle_arr = [
                                        'warehouse_id' => $warehouse_id
                                        ];
                                $direkt_guncelle = direktguncelle("stock_receipt_move","id",$guncelle_id,$guncelle_arr);
                            }
                            if ($direkt_guncelle == 1){
                                echo 1;
                            }else{
                                var_dump($direkt_guncelle);
                            }
                        }
                            else{
                                echo 500;
                            }
                        }else{
                            echo 2;
                        }
                    }
                    }else{
                        echo 404;
                    }
            }
            if ($islem == "bayi-sil"){
                $dealer_id = $_POST["id"];
                $id = $_POST["company_id"];
                $tek_sorgu = tek("select id,dealership_number from companies where status=1 and id='$id'");
                $explode = explode("," ,$tek_sorgu["dealership_number"]);
                if (in_array($dealer_id,$explode) == 1){
                    $array_search = array_search($dealer_id,$explode);
                    unset($explode[$array_search]);
                    $implode = implode(",",$explode);
                    $guncelle_arr = [
                            "dealership_number" => $implode
                            ];
                    $guncelle = direktguncelle("companies","id",$id,$guncelle_arr);
                    if ($guncelle == 1){
                        echo 1;
                    }else{
                        echo 2;
                    }
                }else{
                    echo 3;
                }
            }
            if ($islem == "firma-guncelle"){
                $ekle = direktekle("companies",$_POST);
                if ($ekle == 1){
                    echo "<script>alertify.success('İşlem Başarılı')</script>";
                }else{
                    echo "<script>alertify.warning('Bilinmeyen Bir Hata Oluştu')</script>";
                }
            }
            if ($islem == "bayi-no-ekle-sql"){
                $id = $_POST["id"];
                $tek = tek("select id,dealership_number from companies where status=1 and id='$id'");

                if ($tek>0){
                    $bayiler = explode("," , $tek["dealership_number"]);
                    $push_bayi = array_push($bayiler,$_POST["dealership_number"]);
                    $implode = implode(",",$bayiler);
                    $guncelle_arr = [
                            "dealership_number" => $implode
                            ];
                    $guncelle = direktguncelle("companies","id",$id,$guncelle_arr);
                    if ($guncelle == 1){
                        echo 1;
                    }else{
                        echo 2;
                    }
                }
            }
            if ($islem == "depo-detay-tablo"){
                $id = $_GET["id"];
                $tek = tek(" select * from warehouses where status=1 and id='$id'");
                if ($tek["active_info"] == 1){
                    $aktiflik = "Aktif";
                }else{
                    $aktiflik = "Pasif";
                }
                if ($tek > 0){
                    $veri_arr = [
                            'depo_id' => $tek["id"],
                            'bina_kodu' => $tek["buildingid"],
                            'mkys_kodu' => $tek["mkys_code"],
                            'ekleyen_kullanici' => kullanicigetirid($tek["insert_userid"]),
                            'aktiflik_bilgisi' => $aktiflik,
                            'depo_adi' => $tek["warehouse_name"],
                            'depo_tipi' => islemtanimgetirid($tek["warehouse_type"]),
                            'depo_type' => $tek["warehouse_type"]
                            ];
                    $json = json_encode($veri_arr);
                    echo $json;
                }else{
                    echo 2;
                }
            }
            if ($islem == "depo-filtrele-sql"){
                $depo_adi = $_GET["depo_adi"];
                $mkys_kodu = $_GET["mkys_kodu"];
                $depo_turu = $_GET["depo_turu"];

                $sql = " select warehouse_name,id,mkys_code,warehouse_type from warehouses where status=1";

                if ($depo_adi != "" || $depo_adi != null){
                    $sql .= " AND warehouse_name='$depo_adi'";
                }else if ($mkys_kodu != "" || $mkys_kodu != null){
                    $sql .= " AND mkys_code='$mkys_kodu'";
                }else if ($depo_turu != "" || $depo_turu != null){
                    $sql .= " AND warehouse_type='$depo_turu'";
                }
                $veri = verilericoklucek($sql);

                if ($veri > 0){
                    $json = json_encode($veri);
                    echo $json;
                }else{
                    echo 2;
                }
            }
            if ($islem == "depoyu-guncelle"){
                $id = $_POST["id"];
                $guncelleme = direktguncelle("warehouses","id",$id,$_POST);
                if ($guncelleme == 1){
                    echo "<script>alertify.success('İşlem Başarılı')</script>";
                }else{
                    echo "<script>alertify.error('Bilinmeyen Bir Hata Oluştu')</script>";
                }
            }

            if ($islem == "depo-urun-filterele"){
              $urun_adi = $_GET["urun_adi"];
              $malzeme_turu = $_GET["malzeme_turu"];
              $depo_adi = $_GET["depo_adi"];

              $sql = " 
                    SELECT
                            srm.*,
                            sip.ats_number as ats_no,
                            sip.lot_number as lot_no,
                            w.warehouse_name as depo_adi,
                           td.definition_name as stok_tur
                    FROM
                            stock_receipt_move as srm
                    INNER JOIN stock_invoice_pen as sip ON sip.stock_receiptid=srm.stock_receiptid
                    INNER JOIN warehouses as w ON w.id=srm.warehouse_id
                    INNER JOIN transaction_definitions as td on td.id=srm.stock_type
                    WHERE  
                            srm.status=1";

              if (isset($urun_adi) && $urun_adi != ""){
                  $sql .= " AND srm.stock_name LIKE '%$urun_adi%'";
              }
              if (isset($malzeme_turu) && $malzeme_turu != ""){
                  $sql .= " AND srm.stock_type='$malzeme_turu'";
              }
              if (isset($depo_adi) && $depo_adi != ""){
                  $sql .= " AND w.warehouse_name='$depo_adi'";
              }

              $veri = verilericoklucek($sql);
              if ($veri > 0){
              $json = json_encode($veri);
              echo $json;
              }else{
                  echo 2;
              }
            }

            if ($islem == "depolar_arasi_urun_sql"){
                $verileri_getir = verilericoklucek("
                    select
                        st.*,
                        w.warehouse_name as isteyen_depo,
                        d.warehouse_name as karsilayan_depo,
                        u.name_surname as ad_soyad
                    from
                        stock_transfer as st
                    INNER JOIN warehouses as w ON w.id = st.catering_warehouseid
                    INNER JOIN warehouses as d ON d.id = st.requested_warehouseid
                    INNER JOIN users as u ON u.id=st.insert_userid
                    where
                        st.status=1
                        ");
                if ($verileri_getir > 0){
                $json = json_encode($verileri_getir);
                echo $json;
                }else{
                    echo 2;
                }
            }
            if ($islem == "transfer-stock-kalem"){
                $stock_transferid = $_GET["id"];

                $veriler = verilericoklucek("
                            select
                                tsm.*,
                                sc.stock_name as urun_adi,
                                sc.barcode as barkod,
                                u.name_surname as ad_soyad   
                            from
                                transfer_stock_move as tsm
                            inner join stock_card as sc on sc.id = tsm.stock_cardid
                            inner join users as u on u.id=tsm.insert_userid
                            where
                                tsm.status=1
                            and
                                tsm.stock_transferid='$stock_transferid'");
                if ($veriler > 0){
                    echo json_encode($veriler);
                }else{
                    echo 2;
                }
            }
            if ($islem == "depolari-getir"){
                $warehouse_id = $_GET["warehouse_id"];
                $veriler = verilericoklucek("select warehouse_name,id,mkys_code from warehouses where status=1 and id NOT IN($warehouse_id)");

                if ($veriler > 0){
                    echo json_encode($veriler);
                }else{
                    echo 2;
                }
            }
            if ($islem == "depo-adi-filtrele"){
                $kelime = $_GET["kelime"];

                $veriler = verilericoklucek("select warehouse_name,id,mkys_code from warehouses where status=1 and warehouse_name like '$kelime%'");
                if ($veriler > 0){
                    echo json_encode($veriler);
                }else{
                    echo 2;
                }
            }
            if ($islem == "ait-urun-getir-sql"){
                $depo_id = $_GET["id"];
                if ($depo_id == "0"){
                    $sql = "
                            select
                                srm.stock_name,
                                srm.barcode,
                                srm.id,
                                srm.stock_cardid,
                                srm.stock_amount,
                                srm.unit,
                                td.definition_name as malzeme_tur
                            from
                                stock_receipt_move as srm
                            left join transaction_definitions as td on td.id=srm.stock_type
                            where
                                srm.status=1";
                }else{
                    $sql = "
                            select
                                srm.stock_name,
                                srm.barcode,
                                srm.id,
                                srm.stock_cardid,
                                srm.stock_amount,
                                srm.unit,
                                td.definition_name as malzeme_tur
                            from
                                stock_receipt_move as srm
                            left join transaction_definitions as td on td.id=srm.stock_type
                            where
                                srm.status=1
                            and
                                srm.warehouse_id='$depo_id'";
                }
                $urunleri_coklucek = verilericoklucek($sql);
                if ($urunleri_coklucek > 0){
                    echo json_encode($urunleri_coklucek);
                }else{
                    echo 2;
                }
            }
            if ($islem == "urun-adi-fitrele-sql"){
                $kelime = mb_strtoupper($_GET["kelime"]);
                $warehouse_id = $_GET["depo_id"];
                $urun_adi_getir = verilericoklucek("
                            select
                                srm.stock_name,
                                srm.barcode,
                                srm.id,
                                srm.stock_cardid,
                                srm.stock_amount,
                                srm.unit,
                                td.definition_name as malzeme_tur
                            from
                                stock_receipt_move as srm
                            left join transaction_definitions as td on td.id=srm.stock_type
                            where
                                srm.status=1
                            and
                                srm.warehouse_id='$warehouse_id'   
                            and 
                                  srm.stock_name like '$kelime%' ");

                if ($urun_adi_getir > 0){
                    echo json_encode($urun_adi_getir);
                }else{
                    echo 2;
                }

            }
            if ($islem == "istek-olustur"){
                $direktekle = direktekle("stock_transfer",$_POST);
                if ($direktekle == 1){
                    $son_id = islemtanimsoneklenen("stock_transfer");
                    echo trim($son_id);
                }else{
                    echo 2;
                }
            }

            if ($islem == "transfer-istegi-vazgec"){
                $id = $_POST["id"];
                $cancel_detail = "İstek Oluşturulmaktan Vazgeçilmiş";
                $direktsil = canceldetail("stock_transfer","id",$id,$cancel_detail);
                if ($direktsil == 1){
                    $ikinci_sil_sorgu = verilericoklucek("select * from transfer_stock_move where status=1 and stock_transferid='$id'");
                    if ($ikinci_sil_sorgu > 0){
                        foreach ($ikinci_sil_sorgu as $id){
                            $ikinci_id = $id["stock_transferid"];
                            echo $ikinci_id;
                            $ikinci_sil = canceldetail("transfer_stock_move","stock_transferid",$ikinci_id,$cancel_detail);
                            if ($ikinci_sil == 1){
                                echo 1;
                            }else{
                                echo 2;
                            }
                        }
                    }
                }else{
                    echo 500;
                }
            }
            if ($islem == "transfer-istek-bilgileri-sql"){

                $stock_cardid = $_POST["stock_cardid"];
                $transfer_id = $_POST["stock_transferid"];
                $ilk_sorgu = tek("select * from transfer_stock_move where status=1 and stock_transferid='$transfer_id' and stock_cardid='$stock_cardid'");
                if ($ilk_sorgu > 0){
                    $ilk_sorgu_id = $ilk_sorgu["id"];
                    $son_miktar = $ilk_sorgu["requested_amount"];
                    $gonderilen_miktar = $_POST["requested_amount"];
                    $sonuc = $gonderilen_miktar + $son_miktar;
                    $arr = [
                            'requested_amount' => $sonuc
                            ];
                    $direktguncelle = direktguncelle("transfer_stock_move","id",$ilk_sorgu_id,$arr);
                    if ($direktguncelle == 1){
                         $tek_1 = verilericoklucek("
                            select
                                tsm.*,
                                u.name_surname as ad_soyad,
                                sc.barcode as barkod,
                                sc.stock_name as urun_adi
                            from
                                transfer_stock_move as tsm
                            inner join stock_card as sc on sc.id=tsm.stock_cardid
                            inner join users as u on u.id=tsm.insert_userid
                            where
                                tsm.status = 1
                            and
                                tsm.stock_transferid = '$transfer_id'");
                         if ($tek_1 > 0){
                             echo json_encode($tek_1);
                         }else{
                             echo 2;
                         }
                    }else{
                        echo 500;
                    }
                }else{
                 $kalem_ekle = direktekle("transfer_stock_move",$_POST);
                if ($kalem_ekle == 1){
                    $tek = verilericoklucek("
                            select
                                tsm.*,
                                u.name_surname as ad_soyad,
                                sc.barcode as barkod,
                                sc.stock_name as urun_adi
                            from
                                transfer_stock_move as tsm
                            inner join stock_card as sc on sc.id=tsm.stock_cardid
                            inner join users as u on u.id=tsm.insert_userid
                            where
                                tsm.status = 1
                            and
                                tsm.stock_transferid = '$transfer_id'");
                    if ($tek > 0){
                        echo json_encode($tek);
                    }else{
                        echo 2;
                    }
                }else{
                    echo 500;
                }
                }
            }
            if ($islem == "transfer-istek-tamamla"){
                $requested_warehouseid = $_POST["requested_warehouseid"];
                $transfer_id = $_POST["transfer_id"];

                $arr = [
                        "requested_warehouseid" => $requested_warehouseid
                        ];
                $tamamla = direktguncelle("stock_transfer","id",$transfer_id,$arr);
                if ($tamamla == 1){
                    echo 1;
                }else{
                    echo 2;
                }
            }
            if ($islem == "stock-transfer-filtreleme"){

                $isteyen_depo = $_GET["isteyen_depo"];
                $bas_tarih = $_GET["bas_tarih_transfer"];
                $bit_tarih = $_GET["bit_tarih_transfer"];
                $isteyen_kullanici = $_GET["isteyen_kullanici"];
                $sql = " 
                        SELECT
                             st.insert_datetime,
                             st.id,
                             u.name_surname as ad_soyad,
                             w.warehouse_name as isteyen_depo,
                             w2.warehouse_name as karsilayan_depo
                        FROM
                            stock_transfer as st
                        INNER JOIN warehouses as w on w.id=st.catering_warehouseid
                        INNER JOIN warehouses as w2 on w2.id=st.requested_warehouseid
                        INNER JOIN users as u on u.id=st.insert_userid
                        where 
                              st.status=1";

                if (isset($isteyen_depo) && $isteyen_depo != ""){
                    $sql .= " AND st.catering_warehouseid='$isteyen_depo'";
                }
                if (isset($bas_tarih) && $bas_tarih != "" || isset($bit_tarih) && $bit_tarih !=""){
                    $sql .= " AND st.insert_datetime BETWEEN '$bas_tarih' AND '$bit_tarih'";
                }
                if (isset($isteyen_kullanici) && $isteyen_kullanici != ""){
                    $sql .= " AND st.insert_userid='$isteyen_kullanici'";
                }
                $sql .=" ORDER BY st.insert_datetime DESC";

                $veriler = verilericoklucek($sql);

                if ($veriler > 0){
                    echo json_encode($veriler);
                }else{
                    echo 2;
                }
            }

            if ($islem == "transfer-kalem-vazgec"){
                $id = $_POST["id"];
                $cancel_detail = "İstek Oluşurken Silinmiştir...";
                $artik_sil = canceldetail("transfer_stock_move","id",$id,$cancel_detail);
                if ($artik_sil == 1){
                    echo 1;
                }else{
                    echo 2;
                }
            }

            if ($islem == "muadili-varmi"){
                $id = $_GET["id"];
                $warehouse_id = $_GET["warehouse_id"];
                if ($warehouse_id == 0){
                    $sql = "select
    sm.id,
    sm.stock_id,
    sm.stock_muadil_id,
    sc.stock_name as muadil_adi
from
    stock_muadil as sm
inner join stock_card as sc on sc.id=sm.stock_muadil_id
inner join stock_receipt_move as srm on srm.stock_cardid=sm.stock_muadil_id
where
    sm.status=1
  and
    stock_id='$id'";
                }else{
                    $sql = "
                        select
                            sm.id,
                            sm.stock_id,
    sm.stock_muadil_id,
    sc.stock_name as muadil_adi
from
    stock_muadil as sm
inner join stock_card as sc on sc.id=sm.stock_muadil_id
inner join stock_receipt_move as srm on srm.stock_cardid=sm.stock_muadil_id
where
    sm.status=1
  and
    stock_id='$id'
  and
      srm.warehouse_id='$warehouse_id'";
                }
                $tek = tek($sql);
                if ($tek > 0){
                    echo 1;
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-sql"){
                $date = date("Y-m-d");

                $istek_listesi = verilericoklucek("
                            SELECT
                                sr.*,
                                unit.department_name as birim_adi,
                                u.name_surname as ad_soyad,
                                w.warehouse_name as istedigi_depo
                            FROM
                                stock_request as sr
                            INNER JOIN users as u on u.id=sr.request_userid
                            INNER JOIN units as unit on unit.id=sr.unit_id
                            INNER JOIN warehouses as w on w.id=sr.requested_warehouseid
                            WHERE
                                sr.status=1
                            AND 
                                sr.request_time='$date'
                                ");
                if ($istek_listesi > 0){
                    echo json_encode($istek_listesi);
                }else{
                    echo 2;
                }
            }
            if ($islem == "add-new-request"){
                $_POST["request_time"] =  date("Y-m-d");
                $istek_olustur = direktekle("stock_request",$_POST);
                if ($istek_olustur == 1){
                    $id = islemtanimsoneklenen("stock_request");
                    echo trim($id);
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-depo-getir"){
                $verileri_getir = verilericoklucek("select id,warehouse_name,mkys_code from warehouses where status=1");
                if ($verileri_getir > 0){
                    echo json_encode($verileri_getir);
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-urunleri-getir"){
                $warehouse_id = $_POST["id"];
                if ($warehouse_id == 0){
                    $sql  = "
                    select
                                srm.stock_name,
                                srm.barcode,
                                srm.id,
                                srm.stock_cardid,
                                srm.stock_amount,
                                srm.unit,
                                td.definition_name as malzeme_tur
                            from
                                stock_receipt_move as srm
                            left join transaction_definitions as td on td.id=srm.stock_type
                            where
                                srm.status=1";
                }else{
                    $sql = "select
                                srm.stock_name,
                                srm.barcode,
                                srm.id,
                                srm.stock_cardid,
                                srm.stock_amount,
                                srm.unit,
                                td.definition_name as malzeme_tur
                            from
                                stock_receipt_move as srm
                            left join transaction_definitions as td on td.id=srm.stock_type
                            where
                                srm.status=1
                            and
                                srm.warehouse_id='$warehouse_id'";
                }
                $verileri_cek = verilericoklucek($sql);
                if ($verileri_cek > 0){
                    echo json_encode($verileri_cek);
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-bilgileri-sql"){
                $stock_cardid = $_POST["stock_cardid"];
                $request_id = $_POST["request_id"];
                $ilk_sorgu = tek("select * from service_request_move where status=1 and request_id='$request_id' and stock_cardid='$stock_cardid'");
                if ($ilk_sorgu > 0){
                    $ilk_sorgu_id = $ilk_sorgu["id"];
                    $son_miktar = $ilk_sorgu["requested_amount"];
                    $gonderilen_miktar = $_POST["requested_amount"];
                    $sonuc = $gonderilen_miktar + $son_miktar;
                    $arr = [
                            'requested_amount' => $sonuc
                            ];
                    $direktguncelle = direktguncelle("service_request_move","id",$ilk_sorgu_id,$arr);
                    if ($direktguncelle == 1){
                         $tek_1 = verilericoklucek("
                            select
                                srm.*,
                                u.name_surname as ad_soyad,
                                sc.barcode as barkod,
                                sc.stock_name as urun_adi
                            from
                                service_request_move as srm
                            inner join stock_card as sc on sc.id=srm.stock_cardid
                            inner join users as u on u.id=srm.insert_userid
                            where
                                srm.status = 1
                            and
                                srm.request_id = '$request_id'");
                         if ($tek_1 > 0){
                             echo json_encode($tek_1);
                         }else{
                             echo 2;
                         }
                    }else{
                        echo 500;
                    }
                }else{
                 $kalem_ekle = direktekle("service_request_move",$_POST);
                if ($kalem_ekle == 1){
                    $tek = verilericoklucek("
                            select
                                srm.*,
                                u.name_surname as ad_soyad,
                                sc.barcode as barkod,
                                sc.stock_name as urun_adi
                            from
                                service_request_move as srm
                            inner join stock_card as sc on sc.id=srm.stock_cardid
                            inner join users as u on u.id=srm.insert_userid
                            where
                                srm.status = 1
                            and
                                srm.request_id = '$request_id'");
                    if ($tek > 0){
                        echo json_encode($tek);
                    }else{
                        echo 2;
                    }
                }else{
                    echo 500;
                }
                }
            }
            if ($islem == "servis-istek-vazgec"){
                $id = $_POST["id"];
                $cancel_detail = "İstek Oluşturulmaktan Vazgeçilmiş";
                $direktsil = canceldetail("stock_request","id",$id,$cancel_detail);
                if ($direktsil == 1){
                    $ikinci_sil_sorgu = verilericoklucek("select * from service_request_move where status=1 and request_id='$id'");
                    if ($ikinci_sil_sorgu > 0){
                        foreach ($ikinci_sil_sorgu as $id){
                            $ikinci_id = $id["request_id"];
                            echo $ikinci_id;
                            $ikinci_sil = canceldetail("service_request_move","request_id",$ikinci_id,$cancel_detail);
                            if ($ikinci_sil == 1){
                                echo 1;
                            }else{
                                echo 2;
                            }
                        }
                    }
                }else{
                    echo 500;
                }
            }
            if ($islem == "servis-istek-kalem-vazgec"){
                $id = $_POST["id"];
                $cancel_detail = "İstek Oluşurken Silinmiştir...";
                $artik_sil = canceldetail("service_request_move","id",$id,$cancel_detail);
                if ($artik_sil == 1){
                    echo 1;
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-detayi-getir"){
                $request_id = $_POST["id"];
                $verileri_getir = verilericoklucek("
                                    SELECT
                                        srm.*,
                                        u.name_surname as ad_soyad,
                                        sc.stock_name as urun_adi,
                                        sc.barcode as barkod
                                    FROM
                                        service_request_move as srm
                                    INNER JOIN stock_card as sc on sc.id=srm.stock_cardid
                                    INNER JOIN users as u on u.id=srm.insert_userid
                                    WHERE
                                        srm.status=1
                                    and   
                                       srm.request_id='$request_id'   
                                    ");
                if ($verileri_getir > 0){
                    echo json_encode($verileri_getir);
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-filtrele"){
                $unit_id = $_POST["unit_id"];
                $bas_tarih = $_POST["bas_tarih"];
                $bit_tarih = $_POST["bit_tarih"];
                $isteyen_kullanici = $_POST["user_id"];
                $sql = " 
                            SELECT
                                sr.*,
                                unit.department_name as birim_adi,
                                u.name_surname as ad_soyad,
                                w.warehouse_name as istedigi_depo
                            FROM
                                stock_request as sr
                            INNER JOIN users as u on u.id=sr.request_userid
                            INNER JOIN units as unit on unit.id=sr.unit_id
                            INNER JOIN warehouses as w on w.id=sr.requested_warehouseid
                            WHERE
                                sr.status=1";

                if (isset($unit_id) && $unit_id != ""){
                    $sql .= " AND sr.unit_id='$unit_id'";
                }
                if (isset($bas_tarih) && $bas_tarih != "" || isset($bit_tarih) && $bit_tarih !=""){
                    $sql .= " AND sr.request_time BETWEEN '$bas_tarih' AND '$bit_tarih'";
                }
                if (isset($isteyen_kullanici) && $isteyen_kullanici != ""){
                    $sql .= " AND sr.insert_userid='$isteyen_kullanici'";
                }
                $sql .=" ORDER BY sr.request_time DESC";

                $veriler = verilericoklucek($sql);

                if ($veriler > 0){
                    echo json_encode($veriler);
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-red-sql"){
                $id = $_POST["id"];
                $request_id = $_POST["request_id"];
                $rejection_title = $_POST["rejection_title"];
                $update_datetime = date("Y-m-d H:i:s");
                $user_id = $_SESSION["id"];

                $tek_update = tek("UPDATE service_request_move SET rejection_title='$rejection_title',update_datetime='$update_datetime',update_userid='$user_id',request_status=2 WHERE id='$id'");
                if ($tek_update == true){
                    echo 2;
                }else{
                    $verileri_cek = verilericoklucek("
                                    SELECT
                                        srm.*,
                                        u.name_surname as ad_soyad,
                                        sc.stock_name as urun_adi,
                                        sc.barcode as barkod
                                    FROM
                                        service_request_move as srm
                                    INNER JOIN stock_card as sc on sc.id=srm.stock_cardid
                                    INNER JOIN users as u on u.id=srm.insert_userid
                                    WHERE
                                        srm.status=1
                                    and   
                                       srm.request_id='$request_id'"
                                );
                    if ($verileri_cek > 0){
                        echo json_encode($verileri_cek);
                    }else{
                        echo 2;
                    }
                }
            }
            if ($islem == "istekleri-tekrar-cek"){
                $request_id = $_GET["id"];
                 $verileri_cek = verilericoklucek("
                                    SELECT
                                        srm.*,
                                        u.name_surname as ad_soyad,
                                        sc.stock_name as urun_adi,
                                        sc.barcode as barkod
                                    FROM
                                        service_request_move as srm
                                    INNER JOIN stock_card as sc on sc.id=srm.stock_cardid
                                    INNER JOIN users as u on u.id=srm.insert_userid
                                    WHERE
                                        srm.status=1
                                    and   
                                       srm.request_id='$request_id'"
                                );
                    if ($verileri_cek > 0){
                        echo json_encode($verileri_cek);
                    }else{
                        echo 2;
                    }
            }
            if ($islem == "istegi-tamamla"){
                $requested_warehouseid = $_POST["requested_warehouse_id"];
                $arr = [
                        'requested_warehouseid' => $requested_warehouseid
                        ];
                $request_id = $_POST["request_id"];
                $servis_istek_tamamla = direktguncelle("stock_request","id",$request_id,$arr);
                if ($servis_istek_tamamla == 1){
                    echo 1;
                }else{
                    echo 2;
                }
            }
            if ($islem == "servis-istek-onayla"){
                $service_request_id =$_POST["id"];
                $request_id = $_POST["requested_id"];
                $userid = $_SESSION["id"];
                $date = date("Y-m-d H:i:s");

                $ilk_tek_update = tek("UPDATE service_request_move SET request_status=1,update_userid='$userid',update_datetime='$date' WHERE id='$service_request_id'");
                if ($ilk_tek_update == true){
                    echo 500;
                }else{
                    $istegin_depo_bilgileri = tek("select unit_id,requested_warehouseid from stock_request where id='$request_id'");
                    $istenilen_stock_cardid = tek("select * from service_request_move where status=1 and id='$service_request_id'");
                    $birim_id = $istegin_depo_bilgileri["unit_id"];
                    $istenilen_depoid = $istegin_depo_bilgileri["requested_warehouseid"];

                    $birime_ait_depoid = tek("select id from warehouses where status=1 and unitid='$birim_id'");
                    $isteyen_depoid = $birime_ait_depoid["id"];
                    $stock_cardid = $istenilen_stock_cardid["stock_cardid"];
                    $istenilen_deponun_miktari = tek("select * from stock_receipt_move where status=1 and stock_cardid='$stock_cardid'");
                    $miktar1 = $istenilen_deponun_miktari["stock_amount"];
                    $miktar2= $istenilen_stock_cardid["requested_amount"];
                    if ($miktar1 < $miktar2){
                        echo 404;
                    }else{
                        $sonuc = $miktar1 - $miktar2;
                        $guncelleme_ilk = tek("UPDATE stock_receipt_move SET stock_amount='$sonuc',update_userid='$userid',update_datetime='$date' WHERE warehouse_id='$istenilen_depoid' and stock_cardid='$stock_cardid'");
                        if ($guncelleme_ilk == true){
                            echo 1;
                        }else{
                            $gidecek_depoda_varmi = tek("select * from stock_receipt_move where status=1 and warehouse_id='$isteyen_depoid' and stock_cardid='$stock_cardid'");
                            if ($gidecek_depoda_varmi > 0){
                                $arti_miktar = $miktar1 + $miktar2;
                                $last_update = tek("UPDATE stock_receipt_move SET stock_amount='$arti_miktar',update_userid='$userid',update_datetime='$date' WHERE warehouse_id='$isteyen_depoid' and stock_cardid='$stock_cardid'");
                                if ($last_update == true){
                                    echo 500;
                                }else{
                                    echo 1;
                                }
                            }else{
                                $eklenecek_arr = [
                                        'stock_cardid' => $istenilen_deponun_miktari["stock_cardid"],
                                        'barcode' => $istenilen_deponun_miktari["barcode"],
                                        'lot_number' => $istenilen_deponun_miktari["lot_number"],
                                        'stock_sut_code' => $istenilen_deponun_miktari["stock_sut_code"],
                                        'movable_code' => $istenilen_deponun_miktari["movable_code"],
                                        'sale_unit_price' => $istenilen_deponun_miktari["sale_unit_price"],
                                        'measurement_code' => $istenilen_deponun_miktari["measurement_code"],
                                        'expiration_date' => $istenilen_deponun_miktari["expiration_date"],
                                        'ats_query_number' => $istenilen_deponun_miktari["ats_query_number"],
                                        'insert_datetime' => $date,
                                        'insert_userid' => $userid,
                                        'stock_amount' => $miktar2,
                                        'stock_request_moveid' => $request_id,
                                        'warehouse_id' => $istenilen_deponun_miktari["warehouse_id"],
                                        'stock_type' => $istenilen_deponun_miktari["stock_type"],
                                        'stock_name' => $istenilen_deponun_miktari["stock_name"],
                                        'company_id' => $istenilen_deponun_miktari["company_id"],
                                        'unit' => $istenilen_deponun_miktari["unit"],
                                        ];
                                $direkt_ekle = direktekle("stock_receipt_move",$eklenecek_arr);
                                if ($direktekle == 1){
                                    echo 1;
                                }else{
                                    echo 500;
                                }
                            }
                        }
                    }
                }

//                $isteyen_birim_id = $tek["unit_id"];
//                $stock_cardid = $istek_miktari["stock_cardid"];
//                $istenilen_depoid = $tek["requested_warehouseid"]; // istenilen deponun id si
//                $warehouse_name = tek("select id from warehouses where status=1 and unitid='$isteyen_birim_id'");
//                $isteyen_depoid = $warehouse_name["id"]; // isteyen deponun id si
//
//                $istenilen_depo_icindeki_adet = tek("select stock_amount from stock_receipt_move where status=1 and warehouse_id='$istenilen_depoid'");
//                $istegi_guncelle=tek("UPDATE service_request_move SET request_status=1,update_userid='$userid',update_datetime='$date' where request_id='$service_request_id'");
//                if ($istegi_guncelle == true){
//                    echo 404;
//                }else{
//                    $depodaki_adet = $istenilen_depo_icindeki_adet["stock_amount"];
//                $istenilen_adet = $istek_miktari["requested_amount"];
//                if ($depodaki_adet < $istenilen_adet){
//                    echo 404;
//                }else{
//                    $sonuc = $depodaki_adet - $istenilen_adet;
//                    $istenilen_depo_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$sonuc',update_userid='$userid',update_datetime='$date' where warehouse_id='$istenilen_depoid' and stock_cardid='$stock_cardid'");
//                    if ($istenilen_depo_guncelle == true){
//                        echo 500;
//                    }else{
//                        $isteyen_depo =tek("select stock_amount from stock_receipt_move where status=1  and warehouse_id='$isteyen_depoid'");
//
//
//                        $arti_sonuc = $isteyen_depo["stock_amount"] + $istenilen_adet;
//                        $arti_guncelle = tek("UPDATE stock_receipt_move SET stock_amount='$arti_sonuc' WHERE warehouse_id='$isteyen_depoid' and stock_cardid='$stock_cardid'");
//                        if ($arti_guncelle == true){
//                            echo 500;
//                        }else{
//                            $verileri_cek = verilericoklucek("
//                                    SELECT
//                                        srm.*,
//                                        u.name_surname as ad_soyad,
//                                        sc.stock_name as urun_adi,
//                                        sc.barcode as barkod
//                                    FROM
//                                        service_request_move as srm
//                                    INNER JOIN stock_card as sc on sc.id=srm.stock_cardid
//                                    INNER JOIN users as u on u.id=srm.insert_userid
//                                    WHERE
//                                        srm.status=1
//                                    and
//                                       srm.request_id='$request_id'"
//                                );
//                    if ($verileri_cek > 0){
//                        echo json_encode($verileri_cek);
//                    }else{
//                        echo 300;
//                    }
//                        }
//                    }
//                }
//                }
            }
            ?>