<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];

if ($islem == "numune_kabul_buttonlari") {
    $barkod_no = $_GET['barkod_no']; ?>

    <input type="text" hidden id="barkod_no" value="<?php echo $barkod_no; ?>">

    <div class="col-12 mt-2" align="left">
        <div class="modal-getir"></div>

        <?php $pp = singularactive("patient_prompts", "service_requests_bardoce", $barkod_no);

        if ($pp["sample_acceptance_status"] == 0) {
            ?>

            <button class="btn btn-success btn_numune_kabul"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                    Kabul</b></button>

            <button class="btn btn-success btn_numune_sartli_kabul" data-bs-target="lab_modal_lg"
                    data-bs-toggle="modal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Şartlı Kabul</b></button>

            <button class="btn btn-success btn_numune_ret" data-bs-target="#lab_modal_lg" data-bs-toggle="modal"><i
                        class="fa-sharp fa-solid fa-memo"></i> <b>Numune Ret</b></button>

        <?php } else if ($pp["sample_acceptance_status"] == 1) { ?>

            <button class="btn btn-success btn_numune_kabul_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                    Kabul İptal</b></button>

            <button class="btn btn-success btn_numune_ret" data-bs-target="#lab_modal_lg" data-bs-toggle="modal"><i
                        class="fa-sharp fa-solid fa-memo"></i> <b>Numune Ret</b></button>

        <?php } else if ($pp["sample_acceptance_status"] == 2) { ?>

            <button class="btn btn-success btn_numune_ret_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune Ret
                    İptal</b></button>

        <?php } ?>

        <button class="btn btn-success btn_tup_bilgisi" data-bs-target="#lab_modal_lg" data-bs-toggle="modal"><i
                    class="fa-sharp fa-solid fa-memo"></i> <b>Tüp Bilgisi</b></button>

        <button class="btn btn-success btn_barkod_bas"><i class="fa-sharp fa-solid fa-barcode"></i> <b>Barkod Bas</b>
        </button>
    </div>


    <script>

        $("body").off("click", ".btn_numune_kabul").on("click", ".btn_numune_kabul", function (e) {
            var barkod_num = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul',
                data: {"barkod_num": barkod_num},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    numune_kabul_tup_listesi.ajax.url(numune_kabul_tup_listesi.ajax.url()).load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get("ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar", function (getveri) {
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }
            });
        });

        $("body").off("click", ".btn_numune_sartli_kabul").on("click", ".btn_numune_sartli_kabul", function (e) {
            var barkod_num = $('#barkod_no').val();

            $.get("/ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=modal_numune_sartli_kabul", {barkod_num: barkod_num}, function (getVeri) {
                $('.lab_modal_lg_icerik').html(getVeri);
            });
        });

        $("body").off("click", ".btn_numune_kabul_iptal").on("click", ".btn_numune_kabul_iptal", function (e) {
            var barkod_num = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_iptal',
                data: {"barkod_num": barkod_num},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    numune_kabul_tup_listesi.ajax.url(numune_kabul_tup_listesi.ajax.url()).load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get("ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar", function (getveri) {
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }
            });
        });

        $("body").off("click", ".btn_numune_ret").on("click", ".btn_numune_ret", function (e) {
            var barkod_num = $('#barkod_no').val();

            $.get("/ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=modal_numune_kabul_ret", {"barkod_num": barkod_num}, function (getVeri) {
                $('#lab_modal_lg_icerik').html(getVeri);
            });
        });

        $("body").off("click", ".btn_tup_bilgisi").on("click", ".btn_tup_bilgisi", function (e) {
            var barkod_num = $('#barkod_no').val();

            $.get("/ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=modal_numune_kabul_tup_bilgisi", {"barkod_num": barkod_num}, function (getVeri) {
                $('#lab_modal_lg_icerik').html(getVeri);
            });
        });

        $("body").off("click", ".btn_numune_ret_iptal").on("click", ".btn_numune_ret_iptal", function (e) {
            var barkod_num = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_ret_iptal',
                data: {"barkod_num": barkod_num},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    numune_kabul_tup_listesi.ajax.url(numune_kabul_tup_listesi.ajax.url()).load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get("ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar", function (getveri) {
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }
            });
        });

    </script>

<?php } else if ($islem == "numune_kabul_disabled_buttonlar") { ?>

    <div class="col-12 mt-2" align="left">

        <button disabled class="btn btn-success btn_numune_kabul"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                Kabul</b></button>

        <button disabled class="btn btn-success btn_numune_sartli_kabul"><i class="fa-sharp fa-solid fa-memo"></i> <b>Şartlı
                Kabul</b></button>

        <button disabled class="btn btn-success btn_numune_kabul_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                Kabul İptal</b></button>

        <button disabled class="btn btn-success btn_numune_ret"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                Ret</b></button>

        <button disabled class="btn btn-success btn_numune_ret_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Numune
                Ret İptal</b></button>

        <button disabled class="btn btn-success btn_tup_bilgisi"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tüp
                Bilgisi</b></button>

        <button disabled class="btn btn-success btn_barkod_bas"><i class="fa-sharp fa-solid fa-barcode"></i> <b>Barkod
                Bas</b></button>

    </div>

<?php } else if ($islem == "modal_numune_kabul_ret") {
    $barkod_num = $_GET['barkod_num']; ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header text-white p-1">
            <h4 class="modal-title ">Numune Reddet</h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                   id="numune_kabul_ret_nedeni_datatable">
                <thead>
                <tr>
                    <th>Ret Nedeni</th>
                </tr>
                </thead>
            </table>

            <script>
                var numune_kabul_ret_nedeni = $('#numune_kabul_ret_nedeni_datatable').DataTable({
                    deferRender: true,
                    scrollY: '15vh',
                    scrollX: true,
                    "info": false,
                    "paging": false,
                    "searching": true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

                    ajax: {
                        url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_ret_nedeni',
                        processing: true,
                        method: 'GET',
                        dataSrc: ''
                    },

                    "fnRowCallback": function (nRow, aData) {
                        $(nRow)
                            .attr('id', aData['id'])
                            .attr('class', 'ret_nedeni_sec')
                    },

                    columns: [
                        {data: 'definition_name'},
                    ],
                });

                $("body").off("click", ".ret_nedeni_sec").on("click", ".ret_nedeni_sec", function (e) {

                    $(this).css('background-color') != 'rgb(147,203,198)';
                    $('.ret_nedeni_sec-kaldir').removeClass("text-white");
                    $('.ret_nedeni_sec').removeClass("ret_nedeni_sec-kaldir");
                    $('.ret_nedeni_sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white ret_nedeni_sec-kaldir");

                    var ret_nedeni = $('.ret_nedeni_sec-kaldir').attr('id');
                    $('#sample_acceptance_rejection_detail').val(ret_nedeni);

                });

            </script>

            <form id="numune_reddet_form" action="javascript:void(0);">

                <input type="text" hidden id="barkod_num" name="barkod_num" value="<?php echo $barkod_num; ?>">
                <input type="text" hidden id="sample_acceptance_rejection_detail"
                       name="sample_acceptance_rejection_detail">

            </form>

            <div class="mt-2"></div>
            <h6 style="color: #e80c4d"><b>Numuneyi Reddetmek İstediğinizden Eminmisiniz ?</b></h6>

        </div>

        <div class="modal-footer">
            <button id="btn_numune_ret" type="button" class="btn btn-warning btn-sm">Seçilen Numuneyi Reddet</button>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn_numune_ret").on("click", "#btn_numune_ret", function (e) {
            var sample_acceptance_rejection_detail = $("#sample_acceptance_rejection_detail").val();

            if (sample_acceptance_rejection_detail != '') {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_reddet',
                    data: $("#numune_reddet_form").serialize(),
                    success: function (e) {
                        $('#sonucyaz').html(e);

                        $("#lab_modal_lg").modal("hide");

                        numune_kabul_tup_listesi.ajax.url(numune_kabul_tup_listesi.ajax.url()).load();
                        numune_kabul_tetkik_listesi.rows([]).clear().draw();

                        $.get("ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar", function (getveri) {
                            $('#numune_kabul_buttonlar').html(getveri);
                        })
                    }
                });

            } else if (sample_acceptance_rejection_detail == '') {

                alertify.warning("Ret Nedeni Seçiniz.");

            }
        });

    </script>
<?php } else if ($islem == "modal_numune_kabul_tup_bilgisi") {
    $barkod_numarasi = $_GET['barkod_num']; ?>

    <input type="text" hidden id="tup_barkod_numarasi" value="<?php echo $barkod_numarasi; ?>">

    <div class="modal-content">
        <div class="modal-header text-white p-1">
            <h4 class="modal-title ">Tüp Bilgisi</h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                   id="numune_kabul_tup_bilgisi_datatable">
                <thead>
                <tr>
                    <th>Tüp Adı</th>
                    <th>Tüp Grubu</th>
                    <th>Barkod Numarası</th>
                    <th>İstem Tarihi</th>
                    <th>İsteyen Personel</th>
                </tr>
                </thead>
            </table>

            <script>

                var tup_barkod_numarasi = $("#tup_barkod_numarasi").val();

                var numune_kabul_tup_bilgisi = $('#numune_kabul_tup_bilgisi_datatable').DataTable({
                    deferRender: true,
                    scrollY: '20vh',
                    scrollX: true,
                    "info": false,
                    "paging": false,
                    "searching": false,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

                    ajax: {
                        url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_tup_bilgi&tup_barkod_numarasi="' + tup_barkod_numarasi + '"',
                        processing: true,
                        method: 'GET',
                        dataSrc: ''
                    },

                    "fnRowCallback": function (nRow, aData) {
                        $(nRow)
                            .attr('id', aData['id'])
                        // .attr('class','ret_nedeni_sec')
                    },

                    columns: [
                        {data: 'tup_adi'},
                        {data: 'tup_grubu'},
                        {data: 'tup_barkod'},
                        {
                            data: null,
                            render: function (data) {
                                if (data != 2) {
                                    var numune_kabul_tup_istem_tarih = data.istem_tarihi;
                                    var tup_istem_tarihi = moment(numune_kabul_tup_istem_tarih).format('DD/MM/YYYY H:mm:ss');
                                    if (tup_istem_tarihi == "Invalid date") {
                                        tup_istem_tarihi = "";
                                    }
                                    return tup_istem_tarihi;
                                }
                            }
                        },
                        {data: 'istem_yapan_kullanici'},
                    ],
                });

            </script>

        </div>

    </div>

<?php } else if ($islem == "modal_numune_sartli_kabul") {
    $barkod_numarasi = $_GET['barkod_num']; ?>

    <div class="modal fade" id="acilacak-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header text-white">
                    <input type="text" hidden id="barkodun_numarasi" value="<?php echo $barkod_numarasi; ?>">
                    <h4 class="modal-title">Numune Şartlı Kabul</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-5">
                                Onaylayan Doktor
                            </div>
                            <div class="col-7">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-sm "
                                           aria-describedby="basic-addon2" name="sample_conditional_admission_userid"
                                           id="yetkili_name_surname" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm sartli_kabul_doktor" type="button"
                                                data-bs-target="#numune_sartli_kabul_doktor_modal"
                                                data-bs-toggle="modal"><i class="fa fa-ellipsis-h"
                                                                          aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="numune_sartli_kabul_doktor_modal" aria-hidden="true"
                             data-bs-backdrop="static">
                            <div class="modal-dialog modal-lg" id='numune_sartli_kabul_doktor_modal_icerik'></div>
                        </div>

                        <div class="row">
                            <div class="col-5">
                                Şartlı Kabul Açıklama
                            </div>
                            <div class="col-7">
                                <textarea class="form-control" id="sample_conditional_acceptance_description"
                                          name="sample_conditional_acceptance_description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btn_numune_sartli_kabul_et" type="button" class="btn btn-warning btn-sm">Şartlı Kabul
                        Et
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#acilacak-modal").modal("show");
        });

        $("body").off("click", ".sartli_kabul_doktor").on("click", ".sartli_kabul_doktor", function (e) {
            $.get("/ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=modal_numune_sartli_kabul_doktor", function (getVeri) {
                $('#numune_sartli_kabul_doktor_modal_icerik').html(getVeri);
            });
        });

        $("body").off("click", "#btn_numune_sartli_kabul_et").on("click", "#btn_numune_sartli_kabul_et", function (e) {
            var barkodun_numarsiymis = $("#barkodun_numarasi").val();
            var sample_conditional_acceptance_description = $("#sample_conditional_acceptance_description").val();
            var sample_conditional_admission_userid = $("#yetkili_name_surname").attr('data-id');


            if (sample_conditional_admission_userid != '' && sample_conditional_acceptance_description != '') {

                $.ajax({
                    type: 'POST',
                    url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_sartli_kabul_et',
                    data: {
                        "barkod_num": barkodun_numarsiymis,
                        "sample_conditional_acceptance_description": sample_conditional_acceptance_description,
                        "sample_conditional_admission_userid": sample_conditional_admission_userid

                    },
                    success: function (e) {
                        $('#sonucyaz').html(e);

                        $("#acilacak-modal").modal("hide");

                        numune_kabul_tup_listesi.ajax.url(numune_kabul_tup_listesi.ajax.url()).load();
                        numune_kabul_tetkik_listesi.rows([]).clear().draw();

                        $.get("ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar", function (getveri) {
                            $('#numune_kabul_buttonlar').html(getveri);
                        })
                    }
                });

            } else if (sample_conditional_admission_userid == '') {

                alertify.warning("Onaylayan Doktoru Seçiniz.");

            } else if (sample_conditional_acceptance_description == '') {

                alertify.warning("Şartlı Kabul Açıklaması Giriniz.");

            }
        });
    </script>

<?php } else if ($islem == "modal_numune_sartli_kabul_doktor") { ?>

    <div class="modal-content">
        <div class="modal-header text-white p-1">
            <h4 class="modal-title ">Yetkili Doktor Listesi</h4>
            <button type="button" class="btn-close btn-close-white yetkili_doktor_kapat" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                   id="numune_sartli_kabul_yetkili_datatable">
                <thead>
                <tr>
                    <th>Ad Soyad</th>
                </tr>
                </thead>
            </table>

            <script>
                var numune_sartli_kabul_yetkili = $('#numune_sartli_kabul_yetkili_datatable').DataTable({
                    deferRender: true,
                    scrollY: '50vh',
                    scrollX: true,
                    "info": false,
                    "paging": false,
                    "searching": false,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

                    ajax: {
                        url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_sartli_kabul_doktor',
                        processing: true,
                        method: 'GET',
                        dataSrc: '',
                    },

                    "fnRowCallback": function (nRow, aData) {
                        $(nRow)
                            .attr('yetkili_id', aData['id'])
                            .attr('yetkili_name_surname', aData['name_surname'])
                            .attr('class', 'yetkili_personel_sec')
                    },

                    "initComplete": function (settings, json) {
                    },

                    columns: [
                        {data: 'name_surname'},
                    ],
                });

                $("body").off("click", ".yetkili_personel_sec").on("click", ".yetkili_personel_sec", function (e) {

                    $(this).css('background-color') != 'rgb(147,203,198)';
                    $('.yetkili_personel_sec-kaldir').removeClass("text-white");
                    $('.yetkili_personel_sec-kaldir').removeClass("yetkili_personel_sec-kaldir");
                    $('.yetkili_personel_sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white yetkili_personel_sec-kaldir");


                });
            </script>

        </div>

        <div class="modal-footer">
            <button id="btn_onaylayan_doktor_sec" type="button" class="btn btn-success btn-sm">Seç</button>
        </div>

    </div>

    <script>
        $("body").off("click", ".yetkili_doktor_kapat").on("click", ".yetkili_doktor_kapat", function (e) {
            $("#numune_sartli_kabul_doktor_modal").modal("hide");
        });

        $("body").off("click", "#btn_onaylayan_doktor_sec").on("click", "#btn_onaylayan_doktor_sec", function (e) {

            $("#numune_sartli_kabul_doktor_modal").modal("hide");

            var yetkili_name_surname = $('.yetkili_personel_sec-kaldir').attr('yetkili_name_surname');
            var yetkili_id = $('.yetkili_personel_sec-kaldir').attr('yetkili_id');
            $('#yetkili_name_surname').val(yetkili_name_surname);
            $('#yetkili_name_surname').attr("data-id", yetkili_id);
        });
    </script>


<?php } ?>