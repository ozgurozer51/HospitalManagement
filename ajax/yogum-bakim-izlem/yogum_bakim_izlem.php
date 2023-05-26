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
if ($islem == "yogumbakimizlembody") {

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
<!--    <script src=-->
<!--            "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.min.js"-->
<!--            integrity=-->
<!--            "sha512vDKWohFHe2vkVWXHp3tKvIxxXg0pJxeid5eo+UjdjME3DBFBn2F8yWOE0XmiFcFbXxrEOR1JriWEno5Ckpn15A=="-->
<!--            crossorigin="anonymous">-->
<!--    </script>-->
    <div class="modal-content">
        <div >
            <div class="modal-header py-1 px-3">
                <h5 class="modal-title">Yoğum Bakım İzlem Formu - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row" id="modal-print">
                    <div class="col-md-4 ">
                        <div class="card ">
                            <div class="card-header " style="height: 4vh;">Yoğum Bakım İzlem Listesi</div>
                            <div class="mx-1">
                                <div class="card-body mx-0 yogumbakimizlemlistesi-<?php echo $protokolno; ?>">

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
                                        <h5 style="font-size: 13px;">Yoğum Bakım İzlem Kayıt</h5>
                                    </div>
                                    <div class="col-md-3 p-1" align="right">
                                        <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-3" >
                                <div class="yogumbakimizlembody-<?php echo $protokolno; ?> mt-1">

                                </div>
                                <div class="modal-footer pb-0 pt-0">
                                    <button type="button " class="btn btn-info btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybyazdir'; } ?>">
                                        <i class="fas fa-print " aria-hidden="true"></i>
                                        Yazdır
                                    </button>
                                    <button type="button " class="btn btn-success btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybpdf'; } ?>">
                                        <i class="fa-regular fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        PDF
                                    </button>
                                    <button type="button " class="btn btn-warning btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'uss'; } ?>">
                                        <i class="fa-thin fa-house-window"></i>
                                        USS'ye Gönder
                                    </button>
                                    <?php if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn yeni-btn btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybizlemyeniden'; } ?>" id="<?php echo $_GET["getir"]; ?>"><i class="fa fa-plus" aria-hidden="true"></i> Yeni</button>
                                    <?php }if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm ybizlemkaydet   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybizleminsert'; } ?>" ><i class="fa fa-check" aria-hidden="true"></i> Kaydet</button>
                                    <?php }  if ($sevkislemupdate==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybizlemupdate'; } ?>"><i class="fas fa-edit" aria-hidden="true"></i> Güncelle</button>
                                    <?php } if ($sevkislemdelete==1){ ?>
                                        <button type="button " class="btn sil-btn btn-sm  guncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'ybizlemdelete'; } ?>"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                    <?php } ?>

                                </div>

                            </div>
                        </div>

                    </div>

                    <script>

                        $(document).ready(function () {
                            $(".ybpdf").off().on("click", function () {

                                $('#formybizlempdf').css("display", "block");
                                var element = $('#formybizlempdf').html();
                                html2pdf(element);
                                $('#formybizlempdf').css("display", "none");

                            });

                            $(".ybyazdir").off().on("click", function () {

                                $('#formybizlemprint').css("display", "block");
                                var divToPrint = $('#formybizlemprint').html();

                                var newWin=window.open('','Print-Window');

                                newWin.document.open();

                                newWin.document.write('<html><body onload="window.print()">'+divToPrint+'</body></html>');

                                newWin.document.close();

                                setTimeout(function(){newWin.close();},100);

                                $('#formybizlemprint').css("display", "none");

                            });

                            $(".ybizleminsert").off().on("click", function () {
                                //form reset-->
                                var say=0;
                                $('.ybempty').filter(function() {
                                    if (this.value==''){
                                        say++;
                                    }
                                });

                                if (say>0) {
                                    alertify.error('Boş alanları doldurun');
                                }else{
                                    var gonderilenform = $("#formybizlem").serialize();
                                    document.getElementById("formybizlem").reset();
                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizleminsert',
                                        data: gonderilenform,
                                        success: function (e) {
                                            $("#sonucyaz").html(e);

                                            var getir = "<?php echo $_GET["getir"] ?>";
                                            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=yogumbakimizlemlistesi", {getir: getir}, function (g) {
                                                $(".yogumbakimizlemlistesi-<?php echo $protokolno; ?>").html(g);

                                            });
                                        }
                                    });
                                }
                            });
                            $(document).ready(function () {
                                $(".ybizlemupdate").off('click').on("click", function () {
                                    //form reset-->

                                    var gonderilenform = $("#formybizlem").serialize();
                                    var getir ="<?php echo $_GET["getir"]; ?>";

                                    $.ajax({
                                        type: 'POST',
                                        url: 'ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemupdate',
                                        data: gonderilenform,
                                        success: function (e) {
                                            $("#sonucyaz").html(e);

                                            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=yogumbakimizlemlistesi", {getir: getir}, function (g) {
                                                $(".yogumbakimizlemlistesi-<?php echo $protokolno; ?>").html(g);
                                            });

                                        }
                                    });

                                });

                            });



                            var getir = "<?php echo $_GET["getir"] ?>";
                            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=yogumbakimizlemlistesi", {getir: getir}, function (g) {
                                $(".yogumbakimizlemlistesi-<?php echo $protokolno; ?>").html(g);

                            });
                            var protokol="<?php echo $_GET["getir"]; ?>";
                            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemyeniden", {getir: protokol}, function (getVeri) {
                                $(".yogumbakimizlembody-<?php echo $protokolno; ?>").html(getVeri);

                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function () {
            $(".guncellesil").prop('hidden', true);

            $(".ybizlemyeniden").click(function () {
                $(".guncellesil").prop('hidden', true);
                $(".ybizlemkaydet").prop('hidden', false);
                var getir = $(this).attr('id');
                $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemyeniden", {getir: getir}, function (getVeri) {
                    $(".yogumbakimizlembody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });


            $(".ybizlemdelete").click(function () {
                var id =$(".aktif-satir").attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlembtndelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            var getir = "<?php echo $_GET["getir"]; ?>";


                            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=yogumbakimizlemlistesi", {getir: getir}, function (g) {
                                $(".yogumbakimizlemlistesi-<?php echo $protokolno; ?>").html(g);

                                $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemyeniden", {getir: getir}, function (getVeri) {
                                    $(".yogumbakimizlembody-<?php echo $protokolno; ?>").html(getVeri);

                                });

                                $(".guncellesil").prop('hidden', true);
                                $(".ybizlemkaydet").prop('hidden', false);
                                //$('.secilentab').html(get);
                                $("#deneme123").trigger("click");

                            });

                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
            });



        });

    </script>
<?php }
elseif($islem=="yogumbakimizlemlistesi"){ ?>

    <table class="table border table-bordered border-dark table-hover nowrap display w-100" style="font-size: 13px;"  id="tableyogumbakim-<?php echo $_GET["getir"] ?>">
        <thead>
        <tr>
            <th>Oluşturulma Tarihi</th>
            <th>Personel</th>
<!--            <th>Birim</th>-->
        </tr>
        </thead>
        <tbody>

        <?php $id=$_GET["getir"];
        $demo = "Select * from intensive_care_follow_up WHERE protocol_number='$id' AND status='1'";
        $hello=verilericoklucek($demo);
        foreach ((array) $hello as $row) { ?>

            <tr id="<?php echo $row["id"]; ?>" class="yogumbakimizlembodyguncelle sevkbtn">
                <td><?php echo nettarih($row['watch_time']); ?></td>
                <td><?php echo kullanicigetirid($row['insert_userid']);; ?></td>
<!--                <td>--><?php //echo birimgetirid($row['service_id']); ?><!--</td>-->
            </tr>

        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">

        $("#tableyogumbakim-<?php echo $_GET["getir"] ?>").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "scrollY": '19vh',
            "scrollX": true,
            "info": false,
            "dom": '<"clear">lfrtip',
        });
        $(".yogumbakimizlembodyguncelle").click(function () {
            let tiklananSatir = $(this);
            if (!tiklananSatir.hasClass("aktif-satir")) {
                var getir = $(this).attr('id');
                $(".guncellesil").prop('hidden', false);
                $(".ybizlemkaydet").prop('hidden', true);
                $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemyeniden", {ybizlemid: getir}, function (getVeri) {
                    $(".yogumbakimizlembody-<?php echo $id; ?>").html(getVeri);

                });

                $('.yogumbakimizlembodyguncelle').each(function () {
                    $(this).removeClass('aktif-satir');
                });
                tiklananSatir.addClass('aktif-satir');


            } else {
                $(".guncellesil").prop('hidden', true);
                $(".ybizlemkaydet").prop('hidden', false);

                var getir = "<?php echo $id; ?>";
                $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=ybizlemyeniden", {getir: getir}, function (getVeri) {
                    $(".yogumbakimizlembody-<?php echo $id; ?>").html(getVeri);

                });
                tiklananSatir.removeClass('aktif-satir');
            }

        });

    </script>

<?php }
elseif ($islem == "ybizlemyeniden") {
    if ($_GET['ybizlemid']!=''){
        $ybizlem = singular("intensive_care_follow_up","id", $_GET["ybizlemid"]);
        $yatis = singularactive("patient_registration", "protocol_number", $ybizlem['protocol_number']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }else{
        $yatis = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }
    $y_birim=$yatis['service_id'];
    $y_doktor=$yatis['service_doctor'];
    $birim=singularactive("units","id",$y_birim);
    ?>

    <form id="formybizlem" action="javascript:void(0);"  class="form-control">
        <div class="row">

            <div class="col-md-6">
                <input type="hidden" class="form-control" name="protocol_number"
                       value="<?php echo $yatis['protocol_number'] ?>" id="basicpill-firstname-input">
                <?php if ($ybizlem['id']!=''){ ?>
                    <input type="hidden" class="form-control" name="id"
                           value="<?php echo $ybizlem['id'] ?>" id="basicpill-firstname-input">
                <?php  } ?>
            <input type="hidden" class="form-control" name="transaction_reference_number"
               value="<?php if ($ybizlem['id']!=''){ echo $ybizlem['transaction_reference_number']; }else{ echo rasgelesifreolustur(11); } ?>" id="basicpill-firstname-input">
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >İzlem Zamanı</label>
                    <div class="col-md-8">
                        <input type="datetime-local" class="form-control" name="watch_time" min="<?php echo $yatis['hospitalized_accepted_datetime']; ?>"
                               value="<?php
                               if ($ybizlem['watch_time']!=''){
                                   echo $ybizlem['watch_time'];
                               }else{
                                   echo $simdikitarih;
                               }

                               ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Y. B. Yatıs Tarihi</label>
                    <div class="col-md-8">
                        <input type="datetime-local" class="form-control" disabled
                               value="<?php
                               if ($ybizlem['intensive_care_date']!=''){
                                   echo $ybizlem['intensive_care_date'];
                               }else{
                                   echo $yatis['hospitalized_accepted_datetime'];
                               }

                               ?>" id="basicpill-firstname-input">
                        <input type="hidden" class="form-control" name="intensive_care_date"
                               value="<?php
                               if ($ybizlem['intensive_care_date']!=''){
                                   echo $ybizlem['intensive_care_date'];
                               }else{
                                   echo $yatis['hospitalized_accepted_datetime'];
                               }

                               ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Yogun Bakım Tipi</label>
                    <div class="col-md-8">
                        <select class="form-select" name="intensive_care_type" disabled>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_TIPI'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>
                                <option <?php if($birim['intensive_care_type'] == $value["definition_supplement"]) echo"selected"; ?>
                                        value="<?php echo $value["definition_supplement"]; ?>" ><?php echo $value['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Ventılasyon Durumu</label>
                    <div class="col-md-8">
                        <select class="form-select" name="ventilatıon_status" >
                            <option <?php if($ybizlem['ventilatıon_status'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($ybizlem['ventilatıon_status'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Diyaliz M. Bağlı</label>
                    <div class="col-md-8">
                        <select class="form-select" name="condition_of_dialysis_device" >
                            <option <?php if($ybizlem['condition_of_dialysis_device'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($ybizlem['condition_of_dialysis_device'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Hasta Genel Durumu</label>
                    <div class="col-md-8">
                        <select class="form-select" name="patient_general_status" >
                            <option <?php if($ybizlem['patient_general_status'] ==1) echo"selected"; ?> value="1">İyi</option>
                            <option <?php if($ybizlem['patient_general_status'] ==2) echo"selected"; ?> value="2">Orta</option>
                            <option <?php if($ybizlem['patient_general_status'] ==3) echo"selected"; ?> value="3">Kötü</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Onam Alınma Durumu</label>
                    <div class="col-md-8">
                        <select class="form-select" name="consent_status" >
                            <option <?php if($ybizlem['consent_status'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($ybizlem['consent_status'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-4" >Sepsis Durum</label>
                    <div class="col-md-8">
                        <select class="form-select" name="sepsis_state" >
                            <option <?php if($ybizlem['sepsis_state'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($ybizlem['sepsis_state'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="mt-1 col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Septik Şok</label>
                    <div class="col-md-8">
                        <select class="form-select" name="septic_shock" >
                            <option <?php if($ybizlem['septic_shock'] ==1) echo"selected"; ?> value="1">Evet</option>
                            <option <?php if($ybizlem['septic_shock'] ==2) echo"selected"; ?> value="2">Hayır</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-1 col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-4" >Y. B. Seviyesi</label>
                    <div class="col-md-8">
                        <select class="form-select" name="intensive_care_level" disabled>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_SEVIYESI'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>
                                <option <?php if($birim['intensive_care_level'] == $value["definition_supplement"]) echo"selected"; ?>
                                        value="<?php echo $value["definition_supplement"]; ?>" ><?php echo $value['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="col-md-12">
                <div class="row mx-2">
                    <label class="form-label col-md-2" >Günlük İzlem Notu</label>
                    <div class="col-md-10">
                        <textarea class="form-control ybempty" name="daily_monitoring_note"
                                  rows="3"><?php echo $ybizlem['daily_monitoring_note'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="formybizlempdf" style="display: none" enctype="text/plain" class="form-control"  >
        <div  class="m-1" >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h6>Yoğum Bakım İzlem Formu</h6>
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
                            <label class="form-label col-md-6">Doğum Yeri ve Tarihi</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['birth_place'].'  '.$patients['birth_date']; ?></h6>
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
                                    <?php  echo $ybizlem['transaction_reference_number']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İzlem Zamanı</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['watch_time']!=''){
                                        echo $ybizlem['watch_time'];
                                    }else{
                                        echo $simdikitarih;
                                    }

                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Y. B. Yatıs Tarihi</label>
                            <div class="col-md-6">
                                <h6><?php
                                    if ($ybizlem['intensive_care_date']!=''){
                                        echo $ybizlem['intensive_care_date'];
                                    }else{
                                        echo $yatis['hospitalized_accepted_datetime'];
                                    }

                                    ?></h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Yogun Bakım Tipi</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    $birim_tipi=$birim['intensive_care_type'];
                                    $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_TIPI' AND definition_supplement='$birim_tipi'" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){
                                        echo $value['definition_name'];
                                    } ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Ventılasyon Durumu</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['ventilatıon_status']){
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
                            <label class="form-label col-md-6">Diyaliz M. Bağlı</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['condition_of_dialysis_device']){
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
                            <label class="form-label col-md-6">Hasta Genel Durumu</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['patient_general_status']){
                                        echo 'İyi';
                                    }
                                    elseif ($ybizlem['patient_general_status']==2){
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
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Onam Alınma Durumu</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['consent_status']){
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
                            <label class="form-label col-md-6">Sepsis Durum</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['sepsis_state']){
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
                            <label class="form-label col-md-6">Septik Şok</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($ybizlem['septic_shock']){
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
                            <label class="form-label col-md-6">Y. B. Seviyesi</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    $birim_seviye=$birim['intensive_care_level'];
                                    $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_SEVIYESI' AND definition_supplement='$birim_seviye'" ;

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
                    <label class="form-label col-md-3">Günlük İzlem Notu</label>
                    <div class="col-md-9">
                        <h6>
                            <?php echo $ybizlem['daily_monitoring_note'] ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="formybizlemprint" style="display: none" enctype="text/plain" class="form-control">
        <div  >
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h5>Yoğum Bakım İzlem Formu</h5>
                </div>
            </div>
            <table style="width:100%;border:1px solid;"">
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
                    <td><?php  echo $ybizlem['transaction_reference_number']; ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İzlem Zamanı:</td>
                    <td> <?php
                        if ($ybizlem['watch_time']!=''){
                            echo $ybizlem['watch_time'];
                        }else{
                            echo $simdikitarih;
                        }

                        ?></td>
                    <td style="font-weight: bold;">Y. B. Yatıs Tarihi:</td>
                    <td><?php
                        if ($ybizlem['intensive_care_date']!=''){
                            echo nettarih($ybizlem['intensive_care_date']);
                        }else{
                            echo $yatis['hospitalized_accepted_datetime'];
                        }

                        ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Yogun Bakım Tipi:</td>
                    <td> <?php
                        $birim_tipi=$birim['intensive_care_type'];
                        $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_TIPI' AND definition_supplement='$birim_tipi'" ;

                        $hello=verilericoklucek($bolumgetir);
                        foreach ((array) $hello as $value){
                            echo $value['definition_name'];
                        } ?></td>
                    <td style="font-weight: bold;">Ventılasyon Durumu:</td>
                    <td> <?php
                        if ($ybizlem['ventilatıon_status']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Diyaliz M. Bağlı:</td>
                    <td>  <?php
                        if ($ybizlem['condition_of_dialysis_device']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                    <td style="font-weight: bold;">Hasta Genel D. :</td>
                    <td>   <?php
                        if ($ybizlem['patient_general_status']){
                            echo 'İyi';
                        }
                        elseif ($ybizlem['patient_general_status']==2){
                            echo 'Orta';
                        }
                        else{
                            echo 'Kötü';
                        } ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Onam Alınma D. :</td>
                    <td>   <?php
                        if ($ybizlem['consent_status']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                    <td style="font-weight: bold;">Sepsis Durum:</td>
                    <td>    <?php
                        if ($ybizlem['sepsis_state']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                </tr>

                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Septik Şok:</td>
                    <td>    <?php
                        if ($ybizlem['sepsis_state']){
                            echo 'Evet';
                        }else{
                            echo 'Hayır';
                        } ?></td>
                    <td  style="font-weight: bold;">Y. B. Seviyesi:</td>
                    <td>     <?php
                        $birim_seviye=$birim['intensive_care_level'];
                        $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='YOGUN_BAKIM_SEVIYESI' AND definition_supplement='$birim_seviye'" ;

                        $hello=verilericoklucek($bolumgetir);
                        foreach ((array) $hello as $value){ ?>
                            <?php echo $value['definition_name']; ?>
                        <?php  } ?>
                    </td>
                </tr>
                </table>
                <table style="margin-top:5px;border:1px solid; width:100%" >
                <tr>
                    <td style="font-weight: bold; ">Günlük İzlem Notu: </td>
                </tr>
                <tr>
                    <td ><?php echo $ybizlem['daily_monitoring_note'] ?></td>
                </tr>
            </table>
        </div>
    </form>



<?php }
elseif ($islem == "ybizleminsert") {
    if ($_POST) {
        $_POST["insert_datetime"] =$simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("intensive_care_follow_up",$_POST);

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
elseif ($islem == "ybizlemupdate") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("intensive_care_follow_up", "id", $id, $_POST);
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
elseif ($islem == "ybizlembtndelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];


        $vezneguncelle = canceldetail("intensive_care_follow_up", "id", $id, $detay, $silme, $tarih);
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
