<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];
if($islem=="listeyi-getir"){ ?>

    <div class="col-12">
        <div class="lab_modal_lg_icerik"></div>
        <div class="card">
            <div class="card-body row mt-2">

                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Başlangıç Tarihi</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control form-control-xs" type="date" id="tarih_baslangic" value="<?php echo date("Y-m-d");?>">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-5">
                            <label>Bitiş Tarihi</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control form-control-xs" type="date" id="tarih_bitis" value="<?php echo date("Y-m-d");?>">
                        </div>
                    </div>

                </div>

                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Kabul Durumu</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select kabul_durum_bilgisi" id="numune_kabul_durum">
                                <option selected disabled class="text-white bg-danger" title="Tüm kayıtları görmek için seçiniz">Kabul Durumu Seçiniz..</option>
                                <option value="3">Tümünü Gör</option>
                                <option value="1">Kabul Edildi</option>
                                <option value="0">Kabul Edilemedi</option>
                                <option value="2">Reddedildi</option>

                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-5">
                            <label>Barkod</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text" id="barkod_num">
                        </div>
                    </div>

                </div>

                <div class="col-3 row" align="center">

                    <div class="row mt-2">
                        <div class="col-5">
                            <label>  Otomatik Kabul</label>
                        </div>

                        <div class="col-7">
                            <input type="checkbox" checked id="oto_kabul">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>  Otomatik Barkod</label>
                        </div>

                        <div class="col-7">
                            <input type="checkbox" id="oto_barkod">
                        </div>
                    </div>

                </div>

                <div class="col-1 row">

                    <div class="mt-5" align="center">

                        <button class="btn btn-sm btn-success btn_bilgileri_sorgula" style="height: 25px;"><b><i class="fa-regular fa-magnifying-glass"></i> Sorgula</b></button>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="col-12 row mt-2">

        <div class="col-8">

            <div class="card">
                <div class="card-header p-1" align="left" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tüp Listesi</b></label></div>
                <div class="card-body">
                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_kabul_tup_listesi_datatable">
                        <thead style="position: relative;">
                        <tr>
                            <th title="Numune Kabul Durumu">Kabul</th>
                            <th title="Hasta Protokol Numarası">Protokol No</th>
                            <th title="Numune Barkod Numarası">Barkod</th>
                            <th title="Numune Alım Tarihi">N. Alım Tarihi</th>
                            <th title="Numune Alım Tarihi">N. Alım Personel</th>
                            <th title="Numune Kabul Tarihi">N. Kabul Tarihi</th>
                            <th title="Numune Kabul Eden Personel">N. Kabul Personel</th>
                            <th title="Numune Şartlı Kabul Eden Doktor">N. Şartlı Kabul Eden Dokor</th>
                            <th title="Numune Şartlı Kabul Açıklama">N. Şartlı Kabul Açıklama</th>
                            <th title="Numune Kabul Ret Tarihi">N. Kabul Ret Tarihi</th>
                            <th title="Numuneyi Reddeden Personel">N. Reddeden Personel</th>
                            <th title="Numune Kabul Ret Sebebi">N. Kabul Ret Sebebi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-4">

            <div class="card">
                <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tetkik Listesi</b></div>
                <div class="card-body">

                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_kabul_tetkik_listesi_datatable">
                        <thead style="position: relative;">
                        <tr>
                            <th>Adı</th>
                            <th>Açıklama</th>

                        </tr>
                        </thead>
                    </table>

                </div>
            </div>

        </div>

    </div>

    <div id="numune_kabul_buttonlar">

        <div class="col-12 mt-3" align="left">

            <button disabled class="btn btn-success btn_numune_kabul"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Numune Kabul</b></button>

            <button disabled class="btn btn-success btn_numune_sartli_kabul"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Şartlı Kabul</b></button>

            <button disabled class="btn btn-success btn_numune_kabul_iptal"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Numune Kabul İptal</b></button>

            <button disabled class="btn btn-success btn_numune_ret"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Numune Ret</b></button>

            <button disabled class="btn btn-success btn_numune_ret_iptal"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Numune Ret İptal</b></button>

            <button disabled class="btn btn-success btn_tup_bilgisi"><i class="fa-sharp fa-solid fa-memo"></i>  <b>Tüp Bilgisi</b></button>

            <button disabled class="btn btn-success btn_barkod_bas"><i class="fa-sharp fa-solid fa-barcode"></i>  <b>Barkod Bas</b></button>

        </div>

    </div>

    <div class="mt-3"></div>

    <script>
        var input = document.getElementById("barkod_num");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('.btn_bilgileri_sorgula').trigger("click");
            }
        });

        $("body").off("click", ".btn_bilgileri_sorgula").on("click", ".btn_bilgileri_sorgula", function (e) {
            var tarih1 = $("#tarih_baslangic").val();
            var tarih2 = $("#tarih_bitis").val();
            var kabul_durum = $("#numune_kabul_durum").val();
            var barkod_num = $("#barkod_num").val();

            if(barkod_num==''){

                if(kabul_durum==null || kabul_durum==''){

                    numune_kabul_tup_listesi.ajax.url('ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_tarih_filtre&tarih1="' + tarih1 + '"&tarih2="' + tarih2 + '"').load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar",function(getveri){
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }

                else if (kabul_durum=='3'){

                    numune_kabul_tup_listesi.ajax.url('ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_tarih_filtre&tarih1="' + tarih1 + '"&tarih2="' + tarih2 + '"').load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar",function(getveri){
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }

                else {

                    numune_kabul_tup_listesi.ajax.url('ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_durum_filtre&kabul_durum="' + kabul_durum + '"&tarih1="' + tarih1 + '"&tarih2="' + tarih2 + '"').load();

                    numune_kabul_tetkik_listesi.rows([]).clear().draw();

                    $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar",function(getveri){
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }

            }else {

                if($('#oto_kabul').is(':checked')){
                    $.ajax({
                        type: 'POST',
                        url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul',
                        data: {barkod_num:barkod_num},
                        success:function(e){
                            $('#sonucyaz').html(e);

                            numune_kabul_tup_listesi.ajax.url('ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_barkoda_gore&barkod_num="'+barkod_num+'"').load();

                            $.get("ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_tetkik",{barkod_num:barkod_num},function (result){
                                if (result != 2){
                                    var json = JSON.parse(result);
                                    numune_kabul_tetkik_listesi.clear().draw(false);
                                    json.forEach(function (item){
                                        numune_kabul_tetkik_listesi.row.add([item.analysis_name,item.analysis_explanation]).draw(false);
                                    });
                                } else{
                                    alertify.warning("Barkod Numarası Bulunamadı!");
                                }
                            });

                            $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar",function(getveri){
                                $('#numune_kabul_buttonlar').html(getveri);
                            })

                        }
                    });
                }

                else {

                    numune_kabul_tup_listesi.ajax.url('ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_barkoda_gore&barkod_num="'+barkod_num+'"').load();

                    var barkod_num2 = $("#barkod_num").val();
                    $.get("ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_tetkik",{barkod_num:barkod_num2},function (result){
                        if (result != 2){
                            var json = JSON.parse(result);
                            numune_kabul_tetkik_listesi.clear().draw(false);
                            json.forEach(function (item){
                                numune_kabul_tetkik_listesi.row.add([item.analysis_name,item. analysis_explanation]).draw(false);
                            });
                        } else{
                            alertify.warning("Barkod Numarası Bulunamadı!");
                        }
                    });

                    $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_disabled_buttonlar",function(getveri){
                        $('#numune_kabul_buttonlar').html(getveri);
                    })
                }
            }
        });

        var numune_kabul_tup_listesi = $('#numune_kabul_tup_listesi_datatable').DataTable({
            deferRender: true,
            scrollY: '59vh',
            scrollX: true,
            "info":false,
            "paging":false,
            "searching":false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=numune_kabul_numune_tup_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                if(aData['sample_acceptance_status'] == 0) {

                    $('td:eq(0)', nRow).html("<i title='Kabul veya Ret Bekliyor' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>");

                }
                else if (aData['sample_acceptance_status'] == 1) {

                    $('td:eq(0)', nRow).html("<i title='Kabul Edildi' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>");

                }
                else if (aData['sample_acceptance_status'] == 2) {

                    $('td:eq(0)', nRow).html("<i title='Kabul Reddedildi' class='fa-solid fa-ban fa-lg col-9' style='color: #e80c4d; margin-left: 40%; margin-right: 40%;'></i>");

                }

                $(nRow)
                    .attr('id',aData['id'])
                    .attr('barkod_no',aData['service_requests_bardoce'])
                    .attr('class','numune_kabul_tup_sec')
            },

            "initComplete": function (settings, json) { },

            columns:[
                {data:'sample_acceptance_status'},
                {data:'protocol_number'},
                {data:'service_requests_bardoce'},
                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var numune_alim_tarihi = data.sample_date;
                            var alim_tarihi= moment(numune_alim_tarihi).format('DD/MM/YYYY H:mm:ss');
                            if (alim_tarihi == "Invalid date"){
                                alim_tarihi = "";
                            }
                            return alim_tarihi;
                        }
                    }
                },
                {data:'numune_alan_kullanici'},
                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var numune_kabul_tarihi = data.sample_acceptance_date;
                            var kabul_tarihi= moment(numune_kabul_tarihi).format('DD/MM/YYYY H:mm:ss');
                            if (kabul_tarihi == "Invalid date"){
                                kabul_tarihi = "";
                            }
                            return kabul_tarihi;
                        }
                    }
                },
                {data:'kabul_eden_kullanici'},
                {data:'sartli_kabul_eden_kullanici'},
                {data:'sartli_kabul_aciklama'},
                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var numune_kabul_ret_tarihi = data.sample_acceptance_rejection_date;
                            var kabul_ret_tarihi= moment(numune_kabul_ret_tarihi).format('DD/MM/YYYY H:mm:ss');
                            if (kabul_ret_tarihi == "Invalid date"){
                                kabul_ret_tarihi = "";
                            }
                            return kabul_ret_tarihi;
                        }
                    }
                },
                {data:'ret_eden_kullanici'},
                {data:'ret_nedeni'},
            ],
        });

        $("body").off("click", ".numune_kabul_tup_sec").on("click", ".numune_kabul_tup_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.numune_kabul_tup_sec-kaldir').removeClass("text-white");
            $('.numune_kabul_tup_sec-kaldir').removeClass("numune_kabul_tup_sec-kaldir");
            $('.numune_kabul_tup_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white numune_kabul_tup_sec-kaldir");

            var barkod_no=$('.numune_kabul_tup_sec-kaldir').attr('barkod_no');

            $.get("ajax/laboratuvar/numune-kabul/sql-numunekabul.php?islem=sql_numune_kabul_tetkik",{barkod_num:barkod_no},function (result){
               if (result != 2){
                   var json = JSON.parse(result);
                   numune_kabul_tetkik_listesi.clear().draw(false);
                   json.forEach(function (item){
                       numune_kabul_tetkik_listesi.row.add([item.analysis_name,item.analysis_explanation]).draw(false);
                   });
               } else{
                   alertify.warning("Bilinmeyen Bir Hata Oluştu!");
               }
            });

            $.get( "ajax/laboratuvar/numune-kabul/modal-numunekabul.php?islem=numune_kabul_buttonlari",{"barkod_no":barkod_no},function(getveri){
                $('#numune_kabul_buttonlar').html(getveri);
            })

        });

        var numune_kabul_tetkik_listesi = $('#numune_kabul_tetkik_listesi_datatable').DataTable({
            scrollY: '59vh',
            scrollX: true,
            "info":false,
            "paging":false,
            "searching":false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"}
        });

    </script>

<?php } ?>
