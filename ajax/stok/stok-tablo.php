<?php
include '../../controller/fonksiyonlar.php';
$islem = $_GET["islem"];

if ($islem == "stok-kartlari-getir") {
    ?>
    <script>
        $(document).ready(function () {
            $('#stock-cards').DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });

        $('.stock-id').on("click", function () {
            var id = $(this).attr('id');
            $('.stock-id').css("background-color", "rgb(255, 255, 255)");
            $(this).css("background-color", "#60b3abad");
            $('.stock-id').removeClass('select');
            $(this).addClass("select");
        });

        $(".stok-karti-ekle").on("click", function () {
            $.get("ajax/stok/stok-modal.php?islem=yeni-kart-tanimla", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });
        $(".stok-karti-guncelle").on("click", function () {
            var id = $(".select").attr("id");
            if (id == null) {
                alertify.error('Güncellemek İstediğiniz Veriyi Seçiniz');
            } else {
                $.ajax({
                    url: "ajax/stok/stok-modal.php?islem=stok-kart-guncelle",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (getVeri) {
                        $("#get-modals").html(getVeri);
                    }
                });
            }
        });
        $(".stok-karti-sil").on("click", function () {
            var id = $(".select").attr("id");
            if (id == null) {
                alertify.error('Silmek İstediğiniz Veriyi Seçiniz');
            } else {
                $.ajax({
                    url: "ajax/stok/stok-modal.php?islem=stok-kart-sil",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function (result) {
                        $("#get-modals").html(result);
                    }
                });
            }
        });
    </script>

    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm stok-karti-ekle" style="background-color: #112D4E; color: white;">Yeni Stok Kartı
            Oluştur
        </button>
        <button class="btn btn-sm stok-karti-guncelle" style="background-color: #3F72AF; color: white;">Stok Kartını
            Güncelle
        </button>
        <button class="btn btn-sm stok-karti-sil" style="background-color: #e64848; color: white;">Stok Kartını Sil
        </button>
        <!--            <button class="btn btn-sm muadil-eklenecek-liste" style="background-color: #FF9551; color: white;">Muadil Eklenecekler</button>-->
    </div>
    <div class="muadil-icin-modal-getir"></div>
    <div class="row">
        <div class="px-4">
            <font size="2">
                <table class="table table-bordered table-sm table-hover px-2" id="stock-cards"
                       style="background: white; width: 100%;">
                    <thead class="table-light">
                    <tr>
                        <th>Barkod</th>
                        <th>Ürün Adı</th>
                        <th>Üretici Firma</th>
                        <th>Ürün Tipi</th>
                        <th>Kutu Miktarı</th>
                        <th>Uyarılar</th>
                        <th>Gebe Kullanım</th>
                        <th>Araç Kullanım</th>
                        <th>Açıklama</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $istekler = verilericoklucek(" SELECT * FROM stock_card WHERE status=1");
                    foreach ($istekler as $item) {
                        ?>
                        <tr id="<?php echo $item["id"] ?>" class="stock-id">
                            <td><?php echo $item["barcode"] ?></td>
                            <td><?php echo $item["stock_name"] ?></td>
                            <td><?php echo $item["producting_brand"] ?></td>
                            <td><?php echo islemtanimgetirid($item["stock_type"]) ?></td>
                            <td><?php echo $item["stock_number_of_boxes"] ?></td>
                            <td><?php echo $item["stock_warnings"] ?></td>
                            <td><?php echo $item["stock_pregnant_use"] ?></td>
                            <td><?php echo $item["stock_vehicle_machine_use"] ?></td>
                            <td><?php echo $item["drug_description"] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
        </div>
    </div>
    <script>

        // $(".muadil-eklenecek-liste").click(function (){
        //     $.get("ajax/stok/stok-tablo.php?islem=muadil-eklenecek-tablo-getir",function (e){
        //         $("#get-tables").html(e);
        //     });
        // });
    </script>
<?php }

if ($islem == "depoya-gore-getir") {
    $warehouse_id = $_GET["warehouse_id"];
    ?>
    <script>
        $(document).ready(function () {
            $("#stock-form-warehouse").DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
            $("#buttons").hide();
        });
    </script>
    <div class="row">
        <div class="px-4">
            <input type="hidden" id="warehouse_id" value="<?php echo $warehouse_id ?>">
            <br>
            <table class="table table-bordered table-sm table-hover px-2" id="stock-form-warehouse"
                   style="background: white; width: 100%;">
                <thead class="table-light">
                <tr>
                    <th>Barkod</th>
                    <th>Ürün Adı</th>
                    <th>Kutudaki Adet</th>
                    <th>Depodaki Adet</th>
                    <th>Uyarılar</th>
                    <th>Ürün Tipi</th>
                    <th>Yaş Grupları</th>
                    <th>Açıklama</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek(" SELECT * FROM stock_receipt_move WHERE warehouse_id= '$warehouse_id' AND status=1");
                foreach ($istekler as $item) {
                    ?>
                    <tr id="<?php echo $item["id"] ?>" class="stock-id">
                        <td><?php echo $item["barcode"] ?></td>
                        <td><?php echo $item["stock_name"] ?></td>
                        <td><?php echo $item["stock_number_of_boxes"] ?></td>
                        <td><?php echo $item["stock_amount"] ?></td>
                        <td><?php echo $item["stock_warnings"] ?></td>
                        <td><?php echo islemtanimgetirid($item["stock_type"]); ?></td>
                        <td><?php echo $item["stock_age_groups"] ?></td>
                        <td><?php echo $item["drug_description"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-xl-12" id="buttons">
                    <div class="d-flex justify-content-end align-items-end ">
                        <div class="text-center d-flex flex-column me-3">
                            <button class="btn btn-add update-stock-receipt" style="background-color: #FF9551;">
                                <img src="assets/icons/Add.png" alt="icon" width="40px">
                            </button>
                            <label>Güncelle</label>
                        </div>
                        <div class="text-center d-flex flex-column me-3">
                            <button class="btn btn-pink delete-stock-receipt" style="background-color: #E64848;">
                                <img src="assets/icons/Close.png" alt="icon" width="40px">
                            </button>
                            <label>Sil</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modals"></div>

    <script>
        // DATATABLE YE TIKLANDIĞINDA VERİNİN ID SİNİ ALIR
        $('.stock-id').on("click", function () {
            var id = $(this).attr('id');
            $('.stock-id').css("background-color", "rgb(255, 255, 255)");
            $(this).css("background-color", "#60b3abad");
            $('.stock-id').removeClass('select');
            $(this).addClass("select");
            $("#buttons").show();
        });


        $(".delete-stock-receipt").on("click", function () {
            var id = $(".select").attr("id");
            var warehouse_id = $("#warehouse_id").val();
            $.ajax({
                url: "ajax/stok/stok-modal.php?islem=depo-stok-sil",
                type: "POST",
                data: {
                    id: id,
                    warehouse_id: warehouse_id
                },
                success: function (getVeri) {
                    $("#modals").html(getVeri);
                }
            });
        });

        // UPDATE MODAL ÇAĞIRMA SCRİPTİ
        $(".update-stock-receipt").on("click", function () {
            var id = $(".select").attr("id");
            var warehouse_id = $("#warehouse_id").val();

            $.ajax({
                url: "ajax/stok/stok-modal.php?islem=depodaki-stoklari-guncelle",
                type: "POST",
                data: {
                    id: id,
                    warehouse_id: warehouse_id
                },
                success: function (getVeri) {
                    $("#modals").html(getVeri);
                }
            });

        });
    </script>
<?php }

if ($islem == "firmalari-getir") {
    ?>

    <!-- FİRMALAR SCRİPTİ -->
    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm yeni-firma-ekle" style="background-color: #112D4E; color: white;">Yeni Firma Ekle
        </button>
        <button class="btn btn-sm firma-guncelle" style="background-color: #3F72AF; color: white;">Firma Güncelle
        </button>
        <button class="btn btn-sm firma-sil" style="background-color: #e64848; color: white;">Firma Sil</button>
    </div>
    <script>
        $(document).ready(function () {
            $("#stock-from-warehouse").DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });

    </script>
    <div class="mx-2 mt-2">
        <font size="2">
            <table class="table table-bordered table-sm table-hover px-2" id="stock-from-warehouse"
                   style="background: white; width: 100%;">
                <thead class="table-light">
                <tr>
                    <th>Firma Adı</th>
                    <th>Telefon No</th>
                    <th>Mail Adresi</th>
                    <th>Firma Ünvanı</th>
                    <th>Firma Sahibi</th>
                    <th>Firma Kodu</th>
                    <th>Firma Adresi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek(" SELECT * FROM companies WHERE status=1");
                foreach ($istekler as $item) {
                    ?>
                    <tr id="<?php echo $item["id"] ?>" class="company-id">
                        <td><?php echo $item["company_name"] ?></td>
                        <td><?php echo $item["phone_number"] ?></td>
                        <td><?php echo $item["email"] ?></td>
                        <td><?php echo $item["company_title"] ?></td>
                        <td><?php echo $item["authorized_person"] ?></td>
                        <td><?php echo $item["company_code"] ?></td>
                        <td><?php echo $item["company_address"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
    </div>

    <script>
        $('.company-id').click(function () {
            var company_id = $(this).attr('id');
            $('.company-id').css("background-color", "rgb(255, 255, 255)");
            $(this).css("background-color", "#60b3abad");
            $('.company-id').removeClass('secilen');
            $(this).addClass("secilen");
        });
        $(document).ready(function () {
            $("#button").hide();
        });
        $(".firma-sil").on("click", function () {
            var id = $(".secilen").attr("id");

            if (id == null) {
                alertify.warning("Lütfen Silmek İstediğiniz Kaydı Seçiniz...")
            } else {
                $.get("ajax/stok/stok-modal.php?islem=delete-company", {id: id}, function (getModal) {
                    $("#get-modals").html(getModal);
                });
            }
        });
        $(".firma-guncelle").click(function () {
            var id = $(".secilen").attr("id");
            if (id == null) {
                alertify.warning("Lütfen Güncellemek İstediğiniz Kaydı Seçiniz...");
            } else {
                $.get("ajax/stok/stok-modal.php?islem=update-company", {id: id}, function (getModal) {
                    $("#get-modals").html(getModal);
                });
            }
        });

        $(".yeni-firma-ekle").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=add-companies", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });

    </script>
<?php }

if ($islem == "get-invoices") {
    ?>
    <script>
        var table = $("#fatura-table").DataTable({
            responsive: true,
            scrollX: true,
            searching: false,
            paging: false,
            bLengthChange: false,
            scrollY: '35vh',
            info: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
        $(document).ready(function () {
            $(".yeni-fatura").prop("disabled", true);
            $(".fatura-duzenle").prop("disabled", true);
            var depo = $(".secilen-depo").val();
            if (depo != "Seçilmedi") {
                $(".yeni-fatura").prop("disabled", false);
                $(".fatura-duzenle").prop("disabled", false);
            }
        });

        $(".yeni-fatura").on("click", function () {
            $.get("ajax/stok/stok-modal.php?islem=yeni-fatura-modal", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });

        $(".fatura-duzenle").on("click", function () {
            var id = $(".first-select").attr("id");
            if (id == null || id == "") {
                alertify.warning("Lütfen Güncellemek İstediğiniz Faturayı Seçiniz...");
            } else {
                $.get("ajax/stok/stok-modal.php?islem=fatura-guncelle-modal", {id: id}, function (getModal) {
                    $("#get-modals").html(getModal);
                });
            }
        });


        $("body").off("click", "#faturadaki-urunleri-getir-modal").on("click", "#faturadaki-urunleri-getir-modal", function () {
            $.get("ajax/stok/stok-modal.php?islem=fatura-filtre-urun-getir", function (getTable) {
                $(".fatura-filtre-urun-getir").html(getTable);
            });
        });

    </script>

    <div class="buttons px-2 mt-1">
        <button class="btn btn-sm yeni-fatura" style="background-color: #60B3ABAD; color:white;">Yeni Fatura Oluştur
        </button>
    </div>
    <div class="row col-12">
        <div class="card">
            <div class="card-title text-center" style="font-weight: bold">Kriter Paneli</div>
        </div>
        <div class="col-1">
            <div class="form-group row mt-1">
                <div class="col-sm-12">
                    <input type="text" id="bas_tarih" class="form-control form-control-sm"
                           placeholder="Başlangıç Tarihi" onfocus="(this.type='date')">
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group mt-1">
                <div class="col-sm-12">
                    <input type="text" id="bit_tarih" class="form-control form-control-sm" placeholder="Bitiş Tarihi"
                           onfocus="(this.type='date')">
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group  mt-1">
                <div class="col-sm-12">
                    <div class="input-group">
                        <input type="text"
                               style="size: 10px"
                               placeholder="Firma Adı"
                               id="fatura-filtre-firma"
                               data-id=""
                               class="form-control form-control-sm company_name_receipt"
                               disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-warning btn-sm"
                                    id="fatura-firma-click"
                                    type="button"><i
                                        class="fa fa-ellipsis-h"
                                        aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="filtre-firma-getir"></div>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group  mt-1">
                <div class="col-sm-12">
                    <div class="input-group">
                        <input type="text"
                               placeholder="Ürün Adı"
                               id="fatura-filtre-urun-adi"
                               data-id=""
                               class="form-control form-control-sm fatura-filtre-urun-ad"
                               disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-warning btn-sm"
                                    id="faturadaki-urunleri-getir-modal"
                                    type="button"><i
                                        class="fa fa-ellipsis-h"
                                        aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fatura-filtre-urun-getir"></div>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group mt-1">
                <div class="col-sm-12">
                    <div class="input-group">
                        <input type="text"
                               placeholder="Depo Adı"
                               id="depo-filtre-firma"
                               class="form-control form-control-sm depo-adini-getir"
                               disabled>
                        <div class="input-group-append">
                            <button class="btn btn-outline-warning btn-sm"
                                    type="button"
                                    id="fatura-detay-depo"><i
                                        class="fa fa-ellipsis-h"
                                        aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="filtre-depo-getir"></div>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group mt-1">
                <div class="col-sm-12">
                    <select id="supply_type" class="form-select form-select-sm">
                        <option value="Seçiniz...">Tedarik Tür</option>
                        <option value="Yeni Yıl Devir">Yeni Yıl Devir</option>
                        <option value="Bağış">Bağış</option>
                        <option value="DMO">DMO</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group  mt-1">
                <div class="col-sm-12">
                    <select id="buying_method" class="form-select form-select-sm">
                        <option value="Seçiniz...">Alım Yöntem</option>
                        <option value="Doğrudan Temin">Doğrudan Temin</option>
                        <option value="Açık İhale">Açık İhale</option>
                        <option value="Pazarlık Usulü">Pazarlık Usulü</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-1">
            <div class="form-group row mt-1">
                <div class="col-sm-12">
                    <select id="hareket_tur" class="form-select form-select-sm">
                        <option value="Seçiniz...">Hareket Tür</option>
                        <option value="1">Giriş</option>
                        <option value="2">Çıkış</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-1 mt-1">
            <button class="btn btn-outline-danger btn-sm temizle mx-2">Temizle</button>
        </div>
        <div class="col-1 mt-1">
            <button class="btn btn-outline-success btn-sm" id="fatura-filtrele-buton">Filtrele</button>
        </div>
    </div>
    <div class="col-12 row">
        <div class="col-12 row mt-4">
            <div class="card">
                <div class="card-title text-center" style="font-weight: bold">Fatura Listesi</div>
                <div class="card-body">
                    <div class="row px-2">
                        <table class="table table-bordered  table-hover px-2 nowrap display w-100" id="fatura-table"
                               style="font-size: 12px;">
                            <thead class="table-light">
                            <tr>
                                <th>Fatura Tarihi</th>
                                <th>Fatura No</th>
                                <th>Tedarik Türü</th>
                                <th>Alım Yöntemi</th>
                                <th>Depo Adı</th>
                                <th>Hareket Türü</th>
                                <th>Firma Adi</th>
                                <th>Teslim Eden</th>
                                <th>Fatura Tutar</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody id="veriyi-bas">
                            <?php
                            $faturalari_cek = verilericoklucek("
                        SELECT
                            sr.id as id,
                            sr.insert_datetime as insert_datetime,
                            sr.supply_type as tedarik_tur,
                            sr.buying_method as alim_yontem,
                            sr.identifier_no as f_tanimlayici,
                            sr.tender_date as ihale_tarih,
                            sr.bill_date as fatura_tarihi,
                            sr.delivery_person_info as teslim_eden_kisi,
                            sr.move_type as hareket_tur,
                            c.company_name as firma_adi,
                            w.warehouse_name as depo_adi,
                            sr.total_price as total_price
                        FROM
                            stock_receipt as sr
                        INNER JOIN warehouses as w ON w.id=sr.warehouse_id
                        INNER JOIN companies as c ON c.id=sr.companyid
                        WHERE
                            sr.status=1");
                            foreach ($faturalari_cek as $fatura) {

                                $fatura_tarih = explode(" ", $fatura["insert_datetime"]);
                                $explode = explode("-", $fatura_tarih[0]);

                                $yıl = $explode[0];
                                $ay = $explode[1];
                                $gün = $explode[2];
                                $fatura_tarih_array = [$gün, $ay, $yıl];
                                $fatura_tarih_implode = implode("/", $fatura_tarih_array);
                                if ($fatura["hareket_tur"] == 1) {
                                    $hareket_tur = "Giriş";
                                } else {
                                    $hareket_tur = "Çıkış";
                                }
                                ?>
                                <tr id="kayit-<?php echo $fatura["id"]; ?>" data-id="<?php echo $fatura["id"]; ?>"
                                    class="secilen-fatura">
                                    <td><?php echo $fatura_tarih_implode ?></td>
                                    <td><?php echo $fatura["id"] ?></td>
                                    <td><?php echo $fatura["tedarik_tur"] ?></td>
                                    <td><?php echo $fatura["alim_yontem"] ?></td>
                                    <td><?php echo $fatura["depo_adi"] ?></td>
                                    <td><?php echo $hareket_tur ?></td>
                                    <td><?php echo $fatura["firma_adi"] ?></td>
                                    <td><?php echo $fatura["teslim_eden_kisi"] ?></td>
                                    <td><?php echo $fatura["total_price"] . " TL" ?></td>
                                    <td>
                                        <button class='btn btn-outline-warning btn-sm' id='receipt-edit-page'
                                                data-id="<?php echo $fatura["id"]?>">
                                            <i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>
                                        <button class='btn btn-outline-secondary btn-sm mx-1' id="test-1" data-id="<?php echo $fatura["id"]?>"><i
                                                    class='fa fa-eye' aria-hidden='true'></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" id="faturanin-bilgisini-getir">
        </div>
    </div>
    <script>

        // $("body").off("click",".yeni-filtre-alani tr").on("click",".yeni-filtre-alani tr",function (){
        //     alert("test");
        // });

        $("body").off("click", "#fatura-detay-depo").on("click", "#fatura-detay-depo", function () {
            $.get("ajax/stok/stok-modal.php?islem=fatura-depo-modal", function (getTable) {
                $(".filtre-depo-getir").html(getTable);
            });
        });
        $(".temizle").click(function () {
            $(".company_name_receipt").attr("data-id", "");
            $(".company_name_receipt").val("");
            $(".depo-adini-getir").val("");
            $(".depo-adini-getir").attr("data-id", "");
            $("#supply_type").val("Seçiniz...");
            $("#buying_method").val("Seçiniz...");
            $("#hareket_tur").val("Seçiniz...");
            $("#bas_tarih").val("");
            $("#bit_tarih").val("");
            $("#fatura-filtre-urun-adi").val("");
        });

        $("#fatura-firma-click").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=filtre-firma-modal", function (getModal) {
                $(".filtre-firma-getir").html(getModal);
            });
        });

        $("body").off("click", "#receipt-edit-page").on("click", "#receipt-edit-page", function (e) {
            var id = $(this).attr("data-id");
            $.get("ajax/stok/stok-tablo.php?islem=receipt-edit-table", {id: id}, function (getTable) {
                $("#get-tables").html(getTable);
            });
        });

        $("#fatura-filtrele-buton").click(function () {
            var firma_id = $("#fatura-filtre-firma").attr("data-id");
            var depo_id = $("#depo-filtre-firma").attr("data-id");
            var tedarik_turu = $("#supply_type").val();
            var alim_yontem = $("#buying_method").val();
            var hareket_tur = $("#hareket_tur").val();
            var bas_tarih = $("#bas_tarih").val();
            var bit_tarih = $("#bit_tarih").val();
            var urun_adi = $(".fatura-filtre-urun-ad").val();

            $.ajax({
                url: "ajax/stok/stok-sql.php?islem=fatura-filtre",
                type: "GET",
                data: {
                    firma_id: firma_id,
                    urun_adi: urun_adi,
                    depo_id: depo_id,
                    tedarik_turu: tedarik_turu,
                    alim_yontem: alim_yontem,
                    hareket_tur: hareket_tur,
                    bas_tarih: bas_tarih,
                    bit_tarih: bit_tarih
                },
                success: function (getResult) {
                    if (getResult == 2) {
                        alertify.warning("Kayıt Bulunamadı");
                    } else {
                        var json = JSON.parse(getResult);
                        table.clear();
                        json.forEach(function (item) {
                            var item_split = item.fatura_tarihi;

                            var myarr = item_split.split(" ");
                            var split_again = myarr[0].split("-");
                            var yil = split_again[0];
                            var ay = split_again[1];
                            var gun = split_again[2];
                            var implode_arr = [gun, ay, yil];
                            var receipt_date = implode_arr.join("/");

                            if (item.hareket_tur == 1) {
                                var move_type = "Giriş";
                            } else {
                                move_type = "Çıkış";
                            }
                            table.row.add(['<div class="yeni-filtre-alani" id="data-' + item.receipt_id + '">' + receipt_date + '</div>', item.receipt_id, item.tedarik_tur, item.alim_yontem, item.depo_adi, move_type, item.firma_adi, item.teslim_eden_kisi, item.toplam_fiyat + ' TL', "<td><button class='btn btn-outline-warning btn-sm' id='receipt-edit-page' data-id=" + item.receipt_id + "><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>" +
                            "<button class='btn btn-outline-secondary btn-sm mx-1 fatura-detayi-goster' data-id="+item.receipt_id+"><i class='fa fa-eye' aria-hidden='true'></i></button></td>"]).draw(false);
                        });
                    }
                }
            });
            $("body").off("click", ".fatura-detayi-goster").on("click", ".fatura-detayi-goster", function () {
                var id = $(this).attr("data-id");
                $.ajax({
                    url: "ajax/stok/stok-tablo.php?islem=fatura-detayi",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function (result) {
                        $("#faturanin-bilgisini-getir").html(result);
                    }
                });
            });
        });

        $("body").off("click","#test-1").on("click","#test-1",function (){
            var id = $(this).attr("data-id");
            $.ajax({
                url: "ajax/stok/stok-tablo.php?islem=fatura-detayi",
                type: "GET",
                data: {
                    id: id
                },
                success: function (result) {
                    $("#faturanin-bilgisini-getir").html(result);
                }
            });
        });
    </script>
<?php }


if ($islem == "malzeme-istek-liste") {
    $stock_type = $_GET['stock_type'];
    ?>
    <table id="AMELİYAT_MALZEME_LİSTESİ" class="table  table-bordered table-hover "
           style="background:white;width: 100%;">

        <thead>
        <tr>
            <th>Malzeme Kayıt No</th>
            <th>Barkod</th>
            <th>Malzeme Adı</th>
            <th>Depodaki Kutu Sayısı</th>
            <th>Stok Türü</th>
            <th>Son Kullanma Tarihi</th>
        </tr>
        </thead>

        <tbody>
        <?php $ameliyatgetir = verilericoklucek("select * from stock_receipt_move where stock_type='$stock_type' and status=1 ORDER BY expiration_date ASC ");
        foreach ($ameliyatgetir as $rowa2) { ?>
            <tr class="MALZEME_SEC" data-id=" <?php echo $rowa2["id"]; ?>" style="cursor: pointer;">
                <td class="stock_cardid">      <?php echo $rowa2["stock_cardid"]; ?></td>
                <td class="barcode">      <?php echo $rowa2["barcode"]; ?></td>
                <td class="stock_name">         <?php echo $rowa2["stock_name"]; ?></td>
                <td class="RECETE_TURU">      <?php echo $rowa2["stock_number_of_boxes"]; ?></td>
                <td class="stock_type">    <?php echo islemtanimgetirid($rowa2["stock_type"]); ?></td>
                <td class="expiration_date"><?php echo $rowa2["expiration_date"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>

        <tfoot>
        <tr>
            <th>Malzeme Kayıt No</th>
            <th>Barkod</th>
            <th>Malzeme Adı</th>
            <th>Depodaki Kutu Sayısı</th>
            <th>Stok Türü</th>
            <th>Son Kullanma Tarihi</th>
        </tr>
        </tfoot>

    </table>

    <script>
        $(document).ready(function () {
            $('#AMELİYAT_MALZEME_LİSTESİ').DataTable({
                "responsive": true,
                "paging": false,
                "scrollY": 300,
                "scrollX": false,
                "autoWidth": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });

//Seçilen Malzemeyi Ekle------------------------------------------------------------------------------------------------
            $(".MALZEME_SEC").off().on("click", function () {

                if ($(this).css('background-color') != 'rgb(57, 180, 150)') {
                    $('.malzeme-sec-kaldir').removeClass("text-white");
                    $('.malzeme-sec-kaldir').removeClass("malzeme-sec-kaldir");
                    $('.MALZEME_SEC').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(57, 180, 150)"});
                    $(this).addClass("text-white malzeme-sec-kaldir");
                    $('#MALZEME_KAYIT').removeAttr('disabled');
                } else {
                    $(this).css({"background-color": "rgb(255, 255, 255)"});
                    $(this).removeClass("text-white");
                    $(this).removeClass("malzeme-sec-kaldir");
                    $('#MALZEME_KAYIT').attr('disabled', 'disabled');
                }

            });

//Secilem Malzeme + Girilin Bilgi clk button----------------------------------------------------------------------------
            $("#MALZEME_KAYIT").off().on("click", function () {

                $(this).prop("disabled", true);

                $('#select_item_send').prop("disabled", false);

                var MALZEME_BILGILERI = $('.malzeme-sec-kaldir').attr("data-id");
                var STOK_ISTEK_HEKIM_ACIKLAMA = $('#STOK_ISTEK_HEKIM_ACIKLAMA').val();
                var MIKTAR = $('#requested_amount').val();
                var MALZEME_BILGISI = $('#material_info').val();
                var MALZEME_TALEP_ACILIYET_SEVIYESI = $('#urgency_status').val();
                var TALEP_NOTU = $("#description").val();

                $('textarea[name=STOK_ISTEK_HEKIM_ACIKLAMA').val('');
                $('select[name=OLCU_KODU').val('');
                $('input[name=MIKTAR').val('');
                $('textarea[name=MALZEME_BILGISI').val('');
                $('select[name=MALZEME_TALEP_ACILIYET_SEVIYESI').val('');

                var barcode = $(".malzeme-sec-kaldir").find(".barcode").html();
                var stock_type = $(".malzeme-sec-kaldir").find(".stock_type").html();
                var stock_cardid = $(".malzeme-sec-kaldir").find(".stock_cardid").html();
                var stock_name = $(".malzeme-sec-kaldir").find(".stock_name").html();
                var stock_type_number = $("#stock_type").val();

                $(".malzeme_kaydet_listele").append("<tr class='IDD' name='ID' id='" + MALZEME_BILGILERI + "'> <input type='hidden' " +
                    "name='malzemeler[]' stok-istek-hekim-aciklama='" + STOK_ISTEK_HEKIM_ACIKLAMA + "' malzeme-bilgisi='" + MALZEME_BILGISI + "' " +
                    "stock_cardid='" + stock_cardid + "'" + "miktar='" + MIKTAR + "' malzeme-aciliyet-seviyesi='" + MALZEME_TALEP_ACILIYET_SEVIYESI + "' description='" + TALEP_NOTU + "'" +
                    "stock-name='" + stock_name + "'" + "barcode='" + barcode + "'" + "stock-type='" + stock_type_number + "'" + "request-note='" + TALEP_NOTU + "'" +
                    "value='" + MALZEME_BILGILERI + "' /> " +
                    "<td> " + stock_cardid + "</td>   " +
                    "<td> " + stock_name + "</td>   " +
                    "<td> " + barcode + "</td>  " +
                    "<td> " + stock_type + "</td>" +
                    "<td> " + MIKTAR + " </td>  " +
                    "<td>" + MALZEME_TALEP_ACILIYET_SEVIYESI + "</td>" +
                    "<td>" + TALEP_NOTU + "</td>" +
                    "<td>" + MALZEME_BILGISI + "</td>" +
                    "<td> <button id='delete_malzeme' class='btn btn-danger delete' data-id='" + MALZEME_BILGILERI + "'  type='button'>Sil</button> </td> </tr>");
            });

            $(document).on('click', '#delete_malzeme', function () {
                $(this).closest("tr").remove();
                var MALZEME_ID = [];

                $("input[name='malzemeler[]']").off().each(function () {
                    MALZEME_ID.push($(this).val());
                });

                if (MALZEME_ID == '') {
                    $("#select_item_send").prop("disabled", true);
                }

            });

        });
    </script>

<?php }


if ($islem == "muadil-eklenecek-tablo-getir") {
    ?>
    <div class="buttons mx-1 mt-2">
        <button class="btn btn-sm stok-karti-ekle" style="background-color: #112D4E; color: white;">Yeni Stok Kartı
            Oluştur
        </button>
        <button class="btn btn-sm stok-karti-guncelle" style="background-color: #3F72AF; color: white;">Stok Kartını
            Güncelle
        </button>
        <button class="btn btn-sm stok-karti-sil" style="background-color: #e64848; color: white;">Stok Kartını Sil
        </button>
        <button class="btn btn-sm muadil-eklenecek-liste" style="background-color: #FF9551; color: white;">Muadil
            Eklenecekler
        </button>
    </div>

    <div class="row">
        <div class="col-sm-4 mt-4">
            <div class="card">
                <div class="card-header">
                    Muadil İlaç Ekleme
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-sm">Muadil</label>
                        <div class="col-sm-7">
                            <input type="text" data-id="test" disabled
                                   class="form-control form-control-sm eklenecek_muadil">
                        </div>
                        <div class="col-1">
                            <button class="btn btn-sm-warning" id="get-muadils"><i class="fa fa-ellipsis-h"
                                                                                   aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Malzeme Adı</label>
                        <div class="col-sm-7">
                            <input type="text" disabled class="form-control form-control-sm malzeme-adi">
                            <input type="hidden" disabled class="form-control form-control-sm malzeme-id">
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label-sm">Barkod No</label>
                        <div class="col-sm-7">
                            <input type="number" disabled class="form-control form-control-sm malzeme-barkod">
                        </div>
                    </div>
                    <br>

                    <div class="modal-footer">
                        <button class="btn btn-sm muadil-ekle-button" style="background-color: #112D4E; color: white;">
                            Muadil Ekle
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 mt-4">
            <table class="table table-sm  table-bordered table-hover px-2 nowrap display w-100 display nowrap"
                   id="muadil-table" style="background: white; width: 100%;">
                <thead class="table-light">
                <tr>
                    <th>Barkod</th>
                    <th>Ürün Adı</th>
                    <th>Üretici Firma</th>
                    <th>Ürün Tipi</th>
                    <th>Kutu Miktarı</th>
                    <th>Uyarılar</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $istekler = verilericoklucek(" SELECT * FROM stock_card WHERE status=1 AND stock_type=28464 AND stock_muadilid=0");
                foreach ($istekler as $item) {
                    ?>
                    <tr id="<?php echo $item["id"] ?>" class="muadil-edilecek-id">
                        <td id="muadil_barcode"
                            get-barcode="<?php echo $item["barcode"] ?>"><?php echo $item["barcode"] ?></td>
                        <td id="muadil_malzeme_adi"
                            get-stock-name="<?php echo $item["stock_name"] ?>"><?php echo $item["stock_name"] ?></td>
                        <td><?php echo $item["producting_brand"] ?></td>
                        <td><?php echo islemtanimgetirid($item["stock_type"]) ?></td>
                        <td><?php echo $item["stock_number_of_boxes"] ?></td>
                        <td><?php echo $item["stock_warnings"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <br><br>
            <div id="get-last-muadil"></div>
        </div>
    </div>


    <script>
        $("#muadil-table").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });


        $(".muadil-edilecek-id").click(function () {
            var barcode = $("#muadil_barcode", this).attr("get-barcode");
            var id = $(this).attr("id");
            var stock_name = $("#muadil_malzeme_adi", this).attr("get-stock-name");

            $(".malzeme-barkod").val(barcode);
            $(".malzeme-id").val(id);
            $(".malzeme-adi").val(stock_name);
            $(".muadil-edilecek-id").css("background-color", "rgb(255,255,255)");
            $(this).css("background-color", "#60b3abad");
        });

        $("#get-muadils").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=muadil-ilac-getir", function (getModal) {
                $("#get-modals").html(getModal);
            });
        });

        $(".muadil-ekle-button").click(function () {
            var id = $(".eklenecek_muadil").attr("data-id");
            var stock_id = $(".malzeme-id").val();

            $.ajax({
                url: "ajax/stok/stok-sql.php?islem=muadil-ekle-sql",
                type: "POST",
                data: {
                    id: id,
                    stock_id: stock_id
                },
                success: function (result) {
                    $(".sonucyaz").html(result);
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

if ($islem == "receipt-edit-table") {
    $id = $_GET["id"];

    $veri = tek("
        SELECT
            sr.*,
            sr.bill_date as fatura_tarihi,
            c.company_name as firma_adi,
            w.warehouse_name as depo_adi
        FROM
            stock_receipt as sr
        INNER JOIN warehouses as w ON w.id=sr.warehouse_id
        INNER JOIN companies as c ON c.id=sr.companyid
        WHERE
            sr.status=1
        AND
            sr.id='$id'");

    $fatura_tarihi = $veri["fatura_tarihi"];
    $explode = explode(" ", $fatura_tarihi);
    
    ?>
    <script>
        $("#fatura-kalem-table-detay").DataTable({
            responsive: true,
            scrollX: true,
            scrollY: "30vh",
            paging: false,
            length: 10,
            bLengthChange: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            }
        });
    </script>
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
    <div class="row">
        <input type="hidden" id="get-id" data-id="<?php echo $id ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-6 px-4">
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Tedarik Türü</label>
                        <div class="col-sm-7">
                            <select id="supply_type" class="form-select form-select-sm">
                                <option value="<?php echo $veri["supply_type"] ?>"><?php echo $veri["supply_type"] ?></option>
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
                                <option value="<?php echo $veri["buying_method"] ?>"><?php echo $veri["buying_method"] ?></option>
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
                                       class="form-control form-control-sm get-firm-for-receipt"
                                       data-id="<?php echo $veri["companyid"] ?>"
                                       id="company_id"
                                       aria-describedby="basic-addon2"
                                       value="<?php echo $veri["firma_adi"] ?>"
                                       disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">İhale Tarihi</label>
                        <div class="col-sm-7">
                            <input type="date" id="ihale-tarihi"
                                   class="form-control form-control-sm ihale_tarih"
                                   value="<?php echo $veri["tender_date"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">İhale Kayıt No</label>
                        <div class="col-sm-7">
                            <input type="text" id="ihale-kayit-no"
                                   class="form-control form-control-sm" value="<?php echo $veri["tender_number"]; ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">İrsaliye No</label>
                        <div class="col-sm-7">
                            <input type="text" id="waybill_number"
                                   class="form-control form-control-sm" value="<?php echo $veri["waybill_number"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">İrsaliye Tarih</label>
                        <div class="col-sm-7">
                            <input type="date" id="waybill_datetime"
                                   class="form-control form-control-sm" value="<?php echo $veri["waybill_datetime"] ?>">
                        </div>
                    </div>
                </div>
                <div class="col-6 px-4 mt-2">
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Fatura Tarihi</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control form-control-sm fatura-tarihi-guncelle"
                                   value="<?php echo $explode[0]; ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Fatura No</label>
                        <div class="col-sm-7">
                            <input type="text" id="fatura-numarasi"
                                   class="form-control form-control-sm" value="<?php echo $veri["bill_number"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Depo Adı</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control form-control-sm get-warehouse-for-update"
                                       data-id="<?php echo $veri["warehouse_id"] ?>"
                                       aria-describedby="basic-addon2"
                                       value="<?php echo $veri["depo_adi"] ?>"
                                       disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-warning btn-sm get-warehouse-button"
                                            type="button"><i
                                                class="fa fa-ellipsis-h"
                                                aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="depo-fatura-getir"></div>
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Firma Tanımlayıcı
                            No</label>
                        <div class="col-sm-7">
                            <input type="text"
                                   class="form-control form-control-sm identifier_no" disabled
                                   value="<?php echo $veri["identifier_no"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Teslim Eden Kişi</label>
                        <div class="col-sm-7">
                            <input type="text" id="delivery_person_info"
                                   class="form-control form-control-sm" disabled
                                   value="<?php echo $veri["delivery_person_info"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Teslim Eden Ünvanı</label>
                        <div class="col-sm-7">
                            <input type="text" id="delivery_person_title"
                                   class="form-control form-control-sm" disabled
                                   value="<?php echo $veri["delivery_person_title"] ?>">
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label class="col-sm-3 col-form-label-sm">Hareket Türü</label>
                        <div class="col-sm-7">
                            <select id="move_type" disabled class="form-select form-select-sm">
                                <?php
                                if ($veri["move_type"] == 1) {
                                    $move_type = "Giriş";
                                } else {
                                    $move_type = "Çıkış";
                                }
                                ?>
                                <option value="<?php echo $veri["move_type"] ?>"><?php echo $move_type ?></option>
                                <option value="Seçiniz...">Seçiniz...</option>
                                <option value="1">Giriş</option>
                                <option value="2">Çıkış</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="kalem-modal">
                    <div class="col-12 mt-4">
                        <table class="table table-bordered table-hover nowrap display w-100 dataTable no-footer dtr-inline"
                               id="fatura-kalem-table-detay"
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
                            <tbody>
                            <?php
                            $numara = 0;
                            $stok_kalemler = verilericoklucek(" SELECT * FROM stock_invoice_pen WHERE status=1 AND stock_receiptid='$id'");
                            foreach ($stok_kalemler as $kalem) {
                                $numara += 1;
                                ?>
                                <tr id="fatura-kalem-<?php echo $kalem["id"]; ?>">
                                    <td><?php echo $numara ?></td>
                                    <td><?php echo $kalem["barcode"] ?></td>
                                    <td><?php echo $kalem["stock_name"] ?></td>
                                    <td><?php echo $kalem["sale_price"] ?></td>
                                    <td><?php echo $kalem["stock_amount"] ?></td>
                                    <td><?php echo $kalem["unit"] ?></td>
                                    <td><?php echo $kalem["discount_percent"] ?></td>
                                    <td><?php echo $kalem["discount_total"] ?></td>
                                    <td><?php echo $kalem["kdv_percent"] ?></td>
                                    <td><?php echo $kalem["kdv_total"] ?></td>
                                    <td><?php echo $kalem["lot_number"] ?></td>
                                    <td><?php echo $kalem["ats_number"] ?></td>
                                    <td><?php echo $kalem["expiration_date"] ?></td>
                                    <td><?php echo $kalem["dealership_number"] ?></td>
                                    <td><?php echo islemtanimgetirid($kalem["stock_type"]) ?></td>
                                    <td>
                                        <button class="btn btn-outline-warning btn-sm kalem-duzenle"
                                                id="fatura-kalem-duzenle"
                                                data-id="<?php echo $kalem["id"]; ?>"><i class="fa fa-pencil"
                                                                                         aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
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
                                    <td class="toplam-adet-td"><?php echo $veri["total_amount"] ?></td>
                                    <td class="toplam-kdv-td"><?php echo $veri["total_kdv"] ?></td>
                                    <td class="toplam-iskonto-td"><?php echo $veri["total_discount"] ?></td>
                                    <td class="genel-toplam-td"><?php echo $veri["total_free_wat"] ?></td>
                                    <td class="toplam-fiyat-td"><?php echo $veri["total_price"] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button class="btn btn-outline-danger btn-sm" id="fatura-guncellemekten-vazgec">
                            Kapat
                            <i class="fa fa-times" aria-hidden="true"></i></button>
                        <button class="btn btn-outline-success btn-sm update-for-receipt mx-2">
                            Faturayı Güncelle
                            <i class="fa fa-check" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(".update-for-receipt").click(function () {
            var id = $("#get-id").attr("data-id");
            var supply_type = $("#supply_type").val();
            var buying_method = $("#buying_method").val();
            var tender_date = $("#ihale-tarihi").val();
            var tender_number = $("#ihale-kayit-no").val();
            var waybill_number = $("#waybill_number").val();
            var waybill_datetime = $("#waybill_datetime").val();
            var bill_date = $(".fatura-tarihi-guncelle").val();
            var bill_number = $("#fatura-numarasi").val();
            var warehouse_id = $(".get-warehouse-for-update").attr("data-id");

            $.ajax({
                url: "ajax/stok/stok-sql.php?islem=fatura-guncelle-total",
                type: "POST",
                data: {
                    id: id,
                    supply_type: supply_type,
                    buying_method: buying_method,
                    tender_date: tender_date,
                    tender_number: tender_number,
                    waybill_number: waybill_number,
                    waybill_datetime: waybill_datetime,
                    bill_date: bill_date,
                    bill_number: bill_number,
                    warehouse_id: warehouse_id
                },
                success: function (result) {
                    if (result == 404) {
                        alertify.error("Bilinmeyen Bir Hata Oluştu");
                    } else {
                        alertify.success("İşlem Başarılı");
                        $.get("ajax/stok/stok-tablo.php?islem=get-invoices", function (getVeri) {
                            $("#get-tables").html(getVeri);
                        });
                    }
                }
            });
        });

        $("#fatura-guncellemekten-vazgec").click(function () {
            alertify.warning("İşlemden Vazgeçtiniz")
            $.get("ajax/stok/stok-tablo.php?islem=get-invoices", function (getVeri) {
                $("#get-tables").html(getVeri);
            });
        });

        $("body").off("click", ".kalem-duzenle").on("click", ".kalem-duzenle", function (e) {
            var id = $(this).attr("data-id");
            $.get("ajax/stok/stok-modal.php?islem=fatura-kalem-duzenle-modal", {id: id,}, function (getModal) {
                $("#get-modals").html(getModal);
            });
        });

        $(".get-warehouse-button").click(function () {
            $.get("ajax/stok/stok-modal.php?islem=get-warehouse-from-modal", function (getModal) {
                $(".depo-fatura-getir").html(getModal);
            });
        });
    </script>

<?php }

if ($islem == "fatura-detayi") {
    $id = $_GET["id"];
    ?>
    <script>
        $(document).ready(function () {
            $('#detayi-getir-faturalar').DataTable({
                "responsive": true,
                scrollX: true,
                scrollY: "13vh",
                searching: false,
                paging: false,
                info: false,
                lengthMenu: false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });

    </script>
    <div class="col-12 row mt-4">
        <div class="card">
            <div class="card-title text-center" style="font-weight: bold"><?php echo $id ?> Nolu Faturanın Detayı</div>
            <table class="table table-bordered table-hover nowrap display w-100 dataTable no-footer dtr-inline"
                   id="detayi-getir-faturalar"
                   style="background: white; width: 100%;font-weight: normal;font-size: 12px">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Barkod</th>
                    <th>Ürün Adı</th>
                    <th>Birim Fiyat</th>
                    <th>Adet</th>
                    <th>M. Türü</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $numara = 0;
                $stok_kalemler = verilericoklucek(" SELECT * FROM stock_invoice_pen WHERE status=1 AND stock_receiptid='$id'");
                foreach ($stok_kalemler as $kalem) {
                    $numara += 1;
                    ?>
                    <tr id="fatura-kalem-<?php echo $kalem["id"]; ?>">
                        <td><?php echo $numara ?></td>
                        <td><?php echo $kalem["barcode"] ?></td>
                        <td><?php echo $kalem["stock_name"] ?></td>
                        <td><?php echo $kalem["sale_price"] ?></td>
                        <td><?php echo $kalem["stock_amount"] ?></td>
                        <td><?php echo islemtanimgetirid($kalem["stock_type"]) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>