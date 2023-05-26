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
$kullanicid = $_SESSION['id'];
//$simdikitarih = date('d-m-Y H:i:s');
//$dizi = explode(" ", $simdikitarih);
//$tarih = $dizi['0'];
//$saat = $dizi['1'];
$islem = $_GET['islem'];
if ($islem == "guvenlicerrahibody") {

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);

    if ($hastakayit['mother_tc_identity_number']==''){
        $patients = singular("patients", "id", $hastakayit['patient_id']);
    }else{
        $hastalarid=$hastakayit['patient_id'];
        $patients = tek("SELECT * FROM patients WHERE id='$hastalarid'");
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>

    <div class="modal-content">
        <div >
            <div class="modal-header py-1 px-3">
                <h5 class="modal-title">Güvenli Cerrahi Kontrol Listesi - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 ">
                        <div class="card ">
                            <div class="card-header " style="height: 4vh;">Güvenli Cerrahi Kontrol Listesi</div>
                            <div class="mx-1">
                                <div class="card-body mx-0 guvenlicerrahilistesi-<?php echo $protokolno; ?>">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <?php
                        $sevkisleminsert=yetkisorgula($kullanicid, "sevkisleminsert");
                        $sevkislemupdate=yetkisorgula($kullanicid, "sevkislemupdate");
                        $sevkislemdelete=yetkisorgula($kullanicid, "sevkislemdelete");

                        ?>
                        <div class="card ">
                            <div class="card-header" style="height: 4vh;">
                                <div class="col-12 row">
                                    <div class="col-md-9 p-1 ">
                                        <h5 style="font-size: 13px;">Güvenli Cerrahi Kontrol Listesi Kayıt</h5>
                                    </div>
                                    <div class="col-md-3 p-1" align="right">
                                        <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-1 my-1" >
                                <div class="guvenlicerrahibody-<?php echo $protokolno; ?>">

                                </div>
                                <div class="modal-footer pb-0 pt-0 mt-1">
                                    <button type="button " class="btn btn-info btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcayazdir'; } ?>">
                                        <i class="fas fa-print " aria-hidden="true"></i>
                                        Yazdır
                                    </button>
                                    <button type="button " class="btn btn-success btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcapdf'; } ?>">
                                        <i class="fa-regular fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        PDF
                                    </button>
                                    <button type="button " class="btn btn-warning btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'uss'; } ?>">
                                        <i class="fa-thin fa-house-window"></i>
                                        USS'ye Gönder
                                    </button>
                                    <?php if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn yeni-btn btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcayeniden'; } ?>" id="<?php echo $_GET["getir"]; ?>"><i class="fa fa-plus" aria-hidden="true"></i> Yeni</button>
                                    <?php }if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm gcakaydet   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcainsert'; } ?>" ><i class="fa fa-check" aria-hidden="true"></i> Kaydet</button>
                                    <?php }  if ($sevkislemupdate==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcaupdate'; } ?>"><i class="fas fa-edit" aria-hidden="true"></i> Güncelle</button>
                                    <?php } if ($sevkislemdelete==1){ ?>
                                        <button type="button " class="btn sil-btn btn-sm  guvenlicerrahiguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'gcadelete'; } ?>"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                    </div>

                    <script>

                        $(document).ready(function () {

                            $(".gcapdf").off().on("click", function () {

                                $('#formgcapdf').css("display", "block");
                                var element = $('#formgcapdf').html();
                                html2pdf(element);
                                $('#formgcapdf').css("display", "none");

                            });

                            $(".gcayazdir").off().on("click", function () {

                                $('#formgcaprint').css("display", "block");
                                var divToPrint = $('#formgcaprint').html();

                                var newWin=window.open('','Print-Window');

                                newWin.document.open();

                                newWin.document.write('<html><body onload="window.print()">'+divToPrint+'</body></html>');

                                newWin.document.close();

                                setTimeout(function(){newWin.close();},100);

                                $('#formgcaprint').css("display", "none");

                            });




                            $(".gcainsert").off().on("click", function () {
                                //form reset-->
                                var gonderilenform = $("#formgca").serialize();

                                document.getElementById("formgca").reset();
                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcainsert',
                                    data: gonderilenform,
                                    success: function (e) {
                                        $("#sonucyaz").html(e);

                                        var getir = "<?php echo $_GET["getir"] ?>";
                                        $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=guvenlicerrahilistesi", {getir: getir}, function (g) {
                                            $(".guvenlicerrahilistesi-<?php echo $protokolno; ?>").html(g);

                                            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: getir}, function (getVeri) {
                                                $(".guvenlicerrahibody-<?php echo $protokolno; ?>").html(getVeri);

                                            });
                                        });
                                    }
                                });
                            });
                            $(document).ready(function () {
                                $(".gcaupdate").off('click').on("click", function () {
                                    //form reset-->

                                    var gonderilenform = $("#formgca").serialize();
                                    var getir ="<?php echo $_GET["getir"]; ?>";
                                    document.getElementById("formgca").reset();
                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcaupdate',
                                        data: gonderilenform,
                                        success: function (e) {
                                            $("#sonucyaz").html(e);

                                            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=guvenlicerrahilistesi", {getir: getir}, function (g) {
                                                $(".guvenlicerrahilistesi-<?php echo $protokolno; ?>").html(g);

                                                $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: getir}, function (getVeri) {
                                                    $(".guvenlicerrahibody-<?php echo $protokolno; ?>").html(getVeri);

                                                });
                                            });

                                        }
                                    });

                                });

                            });



                            var getir = "<?php echo $_GET["getir"] ?>";
                            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=guvenlicerrahilistesi", {getir: getir}, function (g) {
                                $(".guvenlicerrahilistesi-<?php echo $protokolno; ?>").html(g);

                            });
                            var protokol="<?php echo $_GET["getir"]; ?>";
                            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: protokol}, function (getVeri) {
                                $(".guvenlicerrahibody-<?php echo $protokolno; ?>").html(getVeri);

                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function () {
            $(".guvenlicerrahiguncellesil").prop('hidden', true);

            $(".gcayeniden").click(function () {
                $(".guvenlicerrahiguncellesil").prop('hidden', true);
                $(".gcayeniden").prop('hidden', false);
                var getir = $(this).attr('id');
                $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: getir}, function (getVeri) {
                    $(".guvenlicerrahibody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });


            $(".gcadelete").click(function () {
                var id =$(".aktif-satir").attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcabtndelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            var getir = "<?php echo $_GET["getir"]; ?>";


                            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=guvenlicerrahilistesi", {getir: getir}, function (g) {
                                $(".guvenlicerrahilistesi-<?php echo $protokolno; ?>").html(g);

                                $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: getir}, function (getVeri) {
                                    $(".guvenlicerrahibody-<?php echo $protokolno; ?>").html(getVeri);

                                });

                                $(".guvenlicerrahiguncellesil").prop('hidden', true);
                                $(".gcakaydet").prop('hidden', false);
                                ///$('.secilentab').html(get);
                                $("#deneme123").trigger("click");

                            });

                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
            });



        });

    </script>
<?php }
elseif($islem=="guvenlicerrahilistesi"){ ?>

    <table class="table border table-bordered border-dark table-hover nowrap display w-100" style="font-size: 13px;" id="tablegca-<?php echo $_GET["getir"]; ?>">
        <thead>
        <tr>
            <th>Oluşturulma Tarihi</th>
            <th>Personel</th>
            <!--            <th>Birim</th>-->
        </tr>
        </thead>
        <tbody>

        <?php $id=$_GET["getir"];
        $demo = "Select * from safe_surgery WHERE protocol_number='$id' AND status='1'";
        $hello=verilericoklucek($demo);
        foreach ((array) $hello as $row) { ?>

            <tr id="<?php echo $row["id"]; ?>" class="guvenlicerrahibodyguncelle sevkbtn">
                <td><?php echo nettarih($row['process_datetime']); ?></td>
                <td><?php echo kullanicigetirid($row['insert_userid']);; ?></td>
                <!--                <td>--><?php //echo birimgetirid($row['service_id']); ?><!--</td>-->
            </tr>

        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">

        $("#tablegca-<?php echo $_GET["getir"]; ?>").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            dom: '<"clear">lfrtip',
        });
        $(".guvenlicerrahibodyguncelle").click(function () {
            let tiklananSatir = $(this);
            if (!tiklananSatir.hasClass("aktif-satir")) {
                var getir = $(this).attr('id');
                $(".guvenlicerrahiguncellesil").prop('hidden', false);
                $(".gcakaydet").prop('hidden', true);
                $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {gcaid: getir}, function (getVeri) {
                    $(".guvenlicerrahibody-<?php echo $id; ?>").html(getVeri);

                });

                $('.guvenlicerrahibodyguncelle').each(function () {
                    $(this).removeClass('aktif-satir');
                });
                tiklananSatir.addClass('aktif-satir');


            } else {
                $(".guvenlicerrahiguncellesil").prop('hidden', true);
                $(".gcakaydet").prop('hidden', false);

                var getir = "<?php echo $id; ?>";
                $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=gcayeniden", {getir: getir}, function (getVeri) {
                    $(".guvenlicerrahibody-<?php echo $id; ?>").html(getVeri);

                });
                tiklananSatir.removeClass('aktif-satir');
            }

        });

    </script>

<?php }
elseif ($islem == "gcayeniden") {
    $random_sayi = uniqid();
    if ($_GET['gcaid']!=''){
        $guvencerrahi = singularactive("safe_surgery","id", $_GET["gcaid"]);
        $yatis = singularactive("patient_registration", "protocol_number", $guvencerrahi['protocol_number']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }else{
        $yatis = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }

    $y_birim=$yatis['service_id'];
    $y_doktor=$yatis['service_doctor'];
    ?>


    <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
        <li class="nav-item col-xl-3" role="presentation">
            <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 active up-btn" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">I. Klinikten Ayrılmadan Önce</button>
        </li>
        <li class="nav-item col-xl-3" role="presentation">
            <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 "   id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">II. Anestezi Verilmeden Önce</button>
        </li>
        <li class="nav-item col-xl-3" role="presentation">
            <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 " id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">III. Ameliyat Kesisinden Önce</button>
        </li>
        <li class="nav-item col-xl-3" role="presentation">
            <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">IV. Ameliyattan Çıkmadan Önce</button>
        </li>
    </ul>
    <script>
        $(".tablist<?php echo $random_sayi; ?>").click(function () {
            $('.tablist<?php echo $random_sayi; ?>').removeClass("text-white");
            $('.tablist<?php echo $random_sayi; ?>').removeClass("up-btn");
            $(this).addClass("text-white");
            $(this).addClass("up-btn");

        });
    </script>
    <form id="formgca" action="javascript:void(0);">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" class="form-control" name="protocol_number"
                               value="<?php echo $yatis['protocol_number'] ?>" id="basicpill-firstname-input">
                        <?php if ($guvencerrahi['id']!=''){ ?>
                            <input type="hidden" class="form-control" name="id"
                                   value="<?php echo $guvencerrahi['id'] ?>" id="basicpill-firstname-input">
                        <?php  } ?>
                        <input type="hidden" class="form-control" name="form_number"
                               value="<?php if ($guvencerrahi['id']!=''){ echo $guvencerrahi['form_number']; }else{ echo rasgelesifreolustur(11); } ?>" id="basicpill-firstname-input">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" >Ameliyat Yapılan Yer:</label>
                            <input type="text" class="form-control  w-50" style="margin-left: 10px!important;" name="operation_name"
                                   value="<?php echo $guvencerrahi['operation_name'];   ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" >Ameliyat Bölgesi:</label>
                            <input type="text" class="form-control  w-50" style="margin-left: 10px!important;" name="surgical_site"
                                   value="<?php echo $guvencerrahi['surgical_site'];   ?>" id="basicpill-firstname-input">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <div class="col-md-6">
                            <label for="basicpill-firstname-input" class="form-label" >Ameliyat Tarihi:</label>
                            <input type="datetime-local" class="form-control  "  name="surgery_date" min="<?php echo $yatis['hospitalized_accepted_datetime']; ?>"
                                   value="<?php
                                   if ($guvencerrahi['surgery_date']!=''){
                                       echo $guvencerrahi['surgery_date'];
                                   }else{
                                       echo $simdikitarih;
                                   }
                                   ?>" id="basicpill-firstname-input">
                        </div>
                        <div class="col-md-6">
                            <label for="basicpill-firstname-input" class="form-label" >İşlem Tarihi:</label>
                            <input type="datetime-local" class="form-control  "  name="process_datetime" min="<?php echo $yatis['hospitalized_accepted_datetime']; ?>"
                                   value="<?php
                                   if ($guvencerrahi['process_datetime']!=''){
                                       echo $guvencerrahi['process_datetime'];
                                   }else{
                                       echo $simdikitarih;
                                   }
                                   ?>" id="basicpill-firstname-input">
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <label for="basicpill-firstname-input" class="form-label mx-3" >1.Hastanın:</label>
                        <div class="row mx-2">
                            <div class="col-md-3">
                               <input type="radio" value="1" <?php if ($guvencerrahi['id_information_1']){ ?> checked <?php } ?>  id="id_information_1" name="id_information_1" class="form-check-input">
                                <label class="form-check-label" for="id_information_1">
                                    Kimlik bilgileri
                                </label>
                            </div>
                            <div class="col-md-3">
                               <input type="radio" value="1" <?php if ($guvencerrahi['operation_1']){ ?> checked <?php } ?> id="operation_1"  name="operation_1" class="form-check-input">
                                <label class="form-check-label" for="operation_1">
                                    Ameliyatı
                                </label>
                            </div>
                            <div class="col-md-6">
                               <input type="radio" value="1" <?php if ($guvencerrahi['surgery_site_1']){ ?> checked <?php } ?> id="surgery_site_1" name="surgery_site_1" class="form-check-input">
                                <label class="form-check-label" for="surgery_site_1">
                                    Ameliyat bölgesi doğrulandı.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >2.Hastanın rızası kontrol edildi mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['operation_status_1']){ ?> checked <?php } ?> id="operation_status_1" name="operation_status_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_status_1">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['operation_status_1']==2){ ?> checked <?php } ?> id="operation_status" name="operation_status_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_status">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >3.Hasta aç mı?</label>

                                <div class="row">
                                    <div class="col-md-4">
                                       <input type="radio" value="1" <?php if ($guvencerrahi['sick_hungry_1']){ ?> checked <?php } ?>id="sick_hungry_1" name="sick_hungry_1" class="form-check-input">
                                        <label class="form-check-label" for="sick_hungry_1">
                                            Evet
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                       <input type="radio" value="2" <?php if ($guvencerrahi['sick_hungry_1']==2){ ?> checked <?php } ?>id="sick_hungry" name="sick_hungry_1" class="form-check-input">
                                        <label class="form-check-label" for="sick_hungry">
                                            Hayır
                                        </label>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >4.Ameliyat bölgesi tıraşı yapıldı mı? </label>
                                <div class="row">
                                    <div class="col-md-4">
                                       <input type="radio" value="1" <?php if ($guvencerrahi['operation_shaved_1']){ ?> checked <?php } ?>id="operation_shaved_13" name="operation_shaved_1" class="form-check-input">
                                        <label class="form-check-label" for="operation_shaved_13">
                                            Evet
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                       <input type="radio" value="2" <?php if ($guvencerrahi['operation_shaved_1']==2){ ?> checked <?php } ?>id="operation_shaved_12" name="operation_shaved_1" class="form-check-input">
                                        <label class="form-check-label" for="operation_shaved_12">
                                            Hayır
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                       <input type="radio" value="3" <?php if ($guvencerrahi['operation_shaved_1']==3){ ?> checked <?php } ?>id="operation_shaved_1" name="operation_shaved_1" class="form-check-input">
                                        <label class="form-check-label" for="operation_shaved_1">
                                            Gerekli değil
                                        </label>
                                    </div>
                                </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" > 5.Hastada makyaj/oje, protez, değerli eşya var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['sick_item_1']){ ?> checked <?php } ?> id="sick_item_14" name="sick_item_1" class="form-check-input">
                                    <label class="form-check-label" for="sick_item_14">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['sick_item_1']==2){ ?> checked <?php } ?> id="sick_item_1" name="sick_item_1" class="form-check-input">
                                    <label class="form-check-label" for="sick_item_1">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >6.Hastanın kıyafetleri tümüyle çıkarılıp ameliyat önlüğü ve bonesi giydirildi mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['operation_apron_bone_1']){ ?> checked <?php } ?> id="operation_apron_bone_12" name="operation_apron_bone_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_apron_bone_12">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['operation_apron_bone_1']==2){ ?> checked <?php } ?> id="operation_apron_bone_1" name="operation_apron_bone_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_apron_bone_1">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >7.Ameliyat öncesi gerekli özel işlem var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['enema_1']){ ?> checked <?php } ?> id="enema_1" name="enema_1" class="form-check-input">
                                    <label class="form-check-label" for="enema_1">
                                        Lavman
                                    </label>
                                </div>
                                <div class="col-md-5">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['bladder_catheterization_1']==2){ ?> checked <?php } ?> id="bladder_catheterization_1" name="bladder_catheterization_1" class="form-check-input">
                                    <label class="form-check-label" for="bladder_catheterization_1">
                                        Mesane Kateterizasyonu
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['compression_stockings_1']){ ?> checked <?php } ?> id="compression_stockings_1" name="compression_stockings_1" class="form-check-input">
                                    <label class="form-check-label" for="compression_stockings_1">
                                        Varis Çorabı
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['special_treatment_protocol_1']==2){ ?> checked <?php } ?> id="special_treatment_protocol_1" name="special_treatment_protocol_1" class="form-check-input">
                                    <label class="form-check-label" for="special_treatment_protocol_1">
                                        Özel Tedavi protokolü
                                    </label>
                                </div>
                                <div class="col-md-5">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['other_1']){ ?> checked <?php } ?> id="other_1" name="other_1" class="form-check-input">
                                    <label class="form-check-label" for="other_1">
                                        Diğer
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['no_process_1']==2){ ?> checked <?php } ?> id="no_process_1" name="no_process_1" class="form-check-input">
                                    <label class="form-check-label" for="no_process_1">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label " >8.Ameliyat için gerekli olacak özel malzeme, implant, kan veya kan ürünü hazırlığı teyit edildi mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['operation_special_material_1']){ ?> checked <?php } ?>id="operation_special_material_15" name="operation_special_material_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_special_material_15">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['operation_special_material_1']==2){ ?> checked <?php } ?>id="operation_special_material_1" name="operation_special_material_1" class="form-check-input">
                                    <label class="form-check-label" for="operation_special_material_1">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-1 col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" > 9.Hastanın gerekli laboratuar ve radyoloji ve tetkikleri mevcut mu?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['lab_rad_conclusion_1']){ ?> checked <?php } ?>id="lab_rad_conclusion_14" name="lab_rad_conclusion_1" class="form-check-input">
                                    <label class="form-check-label" for="lab_rad_conclusion_14">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['lab_rad_conclusion_1']==2){ ?> checked <?php } ?>id="lab_rad_conclusion_1" name="lab_rad_conclusion_1" class="form-check-input">
                                    <label class="form-check-label" for="lab_rad_conclusion_1">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" >10.Hastanın kendisinden </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['id_information_2']){ ?> checked <?php } ?>id="id_information_2" name="id_information_2" class="form-check-input">
                                    <label class="form-check-label" for="id_information_2">
                                        Kimlik bilgileri
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['operation_2']){ ?> checked <?php } ?>id="operation_2" name="operation_2" class="form-check-input">
                                    <label class="form-check-label" for="operation_2">
                                        Ameliyatı
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['surgery_site_2']){ ?> checked <?php } ?>id="surgery_site_2" name="surgery_site_2" class="form-check-input">
                                    <label class="form-check-label" for="surgery_site_2">
                                        Ameliyat bölgesi
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['operation_status_2']){ ?> checked <?php } ?>id="operation_status_2" name="operation_status_2" class="form-check-input">
                                    <label class="form-check-label" for="operation_status_2">
                                        Ameliyatı ile ilgili rızası doğrulandı mı?
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="basicpill-firstname-input " class="form-label mx-4" >11.Ameliyat bölgesinde işaretleme var mı?</label>
                        <div class="row mx-2">
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['operation_sign_status_2']){ ?> checked <?php } ?> id="operation_sign_status_2" name="operation_sign_status_2" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_status_2">
                                    Var
                                </label>
                            </div>
                            <div class="col-md-8">
                               <input type="radio" value="2" <?php if ($guvencerrahi['operation_sign_status_2']==2){ ?> checked <?php } ?> id="operation_sign_status_21" name="operation_sign_status_2" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_status_21">
                                    İşaretlenme Uygulanamaz
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label" >12.Anestezi Güvenlik Kontrol Listesi tamamlandı mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['anesthesia_safety_2']){ ?> checked <?php } ?>id="anesthesia_safety_2" name="anesthesia_safety_2" class="form-check-input">
                                    <label class="form-check-label" for="anesthesia_safety_2">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['anesthesia_safety_2']==2){ ?> checked <?php } ?>id="anesthesia_safety_21" name="anesthesia_safety_2" class="form-check-input">
                                    <label class="form-check-label" for="anesthesia_safety_21">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label" >13.Pulse oksimetre hasta üzerinde ve çalışıyor mu?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['pulse_oximeter_2']){ ?> checked <?php } ?>id="pulse_oximeter_2" name="pulse_oximeter_2" class="form-check-input">
                                    <label class="form-check-label" for="pulse_oximeter_2">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['pulse_oximeter_2']==2){ ?> checked <?php } ?>id="pulse_oximeter_21" name="pulse_oximeter_2" class="form-check-input">
                                    <label class="form-check-label" for="pulse_oximeter_21">
                                        Hastanın Risk Değerlendirmesi
                                    </label>
                                </div>
                            </div>
                        </div>



                </div>
                <div class="mt-1 row">
                    <div class="mt-1 col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" >14.Hastanın bilinen bir alerjisi var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['patient_allergy_2']){ ?> checked <?php } ?>id="patient_allergy_2" name="patient_allergy_2" class="form-check-input">
                                    <label class="form-check-label" for="patient_allergy_2">
                                        Var
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['patient_allergy_2']==2){ ?> checked <?php } ?>id="patient_allergy_21" name="patient_allergy_2" class="form-check-input">
                                    <label class="form-check-label" for="patient_allergy_21">
                                        Yok
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-1 col-md-6">
                        <div class="row mx-4">
                            <label for="basicpill-firstname-input" class="form-label" >15.Gerekli görüntüleme cihazları var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['display_device_2']){ ?> checked <?php } ?>id="display_device_2" name="display_device_2" class="form-check-input">
                                    <label class="form-check-label" for="display_device_2">
                                        Var
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['display_device_2']==2){ ?> checked <?php } ?>id="display_device_21" name="display_device_2" class="form-check-input">
                                    <label class="form-check-label" for="display_device_21">
                                        Yok
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="3" <?php if ($guvencerrahi['display_device_2']==3){ ?> checked <?php } ?>id="display_device_22" name="display_device_2" class="form-check-input">
                                    <label class="form-check-label" for="display_device_22">
                                        Gerekli Değil
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                </div>
                <div class="mt-1 row">
                    <div class="mt-1 col-md-6">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label" >16.Hastada 500 ml ya da daha fazla kan kaybı riski var mı? <?php echo $guvencerrahi['blood_loss_2']; ?></label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['blood_loss_2']==2){ ?> checked <?php } ?>id="blood_loss_2" name="blood_loss_2" class="form-check-input">
                                    <label class="form-check-label" for="blood_loss_2">
                                        Yok
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <input type="radio" value="1" <?php if ($guvencerrahi['blood_loss_2']==1){ ?> checked <?php } ?>id="blood_loss_21" name="blood_loss_2" class="form-check-input">
                                        </div>
                                        <div class="col-md-11">
                                            <label class="form-check-label" for="blood_loss_21">
                                                Var:uygun damar yolu erişimi, sıvı ve kanama durdurucu ajanlar temin edildi.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                <div class="row mx-2">
                    <div class="col-md-6">
                        <label for="basicpill-firstname-input" class="form-label " >17.Ekipteki kişiler kendilerini ad, soyad ve görevleri ile tanıttı mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['name_surname_duty_3']){ ?> checked <?php } ?>id="name_surname_duty_3" name="name_surname_duty_3" class="form-check-input">
                                <label class="form-check-label" for="name_surname_duty_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                               <input type="radio" value="2" <?php if ($guvencerrahi['name_surname_duty_3']==2){ ?> checked <?php } ?>id="name_surname_duty_31" name="name_surname_duty_3" class="form-check-input">
                                <label class="form-check-label" for="name_surname_duty_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="basicpill-firstname-input" class="form-label" >18.Ekipten bir kişi sesli olarak hastanın kimliğini, yapılan ameliyatı, ameliyat bölgesini teyit etti mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['id_surgery_surgery_region_3']){ ?> checked <?php } ?>id="id_surgery_surgery_region_3" name="id_surgery_surgery_region_3" class="form-check-input">
                                <label class="form-check-label" for="id_surgery_surgery_region_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                               <input type="radio" value="2" <?php if ($guvencerrahi['id_surgery_surgery_region_3']==2){ ?> checked <?php } ?>id="id_surgery_surgery_region_31" name="id_surgery_surgery_region_3" class="form-check-input">
                                <label class="form-check-label" for="id_surgery_surgery_region_31">
                                   Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label"> 19.Kritik olaylar gözden geçirildi mi?</label>

                            <div class="row">
                                <div class="col-md-6">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['operation_time_3']){ ?> checked <?php } ?>id="operation_time_3" name="operation_time_3" class="form-check-input">
                                    <label class="form-check-label" for="operation_time_3">
                                        Tahmini ameliyat süresi
                                    </label>
                                </div>
                                <div class="col-md-6">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['blood_loss_3']){ ?> checked <?php } ?>id="blood_loss_3" name="blood_loss_3" class="form-check-input">
                                    <label class="form-check-label" for="blood_loss_3">
                                        Beklenen kan kaybı
                                    </label>
                                </div>
                                <div class="col-md-6">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['anesthesia_risky_3']){ ?> checked <?php } ?>id="anesthesia_risky_3" name="anesthesia_risky_3" class="form-check-input">
                                    <label class="form-check-label" for="anesthesia_risky_3">
                                        Olası anestezi riskleri
                                    </label>
                                </div>
                                <div class="col-md-6">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['patient_position_3']){ ?> checked <?php } ?>id="patient_position_3" name="patient_position_3" class="form-check-input">
                                    <label class="form-check-label" for="patient_position_3">
                                        Hastanın pozisyonu
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['surgery_event_3']){ ?> checked <?php } ?>id="surgery_event_3" name="surgery_event_3" class="form-check-input">
                                    <label class="form-check-label" for="surgery_event_3">
                                        Ameliyat sırasında gerçekleşebilecek beklenmedik olaylar
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row ">
                            <label class="form-label" >20.Profilaktik antibiyotik sorgulandı mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['prophylactic_antibiotic_3']==2){ ?> checked <?php } ?>id="prophylactic_antibiotic_3" name="prophylactic_antibiotic_3" class="form-check-input">
                                    <label class="form-check-label" for="prophylactic_antibiotic_3">
                                        Kullanılmaz
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="radio" value="1"
                                        <?php if ($guvencerrahi['prophylactic_antibiotic_3']){ ?> checked <?php } ?>
                                           id="prophylactic_antibiotic_31" name="prophylactic_antibiotic_3" class="form-check-input">
                                    <label class="form-check-label" for="prophylactic_antibiotic_31">Kesiden önceki son 60 dakika içerisinde uygulandı</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label" >21.Kullanılacak malzemeler hazır mı? </label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['material_ready_3']){ ?> checked <?php } ?>id="material_ready_3" name="material_ready_3" class="form-check-input">
                                    <label class="form-check-label" for="material_ready_3">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['material_ready_3']==2){ ?> checked <?php } ?>id="material_ready_31" name="material_ready_3" class="form-check-input">
                                    <label class="form-check-label" for="material_ready_31">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label " >22.Malzemelerin sterilizasyonu uygun mu?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['material_sterilization_3']){ ?> checked <?php } ?>id="material_sterilization_3" name="material_sterilization_3" class="form-check-input">
                                    <label class="form-check-label" for="material_sterilization_3">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['material_sterilization_3']==2){ ?> checked <?php } ?>id="material_sterilization_31" name="material_sterilization_3" class="form-check-input">
                                    <label class="form-check-label" for="material_sterilization_31">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label" >23.Kan şekeri kontrolü gerekli mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['blood_sugar_3']){ ?> checked <?php } ?>id="blood_sugar_3" name="blood_sugar_3" class="form-check-input">
                                    <label class="form-check-label" for="blood_sugar_3">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['blood_sugar_3']==2){ ?> checked <?php } ?>id="blood_sugar_31" name="blood_sugar_3" class="form-check-input">
                                    <label class="form-check-label" for="blood_sugar_31">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row ">
                            <label class="form-label ">24.Antikoagülan kullanımı var mı? </label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['anticoagulant_3']){ ?> checked <?php } ?>id="anticoagulant_3" name="anticoagulant_3" class="form-check-input">
                                    <label class="form-check-label" for="anticoagulant_3">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                   <input type="radio" value="2" <?php if ($guvencerrahi['anticoagulant_3']==2){ ?> checked <?php } ?>id="anticoagulant_31" name="anticoagulant_3" class="form-check-input">
                                    <label class="form-check-label" for="anticoagulant_31">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-1 row">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label" >25.Derin Ven Trombozu profilaksisi gerekli mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['vein_thrombosis_3']){ ?> checked <?php } ?>id="vein_thrombosis_3" name="vein_thrombosis_3" class="form-check-input">
                                    <label class="form-check-label" for="vein_thrombosis_3">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" value="2" <?php if ($guvencerrahi['vein_thrombosis_3']==2){ ?> checked <?php } ?>id="vein_thrombosis_31" name="vein_thrombosis_3" class="form-check-input">
                                    <label class="form-check-label" for="vein_thrombosis_31">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">
                <div class="row mx-2" >
                    <div class="col-md-6">
                        <label for="basicpill-firstname-input" class="form-label" >26.Gerçekleştirilen ameliyat için sözlü olarak;</label>
                        <div class="row">
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['patient_4']){ ?> checked <?php } ?>id="patient_4" name="patient_4" class="form-check-input">
                                <label class="form-check-label" for="patient_4">
                                    Hasta
                                </label>
                            </div>
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['performed_surgery_4']){ ?> checked <?php } ?>id="performed_surgery_4" name="performed_surgery_4" class="form-check-input">
                                <label class="form-check-label" for="performed_surgery_4">
                                    Yapılan ameliyat
                                </label>
                            </div>
                            <div class="col-md-8">
                               <input type="radio" value="1" <?php if ($guvencerrahi['operation_sign_4']){ ?> checked <?php } ?>id="operation_sign_4" name="operation_sign_4" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_4">
                                    Ameliyat bölgesi, teyit edildi.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="basicpill-firstname-input" class="form-label " >27.Alet, spanç/kompres ve iğne  sayımları yapıldı mı?</label>
                        <div class="row ">
                            <div class="col-md-4">
                               <input type="radio" value="1" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']){ ?> checked <?php } ?>id="tool_sponge_compress_needle_4" name="tool_sponge_compress_needle_4" class="form-check-input">
                                <label class="form-check-label" for="tool_sponge_compress_needle_4">
                                    Evet/Tam
                                </label>
                            </div>
                            <div class="col-md-4">
                               <input type="radio" value="2" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==2){ ?> checked <?php } ?>id="tool_sponge_compress_needle_41" name="tool_sponge_compress_needle_4" class="form-check-input">
                                <label class="form-check-label" for="tool_sponge_compress_needle_41">
                                    Hayır
                                </label>
                            </div>
                            <div class="col-md-4">
                               <input type="radio" value="3" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==3){ ?> checked <?php } ?>id="tool_sponge_compress_needle_42" name="tool_sponge_compress_needle_4" class="form-check-input">
                                <label class="form-check-label" for="tool_sponge_compress_needle_42">
                                    Sayım Uygulanmaz
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label">28.Hastadan alınan numune etiketinde;</label>
                            <div class="row">
                                <div class="col-md-5">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['patient_name_4']){ ?> checked <?php } ?>id="patient_name_4" name="patient_name_4" class="form-check-input">
                                    <label class="form-check-label" for="patient_name_4">
                                        Hastanın adı doğru yazılı
                                    </label>
                                </div>
                                <div class="col-md-7">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['sample_number_4']){ ?> checked <?php } ?>id="sample_number_4" name="sample_number_4" class="form-check-input">
                                    <label class="form-check-label" for="sample_number_4">
                                        Numunenin alındığı bölge yazılı
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label">29.Ameliyat sonrası kritik gereksinimler gözden geçirildi mi?</label>
                            <div class="row">
                                <div class="col-md-4">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['anesthetist_advice_4']){ ?> checked <?php } ?>id="anesthetist_advice_4" name="anesthetist_advice_4" class="form-check-input">
                                    <label class="form-check-label" for="anesthetist_advice_4">
                                        Anestezistin önerileri
                                    </label>
                                </div>
                                <div class="col-md-8">
                                   <input type="radio" value="1" <?php if ($guvencerrahi['surgery_advice_4']){ ?> checked <?php } ?>id="surgery_advice_4" name="surgery_advice_4" class="form-check-input">
                                    <label class="form-check-label" for="surgery_advice_4">
                                        Cerrahın önerileri
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label">30.Hastanın ameliyat sonrası gideceği bölüm teyit edildi mi?
                            </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['section_to_go_4']){ ?> checked <?php } ?>id="section_to_go_4" name="section_to_go_4" class="form-check-input">
                                    <label class="form-check-label" for="section_to_go_4">
                                        Evet
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="radio" value="1" <?php if ($guvencerrahi['section_to_go_4']){ ?> checked <?php } ?>id="section_to_go_41" name="section_to_go_4" class="form-check-input">
                                    <label class="form-check-label" for="section_to_go_41">
                                        Hayır
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <form id="formgcapdf" style="display:none" enctype="text/plain" class="form-control"  >
        <div  class="m-1" >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h6>Güvenli Cerrahi Kontrol Listesi Formu</h6>
                </div>
            </div>
            <div class="border border-dark mt-2 px-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Adı Soyadı</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['patient_name'].' '.$patients['patient_surname']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">T.C. Kimlik No</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['tc_id']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Baba Adı</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['father']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Anne Adı</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['mother']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Doğum Yeri</label>
                            <div class="col-md-6">
                                <h6><?php $d=singularactive("province","id",$patients['birth_place']);
                                    echo $d['province_name'];
                                    ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Doğum  Tarihi</label>
                            <div class="col-md-6">
                                <h6><?php echo nettarih($patients['birth_date']); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Servis</label>
                            <div class="col-md-6">
                                <h6><?php echo birimgetirid($y_birim); ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Doktor</label>
                            <div class="col-md-6">
                                <h6><?php echo kullanicigetirid($y_doktor); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Protokol Numarası</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $yatis['protocol_number'] ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Form Numarası</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php  echo $guvencerrahi['form_number']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Ameliyat Yapılan Yer:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $guvencerrahi['operation_name'] ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Ameliyat Bölgesi:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php  echo $guvencerrahi['surgical_site']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Ameliyat Tarihi:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php  echo $guvencerrahi['surgery_date']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İşlem Tarihi:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php  echo $guvencerrahi['process_datetime']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="margin-top:10px;font-weight: bold;margin-left:5px;margin-bottom:10px">I. Klinikten Ayrılmadan Önce</div>
                <div style="width:100%;border:1px solid;padding-right:10px;padding-left:10px">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label" >1.Hastanın:</label>
                        <div class="row ">
                            <div class="col-md-3">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['id_information_1']==1){ ?> checked <?php } ?>  id="id_information_1" name="id_information_1" class="form-check-input">
                                <label class="form-check-label" for="id_information_1">
                                    Kimlik bilgileri
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_1']==1){ ?> checked <?php } ?> id="operation_1"  name="operation_1" class="form-check-input">
                                <label class="form-check-label" for="operation_1">
                                    Ameliyatı
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_site_1']==1){ ?> checked <?php } ?>id="surgery_site_1" name="surgery_site_1" class="form-check-input">
                                <label class="form-check-label" for="surgery_site_1">
                                    Ameliyat bölgesi doğrulandı.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label " >2.Hastanın rızası kontrol edildi mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_status_1']==1){ ?> checked <?php } ?>id="operation_status_1" name="operation_status_1" class="form-check-input">
                                <label class="form-check-label" for="operation_status_1">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_status_1']==2){ ?> checked <?php } ?>id="operation_status" name="operation_status_1" class="form-check-input">
                                <label class="form-check-label" for="operation_status">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label " >3.Hasta aç mı?</label>

                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['sick_hungry_1']==1){ ?> checked <?php } ?>id="sick_hungry_1" name="sick_hungry_1" class="form-check-input">
                                <label class="form-check-label" for="sick_hungry_1">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['sick_hungry_1']==2){ ?> checked <?php } ?>id="sick_hungry" name="sick_hungry_1" class="form-check-input">
                                <label class="form-check-label" for="sick_hungry">
                                    Hayır
                                </label>
                            </div>


                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label " >4.Ameliyat bölgesi tıraşı yapıldı mı? </label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_shaved_1']==1){ ?> checked <?php } ?>id="operation_shaved_13" name="operation_shaved_1" class="form-check-input">
                                <label class="form-check-label" for="operation_shaved_13">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_shaved_1']==2){ ?> checked <?php } ?>id="operation_shaved_12" name="operation_shaved_1" class="form-check-input">
                                <label class="form-check-label" for="operation_shaved_12">
                                    Hayır
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="3" <?php if ($guvencerrahi['operation_shaved_1']==3){ ?> checked <?php } ?>id="operation_shaved_1" name="operation_shaved_1" class="form-check-input">
                                <label class="form-check-label" for="operation_shaved_1">
                                    Gerekli değil
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label" > 5.Hastada makyaj/oje, protez, değerli eşya var mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['sick_item_1']==1){ ?> checked <?php } ?>id="sick_item_14" name="sick_item_1" class="form-check-input">
                                <label class="form-check-label" for="sick_item_14">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['sick_item_1']==2){ ?> checked <?php } ?>id="sick_item_1" name="sick_item_1" class="form-check-input">
                                <label class="form-check-label" for="sick_item_1">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label " >6.Hastanın kıyafetleri tümüyle çıkarılıp ameliyat önlüğü ve bonesi giydirildi mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_apron_bone_1']==1){ ?> checked <?php } ?>id="operation_apron_bone_12" name="operation_apron_bone_1" class="form-check-input">
                                <label class="form-check-label" for="operation_apron_bone_12">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_apron_bone_1']==2){ ?> checked <?php } ?>id="operation_apron_bone_1" name="operation_apron_bone_1" class="form-check-input">
                                <label class="form-check-label" for="operation_apron_bone_1">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <label class="form-label " >7.Ameliyat öncesi gerekli özel işlem var mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['enema_1']==1){ ?> checked <?php } ?>id="enema_1" name="enema_1" class="form-check-input">
                                <label class="form-check-label" for="enema_1">
                                    Lavman
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['bladder_catheterization_1']==2){ ?> checked <?php } ?>id="bladder_catheterization_1" name="bladder_catheterization_1" class="form-check-input">
                                <label class="form-check-label" for="bladder_catheterization_1">
                                    Mesane Kateterizasyonu
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['compression_stockings_1']==1){ ?> checked <?php } ?>id="compression_stockings_1" name="compression_stockings_1" class="form-check-input">
                                <label class="form-check-label" for="compression_stockings_1">
                                    Varis Çorabı
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['special_treatment_protocol_1']==2){ ?> checked <?php } ?>id="special_treatment_protocol_1" name="special_treatment_protocol_1" class="form-check-input">
                                <label class="form-check-label" for="special_treatment_protocol_1">
                                    Özel Tedavi protokolü
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['other_1']==1){ ?> checked <?php } ?>id="other_1" name="other_1" class="form-check-input">
                                <label class="form-check-label" for="other_1">
                                    Diğer
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['no_process_1']==2){ ?> checked <?php } ?>id="no_process_1" name="no_process_1" class="form-check-input">
                                <label class="form-check-label" for="no_process_1">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <label class="form-label " >8.Ameliyat için gerekli olacak özel malzeme,implant, kan veya kan ürünü hazırlığı teyit edildi mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_special_material_1']==1){ ?> checked <?php } ?>id="operation_special_material_15" name="operation_special_material_1" class="form-check-input">
                                <label class="form-check-label" for="operation_special_material_15">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_special_material_1']==2){ ?> checked <?php } ?>id="operation_special_material_1" name="operation_special_material_1" class="form-check-input">
                                <label class="form-check-label" for="operation_special_material_1">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label" >9.Hastanın gerekli laboratuar ve radyoloji ve tetkikleri mevcut mu?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['lab_rad_conclusion_1']==1){ ?> checked <?php } ?>id="lab_rad_conclusion_14" name="lab_rad_conclusion_1" class="form-check-input">
                                <label class="form-check-label" for="lab_rad_conclusion_14">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['lab_rad_conclusion_1']==2){ ?> checked <?php } ?>id="lab_rad_conclusion_1" name="lab_rad_conclusion_1" class="form-check-input">
                                <label class="form-check-label" for="lab_rad_conclusion_1">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div align="right" style="padding-right:200px;margin-top:100px;">
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
                    </div>

                </div>
                <div style="margin-top:100px;font-weight: bold;margin-left:5px;margin-bottom:10px">II. Anestezi Verilmeden Önce</div>
                <div style="width:100%;border:1px solid;padding-right:10px;padding-left:10px">
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label" > 10.Hastanın kendisinden</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="radio" value="1" <?php if ($guvencerrahi['id_information_2']==1){ ?> checked <?php } ?>id="id_information_2" name="id_information_2" class="form-check-input">
                                <label class="form-check-label" for="id_information_2">
                                    Kimlik bilgileri
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" value="1" <?php if ($guvencerrahi['operation_2']==1){ ?> checked <?php } ?>id="operation_2" name="operation_2" class="form-check-input">
                                <label class="form-check-label" for="operation_2">
                                    Ameliyatı
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" value="1" <?php if ($guvencerrahi['surgery_site_2']==1){ ?> checked <?php } ?>id="surgery_site_2" name="surgery_site_2" class="form-check-input">
                                <label class="form-check-label" for="surgery_site_2">
                                    Ameliyat bölgesi
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="radio" value="1" <?php if ($guvencerrahi['operation_status_2']==1){ ?> checked <?php } ?>id="operation_status_2" name="operation_status_2" class="form-check-input">
                                <label class="form-check-label" for="operation_status_2">
                                    Ameliyatı ile ilgili rızası doğrulandı.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label" > 11.Ameliyat bölgesinde işaretleme var mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="radio" value="1" <?php if ($guvencerrahi['operation_sign_status_2']==1){ ?> checked <?php } ?>id="operation_sign_status_2" name="operation_sign_status_2" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_status_2">
                                    Var
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" value="2" <?php if ($guvencerrahi['operation_sign_status_2']==2){ ?> checked <?php } ?>id="operation_sign_status_21" name="operation_sign_status_2" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_status_21">
                                    İşaretlenme Uygulanamaz
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding-top:5px">
                        <label class="form-label" >12.Anestezi Güvenlik Kontrol Listesi tamamlandı mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthesia_safety_2']==1){ ?> checked <?php } ?>id="anesthesia_safety_2" name="anesthesia_safety_2" class="form-check-input">
                                <label class="form-check-label" for="anesthesia_safety_2">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['anesthesia_safety_2']==2){ ?> checked <?php } ?>id="anesthesia_safety_21" name="anesthesia_safety_2" class="form-check-input">
                                <label class="form-check-label" for="anesthesia_safety_21">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label" >13.Pulse oksimetre hasta üzerinde ve çalışıyor mu?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['pulse_oximeter_2']==1){ ?> checked <?php } ?>id="pulse_oximeter_2" name="pulse_oximeter_2" class="form-check-input">
                                <label class="form-check-label" for="pulse_oximeter_2">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['pulse_oximeter_2']==2){ ?> checked <?php } ?>id="pulse_oximeter_21" name="pulse_oximeter_2" class="form-check-input">
                                <label class="form-check-label" for="pulse_oximeter_21">
                                    Hastanın Risk Değerlendirmesi
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label" >14.Hastanın bilinen bir alerjisi var mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_allergy_2']==1){ ?> checked <?php } ?>id="patient_allergy_2" name="patient_allergy_2" class="form-check-input">
                                <label class="form-check-label" for="patient_allergy_2">
                                    Var
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['patient_allergy_2']==2){ ?> checked <?php } ?>id="patient_allergy_21" name="patient_allergy_2" class="form-check-input">
                                <label class="form-check-label" for="patient_allergy_21">
                                    Yok
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class=" row">

                        <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label" >15.Gerekli görüntüleme cihazları var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="checkbox" value="1" <?php if ($guvencerrahi['display_device_2']==1){ ?> checked <?php } ?>id="display_device_2" name="display_device_2" class="form-check-input">
                                    <label class="form-check-label" for="display_device_2">
                                        Var
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="checkbox" value="2" <?php if ($guvencerrahi['display_device_2']==2){ ?> checked <?php } ?>id="display_device_21" name="display_device_2" class="form-check-input">
                                    <label class="form-check-label" for="display_device_21">
                                        Yok
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label" >16.Hastada 500 ml ya da daha fazla kan kaybı riski var mı?</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="checkbox" value="2" <?php if ($guvencerrahi['blood_loss_2']==2){ ?> checked <?php } ?>id="blood_loss_2" name="blood_loss_2" class="form-check-input">
                                    <label class="form-check-label" for="blood_loss_2">
                                        Yok
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <input type="checkbox" value="1" <?php if ($guvencerrahi['blood_loss_2']==1){ ?> checked <?php } ?>id="blood_loss_21" name="blood_loss_2" class="form-check-input">
                                        </div>
                                        <div class="col-md-11">
                                            <label class="form-check-label" for="blood_loss_21">
                                                Var:uygun damar yolu erişimi, sıvı ve kanama durdurucu ajanlar temin edildi.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div align="right" style="padding-right:200px;margin-top:100px;">
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
                    </div>

                </div>
                <div style="margin-top:400px;font-weight: bold;margin-left:5px;margin-bottom:10px">III. Ameliyat Kesisinden Önce</div>
                <div style="width:100%;border:1px solid;padding-right:10px;padding-left:10px">
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label " >17.Ekipteki kişiler kendilerini ad, soyad ve görevleri ile tanıttı mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['name_surname_duty_3']==1){ ?> checked <?php } ?>id="name_surname_duty_3" name="name_surname_duty_3" class="form-check-input">
                                <label class="form-check-label" for="name_surname_duty_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['name_surname_duty_3']==2){ ?> checked <?php } ?>id="name_surname_duty_31" name="name_surname_duty_3" class="form-check-input">
                                <label class="form-check-label" for="name_surname_duty_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label" >18.Ekipten bir kişi sesli olarak hastanın kimliğini, yapılan ameliyatı, ameliyat bölgesini teyit etti mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['id_surgery_surgery_region_3']==1){ ?> checked <?php } ?>id="id_surgery_surgery_region_3" name="id_surgery_surgery_region_3" class="form-check-input">
                                <label class="form-check-label" for="id_surgery_surgery_region_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['id_surgery_surgery_region_3']==2){ ?> checked <?php } ?>id="id_surgery_surgery_region_31" name="id_surgery_surgery_region_3" class="form-check-input">
                                <label class="form-check-label" for="id_surgery_surgery_region_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="form-label"> 19.Kritik olaylar gözden geçirildi mi?</label>

                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_time_3']==1){ ?> checked <?php } ?>id="operation_time_3" name="operation_time_3" class="form-check-input">
                                <label class="form-check-label" for="operation_time_3">
                                    Tahmini ameliyat süresi
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['blood_loss_3']==1){ ?> checked <?php } ?>id="blood_loss_3" name="blood_loss_3" class="form-check-input">
                                <label class="form-check-label" for="blood_loss_3">
                                    Beklenen kan kaybı
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthesia_risky_3']==1){ ?> checked <?php } ?>id="anesthesia_risky_3" name="anesthesia_risky_3" class="form-check-input">
                                <label class="form-check-label" for="anesthesia_risky_3">
                                    Olası anestezi riskleri
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_position_3']==1){ ?> checked <?php } ?>id="patient_position_3" name="patient_position_3" class="form-check-input">
                                <label class="form-check-label" for="patient_position_3">
                                    Hastanın pozisyonu
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_event_3']==1){ ?> checked <?php } ?>id="surgery_event_3" name="surgery_event_3" class="form-check-input">
                                <label class="form-check-label" for="surgery_event_3">
                                    Ameliyat sırasında gerçekleşebilecek beklenmedik olaylar
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="row ">
                        <label class="form-label" >20.Profilaktik antibiyotik sorgulandı mı?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['prophylactic_antibiotic_3']==2){ ?> checked <?php } ?>id="prophylactic_antibiotic_3" name="prophylactic_antibiotic_3" class="form-check-input">
                                <label class="form-check-label" for="prophylactic_antibiotic_3">
                                    Kullanılmaz
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['prophylactic_antibiotic_3']==1){ ?> checked <?php } ?>id="prophylactic_antibiotic_31" name="prophylactic_antibiotic_3" class="form-check-input">
                                <label class="form-check-label" for="prophylactic_antibiotic_31">Kesiden önceki son 60 dakika içerisinde uygulandı</label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label" >21.Kullanılacak malzemeler hazır mı? </label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['material_ready_3']==1){ ?> checked <?php } ?>id="material_ready_3" name="material_ready_3" class="form-check-input">
                                <label class="form-check-label" for="material_ready_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['material_ready_3']==2){ ?> checked <?php } ?>id="material_ready_31" name="material_ready_3" class="form-check-input">
                                <label class="form-check-label" for="material_ready_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label for="basicpill-firstname-input" class="form-label " >22.Malzemelerin sterilizasyonu uygun mu?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['material_sterilization_3']==1){ ?> checked <?php } ?>id="material_sterilization_3" name="material_sterilization_3" class="form-check-input">
                                <label class="form-check-label" for="material_sterilization_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['material_sterilization_3']==2){ ?> checked <?php } ?>id="material_sterilization_31" name="material_sterilization_3" class="form-check-input">
                                <label class="form-check-label" for="material_sterilization_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label" >23.Kan şekeri kontrolü gerekli mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['blood_sugar_3']==1){ ?> checked <?php } ?>id="blood_sugar_3" name="blood_sugar_3" class="form-check-input">
                                <label class="form-check-label" for="blood_sugar_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['blood_sugar_3']==2){ ?> checked <?php } ?>id="blood_sugar_31" name="blood_sugar_3" class="form-check-input">
                                <label class="form-check-label" for="blood_sugar_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <label class="form-label ">24.Antikoagülan kullanımı var mı? </label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['anticoagulant_3']==1){ ?> checked <?php } ?>id="anticoagulant_3" name="anticoagulant_3" class="form-check-input">
                                <label class="form-check-label" for="anticoagulant_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['anticoagulant_3']==2){ ?> checked <?php } ?>id="anticoagulant_31" name="anticoagulant_3" class="form-check-input">
                                <label class="form-check-label" for="anticoagulant_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <label class="form-label ">25.Derin Ven Trombozu profilaksisi gerekli mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['vein_thrombosis_3']==1){ ?> checked <?php } ?>id="vein_thrombosis_3" name="vein_thrombosis_3" class="form-check-input">
                                <label class="form-check-label" for="vein_thrombosis_3">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['vein_thrombosis_3']==2){ ?> checked <?php } ?>id="vein_thrombosis_31" name="vein_thrombosis_3" class="form-check-input">
                                <label class="form-check-label" for="vein_thrombosis_31">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>

                </div>


              <div style="margin-top:10px;font-weight: bold;margin-bottom: 10px;">IV. Ameliyattan Çıkmadan Önce</div>
                <div style="width:100%;border:1px solid;padding-right:10px;padding-left:10px">
                    <div class="row " >
                        <label for="basicpill-firstname-input" class="form-label" >26.Gerçekleştirilen ameliyat için sözlü olarak;</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_4']==1){ ?> checked <?php } ?>id="patient_4" name="patient_4" class="form-check-input">
                                <label class="form-check-label" for="patient_4">
                                    Hasta
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['performed_surgery_4']==1){ ?> checked <?php } ?>id="performed_surgery_4" name="performed_surgery_4" class="form-check-input">
                                <label class="form-check-label" for="performed_surgery_4">
                                    Yapılan ameliyat
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_sign_4']==1){ ?> checked <?php } ?>id="operation_sign_4" name="operation_sign_4" class="form-check-input">
                                <label class="form-check-label" for="operation_sign_4">
                                    Ameliyat bölgesi, teyit edildi.
                                </label>
                            </div>
                        </div>


                        <label for="basicpill-firstname-input" class="form-label " >27.Alet, spanç/kompres ve iğne  sayımları yapıldı mı?</label>
                        <div class="row ">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==1){ ?> checked <?php } ?>id="tool_sponge_compress_needle_4" name="tool_sponge_compress_needle_4" class="form-check-input">
                                <label class="form-check-label" for="tool_sponge_compress_needle_4">
                                    Evet/Tam
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="checkbox" value="2" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==2){ ?> checked <?php } ?>id="tool_sponge_compress_needle_41" name="tool_sponge_compress_needle_4" class="form-check-input">
                                <label class="form-check-label" for="tool_sponge_compress_needle_41">
                                    Hayır
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <label class="form-label">28.Hastadan alınan numune etiketinde;</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_name_4']==1){ ?> checked <?php } ?>id="patient_name_4" name="patient_name_4" class="form-check-input">
                                <label class="form-check-label" for="patient_name_4">
                                    Hastanın adı doğru yazılı
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['sample_number_4']==1){ ?> checked <?php } ?>id="sample_number_4" name="sample_number_4" class="form-check-input">
                                <label class="form-check-label" for="sample_number_4">
                                    Numunenin alındığı bölge yazılı
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="form-label">29.Ameliyat sonrası kritik gereksinimler gözden geçirildi mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthetist_advice_4']==1){ ?> checked <?php } ?>id="anesthetist_advice_4" name="anesthetist_advice_4" class="form-check-input">
                                <label class="form-check-label" for="anesthetist_advice_4">
                                    Anestezistin önerileri
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_advice_4']==1){ ?> checked <?php } ?>id="surgery_advice_4" name="surgery_advice_4" class="form-check-input">
                                <label class="form-check-label" for="surgery_advice_4">
                                    Cerrahın önerileri
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="form-label">30.Hastanın ameliyat sonrası gideceği bölüm teyit edildi mi?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['section_to_go_4']==1){ ?> checked <?php } ?>id="section_to_go_4" name="section_to_go_4" class="form-check-input">
                                <label class="form-check-label" for="section_to_go_4">
                                    Evet
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="checkbox" value="1" <?php if ($guvencerrahi['section_to_go_4']==1){ ?> checked <?php } ?>id="section_to_go_41" name="section_to_go_4" class="form-check-input">
                                <label class="form-check-label" for="section_to_go_41">
                                    Hayır
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
        </div>
        <div align="right" style="padding-right:200px;margin-top:100px;">
            <div>
                <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
            </div>
            <div>
                <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
            </div>
            <div>
                <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
            </div>

        </div>
    </form>

    <form id="formgcaprint" style="display:none" enctype="text/plain" class="form-control">
        <div  >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h5>Güvenli Cerrahi Kontrol Listesi Formu</h5>
                </div>
            </div>
            <table style="width:100%;border:1px solid;">
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Adı Soyadı:</td>
                    <td><?php echo $patients['patient_name'].' '.$patients['patient_surname']; ?></td>
                    <td style="font-weight: bold;">T.C. Kimlik No:</td>
                    <td><?php echo $patients['tc_id']; ?></td>
                </tr>
                <tr  style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Baba Adı:</td>
                    <td><?php echo $patients['father']; ?></td>
                    <td style="font-weight: bold;">Anne Adı:</td>
                    <td><?php echo $patients['mother']; ?></td>
                </tr>
                <tr  style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Doğum Yeri:</td>
                    <td><?php
                        $d=singularactive("province","id",$patients['birth_place']);
                        echo $d['province_name'];
                        ?></td>
                    <td style="font-weight: bold;">Doğum Tarihi:</td>
                    <td><?php echo nettarih($patients['birth_date']); ?></td>
                </tr>
                <tr  style="margin-top:2px;margin-bottom=2px;">
                    <td  style="font-weight: bold;">Servis:</td>
                    <td><?php echo birimgetirid($y_birim); ?></td>
                    <td style="font-weight: bold;">Doktor:</td>
                    <td><?php echo kullanicigetirid($y_doktor); ?></td>
                </tr>

                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Protokol Numarası:</td>
                    <td><?php echo $yatis['protocol_number'] ?></td>
                    <td style="font-weight: bold;">Form Numarası:</td>
                    <td><?php  echo $guvencerrahi['form_number']; ?></td>
                </tr>

                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Ameliyat Yapılan Yer:</td>
                    <td><?php echo $yatis['operation_name'] ?></td>
                    <td style="font-weight: bold;">Ameliyat Bölgesi:</td>
                    <td><?php  echo $guvencerrahi['surgical_site']; ?></td>
                </tr>

                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td style="font-weight: bold;">Ameliyat Tarihi:</td>
                    <td>   <?php  echo $guvencerrahi['surgery_date']; ?></td>
                    <td style="font-weight: bold;">İşlem Tarihi:</td>
                    <td>   <?php  echo $guvencerrahi['process_datetime']; ?></td>
                </tr>
            </table>
            <div style="margin-top:10px;font-weight: bold;margin-bottom:10px">I. Klinikten Ayrılmadan Önce</div>
            <table style="width:100%;border:1px solid;">
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        1.Hastanın:
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['id_information_1']==1){ ?> checked <?php } ?>  id="id_information_1" name="id_information_1" class="form-check-input">
                        <label class="form-check-label" for="id_information_1">
                            Kimlik bilgileri
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_1']==1){ ?> checked <?php } ?> id="operation_1"  name="operation_1" class="form-check-input">
                        <label class="form-check-label" for="operation_1">
                            Ameliyatı
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_site_1']==1){ ?> checked <?php } ?>id="surgery_site_1" name="surgery_site_1" class="form-check-input">
                        <label class="form-check-label" for="surgery_site_1">
                            Ameliyat bölgesi doğrulandı.
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        2.Hastanın rızası kontrol edildi mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_status_1']==1){ ?> checked <?php } ?>id="operation_status_1" name="operation_status_1" class="form-check-input">
                        <label class="form-check-label" for="operation_status_1">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_status_1']==2){ ?> checked <?php } ?>id="operation_status" name="operation_status_1" class="form-check-input">
                        <label class="form-check-label" for="operation_status">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        3.Hasta aç mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['sick_hungry_1']==1){ ?> checked <?php } ?>id="sick_hungry_1" name="sick_hungry_1" class="form-check-input">
                        <label class="form-check-label" for="sick_hungry_1">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['sick_hungry_1']==2){ ?> checked <?php } ?>id="sick_hungry" name="sick_hungry_1" class="form-check-input">
                        <label class="form-check-label" for="sick_hungry">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        4.Ameliyat bölgesi tıraşı yapıldı mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_shaved_1']==1){ ?> checked <?php } ?>id="operation_shaved_13" name="operation_shaved_1" class="form-check-input">
                        <label class="form-check-label" for="operation_shaved_13">
                            Evet
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_shaved_1']==2){ ?> checked <?php } ?>id="operation_shaved_12" name="operation_shaved_1" class="form-check-input">
                        <label class="form-check-label" for="operation_shaved_12">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        5.Hastada makyaj/oje, protez, değerli eşya var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['sick_item_1']==1){ ?> checked <?php } ?>id="sick_item_14" name="sick_item_1" class="form-check-input">
                        <label class="form-check-label" for="sick_item_14">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['sick_item_1']==2){ ?> checked <?php } ?>id="sick_item_1" name="sick_item_1" class="form-check-input">
                        <label class="form-check-label" for="sick_item_1">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        6.Hastanın kıyafetleri tümüyle çıkarılıp ameliyat önlüğü ve bonesi giydirildi mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_apron_bone_1']==1){ ?> checked <?php } ?>id="operation_apron_bone_12" name="operation_apron_bone_1" class="form-check-input">
                        <label class="form-check-label" for="operation_apron_bone_12">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_apron_bone_1']==2){ ?> checked <?php } ?>id="operation_apron_bone_1" name="operation_apron_bone_1" class="form-check-input">
                        <label class="form-check-label" for="operation_apron_bone_1">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        7.Ameliyat öncesi gerekli özel işlem var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['enema_1']==1){ ?> checked <?php } ?>id="enema_1" name="enema_1" class="form-check-input">
                        <label class="form-check-label" for="enema_1">
                            Lavman
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['bladder_catheterization_1']==2){ ?> checked <?php } ?> id="bladder_catheterization_1" name="bladder_catheterization_1" class="form-check-input">
                        <label class="form-check-label" for="bladder_catheterization_1">
                            Mesane Kateterizasyonu
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['compression_stockings_1']==1){ ?> checked <?php } ?> id="compression_stockings_1" name="compression_stockings_1" class="form-check-input">
                        <label class="form-check-label" for="compression_stockings_1">
                            Varis Çorabı
                        </label>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['special_treatment_protocol_1']==2){ ?> checked <?php } ?> id="special_treatment_protocol_1" name="special_treatment_protocol_1" class="form-check-input">
                        <label class="form-check-label" for="special_treatment_protocol_1">
                            Özel Tedavi protokolü
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['other_1']==1){ ?> checked <?php } ?>id="other_1" name="other_1" class="form-check-input">
                        <label class="form-check-label" for="other_1">
                            Diğer
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['no_process_1']==2){ ?> checked <?php } ?>id="no_process_1" name="no_process_1" class="form-check-input">
                        <label class="form-check-label" for="no_process_1">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        8.Ameliyat için gerekli olacak özel malzeme, implant, kan veya kan ürünü hazırlığı teyit edildi mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_special_material_1']==1){ ?> checked <?php } ?>id="operation_special_material_15" name="operation_special_material_1" class="form-check-input">
                        <label class="form-check-label" for="operation_special_material_15">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['operation_special_material_1']==2){ ?> checked <?php } ?>id="operation_special_material_1" name="operation_special_material_1" class="form-check-input">
                        <label class="form-check-label" for="operation_special_material_1">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        9.Hastanın gerekli laboratuar ve radyoloji ve tetkikleri mevcut mu?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['lab_rad_conclusion_1']==1){ ?> checked <?php } ?>id="lab_rad_conclusion_14" name="lab_rad_conclusion_1" class="form-check-input">
                        <label class="form-check-label" for="lab_rad_conclusion_14">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['lab_rad_conclusion_1']==2){ ?> checked <?php } ?>id="lab_rad_conclusion_1" name="lab_rad_conclusion_1" class="form-check-input">
                        <label class="form-check-label" for="lab_rad_conclusion_1">
                            Hayır
                        </label>
                    </td>
                </tr>
            </table>
            <div align="right" style="padding-right:200px;margin-top:100px;">
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
                </div>

            </div>

            <div style="margin-top:200px;font-weight: bold;margin-bottom: 10px">II. Anestezi Verilmeden Önce</div>

            <table style="width:100%;border:1px solid;">
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        10.Hastanın kendisinden
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <input type="radio" value="1" <?php if ($guvencerrahi['id_information_2']==1){ ?> checked <?php } ?>id="id_information_2" name="id_information_2" class="form-check-input">
                        <label class="form-check-label" for="id_information_2">
                            Kimlik bilgileri
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="radio" value="1" <?php if ($guvencerrahi['operation_2']==1){ ?> checked <?php } ?>id="operation_2" name="operation_2" class="form-check-input">
                        <label class="form-check-label" for="operation_2">
                            Ameliyatı
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="radio" value="1" <?php if ($guvencerrahi['surgery_site_2']==1){ ?> checked <?php } ?>id="surgery_site_2" name="surgery_site_2" class="form-check-input">
                        <label class="form-check-label" for="surgery_site_2">
                            Ameliyat bölgesi
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="radio" value="1" <?php if ($guvencerrahi['operation_status_2']==1){ ?> checked <?php } ?>id="operation_status_2" name="operation_status_2" class="form-check-input">
                        <label class="form-check-label" for="operation_status_2">
                            Ameliyatı ile ilgili rızası doğrulandı mı?
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        11.Ameliyat bölgesinde işaretleme var mı?
                    </td>
                </tr>
                <tr>
                    <td colspan="1">
                        <input type="radio" value="1" <?php if ($guvencerrahi['operation_sign_status_2']==1){ ?> checked <?php } ?>id="operation_sign_status_2" name="operation_sign_status_2" class="form-check-input">
                        <label class="form-check-label" for="operation_sign_status_2">
                            Var
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="radio" value="2" <?php if ($guvencerrahi['operation_sign_status_2']==2){ ?> checked <?php } ?>id="operation_sign_status_21" name="operation_sign_status_2" class="form-check-input">
                        <label class="form-check-label" for="operation_sign_status_21">
                            İşaretlenme Uygulanamaz
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        12.Anestezi Güvenlik Kontrol Listesi tamamlandı mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthesia_safety_2']==1){ ?> checked <?php } ?>id="anesthesia_safety_2" name="anesthesia_safety_2" class="form-check-input">
                        <label class="form-check-label" for="anesthesia_safety_2">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['anesthesia_safety_2']==2){ ?> checked <?php } ?>id="anesthesia_safety_21" name="anesthesia_safety_2" class="form-check-input">
                        <label class="form-check-label" for="anesthesia_safety_21">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        13.Pulse oksimetre hasta üzerinde ve çalışıyor mu?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['pulse_oximeter_2']==1){ ?> checked <?php } ?>id="pulse_oximeter_2" name="pulse_oximeter_2" class="form-check-input">
                        <label class="form-check-label" for="pulse_oximeter_2">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['pulse_oximeter_2']==2){ ?> checked <?php } ?>id="pulse_oximeter_21" name="pulse_oximeter_2" class="form-check-input">
                        <label class="form-check-label" for="pulse_oximeter_21">
                            Hastanın Risk Değerlendirmesi
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        14.Hastanın bilinen bir alerjisi var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_allergy_2']==1){ ?> checked <?php } ?>id="patient_allergy_2" name="patient_allergy_2" class="form-check-input">
                        <label class="form-check-label" for="patient_allergy_2">
                            Var
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['patient_allergy_2']==2){ ?> checked <?php } ?>id="patient_allergy_21" name="patient_allergy_2" class="form-check-input">
                        <label class="form-check-label" for="patient_allergy_21">
                            Yok
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        15.Gerekli görüntüleme cihazları var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['display_device_2']==1){ ?> checked <?php } ?>id="display_device_2" name="display_device_2" class="form-check-input">
                        <label class="form-check-label" for="display_device_2">
                            Var
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['display_device_2']==2){ ?> checked <?php } ?>id="display_device_21" name="display_device_2" class="form-check-input">
                        <label class="form-check-label" for="display_device_21">
                            Yok
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold; margin-top:100px;" >
                    <td colspan="5" style="padding-top:10px">
                        16.Hastada 500 ml ya da daha fazla kan kaybı riski var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['blood_loss_2']==2){ ?> checked <?php } ?>id="blood_loss_2" name="blood_loss_2" class="form-check-input">
                        <label class="form-check-label" for="blood_loss_2">
                            Yok
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['blood_loss_2']==1){ ?> checked <?php } ?>id="blood_loss_21" name="blood_loss_2" class="form-check-input">
                        <label class="form-check-label" for="blood_loss_21">
                            Var:uygun damar yolu erişimi, sıvı ve kanama durdurucu ajanlar temin edildi.
                        </label>
                    </td>
                </tr>
            </table>

            <div align="right" style="padding-right:200px;margin-top:100px;">
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
                </div>

            </div>



            <div style="margin-top:500px;font-weight: bold;">III. Ameliyat Kesisinden Önce </div>
            <table style="width:100%;margin-top:5px;border:1px solid;">
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        17.Ekipteki kişiler kendilerini ad, soyad ve görevleri ile tanıttı mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['name_surname_duty_3']==1){ ?> checked <?php } ?>id="name_surname_duty_3" name="name_surname_duty_3" class="form-check-input">
                        <label class="form-check-label" for="name_surname_duty_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['name_surname_duty_3']==2){ ?> checked <?php } ?>id="name_surname_duty_31" name="name_surname_duty_3" class="form-check-input">
                        <label class="form-check-label" for="name_surname_duty_31">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        18.Ekipten bir kişi sesli olarak hastanın kimliğini, yapılan ameliyatı, ameliyat bölgesini teyit etti mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['id_surgery_surgery_region_3']==1){ ?> checked <?php } ?>id="id_surgery_surgery_region_3" name="id_surgery_surgery_region_3" class="form-check-input">
                        <label class="form-check-label" for="id_surgery_surgery_region_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['id_surgery_surgery_region_3']==2){ ?> checked <?php } ?>id="id_surgery_surgery_region_31" name="id_surgery_surgery_region_3" class="form-check-input">
                        <label class="form-check-label" for="id_surgery_surgery_region_31">
                            Hayır
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        19.Kritik olaylar gözden geçirildi mi?19.Kritik olaylar gözden geçirildi mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_time_3']==1){ ?> checked <?php } ?>id="operation_time_3" name="operation_time_3" class="form-check-input">
                        <label class="form-check-label" for="operation_time_3">
                            Tahmini ameliyat süresi
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthesia_risky_3']==1){ ?> checked <?php } ?>id="anesthesia_risky_3" name="anesthesia_risky_3" class="form-check-input">
                        <label class="form-check-label" for="anesthesia_risky_3">
                            Olası anestezi riskleri
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_position_3']==1){ ?> checked <?php } ?>id="patient_position_3" name="patient_position_3" class="form-check-input">
                        <label class="form-check-label" for="patient_position_3">
                            Hastanın pozisyonu
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_event_3']==1){ ?> checked <?php } ?>id="surgery_event_3" name="surgery_event_3" class="form-check-input">
                        <label class="form-check-label" for="surgery_event_3">
                            Ameliyat sırasında gerçekleşebilecek beklenmedik olaylar
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        20.Profilaktik antibiyotik sorgulandı mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['prophylactic_antibiotic_3']==2){ ?> checked <?php } ?>id="prophylactic_antibiotic_3" name="prophylactic_antibiotic_3" class="form-check-input">
                        <label class="form-check-label" for="prophylactic_antibiotic_3">
                            Kullanılmaz
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['prophylactic_antibiotic_3']==1){ ?> checked <?php } ?>id="prophylactic_antibiotic_31" name="prophylactic_antibiotic_3" class="form-check-input">
                        <label class="form-check-label" for="prophylactic_antibiotic_31">Kesiden önceki son 60 dakika içerisinde uygulandı</label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        21.Kullanılacak malzemeler hazır mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['material_ready_3']==1){ ?> checked <?php } ?>id="material_ready_3" name="material_ready_3" class="form-check-input">
                        <label class="form-check-label" for="material_ready_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['material_ready_3']==2){ ?> checked <?php } ?>id="material_ready_31" name="material_ready_3" class="form-check-input">
                        <label class="form-check-label" for="material_ready_31">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        22.Malzemelerin sterilizasyonu uygun mu?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['material_sterilization_3']==1){ ?> checked <?php } ?>id="material_sterilization_3" name="material_sterilization_3" class="form-check-input">
                        <label class="form-check-label" for="material_sterilization_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['material_sterilization_3']==2){ ?> checked <?php } ?>id="material_sterilization_31" name="material_sterilization_3" class="form-check-input">
                        <label class="form-check-label" for="material_sterilization_31">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        23.Kan şekeri kontrolü gerekli mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['blood_sugar_3']==1){ ?> checked <?php } ?>id="blood_sugar_3" name="blood_sugar_3" class="form-check-input">
                        <label class="form-check-label" for="blood_sugar_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['blood_sugar_3']==2){ ?> checked <?php } ?>id="blood_sugar_31" name="blood_sugar_3" class="form-check-input">
                        <label class="form-check-label" for="blood_sugar_31">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        24.Antikoagülan kullanımı var mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['anticoagulant_3']==1){ ?> checked <?php } ?>id="anticoagulant_3" name="anticoagulant_3" class="form-check-input">
                        <label class="form-check-label" for="anticoagulant_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['anticoagulant_3']==2){ ?> checked <?php } ?>id="anticoagulant_31" name="anticoagulant_3" class="form-check-input">
                        <label class="form-check-label" for="anticoagulant_31">
                            Hayır
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        25.Derin Ven Trombozu profilaksisi gerekli mi?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['vein_thrombosis_3']==1){ ?> checked <?php } ?>id="vein_thrombosis_3" name="vein_thrombosis_3" class="form-check-input">
                        <label class="form-check-label" for="vein_thrombosis_3">
                            Evet
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['vein_thrombosis_3']==2){ ?> checked <?php } ?>id="vein_thrombosis_31" name="vein_thrombosis_3" class="form-check-input">
                        <label class="form-check-label" for="vein_thrombosis_31">
                            Hayır
                        </label>
                    </td>
                </tr>
               </table>
                <div style="margin-top:10px;font-weight: bold;">IV. Ameliyattan Çıkmadan Önce</div>
                <table style="width:100%;margin-top:5px;border:1px solid;">
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        Gerçekleştirilen ameliyat için sözlü olarak:
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="1">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_4']==1){ ?> checked <?php } ?>id="patient_4" name="patient_4" class="form-check-input">
                        <label class="form-check-label" for="patient_4">
                            Hasta
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['performed_surgery_4']==1){ ?> checked <?php } ?>id="performed_surgery_4" name="performed_surgery_4" class="form-check-input">
                        <label class="form-check-label" for="performed_surgery_4">
                            Yapılan ameliyat
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['operation_sign_4']==1){ ?> checked <?php } ?>id="operation_sign_4" name="operation_sign_4" class="form-check-input">
                        <label class="form-check-label" for="operation_sign_4">
                            Ameliyat bölgesi, teyit edildi.
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        27.Alet, spanç/kompres ve iğne  sayımları yapıldı mı?
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==1){ ?> checked <?php } ?>id="tool_sponge_compress_needle_4" name="tool_sponge_compress_needle_4" class="form-check-input">
                        <label class="form-check-label" for="tool_sponge_compress_needle_4">
                            Evet/Tam
                        </label>
                    </td>
                    <td colspan="1">
                        <input type="checkbox" value="2" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==2){ ?> checked <?php } ?>id="tool_sponge_compress_needle_41" name="tool_sponge_compress_needle_4" class="form-check-input">
                        <label class="form-check-label" for="tool_sponge_compress_needle_41">
                            Hayır
                        </label>
                    </td>
                    <td colspan="2">
                        <input type="checkbox" value="3" <?php if ($guvencerrahi['tool_sponge_compress_needle_4']==3){ ?> checked <?php } ?>id="tool_sponge_compress_needle_42" name="tool_sponge_compress_needle_4" class="form-check-input">
                        <label class="form-check-label" for="tool_sponge_compress_needle_42">
                            Sayım Uygulanmaz
                        </label>
                    </td>
                </tr>

                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        28.Hastadan alınan numune etiketinde:
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['patient_name_4']==1){ ?> checked <?php } ?>id="patient_name_4" name="patient_name_4" class="form-check-input">
                        <label class="form-check-label" for="patient_name_4">
                            Hastanın adı doğru yazılı
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['sample_number_4']==1){ ?> checked <?php } ?>id="sample_number_4" name="sample_number_4" class="form-check-input">
                        <label class="form-check-label" for="sample_number_4">
                            Numunenin alındığı bölge yazılı
                        </label>
                    </td>
                </tr>
                <tr  style="font-weight: bold;" >
                    <td colspan="5">
                        29.Ameliyat sonrası kritik gereksinimler gözden geçirildi mi?
                    </td>
                </tr>
                <tr  style="margin-top:2px;margin-bottom=2px;">
                    <td colspan="2">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['anesthetist_advice_4']==1){ ?> checked <?php } ?>id="anesthetist_advice_4" name="anesthetist_advice_4" class="form-check-input">
                        <label class="form-check-label" for="anesthetist_advice_4">
                            Anestezistin önerileri
                        </label>
                    </td>
                    <td colspan="3">
                        <input type="checkbox" value="1" <?php if ($guvencerrahi['surgery_advice_4']==1){ ?> checked <?php } ?>id="surgery_advice_4" name="surgery_advice_4" class="form-check-input">
                        <label class="form-check-label" for="surgery_advice_4">
                            Cerrahın önerileri
                        </label>
                    </td>
                </tr>

                    <tr  style="font-weight: bold;" >
                        <td colspan="5">
                            30.Hastanın ameliyat sonrası gideceği bölüm teyit edildi mi?
                        </td>
                    </tr>
                    <tr style="margin-top:2px;margin-bottom=2px;">
                        <td colspan="2">
                            <input type="checkbox" value="1" <?php if ($guvencerrahi['section_to_go_4']==1){ ?> checked <?php } ?>id="section_to_go_4" name="section_to_go_4" class="form-check-input">
                            <label class="form-check-label" for="section_to_go_4">
                                Evet
                            </label>
                        </td>
                        <td colspan="3">
                            <input type="checkbox" value="2" <?php if ($guvencerrahi['section_to_go_4']==2){ ?> checked <?php } ?>id="section_to_go_41" name="section_to_go_4" class="form-check-input">
                            <label class="form-check-label" for="section_to_go_41">
                                Hayır
                            </label>
                        </td>
                    </tr>
            </table>
            <div align="right" style="padding-right:200px;margin-top:100px;">
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Liste Sorumlusu:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >Adı Soyadı:</label>
                </div>
                <div>
                    <label for="basicpill-firstname-input" class="form-label" >İmza:</label>
                </div>

            </div>

    </form>
<?php }
elseif ($islem == "gcainsert") {
    if ($_POST) {
        $dizi=[];
        $_POST["insert_datetime"] =$simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $yatissekle = direktekle("safe_surgery",$_POST);
        var_dump($yatissekle);
       
        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
elseif ($islem == "gcaupdate") {
    if ($_POST) {
        $dizi=[];
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];

        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("safe_surgery", "id", $id, $_POST);
        var_dump($sonuc);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
elseif ($islem == "gcabtndelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("safe_surgery", "id", $id, $detay, $silme, $tarih);
        var_dump($vezneguncelle);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}
