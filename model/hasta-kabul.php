<?php include "../controller/fonksiyonlar.php";  ?>

<div id="modul-ana-tab" class="easyui-tabs" data-options="tools:'#tab-tools',fit:true">
    <div title="Poliklinik" iconCls="fa-solid fa-house-heart">

        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true" title="Kriter Paneli" style="height:35%;">

                <div class="row">

                    <div class="col-md-4-half mt-1">

                        <div class="row">
                            <div class="col-md-2-half">
                                <label class="form-label">Birim:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="birim-filt" placeholder="Birim Adı" disabled>
                                    <div class="input-group-append input-group-sm">
                                        <span id="birim-clk" data-bs-target="#birim-listesi-modal" data-bs-toggle="modal" class="input-group-text birim-one-clk">...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1-half">
                                <label class="form-label">Doktor:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="doktor-filt" placeholder="Doktor Adı">
                                    <div class="input-group-append input-group-sm">
                                        <span id="doktor-listesi-btn" data-bs-target="#doktor-listesi-modal" data-bs-toggle="modal" class="input-group-text">...</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-2-half">
                                <label class="form-label">Muayene Du.:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <select class="form-select form-select-xs" id="kabul-filt">
                                        <option value="1" selected>Tümü...</option>
                                        <option value="2">Muayene Edilen</option>
                                        <option value="3">Muayene Edilmeyen</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1-half">
                                <label class="form-label">Triaj:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <select class="form-select form-select-xs" id="triaj-filt">

                                        <option value="1" selected>Tümü...</option>
                                        <?php $sql = sql_select("select * from transaction_definitions where definition_type='TRIAJ_KODU'");
                                        foreach ($sql as $item) { ?>
                                            <option value="<?php echo $item['definition_code']; ?>"><?php echo $item['definition_name']; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2-half">
                                <label class="form-label">Kabul Şekli:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <select class="form-select form-select-xs" id="kabul-sekli-filt">
                                        <option value="1" selected>Tümü...</option>

                                        <?php $sql = sql_select("select * from transaction_definitions where definition_type='HASTA_KABUL_SEKLI'");
                                        foreach ($sql as $item) { ?>
                                            <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1-half">
                                <label class="form-label">P. No:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="protokol-no-filt" placeholder="Protokol No">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2-half">
                                <label class="form-label">İlk Tarih:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input class="form-control form-control-xs" type="datetime-local" id="ilk-tarih-filt"/>
                                </div>
                            </div>

                            <div class="col-md-1-half">
                                <label class="form-label">Adı:</label>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="soyad-filt" placeholder="Hasta Soyadı">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2-half">
                                <label class="form-label">Son Tarih:</label>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input class="form-control form-control-xs" id="son-tarih-filt" type="datetime-local">
                                </div>
                            </div>

                            <div class="col-md-1-half">
                                <label class="form-label form-label-sm">Soyadı:</label>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="adi-filt" placeholder="Hasta Adı">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-2-half">
                                <label class="form-label">Kimlik:</label>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="kimlik-filt" placeholder="Kimlik No">
                                </div>
                            </div>

                            <div class="col-md-1-half">
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="input-group-append input-group-sm">
                                    <button id="hasta-filtre-detay-one-clk" class="btn btn-secondary hasta-filtre-detay"><i id="sorgula-icon-dom" class="fa-solid fa-magnifying-glass-plus fa-1x"></i>Sorgula</button>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-2-half mt-1">

                        <div class="row">
                            <div class="col-md-4-half">
                                <label class="form-label">Toplam Hasta:</label>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="toplam-tum-hastalarim" title="Toplam Muayene Edilen Hasta Sayısı" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4-half">
                                <label class="form-label">M. Edilen:</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="toplam-muayene-olan-hastalarim" title="Toplam Muayene Edilen Hasta Sayısı" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4-half">
                                <label class="form-label">M. Edilmeyen:</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-xs" id="muayene-olmayan" title="Toplam Muayene Edilmeyen Hasta Sayısı" disabled>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div data-options="region:'center',split:true" title="Hasta listesi" style="height:65%;">
                <table id="kayili-hasta-listesi" class="table table-sm table-hover table-bordered nowrap" style="font-size: 13px;">
                    <thead>
                    <th scope="col">Hasta Adı</th>
                    <th scope="col">Tc Kimlik</th>
                    <th scope="col">Müracat T.</th>
                    <th scope="col">Poliklinik</th>
                    <th scope="col">Durumu</th>
                    <th scope="col">Protokol No</th>
                    <th scope="col">Dış Ekran</th>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

        <div class="modal" id="doktor-listesi-modal" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-1">
                        Doktor Lİstesi
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="doktor-listesi-getir">
                        <table class="table table-sm display nowrap table-hover table-bordered" id="doktor-listesi-table" style="font-size: 13px;">
                            <thead>
                            <th>Adı</th>
                            <th>Birimi</th>
                            <th>Kayıt Tarihi</th>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="birim-listesi-modal" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-1">Birim Lİstesi<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="doktor-listesi-getir">
                        <table class="table table-sm display nowrap table-hover w-100 table-bordered" id="birim-listesi-table" style="font-size: 13px;">
                            <thead>
                            <th class="col-md-6">Bölüm Adı</th>
                            <th class="col-md-6">Kodu</th>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="secilen-birimler-clk" class="btn btn-success" data-bs-dismiss="modal">Seçimleri Onayla</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" data-bs-backdrop="static" id="yatis-modal" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div id="yatis-icerik"></div>
            </div>
        </div>

        <div class="modal" data-bs-backdrop="static" id="tani-modal1" aria-hidden="true">
            <div class="modal-dialog" style="width: 95% !important; max-width: 95% !important;">
                <div id="tani-icerik"></div>
            </div>
        </div>

        <div class="modal" data-bs-backdrop="static" id="ana-modal" aria-hidden="true"
             style="--bs-modal-padding: 0rem !important; --bs-modal-margin: 0rem !important;">
            <div id="ana-icerik">
            </div>
        </div>

<div class="w-simple easyui-dialog" closed="true" fit="true" modal="true"></div>
<div class="w-simple-2 easyui-dialog" closed="true" modal="true"></div>

        <script>
            $(document).ready(function () {
                $("body").off("click", "#doktor-filt").on("click", "#doktor-filt", function () {
                    $("#doktor-filt").attr("doktor-id", "");
                    $("#doktor-filt").attr("doktor-adi", "");
                });

                var secilen_birimler = [];

                function removePanel() {
                    var tab = $('#hasta-kabul-tab').tabs('getSelected');
                    if (tab) {
                        var index = $('#hasta-kabul-tab').tabs('getTabIndex', tab);
                        $('#hasta-kabul-tab').tabs('close', index);
                    }
                }

                $("body").off("click", ".hasta-filtre-detay").on("click", ".hasta-filtre-detay", function (e) {
                    $('#sorgula-icon-dom').removeClass("fa-magnifying-glass-plus");
                    $('#sorgula-icon-dom').addClass("fa-spinner fa-spin-pulse");
                    setTimeout(function () {
                        $('#sorgula-icon-dom').addClass("fa-magnifying-glass-plus");
                        $('#sorgula-icon-dom').removeClass("fa-spinner fa-spin-pulse");
                    }, 1000);

                    $value_control_1 = $('#birim-filt').val();
                    $value_control_2 = $('#doktor-filt').val();

                    if ($value_control_1 && $value_control_2) {
                        $('#birim-filt').attr('birim-adi', '');
                    }

                    table_muayene.ajax.url(table_muayene.ajax.url()).load();

                    setTimeout(function () {
                        var bekleyen_hasta = 0;
                        var muayene_olan_hasta = 0;
                        $(".hasta-sec").each(function () {

                            var kontrol_et = $(this).attr("m_durumu");

                            if (kontrol_et > 0) {
                                muayene_olan_hasta += 1;
                            } else {
                                bekleyen_hasta += 1;
                            }

                        });

                        $('#muayene-olmayan').val(bekleyen_hasta);
                        $('#toplam-muayene-olan-hastalarim').val(muayene_olan_hasta);
                        $('#toplam-tum-hastalarim').val($(".hasta-sec").length);
                    }, 1000);
                });

                $("body").off("click", "#doktor-listesi-btn").on("click", "#doktor-listesi-btn", function (e) {
                    table_doktor.ajax.url(table_doktor.ajax.url()).load();
                });

                $("body").off("click", "#secilen-birimler-clk").on("click", "#secilen-birimler-clk", function (e) {

                    secilen_birimler = [];
                    secilen_birimler_adi = [];
                    $(".bg-yesil").each(function (index) {
                        secilen_birimler.push($(this).attr('birim-id'));
                    });

                    $(".bg-yesil").each(function (index) {
                        secilen_birimler_adi.push($(this).attr('birim-adi'));
                    });

                    $('#birim-filt').val(secilen_birimler_adi);
                    $('#birim-filt').attr("title", secilen_birimler_adi);
                });

                $("body").off("click", ".doktor-sec").on("click", ".doktor-sec", function (e) {
                    $('#doktor-filt').attr("doktor-id", $(this).attr('doktor-id'));
                    $('#doktor-filt').attr("doktor-adi", $(this).attr('doktor-adi'));
                    $('#doktor-filt').val($(this).attr('doktor-adi'));
                });

                $("body").off("click", ".birim-sec").on("click", ".birim-sec", function (e) {
                    $('#birim-filt').attr("birim-id", $(this).attr('birim-id'));
                    $('#birim-filt').attr("birim-adi", $(this).attr('birim-adi'));

                    if ($(this).hasClass("bg-yesil")) {
                        $(this).removeClass("bg-yesil");
                        $(this).removeClass("text-white");
                    } else {
                        $(this).addClass("bg-yesil");
                        $(this).addClass("text-white");
                    }

                });

                $("body").off("click", "#birim-clk").on("click", "#birim-clk", function (e) {
                    $('#birim-filt').val('');
                    $('#birim-filt').attr('birim-id', "");
                    $('#birim-filt').attr('birim-adi', "");
                });

                $(".birim-one-clk").one("click", function () {
                    var table_birim = $('#birim-listesi-table').DataTable({
                        deferRender: true,
                        scrollY: '50vh',
                        "ordering": false,
                        scrollX: false,
                        "info": false,
                        "paging": false,
                        "searching": true,
                        colReorder: true,

                        ajax: {
                            url: '/ajax/poliklinik/poliklinik-liste.php?islem=birim-listesi',
                            processing: true,
                            method: 'POST',
                            "dataSrc": '',
                            data: function (data) {
                                data.birim_filt = $('#doktor-filt').attr("doktor-id");
                            },
                        },

                        columns: [
                            {data: 'department_name'},
                            {data: 'id'},
                        ],

                        "fnRowCallback": function (nRow, aData) {
                            var $nRow = $(nRow);
                            $adi = `${aData['department_name']}`;
                            $nRow.attr("birim-adi", $adi);
                            $id = `${aData['id']}`;
                            $nRow.attr("birim-id", $id);
                            $nRow.attr("class", "birim-sec");
                            return nRow;
                        },

                    });

                    setTimeout(function () {
                        table_birim.columns.adjust().draw();
                    },500);

                });

                var table_doktor = $('#doktor-listesi-table').DataTable({
                    deferRender: true,
                    scrollY: '65vh',
                    "deferLoading": 0,
                    serverSide: true,
                    scrollX: false,
                    "info": false,
                    colReorder: true,
                    "paging": false,
                    "searching": true,
                    "autoWidth": true,
                    ajax: {
                        url: '/ajax/poliklinik/poliklinik-liste.php?islem=doktor-listesi',
                        processing: true,
                        method: 'POST',
                        "dataSrc": '',
                        data: function (data) {
                            data.birim_filt = secilen_birimler;
                        },
                    },

                    columns: [
                        {data: 'name_surname'},
                        {data: 'department_name'},
                        {data: 'kayit_tarihi'},
                    ],

                    "fnRowCallback": function (nRow, aData) {
                        var $nRow = $(nRow);
                        $adi = `${aData['name_surname']}`;
                        $nRow.attr("doktor-adi", $adi);
                        $id = `${aData['doktor_id']}`;
                        $nRow.attr("doktor-id", $id);
                        $nRow.attr("class", "doktor-sec");
                        $nRow.attr("data-bs-dismiss", "modal");
                        return nRow;
                    },

                });

                $("body").off("click", ".hasta-sec").on("click", ".hasta-sec", function (e) {
                    var secilen = $(this).attr('id');
                    var adi = $(this).attr('adi');
                    var deger = $(this).attr('deger');
                    var tab_class = $(this).attr('id-2');
                    var tabs_count = $('.' + tab_class).length;
                    var name = "&deger=" + tab_class;

                    if (tabs_count == 0) {

                        $.get("ajax/" + secilen + ".php?islem=listeyi-getir" + name, function (e) {
                            var tabs = $('#modul-ana-tab').tabs('add', {
                                iconCls: "fa-solid fa-hospital-user " + tab_class,
                                title: adi,
                                content: '<div class="sayfa-icerik" style="height:100%; width: 100%;" >' + e + '</div>',
                                closable: true,

                            });
                        });

                    } else {
                        $("." + tab_class).trigger("click");
                    }

                });

                var data_istem = [];
                var table_muayene = $('#kayili-hasta-listesi').DataTable({
                    "serverSide": true,
                    scrollY: '40vh',
                    scrollX: false,
                    "deferLoading": 0,
                    colReorder: true,
                    "info": false,
                    "paging": false,
                    "searching": false,
                    "dom": "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },

                    ajax: {
                        url: 'ajax/poliklinik/poliklinik-liste.php?islem=muayene-hasta-listesi',
                        processing: true,
                        method: 'POST',

                        data: function (data) {
                            data.birim_filt = $('#birim-filt').attr("birim-adi");
                            data.kabul_filt = $('#kabul-filt').val();
                            data.kabul_sekli_filt = $('#kabul-sekli-filt').val();
                            data.ilk_tarih_filt = $('#ilk-tarih-filt').val();
                            data.son_tarih_filt = $('#son-tarih-filt').val();
                            data.doktor_filt = $('#doktor-filt').attr('doktor-id');
                            data.triaj_filt = $('#triaj-filt').val();
                            data.protokol_no_filt = $('#protokol-no-filt').val();
                            data.kimlik_filt = $('#kimlik-filt').val();
                            data.ad_filt = $('#adi-filt').val();
                            data.soyad_filt = $('#soyad-filt').val();
                        },

                        "dataSrc": function (data) {
                            if (data.data[0] == null) {
                                return [];
                            } else {
                                data_istem = data.data_istem[0];
                                return data.data[0];
                            }
                        }
                    },

                    "createdRow": function (row, data, dataIndex) {
                        $(row).addClass('muayene-listesi-hasta');

                    },

                    "fnRowCallback": function (nRow, aData) {
                        var $nRow = $(nRow);

                        $('td:eq(4)', nRow).attr('istem_durumu', aData['protokol_no']);

                        if (data_istem) {
                            data_istem.forEach(function (data) {
                                if (data.protocol_number == aData.protokol_no) {
                                    $('td:eq(4)', nRow).html("<span class='text-success'> Muayenede</span>");
                                    $nRow.attr("m_durumu", "1");
                                } else {
                                    $nRow.attr("m_durumu", "0");
                                }

                            });
                        }

                        if (aData.consultation_id > 0) {
                            $nRow.css("background-color", "red");
                            $nRow.css("color", "white");

                        }

                        $nRow.attr("class", "hasta-sec");
                        $nRow.attr("id", "poliklinik/hasta-detay-islem");

                        $adi = `${aData['patient_name'] + " " + aData['patient_surname']}`;
                        $nRow.attr("adi", $adi);

                        $protokol_no = `${aData['hasta_kayit_id']}`;
                        $nRow.attr("id-2", $protokol_no);

                        return nRow;
                    },

                    "initComplete": function (settings, json) {

                    },

                    columns: [
                        {
                            data: null,
                            render: function (data) {
                                return data.patient_name + " " + data.patient_surname;
                            }
                        },
                        {data: 'kimlik_no'},
                        {data: 'muracat_tarihi'},
                        {data: 'polikilinik'},
                        {
                            data: null,
                            render: function (data) {
                                return "<span class='text-danger'>Beklemede</span>";
                            }
                        },
                        {data: 'protokol_no'},
                        {
                            data: null,
                            render: function (data) {
                                if (data.hasta_cagirildimi > 0) {
                                    return "<span class='text-success'>Çağırıldı</span>";
                                } else {
                                    return "<span class='text-danger'>Çağırılmadı</span>"
                                }
                            }
                        },
                    ],

                });

 $("body").off("click", ".tanigetir").on("click", ".tanigetir", function (e) {
       $(".tanigetir").removeClass("tanibtnsec");
       $(this).addClass("tanibtnsec");
       var diagnosis_modul = $(this).attr("diagnosis-modul");
       var tip = 0;
       var modul = "ayaktan";
       var protokol_no = $(this).attr("protokol-no");
     $('.w-simple').dialog('open');
     $('.w-simple').dialog('refresh' , 'ajax/tani/tanilistesi.php?islem=tanilistesigetir&protokolno='+protokol_no+'&diagnosis_modul='+diagnosis_modul+'&tip='+tip+'&modul='+modul+'');
});

                $("body").off("click", ".yatistalep").on("click", ".yatistalep", function (e) {
                    var protokol_no = $(this).attr("protokol-no");
                    if (!protokol_no) {
                        alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
                    } else {
                        $.get("ajax/yatis/yatismodalbody.php?islem=yatistalepbody", {getir: protokol_no}, function (getVeri) {
                            $('#yatis-modal').modal('show');
                            $('#yatis-icerik').html(getVeri);
                        });
                    }
                });

                $("body").off("click", "#recete").on("click", "#recete", function (e) {

                    var protokol_no = $(this).attr("protokol-no");

                    $('.w-simple').dialog('open');
                    $('.w-simple').dialog('refresh' , 'ajax/recete/recetemodal.php?islem=modal_recete&getir='+protokol_no+'');

                });

                var date = new Date();
                var bir_onceki_gun = date.getDate();
                var bir_sonraki_gun = date.getDate();
                var fetch_data_1 = date.getFullYear() + "-" + ("00" + (date.getMonth() + 1)).slice(-2) + "-" + (bir_onceki_gun) + "T" + ("00:00");
                var fetch_data_2 = date.getFullYear() + "-" + ("00" + (date.getMonth() + 1)).slice(-2) + "-" + (bir_sonraki_gun) + "T" + ("23:59");

                $('#ilk-tarih-filt').val(fetch_data_1);
                $('#son-tarih-filt').val(fetch_data_2);

            });
        </script>







