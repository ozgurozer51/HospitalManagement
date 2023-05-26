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

if ($islem == "ilacsarfekle") {
    if ($_POST) {
        $stock_r_count = count($_POST['material_id']);
        $kayit = $_POST;
        for ($i = 0; $i < $stock_r_count; $i++) {
            $id = $kayit["material_id"][$i];
            $_POST["material_id"] = $kayit["material_id"][$i];
            $_POST["barcode_code"] = $kayit["barcode_code"][$i];
            $_POST["warehouse_id"] = $kayit["warehouse_id"][$i];
            $_POST["material_type"] = $kayit["material_type"][$i];
            $_POST["material_price"] = $kayit["material_price"][$i];
            $_POST["request_pcs"] = $kayit["request_pcs"][$i];
            $_POST["request_detail"] = $kayit["request_detail"][$i];
            $_POST["expiration_date"] = $kayit["expiration_date"][$i];
            $_POST["protocol_number"] = $kayit["protocol_number"];
            $_POST["patient_id"] = $kayit["patient_id"];

            $multirequestmove = direktekle("patient_stock_consumables", $_POST);
            var_dump($multirequestmove);
            if ($multirequestmove) {
                $bilgileri = singularactive("stock_receipt_move", "id", $id);
                $t_adet = intval($bilgileri['stock_amount']);
                $adet = intval($_POST["request_pcs"]);
                $sonuc = $t_adet - $adet;
                $islemsonuc = guncelle("UPDATE stock_receipt_move SET stock_amount=$sonuc WHERE id='$id'");
                echo "<br>";
                var_dump($islemsonuc);
            }

        }

        if ($islemsonuc) { ?>
            <script type="text/javascript">
                alertify.success("Kayit başarili");

            </script>
            <?php
        } else { ?>
            <script type="text/javascript">
                alertify.alert("Kayit başarisiz2");

            </script>
            <?php
        }

    }
}
elseif ($islem == "ilacsarfiptal") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("patient_stock_consumables", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        $ilacsarf = singular("patient_stock_consumables", "id", $id);
        $D_id = $ilacsarf['material_id'];
        $bilgileri = singularactive("stock_receipt_move", "id", $D_id);
        $t_adet = intval($bilgileri['stock_amount']);
        $adet = intval($ilacsarf['request_pcs']);
        $sonuc = $t_adet + $adet;
        $islemsonuc = guncelle("UPDATE stock_receipt_move SET stock_amount=$sonuc WHERE id='$D_id'");
        var_dump($islemsonuc);
        if ($vezneguncelle == 1) { ?>
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
elseif ($islem == "ilacsarfdüzenle") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $m_value = $_POST['m_value'];

        $ilacsarf = singular("patient_stock_consumables", "id", $id);
        $D_id = $ilacsarf['material_id'];
        $bilgileri = singularactive("stock_receipt_move", "id", $D_id);
        $t_adet = intval($bilgileri['stock_amount']);
        $i_adet = intval($ilacsarf['request_pcs']);
        $adet = intval($m_value);
        $islem_sonuc = $i_adet - $adet;
        $islem_sonuc1 = $t_adet + $islem_sonuc;


        $islemsonuc = guncelle("UPDATE stock_receipt_move SET stock_amount=$islem_sonuc1 WHERE id='$D_id'");
        var_dump($islemsonuc);
        $islemsonuc1 = guncelle("UPDATE patient_stock_consumables SET request_pcs=$m_value WHERE id=$id");
        var_dump($islemsonuc1);
        if ($islemsonuc == 1) { ?>
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
elseif ($islem == "ilacsarfpaketolustur") {
    if ($_POST) {
        $stock_r_count = count($_POST['material_id']);
        $kayit = $_POST;
        for ($i = 0; $i < $stock_r_count; $i++) {
            $id = $kayit["material_id"][$i];
            $_POST["material_id"] = $kayit["material_id"][$i];
            $_POST["barcode_code"] = $kayit["barcode_code"][$i];
            $_POST["warehouse_id"] = $kayit["warehouse_id"][$i];
            $_POST["material_type"] = $kayit["material_type"][$i];
            $_POST["request_pcs"] = $kayit["request_pcs"][$i];
            $_POST["request_detail"] = $kayit["request_detail"][$i];


            $multirequestmove = direktekle("medicine_consumables_package", $_POST);
            var_dump($multirequestmove);

        }

        if ($multirequestmove) { ?>
            <script type="text/javascript">
                alertify.success("Kayit başarili");

            </script>
            <?php
        } else { ?>
            <script type="text/javascript">
                alertify.alert("Kayit başarisiz2");

            </script>
            <?php
        }

    }
}
elseif ($islem == "hasta_paket_ilacsarfi") {
    if ($_POST) {
        $paketadi = $_POST['paketadi'];
        $protokolno = $_POST['protokolno'];
        $hastaid = $_POST['hastaid'];
        unset($_POST);
        $hastaistemlerigetir = "select * from medicine_consumables_package where package_name='{$paketadi}' and  status='1' ";
        $hello = verilericoklucek($hastaistemlerigetir);
        foreach ($hello as $kayit) {
            $id = $kayit["material_id"];
            $bilgileri = singularactive("stock_receipt_move", "id", $id);
            $_POST["expiration_date"] = $bilgileri["expiration_date"];
            $_POST["material_price"] = $bilgileri["material_price"];
            $_POST["material_id"] = $kayit["material_id"];
            $_POST["barcode_code"] = $kayit["barcode_code"];
            $_POST["warehouse_id"] = $kayit["warehouse_id"];
            $_POST["material_type"] = $kayit["material_type"];
            $_POST["request_pcs"] = $kayit["request_pcs"];
            $_POST["request_detail"] = $kayit["request_detail"];
            $_POST["package_name"] = $kayit["package_name"];
            $_POST["protocol_number"] = $protokolno;
            $_POST["patient_id"] = $hastaid;

            $multirequestmove = direktekle("patient_stock_consumables", $_POST);
            var_dump($multirequestmove);
            if ($multirequestmove) {

                $t_adet = intval($bilgileri['stock_amount']);
                $adet = intval($_POST["request_pcs"]);
                $sonuc = $t_adet - $adet;
                $islemsonuc = guncelle("UPDATE stock_receipt_move SET stock_amount=$sonuc WHERE id='$id'");
                echo "<br>";
                var_dump($islemsonuc);
            }

        }

        if ($islemsonuc) { ?>
            <script type="text/javascript">
                alertify.success("Kayit başarili");

            </script>
            <?php
        } else { ?>
            <script type="text/javascript">
                alertify.alert("Kayit başarisiz2");

            </script>
            <?php
        }

    }
}
if ($islem == "get-json") {
    $paket_adi = $_GET['paketadi'];
    $hastaistemlerigetir = "select medicine_consumables_package.*,stock_receipt_move.stock_name as malzeme_adi,transaction_definitions.definition_name as malzeme_tip,
       stock_receipt_move.sale_unit_price as malzeme_fiyat,stock_receipt_move.stock_amount as depo_adet,
       stock_receipt_move.expiration_date as malzeme_tarih from medicine_consumables_package inner join stock_receipt_move
           on medicine_consumables_package.material_id=stock_receipt_move.id
inner join transaction_definitions on medicine_consumables_package.material_type=transaction_definitions.id
where medicine_consumables_package.package_name='$paket_adi' and  medicine_consumables_package.status='1'";
    $hello = verilericoklucek($hastaistemlerigetir);

    $json = json_encode($hello);
    echo $json;

}
elseif ($islem == "ilacsarfpaketduzenle") {
    if ($_POST) {
        $deger=$_POST["package_name"];
        $sql = "select * from medicine_consumables_package where package_name='$deger'";
        $hello=verilericoklucek($sql);
        foreach ($hello as $row) {
            $id_m=$row['id'];
            $islemsil=kesinsil("medicine_consumables_package", "id", $id_m);
            var_dump($islemsil);
            echo "<br>";
        }
        $stock_r_count = count($_POST['material_id']);
        echo "sayisi".$stock_r_count;
        echo "<br>";
        $kayit = $_POST;
        for ($i = 0; $i < $stock_r_count; $i++) {
            $id = $kayit["material_id"][$i];
            $_POST["material_id"] = $kayit["material_id"][$i];
            $_POST["barcode_code"] = $kayit["barcode_code"][$i];
            $_POST["warehouse_id"] = $kayit["warehouse_id"][$i];
            $_POST["material_type"] = $kayit["material_type"][$i];
            $_POST["request_pcs"] = $kayit["request_pcs"][$i];
            $_POST["request_detail"] = $kayit["request_detail"][$i];
            $_POST["package_name"] = $kayit["package_name"];
            $_POST["package_detail"] = $kayit["package_detail"];


            $multirequestmove = direktekle("medicine_consumables_package", $_POST);
            var_dump($multirequestmove);
            echo "<br>";
        }

        if ($multirequestmove) { ?>
            <script type="text/javascript">
                alertify.success("Kayit başarili");

            </script>
            <?php
        } else { ?>
            <script type="text/javascript">
                alertify.alert("Kayit başarisiz2");

            </script>
            <?php
        }

    }
}
elseif ($islem == "ilacsarfpaketiptal") {
    if ($_POST) {
        $deger = $_POST["paketadi"];
        $sql = "select * from medicine_consumables_package where package_name='$deger'";
        $hello = verilericoklucek($sql);
        foreach ($hello as $row) {
           echo $id_m = $row['id'];
            $islemsil = kesinsil("medicine_consumables_package", "id", $id_m);
            var_dump($islemsil);

        }
    }
}
elseif ($islem == "ilacsarftopluiptal") {
    if ($_POST) {
        $idler = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        foreach ($idler as $id) {
            $vezneguncelle = canceldetail("patient_stock_consumables", "id", $id, $detay, $silme, $tarih);
            var_dump($vezneguncelle);
            $ilacsarf = singular("patient_stock_consumables", "id", $id);
            $D_id = $ilacsarf['material_id'];
            $bilgileri = singularactive("stock_receipt_move", "id", $D_id);
            $t_adet = intval($bilgileri['stock_amount']);
            $adet = intval($ilacsarf['request_pcs']);
            $sonuc = $t_adet + $adet;
            $islemsonuc = guncelle("UPDATE stock_receipt_move SET stock_amount=$sonuc WHERE id='$D_id'");
            var_dump($islemsonuc);
        }


        if ($vezneguncelle == 1) { ?>
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



?>