<?php

include '../../controller/fonksiyonlar.php';
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
session_start();
ob_start();

$islem = $_GET["islem"];

if ($islem == "depo-stok-sil"){
$id = $_POST["id"];
$warehouse_id = $_POST["warehouse_id"];
?>
<div class="container">
    <div class="modal fade" id="delete-modal-stock" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h5>Silme İşlemini Onayla</h5>
                    <hr>
                    <div>
                        <div>
                            <label></label>
                            <label> Silme Nedeniniz</label>
                            <input type="text" class="form-control delete_detail">
                        </div>
                        <div>
                            <input type="hidden" disabled class="form-control select-id" value="<?php echo $id ?>">
                            <input type="hidden" disabled class="form-control stock-warehouseid"
                                   value="<?php echo $warehouse_id ?>">
                        </div>
                        <br>
                        <div class="offset-md-4">
                            <button type="button" class="btn btn-light delete-stock-from-warehouse"
                                    style="color: blue;">Onayla
                            </button>
                            <button type="button" class="btn btn-light close-button" data-dismiss="modal">Vazgeç
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $("#delete-modal-stock").modal("show");
            });
            $(".close-button").click(function () {
                $("#delete-modal-stock").modal("hide");
                alertify.warning("İşlemden Vazgeçtiniz");
            });
            $(".delete-stock-from-warehouse").click(function () {
                var id = $(".select-id").val();
                var warehouse_id = $(".stock-warehouseid").val();
                var delete_detail = $(".delete-detail").val();

                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=depo-stok-sil",
                    type: "POST",
                    data: {
                        id: id,
                        delete_detail: delete_detail
                    },
                    success: function (result) {
                        alertify.success("Silme İşlemi Başarı İle Gerçekleşti");
                        $("#delete-modal-stock").modal("hide");
                        $.get("ajax/stok/stok-tablo.php?islem=depoya-gore-getir", {warehouse_id: warehouse_id}, function (getVeri) {
                            $("#get-tables").html(getVeri);
                        });
                    }
                });

            });
        </script>
        <?php }

        if ($islem == "depodaki-stoklari-guncelle"){
        $id = $_POST["id"];
        $warehouse_id = $_POST["warehouse_id"];
        $guncellenecek = tek(" SELECT * FROM stock_receipt_move WHERE id='$id'");


        $son_kullanma_tarih = $guncellenecek["expiration_date"];
        $barcode = $guncellenecek["barcode"];
        $stock_name = $guncellenecek["stock_name"];
        $stock_warnings = $guncellenecek["stock_warnings"];
        $stock_number_of_boxes = $guncellenecek["stock_number_of_boxes"];
        $stock_type = $guncellenecek["stock_type"];
        $stock_age_groups = $guncellenecek["stock_age_groups"];
        $drug_description = $guncellenecek["drug_description"];
        ?>

        <div class="container">
            <div class="modal fade" id="update-stock-from-warehouse" data-bs-backdrop="static" data-bs-keyboard="false"
                 role="dialog">
                <div class="modal-dialog" style="width: 80%; max-width: 80%;">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h4 class="modal-title">Depodaki Ürünü Güncelle</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="col-12 row">
                                    <input type="hidden" id="id" value="<?php echo $id ?>">
                                    <!-- BURADA İSE GÜNCELLEMEK İSTEDİĞİMİZ VERİNİN ID SİNİ TUTUYORUZ -->
                                    <input type="hidden" id="id" value="<?php echo $warehouse_id ?>">
                                    <!-- BURADA TEKRAR FİLTRELEYEBİLMEK ADINA WAREHOUSEID Yİ BİR İNPUTA YAZDIRIYORUZ -->
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Barkod No</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" value="<?php echo $barcode ?>"
                                                       id="barcode">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Ürün Adı</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"
                                                       value="<?php echo $stock_name ?>" id="stock_name">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Uyarılar</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" id="stock_warnings" cols="20"
                                                          rows="5"><?php echo $stock_warnings ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Kutudaki Adet</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control"
                                                       value="<?php echo $stock_number_of_boxes ?>"
                                                       id="stock_number_of_boxes">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Yaş Grupları</label>
                                            <div class="col-sm-7">
                                                <select class="form-select" id="stock_age_groups">
                                                    <option value="<?php echo $stock_age_groups ?>"><?php echo $stock_age_groups ?></option>
                                                    <option value="Seçiniz...">Seçiniz...</option>
                                                    <option value="0-1 Yaş Arası">0-1 Yaş Arası</option>
                                                    <option value="1-3 Yaş Arası">1-3 Yaş Arası</option>
                                                    <option value="3-6 Yaş Arası">3-6 Yaş Arası</option>
                                                    <option value="6-12 Yaş Arası">6-12 Yaş Arası</option>
                                                    <option value="13-18 Yaş Arası">13-18 Yaş Arası</option>
                                                    <option value="18 Ve Üstü Yaşlar">18 Ve Üstü Yaşlar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Açıklama</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" id="drug_description" cols="20"
                                                          rows="5"><?php echo $drug_description; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="offset-10">
                                        <br><br>
                                        <button type="button" class="btn btn-danger close-update-modal"
                                                data-dismiss="modal" style="background-color: #3F72AF; color: white;">
                                            Vazgeç
                                        </button>
                                        <button type="button" class="btn btn-success update-button"
                                                style="background-color: #93CBC6; color: white;">Güncelle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                $("#update-stock-from-warehouse").modal("show");
                            });

                            $(".close-update-modal").on("click", function () {
                                $("#update-stock-from-warehouse").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz");
                            });

                            $(".update-button").on("click", function () {
                                var id = $("#id").val();
                                var barcode = $("#barcode").val();
                                var stock_name = $("#stock_name").val();
                                var stock_warnings = $("#stock_warnings").val();
                                var stock_number_of_boxes = $("#stock_number_of_boxes").val();
                                var stock_age_groups = $("#stock_age_groups").val();
                                var drug_description = $("#drug_description").val();
                                var warehouse_id = $("#warehouse_id").val();

                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=depodaki-urunu-guncelle",
                                    type: "POST",
                                    data: {
                                        id: id,
                                        barcode: barcode,
                                        stock_name: stock_name,
                                        stock_warnings: stock_warnings,
                                        stock_number_of_boxes: stock_number_of_boxes,
                                        stock_age_groups: stock_age_groups,
                                        drug_description: drug_description
                                    },
                                    success: function (result) {
                                        alertify.success(result);
                                        $("#update-stock-from-warehouse").modal("hide");
                                        $.get("ajax/stok/stok-tablo.php?islem=depoya-gore-getir", {warehouse_id: warehouse_id}, function (getVeri) {
                                            $("#get-tables").html(getVeri);
                                        });
                                    }
                                });
                            });
                        </script>

                        <?php }


                        if ($islem == "delete-company"){
                        $company_id = $_GET["id"];
                        ?>
                        <div class="container">
                            <div class="modal fade" id="delete-company-modal" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h5>Silme İşlemini Onayla</h5>
                                            <hr>
                                            <div>

                                                <div>
                                                    <label> Silme Nedeniniz</label>
                                                    <input type="text" class="form-control delete_detail">
                                                </div>
                                                <div>
                                                    <input type="hidden" disabled class="form-control select-id"
                                                           value="<?php echo $company_id ?>">
                                                </div>
                                                <br>
                                                <div class="offset-md-4">
                                                    <button type="button" class="btn btn-light delete-company"
                                                            style="color: blue;">Onayla
                                                    </button>
                                                    <button type="button" class="btn btn-light close-button"
                                                            data-dismiss="modal">Vazgeç
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        $("#delete-company-modal").modal("show");
                                    });
                                    $(".close-button").click(function () {
                                        $("#delete-company-modal").modal("hide");
                                        alertify.warning("İşlemden Vazgeçtiniz");
                                    });
                                    $(".delete-company").click(function () {
                                        var id = $(".select-id").val();
                                        var delete_detail = $(".delete_detail").val();
                                        $.ajax({
                                            url: "ajax/stok/stok-sql.php?islem=sql-firma-sil",
                                            type: "POST",
                                            data: {
                                                id: id,
                                                delete_detail: delete_detail
                                            },
                                            success: function () {
                                                alertify.success("İşlem Başarılı");
                                                $("#delete-company-modal").modal("hide");
                                                $.get("ajax/stok/stok-tablo.php?islem=firmalari-getir", function (getVeri) {
                                                    $("#get-tables").html(getVeri);
                                                });
                                            }
                                        });
                                    });
                                </script>
                                <?php }

                                if ($islem == "stok-kart-sil"){
                                $id = $_POST["id"];
                                ?>
                                <div class="container">
                                    <div class="modal fade" id="delete-stock-card-modal" data-bs-backdrop="static"
                                         data-bs-keyboard="false" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-body">
                                                    <h5>Silme İşlemini Onayla</h5>
                                                    <hr>
                                                    <div>

                                                        <div>
                                                            <label></label>
                                                            <label> Silme Nedeniniz</label>
                                                            <input type="text" class="form-control delete_detail">
                                                        </div>
                                                        <div>
                                                            <input type="hidden" disabled class="form-control select-id"
                                                                   value="<?php echo $id ?>">
                                                        </div>
                                                        <br>
                                                        <div class="offset-md-4">
                                                            <button type="button" class="btn btn-light delete-company"
                                                                    style="color: blue;">Onayla
                                                            </button>
                                                            <button type="button" class="btn btn-light close-button"
                                                                    data-dismiss="modal">Vazgeç
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function () {
                                                $("#delete-stock-card-modal").modal("show");
                                            });

                                            $(".close-button").click(function () {
                                                $("#delete-stock-card-modal").modal("hide");
                                                alertify.warning("İşlemden Vazgeçtiniz");
                                            });
                                            $(".delete-company").on("click", function () {
                                                var id = $(".select-id").val();
                                                var delete_detail = $(".delete-detail").val();
                                                $.ajax({
                                                    url: "ajax/stok/stok-sql.php?islem=stok-karti-sil",
                                                    type: "POST",
                                                    data: {
                                                        id: id,
                                                        delete_detail: delete_detail
                                                    },
                                                    success: function (result) {
                                                        alert(result);
                                                        $("#delete-stock-card-modal").modal("hide");
                                                        $.get("ajax/stok/stok-tablo.php?islem=stok-kartlari-getir", function (getVeri) {
                                                            $("#get-tables").html(getVeri);
                                                        })
                                                    }
                                                });
                                            });
                                        </script>
                                        <?php }

                                        if ($islem == "stok-kart-guncelle"){
                                        $id = $_POST["id"];
                                        $varmi = tek(" SELECT * FROM stock_card WHERE id='$id'");
                                        $id = $varmi["id"];
                                        $stock_warehouseid = $_POST["stock_warehouseid"];
                                        $barcode = $varmi["barcode"];
                                        $stock_name = $varmi["stock_name"];
                                        $producting_brand = $varmi["producting_brand"];
                                        $stock_number_of_boxes = $varmi["stock_number_of_boxes"];
                                        $stock_warning = $varmi["stock_warnings"];
                                        $stock_pregnant_use = $varmi["stock_pregnant_use"];
                                        $stock_vehicle_machine = $varmi["stock_vehicle_machine_use"];
                                        $stock_type = $varmi["stock_type"];
                                        $stock_sut_code = $varmi["stock_sut_code"];
                                        $stock_age_to_use = $varmi["stock_age_to_use"];
                                        $drug_description = $varmi["drug_description"];
                                        $sale_unit_price = $varmi["sale_unit_price"];

                                        // sonradan ekleneneler için değişkenler
                                        $cold_chain_product = $varmi["cold_chain_product"];
                                        $narcotic_drug = $varmi["narcotic_drug"];
                                        $light_affected_state = $varmi["light_affected_state"];
                                        $expnesive_stock = $varmi["expensive_stock"];

                                        $recete_turu = $varmi["prescription_type"];
                                        $mkys_kodu = $varmi["mkys_stock_code"];
                                        $movable_code = $varmi["movable_code"];
                                        $medula_carpan = $varmi["medula_multiplier"];
                                        $ehu_gun_miktar = $varmi["ehu_medicine_day_amount"];
                                        $ehu_max_adet = $varmi["ehu_medicine_max_amount"];
                                        $iskonto_yuzde = $varmi["discount_percent"];
                                        $olcu_kodu = $varmi["measurement_code"];
                                        $ehu_onay = $varmi["ehu_confirmation_state"];
                                        $maks_stok = $varmi["maximum_stock_amount"];
                                        $min_stok = $varmi["minimum_stock_amount"];
                                        $kritik_stok = $varmi["critical_stock_amount"];

                                        if ($narcotic_drug == 1) {
                                            $narco = "checked";
                                        } else {
                                            $narco = "";
                                        }
                                        if ($light_affected_state == 1) {
                                            $affected_state = "checked";
                                        } else {
                                            $affected_state = "";
                                        }
                                        if ($expnesive_stock == 1) {
                                            $expensive = "checked";
                                        } else {
                                            $expensive = "";
                                        }

                                        if ($cold_chain_product == 1) {
                                            $cold_chain = "checked";
                                        } else {
                                            $cold_chain = "";
                                        }
                                        if ($ehu_onay == 1) {
                                            $ehu = "Onaylı";
                                        } else {
                                            $ehu = "Onaylanmamış";
                                        }

                                        if ($stock_vehicle_machine == 1) {
                                            $stock_vehicle = "checked";
                                        } else {
                                            $stock_vehicle = "";
                                        }
                                        if ($stock_pregnant_use == 1) {
                                            $stock_pregnant = "checked";
                                        } else {
                                            $stock_pregnant = "";
                                        }
                                        ?>

                                        <style>
                                            .modal-body {
                                                max-height: calc(100vh - 210px);
                                                overflow-y: auto;
                                            }
                                        </style>
                                        <div class="container">

                                            <input type="hidden" value="<?php echo $id; ?>" class="stok-update-id">
                                            <div class="modal fade update-stock-cards" data-bs-backdrop="static"
                                                 data-bs-keyboard="false" role="dialog">
                                                <div class="modal-dialog" style="width: 90%; max-width: 90%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Stok Kart Güncelle</h4>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div>
                                                                        <div class="col-12 row">
                                                                            <div class="col-4">
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Barkod
                                                                                        No</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="barcode"
                                                                                               maxlength="20"
                                                                                               value="<?php echo $barcode ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ürün
                                                                                        Adı</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="stock_name"
                                                                                               data-id="<?php echo $id ?>"
                                                                                               value="<?php echo $stock_name ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Reçete
                                                                                        Türü</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select class="form-select form-select-sm prescription_type">
                                                                                            <option value="<?php echo $recete_turu ?>"><?php echo $recete_turu ?></option>
                                                                                            <option value="Normal">
                                                                                                Normal
                                                                                            </option>
                                                                                            <option value="Kırmızı">
                                                                                                Kırmızı
                                                                                            </option>
                                                                                            <option value="Turuncu">
                                                                                                Turuncu
                                                                                            </option>
                                                                                            <option value="Mor">Mor
                                                                                            </option>
                                                                                            <option value="Yeşil">
                                                                                                Yeşil
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">MKYS
                                                                                        Kod</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="mkys_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   value="<?php echo $mkys_kodu; ?>">
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-mkys-code"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Taşınır
                                                                                        Kod</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="movable_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   value="<?php echo $movable_code; ?>">
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-movable-code"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">Taşınır-->
                                                                                <!--                                                                                        Kod</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="text"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               id="movable_code"-->
                                                                                <!--                                                                                               value="-->
                                                                                <?php //echo $movable_code;
                                                                                ?><!--">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->

                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Malzeme
                                                                                        Türü</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select class="form-select form-select-sm stock_type">
                                                                                            <option value="<?php echo $stock_type ?>"><?php echo islemtanimgetirid($stock_type) ?></option>
                                                                                            <option value="28464">İLAÇ
                                                                                            </option>
                                                                                            <option value="28462">SARF
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 row">
                                                                                    <div class="col-6">
                                                                                        <div class="col-sm-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $stock_pregnant ?>
                                                                                                   type="checkbox"
                                                                                                   name="hamile-kullanabilir">
                                                                                            <label class="form-check-label-sm"
                                                                                                   for="hamile-kullanabilir">
                                                                                                Hamile Kul.
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-md-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $stock_vehicle ?>
                                                                                                   type="checkbox"
                                                                                                   name="arac-kullanabilir">
                                                                                            <label class="form-check-label"
                                                                                                   for="arac-kullanabilir">
                                                                                                Araç Kul.
                                                                                            </label>
                                                                                        </div>

                                                                                        <div class="col-sm-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $expensive ?>
                                                                                                   type="checkbox"
                                                                                                   name="expensive_stock">
                                                                                            <label class="form-check-label-sm"
                                                                                                   for="expensive_stock">
                                                                                                Pahalı Mal.
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <div class="col-md-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $narco ?>
                                                                                                   type="checkbox"
                                                                                                   name="narcotic_drug">
                                                                                            <label class="form-check-label"
                                                                                                   for="narcotic_drug">
                                                                                                Uyuşturucu
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-md-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $cold_chain ?>
                                                                                                   type="checkbox"
                                                                                                   name="cold_chain_product">
                                                                                            <label class="form-check-label"
                                                                                                   for="cold_chain_product">
                                                                                                Soğuk Zincir
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-md-9">
                                                                                            <input class="form-check-label col-form-label-sm" <?php echo $affected_state ?>
                                                                                                   type="checkbox"
                                                                                                   name="light_affected_state">
                                                                                            <label class="form-check-label form-check-label-sm"
                                                                                                   for="light_affected_state">
                                                                                                Işıktan Etk.
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">S.-->
                                                                                <!--                                                                                        Birim Fiyat</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="text"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               id="sale_unit_price"-->
                                                                                <!--                                                                                               value="-->
                                                                                <?php //echo $sale_unit_price;
                                                                                ?><!--">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">SUT
                                                                                        Kodu</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="stock_sut_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   value="<?php echo $stock_sut_code; ?>">
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-sut-code"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">SUT-->
                                                                                <!--                                                                                        KODU</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="text"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               id="stock_sut_code"-->
                                                                                <!--                                                                                               value="-->
                                                                                <?php //echo $stock_sut_code;
                                                                                ?><!--">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">M.
                                                                                        Çarpan</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="medula_multiplier"
                                                                                               value="<?php echo $medula_carpan; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">EHU
                                                                                        Gün
                                                                                        Miktar</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="number"
                                                                                               class="form-control form-control-sm"
                                                                                               id="ehu_medicine_day_amount"
                                                                                               value="<?php echo $ehu_gun_miktar; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ehu
                                                                                        Max. Adet</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="ehu_medicine_max_amount"
                                                                                               value="<?php echo $ehu_max_adet; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">İskonto-->
                                                                                <!--                                                                                        Yüzde</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="text"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               id="discount_percent"-->
                                                                                <!--                                                                                               value="-->
                                                                                <?php //echo $iskonto_yuzde;
                                                                                ?><!--">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ölçü
                                                                                        Kodu</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="measurement_code"
                                                                                               value="<?php echo $olcu_kodu; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ehu
                                                                                        Durum</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select class="form-select form-select-sm"
                                                                                                id="ehu_confirmation_state">
                                                                                            <option value="<?php echo $ehu; ?>"><?php echo $ehu; ?></option>
                                                                                            <option value="1">Onaylı
                                                                                            </option>
                                                                                            <option value="0">Onaysız
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Firma </label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm producting-brand-update"
                                                                                                   id="producting_brand"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   value="<?php echo $producting_brand; ?>"
                                                                                                   disabled>
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm get-company-buton"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-firma"></div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">Firma</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="text"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               id="producting_brand"-->
                                                                                <!--                                                                                               value="-->
                                                                                <?php //echo $producting_brand
                                                                                ?><!--">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->

                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Kutudaki
                                                                                        Adet</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="number"
                                                                                               class="form-control form-control-sm"
                                                                                               id="stock_number_of_boxes"
                                                                                               value="<?php echo $stock_number_of_boxes ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Max.
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="number"
                                                                                               class="form-control form-control-sm"
                                                                                               id="maximum_stock_amount"
                                                                                               value="<?php echo $maks_stok; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Min.
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="number"
                                                                                               class="form-control form-control-sm"
                                                                                               id="minimum_stock_amount"
                                                                                               value="<?php echo $min_stok; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Kritik
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="number"
                                                                                               class="form-control form-control-sm"
                                                                                               id="critical_stock_amount"
                                                                                               value="<?php echo $kritik_stok; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Açıklama</label>
                                                                                    <div class="col-sm-7">
                                                                                            <textarea
                                                                                                    class="form-control form-control-sm"
                                                                                                    rows="1"
                                                                                                    id="drug_description"><?php echo $drug_description; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Uyarılar</label>
                                                                                    <div class="col-sm-7">
                                                                                        <textarea
                                                                                                class="form-control form-control-sm"
                                                                                                rows="1"
                                                                                                id="stock_warnings"><?php echo $stock_warning; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Muadil
                                                                                        İlaç</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm muadil-tanimla-input"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   disabled>
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        id="ilac-liste-getir"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                                <button class="btn btn-outline-success btn-sm"
                                                                                                        id="muadil-ekle-buton"
                                                                                                        type="button">
                                                                                                    Ekle
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-muadil-modal"></div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 mt-2 muadil-tekrar-cek">
                                                                                    <table class="table table-bordered  table-hover px-2 nowrap display w-100"
                                                                                           id="muadil-tablo-listesi"
                                                                                           class="">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>Muadili</th>
                                                                                            <th>Ekleyen Kullanıcı</th>
                                                                                            <th>İşlem</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody class="muadil-td"
                                                                                               style="cursor: pointer;">
                                                                                        <?php
                                                                                        $muadilleri_getir = verilericoklucek("
                                                                                             SELECT stock_card.stock_name as stock_name,
                                                                                             stock_muadil.insert_userid as user_id,
                                                                                             stock_muadil.stock_muadil_id as muadil_id
                                                                                             FROM stock_muadil
                                                                                             INNER JOIN stock_card ON stock_card.id=stock_muadil.stock_muadil_id
                                                                                             WHERE stock_muadil.status=1 AND stock_muadil.stock_id='$id'");
                                                                                        foreach ($muadilleri_getir as $muadil) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <input type="hidden"
                                                                                                       class="muadilin_idsi"
                                                                                                       id="id-sayaci">
                                                                                                <td class="muadillerin-idleri"
                                                                                                    id="<?php echo $muadil["muadil_id"] ?>"><?php echo $muadil["stock_name"] ?></td>
                                                                                                <td><?php echo kullanicigetirid($muadil["user_id"]); ?></td>
                                                                                                <td>
                                                                                                    <button class='btn btn-sm delete-malzeme'
                                                                                                            data-id="<?php echo $muadil["muadil_id"] ?>"
                                                                                                            style='background-color: #E64848; color:white;'>
                                                                                                        Sil
                                                                                                    </button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-sm close-button"
                                                                            style="background-color: #E64848; color: white;"
                                                                            data-dismiss="modal" id="closeButton">Vazgeç
                                                                    </button>
                                                                    <button type="button"
                                                                            class="btn btn-sm update-stock-button"
                                                                            style="background-color: #4c954e; color: white;">
                                                                        Güncelle
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sonucla-gel"></div>
                            <div class="delete-muadil-modal"></div>
                        </div>
                        <script>

                            $(".get-company-buton").click(function () {
                                $.get("ajax/stok/stok-modal.php?islem=guncellenecek-firma-modal-getir", function (getModal) {
                                    $(".get-firma").html(getModal);
                                });
                            });

                            $("#ilac-liste-getir").click(function () {
                                $.get("ajax/stok/stok-modal.php?islem=ilac-getir", function (getModal) {
                                    $(".get-muadil-modal").html(getModal);
                                });
                            });

                            $(document).ready(function () {
                                $(".update-stock-cards").modal("show");
                                setTimeout(function () {
                                    $("#muadil-tablo-listesi").DataTable({
                                        scrollX: true,
                                        scrollY: "20vh",
                                        info: false,
                                        paging: false,
                                        length: 5,
                                        searching: false,
                                        bLengthChange: false,
                                        language: {
                                            url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                        }
                                    });
                                }, 300);
                            });

                            $("#closeButton").click(function () {
                                $(".update-stock-cards").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz");
                            });

                            $(".update-stock-button").on("click", function () {
                                if ($("[name='hamile-kullanabilir']").is(":checked")) {
                                    var stock_pregnant_use = $(this).val();
                                    stock_pregnant_use = 1;
                                } else {
                                    stock_pregnant_use = 0;
                                }

                                if ($("[name=cold_chain_product]").is(":checked")) {
                                    var cold_chain_product = $(this).val();
                                    cold_chain_product = 1;
                                } else {
                                    cold_chain_product = 0;
                                }

                                if ($("[name=light_affected_state]").is(":checked")) {
                                    var light_affected_state = $(this).val();
                                    light_affected_state = 1;
                                } else {
                                    light_affected_state = 0;
                                }

                                if ($("[name=expensive_stock]").is(":checked")) {
                                    var expensive_stock = $(this).val();
                                    expensive_stock = 1;
                                } else {
                                    expensive_stock = 0;
                                }

                                if ($("[name=narcotic_drug]").is(":checked")) {
                                    var narcotic_drug = $(this).val();
                                    narcotic_drug = 1;
                                } else {
                                    narcotic_drug = 0;
                                }

                                if ($("[name='arac-kullanabilir']").is(":checked")) {
                                    var stock_vehicle_machine_use = $(this).val();
                                    stock_vehicle_machine_use = 1;
                                } else {
                                    stock_vehicle_machine_use = 0;
                                }

                                var minimum_stock_amount = $("#minimum_stock_amount").val();
                                var id = $(".stok-update-id").val();
                                var barcode = $("#barcode").val();
                                var stock_name = $("#stock_name").val();
                                var prescription_type = $(".prescription_type").val();
                                var mkys_code = $("#mkys_code").val();
                                var movable_code = $("#movable_code").val();
                                var stock_type = $(".stock_type").val();
                                var sale_unit_price = $("#sale_unit_price").val();
                                var stock_sut_code = $("#stock_sut_code").val();
                                var medula_multiplier = $("#medula_multiplier").val();
                                var ehu_medicine_day_amount = $("#ehu_medicine_day_amount").val();
                                var ehu_medicine_max_amount = $("#ehu_medicine_max_amount").val();
                                var discount_percent = $("#discount_percent").val();
                                var measurement_code = $("#measurement_code").val();
                                var ehu_confirmation_state = $("#ehu_confirmation_state").val();
                                var producting_brand = $("#producting_brand").val();
                                var stock_number_of_boxes = $("#stock_number_of_boxes").val();
                                var maximum_stock_amount = $("#maximum_stock_amount").val();
                                var critical_stock_amount = $("#critical_stock_amount").val();
                                var drug_description = $("#drug_description").val();
                                var stock_warnings = $("#stock_warnings").val();


                                if (ehu_confirmation_state == "Onaylı") {
                                    ehu_confirmation_state = 1;
                                } else {
                                    ehu_confirmation_state = 2;
                                }
                                if (medula_multiplier == "" || medula_multiplier == null) {
                                    alertify.warning("Lütfen Medula Çarpanı Değerini Giriniz...")
                                } else if (ehu_medicine_max_amount == "" || ehu_medicine_max_amount == null) {
                                    alertify.warning("Lütfen Ehu Maks. Adedi Giriniz...")
                                } else if (measurement_code == "" || measurement_code == null) {
                                    alertify.warning("Lütfen Ölçü Kodu Giriniz...")
                                } else if (ehu_medicine_day_amount == "" || ehu_medicine_day_amount == null) {
                                    alertify.warning("Lütfen ehu Gün Miktarı Giriniz...")
                                } else if (maximum_stock_amount == "" || maximum_stock_amount == null) {
                                    alertify.warning("Lütfen Maks. Stok Adedi Giriniz...");
                                } else if (minimum_stock_amount == "" || minimum_stock_amount == null) {
                                    alertify.warning("Lüfen Min. Stok Adedi Giriniz...");
                                } else if (critical_stock_amount == "" || critical_stock_amount == null) {
                                    alertify.warning("Lütfen Kritik Stok Adedi Giriniz...")
                                } else if (stock_number_of_boxes == "" || stock_number_of_boxes == null) {
                                    alertify.warning("Lütfen Kutudaki Adet Sayısını Giriniz...")
                                } else {
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=stok-kart-guncelle",
                                        type: "POST",
                                        data: {
                                            id: id,
                                            stock_vehicle_machine_use: stock_vehicle_machine_use,
                                            narcotic_drug: narcotic_drug,
                                            expensive_stock: expensive_stock,
                                            light_affected_state: light_affected_state,
                                            cold_chain_product: cold_chain_product,
                                            stock_pregnant_use: stock_pregnant_use,
                                            barcode: barcode,
                                            stock_name: stock_name,
                                            prescription_type: prescription_type,
                                            mkys_stock_code: mkys_code,
                                            movable_code: movable_code,
                                            stock_type: stock_type,
                                            minimum_stock_amount: minimum_stock_amount,
                                            stock_sut_code: stock_sut_code,
                                            medula_multiplier: medula_multiplier,
                                            ehu_medicine_day_amount: ehu_medicine_day_amount,
                                            ehu_medicine_max_amount: ehu_medicine_max_amount,
                                            discount_percent: discount_percent,
                                            measurement_code: measurement_code,
                                            ehu_confirmation_state: ehu_confirmation_state,
                                            producting_brand: producting_brand,
                                            stock_number_of_boxes: stock_number_of_boxes,
                                            maximum_stock_amount: maximum_stock_amount,
                                            critical_stock_amount: critical_stock_amount,
                                            drug_description: drug_description,
                                            stock_warnings: stock_warnings
                                        },
                                        success: function (getVeri) {
                                            $(".sonucyaz").html(getVeri);
                                            $(".update-stock-cards").modal("hide");
                                            $.get("ajax/stok/stok-tablo.php?islem=stok-kartlari-getir", function (getList) {
                                                $("#get-tables").html(getList);
                                            });
                                        }
                                    });
                                }
                            });


                            $("#muadil-ekle-buton").click(function () {
                                var stock_id = $("#stock_name").attr("data-id");
                                var muadil_id = $(".muadil-tanimla-input").attr("id");
                                var id = $(".stok-update-id").val();

                                if (muadil_id == "" || muadil_id == null) {
                                    alertify.warning("Lütfen Bir Muadil Seçimi Yapınız");
                                } else {
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=muadil-ekle-sql",
                                        type: "POST",
                                        data: {
                                            stock_muadil_id: muadil_id,
                                            stock_id: stock_id
                                        },
                                        success: function (getVeri) {
                                            $(".sonucla-gel").html(getVeri);
                                            $(".update-stock-cards").modal("hide");
                                            $.ajax({
                                                url: "ajax/stok/stok-modal.php?islem=stok-kart-guncelle",
                                                type: "POST",
                                                data: {
                                                    id: id
                                                },
                                                success: function (getModal) {
                                                    $("#get-modals").html(getModal);
                                                }
                                            });
                                        }
                                    });
                                }
                            });

                            $(".delete-malzeme").click(function () {

                                var id = $(".stok-update-id").val();
                                var stock_id = $("#stock_name").attr("data-id");
                                var muadil_id = $(this).attr("data-id");

                                $.get("ajax/stok/stok-modal.php?islem=muadil-sil-modal", {
                                    id: id,
                                    stock_id: stock_id,
                                    stock_muadil_id: muadil_id
                                }, function (getModal) {
                                    $(".delete-muadil-modal").html(getModal);
                                });
                            });

                        </script>

                    <?php }

                    if ($islem == "yeni-kart-tanimla") {
                        ?>
                        <style>
                            .modal-body {
                                max-height: calc(65vh - 100px);
                                overflow-y: auto;
                            }
                        </style>
                        <div class="modal fade" id="new-stock-cards" data-bs-backdrop="static" data-bs-keyboard="false"
                             role="dialog">
                            <div class="modal-dialog" style="width: 95%; max-width: 95%;">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h4 class="modal-title">Yeni Stok Kartı Ekle</h4>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-xl-12 ">
                                                    <div style=" height: 600px;">
                                                        <div class="col-xl-12">
                                                            <div class="p-2 my-1" style="height: 580px;">
                                                                <div class="d-flex">
                                                                    <div class="col-xl-12">
                                                                        <div class="col-12 row mx-1 ">
                                                                            <div class="col-4 mt-2">
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Barkod</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="text" id="barcode">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ürün
                                                                                        Adı</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="text"
                                                                                               id="stock_name">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Reçete
                                                                                        Türü</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select id="prescription_type"
                                                                                                class="form-select form-select-sm">
                                                                                            <option value="Normal">
                                                                                                Normal
                                                                                            </option>
                                                                                            <option value="Kırmızı">
                                                                                                Kırmızı
                                                                                            </option>
                                                                                            <option value="Turuncu">
                                                                                                Turuncu
                                                                                            </option>
                                                                                            <option value="Mor">Mor
                                                                                            </option>
                                                                                            <option value="Yeşil">
                                                                                                Yeşil
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">MKYS
                                                                                        Kod</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="mkys_stock_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                            >
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="get-mkys-code"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">MKYS-->
                                                                                <!--                                                                                        Kod</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input class="form-control form-control-sm"-->
                                                                                <!--                                                                                               type="text"-->
                                                                                <!--                                                                                               id="mkys_stock_code">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Taşınır
                                                                                        Kod</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="movable_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                            >
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="tasinir-kod-getir"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">Taşınır-->
                                                                                <!--                                                                                        Kod</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input class="form-control form-control-sm"-->
                                                                                <!--                                                                                               type="text"-->
                                                                                <!--                                                                                               id="movable_code">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Malzeme
                                                                                        Türü</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select id="stock_type"
                                                                                                class="form-select form-select-sm">
                                                                                            <option value="Seçiniz...">
                                                                                                Seçiniz...
                                                                                            </option>
                                                                                            <option value="28464">İLAÇ
                                                                                            </option>
                                                                                            <option value="28462">SARF
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 row">
                                                                                    <div class="col-6">
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-8">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group"
                                                                                                       id="cold_chain_product">
                                                                                                <label class="form-check-label col-form-label-sm"
                                                                                                       for="cold_chain_product">
                                                                                                    Soğuk Zincir
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-8">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group"
                                                                                                       id="stock_pregnant_use">
                                                                                                <label class="form-check-label col-form-label-sm"
                                                                                                       for="stock_pregnant_use">
                                                                                                    Hamile
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-8">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group form-group-sm"
                                                                                                       id="light_affected_state">
                                                                                                <label class="form-check-label col-form-label-sm"
                                                                                                       for="light_affected_state">
                                                                                                    Işıktan Etkilenir
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-6">
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-9">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group form-group-sm"
                                                                                                       id="narcotic_drug">
                                                                                                <label class="form-check-label col-form-label-sm"
                                                                                                       for="narcotic_drug">
                                                                                                    Uyuşturucu
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-9">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group form-group-sm"
                                                                                                       id="stock_vehicle_machine_use">
                                                                                                <label class="form-check-label col-form-label-sm"
                                                                                                       for="stock_vehicle_machine_use">
                                                                                                    Araç Kullanabilir
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group mt-2">
                                                                                            <div class="col-sm-9">
                                                                                                <input type="checkbox"
                                                                                                       class="form-group form-group-sm"
                                                                                                       id="expensive_stock">
                                                                                                <label class="form-check-label form-check-label-sm"
                                                                                                       for="expensive_stock">
                                                                                                    Pahalı Malzeme
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 mt-2">
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">S.-->
                                                                                <!--                                                                                        Birim Fiyat</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input class="form-control form-control-sm"-->
                                                                                <!--                                                                                               type="number"-->
                                                                                <!--                                                                                               id="sale_unit_price">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">SUT
                                                                                        Kodu</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm"
                                                                                                   id="stock_sut_code"
                                                                                                   aria-describedby="basic-addon2"
                                                                                            >
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="sut-kod-listesi"></div>

                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">SUT-->
                                                                                <!--                                                                                        Kodu</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input class="form-control form-control-sm"-->
                                                                                <!--                                                                                               type="text"-->
                                                                                <!--                                                                                               id="stock_sut_code">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Medula
                                                                                        Çarpanı</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="number"
                                                                                               id="medula_multiplier">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ehu
                                                                                        Gün
                                                                                        Miktar</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="number"
                                                                                               id="ehu_medicine_day_amount">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ehu
                                                                                        Max. Adet</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="number"
                                                                                               id="ehu_medicine_max_amount">
                                                                                    </div>
                                                                                </div>
                                                                                <!--                                                                                <div class="form-group row mt-2">-->
                                                                                <!--                                                                                    <label class="col-sm-3 col-form-label-sm">İskonto-->
                                                                                <!--                                                                                        Yüzdesi</label>-->
                                                                                <!--                                                                                    <div class="col-sm-7">-->
                                                                                <!--                                                                                        <input type="number"-->
                                                                                <!--                                                                                               class="form-control form-control-sm"-->
                                                                                <!--                                                                                               placeholder="Örnek %10"-->
                                                                                <!--                                                                                               id="discount_percent">-->
                                                                                <!--                                                                                    </div>-->
                                                                                <!--                                                                                </div>-->
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ölçü
                                                                                        Kodu</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input type="text"
                                                                                               class="form-control form-control-sm"
                                                                                               id="measurement_code">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Ehu
                                                                                        Durumu</label>
                                                                                    <div class="col-sm-7">
                                                                                        <select id="ehu_confirmation_state"
                                                                                                class="form-select form-select-sm">
                                                                                            <option value="Seçiniz...">
                                                                                                Seçiniz...
                                                                                            </option>
                                                                                            <option value="1">Onaylı
                                                                                            </option>
                                                                                            <option value="0">Onaysız
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Firma</label>
                                                                                    <div class="col-sm-7">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   class="form-control form-control-sm producting_brand"
                                                                                                   id="producting_brand"
                                                                                                   aria-describedby="basic-addon2"
                                                                                                   disabled>
                                                                                            <div class="input-group-append">
                                                                                                <button class="btn btn-outline-warning btn-sm"
                                                                                                        id="firma-liste-getir"
                                                                                                        data-id="test"
                                                                                                        type="button"><i
                                                                                                            class="fa fa-ellipsis-h"
                                                                                                            aria-hidden="true"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-4 mt-2">
                                                                                <div class="form-group row mt-1">
                                                                                    <label class="col-sm-3 col-form-label-sm">Kutudaki
                                                                                        Adet</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="text"
                                                                                               id="stock_number_of_boxes">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Max.
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="number"
                                                                                               id="maximum_stock_amount">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Min.
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control"
                                                                                               type="number"
                                                                                               id="minimumu_stock_amount">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Kritik
                                                                                        Stok</label>
                                                                                    <div class="col-sm-7">
                                                                                        <input class="form-control form-control-sm"
                                                                                               type="number"
                                                                                               id="critical_stock_amount">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Açıklama</label>
                                                                                    <div class="col-sm-7">
                                                                                        <textarea
                                                                                                class="form-control form-control-sm"
                                                                                                id="drug_description"
                                                                                                rows="1"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row mt-2">
                                                                                    <label class="col-sm-3 col-form-label-sm">Uyarılar</label>
                                                                                    <div class="col-sm-7">
                                                                                        <textarea id="stock_warnings"
                                                                                                  rows="1"
                                                                                                  class="form-control form-control-sm"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm closeModal" data-dismiss="modal"
                                                style="background-color: #E64848; color: white;">Vazgeç
                                        </button>
                                        <button type="button" class="btn btn-sm new-stock-button"
                                                style="background-color: #4c954e; color: white;">Kaydet
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="get-firm"></div>

                        <script>
                            $(document).ready(function () {
                                $("#new-stock-cards").modal("show");
                            });
                            $(".closeModal").on("click", function () {
                                $("#new-stock-cards").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz");
                            });

                            $(".new-stock-button").on("click", function () {
                                var barcode = $("#barcode").val();
                                var prescription_type = $("#prescription_type").val();
                                var stock_name = $("#stock_name").val();
                                var mkys_stock_code = $("#mkys_stock_code").val();
                                var movable_code = $("#movable_code").val();
                                var stock_type = $("#stock_type").val();
                                //BURASI CHECBOXLARIN BAŞLADIĞI YER
                                var light_affected_state = $("#light_affected_state").val();
                                var narcotic_drug = $("#narcotic_drug").val();
                                var stock_pregnant_use = $("#stock_pregnant_use").val();
                                var stock_vehicle_machine_use = $("#stock_vehicle_machine_use").val();
                                var cold_chain_product = $("#cold_chain_product").val();
                                var expensive_stock = $("#expensive_stock").val();
                                // CHECBOX BİTİŞ ALANI
                                // var sale_unit_price = $("#sale_unit_price").val();
                                var stock_sut_code = $("#stock_sut_code").val();
                                var medula_multiplier = $("#medula_multiplier").val();
                                var ehu_medicine_max_amount = $("#ehu_medicine_max_amount").val();
                                var ehu_medicine_day_amount = $("#ehu_medicine_day_amount").val();
                                // var discount_percent = $("#discount_percent").val();
                                var ehu_confirmation_state = $("#ehu_confirmation_state").val();
                                var producting_brand = $("#producting_brand").val();
                                var stock_number_of_boxes = $("#stock_number_of_boxes").val();
                                var maximum_stock_amount = $("#maximum_stock_amount").val();
                                var minimum_stock_amount = $("#minimum_stock_amount").val();
                                var critical_stock_amount = $("#critical_stock_amount").val();
                                var drug_description = $("#drug_description").val();
                                var stock_warnings = $("#stock_warnings").val();
                                var measurement_code = $("#measurement_code").val();
                                if (stock_type == "Seçiniz...") {

                                } else {

                                    if ($("[id='stock_pregnant_use']").is(":checked")) {
                                        stock_pregnant_use = 1;
                                    } else {
                                        stock_pregnant_use = 0;
                                    }
                                    if ($("[id='light_affected_state']").is(":checked")) {
                                        light_affected_state = 1;
                                    } else {
                                        light_affected_state = 0;
                                    }
                                    if ($("[id='narcotic_drug']").is(":checked")) {
                                        narcotic_drug = 1;
                                    } else {
                                        narcotic_drug = 0;
                                    }
                                    if ($("[id='stock_vehicle_machine_use']").is(":checked")) {
                                        stock_vehicle_machine_use = 1;
                                    } else {
                                        stock_vehicle_machine_use = 0;
                                    }
                                    if ($("[id='cold_chain_product']").is(":checked")) {
                                        cold_chain_product = 1;
                                    } else {
                                        cold_chain_product = 0;
                                    }
                                    if ($("[id='expensive_stock']").is(":checked")) {
                                        expensive_stock = 1;
                                    } else {
                                        expensive_stock = 0;
                                    }

                                    if (ehu_confirmation_state == "Seçiniz...") {
                                        alertify.warning("Lütfen Ehu Onay Durumu Giriniz")
                                    } else {
                                        $.ajax({
                                            url: "ajax/stok/stok-sql.php?islem=yeni-stok-ekle",
                                            type: "POST",
                                            data: {
                                                measurement_code: measurement_code,
                                                barcode: barcode,
                                                prescription_type: prescription_type,
                                                stock_name: stock_name,
                                                mkys_stock_code: mkys_stock_code,
                                                movable_code: movable_code,
                                                stock_type: stock_type,
                                                light_affected_state: light_affected_state,
                                                narcotic_drug: narcotic_drug,
                                                stock_pregnant_use: stock_pregnant_use,
                                                stock_vehicle_machine_use: stock_vehicle_machine_use,
                                                cold_chain_product: cold_chain_product,
                                                expensive_stock: expensive_stock,
                                                stock_sut_code: stock_sut_code,
                                                medula_multiplier: medula_multiplier,
                                                ehu_medicine_day_amount: ehu_medicine_day_amount,
                                                ehu_medicine_max_amount: ehu_medicine_max_amount,
                                                ehu_confirmation_state: ehu_confirmation_state,
                                                producting_brand: producting_brand,
                                                stock_number_of_boxes: stock_number_of_boxes,
                                                maximum_stock_amount: maximum_stock_amount,
                                                minimum_stock_amount: minimum_stock_amount,
                                                critical_stock_amount: critical_stock_amount,
                                                drug_description: drug_description,
                                                stock_warnings: stock_warnings
                                            },
                                            success: function (result) {
                                                $(".sonucyaz").html(result);
                                                $("#new-stock-cards").modal("hide");
                                                $.get("ajax/stok/stok-tablo.php?islem=stok-kartlari-getir", function (getVeri) {
                                                    $("#get-tables").html(getVeri);
                                                });
                                            }
                                        });
                                    }
                                }
                            });

                            $("#firma-liste-getir").click(function () {
                                $.get("ajax/stok/stok-modal.php?islem=firma-liste", function (getList) {
                                    $(".get-firm").html(getList);
                                });
                            });
                        </script>

                    <?php }
                    if ($islem == "update-company"){
                    $id = $_GET["id"];
                    $veriler = tek(" SELECT * FROM companies WHERE id='$id'");

                    $company_name = $veriler["company_name"];
                    $phone_number = $veriler["phone_number"];
                    $authorized_person = $veriler["authorized_person"];
                    $company_address = $veriler["company_address"];
                    $tax_office = $veriler["tax_office"];
                    $tax_number = $veriler["tax_number"];
                    $email = $veriler["email"];
                    $iban_number = $veriler["iban_number"];
                    $company_type = $veriler["company_type"];
                    $company_code = $veriler["company_code"];
                    $company_title = $veriler["company_title"];
                    $company_currency_unit = $veriler["company_currency_unit"];

                    ?>
                        <input type="hidden" value="<?php echo $id; ?>" id="company_id">
                        <div class="container">
                            <div class="modal fade" id="update-companies" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 80%; max-width: 80%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Firma Güncelle</h4>
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div>
                                                        <input type="hidden" id="this-id" value="<?php echo $id ?>">
                                                        <div class="col-12 row">
                                                            <div class="col-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-3 col-form-label-sm">Firma
                                                                        Adı</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="company_name"
                                                                               value="<?php echo $company_name ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Telefon
                                                                        Numarası</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="phone_number"
                                                                               value="<?php echo $phone_number ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Firma
                                                                        Sahibi</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="authorized_person"
                                                                               value="<?php echo $authorized_person ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Adres</label>
                                                                    <div class="col-sm-7">
                                                                        <textarea id="company_address"
                                                                                  class="form-control form-control-sm"
                                                                                  rows="1"><?php echo $company_address ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Vergi
                                                                        Dairesi</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="tax_office"
                                                                               value="<?php echo $tax_office ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Vergi
                                                                        No</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="tax_number"
                                                                               value="<?php echo $tax_number ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Para
                                                                        Birimi</label>
                                                                    <div class="col-sm-7">
                                                                        <select id="company_currency_unit"
                                                                                class="form-select form-select-sm">
                                                                            <option value="<?php echo $company_currency_unit ?>"><?php echo $company_currency_unit ?></option>
                                                                            <option value="TL">TL</option>
                                                                            <option value="Dolar">Dolar</option>
                                                                            <option value="Euro">Euro</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Mail
                                                                        Adresi</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="email"
                                                                               class="form-control form-control-sm"
                                                                               id="email" value="<?php echo $email ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">IBAN
                                                                        NO</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="iban_number"
                                                                               value="<?php echo $iban_number ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Firma
                                                                        Kodu</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="company_code"
                                                                               value="<?php echo $company_code ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Firma
                                                                        Türü</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="company_type"
                                                                               value="<?php echo $company_type ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Şirket
                                                                        Ünvanı</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="company_title"
                                                                               value="<?php echo $company_title ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Firma
                                                                        Tanımlayıcı No</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="identifier_number_update"
                                                                               value="<?php echo $veriler["identifier_number"]; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Bayilik
                                                                        No</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="dealership_number">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-12 mt-1">
                                                                <table class="table table-bordered table-sm table-hover px-2"
                                                                       id="stock-add-table"
                                                                       style="background: white; width: 100%;">
                                                                    <thead class="table-light">
                                                                    <tr>
                                                                        <th>Bayilik Numarası</th>
                                                                        <th>İşlem</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="stock_name_table">
                                                                    <?php
                                                                    $sql = tek("SELECT dealership_number,company_name FROM companies WHERE status=1 AND id='$id'");
                                                                    $explode = explode(",", $sql["dealership_number"]);
                                                                    foreach ($explode as $dealer) {
                                                                        ?>
                                                                        <tr id="data-<?php echo $dealer ?>">
                                                                            <td><?php echo $dealer ?></td>
                                                                            <td>
                                                                                <button class="btn btn-danger btn-sm delete-dealer"
                                                                                        data-id="<?php echo $dealer ?>">
                                                                                    Sil
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm closeButton"
                                                            data-dismiss="modal"
                                                            style="background-color: #E64848; color: white;">Vazgeç
                                                    </button>

                                                    <button type="button" class="btn btn-sm firmalari-guncelle"
                                                            style="background-color: #4c954e; color: white;">Güncelle
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).on("click", ".delete-dealer", function () {
                var silinecek_tr_firma = $(this).closest("tr");
                var id = $(this).attr("data-id");
                var company_id = $("#this-id").val();
                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=bayi-sil",
                    type: "POST",
                    data: {
                        id: id,
                        company_id: company_id
                    },
                    success: function (result) {
                        if (result == 1) {
                            alertify.success("İşlem Başarılı");
                            silinecek_tr_firma.remove();
                        } else if (result == 2) {
                            alertify.error("İşlemde Bir Hata Oluştu")
                        } else {
                        }
                    }
                });
            });

            $(document).ready(function () {
                $("#update-companies").modal("show");
            });

            $("#dealership_number").keypress(function (e) {
                if (e.which === 13) {
                    var id = $("#this-id").val();
                    var dealership_number = $("#dealership_number").val();
                    $.ajax({
                        url: "ajax/stok/stok-sql.php?islem=bayi-no-ekle-sql",
                        type: "POST",
                        data: {
                            id: id,
                            dealership_number: dealership_number
                        },
                        success: function (result) {
                            if (result == 1) {
                                $("#stock_name_table").append("<tr>" +
                                    "<td>" + dealership_number + "</td>" +
                                    "<td><button class='btn btn-danger btn-sm delete-dealer' data-id='" + dealership_number + "'>Sil</button></td>" +
                                    "</tr>");
                                $("#dealership_number").val("");
                            } else if (result == 2) {
                                alertify.error("Bilinmeyen Bir Hata Oluştu")
                            } else {
                            }
                        }
                    });
                }
            });

            $(".closeButton").click(function () {
                $("#update-companies").modal("hide");
                alertify.warning("İşlemden Vazgeçtiniz");
            });

            $(".firmalari-guncelle").click(function () {
                var company_name = $("#company_name").val();
                var phone_number = $("#phone_number").val();
                var authorized_person = $("#authorized_person").val();
                var company_address = $("#company_address").val();
                var tax_office = $("#tax_office").val();
                var tax_number = $("#tax_number").val();
                var email = $("#email").val();
                var iban_number = $("#iban_number").val();
                var company_code = $("#company_code").val();
                var company_type = $("#company_type").val();
                var company_title = $("#company_title").val();
                var company_currency_unit = $("#company_currency_unit").val();
                var identifier_number = $("#identifier_number_update").val();
                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=firma-guncelle",
                    type: "POST",
                    data: {
                        company_name: company_name,
                        phone_number: phone_number,
                        authorized_person: authorized_person,
                        company_address: company_address,
                        tax_office: tax_office,
                        tax_number: tax_number,
                        email: email,
                        iban_number: iban_number,
                        company_code: company_code,
                        company_type: company_type,
                        company_title: company_title,
                        company_currency_unit: company_currency_unit,
                        identifier_number: identifier_number
                    },
                    success: function (result) {
                        $(".sonucyaz").html(result);
                        $("#update-companies").modal("hide");
                        $.get("ajax/stok/stok-tablo.php?islem=firmalari-getir", function (getTable) {
                            $("#get-tables").html(getTable);
                        });
                    }
                });
            });
        </script>
    <?php }

    if ($islem == "add-companies"){
    ?>
        <div class="container">
            <div class="modal fade" id="new-company-add" data-bs-backdrop="static" data-bs-keyboard="false"
                 role="dialog">
                <div class="modal-dialog" style="width: 80%; max-width: 80%;">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h4 class="modal-title">Yeni Firma Ekle</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <div>
                                        <div class="col-12 row">
                                            <div class="col-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="company_name">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Telefon Numarası</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="phone_number">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Sahibi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="authorized_person">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Adres</label>
                                                    <div class="col-sm-7">
                                                        <textarea id="company_address" rows="1"
                                                                  class="form-control form-control-sm"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Vergi Dairesi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="tax_office">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Vergi No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="tax_number">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Para Birimi</label>
                                                    <div class="col-sm-7">
                                                        <select id="company_currency_unit"
                                                                class="form-select form-select-sm">
                                                            <option value="TL">TL</option>
                                                            <option value="Dolar">Dolar</option>
                                                            <option value="Euro">Euro</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">

                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label-sm">Mail Adresi</label>
                                                    <div class="col-sm-7">
                                                        <input type="email" class="form-control form-control-sm"
                                                               id="email">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">IBAN NO</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="iban_number">
                                                    </div>
                                                </div>
                                                <div class="form-group row  mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Kodu</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="company_code">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Türü</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="company_type">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Şirket Ünvanı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm"
                                                               id="company_title">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Tanımlayıcı
                                                        No</label>
                                                    <div class="col-sm-7">
                                                        <input type="number" class="form-control form-control-sm"
                                                               id="identifier_number">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Bayilik No</label>
                                                    <div class="col-sm-7">
                                                        <input type="number" class="form-control form-control-sm"
                                                               id="dealership_number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-1">
                                                <table class="table table-bordered table-sm table-hover px-2"
                                                       id="stock-add-table" style="background: white; width: 100%;">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Bayilik Numarası</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="stock_name_table">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm closeButton" data-dismiss="modal"
                                            style="background-color: #E64848; color: white;">Vazgeç
                                    </button>
                                    <button type="button" class="btn btn-sm add-new-companies"
                                            style="background-color: #4c954e; color: white;">Kaydet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#new-company-add").modal("show");
        });
        $(".closeButton").click(function () {
            $("#new-company-add").modal("hide");
            alertify.warning("İşlemden Vazgeçtiniz");
        });

        $("#dealership_number").keypress(function (e) {
            if (e.which === 13) {
                var dealership_number = $("#dealership_number").val();
                $("#stock_name_table").append('<tr><td  name="bayilik_no[]"  data-id="' + dealership_number + '" >' + dealership_number + '</td></tr>');
                $("#dealership_number").val("");
            }
        });

        $(".add-new-companies").click(function () {


            var dealirship_array = [];
            $("td[name='bayilik_no[]']").off().each(function () {
                dealirship_array.push($(this).attr('data-id'));
            });

            var identifier_number = $("#identifier_number").val();
            var company_name = $("#company_name").val();
            var phone_number = $("#phone_number").val();
            var authorized_person = $("#authorized_person").val();
            var company_address = $("#company_address").val();
            var tax_office = $("#tax_office").val();
            var tax_number = $("#tax_number").val();
            var email = $("#email").val();
            var iban_number = $("#iban_number").val();
            var company_code = $("#company_code").val();
            var company_type = $("#company_type").val();
            var company_title = $("#company_title").val();
            var company_currency_unit = $("#company_currency_unit").val();

            $.ajax({
                url: "ajax/stok/stok-sql.php?islem=add-new-companies",
                type: "POST",
                data: {
                    dealership_number: dealirship_array.toString(),
                    company_name: company_name,
                    identifier_number: identifier_number,
                    phone_number: phone_number,
                    authorized_person: authorized_person,
                    company_address: company_address,
                    tax_office: tax_office,
                    tax_number: tax_number,
                    email: email,
                    iban_number: iban_number,
                    company_code: company_code,
                    company_type: company_type,
                    company_title: company_title,
                    company_currency_unit: company_currency_unit
                },
                success: function (result) {
                    $(".sonucyaz").html(result);
                    $("#new-company-add").modal("hide");
                    $.get("ajax/stok/stok-tablo.php?islem=firmalari-getir", function (getVeri) {
                        $("#get-tables").html(getVeri);
                    });
                }
            });
        });
    </script>
    <?php }
    if ($islem == "depo-sil"){
    $id = $_GET["id"];
    ?>
    <div class="container">
        <div class="modal fade" id="delete-warehouse-modal" data-bs-backdrop="static" data-bs-keyboard="false"
             role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-body">
                        <h5>Silme İşlemini Onayla</h5>
                        <hr>
                        <div>
                            <div>
                                <label></label>
                                <label> Silme Nedeniniz</label>
                                <input type="text" class="form-control delete_detail">
                            </div>
                            <div>
                                <input type="hidden" disabled class="form-control select-id" value="<?php echo $id ?>">
                            </div>
                            <br>
                            <div class="offset-md-4">
                                <button type="button" class="btn btn-light delete-warehouse" style="color: blue;">
                                    Onayla
                                </button>
                                <button type="button" class="btn btn-light close-button" data-dismiss="modal">Vazgeç
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#delete-warehouse-modal").modal("show");
                });
                $(".close-button").click(function () {
                    $("#delete-warehouse-modal").modal("hide");
                    alertify.warning("İşlemden Vazgeçtiniz...");
                });
                $(".delete-warehouse").click(function () {
                    var id = $(".select-id").val();
                    var delete_detail = $("#delete_detail").val();
                    $.ajax({
                        url: "ajax/stok/stok-sql.php?islem=delete-warehouse",
                        type: "POST",
                        data: {
                            id: id,
                            delete_detail: delete_detail,
                        },
                        success: function () {
                            alertify.success("İşlem Başarı İle Gerçekleşti");
                            $("#delete-warehouse-modal").modal("hide");
                            $.get("ajax/stok/stok-depo.php?islem=yeni-depo-tanimla", function (getVeri) {
                                $("#get-warehouse-process").html(getVeri);
                            });
                        },
                        error: function () {
                            alertify.danger("Veritabanında Bir Sorun Oluştu");
                        }
                    });
                });
            </script>

            <?php }

            if ($islem == "yeni-depo-ekle") {
                ?>
                <div class="container">
                    <div class="modal fade" id="add-new-warehouse" data-bs-backdrop="static" data-bs-keyboard="false"
                         role="dialog">
                        <div class="modal-dialog" style="width: 55%; max-width: 55%;">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header text-white">
                                    <h4 class="modal-title">Yeni Depo Ekle</h4>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6 px-4">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label-sm">Depo Türü</label>
                                            <div class="col-sm-7">
                                                <select id="warehouse_type" class="form-select form-select-sm">
                                                    <option value="Seçiniz...">Seçiniz...</option>
                                                    <option value="28461">Ana</option>
                                                    <option value="2079">Cep</option>
                                                    <option value="30112">Ambar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-1">
                                            <label class="col-sm-3 col-form-label-sm">Bina Kodu</label>
                                            <div class="col-sm-7">
                                                <input type="number" id="buildingid"
                                                       class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-1">
                                            <label class="col-sm-3 col-form-label-sm">MKYS Kodu</label>
                                            <div class="col-sm-7">
                                                <input type="text" id="mkys_code" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row mt-1">
                                            <label class="col-sm-3 col-form-label-sm">Aktiflik Bilgisi</label>
                                            <div class="col-sm-7">
                                                <input type="checkbox" id="active_info"
                                                       class="form-check-input form-check-input-sm"><label
                                                        for="active_info">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-1">
                                            <label class="col-sm-3 col-form-label-sm">Depo Adı</label>
                                            <div class="col-sm-7">
                                                <input type="text" id="warehouse_name"
                                                       class="form-control form-control-sm warehouse_adi">
                                            </div>
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm close-button"
                                                style="background-color: #E64848; color: white;" data-dismiss="modal">
                                            Vazgeç
                                        </button>
                                        <button type="button" class="btn btn-sm add-warehouse"
                                                style="background-color: #4c954e; color: white;">Kaydet
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function () {
                        $("#add-new-warehouse").modal("show");
                    });
                    $(".close-button").click(function () {
                        $("#add-new-warehouse").modal("hide");
                        alertify.warning("İşlemden Vazgeçtiniz");
                    });
                    $(".add-warehouse").click(function () {
                        var warehouse_type = $("#warehouse_type").val();
                        var buildingid = $("#buildingid").val();
                        var mkys_code = $("#mkys_code").val();
                        var warehouse_name = $(".warehouse_adi").val();

                        if ($("[id='active_info']").is(":checked")) {
                            var active_info = $(this).val();
                            active_info = 1;
                        } else {
                            active_info = 0;
                        }
                        if (warehouse_type == "Seçiniz...") {
                            alertify.warning("Lütfen Depo Türü Seçiniz...");
                        } else {
                            $.ajax({
                                url: "ajax/stok/stok-sql.php?islem=add-new-warehouse",
                                type: "POST",
                                data: {
                                    warehouse_name: warehouse_name,
                                    warehouse_type: warehouse_type,
                                    mkys_code: mkys_code,
                                    buildingid: buildingid,
                                    active_info: active_info
                                },
                                success: function (result) {
                                    if (result == 1) {
                                        alertify.success("İşlem Başarılı");
                                        $("#add-new-warehouse").modal("hide");
                                        $.get("ajax/stok/stok-depo.php?islem=yeni-depo-tanimla", function (getVeri) {
                                            $("#get-warehouse-process").html(getVeri)
                                        });
                                    } else if (result == 2) {
                                        alertify.error("Bilinmeyen Bir Hata Oluştu");
                                    } else {
                                        alertify.warning("Bu Depo Zaten Mevcut")
                                    }
                                }
                            });
                        }
                    });
                </script>

            <?php }


            if ($islem == "silinecek-stok"){
            $id = $_GET["id"];
            ?>

            <div class="container">
                <div class="modal fade" id="delete-stock-from-warehouse-modal" data-bs-backdrop="static"
                     data-bs-keyboard="false" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-body">
                                <h5>Silme İşlemini Onayla</h5>
                                <hr>
                                <div>
                                    <div>
                                        <label></label>
                                        <label> Silme Nedeniniz</label>
                                        <input type="text" class="form-control delete_detail">
                                    </div>
                                    <div>
                                        <input type="hidden" disabled class="form-control" id="id"
                                               value="<?php echo $id ?>">
                                    </div>
                                    <br>
                                    <div class="offset-md-4">
                                        <button type="button" class="btn btn-light delete-stock-from-warehouse"
                                                style="color: blue;">Onayla
                                        </button>
                                        <button type="button" class="btn btn-light close-button" data-dismiss="modal">
                                            Vazgeç
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function () {
                            $("#delete-stock-from-warehouse-modal").modal("show");
                        });
                        $(".close-button").click(function () {
                            $("#delete-stock-from-warehouse-modal").modal("hide");
                            alertify.warning("İşlemden Vazgeçtiniz...");
                        });
                        $(".delete-stock-from-warehouse").click(function () {
                            var id = $("#id").val();
                            var delete_detail = $("#delete_detail").val();
                            $.ajax({
                                url: "ajax/stok/stok-sql.php?islem=depodaki-urunu-sil",
                                type: "POST",
                                data: {
                                    id: id,
                                    delete_detail: delete_detail
                                },
                                success: function (result) {
                                    $("#delete-stock-from-warehouse-modal").modal("hide");
                                    $.get("ajax/stok/stok-depo.php?islem=depoya-urun-ekle", function (getVeri) {
                                        $("#get-warehouse-process").html(getVeri);
                                    });
                                }
                            });
                        })
                    </script>
                    <?php }
                    if ($islem == "depoya-stok-giris-modal") {

                        ?>
                        <style>
                            .modal-body {
                                max-height: calc(100vh - 210px);
                                overflow-y: auto;
                            }
                        </style>
                        <div class="container">
                            <div class="modal fade" id="yeni-depo-girisi" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 75%; max-width: 75%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Depoya Yeni Ürün Girişi</h4>
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Stok Fiş No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"
                                                                   id="stock_receiptid">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Barkod</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-select barcode" disabled>
                                                                <option value="0">Seçiniz...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Lot No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="lot_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Seri Sıra No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"
                                                                   id="serial_queue_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Firma Tanımlayıcı
                                                            No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"
                                                                   id="company_descriptive_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Malzeme SUT Kodu</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" disabled
                                                                   id="stock_sut_code">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Stok Miktarı</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="stock_amount">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Taşınır No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control movable_code"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Satış Birim Fiyat</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" disabled
                                                                   id="sale_unit_price">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Ölçü Kodu</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"
                                                                   id="measurement_code">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Son Kullanma
                                                            Tarihi</label>
                                                        <div class="col-sm-7">
                                                            <input type="date" class="form-control"
                                                                   id="expiration_date">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Uyarılar</label>
                                                        <div class="col-sm-7">
                                                            <textarea id="stock_warnings" cols="1"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">MKYS Stok Hareket
                                                            Kodu</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control"
                                                                   id="mkys_stock_receipt_move_code">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">MKYS Karşı Stok Hareket
                                                            Kodu</label>
                                                        <div class="col-sm-7">
                                                            <input type="number" class="form-control"
                                                                   id="mkys_opposite_stock_move_code">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">MKYS Künye No</label>
                                                        <div class="col-sm-7">
                                                            <input type="number" class="form-control"
                                                                   id="mkys_tag_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">UYS Kayıt UDI</label>
                                                        <div class="col-sm-7">
                                                            <input type="number" class="form-control"
                                                                   id="uts_register_udi">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Firma Adı</label>
                                                        <div class="col-sm-7">
                                                            <select disabled class="form-select company_id">
                                                                <option value="null">Seçiniz...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Bayilik No</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-select dealership_number">

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Cihaz Kodu</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="device_code">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">ATS Sorgu No</label>
                                                        <div class="col-sm-7">
                                                            <input type="number" class="form-control"
                                                                   id="ats_query_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Depo Adı</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" disabled class="form-control depo_adi">
                                                            <input type="hidden" disabled
                                                                   class="form-control warehouse_ids">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Stok Türü</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" disabled class="form-control stock_tur">
                                                            <input type="hidden" disabled
                                                                   class="form-control stock_type_number">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Yaş Grubu</label>
                                                        <div class="col-sm-7">
                                                            <select id="stock_age_groups" class="form-select">
                                                                <option value="0-13 Yaş Arası">0-13 Yaş Arası</option>
                                                                <option value="13-18 Yaş Arası">13-18 Yaş Arası</option>
                                                                <option value="18-25 Yaş Arası">18-25 Yaş Arası</option>
                                                                <option value="25 Ve Üstü Yaşlar">25 Ve Üstü Yaşlar
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row mx-2">
                                                        <label class="col-sm-3 col-form-label">Açıklama</label>
                                                        <div class="col-sm-7">
                                                            <textarea id="drug_description" cols="1"
                                                                      class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm close-button"
                                                            style="background-color: #E64848; color: white;"
                                                            data-dismiss="modal">Vazgeç
                                                    </button>
                                                    <button type="button" class="btn btn-sm add-button"
                                                            style="background-color: #3F72AF; color: white;"
                                                            data-dismiss="modal">Kaydet
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>

                            $(document).ready(function () {
                                $("#yeni-depo-girisi").modal("show");
                            });
                            $(".close-button").click(function () {
                                $("#yeni-depo-girisi").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz");
                            });

                            $("#stock_receiptid").keypress(function (e) {
                                if (e.which === 13) {
                                    var id = $("#stock_receiptid").val();
                                    $.get("ajax/stok/stok-sql.php?islem=fis-getir", {id: id}, function (getVeri) {
                                        var json = JSON.parse(getVeri);

                                        json.forEach(function (item) {
                                            $(".barcode").append("<option value=" + item.barcode + ">" + item.barcode + "</option>");
                                        });
                                        $("#stock_receiptid").prop("disabled", true);
                                        $(".barcode").prop("disabled", false);
                                    });
                                    $.get("ajax/stok/stok-sql.php?islem=fis-bilgileri", {id: id}, function (result) {
                                        var json_veri = JSON.parse(result);

                                        $(".company_id").append("<option value=" + json_veri.company_id + ">" + json_veri.company_name + "</option>");
                                        $(".depo_adi").val(json_veri.warehouse_name);
                                        $(".stock_tur").val(json_veri.stock_type);
                                        $(".stock_type_number").val(json_veri.stock_type_number);
                                        $(".warehouse_ids").val(json_veri.warehouse_id);
                                        $(".company_id").prop("disabled", false);
                                    });
                                }
                            });

                            $(".barcode").on("change", function () {
                                var barcode = $(".barcode").val();
                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=sql-barkod-bilgisi",
                                    type: "GET",
                                    data: {
                                        barcode: barcode
                                    },
                                    success: function (getVeri) {
                                        var json_veriler = JSON.parse(getVeri);
                                        $("#stock_sut_code").val(json_veriler.stock_sut_code);
                                        $(".movable_code").val(json_veriler.movable_code);
                                        $("#sale_unit_price").val(json_veriler.sale_unit_price);
                                    }
                                });
                            });

                            $(".company_id").on("change", function () {
                                var company_id = $(".company_id").val();
                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=bayi-no-getir",
                                    type: "GET",
                                    data: {
                                        company_id: company_id
                                    },
                                    success: function (getJson) {
                                        var json_veri = JSON.parse(getJson);
                                        json_veri.forEach(function (item) {
                                            $(".dealership_number").append("<option value=" + item + ">" + item + "</option>");
                                        });
                                    }
                                });
                            });

                            $(".add-button").click(function () {

                                var stock_receiptid = $("#stock_receiptid").val();
                                var barcode = $(".barcode").val();
                                var lot_number = $("#lot_number").val();
                                var serial_queue_number = $("#serial_queue_number").val();
                                var company_descriptive_number = $("#company_descriptive_number").val();
                                var stock_sut_code = $("#stock_sut_code").val();
                                var movable_code = $(".movable_code").val();
                                var sale_unit_price = $("#sale_unit_price").val();
                                var measurement_code = $("#measurement_code").val();
                                var expiration_date = $("#expiration_date").val();
                                var stock_warnings = $("#stock_warnings").val();
                                var mkys_stock_receipt_move_code = $("#mkys_stock_receipt_move_code").val();
                                var mkys_opposite_stock_move_code = $("#mkys_opposite_stock_move_code").val();
                                var mkys_tag_number = $("#mkys_tag_number").val();
                                var uts_register_udi = $("#uts_register_udi").val();
                                var dealership_number = $("#dealership_number").val();
                                var device_code = $("#device_code").val();
                                var ats_query_number = $("#ats_query_number").val();
                                var company_id = $(".company_id").val();
                                var warehouse_id = $(".warehouse_ids").val();
                                var stock_type = $(".stock_type_number").val();
                                var stock_age_groups = $("#stock_age_groups").val();
                                var drug_description = $("#drug_description").val();
                                var stock_amount = $("#stock_amount").val();

                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=add-stock-from-warehouses",
                                    type: "POST",
                                    data: {
                                        stock_receiptid: stock_receiptid,
                                        barcode: barcode,
                                        lot_number: lot_number,
                                        serial_queue_number: serial_queue_number,
                                        company_descriptive_number: company_descriptive_number,
                                        stock_sut_code: stock_sut_code,
                                        movable_code: movable_code,
                                        sale_unit_price: sale_unit_price,
                                        measurement_code: measurement_code,
                                        expiration_date: expiration_date,
                                        stock_warnings: stock_warnings,
                                        mkys_stock_receipt_move_code: mkys_stock_receipt_move_code,
                                        mkys_opposite_stock_move_code: mkys_opposite_stock_move_code,
                                        mkys_tag_number: mkys_tag_number,
                                        uts_register_udi: uts_register_udi,
                                        dealership_number: dealership_number,
                                        device_code: device_code,
                                        ats_query_number: ats_query_number,
                                        company_id: company_id,
                                        warehouse_id: warehouse_id,
                                        stock_type: stock_type,
                                        stock_age_groups: stock_age_groups,
                                        stock_amount: stock_amount,
                                        drug_description: drug_description
                                    },
                                    success: function (result) {
                                        $(".sonucyaz").html(result);
                                        $("#yeni-depo-girisi").modal("hide");
                                        $.get("ajax/stok/stok-depo.php?islem=depoya-urun-ekle", function (getResult) {
                                            $("#get-tables").html(getResult);
                                        });
                                    }
                                });
                            });

                        </script>

                    <?php }
                    if ($islem == "depolar-arasi-stok") {
                        ?>
                        <style>
                            .modal-body {
                                max-height: calc(100vh - 150px);
                                overflow-y: auto;
                            }
                        </style>
                        <input type="hidden" value="<?php echo $_GET["id"]; ?>" id="transfer_id">
                        <input type="hidden" value="<?php echo $_GET["warehouse_id"]; ?>" id="catering_warehouse">
                        <div class="modal fade" id="urun-aktarim-modal" data-bs-backdrop="static" role="dialog">
                            <div class="modal-dialog" style="width: 100%; max-width: 100%;">
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h4 class="modal-title">Ürün Aktarım İstek Sayfası</h4>
                                        <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="col-12 row">
                                            <div class="col-4 kitlenecek-tablo">
                                                <div class="col-12">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="depo-filtrele" placeholder="Depo Adı...">
                                                </div>
                                                <div class="card-title text-center mt-1">Depo Listesi</div>
                                                <table class="table table-bordered table-hover nowrap display w-100 mt-1"
                                                       id="tablo_1"
                                                       style="background: white; width: 100%;font-weight: normal;font-size: 13px">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Depo Kodu</th>
                                                        <th>Depo Adı</th>
                                                        <th>MKYS Kodu</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="col-8 row">
                                                <div class="col-12">
                                                    <input type="text" class="form-control form-control-sm"
                                                           placeholder="Ürün Adı..." id="urun-adi-kelime">
                                                </div>
                                                <div class="card-title text-center mt-1">Malzeme Listesi</div>
                                                <table class="table table-bordered table-hover nowrap display mt-1"
                                                       id="tablo_2"
                                                       style="background: white; width: 100% !important;font-weight: normal;font-size: 13px">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Ürün Adı</th>
                                                        <th>Barkod No</th>
                                                        <th>Ürün Adet</th>
                                                        <th>Malzeme Türü</th>
                                                        <th>Birim</th>
                                                        <th>İşlem</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 row">
                                            <div class="card-title text-center mt-1">Eklenen Ürünler</div>
                                            <table class="table table-bordered table-hover nowrap display w-100 mt-1"
                                                   id="tablo_3"
                                                   style="background: white; width: 100%;font-weight: normal;font-size: 13px">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Ürün Adı</th>
                                                    <th>Barkod</th>
                                                    <th>İstek Miktarı</th>
                                                    <th>İsteyen Personel</th>
                                                    <th>İstek Tarihi</th>
                                                    <th>İşlem</th>
                                                </tr>
                                                </thead>
                                                <tbody class="transfer-eklenen-kalemler"></tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-outline-danger btn-sm" id="transfer-istek-iptal-button">
                                            <i
                                                    class="fa fa-times" aria-hidden="true"></i> Vazgeç
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" id="urunu-aktar"><i
                                                    class="fa-solid fa-code-pull-request"></i> İstek Yap
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="muadilleri-getir"></div>

                        <script>

                            $(document).ready(function () {
                                $("#urun-aktarim-modal").modal("show");
                                // setTimeout(function () {
                                var tablo_1 = $("#tablo_1").DataTable({
                                    "responsive": true,
                                    scrollX: true,
                                    scrollY: "28vh",
                                    info: false,
                                    paging: false,
                                    searching: false,
                                    createdRow: function (row) {
                                        $(row).addClass('depo-id');
                                    },
                                    bLengthChange: false,
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                    }
                                });

                                $("body").off("click", "#transfer-istek-iptal-button").on("click", "#transfer-istek-iptal-button", function () {
                                    var id = $("#transfer_id").val();
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=transfer-istegi-vazgec",
                                        type: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function (result) {
                                            if (result != 500 || result != 2) {
                                                alertify.warning("İşlemden Vazgeçtiniz");
                                                $("#urun-aktarim-modal").modal("hide");
                                            }
                                        }
                                    });
                                });
                                var catering_id = $("#catering_warehouse").val();
                                $.get("ajax/stok/stok-sql.php?islem=depolari-getir", {warehouse_id: catering_id}, function (result) {
                                    if (result != 2) {
                                        var json = JSON.parse(result);
                                        tablo_1.row.add([0, "Tümü", "MKYS Kod"]).draw(false);
                                        json.forEach(function (item) {
                                            tablo_1.row.add([item.id, item.warehouse_name, item.mkys_code]).draw(false);
                                        });
                                    } else {
                                        alert("Veritabanı Hatası Oluştu Sayfa Kapanacak");
                                    }
                                });
                                // }, 150);

                                // setTimeout(function () {
                                var i = 1;
                                var tablo_2 = $("#tablo_2").DataTable({
                                    "responsive": true,
                                    scrollX: true,
                                    scrollY: "28vh",
                                    info: false,
                                    paging: false,
                                    searching: false,
                                    bLengthChange: false,
                                    createdRow: function (row) {
                                        $(row).addClass('secilecek-muadil-' + i);
                                        i += 1;
                                    },
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                    }
                                });

                                $("#depo-filtrele").keydown(function () {
                                    setTimeout(function () {
                                        var kelime = $("#depo-filtrele").val();
                                        $.get("ajax/stok/stok-sql.php?islem=depo-adi-filtrele", {kelime: kelime}, function (result) {
                                            if (result != 2) {
                                                var json = JSON.parse(result);
                                                tablo_1.clear();
                                                tablo_1.row.add([0, "Tümü", "MKYS Kod"]).draw(false);
                                                json.forEach(function (item) {
                                                    tablo_1.row.add([item.id, item.warehouse_name, item.mkys_code]).draw(false);
                                                });
                                            } else {
                                                var json = JSON.parse(result);
                                                tablo_1.clear();
                                                tablo_1.row.add([0, "Tümü", "MKYS Kod"]).draw(false);

                                            }
                                        })
                                    }, 3);
                                });

                                $("body").off("click", "#listeden-kaldir").on("click", "#listeden-kaldir", function () {
                                    var id = $(this).attr("data-id");
                                    var silinece_tr = $(this).closest("tr");
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=transfer-kalem-vazgec",
                                        type: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function (result) {
                                            if (result != 2) {
                                                alertify.success("İşlem Başarılı");
                                                silinece_tr.remove();
                                            } else {
                                                alertify.warning("Bilinmeyen Bir hata İle Karşılaşıldı");
                                            }
                                        }
                                    });
                                });

                                var ware;
                                var ware_2;
                                $("body").off("click", ".depo-id").on("click", ".depo-id", function () {
                                    var data = tablo_1.row(this).data();
                                    var id = data[0];

                                    $(".depo-id").css("background-color", "rgb(255, 255, 255)");
                                    $(this).css("background-color", "#60b3abad");
                                    $('.depo-id').removeClass('select-depo-id');
                                    $(this).addClass("select-depo-id");
                                    
                                    ware_2 = id;hasta_recete_kalem
                                    ware = id;
                                    $.get("ajax/stok/stok-sql.php?islem=ait-urun-getir-sql", {id: id}, function (result) {
                                        if (result != 2) {
                                            var json = JSON.parse(result);
                                            tablo_2.clear();
                                            var no = 0;
                                            json.forEach(function (item) {
                                                no += 1;
                                                tablo_2.row.add([no, item.stock_name, item.barcode, item.stock_amount, item.malzeme_tur, item.unit, "<button title='Ürün Ekle' class='btn btn-outline-success btn-sm' id='transfer-istegini-olustur-button' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-plus'></i></button><button class='btn btn-outline-warning btn-sm mx-2' id='muadil-istek-buton' title='Muadil Ürün Ekle' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-equals'></i></button>"]).draw(false);
                                            });
                                        } else {
                                            alertify.warning("Depoya Ait Ürün Yok");
                                        }
                                    });
                                });

                                $("body").off("click", "#muadil-istek-buton").on("click", "#muadil-istek-buton", function () {
                                    var id = $(this).attr("data-id");
                                    var warehouse_id = ware_2;
                                    var stock_transferid = $("#transfer_id").val();
                                    var gidecek_class = $(this).closest('tr').attr('class')

                                    $.get("ajax/stok/stok-sql.php?islem=muadili-varmi", {
                                        id: id,
                                        warehouse_id: warehouse_id
                                    }, function (result) {
                                        if (result == 1) {
                                            $.get("ajax/stok/stok-modal.php?islem=muadil-listesi-getir", {
                                                id: id,
                                                gidecek_class: gidecek_class,
                                                warehouse_id: warehouse_id,
                                                stock_transferid: stock_transferid
                                            }, function (getModal) {
                                                $(".muadilleri-getir").html(getModal);
                                            });
                                        } else {
                                            alertify.warning("Depoda Ürünün Muadili Yoktur...");
                                        }
                                    });
                                });

                                $("body").off("click", "#urunu-aktar").on("click", "#urunu-aktar", function () {
                                    var transfer_id = $("#transfer_id").val();
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=transfer-istek-tamamla",
                                        type: "POST",
                                        data: {
                                            requested_warehouseid: ware_2,
                                            transfer_id: transfer_id
                                        },
                                        success: function (result) {
                                            if (result == 1) {
                                                alertify.success("İstek Kaydı Oluşturuldu");
                                                $("#urun-aktarim-modal").modal("hide");
                                                $.get("ajax/stok/stok-depo.php?islem=transfer-request", function (getVeri) {
                                                    $("#get-warehouse-process").html(getVeri);
                                                });
                                            } else {
                                                alertify.warning("İstek Kaydı Oluşurken Bir Hata Oluştu");
                                            }
                                        }
                                    })
                                });

                                $("#urun-adi-kelime").keydown(function () {
                                    var kelime = $("#urun-adi-kelime").val();
                                    setTimeout(function () {
                                        $.get("ajax/stok/stok-sql.php?islem=urun-adi-fitrele-sql", {
                                            kelime: kelime,
                                            depo_id: ware
                                        }, function (result) {
                                            if (result != 2) {
                                                var json = JSON.parse(result);
                                                var no = 0;
                                                tablo_2.clear();
                                                json.forEach(function (item) {
                                                    no += 1;
                                                    tablo_2.row.add([no, item.stock_name, item.barcode, item.stock_amount, item.malzeme_tur, item.unit, "<button title='Ürün Ekle' class='btn btn-outline-success btn-sm' id='transfer-istegini-olustur-sql' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-plus'></i></button><button class='btn btn-outline-warning btn-sm' id='muadil-istek-buton' title='Muadil Ürün Ekle' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-equals'></i></button>"]).draw(false);
                                                });
                                            }
                                        })
                                    }, 3);
                                });

                            });
                            $("body").off("click", "#transfer-istegini-olustur-button").on("click", "#transfer-istegini-olustur-button", function () {
                                var stock_cardid = $(this).attr("data-id");
                                var stock_transferid = $("#transfer_id").val();
                                $.get("ajax/stok/stok-modal.php?islem=getir-modal-kalem", {
                                    stock_cardid: stock_cardid,
                                    stock_transferid: stock_transferid,
                                }, function (getVeri) {
                                    $(".kalem-ekle-detay-modal").html(getVeri);
                                });
                            });
                        </script>
                        <div class="kalem-ekle-detay-modal"></div>
                    <?php }
                    if ($islem == "getir-modal-kalem") {
                        ?>
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true"
                             data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-center">İstek Bilgileri</h5>
                                    </div>
                                    <input type="hidden" value="<?php echo $_GET["stock_cardid"]; ?>" id="card_id">
                                    <input type="hidden" value="<?php echo $_GET["stock_transferid"]; ?>"
                                           id="transfer_id">
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="form-group row mt-1">
                                                <label class="col-sm-3 col-form-label-sm">Miktar</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="istek-miktari">
                                                </div>
                                            </div>
                                            <div class="form-group row mt-1">
                                                <label class="col-sm-3 col-form-label-sm">Tarih</label>
                                                <div class="col-sm-7">
                                                    <input type="date" class="form-control form-control-sm"
                                                           id="istek-tarihi">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-outline-danger btn-sm close-this-page"
                                                data-bs-dismiss="modal"><i
                                                    class="fa fa-times" aria-hidden="true"></i> Vazgeç
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" id="urunu-aktar-istek"><i
                                                    class="fa fa-plus" aria-hidden="true"></i> Ekle
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>

                            $(document).ready(function () {
                                $("#exampleModalCenter").modal("show");
                                document.getElementById('istek-tarihi').valueAsDate = new Date();
                                $("body").off("click", "#urunu-aktar-istek").on("click", "#urunu-aktar-istek", function () {
                                    var istek_miktar = $("#istek-miktari").val();
                                    var istek_tarihi = $("#istek-tarihi").val();
                                    var stock_cardid = $("#card_id").val();
                                    var transfer_id = $("#transfer_id").val();
                                    if (istek_miktar == "" || istek_miktar == 0) {
                                        alertify.warning("En Az 1 Adet Miktar Girmelisiniz...");
                                    } else {
                                        $.ajax({
                                            url: "ajax/stok/stok-sql.php?islem=transfer-istek-bilgileri-sql",
                                            type: "POST",
                                            data: {
                                                requested_amount: istek_miktar,
                                                stock_cardid: stock_cardid,
                                                stock_transferid: transfer_id,
                                                request_time: istek_tarihi
                                            },
                                            success: function (result) {
                                                if (result != 2 || result != 500) {
                                                    $(".depo-id").prop("disabled", true);
                                                    $("#exampleModalCenter").modal("hide");
                                                    var json = JSON.parse(result);
                                                    $(".transfer-eklenen-kalemler").html("");
                                                    json.forEach(function (item) {
                                                        $(".transfer-eklenen-kalemler").append(
                                                            "<tr>" +
                                                            "<td>" + item.urun_adi + "</td>" +
                                                            "<td>" + item.barkod + "</td>" +
                                                            "<td>" + item.requested_amount + "</td>" +
                                                            "<td>" + item.ad_soyad + "</td>" +
                                                            "<td>" + item.request_time + "</td>" +
                                                            "<td><button class='btn btn-outline-danger btn-sm' data-id='" + item.id + "' id='listeden-kaldir'>Sil</button></td>" +
                                                            "</tr>");
                                                    });

                                                } else {
                                                    alertify.error("Beklenmeyen Bir Hata İle Karşılaşıldı");
                                                }
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                    <?php }
                    if ($islem == "yeni-fatura-modal") {
                        ?>
                        <style>
                            .toplam-adet {
                                text-align: right;
                                color: red;
                            }

                            .toplam-ucret {
                                text-align: right;
                                color: red;
                            }

                            .toplam-adet-td {
                                text-align: right;
                                color: red;
                            }

                            .toplam-fiyat-td {
                                text-align: right;
                                color: red;
                            }

                            .toplam-kdv-td {
                                text-align: right;
                                color: red;
                            }

                            .toplam-iskonto-td {
                                text-align: right;
                                color: red;
                            }

                            .toplam-kdv-th {
                                text-align: right;
                                color: red;
                            }

                            .toplam-iskonto-th {
                                text-align: right;
                                color: red;
                            }

                            .genel-toplam-th {
                                text-align: right;
                                color: red;
                            }

                            .genel-toplam-td {
                                text-align: right;
                                color: red;
                            }
                        </style>
                        <div class="container">
                            <div class="modal fade" id="yeni-fatura-modal" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 98%; max-width: 98%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Yeni Fatura Oluştur</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 px-4">
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Tedarik Türü</label>
                                                    <div class="col-sm-7">
                                                        <select id="supply_type" class="form-select form-select-sm">
                                                            <option value="Seçiniz...">Seçiniz...</option>
                                                            <option value="Yeni Yıl Devir">Yeni Yıl Devir</option>
                                                            <option value="Bağış">Bağış</option>
                                                            <option value="DMO">DMO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Alım Yöntemi</label>
                                                    <div class="col-sm-7">
                                                        <select id="buying_method" class="form-select form-select-sm">
                                                            <option value="Seçiniz...">Seçiniz...</option>
                                                            <option value="Doğrudan Temin">Doğrudan Temin</option>
                                                            <option value="Açık İhale">Açık İhale</option>
                                                            <option value="Pazarlık Usulü">Pazarlık Usulü</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control form-control-sm fatura-firma-getir"
                                                                   data-id=""
                                                                   id="company_id"
                                                                   aria-describedby="basic-addon2"
                                                                   disabled>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-warning btn-sm get-company-button"
                                                                        type="button"><i
                                                                            class="fa fa-ellipsis-h"
                                                                            aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="firma-modal-fatura"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">İhale Tarihi</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" id="ihale-tarihi"
                                                               class="form-control form-control-sm ihale_tarih">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">İhale Kayıt No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" id="ihale-kayit-no"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">İrsaliye No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" id="waybill_number"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">İrsaliye Tarih</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" id="waybill_datetime"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 px-4 mt-2">
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Fatura Tarihi</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" id="bill_date"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Fatura No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" id="fatura-numarasi"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Depo Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" data-id="" disabled
                                                               class="form-control form-control-sm warehouse_name"
                                                               id="warehouse_id_receipt">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Firma Tanımlayıcı
                                                        No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text"
                                                               class="form-control form-control-sm identifier_no"
                                                               disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Teslim Eden Kişi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" id="delivery_person_info"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Teslim Eden Ünvanı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" id="delivery_person_title"
                                                               class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-1">
                                                    <label class="col-sm-3 col-form-label-sm">Hareket Türü</label>
                                                    <div class="col-sm-7">
                                                        <select id="move_type" class="form-select form-select-sm">
                                                            <option value="Seçiniz...">Seçiniz...</option>
                                                            <option value="1">Giriş</option>
                                                            <option value="2">Çıkış</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer ekle-butonları">
                                                <button type="button" class="btn btn-sm close-button"
                                                        style="background-color: #E64848; color: white;"
                                                        data-dismiss="modal">Vazgeç
                                                </button>
                                                <button type="button" class="btn btn-sm fatura-ekle"
                                                        style="background-color: #3F72AF; color: white;"
                                                        data-dismiss="modal">Faturayı Oluştur
                                                </button>
                                            </div>
                                            <div class="kalem-modal">
                                                <div class="col-12 mt-4">
                                                    <div>
                                                        <div class="card">
                                                            <div class="card-header">Ürün Bilgileri</div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <div class="input-group">
                                                                                    <input type="text"
                                                                                           class="form-control form-control-sm barcod-no"
                                                                                           data-id=""
                                                                                           stock-type=""
                                                                                           id="barcode"
                                                                                           title="Barkod No"
                                                                                           aria-describedby="basic-addon2"
                                                                                           placeholder="Barkod"
                                                                                           disabled>
                                                                                    <input type="hidden"
                                                                                           class="kalem-eklenecek-id">
                                                                                    <div class="input-group-append">
                                                                                        <button class="btn btn-outline-warning btn-sm get-barcodes"
                                                                                                type="button"><i
                                                                                                    class="fa fa-ellipsis-h"
                                                                                                    aria-hidden="true"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="barkod-getir-div"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <input type="text"
                                                                                       title="Ürün Adı"
                                                                                       placeholder="Ürün Adı"
                                                                                       class="form-control form-control-sm get-stok"
                                                                                       disabled>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1 row ">
                                                                            <div class="col-sm-7">
                                                                                <input type="number"
                                                                                       placeholder="Fiyat"
                                                                                       title="Birim Fiyatı"
                                                                                       class="form-control form-control-sm fiyati">
                                                                            </div>
                                                                            <div class="col-sm-5">
                                                                                <input type="text" id="stock_amount"
                                                                                       placeholder="Adet"
                                                                                       title="Adet"
                                                                                       class="form-control form-control-sm adedi">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <select class="form-select form-select-sm birimi"
                                                                                        title="Birim">
                                                                                    <option value="Seç">Birim Seç
                                                                                    </option>
                                                                                    <option value="Adet">Adet</option>
                                                                                    <option value="Litre">Litre</option>
                                                                                    <option value="Bidon">Bidon</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1 row">
                                                                            <div class="col-sm-6">
                                                                                <input type="number"
                                                                                       placeholder="İ.O."
                                                                                       title="İskonto Oranı"
                                                                                       class="form-control form-control-sm iskonto-oran-girisi">
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <input type="number" disabled
                                                                                       iskonto-tutar=""
                                                                                       placeholder="İ.T."
                                                                                       title="İskonto Tutarı"
                                                                                       class="form-control form-control-sm iskontolu-tutar">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <select class="form-select form-select-sm kdv-miktari"
                                                                                        id="kdv_percent"
                                                                                        title="KDV Oranı">
                                                                                    <option value="KDV">KDV</option>
                                                                                    <option value="0">0%</option>
                                                                                    <option value="1">1%</option>
                                                                                    <option value="8">8%</option>
                                                                                    <option value="18">18%</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <input type="text"
                                                                                       disabled
                                                                                       placeholder="KDV Tutar"
                                                                                       kdv-tutar=""
                                                                                       title="KDV Tutarı"
                                                                                       class="form-control form-control-sm kdv-tutari">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <input type="text"
                                                                                       placeholder="Lot No"
                                                                                       title="LOT No"
                                                                                       class="form-control form-control-sm lot-no">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="col-sm-12">
                                                                                <input type="text" placeholder="ATS No"
                                                                                       title="ATS No"
                                                                                       class="form-control form-control-sm ats-no">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="form-group row mt-1">
                                                                            <div class="col-sm-12">
                                                                                <input placeholder="Son Kullanma"
                                                                                       title="Son Kullanma Tarihi"
                                                                                       class="form-control form-control-sm son-kullanma"
                                                                                       type="text"
                                                                                       onfocus="(this.type='date')"
                                                                                       id="date">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1">
                                                                        <div class="mt-1">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm bayi-no-fatura"
                                                                                       data-id=""
                                                                                       title="Bayi No"
                                                                                       aria-describedby="basic-addon2"
                                                                                       placeholder="Bayi No"
                                                                                       disabled>
                                                                                <input type="hidden"
                                                                                       class="kaleme-eklenecek-bayi">
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-outline-warning btn-sm get-dealership"
                                                                                            type="button"><i
                                                                                                class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="bayiler-getir"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1 mt-1">
                                                                        <button class="btn btn-outline-success btn-sm faturaya-ekle"
                                                                                data-id="">
                                                                            Faturaya Ekle
                                                                            <i class="fa fa-plus-square"
                                                                               aria-hidden="true"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <table class="table table-bordered table-hover nowrap display w-100 dataTable no-footer dtr-inline"
                                                           id="fatura-kalem-table"
                                                           style="background: white; width: 100%;font-weight: normal;font-size: 13px">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Barkod</th>
                                                            <th>Ürün Adı</th>
                                                            <th>Birim Fiyat</th>
                                                            <th>Adet</th>
                                                            <th>Birim</th>
                                                            <th>İsk. Oran</th>
                                                            <th>İsk. Tutar</th>
                                                            <th>KDV Oran</th>
                                                            <th>KDV Tutar</th>
                                                            <th>Lot No</th>
                                                            <th>ATS No</th>
                                                            <th>Son Kullanma</th>
                                                            <th>Bayi No</th>
                                                            <th>M. Türü</th>
                                                            <th>İşlem</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="eklenen-kalemler-tablosu">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <table class="table table-bordered border-primary mb-0 table-style"
                                                               style=" background: #eff0f2; font-weight: normal;font-size: 13px">
                                                            <thead>
                                                            <tr>
                                                                <th class="toplam-adet">Toplam Adet</th>
                                                                <th class="toplam-kdv-th">KDV Tutar</th>
                                                                <th class="toplam-iskonto-th">İskonto Tutar</th>
                                                                <th class="genel-toplam-th">Toplam Ücret</th>
                                                                <th class="toplam-ucret">Genel Toplam</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="ucretler-tablosu">
                                                            <tr>
                                                                <td class="toplam-adet-td">0</td>
                                                                <td class="toplam-kdv-td">0</td>
                                                                <td class="toplam-iskonto-td">0</td>
                                                                <td class="genel-toplam-td">0</td>
                                                                <td class="toplam-fiyat-td">0</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-outline-danger btn-sm"
                                                            id="faturadan-vazgec-button">
                                                        Vazgeç
                                                        <i class="fa fa-times" aria-hidden="true"></i></button>
                                                    <button class="btn btn-outline-success btn-sm faturayı-kalemler-ile-kaydet">
                                                        Faturayı Kaydet
                                                        <i class="fa fa-check" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>

                            $(".faturayı-kalemler-ile-kaydet").click(function () {
                                var toplam_adet = $(".toplam-adet-td").html();
                                var toplam_kdv = $(".toplam-kdv-td").html();
                                var toplam_iskonto = $(".toplam-iskonto-td").html();
                                var genel_toplam = $(".genel-toplam-td").html();
                                var toplam_fiyat = $(".toplam-fiyat-td").html();
                                var id = $(".kalem-eklenecek-id").val();
                                var stock_receiptid = id.trim();
                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=faturayı-onayla",
                                    type: "POST",
                                    data: {
                                        stock_receiptid: stock_receiptid,
                                        total_amount: toplam_adet,
                                        total_kdv: toplam_kdv,
                                        total_discount: toplam_iskonto,
                                        total_price: genel_toplam,
                                        total_free_wat: toplam_fiyat
                                    },
                                    success: function (resutl) {
                                        $(".sonucyaz").html(resutl);
                                        $("#yeni-fatura-modal").modal("hide");
                                        $.get("ajax/stok/stok-tablo.php?islem=get-invoices", function (getTable) {
                                            $("#get-tables").html(getTable);
                                        });
                                    }
                                });
                            });

                            $(".get-dealership").click(function () {
                                var company_id = $(".fatura-firma-getir").attr("data-id");
                                $.get("ajax/stok/stok-modal.php?islem=bayino-getir", {id: company_id}, function (getModal) {
                                    $(".bayiler-getir").html(getModal);
                                });
                            });

                            $(".get-barcodes").click(function () {
                                $.get("ajax/stok/stok-modal.php?islem=barkodlari-getir", function (getModal) {
                                    $(".barkod-getir-div").html(getModal);
                                });
                            });

                            $(".get-company-button").click(function () {
                                $.get("ajax/stok/stok-modal.php?islem=firma-modal-getir", function (getModal) {
                                    $(".firma-modal-fatura").html(getModal);
                                });
                            });

                            $(document).ready(function () {
                                $("#yeni-fatura-modal").modal("show");
                                $(".kalem-modal").css("display", "none");
                                $(".fiyati").prop("disabled", true);
                                $(".adedi").prop("disabled", true);
                                $(".kdv-miktari").prop("disabled", true);
                                $(".lot-no").prop("disabled", true);
                                $(".ats-no").prop("disabled", true);
                                $(".birimi").prop("disabled", true);
                                $(".iskonto-oran-girisi").prop("disabled", true);
                                $(".son-kullanma").prop("disabled", true);
                                $(".faturaya-ekle").prop("disabled", true);

                                var depo_adi = $('.secilen-depo').find(":selected").attr("data-name");
                                var depo_id = $('.secilen-depo').find(":selected").val();
                                $(".warehouse_name").val(depo_adi);
                                $(".warehouse_name").attr("data-id", depo_id);
                            });
                            $(".close-button").click(function () {
                                $("#yeni-fatura-modal").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz");
                            });

                            $(".fatura-ekle").click(function () {
                                var supply_type = $("#supply_type").val();
                                var buying_method = $("#buying_method").val();
                                var ihale_tarih = $(".ihale_tarih").val();
                                var ihale_kayit_no = $("#ihale-kayit-no").val();
                                var waybill_number = $("#waybill_number").val();
                                var bill_date = $("#bill_date").val();
                                var fatura_numarasi = $("#fatura-numarasi").val();
                                var delivery_person_info = $("#delivery_person_info").val();
                                var delivery_person_title = $("#delivery_person_title").val();
                                var identifier_no = $(".identifier_no").val()

                                var ware_id = $("#warehouse_id_receipt").attr("data-id");

                                var move_type = $("#move_type").val();
                                var company_id = $(".fatura-firma-getir").attr("data-id");
                                if (company_id == null || company_id == "") {
                                    alertify.error("Lütfen Firma Seçimi Yapınız");
                                    $(".get-company-button").focus();
                                    $(".get-company-button").removeClass(" border border-danger");
                                } else if (identifier_no == "Seçiniz...") {
                                    alertify.error("Lütfen Firma Tanımlayıcı No Giriniz");
                                    $(".identifier_no").focus();
                                    $(".identifier_no").removeClass(" border border-danger");
                                } else if (move_type == "Seçiniz...") {
                                    alertify.error("Lütfen Hareket Türünü Seçiniz");
                                    $("#move_type").focus();
                                    $("#move_type").addClass(" border border-danger");
                                } else {
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=fatura-olustur",
                                        type: "POST",
                                        data: {
                                            companyid: company_id,
                                            identifier_no: identifier_no,
                                            supply_type: supply_type,
                                            buying_method: buying_method,
                                            tender_date: ihale_tarih,
                                            tender_number: ihale_kayit_no,
                                            waybill_number: waybill_number,
                                            warehouse_id: ware_id,
                                            bill_date: bill_date,
                                            bill_number: fatura_numarasi,
                                            delivery_person_title: delivery_person_title,
                                            delivery_person_info: delivery_person_info,
                                            move_type: move_type
                                        },
                                        success: function (result) {
                                            $(".kalem-eklenecek-id").val(result);
                                            $(".kalem-modal").show();
                                            $(".get-company-button").prop("disabled", true);
                                            $(".ekle-butonları").hide();
                                        }
                                    });
                                }
                            });

                            $("#move_type").change(function () {
                                $("#move_type").removeClass("border border-danger");
                                $("#move_type").addClass("border border-success");
                            });

                            $(".kdv-miktari").change(function () {
                                setTimeout(function () {
                                    var kdv_tutar = $(".kdv-miktari").val();
                                    var yuzde = kdv_tutar / 100
                                    var iskonto_orani = Number($(".iskontolu-tutar").attr("iskonto-tutar"));
                                    var kdv_toplami = iskonto_orani * yuzde;
                                    var kdv_toplami_fiyat = iskonto_orani + (iskonto_orani * yuzde);
                                    $(".kdv-tutari").val(kdv_toplami);
                                    $(".kdv-tutari").attr("kdv-tutar", kdv_toplami_fiyat);
                                }, 1);
                            });
                            $("#stock_amount").keydown(function () {
                                setTimeout(function () {
                                    var adet = $("#stock_amount").val();
                                    var fiyat = $(".fiyati").val();
                                    var net_tutar = fiyat * adet;
                                    var iskonto_oran = $(".iskonto-oran-girisi").val();
                                    var yuzde = iskonto_oran / 100;
                                    var iskonto_tutar = net_tutar * yuzde;
                                    var iskontolu = net_tutar - (net_tutar * yuzde);
                                    $(".iskontolu-tutar").attr("iskonto-tutar", iskontolu);
                                    $(".iskontolu-tutar").val(iskonto_tutar);
                                    $(".kdv-tutari").val(iskonto_tutar);
                                    $(".kdv-tutari").attr("kdv-tutar", iskontolu);
                                }, 1);
                            });

                            $(".iskonto-oran-girisi").keydown(function (e) {

                                setTimeout(function () {
                                    var adet = $("#stock_amount").val();
                                    var fiyat = $(".fiyati").val();
                                    var net_tutar = fiyat * adet;
                                    var iskonto_oran = $(".iskonto-oran-girisi").val();
                                    var yuzde = iskonto_oran / 100;
                                    var iskonto_tutar = net_tutar * yuzde;
                                    var iskontolu = net_tutar - (net_tutar * yuzde);
                                    $(".iskontolu-tutar").attr("iskonto-tutar", iskontolu);
                                    $(".iskontolu-tutar").val(iskonto_tutar);
                                }, 1);
                            });
                            var kdv_tutar = 0;
                            var adet = 0;
                            var iskonto = 0;
                            var t_ucret = 0;
                            var g_toplam = 0;
                            var silme_deger = 0;
                            var arr = [];
                            var no = 0;

                            $(".fiyati").keyup(function () {
                                $(this).removeClass(" border border-danger");
                                $(this).addClass(" border border-success");
                            });
                            $(".adedi").keyup(function () {
                                $(this).removeClass(" border border-danger");
                                $(this).addClass(" border border-success");
                            });
                            $(".birimi").change(function () {
                                $(this).removeClass(" border border-danger");
                                $(this).addClass(" border border-success");
                            });
                            $(".son-kullanma").change(function () {
                                var stock_type = $(".barcod-no").attr("stock-type");
                                if (stock_type == "28464") {
                                    $(this).removeClass(" border border-danger");
                                    $(this).addClass(" border border-success");
                                }
                            });

                            $(".faturaya-ekle").click(function () {
                                var id = $(".kalem-eklenecek-id").val();
                                var stock_receiptid = id.trim();
                                var stock_id = $("#barcode").attr("data-id");
                                var barcode = $("#barcode").val();
                                var stock_name = $(".get-stok").val();
                                var sale_price = $(".fiyati").val();
                                var stock_amount = $(".adedi").val();
                                var unit = $(".birimi").val();
                                var discount_percent = $(".iskonto-oran-girisi").val();
                                var discount_percent_total = $(".iskontolu-tutar").attr("iskonto-tutar");
                                var discount_price_applied = $(".iskontolu-tutar").val();
                                var kdv_percent = $(".kdv-miktari").val();
                                var kdv_percent_total = $(".kdv-tutari").attr("kdv-tutar");
                                var kdv_percent_applied = $(".kdv-tutari").val();
                                var lot_number = $(".lot-no").val();
                                var ats_number = $(".ats-no").val();
                                var expiration_date = $(".son-kullanma").val();
                                var hareket_turu = $("#move_type").val();
                                var depo_id = $(".warehouse_name").attr("data-id");
                                var company_id = $("#company_id").attr("data-id");
                                var stock_type = $(".barcod-no").attr("stock-type");
                                var dealership_number = $(".bayi-no-fatura").val();


                                if (sale_price == "" || sale_price == null) {
                                    alertify.warning("Lütfen Fiyat Giriniz");
                                    $(".fiyati").focus();
                                    $(".fiyati").addClass(" border border-danger");
                                } else if (stock_amount == "" || stock_amount == null) {
                                    alertify.warning("Lütfen Adet Giriniz");
                                    $(".adedi").focus();
                                    $(".adedi").addClass(" border border-danger");
                                } else if (unit == "Seç" || unit == null) {
                                    alertify.warning("Lütfen Birim Giriniz");
                                    $(".birimi").focus();
                                    $(".birimi").addClass(" border border-danger");
                                } else if (stock_type == "28464" && expiration_date == "") {
                                    alertify.warning("İlaç İçin Son Kullanma Tarihi Giriniz...");
                                    $(".son-kullanma").focus();
                                    $(".son-kullanma").addClass(" border border-danger");
                                } else {
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=faturaya-kalem-ekle",
                                        type: "POST",
                                        data: {
                                            dealership_number: dealership_number,
                                            stock_type: stock_type,
                                            warehouse_id: depo_id,
                                            company_id: company_id,
                                            move_type: hareket_turu,
                                            stock_receiptid: stock_receiptid,
                                            stock_cardid: stock_id,
                                            barcode: barcode,
                                            stock_name: stock_name,
                                            sale_price: sale_price,
                                            stock_amount: stock_amount,
                                            unit: unit,
                                            discount_percent: discount_percent,
                                            discount_total: discount_percent_total,
                                            discount_price_applied: discount_price_applied,
                                            kdv_percent: kdv_percent,
                                            kdv_total: kdv_percent_total,
                                            kdv_price_applied: kdv_percent_applied,
                                            lot_number: lot_number,
                                            ats_number: ats_number,
                                            expiration_date: expiration_date
                                        },
                                        success: function () {
                                            alertify.success("Kayıt Oluşturuldu");
                                            if (stock_type == "26464") {
                                                stock_type = "İlaç"
                                            } else {
                                                stock_type = "sarf";
                                            }
                                            if (jQuery.inArray(stock_id, arr) !== -1) {
                                                var tablodaki_deger = $('.stok-miktar-' + stock_id + '').html();
                                                var son_deger = Number(tablodaki_deger) + Number(stock_amount);
                                                $('.stok-miktar-' + stock_id + '').html(son_deger);
                                            } else {
                                                arr.push(stock_id);
                                                no += 1;
                                                if (discount_percent == null || discount_percent == "") {
                                                    discount_percent = 0;
                                                }
                                                $(".eklenen-kalemler-tablosu").append(
                                                    "<tr class='stok-fatura-grubu' data-id='" + stock_id + "'>" +
                                                    "<td>" + no + "</td>" +
                                                    "<td>" + barcode + "</td>" +
                                                    "<td>" + stock_name + "</td>" +
                                                    "<td>" + sale_price + " TL</td>" +
                                                    "<td id='faturadaki-stok-miktar' class='stok-miktar-" + stock_id + "'>" + stock_amount + "</td>" +
                                                    "<td>" + unit + "</td>" +
                                                    "<td>" + discount_percent + "</td>" +
                                                    "<td id='faturadaki-iskonto' data-id='" + discount_percent_total + "'>" + discount_price_applied + "</td>" +
                                                    "<td>" + kdv_percent + "</td>" +
                                                    "<td class='toplam-kdv-tutar' data-id='" + kdv_percent_total + "'>" + kdv_percent_applied + "</td>" +
                                                    "<td>" + lot_number + "</td>" +
                                                    "<td>" + ats_number + "</td>" +
                                                    "<td>" + expiration_date + "</td>" +
                                                    "<td>" + dealership_number + "</td>" +
                                                    "<td>" + stock_type + "</td>" +
                                                    "<td><button class='btn btn-sm delete-urun-kalem' data-id=" + stock_id + " stok-adet='" + stock_amount + "'" +
                                                    " kdv-tutar='" + kdv_percent_applied + "'" + " iskontosu='" + discount_price_applied + "' " +
                                                    "t_ucret='" + discount_percent_total + "' " + " g_toplam='" + kdv_percent_total + "'" +
                                                    "depo_id='" + depo_id + "' " + " move_type='" + hareket_turu + "'" +
                                                    "stock_receiptid='" + stock_receiptid + "' " +
                                                    "><i class='fa fa-trash' aria-hidden='true'></i></button></td>" +
                                                    "</tr>"
                                                );
                                            }

                                            $(".fiyati").val("");
                                            $(".barcod-no").val("");
                                            $(".barcod-no").removeAttr("data-id");
                                            $(".adedi").val("");
                                            $(".iskonto-oran-girisi").val("");
                                            $(".iskontolu-tutar").val("");
                                            $(".iskontolu-tutar").removeAttr("iskonto-tutar");
                                            $(".kdv-miktari").val("KDV");
                                            $(".get-stok").val("");
                                            $(".kdv-tutari").val("");
                                            $(".kdv-tutari").removeAttr("kdv-tutar");
                                            $(".lot-no").val("");
                                            $(".ats-no").val("");
                                            $(".birimi").val("Seç");
                                            $(".son-kullanma").val("");
                                            $(".bayi-no-fatura").val("");
                                            $(".faturaya-ekle").prop("disabled", true);

                                            kdv_tutar += Number(kdv_percent_applied);
                                            adet += Number(stock_amount);
                                            iskonto += Number(discount_price_applied);
                                            t_ucret += Number(discount_percent_total);
                                            g_toplam += Number(kdv_percent_total);

                                            $(".toplam-kdv-td").html(kdv_tutar);
                                            $(".toplam-adet-td").html(adet);
                                            $(".genel-toplam-td").html(t_ucret);
                                            $(".toplam-fiyat-td").html(g_toplam);
                                            $(".toplam-iskonto-td").html(iskonto);
                                        }
                                    });
                                }
                            });

                            $(document).on('click', '.delete-urun-kalem', function () {
                                var silinecek_tr = $(this).closest("tr");
                                var id = $(this).attr("data-id");
                                var kdv_percent_applied = $(this).attr("kdv-tutar");
                                var stock_amount = $(this).attr("stok-adet");
                                var discount_price_applied = $(this).attr("iskontosu");
                                var discount_percent_total = $(this).attr("t_ucret");
                                var kdv_percent_total = $(this).attr("g_toplam");
                                var warehouse_id = $(this).attr("depo_id");
                                var receipt_id = $(this).attr("stock_receiptid");
                                var move_type = $(this).attr("move_type");

                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=faturadan-sil",
                                    type: "POST",
                                    data: {
                                        move_type: move_type,
                                        stock_amount: stock_amount,
                                        warehouse_id: warehouse_id,
                                        stock_id: id,
                                        receipt_id: receipt_id
                                    },
                                    success: function () {
                                        alertify.success("İşlem Başarılı");
                                        kdv_tutar -= Number(kdv_percent_applied);
                                        adet -= Number(stock_amount);
                                        iskonto -= Number(discount_price_applied);
                                        t_ucret -= Number(discount_percent_total);
                                        g_toplam -= Number(kdv_percent_total);
                                        $(".toplam-kdv-td").html(kdv_tutar);
                                        $(".toplam-adet-td").html(adet);
                                        $(".genel-toplam-td").html(t_ucret);
                                        $(".toplam-fiyat-td").html(g_toplam);
                                        $(".toplam-iskonto-td").html(iskonto);
                                        silinecek_tr.remove();
                                    }
                                });
                            });

                            $("#faturadan-vazgec-button").click(function () {
                                var id = $(".kalem-eklenecek-id").val();
                                var stock_receiptid = id.trim();
                                var warehouse_id = $(".warehouse_name").attr("data-id")

                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=fatura-vazgec",
                                    type: "POST",
                                    data: {
                                        warehouse_id: warehouse_id,
                                        stock_receiptid: stock_receiptid
                                    },
                                    success: function () {
                                        alertify.warning("İşlemden Vazgeçtiniz");
                                        $("#yeni-fatura-modal").modal("hide");
                                        $.get("ajax/stok/stok-tablo.php?islem=get-invoices", function (getTable) {
                                            $("#get-tables").html(getTable);
                                        });
                                    }
                                });
                            });

                        </script>
                    <?php }
                    if ($islem == "fatura-detay") {
                        $id = $_POST["id"];
                        $sorgu = tek(" SELECT * FROM stock_receipt WHERE id='$id'");

                        $firmaid = $sorgu["companyid"];
                        $depoid = $sorgu["warehouseid"];
                        $stock_cardid = $sorgu["stock_cardid"];
                        $hareket_type = $sorgu["move_type"];

                        if ($hareket_type == 1) {
                            $hareket_type = "Giriş";
                        } else {
                            $hareket_type = "Çıkış";
                        }
                        $ids = explode(',', $stock_cardid);
                        $urunler = verilericoklucek(" SELECT stock_name FROM stock_card WHERE id IN($stock_cardid)");
                        $depoadi = singular("warehouses", "id", $depoid);
                        $firmaadi = singular("companies", "id", $firmaid);
                        ?>
                        <div class="container">
                            <div class="modal fade" id="fatura-detay-modal" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 75%; max-width: 75%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Fatura Detay</h4>
                                            <input type="hidden" id="id" class="form-control" value="<?php echo $id ?>">
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 px-4">
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Firma Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="transaction_datetime"
                                                               value="<?php echo $firmaadi["company_name"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Teslim Eden Kişi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="delivery_person_info"
                                                               value="<?php echo $sorgu["delivery_person_info"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Teslim Eden Ünvanı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="delivery_person_title"
                                                               value="<?php echo $sorgu["delivery_person_title"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Fatura Tarihi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="bill_date"
                                                               value="<?php echo $sorgu["bill_date"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Hareket Türü</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="move_type"
                                                               value="<?php echo $hareket_type; ?>"
                                                               class="form-control" <?php echo $sorgu["move_type"]; ?> >
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">İrsaliye No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="waybill_number"
                                                               value="<?php echo $sorgu["waybill_number"] ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>

                                            </div>
                                            <div class="col-4 px-2">
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">İrsaliye Tarih</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="waybill_datetime"
                                                               value="<?php echo $sorgu["waybill_datetime"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Belge No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="receipt_number"
                                                               value="<?php echo $sorgu["receipt_number"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Malzeme Tipi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="stock_type" class="form-control"
                                                               value="<?php echo islemtanimgetirid($sorgu["stock_type"]); ?>">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Depo Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="warehouseid"
                                                               value="<?php echo $depoadi["warehouse_name"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Toplam Tutar</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="receipt_total_amount"
                                                               class="form-control"
                                                               value="<?php echo $sorgu["receipt_total_amount"]; ?>">
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-4">
                                                <br>
                                                <table class="table table-bordered table-sm table-hover px-2"
                                                       id="stock-add-table" style="background: white; width: 100%;">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Malzeme Adı</th>
                                                        <th>Malzeme Adet Fiyatı</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $urunun_fiyati = explode(',', $sorgu["total_price_of_the_product"]);
                                                    foreach ($urunler as $key => $urun) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $urun["stock_name"]; ?></td>
                                                            <td><?php echo $urunun_fiyati[$key] . " TL"; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm close-button"
                                                        style="background-color: #E64848; color: white;"
                                                        data-dismiss="modal">Detayı Kapat
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $("#fatura-detay-modal").modal("show");
                            });
                            $(".close-button").click(function () {
                                $("#fatura-detay-modal").modal("hide");
                                alertify.warning("Detay Kapatıldı...");
                            });
                        </script>
                    <?php }

                    if ($islem == "fatura-guncelle-modal") {
                        $id = $_GET["id"];

                        $sorgu = tek(" SELECT * FROM stock_receipt WHERE id='$id'");
                        $firmaid = $sorgu["companyid"];
                        $depoid = $sorgu["warehouseid"];
                        $stock_cardid = $sorgu["stock_cardid"];
                        $hareket_type = $sorgu["move_type"];

                        if ($hareket_type == 1) {
                            $hareket_type = "Giriş";
                        } else {
                            $hareket_type = "Çıkış";
                        }
                        $urunler = verilericoklucek(" SELECT stock_name,id FROM stock_card WHERE id IN($stock_cardid)");
                        $depoadi = singular("warehouses", "id", $depoid);
                        $firmaadi = singular("companies", "id", $firmaid);
                        ?>
                        <div class="container" id="again">
                            <div class="modal fade" id="fatura-guncelle" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 85%; max-width: 85%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Fatura Güncelleme Sayfası</h4>
                                            <input type="hidden" id="id" class="form-control" value="<?php echo $id ?>">
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 px-4">
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Firma Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="date" disabled id="transaction_datetime"
                                                               value="<?php echo $firmaadi["company_name"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Teslim Eden Kişi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="delivery_person_info"
                                                               value="<?php echo $sorgu["delivery_person_info"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Teslim Eden Ünvanı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="delivery_person_title"
                                                               value="<?php echo $sorgu["delivery_person_title"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Fatura Tarihi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="bill_date"
                                                               value="<?php echo $sorgu["bill_date"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Hareket Türü</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="move_type"
                                                               value="<?php echo $hareket_type; ?>"
                                                               class="form-control" <?php echo $sorgu["move_type"]; ?> >
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">İrsaliye No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="waybill_number"
                                                               value="<?php echo $sorgu["waybill_number"] ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>

                                            </div>
                                            <div class="col-4 px-2">
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">İrsaliye Tarih</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="waybill_datetime"
                                                               value="<?php echo $sorgu["waybill_datetime"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Belge No</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="receipt_number"
                                                               value="<?php echo $sorgu["receipt_number"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Malzeme Tipi</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="stock_type" class="form-control"
                                                               value="<?php echo islemtanimgetirid($sorgu["stock_type"]); ?>">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Depo Adı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="warehouseid"
                                                               value="<?php echo $depoadi["warehouse_name"]; ?>"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Toplam Tutar</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled id="receipt_total_amount"
                                                               class="form-control"
                                                               value="<?php echo $sorgu["receipt_total_amount"] . ' TL'; ?>">
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="col-4 px-2">
                                                <br>
                                                <table class="table table-bordered table-sm table-hover px-2"
                                                       id="fatura-malzeme" style="background: white; width: 100%;">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Malzeme Adı</th>
                                                        <th>Fiyat</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($urunler as $key => $urun) {
                                                        $urunun_fiyati = explode(',', $sorgu["total_price_of_the_product"]);
                                                        ?>
                                                        <tr class="select-id" id="<?php echo $urun["id"]; ?>"
                                                            ucret="<?php echo $urunun_fiyati[$key]; ?>">
                                                            <td><?php echo $urun["stock_name"]; ?></td>
                                                            <td><?php echo $urunun_fiyati[$key] . " TL"; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12" id="buttons">
                                                    <div class="d-flex justify-content-end align-items-end ">
                                                        <div class="text-center d-flex flex-column me-3">
                                                            <button class="btn btn-sm fatura-tamamen-sil"
                                                                    style="background-color: #E64848;">
                                                                <img src="assets/icons/Close.png" alt="icon"
                                                                     width="40px">
                                                            </button>
                                                            <label>Sil</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm close-button"
                                                        style="background-color: #E64848; color: white;"
                                                        data-dismiss="modal">Detayı Kapat
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $("#fatura-guncelle").modal("show");
                                $("#fatura-malzeme").DataTable({
                                    "responsive": true,
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                    },
                                });
                            });

                            $(".select-id").click(function () {
                                var stock_cardid = $(this).attr("id");
                                var stock_amount = $(this).attr("ucret");
                                $('.select-id').css("background-color", "rgb(255, 255, 255)");
                                $(this).css("background-color", "#60b3abad");
                                $('.select-id').removeClass('select');
                                $(this).addClass("select");

                            });

                            $(".fatura-tamamen-sil").click(function () {
                                var stock_cardid = $(".select").attr("id");
                                var stock_amount = $(".select").attr("ucret");
                                var receipt_id = $("#id").val();
                                var receipt_total_amount = $("#receipt_total_amount").val();

                                if (stock_cardid == null) {
                                    alertify.warning("Silmek İstediğiniz Ürünü Listeden Seçiniz...");
                                } else {

                                    alertify.confirm('Silme İşlemini Onayla', '<div class="alert alert-danger">Silme İşlemini Onaylamak Üzeresiniz!!!</div>', function () {
                                            $.ajax({
                                                url: "ajax/stok/stok-sql.php?islem=faturadan-tamamen-sil",
                                                type: "POST",
                                                data: {
                                                    stock_cardid: stock_cardid,
                                                    stock_amount: stock_amount,
                                                    receipt_id: receipt_id,
                                                    receipt_total_amount: receipt_total_amount
                                                },
                                                success: function (result) {
                                                    alertify.success(result);
                                                    $("#fatura-guncelle").modal("hide");
                                                    $.get("ajax/stok/stok-modal.php?islem=fatura-guncelle-modal", {id: receipt_id}, function (getVeri) {
                                                        $("#again").html(getVeri);
                                                    });
                                                }
                                            });
                                        }
                                        , function () {
                                            alertify.message('Vazgeçtiniz')
                                        });
                                }
                            });
                            $(".close-button").click(function () {

                                $("#fatura-guncelle").modal("hide");
                                alertify.warning("Detay Kapatıldı...");
                                $.get("ajax/stok/stok-tablo.php?islem=get-invoices", function (getTable) {
                                    $("#get-tables").html(getTable);
                                });
                            });
                        </script>
                    <?php }

                    if ($islem == "depodan-urun-cikis") {
                        ?>
                        <div class="container">
                            <div class="modal fade" id="depodan-urun-cikis" data-bs-backdrop="static"
                                 data-bs-keyboard="false" role="dialog">
                                <div class="modal-dialog" style="width: 50%; max-width: 50%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Depodan Ürün Çıkış</h4>
                                            <input type="hidden" id="id" class="form-control">
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row px-2">
                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Fiş No</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" id="fis-no" class="form-control">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Barkod No</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-select barcode" disabled>
                                                                <option value="0">Seçiniz...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Depodan Çıkacak
                                                            Miktar</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control stock_amount">
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm close-button"
                                                            style="background-color: #E64848; color:white;">Vazgeç
                                                    </button>
                                                    <button class="btn btn-sm depodan-cikisi-ver"
                                                            style="background-color: #112D4E; color:white;">Depodan
                                                        Çıkışı Yap
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>

                            $(document).ready(function () {
                                $("#depodan-urun-cikis").modal("show");
                            });

                            $(".close-button").click(function () {
                                $("#depodan-urun-cikis").modal("hide");
                                alertify.warning("İşlemden Vazgeçtiniz...");
                            });

                            $(".depodan-cikisi-ver").click(function () {
                                var fis_no = $("#fis-no").val();
                                var barcode = $(".barcode").val();
                                var stock_amount = $(".stock_amount").val();

                                $.ajax({
                                    url: "ajax/stok/stok-sql.php?islem=depodan-cikis",
                                    type: "POST",
                                    data: {
                                        stock_receiptid: fis_no,
                                        barcode: barcode,
                                        stock_amount: stock_amount
                                    },
                                    success: function (getVeri) {
                                        $(".sonucyaz").html(getVeri);
                                        $("#depodan-urun-cikis").modal("hide");
                                        $.get("ajax/stok/stok-depo.php?islem=depoya-urun-ekle", function (getAgain) {
                                            $("#get-tables").html(getAgain);
                                        });
                                    }
                                });
                            });

                            $("#fis-no").keypress(function (e) {
                                if (e.which === 13) {
                                    var fis_no = $("#fis-no").val();
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=fis-ile-barkod-getir",
                                        type: "GET",
                                        data: {
                                            id: fis_no
                                        },
                                        success: function (getVeri) {
                                            if (getVeri.trim() === null || getVeri.trim() == "") {
                                                alertify.warning("Bu Fatura Numarası Bir Çıkış Faturası Değildir Lütfen Geçerli Bir Çıkış Fatura Numarası Giriniz");
                                            } else {
                                                var json_veri = JSON.parse(getVeri);
                                                json_veri.forEach(function (item) {
                                                    $(".barcode").append("<option value=" + item.barcode + ">" + item.barcode + "</option>");
                                                });
                                                $("#fis-no").prop("disabled", true);
                                                $(".barcode").prop("disabled", false);
                                            }
                                        }
                                    });
                                }
                            });
                        </script>
                    <?php }
                    if ($islem == "istegi-onayla-modal") {
                        $id = $_GET["id"];
                        $given_stock = $_GET["given_stock"];
                        $singular_cek = singular("stock_request_move", "id", $id);
                        ?>
                        <style>
                            .modal-body {
                                max-height: calc(100vh - 150px);
                                overflow-y: auto;
                            }
                        </style>

                        <div class="modal fade" id="istek-karsila-modal" data-bs-backdrop="static" role="dialog">
                            <div class="modal-dialog" style="width: 65%; max-width: 65%;">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h4 class="modal-title">Talep Karşıla</h4>
                                        <input type="hidden" id="id" class="form-control">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        <input type="hidden" id="<?php echo $id ?>" class="request_id">
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">İstek Miktarı</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" disabled class="form-control"
                                                               value="<?php echo $singular_cek["requested_amount"]; ?>"
                                                               id="requested_amount">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Karşılanacak Miktar</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control"
                                                               id="given_amount_from_warehouse">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Karşılayan Depo</label>
                                                    <div class="col-sm-7">
                                                        <select id="given_from_warehouse" class="form-select">
                                                            <option value="0">Seçiniz...</option>
                                                            <?php
                                                            $veriler_sorgu = verilericoklucek(" 
                                                       select distinct w.warehouse_name  as depo_adi ,w.id as depo_id,srm.stock_cardid as stock_cardid  from stock_receipt_move as srm
                                                       inner join stock_card as sc on sc.id=srm.stock_cardid
                                                       inner join warehouses as w on w.id=srm.warehouse_id
                                                       where srm.stock_cardid='$given_stock' and w.status=1 
                                                       ORDER BY w.warehouse_name ASC");
                                                            foreach ($veriler_sorgu as $sorgu) {
                                                                ?>
                                                                <option stock_cardid="<?php echo $sorgu["stock_cardid"]; ?>"
                                                                        value="<?php echo $sorgu["depo_id"]; ?>"><?php echo $sorgu["depo_adi"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-sm w-md justify-content-end"
                                                        style="background-color: #E64848; color:white;"
                                                        data-bs-dismiss="modal" type="button">Kapat
                                                </button>
                                                <button id="select_item_send"
                                                        class="btn btn-sm w-md justify-content-end"
                                                        style="background-color: #112D4E; color:white;"
                                                        data-dismiss="modal" type="button">Gönder
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            $(document).ready(function () {
                                $("#istek-karsila-modal").modal("show");
                            });
                            $("#select_item_send").click(function () {
                                var request_id = $(".request_id").attr("id");
                                var given_amount_from_warehouse = $("#given_amount_from_warehouse").val();
                                var requested_amount = $("#requested_amount").val();
                                var given_from_warehouse = $("#given_from_warehouse").val();

                                var given_stock_cardid = $('#given_from_warehouse option:selected').attr('stock_cardid');

                                if (given_amount_from_warehouse > requested_amount) {
                                    alertify.error("Hatalı İşlem");
                                } else {
                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=istek-karsila-sql",
                                        type: "POST",
                                        data: {
                                            given_stock_cardid: given_stock_cardid,
                                            given_amount_from_warehouse: given_amount_from_warehouse,
                                            requested_amount: requested_amount,
                                            given_from_warehouse: given_from_warehouse,
                                            id: request_id
                                        },
                                        success: function (result) {
                                            $(".sonucyaz").html(result);
                                            $("#istek-karsila-modal").modal("hide");
                                            $.get("ajax/stok/stok-depo.php?islem=istekler", function (result) {
                                                $("#get-warehouse-process").html(result);
                                            });
                                        }
                                    });
                                }

                            });
                        </script>
                    <?php }

                    if ($islem == "gelen-istek-red-modal"){
                    $id = $_GET["id"];
                    ?>
                    <div class="modal fade" id="istek-red" data-bs-backdrop="static" data-bs-keyboard="false"
                         role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h5>İstek Red</h5>
                                    <hr>
                                    <div>
                                        <div>
                                            <label></label>
                                            <label>Red Nedenini Giriniz</label>
                                            <input type="text" class="form-control stock_request_rejection_cause">
                                        </div>
                                        <div>
                                            <input type="hidden" disabled class="form-control request_id"
                                                   value="<?php echo $id ?>">
                                        </div>
                                        <br>
                                        <div class="offset-md-4">
                                            <button type="button" class="btn btn-light stock_rejection"
                                                    style="color: blue;">Onayla
                                            </button>
                                            <button type="button" class="btn btn-light close-button"
                                                    data-bs-dismiss="modal">Vazgeç
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    $("#istek-red").modal("show");
                                });

                                $(".stock_rejection").click(function () {
                                    var stock_request_rejection_cause = $(".stock_request_rejection_cause").val();
                                    var id = $(".request_id").val();

                                    $.ajax({
                                        url: "ajax/stok/stok-sql.php?islem=istek-red-sql",
                                        type: "POST",
                                        data: {
                                            id: id,
                                            stock_request_rejection_cause: stock_request_rejection_cause
                                        },
                                        success: function (result) {
                                            $(".sonucyaz").html(result);
                                            $("#istek-red").modal("hide");
                                            $.get("ajax/stok/stok-depo.php?islem=istekler", function (result) {
                                                $("#get-warehouse-process").html(result);
                                            });
                                        }
                                    });
                                });
                            </script>
                            <?php }

                            if ($islem == "urun-iade-al-modal") {
                                $id = $_GET["id"];
                                ?>
                                <style>
                                    .modal-body {
                                        max-height: calc(100vh - 150px);
                                        overflow-y: auto;
                                    }
                                </style>

                                <div class="modal fade" id="iade-al-modal" data-bs-backdrop="static" role="dialog">
                                    <div class="modal-dialog" style="width: 90%; max-width: 90%;">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h4 class="modal-title">Gönderilen Ürünü İade Al</h4>
                                                <input type="hidden" id="id" class="form-control">
                                                <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                <input type="hidden" value="<?php echo $id ?>" class="request_id">
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card mt-2">
                                                            <div class="card-header bg-success p-2 text-white"><strong>Gönderilen
                                                                    Ürünlerin Listesi</strong></div>

                                                            <table class="table table-bordered table-hover bg-white w-100"
                                                                   id="iade-alinacak-tablo">
                                                                <thead>
                                                                <tr>
                                                                    <th>İstek No</th>
                                                                    <th>Gönderilen Ürün</th>
                                                                    <th>Doktor Adı</th>
                                                                    <th>Gönderilen Adet</th>
                                                                    <th>Gönderilen Birim</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="iade-id" style="cursor: pointer;">
                                                                <?php
                                                                $_GET["protokol"] = 703;
                                                                $unit_id = $_GET["protokol"];
                                                                $verileri_getir = verilericoklucek("
                                                           SELECT warehouse.warehouse_name as w_name,
                                                                  srm.given_stock_cardid as gsid,
                                                                  srm.doctorid as doktor,
                                                                  srm.given_amount_from_warehouse as gonderilen,
                                                                  stock_card.stock_name as sc_name,
                                                                  srm.id as ids,
                                                                  unit.department_name as department
                                                           FROM stock_request_move as srm
                                                           INNER JOIN stock_card ON stock_card.id=srm.given_stock_cardid
                                                           INNER JOIN warehouses as warehouse ON warehouse.id=srm.given_from_warehouse
                                                           INNER JOIN units as unit ON unit.id=srm.stock_request_unitid
                                                           WHERE srm.status=1 AND srm.request_rejection_status=1 AND unit.id='$unit_id'");

                                                                foreach ($verileri_getir as $veri) {
                                                                    ?>
                                                                    <tr class="iade-alinacak-select"
                                                                        id="<?php echo $veri["ids"]; ?>">
                                                                        <td id="gsid"
                                                                            data-gsid="<?php echo $veri["gsid"]; ?>"><?php echo $veri["gsid"]; ?></td>
                                                                        <td id="sc_name"><?php echo $veri["sc_name"]; ?></td>
                                                                        <td id="doktor"><?php echo kullanicigetirid($veri["doktor"]); ?></td>
                                                                        <td id="gonderilen"
                                                                            data-gonderilen="<?php echo $veri["gonderilen"]; ?>"><?php echo $veri["gonderilen"]; ?></td>
                                                                        <td id="gonderilen"
                                                                            data-gonderilen="<?php echo $veri["department"]; ?>"><?php echo $veri["department"]; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İstek No</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" disabled
                                                                       class="form-control request-id">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İade Edilecek
                                                                Miktar</label>
                                                            <div class="col-sm-7">
                                                                <input type="number"
                                                                       class="form-control number_of_return">
                                                                <input type="hidden" disabled
                                                                       class="form-control gonderlien-miktar">
                                                                <input type="hidden" disabled
                                                                       class="form-control veri-id">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İade Sebebi</label>
                                                            <div class="col-sm-7">
                                                                <textarea cols="2" class="form-control"
                                                                          id="reason_for_return"></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm w-md justify-content-end close-button"
                                                            style="background-color: #E64848; color:white;"
                                                            data-bs-dismiss="modal" type="button">Vazgeç
                                                    </button>
                                                    <button class="btn btn-sm w-md justify-content-end iade-al"
                                                            style="background-color: #112D4E; color:white;"
                                                            data-dismiss="modal" type="button">İade Al
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        $("#iade-al-modal").modal("show");
                                        $("#iade-alinacak-tablo").DataTable({
                                            "responsive": true,
                                            "language": {
                                                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                            },
                                        });
                                    });

                                    $(".iade-alinacak-select").click(function () {
                                        var id = $(this).attr("id");
                                        var gsid = $("#gsid", this).attr("data-gsid");
                                        var gonderilen = $("#gonderilen", this).attr("data-gonderilen");

                                        $(".veri-id").val(id);
                                        $(".request-id").val(gsid);
                                        $(".gonderlien-miktar").val(gonderilen);
                                    });

                                    $(".close-button").click(function () {
                                        alertify.warning("İşlemden Vazgeçtiniz...");
                                    });

                                    $(".iade-al").click(function () {
                                        var id = $(".veri-id").val();
                                        var given_stock_id = $(".request-id").val();
                                        var requested_amount = $(".gonderlien-miktar").val();
                                        var number_of_return = $(".number_of_return").val();
                                        var reason_for_return = $("#reason_for_return").val();
                                        if (number_of_return > requested_amount) {
                                            alertify.warning("İade Almak İstediğiniz Miktar Gönderdiğinizden Fazla!!!");
                                        } else {
                                            $.ajax({
                                                url: "ajax/stok/stok-sql.php?islem=iade-al-sql",
                                                type: "POST",
                                                data: {
                                                    id: id,
                                                    given_stock_id: given_stock_id,
                                                    requested_amount: requested_amount,
                                                    number_of_return: number_of_return,
                                                    reason_for_return: reason_for_return
                                                },
                                                success: function (result) {
                                                    $(".sonucyaz").html(result);
                                                    $("#iade-al-modal").modal("hide");
                                                    $.get("ajax/stok/stok-depo.php?islem=istekler", function (result) {
                                                        $("#get-warehouse-process").html(result);
                                                    });
                                                }
                                            });
                                        }
                                    });
                                </script>
                            <?php }

                            if ($islem == "urun-iade-et-modal") {

                                ?>
                                <style>
                                    .modal-body {
                                        max-height: calc(100vh - 150px);
                                        overflow-y: auto;
                                    }
                                </style>

                                <div class="modal fade" id="iade-et-modal" data-bs-backdrop="static" role="dialog">
                                    <div class="modal-dialog" style="width: 90%; max-width: 90%;">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header text-white">
                                                <h4 class="modal-title">Depodaki Ürünü İade Et</h4>
                                                <input type="hidden" id="id" class="form-control">
                                                <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card mt-2">
                                                            <div class="card-header bg-success p-2 text-white"><strong>Depodaki
                                                                    Ürünlerin Listesi</strong></div>

                                                            <table class="table table-bordered table-hover bg-white w-100"
                                                                   id="iade-edilecek-tablo">
                                                                <thead>
                                                                <tr>
                                                                    <th>Firma Adı</th>
                                                                    <th>Bayi No</th>
                                                                    <th>Barkod</th>
                                                                    <th>Depodaki Miktarı</th>
                                                                    <th>Depo Adı</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="iade-edilecek-id"
                                                                       style="cursor: pointer;">
                                                                <?php
                                                                $verileri_getir = verilericoklucek("
                                                        SELECT
                                                        srm.id as srm_id
                                                        ,srm.barcode,srm.stock_amount as srm_stock_amount
                                                        ,srm.stock_cardid as stock_id     
                                                        ,c.id as company_id
                                                        ,warehouses.warehouse_name as depo_adi
                                                        ,warehouses.id as depo_id
                                                        ,c.company_name as company_name
                                                        ,srm.dealership_number as dealership
                                                        FROM stock_receipt_move as srm
                                                        INNER JOIN warehouses on srm.warehouse_id = warehouses.id
                                                        INNER JOIN companies c on srm.company_id = c.id 
                                                        WHERE srm.status=1");
                                                                foreach ($verileri_getir as $veri) {
                                                                    ?>
                                                                    <tr class="iade-alinacak-select"
                                                                        data-id="<?php echo $veri["srm_id"]; ?>">
                                                                        <td id="company_name"
                                                                            data-company-name="<?php echo $veri["company_id"]; ?>"
                                                                            company-name="<?php echo $veri["company_name"] ?>"><?php echo $veri["company_name"]; ?></td>
                                                                        <td id="dealership"
                                                                            data-company-dealership="<?php echo $veri["dealership"]; ?>"><?php echo $veri["dealership"]; ?></td>
                                                                        <td id="barcode"
                                                                            data-barcode="<?php echo $veri["barcode"]; ?>"
                                                                            data-stock-id="<?php echo $veri["stock_id"]; ?>"><?php echo $veri["barcode"]; ?></td>
                                                                        <td id="stock_amount"
                                                                            stock-amount="<?php echo $veri["srm_stock_amount"] ?>"><?php echo $veri["srm_stock_amount"]; ?></td>
                                                                        <td id="depo_adi"
                                                                            data-depo="<?php echo $veri["depo_id"] ?>"
                                                                            depo-name="<?php echo $veri["depo_adi"]; ?>"><?php echo $veri["depo_adi"]; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Barkod No</label>
                                                            <div class="col-sm-7">
                                                                <input type="number" disabled class="form-control"
                                                                       id="barkodlar">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Firma Adı</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" disabled class="form-control"
                                                                       id="firmalar">
                                                                <input type="hidden" disabled class="form-control"
                                                                       id="company_id">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Bayi No</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" disabled class="form-control"
                                                                       id="bayi_nolari">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Depo Adı</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" disabled class="form-control"
                                                                       id="warehouse_name">
                                                                <input type="hidden" disabled class="form-control"
                                                                       id="warehouse_id">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İade Miktarı</label>
                                                            <div class="col-sm-7">
                                                                <input type="number"
                                                                       class="form-control number_of_returns">
                                                                <input type="hidden" class="form-control iade-et-id">
                                                                <input type="hidden" class="form-control stock-card-id">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İade Sebebi</label>
                                                            <div class="col-sm-7">
                                                                <textarea class="form-control" cols="2"
                                                                          id="iade_sebepleri"></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-sm w-md justify-content-end kalemi-ekle"
                                                                    style="background-color: #3F72AF; color:white;"
                                                                    data-dismiss="modal" type="button">Kalemi ekle
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="card mt-2">
                                                            <div class="card-header bg-success p-2 text-white"><strong>İade
                                                                    Edilecek Ürün Listesi</strong></div>

                                                            <table class="table table-bordered table-hover bg-white w-auto">
                                                                <thead>
                                                                <tr>
                                                                    <th>Barkod</th>
                                                                    <th>Depo Adi</th>
                                                                    <th>Firma Ad</th>
                                                                    <th>Bayi No</th>
                                                                    <th>İade Edilen Miktar</th>
                                                                    <th>İade Sebepleri</th>
                                                                    <th>İşlem</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="iade_edilecek_kaydet_listele"
                                                                       style="cursor: pointer;">

                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm w-md justify-content-end close-button"
                                                            style="background-color: #E64848; color:white;"
                                                            data-bs-dismiss="modal" type="button">Vazgeç
                                                    </button>
                                                    <button class="btn btn-sm w-md justify-content-end iade-et"
                                                            style="background-color: #112D4E; color:white;"
                                                            data-dismiss="modal" type="button">İade Et
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function () {
                                        $("#iade-et-modal").modal("show");
                                        $("#iade-edilecek-tablo").DataTable({
                                            "responsive": true,
                                            "language": {
                                                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                            },
                                        });
                                    });
                                    $(".iade-alinacak-select").click(function () {
                                        var id = $(this).attr("data-id");
                                        var company_id = $("#company_name", this).attr("data-company-name");
                                        var barcode = $("#barcode", this).attr("data-barcode");
                                        var depo_id = $("#depo_adi", this).attr("data-depo");
                                        var dealership = $("#dealership", this).attr("data-company-dealership");
                                        var stock_cardid = $("#barcode", this).attr("data-stock-id");


                                        var warehouse_name = $("#depo_adi", this).attr("depo-name");
                                        var company_name = $("#company_name", this).attr("company-name");

                                        $("#warehouse_id").val(depo_id);
                                        $("#company_id").val(company_id);
                                        $("#bayi_nolari").val(dealership);
                                        $("#warehouse_name").val(warehouse_name);
                                        $(".iade-et-id").val(id);
                                        $(".stock-card-id").val(stock_cardid);
                                        $("#firmalar").val(company_name);
                                        $("#barkodlar").val(barcode);

                                    });

                                    $(".kalemi-ekle").click(function () {
                                        var stock_cardid = $(".stock-card-id").val();
                                        var depo_adi = $("#warehouse_id").val();
                                        var id = $(".iade-et-id").val();
                                        var barkodlar = $("#barkodlar").val();
                                        var firmalar = $("#company_id").val();
                                        var bayi_nolari = $("#bayi_nolari").val();
                                        var number_of_returns = $(".number_of_returns").val();
                                        var iade_sebepleri = $("#iade_sebepleri").val();

                                        var warehouse_name = $("#warehouse_name").val();
                                        var company_name = $("#firmalar").val();

                                        $(".iade_edilecek_kaydet_listele").append(
                                            "<tr id='" + id + "'>" +
                                            "<input type='hidden' name='malzeme[]' td_barkodlar='" + barkodlar + "' td_depolar='" + depo_adi + "' " +
                                            "td_firmalar='" + firmalar + "' td_bayi_nolar='" + bayi_nolari + "' td_number_of_returns='" + number_of_returns + "'" +
                                            "td_iade_sebepleri='" + iade_sebepleri + "'  td_stock_cardid='" + stock_cardid + "'/>" +
                                            "<td class='td_barkodlar'>" + barkodlar + "</td>" +
                                            "<td class='td_depolar'>" + warehouse_name + "</td>" +
                                            "<td class='td_firmalar'>" + company_name + "</td>" +
                                            "<td class='td_bayi_nolar'>" + bayi_nolari + "</td>" +
                                            "<td class='td_number_of_returns'>" + number_of_returns + "</td>" +
                                            "<td class='td_iade_sebepleri'>" + iade_sebepleri + "</td>" +
                                            "<td><button class='btn btn-sm delete-malzeme' style='background-color: #E64848; color:white;'>Sil</button></td>" +
                                            "</tr>");
                                    });

                                    $(document).on('click', '.delete-malzeme', function () {
                                        $(this).closest("tr").remove();
                                    });

                                    $(".iade-et").click(function () {
                                        var stock_cardid = [];
                                        var BARCODE = [];
                                        var WAREHOUSE = [];
                                        var COMPANIES = [];
                                        var BAYI_NO = [];
                                        var NUMBER_OF_RETURNS = [];
                                        var IADE_SEBEBI = [];

                                        $("input[name='malzeme[]']").off().each(function () {
                                            stock_cardid.push($(this).attr("td_stock_cardid"));
                                            BARCODE.push($(this).attr("td_barkodlar"));
                                            WAREHOUSE.push($(this).attr("td_depolar"));
                                            COMPANIES.push($(this).attr("td_firmalar"));
                                            BAYI_NO.push($(this).attr("td_bayi_nolar"));
                                            NUMBER_OF_RETURNS.push($(this).attr("td_number_of_returns"));
                                            IADE_SEBEBI.push($(this).attr("td_iade_sebepleri"));
                                        });


                                        $.ajax({
                                            url: "ajax/stok/stok-sql.php?islem=iade-et-sql",
                                            type: "POST",
                                            data: {
                                                stock_cardid: stock_cardid,
                                                stock_barcode: BARCODE,
                                                warehouse_id: WAREHOUSE,
                                                company_id: COMPANIES,
                                                dealership_number: BAYI_NO,
                                                number_of_returns: NUMBER_OF_RETURNS,
                                                reason_of_returns: IADE_SEBEBI
                                            },
                                            success: function (result) {
                                                $(".sonucyaz").html(result);
                                            }
                                        });
                                    });

                                </script>
                            <?php }

                            if ($islem == "get-doctors-modal"){

                            ?>

                            <style>
                                .modal-body {
                                    max-height: calc(100vh - 150px);
                                    overflow-y: auto;
                                }
                            </style>

                            <div class="modal fade" id="get-doctors-modal" data-bs-backdrop="static" role="dialog">
                                <div class="modal-dialog" style="width: 90%; max-width: 90%;">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header text-white">
                                            <h4 class="modal-title">Doktorlar</h4>
                                            <input type="hidden" id="id" class="form-control">
                                            <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <table class="table table-bordered table-hover bg-white w-100"
                                                           id="doctors-table">
                                                        <thead>
                                                        <tr>
                                                            <th>Doktor Adı</th>
                                                            <th>Birimi</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="cursor: pointer;">
                                                        <?php
                                                        $doctorlari_getir = verilericoklucek("SELECT name_surname,department FROM users WHERE status=1");
                                                        foreach ($doctorlari_getir as $doktor) {
                                                            ?>
                                                            <tr id="secilecek-doktor">
                                                                <td id="doctor-name"
                                                                    data-id="<?php echo $doktor["name_surname"]; ?>"><?php echo $doktor["name_surname"]; ?></td>
                                                                <td><?php echo birimgetirid($doktor["department"]); ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function () {
                                        $("#get-doctors-modal").modal("show");
                                    });
                                    $("#doctors-table").DataTable({
                                        "responsive": true,
                                        "language": {
                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                        }
                                    });

                                    $(document).on('dblclick', '#secilecek-doktor', function () {
                                        var doktor_adi = $("#doctor-name", this).attr("data-id");
                                        $(".gelecek_doktor_adi").val(doktor_adi);
                                        $("#get-doctors-modal").modal("hide");
                                    });

                                </script>

                                <?php }

                                if ($islem == "muadil-ilac-getir") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="muadil-modal" data-bs-backdrop="static" role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Muadil İlaç Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Muadil Edilecek Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="muadils-table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>
                                                                        <th>Adet</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id,stock_number_of_boxes FROM stock_card WHERE status=1 AND stock_type=28464");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                            <td><?php echo $muadil["stock_number_of_boxes"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#muadil-modal").modal("show");
                                            $("#muadils-table").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var id = $(this).attr("id");
                                            var stock_name = $("#stok-adi", this).attr("data-id");
                                            $(".eklenecek_muadil").val(stock_name);
                                            $(".eklenecek_muadil").attr("data-id", id);
                                            $("#muadil-modal").modal("hide");
                                        });
                                    </script>
                                <?php }

                                if ($islem == "change-muadil") {
                                    $id = $_GET["id"];

                                    $tek = tek("SELECT stock_name,id,stock_muadilid FROM stock_card WHERE status=1 AND id='$id'");
                                    $muadil_id = $tek["stock_muadilid"];
                                    $singular = singular("stock_card", "id", $muadil_id);
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="muadil-change-modal" data-bs-backdrop="static"
                                         role="dialog">
                                        <div class="modal-dialog" style="width: 40%; max-width: 40%; ">
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Muadil Değiştir</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Muadil İlaç</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" disabled
                                                                       class="form-control form-control-sm degisecek_muadil"
                                                                       value="<?php echo $singular["stock_name"]; ?>">
                                                            </div>
                                                            <div class="col-1">
                                                                <button class="btn btn-sm-warning get-muadils"><i
                                                                            class="fa fa-ellipsis-h"
                                                                            aria-hidden="true"></i></button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">İlaç Adı</label>
                                                            <div class="col-sm-7">
                                                                <input type="text" id="ilac-cek"
                                                                       data-id="<?php echo $tek["id"]; ?>"
                                                                       class="form-control form-control-sm"
                                                                       value="<?php echo $tek["stock_name"] ?>"
                                                                       disabled>
                                                            </div>
                                                        </div>
                                                        <br>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm w-md justify-content-end close-button"
                                                            style="background-color: #E64848; color:white;"
                                                            data-bs-dismiss="modal" type="button">Vazgeç
                                                    </button>
                                                    <button class="btn btn-sm w-md justify-content-end muadil-degistir"
                                                            style="background-color: #112D4E; color:white;"
                                                            data-dismiss="modal" type="button">Onayla
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="get-muadil-class"></div>
                                    <script>
                                        $(document).ready(function () {
                                            $("#muadil-change-modal").modal("show");
                                        });

                                        $(".close-button").click(function () {
                                            alertify.warning("İşlemden Vazgeçtiniz...");
                                        });

                                        $(".get-muadils").click(function () {
                                            $.get("ajax/stok/stok-modal.php?islem=muadil-ilac-getir-change", function (getModal) {
                                                $(".get-muadil-class").html(getModal);
                                            });
                                        });

                                        $(".muadil-degistir").click(function () {
                                            var id = $(".degisecek_muadil").attr("data-id");
                                            var stock_id = $("#ilac-cek").attr("data-id");

                                            $.ajax({
                                                url: "ajax/stok/stok-sql.php?islem=muadil-ekle-sql",
                                                type: "POST",
                                                data: {
                                                    id: id,
                                                    stock_id: stock_id
                                                },
                                                success: function (result) {
                                                    $(".sonucyaz").html(result);
                                                    $("#muadil-change-modal").modal("hide");
                                                    $.get("ajax/stok/stok-tablo.php?islem=muadil-eklenecek-tablo-getir", function (e) {
                                                        $("#get-tables").html(e);
                                                    });
                                                    $.get("ajax/stok/stok-sql.php?islem=get-islem-muadil", {stock_id: stock_id}, function (result) {
                                                        $("#get-last-muadil").html(result);
                                                    });
                                                }
                                            });
                                        });
                                    </script>
                                <?php }

                                if ($islem == "muadil-ilac-getir-change") {

                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="muadil-modal" data-bs-backdrop="static" role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Muadil İlaç Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Muadil Edilecek Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="muadils-table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>
                                                                        <th>Adet</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id,stock_number_of_boxes FROM stock_card WHERE status=1 AND stock_type=28464");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                            <td><?php echo $muadil["stock_number_of_boxes"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#muadil-modal").modal("show");
                                            $("#muadils-table").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var id = $(this).attr("id");
                                            var stock_name = $("#stok-adi", this).attr("data-id");
                                            $(".degisecek_muadil").val(stock_name);
                                            $(".degisecek_muadil").attr("data-id", id);
                                            $("#muadil-modal").modal("hide");
                                        });
                                    </script>
                                <?php }

                                if ($islem == "ilac-adi-getir") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="ilac-modal" data-bs-backdrop="static" role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Malzeme Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Malzeme Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="ilac-adlari">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id FROM stock_card WHERE status=1");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi-getir"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#ilac-modal").modal("show");
                                            $("#ilac-adlari").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var stock_name = $("#stok-adi-getir", this).attr("data-id");
                                            $(".malzeme-adi").val(stock_name);
                                            $("#ilac-modal").modal("hide");
                                        });
                                    </script>
                                <?php }

                                if ($islem == "get-drugs") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="malzeme-ilac-modal" data-bs-backdrop="static"
                                         role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Malzeme Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Malzeme Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="ilac-adlari">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id FROM stock_card WHERE status=1");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi-getir"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#malzeme-ilac-modal").modal("show");
                                            $("#ilac-adlari").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var stock_name = $("#stok-adi-getir", this).attr("data-id");
                                            $(".ilac-yazilacak-input").val(stock_name);
                                            $("#malzeme-ilac-modal").modal("hide");
                                        });
                                    </script>

                                <?php }

                                if ($islem == "ilac-listesi-getir-modal") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="ilac-getir-modal" data-bs-backdrop="static"
                                         role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Malzeme Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Malzeme Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="ilac-adlari-getir">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id FROM stock_card WHERE status=1");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi-getir"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#ilac-getir-modal").modal("show");
                                            $("#ilac-adlari-getir").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var stock_name = $("#stok-adi-getir", this).attr("data-id");
                                            $(".drug-name").val(stock_name);
                                            $("#ilac-getir-modal").modal("hide");
                                        });
                                    </script>
                                <?php }

                                if ($islem == "ilac-getir") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="ilac-getir-modal" data-bs-backdrop="static"
                                         role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Malzeme Listesi</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Malzeme Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="ilac-adlari-getir">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Barkod</th>
                                                                        <th>Ürün Adı</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT barcode,stock_name,id FROM stock_card WHERE status=1 AND stock_type=28464");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td><?php echo $muadil["barcode"]; ?></td>
                                                                            <td id="stok-adi-getir"
                                                                                data-id="<?php echo $muadil["stock_name"] ?>"><?php echo $muadil["stock_name"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#ilac-getir-modal").modal("show");
                                            $("#ilac-adlari-getir").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var id = $(this).attr("id");
                                            var stock_name = $("#stok-adi-getir", this).attr("data-id");
                                            $(".muadil-tanimla-input").val(stock_name);
                                            $(".muadil-tanimla-input").attr("id", id);
                                            $("#ilac-getir-modal").modal("hide");
                                        });
                                    </script>
                                <?php }
                                if ($islem == "firma-liste") {
                                    ?>
                                    <style>
                                        .modal-body {
                                            max-height: calc(100vh - 150px);
                                            overflow-y: auto;
                                        }
                                    </style>

                                    <div class="modal fade" id="firma-getir-modal" data-bs-backdrop="static"
                                         role="dialog">
                                        <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header text-white">
                                                    <h4 class="modal-title">Firmalar</h4>
                                                    <input type="hidden" id="id" class="form-control">
                                                    <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-12">
                                                        <button class="btn btn-warning btn-sm add-new-companies-from-modal">
                                                            Yeni Firma Oluştur
                                                        </button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card mt-2">
                                                                <div class="card-header bg-success p-2 text-white">
                                                                    <strong>Firma Listesi</strong></div>

                                                                <table class="table table-bordered table-hover bg-white w-100"
                                                                       id="firma-adlari-getir">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Firma Adı</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="get-equivalent"
                                                                           style="cursor: pointer;">
                                                                    <?php
                                                                    $muadilleri_getir = verilericoklucek(" SELECT company_name,id FROM companies WHERE status=1");
                                                                    foreach ($muadilleri_getir as $muadil) {
                                                                        ?>
                                                                        <tr id="<?php echo $muadil["id"]; ?>"
                                                                            class="select-equivalent">
                                                                            <td data-id="<?php echo $muadil["company_name"] ?>"
                                                                                id="stok-adi-getir"><?php echo $muadil["company_name"]; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).ready(function () {
                                            $("#firma-getir-modal").modal("show");
                                            $("#firma-adlari-getir").DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                }
                                            });
                                        });

                                        $(".select-equivalent").click(function () {
                                            var id = $(this).attr("id");
                                            var company_name = $("#stok-adi-getir", this).attr("data-id");
                                            $(".producting_brand").val(company_name);
                                            $(".producting_brand").attr("data-id", id);
                                            $("#firma-getir-modal").modal("hide");
                                        });
                                    </script>
                                <?php }


                                if ($islem == "muadil-sil-modal"){
                                $muadil_id = $_GET["stock_muadil_id"];
                                $stock_id = $_GET["stock_id"];
                                $id = $_GET["id"];
                                ?>
                                <div class="sonucu-getir"></div>
                                <div class="modal fade" id="delete-muadil-modal" data-bs-backdrop="static"
                                     data-bs-keyboard="false" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h5>Silme İşlemini Onayla</h5>
                                                <hr>
                                                <div>
                                                    <div>
                                                        <label></label>
                                                        <label> Silme Nedeniniz</label>
                                                        <input type="text" class="form-control delete_detail">
                                                    </div>
                                                    <div>
                                                        <input type="hidden" disabled class="form-control stock_id"
                                                               value="<?php echo $stock_id ?>">
                                                        <input type="hidden" disabled
                                                               class="form-control guncellenen-id"
                                                               value="<?php echo $id ?>">
                                                        <input type="hidden" disabled
                                                               class="form-control stock_muadil_id"
                                                               value="<?php echo $muadil_id ?>">
                                                    </div>
                                                    <br>
                                                    <div class="offset-md-4">
                                                        <button type="button" class="btn btn-light delete-muadil-sql"
                                                                style="color: blue;">Onayla
                                                        </button>
                                                        <button type="button" class="btn btn-light close-button"
                                                                data-bs-dismiss="modal">Vazgeç
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(".delete-muadil-sql").click(function () {

                                                var delete_detail = $(".delete_detail").val();
                                                var stock_muadil_id = $(".stock_muadil_id").val();
                                                var stock_id = $(".stock_id").val();
                                                var id = $(".guncellenen-id").val();

                                                $.ajax({
                                                    url: "ajax/stok/stok-sql.php?islem=sil-sql-muadil",
                                                    type: "POST",
                                                    data: {
                                                        delete_detail: delete_detail,
                                                        stock_muadil_id: stock_muadil_id,
                                                        stock_id: stock_id
                                                    },
                                                    success: function (getVeri) {
                                                        $(".sonucu-getir").html(getVeri);
                                                        $("#delete-muadil-modal").modal("hide");
                                                        $(".update-stock-cards").modal("hide");
                                                        $.ajax({
                                                            url: "ajax/stok/stok-modal.php?islem=stok-kart-guncelle",
                                                            type: "POST",
                                                            data: {
                                                                id: id
                                                            },
                                                            success: function (getModal) {
                                                                $("#get-modals").html(getModal);
                                                            }
                                                        });
                                                    }
                                                });
                                            });

                                            $(document).ready(function () {
                                                $("#delete-muadil-modal").modal("show");
                                            });
                                        </script>
                                        <?php }


                                        if ($islem == "guncellenecek-firma-modal-getir") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="guncellenecek-firma-getir-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Firmalar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-12">
                                                                <button class="btn btn-warning btn-sm add-new-companies-from-modal">
                                                                    Yeni Firma Oluştur
                                                                </button>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Firma Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="firma-adlari-getir">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Firma Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $muadilleri_getir = verilericoklucek(" SELECT company_name,id FROM companies WHERE status=1");
                                                                            foreach ($muadilleri_getir as $muadil) {
                                                                                ?>
                                                                                <tr id="<?php echo $muadil["id"]; ?>"
                                                                                    class="select-equivalent">
                                                                                    <td data-id="<?php echo $muadil["company_name"] ?>"
                                                                                        id="stok-adi-getir"><?php echo $muadil["company_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#guncellenecek-firma-getir-modal").modal("show");
                                                    $("#firma-adlari-getir").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".select-equivalent").click(function () {
                                                    var company_name = $("#stok-adi-getir", this).attr("data-id");
                                                    $(".producting-brand-update").val(company_name);
                                                    $("#guncellenecek-firma-getir-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "barkodlari-getir") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="kalem-barkod"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Ürünler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Ürün Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="kalem-barkod-getir">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ürün Adi</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT stock_type,barcode,id,sale_unit_price,discount_percent,stock_name FROM stock_card WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr id="<?php echo $barkod["id"]; ?>"
                                                                                    class="select-equivalent">
                                                                                    <td stock-type="<?php echo $barkod["stock_type"] ?>"
                                                                                        data-id="<?php echo $barkod["barcode"] ?>"
                                                                                        id="stok-adi-getir"
                                                                                        data-name="<?php echo $barkod["stock_name"] ?>"><?php echo $barkod["stock_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#kalem-barkod").modal("show");
                                                    $("#kalem-barkod-getir").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".select-equivalent").click(function () {
                                                    var id = $(this).attr("id");
                                                    var barcode = $("#stok-adi-getir", this).attr("data-id");
                                                    var stock_name = $("#stok-adi-getir", this).attr("data-name");
                                                    var stock_type = $("#stok-adi-getir", this).attr("stock-type");


                                                    $(".barcod-no").val(barcode);
                                                    $(".barcod-no").attr("data-id", id);
                                                    $(".get-stok").val(stock_name)
                                                    $("#kalem-barkod").modal("hide");
                                                    $(".barcod-no").attr("stock-type", stock_type);


                                                    $(".fiyati").prop("disabled", false);
                                                    $(".adedi").prop("disabled", false);
                                                    $(".iskonto-oran-girisi").prop("disabled", false);
                                                    $(".kdv-miktari").prop("disabled", false);
                                                    $(".lot-no").prop("disabled", false);
                                                    $(".ats-no").prop("disabled", false);
                                                    $(".birimi").prop("disabled", false);
                                                    $(".son-kullanma").prop("disabled", false);
                                                    $(".faturaya-ekle").prop("disabled", false);
                                                });

                                            </script>


                                        <?php }
                                        if ($islem == "firma-modal-getir") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="guncellenecek-firma-getir-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Firmalar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Firma Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="firma-adlari-getir">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Firma Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT identifier_number,company_name,id FROM companies WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr id="<?php echo $barkod["id"]; ?>"
                                                                                    data-id="<?php echo $barkod["identifier_number"] ?>"
                                                                                    class="firmanin-idsi">
                                                                                    <td data-name="<?php echo $barkod["company_name"] ?>"><?php echo $barkod["company_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#guncellenecek-firma-getir-modal").modal("show");
                                                    $("#firma-adlari-getir").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".firmanin-idsi").click(function () {
                                                    var id = $(this).attr("id");
                                                    var identifier_number = $(this).attr("data-id");
                                                    var firma_adi = $("td", this).attr("data-name");
                                                    $(".fatura-firma-getir").val(firma_adi);
                                                    $(".fatura-firma-getir").attr("data-id", id);
                                                    $(".identifier_no").val(identifier_number);
                                                    $("#guncellenecek-firma-getir-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "bayino-getir") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>
                                            <div class="modal fade" id="fatura-bayi-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Bayiler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Bayi Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="bayi-adlari-getir">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Bayi Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $id = $_GET["id"];
                                                                            $bayi_sorgu = tek(" SELECT id,dealership_number as bayi FROM companies WHERE status=1 AND id='$id'");

                                                                            $bayiler = explode(",", $bayi_sorgu["bayi"]);
                                                                            foreach ($bayiler as $dealership) {
                                                                                ?>
                                                                                <tr class="firmanin-bayisi">
                                                                                    <td data-name="<?php echo $dealership ?>"><?php echo $dealership; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#fatura-bayi-modal").modal("show");
                                                    $("#bayi-adlari-getir").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".firmanin-bayisi").click(function () {
                                                    var bayi_no = $("td", this).attr("data-name");
                                                    $(".bayi-no-fatura").val(bayi_no);
                                                    $("#fatura-bayi-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "filtre-firma-modal") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="filtre-firma-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Firmalar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Firma Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="firmanin-adi">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Firma Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-company-receipt"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT company_name,id FROM companies WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr data-id="<?php echo $barkod["id"]; ?>"
                                                                                    class="firma-id-fatura-detay">
                                                                                    <td data-name="<?php echo $barkod["company_name"] ?>"><?php echo $barkod["company_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#filtre-firma-modal").modal("show");
                                                    $("#firmanin-adi").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".firma-id-fatura-detay").click(function () {
                                                    var id = $(this).attr("data-id");
                                                    var firma_adi = $("td", this).attr("data-name");
                                                    $(".company_name_receipt").val(firma_adi);
                                                    $(".company_name_receipt").attr("data-id", id);
                                                    $("#filtre-firma-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "fatura-depo-modal") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="depolar-modal-filtreleme-icin"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Depolar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="deponun-adi-filtre-modal">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT warehouse_name,id FROM warehouses WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr data-id="<?php echo $barkod["id"]; ?>"
                                                                                    class="depo-id-fatura-detay">
                                                                                    <td data-name="<?php echo $barkod["warehouse_name"] ?>"><?php echo $barkod["warehouse_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#depolar-modal-filtreleme-icin").modal("show");
                                                    $("#deponun-adi-filtre-modal").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".depo-id-fatura-detay").click(function () {
                                                    var id = $(this).attr("data-id");
                                                    var firma_adi = $("td", this).attr("data-name");
                                                    $(".depo-adini-getir").val(firma_adi);
                                                    $(".depo-adini-getir").attr("data-id", id);
                                                    $("#depolar-modal-filtreleme-icin").modal("hide");
                                                });
                                            </script>
                                        <?php }


                                        if ($islem == "get-warehouse-from-modal") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="guncelle-depo-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Depolar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>
                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="deponun-adi-guncelle-icin">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT warehouse_name,id FROM warehouses WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr data-id="<?php echo $barkod["id"]; ?>"
                                                                                    class="guncelle-icin-depo-getir">
                                                                                    <td data-name="<?php echo $barkod["warehouse_name"] ?>"><?php echo $barkod["warehouse_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $(document).ready(function () {
                                                    $("#guncelle-depo-modal").modal("show");
                                                    $("#deponun-adi-guncelle-icin").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });
                                                $(".guncelle-icin-depo-getir").click(function () {
                                                    var id = $(this).attr("data-id");
                                                    var firma_adi = $("td", this).attr("data-name");
                                                    $(".get-warehouse-for-update").val(firma_adi);
                                                    $(".get-warehouse-for-update").attr("data-id", id);
                                                    $("#guncelle-depo-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "fatura-kalem-duzenle-modal") {
                                            $id = $_GET["id"];
                                            $tek_veri = tek("SELECT * FROM stock_invoice_pen WHERE status=1 AND id='$id'")
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>
                                            <input type="hidden" id="pen_id" value="<?php echo $tek_veri["id"]; ?>">
                                            <div class="modal fade" id="guncelle-fatura-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 65%; max-width: 65%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Faturadaki Ürünü Güncelleme
                                                                Sayfası</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-6 mt-1">
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Barkod
                                                                            No</label>
                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm barcode-for-receipt"
                                                                                       aria-describedby="basic-addon2"
                                                                                       value="<?php echo $tek_veri["barcode"] ?>"
                                                                                       disabled>
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-outline-warning btn-sm get-barcode-for-receipt"
                                                                                            type="button"><i
                                                                                                class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="get-barcode-for-receipt-update"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Ürün
                                                                            Adı</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text"
                                                                                   class="form-control form-control-sm stock-name"
                                                                                   id="stock-name"
                                                                                   disabled
                                                                                   value="<?php echo $tek_veri["stock_name"] ?>"
                                                                                   maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Birim
                                                                            Fiyat</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   class="form-control form-control-sm"
                                                                                   value="<?php echo $tek_veri["sale_price"] ?>"
                                                                                   id="sale-price"
                                                                                   maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Adet</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   class="form-control form-control-sm"
                                                                                   value="<?php echo $tek_veri["stock_amount"] ?>"
                                                                                   id="stock-amount"
                                                                                   maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Birim</label>
                                                                        <div class="col-sm-7">
                                                                            <select id="unit"
                                                                                    class="form-select form-select-sm">
                                                                                <option value="<?php echo $tek_veri["barcode"] ?>"><?php echo $tek_veri["unit"] ?>
                                                                                </option>
                                                                                <option value="Seçiniz...">Seçiniz...
                                                                                </option>
                                                                                <option value="Adet">Adet</option>
                                                                                <option value="Litre">Litre</option>
                                                                                <option value="Bidon">Bidon</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">İskonto
                                                                            Oran</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   id="discount-percent"
                                                                                   value="<?php echo $tek_veri["discount_percent"] ?>"
                                                                                   class="form-control form-control-sm"
                                                                                   maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">İskonto
                                                                            Tutar</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   id="discount-applied"
                                                                                   value="<?php echo $tek_veri["discount_price_applied"] ?>"
                                                                                   data-id="<?php echo $tek_veri["discount_total"] ?>"
                                                                                   class="form-control form-control-sm"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mt-1">
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Kdv
                                                                            Oran</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   id="kdv-percent"
                                                                                   value="<?php echo $tek_veri["kdv_percent"] ?>"
                                                                                   class="form-control form-control-sm"
                                                                                   maxlength="20">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">KDV
                                                                            Tutar</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="number"
                                                                                   value="<?php echo $tek_veri["kdv_price_applied"] ?>"
                                                                                   id="kdv-applied"
                                                                                   data-id="<?php echo $tek_veri["kdv_total"] ?>"
                                                                                   class="form-control form-control-sm"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">LOT
                                                                            No</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text"
                                                                                   id="lot-no"
                                                                                   value="<?php echo $tek_veri["lot_number"] ?>"
                                                                                   class="form-control form-control-sm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">ATS
                                                                            No</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text"
                                                                                   value="<?php echo $tek_veri["ats_number"] ?>"
                                                                                   id="ats-no"
                                                                                   class="form-control form-control-sm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Son
                                                                            Kullanma</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="date"
                                                                                   id="expiration-date"
                                                                                   value="<?php echo $tek_veri["expiration_date"] ?>"
                                                                                   class="form-control form-control-sm">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Bayi
                                                                            No</label>
                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm get-dealer"
                                                                                       id="dealer-for-receipt"
                                                                                       aria-describedby="basic-addon2"
                                                                                       value="<?php echo $tek_veri["dealership_number"] ?>"
                                                                                       disabled>
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-outline-warning btn-sm get-dealer-button"
                                                                                            type="button"><i
                                                                                                class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="get-dealer-for-receipt-update"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mt-1">
                                                                        <label class="col-sm-3 col-form-label-sm">Malzeme
                                                                            Türü</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" disabled
                                                                                   class="form-control form-control-sm "
                                                                                   id="stock-type"
                                                                                   value="<?php echo islemtanimgetirid($tek_veri["stock_type"]) ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-outline-danger btn-sm"
                                                                        id="guncelle-fatura-vazgec">
                                                                    Vazgeç
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success btn-sm"
                                                                        id="fatura-kalem-guncelle-button">Kaydet
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $("#guncelle-fatura-vazgec").click(function () {
                                                    alertify.warning("İşlemden Vazgeçtiniz");
                                                    $("#guncelle-fatura-modal").modal("hide");
                                                });

                                                $("#kdv-percent").keydown(function () {
                                                    setTimeout(function () {
                                                        var kdv_tutar = $("#kdv-percent").val();
                                                        var yuzde = kdv_tutar / 100
                                                        var iskonto_orani = Number($("#discount-applied").attr("data-id"));
                                                        var kdv_toplami = iskonto_orani * yuzde;
                                                        var kdv_toplami_fiyat = iskonto_orani + (iskonto_orani * yuzde);
                                                        $("#kdv-applied").val(kdv_toplami);
                                                        $("#kdv-applied").attr("data-id", kdv_toplami_fiyat);
                                                    }, 2);
                                                });
                                                $("#stock-amount").keydown(function () {
                                                    setTimeout(function () {
                                                        var adet = $("#stock-amount").val();
                                                        var fiyat = $("#sale-price").val();
                                                        var net_tutar = fiyat * adet;
                                                        var iskonto_oran = $("#discount-percent").val();
                                                        var yuzde = iskonto_oran / 100;
                                                        var iskonto_tutar = net_tutar * yuzde;
                                                        var iskontolu = net_tutar - (net_tutar * yuzde);
                                                        $("#discount-applied").attr("data-id", iskontolu);
                                                        $("#discount-applied").val(iskonto_tutar);
                                                        $("#kdv-applied").val(iskonto_tutar);
                                                        $("#kdv-applied").attr("data-id", iskontolu);
                                                    }, 1);
                                                });

                                                $("#discount-percent").keydown(function (e) {

                                                    setTimeout(function () {
                                                        var adet = $("#stock-amount").val();
                                                        var fiyat = $("#sale-price").val();
                                                        var net_tutar = fiyat * adet;
                                                        var iskonto_oran = $("#discount-percent").val();
                                                        var yuzde = iskonto_oran / 100;
                                                        var iskonto_tutar = net_tutar * yuzde;
                                                        var iskontolu = net_tutar - (net_tutar * yuzde);
                                                        $("#discount-applied").attr("data-id", iskontolu);
                                                        $("#discount-applied").val(iskonto_tutar);
                                                    }, 1);
                                                });
                                                $("#fatura-kalem-guncelle-button").click(function () {
                                                    var barcode = $(".barcode-for-receipt").val();
                                                    var stock_name = $("#stock-name").val();
                                                    var sale_price = $("#sale-price").val();
                                                    var stock_amount = $("#stock-amount").val();
                                                    var unit = $("#unit").val();
                                                    var discount_percent = $("#discount-percent").val();
                                                    var discount_percent_applied = $("#discount-applied").val();
                                                    var discount_percent_total = $("#discount-applied").attr("data-id");
                                                    var kdv_percent = $("#kdv-percent").val();
                                                    var kdv_percent_applied = $("#kdv-applied").val();
                                                    var kdv_percent_total = $("#kdv-applied").attr("data-id");
                                                    var lot_no = $("#lot-no").val();
                                                    var ats_number = $("#ats-no").val();
                                                    var expiration_date = $("#expiration-date").val();
                                                    var dealership_number = $("#dealer-for-receipt").val();
                                                    var id = $("#pen_id").val();
                                                    $.ajax({
                                                        url: "ajax/stok/stok-sql.php?islem=update-receipt-from-detail",
                                                        type: "POST",
                                                        data: {
                                                            id: id,
                                                            barcode: barcode,
                                                            stock_name: stock_name,
                                                            sale_price: sale_price,
                                                            stock_amount: stock_amount,
                                                            unit: unit,
                                                            discount_percent: discount_percent,
                                                            discount_price_applied: discount_percent_applied,
                                                            discount_total: discount_percent_total,
                                                            kdv_percent: kdv_percent,
                                                            kdv_price_applied: kdv_percent_applied,
                                                            kdv_total: kdv_percent_total,
                                                            lot_number: lot_no,
                                                            ats_number: ats_number,
                                                            expiration_date: expiration_date,
                                                            dealership_number: dealership_number,
                                                        },
                                                        success: function (result) {
                                                            if (result == 2) {
                                                                alertify.error("Bilinmeyen Bir Hata İle Karşılaşıldı")
                                                            } else if (result == 3) {
                                                                alertify.warning("Bilinmeyen Bir Hata Oluştu");
                                                            } else {
                                                                var json = JSON.parse(result);
                                                                var id = json.id;
                                                                alertify.success("İşlem Başarılı");
                                                                $.get("ajax/stok/stok-tablo.php?islem=receipt-edit-table", {id: id}, function (getAgain) {
                                                                    $("#get-tables").html(getAgain);
                                                                });
                                                                $("#guncelle-fatura-modal").modal("hide");
                                                            }
                                                        }
                                                    });
                                                });

                                                $(".get-barcode-for-receipt").click(function () {
                                                    $.get("ajax/stok/stok-modal.php?islem=get-barcode-for-receipt", function (getBarcode) {
                                                        $(".get-barcode-for-receipt-update").html(getBarcode);
                                                    });
                                                })
                                                $(".get-dealer-button").click(function () {
                                                    var id = $(".get-firm-for-receipt").attr("data-id");
                                                    $.get("ajax/stok/stok-modal.php?islem=get-dealer-for-receipt", {id: id}, function (getDealer) {
                                                        $(".get-dealer-for-receipt-update").html(getDealer);
                                                    });
                                                });
                                                $(document).ready(function () {
                                                    $("#guncelle-fatura-modal").modal("show");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "get-barcode-for-receipt") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>
                                            <div class="modal fade" id="get-stocks-for-receipt"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Ürünler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Ürün Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="get-barcodes-for-receipt">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ürün Adi</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $barkodlar = verilericoklucek(" SELECT stock_type,barcode,id,sale_unit_price,discount_percent,stock_name FROM stock_card WHERE status=1");
                                                                            foreach ($barkodlar as $barkod) {
                                                                                ?>
                                                                                <tr id="<?php echo $barkod["id"]; ?>"
                                                                                    class="select-barcode-for-receipt">
                                                                                    <td stock-type="<?php echo $barkod["stock_type"] ?>"
                                                                                        data-id="<?php echo $barkod["barcode"] ?>"
                                                                                        id="stok-adi-getir"
                                                                                        data-name="<?php echo $barkod["stock_name"] ?>"><?php echo $barkod["stock_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#get-stocks-for-receipt").modal("show");
                                                    $("#get-barcodes-for-receipt").DataTable({
                                                        "responsive": true,
                                                        scrollX: true,
                                                        scrollY: "35vh",
                                                        paging: false,
                                                        length: 5,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".select-barcode-for-receipt").click(function () {
                                                    var barcode = $("#stok-adi-getir", this).attr("data-id");
                                                    var stock_name = $("#stok-adi-getir", this).attr("data-name");

                                                    $(".barcode-for-receipt").val(barcode);
                                                    $(".stock-name").val(stock_name)
                                                    $("#get-stocks-for-receipt").modal("hide");
                                                });

                                            </script>
                                        <?php }

                                        if ($islem == "get-dealer-for-receipt") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="receipt-dealer"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Bayiler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Bayi Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="get-dealer-for-receipt">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Bayi Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $id = $_GET["id"];
                                                                            $bayi_sorgu = tek(" SELECT id,dealership_number as bayi FROM companies WHERE status=1 AND id='$id'");

                                                                            $bayiler = explode(",", $bayi_sorgu["bayi"]);
                                                                            foreach ($bayiler as $dealership) {
                                                                                ?>
                                                                                <tr class="firmanin-bayisi">
                                                                                    <td data-name="<?php echo $dealership ?>"><?php echo $dealership; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#receipt-dealer").modal("show");
                                                    $("#get-dealer-for-receipt").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".firmanin-bayisi").click(function () {
                                                    var bayi_no = $("td", this).attr("data-name");
                                                    $(".get-dealer").val(bayi_no);
                                                    $("#receipt-dealer").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "fatura-filtre-urun-getir") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="receipt-stock-name"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Ürünler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Ürün Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="get-stock-for-receipt">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ürün Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $urunler = verilericoklucek(" SELECT stock_name as urun_adi FROM stock_card WHERE status=1");
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="urun-fatura-icin">
                                                                                    <td data-name="<?php echo $urun_adi["urun_adi"] ?>"><?php echo $urun_adi["urun_adi"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#receipt-stock-name").modal("show");
                                                    $("#get-stock-for-receipt").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".urun-fatura-icin").click(function () {
                                                    var bayi_no = $("td", this).attr("data-name");
                                                    $(".fatura-filtre-urun-ad").val(bayi_no);
                                                    $("#receipt-stock-name").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "urun_adi_modal") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="urun_adi_depolar_arasi_urun"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Ürünler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Ürün Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="urun_adi_depolar_arasi_urun_tablo">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ürün Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $urunler = verilericoklucek(" SELECT stock_name as urun_adi FROM stock_card WHERE status=1");
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="urun-fatura-icin">
                                                                                    <td data-name="<?php echo $urun_adi["urun_adi"] ?>"><?php echo $urun_adi["urun_adi"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#urun_adi_depolar_arasi_urun").modal("show");
                                                    $("#urun_adi_depolar_arasi_urun_tablo").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".urun-fatura-icin").click(function () {
                                                    var urun_adi = $("td", this).attr("data-name");
                                                    $(".urun_adi_filter_depo").val(urun_adi);
                                                    $("#urun_adi_depolar_arasi_urun").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "depo_adi_modal") {

                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="depo_adi_depolar_arasi_depo"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Bayiler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="depo_adi_depolar_arasi_depo_tablo">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $urunler = verilericoklucek(" SELECT warehouse_name as depo_adi FROM warehouses WHERE status=1");
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="urun-fatura-icin">
                                                                                    <td data-name="<?php echo $urun_adi["depo_adi"] ?>"><?php echo $urun_adi["depo_adi"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#depo_adi_depolar_arasi_depo").modal("show");
                                                    $("#depo_adi_depolar_arasi_depo_tablo").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".urun-fatura-icin").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    $(".depo_adi_filter_depo").val(depo_adi);
                                                    $("#depo_adi_depolar_arasi_depo").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "alinan-depo-modal") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="alinan_depo"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Alınacak Depo Listesi</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="alinan_depo_tablo">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $urunler = verilericoklucek(" SELECT id,warehouse_name as depo_adi FROM warehouses WHERE status=1");
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="alinan-depo-select">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["depo_adi"] ?>"><?php echo $urun_adi["depo_adi"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#alinan_depo").modal("show");
                                                    $("#alinan_depo_tablo").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".alinan-depo-select").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $(".alinan-depo-urun-aktarim-input").val(depo_adi);
                                                    $(".alinan-depo-urun-aktarim-input").attr("data-id", depo_id);
                                                    $("#alinan_depo").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "aktarilan-modal") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="aktarilan_depo"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Aktarılacak Depo Listesi</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="aktarilacak_depo_tablo">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $id = $_GET["depo"];
                                                                            $sql = " SELECT id,warehouse_name as depo_adi FROM warehouses WHERE status=1 AND id NOT IN ('$id')";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="depo-aktar-select">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["depo_adi"] ?>"><?php echo $urun_adi["depo_adi"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#aktarilan_depo").modal("show");
                                                    $("#aktarilacak_depo_tablo").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".depo-aktar-select").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $(".aktarilan-input").val(depo_adi);
                                                    $(".aktarilan-input").attr("data-id", depo_id);
                                                    $("#aktarilan_depo").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "aktarilan-urun-modal") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="aktarilan-urun"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Aktarılacak Depo Listesi</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="aktarilan_urun_tablo">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $id = $_GET["warehouse_id"];
                                                                            $sql = " select stock_name,stock_cardid from stock_receipt_move where status=1 and warehouse_id='$id'";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="urun-aktar-select">
                                                                                    <td data-id="<?php echo $urun_adi["stock_cardid"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["stock_name"] ?>"><?php echo $urun_adi["stock_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#aktarilan-urun").modal("show");
                                                    $("#aktarilan_urun_tablo").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".urun-aktar-select").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $(".aktarilacak-urun-adi").val(depo_adi);
                                                    $(".aktarilacak-urun-adi").attr("data-id", depo_id);
                                                    $("#aktarilan-urun").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "isteyen-depo-filtre") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="isteyen-depo-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Depolar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Depo Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="private-gizli">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Depo Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $sql = " select warehouse_name,id from warehouses where status=1";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="isteyen-depo">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["warehouse_name"] ?>"><?php echo $urun_adi["warehouse_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#isteyen-depo-modal").modal("show");
                                                    $("#private-gizli").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".isteyen-depo").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $("#isteyen-depo-filter").val(depo_adi);
                                                    $("#isteyen-depo-filter").attr("data-id", depo_id);
                                                    $("#isteyen-depo-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "isteyen-kullanici-filtre") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="isteyen-personel-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Kullanıcılar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Kullanıcı Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="personel-listesi">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ad Soyad</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $sql = " select name_surname,id from users where status=1";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="personel-sec">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["name_surname"] ?>"><?php echo $urun_adi["name_surname"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#isteyen-personel-modal").modal("show");
                                                    $("#personel-listesi").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".personel-sec").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $("#isteyen-kullanicilar").val(depo_adi);
                                                    $("#isteyen-kullanicilar").attr("data-id", depo_id);
                                                    $("#isteyen-personel-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "muadil-listesi-getir") {
                                            $transfer_id = $_GET["stock_transferid"];
                                            $gelen_class = $_GET["gidecek_class"];
                                            ?>
                                            <input type="hidden" value="<?php echo $gelen_class ?>" id="gelen_class">
                                            <input type="hidden" value="<?php echo $transfer_id ?>"
                                                   id="transfer_id_muadil">
                                            <div class="modal fade" id="ilacın-muadili-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Muadiller</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered table-hover bg-white w-100"
                                                                   id="muadil-tablosu-transfer">
                                                                <thead>
                                                                <tr>
                                                                    <th>Muadil Adı</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="get-equivalent"
                                                                       style="cursor: pointer;">
                                                                <?php
                                                                $id = $_GET["id"];
                                                                $warehouse_id = $_GET["warehouse_id"];
                                                                if ($warehouse_id == 0) {
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
                                                                            stock_id='$id'";
                                                                } else {
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
                                                                $urunler = verilericoklucek($sql);
                                                                foreach ($urunler as $urun_adi) {
                                                                    ?>
                                                                    <tr class="ilacın-muadili">
                                                                        <td data-id="<?php echo $urun_adi["stock_id"]; ?>"
                                                                            data-name="<?php echo $urun_adi["muadil_adi"] ?>"><?php echo $urun_adi["muadil_adi"]; ?></td>

                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                            <div class="card">
                                                                <div class="card-title text-center">İlacı İste</div>
                                                                <div class="col-12 row">
                                                                    <div class="col-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label-sm">Miktar</label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="request_amount">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label-sm">Tarih</label>
                                                                            <div class="col-sm-7">
                                                                                <input type="date"
                                                                                       class="form-control form-control-sm"
                                                                                       id="request_time">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer mt-2" style="">
                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                            data-bs-dismiss="modal" id="muadil-vazgec">
                                                                        <i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"></i> Vazgeç
                                                                    </button>
                                                                    <button class="btn btn-outline-success btn-sm"
                                                                            disabled
                                                                            id="muadil-iste"><i class="fa fa-plus"
                                                                                                aria-hidden="true"></i>
                                                                        Ekle
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>

                                                $(document).ready(function () {
                                                    document.getElementById('request_time').valueAsDate = new Date();
                                                    $("#ilacın-muadili-modal").modal("show");
                                                    setTimeout(function () {
                                                        $("#muadil-tablosu-transfer").DataTable({
                                                            scrollX: true,
                                                            scrollY: "20vh",
                                                            info: false,
                                                            paging: false,
                                                            length: 5,
                                                            searching: false,
                                                            bLengthChange: false,
                                                            language: {
                                                                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                            }
                                                        });
                                                    }, 300);
                                                });

                                                $("body").off("click", ".ilacın-muadili").on("click", ".ilacın-muadili", function () {
                                                    var stock_id = $("td", this).attr("data-id");
                                                    var transfer_id = $("#transfer_id_muadil").val();
                                                    $("#request_amount").attr("stock_id", stock_id);
                                                    $("#request_amount").attr("transfer_id", transfer_id);
                                                    $(".ilacın-muadili").css("background-color", "rgb(255, 255, 255)");
                                                    $(this).css("background-color", "#60b3abad");
                                                    $("#muadil-iste").prop("disabled", false);
                                                });

                                                $("body").off("click", "#muadil-iste").on("click", "#muadil-iste", function () {
                                                    var stock_id = $("#request_amount").attr("stock_id");
                                                    var transfer_id = $("#request_amount").attr("transfer_id");
                                                    var requested_amount = $("#request_amount").val();
                                                    var request_time = $("#request_time").val();
                                                    $.ajax({
                                                        url: "ajax/stok/stok-sql.php?islem=transfer-istek-bilgileri-sql",
                                                        type: "POST",
                                                        data: {
                                                            stock_cardid: stock_id,
                                                            stock_transferid: transfer_id,
                                                            requested_amount: requested_amount,
                                                            request_time: request_time
                                                        },
                                                        success: function (result) {
                                                            var gelen_class = $("#gelen_class").val();
                                                            var split_class = gelen_class.split(" ");
                                                            var olan_split = split_class[0];


                                                            if (result != 2 && result != 500) {
                                                                $("." + olan_split).css("background-color", "#F1A661");
                                                                $(".depo-id").prop("disabled", true);

                                                                var json = JSON.parse(result);

                                                                $(".transfer-eklenen-kalemler").html("");
                                                                json.forEach(function (item) {
                                                                    console.log(item);
                                                                    $(".transfer-eklenen-kalemler").append(
                                                                        "<tr>" +
                                                                        "<td>" + item.urun_adi + "</td>" +
                                                                        "<td>" + item.barkod + "</td>" +
                                                                        "<td>" + item.requested_amount + "</td>" +
                                                                        "<td>" + item.ad_soyad + "</td>" +
                                                                        "<td>" + item.request_time + "</td>" +
                                                                        "<td><button class='btn btn-outline-danger btn-sm' data-id='" + item.id + "' id='listeden-kaldir'>Sil</button></td>" +
                                                                        "</tr>");
                                                                    $("#ilacın-muadili-modal").modal("hide");
                                                                });
                                                            } else {
                                                                alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı");
                                                            }
                                                        }
                                                    });
                                                });

                                            </script>
                                        <?php }
                                        if ($islem == "filtre-icin-urun-getir") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="urun-getir-modali"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Ürünler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Ürün Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="urunlerin-listesi">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Urun Adi</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $sql = " select id,stock_name from stock_card where status=1";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="urun-selecti">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["stock_name"] ?>"><?php echo $urun_adi["stock_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#urun-getir-modali").modal("show");
                                                    $("#urunlerin-listesi").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".urun-selecti").click(function () {
                                                    var urun_adi = $("td", this).attr("data-name");
                                                    $("#urun_adi_filter").val(urun_adi);
                                                    $("#urun-getir-modali").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "add-new-request") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>
                                            <input type="hidden" value="<?php echo $_GET["id"]; ?>" id="request_id">
                                            <div class="modal fade" id="servis-istekleri-modali"
                                                 data-bs-backdrop="static" role="dialog">
                                                <div class="modal-dialog" style="width: 100%; max-width: 100%;">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Servis İstek Sayfası</h4>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="col-12 row">
                                                                <div class="col-4 kitlenecek-tablo">
                                                                    <div class="col-12">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="depo-filtrele"
                                                                               placeholder="Depo Adı...">
                                                                    </div>
                                                                    <div class="card-title text-center mt-1">Depo
                                                                        Listesi
                                                                    </div>
                                                                    <table class="table table-bordered table-hover nowrap display w-100 mt-1"
                                                                           id="depolar-tablosu-1"
                                                                           style="background: white; width: 100%;font-weight: normal;font-size: 13px">
                                                                        <thead class="table-light">
                                                                        <tr>
                                                                            <th>Depo Kodu</th>
                                                                            <th>Depo Adı</th>
                                                                            <th>MKYS Kodu</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-8 row">
                                                                    <div class="col-12">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               placeholder="Ürün Adı..."
                                                                               id="urun-adi-kelime">
                                                                    </div>
                                                                    <div class="card-title text-center mt-1">Malzeme
                                                                        Listesi
                                                                    </div>
                                                                    <table class="table table-bordered table-hover nowrap display mt-1"
                                                                           id="urunler-tablosu-1"
                                                                           style="background: white; width: 100% !important;font-weight: normal;font-size: 13px">
                                                                        <thead class="table-light">
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Ürün Adı</th>
                                                                            <th>Barkod No</th>
                                                                            <th>Ürün Adet</th>
                                                                            <th>Malzeme Türü</th>
                                                                            <th>Birim</th>
                                                                            <th>İşlem</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 row">
                                                                <div class="card-title text-center mt-1">Eklenen
                                                                    Ürünler
                                                                </div>
                                                                <table class="table table-bordered table-hover nowrap display w-100 mt-1"
                                                                       id="tablo_3"
                                                                       style="background: white; width: 100%;font-weight: normal;font-size: 13px">
                                                                    <thead class="table-light">
                                                                    <tr>
                                                                        <th>Ürün Adı</th>
                                                                        <th>Barkod</th>
                                                                        <th>İstek Miktarı</th>
                                                                        <th>İsteyen Personel</th>
                                                                        <th>İstek Tarihi</th>
                                                                        <th>İşlem</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="servis-istek-eklenen-kalemler"></tbody>
                                                                </table>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-outline-danger btn-sm"
                                                                    id="servis-istek-iptal-button">
                                                                <i
                                                                        class="fa fa-times" aria-hidden="true"></i>
                                                                Vazgeç
                                                            </button>
                                                            <button class="btn btn-outline-success btn-sm"
                                                                    id="servis-istek-olustur"><i
                                                                        class="fa-solid fa-code-pull-request"></i> İstek
                                                                Yap
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="servis-muadilleri-getir"></div>

                                            <script>
                                                $(document).ready(function () {

                                                    var ware;
                                                    $("body").off("click", "#servis-istek-olustur").on("click", "#servis-istek-olustur", function () {
                                                        var requested_warehouse_id = ware;
                                                        var request_id = $("#request_id").val();
                                                        $.ajax({
                                                            url: "ajax/stok/stok-sql.php?islem=istegi-tamamla",
                                                            type: "POST",
                                                            data: {
                                                                requested_warehouse_id: requested_warehouse_id,
                                                                request_id:request_id
                                                            },
                                                            success: function (result) {
                                                                if (result != 2){
                                                                    alertify.success("İstek Kaydı Oluşturuldu");
                                                                    $("#servis-istekleri-modali").modal("hide");
                                                                    $.get("ajax/stok/stok-depo.php?islem=istekler", function (getVeri) {
                                                                        $("#get-warehouse-process").html(getVeri);
                                                                    });
                                                                }else{
                                                                    alertify.warning("Bilinmeyen Bir Hata Oluştu");
                                                                    $("#servis-istekleri-modali").modal("hide");
                                                                }
                                                            }
                                                        });
                                                    });

                                                    $("body").off("click", "#servis-istek-iptal-button").on("click", "#servis-istek-iptal-button", function () {
                                                        var id = $("#request_id").val();
                                                        $.ajax({
                                                            url: "ajax/stok/stok-sql.php?islem=servis-istek-vazgec",
                                                            type: "POST",
                                                            data: {
                                                                id: id
                                                            },
                                                            success: function (result) {
                                                                if (result != 500 || result != 2) {
                                                                    alertify.warning("İşlemden Vazgeçtiniz");
                                                                    $("#servis-istekleri-modali").modal("hide");
                                                                }
                                                            }
                                                        });
                                                    });

                                                    var i = 1;
                                                    $("#servis-istekleri-modali").modal("show");
                                                    var depo_tablo = $("#depolar-tablosu-1").DataTable({
                                                        scrollX: true,
                                                        scrollY: "20vh",
                                                        info: false,
                                                        paging: false,
                                                        length: 5,
                                                        searching: false,
                                                        bLengthChange: false,
                                                        createdRow: function (row) {
                                                            $(row).addClass("ware-id")
                                                        },
                                                        language: {
                                                            url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });

                                                    var urun_tablo = $("#urunler-tablosu-1").DataTable({
                                                        scrollX: true,
                                                        scrollY: "20vh",
                                                        info: false,
                                                        paging: false,
                                                        length: 5,
                                                        searching: false,
                                                        bLengthChange: false,
                                                        createdRow: function (row) {
                                                            $(row).addClass("stock-id-" + i);
                                                            i += 1;
                                                        },
                                                        language: {
                                                            url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });

                                                    $("body").off("click", ".ware-id").on("click", ".ware-id", function () {
                                                        var data = depo_tablo.row(this).data();
                                                        var id = data[0];
                                                        ware = id;
                                                        $.ajax({
                                                            url: "ajax/stok/stok-sql.php?islem=servis-istek-urunleri-getir",
                                                            type: "POST",
                                                            data: {
                                                                id: id
                                                            },
                                                            success: function (result) {
                                                                if (result != 2) {
                                                                    var json = JSON.parse(result);
                                                                    var no = 0;
                                                                    urun_tablo.clear();
                                                                    json.forEach(function (item) {
                                                                        no += 1;
                                                                        urun_tablo.row.add([no, item.stock_name, item.barcode, item.stock_amount, item.malzeme_tur, item.unit, "<button title='Ürün Ekle' class='btn btn-outline-success btn-sm' id='servis-istegini-olustur-button' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-plus'></i></button><button class='btn btn-outline-warning btn-sm mx-2' id='muadil-servis-istek-buton' title='Muadil Ürün Ekle' data-id='" + item.stock_cardid + "'><i class='fa-solid fa-equals'></i></button>"]).draw(false);
                                                                    });
                                                                } else {
                                                                    alertify.warning("Bu Depoya Ait Ürün Bulunmamaktadır");
                                                                }
                                                            }
                                                        });
                                                    });
                                                    $("body").off("click", "#servis-istek-listeden-kaldir").on("click", "#servis-istek-listeden-kaldir", function () {
                                                        var id = $(this).attr("data-id");
                                                        var silinecek_tr = $(this).closest("tr");
                                                        $.ajax({
                                                            url: "ajax/stok/stok-sql.php?islem=servis-istek-kalem-vazgec",
                                                            type: "POST",
                                                            data: {
                                                                id: id
                                                            },
                                                            success: function (result) {
                                                                if (result != 2) {
                                                                    alertify.success("İşlem Başarılı");
                                                                    silinecek_tr.remove();
                                                                } else {
                                                                    alertify.warning("Bilinmeyen Bir hata İle Karşılaşıldı");
                                                                }
                                                            }
                                                        });
                                                    });

                                                    $("body").off("click", "#muadil-servis-istek-buton").on("click", "#muadil-servis-istek-buton", function () {
                                                        var id = $(this).attr("data-id");
                                                        var warehouse_id = ware;
                                                        var request_id = $("#request_id").val();
                                                        var gidecek_class = $(this).closest('tr').attr('class');

                                                        $.get("ajax/stok/stok-sql.php?islem=muadili-varmi", {
                                                            id: id,
                                                            warehouse_id: warehouse_id
                                                        }, function (result) {
                                                            if (result == 1) {
                                                                $.get("ajax/stok/stok-modal.php?islem=servis-muadil-listesi-getir", {
                                                                    id: id,
                                                                    gidecek_class: gidecek_class,
                                                                    warehouse_id: warehouse_id,
                                                                    request_id: request_id
                                                                }, function (getModal) {
                                                                    $(".servis-muadilleri-getir").html(getModal);
                                                                });
                                                            } else {
                                                                alertify.warning("Depoda Ürünün Muadili Yoktur...");
                                                            }
                                                        });
                                                    });

                                                    $.get("ajax/stok/stok-sql.php?islem=servis-istek-depo-getir", function (result) {
                                                        if (result != 2) {
                                                            setTimeout(function () {
                                                                var json = JSON.parse(result);
                                                                depo_tablo.row.add([0, "Tümü", "MKYS Kodu"]).draw(false);
                                                                json.forEach(function (item) {
                                                                    depo_tablo.row.add([item.id, item.warehouse_name, item.mkys_code]).draw(false);
                                                                });
                                                            }, 100);

                                                        } else {
                                                            alertify.warning("Depolar Bulunamıyor");
                                                        }
                                                    });
                                                });

                                                $("body").off("click", "#servis-istegini-olustur-button").on("click", "#servis-istegini-olustur-button", function (getModal) {
                                                    var stock_cardid = $(this).attr("data-id");
                                                    var request_id = $("#request_id").val();
                                                    $.get("ajax/stok/stok-modal.php?islem=servis-istek-onayla-modal", {
                                                        stock_cardid: stock_cardid,
                                                        request_id: request_id,
                                                    }, function (getVeri) {
                                                        $(".servis-istegi-modal").html(getVeri);
                                                    });
                                                });
                                            </script>
                                            <div class="servis-istegi-modal"></div>

                                        <?php }

                                        if ($islem == "servis-istek-onayla-modal") {
                                            ?>
                                            <div class="modal fade" id="servis-istek-talep-modal" tabindex="-1"
                                                 role="dialog" aria-hidden="true"
                                                 data-bs-backdrop="static">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-center">İstek Bilgileri</h5>
                                                        </div>
                                                        <input type="hidden"
                                                               value="<?php echo $_GET["stock_cardid"]; ?>"
                                                               id="stock_cardid">
                                                        <input type="hidden" value="<?php echo $_GET["request_id"]; ?>"
                                                               id="request_id">
                                                        <div class="modal-body">
                                                            <div class="col-12">
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Miktar</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="servis-istek-miktari">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mt-1">
                                                                    <label class="col-sm-3 col-form-label-sm">Tarih</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="date"
                                                                               class="form-control form-control-sm"
                                                                               id="servis-istek-tarihi">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-outline-danger btn-sm close-this-page"
                                                                    data-bs-dismiss="modal"><i
                                                                        class="fa fa-times" aria-hidden="true"></i>
                                                                Vazgeç
                                                            </button>
                                                            <button class="btn btn-outline-success btn-sm"
                                                                    id="servis-istek-olustur-buton"><i
                                                                        class="fa fa-plus" aria-hidden="true"></i> Ekle
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>

                                                $(document).ready(function () {
                                                    $("#servis-istek-talep-modal").modal("show");
                                                    document.getElementById('servis-istek-tarihi').valueAsDate = new Date();
                                                    $("body").off("click", "#servis-istek-olustur-buton").on("click", "#servis-istek-olustur-buton", function () {
                                                        var istek_miktar = $("#servis-istek-miktari").val();
                                                        var istek_tarihi = $("#servis-istek-tarihi").val();
                                                        var stock_cardid = $("#stock_cardid").val();
                                                        var request_id = $("#request_id").val();
                                                        if (istek_miktar == "" || istek_miktar == 0) {
                                                            alertify.warning("En Az 1 Adet Miktar Girmelisiniz...");
                                                        } else {
                                                            $.ajax({
                                                                url: "ajax/stok/stok-sql.php?islem=servis-istek-bilgileri-sql",
                                                                type: "POST",
                                                                data: {
                                                                    requested_amount: istek_miktar,
                                                                    stock_cardid: stock_cardid,
                                                                    request_id: request_id,
                                                                    request_time: istek_tarihi
                                                                },
                                                                success: function (result) {
                                                                    if (result != 2 && result != 500) {
                                                                        $(".ware-id").prop("disabled", true);
                                                                        $("#servis-istek-talep-modal").modal("hide");
                                                                        var json = JSON.parse(result);
                                                                        $(".servis-istek-eklenen-kalemler").html("");
                                                                        json.forEach(function (item) {
                                                                            $(".servis-istek-eklenen-kalemler").append(
                                                                                "<tr>" +
                                                                                "<td>" + item.urun_adi + "</td>" +
                                                                                "<td>" + item.barkod + "</td>" +
                                                                                "<td>" + item.requested_amount + "</td>" +
                                                                                "<td>" + item.ad_soyad + "</td>" +
                                                                                "<td>" + item.request_time + "</td>" +
                                                                                "<td><button class='btn btn-outline-danger btn-sm' data-id='" + item.id + "' id='servis-istek-listeden-kaldir'>Sil</button></td>" +
                                                                                "</tr>");
                                                                        });
                                                                    } else {
                                                                        alertify.error("Beklenmeyen Bir Hata İle Karşılaşıldı");
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    });
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "servis-muadil-listesi-getir") {
                                            $transfer_id = $_GET["request_id"];
                                            $gelen_class = $_GET["gidecek_class"];
                                            ?>
                                            <input type="hidden" value="<?php echo $gelen_class ?>" id="gelen_class">
                                            <input type="hidden" value="<?php echo $transfer_id ?>"
                                                   id="request_id_muadil">
                                            <div class="modal fade" id="ilacın-muadili-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Muadiller</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered table-hover bg-white w-100"
                                                                   id="muadil-tablosu-transfer">
                                                                <thead>
                                                                <tr>
                                                                    <th>Muadil Adı</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="get-equivalent"
                                                                       style="cursor: pointer;">
                                                                <?php
                                                                $id = $_GET["id"];
                                                                $warehouse_id = $_GET["warehouse_id"];
                                                                if ($warehouse_id == 0) {
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
                                                                            stock_id='$id'";
                                                                } else {
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
                                                                $urunler = verilericoklucek($sql);
                                                                foreach ($urunler as $urun_adi) {
                                                                    ?>
                                                                    <tr class="ilacın-muadili">
                                                                        <td data-id="<?php echo $urun_adi["stock_id"]; ?>"
                                                                            data-name="<?php echo $urun_adi["muadil_adi"] ?>"><?php echo $urun_adi["muadil_adi"]; ?></td>

                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                            <div class="card">
                                                                <div class="card-title text-center">İlacı İste</div>
                                                                <div class="col-12 row">
                                                                    <div class="col-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label-sm">Miktar</label>
                                                                            <div class="col-sm-7">
                                                                                <input type="text"
                                                                                       class="form-control form-control-sm"
                                                                                       id="request_amount">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-3 col-form-label-sm">Tarih</label>
                                                                            <div class="col-sm-7">
                                                                                <input type="date"
                                                                                       class="form-control form-control-sm"
                                                                                       id="request_time">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer mt-2" style="">
                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                            data-bs-dismiss="modal" id="muadil-vazgec">
                                                                        <i
                                                                                class="fa fa-times"
                                                                                aria-hidden="true"></i> Vazgeç
                                                                    </button>
                                                                    <button class="btn btn-outline-success btn-sm"
                                                                            disabled
                                                                            id="muadil-iste"><i class="fa fa-plus"
                                                                                                aria-hidden="true"></i>
                                                                        Ekle
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>

                                                $(document).ready(function () {
                                                    document.getElementById('request_time').valueAsDate = new Date();
                                                    $("#ilacın-muadili-modal").modal("show");
                                                    setTimeout(function () {
                                                        $("#muadil-tablosu-transfer").DataTable({
                                                            scrollX: true,
                                                            scrollY: "20vh",
                                                            info: false,
                                                            paging: false,
                                                            length: 5,
                                                            searching: false,
                                                            bLengthChange: false,
                                                            language: {
                                                                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                            }
                                                        });
                                                    }, 300);
                                                });

                                                $("body").off("click", ".ilacın-muadili").on("click", ".ilacın-muadili", function () {
                                                    var stock_id = $("td", this).attr("data-id");
                                                    var request_id = $("#request_id_muadil").val();
                                                    $("#request_amount").attr("stock_id", stock_id);
                                                    $("#request_amount").attr("request_id", request_id);
                                                    $(".ilacın-muadili").css("background-color", "rgb(255, 255, 255)");
                                                    $(this).css("background-color", "#60b3abad");
                                                    $("#muadil-iste").prop("disabled", false);
                                                });

                                                $("body").off("click", "#muadil-iste").on("click", "#muadil-iste", function () {
                                                    var stock_id = $("#request_amount").attr("stock_id");
                                                    var request_id = $("#request_amount").attr("request_id");
                                                    var requested_amount = $("#request_amount").val();
                                                    var request_time = $("#request_time").val();
                                                    $.ajax({
                                                        url: "ajax/stok/stok-sql.php?islem=servis-istek-bilgileri-sql",
                                                        type: "POST",
                                                        data: {
                                                            stock_cardid: stock_id,
                                                            request_id: request_id,
                                                            requested_amount: requested_amount,
                                                            request_time: request_time
                                                        },
                                                        success: function (result) {
                                                            var gelen_class = $("#gelen_class").val();
                                                            var split_class = gelen_class.split(" ");
                                                            var olan_split = split_class[0];

                                                            if (result != 2 && result != 500) {
                                                                $("." + olan_split).css("background-color", "#F1A661");
                                                                $(".ware-id").prop("disabled", true);
                                                                var json = JSON.parse(result);

                                                                $(".servis-istek-eklenen-kalemler").html("");
                                                                json.forEach(function (item) {
                                                                    $(".servis-istek-eklenen-kalemler").append(
                                                                        "<tr>" +
                                                                        "<td>" + item.urun_adi + "</td>" +
                                                                        "<td>" + item.barkod + "</td>" +
                                                                        "<td>" + item.requested_amount + "</td>" +
                                                                        "<td>" + item.ad_soyad + "</td>" +
                                                                        "<td>" + item.request_time + "</td>" +
                                                                        "<td><button class='btn btn-outline-danger btn-sm' data-id='" + item.id + "' id='servis-istek-listeden-kaldir'>Sil</button></td>" +
                                                                        "</tr>");
                                                                    $("#ilacın-muadili-modal").modal("hide");
                                                                });
                                                            } else {
                                                                alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı");
                                                            }
                                                        }
                                                    });
                                                });
                                            </script>
                                        <?php }
                                        if ($islem == "servis-icin-birim-getir") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>

                                            <div class="modal fade" id="isteyen-birim-modal"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Birimler</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Birim Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="birim-getir-modali">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Birim Adı</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $sql = " select department_name,id from units where status=1";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="isteyen-depo">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["department_name"] ?>"><?php echo $urun_adi["department_name"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#isteyen-birim-modal").modal("show");
                                                    $("#birim-getir-modali").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".isteyen-depo").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $("#isteyen-birim-filter").val(depo_adi);
                                                    $("#isteyen-birim-filter").attr("data-id", depo_id);
                                                    $("#isteyen-birim-modal").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "servis-isteyen-kullanici") {
                                            ?>
                                            <style>
                                                .modal-body {
                                                    max-height: calc(100vh - 150px);
                                                    overflow-y: auto;
                                                }
                                            </style>
                                            <div class="modal fade" id="servis-isteyen-kullanici"
                                                 data-bs-backdrop="static"
                                                 role="dialog">
                                                <div class="modal-dialog" style="width: 45%; max-width: 45%;">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header text-white">
                                                            <h4 class="modal-title">Kullanıcılar</h4>
                                                            <input type="hidden" id="id" class="form-control">
                                                            <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card mt-2">
                                                                        <div class="card-header bg-success p-2 text-white">
                                                                            <strong>Kullanıcı Listesi</strong></div>

                                                                        <table class="table table-bordered table-hover bg-white w-100"
                                                                               id="servis-isteyen-kullanici-getir">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Ad Soyad</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody class="get-equivalent"
                                                                                   style="cursor: pointer;">
                                                                            <?php
                                                                            $sql = " select name_surname,id from users where status=1";
                                                                            $urunler = verilericoklucek($sql);
                                                                            foreach ($urunler as $urun_adi) {
                                                                                ?>
                                                                                <tr class="isteyen-depo">
                                                                                    <td data-id="<?php echo $urun_adi["id"]; ?>"
                                                                                        data-name="<?php echo $urun_adi["name_surname"] ?>"><?php echo $urun_adi["name_surname"]; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    $("#servis-isteyen-kullanici").modal("show");
                                                    $("#servis-isteyen-kullanici-getir").DataTable({
                                                        "responsive": true,
                                                        "language": {
                                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                        }
                                                    });
                                                });

                                                $(".isteyen-depo").click(function () {
                                                    var depo_adi = $("td", this).attr("data-name");
                                                    var depo_id = $("td", this).attr("data-id");
                                                    $("#servis-isteyen-kullanicilar").val(depo_adi);
                                                    $("#servis-isteyen-kullanicilar").attr("data-id", depo_id);
                                                    $("#servis-isteyen-kullanici").modal("hide");
                                                });
                                            </script>
                                        <?php }

                                        if ($islem == "servis-istek-red-modal"){
                                        $id = $_POST["id"];
                                        $request_id = $_POST["request_id"];
                                        ?>
                                        <style>
                                            .modal-body {
                                                max-height: calc(100vh - 150px);
                                                overflow-y: auto;
                                            }
                                        </style>
                                        <div class="modal fade" id="rejection-service-request" data-bs-backdrop="static"
                                             data-bs-keyboard="false" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <h5>Silme İşlemini Onayla</h5>
                                                        <hr>
                                                        <div>
                                                            <div>
                                                                <label>Red Nedeniniz</label>
                                                                <input type="text" class="form-control"
                                                                       id="rejection_title">
                                                            </div>
                                                            <div>
                                                                <input type="hidden"
                                                                       class="form-control"
                                                                       id="stock-cardid"
                                                                       value="<?php echo $id ?>">
                                                                <input type="hidden"
                                                                       class="form-control"
                                                                       id="request_id"
                                                                       value="<?php echo $request_id ?>">
                                                            </div>
                                                            <br>
                                                            <div class="offset-md-4">
                                                                <button type="button"
                                                                        class="btn btn-light"
                                                                        id="servis-istek-red"
                                                                        style="color: blue;">Onayla
                                                                </button>
                                                                <button type="button" class="btn btn-light"
                                                                        id="red-vazgec"
                                                                        data-bs-dismiss="modal">Vazgeç
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>

                                                    $("body").off("click", "#red-vazgec").on("click", "#red-vazgec", function () {
                                                        var request_id = $("#request_id").val();
                                                        $.get("ajax/stok/stok-sql.php?islem=istekleri-tekrar-cek", {id: request_id}, function (result) {
                                                            if (result != 2) {
                                                                $(".request-detail").html("");
                                                                var json = JSON.parse(result);
                                                                var no = 0;
                                                                json.forEach(function (item) {
                                                                    no += 1;
                                                                    var zamani = item.request_time;
                                                                    var explode = zamani.split("-");
                                                                    var gun = explode[2];
                                                                    var ay = explode[1];
                                                                    var yil = explode[0];
                                                                    var arr = [gun, ay, yil];
                                                                    var implode = arr.join("/");

                                                                    var status = item.request_status;
                                                                    var durum;
                                                                    var onay_check;
                                                                    var red_check;

                                                                    if (status == 0) {
                                                                        durum = "Bekliyor";
                                                                        onay_check = "";
                                                                        red_check = "";
                                                                    } else if (status == 1) {
                                                                        durum = "Onay";
                                                                        onay_check = "checkde";
                                                                        red_check = "";
                                                                    } else {
                                                                        durum = "Red";
                                                                        onay_check = "";
                                                                        red_check = "checked";
                                                                    }
                                                                    no += 1;
                                                                    $(".request-detail").append("" +
                                                                        "<tr>" +
                                                                        "<td>" + no + "</td>" +
                                                                        "<td>" + item.barkod + "</td>" +
                                                                        "<td>" + item.urun_adi + "</td>" +
                                                                        "<td>" + item.requested_amount + "</td>" +
                                                                        "<td>" + item.ad_soyad + "</td>" +
                                                                        "<td>" + implode + "</td>" +
                                                                        "<td>" + durum + "</td>" +
                                                                        "<td><input type='checkbox' class='servis-onay-checked col-9' " + onay_check + " data-id='" + item.id + "'/></td>" +
                                                                        "<td><input type='checkbox' class='servis-red-checked col-9' " + red_check + " data-id='" + item.id + "'/></td>" +
                                                                        "</tr>");
                                                                });
                                                            }
                                                        });
                                                    });

                                                    $(document).ready(function () {
                                                        $("#rejection-service-request").modal("show");
                                                    });

                                                    $("body").off("click", "#servis-istek-red").on("click", "#servis-istek-red", function () {
                                                        var id = $("#stock-cardid").val();
                                                        var rejection_title = $("#rejection_title").val();
                                                        var request_id = $("#request_id").val();
                                                        $.ajax({
                                                            url: "ajax/stok/stok-sql.php?islem=servis-istek-red-sql",
                                                            type: "POST",
                                                            data: {
                                                                id: id,
                                                                rejection_title: rejection_title,
                                                                request_id: request_id
                                                            },
                                                            success: function (result) {
                                                                if (result != 2) {
                                                                    alertify.success("İstek Reddedildi");
                                                                    $("#rejection-service-request").modal("hide");
                                                                    $(".request-detail").html("");
                                                                    var json = JSON.parse(result);
                                                                    var no = 0;
                                                                    json.forEach(function (item) {
                                                                        no += 1;
                                                                        var zamani = item.request_time;
                                                                        var explode = zamani.split("-");
                                                                        var gun = explode[2];
                                                                        var ay = explode[1];
                                                                        var yil = explode[0];
                                                                        var arr = [gun, ay, yil];
                                                                        var implode = arr.join("/");

                                                                        var status = item.request_status;
                                                                        var durum;
                                                                        var onay_check;
                                                                        var red_check;

                                                                        if (status == 0) {
                                                                            durum = "Bekliyor";
                                                                            onay_check = "";
                                                                            red_check = "";
                                                                        } else if (status == 1) {
                                                                            durum = "Onay";
                                                                            onay_check = "checked";
                                                                            red_check = "";
                                                                        } else {
                                                                            durum = "Red";
                                                                            onay_check = "";
                                                                            red_check = "checked";
                                                                        }
                                                                        no += 1;
                                                                        $(".request-detail").append("" +
                                                                            "<tr>" +
                                                                            "<td>" + no + "</td>" +
                                                                            "<td>" + item.barkod + "</td>" +
                                                                            "<td>" + item.urun_adi + "</td>" +
                                                                            "<td>" + item.requested_amount + "</td>" +
                                                                            "<td>" + item.ad_soyad + "</td>" +
                                                                            "<td>" + implode + "</td>" +
                                                                            "<td>" + durum + "</td>" +
                                                                            "<td><input type='checkbox' class='servis-onay-checked col-9' " + onay_check + " data-id='" + item.id + "'/></td>" +
                                                                            "<td><input type='checkbox' class='servis-red-checked col-9' " + red_check + " data-id='" + item.id + "'/></td>" +
                                                                            "</tr>");
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <?php } ?>