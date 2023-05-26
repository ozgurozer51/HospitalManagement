<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if($islem=="hasta_bilgileri_from"){
    $git = $_GET['barkod_no'];
    $barkod_no = trim($git);

    $pp = tek("select distinct pp.service_requests_bardoce, pp.patient_id,pp.protocol_number from  patient_prompts as pp where pp.service_requests_bardoce='$barkod_no'
group by service_requests_bardoce,pp.patient_id,pp.protocol_number");

    $hasta_kayit=singularactive("patient_registration","protocol_number",$pp["protocol_number"]);
    $hasta_id=$hasta_kayit["patient_id"];

    $hasta=singularactive("patients","id",$hasta_id);
    $dogum=$hasta['birth_date'];

    $YAS = ikitariharasindakiyilfark($simdikitarih,$dogum);
    $sosyalguvence = tek("select * from transaction_definitions where definition_code='{$hasta["social_assurance"]}'");
    $kurumgetir = tek("select * FROM transaction_definitions where definition_code='{$hasta["institution"]}'");
    $oda = tek("select * FROM hospital_room where id='{$hasta_kayit["room_id"]}'");
    $yatak = tek("select * FROM hospital_bed where id='{$hasta_kayit["bed_id"]}'");
    ?>

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
                            <input readonly class="form-control form-control-xs" type="text" id="protokol_no" value="<?php echo $pp["protocol_number"] ;?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Adı Soyadı</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="adi_soyadi" value="<?php echo ucwords($hasta["patient_name"] . " " . $hasta["patient_surname"]) ?>(<?php echo $YAS ?>)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>TC Kimlik No</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="tc_id" value="<?php echo  $hasta["tc_id"]  ;?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Kur Kodu</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="krum" value="<?php echo $kurumgetir["definition_name"]." / ".$sosyalguvence["definition_name"] ;?>">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-5">
                            <label>Kan Grubu</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="kan" value="<?php echo islemtanimgetirid($hasta["blood_group"]);?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Müracaat Tarihi</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="muracaat_tarihi" value="<?php echo nettarih($hasta_kayit["insert_datetime"]);?>">
                        </div>
                    </div>

                </div>

                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Birim</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="birim" value="<?php
                            if($hasta_kayit["service_id"]==null){
                                echo birimgetirid($hasta_kayit["outpatient_id"]);
                            } else {
                                echo birimgetirid($hasta_kayit["service_id"]);
                            }?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Doktor</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="doktor" value="<?php
                            if($hasta_kayit["outpatient_id"]==null){
                                echo kullanicigetirid($hasta_kayit["service_doctor"]);
                            } else {
                                echo kullanicigetirid($hasta_kayit["doctor"]);
                            }?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Vaka Türü</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="vaka_turu" value="<?php echo islemtanimgetirid($hasta_kayit["reason_arrival"]);?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Kabul Şekli</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="kabul_sekli" value="<?php echo islemtanimgetirid($hasta_kayit["patient_admission_type"]);?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label>Oda/Yatak</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="oda_yatak" value="<?php echo $oda["room_name"]." / ".$yatak["bed_name"] ;?>">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-5">
                            <label>Yatış Tarihi</label>
                        </div>
                        <div class="col-7">
                            <input readonly class="form-control form-control-xs" type="text" id="yatis_tarihi" value="<?php echo nettarih($hasta_kayit["admission_start_date"]);?>">
                        </div>
                    </div>

                </div>

                <div class="col-4">

                    <div align="center">
                        <img style="width: 50%;" class="rounded h-75 mt-2  img-thumbnail"
                             src="<?php if ($hasta["photo"] != '') {
                                 echo $hasta["photo"];
                             } else {
                                 if ($hasta["gender"] == 'E') {
                                     echo "assets/img/dummy-user.jpeg";
                                 } elseif ($hasta["gender"] == 'K') {
                                     echo "assets/img/bdummy-user.jpeg";
                                 }
                             } ?>">
                    </div>


                </div>

            </div>

        </div>
    </div>

<?php }

else if($islem=="numune_alma_tup_buttonlari"){
    $patient_promptsid = $_GET['patient_promptsid'];
    $barkod = $_GET['barkod'];?>

    <input type="text" hidden id="barkod_no" value="<?php echo $barkod;?>">

    <div class="col-12 mt-2" align="left">
        <?php
        $pp = singularactive("patient_prompts","service_requests_bardoce",$barkod);?>

        <?php
        if ($pp["sampling_confirmation"]==0){?>
        <button class="btn btn-success btn_numune_al"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Al</b></button>
        <button class="btn btn-success btn_numune_reddet" data-bs-target="#lab_modal_lg" data-bs-toggle="modal"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Reddet</b></button>
        <?php }
        else if($pp["sampling_confirmation"]==1){ ?>
            <button class="btn btn-success btn_numune_alim_iptal"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Alım İptal</b></button>
            <button class="btn btn-success btn_numune_reddet" data-bs-target="#lab_modal_lg" data-bs-toggle="modal"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Reddet</b></button>
        <?php }
        else if($pp["sampling_confirmation"]==2){ ?>
            <button class="btn btn-success btn_numune_ret_iptal"><b><i class="fa-sharp fa-solid fa-memo"></i> Numune Ret İptal</b></button>

        <?php } ?>

    </div>


    <script>
        $("body").off("click", ".btn_numune_al").on("click", ".btn_numune_al", function(e){
            var barkod_no = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_btn_numune_al',
                data: {"barkod_no":barkod_no},
                success:function(e){
                    $('#sonucyaz').html(e);

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

                                numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();

                                hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();

                                $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=numune_alma_tup_button_disbled",function(getveri){
                                    $('#tup_buttonlari_numune_alma').html(getveri);
                                })

                            }
                        }
                    });
                }
            });
        });

        $("body").off("click", ".btn_numune_alim_iptal").on("click", ".btn_numune_alim_iptal", function(e){
            var barkod_no = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_btn_numune_alim_iptal',
                data: {"barkod_no":barkod_no},
                success:function(e){
                    $('#sonucyaz').html(e);

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

                                numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();

                                hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();

                                $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=numune_alma_tup_button_disbled",function(getveri){
                                    $('#tup_buttonlari_numune_alma').html(getveri);
                                })

                            }
                        }
                    });
                }
            });
        });

        $("body").off("click", ".btn_numune_reddet").on("click", ".btn_numune_reddet", function(e){
            var barkod_no = $('#barkod_no').val();
            $.get("ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=modal_n_alma_numune_reddet",{"barkod_no":barkod_no},function(getVeri){
                $('#lab_modal_lg_icerik').html(getVeri);
            });
        });

        $("body").off("click", ".btn_numune_ret_iptal").on("click", ".btn_numune_ret_iptal", function(e){
            var barkod_no = $('#barkod_no').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_reddet_iptal',
                data: {"barkod_no":barkod_no},
                success:function(e){
                    $('#sonucyaz').html(e);
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

                                numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();

                                hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();

                                $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=numune_alma_tup_button_disbled",function(getveri){
                                    $('#tup_buttonlari_numune_alma').html(getveri);
                                })

                            }
                        }
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal_n_alma_numune_reddet"){
    $barkod_numara = $_GET['barkod_no'];?>

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header text-white p-1" >
            <h4 class="modal-title ">Numune Reddet</h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="numune_alma_numune_reddet_datatable">
                <thead>
                <tr>
                    <th>Ret Nedeni</th>
                </tr>
                </thead>
            </table>

            <script>
                var numune_alma_numune_reddet = $('#numune_alma_numune_reddet_datatable').DataTable({
                    deferRender: true,
                    scrollY: '15vh',
                    scrollX: true,
                    "info":false,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

                    ajax: {
                        url: '/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_ret_nedenleri',
                        processing: true,
                        method: 'GET',
                        dataSrc: ''
                    },
                    
                    "fnRowCallback": function (nRow, aData) {
                        $(nRow)
                            .attr('id',aData['id'])
                            .attr('class','ret_nedeni_sec')
                    },
                    
                    columns:[
                        {data:'definition_name'},
                    ],
                });

                $("body").off("click", ".ret_nedeni_sec").on("click", ".ret_nedeni_sec", function (e) {

                    $(this).css('background-color') != 'rgb(147,203,198)' ;
                    $('.ret_nedeni_sec-kaldir').removeClass("text-white");
                    $('.ret_nedeni_sec').removeClass("ret_nedeni_sec-kaldir");
                    $('.ret_nedeni_sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white ret_nedeni_sec-kaldir");

                    var ret_nedeni=$('.ret_nedeni_sec-kaldir').attr('id');
                    $('#sample_rejection_detail').val(ret_nedeni);
                    
                });
            </script>

            <form id="numune_reddet_form" action="javascript:void(0);">

                <input type="text" hidden id="barkod_no" name="barkod_no" value="<?php echo $barkod_numara;?>">
                <input type="text" hidden id="sample_rejection_detail" name="sample_rejection_detail">

            </form>

            <div class="mt-2"></div>
            <h6 style="color: #e80c4d"><b>Numuneyi Reddetmek İstediğinizden Eminmisiniz ?</b></h6>

        </div>

        <div class="modal-footer">
            <button id="btn_numune_reddet" type="button" class="btn btn-warning btn-sm"  data-bs-dismiss="modal">Seçilen Numuneyi Reddet</button>
        </div>

    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn_numune_reddet").on("click", "#btn_numune_reddet", function(){
                var barkod_noo = $('#barkod_no').val();
                var sample_rejection_detail = $("#sample_rejection_detail").val();
                if (sample_rejection_detail == ""){
                    alertify.warning("Red Nedeni Seçmediğiniz İçin Kapatıldı")
                }else{
                    $.ajax({
                        type:'POST',
                        url:'/ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_reddet',
                        data:$("#numune_reddet_form").serialize(),
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $.get("ajax/laboratuvar/numune-alma/sql-numunealma.php?islem=sql_numune_alma_hasta_tup",{barkod_no:barkod_noo},function (result){
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
                                        numune_alim_liste.ajax.url(numune_alim_liste.ajax.url()).load();
                                        hastanin_gecmis_numune_listesi.ajax.url(hastanin_gecmis_numune_listesi.ajax.url()).load();
                                        $.get( "ajax/laboratuvar/numune-alma/from-button-numunealma.php?islem=numune_alma_tup_button_disbled",function(getveri){
                                            $('#tup_buttonlari_numune_alma').html(getveri);
                                        })
                                    }
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
<?php }

else if($islem=="numune_alma_tup_button_disbled"){?>
    <div class="col-12 mt-2" align="left">
        <button disabled class="btn btn-success btn_numune_al"><b><i class="fa-regular fa-syringe"></i> Numune Al</b></button>
        <button disabled class="btn btn-danger btn_numune_reddet"><b><i class="fa-regular fa-x"></i> Numune Reddet</b></button>
    </div>
<?php } ?>

