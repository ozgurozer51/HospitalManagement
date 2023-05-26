<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];
if ($islem == "listeyi-getir") { ?>
    <style>
        .acik {
            color: darkgreen;
            /*margin-left: 15%;*/
            /*margin-right: 15%;*/

        }

        .kapali {
            color: red;
            /*margin-left: 15%;*/
            /*margin-right: 15%;*/

        }
    </style>

    <div class="easyui-layout" data-options="fit:true" style="height:100%; width: 100%;">
        <!--        <div data-options="region:'east',split:true" title="East" style="width:180px;"></div>-->
        <div data-options="region:'west',split:true ,hideCollapsedContent:false" title="Kriter Paneli"
             style="width:17%;">

            <div class="px-1">

                <div id="kriter_paneli_form">

                <div class="row mt-1">

                    <div class="col-5">
                        <label>Başlangıç Tarihi</label>
                    </div>

                    <div class="col-7">
                        <input class="form-control" id="sonuc_onay_bas_tarih" type="date" value="<?php echo date("Y-m-d"); ?>">
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Bitiş Tarihi</label>
                    </div>

                    <div class="col-7">
                        <input class="form-control" id="sonuc_onay_bit_tarih" type="date" value="<?php echo date("Y-m-d"); ?>">
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Barkod No</label>
                    </div>

                    <div class="col-7">
                        <input class="form-control" id="tup_barkod_numarasi" type="text">
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Protokol No</label>
                    </div>

                    <div class="col-7">
                        <input class="form-control" id="protokol_no" type="text">
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Tüp Grubu</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="tup_grubu" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning tup_grup_getir" type="button"><i class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Cihaza Göre</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="cihaz_adi" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning " type="button"><i
                                            class="fa fa-ellipsis-h cihazlari_getir" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Sonuç Durum</label>
                    </div>

                    <div class="col-7">
                        <select class="form-select kabul_durum_bilgisi" id="sonuc_durumu">
                            <option selected disabled class="text-white bg-danger">Seçiniz..</option>
                            <option id="sonuc_cikmadi" value="0">Sonucun Çıkması Bekleniyor</option>
                            <option id="lobrant_onayladi" value="1">Uzman Onayı Bekliyor</option>
                            <option id="uzman_onayladi" value="2">Sonuçlandı</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Tetkik</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="secilen_tetkik" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning tetkik_getir" type="button"><i class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Doktor</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="secilen_doktor" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning doktor_getir" type="button"><i class="fa fa-ellipsis-h"
                                                                                                aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Birim</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="isteyen_birim" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning birim_getir" type="button"><i
                                            class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

<!--                <div class="row mt-2">-->
<!---->
<!--                    <div class="col-5">-->
<!--                        <label>Aşama</label>-->
<!--                    </div>-->
<!---->
<!--                    <div class="col-7">-->
<!--                        <select class="form-select">-->
<!--                            <option selected disabled class="text-white bg-danger"-->
<!--                                    title="Hangi Aşamada Olduğunu Görmek İçin Seçiniz">Seçiniz..-->
<!--                            </option>-->
<!--                        </select>-->
<!--                    </div>-->
<!---->
<!--                </div>-->

                <div class="row mt-2">

                    <div class="col-5">
                        <label>İstem No</label>
                    </div>

                    <div class="col-7">
                        <input class="form-control" id="istem_no" type="text">
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Hasta Türü</label>
                    </div>

                    <div class="col-7">
                        <select class="form-select hasta_turu">
                            <option selected disabled class="text-white bg-danger">Seçiniz..</option>
                            <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='HASTA_TURU' and status='1'");
                            foreach ($sql as $item){ ?>
                                <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                            <?php }?>
                        </select>
                    </div>

                </div>

                <div class="row mt-2">

                    <div class="col-5">
                        <label>Laboratuvar</label>
                    </div>

                    <div class="col-7">
                        <div class="input-group ">
                            <input type="text" id="secilen_laboratuvar" class="form-control form-control-sm" aria-describedby="basic-addon2"
                                   readonly>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-outline-warning laboratuvar_getir" type="button"><i
                                            class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                </div>

                <div class="row mt-2">

                    <div class="col-6" align="left"></div>
                    <div class="col-6" align="right">

                        <button class="btn btn-sm btn-success btn_bilgileri_sorgula" style="height: 25px;">
                            <b><i class="fa-regular fa-magnifying-glass"></i> Sorgula</b>
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <div data-options="region:'center'">
            <div class="easyui-layout" data-options="fit:true">
                <div data-options="region:'north',split:true" style="height:30%">
                    <div class="easyui-layout" data-options="fit:true">
                        <div data-options="region:'west',split:true,hideCollapsedContent:false" title="Tanı Bilgileri" style="width:27%;">

                            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                                   id="hasta_tani_bilgileri_datatable">
                                <thead style="position: relative;">
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                        <div data-options="region:'center'">
                            <div id="sonuc_onaylama_hasta_bilgileri">

                                <div class="row px-1">

                                    <h6 class="contentTitle mt-2">Hasta Bilgileri</h6>

                                    <div class="col-4 mt-1">

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Protokol No</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="protokol_no">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Adı Soyadı</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="adi_soyadi">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>TC Kimlik No</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="tc_id">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Kurum</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="krum">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-5">
                                                <label>Kan Grubu</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="kan">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Müracaat Tarihi</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="muracaat_tarihi">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4 mt-1">

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Birim</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="birim">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Doktor</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="doktor">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Vaka Türü</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="vaka_turu">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Kabul Şekli</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="kabul_sekli">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-5">
                                                <label>Oda/Yatak</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="oda_yatak">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-5">
                                                <label>Yatış Tarihi</label>
                                            </div>
                                            <div class="col-7">
                                                <input readonly class="form-control " type="text" id="yatis_tarihi">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-4 mt-1">

                                        <div align="center">
                                            <img style="width: 50%;" class="rounded h-80 mt-2  img-thumbnail"
                                                 src="assets/img/dummy-user.jpeg">
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div data-options="region:'west',split:true,hideCollapsedContent:false" title="Numune Listesi" style="width:27%;">
                    <div class="row">

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                               id="sonuc_onay_numune_listesi_datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Protokol</th>
                                <th>T.C.</th>
                                <th>Adı Soyadı</th>
                            </tr>
                            </thead>

                        </table>

                        <div class="mt-1"></div>

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;"
                               id="sonuc_onay_numune_listesi_icerik_datatable">
                            <thead>
                            <tr>
                                <th>Barkod</th>
                                <th>Tüp/Kap</th>
                                <th>N. Alım Tarihi</th>
                                <th>N. Alan Personel</th>
                                <th>N. Kabul Tarihi</th>
                                <th>N. Kabul Eden Kullanıcı</th>
                                <th>N. Şartlı Kabul Eden Doktor</th>
                                <th>N. Şartlı Kabul Açıklama</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>
                <div data-options="region:'center'">

                    <div class="card">
                        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label>&nbsp;</label></div>

                    </div>
                    <table id="test_table" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Drum</th>
                            <th>Tetkik Adı</th>
                            <th>İşlem Tarihi</th>
                            <th>Laborant Onay Tarihi</th>
                            <th>Onaylayan Laborant</th>
                            <th>Uzman Onay Tarihi</th>
                            <th>Onaylayan Uzman</th>
                            <th>Açıklama</th>
                            <th>Cihaz Adı</th>
                        </tr>
                        </thead>
                    </table>

                    <div id="sonuc_onaylama_buttons">

                        <div class="col-12 mt-1 px-2" align="left">

                            <button title="Manuel Sonuç Girişi" disabled class="btn btn-sm btn-success"><i
                                        class="fa-sharp fa-solid fa-memo"></i> <b>Sonuç Gir</b></button>

                            <button disabled class="btn btn-sm btn-success btn_lbrnt_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Laborant
                                    Onay</b></button>

                            <button disabled class="btn btn-sm btn-success btn_lbrnt_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i>
                                <b>Laborant
                                    Onay İptal</b></button>

                            <button disabled class="btn btn-sm btn-success btn_uzm_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman
                                    Onay</b></button>

                            <button disabled class="btn btn-sm btn-success btn_uzm_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman
                                    Onay
                                    İptal</b></button>

                            <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-memo"></i> <b>Geçmiş
                                    Sonuçlar</b></button>

                            <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tekrar
                                    Sonuçlar</b></button>

                            <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-print"></i> <b>Rapor</b>
                            </button>

                        </div>

                    </div>

                </div>
            </div>


        </div>
    </div>

    <script>
        $("body").off("click", ".tup_grup_getir").on("click", ".tup_grup_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Tüp Seç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_tup_getir');
        });

        $("body").off("click", ".cihazlari_getir").on("click", ".cihazlari_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Cihaz Seç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_cihaz_getir');
        });

        $("body").off("click", ".tetkik_getir").on("click", ".tetkik_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Tetkik Seç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_tetkik_getir');
        });

        $("body").off("click", ".doktor_getir").on("click", ".doktor_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Doktor Seç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_doktor_getir');
        });

        $("body").off("click", ".birim_getir").on("click", ".birim_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Birim Şeç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_birim_getir');
        });

        $("body").off("click", ".laboratuvar_getir").on("click", ".laboratuvar_getir", function (e) {
            $('.kriter_paneli_window').window('setTitle', 'Laboratuvar Seç');
            $('.kriter_paneli_window').window('open');
            $('.kriter_paneli_window').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=modal_laboratuvar_getir');
        });

        $("body").off("click", ".btn_bilgileri_sorgula").on("click", ".btn_bilgileri_sorgula", function (e) {

            var sonuc_onay_bas_tarih = $('#sonuc_onay_bas_tarih').val();
            var sonuc_onay_bit_tarih = $('#sonuc_onay_bit_tarih').val();
            var tup_barkod_numarasi = $('#tup_barkod_numarasi').val();
            var protokol_no = $('#protokol_no').val();
            var tup_id = $("#tup_grubu").attr('tup-id');
            var cihaz_id = $("#cihaz_adi").attr('cihaz-id');
            var sonuc_durumu = $('#sonuc_durumu').val();
            var tetkik_id = $("#secilen_tetkik").attr('tetkik-id');
            var doktor_id = $("#secilen_doktor").attr('kullanici-id');
            var birim_id = $("#isteyen_birim").attr('birim-id');
            var istem_no = $('#istem_no').val();
            var hasta_turu = $('.hasta_turu').val();
            var lab_id = $("#secilen_laboratuvar").attr('lab-id');

            alert(sonuc_onay_bas_tarih+' '+sonuc_onay_bit_tarih+' '+tup_barkod_numarasi+' '+protokol_no+' '+tup_id+' '+cihaz_id+' '+sonuc_durumu+' '+tetkik_id+' '+doktor_id+' '+birim_id+' '+istem_no+' '+hasta_turu+' '+lab_id);

            // sonuc_onay_numune_listesi.ajax.url('ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_kriter_paneli_filtre&sonuc_onay_bas_tarih="' + sonuc_onay_bas_tarih + '"&sonuc_onay_bit_tarih="' + sonuc_onay_bit_tarih + '"&tup_barkod_numarasi="' + tup_barkod_numarasi + '"&protokol_no="' + protokol_no + '"&tup_id="' + tup_id + '"&cihaz_id="' + cihaz_id + '"&sonuc_durumu="' + sonuc_durumu + '"&tetkik_id="' + tetkik_id + '"&doktor_id="' + doktor_id + '"&birim_id="' + birim_id + '"&istem_no="' + istem_no + '"&hasta_turu="' + hasta_turu + '"&lab_id="' + lab_id + '"').load();


            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_kriter_paneli_filtre",
                {"sonuc_onay_bas_tarih": sonuc_onay_bas_tarih,
                    "sonuc_onay_bit_tarih":sonuc_onay_bit_tarih,
                    "tup_barkod_numarasi":tup_barkod_numarasi,
                    "protokol_no":protokol_no,
                    "tup_id":tup_id,
                    "cihaz_id":cihaz_id,
                    "sonuc_durumu":sonuc_durumu,
                    "tetkik_id":tetkik_id,
                    "doktor_id":doktor_id,
                    "birim_id":birim_id,
                    "istem_no":istem_no,
                    "hasta_turu":hasta_turu,
                    "lab_id":lab_id
                },
                function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    sonuc_onay_numune_listesi.clear().draw(false);
                    json.forEach(function (item) {
                        let sonuc_onay_numune_listesi_attr = sonuc_onay_numune_listesi.row.add([item.service_requestsid,item.protocol_number,item.tc_id,item.patient_name+" "+item.patient_surname]).draw(false).node();

                        $(sonuc_onay_numune_listesi_attr).attr('service_requestsid', item.service_requestsid)
                        $(sonuc_onay_numune_listesi_attr).attr('protocol_number', item.protocol_number)
                        $(sonuc_onay_numune_listesi_attr).attr('patient_id', item.patient_id)
                    });
                } else {
                    alertify.warning("Bir Hata Oluştu!");
                }
            });
        });

        var hasta_tani_bilgileri = $('#hasta_tani_bilgileri_datatable').DataTable({
            scrollY: '15vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
        })

        var sonuc_onay_numune_listesi = $('#sonuc_onay_numune_listesi_datatable').DataTable({
            scrollY: '24vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('class', 'sonuc_onay_numune_sec')
            },


        });


            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onaylama_numune_listesi", function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    json.forEach(function (item) {
                        let sonuc_onay_numune_listesi_attr = sonuc_onay_numune_listesi.row.add([item.service_requestsid,item.protocol_number,item.tc_id,item.patient_name+" "+item.patient_surname]).draw(false).node();

                        $(sonuc_onay_numune_listesi_attr).attr('service_requestsid', item.service_requestsid)
                        $(sonuc_onay_numune_listesi_attr).attr('protocol_number', item.protocol_number)
                        $(sonuc_onay_numune_listesi_attr).attr('patient_id', item.patient_id)
                    });
                } else {
                    alertify.warning("Bir Hata Oluştu!");
                }
            });

        var test_dt = $('#test_table').DataTable({
            // deferRender: true,
            scrollY: '50vh',
            scrollX: true,
            // "serverSide": true,
            "info": false,
            "paging": false,
            // "deferLoading": 0,
            "searching": false,
            "fnRowCallback": function (nRow, aData) {
                $(nRow).addClass("treeTable_icerik");
                $(nRow).addClass("sec_detay_liste");
            },
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"}
        });

        $("body").off("click", ".sonuc_onay_numune_sec").on("click", ".sonuc_onay_numune_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.sonuc_onay_numune_sec-kaldir').removeClass("text-white");
            $('.sonuc_onay_numune_sec-kaldir').removeClass("sonuc_onay_numune_sec-kaldir");
            $('.sonuc_onay_numune_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white sonuc_onay_numune_sec-kaldir");

            var service_requestsid = $(this).attr("service_requestsid")
            var protocol_number = $(this).attr("protocol_number")


            function format(d) {
                // console.log(d[0].split('/')[1]);
                return (
                    '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Alt Parametre</th>' +
                    '<th>Sonuç</th>' +
                    '<th>Birim</th>' +
                    '<th>Referans Aralığı</th>' +
                    '<th>Tekrar Çalışma Sayısı</th>' +
                    '<th>Tekrar Çalışılma Nedeni</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody class="eklenecek' + d[0].split('/')[1] + '"></tbody>' +
                    '</table>'
                );
            }

            $(document).ready(function () {

                $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        test_dt.rows([]).clear().draw();
                        json.forEach(function (item) {
                            var islem_tarih = item.sonuc_tarihi;
                            var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                            if (islem_tarihi == "Invalid date") {
                                islem_tarihi = "";
                            }

                            var onay_tarihi_laborant = item.laborant_onay_tarihi;
                            var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                            if (tarih_onaylayan_laborant == "Invalid date") {
                                tarih_onaylayan_laborant = "";
                            }

                            var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                            var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                            if (uzman_onay_tarihi == "Invalid date") {
                                uzman_onay_tarihi = "";
                            }

                            var basilacak = "";
                            if (item.tetkik_sonuc_durum == 0) {
                                basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                            } else if (item.tetkik_sonuc_durum == 1) {
                                basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                            } else if (item.tetkik_sonuc_durum == 2) {
                                basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                            }

                            let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/" + item.idsi + "/'></i>", basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                            $(row).attr("data-id", item.idsi);
                        });
                    }

                });

                $("body").off("click", ".treeTable_icerik").on("click", ".treeTable_icerik", function () {
                    var id = $(this).attr("data-id");
                    var tr = $(this).closest('tr');
                    var row = test_dt.row(tr);
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler", {patient_promptsid: id}, function (result) {
                        if (result != 2) {
                            var json = JSON.parse(result);
                            json.forEach(function (item) {

                                var tektrar_calisma = item.tektrar_calisma_sayisi;
                                if (tektrar_calisma == null) {
                                    tektrar_calisma = "";
                                }

                                var yeniden_calisilma_neden = item.yeniden_calisilma_nedeni;
                                if (yeniden_calisilma_neden == null) {
                                    yeniden_calisilma_neden = "";
                                }


                                $(".eklenecek" + id).append(
                                    "<tr>" +
                                    "<td>" + item.parametre_adi + "</td>" +
                                    "<td>" + item.tetkik_sonucu + "</td>" +
                                    "<td>" + item.birimadi + "</td>" +
                                    "<td>" + item.alt_limit + ' - ' + item.ust_limit + "</td>" +
                                    "<td>" + tektrar_calisma + "</td>" +
                                    "<td>" + yeniden_calisilma_neden + "</td>" +
                                    "</tr>");
                            });
                        }
                    });
                });

            });

            $("body").off("click", ".iconudegistir").on("click", ".iconudegistir", function () {
                var classvarmi = $(this).hasClass('ekle');
                if (classvarmi) {
                    $(this).removeClass('fa-circle-minus');
                    $(this).addClass('fa-circle-plus');
                    $(this).removeClass('ekle');
                    $(this).removeClass('kapali');
                    $(this).addClass('acik');


                } else {
                    $(this).addClass('fa-circle-minus');
                    $(this).removeClass('fa-circle-plus');
                    $(this).addClass('ekle');
                    // $(this).removeAttr("style")
                    $(this).removeClass('acik');
                    $(this).addClass('kapali');
                }

            });


            $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=sonuc_onaylama_hasta_bilgileri_form", {"service_requestsid": service_requestsid}, function (getveri) {
                $('#sonuc_onaylama_hasta_bilgileri').html(getveri);
            })

            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onaylama_hasta_tani_bilgileri", {"protocol_number": protocol_number}, function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    hasta_tani_bilgileri.clear().draw(false);
                    json.forEach(function (item) {
                        hasta_tani_bilgileri.row.add([item.diagnoses_name]).draw(false);
                    });
                } else {
                    alertify.warning("Bir Hata Oluştu!");
                }
            });

            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onaylama_numune_listesi_icerik", {"service_requestsid": service_requestsid}, function (result) {
                if (result != 2) {
                    var json = JSON.parse(result);
                    sonuc_onay_numune_listesi_icerik.clear().draw(false);
                    json.forEach(function (item) {
                        var alim_tarihi = item.numune_alim_tarihi;
                        var tarih_numune_alim = moment(alim_tarihi).format('DD/MM/YY H:mm:ss');
                        if (tarih_numune_alim == "Invalid date") {
                            tarih_numune_alim = "";
                        }

                        var kabul_tarihi = item.numune_kabul_tarihi;
                        var tarih_numune_kabul = moment(kabul_tarihi).format('DD/MM/YY H:mm:ss');
                        if (tarih_numune_kabul == "Invalid date") {
                            tarih_numune_kabul = "";
                        }
                        let eklenenSatir = sonuc_onay_numune_listesi_icerik.row.add([item.barkod_numarasi, item.tup_kap_adi, tarih_numune_alim, item.numune_alan_kullanici, tarih_numune_kabul, item.kabul_eden_kullanici, item.sartli_kabul_eden_kullanici, item.sartli_kabul_aciklama]).draw(false).node();

                        $(eklenenSatir).attr('service-requestsid', item.service_requestsid)
                        $(eklenenSatir).attr('tube-id', item.tup_kap_tipi)
                    });
                } else {
                    alertify.warning("Bir Hata Oluştu!");
                }
            });


        });


        var sonuc_onay_numune_listesi_icerik = $('#sonuc_onay_numune_listesi_icerik_datatable').DataTable({
            // deferRender: true,
            scrollY: '25vh',
            scrollX: true,
            // "serverSide": true,
            "info": false,
            "paging": false,
            // "deferLoading": 0,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            "createdRow": function (row, data, dataIndex) {
                $(row).addClass('sonuc_onay_numune_icerik_sec');
            },
        });

        $("body").off("click", ".sonuc_onay_numune_icerik_sec").on("click", ".sonuc_onay_numune_icerik_sec", function (e) {
            if ($(this).css('background-color') != 'rgb(147, 203, 198)') {
                $(this).addClass("text-white");
                $(this).css("background-color", "rgb(147, 203, 198)");
                $(this).addClass("sec1233");
            } else {
                $(this).css("background-color", "rgb(255, 255, 255)");
                $(this).addClass("text-dark");
                $(this).removeClass("text-white");
                $(this).removeClass("sec1233");
            }

            var ID = [];
            $(".sec1233").off().each(function () {
                ID.push($(this).attr('tube-id'));
            });

            // alert(ID);

            var service_requests = $(this).attr("service-requestsid");

            function format(d) {
                // console.log(d[0].split('/')[1]);
                return (
                    '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>Alt Parametre</th>' +
                    '<th>Sonuç</th>' +
                    '<th>Birim</th>' +
                    '<th>Referans Aralığı</th>' +
                    '<th>Tekrar Çalışma Sayısı</th>' +
                    '<th>Tekrar Çalışılma Nedeni</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody class="eklenecek' + d[0].split('/')[1] + '"></tbody>' +
                    '</table>'
                );
            }

            $(document).ready(function () {
                $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {
                    'ID': ID,
                    'service_requestsid': service_requests
                }, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        test_dt.rows([]).clear().draw();
                        json.forEach(function (item) {
                            var basilacak = "";
                            if (item.tetkik_sonuc_durum == 0) {
                                basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>";
                            } else if (item.tetkik_sonuc_durum == 1) {
                                basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 40%; margin-right: 40%;'></i>";
                            } else if (item.tetkik_sonuc_durum == 2) {
                                basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>";
                            }

                            let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/" + item.idsi + "/'></i>", basilacak, item.tetkik_adi, item.sonuc_tarihi, item.laborant_onay_tarihi, item.onaylayan_laborant, item.lab_uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                            $(row).attr("data-id", item.idsi);
                        });
                    }
                });

                $("body").off("click", ".treeTable_icerik").on("click", ".treeTable_icerik", function () {
                    var id = $(this).attr("data-id");
                    var tr = $(this).closest('tr');
                    var row = test_dt.row(tr);
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler", {patient_promptsid: id}, function (result) {
                        if (result != 2) {
                            var json = JSON.parse(result);
                            json.forEach(function (item) {

                                var tektrar_calisma = item.tektrar_calisma_sayisi;
                                if (tektrar_calisma == null) {
                                    tektrar_calisma = "";
                                }

                                var yeniden_calisilma_neden = item.yeniden_calisilma_nedeni;
                                if (yeniden_calisilma_neden == null) {
                                    yeniden_calisilma_neden = "";
                                }


                                $(".eklenecek" + id).append(
                                    "<tr>" +
                                    "<td>" + item.parametre_adi + "</td>" +
                                    "<td>" + item.tetkik_sonucu + "</td>" +
                                    "<td>" + item.birimadi + "</td>" +
                                    "<td>" + item.alt_limit + ' - ' + item.ust_limit + "</td>" +
                                    "<td>" + tektrar_calisma + "</td>" +
                                    "<td>" + yeniden_calisilma_neden + "</td>" +
                                    "</tr>");
                            });
                        }
                    });
                });
            });

            $("body").off("click", ".iconudegistir").on("click", ".iconudegistir", function () {
                var classvarmi = $(this).hasClass('ekle');
                if (classvarmi) {
                    $(this).removeClass('fa-circle-minus');
                    $(this).addClass('fa-circle-plus');
                    $(this).removeClass('ekle');
                    $(this).removeClass('kapali');
                    $(this).addClass('acik');

                } else {
                    $(this).addClass('fa-circle-minus');
                    $(this).removeClass('fa-circle-plus');
                    $(this).addClass('ekle');
                    // $(this).removeAttr("style")
                    $(this).removeClass('acik');
                    $(this).addClass('kapali');
                }
            });
        });

        $("body").off("click", ".sec_detay_liste").on("click", ".sec_detay_liste", function () {
            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.sec_detay_liste-kaldir').removeClass("text-white");
            $('.sec_detay_liste-kaldir').removeClass("sec_detay_liste-kaldir");
            $('.sec_detay_liste').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white sec_detay_liste-kaldir");

            var patient_promptsid = $(this).attr("data-id");

            $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari", {"patient_promptsid": patient_promptsid}, function (getveri) {
                $('#sonuc_onaylama_buttons').html(getveri);
            })
        });

    </script>

<?php } ?>
