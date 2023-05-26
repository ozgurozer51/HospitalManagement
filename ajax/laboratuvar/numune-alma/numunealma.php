 <?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];
if($islem=="listeyi-getir"){ ?>

    <div class="col-12">
        <div class="row">

            <div class="col-3">

                <div class="card">
                    <div class="card-body"  style="height: 26vh;">

                        <div class="row mt-1">
                            <div class="col-5">
                                <label>Barkod</label>
                            </div>
                            <div class="col-7">
                                <input class="d-flex flex-column form-control form-control-xs" type="text" id="barkod_no">
                            </div>
                        </div>


                        <div class="row mt-1">
                            <div class="col-5">
                                <label>  Otomatik Alım</label>
                            </div>
                            <div class="col-7">
                                <input type="checkbox" checked id="otomatik_al">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-5">
                                <label>Kanalma Birim</label>
                            </div>
                            <div class="col-7">
                                <div class="input-group ">
                                    <input type="text" class="form-control form-control-xs" aria-describedby="basic-addon2" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm" id="kan_alan_birim" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-5">
                                <label>Oda</label>
                            </div>
                            <div class="col-7">
                                <div class="input-group ">
                                    <input type="text" class="form-control form-control-xs" aria-describedby="basic-addon2" disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning btn-sm" id="kan_alinan_oda" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-6" align="left">
                                <button class="btn btn-success btn_siradaki_hastayi_cagir"><b><i class="fa-regular fa-user"></i> Sıradaki Hasta</b></button>
                            </div>

                            <div class="col-6" align="right">
                                <button class="btn btn-success btn_barkod_sorgula"><b><i class="fa-regular fa-magnifying-glass"></i> Sorgula</b></button>
                            </div>



                        </div>

                    </div>
                </div>

            </div>

            <div class="col-9">

                <div id="from_hasta_bilgileri">
                    <div class="card">
                        <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black"><b>Hasta Bilgileri</b></div>
                    <div class="card-body" style="height: 23vh">


                        <div class="row ">

                            <div class="col-4">

                                <div class="row">
                                    <div class="col-5">
                                        <label>Protokol No</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="protokol_no">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Adı Soyadı</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="adi_soyadi">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>TC Kimlik No</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="tc_id">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Kurum</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="krum">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-5">
                                        <label>Kan Grubu</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="kan">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Müracaat Tarihi</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="muracaat_tarihi">
                                    </div>
                                </div>

                            </div>

                            <div class="col-4">

                                <div class="row">
                                    <div class="col-5">
                                        <label>Birim</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="birim">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Doktor</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="doktor">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Vaka Türü</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="vaka_turu">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Kabul Şekli</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="kabul_sekli">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        <label>Oda/Yatak</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="oda_yatak">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-5">
                                        <label>Yatış Tarihi</label>
                                    </div>
                                    <div class="col-7">
                                        <input readonly class="form-control form-control-xs" type="text" id="yatis_tarihi">
                                    </div>
                                </div>

                            </div>

                            <div class="col-4">

                                <div align="center">
                                    <img style="width: 50%;" class="rounded h-80 mt-2  img-thumbnail" src="assets/img/dummy-user.jpeg">
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                </div>

            </div>

        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="row">

            <div class="col-4">

                    <div class="card">
                        <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Tüp Listesi</b></div>
                        <div class="card-body" style="height: 23vh">

                            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_alma_tup_tablosu_datatable">
                                <thead style="position: relative;">
                                <tr>
                                    <th>Alım</th>
                                    <th>Barkod</th>
                                    <th title="Numune Alım Tarihi">N. Alım Tarihi</th>
                                    <th title="Numune Alım Ret Sebebi">N. Alım Ret</th>
                                    <th>Tüp Rengi</th>
                                    <th>Tüp Grubu</th>
                                    <th title="Numune Alım Ret İşlemi İptal Tarihi">N. Alım Ret İptal T.</th>
                                    <th title="Numune Alım Ret İşlemi İptal Eden Personel">N. Alım Ret İptal Personel</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>


            </div>

            <div class="col-4">

                <div class="card">
                    <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Tetkik Listesi</b></div>
                    <div class="card-body" style="height: 23vh">

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_alma_tetkik_tablosu_datatable">
                            <thead>
                            <tr>
                                <th>Adı</th>
                                <th>Açıklama</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>

            </div>

            <div class="col-4">

                <div class="card">
                    <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Diğer Tüp Listesi</b></div>
                    <div class="card-body" style="height: 23vh">

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_alma_diger_tup_tablosu_datatable">
                            <thead>
                            <tr>
                                <th>Tüp Adı</th>
                                <th>Barkod</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <div id="tup_buttonlari_numune_alma">

        <div class="col-12 mt-2" align="left">
            <button disabled class="btn btn-success numune_al"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Al</b></button>
            <button disabled class="btn btn-success numune_reddet"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Reddet</b></button>
        </div>

    </div>

    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label>&nbsp;</label></div>
            <div class="card-body" style="height: 26vh">




                    <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
                        <li class="nav-item col-xl-3" role="presentation">
                            <button class="nav-link w-100 active up-btn numune_alim_liste_tab" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#numune_alim_listesi" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Numune Alım Listesi</button>
                        </li>

                        <li class="nav-item col-xl-3" role="presentation">
                            <button class="nav-link w-100 numune_alim_liste_tab"  id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#gecmis_numune_listesi" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Hastanın Geçmiş Numune Listesi</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="numune_alim_listesi" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">

                            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_alim_liste_datatable">
                                <thead>
                                <tr>
                                    <th>Barkod</th>
                                    <th>Tüp Adı</th>
                                    <th>Birim Adı</th>
                                    <th>Numune Alım Personel</th>
                                    <th>Numune Alım Tarihi</th>
                                </tr>
                                </thead>
                            </table>

                        </div>

                        <div class="tab-pane fade" id="gecmis_numune_listesi" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

                            <div class="col-12 row">

                                <div class="col-3">

                                    <div class="card">
                                        <div class="card-body" style="height: 20vh">

                                        <form id="gecmis_numune_tarih_filtrele">

                                            <div class="row mt-1">
                                                <div class="col-5">
                                                    <label>Başlangıç Tarihi</label>
                                                </div>
                                                <div class="col-7">
                                                    <input class="form-control form-control-xs" type="date" id="gecmis_numune_baslangic_tarihi" value="<?php echo date("Y-m-d");?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-5">
                                                    <label>Bitiş Tarihi</label>
                                                </div>
                                                <div class="col-7">
                                                    <input class="form-control form-control-xs" type="date" id="gecmis_numune_bitis_tarihi" value="<?php echo date("Y-m-d");?>">
                                                </div>
                                            </div>

                                        </form>

                                            <div class="row mt-3">
                                                <div class="col-6" align="left">
                                                    <button class="btn btn-danger gecmis_numune_tarih_filtrele_reset"><b><i class="fa-regular fa-xmark"></i> Temizle</b></button>
                                                </div>

                                                <div class="col-6" align="right">
                                                    <button class="btn btn-success btn_numune_alma_tarih_sorgula"><b><i class="fa-regular fa-magnifying-glass"></i> Sorgula</b></button>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-9">

                                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="hastanin_gecmis_numune_listesi_datatable">
                                        <thead>
                                        <tr>
                                            <th>Barkod</th>
                                            <th>Tüp Adı</th>
                                            <th>Birim Adı</th>
                                            <th>Numune Alım Personel</th>
                                            <th>Numune Alım Tarihi</th>
                                        </tr>
                                        </thead>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>



            </div>
        </div>
    </div>

<?php } ?>

<script>


    var input = document.getElementById("barkod_no");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            $('.btn_barkod_sorgula').trigger("click");

        }
    });

    $("body").off("click", ".btn_barkod_sorgula").on("click", ".btn_barkod_sorgula", function (e) {
        var barkod_no = $('#barkod_no').val();

        if (barkod_no == '') {
            alertify.warning('Barkod Numarası Giriniz');
        }else{
            if ($('#otomatik_al').is(':checked')) {
                $.get("ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_hasta_tup",{barkod_no:barkod_no},function (result){
                    if (result != 2){
                        if (result == 404){
                            alertify.warning("Barkod Bilgisi Bulunamadı");
                        }else{
                            $.ajax({
                                type: 'POST',
                                url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_otomatik_alim_checkbox',
                                data: {barkod_no: barkod_no},
                                success: function (e) {
                                    $('#sonucyaz').html(e);

                                    $.get("ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_hasta_tup",{barkod_no:barkod_no},function (result){
                                        if (result != 2){
                                            if (result == 404){
                                                alertify.warning("Barkod Bilgisi Bulunamadı");
                                            }else{
                                                var json = JSON.parse(result);
                                                numune_alma_tup_tablosu.rows([]).clear().draw();
                                                json.forEach(function (item){
                                                    var sampledatea = item.sample_date;
                                                    var numune_alim_tarihia = moment(sampledatea).format('DD/MM/YY H:mm:ss');
                                                    if (numune_alim_tarihia == "Invalid date"){
                                                        numune_alim_tarihia = "";
                                                    }


                                                    var iptaltarihia = item.sample_rejection_cancellation_date;
                                                    var numune_alim_iptal_tarihia = moment(iptaltarihia).format('DD/MM/YY H:mm:ss');
                                                    if (numune_alim_iptal_tarihia == "Invalid date"){
                                                        numune_alim_tarihia = "";
                                                    }

                                                    var basilacak = "";
                                                    if (item.sampling_confirmation == 1){
                                                        basilacak = "<i title='Onaylandı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>";
                                                    }else if (item.sampling_confirmation == 0){
                                                        basilacak = "<i title='Onay veya Ret Bekliyor' class='fa-solid fa-circle-xmark fa-lg'  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>";
                                                    }else if (item.sampling_confirmation == 2){
                                                        basilacak = "<i title='Reddedildi' class='fa-solid fa-ban fa-lg col-9' style='color: #e80c4d; margin-left: 40%; margin-right: 40%;'></i>";
                                                    }

                                                    numune_alma_tup_tablosu.row.add([basilacak,item.service_requests_bardoce,numune_alim_tarihia,item.ret,item.definition_name,item.group_name,numune_alim_iptal_tarihia,item.name_surname]).draw(false);
                                                });

                                                numune_alma_tetkik_tablosu.ajax.url(numune_alma_tetkik_tablosu.ajax.url()).load();

                                                numune_alma_diger_tup_tablosu.ajax.url(numune_alma_diger_tup_tablosu.ajax.url()).load();

                                                numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();

                                                hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();

                                                $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=hasta_bilgileri_from",{"barkod_no":barkod_no},function(getveri){
                                                    $('#from_hasta_bilgileri').html(getveri);
                                                })

                                            }
                                        }
                                    });

                                }
                            });
                        }
                    }
                });

            } else {
                $.get("ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_hasta_tup",{barkod_no:barkod_no},function (result){
                    if (result != 2){
                        if (result == 404){
                            alertify.warning("Barkod Bilgisi Bulunamadı");
                        }else{
                            var json = JSON.parse(result);
                            numune_alma_tup_tablosu.rows([]).clear().draw();
                            json.forEach(function (item){
                                var sampledate = item.sample_date;
                                var numune_alim_tarihi = moment(sampledate).format('DD/MM/YY H:mm:ss');
                                if (numune_alim_tarihi == "Invalid date"){
                                    numune_alim_tarihi = "";
                                }

                                var iptaltarihi = item.sample_rejection_cancellation_date;
                                var numune_alim_iptal_tarihi = moment(iptaltarihi).format('DD/MM/YY H:mm:ss');
                                if (numune_alim_tarihi == "Invalid date"){
                                    numune_alim_tarihi = "";
                                }

                                var basilacak = "";
                                if (item.sampling_confirmation == 1){
                                    basilacak = "<i title='Onaylandı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>";
                                }else if (item.sampling_confirmation == 0){
                                    basilacak = "<i title='Onay veya Ret Bekliyor' class='fa-solid fa-circle-xmark fa-lg'  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>";
                                }else if (item.sampling_confirmation == 2){
                                    basilacak = "<i title='Reddedildi' class='fa-solid fa-ban fa-lg col-9' style='color: #e80c4d; margin-left: 40%; margin-right: 40%;'></i>";
                                }

                                numune_alma_tup_tablosu.row.add([basilacak,item.service_requests_bardoce,numune_alim_tarihi,item.ret,item.definition_name,item.group_name,numune_alim_iptal_tarihi,item.name_surname]).draw(false);
                            });

                            numune_alma_tetkik_tablosu.ajax.url(numune_alma_tetkik_tablosu.ajax.url()).load();

                            numune_alma_diger_tup_tablosu.ajax.url(numune_alma_diger_tup_tablosu.ajax.url()).load();

                            numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();

                            hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();

                            $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=hasta_bilgileri_from",{"barkod_no":barkod_no},function(getveri){
                                $('#from_hasta_bilgileri').html(getveri);
                            })

                        }
                    }
                });
            }
        }
    });


    var numune_alma_tup_tablosu = $('#numune_alma_tup_tablosu_datatable').DataTable({
        // deferRender: true,
        scrollY: '16vh',
        scrollX: true,
        // "serverSide": true,
        "info":false,
        "paging":false,
        // "deferLoading": 0,
        "searching":false,
        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
        fnRowCallback:function (row){
            $(row).addClass("numune_alma_tup_sec");
        }
    });


    $("body").off("click", ".numune_alma_tup_sec").on("click", ".numune_alma_tup_sec", function (e) {
        $('.numune_al').attr('disabled', false);
        $('.numune_alim_iptal').attr('disabled', false);
        $('.numune_reddet').attr('disabled', false);
        $('.numune_ret_iptal').attr('disabled', false);

        $(this).css('background-color') != 'rgb(147,203,198)' ;
        $('.numune_alma_tup_sec-kaldir').removeClass("text-white");
        $('.numune_alma_tup_sec-kaldir').removeClass("numune_alma_tup_sec-kaldir");
        $('.numune_alma_tup_sec').css({"background-color": "rgb(255, 255, 255)"});
        $(this).css({"background-color": "rgb(147,203,198)"});
        $(this).addClass("text-white numune_alma_tup_sec-kaldir");
        var data = numune_alma_tup_tablosu.row(this).data();

        var barkod = data[1];

        $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=numune_alma_tup_buttonlari",{"barkod":barkod},function(getveri){
            $('#tup_buttonlari_numune_alma').html(getveri);
        })
    });



    var numune_alma_tetkik_tablosu = $('#numune_alma_tetkik_tablosu_datatable').DataTable({
        deferRender: true,
        scrollY: '16vh',
        scrollX: true,
        "serverSide": true,
        "info":false,
        "deferLoading": 0,
        "paging":false,

        "searching":false,
        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

        ajax: {
            url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_tup_tetkik',
            processing: true,
            method: 'GET',
            dataSrc: '',

            data: function(data){
                data.barkod_no = $('#barkod_no').val();
            },
        },

        "fnRowCallback": function (nRow, aData) {
            // $(nRow)
            //     .attr('id',aData['id'])
        },

        "initComplete": function (settings, json) {
        },

        columns:[
            {data:'analysis_name'},
            {data:'analysis_explanation'},
        ],
    });



    var numune_alma_diger_tup_tablosu = $('#numune_alma_diger_tup_tablosu_datatable').DataTable({
        deferRender: true,
        scrollY: '16vh',
        scrollX: true,
        "serverSide": true,
        "info":false,
        "deferLoading": 0,
        "paging":false,
        "searching":false,
        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

        ajax: {
            url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_diger_tup',
            processing: true,
            method: 'GET',
            dataSrc: '',

            data: function(data){
                data.barkod_no = $('#barkod_no').val();
            },
        },

        "fnRowCallback": function (nRow, aData) {
            // $(nRow)
            //     .attr('id',aData['id'])
        },

        "initComplete": function (settings, json) {
        },

        columns:[
            {data:'definition_name'},
            {data:'service_requests_bardoce'}
        ],
    });

    var numune_alim_liste = $('#numune_alim_liste_datatable').DataTable({
        deferRender: true,
        scrollY: '15vh',
        scrollX: true,
        "serverSide": true,
        "info":false,
        "deferLoading": 0,
        "paging":false,
        "searching":false,
        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

        ajax: {
            url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alim_listesi',
            processing: true,
            method: 'GET',
            dataSrc: '',

            data: function(data){
                data.barkod_no = $('#barkod_no').val();
            },
        },

        "fnRowCallback": function (nRow, aData) {
            // $(nRow)
            //     .attr('id',aData['id'])
        },

        "initComplete": function (settings, json) {
        },

        columns:[
            {data:'service_requests_bardoce'},
            {data:'definition_name'},
            {
                data:null,
                render:function (data){if(data!=2){return data.department_name}}
            },
            {data:'name_surname'},
            {
                data:null,
                render:function (data){
                    if (data != 2){
                        var sample_date1 = data.sample_date;
                        var sample_date11= moment(sample_date1).format('MM/DD/YYYY H:mm:ss');
                        if (sample_date11 == "Invalid date"){
                            sample_date11 = "";
                        }
                        return sample_date11;
                    }
                }
            },
        ],
    });

    var hastanin_gecmis_numune_listesi = $('#hastanin_gecmis_numune_listesi_datatable').DataTable({
        deferRender: true,
        scrollY: '16vh',
        scrollX: true,
        "serverSide": true,
        "info":false,
        "deferLoading": 0,
        "paging":false,
        "searching":false,
        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

        ajax: {
            url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_hastanin_gecmis_numune_listesi',
            processing: true,
            method: 'GET',
            dataSrc: '',

            data: function(data){
                data.barkod_no = $('#barkod_no').val();
            },
        },

        "fnRowCallback": function (nRow, aData) {
            // $(nRow)
            //     .attr('id',aData['id'])
        },

        "initComplete": function (settings, json) {
        },

        columns:[
            {data:'service_requests_bardoce'},
            {data:'definition_name'},
            {
                data:null,
                render:function (data){if(data!=2){return data.department_name}}
            },
            {data:'name_surname'},
            {
                data:null,
                render:function (data){
                    if (data != 2){
                        var sample_date2 = data.sample_date;
                        var sample_date22= moment(sample_date2).format('MM/DD/YYYY H:mm:ss');
                        if (sample_date22 == "Invalid date"){
                            sample_date22 = "";
                        }
                        return sample_date22;
                    }
                }
            },
        ],
    });

    $("body").off("click", ".gecmis_numune_tarih_filtrele_reset").on("click", ".gecmis_numune_tarih_filtrele_reset", function(e){
        document.getElementById("gecmis_numune_tarih_filtrele").reset();

        hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();
    });

    $("body").off("click", ".btn_numune_alma_tarih_sorgula").on("click", ".btn_numune_alma_tarih_sorgula", function(e){
        var baslangic_tarihi = $('#gecmis_numune_baslangic_tarihi').val();
        var bitis_tarihi = $('#gecmis_numune_bitis_tarihi').val();

        hastanin_gecmis_numune_listesi.ajax.url('ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_hasta_gesmi_numune_filtre&barkod_no="' + barkod_no + '"&baslangic_tarihi="' + baslangic_tarihi + '"&bitis_tarihi="' + bitis_tarihi + '"').load();

    });

    $("body").off("click", ".numune_alim_liste_tab").on("click", ".numune_alim_liste_tab", function (e) {
        $('.numune_alim_liste_tab').removeClass("text-white");
        $('.numune_alim_liste_tab').removeClass("up-btn");
        $(this).addClass("text-white");
        $(this).addClass("up-btn");
    });

</script>

