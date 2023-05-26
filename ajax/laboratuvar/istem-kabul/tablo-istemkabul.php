<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if($islem=="tb_istem_kabul_hasta"){?>
        <div class="row mt-2">
            <div class="col-6 row">
                <div class="col-4">
                    <label >Tarih</label>
                </div>
                <div class="col-8">
                    <input class="form-control form-control-sm form-sm tarih_bilgisi" type="date" id="tarih" value="<?php echo date("Y-m-d");?>">
                </div>
            </div>

            <div class="col-6 row">
                <div class="col-4">
                    <label>Durum</label>
                </div>
                <div class="col-8">
                    <select class="form-select form-select-sm durum_bilgisi" id="durum">
                        <option selected disabled class="text-white bg-danger" title="Tüm kayıtları görmek için seçiniz">Durum Seçiniz..</option>
                        <option value="3">Tümünü Gör</option>
                        <option value="1">Onaylandı</option>
                        <option value="0">Onaylanmadı</option>
                        <option value="2">Randevu</option>
                    </select>
                </div>
            </div>

            <script>
                $(".durum_bilgisi").change(function () {
                    var durum = $("#durum").val();
                    var tarih = $("#tarih").val();

                    if (durum == '3') {
                        istem_kabul_hasta_listesi.ajax.url('ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tarih_filtre&tarih="'+tarih+'"').load();
                    } else {
                        istem_kabul_hasta_listesi.ajax.url('ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_filtre&durum="' + durum + '"&tarih="' + tarih + '"').load();

                    }
                });

                $(".tarih_bilgisi").change(function () {
                    var durum = $("#durum").val();
                    var tarih = $("#tarih").val();

                    if (durum== null) {
                        istem_kabul_hasta_listesi.ajax.url('ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tarih_filtre&tarih="'+tarih+'"').load();
                    } else if (durum == '3') {
                        istem_kabul_hasta_listesi.ajax.url('ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tarih_filtre&tarih="'+tarih+'"').load();
                    }
                    else {
                        istem_kabul_hasta_listesi.ajax.url('ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_filtre&durum="' + durum + '"&tarih="' + tarih + '"').load();

                    }
                });
            </script>

        </div>

    <div class="mt-2"></div>
    <div class="card">
        <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>İstem Listesi</b></div>
        <div class="card-body" style="height: 74vh;">

            <div class="mt-1"></div>
            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="istem_kabul_hasta_listesi_datatable">
                <thead>
                <tr>
                    <th>Durum</th>
                    <th>İstem Tarihi ve Saati</th>
                    <th>Sıra No</th>
                    <th>TC Kimlik No</th>
                    <th>Protokol No</th>
                    <th>Adı Soyadaı</th>
                    <th title="İstek Yapılan Birim">Birim</th>
                </tr>
                </thead>
            </table>
            <div class="row mt-3">
                <div class="col-8 align-self-center" id="istem_kabul_buttonlari">
                    <button disabled class="btn btn-success"><i class="fa-sharp fa-solid fa-memo"></i> Kabul Et</button>
                    <button disabled class="btn btn-success "><i class="fa-sharp fa-solid fa-memo"></i> Hasta Kartı</button>
                    <button disabled class="btn btn-success"><i class="fa-sharp fa-solid fa-memo"></i> Barkod</button>

                </div>
                <div class="col-4 align-self-center">
                <input type="checkbox" checked value="1"> Oto. Barkod Çıkart
                </div>

            </div>
        </div>
    </div>

    <script>
        var istem_kabul_hasta_listesi = $('#istem_kabul_hasta_listesi_datatable').DataTable({
            deferRender: true,
            scrollY: '58vh',
            scrollX: true,
            "info":true,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta',
                processing: true,
                method: 'GET',
                dataSrc: ''
            },

            "fnRowCallback": function (nRow, aData) {
                if (aData['prompt_status'] == 1) {

                    $('td:eq(0)', nRow).html("<i class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>");
                } else if (aData['prompt_status'] == 0) {
                    $('td:eq(0)', nRow).html("<i  class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>");
                }
                else if(aData['prompt_status'] == 2) {
                    $('td:eq(0)', nRow).html("<i  class='fa-solid fa-calendar-days fa-lg  '  style='color: black; margin-left: 8%; margin-right: 8%; ' ></i><i  class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d;' ></i>");

                }
                $(nRow)
                    .attr('id',aData['id'])
                    .attr('class','hizmet_istem_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns:[
                {data:'prompt_status'},
                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var tarih = data.insert_datetime;
                            var split1 = tarih.split(" ");
                            var split2 = split1[0];
                            var split3 = split2.split("-");
                            var gun = split3[2];
                            var ay = split3[1];
                            var yil = split3[0];
                            var arr = [gun,ay,yil];
                            var implode = arr.join("/");
                            return implode + " " +split1[1];
                        }
                    }
                },

                {
                    data:null,
                    render:function (data){if(data!=2){return data.patient_queue}}
                },
                {data:'tc_id'},
                {data:'protocol_id'},
                {
                    data: null,
                    render: function (data) { return  data.patient_name + " " + data.patient_surname; }
                },
                {data:'department_name'},
            ],
        });

        $("body").off("click", ".hizmet_istem_sec").on("click", ".hizmet_istem_sec", function (e) {
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.hizmet_istem_sec-kaldir').removeClass("text-white");
            $('.hizmet_istem_sec-kaldir').removeClass("hizmet_istem_sec-kaldir");
            $('.hizmet_istem_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white hizmet_istem_sec-kaldir");

            var service_requests = $(this).attr("id")
            $.get( "/ajax/laboratuvar/istem-kabul/tablo-istemkabul.php?islem=tb_istem_kabul_hasta_tup",{"service_requests":service_requests},function(getveri){
                $('#tablo_istem_kabul_hasta_tup').html(getveri);
            });

            $.get( "/ajax/laboratuvar/istem-kabul/tablo-istemkabul.php?islem=istem_kabul_button",{"service_requests":service_requests},function(getveri){
                $('#istem_kabul_buttonlari').html(getveri);
            });
        });
    </script>

<?php }
else if($islem=="istem_kabul_button"){
    $service_requests = $_GET['service_requests'];?>
    <?php $prompt_status = singularactive("patient_service_requests","id",$service_requests);
    if($prompt_status['prompt_status']=='0'){?>

        <button class="btn btn-success btn_istem_kabulet" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Kabul Et</button>

        <button class="btn btn-success btn_istem_hastakart" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Hasta Kartı</button>

        <button class="btn btn-success btn_istem_barkod" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Barkod</button>

    <?php }

    else if($prompt_status['prompt_status']=='1'){ ?>
        <button class="btn btn-success btn_istem_reddet" id="<?php echo$service_requests;?>"> <i class="fa-sharp fa-solid fa-memo"></i> Reddet</button>

        <button class="btn btn-success btn_istem_hastakart" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Hasta Kartı</button>

        <button class="btn btn-success btn_istem_barkod" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Barkod</button>

    <?php }

    else if($prompt_status['prompt_status']=='2'){ ?>
        <button class="btn btn-success btn_istem_kabulet" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Kabul Et</button>

        <button class="btn btn-success btn_istem_hastakart" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Hasta Kartı</button>

        <button class="btn btn-success btn_istem_barkod" id="<?php echo $service_requests;?>"><i class="fa-sharp fa-solid fa-memo"></i> Barkod</button>

    <?php } ?>

    <script>
        $("body").off("click", ".btn_istem_kabulet").on("click", ".btn_istem_kabulet", function(e){
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: 'ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_istem_kabulet',
                data: {id:id},
                success:function(e){
                    $('#sonucyaz').html(e);
                    istem_kabul_hasta_listesi.ajax.url(istem_kabul_hasta_listesi.ajax.url()).load();
                    istem_kabul_tup_bilgi.ajax.url(istem_kabul_tup_bilgi.ajax.url()).load();
                }
            });
        });

        $("body").off("click", ".btn_istem_reddet").on("click", ".btn_istem_reddet", function(e){
            var id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: 'ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_istem_reddet',
                data: {id:id},
                success:function(e){
                    $('#sonucyaz').html(e);
                    istem_kabul_hasta_listesi.ajax.url(istem_kabul_hasta_listesi.ajax.url()).load();
                    istem_kabul_tup_bilgi.ajax.url(istem_kabul_tup_bilgi.ajax.url()).load();
                }
            });
        });
        $("body").off("click", ".btn_istem_hastakart").on("click", ".btn_istem_hastakart", function(e){
            var id = $(this).attr('id');


            $('.tanimlamalar_w50_h35').window('setTitle', 'Hasta Kartı');
            $('.tanimlamalar_w50_h35').window('open');
            $('.tanimlamalar_w50_h35').window('refresh', 'ajax/laboratuvar/istem-kabul/modal-istemkabul.php?islem=modal_istem_kabul_hasta_kart&id='+id+'');
        });
    </script>

<?php }
else if($islem=="tb_istem_kabul_hasta_tup"){
    $service_requests = $_GET['service_requests'];?>

    <div class="card mt-2">

        <input type="hidden" value="<?php echo $service_requests; ?>" id="gel-id">

        <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Tüp Bilgileri</b></div>
        <div class="card-body" style="height: 32vh;">
            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="istem_kabul_tup_bilgi_datatb">
                <thead>
                <tr>
                    <th>Barkod</th>
                    <th>Kabul Tarihi</th>
                    <th>Tüp/Kap Adı</th>
                    <th>Grup Adı</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
        var service_requests = $("#gel-id").val();


        var istem_kabul_tup_bilgi = $('#istem_kabul_tup_bilgi_datatb').DataTable({
            deferRender: true,
            scrollY: '22vh',
            scrollX: true,
            "info":false,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tup&service_requests="'+service_requests+'"',
                processing: true,
                method: 'GET',
                dataSrc: ''
            },

            "fnRowCallback": function (nRow, aData) {
                var $nRow = $(nRow);
                $asd = `${aData['tube_container_type']}`;
                $nRow.attr("tube-type", $asd);

                return nRow;
            },

            "createdRow": function (row, data, dataIndex) {
                $(row).addClass('istem_kabul_tup_sec');
            },

            "initComplete": function (settings, json) {



            },

            columns:[
                {data:'service_requests_bardoce'},
                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var approval_date = data.service_request_approval_date;
                            var kabul_tarihi= moment(approval_date).format('MM/DD/YYYY H:mm:ss');
                            if (kabul_tarihi == "Invalid date"){
                                kabul_tarihi = "";
                            }
                            return kabul_tarihi;
                        }
                    }
                },
                {data:'definition_name'},
                {data:'group_name'},
            ],
        });
        //
        // $.get("ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tup",{service_requests:service_requests},function (result){
        //     if (result != 2){
        //         var json = JSON.parse(result);
        //         json.forEach(function (item){
        //             istem_kabul_tup_bilgi.row.add([item.service_requests_bardoce,item.definition_name,item.group_name]).draw(false);
        //         });
        //     }
        // });


        $("body").off("click", ".istem_kabul_tup_sec").on("click", ".istem_kabul_tup_sec", function (e) {

            if ($(this).css('background-color') != 'rgb(147, 203, 198)') {
                $(this).addClass("text-white");
                $(this).css("background-color", "rgb(147, 203, 198)");
                $(this).addClass("sec123");
            }else{
                $(this).css("background-color", "rgb(255, 255, 255)");
                $(this).addClass("text-dark");
                $(this).removeClass("text-white");
                $(this).removeClass("sec123");
            }

            var ID = [];
            $(".sec123").off().each(function () {
                ID.push($(this).attr('tube-type'));
            });

            var service_requests = $("#gel-id").val();
            istem_kabul_tetkik_bilgi.ajax.url("/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tup_tetkik&service_requests="+service_requests+"&ID="+ID+"").load();

        });


        var tup_id = $("#tup_id").val();
        var istem_kabul_tetkik_bilgi = $('#istem_kabul_tetkik_bilgi_datatb').DataTable({
            deferRender: true,
            scrollY: '27vh',
            scrollX: true,
            "info":false,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                processing: true,
                method: 'GET',
                url:'/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tup_tetkik&service_requests="'+service_requests+'"',
                dataSrc: ''
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('class','istem_kabul_tektik_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns:[
                {data:'analysis_name'},
                {data:'analysis_explanation'},
            ],
        });

        // $.get("ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_tup_tetkik",{"service_requests":service_requests,"tup_id":tup_id},function (result){
        //     if (result != 2){
        //         var json = JSON.parse(result);
        //         json.forEach(function (item){
        //             istem_kabul_tetkik_bilgi.row.add([item.analysis_name,item.analysis_explanation]).draw(false);
        //         });
        //     }
        // });

        $("body").off("click", ".istem_kabul_tektik_sec").on("click", ".istem_kabul_tektik_sec", function (e) {
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.istem_kabul_tektik_sec-kaldir').removeClass("text-white");
            $('.istem_kabul_tektik_sec-kaldir').removeClass("istem_kabul_tektik_sec-kaldir");
            $('.istem_kabul_tektik_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white istem_kabul_tektik_sec-kaldir");

        });
    </script>

    <div class="card">
        <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Tetkik Bilgileri</b></div>
        <div class="card-body" style="height: 39vh;">
            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="istem_kabul_tetkik_bilgi_datatb">
                <thead>
                <tr>
                    <th>Tetkik Adı</th>
                    <th>Açıklama</th>
                </thead>
            </table>
        </div>
    </div>

<?php }

else if($islem=="tb_istem_kabul_tup_tetkik"){
    $service_requests = $_GET['service_requests'];
    $ID = $_GET['ID'];?>
    <input type="hidden" value="<?php echo $service_requests; ?>" id="service_requests">
    <input type="hidden" value="<?php echo $ID; ?>" id="tup_id">



<?php }

else if($islem=="randevu_tablo"){?>
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black;"><b>Randevu Listesi</b></label></div>
        <div class="card-body">
            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="istem_kabul_hasta_randevu_listesi_dt">
                <thead>
                <tr>
                    <th>Durum</th>
                    <th>Randevu Tarihi</th>
                    <th>İstem Tarihi</th>
                    <th>TC Kimlik No</th>
                    <th>Protokol No</th>
                    <th>Adı Soyadaı</th>
                    <th title="İstek Yapılan Birim">Birim</th>
                    <th>İşlem</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script>
        var istem_kabul_hasta_randevu_listesi = $('#istem_kabul_hasta_randevu_listesi_dt').DataTable({
            deferRender: true,
            scrollY: '30vh',
            scrollX: true,
            "info":false,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: '<i class="fa-regular fa-calendar-days"></i> Randevu Tanımla',
                    className: 'btn btn-success btn-sm btn-radnevu-tanimla',
                    attr:  {
                        'disabled':true,

                    },

                }
            ],

            ajax: {
                url: '/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_listesi',
                processing: true,
                method: 'GET',
                dataSrc: ''
            },

            "fnRowCallback": function (nRow, aData) {
                if (aData['prompt_status'] == 1) {

                    $('td:eq(0)', nRow).html("<i class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 40%; margin-right: 40%;'></i>");
                } else if (aData['prompt_status'] == 0) {
                    $('td:eq(0)', nRow).html("<i  class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 40%; margin-right: 40%; ' ></i>");
                }
                else if(aData['prompt_status'] == 2) {
                    $('td:eq(0)', nRow).html("<i  class='fa-solid fa-calendar-days fa-lg  '  style='color: black; margin-left: 8%; margin-right: 8%; ' ></i><i  class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d;' ></i>");

                }
                $(nRow)
                    .attr('id',aData['id'])
                    .attr('class','randevu_hasta_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns:[
                {data:'prompt_status'},
                {

                    data:null,
                    render:function (data){if(data!=2){
                        var randevu = data.appointment_datetime;
                        var randevu_tarihi= moment(randevu).format('MM/DD/YYYY H:mm:ss');
                        if (randevu_tarihi == "Invalid date") {
                            randevu_tarihi = "";
                        }
                        return randevu_tarihi;
                    }
                    }
                },

                {
                    data:null,
                    render:function (data){
                        if (data != 2){
                            var kayit = data.insert_datetime;
                            var kayit_tarihi= moment(kayit).format('MM/DD/YYYY H:mm:ss');
                            return kayit_tarihi;
                        }
                    }
                },

                {data:'tc_id'},
                {data:'protocol_id'},
                {
                    data: null,
                    render: function (data) { return  data.patient_name + " " + data.patient_surname; }
                },
                {data:'department_name'},
                {
                    data: null,
                    render: function (data) { return '<button id="'+ data.id +'" class="btn btn-warning btn-sm btn_randevu_duzenle"><i class="fas fa-edit"></i></button><button id="'+ data.id +'" class="btn btn-danger btn-sm btn_randevu_sil"><i class="fas fa-trash"></i></button>'; }
                },
            ],
        });

        $("body").off("click", ".randevu_hasta_sec").on("click", ".randevu_hasta_sec", function (e) {
            $('.btn-radnevu-tanimla').attr('disabled', false);
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.randevu_hasta_sec-kaldir').removeClass("text-white");
            $('.randevu_hasta_sec-kaldir').removeClass("randevu_hasta_sec-kaldir");
            $('.randevu_hasta_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white randevu_hasta_sec-kaldir");
        });



        var randevu_tanimla = $('#randevu_tanimla_form').html();
        $('#randevu_tanimla_form').remove();


        $(document).on('click', '.btn-radnevu-tanimla', function () {
            alertify.confirm(randevu_tanimla, function(){
                var id=$('.randevu_hasta_sec-kaldir').attr('id');
                var appointment_datetime= $('.randevutanimla').find('#appointment_datetime').val();
                if (appointment_datetime != '') {
                $.ajax({
                    url:'/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_randevu_tanimla',
                    type:'POST',
                    data:{
                        id,
                        appointment_datetime
                    },
                    success:function(e){
                        $("#sonucyaz").html(e);

                        istem_kabul_hasta_listesi.ajax.url(istem_kabul_hasta_listesi.ajax.url()).load();
                        istem_kabul_hasta_randevu_listesi.ajax.url(istem_kabul_hasta_randevu_listesi.ajax.url()).load();
                        $.get( "/ajax/laboratuvar/istem-kabul/calendar-istem-kabul.php?islem=istem-kabul-calendar",function(getveri){
                            $('#calendar').html(getveri);
                        });

                        $('.alertify').remove();
                    }
                });
                } else if (appointment_datetime == '') {
                    alertify.warning("Randevu Tarihi Giriniz.");
                    setTimeout(() => {
                        $(".btn-radnevu-tanimla").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Randevu Tanımlama İşleminden Vazgeçtiniz')}).set({labels:{ok: "Kaydet", cancel: "Vazgeç"}}).set({title:"Randevu Tanımla"});
        });

        var randevu_duzenle = $('#randevu_duzenle_form').html();
        $('#randevu_duzenle_form').remove();

        $(document).on('click', '.btn_randevu_duzenle', function () {
            var id = $(this).attr("id");
            alertify.confirm(randevu_duzenle, function(){

                var appointment_datetime= $('.randevuduzenle').find('#appointment_datetime1').val();
                if (appointment_datetime != '') {
                    $.ajax({
                        url:'/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_randevu_duzenle',
                        type:'POST',
                        data:{
                            id,
                            appointment_datetime
                        },
                        success:function(e){
                            $("#sonucyaz").html(e);

                            istem_kabul_hasta_listesi.ajax.url(istem_kabul_hasta_listesi.ajax.url()).load();
                            istem_kabul_hasta_randevu_listesi.ajax.url(istem_kabul_hasta_randevu_listesi.ajax.url()).load();
                            $.get( "/ajax/laboratuvar/istem-kabul/calendar-istem-kabul.php?islem=istem-kabul-calendar",function(getveri){
                                $('#calendar').html(getveri);
                            });

                            $('.alertify').remove();
                        }
                    });
                } else if (appointment_datetime == '') {
                    alertify.warning("Yeni Randevu Tarihi Giriniz.");
                    setTimeout(() => {
                        $(".btn_randevu_duzenle").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Randevu Tarihini Değiştirme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Kaydet", cancel: "Vazgeç"}}).set({title:"Randevu Düzenle"});
        });

        $("body").off("click", ".btn_randevu_sil").on("click", ".btn_randevu_sil", function(e){
            var id = $(this).attr("id");
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='appointment_deletion_details' name='appointment_deletion_details' rows='1' placeholder='Silme Nedeni Giriniz.' title='Randevu Silme Nedeni..'></textarea>", function(){

                var appointment_deletion_details = $('#appointment_deletion_details').val();
                if (appointment_deletion_details != '') {
                    $.ajax({
                        type: 'POST',
                        url:'/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_randevu_sil',
                        data: {
                            id,
                            appointment_deletion_details
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            istem_kabul_hasta_listesi.ajax.url(istem_kabul_hasta_listesi.ajax.url()).load();
                            istem_kabul_hasta_randevu_listesi.ajax.url(istem_kabul_hasta_randevu_listesi.ajax.url()).load();
                            $.get( "/ajax/laboratuvar/istem-kabul/calendar-istem-kabul.php?islem=istem-kabul-calendar",function(getveri){
                                $('#calendar').html(getveri);
                            });

                            $('.alertify').remove();
                        }
                    });
                } else if (appointment_deletion_details == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".btn_randevu_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Randevu Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Kaydet", cancel: "Vazgeç"}}).set({title:"Randevu Sil"});
        });

    </script>

    <form id="randevu_tanimla_form">
        <div class="randevutanimla">

            <div class="row">
                <div class="col-4">
                    <label >Randevu Tarihi</label>
                </div>
                <div class="col-8">
                    <input class="form-control form-control-sm form-sm " type="datetime-local" name="appointment_datetime" id="appointment_datetime">
                </div>
            </div>

        </div>
    </form><!-- form bitiş -->

    <form id="randevu_duzenle_form">
        <div class="randevuduzenle">

            <div class="row">
                <div class="col-4">
                    <label >Randevu Tarihi</label>
                </div>
                <div class="col-8">
                    <input class="form-control form-control-sm form-sm " type="datetime-local" name="appointment_datetime" id="appointment_datetime1">
                </div>
            </div>

        </div>
    </form><!-- form bitiş -->
<?php } ?>


<!--<script src="assets/jsBarcode.js"></script>-->
<!---->
<!---->
<!--<svg id="barcode"></svg>-->
<!---->
<!---->
<!--<script>-->
<!--    JsBarcode("#barcode", "TEST");-->
<!---->
<!--</script>-->
