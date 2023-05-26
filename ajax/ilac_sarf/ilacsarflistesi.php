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
$kullanicininidsi = $_SESSION["id"];
$hizlipaketlistele = yetkisorgula($kullanicininidsi, "hizlipaketlistele");
$hizlipaketolustur = yetkisorgula($kullanicininidsi, "hizlipaketolustur");


if ($islem == "hasta_ilacsarfi") {
    $protokolno = $_GET['protokolno'];
    $hastalarid = $_GET['hastalarid'];
    $kullanicibilgileri = singularactive("patient_registration", "protocol_number", $protokolno);
//    $protokolno=$kullanicibilgileri['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol", $protokolno);
    $izin = singularactive("patient_permission", "protocol_number", $protokolno);
    ?>
    <!--        hastaya eklenen ilaç sarf listesi-->
    <div>
        <table class="table table-bordered border-primary  w-100" id="ilacsarf<?php echo $protokolno; ?>">

            <thead>
            <tr>
                <th>#</th>
                <th>İ̇şlem Tarihi</th>
                <th>Barkod Numarası</th>
                <th>İlaç/Sarf Adı</th>
                <th>Malzeme Türü</th>
                <th>Malzeme Adeti</th>
                <th>Depo Adı</th>
                <th>Ücret</th>
                <th>İşlem Yapan Kullanıcı</th>
                <th>İ̇şlem</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $istemlersilyetkisi = yetkisorgula($kullanicininidsi, "istemlersilyetkisi");
            $hastaistemlerigetir = "select * from patient_stock_consumables where protocol_number='{$_GET["protokolno"]}' and  status='1' ";

            $hello = verilericoklucek($hastaistemlerigetir);
            foreach ($hello as $rowa) {

                $toplamadet = $toplamadet + $rowa["request_pcs"];
                $toplamucret = $toplamucret + $rowa["material_price"];


                ?>

                <tr>
                    <td><input type="checkbox" id="<?php echo $rowa['id']; ?>" class="checkboxilacsarf"></td>
                    <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                    <td><?php echo $rowa["barcode_code"]; ?></td>
                    <td><?php $stok_sarf = singularactive("stock_receipt_move", "id", $rowa["material_id"]);
                        echo $stok_sarf['stock_name']; ?></td>
                    <td><?php echo islemtanimgetirid($rowa["material_type"]); ?></td>
                    <td><?php echo $rowa["request_pcs"]; ?></td>
                    <td><?php $stok_sarf = singularactive("warehouses", "id", $rowa["warehouse_id"]);
                        echo $stok_sarf['warehouse_name']; ?></td>
                    <td><?php echo $rowa["material_price"]; ?> ₺</td>
                    <td><?php $istemiyapankullanicigetir = singular("users", "id", $rowa["insert_userid"]);
                        echo $istemiyapankullanicigetir["name_surname"]; ?></td>
                    <?php if ($rowa["insert_userid"] == $kullanicininidsi or $istemlersilyetkisi != "") { ?>
                        <td>
                            <button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"], $kullanicininidsi); ?>
                                    type="button"
                                    islem_id="<?php echo $rowa["id"]; ?>" class="ilacsarfsil btn sil-btn btn-sm">
                                <i class="fa fa-trash"></i></button>
                            <button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"], $kullanicininidsi); ?>
                                    type="button"
                                    islem_id="<?php echo $rowa["id"]; ?>" m_adet="<?php echo $rowa["request_pcs"]; ?>"
                                    class="ilacsarfduzenle btn up-btn btn-sm">
                                <i class="fa fa-edit"></i></button>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>
    <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">

        <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>Toplam Adet</th>
            <th>Toplam Ücret</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <tr class="text-danger fw-bold">
            <td>Toplam</td>
            <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $toplamadet; ?></td>
            <td><?php echo $toplamucret;
                echo " ";
                echo sistem_para_birimi; ?> </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>

    </table>

    <div class="modal fade bd-example-modal" id="ilacsarfmodal" role="dialog">
        <div class="modal-dialog" style=" width: 75%; max-width: 75%; ">
            <div id="ilacsarfmodal">

            </div>
        </div><!-- /.modal-dialog -->
    </div>

    <?php

    ?>

    <script>

        var count_701 = 0;
        $('#ilacsarf<?php echo $protokolno; ?>').DataTable({
            "scrollX": true,
            "scrollY": '30vh',
            "lengthChange": false,
            "pageLength": 20,
            "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [

                <?php if($hizlipaketolustur){ ?>
                {
                    text: 'İlaç/Sarf  Ekle',
                    className: 'btn yeni-btn btn-sm text-white mx-1',
                    titleAttr: 'Hastaya İlaç/Sarf Eklemek İçin Tıklayınız...',

                    action: function (e, dt, node, config) {
                        <?php if ($taburcu['discharge_status'] == 1){ ?>
                        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php } else if ($izin['id'] != ''){?>
                        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php }else{ ?>
                        var protokolno = "<?php echo $_GET["protokolno"]; ?>";
                        var hastalarid = "<?php echo $_GET["hastalarid"]; ?>";
                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=ilacsarfpaketolustur", {
                            protokolno: protokolno,
                            hastalarid: hastalarid
                        }, function (getveri) {
                            $('#ilacsarfmodal').html(getveri);
                        });
                        <?php } ?>
                    },
                    attr: {
                        <?php
                        $yetkivarmi = hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"], $kullanicininidsi);
                        if ($yetkivarmi == 'disabled'){ ?>
                        'disabled': "disabled",
                        <?php } ?>
                        <?php if ($taburcu['discharge_status'] != 1 && $izin['id'] == ''){ ?>
                        'data-bs-toggle': "modal",
                        'data-bs-target': "#ilacsarfmodal",
                        <?php } ?>
                    }
                }
                <?php } ?>
                , {
                    text: '<i class="fas fa-check"></i> Tümünü Seç',
                    className: 'btn btn-warning all-selecet',
                    titleAttr: 'Tümünü Seç',
                    action: function (e, dt, node, config) {

                        if (count_701 == 0) {
                            $('.checkboxilacsarf').prop("checked", true);
                            count_701 = 1;
                        } else {
                            $('.checkboxilacsarf').prop("checked", false);
                            count_701 = 0;
                        }

                        $('.sil-701').prop("disabled", false);

                    }
                }, {
                    text: '<i class="fas fa-trash"></i> Sil',
                    className: 'btn btn-danger sil-701',
                    titleAttr: 'Sil',
                    action: function (e, dt, node, config) {
                        var id = [];
                        $(".checkboxilacsarf:checked").off().each(function () {
                            id.push($(this).attr('id'));
                        });

                        var boyut = id.length
                        if (boyut > 0) {
                            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function () {

                                var delete_detail = $('#personel_delete_detail').val();

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarftopluiptal',
                                    data: {id: id, delete_detail: delete_detail},
                                    success: function (e) {
                                        $("#sonucyaz").html(e);
                                        var protokolno = "<?php echo $protokolno ?>";
                                        var hastalarid = "<?php echo $hastalarid ?>";
                                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {
                                            protokolno: protokolno,
                                            hastalarid: hastalarid
                                        }, function (getVeri) {
                                            $('.ilacsarfbody').html(getVeri);
                                        });

                                    }
                                });

                            }, function () {
                                alertify.warning('Silme işleminden Vazgeçtiniz')
                            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme işlemini Onayla"});

                        } else {
                            alertify
                                .alert("Lütfen silmek istediğiniz malzemeyi seçiniz..", function () {
                                    alertify.message('OK');
                                });
                        }
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $(".ilacsarfsil").click(function () {
            var id = $(this).attr('islem_id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function () {

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfiptal',
                    data: {id: id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var protokolno = "<?php echo $protokolno ?>";
                        var hastalarid = "<?php echo $hastalarid ?>";
                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {
                            protokolno: protokolno,
                            hastalarid: hastalarid
                        }, function (getVeri) {
                            $('.ilacsarfbody').html(getVeri);
                        });

                    }
                });

            }, function () {
                alertify.warning('Silme işleminden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme işlemini Onayla"});
        });
        $(".ilacsarfduzenle").click(function () {
            var id = $(this).attr('islem_id');
            var m_adet = $(this).attr('m_adet');
            $('#personel_delete_detail').val(m_adet);
            alertify.confirm("" +
                "<label class='form-label'>Malzeme Miktarı</label>" +
                "<input class='form-control' type='text' id='personel_delete_detail' >" +
                "", function () {

                var m_value = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfdüzenle',
                    data: {id: id, m_value: m_value},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var protokolno = "<?php echo $protokolno ?>";
                        var hastalarid = "<?php echo $hastalarid ?>";
                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {
                            protokolno: protokolno,
                            hastalarid: hastalarid
                        }, function (getVeri) {
                            $('.ilacsarfbody').html(getVeri);
                        });

                    }
                });

            }, function () {
                alertify.warning('işlemden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Malzeme Düzenle"});
        });
    </script>

<?php }
if ($islem == 'ilacsarfpaketolustur') {
    $hastalarid = $_GET['hastalarid'];
    $protokolno = $_GET['protokolno'];
    ?>

    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">İlaç/Sarf Listesi</h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
            <div class="card-body">
                <!--                ilaç sarf ekleme sayfası-->
                <div class="row">
                    <div class="col-md-6">
                        <script>
                            function showresults(str) {
                                $(".ilacsarfgetir").html('');
                                if (str.length >= 2) {
                                    $.ajax({
                                        url: 'ajax/ilac_sarf/ilacsarflistesi.php?islem=ilacsarfara',
                                        type: 'GET',
                                        data: {q: str},
                                        success: function (data) {
                                            try {
                                                var json = JSON.parse(data);
                                                json.forEach((item) => {
                                                    $(".ilacsarfgetir").append("<tr class='request-stock' data-id-9='" + item.barcode_code + "'> " +
                                                        "<td> " + item.barcode + " </td>" +
                                                        "<td> " + item.stock_name + " </td> " +
                                                        "<td> " + item.definition_name + " </td> " +
                                                        "<td> " + item.warehouse_name + " </td> " +
                                                        "<td > " + item.stock_amount + " </td>" +
                                                        "<td > " + item.sale_unit_price + " ₺</td>" +
                                                        "<td> <button class='ilacsarfistem btn up-btn waves-effect waves-light' title='Hastaya ilaç/sarf eklemek için tıklayınız..' " +
                                                        "m_name='" + item.stock_name + "'m_depo='" + item.warehouse_id + "'   " +
                                                        "m_fiyat='" + item.sale_unit_price + "' " +
                                                        "m_type_name='" + item.definition_name + "' " +
                                                        "m_type='" + item.stock_type + "'" + "m_id='" + item.id + "' m_code='" + item.barcode + "' " +
                                                        "m_tarih='" + item.expiration_date + "' m_tarih='" + item.malzeme_tarih + "'" + "' m_toplamAdet='" + item.stock_amount + "'" +
                                                        "type='button'>Ekle</button> </td> </tr>");
                                                })
                                            } catch (err) {

                                            }

                                        }
                                    });
                                } else {
                                    $.ajax({
                                        url: 'ajax/ilac_sarf/ilacsarflistesi.php?islem=ilacsarfarabos',
                                        type: 'GET',
                                        data: {q: str},
                                        success: function (data) {
                                            try {
                                                var json = JSON.parse(data);
                                                json.forEach((item) => {
                                                    $(".ilacsarfgetir").append("<tr class='request-stock' data-id-9='" + item.barcode_code + "'> " +
                                                        "<td> " + item.barcode + " </td>" +
                                                        "<td> " + item.stock_name + " </td> " +
                                                        "<td> " + item.definition_name + " </td> " +
                                                        "<td> " + item.warehouse_name + " </td> " +
                                                        "<td > " + item.stock_amount + " </td>" +
                                                        "<td > " + item.sale_unit_price + " ₺</td>" +
                                                        "<td> <button class='ilacsarfistem btn up-btn waves-effect waves-light' title='Hastaya ilaç/sarf eklemek için tıklayınız..'" +
                                                        "m_name='" + item.stock_name + "'m_depo='" + item.warehouse_id + "'   " +
                                                        "m_fiyat='" + item.sale_unit_price + "' " +
                                                        "m_type_name='" + item.definition_name + "' " +
                                                        "m_type='" + item.stock_type + "'" + "m_id='" + item.id + "' m_code='" + item.barcode + "' " +
                                                        "m_tarih='" + item.expiration_date + "' m_tarih='" + item.malzeme_tarih + "'" + "' m_toplamAdet='" + item.stock_amount + "'" +
                                                        "type='button'>Ekle</button> </td> </tr>");
                                                })
                                            } catch (err) {

                                            }

                                        }
                                    });
                                }


                            }
                        </script>
                        <div class="card ">
                            <h5 class="card-title p-1 text-white">İlaç/Sarf Listesi</h5>
                            <input autocomplete="off" onkeyup="showresults(this.value)" required type="text"
                                   class="form-control"
                                   placeholder="ilaç/sarf  adıyla arayınız"
                                   name="ilac_sarf_adi" id="basicpill-firstname-input">


                            <table class="table table-striped table-bordered" id="sarfilactable1">
                                <thead>
                                <tr>
                                    <th>Barkod Numarası</th>
                                    <th>İlaç/Sarf Adı</th>
                                    <th>Malzeme Türü</th>
                                    <th>Depo Adı</th>
                                    <th>Depo Adet</th>
                                    <th>Ücret</th>
                                    <th>İ̇şlem</th>
                                </tr>
                                </thead>

                                <tbody class="ilacsarfgetir">
                                <script>
                                    function createTable(jsonData) {
                                        $(document).ready(function () {
                                            $('#sarfilactable1').dataTable({
                                                "data": jsonData,
                                                "scrollY": '200px',
                                                "bFilter": false,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                },
                                                "scrollCollapse": true,
                                                "paging": false,
                                                "columns": [
                                                    {data: 'barcode'},
                                                    {data: 'stock_name'},
                                                    {data: 'definition_name'},
                                                    {data: 'warehouse_name'},
                                                    {data: 'stock_amount'},
                                                    {data: 'sale_unit_price'},
                                                    {
                                                        data: null,
                                                        render: function (data, type, row) {
                                                            return '<button class="ilacsarfistem btn up-btn waves-effect waves-light" title="Hastaya ilaç/sarf eklemek için tıklayınız.." ' +
                                                                "m_name='" + row.stock_name + "'m_depo='" + row.warehouse_id + "'   " +
                                                                "m_fiyat='" + row.sale_unit_price + "' " +
                                                                "m_type_name='" + row.definition_name + "' " +
                                                                "m_type='" + row.stock_type + "'" + "m_id='" + row.id + "' m_code='" + row.barcode + "' " +
                                                                "m_tarih='" + row.expiration_date + "' m_tarih='" + row.malzeme_tarih + "'" + "' m_toplamAdet='" + row.stock_amount + "' " +
                                                                ' >Ekle</button>';
                                                        }
                                                    }

                                                ]
                                            });
                                        });
                                    }


                                    var str = '';
                                    $(".ilacsarfgetir").html('');
                                    $.ajax({
                                        url: 'ajax/ilac_sarf/ilacsarflistesi.php?islem=ilacsarfarabos',
                                        type: 'GET',
                                        data: {q: str},
                                        success: function (data) {
                                            try {
                                                var json = JSON.parse(data);
                                                createTable(json);

                                            } catch (err) {

                                            }

                                        }
                                    });
                                </script>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card ">
                            <h5 class="card-title  p-1 text-white">Hastaya Eklenecek Paket Listesi</h5>
                            <div id="paketlistebody">
                                <script>
                                    $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=paketlistebody", {}, function (getveri) {
                                        $('#paketlistebody').html(getveri);
                                    });
                                </script>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mt-2">
                    <div class="card">
                        <h5 class="card-title  p-1 text-white">Hastaya Eklenecek İlaç/Sarflar Listesi</h5>
                        <div class="hastasarfgörlistesi">

                        </div>
                        <script>
                            $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hastailacsarf", {}, function (getVeri) {
                                $('.hastasarfgörlistesi').html(getVeri);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input class="btn kps-btn w-md justify-content-end "
                   type="hidden" id="stoksarfpaketup" value="Paket Düzenle">
            <input class="btn kps-btn w-md justify-content-end stoksarfpaket"
                   type="submit" value="Paket Oluştur">
            <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>
            <button class="btn up-btn w-md justify-content-end stoksarfbtn"
                    type="submit" data-bs-dismiss="modal">Kaydet
            </button>
        </div>
    </div><!-- /.modal-content -->

    <div id="paket-content" style="display: none">
        <label class="form-label">Paket Adı</label>
        <input class="form-control" type="text" id="paketadi">
        <label class="form-label">Paket Detay</label>
        <textarea class="form-control" type='text' id="paketdetay"></textarea>
    </div>


    <script>


        $(".stoksarfbtn").off().on("click", function () {


            var m_id_value = [];
            var m_kod_value = [];
            var m_depo_value = [];
            var m_type_value = [];
            var m_fiyat_value = [];
            var malzeme_adet_value = [];
            var malzeme_aciklama_value = [];
            var m_tarih_value = [];
            var protokolno = "<?php echo $protokolno ?>";
            var hastalarid = "<?php echo $hastalarid ?>";


            $("input[name='stoksarfinput[]']").off().each(function () {
                m_id_value.push($(this).attr('m_id'));
                m_kod_value.push($(this).attr('m_code'));
                m_depo_value.push($(this).attr('m_depo'));
                m_type_value.push($(this).attr('m_type'));
                m_fiyat_value.push($(this).attr('m_fiyat'));
                malzeme_adet_value.push($(this).attr('malzeme_adet'));
                malzeme_aciklama_value.push($(this).attr('malzeme_aciklama'));
                m_tarih_value.push($(this).attr('m_tarih'));
            });

            $.ajax({
                type: 'POST',
                url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfekle',
                data: {
                    material_id: m_id_value,
                    barcode_code: m_kod_value,
                    warehouse_id: m_depo_value,
                    material_type: m_type_value,
                    material_price: m_fiyat_value,
                    request_pcs: malzeme_adet_value,
                    request_detail: malzeme_aciklama_value,
                    expiration_date: m_tarih_value,
                    protocol_number: protokolno,
                    patient_id: hastalarid
                },
                success: function (e) {

                    $("#sonucyaz").html(e);
                    var protokolno = "<?php echo $protokolno ?>";
                    var hastalarid = "<?php echo $hastalarid ?>";

                    $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {
                        protokolno: protokolno,
                        hastalarid: hastalarid
                    }, function (getVeri) {
                        $('.ilacsarfbody').html(getVeri);
                    });


                }

            });

        });

        $(".stoksarfpaket").click(function () {
            alertify.confirm($("#paket-content").html(), function () {
                var paketadi = $('.ajs-modal').find('#paketadi').val();
                var paketdetay = $('.ajs-modal').find('#paketdetay').val();

                var userid = "<?php echo $kullanicininidsi; ?>"
                var m_id_value = [];
                var m_kod_value = [];
                var m_depo_value = [];
                var m_type_value = [];
                var m_fiyat_value = [];
                var malzeme_adet_value = [];
                var malzeme_aciklama_value = [];
                var m_tarih_value = [];
                $("input[name='stoksarfinput[]']").off().each(function () {
                    m_id_value.push($(this).attr('m_id'));
                    m_kod_value.push($(this).attr('m_code'));
                    m_depo_value.push($(this).attr('m_depo'));
                    m_type_value.push($(this).attr('m_type'));
                    //m_fiyat_value.push($(this).attr('m_fiyat'));
                    malzeme_adet_value.push($(this).attr('malzeme_adet'));
                    malzeme_aciklama_value.push($(this).attr('malzeme_aciklama'));
                    m_tarih_value.push($(this).attr('m_tarih'));
                });

                $.ajax({
                    type: 'POST',
                    url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfpaketolustur',
                    data: {
                        material_id: m_id_value,
                        barcode_code: m_kod_value,
                        warehouse_id: m_depo_value,
                        material_type: m_type_value,
                        request_pcs: malzeme_adet_value,
                        request_detail: malzeme_aciklama_value,
                        package_name: paketadi,
                        package_detail: paketdetay,
                        user_package: userid
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=paketlistebody", {}, function (getveri) {
                            $('#paketlistebody').html(getveri);
                            $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hastailacsarf", {}, function (Veri) {
                                $('.hastasarfgörlistesi').html(Veri);
                            });
                        });
                    }
                });

            }, function () {
                alertify.warning('işlemden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Paket Detay"});


        });

        $(".ilacsarfpaketistem").on("click", function () { // buton idli elemana tiklandiğinda
            var protokolno = $(this).attr('protokolno');
            var paketadi = $(this).attr('paketadi');
            var hastaid = $(this).attr('hastaid');
            $.ajax({
                type: 'POST',
                url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=hasta_paket_ilacsarfi',
                data: {"paketadi": paketadi, "protokolno": protokolno, "hastaid": hastaid},
                success: function (e) {
                    $('#sonucyaz').html(e);


                }
            });

        });


        $("#stoksarfpaketup").click(function () {
            var userid = "<?php echo $kullanicininidsi; ?>"
            var m_id_value = [];
            var m_kod_value = [];
            var m_depo_value = [];
            var m_type_value = [];
            var malzeme_adet_value = [];
            var malzeme_aciklama_value = [];
            var p_adi_value = $("input[name='stoksarfinput[]']").attr('paketadi');
            var p_detay_value = $("input[name='stoksarfinput[]']").attr('paketdetay');
            $("input[name='stoksarfinput[]']").off().each(function () {
                m_id_value.push($(this).attr('m_id'));
                m_kod_value.push($(this).attr('m_code'));
                m_depo_value.push($(this).attr('m_depo'));
                m_type_value.push($(this).attr('m_type'));
                malzeme_adet_value.push($(this).attr('malzeme_adet'));
                malzeme_aciklama_value.push($(this).attr('malzeme_aciklama'));
                // p_adi_value.push($(this).attr('paketadi'));
                // p_detay_value.push($(this).attr('paketdetay'));

            });

            $.ajax({
                type: 'POST',
                url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfpaketduzenle',
                data: {
                    material_id: m_id_value,
                    barcode_code: m_kod_value,
                    warehouse_id: m_depo_value,
                    material_type: m_type_value,
                    request_pcs: malzeme_adet_value,
                    request_detail: malzeme_aciklama_value,
                    package_name: p_adi_value,
                    package_detail: p_detay_value,
                    user_package: userid
                },
                success: function (e) {
                    $("#sonucyaz").html(e);
                    $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=paketlistebody", {}, function (getveri) {
                        $('#paketlistebody').html(getveri);
                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hastailacsarf", {}, function (Veri) {
                            $('.hastasarfgörlistesi').html(Veri);
                        });
                    });

                }
            });
        });
    </script>
<?php } elseif ($islem == "paketlistebody") { ?>
    <table id="sarfilactable3" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Paket Adı</th>
            <th>Paket Açıklama</th>
            <th>İ̇şlem</th>
        </tr>
        </thead>

        <tbody>

        <?php
        $kullanici_id = $_SESSION['id'];
        $sql = "SELECT package_name,package_detail FROM medicine_consumables_package WHERE user_package={$kullanici_id} 
                                                                       AND status=1 GROUP BY package_name,package_detail";

        $hello = verilericoklucek($sql);
        foreach ($hello as $row) { ?>

            <tr>

                <th><?php echo $row["package_name"]; ?></th>
                <th><?php echo $row["package_detail"]; ?></th>
                <th>
                    <button type="button" class="ilacsarfpaketistem1 btn kps-btn waves-effect waves-light btn-sm"
                            title="Hastaya paket eklemek için tıklayın.."
                            protokolno="<?php echo $protokolno; ?>" paketadi="<?php echo $row["package_name"]; ?>"
                            hastaid="<?php echo $hastalarid; ?>">
                        <i class="fa-solid fa-plus"></i></button>

                    <button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"], $kullanicininidsi); ?>
                            type="button" title="Paket düzenlemek için tıklayınız.."
                            paketadi="<?php echo $row["package_name"]; ?>"
                            class="ilacsarfpaketduzenle btn up-btn btn-sm">
                        <i class="fa fa-edit"></i></button>

                    <button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"], $kullanicininidsi); ?>
                            type="button" title="Paket silmek için tıklayınız.."
                            paketadi="<?php echo $row["package_name"]; ?>" class="ilacsarfpaketsil btn sil-btn btn-sm">
                        <i class="fa fa-trash"></i></button>
                </th>

            </tr>
        <?php } ?>
        </tbody>
    </table>


    <script>
        $('#sarfilactable3').DataTable({
            "responsive": true,
            "scrollY": '230px',
            "scrollCollapse": true,
            "paging": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            dom: '<"clear">lfrtip',
        });


        var dizi = [];
        $(".ilacsarfpaketistem1").on("click", function () { // buton idli elemana tiklandiğinda
            var paketadi = $(this).attr('paketadi');
            $.ajax({
                url: "ajax/ilac_sarf/ilacsarfislem.php?islem=get-json",
                type: "GET",
                data: {"paketadi": paketadi},
                success: function (getVeri) {
                    var json = JSON.parse(getVeri);

                    json.forEach(function (item){
                        var varmi = item.barcode_code;
                        var classadi = 'adet-' + varmi;
                        // console.log("item"+varmi)

                        if (t.data().count() == 0) {
                            // alert( 'Empty table' );
                            dizi.push(varmi);

                            t.row.add(["<input type='hidden' name='stoksarfinput[]' class='" + classadi + "' m_depo='" + item.warehouse_id + "' m_id='" + item.material_id + "' " +"' paketadi='" + item.package_name + "' " +
                            "m_code='" + item.barcode_code + "'"+"m_fiyat='" + item.malzeme_fiyat + "'"+"m_type='" + item.material_type + "'"+ "'   paketdetay='" + item.package_detail + "' " +
                            "malzeme_adet='" + item.request_pcs + "' malzeme_aciklama='"+item.request_detail+"' m_tarih='"+item.malzeme_tarih+"' />"+
                            item.barcode_code,item.malzeme_adi,item.malzeme_tip,"<p class='"+item.barcode_code+"'>"+item.request_pcs+"</p>",item.malzeme_fiyat,
                                "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + item.material_id + "' type='button'>Sil</button>"]).draw(false);
                        }else{
                            if(jQuery.inArray(varmi, dizi)!== -1) {
                                if (confirm("İlaç/sarf liste içersinde var tekrar eklensin mi?")) {
                                    dizi.push(varmi);

                                    var toplam=$("."+item.barcode_code).html();
                                    var toplam_m=parseInt(toplam) + parseInt(item.request_pcs);
                                    $("."+item.barcode_code).html(toplam_m);
                                    $("."+classadi).attr("malzeme_adet",toplam_m);
                                    console.log(toplam_m);
                                    // t.row.add(["<input type='hidden' class='" + classadi + "' name='stoksarfinput[]' m_depo='" + item.warehouse_id + "' m_id='" + item.material_id + "' " +"' paketadi='" + item.package_name + "' " +
                                    // "m_code='" + item.barcode_code + "'"+"m_fiyat='" + item.malzeme_fiyat + "'"+"m_type='" + item.material_type + "'"+ "'   paketdetay='" + item.package_detail + "' " +
                                    // "malzeme_adet='" + toplam_m + "' malzeme_aciklama='"+item.request_detail+"' m_tarih='"+item.malzeme_tarih+"' />"+
                                    // item.barcode_code,item.malzeme_adi,item.malzeme_tip,toplam_m,item.malzeme_fiyat,
                                    //     "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + item.material_id + "' type='button'>Sil</button>"]).draw(false);
                                    // setTimeout(function(){
                                    //     $("."+classadi).first().closest("tr").remove();
                                    //
                                    // }, 100);


                                    //console.log("delete: "+metin);

                                }
                            } else {
                                dizi.push(varmi);
                                t.row.add(["<input type='hidden' name='stoksarfinput[]' class='" + classadi + "' m_depo='" + item.warehouse_id + "' m_id='" + item.material_id + "' " +"' paketadi='" + item.package_name + "' " +
                                "m_code='" + item.barcode_code + "'"+"m_fiyat='" + item.malzeme_fiyat + "'"+"m_type='" + item.material_type + "'"+ "'   paketdetay='" + item.package_detail + "' " +
                                "malzeme_adet='" + item.request_pcs + "' malzeme_aciklama='"+item.request_detail+"' m_tarih='"+item.malzeme_tarih+"' />"+
                                item.barcode_code,item.malzeme_adi,item.malzeme_tip,"<p class='"+item.barcode_code+"'>"+item.request_pcs+"</p>",item.malzeme_fiyat,
                                    "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + item.material_id + "' type='button'>Sil</button>"]).draw(false);
                               }
                   }
                });
                //console.log("içerik "+dizi);
                $("#stoksarfpaketup").attr("type", "hidden");
                $(".stoksarfpaket").attr("type", "hidden");
            }
            });

        })
        ;

        $(".ilacsarfpaketsil").click(function () {
            var paketadi = $(this).attr('paketadi');

            alertify.confirm("<div class='alert alert-danger'>Silme için emin mısınız</div>", function () {

                $.ajax({
                    type: 'POST',
                    url: 'ajax/ilac_sarf/ilacsarfislem.php?islem=ilacsarfpaketiptal',
                    data: {paketadi: paketadi},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=paketlistebody", {}, function (getveri) {
                            $('#paketlistebody').html(getveri);
                            // liste temizleme
                            $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hastailacsarf", {}, function (Veri) {
                                $('.hastasarfgörlistesi').html(Veri);
                            });
                        });

                    }
                });

            }, function () {
                alertify.warning('Silme işleminden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme işlemini Onayla"});
        });


        $(".ilacsarfpaketduzenle").off().on("click", function () {
            var paketadi = $(this).attr('paketadi');
            // $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hastailacsarf", {}, function (Veri) {
            //     $('.hastasarfgörlistesi').html(Veri);
            // });
            $.ajax({
                url: "ajax/ilac_sarf/ilacsarfislem.php?islem=get-json",
                type: "GET",
                data: {"paketadi": paketadi},
                success: function (getVeri) {
                    var json = JSON.parse(getVeri);
                    json.forEach((item) => {
                        // $("#sarfilacistemtab").append("<tr class='request-stock' data-id-9='" + item.barcode_code + "'> " +
                        //     "<input type='hidden' name='stoksarfinput[]' m_depo='" + item.warehouse_id + "' m_id='" + item.material_id + "'   paketdetay='" + item.package_detail + "' " +
                        //     "m_code='" + item.barcode_code + "'"+"m_type='" + item.material_type + "' paketadi='" + item.package_name + "' " +
                        //     "malzeme_adet='" + item.request_pcs + "' malzeme_aciklama='"+item.request_detail+"' />" +
                        //     "<td> " + item.barcode_code + " </td>" +
                        //     "<td> " + item.malzeme_adi + " </td> " +
                        //     "<td> " + item.malzeme_tip + " </td> " +
                        //     "<td > " + item.request_pcs + " </td>" +
                        //     "<td > " + item.malzeme_fiyat + " ₺ </td>" +
                        //     "<td> <button class='btn btn-danger delete_istek' data-id-10='" + item.material_id + "' type='button'>Sil</button> </td> </tr>");

                        t.row.add(["<input type='hidden' name='stoksarfinput[]' m_depo='" + item.warehouse_id + "' m_id='" + item.material_id + "'   paketdetay='" + item.package_detail + "' " +
                        "m_code='" + item.barcode_code + "'" + "m_type='" + item.material_type + "' paketadi='" + item.package_name + "' " +
                        "malzeme_adet='" + item.request_pcs + "' malzeme_aciklama='" + item.request_detail + "' />" +
                        item.barcode_code, item.malzeme_adi, item.malzeme_tip, item.request_pcs, item.malzeme_fiyat,
                            "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + item.material_id + "' type='button'>Sil</button>"]).draw(false);
                    })

                    $("#stoksarfpaketup").attr("type", "button");
                    $(".stoksarfpaket").attr("type", "hidden");
                    // $("#stoksarfpaketup").text("Düzenle");
                    // $("#stoksarfpaketup").removeClass('stoksarfpaket');
                }
            });
        });
    </script>

<?php }
if ($islem == "ilacsarfara") {
    $q = $_GET["q"];

    $hastaistemlerigetir = "select stock_receipt_move.*,transaction_definitions.definition_name,warehouse_name from stock_receipt_move
    inner join  transaction_definitions on stock_receipt_move.stock_type=transaction_definitions.id
    inner join  warehouses on stock_receipt_move.warehouse_id=warehouses.id where   (( lower(stock_name) like '%$q%' or  upper(stock_name) like '%$q%' ) or 
  (lower(barcode) like '%$q%' or upper(barcode) like '%$q%')) and  stock_receipt_move.status='1' and stock_receipt_move.warehouse_id=7 order by stock_name fetch first 7 rows only";

    $hello = verilericoklucek($hastaistemlerigetir);

    $json = json_encode($hello);
    echo $json;

}

if ($islem == "ilacsarfarabos") {
    $q = $_GET["q"];

    $hastaistemlerigetir = "select stock_receipt_move.*,transaction_definitions.definition_name,warehouse_name from stock_receipt_move
    inner join  transaction_definitions on stock_receipt_move.stock_type=transaction_definitions.id
    inner join  warehouses on stock_receipt_move.warehouse_id=warehouses.id
where stock_receipt_move.status='1' and stock_receipt_move.warehouse_id=7 order by stock_name fetch first 7 rows only";

    $hello = verilericoklucek($hastaistemlerigetir);

    $json = json_encode($hello);
    echo $json;

}
if ($islem == "hastailacsarf") { ?>
    <table id="sarfilactable2" class="table table-striped table-bordered w-100">
        <thead>
        <tr>
            <th>Barkod Numarası</th>
            <th>İlaç/Sarf Adı</th>
            <th>Malzeme Türü</th>
            <th>Adet</th>
            <th>Ücret</th>
            <th>İ̇şlem</th>
        </tr>
        </thead>

        <tbody class="sarfilacistemtab" id="sarfilacistemtab">
        <script>

            var t = $('#sarfilactable2').DataTable({
                "responsive": true,
                "scrollY": '200px',
                "scrollCollapse": true,
                "paging": false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "lengthChange": false,
                "dom": '<"clear">lfrtip',
            });


            $("body").off("click", ".ilacsarfistem").on("click", ".ilacsarfistem", function (e) {
                $('#malzeme_adet').val('');
                $('#malzeme_aciklama').val('');
                var m_id = $(this).attr('m_id');
                var m_code = $(this).attr('m_code');
                var m_name = $(this).attr('m_name');
                var m_type = $(this).attr('m_type');
                var m_tarih = $(this).attr('m_tarih');
                var m_toplamAdet = $(this).attr('m_toplamAdet');
                var m_fiyat = $(this).attr('m_fiyat');
                var m_depo = $(this).attr('m_depo');
                var m_type_name = $(this).attr('m_type_name');

                var varmi = m_code;
                // console.log("item"+varmi)
                if (t.data().count() == 0) {
                    // alert( 'Empty table' );
                    dizi.push(varmi);
                    alertify.confirm("" +
                        "<label class='form-label'>Malzeme Adet</label><input class='form-control' type='text' id='malzeme_adet' placeholder='Malzeme Adet giriniz...'>" +
                        "<labeel class='form-label'>Malzeme Açıklama</labeel><input class='form-control' type='text' id='malzeme_aciklama' placeholder='Malzeme açıklama giriniz...'>" +
                        "", function () {

                        var malzeme_adet = $('#malzeme_adet').val();
                        var malzeme_aciklama = $('#malzeme_aciklama').val();


                        t.row.add(["<input type='hidden' name='stoksarfinput[]' class='adet-" + m_code + "' m_depo='" + m_depo + "' m_id='" + m_id + "' " +
                        "m_code='" + m_code + "'" + "m_fiyat='" + m_fiyat + "'" + "m_type='" + m_type + "'" +
                        "malzeme_adet='" + malzeme_adet + "' malzeme_aciklama='" + malzeme_aciklama + "' m_tarih='" + m_tarih + "' />" +
                        m_code, m_name, m_type_name,"<p class='"+m_code+"'>"+malzeme_adet+"</p>", m_fiyat,
                            "<button class='btn btn-danger delete_istek adet" + m_code + "' title='Silmek için tıklayınız..' data-id-10='" + m_id + "' type='button'>Sil</button>"]).draw(false);

                    }, function () {
                        alertify.warning('işlemden Vazgeçtiniz')
                    }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Malzeme  Detay"});
                } else {
                    if (jQuery.inArray(varmi, dizi) !== -1) {
                        if (confirm("İlaç/sarf liste içersinde var tekrar eklensin mi?")) {
                            dizi.push(varmi);
                            // var metin = "adet" + m_code;
                            //
                            // var toplam = $("." + metin).attr('malzeme_adet');
                            //var codevarmi=$("."+metin).attr('malzeme_adet',toplam+malzeme_adet);


                            alertify.confirm("" +
                                "<label class='form-label'>Malzeme Adet</label><input class='form-control' type='text' id='malzeme_adet' placeholder='Malzeme Adet giriniz...'>" +
                                "<labeel class='form-label'>Malzeme Açıklama</labeel><input class='form-control' type='text' id='malzeme_aciklama' placeholder='Malzeme açıklama giriniz...'>" +
                                "", function () {

                                var malzeme_adet = $('#malzeme_adet').val();
                                var malzeme_aciklama = $('#malzeme_aciklama').val();
                                // var toplam_m = parseInt(toplam) + parseInt(malzeme_adet);

                                var toplam=$("."+m_code).html();
                                var toplam_m=parseInt(toplam) + parseInt(malzeme_adet);
                                $("."+m_code).html(toplam_m);
                                $(".adet-"+m_code).attr("malzeme_adet",toplam_m);
                                console.log("toplam"+toplam+"    "+"toplam_m"+toplam_m+"      "+"malzeme_adet"+malzeme_adet);
                                // t.row.add(["<input type='hidden' class='adet" + m_code + "' name='stoksarfinput[]'  m_depo='" + m_depo + "' m_id='" + m_id + "' " +
                                // "m_code='" + m_code + "'" + "m_fiyat='" + m_fiyat + "'" + "m_type='" + m_type + "'" +
                                // "malzeme_adet='" + toplam_m + "' malzeme_aciklama='" + malzeme_aciklama + "' m_tarih='" + m_tarih + "' />" +
                                // m_code, m_name, m_type_name, toplam_m, m_fiyat,
                                //     "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + m_id + "' type='button'>Sil</button>"]).draw(false);
                                // $("." + metin).first().closest("tr").remove();
                                // console.log("toplam: " + toplam + "    " + "malzeme_adet: " + malzeme_adet + "    " + "id: " + metin);

                            }, function () {
                                alertify.warning('işlemden Vazgeçtiniz')
                            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Malzeme  Detay"});

                        }
                    } else {
                        dizi.push(varmi);
                        alertify.confirm("" +
                            "<label class='form-label'>Malzeme Adet</label><input class='form-control' type='text' id='malzeme_adet' placeholder='Malzeme Adet giriniz...'>" +
                            "<labeel class='form-label'>Malzeme Açıklama</labeel><input class='form-control' type='text' id='malzeme_aciklama' placeholder='Malzeme açıklama giriniz...'>" +
                            "", function () {

                            var malzeme_adet = $('#malzeme_adet').val();
                            var malzeme_aciklama = $('#malzeme_aciklama').val();


                            t.row.add(["<input type='hidden' name='stoksarfinput[]' class='adet-" + m_code + "' m_depo='" + m_depo + "' m_id='" + m_id + "' " +
                            "m_code='" + m_code + "'" + "m_fiyat='" + m_fiyat + "'" + "m_type='" + m_type + "'" +
                            "malzeme_adet='" + malzeme_adet + "' malzeme_aciklama='" + malzeme_aciklama + "' m_tarih='" + m_tarih + "' />" +
                            m_code, m_name, m_type_name,"<p class='"+m_code+"'>"+malzeme_adet+"</p>", m_fiyat,
                                "<button class='btn btn-danger delete_istek' title='Silmek için tıklayınız..' data-id-10='" + m_id + "' type='button'>Sil</button>"]).draw(false);

                        }, function () {
                            alertify.warning('işlemden Vazgeçtiniz')
                        }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Malzeme  Detay"});
                    }
                }


                $("#stoksarfpaketup").attr("type", "hidden");
                $(".stoksarfpaket").attr("type", "button");
            });


            $(document).on('click', '.delete_istek', function () {
                $(this).closest("tr").remove();
            });
        </script>
        </tbody>
    </table>
<?php } ?>



