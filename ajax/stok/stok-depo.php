<?php


include '../../controller/fonksiyonlar.php';
$islem = $_GET["islem"];

if ($islem == "warehouses") {
    ?>
    <div class="row mt-3">
        <ul class="nav nav-pills nav-justified" role="tablist" id="mytab">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" id="add-warehouses" role="tab" aria-selected="false">
                    <span class="d-none d-sm-block stoklist" style="cursor: pointer;">Depolar</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" id="add-stock-warehouse" role="tab" aria-selected="false">
                    <span class="d-none d-sm-block" style="cursor: pointer;">Depo Ürün İşlemleri</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link " data-bs-toggle="tab" id="requests" role="tab" aria-selected="true">
                    <span class="d-none d-sm-block" style="cursor: pointer;">İstekler</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link tanimlamalar-button" data-bs-toggle="tab" id="transfer-request" role="tab"
                   aria-selected="false">
                    <span class="d-none d-sm-block" style="cursor: pointer;">Depolar Arası Transfer</span>
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link tanimlamalar-button" data-bs-toggle="tab" id="stock_movement" role="tab"
                   aria-selected="false">
                    <span class="d-none d-sm-block" style="cursor: pointer;">Stok Kart Hareket Detay</span>
                </a>
            </li>
        </ul>
    </div>
    <div id="get-warehouse-process"></div>
    <script>
        $("#add-warehouses").click(function () {
            $.get("ajax/stok/stok-depo.php?islem=yeni-depo-tanimla", function (getVeri) {
                $("#get-warehouse-process").html(getVeri);
            });
        });
        $("#stock_movement").click(function () {
            $.get("ajax/stok/stok-depo.php?islem=stok-hareketleri", function (getVeri) {
                $("#get-warehouse-process").html(getVeri);
            });
        });
        $("#add-stock-warehouse").click(function () {
            $.get("ajax/stok/stok-depo.php?islem=depoya-urun-ekle", function (getVeri) {
                $("#get-warehouse-process").html(getVeri);
            });
        });
        $("#transfer-request").click(function () {
            $.get("ajax/stok/stok-depo.php?islem=transfer-request", function (getVeri) {
                $("#get-warehouse-process").html(getVeri);
            });
        });
        $("#requests").click(function () {
            $.get("ajax/stok/stok-depo.php?islem=istekler", function (getVeri) {
                $("#get-warehouse-process").html(getVeri);
            });
        });
    </script>
<?php }
if ($islem == "yeni-depo-tanimla") {
    ?>
    <script>
        var detay_arr = [];
        $(document).ready(function () {
            $("#warehouse-detail-table").css("display", "none");
            $(".update-warehouse-card").css("display", "none");
            var table1 = $("#stock-form-warehouse").DataTable({
                "responsive": true,
                scrollX: true,
                scrollY: "28vh",
                lengthMenu: false,
                "bLengthChange": false,
                "createdRow": function (row) {
                    $(row).addClass('warehouse-id');
                },
                info: false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });

            var table = $("#warehouse-detail-table").DataTable({
                responsive: true,
                lengthMenu: false,
                bLengthChange: false,
                paging: false,
                searching: false,
                info: false,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });

            $("body").off("click", ".yeni-depo-ekle").on("click", ".yeni-depo-ekle", function () {
                $.get("ajax/stok/stok-modal.php?islem=yeni-depo-ekle", function (getModal) {
                    $("#get-modals").html(getModal);
                });

            });

            $("body").off("click", "#filter-warehouse").on("click", "#filter-warehouse", function () {
                var depo_adi = $("#depo_adi_filtre").val();
                var mkys_kodu = $("#mkys_kodu_filtre").val();
                var depo_turu = $("#depo_turu_filtre").val();

                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=depo-filtrele-sql",
                    type: "GET",
                    data: {
                        depo_adi: depo_adi,
                        mkys_kodu: mkys_kodu,
                        depo_turu: depo_turu
                    },
                    success: function (result) {
                        if (result != 2) {
                            var json = JSON.parse(result);
                            no = 0;
                            table1.clear();
                            json.forEach(function (item) {
                                no += 1;
                                table1.row.add([no, item.id, item.warehouse_name, item.mkys_code]).draw(false);
                            });
                        } else {
                            alertify.warning("Kayıt Bulunamadı");
                        }
                    }
                });
            });

            $("body").off("click", "#get-upgrade-info").on("click", "#get-upgrade-info", function () {
                $("#warehouse_name").val(detay_arr[0]["depo_adi"]);
                $("#bina_kodu").val(detay_arr[0]["bina_kodu"]);
                $("#mkys_kodu").val(detay_arr[0]["mkys_kodu"]);
                $("#ekleyen_kullanici").val(detay_arr[0]["ekleyen_kullanici"]);
                $(".select-box").val(detay_arr[0]["depo_type"]);
                $(".select-box").html(detay_arr[0]["depo_tipi"]);
                $("#depo_id_guncelle").val(detay_arr[0]["depo_id"]);
            });

            $("body").off("click", ".warehouse-id").on("click", ".warehouse-id", function () {
                var data = table1.row(this).data();
                var id = data[1];
                $(".warehouse-id").css("background-color", "rgb(255, 255, 255)");
                $(this).css("background-color", "#60b3abad");
                $('.warehouse-id').removeClass('select');
                $(this).addClass("select");

                $.get("ajax/stok/stok-sql.php?islem=depo-detay-tablo", {id: id}, function (result) {
                    if (result != 2) {
                        table.clear();
                        detay_arr = [];
                        var json = JSON.parse(result);
                        table.row.add([json.depo_id, json.depo_adi, json.bina_kodu, json.mkys_kodu, json.depo_tipi, json.aktiflik_bilgisi, json.ekleyen_kullanici, "<button class='btn btn-outline-secondary btn-sm' id='get-upgrade-info'><i class='fa fa-eye' aria-hidden='true'></i></button>"]).draw(false);
                        detay_arr.push(json);
                        $("#warehouse-detail-table").show();
                        $(".update-warehouse-card").show();
                    } else {
                        alertify.warning("Veri Bulunamadı");
                    }
                });
            });


            $("body").off("click", ".update-warehouse-buton").on("click", ".update-warehouse-buton", function () {

                var warehouse_name = $("#warehouse_name").val();
                var bina_kodu = $("#bina_kodu").val();
                var mkys_kodu = $("#mkys_kodu").val();
                var depo_turu = $("#depo_turu").val();
                var id = $("#depo_id_guncelle").val();

                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=depoyu-guncelle",
                    type: "POST",
                    data: {
                        id: id,
                        warehouse_name: warehouse_name,
                        buildingid: bina_kodu,
                        mkys_code: mkys_kodu,
                        warehouse_type: depo_turu
                    },
                    success: function (result) {
                        $(".sonucyaz").html(result);
                        $.get("ajax/stok/stok-depo.php?islem=yeni-depo-tanimla", function (getVeri) {
                            $("#get-warehouse-process").html(getVeri);
                        });
                    }
                });
            });
        });

        $("body").off("click", "#vazgec-filtre").on("click", "#vazgec-filtre", function () {
            $("#depo_adi_filtre").val("");
            $("#depo_turu_filtre").val("");
            $("#mkys_kodu_filtre").val("");
        });

    </script>

    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm yeni-depo-ekle" style="background-color: #112D4E; color: white;">Yeni Depo Tanımla
        </button>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-title text-center">Kriter Paneli</div>
            </div>
            <div class="card-body">
                <div class="col-12 row">
                    <div class="col-4">
                    </div>
                    <div class="col-4">
                        <div class="col-12 row">
                            <div class="form-group col-4 mt-1">
                                <div class="col-12">
                                    <input type="text" id="depo_adi_filtre" class="form-control form-control-sm"
                                           placeholder="Depo Adı">
                                </div>
                            </div>
                            <div class="form-group col-4 mt-1">
                                <div class="col-12">
                                    <select id="depo_turu_filtre" class="form-select form-select-sm">
                                        <option value="">Depo Türü Seç.</option>
                                        <option value="2079">Ana</option>
                                        <option value="28461">Cep</option>
                                        <option value="30112">Ambar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-4 mt-1">
                                <div class="col-12">
                                    <input type="text" id="mkys_kodu_filtre" class="form-control form-control-sm"
                                           placeholder="MKYS Kodu">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mt-1">
                        <button class="btn btn-outline-danger btn-sm" id="vazgec-filtre"><i
                                    class="fa-sharp fa-solid fa-xmark"></i> Temizle
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="filter-warehouse"><i
                                    class="fas fa-filter"></i> Filtrele
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="row">
                <div class="card">
                    <div class="card-title text-center">Depo Listesi</div>
                </div>
                <div class="px-4">
                    <table class="table table-bordered table-sm table-hover px-2" id="stock-form-warehouse"
                           style="background: white; width: 100%; font-size:13px;">
                        <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Depo Kodu</th>
                            <th>Depo Adı</th>
                            <th>MKYS Kodu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $istekler = verilericoklucek(" SELECT * FROM warehouses WHERE status=1");
                        $no = 0;
                        foreach ($istekler as $item) {
                            $no += 1;
                            ?>
                            <tr id="<?php echo $item["id"] ?>">
                                <td><?php echo $no ?></td>
                                <td><?php echo $item["id"] ?></td>
                                <td><?php echo $item["warehouse_name"] ?></td>
                                <td><?php echo $item["mkys_code"] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 row">
            <div class="col-9 mt-4">
                <div class="row mt-2">
                    <div class="px-4">
                        <table class="table table-bordered table-sm table-hover" id="warehouse-detail-table"
                               style="background: white; width: 100%; font-size:13px;">
                            <thead class="table-light">
                            <tr>
                                <th>Depo Kodu</th>
                                <th>Depo Adı</th>
                                <th>Bina Kodu</th>
                                <th>MKYS Kodu</th>
                                <th>Depo Türü</th>
                                <th>Durum</th>
                                <th>Ekleyen Kullanıcı</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-3 update-warehouse-card">
                <div class="col-12">
                    <div class="p-1">
                        <h6 class="fw-bold text-center">Depo Güncelle</h6>
                    </div>
                    <div id="get-upgrade-warehouses">
                        <div class="col-12 row">
                            <div class="col-6">
                                <div class="form-group mx-1 mt-1">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm" value=""
                                               placeholder="Depo Adı"
                                               id="warehouse_name">
                                    </div>
                                </div>
                                <div class="form-group mt-1 mx-1">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm" value=""
                                               placeholder="Bina Kodu"
                                               id="bina_kodu">
                                    </div>
                                </div>
                                <div class="form-group mt-1 mx-1">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm" value=""
                                               placeholder="MKYS Kodu"
                                               id="mkys_kodu">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mt-1">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm" value=""
                                               placeholder="Ekleyen Kullanıcı"
                                               disabled
                                               id="ekleyen_kullanici">
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <div class="col-sm-12">
                                        <select class="form-select form-select-sm" id="depo_turu">
                                            <option class="select-box" value="Depo Türü..">Depo Türü</option>
                                            <option value="2079">Ana</option>
                                            <option value="28461">Cep</option>
                                            <option value="30112">Ambar</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="depo_id_guncelle">
                            <div class="modal-footer mx-2 mt-2">
                                <div class="col-12">
                                    <button class="btn btn-outline-success btn-sm update-warehouse-buton">Güncelle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
if ($islem == "depoya-urun-ekle") {
    ?>
    <script>

        $(document).ready(function () {
            var table1 = $("#stock_receipt_move").DataTable({
                "responsive": true,
                scrollX: true,
                scrollY: "28vh",
                paging: false,
                lengthMenu: false,
                searching: false,
                "bLengthChange": false,
                "createdRow": function (row) {
                    $(row).addClass('warehouse-id');
                },
                info: false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });


            $(".depoya-stok-girisi-yap").click(function () {
                $.get("ajax/stok/stok-modal.php?islem=depoya-stok-giris-modal", function (getModal) {
                    $("#get-modals").html(getModal);
                });
            });

            $("body").off("click", "#filter-depo-urunleri").on("click", "#filter-depo-urunleri", function () {
                var urun_adi = $("#urun_adi_filter").val();
                var malzeme_turu = $("#malzeme_turu_filter").val();
                var depo_adi = $("#depo_adi_filter").val();

                $.get("ajax/stok/stok-sql.php?islem=depo-urun-filterele", {
                    urun_adi: urun_adi,
                    malzeme_turu: malzeme_turu,
                    depo_adi: depo_adi
                }, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        no = 0;
                        table1.clear();
                        json.forEach(function (item) {
                            no += 1;
                            table1.row.add([no, item.barcode, item.stock_name, item.stock_amount, item.stok_tur, item.lot_no, item.ats_no, item.depo_adi]).draw(false);
                        })
                    } else {
                        alertify.warning("Herhangi Bir Kayıt Bulunamadı");
                    }
                });
            });
        });
        $("body").off("click", "#urunleri-getir-button").on("click", "#urunleri-getir-button", function () {
            $.get("ajax/stok/stok-modal.php?islem=filtre-icin-urun-getir", function (getModal) {
                $(".get-urunler").html(getModal);
            });
        });

        $("body").off("click", "#get-depolar-modali").on("click", "#get-depolar-modali", function () {
            $.get("ajax/stok/stok-modal.php?islem=filtre-icin-depo-getir", function (getModal) {
                $(".get-depolar").html(getModal);
            });
        });

        $("body").off("click", "#filtre-temizle").on("click", "#filtre-temizle", function (item) {
            $("#urun_adi_filter").val("");
            $("#malzeme_turu_filter").val("");
            $("#depo_adi_filter").val("");
        });

    </script>
    <br>
    <div class="row">
        <div class="col-12 row">
            <div class="card-title text-center">Kriter Paneli</div>
            <div class="col-4">
            </div>
            <div class="col-4">
                <div class="col-12 row mt-2">
                    <div class="col-4">
                        <div class="col-12">
                            <div class="form-group row ">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="urun_adi_filter"
                                               placeholder="Ürün Adı"
                                               aria-describedby="basic-addon2"
                                               disabled>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-warning btn-sm"
                                                    type="button"
                                                    id="urunleri-getir-button">
                                                <i
                                                        class="fa fa-ellipsis-h"
                                                        aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="get-urunler"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="col-12">
                            <select class="form-select form-select-sm" id="malzeme_turu_filter">
                                <option value="">Malzeme Tür</option>
                                <option value="28464">İlaç</option>
                                <option value="28462">Sarf</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group row ">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="depo_adi_filter"
                                           placeholder="Depo Adı"
                                           aria-describedby="basic-addon2"
                                           disabled
                                    >
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm"
                                                type="button"
                                                id="get-depolar-modali">
                                            <i
                                                    class="fa fa-ellipsis-h"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="get-depolar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mt-2">
                <button class="btn btn-outline-danger btn-sm" id="filtre-temizle"><i
                            class="fa-sharp fa-solid fa-xmark"></i> Temizle
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="filter-depo-urunleri"><i
                            class="fas fa-filter"></i> Filtrele
                </button>
            </div>

        </div>
        <div class="col-12 px-2 card border-primary mt-2">
            <div class="card-title text-center">
                Depolardaki Ürünler
            </div>
            <table class="table table-bordered table-sm table-hover px-2 mt-2" id="stock_receipt_move"
                   style="background: white; width: 100%; font-size: 13px">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Barkod</th>
                    <th>Ürün Adı</th>
                    <th>Ürün Miktarı</th>
                    <th>Malzeme Türü</th>
                    <th>Lot No</th>
                    <th>Ats No</th>
                    <th>Depo Adı</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek(" 
                                SELECT
                                    srm.*,
                                    sip.ats_number as ats_no,
                                    sip.lot_number as lot_no,
                                    w.warehouse_name as depo_adi
                                FROM
                                    stock_receipt_move as srm
                                INNER JOIN stock_invoice_pen as sip ON sip.stock_receiptid=srm.stock_receiptid
                                INNER JOIN warehouses as w ON w.id=srm.warehouse_id
                                WHERE  
                                srm.status=1
                                ");
                $sira_no = 0;
                foreach ($istekler as $item) {
                    $sira_no += 1;
                    ?>
                    <tr id="<?php echo $item["id"] ?>" class="select-id">
                        <td><?php echo $sira_no ?></td>
                        <td><?php echo $item["barcode"] ?></td>
                        <td><?php echo $item["stock_name"] ?></td>
                        <td><?php echo $item["stock_amount"] ?></td>
                        <td><?php echo islemtanimgetirid($item["stock_type"]) ?></td>
                        <td><?php echo $item["lot_no"] ?></td>
                        <td><?php echo $item["ats_no"] ?></td>
                        <td><?php echo $item["depo_adi"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // $(".select-id").click(function () {
        //     var id = $(this).attr("id");
        //     $(".select-id").css("background-color", "rgb(255,255,255)");
        //     $(this).css("background-color", "#60b3abad");
        //     $(".select-id").removeClass("select");
        //     $(this).addClass("select");
        //     $.ajax({
        //         url: "ajax/stok/stok-sql.php?islem=depodaki-urunu-getir",
        //         type: "POST",
        //         data: {
        //             id: id
        //         },
        //         success: function (getVeri) {
        //             var veriler = JSON.parse(getVeri);
        //             $("#stock_name").val(veriler.stock_name);
        //             $("#stock_amount").val(veriler.stock_amount);
        //             $("#lot_number").val(veriler.lot_number);
        //             $("#movable_code").val(veriler.movable_code);
        //             $("#device_code").val(veriler.device_code);
        //             $("#ats_query_number").val(veriler.ats_query_number);
        //             $("#warehouse_id").val(veriler.warehouse_id);
        //             $("#expiration_date").val(veriler.expiration_date);
        //         }
        //     });
        //     var warehouse_id = $("#warehouse_id").val();
        //     $.ajax({
        //         url: "ajax/stok/stok-sql.php?islem=depo-ismi-getir",
        //         type: "GET",
        //         data: {
        //             warehouse_id: warehouse_id
        //         },
        //         success: function (veri) {
        //             var depo_adi = JSON.parse(veri);
        //             $("#warehouse_name").val(depo_adi.warehouse_name);
        //         }
        //     });
        // });

        $(".depolar-arasi-urun-aktarimi").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=depolar-arasi-stok", function (getVeri) {
                $("#get-modals").html(getVeri);
            })
        });
    </script>
<?php }
if ($islem == "istekler") {
    ?>
    <script>

        $(document).ready(function () {
            $(".istek-detay-tablosu").css("display", "none");
            var tablo = $("#request-table").DataTable({
                scrollX: true,
                scrollY: "23vh",
                info: false,
                paging: false,
                length: 5,
                searching: false,
                bLengthChange: false,
                "createdRow": function (row) {
                    $(row).addClass('request-id');
                },
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                }
            });

            $("body").off("click", "#filtrele-servis-istekleri-buton").on("click", "#filtrele-servis-istekleri-buton", function () {
                var unit_id = $("#isteyen-birim-filter").attr("data-id");
                var bas_tarih = $("#bas_tarih_servis").val();
                var bit_tarih = $("#bit_tarih_servis").val();
                var user_id = $("#servis-isteyen-kullanicilar").attr("data-id");
                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=servis-istek-filtrele",
                    type: "POST",
                    data: {
                        unit_id: unit_id,
                        bas_tarih: bas_tarih,
                        bit_tarih: bit_tarih,
                        user_id: user_id
                    },
                    success: function (result) {
                        if (result != 2) {
                            var json = JSON.parse(result);
                            tablo.clear();
                            json.forEach(function (item) {
                                var request_time = item.request_time;
                                var explode = request_time.split("-");
                                var gun = explode[2];
                                var ay = explode[1];
                                var yil = explode[0];
                                var date_arr = [gun, ay, yil];
                                var implode = date_arr.join("/");

                                tablo.row.add([item.id, implode, item.birim_adi,item.istedigi_depo, item.ad_soyad]).draw(false);
                            });
                        } else {
                            alertify.warning("İstek Bulunamadı...");
                        }
                    }
                });
            });
            var request_id;

            $("body").off("click", ".request-id").on("click", ".request-id", function () {
                var data = tablo.row(this).data();
                var id = data[0];
                request_id = id;
                setTimeout(function () {
                    $.ajax({
                        url: "ajax/stok/stok-sql.php?islem=servis-istek-detayi-getir",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function (result) {
                            if (result != 2) {
                                $(".istek-detay-tablosu").show();
                                var json = JSON.parse(result);
                                var no = 0;
                                detay_table.clear();
                                $("#servis-istek-duzenle").attr("data-id", id);
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
                                    detay_table.row.add([no, item.barkod, item.urun_adi, item.requested_amount, item.ad_soyad, implode, durum, "<input type='checkbox' class='servis-onay-checked servis-onay-checked-" + item.id + " col-9' " + onay_check + " data-id='" + item.id + "' disabled />","<input " + red_check + "  disabled type='checkbox' class='servis-red-checked servis-red-checked-"+item.id+" col-9' data-id='" + item.id + "' />"]).draw(false);
                                });
                            } else {
                                $(".istek-detay-tablosu").hide();
                                alertify.warning("İsteğe Ait Ürün Yoktur...")
                            }
                        }
                    })
                }, 100);

            });

            $.get("ajax/stok/stok-sql.php?islem=servis-istek-sql", function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    json.forEach(function (item) {
                        var request_time = item.request_time;
                        var explode = request_time.split("-");
                        var gun = explode[2];
                        var ay = explode[1];
                        var yil = explode[0];
                        var date_arr = [gun, ay, yil];
                        var implode = date_arr.join("/");

                        tablo.row.add([item.id, implode, item.birim_adi, item.istedigi_depo,item.ad_soyad]).draw(false);
                    });
                }
            });

            $("body").off("click", "#add-request").on("click", "#add-request", function () {
                var unit_id = 703;
                var user_id = $("#kullanici_id").val();

                $.ajax({
                    url: "ajax/stok/stok-sql.php?islem=add-new-request",
                    type: "POST",
                    data: {
                        unit_id: unit_id,
                        request_userid: user_id
                    },
                    success: function (result) {
                        if (result != 2) {
                            var id = result.trim();
                            $.get("ajax/stok/stok-modal.php?islem=add-new-request", {id: id}, function (getModal) {
                                $("#get-modals").html(getModal);
                            });
                        } else {
                            alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı...");
                        }
                    }
                });
            });

            $("body").off("click",".servis-onay-checked").on("click",".servis-onay-checked",function (){
                var id = $(this).attr("data-id");
                var requested_id = request_id;
                if ($(".servis-red-checked-"+id).is(":checked")){
                    $(".servis-red-checked-"+id).prop("checked",false);
                    $.ajax({
                        url:"ajax/stok/stok-sql.php?islem=servis-istek-onayla",
                        type:"POST",
                        data:{
                            id:id,
                            requested_id:requested_id
                        },
                        success:function (result){
                            if (result != 500 || result != 404 || result !=300){
                                console.log(result);
                            }
                        }
                    });
                }else{
                }
            });

            $("body").off("click", "#servis-filtre-temizle").on("click", "#servis-filtre-temizle", function () {
                $("#isteyen-birim-filter").val("");
                $("#isteyen-birim-filter").attr("data-id", "");
                $("#servis-isteyen-kullanicilar").val("");
                $("#servis-isteyen-kullanicilar").attr("data-id", "");
                $("#bas_tarih_servis").val("");
                $("#bit_tarih_servis").val("");
            });

            $("body").off("click", "#servis-istek-duzenle").on("click", "#servis-istek-duzenle", function () {
                $(".servis-onay-checked").prop("disabled", false);
                $(".servis-red-checked").prop("disabled", false);
            });

            $("body").off("click", ".servis-red-checked").on("click", ".servis-red-checked", function () {
                var id = $(this).attr("data-id");
                var requested_id = request_id;
                if ($(".servis-onay-checked-" + id).is(":checked")) {
                    alertify.warning("Reddedilemez Yöneticinize Başvurunuz...");
                    $(this).prop("checked",false);
                } else {
                    $.ajax({
                        url: "ajax/stok/stok-modal.php?islem=servis-istek-red-modal",
                        type: "POST",
                        data: {
                            id: id,
                            request_id: requested_id
                        },
                        success: function (getModal) {
                            $("#get-modals").html(getModal);
                        }
                    });
                }
            });

            $("body").off("click", "#get-birimler-modali").on("click", "#get-birimler-modali", function () {
                $.get("ajax/stok/stok-modal.php?islem=servis-icin-birim-getir", function (getModal) {
                    $("#get-modals").html(getModal);
                });
            });
            $("body").off("click", "#servis-isteyen-kullanici-button").on("click", "#servis-isteyen-kullanici-button", function () {
                $.get("ajax/stok/stok-modal.php?islem=servis-isteyen-kullanici", function (getModal) {
                    $("#get-modals").html(getModal);
                });
            });

            var detay_table = $("#request_detail_table").DataTable({
                scrollX: true,
                scrollY: "10vh",
                info: false,
                paging: false,
                length: 5,
                searching: false,
                bLengthChange: false,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                }
            });
        });
    </script>

    <input type="hidden" value="<?php echo $_SESSION["id"]; ?>" id="kullanici_id">
    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm" id="add-request" style="background-color: #112D4E; color:white;">
            İstek Oluştur
        </button>
    </div>
    <div class="col-12 row mt-3">

        <div class="col-12 row mt-3">
            <div class="card-title text-center">Kriter Paneli</div>
            <div class="col-4">
            </div>
            <div class="col-4">
                <div class="col-12 row mt-2">
                    <div class="col-3">
                        <div class="col-12">
                            <div class="form-group row ">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               id="isteyen-birim-filter"
                                               placeholder="İ.Birim"
                                               aria-describedby="basic-addon2"
                                               disabled
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-warning btn-sm"
                                                    type="button"
                                                    id="get-birimler-modali">
                                                <i
                                                        class="fa fa-ellipsis-h"
                                                        aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="servis-isteyen-Birim"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" placeholder="Bas. Tarih"
                                   onfocus="(this.type='date')" id="bas_tarih_servis">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="col-12">
                            <input type="text" class="form-control form-control-sm" placeholder="Bit. Tarih"
                                   onfocus="(this.type='date')" id="bit_tarih_servis">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group row ">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="servis-isteyen-kullanicilar"
                                           placeholder="İ.Kullanıcı"
                                           aria-describedby="basic-addon2"
                                           disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm"
                                                type="button"
                                                id="servis-isteyen-kullanici-button">
                                            <i
                                                    class="fa fa-ellipsis-h"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="servis-isteyen-kullanici-div"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mt-2">
                <button class="btn btn-outline-danger btn-sm" id="servis-filtre-temizle"><i
                            class="fa-sharp fa-solid fa-xmark"></i>
                    Temizle
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="filtrele-servis-istekleri-buton"><i
                            class="fas fa-filter"></i> Filtrele
                </button>
            </div>
        </div>
    </div>

    <div class="col-12 row mt-3">
        <div class="card">
            <div class="card-title text-center">İstek Listesi</div>
        </div>
        <table class="table table-bordered table-sm table-hover px-2 mt-2" id="request-table"
               style="background: white; width: 100%; font-size: 13px">
            <thead class="table-light">
            <tr>
                <th>İstek No</th>
                <th>İstek Tarihi</th>
                <th>İsteyen Birim</th>
                <th>İstediği Depo</th>
                <th>İsteyen Kullanıcı</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-12 row mt-3 istek-detay-tablosu">
        <div class="card">
            <div class="card-title text-center">İstek Detay Listesi</div>
        </div>
        <div class="col-12 mt-1">
            <div class="col-2">
                <button class="btn btn-outline-warning btn-sm mx-2" id="servis-istek-duzenle" title="Düzenle"><i
                            class="fa-regular fa-pen-to-square"></i> Düzenle
                </button>
            </div>
        </div>
        <table class="table table-bordered table-sm table-hover px-2 mt-2" id="request_detail_table"
               style="background: white; width: 100%; font-size: 13px">
            <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Barkod</th>
                <th>Ürün Adı</th>
                <th>İstek Miktarı</th>
                <th>İsteyen Personel</th>
                <th>İstek Zamanı</th>
                <th>Durum</th>
                <th>Onay</th>
                <th>Red</th>
            </tr>
            </thead>
            <tbody class="request-detail">
            </tbody>
        </table>
    </div>
<?php }
if ($islem == "transfer-request") {
    ?>

    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm depolar-arasi-urun-aktarimi" disabled style="background-color: #112D4E; color:white;">
            Depolar
            Arası Ürün Aktarımı
        </button>
    </div>

    <div class="col-12 row mt-3">
        <div class="card-title text-center">Kriter Paneli</div>
        <div class="col-4">
        </div>
        <div class="col-4">
            <div class="col-12 row mt-2">
                <div class="col-3">
                    <div class="col-12">
                        <div class="form-group row ">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           id="isteyen-depo-filter"
                                           placeholder="İ.Depo"
                                           aria-describedby="basic-addon2"
                                           disabled
                                    >
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm"
                                                type="button"
                                                id="get-depolar-modali">
                                            <i
                                                    class="fa fa-ellipsis-h"
                                                    aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="isteyen-depolar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="col-12">
                        <input type="text" class="form-control form-control-sm" placeholder="Bas. Tarih"
                               onfocus="(this.type='date')" id="bas_tarih">
                    </div>
                </div>
                <div class="col-3">
                    <div class="col-12">
                        <input type="text" class="form-control form-control-sm" placeholder="Bit. Tarih"
                               onfocus="(this.type='date')" id="bit_tarih">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group row ">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="isteyen-kullanicilar"
                                       placeholder="İ.Kullanıcı"
                                       aria-describedby="basic-addon2"
                                       disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-warning btn-sm"
                                            type="button"
                                            id="isteyen-kullanici-button">
                                        <i
                                                class="fa fa-ellipsis-h"
                                                aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="isteyen-kullanici-div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mt-2">
            <button class="btn btn-outline-danger btn-sm" id="filtre-temizle"><i class="fa-sharp fa-solid fa-xmark"></i>
                Temizle
            </button>
            <button class="btn btn-outline-secondary btn-sm" id="filtrele-transfer-istekleri"><i
                        class="fas fa-filter"></i> Filtrele
            </button>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-title text-center">Transfer İstek Listesi</div>
        </div>
        <div class="col-12">
            <table class="table table-sm  table-bordered table-hover px-2 nowrap display w-100 display nowrap"
                   style="font-size: 13px"
                   id="transferleri-getir-tablosu">
                <thead class="table-light">
                <tr>
                    <th>İstek No</th>
                    <th>İstek Tarihi</th>
                    <th>İsteyen Depo</th>
                    <th>Karşılayan Depo</th>
                    <th>İsteyen Personel</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 mt-3 gorunur-tablo">
        <div class="card">
            <div class="card-title text-center">İstek Detay Listesi</div>
        </div>
        <div class="col-12">
            <table class="table table-sm  table-bordered table-hover px-2 nowrap display w-100 display nowrap"
                   style="font-size: 13px"
                   id="transfer-istek-detay">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Ürün Adı</th>
                    <th>Barkod</th>
                    <th>İstek Tarihi</th>
                    <th>İstek Miktarı</th>
                    <th>İsteyen Personel</th>
                    <th>İstek Durumu</th>
                    <th>Onay</th>
                    <th>Red</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var warehouse = $(".secilen-depo").val();
            if (warehouse != "Seçilmedi") {
                $(".depolar-arasi-urun-aktarimi").prop("disabled", false);
            }
            $(".gorunur-tablo").css("display", "none");
            var table = $("#transferleri-getir-tablosu").DataTable({
                "responsive": true,
                scrollX: true,
                scrollY: "25vh",
                lengthMenu: false,
                paging: false,
                searching: false,
                "bLengthChange": false,
                "createdRow": function (row) {
                    $(row).addClass('transfer-id');
                },
                info: false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
            $.get("ajax/stok/stok-sql.php?islem=depolar_arasi_urun_sql", function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    json.forEach(function (item) {
                        var insert = item.insert_datetime;
                        var explode = insert.split(" ");
                        var tre_tarih = explode[0]
                        var explode2 = tre_tarih.split("-");
                        var gun = explode2[2];
                        var ay = explode2[1];
                        var yil = explode2[0];
                        var arr = [gun, ay, yil];
                        var implode = arr.join("/");
                        table.row.add([item.id, implode, item.isteyen_depo, item.karsilayan_depo, item.ad_soyad]).draw(false);
                    })
                } else {
                    alertify.warning("Transfer İsteği Bulunamadı")
                }
            });

            var detay_table = $("#transfer-istek-detay").DataTable({
                "responsive": true,
                scrollX: true,
                paging: false,
                searching: false,
                scrollY: "10vh",
                lengthMenu: false,
                "bLengthChange": false,
                info: false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });

            $("body").off("click", ".transfer-id").on("click", ".transfer-id", function () {
                var data = table.row(this).data();
                var id = data[0];
                $(".transfer-id").css("background-color", "rgb(255, 255, 255)");
                $(this).css("background-color", "#60b3abad");
                $('.transfer-id').removeClass('select-transfer-id');
                $(this).addClass("select-transfer-id");

                var no = 0;
                $.get("ajax/stok/stok-sql.php?islem=transfer-stock-kalem", {id: id}, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        detay_table.clear();
                        json.forEach(function (item) {
                            no += 1;
                            if (item.request_status == 0) {
                                var durum = "Bekliyor";
                            } else if (item.request_status == 1) {
                                durum = "Onay";
                                var checked_onay = "checked";
                                var checked_red = "";
                            } else {
                                durum = "Red";
                                var checked_onay = "";
                                var checked_red = "checked";
                            }
                            detay_table.row.add([no, item.urun_adi, item.barkod, item.request_time, item.requested_amount, item.ad_soyad, durum, "<input type='checkbox' " + checked_onay + " class='col-9' disabled>", "<input type='checkbox'  " + checked_red + " class='col-9' disabled>"]).draw(false);
                        });
                        $(".gorunur-tablo").show();
                    } else {
                        alertify.warning("İsteğe Ait Bir Ürün Bulunmuyor...");
                    }
                });
            });

            $("body").off("click", "#filtrele-transfer-istekleri").on("click", "#filtrele-transfer-istekleri", function () {
                var isteyen_kullanicilar = $("#isteyen-kullanicilar").attr("data-id");
                var bas_tarih = $("#bas_tarih").val();
                var bit_tarih = $("#bit_tarih").val();
                var isteyen_depo_filter = $("#isteyen-depo-filter").attr("data-id");

                $.get("ajax/stok/stok-sql.php?islem=stock-transfer-filtreleme", {
                    isteyen_depo: isteyen_depo_filter,
                    bas_tarih_transfer: bas_tarih,
                    bit_tarih_transfer: bit_tarih,
                    isteyen_kullanici: isteyen_kullanicilar
                }, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        table.clear();
                        json.forEach(function (item) {
                            var insert = item.insert_datetime;
                            var explode = insert.split(" ");
                            var tre_tarih = explode[0]
                            var explode2 = tre_tarih.split("-");
                            var gun = explode2[2];
                            var ay = explode2[1];
                            var yil = explode2[0];
                            var arr = [gun, ay, yil];
                            var implode = arr.join("/");

                            table.row.add([item.id, implode, item.isteyen_depo, item.karsilayan_depo, item.ad_soyad]).draw(false);
                        });
                    } else {
                        alertify.warning("Kayıt Bulunamadı...");
                    }
                });
            });
        });

        $("body").off("click", ".depolar-arasi-urun-aktarimi").on("click", ".depolar-arasi-urun-aktarimi", function () {
            var catering_warehouseid = $(".secilen-depo").val();
            $.ajax({
                url: "ajax/stok/stok-sql.php?islem=istek-olustur",
                type: "POST",
                data: {
                    catering_warehouseid: catering_warehouseid
                },
                success: function (result) {
                    if (result != 2) {
                        var id = result.trim();
                        $.get("ajax/stok/stok-modal.php?islem=depolar-arasi-stok", {
                            id: id,
                            warehouse_id: catering_warehouseid
                        }, function (getModal) {
                            $("#get-modals").html(getModal);
                        });
                    } else {
                        alertify.warning("Bilinmeyen Bir Hata İle Karşılaşıldı");
                    }
                }
            });
        });

        $("body").off("click", "#filtre-temizle").on("click", "#filtre-temizle", function () {
            $("#isteyen-kullanicilar").val("");
            $("#isteyen-kullanicilar").attr("data-id", "");
            $("#bas_tarih").val("");
            $("#bit_tarih").val("");
            $("#isteyen-depo-filter").val("");
            $("#isteyen-depo-filter").attr("data-id", "");
        });

        $("body").off("click", "#isteyen-kullanici-button").on("click", "#isteyen-kullanici-button", function () {
            $.get("ajax/stok/stok-modal.php?islem=isteyen-kullanici-filtre", function (getModal) {
                $(".isteyen-kullanici-div").html(getModal);
            });
        });

        $("body").off("click", "#get-depolar-modali").on("click", "#get-depolar-modali", function () {
            $.get("ajax/stok/stok-modal.php?islem=isteyen-depo-filtre", function (getModal) {
                $(".isteyen-depolar").html(getModal);
            });
        });
    </script>
<?php }
if ($islem == "filtrele-ve-getir") {

    $doctor_name = $_POST["doctor_name"];
    $unit_id = $_POST["unit_id"];
    $date1 = $_POST["date1"];
    $date2 = $_POST["date2"];
    $status = $_POST["status"];
    $stock_type_filter = $_POST["stock_type_filter"];
    $singular = singular("users", "name_surname", $doctor_name);
    $doctor_id = $singular["id"];
    $aranacak_tarih = tredate($date1);
    $aranacak_tarih2 = tredate($date2);

    $sql = "SELECT * FROM stock_request_move WHERE status=1 ";


    if ($unit_id != "Seçiniz..." && isset($unit_id)) {
        $sql .= " AND stock_request_unitid='$unit_id'";
    }
    if ($date1 != null && $date2 != null || $date1 != "" && $date2 != "") {
        $sql .= " AND insert_datetime BETWEEN '$aranacak_tarih' AND '$aranacak_tarih2'";
    }
    if (isset($status) && $status != "Seçiniz...") {
        $sql .= " AND request_rejection_status='$status'";
    }
    if (isset($stock_type_filter) && $stock_type_filter != "Seçiniz...") {
        $sql .= " AND stock_type='$stock_type_filter'";
    }
    if (isset($doctor_name) && $doctor_name != null) {
        $sql .= " AND doctorid='$doctor_id'";
    }
    ?>
    <script>
        $(document).ready(function () {
            $("#requests-table").DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
        $(".istek-olustur").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=get-request-modal", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });
    </script>
    <div class="buttons px-4">
        <button class="btn btn-sm istek-olustur" style="background-color: #60B3ABAD; color:white;">İstek Oluştur
        </button>
    </div>
    <br>
    <div class="row">
        <div class="col-3 px-2">
            <div class="card">
                <div class="card-header">
                    Filtreleme Kriterleri
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Doktor Adı</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control form-control-sm gelecek_doktor_adi" disabled>
                        </div>
                        <div class="col-1">
                            <button class="btn btn-sm-warning" id="get-doctors"><i class="fa fa-ellipsis-h"
                                                                                   aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Birim Adı</label>
                        <div class="col-sm-7">
                            <select id="unit_id" class="form-select form-select-sm">
                                <option value="Seçiniz...">Seçiniz...</option>
                                <?php
                                $get_units = verilericoklucek("SELECT department_name,id FROM units WHERE status=1");
                                foreach ($get_units as $unit) {
                                    ?>
                                    <option value="<?php echo $unit["id"]; ?>"><?php echo $unit["department_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Baş. Tarih</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control form-control-sm" id="date1">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Bit. Tarihi</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control form-control-sm" id="date2">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Durum</label>
                        <div class="col-sm-7">
                            <select class="form-select form-select-sm filter_status">
                                <option value="Seçiniz...">Seçiniz...</option>
                                <option value="1">Karşılandı</option>
                                <option value="2">Rededildi</option>
                                <option value="0">Bekliyor</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Stok Türü</label>
                        <div class="col-sm-7">
                            <select id="stock_type_filter" class="form-select form-select-sm">
                                <option value="Seçiniz...">Seçiniz...</option>
                                <option value="28464">İLAÇ</option>
                                <option value="28462">SARF</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm fiter-sql" data-dismiss="modal"
                                style="background-color: #112D4E; color: white;">Filtrele
                        </button>
                    </div>
                </div>

                <div class="select-doctor"></div>
            </div>
        </div>
        <div class="col-9">
            <font size="2">
                <table class="table table-sm  table-bordered table-hover px-2 nowrap display w-100 display nowrap"
                       id="requests-table">
                    <thead class="table-light">
                    <tr>
                        <th>İstek No</th>
                        <th>İstenen Ürün</th>
                        <th>İstek Miktarı</th>
                        <th>Doktor Adı</th>
                        <th>Gelen Birim</th>
                        <th>Talep Durum</th>
                        <th>Ürün Bilgisi</th>
                        <th>İstek Türü</th>
                        <th>İstek Zamanı</th>
                        <th>Açıklama</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $_GET["gelen_birim"] = "policlinic";
                    $gelis = $_GET["gelen_birim"];
                    if ($gelis == "policlinic") {
                        $gelis = "doctorid";
                    } else {
                        $gelis = "service_doctorid";
                    }

                    $verilericoklucek = verilericoklucek($sql);
                    foreach ($verilericoklucek as $item) {
                        $id = $item["given_stock_cardid"];
                        $units_id = $item["stock_request_unitid"];
                        $stock_name = singular("stock_card", "id", $id);
                        $birim_id = singular("units", "id", $units_id);
                        ?>
                        <tr id="<?php echo $item["id"] ?>" class="select-id">
                            <td><?php echo $item["id"] ?></td>
                            <td><?php echo $stock_name["stock_name"] ?></td>
                            <td><?php echo $item["requested_amount"]; ?></td>
                            <td><?php echo kullanicigetirid($item[$gelis]); ?></td>
                            <td><?php echo $birim_id["department_name"]; ?></td>
                            <td <?php if ($item["request_rejection_status"] == 1){ ?> style="color: darkgreen;"
                                <?php } if ($item["request_rejection_status"] == 2){ ?>style="color: red;"<?php } ?>
                                <?php if ($item["request_rejection_status"] == 0){ ?>style="color: gray;"<?php } ?> > <?php if ($item["request_rejection_status"] == 1) {
                                    echo 'Karşılandı';
                                }
                                if ($item["request_rejection_status"] == 2) {
                                    echo "Reddedildi";
                                }
                                if ($item["request_rejection_status"] == 0) {
                                    echo 'Bekliyor';
                                } ?></td>
                            <td><?php echo $item["material_info"]; ?></td>
                            <td><?php echo islemtanimgetirid($item["stock_type"]); ?></td>
                            <td><?php echo $item["insert_datetime"]; ?></td>
                            <td><?php echo $item["description"]; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>

    <script>
        $(".gelismis-arama").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=gelismis-arama-modal", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });
    </script>

    <script>

        $("#get-doctors").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=get-doctors-modal", function (getModal) {
                $(".select-doctor").html(getModal);
            });
        });

        $(".fiter-sql").click(function () {
            var doctor_name = $(".gelecek_doktor_adi").val();
            var unit_id = $("#unit_id").val();
            var date1 = $("#date1").val();
            var date2 = $("#date2").val();
            var status = $(".filter_status").val();
            var stock_type_filter = $("#stock_type_filter").val();
            $.ajax({
                url: "ajax/stok/stok-depo.php?islem=filtrele-ve-getir",
                type: "POST",
                data: {
                    doctor_name: doctor_name,
                    unit_id: unit_id,
                    date1: date1,
                    date2: date2,
                    status: status,
                    stock_type_filter: stock_type_filter
                },
                success: function (result) {
                    $("#get-warehouse-process").html(result);
                }
            });
        });
    </script>

<?php }
if ($islem == "stok-hareketleri") {

    ?>
    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .fa-plus {
            color: #dbe2ef;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div id="yonetimModuluKullanicilarMainContent">
                <div class="row">

                    <div class="col-xxl-2 col-xl-3 col-md-4 col-lg-4 col-sm-5 cikti-menu" id="leftMenuDomSuper">
                        <div class="card" style="background-color: #FFFAE7">
                            <div class="card leftMenuCard" style="background-color: #FFFAE7">
                                <div class="d-flex align-items-center">
                                    <i class="fa-regular fa-square-plus fa-2x"></i>
                                    <span class="fw-bold leftMenuTitle">&nbsp;Stok Detay</span>
                                </div>
                            </div>
                            <br>
                            <!--                        <div class="px-1 leftMenuTextOrangeBtn">-->
                            <!--                            <button class="stok-hareketleri"><i class="fa fa-plus fa-2x"></i></button>-->
                            <!--                            <span>Stok Kartları</span>-->
                            <!--                        </div>-->

                            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                                <button class="fatura-hareketleri"><i class="fa fa-plus fa-2x"></i></button>
                                <span>Stok Hareketleri</span>
                            </div>
                            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                                <div>
                                    <button class="depo-stoklari"><i class="fa fa-plus fa-2x"></i></button>
                                    <span>Depo Stokları</span>
                                </div>
                            </div>

                            <div class="px-1 mt-1 leftMenuTextOrangeBtn">
                                <div>
                                    <button class="iade-hareketleri"><i class="fa fa-plus fa-2x"></i></button>
                                    <span>İade Hareketleri</span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-md-8 col-lg-8 col-sm-7 tablolar-bastir">
                        <div class="warning-definitions mt-5">
                            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                                <p>İşlem Yapmak İçin Seçim yapınız</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(".stok-hareketleri").click(function () {
            $.get("ajax/stok/stok-detay.php?islem=stok-hareketleri-tablo", function (getTable) {
                $(".tablolar-bastir").html("");
                $(".tablolar-bastir").html(getTable);
            });
        });

        $(".fatura-hareketleri").click(function () {
            $.get("ajax/stok/stok-detay.php?islem=stok-hareket-sorgula-getir", function (getTable) {
                $(".tablolar-bastir").html("");
                $(".tablolar-bastir").html(getTable);
            });
        });

        $(".servis-stoklari").click(function () {
            alert("servis-stoklari");
        });

        $(".depo-stoklari").click(function () {
            $.get("ajax/stok/stok-detay.php?islem=depo-stoklari", function (getTable) {
                $(".tablolar-bastir").html("");
                $(".tablolar-bastir").html(getTable);
            });
        });

        $(".iade-hareketleri").click(function () {
            $.get("ajax/stok/stok-detay.php?islem=iade-hareketleri", function (getTable) {
                $(".tablolar-bastir").html("");
                $(".tablolar-bastir").html(getTable);
            });
        });
    </script>
<?php } ?>
