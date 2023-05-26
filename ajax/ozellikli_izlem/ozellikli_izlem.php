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
if ($islem == "ozellikliizlembody") {

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
                <h5 class="modal-title">Özellikli İzlem Formu - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 ">
                        <div class="card ">
                            <div class="card-header " style="height: 4vh;">Özellikli İzlem Listesi</div>
                            <div class="mx-1">
                                <div class="card-body mx-0 ozellikliizlemlistesi-<?php echo $protokolno; ?>">

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
                                        <h5 style="font-size: 13px;">Özellikli İzlem Kayıt</h5>
                                    </div>
                                    <div class="col-md-3 p-1" align="right">
                                        <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-1 my-1" >
                                <div class="ozellikliizlembody-<?php echo $protokolno; ?>">

                                </div>
                                <div class="modal-footer pb-0 pt-0 mt-1">
                                    <button type="button " class="btn btn-info btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'oyazdir'; } ?>">
                                        <i class="fas fa-print " aria-hidden="true"></i>
                                        Yazdır
                                    </button>
                                    <button type="button " class="btn btn-success btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'opdf'; } ?>">
                                        <i class="fa-regular fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        PDF
                                    </button>
                                    <button type="button " class="btn btn-warning btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'uss'; } ?>">
                                        <i class="fa-thin fa-house-window"></i>
                                        USS'ye Gönder
                                    </button>
                                    <?php if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn yeni-btn btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'oizlemyeniden'; } ?>" id="<?php echo $_GET["getir"]; ?>"><i class="fa fa-plus" aria-hidden="true"></i> Yeni</button>
                                    <?php }if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm ybizlemkaydet   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'oizleminsert'; } ?>" ><i class="fa fa-check" aria-hidden="true"></i> Kaydet</button>
                                    <?php }  if ($sevkislemupdate==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'oizlemupdate'; } ?>"><i class="fas fa-edit" aria-hidden="true"></i> Güncelle</button>
                                    <?php } if ($sevkislemdelete==1){ ?>
                                        <button type="button " class="btn sil-btn btn-sm  ozelguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'oizlemdelete'; } ?>"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                    </div>

                    <script>

                        $(document).ready(function () {

                            $(".opdf").off().on("click", function () {

                                $('#formoizlempdf').css("display", "block");
                                var element = $('#formoizlempdf').html();
                                html2pdf(element);
                                $('#formoizlempdf').css("display", "none");

                            });

                            $(".oyazdir").off().on("click", function () {

                                $('#formoizlemprint').css("display", "block");
                                var divToPrint = $('#formoizlemprint').html();

                                var newWin=window.open('','Print-Window');

                                newWin.document.open();

                                newWin.document.write('<html><body onload="window.print()">'+divToPrint+'</body></html>');

                                newWin.document.close();

                                setTimeout(function(){newWin.close();},100);

                                $('#formoizlemprint').css("display", "none");

                            });




                            $(".oizleminsert").off().on("click", function () {
                                //form reset-->

                                var say=0;
                                $('.ozellikliempty').filter(function() {
                                    if (this.value==''){
                                        say++;
                                    }
                                });

                                if (say>0) {
                                    alertify.error('Boş alanları doldurun');
                                }else{
                                    var gonderilenform = $("#formoizlem").serialize();
                                    document.getElementById("formoizlem").reset();
                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizleminsert',
                                        data: gonderilenform,
                                        success: function (e) {
                                            $("#sonucyaz").html(e);

                                            var getir = "<?php echo $_GET["getir"] ?>";
                                            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=ozellikliizlemlistesi", {getir: getir}, function (g) {
                                                $(".ozellikliizlemlistesi-<?php echo $protokolno; ?>").html(g);

                                            });
                                        }
                                    });
                                }

                            });
                            $(document).ready(function () {
                                $(".oizlemupdate").off('click').on("click", function () {
                                    //form reset-->

                                    var gonderilenform = $("#formoizlem").serialize();
                                    var getir ="<?php echo $_GET["getir"]; ?>";

                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemupdate',
                                        data: gonderilenform,
                                        success: function (e) {
                                            $("#sonucyaz").html(e);

                                            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=ozellikliizlemlistesi", {getir: getir}, function (g) {
                                                $(".ozellikliizlemlistesi-<?php echo $protokolno; ?>").html(g);
                                            });

                                        }
                                    });

                                });

                            });



                            var getir = "<?php echo $_GET["getir"] ?>";
                            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=ozellikliizlemlistesi", {getir: getir}, function (g) {
                                $(".ozellikliizlemlistesi-<?php echo $protokolno; ?>").html(g);

                            });
                            var protokol="<?php echo $_GET["getir"]; ?>";
                            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemyeniden", {getir: protokol}, function (getVeri) {
                                $(".ozellikliizlembody-<?php echo $protokolno; ?>").html(getVeri);

                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function () {
            $(".ozelguncellesil").prop('hidden', true);

            $(".oizlemyeniden").click(function () {
                $(".ozelguncellesil").prop('hidden', true);
                $(".ybizlemkaydet").prop('hidden', false);
                var getir = $(this).attr('id');
                $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemyeniden", {getir: getir}, function (getVeri) {
                    $(".ozellikliizlembody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });


            $(".oizlemdelete").click(function () {
                var id =$(".aktif-satir").attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlembtndelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            var getir = "<?php echo $_GET["getir"]; ?>";


                            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=ozellikliizlemlistesi", {getir: getir}, function (g) {
                                $(".ozellikliizlemlistesi-<?php echo $protokolno; ?>").html(g);

                                $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemyeniden", {getir: getir}, function (getVeri) {
                                    $(".ozellikliizlembody-<?php echo $protokolno; ?>").html(getVeri);

                                });

                                $(".ozelguncellesil").prop('hidden', true);
                                $(".ybizlemkaydet").prop('hidden', false);
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
elseif($islem=="ozellikliizlemlistesi"){ ?>

    <table class="table border table-bordered border-dark table-hover nowrap display w-100" style="font-size: 13px;" id="tableyogumbakim-<?php echo $_GET["getir"]; ?>">
        <thead>
        <tr>
            <th>Oluşturulma Tarihi</th>
            <th>Personel</th>
<!--            <th>Birim</th>-->
        </tr>
        </thead>
        <tbody>

        <?php $id=$_GET["getir"];
        $demo = "Select * from featured_monitoring WHERE protocol_number='$id' AND status='1'";
        $hello=verilericoklucek($demo);
        foreach ((array) $hello as $row) { ?>

            <tr id="<?php echo $row["id"]; ?>" class="ozellikliizlembodyguncelle sevkbtn">
                <td><?php echo nettarih($row['track_date']); ?></td>
                <td><?php echo kullanicigetirid($row['insert_userid']);; ?></td>
<!--                <td>--><?php //echo birimgetirid($row['service_id']); ?><!--</td>-->
            </tr>

        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">

        $("#tableyogumbakim-<?php echo $_GET["getir"]; ?>").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            dom: '<"clear">lfrtip',
        });
        $(".ozellikliizlembodyguncelle").click(function () {
            let tiklananSatir = $(this);
            if (!tiklananSatir.hasClass("aktif-satir")) {
                var getir = $(this).attr('id');
                $(".ozelguncellesil").prop('hidden', false);
                $(".ybizlemkaydet").prop('hidden', true);
                $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemyeniden", {oizlemid: getir}, function (getVeri) {
                    $(".ozellikliizlembody-<?php echo $id; ?>").html(getVeri);

                });

                $('.ozellikliizlembodyguncelle').each(function () {
                    $(this).removeClass('aktif-satir');
                });
                tiklananSatir.addClass('aktif-satir');


            } else {
                $(".ozelguncellesil").prop('hidden', true);
                $(".ybizlemkaydet").prop('hidden', false);

                var getir = "<?php echo $id; ?>";
                $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=oizlemyeniden", {getir: getir}, function (getVeri) {
                    $(".ozellikliizlembody-<?php echo $id; ?>").html(getVeri);

                });
                tiklananSatir.removeClass('aktif-satir');
            }

        });

    </script>

<?php }
elseif ($islem == "oizlemyeniden") {
    if ($_GET['oizlemid']!=''){
        $oizlem = singularactive("featured_monitoring","id", $_GET["oizlemid"]);
        $yatis = singularactive("patient_registration", "protocol_number", $oizlem['protocol_number']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }else{
        $yatis = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }

    $y_birim=$yatis['service_id'];
    $y_doktor=$yatis['service_doctor'];
      ?>
    <form id="formoizlem" action="javascript:void(0);">
        <div class="row">

            <div class="col-md-6">
                <input type="hidden" class="form-control" name="protocol_number"
                       value="<?php echo $yatis['protocol_number'] ?>" id="basicpill-firstname-input">
                <?php if ($oizlem['id']!=''){ ?>
                    <input type="hidden" class="form-control" name="id"
                           value="<?php echo $oizlem['id'] ?>" id="basicpill-firstname-input">
                <?php  } ?>
            <input type="hidden" class="form-control" name="tracking_reference_number"
               value="<?php if ($oizlem['id']!=''){ echo $oizlem['tracking_reference_number']; }else{ echo rasgelesifreolustur(11); } ?>" id="basicpill-firstname-input">
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >İzlem Tarihi</label>
                    <div class="col-md-8">
                        <input type="datetime-local" class="form-control" name="track_date" min="<?php echo $yatis['hospitalized_accepted_datetime']; ?>"
                               value="<?php
                               if ($oizlem['track_date']!=''){
                                   echo $oizlem['track_date'];
                               }else{
                                   echo $simdikitarih;
                               }

                               ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >İzolasyon Amaçlı Yatış</label>
                    <div class="col-md-8">
                        <select class="form-select" name="is_insulation_purposes" >
                            <option <?php if($oizlem['is_insulation_purposes'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['is_insulation_purposes'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Covid Dışı Yatış</label>
                    <div class="col-md-8">
                        <select class="form-select" name="non_covıd_installed" >
                            <option <?php if($oizlem['non_covıd_installed'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['non_covıd_installed'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Klinik Bulgu</label>
                    <div class="col-md-8">
                        <select class="form-select" name="are_there_clinical_findings" >
                            <option <?php if($oizlem['are_there_clinical_findings'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['are_there_clinical_findings'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Bt Çekildi</label>
                    <div class="col-md-8">
                        <select class="form-select" name="bt_withdrawn" >
                            <option <?php if($oizlem['bt_withdrawn'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['bt_withdrawn'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Hasta Durumu</label>
                    <div class="col-md-8">
                        <select class="form-select" name="general_situation" >
                            <option <?php if($oizlem['general_situation'] ==1) echo"selected"; ?> value="1">İyi</option>
                            <option <?php if($oizlem['general_situation'] ==2) echo"selected"; ?> value="2">Orta</option>
                            <option <?php if($oizlem['general_situation'] ==3) echo"selected"; ?> value="3">Kötü</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Pnomoni</label>
                    <div class="col-md-8">
                        <select class="form-select" name="pneumonia" >
                            <option <?php if($oizlem['pneumonia'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['pneumonia'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Pao2 Fıo2 Oranı</label>
                    <div class="col-md-8">
                        <input type="number" class="form-control ozellikliempty" name="pao2_fio2_ratio"
                               value="<?php echo $oizlem['pao2_fio2_ratio']; ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="mt-1 col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >BT Sonuç</label>
                    <div class="col-md-8">
                        <select class="form-select" name="bt_conclusion" >
                            <option value="7">SONUÇ BEKLENİYOR</option>
                            <?php
                            $bt_sonuc=$oizlem['bt_conclusion'];
                            $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='BT SONUCU'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>
                            <option value="<?php echo $value['definition_supplement']; ?>"
                                <?php if($value['definition_supplement'] ==$bt_sonuc) echo"selected"; ?>>
                                <?php echo $value['definition_name']; ?>
                            </option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >İzlem Y. Doktor</label>
                    <div class="col-md-8">
                        <select class="form-select" name="follower_doctor_number" >
                            <?php
                                $bolumgetir = "SELECT * FROM users   ORDER BY name_surname ASC ";
                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $rowa) {
                                    ?>
                                    <option <?php if ($oizlem['follower_doctor_number']==''){
                                        if($yatis['service_doctor'] == $rowa["id"]) echo"selected";
                                    }else{
                                        if($oizlem['follower_doctor_number'] == $rowa["id"]) echo"selected";
                                    } ?>
                                            value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                                <?php }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >İzlem Y. Dr. Tel.</label>
                    <div class="col-md-8">
                        <input type="tel" class="form-control" name="follower_doctor_phone"
                               value="<?php echo $oizlem['follower_doctor_phone']; ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
            <div class="mt-1 col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Entubasyon</label>
                    <div class="col-md-8">
                        <select class="form-select" name="intubation" >
                            <option <?php if($oizlem['intubation'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['intubation'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Yoğum Bakımda</label>
                    <div class="col-md-8">
                        <select class="form-select" name="is_i_in_icu" >
                            <option <?php if($oizlem['is_i_in_icu'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($oizlem['is_i_in_icu'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-2" >İzlem Notu</label>
                    <div class="col-md-10">
                         <textarea class="form-control ozellikliempty" name="follow_up_note" cols="3" placeholder="."
                                   rows="1"><?php echo $oizlem['follow_up_note'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="formoizlempdf" style="display:none" enctype="text/plain" class="form-control"  >
        <div  class="m-1" >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h6>Özellikli İzlem Formu</h6>
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
                                    <?php  echo $oizlem['tracking_reference_number']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Yoğum Bakımda</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['is_i_in_icu']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İzolasyon Amaçlı Yatış</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['is_insulation_purposes']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Covid Dışı Yatış</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['non_covıd_installed']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Klinik Bulgu</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['are_there_clinical_findings']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Bt Çekildi</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['bt_withdrawn']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Pnomoni</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($oizlem['pneumonia']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Pao2 Fıo2 Oranı</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $oizlem['pao2_fio2_ratio']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">BT Sonuç</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    $bt_sonuc=$oizlem['bt_conclusion'];
                                    $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='BT SONUCU' AND definition_supplement='$bt_sonuc'" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>
                                        <?php echo $value['definition_name']; ?>
                                    <?php  } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İzlem Y. Doktor</label>
                            <div class="col-md-6">
                                <h6>

                                    <?php if ($oizlem['follower_doctor_number']==''){
                                        $doktor=$yatis['service_doctor'];
                                    }else{
                                        $doktor=$oizlem['follower_doctor_number'];
                                    }
                                    $bolumgetir = "SELECT * FROM users where id=$doktor ORDER BY name_surname ASC ";
                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $rowa) {
                                        echo $rowa["name_surname"];
                                    }?>

                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İzlem Y. Dr. Tel.</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $oizlem['follower_doctor_phone']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Entubasyon</label>
                            <div class="col-md-6">
                                <h6>

                                    <?php
                                    if ($oizlem['intubation']){
                                        echo 'Evet';
                                    }else{
                                        echo 'Hayır';
                                    } ?>

                                </h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İzlem Tarihi</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    echo nettarih($oizlem['track_date']);
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Hasta Durumu</label>
                            <div class="col-md-6">
                                <h6>

                                    <?php
                                    if ($oizlem['general_situation']){
                                        echo 'İyi';
                                    }
                                    elseif ($oizlem['general_situation']==2){
                                        echo 'Orta';
                                    }
                                    else{
                                        echo 'Kötü';
                                    } ?>

                                </h6>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <label class="form-label col-md-3">İzlem Notu</label>
                    <div class="col-md-9">
                        <h6>
                            <?php echo $oizlem['follow_up_note'] ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="formoizlemprint" style="display:none" enctype="text/plain" class="form-control">
        <div  >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h5>Özellikli İzlem Formu</h5>
                </div>
            </div>
            <table style="width:100%;border:1px solid;">
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Adı Soyadı:</td>
                    <td><?php echo $patients['patient_name'].' '.$patients['patient_surname']; ?></td>
                    <td style="font-weight: bold;">T.C. Kimlik No:</td>
                    <td><?php echo $patients['tc_id']; ?></td>
                </tr>
                <tr  style="margin-top:2px;">
                    <td style="font-weight: bold;">Baba Adı:</td>
                    <td><?php echo $patients['father']; ?></td>
                    <td style="font-weight: bold;">Anne Adı:</td>
                    <td><?php echo $patients['mother']; ?></td>
                </tr>
                <tr  style="margin-top:2px;">
                    <td style="font-weight: bold;">Doğum Yeri:</td>
                    <td><?php
                        $d=singularactive("province","id",$patients['birth_place']);
                        echo $d['province_name'];
                        ?></td>
                    <td style="font-weight: bold;">Doğum Tarihi:</td>
                    <td><?php echo nettarih($patients['birth_date']); ?></td>
                </tr>
            </table>
            <table style="width:100%;margin-top:10px;border:1px solid;">
                <tr  style="margin-top:2px;">
                    <td style="font-weight: bold;">Servis:</td>
                    <td><?php echo birimgetirid($y_birim); ?></td>
                    <td style="font-weight: bold;">Doktor:</td>
                    <td><?php echo kullanicigetirid($y_doktor); ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Protokol Numarası:</td>
                    <td><?php echo $yatis['protocol_number'] ?></td>
                    <td style="font-weight: bold;">Form Numarası:</td>
                    <td><?php  echo $oizlem['tracking_reference_number']; ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Yoğum Bakımda</td>
                    <td>  <?php
                        if ($oizlem['is_i_in_icu']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?>

                    </td>
                    <td  style="font-weight: bold;">Hasta Durumu</td>
                    <td>   <?php
                        if ($oizlem['general_situation']){
                            echo 'İyi';
                        }
                        elseif ($oizlem['general_situation']==2){
                            echo 'Orta';
                        }
                        else{
                            echo 'Kötü';
                        } ?>
                    </td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İzolasyon Amaçlı Yatış</td>
                    <td>  <?php
                        if ($oizlem['is_insulation_purposes']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?>

                    </td>
                    <td style="font-weight: bold;">Covid Dışı Yatış</td>
                    <td><?php
                        if ($oizlem['non_covıd_installed']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Klinik Bulgu</td>
                    <td>   <?php
                        if ($oizlem['are_there_clinical_findings']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                    <td style="font-weight: bold;">Bt Çekildi</td>
                    <td>   <?php
                        if ($oizlem['bt_withdrawn']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Pnomoni</td>
                    <td>  <?php
                        if ($oizlem['pneumonia']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                    <td style="font-weight: bold;">Pao2 Fıo2 Oranı</td>
                    <td>   <?php echo $oizlem['pao2_fio2_ratio']; ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">BT Sonuç</td>
                    <td>    <?php
                        $bt_sonuc=$oizlem['bt_conclusion'];
                        $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='BT SONUCU' AND definition_supplement='$bt_sonuc'" ;

                        $hello=verilericoklucek($bolumgetir);
                        foreach ((array) $hello as $value){ ?>
                            <?php echo $value['definition_name']; ?>
                        <?php  } ?></td>
                    <td  style="font-weight: bold;">İzlem Y. Doktor</td>
                    <td> <?php if ($oizlem['follower_doctor_number']==''){
                            $doktor=$yatis['service_doctor'];
                        }else{
                            $doktor=$oizlem['follower_doctor_number'];
                        }
                        $bolumgetir = "SELECT * FROM users where id=$doktor ORDER BY name_surname ASC ";
                        $hello = verilericoklucek($bolumgetir);
                        foreach ((array) $hello as $rowa) {
                            echo $rowa["name_surname"];
                        }?>
                    </td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İzlem Y. Dr. Tel.</td>
                    <td>    <?php echo $oizlem['follower_doctor_phone']; ?></td>
                    <td  style="font-weight: bold;">Entubasyon</td>
                    <td>  <?php
                        if ($oizlem['intubation']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?>
                    </td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İzlem Tarihi</td>
                    <td>    <?php echo nettarih($oizlem['track_date']); ?></td>
                </tr>
            </table>
            <table style="margin-top:5px;border:1px solid;width:100%;">
                <tr>
                    <td style="font-weight: bold;">İzlem Notu: </td>
                </tr>
                <tr>
                    <td ><?php echo $oizlem['follow_up_note'] ?></td>
                </tr>
            </table>
        </div>
    </form>
<?php }
elseif ($islem == "oizleminsert") {
    if ($_POST) {
        $_POST["insert_datetime"] =$simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("featured_monitoring",$_POST);
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
elseif ($islem == "oizlemupdate") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("featured_monitoring", "id", $id, $_POST);
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
elseif ($islem == "oizlembtndelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("featured_monitoring", "id", $id, $detay, $silme, $tarih);
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
