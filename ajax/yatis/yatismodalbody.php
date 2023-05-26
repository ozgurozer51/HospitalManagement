
<script>
    $(".alerttaburcu").on("click", function () {
        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
    });
    $(".alertizin").on("click", function () {
        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
    });
    $( ".modal-header" ).addClass( 'py-1 px-3');

</script>

<?php  include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid=$_SESSION['id'];
//$simdikitarih = date('d-m-Y H:i:s');
//$dizi = explode(" ", $simdikitarih);
//$tarih = $dizi['0'];
//$saat = $dizi['1'];
$islem = $_GET['islem'];

$random_sayi = uniqid();
$hastaya_uniqid=$_GET['hastaya_uniqid'];
//$hastaya_uniqid = uniqid();
?>

<?php
if ($islem == "yatistalepbody") {

    $hastakayit = singularactive("patient_registration","protocol_number",$_GET["getir"]);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    $POLiKLiNiK = birimgetirid($hastakayit["outpatient_id"]);
    $priority = islemtanimgetirid($patients["priority"]);

    $TANiSORGULA=tek("SELECT * FROM patient_record_diagnoses WHERE protocol_number='{$_GET["getir"]}' and status='1' ORDER BY id DESC");
    if($TANiSORGULA["id"]=="") {  ?>

        <script>
            alertify.alert("Tani Uyarisi", "Yatiş talebi oluşturmak için hastaya tani girilmesi gerekir", function () {
                alertify.success("Tanı Giriniz");
            });

            setTimeout(function (){
            $(".modal-backdrop").remove();
            $('#yatis-modal').modal('hide');
            },1000);


        </script>

        <?php exit(); }  ?>

        <div class="modal-content" style="100vh !important;">
            <div class="modal-header p-1">
                YATiŞ TALEP İŞLEMi
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formytis" action="javascript:void(0);" style="font-size: 13px;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        <?php  $hastakayitid=$hastakayit['protocol_number'];
                        $yatissayisi=kayitsayisigetir('patient_registration','hospitalization_protocol',$hastakayitid);
                        if ($yatissayisi>0) { ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fa-solid fa-exclamation"></i> Daha önceden yatiş işlemi yapilmiş.
                            </div>
                        <?php } ?>

                        <div id="uyarigel"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Adi Soyadi:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control " disabled value="<?php echo $patients['patient_name'] . " " . $patients['patient_surname']; ?>">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Hasta No:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control " disabled value="<?php echo $patients['id'] ?>">
                                <input type="hidden" class="form-control " name="patient_id" value="<?php echo $patients['id'] ?>">
                                <input type="hidden" value="1" name="hospitalization_demand">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Protokol No:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control " disabled value="<?php echo $hastakayit['protocol_number'] ?>">
                                <input type="hidden" class="form-control " name="hospitalization_protocol" value="<?php echo $hastakayit['protocol_number']; ?>">
                                <input type="hidden" class="form-control " name="tc_id" value="<?php echo $patients['tc_id']; ?>">
                                <input type="hidden" class="form-control " name="protocol_number" value="<?php $row =tek("select  MAX(protocol_number) as protocol_number  from patient_registration ");echo intval($row["protocol_number"]) + 1; ?>">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Öncelik Sirasi:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control " disabled value="<?php echo $priority; ?>">
                                <?PHP if ($hastakayit["bebek"] != 0) { ?>
                            <?php } ?>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Servis</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select form-select-xs" required name="service_id" id="poliklinik">
                                    <option value="">Servis Seçiniz</option>
                                    <?php $kullanicininidsi = $_SESSION["id"];
                                    $yetkilioldugumunits = birimyetkiselect($kullanicininidsi);
                                    $bolumgetir = "SELECT * FROM units WHERE unit_type= '1' $yetkilioldugumunits ORDER BY department_name ASC ";
                                    $sql = verilericoklucek($bolumgetir);
                                    foreach ((array)$sql as $rowa) { ?>
                                        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label class="form-label">Doktor</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-select form-select-xs" name="service_doctor" id="servisdoktor"></select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-4">
                                <label  class="form-label">Tahmini yatış süresi</label>
                            </div>
                                <div class="col-md-8">
                                    <input type="number" class="form-control " name="hospitalization_day">
                                </div>
                        </div>


                            <div class="row mt-1">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <button class="btn btn-success btn-sm" id="rezerve-et-clk">Rezerve Et</button>
                                </div>
                            </div>

                    </div>
                        <div class="col-md-8" style="padding: 0;">

                                <div class="card" id="yatak-gizle" style="display:none;">
                                    <div class="card-header p-1">Yatak Seçme İşlemi</div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-4">
                                            <table class="table table-bordered table-hover display" id="servis-yatek-table" style="font-size: 10px !important;">
                                                <thead>
                                                <th id="service-name">Servis Adı</th>
                                                <th>Kodu</th>
                                                </thead>
                                                <tbody>
                                                <?php $sql = sql_select("select * from units where status=1 and unit_type=1");
                                                foreach ($sql as $item) { ?>
                                                    <tr id="servis-listesi-tr" class="servis<?php echo $item['id']; ?>" service-id="<?php echo $item['id']; ?>">
                                                        <td><?php echo $item['department_name']; ?></td>
                                                        <td><?php echo $item['id']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-8" id="get-beds">

                                            <div class="warning-definitions mt-5">
                                                <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                                    <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                                    <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                                                    <p>İşlem Yapmak İçin Seçim yapınız</p>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn kapat-btn btn-sm" class="btn-close" data-bs-dismiss="modal" >Kapat</button>
                    <input class="btn up-btn w-md justify-content-end btn-sm" <?php if ($yatissayisi>0) { echo "disabled"; } ?>  id="yatisinsert" type="submit" data-dismiss="modal" value="Kaydet"/>
                </div>
            </form>
        </div>

    <script type="text/javascript">
        $(document).ready(function () {
       var servisler_table = $('#servis-yatek-table').DataTable({
                scrollY: "60vh",
                searching:false,
                scrollX:true,
                info:false,
                paging:false,
                lengthChange:false,
                select: true
            });

            servisler_table.$('tr').click(function() {
                var service_id = $(this).attr('service-id');

                $.ajax({
                    type: "POST",
                    url: "ajax/yatis/yatismodalbody.php?islem=servisde-bulunan-yatklari-getir",
                    data: {service_id:service_id},
                    success: function (e) {
                    $('#get-beds').html(e);
                    }
                });
            });

            $("body").off("click", "#rezerve-et-clk").on("click", "#rezerve-et-clk", function (e) {

                 $("#yatak-gizle").show();
                 $("#service-name").trigger("click");

            });

                $("body").off("change", ".#poliklinik").on("change", "#poliklinik", function (e) {
                    var birimid = $(this).val();

                    $(".servis"+birimid).trigger("click");
                    $("#servis-listesi-tr").removeClass("bg-primary text-white");
                    $(".servis"+birimid).addClass("bg-primary text-white");

                $.ajax({
                    type: "POST",
                    url: "ajax/yatis/yatisislem.php?islem=servisdoktorgetir",
                    data: {"birimid": birimid},
                    success: function (e) {
                        $("#servisdoktor").html(e);
                    }
                });
            });

            //     $("body").off("click", ".odayatak").on("click", ".odayatak", function (e) {
            //     $('.odayatak').find("#yatak-icon-dom").removeClass("fa-check");
            //     $(this).find("#yatak-icon-dom").addClass("fa-check");
            //
            //
            //
            //     var oda_id=$(this).attr("oda_id");
            //     var yatak_id=$(this).attr("yatak_id");
            //     var bina_id=$(this).attr("bina_id");
            //     var kat_id=$(this).attr("kat_id");
            //     $(".bina_sec").val(bina_id);
            //     $(".kat_sec").val(kat_id);
            //     $(".oda_sec").val(oda_id);
            //     $(".yatak_sec").val(yatak_id);
            // });

                $("body").off("click", "#yatisinsert").on("click", "#yatisinsert", function (e) {  // buton idli elemana tiklandiğinda
                var gonderilenform = $("#formytis").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=yatisinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $('#sonucyaz').html(e);
                    }
                });

            });
        });

    </script>


<?php } elseif ($islem == "yatisyapbody") {
    $bebek=$_GET["bebek"];
    $hastaya_uniqid=$_GET["hastaya_uniqid"];
    $id=$_GET["getir"];
    $hastakayit = singularactive("patient_registration", "protocol_number","$id");

    if($hastakayit["baby"]!=0){
        $patients = tek("SELECT * FROM patients WHERE mother_tc_id='{$hastakayit['tc_id']}' AND birth_order='{$hastakayit['birth_order']}' ");
    }else{

        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    }

    if ($hastakayit['transfer_service']!=''){

        $poligetir = singularactive("patient_registration", "hospitalization_protocol", $hastakayit['hospitalization_protocol']);
        $POLiKLiNiK = birimgetirid($poligetir["outpatient_id"]);

        $birim = singularactive("units", "id",$poligetir["outpatient_id"]);
        $servisdoktor=$poligetir["doctor"];

        $HEKiM = kullanicigetirid($servisdoktor);

        $priority = islemtanimgetirid($patients["priority"]);
    }else{
        $poligetir = singularactive("patient_registration", "protocol_number", $hastakayit['hospitalization_protocol']);
        $POLiKLiNiK = birimgetirid($poligetir["outpatient_id"]);

        $birim = singularactive("units", "id",$poligetir["outpatient_id"]);
        $servisdoktor=$poligetir["doctor"];

        $HEKiM = kullanicigetirid($servisdoktor);

        $priority = islemtanimgetirid($patients["priority"]);
    }


    ?>


    <form id="formytisupdate" action="javascript:void(0);">
        <div class="modal-body">
            <div id="uyarigel"></div>
            <div class="row mx-2">
                <div class="col-md-2">
                    <?php $cinsiyet = $patients['gender'];
                    if (isset($patients['photo'])) { ?>
                        <img src="<?php echo $patients['photo'] ?>" alt="" class="rounded img-thumbnail mx-auto mt-2 " style="height:170px">
                    <?php } else { ?>
                        <?php if ($cinsiyet == 'E') { ?>
                            <img src="assets/img/dummy-user.jpeg" alt="photo" class="rounded img-thumbnail mx-auto mt-2 " style="height:170px">
                        <?php } elseif ($cinsiyet == 'K') { ?>
                            <img src="assets/img/bdummy-user.jpeg" alt="photo" class="rounded img-thumbnail mx-auto mt-2 " style="height:170px">
                        <?php }
                    } ?>
                </div>

                <div class="col-md-10 ">
                    <div class="row mt-4">
                        <div class="col-md-4">
<!--                            <label for="example-text-input" class="col-form-label">Adi Soyadi</label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $patients["patient_name"] . " " . $patients["patient_surname"] ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Adi Soyadi:'" readonly value="<?php echo $patients["patient_name"] . " " . $patients["patient_surname"] ?>">
                            <input class="form-control" type="hidden" name="yatisyap[id]" value="<?php echo $_GET['getir']; ?>" id="example-text-input">
                        </div>

                        <div class="col-md-4 ">
<!--                            <label for="example-text-input" class="col-form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Tc Kimlik</font></font></label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $patients['tc_id']; ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Tc Kimlik:'" readonly value="<?php echo $patients['tc_id']; ?>">
                        </div>

                        <div class="col-md-4">
<!--                            <label for="example-text-input" class="col-form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Öncelik</font></font></label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $priority; ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Öncelik:'" readonly value="<?php echo $priority; ?>">
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
<!--                            <label for="example-text-input" class="col-form-label">Protokol Numarasi</label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $hastakayit['protocol_number'] ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Protokol N.:'" readonly value="<?php echo $hastakayit['protocol_number']; ?>">
                        </div>

                        <div class="col-md-4">
<!--                            <label for="example-text-input" class="col-form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Birim</font></font></label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $POLiKLiNiK ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Birim:'" readonly value="<?php echo $POLiKLiNiK; ?>">
                        </div>

                        <div class="col-md-4">
<!--                            <label for="example-text-input" class="col-form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Doktor</font></font></label>-->
<!--                            <input class="form-control" type="text" disabled value="--><?php //echo $HEKiM ?><!--" id="example-text-input">-->
                            <input class="easyui-textbox"  style="width:100%" data-options="label:'Doktor:'" readonly value="<?php echo $HEKiM; ?>">
                        </div>

                    </div>


                    <div class="row mb-2 mt-2">
                        <div class="col-md-4">

                            <input class="easyui-textbox"  style="width:90%" id="oda_name" data-options="label:'Oda:'" readonly
                                   value="<?php  $oda=singularactive("hospital_room","id",$hastakayit['room_id']); echo $oda['room_name']; ?>">
                            <a href="#" class="easyui-linkbutton odayatakgetir" style="margin:unset" iconCls="icon-more"></a>
                            <input type="hidden" class="form-control form-control-sm " name="yatisyap[building_id]" id="bina_id"  value="<?php echo $hastakayit['building_id']; ?>">
                            <input type="hidden" class="form-control form-control-sm " name="yatisyap[floor_id]" id="kat_id" value="<?php echo $hastakayit['floor_id']; ?>">
                            <input type="hidden" class="form-control form-control-sm " name="yatisyap[room_id]" id="oda_id" value="<?php echo $hastakayit['room_id']; ?>">

<!--                            <label class="col-md-6">Oda</label>-->
<!--                            <div class="input-group">-->
<!--                                <input type="text" class="form-control form-control-sm "  id="oda_name" value="--><?php // $oda=singularactive("hospital_room","id",$hastakayit['room_id']); echo $oda['room_name']; ?><!--">-->
<!--                                <div class="input-group-append">-->
<!--                                    <button class="btn btn-outline-warning btn-sm get-company-button odayatakgetir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i>-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                        <div class="col-md-4">
                            <input class="easyui-textbox"  style="width:90%" id="yatak_name" data-options="label:'Yatak:'" readonly
                                   value="<?php  $yatak=singularactive("hospital_bed","id",$hastakayit['bed_id']); echo $yatak['bed_name']; ?>">
                            <a href="#" class="easyui-linkbutton odayatakgetir" style="margin:unset" iconCls="icon-more"></a>
                            <input type="hidden" class="form-control form-control-sm" name="yatisyap[bed_id]" id="yatak_id" value="<?php echo $yatak['id']; ?>">
                            <input type="hidden" class="form-control form-control-sm" name="yatisyap[bed_type]" id="yataktur_id" value="<?php echo $yatak['bed_type']; ?>">

<!--                            <label class="col-md-6">Yatak</label>-->
<!--                            <div class="input-group">-->
<!--                                <input type="text" class="form-control form-control-sm "  id="yatak_name" value="--><?php // $yatak=singularactive("hospital_bed","id",$hastakayit['bed_id']); echo $yatak['bed_name']; ?><!--">-->
<!--                               -->
<!--                                <div class="input-group-append">-->
<!--                                    <button class="btn btn-outline-warning btn-sm get-company-button odayatakgetir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i>-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->

                            <script>
                                $("body").off("click", ".odayatakgetir").on("click", ".odayatakgetir", function (e) {
                                    $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog({
                                        title: 'Oda Yatak',
                                        width: 1500,
                                        height: 750,
                                        buttons: [{
                                            text:'Cancel',
                                            handler:function(){
                                                $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog('close');
                                            }
                                        }],
                                        closed: false,
                                        cache: false,
                                        modal: true
                                    });

                                    $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog('refresh', 'ajax/yatis/yatismodalbody.php?islem=odayatakgetir'+'&oda_id='+'<?php echo $hastakayit['room_id']; ?>'+'&yatak_id='+'<?php echo $hastakayit['bed_id']; ?>'+'');
                                });
                            </script>
                        </div>
                        <div class="col-md-4">
                            <?php
                            $diyet=tek("select * from sick_diet where diet_name='Daha Sonra Diyet'");
                            ?>
                            <input class="easyui-textbox"  style="width:90%" id="diyet_name" data-options="label:'Diyet:'" readonly
                                   value="<?php  echo $diyet['diet_name']; ?>">
                            <a href="#" class="easyui-linkbutton diyetgetir" style="margin:unset" iconCls="icon-more"></a>
                            <input type="hidden" class="form-control form-control-sm  yatak_diyet" name="yatisyap[sick_diet]" id="diyet_id" value="<?php  echo $diyet['id']; ?>">

<!--                            <label class="col-md-6">Diyet</label>-->
<!--                            <div class="input-group">-->
<!--                                -->
<!--                                <input type="text" class="form-control form-control-sm"  id="diyet_name" value="--><?php // echo $diyet['diet_name']; ?><!--">-->
<!--                               -->
<!--                                <div class="input-group-append">-->
<!--                                    <button class="btn btn-outline-warning btn-sm get-company-button diyetgetir" type="button"><i class="fa fa-ellipsis-h" aria-hidden="true"></i>-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <script>
                                $("body").off("click", ".diyetgetir").on("click", ".diyetgetir", function (e) {
                                    $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog({
                                        title: 'Diyet',
                                        width: 350,
                                        height: 400,
                                        buttons: [{
                                            text:'Cancel',
                                            handler:function(){
                                                $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog('close');
                                            }
                                        }],
                                        closed: false,
                                        cache: false,
                                        modal: true
                                    });

                                    $('#dialogmodal-<?php echo $hastaya_uniqid; ?>').dialog('refresh', 'ajax/yatis/yatismodalbody.php?islem=diyet_getir');
                                });
                            </script>



                        </div>

                        <script type="text/javascript">
                            $(document).ready(function () {
                                var hastaid = "<?php echo $patients['id']; ?>";
                                $("#oda_id").change(function () {
                                    var odaid = $(this).val();
                                        $.get("ajax/yatis/yatismodalbody.php?islem=cinsiyetgetir", {odaid: odaid, hastaid: hastaid}, function (getir1) {
                                            $('#uyarigel').html(getir1);
                                        });

                                })

                            });
                        </script>

                    </div>
                    <?php if ($hastakayit['hospitalization_demand']==2) { ?>
                        <div align="center">
                            <label for="basicpill-firstname-input" class="form-label">Güncelleme Detay</label>
                            <br>
                            <textarea name="yatisyap[update_detail]" placeholder="Güncelleme neden yaptiğinizi yaziniz." rows="1" class="col-md-12"></textarea>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>

        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 30px 0;">
            <?php
            if($hastakayit['hospitalization_demand']==2){ ?>
            <a class="easyui-linkbutton yatisislemup" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="javascript:" style="width:150px">Yatiş Güncelle</a>
            <?php   }
            else{
            $transfergeldimi=$hastakayit['transfer_service'];
            if ($transfergeldimi==''){ ?>
            <a class="easyui-linkbutton yatisupdate" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="javascript:" style="width:150px">Yatiş Kabul</a>
            <?php }
            elseif ($transfergeldimi!=''){ ?>
            <a class="easyui-linkbutton yatistransferupdate" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="javascript:" style="width:150px">Transfer Yatiş Kabul</a>
            <?php }
            } ?>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)" onclick="$('#windows-<?php echo $hastaya_uniqid; ?>').window('close')" style="width:80px">Kapat</a>
        </div>
    </form>


    <script>
        $(document).ready(function () {
            $(".yatisipal").click(function () {
                var id ="<?php echo $hastakayit['id']; ?>";
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=yatisiptal',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
            });

            $(".yatisupdate").on("click", function () {
                var oda=$(".yatak_oda").val();
                var yatak=$(".yatak_yatak").val();
                var diyet=$(".yatak_diyet").val();
                if(oda!='' && yatak!='' && diyet!=''){
                    var getir ="<?php echo $hastakayit['service_id']; ?>";
                    var gonderilenform = $("#formytisupdate").serialize();
                    document.getElementById("formytisupdate").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=yatiskabul',
                        data: gonderilenform,
                        success: function (e) {
                            $('#sonucyaz').html(e);
                            // var baslangic=$("#baslangictarihi").val();
                            // var bitis=$("#bitistarihi").val();
                            // $.get("ajax/yatis/yatislistesi.php?islem=birimyatis", {birimid: getir,baslangic:baslangic,bitis:bitis}, function (e1) {
                            //
                            //     $('#modal-kapat').trigger('click');
                            //
                            //     $('.secilentab').html(e1);


                                //console.log(e1);
                                //alert("#modal-<?php //echo $hastakayit['protocol_number']; ?>//");

                            $('#yatisuaes').datagrid('load');

                            // });
                        }
                    });
                }else{
                    if (oda==''){
                        alertify.warning('Oda seçiniz');
                    }else if(yatak==''){
                        alertify.warning('Yatak seçiniz');
                    }else if(diyet==''){
                        alertify.warning('Diyet seçiniz');
                    }
                }


            });

            $(".yatisislemup").on("click", function () {
                var gonderilenform = $("#formytisupdate").serialize();
                document.getElementById("formytisupdate").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=yatisislemup',
                    data: gonderilenform,
                    success: function (e) {
                        $('#sonucyaz').html(e);

                    }
                });

            });

            $(".yatistransferupdate").on("click", function () {
                var getir ="<?php echo $hastakayit['service_id']; ?>";
                var gonderilenform = $("#formytisupdate").serialize();
                document.getElementById("formytisupdate").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=transferyatiskabul',
                    data: gonderilenform,
                    success: function (e) {
                        $('#sonucyaz').html(e);

                        //alert(e);
                        $.get("ajax/yatis/yatislistesi.php?islem=yatislistasi", {getir: getir}, function (e) {
                            $('.secilentab').html(e);

                        });
                    }
                });

            });
        });
    </script>

    <?php }
elseif ($islem=="diyet_getir"){ ?>
       <table class="table table-bordered table-hover w-100 display" id="diyet-table" style="font-size: 12px !important;">
           <thead>
           <th id="service-name">Diyet Adı</th>
           </thead>
           <tbody>
           <?php $sql = sql_select("select * from sick_diet  ORDER BY id desc");
           foreach ($sql as $item) { ?>
               <tr class="secilen_diyet " diyet-id="<?php echo $item['id']; ?>" diyet-name="<?php echo $item['diet_name']; ?>">
                   <td><?php echo $item['diet_name']; ?></td>
               </tr>
           <?php } ?>
           </tbody>
       </table>
    <script type="text/javascript">

        var diyet_table = $('#diyet-table').DataTable({
            scrollY: "28vh",
            searching:false,
            scrollX:true,
            info:false,
            paging:false,
            lengthChange:false,
            select: true
        });

        diyet_table.$('tr').click(function() {
            var diyet_id = $(this).attr('diyet-id');
            var diyet_name = $(this).attr('diyet-name');


            $('#diyet_name').textbox('setValue', diyet_name);
            // $("#diyet_name").val(diyet_name);
            $("#diyet_id").val(diyet_id);

        });
    </script>


<?php }
elseif($islem=="odayatakgetir"){ ?>

    <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <table class="table table-bordered table-hover display" id="servis-yatek-table" style="font-size: 10px !important;">
                        <thead>
                        <th id="service-name">Servis Adı</th>
                        <th>Kodu</th>
                        </thead>
                        <tbody>
                        <?php $sql = sql_select("select * from units where status=1 and unit_type=1");
                        foreach ($sql as $item) { ?>
                            <tr id="servis-listesi-tr" class="servis<?php echo $item['id']; ?>" service-id="<?php echo $item['id']; ?>">
                                <td><?php echo $item['department_name']; ?></td>
                                <td><?php echo $item['id']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-9" id="get-beds">

                    <div class="warning-definitions mt-5">
                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                            <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                            <p>İşlem Yapmak İçin Seçim yapınız</p>
                        </div>
                    </div>

                </div>

            </div>
    </div>

    <script type="text/javascript">

        var servisler_table = $('#servis-yatek-table').DataTable({
            scrollY: "60vh",
            searching:false,
            scrollX:true,
            info:false,
            paging:false,
            lengthChange:false,
            select: true
        });

        servisler_table.$('tr').click(function() {
            var service_id = $(this).attr('service-id');

            $.ajax({
                type: "POST",
                url: "ajax/yatis/yatismodalbody.php?islem=servisde-bulunan-yatklari-getir",
                data: {service_id:service_id},
                success: function (e) {
                    $('#get-beds').html(e);
                }
            });
        });
    </script>

<?php }

elseif($islem=="cinsiyetgetir"){
    $odaid=$_GET['odaid'];

    $hastaid=$_GET['hastaid'];

    $hastakayit=tek("select * from patient_registration where room_id=$odaid and hospitalization_demand='2'");
    $hastalar=singularactive("patients","id",$hastakayit['patient_id']);
    $hastalar2=singularactive("patients","id",$hastaid);
    $cinsiyet=$hastalar['gender'];
    $cinsiyet2=$hastalar2['gender'];
    if ($cinsiyet!=$cinsiyet2 && $cinsiyet=='E'){ ?>

        <div class="alert alert-danger" role="alert">
            seçtiğiniz odada erkek hasta var.
        </div>

    <?php }elseif ($cinsiyet!=$cinsiyet2 && $cinsiyet=='K'){ ?>
        <div class="alert alert-danger" role="alert">
            seçtiğiniz odada kadın hasta var.
        </div>
    <?php   }else{
        echo 'cinsiyet1: '.$cinsiyet.' '.'cinsiyet2: '.$cinsiyet2;
    }
}
  elseif ($islem == "epikrizbody") {
    $protokolno=$_GET["protokolno"];
    $hastakayit = singular("patient_registration", "protocol_number", $protokolno);
    $patients = singular("patients", "id", $hastakayit['patient_id']);
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>

    <div id="tt">
        <?php $epikrizinsert = yetkisorgula($kullanicid, "epikrizinsert");
        $epikrizupdate = yetkisorgula($kullanicid, "epikrizupdate");
        $epikrizdelete = yetkisorgula($kullanicid, "epikrizdelete");
        if ($taburcu['discharge_status'] == 1) { ?>
            <a href="javascript:void(0)" class="alerttaburcu icon-add"></a>
            <a href="javascript:void(0)" class="alerttaburcu icon-reload"></a>
        <?php } else if ($izin['id'] != '') { ?>
            <a href="javascript:void(0)" class="alertizin icon-add"></a>
            <a href="javascript:void(0)" class="alertizin icon-reload"></a>
        <?php } else { ?>
            <?php if ($epikrizinsert == 1) { ?>
                <a href="javascript:void(0)" id="<?php echo $protokolno; ?>" class="epikrizyeniden icon-add"></a>
            <?php } ?>
            <?php if ($epikrizinsert == 1) { ?>
                <a href="javascript:void(0)" class="epikrizkaydet icon-reload" id="epikrizinsert"></a>
            <?php } ?>
        <?php } ?>
        <?php if ($epikrizupdate == 1) { ?>
            <a href="javascript:void(0)" class="epikrizupdateyap epikrizguncellesil icon-edit" title="Düzenle"></a>
        <?php } ?>
        <?php if ($epikrizdelete == 1) { ?>
            <a href="javascript:void(0)" class="epikrizdeletyap epikrizguncellesil icon-remove" title="Kaldır"></a>
        <?php } ?>
    </div>


    <div class="easyui-panel" style="width:100%;height:100%;">

        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west' , split:true , iconCls:'fa-solid fa-list', closable:false , fit:true ,  tools:'#tt'" title="Hasta Epikriz Listesi">

                <div class="epikrizlistesi-<?php echo $protokolno; ?>" style="width: 40%;">
                </div>

            </div>

            <div class="easyui-panel"  data-options="region:'east' ,  split:true , iconCls:'fa-solid fa-save' ,  tools:'#tt' " title="Hasta Epikriz Kayıt-- Durum: <?php if ($taburcu['discharge_status'] == 1) { ?> Taburcu  <?php } elseif ($izin['id'] != '') { ?> İzinli <?php } else { ?>Yatışta <?php } ?> " style="width:60%; height:100%;">

                  <div class="epikrizbody-<?php echo $protokolno; ?>">

                  </div>

            </div>

        </div>
    </div>








    <script>
        var getir = "<?php echo $protokolno; ?>";
        $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {getir: getir}, function (getVeri) {
            $(".epikrizbody-<?php echo $protokolno; ?>").html(getVeri);

        });

        $.get("ajax/yatis/yatislistesi.php?islem=epikrizlistesi", {getir: getir}, function (e) {
            $(".epikrizlistesi-<?php echo $protokolno; ?>").html(e);
        });



        $(document).ready(function () {

            var getin = "<?php echo $protokolno ?>";

            $("#epikrizinsert").off().on("click", function () {
                //form reset-->
                var say = 0;
                $('.epikrizempty').filter(function () {
                    if (this.value == '') {
                        say++;
                    }
                });

                if (say > 0) {
                    alertify.error('Boş alanları doldurun');
                } else {
                    var gonderilenform = $("#formepikriz").serialize();

                    document.getElementById("formepikriz").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=epikrizinsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            //var getir = "<?php //echo $yatis["yatis_protokol"] ?>//";
                            $.get("ajax/yatis/yatislistesi.php?islem=epikrizlistesi", {getir: getin}, function (g) {
                                $(".epikrizlistesi-<?php echo $protokolno; ?>").html(g);

                            });

                        }
                    });
                }

            });
            $(".epikrizupdateyap").off('click').on("click", function () {
                //form reset-->

                var gonderilenform = $("#formepikriz").serialize();
                var getir = "<?php echo $protokolno; ?>";

                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=epikrizupdate',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        $.get("ajax/yatis/yatislistesi.php?islem=epikrizlistesi", {getir: getir}, function (g) {
                            $(".epikrizlistesi-<?php echo $protokolno; ?>").html(g);

                        });
                        $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {getir: getir}, function (getVeri) {
                            $(".epikrizbody-<?php echo $protokolno; ?>").html(getVeri);
                        });

                    }
                });

            });

            var taburcumu = "<?php echo $taburcu['discharge_status']; ?>";
            if (taburcumu == 1) {
                $(".islemdisabled").prop('disabled', true);
            }

        });


        $(document).ready(function () {
            $(".epikrizbodyguncelle").click(function () {
                $(".epikrizguncellesil").prop("disabled", false);
                $(".epikrizkaydet").prop("disabled", true);
            });

            $(".epikrizbodyguncelle").click(function () {
                var getir = $(this).attr('id');
                $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {getir: getir}, function (getVeri) {
                    $(".epikrizbody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });

            $(".epikrizyeniden").click(function () {
                $(".epikrizkaydet").prop("disabled", false);
                $(".epikrizguncellesil").prop("disabled", true);
                $('.epikriz-dom').removeClass("text-white");
                $('.epikriz-dom').css({"background-color": "rgb(255, 255, 255)"});
                var getir = $(this).attr('id');
                $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {getir: getir}, function (getVeri) {
                    $(".epikrizbody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });


            $(".epikrizdeletyap").click(function () {
                var id = $('.epikriz-dom').attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function () {

                    var delete_detail = $('#personel_delete_detail').val();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=btnepikrizdelete',
                        data: {id: id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            var getir = "<?php echo $hastakayit["protocol_number"] ?>";
                            var protokolid = "<?php echo $hastakayit["protocol_number"] ?>";
                            $.get("ajax/yatis/yatislistesi.php?islem=epikrizlistesi", {getir: getir}, function (g) {
                                $(".epikrizlistesi-<?php echo $protokolno; ?>").html(g);

                                $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {getir: getir}, function (getVeri) {
                                    $(".epikrizbody-<?php echo $protokolno; ?>").html(getVeri);
                                    $(".epikrizguncellesil").prop("disabled", true);
                                    $(".epikrizkaydet").prop("disabled", false);
                                });
                                $('.alertify').remove();

                            });

                        }
                    });

                }, function () {
                    alertify.warning('Silme işleminden Vazgeçtiniz')
                }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme işlemini Onayla"});

                $("#deneme123").trigger("click");

            });

            $(".epikrizbtn").on("click", function () {
                $('.epikriz-dom').css({"background-color": "rgb(255, 255, 255)"});
                $('.epikriz-dom').removeClass("text-white");
                $(this).css({"background-color": "rgb(57, 180, 150"});
                $(this).addClass("text-white");
                $(this).addClass("epikriz-dom");

            });

        });

    </script>
<?php }

else if($islem=="evrakmodalbody"){
    $protokolno=$_GET["getir"];

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);
    $patients = singular("patients", "id", $hastakayit['patient_id']);
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
     ?>

    <form id="uploadForm" action="javascript:void(0);" enctype="multipart/form-data">

        <div class="col-12 row">

            <div class="col-12">
                <div class="col-lg-12">
                    <div class="mb-2">
                        <div class="row mx-2">
                            <label for="basicpill-firstname-input" class="form-label col-md-2">Evrak Adi</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Evrak adi giriniz." name="document_name" id="basicpill-firstname-input">
                                <input type="hidden" value="<?php echo $patients['id']; ?>" name="patients_id">
                                <input type="hidden" value="<?php echo $protokolno; ?>" name="protocol_id">
                            </div>
                        </div>


                    </div>
                </div><!-- end col -->
            </div>

            <div class="col-12 mt-2">
                <div class="fallback">
                    <input class="mx-3" name="hastaevrak[]" type="file" multiple="multiple">
                </div>
            </div>

            <div class="text-center mt-4">
                <?php
                $evrakinsert=yetkisorgula($kullanicid, "evrakinsert");
                if ($taburcu['discharge_status']==1){ ?>
                    <!--   alert uyarı verme btn-->
                    <button type="button" class="btn up-btn alerttaburcu"><i class="fa-sharp fa fa-file-invoice fa-lg"></i> Yükle</button>
                <?php }else if($izin['id']!='') { ?>
                    <button type="button" class="btn up-btn alertizin"><i class="fa-sharp fa fa-file-invoice fa-lg"></i> Yükle</button>
                <?php }else if($evrakinsert==1){ ?>
                    <button type="submit" class="btn up-btn"><i class="fa-sharp fa fa-file-invoice fa-lg"></i> Yükle</button>
               <?php } ?>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {

            $('#uploadForm').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    url: 'ajax/yatis/yatisislem.php?islem=evrakinsert',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function(){

                        $('#loading').html('<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"><span class="sr-only">Lütfen Bekleyiniz....</span></div></div>');
                    },
                    success: function(data){
                        $("#sonucyaz").html(data);

                        $('#uploadForm')[0].reset();
                        var getir = "<?php  echo $protokolno ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=evraklistesi", {getir: getir}, function (g) {
                            $(".evraklistesi-<?php echo $protokolno; ?>").html(g);
                        });
                        $('#loading').html('');
                    }
                });
            });

        });
    </script>
<?php }
elseif ($islem == "evraksilbody") {
    $protokolno=$_GET['protokolno'];
    $evrakid=$_GET['islem_id'];

//    $yatis = verilericoklucek("select * from YATiS where YATiS_PROTOKOL='$protokol'");
    ?>
    <form action="javascript:void(0);" id="formevrakdel">
        <div class="mb-3">
            <div class="alert alert-danger" role="alert">
                Evrak silmek için emin misiniz ?...
            </div>

            <input class="form-control" name="id" type="text" value="<?php echo $evrakid; ?>">


        </div>
        <div class="mb-3">
            <label for="basicpill-firstname-input" class="form-label">Neden silmek istediğinizi açiklar
                misiniz ?</label>
            <textarea class="form-control" name="delete_detail"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
            <button class="btn btn-success w-md justify-content-end btnevraksilme" style="margin-bottom:4px"
                    type="submit" >Silme işlemini Onayla
            </button>
        </div>
    </form>


    <script type="text/javascript">
        $(document).ready(function () {


            $(".btnevraksilme").on("click", function () {
                var gonderilenform = $("#formevrakdel").serialize();
                document.getElementById("formevrakdel").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=btnevrakdelete',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var getir = "<?php  echo $_GET['protokolno'] ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=evraklistesi", {getir: getir}, function (g) {
                            $(".evraklistesi-<?php echo $protokolno; ?>").html(g);
                        });

                    }
                });

            });

        });

    </script>

<?php }
elseif ($islem == "dissevkbody") {

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);

    if ($hastakayit['mother_tc_identity_number']==''){
        $patients = singular("patients", "id", $hastakayit['patient_id']);
    }else{
//        $ANNETC=$yatis['mother_tc_identity_number'];
//        $DOGUMSiRA=$yatis['birth_order'];
        $hastalarid=$hastakayit['patient_id'];
        $patients = tek("SELECT * FROM patients WHERE id='$hastalarid'");
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>


        <!-- Modal content-->

        <div class="modal-content">
            <div >
                <div class="modal-header">
                    <h5 class="modal-title">SEVK iŞLEMLERi - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-1">
                    <div class="row">
                        <div class="col-md-5 px-2 ">
                            <div class="card ">
                                <div class="card-header " style="height: 4vh;">Oluşturulan Sevkler</div>
                                <div class="">
                                    <div class="card-body  sevklistesi-<?php echo $protokolno; ?>">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <?php
                            $sevkisleminsert=yetkisorgula($kullanicid, "sevkisleminsert");
                            $sevkislemupdate=yetkisorgula($kullanicid, "sevkislemupdate");
                            $sevkislemdelete=yetkisorgula($kullanicid, "sevkislemdelete");

                            ?>
                            <div class="card">
                                <div class="card-header" style="height: 4vh;">
                                    <div class="col-12 row">
                                        <div class="col-md-9 ">
                                            <h5 style="font-size: 13px;">Hasta Sevk Kayıt</h5>
                                        </div>
                                        <div class="col-md-3 " align="right">
                                            <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="sevkbody-<?php echo $protokolno; ?>">

                                    </div>
                                    <div class="modal-footer pb-0 pt-0">
                                        <?php if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn yeni-btn btn-sm  sevkguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'sevkyeniden'; } ?>" id="<?php echo $_GET["getir"]; ?>"><i class="fa fa-plus" aria-hidden="true"></i> Yeni</button>
                                        <?php }if ($sevkisleminsert==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm sevkkaydet   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'sevkinsert'; } ?>" ><i class="fa fa-check" aria-hidden="true"></i> Kaydet</button>
                                        <?php }  if ($sevkislemupdate==1){ ?>
                                        <button type="button " class="btn up-btn btn-sm  sevkguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'sevkupdateyap'; } ?>"><i class="fas fa-edit" aria-hidden="true"></i> Güncelle</button>
                                        <?php } if ($sevkislemdelete==1){ ?>
                                        <button type="button " class="btn sil-btn btn-sm  sevkguncellesil  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'sevkdeletyap'; } ?>"><i class="fa fa-trash" aria-hidden="true"></i> Sil</button>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>

                        </div>


                        <script>

                            $(document).ready(function () {
                                $(".sevkinsert").off().on("click", function () {
                                    //form reset-->

                                    var say=0;
                                    $('.sevkempty').filter(function() {
                                        if (this.value==''){
                                            say++;
                                        }
                                    });

                                    if (say>0) {
                                        alertify.error('Boş alanları doldurun');
                                    }else{
                                        var gonderilenform = $("#formsevk").serialize();
                                        document.getElementById("formsevk").reset();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/yatis/yatisislem.php?islem=sevkinsert',
                                            data: gonderilenform,
                                            success: function (e) {
                                                $("#sonucyaz").html(e);

                                                var getir = "<?php echo $_GET["getir"] ?>";
                                                $.get("ajax/yatis/yatislistesi.php?islem=sevklistesi", {getir: getir}, function (g) {
                                                    $(".sevklistesi-<?php echo $protokolno; ?>").html(g);

                                                });
                                            }
                                        });
                                    }
                                });
                                $(document).ready(function () {
                                    $(".sevkupdateyap").off('click').on("click", function () {
                                        //form reset-->

                                        var gonderilenform = $("#formsevk").serialize();
                                        var getir ="<?php echo $_GET["getir"]; ?>";

                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/yatis/yatisislem.php?islem=sevkupdate',
                                            data: gonderilenform,
                                            success: function (e) {
                                                $("#sonucyaz").html(e);

                                                $.get("ajax/yatis/yatislistesi.php?islem=sevklistesi", {getir: getir}, function (g) {
                                                    $(".sevklistesi-<?php echo $protokolno; ?>").html(g);
                                                    $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {getir: getir}, function (getVeri) {
                                                        $(".sevkbody-<?php echo $protokolno; ?>").html(getVeri);

                                                    });
                                                });

                                            }
                                        });

                                    });

                                });



                                var getir = "<?php echo $_GET["getir"] ?>";
                                $.get("ajax/yatis/yatislistesi.php?islem=sevklistesi", {getir: getir}, function (g) {
                                    $(".sevklistesi-<?php echo $protokolno; ?>").html(g);

                                });
                                var protokol="<?php echo $_GET["getir"]; ?>";
                                $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {getir: protokol}, function (getVeri) {
                                    $(".sevkbody-<?php echo $protokolno; ?>").html(getVeri);

                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>


    <script>

        $(document).ready(function () {
            $(".sevkguncellesil").prop('hidden', true);

            $(".sevkyeniden").click(function () {
                $(".sevkguncellesil").prop('hidden', true);
                $(".sevkkaydet").prop('hidden', false);
                var getir = $(this).attr('id');
                $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {getir: getir}, function (getVeri) {
                    $(".sevkbody-<?php echo $protokolno; ?>").html(getVeri);

                });
            });


            $(".sevkdeletyap").click(function () {
                var id =$(".aktif-satir").attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=btnsevkdelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            var getir = "<?php echo $_GET["getir"]; ?>";


                            $.get("ajax/yatis/yatislistesi.php?islem=sevklistesi", {getir: getir}, function (g) {
                                $(".sevklistesi-<?php echo $protokolno; ?>").html(g);

                                $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {getir: getir}, function (getVeri) {
                                    $(".sevkbody-<?php echo $protokolno; ?>").html(getVeri);

                                });

                                $(".sevkguncellesil").prop('hidden', true);
                                $(".sevkkaydet").prop('hidden', false);
                                // $('.secilentab').html(get);
                                $("#deneme123").trigger("click");

                            });

                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
            });



        });

    </script>
<?php }
elseif ($islem == "taburcubody") {

    $hastakayit = singularactive("patient_registration", "protocol_number",$_GET["getir"]);
//    if ($yatis['anne_tc_kimlik_numarasi']==''){
//        $patients = singularactive("patients", "tc_id", $yatis['tc_kimlik_no']);
//    }else{
//        $ANNETC=$yatis['anne_tc_kimlik_numarasi'];
//        $DOGUMSiRA=$yatis['birth_order'];
//        $patients = tek("SELECT * FROM patients WHERE mother_tc_identity_number='$ANNETC' AND birth_order='$DOGUMSiRA'");
//    }
    $hastaid=$hastakayit['patient_id'];
    $patients = tek("SELECT * FROM patients WHERE id=$hastaid");
    $yatisprotokol=$hastakayit["protocol_number"];
    $poliklinikprotokol=$hastakayit["hospitalization_protocol"];
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = tek("select * from patient_discharge where admission_protocol='$yatisprotokol' AND status='1'");
    $izin = singularactive("patient_permission","protocol_number",$protokolno);

    ?>

    <form id="formtaburcu" action="javascript:void(0);">
        <div class=" row">
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="admission_protocol"
                       value="<?php echo $yatisprotokol ?>" id="basicpill-firstname-input">
                <input type="hidden" class="form-control" name="patients_id"
                       value="<?php echo $hastaid ?>" id="basicpill-firstname-input">
                <?php if ($taburcu['id']){ ?>
                <input type="hidden" class="form-control" name="id"
                       value="<?php echo $taburcu['id'] ?>" id="basicpill-firstname-input">
                <?php } ?>
            </div>

        </div>
        <?php
        $borcuVarmi=hastaborcsorgula($hastaid);
        if ($borcuVarmi>0){
        ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <div>
                <i class="fa-solid fa-triangle-exclamation"></i>
               <b class="">Hastanın <?php echo $borcuVarmi; ?> ₺ borçu bulunmaktadır.Taburcu olması için vezneye uğraması gerekiyor.</b>
            </div>
        </div>
        <?php } ?>
        <div id="p" class="easyui-panel" title="Taburcu Kayıt Formu" style="width:100%;padding:10px;" >
            <div class="mt-2">
                <div class="mb-2 row">
                    <div class="col-md-4">
                        <div class="row ">
                            <label for="basicpill-firstname-input" title="Taburculuk Tarihi" class="form-label col-md-3">T. Tarihi</label>
                            <div class="col-md-9">
                                <input type="datetime-local"
                                       value="<?php if($taburcu['discharge_date']){ echo $taburcu['discharge_date']; }else {echo $simdikitarih; } ?>"
                                       class="form-control" name="discharge_date" min="<?php echo $hastakayit['hospitalized_accepted_datetime']; ?>"
                                       id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row ">
                            <label class="form-label col-md-3" title="Taburculuk Türü">T. Türü</label>
                            <div class="col-md-9">
                                <select class="form-select secilenislem" name="discharge_type"
                                    <?php //if ($taburcu['discharge_status']==1 || $borcuVarmi>0 ){ echo 'disabled'; } ?> >
                                    <option value="">Taburculuk türü seçiniz..</option>
                                    <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='TABURCULUK_TURU'" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>

                                        <option <?php if($taburcu['discharge_type'] == $value["id"]) echo"selected"; ?>
                                                value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Yatiş Statusu">Yatiş S.</label>
                            <div class="col-md-9">
                                <input type="hidden"  class="form-control" value="<?php
                                echo  $hastakayit['protocol_number']; ?> "
                                       name="yatisid"    id="basicpill-firstname-input">
                                <?php
                                //$taburcu = singular("patient_discharge", "admission_protocol",$yatis["yatis_protokol"]);
                                //                                                    $taburcu = tek('select * from patient_discharge where admission_protocol=$yatis["yatis_protokol"] AND status="1"');
                                if ($taburcu['discharge_type']=='') { ?>
                                    <input type="text" disabled class="form-control" value="<?php
                                    $yatisstatus=islemtanimekgetir('yatis_status',$hastakayit['hospitalization_demand']);

                                    if ($hastakayit['hospitalization_demand']){
                                        echo  "Yatiyor";
                                    }
                                    else{
                                        echo $yatisstatus;
                                    }
                                    ?> "
                                           id="basicpill-firstname-input">

                                <?php  }else{ ?>

                                    <input type="text" disabled class="form-control" value="<?php
                                    if($taburcu['discharge_type']==28882){
                                        echo "Taburcu edildi";
                                    }else if($taburcu['discharge_type']==28961) {
                                        echo 'Ex hasta';
                                    }else{
                                        echo islemtanimgetirid($taburcu['discharge_type']);
                                    }


                                    ?> "
                                           id="basicpill-firstname-input">

                                <?php  }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-2 row">
                    <div class="col-md-4">
                        <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Servis">Servis </label>
                            <div class="col-md-9">
                                <select class="form-select"
                                        name="discharge_service"
                                        id="poliklinik13">

                                    <?php

                                    $kullanicininidsi = $_SESSION["id"];
                                    $yetkilioldugumunits = birimyetkiselect($kullanicininidsi);
                                    $bolumgetir = "SELECT * FROM units WHERE unit_type='1' $yetkilioldugumunits ORDER BY department_name ASC ";
                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $rowa) {
                                        ?>
                                        <option <?php if($hastakayit['service_id'] == $rowa["id"]) echo"selected"; ?>
                                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-3" title="Doktor">Doktor </label>
                            <div class="col-md-9">
                                <select class="form-select" name="discharge_doctor" id="servisdoktor13">
                                    <?php
                                    $servis=$hastakayit['service_id'];
                                    if ($servis!=''){
                                        $bolumgetir = "SELECT * FROM users WHERE department=$servis  ORDER BY name_surname ASC ";
                                        $hello = verilericoklucek($bolumgetir);
                                        foreach ((array) $hello as $rowa) {
                                            ?>
                                            <option <?php if($hastakayit['service_id'] == $rowa["id"]) echo"selected"; ?>
                                                    value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                                        <?php }
                                    }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ">
                        <div class="row">
                            <label class="form-label col-md-3" title="Oda Bilgisi">Oda</label>
                            <div class="col-md-9">
                                <select class="form-select" name="room_id" id="odaid">
                                    <?php
                                    $servis=$hastakayit['service_id'];
                                    $bolumgetir = "select hospital_room.id as  odaid,* from hospital_room inner join patient_registration on hospital_room.id=patient_registration.room_id";
                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $rowa) {
                                        ?>
                                        <option <?php if($hastakayit['room_id'] == $rowa["odaid"]) echo"selected"; ?>
                                                value="<?php echo $rowa["odaid"]; ?>"><?php echo $rowa["room_name"].'('.islemtanimgetirid($rowa["room_type"]).')'; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row ">
                            <label class="form-label col-md-3" title="Yatak Bilgisi">Yatak</label>
                            <div class="col-md-9">
                                <select class="form-select" name="bed_id" id="yatakid">
                                    <?php
                                    $odaid=$hastakayit['room_id'];
                                    $bolumgetir = "select * from hospital_bed WHERE room_id=$odaid AND status='1'  ORDER BY bed_name";

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>
                                        <option value="<?php echo $value['id'] ?>" <?php if($hastakayit['bed_id'] == $value["id"]) echo"selected"; ?>
                                            <?php //if ($value['DOLULUK']==1){ echo "disabled"; }?> > <?php echo $value['bed_name'] ?>
                                            <?php if ($value['full_status']=='1'){ echo " (DOLU)"; }?></option>';

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php  if ($hastakayit['hospitalization_demand']=='4' && $taburcu['transfer_date']==''){ ?>
                        <div class="row  mt-2">
                            <label class="form-label col-md-1" title="Taburcu iptal işlemi">T. İ. Detayı</label>
                            <div class="col-md-11 ">
                            <textarea name="delete_detail"
                                      placeholder="Taburcu iptal nedeni giriniz.." rows="2" class="col-md-11"> </textarea>
                            </div>
                        </div>
                    <?php } ?>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#poliklinik13").change(function () {
                                var birimid = $(this).val();
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/yatis/yatisislem.php?islem=servisdoktorgetir",
                                    data: {"birimid": birimid},
                                    success: function (e) {
                                        $("#servisdoktor13").html(e);
                                    }
                                });
                            });
                            $("#odaid").change(function () {
                                var odaid = $(this).val();
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/yatis/yatisislem.php?islem=odayatak",
                                    data: {"odaid": odaid},
                                    success: function (e) {
                                        $("#yatakid").html(e);
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="transferhidden " style="width:99%;overflow: unset; <?php if($taburcu['transfer_date']!=''){  }else{ ?>display:none; <?php } ?>">
            <div id="p1" class="easyui-panel" title="Transfer Bilgisi" style="width:100%;padding:10px;" data-options="footer:'#taburcufooter',tools:'#taburcudurum'">
                <div id="taburcudurum" >
                    <?php
                    if ($hastakayit['transfer_status']==''){

                    }else{
                        if ($hastakayit['transfer_status']==2){ ?>
                            <span style="font-size:12px">Transfer Bilgisi<b class="text-success">: Transfer işlemi Kabul Edildi.</b></span>
                        <?php  }else if ($hastakayit['transfer_status']==1){ ?>
                            <span style="font-size:12px">Transfer Bilgisi<b class="text-warning">: Transfer işlemi Beklemede.</b></span>
                        <?php }
                        else if ($hastakayit['transfer_status']==3){ ?>
                            <span style="font-size:12px">Transfer Bilgisi<b class="text-danger">: Transfer işlemi red edildi.</b></span>
                        <?php }
                    }
                    ?>
                </div>

                <div class="row mt-1">
                    <div class="col-md-4">
                        <div class="row ">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Transfer Edilen Servis">T. E. Servis</label>
                            <div class="col-md-9">
                                <select class="form-select transferislem" name="transfer_service" id="poliklinik12">
                                    <option value="">Transfer servisini seçiniz..</option>
                                    <?php

                                    $kullanicininidsi = $_SESSION["id"];
                                    $yetkilioldugumunits = birimyetkiselect($kullanicininidsi);
                                    $bolumgetir = "SELECT * FROM units WHERE unit_type='1' $yetkilioldugumunits ORDER BY department_name ASC ";
                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $rowa) {
                                        ?>
                                        <option <?php if($taburcu['transfer_service'] == $rowa["id"]) echo"selected"; ?>
                                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-3" title="Transfer Edilen Doktor">T. E. Doktor</label>
                            <div class="col-md-9">
                                <select class="form-select transferislem" name="transfer_doctor" id="servisdoktor12">
                                    <?php
                                    $servis=$taburcu['transfer_service'];
                                    if ($servis!=''){
                                        $bolumgetir = "SELECT * FROM users WHERE department=$servis  ORDER BY name_surname ASC ";
                                        $hello = verilericoklucek($bolumgetir);
                                        foreach ((array) $hello as $rowa) {
                                            ?>
                                            <option <?php if($taburcu['transfer_doctor'] == $rowa["id"]) echo"selected"; ?>
                                                    value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#poliklinik12").change(function () {
                                var birimid = $(this).val();
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/yatis/yatisislem.php?islem=servisdoktorgetir",
                                    data: {"birimid": birimid},
                                    success: function (e) {
                                        $("#servisdoktor12").html(e);
                                    }
                                });
                            });
                        });
                    </script>
                    <div class="col-md-4">
                        <div class="row">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Transfer Tarihi">T. Tarihi</label>
                            <div class="col-md-9">
                                <input type="datetime-local" class="form-control" name="transfer_date"
                                       min="<?php echo $hastakayit['hospitalized_accepted_datetime']; ?>"
                                       value="<?php if ($taburcu['transfer_date']){ echo $taburcu['transfer_date']; }else{ echo $simdikitarih; } ?>"
                                       id="basicpill-firstname-input">
                            </div>
                        </div>


                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col-md-4 ">
                        <div class="row">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Gelen  Transfer Servis">G. T. Servis </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control gelenservisadi" value="<?php if ($taburcu['transfer_date']!=''){
                                    $servis=$hastakayit['service_id'];
                                    echo birimgetirid($servis); } ?>" disabled id="basicpill-firstname-input">
                                <input type="hidden" class="form-control gelenservis" name="inbound_transfer_service"  id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ">
                        <div class="row " >
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Gelen  Transfer Doktor">G. T. Doktor</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control gelendoktoradi"
                                       value="<?php if ($taburcu['transfer_date']!='') {
                                           $servisdoktor = $hastakayit['service_doctor'];
                                           if ($servisdoktor != '') {
                                               echo kullanicigetirid($servisdoktor);
                                           }
                                       }?>" disabled id="basicpill-firstname-input">
                                <input type="hidden" class="form-control gelendoktor" name="inbound_transfer_doctor"   id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <label for="basicpill-firstname-input" class="form-label col-md-3" title="Tranfer Kabul Tarihi">T. K. Tarihi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" disabled
                                       value="<?php
                                       if ($hastakayit['transfer_status']==1){
                                           echo $hastakayit['transfer_datetime'];
                                       } ?>" id="basicpill-firstname-input">
                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>
        <div id="taburcubtn" class="easyui-panel" align="right" style="width:100%;height:45px;padding:5px;position: absolute;bottom:5px;font-size:10px;">
            <?php
            $taburcuisleminsert=yetkisorgula($kullanicid, "taburcuisleminsert");
            $taburcuislemupdate=yetkisorgula($kullanicid, "taburcuislemupdate");
            $taburcuislemdelete=yetkisorgula($kullanicid, "taburcuislemdelete");
            ?>
            <?php if ($hastakayit['hospitalization_demand']=='3' && $taburcuislemupdate==1){ ?>
                <a href="#" class="easyui-linkbutton <?php  if ($izin['id']!=''){echo 'alertizin';}else { echo 'taburculukgerial'; } ?>"
                   iconCls="icon-reload">Taburcu hazirlik geri al</a>
            <?php }
            if ($taburcu['transfer_date']!='' && $taburcuislemupdate==1){ ?>
                <a href="#" class="easyui-linkbutton <?php if ($izin['id']!=''){echo 'alertizin';}else { echo 'transfergerial'; } ?>"
                   iconCls="icon-reload">Transfer Gerial</a>
            <?php }
            if ($hastakayit['hospitalization_demand']=='4' && $taburcu['transfer_date']==''){ ?>
                <?php if($taburcuislemupdate==1){ ?>
                    <a href="#" class="easyui-linkbutton
                    <?php  if ($izin['id']!=''){echo 'alertizin';}else { echo 'taburcugerial'; } ?>" iconCls="icon-reload">Taburculuk geri al</a>
                <?php }
            }?>
        </div>



        <script type="text/javascript">
            $(document).ready(function () {
                var transfertarihi="<? echo $taburcu['transfer_date']; ?>"

                $(".secilenislem").change(function () {
                    var btnislem = $(this).val();
                    // alert(btnislem);
                    var yatisprotokol="<?php echo $hastakayit['protocol_number'] ?>";
                    $('#taburcubtn').panel('refresh', 'ajax/yatis/yatismodalbody.php?islem=btnsecilentaburcuislemi&btnislem='+btnislem+'&yatisprotokol='+yatisprotokol+'');
                    // $.ajax({
                    //     type: "POST",
                    //     url: "ajax/yatis/yatismodalbody.php?islem=btnsecilentaburcuislemi",
                    //     data: {"btnislem": btnislem,"yatisprotokol": yatisprotokol},
                    //     success: function (e) {
                    //         $(".secilenbtn").html(e);

                            if(btnislem=='28882'){

                                $(".transferhidden").show();

                                $(".gelenservis").val("<?php echo $hastakayit['service_id'] ?>");
                                $(".gelendoktor").val("<?php echo $hastakayit['service_doctor'] ?>");
                                $(".gelenservisadi").val("<?php echo birimgetirid($hastakayit['service_id']); ?>");
                                $(".gelendoktoradi").val("<?php
                                    $servisdoktor=$hastakayit['service_doctor'];
                                    if ($servisdoktor!='') {
                                        echo kullanicigetirid($servisdoktor);
                                    } ?>");
                            }
                            else{
                                $(".transferhidden").css("display","none")
                                $(".gelenservis").val('');
                                $(".gelendoktor").val('');
                                $(".gelenservisadi").val('');
                                $(".gelendoktoradi").val('');
                            }
                    //     }
                    // });
                });

                if (transfertarihi==''){
                    $(".gelenservis").val("<?php echo $hastakayit['service_id'] ?>");
                    $(".gelendoktor").val("<?php echo $hastakayit['service_doctor'] ?>");
                    $(".gelenservisadi").val("<?php echo birimgetirid($hastakayit['transfer_servis']); ?>");
                    $(".gelendoktoradi").val("<?php
                        $servisdoktor=$hastakayit['transfer_doktor'];
                        if ($servisdoktor!='') {
                            echo kullanicigetirid($servisdoktor);
                        }
                        ?>");
                }else{
                    //alert(transfertarihi);
                }

            });
        </script>
    </form>

    <script>

        $(document).ready(function () {


            $(".taburculukgerial").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcuhazirlikgerial',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=taburcuolacakhastalistesi", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis  },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });

            $(".transfergerial").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=transfergerial',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        console.log(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=baskaservisgidengelen", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis  },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });

            $(".taburcugerial").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcugerialma',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=taburcuhastalistesi", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });



        });

    </script>
<?php }
elseif ($islem == "btnsecilentaburcuislemi") {
    $islemgelen=$_GET['btnislem'];
    $yatisprotokol=$_GET['yatisprotokol'];
    $taburcu=singular("patient_discharge", "admission_protocol",$yatisprotokol);
    $hastakayit = singular("patient_registration", "protocol_number",$yatisprotokol);
    $izin = singularactive("patient_permission","protocol_number",$yatisprotokol);

    $taburcuisleminsert=yetkisorgula($kullanicid, "taburcuisleminsert");
    $taburcuislemupdate=yetkisorgula($kullanicid, "taburcuislemupdate");
    $taburcuislemdelete=yetkisorgula($kullanicid, "taburcuislemdelete");
    ?>
    <?php

    if($islemgelen=='28881'){
        if ($hastakayit['hospitalization_demand']=='3' && $taburcuislemupdate==1){
            var_dump($hastakayit['hospitalization_demand']);?>
            <a href="#" class="easyui-linkbutton
            <?php  if ($izin['id']!=''){echo 'alertizin';}else {  echo "taburculukgerial";  } ?>"
               iconCls="icon-reload">Taburcu hazirlik geri al</a>
        <?php
        }
        if($taburcuisleminsert==1){ ?>
            <a href="#" class="easyui-linkbutton <?php  if ($izin['id']!=''){echo 'alertizin';}else { echo "taburcuhazirlik";  } ?>"
               iconCls="icon-save">Taburcu hazirlik kaydet</a>
        <?php }
    }
    elseif ($islemgelen=='28961'){
        if($taburcuisleminsert==1){?>
            <a href="#" class="easyui-linkbutton  <?php if ($izin['id']!=''){echo 'alertizin';}else { echo "btnexislem";  } ?>"
               iconCls="icon-save">Ex Hasta Kaydet</a>
        <?php }
    }
    elseif ($islemgelen=='28882' ){
//        if ($taburcu['transfer_date']!='' || $taburcuislemupdate==1){ ?>
<!--            <input class="btn up-btn   transfergerial btn-sm" style="margin-bottom:4px"-->
<!--                   type="submit" data-dismiss="modal" value="Transfer gerial2 "/>-->
<!--        --><?php //}
        if($taburcuisleminsert==1){ ?>
            <a href="#" class="easyui-linkbutton  <?php if ($izin['id']!=''){echo 'alertizin';}else {  echo "transferkaydet";  } ?>"
               iconCls="icon-save">Transfer kaydet</a>
        <?php }
    }
    else{
        if ($hastakayit['hospitalization_demand']=='3'){
            if($taburcuisleminsert==1){ ?>
                <a href="#" class="easyui-linkbutton <?php  if ($izin['id']!=''){echo 'alertizin';}else {  echo "taburcuhazirlikkesinlestir";  } ?>"
                   iconCls="icon-edit">Taburcu Hazirliği Kesinleştir</a>

                <?php } if($taburcuislemupdate==1){ ?>
                <a href="#" class="easyui-linkbutton <?php  if ($izin['id']!=''){echo 'alertizin';}else {  echo "taburculukgerial";  } ?>"
                   iconCls="icon-reload">Taburcu hazirlik geri al</a>
        <?php }
        }
        if($taburcuisleminsert==1 && $hastakayit['hospitalization_demand']=='2'){ ?>
            <a href="#" class="easyui-linkbutton <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else {  echo "taburcukaydet";  } ?>"
               iconCls="icon-save">Kaydet</a>
<!--            <a href="javascript:void(0)" style="margin-right:10px;" title="Kaydet" class="icon-edit panel-tool-a update_btn-->
<!--             --><?php //if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else {  echo "taburcukaydet";  } ?><!--"></a>-->

        <?php }?>

    <?php } ?>


    <script>

        $(document).ready(function () {
            $(".taburcuhazirlik").off().on("click", function () {

                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcuhazirlikinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        // var baslangic=$('#baslangictarihi').val();
                        // var bitis=$('#bitistarihi').val();
                        // $.get( "ajax/yatis/yatislistesi.php?islem=birimyatan", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                        //     $('.secilentab').html(get);
                        // });
                        $('#yatisuaes').datagrid('load');
                    }
                });
            });

            $(".taburcukaydet").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcukaydetinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        // var baslangic=$('#baslangictarihi').val();
                        // var bitis=$('#bitistarihi').val();
                        // $.get( "ajax/yatis/yatislistesi.php?islem=birimyatan", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                        //     $('.secilentab').html(get);
                        // });
                        $('#yatisuaes').datagrid('load');
                    }
                });
            });


            $(".btnexislem").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcukaydetinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        var getir="<?php echo $yatisprotokol; ?>"

                        $.get("ajax/yatis/yatismodalbody.php?islem=olumformbody", {getir: getir}, function (getVeri) {
                            $('#modal-tanim-icerik').html(getVeri);

                        });
                        alert('modal açıldi');
                        // ölüm formu işlem
                        // $.get( "ajax/yatis/yatislistesi.php?islem=birimyatan", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                        //     $('.secilentab').html(get);
                        // });
                        $('#yatisuaes').datagrid('load');
                    }
                });
            });

            $(".transferkaydet").off().on("click", function () {
                //form reset-->
                var say=0;
                $('.transferislem').filter(function() {
                    if (this.value==''){
                        say++;
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formtaburcu").serialize();

                    document.getElementById("formtaburcu").reset();
                    var birimid="<?php echo $hastakayit['service_id']; ?>"
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=transferinsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            // var baslangic=$('#baslangictarihi').val();
                            // var bitis=$('#bitistarihi').val();
                            // $.get( "ajax/yatis/yatislistesi.php?islem=birimyatan", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                            //     $('.secilentab').html(get);
                            // });

                            $('#yatisuaes').datagrid('load');

                        }
                    });
                }

            });
            $(".taburculukgerial").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcuhazirlikgerial',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=taburcuolacakhastalistesi", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });


            $(".transfergerial").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=transfergerial',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=baskaservisgidengelen", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });

            $(".taburcuhazirlikkesinlestir").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#formtaburcu").serialize();
                var birimid="<?php echo $hastakayit['service_id']; ?>"
                document.getElementById("formtaburcu").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=taburcuhazirlikkesinlestir',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var baslangic=$('#baslangictarihi').val();
                        var bitis=$('#bitistarihi').val();
                        $.get( "ajax/yatis/yatislistesi.php?islem=taburcuolacakhastalistesi", { "birimid":birimid,"baslangic":baslangic,"bitis":bitis },function(get){
                            $('.secilentab').html(get);
                        });
                    }
                });
            });


        });
    </script>

<?php }
elseif ($islem == "olumformbody") {
    $hastaya_uniqid=$_GET['hastaya_uniqid'];
    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);
//    if ($hastakayit['anne_tc_kimlik_numarasi']==''){
//        $patients = singular("patients", "id", $hastakayit['patient_id']);
//    }else{
//        $ANNETC=$hastakayit['mother_tc_id'];
//        $DOGUMSiRA=$hastakayit['birth_order'];
//        $patients = tek("SELECT * FROM patients WHERE mother_tc_id='$ANNETC' AND birth_order='$DOGUMSiRA'");
//    }
    $hastaid=$hastakayit['patient_id'];
    $patients = tek("SELECT * FROM patients WHERE id='$hastaid'");
    $olum = singularactive("death_form","hospitalization_protocol",$_GET["getir"]);


    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>
    <div id="p" class="easyui-panel" title="ÖLüm Kayıt Formu" style="width:100%;height:93%;padding:10px;" data-options="footer:'#olum_footer'">
        <form id="formolum" action="javascript:void(0);">

            <div class="col-md-6">
                <input type="hidden" class="form-control protokol_numarasi" name="hospitalization_protocol"
                       value="<?php echo $hastakayit['protocol_number'] ?>" id="basicpill-firstname-input">
                <input type="hidden" class="form-control" name="form_number"
                       value="<?php echo rasgelesifreolustur(11) ?>" id="basicpill-firstname-input">
                <?php if($olum['id']!=''){ ?>
                    <input type="hidden" class="form-control" name="id"
                           value="<?php echo $olum['id'] ?>" id="basicpill-firstname-input">
                <?php  }?>
            </div>


            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">İşlem Tarihi</label>
                        <div class="col-md-9">
                            <input type="datetime-local" class="form-control" name="transaction_date"
                                   value="<?php if($olum['transaction_date']==''){echo date('Y-m-d H:i:s');}else{echo $olum['transaction_date'];} ?>" >
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3">Servis </label>
                        <div class="col-md-9">
                            <select class="form-select"
                                    name="approved_service"
                                    id="poliklinik23">

                                <?php

                                $kullanicininidsi = $_SESSION["id"];
                                $yetkilioldugumunits = birimyetkiselect($kullanicininidsi);
                                $bolumgetir = "SELECT * FROM units WHERE unit_type='1' $yetkilioldugumunits ORDER BY department_name ASC ";
                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $rowa) {
                                    ?>
                                    <option <?php
                                    if($olum['approved_service']==''){
                                        if($hastakayit['service_id'] == $rowa["id"]) echo"selected";
                                    }else{
                                        if($olum['approved_service'] == $rowa["id"]) echo"selected";
                                    } ?>

                                            value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2" >
                        <label class="form-label col-md-3">Doktor</label>
                        <div class="col-md-9">
                            <select class="form-select" name="approved_doctor" id="servisdoktor23">
                                <?php
                                $servis=$hastakayit['service_id'];
                                if($servis!=''){
                                    $bolumgetir = "SELECT * FROM users WHERE department=$servis  ORDER BY name_surname ASC ";
                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $rowa) {
                                        ?>
                                        <option <?php
                                        if($olum['approved_doctor']=='') {
                                            if ($hastakayit['service_doctor'] == $rowa["id"]) echo "selected";
                                        }else{
                                            if ($olum['approved_doctor'] == $rowa["id"]) echo "selected";
                                        }?>
                                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">Ölüm Tarihi</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" name="date_of_death"
                                   value="<?php if($olum['date_of_death']==''){echo date('Y-m-d');}else{echo $olum['date_of_death'];} ?>" >
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">Ölüm Saati</label>
                        <div class="col-md-9 ">
                            <input type="time" class="form-control" name="hour_of_death"
                                   value="<?php if($olum['hour_of_death']==''){echo date('H:i:s');}else{echo $olum['hour_of_death'];} ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">Ölüm Yeri</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="death_place" >
                                <option value="">Ölüm yeri seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='OLUM_YERI'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){ ?>

                                    <option  <?php if ($olum['death_place'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#poliklinik23").change(function () {
                        var birimid = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/yatis/yatisislem.php?islem=servisdoktorgetir",
                            data: {"birimid": birimid},
                            success: function (e) {
                                $("#servisdoktor23").html(e);
                            }
                        });
                    });
                });
            </script>
            <div class="row mt-1">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label class="col-md-3 ">Ölüm Sebebi</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="cause_of_death_select" >
                                <option value="">Ölüm sebebini seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='OLUM_NEDENLERI'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value){ ?>

                                    <option  <?php if ($olum['cause_of_death_select'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label class="col-md-3 ">Ölüm Şekli</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="method_of_death" >
                                <option value="">Ölüm şekli seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='METHOD_OF_DEATH'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){ ?>

                                    <option  <?php if ($olum['method_of_death'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label class="col-md-3 ">Adli Sonuç</label>
                        <div class="col-md-9">
                            <select class="form-select" name="forensic_result" >
                                <option value="">Adli sonuç seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='FORENSIC_RESULT'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){?>

                                    <option  <?php if ($olum['forensic_result'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">K. G. Nedeni</label>
                        <div class="col-md-9">
                            <textarea class="form-control olumempty" placeholder="Kuruma gelme nedini giriniz.." name="reason_come_institution" rows="1" cols="10"> <?php echo $olum['reason_come_institution']  ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3 ">Ölüm Sebebi</label>
                        <div class="col-md-9">
                            <textarea class="form-control olumempty" rows="1" cols="10" name="cause_of_death" ><?php echo $olum['cause_of_death'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-4">
                        <button type="button" class="btn yeni-btn otopsistatus col-md-6">Otopsi</button>
                        <button type="button" class="btn kps-btn yaralamastatus col-md-6">Yaralama Sonuçu</button>
                    </div>
                </div>

            </div>
            <div class="mt-1  row">
                <?php if ($hastakayit['mother_tc_id']!=''){ ?>
                    <input type="hidden" class="form-control" name="death_baby" value="<?php echo '1'; ?>">
                    <input type="hidden" class="form-control" name="mother_tr_id_no" value="<?php echo $hastakayit['mother_tc_id']; ?>">


                    <div class="col-md-1 " align="center">
                        <div class="row mx-2">
                            <label  class="form-label col-md-3">Ölü Doğum</label>
                            <div class="col-md-9">
                                <input type="checkbox"   name="death_birth" value="1" <?php if($olum['death_birth']){ echo "checked"; } ?>>
                            </div>
                        </div>
                    </div>
                <?php  }?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label class="col-md-3">İl</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="province_of_death" id="olumil">
                                <option value="">il seçiniz..</option>
                                <?php $bolumgetir = "select * from province" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){?>

                                    <option <?php if ($olum['province_of_death'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['province_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="col-md-3">ilçe <?php echo $olum['province_of_death']; ?></label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="county_of_death" id="olumilce" disabled>
                                <?php $ilno=$olum['province_of_death'];
                                if ($ilno!=''){
                                    $bolumgetir = "select * from district where id='$ilno'" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>

                                        <option <?php if ($olum['county_of_death'] == $value["id"]) echo "selected"; ?>
                                                value="<?php echo $value["id"]; ?>" ><?php echo $value['district_name']; ?></option>

                                    <?php  }
                                }?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class=" col-md-3">Mahalle</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="death_area" id="olummahalle" disabled>
                                <?php $ilce=$olum['county_of_death'];
                                if ($ilce!=''){
                                    $bolumgetir = "select * from neighborhood where district_id=$ilce" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>

                                        <option <?php if ($olum['death_area'] == $value["id"]) echo "selected"; ?>
                                                value="<?php echo $value["id"]; ?>" ><?php echo $value['neighborhood_name']; ?></option>

                                    <?php  }
                                }?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-4 ">
                    <div class="row mx-2">
                        <label  class=" col-md-3">Köy</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="village_of_death" id="olumkoy" disabled>
                                <?php $ilce=$olum['county_of_death'];
                                if ($ilce!=''){
                                    $bolumgetir = "select * from neighborhood where district_id=$ilce" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){ ?>

                                        <option <?php if ($olum['village_of_death'] == $value["id"]) echo "selected"; ?>
                                                value="<?php echo $value["id"]; ?>" ><?php echo $value['neighborhood_name']; ?></option>

                                    <?php  }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 divyaralama" style="<?php if ($olum['injury_site']==''){ ?> display: none <?php } ?>">
                    <div class="row mx-2">
                        <label class=" col-md-3">Y. Yeri</label>
                        <div class="col-md-9">
                            <select class="form-select" name="injury_site" >
                                <option value="">Yaralanma yeri seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='INJURY_SITE'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){ ?>

                                    <option  <?php if($olum['injury_site'] == $value["id"]) echo"selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="col-md-4 divyaralama" style="<?php if ($olum['time_of_injury']==''){ ?> display: none <?php } ?>">
                    <div class="row mx-2">
                        <label  class=" col-md-3">Y. tarihi</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" name="time_of_injury"
                                   value="<?php if($olum['time_of_injury']==''){echo date('Y-m-d');}else{echo date('Y-m-d',strtotime($olum['time_of_injury']));} ?>">
                        </div>
                    </div>


                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#olumil").change(function () {
                        var ilid =$(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/yatis/yatisislem.php?islem=olumililce",
                            data: {"ilid": ilid},
                            success: function (e) {
                                $("#olumilce").html(e);
                                $("#olumilce"). prop('disabled', false);
                            }
                        });
                    });

                    $("#olumilce").change(function () {
                        var ilceid =$(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/yatis/yatisislem.php?islem=olumililcemahalle",
                            data: {"ilceid": ilceid},
                            success: function (e) {
                                $("#olummahalle").html(e);
                                $("#olummahalle"). prop('disabled', false);
                            }
                        })
                    })
                    $("#olumilce").change(function () {
                        var ilceid =$(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/yatis/yatisislem.php?islem=olumililcemahalle",
                            data: {"ilceid": ilceid},
                            success: function (e) {
                                $("#olumkoy").html(e);
                                $("#olumkoy"). prop('disabled', false);
                            }
                        })
                    })


                });
            </script>
            <div class="row mt-1">
                <?php
                $yasfark=ikitariharasindakiyilfark($simdikitarih,$patients['birth_date'],'-');

                if ($patients['sex']=='K' && $yasfark>2){ ?>
                    <div class="col-md-4">
                        <div class="row mx-2">
                            <label  class="form-label col-md-3">K. Ö. Nedeni</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="cause_of_death_a_female" cols="1" rows="1"> <?php echo $olum['cause_of_death_a_female']  ?></textarea>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-8">

                        <div class="row">
                            <div class="col-md-3">
                                <input type="radio" id="mother_is_not_death" class="selectcheck" name="mother_is_not_death" value="1" <?php if($olum['mother_is_not_death']){ echo "checked"; } ?>>
                                <label for="mother_is_not_death">Anne Ölümü Değil</label>
                            </div>
                            <div class="col-md-5">
                                <input type="radio" id="woman_death_pregnancy" class="selectcheck"  name="woman_death_pregnancy" value="1" <?php if($olum['woman_death_pregnancy']){ echo "checked"; } ?>>
                                <label for="woman_death_pregnancy">Ölüm Hamileliği Esnasinda Gerçekleşti</label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" id="woman_death_birth" class="selectcheck"  name="woman_death_birth" value="1" <?php if($olum['woman_death_birth']){ echo "checked"; } ?>>
                                <label for="woman_death_birth">Ölüm Doğum Esnasinda Gerçekleşti</label>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <input type="radio" id="woman_death_birth_42" class="selectcheck" name="woman_death_birth_42" value="1" <?php if($olum['woman_death_birth_42']){ echo "checked"; } ?>>
                            <label for="woman_death_birth_42">Ölüm Doğumdan Sonraki 42 Gün içerisinde Gerçekleşti ?</label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" id="woman_death_birth_42_365" class="selectcheck" name="woman_death_birth_42_365" value="1" <?php if($olum['woman_death_birth_42_365']){ echo "checked"; } ?>>
                            <label for="woman_death_birth_42_365">Ölüm Doğumdan Sonraki 42 Gün ile 365 Gün içerisinde Gerçekleşti ?</label>
                        </div>
                    </div>
                <?php } ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".selectcheck").click(function () {
                            // $(".divotopsi").show();
                            //$('.divyaralama').toggle();
                            $(".selectcheck").prop('checked', false);
                            $(this).prop('checked', true);
                        })

                    });
                </script>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3">B. V. A. S.</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control olumempty" placeholder="Bilgi Verilenin Adi Soyadi" name="informed_name_surname" value="<?php echo $olum['informed_name_surname'] ?>" >
                        </div>
                    </div>


                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3">B. V. T.</label>
                        <div class="col-md-9">
                            <input type="tel" class="form-control olumempty" placeholder="Bilgi Verilenin Telefon" name="informed_telephone" value="<?php echo $olum['informed_telephone'] ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label  class="form-label col-md-3">B. V. Y.</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control olumempty" placeholder="Bilgi Verilenin Yakinliği" name="informed_proximity" value="<?php echo $olum['informed_proximity'] ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">

                <div class="col-md-4">
                    <div class="row mx-2">
                        <label for="basicpill-firstname-input" class="form-label col-md-3">Ö. N. B. K.</label>
                        <div class="col-md-9">
                            <select class="form-select olumempty" name="death_determining_institution" >
                                <option value="">Ölüm Nedenini Belirleyen Kurum</option>
                                <?php
                                $servis=$hastakayit['service_id'];
                                $bolumgetir = "SELECT * FROM hospital_building WHERE status='1'  ORDER BY building_name ASC ";
                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $rowa) {
                                    ?>
                                    <option <?php
                                    if($olum['death_determining_institution']=='') {
                                        if($hastakayit['building_id'] == $rowa["id"]) echo"selected";
                                    }else{
                                        if ($olum['death_determining_institution'] == $rowa["id"]) echo "selected";
                                    }

                                    ?>
                                            value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["building_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".yaralamastatus").click(function () {
                            // $(".divotopsi").show();
                            $('.divyaralama').toggle();
                        })

                    });
                </script>
            </div>
            <div class="row mt-1">
                <div class="col-md-4 divotopsi" style="<?php if ($olum['autopsy_status_process']==''){ ?> display: none <?php } ?>">
                    <div class="row mx-2">
                        <label class="col-md-3">Otopsi statusu</label>
                        <div class="col-md-9">
                            <select class="form-select" name="autopsy_status_process" >
                                <option value="">Otopsi statusunu  seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='AUTOPSY_STATUS_PROCESS'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){ ?>

                                    <option  <?php if($olum['autopsy_status_process'] == $value["id"]) echo"selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".otopsistatus").click(function () {

                            $('.divotopsi').toggle();
                            $('.check').prop('checked',false);
                        })

                    });
                </script>
                <div class="col-md-4 divotopsi  mx-2" style="<?php if ($olum['autopsy_status_process']==''){ ?> display: none <?php } ?>">
                    <div class="row">

                        <div class="col-md-1">
                            <input type="radio" class="check"  name="from_autopsy_findings" value="1" <?php if($olum['from_autopsy_findings']){ echo "checked"; } ?>>
                        </div>
                        <div class="col-md-11">
                            <b>Ölüm nedeni otopsi bulgularindan mi elde edildi ?</b>
                        </div>
                    </div>
                    <div class="row mt-1">

                        <div class="col-md-1">
                            <input type="radio" class="check"  name="autopsy_more_info" value="1" <?php if($olum['autopsy_more_info']){ echo "checked"; } ?>>
                        </div>
                        <div class="col-md-10">
                            <b>Daha sonra daha fazla bilgi elde edilir mi ?</b>
                        </div>
                    </div>
                </div>
            </div>


        </form>
    </div>
    <div class="panel-header" style="height:30px">
        <div class="panel-tool">
        <?php
        $hastaolumforminsert=yetkisorgula($kullanicid, "hastaolumforminsert");
        $hastaolumformupdate=yetkisorgula($kullanicid, "hastaolumformupdate");
        $hastaolumformdelete=yetkisorgula($kullanicid, "hastaolumformdelete");

        ?>

        <?php if($olum['id']==''){ ?>
            <?php if($taburcu['discharge_status']==1){
                if ($hastaolumforminsert==1){ ?>
                    <a href="javascript:void(0)" style="margin-right:10px;" title="Yeni Kayıt" class="icon-save update_btn panel-tool-a
                                <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'olumkaydet olumtaburcu '; } ?>"
                    ></a>
                <?php  }
            }
            else{
                if ($hastaolumforminsert==1){ ?>
                    <a href="javascript:void(0)" style="margin-right:10px;" title="Yeni Kayıt" class="icon-save update_btn panel-tool-a
                                <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'olumkaydet oluminsert'; } ?>"
                    ></a>
                <?php  }
            } ?>

        <?php }
        else{
            if ($hastaolumformupdate==1){ ?>
                <a href="javascript:void(0)" style="margin-right:10px;" title="Düzenle" class="icon-edit panel-tool-a update_btn
                                     <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'olumupdateyap'; } ?>"
                ></a>
            <?php } if ($hastaolumformdelete==1){ ?>
                <a href="javascript:void(0)" style="margin-right:10px;" title="İptal Et" data-id="<?php echo $olum["id"]; ?>" class="icon-cancel panel-tool-a update_btn
                                  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'olumdeletyap'; } ?>"
                ></a>
            <?php }
        } ?>

        </div>
    </div>


   <script>

            $(document).ready(function () {

                $(".olumtaburcu").off().on("click", function () {
                    //form reset-->
                    var gonderilenform = $("#formolum").serialize();

                    //document.getElementById("formolum").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=oluminsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);


                        }
                    });
                });

                $(".oluminsert").off().on("click", function () {
                    //form reset-->

                    var say=0;
                    $('.olumempty').filter(function() {
                        if (this.value==''){
                            say++;
                        }
                    });

                    if (say>0) {
                        alertify.error('Boş alanları doldurun');
                    }else{
                        var gonderilenform = $("#formolum").serialize();

                        //document.getElementById("formolum").reset();
                        $.ajax({
                            type: 'POST',
                            url: 'ajax/yatis/yatisislem.php?islem=oluminsert',
                            data: gonderilenform,
                            success: function (e) {
                                $("#sonucyaz").html(e);

                                var getir ="<?php echo $protokolno;  ?>";

                                alertify.confirm("<div class='alert alert-danger'>Taburculuk işlemleri Ekrani Açilsin Mi....</div>", function(){


                                    $.get("ajax/yatis/yatismodalbody.php?islem=taburcubody", {getir: getir}, function (getVeri) {
                                        $('#modal-tanim-icerik').html(getVeri);
                                        yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                                    });

                                }, function(){

                                    $.get("ajax/yatis/yatisislem.php?islem=taburcuekranihayir", {getir: getir}, function (getVeri) {
                                        console.log(getVeri);
                                        yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                                    });

                                }).set({labels:{ok: "Evet", cancel: "Hayir"}}).set({title:"Taburculuk işlemi"});


                            }
                        });

                        $('#windows50-<?php echo $hastaya_uniqid; ?>').dialog('close');
                    }

                });


            });
        </script>

    <script>

        $(document).ready(function () {

            $(".olumupdateyap").on("click", function () {
                //form reset-->
                var gonderilenform = $("#formolum").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/yatis/yatisislem.php?islem=olumupdate',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);

                    }
                });

            });
            $(".olumdeletyap").click(function () {


                var id = $(this).attr('data-id');

                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#personel_delete_detail').val();
                    var birimid = "<?php echo $olum['approved_service'] ?>";

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=btnolumdelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            console.log(e);
                            // $.get( "ajax/yatis/yatislistesi.php?islem=taburcuolacakhastalistesi", { "birimid":birimid },function(get){
                            //     $('.secilentab').html(get);
                            // });
                            $('#windows50-<?php echo $hastaya_uniqid; ?>').dialog('close');
                            yatisdatatable.ajax.url(yatisdatatable.ajax.url()).load();
                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});



            });

        });

    </script>
<?php }


elseif ($islem == "epkrizyeniden") {
    if ($_GET['epikrizid']!=''){
        $epikrizid=$_GET['epikrizid'];
        $epikriz = singularactive("epicrisis","id",$epikrizid);
        $hastakayit = singularactive("patient_registration", "protocol_number",$epikriz["protocol_id"]);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $protokolno=$epikriz["protocol_id"];
        $ameliyat = singularactive("operation", "patient_protocol_number", $epikriz["protocol_id"]);
    }else{
        $protokolno=$_GET['getir'];
        $epikrizislem=tek("select * from epicrisis where protocol_id=8 order by id desc fetch first 1 rows only");
        $hastakayit = singularactive("patient_registration", "protocol_number",$protokolno );
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $ameliyat = singularactive("operation", "patient_protocol_number", $protokolno);
    }

    ?>

    <form id="formepikriz" action="javascript:void(0);">
        <div class="mb-2 row">

            <div class="col-md-6">

                <input type="hidden" class="form-control" name="protocol_id"
                       value="<?php echo $protokolno; ?>" id="basicpill-firstname-input">
                <?php if ($epikriz["id"]!=''){ ?>
                    <input type="hidden" class="form-control" name="id"
                           value="<?php echo $epikriz["id"] ?>" id="basicpill-firstname-input">
               <?php }?>
            </div>

        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="servis_id"
                       value="<?php echo $hastakayit["service_id"] ?>" id="basicpill-firstname-input">
            </div>
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="servis_doktor"
                       value="<?php echo $hastakayit["service_doctor"] ?>" id="basicpill-firstname-input">
            </div>

        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Özgeçmiş">Özgeçmiş</label>
                    <div class="col-md-9">
                        <textarea class="form-control " name="curriculum_vitae"  placeholder="ÖZgeçmiş giriniz.."><?php if ($_GET['epikrizid']!='') { echo $epikriz["curriculum_vitae"]; }else{ echo $epikrizislem["curriculum_vitae"]; }?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Aile Öyküsü">Aile Öyküsü</label>
                    <div class="col-md-9">
                        <textarea class="form-control " name="family_history"  placeholder="Aile Öyküsü giriniz.."><?php if ($_GET['epikrizid']!='') { echo $epikriz["family_history"]; }else{ echo $epikrizislem["family_history"]; }?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Şikayet">Şikayet</label>
                    <div class="col-md-9">
                        <textarea class="form-control epikrizempty" name="complaint" placeholder="Şikayet giriniz.." ><?php echo $epikriz['complaint']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Öykü">Öykü</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="story" placeholder="Öykü giriniz.."><?php echo $epikriz['story']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Klinik Bulgu">Klinik Bulgu</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="clinical_findings" placeholder="Klinik bulgu giriniz.."><?php echo $epikriz['clinical_findings']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Karar">Karar</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="decision" placeholder="Karar giriniz.."><?php echo $epikriz['decision']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Ameliyat Notu">A. Notu</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="surgery_note" placeholder="Ameliyat Notu giriniz.."><?php echo $epikriz['surgery_note']; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Tedavi Önerileri" >T. Önerileri</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="treatment_recommendations"  placeholder="Tedavi önerisi giriniz.."><?php echo $epikriz['treatment_recommendations']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Ameliyat Notu">Açıklama</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="epicrisis_explanation" placeholder="Açıklama giriniz.."><?php echo $epikriz['epicrisis_explanation']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Fiziki Bulgular">F.Bulgular</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="examination" placeholder="Fiziki Bulgular giriniz.."><?php echo $epikriz['examination']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

            <button class="btn kapat-btn btn-sm">Kaydet</button>

    </form>


<?php }
elseif ($islem == "sevkyeniden") {
    if ($_GET['sevkid']!=''){
        $sevk = singular("patient_reference","id", $_GET["sevkid"]);
        $yatis = singularactive("patient_registration", "protocol_number", $sevk['protocol_id']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }else{
        $yatis = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }



    ?>

    <form id="formsevk" action="javascript:void(0);">
        <div class="row ">

            <div class="col-md-6">

                <input type="hidden" class="form-control" name="protocol_id"
                       value="<?php echo $yatis['protocol_number'] ?>" id="basicpill-firstname-input">
                <?php if ($sevk['id']!=''){ ?>
                    <input type="hidden" class="form-control" name="id"
                           value="<?php echo $sevk['id'] ?>" id="basicpill-firstname-input">
              <?php  }else{?>

                <?php } ?>
                <input type="hidden" class="form-control" name="tracking_number"
                       value="<?php if ($sevk['id']!=''){ echo $sevk['tracking_number']; }else{ echo rasgelesifreolustur(11); } ?>" id="basicpill-firstname-input">
            </div>

        </div>
        <div class="row mt-1">
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="service_id"
                       value="<?php echo $yatis["service_id"] ?>" id="basicpill-firstname-input">
            </div>
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="service_doctor"
                       value="<?php echo $yatis["service_doctor"] ?>" id="basicpill-firstname-input">
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Tarihi">Sevk Tarihi</label>
                    <div class="col-md-9">
                        <input type="datetime-local" class="form-control" name="the_shipment_date"
                               value="<?php
                               if ($sevk['the_shipment_date']!=''){
                                   echo $sevk['the_shipment_date'];
                               }else{
                                   echo $simdikitarih;
                               }

                               ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Edilen Yer">S. E. Yer</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control sevkempty" name="shipped_place" placeholder="Sevk edilen yeri giriniz.." value="<?php echo $sevk['shipped_place'] ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="Sevk Türü">Sevk Türü</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="shipping_type" >
                            <option value="">Sevk türü seçiniz..</option>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='SHIPPING_TYPE'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['shipping_type'] == $value["id"]) echo"selected"; ?>
                                        value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="sevk tipi">S. Tipi</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="dispatch_status" id="sevktipistatus">
                            <option value="">Sevk tipi seçiniz..</option>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='SEVK_TURU'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['dispatch_status'] == $value["definition_supplement"]) echo"selected"; ?>
                                        value="<?php echo $value["definition_supplement"]; ?>" ><?php echo $value['definition_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Araci">Sevk Araci</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="dispatch_vehicle" >
                            <option value="">Sevk aracı seçiniz..</option>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='SEVK_ARACI'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['dispatch_vehicle'] == $value["id"]) echo"selected"; ?>
                                        value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Edilen Branş">S. E. Branş</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="shipped_branch" >
                            <option value="">sevk edilen branş seçiniz..</option>
                            <?php $bolumgetir = "select * from branch WHERE status='1' And branch_code in 
                                                ('6000','2','1000','1048','1062','1099','1100','1148','1171','1300','1500','1548','1561','1582','1584'
                                                ,'1586','1588','1594','1596','1599','1700','1800','1900','1910','2000','2100','2200','2300','2387','2400','2500','2600',
                                                '2700','2781','3197','3300','3400','3500','4400','4500','4800','5100','5150','5200','5300','5400','5500','5600','5700','6000','9900')" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['shipped_branch'] == $value["id"]) echo"selected"; ?>
                                        value="<?php echo $value["id"]; ?>" ><?php echo $value['branch_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="Tedavi Türü">Tedavi Türü</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="type_of_treatment" >
                            <option value="">Tedavi türü seçiniz..</option>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='TYPE_OF_TREATMENT'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['type_of_treatment'] == $value["id"]) echo"selected"; ?>
                                        value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="Refakat status">R. Status</label>
                    <div class="col-md-9">
                        <select class="form-select sevkempty" name="accompanying_status" id="refakatstatus1">
                            <option value="">Refakatçi status seçiniz..</option>
                            <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='ACCOMPANYING_STATUS'" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){ ?>

                                <option  <?php if($sevk['accompanying_status'] == $value["definition_supplement"]) echo"selected"; ?>
                                        value="<?php echo $value["definition_supplement"]; ?>" ><?php echo $value['definition_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row" id="refstatushide1" style="
        <?php $bakvarmi=tek("select * from transaction_definitions WHERE status='1' AND definition_type='ACCOMPANYING_STATUS' AND definition_name='Refakatli'");
        if ($bakvarmi['definition_supplement']==$sevk['accompanying_status']){  ?>

       <?php  }else{ ?>
                display: none;
      <?php  } ?>">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Refakat Notu">R. Notu</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="accompanying_note" placeholder="Refakatçı notu giriniz.." rows="1"><?php echo $sevk['accompanying_note'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Refakatçi Adi Soyadi">R. A. Soyadi</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="accompanying_name" placeholder="Refakatçı adi soyadi giriniz.." value="<?php echo $sevk['accompanying_name'] ?>" id="basicpill-firstname-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-1 row" id="sevktipihide" style="
        <?php $bakvarmi=tek("select * from transaction_definitions WHERE status='1' AND definition_type='SEVK_TURU' AND definition_name='DIŞ SEVK'");
        if ($bakvarmi['definition_supplement']==$sevk['dispatch_status']){  ?>

        <?php  }else{ ?>
                display: none;
        <?php  } ?>
            ">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="Tedavi Türü">Sevk İl</label>
                    <div class="col-md-9">
                        <select class="form-select" name="province_dispatch" id="sevkil">
                            <option value="">il seçiniz..</option>
                            <?php $bolumgetir = "select * from province" ;

                            $hello=verilericoklucek($bolumgetir);
                            foreach ((array) $hello as $value){?>

                                <option <?php if ($sevk['province_dispatch'] == $value["id"]) echo "selected"; ?>
                                        value="<?php echo $value["id"]; ?>" ><?php echo $value['province_name']; ?></option>

                            <?php  } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3" title="Refakat status">Sevk İlçe</label>
                    <div class="col-md-9">
                        <select class="form-select" name="dispatch_county" id="sevkilce">
                            <?php $ilno=$sevk['province_dispatch'];
                            if ($ilno!=''){
                                $bolumgetir = "select * from district where province_number='$ilno'" ;

                                $hello=verilericoklucek($bolumgetir);
                                foreach ((array) $hello as $value){ ?>

                                    <option <?php if ($sevk['dispatch_county'] == $value["id"]) echo "selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['district_name']; ?></option>

                                <?php  }
                            }?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-1 row">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Açiklama">S. Açiklama</label>
                    <div class="col-md-9">
                        <textarea class="form-control sevkempty" name="shipment_description" placeholder="Sevk açıklaması giriniz.." rows="2"><?php echo $sevk['shipment_description'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Sevk Nedeni">Sevk Nedeni</label>
                    <div class="col-md-9">
                        <textarea class="form-control sevkempty" name="reason_for_shipping" placeholder="Sevk nedeni giriniz.." rows="2"><?php echo $sevk['reason_for_shipping'] ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $("#sevkil").change(function () {
            var ilid =$(this).val();
            $.ajax({
                type: "POST",
                url: "ajax/yatis/yatisislem.php?islem=olumililce",
                data: {"ilid": ilid},
                success: function (e) {
                    $("#sevkilce").html(e);
                }
            });
        });

        $("#refakatstatus1").change(function () {
            var refstatus = $(this).val();
            if(refstatus=="1"){
                $("#refstatushide1").show();
            }else{

                $("#refstatushide1").hide();
            }
        })
        $("#sevktipistatus").change(function () {
            var refstatus = $(this).val();
            if(refstatus=="2"){
                $("#sevktipihide").show();
            }else{

                $("#sevktipihide").hide();
            }
        })
    </script>
<?php }



elseif ($islem=="izingeribody"){


    if($_GET['izinid']){
//        $refakatci = tek("select * from patient_companion where id={$_GET['refakatciid']}");
        $izin = singularactive("patient_permission","id",$_GET['izinid']);
        $hastakayit = singularactive("patient_registration", "protocol_number", $izin['protocol_number']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }else{
        $hastakayit = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
//    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>


    <form id="formizin" action="javascript:void(0);">
        <div class="modal-body">
            <div id="sonuckps">

            </div>
            <div class="mb-2 row">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="row">
                            <label  class="form-label col-md-5">İzin Başlangıç Tarihi</label>
                            <div class="col-md-7">
                                <input type="date" class="form-control izinempty" name="izin[permission_start_date]" value="<?php if($izin['permission_start_date']){ echo $izin['permission_start_date']; }else{ echo $tarih;} ?>" >

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label  class="form-label col-md-5">İzin Bitiş Tarihi</label>
                            <div class="col-md-7">
                                <input type="date" class="form-control izinempty" name="izin[permission_end_date]"  value="<?php if($izin['permission_end_date']){ echo $izin['permission_end_date']; }else{ echo $tarih;} ?>" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5 ">Hasta Protokol No </label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" disabled value="<?php echo $hastakayit['protocol_number'] ?>">

                                <input type="hidden" class="form-control" name="izin[protocol_number]"
                                       value="<?php echo $hastakayit['protocol_number']; ?>" >
                                <input type="hidden" class="form-control" name="izin[patient_id]"
                                       value="<?php echo $hastakayit['patient_id']; ?>" >
                                <?php if ($_GET['izinid']){ ?>
                                <input type="hidden" class="form-control" name="izin[id]"
                                       value="<?php echo $_GET['izinid']; ?>" id="basicpill-firstname-input">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5 ">Hasta T.C. No</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control " disabled
                                       value="<?php echo $patients['tc_id'];  ?>" >
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5">Telefon</label>
                            <div class="col-md-7">
                                <input type="tel" class="form-control izinempty" name="izin[permission_telephone]" value="<?php echo $izin['permission_telephone'] ?>" placeholder="Telefon  giriniz..">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label  class="form-label col-md-5">Adres</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control izinempty" name="izin[permission_address]" value="<?php echo $izin['permission_address'] ?>" placeholder="Adres giriniz..">

                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <div class="modal-footer py-0">
            <?php
            $hastaizininsert=yetkisorgula($kullanicid, "hastaizininsert");
            $hastaizinupdate=yetkisorgula($kullanicid, "hastaizinupdate");
            $hastaizindelete=yetkisorgula($kullanicid, "hastaizindelete");
            ?>
            <?php if($_GET['izinid']){
                if ($hastaizininsert==1){ ?>
                <input class="btn  w-md justify-content-end  btn-sm yeni-btn  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'izinyeni'; } ?> "
                       type="submit"  value="Yeni"/>
                    <?php } if ($hastaizindelete==1){ ?>
                <input class="btn  w-md justify-content-end  btn-sm sil-btn  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else { echo 'izindelet'; } ?>"
                       data-id="<?php echo $izin["id"]; ?>"
                       type="submit"  value="Silme"/>
                <?php } if ($hastaizinupdate==1){ ?>
                <input class="btn w-md justify-content-end btn-sm up-btn <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else { echo 'izinupdate'; } ?>"
                       type="submit"  value="Güncelle"/>
            <?php }
            }
            if ($hastaizininsert==1 && $_GET['izinid']==''){ ?>
                <input class="btn text-white w-md justify-content-end btn-sm up-btn <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'izininsert'; } ?>"
                       type="submit"  value="Kaydet"/>
            <?php } ?>

        </div>
    </form>

    <script>
        $(document).ready(function () {
            $(".izininsert").on("click", function () {
                //form reset-->

                var say=0;
                $('.izinempty').filter(function() {
                    if (this.value==''){
                        say++;
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formizin").serialize();

                    document.getElementById("formizin").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=izininsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $_GET['getir']; ?>";
                            $.get("ajax/yatis/yatislistesi.php?islem=izinlistesi", {getir: getir}, function (g) {
                                $(".izinlilistesi-<?php echo $protokolno; ?>").html(g);

                            });

                        }
                    });
                }

            });

            $(".izinupdate").on("click", function () {
                //form reset-->

                var gonderilenform = $("#formizin").serialize();
                var getir ="<?php echo $izin["protocol_number"]; ?>";

                //document.getElementById("formrefakatup").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=izinupdate',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        $.get("ajax/yatis/yatislistesi.php?islem=izinlistesi", {getir: getir}, function (g) {
                            $(".izinlilistesi-<?php echo $protokolno; ?>").html(g);

                        });
                        $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir:getir}, function (ec) {
                            $("#izinlibodyupdate-<?php echo $protokolno; ?>").html(ec);
                        });

                    }
                });

            });

            $(".izindelet").click(function () {
                var id = $(this).data('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                    "<input class='form-control' type='text' id='delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#delete_detail').val();
                    var getir ="<?php echo $izin["protocol_number"]; ?>";
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=izindelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            $.get("ajax/yatis/yatislistesi.php?islem=izinlistesi", {getir: getir}, function (g) {
                                $(".izinlilistesi-<?php echo $protokolno; ?>").html(g);
                                $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir:getir}, function (ec) {
                                    $("#izinlibodyupdate-<?php echo $protokolno; ?>").html(ec);
                                });
                            });

                            $('.alertify').remove();
                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});

            });
            $(".izinyeni").click(function () {
                var getir ="<?php echo $izin["protocol_number"]; ?>";
                $('.aktif-satir').removeClass('aktif-satir');

                $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir: getir}, function (getVeri) {
                    $("#izinlibodyupdate-<?php echo $protokolno; ?>").html(getVeri);

                });
            });

        });
    </script>


<?php }


elseif ($islem=="temizlikgeribody"){


    if($_GET['temizlikid']){
//        $refakatci = tek("select * from patient_companion where id={$_GET['refakatciid']}");
        $temizlik = singularactive("bed_cleaning","id",$_GET['temizlikid']);
        $hastakayit = singularactive("patient_registration", "protocol_number", $temizlik['protokol_no']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }else{
        $hastakayit = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }

    ?>


    <form id="formizin" action="javascript:void(0);">
        <div class="modal-body">

            <div class=" row">
                <div class="row ">
                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5 ">Hasta Protokol No </label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" disabled value="<?php echo $hastakayit['protocol_number'] ?>">

                                <input type="hidden" class="form-control" name="temizlik[protokol_no]"
                                       value="<?php echo $hastakayit['protocol_number']; ?>" >
                                <input type="hidden" class="form-control" name="temizlik[patient_id]"
                                       value="<?php echo $hastakayit['patient_id']; ?>" >
                                <?php if ($_GET['temizlikid']){ ?>
                                <input type="hidden" class="form-control" name="temizlik[id]"
                                       value="<?php echo $_GET['temizlikid']; ?>" id="basicpill-firstname-input">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5 ">Hasta T.C. No</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control " disabled
                                       value="<?php echo $patients['tc_id'];  ?>" >
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-md-6">
                        <div class="row ">
                            <label  class="form-label col-md-5">Yatak</label>
                            <div class="col-md-7">
                                <select class="form-select yataktemizleempty" name="temizlik[bed_id]" >
                                    <option value="">Yatak seçiniz..</option>
                                    <?php $bolumgetir = "select bed_id as yatak_id,bed_name yatak_adi,* from patient_registration inner join hospital_bed on patient_registration.bed_id=hospital_bed.id" ;

                                    $hello=verilericoklucek($bolumgetir);
                                    foreach ((array) $hello as $value){?>

                                        <option <?php if ($temizlik['bed_id'] == $value["yatak_id"]) echo "selected"; ?>
                                                value="<?php echo $value["yatak_id"]; ?>" ><?php echo $value['yatak_adi']; ?></option>

                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <?php
            $yataktemizleinsert=yetkisorgula($kullanicid, "yataktemizleinsert");
            $yataktemizleupdate=yetkisorgula($kullanicid, "yataktemizleupdate");
            $yataktemizledelete=yetkisorgula($kullanicid, "yataktemizledelete");
            $yataktemizleclean=yetkisorgula($kullanicid, "yataktemizleclean");

            ?>
            <button type="button" class="btn kapat-btn btn-sm  text-white"  data-bs-dismiss="modal">Kapat</button>
            <?php if($_GET['temizlikid']){
                    if ($yataktemizleinsert==1){ ?>
                    <input class="btn  w-md justify-content-end temizlikyeni btn-sm yeni-btn"
                           type="submit"  value="Yeni"/>
                     <?php } if ($yataktemizledelete==1){ ?>
                    <input class="btn  w-md justify-content-end temizlikdelet btn-sm sil-btn"  data-id="<?php echo $temizlik["id"]; ?>"
                           type="submit"  value="Silme"/>
                     <?php } if ($yataktemizleupdate==1){  ?>
                    <input class="btn w-md justify-content-end btn-sm up-btn temizlikupdate"
                           type="submit"  value="Güncelle"/>
                    <?php } if ($yataktemizleclean==1){ ?>
                    <input class="btn w-md justify-content-end btn-sm kps-btn temizlendi"
                           <?php if ($temizlik['bed_status']){ echo 'disabled'; } ?>
                           type="submit"  value="Temizlendi"/>
                <?php }
                    }else if ($yataktemizleinsert==1){ ?>
                <input class="btn text-white w-md justify-content-end btn-sm up-btn temizlikinsert"
                       type="submit"  value="Kaydet"/>
            <?php } ?>

        </div>
    </form>

    <script>
        $(document).ready(function () {
            $(".temizlendi").on("click",function (){
                alert('geldi');
            })
            $(".temizlendi").on("click", function () {
                alert('hata btn');
                var id ="<?php echo $temizlik["id"]; ?>";
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=temizledi',
                    data:{id:id},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var getir ="<?php echo $temizlik["protokol_no"]; ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=temizliklistesi", {getir: getir}, function (g) {
                            $(".temizliklistesi-<?php echo $temizlik["protokol_no"]; ?>").html(g);

                        });

                    }
                });

            });

            $(".temizlikinsert").on("click", function () {
                //form reset-->

                var say=0;
                $('.yataktemizleempty').filter(function() {
                    if (this.value==''){
                        say++;
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formizin").serialize();

                    document.getElementById("formizin").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=temizlikinsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $_GET['getir']; ?>";
                            $.get("ajax/yatis/yatislistesi.php?islem=temizliklistesi", {getir: getir}, function (g) {
                                $(".temizliklistesi-"+getir).html(g);

                            });

                        }
                    });
                }

            });

            $(".temizlikupdate").on("click", function () {
                //form reset-->

                var gonderilenform = $("#formizin").serialize();
                var getir ="<?php echo $temizlik["protokol_no"]; ?>";

                //document.getElementById("formrefakatup").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=temizlikupdate',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        $.get("ajax/yatis/yatislistesi.php?islem=temizliklistesi", {getir: getir}, function (g) {
                            $(".temizliklistesi-<?php echo $temizlik["protokol_no"]; ?>").html(g);

                        });
                        $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {getir:getir}, function (ec) {
                            $("#temizbodyupdate-<?php echo $temizlik["protokol_no"]; ?>").html(ec);
                        });


                    }
                });

            });

            $(".temizlikdelet").click(function () {
                var id = $(this).data('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                    "<input class='form-control' type='text' id='delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#delete_detail').val();
                    var getir ="<?php echo $temizlik["protokol_no"]; ?>";
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=temizlikdelete',
                        data: {id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            $.get("ajax/yatis/yatislistesi.php?islem=temizliklistesi", {getir: getir}, function (g) {
                                $(".temizliklistesi-<?php echo $temizlik["protokol_no"]; ?>").html(g);
                                $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {getir:getir}, function (ec) {
                                    $("#temizbodyupdate-<?php echo $temizlik["protokol_no"]; ?>").html(ec);
                                });
                            });

                            $('.alertify').remove();
                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});

            });
            $(".temizlikyeni").click(function () {
                var getir ="<?php echo $temizlik["protokol_no"]; ?>";
                $('.aktif-satir').removeClass('aktif-satir');

                //$.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir: getir}, function (getVeri) {
                //    $("#izinlibodyupdate-<?php //echo $temizlik["protokol_no"]; ?>//").html(getVeri);
                //
                //});
                $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {getir:getir}, function (ec) {
                    $("#temizbodyupdate-<?php echo $temizlik["protokol_no"]; ?>").html(ec);
                });
            });

        });
    </script>


<?php }

elseif ($islem == "yatisemanet") {

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);

//    if ($yatis['mother_tc_identity_number']==''){
//        $patients = singular("patients", "tc_id", $yatis['tc_kimlik_no']);
//    }else{
//        $ANNETC=$yatis['mother_tc_identity_number'];
//        $DOGUMSiRA=$yatis['birth_order'];
//        $patients = tek("SELECT * FROM patients WHERE mother_tc_identity_number='$ANNETC' AND birth_order='$DOGUMSiRA'");
//    }
    $patients = singular("patients", "id", $hastakayit['patient_id']);
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
     ?>

    <div class="modal-dialog modal-xxl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">EMANET iŞLEMLERI-<?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row" >
                <div class="col-md-5 px-3 pt-1 pb-1">
                    <div class="card" >
                        <div class="card-header " style="height: 4vh;">Oluşturulan Emanetler</div>
                        <div class="">
                            <div id="emanettablelistesi-<?php echo $protokolno; ?>">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 ">
                    <div class="card">
                        <div class="card-header" style="height: 4vh;">
                            <div class="col-12 row">
                                <div class="col-md-9 ">
                                    <h5 style="font-size: 13px;">Hasta Emanet Kayıt</h5>
                                </div>
                                <div class="col-md-3 " align="right">
                                    <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                </div>
                            </div>
                        </div>
                        <div class="mx-3">
                            <div class="emanetbody-<?php echo $protokolno; ?>">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <script type="text/javascript">
                var getir ="<?php echo $_GET["getir"]; ?>";
                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {
                    $(".emanetbody-<?php echo $protokolno; ?>").html(g);
                });

                $.get("ajax/yatis/yatislistesi.php?islem=emanetlistesi", {getir:getir}, function (e) {
                 $("#emanettablelistesi-<?php echo $protokolno; ?>").html(e);
                });

            </script>


        </div>
    </div>

<?php }
elseif ($islem=="emanetbodygeri"){
   if ($_GET["emanetid"]!=''){
       $emanet = singular("patient_trust", "id", $_GET["emanetid"]);
       $hastakayit = singular("patient_registration", "protocol_number", $emanet["patient_id"]);
       $patients = singular("patients", "id", $hastakayit['patient_id']);
   }else{
       $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);
       $patients = singular("patients", "id", $hastakayit['patient_id']);
   }

    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);

   $borç = hastaborcsorgula($hastakayit['protocol_number']);

      ?>


    <form id="formhsremanet" action="javascript:void(0);">
        <div class="modal-body">
            <div class="row mb-1">
                <div class="col-md-6">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label col-md-3" title="Emanet Alma Tarihi">E. A. Tarihi</label>
                        <div class="col-md-9">
                            <input type="datetime-local" class="form-control" value="<?php if ($emanet['trust_datetime']!=''){ echo $emanet['trust_datetime']; }else{ echo $simdikitarih; }?>" name="trust_datetime" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label col-md-3" title="Emanet Teslim Tarihi">E. T. Tarihi</label>
                        <div class="col-md-9">
                            <input type="datetime-local" class="form-control " value="<?php if ($emanet['trust_delivery_datetime']!=''){echo $emanet['trust_delivery_datetime']; } ?>" name="trust_delivery_datetime" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mb-1">
                <div class="col-md-6">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label col-md-3" title="Hasta Protokol No">H. P. No</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" disabled value="<?php echo $hastakayit['protocol_number'] ?>"
                                   id="basicpill-firstname-input">
                            <input type="hidden" class="form-control" name="patient_id" value="<?php echo $hastakayit["protocol_number"] ?>"
                                   id="basicpill-firstname-input">
                            <?php if ($emanet['id']){ ?>
                                <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["emanetid"] ?>"
                                       id="basicpill-firstname-input">
                           <?php  }?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label col-md-3" title="Hasta T.C. No">H. T.C. No</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" disabled value="<?php echo $patients['tc_id'] ?>"
                                   id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-md-6">
                    <div class="row">
                        <label for="basicpill-firstname-input" class="form-label col-md-3" title="Hasta Emanet Adi">Emanet Adi</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control emanetempty" name="escrow_name" value="<?php echo $emanet['escrow_name']; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label class="form-label col-md-3" title="Emanet Veren">E. Veren</label>
                        <div class="col-md-9">
                            <select class="form-select emanetempty" name="trust_user" >
                                <option value="">Emanet veren seçiniz..</option>
                                <?php $bolumgetir = "select * from transaction_definitions WHERE status='1' AND definition_type='EMANET_VEREN'" ;
                                $hello=verilericoklucek("$bolumgetir");
                                foreach ((array) $hello as $value) {  ?>

                                    <option <?php if($emanet['trust_user']==$value["id"]) echo"selected"; ?>
                                            value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>

                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <label for="basicpill-firstname-input" class="form-label col-md-2" title="Emanet Açiklama">E. Açiklama</label>
                <div class="col-md-9" >
                    <textarea class="col-md-12 emanetempty" name="escrow_description" rows="2" ><?php echo $emanet['escrow_description']; ?></textarea>
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn kapat-btn btn-sm" class="btn-close" data-bs-dismiss="modal">Kapat</button>

            <?php
            $emanetisleminsert=yetkisorgula($kullanicid, "emanetisleminsert");
            $emanetlemupdate=yetkisorgula($kullanicid, "emanetlemupdate");
            $emanetlemdelete=yetkisorgula($kullanicid, "emanetlemdelete");
            $emanetislemdelivery=yetkisorgula($kullanicid, "emanetislemdelivery");
            if($_GET["emanetid"]!=''){
                if ($emanetisleminsert==1){ ?>
                <input class="btn yeni-btn w-md justify-content-end  btn-sm  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';} else { echo 'btnyeniden'; } ?>
" style="margin-bottom:4px"
                       type="submit" data-id="<?php echo $emanet['protocol_number']; ?> " value="Yeni"/>
                    <?php } if ($emanetlemdelete==1){ ?>
                <input class="btn sil-btn w-md justify-content-end  btn-sm  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';} else { echo 'btnhastaemanetsilme'; } ?>
" style="margin-bottom:4px"
                       type="submit" data-id="<?php echo $_GET["emanetid"] ?>" value="Silme"/>
                <?php } if ($emanetislemdelivery==1){ ?>
                <button class="btn kps-btn  hastemanetonay  mx-2 btn-sm" style="margin-bottom:4px" type="submit" data-id="<?php echo $_GET["emanetid"] ?>" >Teslim Et
                </button>
                <?php } if ($emanetlemupdate==1){?>
                <input class="btn up-btn w-md justify-content-end  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';} else { echo 'btnhastaemanetup'; } ?>
" style="margin-bottom:4px"
                        type="submit"  value="Güncelle"/>
           <?php }
                }
                else{
                    if ($emanetisleminsert==1){ ?>
                <input class="btn yeni-btn btn-sm w-md justify-content-end   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';} else { echo 'btnyeniden'; } ?>
" type="submit" data-id="<?php echo $_GET["getir"]; ?> " value="Yeni"/>
                        <?php } if ($emanetisleminsert==1){ ?>
                <input class="btn up-btn btn-sm w-md justify-content-end  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';} else { echo 'btnhastaemanet'; } ?>
"  type="submit"  value="Kaydet"/>
          <?php }
                    } ?>


        </div>
    </form>

    <script>

        $(document).ready(function () {
            $(".btnhastaemanet").on("click", function () {
                //form reset-->

                var say=0;
                $('.emanetempty').filter(function() {
                    if (this.value==''){
                        say++;
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formhsremanet").serialize();

                    var getir ="<?php echo $_GET["getir"]; ?>";
                    document.getElementById("formhsremanet").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=hastaemanetekle',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            $.get("ajax/yatis/yatislistesi.php?islem=emanetlistesi", {getir:getir}, function (e) {
                                $("#emanettablelistesi-<?php echo $protokolno; ?>").html(e);

                            });
                        }
                    });
                }
            });

            $(".btnyeniden").click(function () {
                var getir = $(this).data('id');
                //alert(getir);
                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {
                    $(".emanetbody-<?php echo $protokolno; ?>").html(g);
                });
            });
        });

            $(".btnhastaemanetup").on("click", function () {
                //form reset-->
                var getir = "<?php echo $emanet['patient_id']; ?>";
                var gonderilenform = $("#formhsremanet").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=hastaemanetguncelle',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);


                        $.get("ajax/yatis/yatislistesi.php?islem=emanetlistesi", {getir:getir}, function (e) {
                            $("#emanettablelistesi-<?php echo $protokolno; ?>").html(e);

                            $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {

                                $(".emanetbody-<?php echo $protokolno; ?>").html(g);

                            });

                        });
                    }
                });

            });

            $(".btnhastaemanetsilme").click(function () {

                var id = $(this).data('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#delete_detail').val();

                    var getir ="<?php echo $emanet['patient_id']; ?>";
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=emanetsilme',
                        data:{id:id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            $.get("ajax/yatis/yatislistesi.php?islem=emanetlistesi", {getir:getir}, function (e) {
                                $("#emanettablelistesi-<?php echo $protokolno; ?>").html(e);

                                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {

                                    $(".emanetbody-<?php echo $protokolno; ?>").html(g);

                                });
                                $('.alertify').remove();
                            });

                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});

                $("#deneme123").trigger("click");



            });


            $(".btnyeniden").click(function () {
                var getir = $(this).data('id');
                //alert(getir);
                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {
                    $(".emanetbody-<?php echo $protokolno; ?>").html(g);
                });
            });

            $(".hastemanetonay").on("click", function () {
                var getir = "<?php echo $emanet['patient_id']; ?>";
                var emanetid =$(this).data('id');
                var teslimtarih ="<?php echo $simdikitarih; ?>";
                // var teslimtarih = $('input[name="trust_delivery_datetime"]').val();
                alert(teslimtarih);

                $.get("ajax/yatis/yatisislem.php?islem=yatisemanetonayla", {emanetid:emanetid,teslimtarih:teslimtarih}, function (e) {
                    $('#sonucyaz').html(e);

                    $.get("ajax/yatis/yatislistesi.php?islem=emanetlistesi", {getir:getir}, function (e) {
                        $("#emanettablelistesi-<?php echo $protokolno; ?>").html(e);

                        $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:getir}, function (g) {

                            $(".emanetbody-<?php echo $protokolno; ?>").html(g);

                        });

                    });

                });

            });



    </script>
<?php }

elseif ($islem == "evrakislem") {
$protokolno= $_GET["protokolno"];
//    $yatis = singular("yatis", "yatis_protokol", $_GET["protokolno"]);
//    $protokolgetirme=$yatis["yatis_protokol"];
//    if ($yatis['mother_tc_identity_number']==''){
//        $patients = singular("patients", "tc_id", $yatis['tc_kimlik_no']);
//    }else{
//        $ANNETC=$yatis['mother_tc_identity_number'];
//        $DOGUMSiRA=$yatis['birth_order'];
//        $patients = tek("SELECT * FROM patients WHERE mother_tc_identity_number='$ANNETC' AND birth_order='$DOGUMSiRA'");
//    }
    $hastakayit = singular("patient_registration", "protocol_number", $protokolno);
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $patients = singular("patients", "id", $hastakayit['patient_id']);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->

        <div class="modal-content">
            <div >
                <div class="modal-header">
                    <div class="col-md-11">
                        <div class="row modal-title px-2  text-white">
                            <div class="col-md-9 ">
                                <h5 class="pt-2">EVRAK iŞLEMLERi - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>
                            </div>
                            <div class="col-md-3" align="right">
                                <p class="pt-2">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1" align="right">
                        <button type="button" class="btn-close btn-close-white w-20" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;
                left:50%;padding:2px;"><img src='../loading/demo_wait.gif' width="64" height="64" /><br>Loading..</div>
                <script>
                    $(document).ajaxStart(function(){
                        $("#wait").css("display", "block");
                    });
                    $(document).ajaxComplete(function(){
                        $("#wait").css("display", "none");
                    });
                </script>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" >
                                <div class="row card-title">
                                    <div class="col-md-6">
                                        <h5 class=" p-2  text-white" >Hasta Evrak Listesi</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div align="right" class="mt-2">
                                            <?php if ($taburcu['discharge_status']==1){ ?>
                                                <button type="button" class="btn btn-primary btn-sm alerttaburcu mx-3 mb-2" >
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </button>
                                            <?php }
                                            else if ($izin['id']!=''){ ?>
                                                <button type="button" class="btn btn-primary btn-sm alertizin mx-3 mb-2" >
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </button>
                                            <?php }
                                            else{ ?>
                                                <button type="button" class="btn yeni-btn btn-sm evrakyeniden mx-3 mb-2" id="<?php echo $_GET["getir"]; ?>">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </button>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="mx-3 evraklistesi-<?php echo $protokolno; ?>" <?php if ($taburcu['discharge_status']==1 || $izin['id']!=''){ ?> style="pointer-events:none;" <?php } ?>>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" >
                                <h5 class="card-title p-2  text-white" >Hasta Evrak Formu</h5>
                                <div class="evrakbody-<?php echo $protokolno; ?>" style="margin-left:10px ">

                                </div>
                            </div>
                            <div id="loading"></div>
                        </div>


                        <script>



                            $(document).ready(function () {


                                var taburcumu="<?php echo $taburcu['discharge_status']; ?>";
                                if(taburcumu==1){
                                    $(".islemdisabled").prop('disabled', true);
                                }
                                var getir = "<?php  echo $protokolno ?>";
                                $.get("ajax/yatis/yatislistesi.php?islem=evraklistesi", {getir: getir}, function (g) {
                                    $(".evraklistesi-<?php echo $protokolno; ?>").html(g);
                                });
                                $.get("ajax/yatis/yatismodalbody.php?islem=evrakmodalbody", {getir: getir}, function (ge) {
                                    $(".evrakbody-<?php echo $protokolno; ?>").html(ge);
                                });

                                $(".evrakyeniden").click(function(){
                                    $.get("ajax/yatis/yatismodalbody.php?islem=evrakmodalbody", {getir: getir}, function (get) {
                                        $(".evrakbody-<?php echo $protokolno; ?>").html(get);
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php }

elseif ($islem == "hastabilgi") {
    $protokolno = $_GET['protokolno'];
    $HASTAKAYiTGETiR = singularactive("patient_registration", "protocol_number", $_GET["protokolno"]);
    $hasta_protokol=$HASTAKAYiTGETiR['protocol_number'];
    $hasta_id=$HASTAKAYiTGETiR['patient_id'];

    if($HASTAKAYiTGETiR["baby"]!=0){
        $patientsGETiR = tek("SELECT * FROM patients WHERE mother_tc_id='{$HASTAKAYiTGETiR['patient_id']}'
                         AND birth_order='{$HASTAKAYiTGETiR['birth_order']}' ");
        $hastaadisoyadi=$patientsGETiR["patient_name"] . " " . $patientsGETiR["patient_surname"];
    }else{

        $patientsGETiR=singularactive("patients","id",$HASTAKAYiTGETiR["patient_id"]);

        $hastaadisoyadi=$patientsGETiR["patient_name"] . " " . $patientsGETiR["patient_surname"];
    }

    $HEKiM = singularactive("users", "id", $HASTAKAYiTGETiR["doctor"]);

    $POLiKLiNiK = singularactive("units","id", $HASTAKAYiTGETiR["outpatient_id"]);



    $simdi = explode(" ", $simdikitarih);
    $simditarih = $simdi['0'];
    $dogum=$patientsGETiR['birth_date'];
    $YAS = ikitariharasindakiyilfark($simdikitarih,$dogum);

    $protokolno=$HASTAKAYiTGETiR['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>

    <?php
//    $uniq_rand = uniqid();
    $uniq_rand = $hasta_protokol;
    ?>
    <script>
        var unikey="<?php echo $hasta_protokol; ?>";

    </script>

    <div class="modal fade " id="modal-<?php echo $uniq_rand; ?>">
        <div class="modal-dialog modal-xxl" id="modal-tanim-icerik-<?php echo $uniq_rand; ?>">Ana Modal</div>
    </div>



<div class="row" style="margin-left:2px">
    <div class="col-xl-12 bgSoftBlue">
        <div class="row ">
            <div class="col-xl-6 headerBorder">
                <h6 class=" mb-0 text-center mt-1">Liste İşlemleri</h6>
                <div  class="d-flex justify-content-evenly my-1">
                    <div class="text-center">
                        <button class="bg-transparent border-0 " onclick="get_refakat('<?php echo $hasta_protokol ?>')">
                            <i class="fa-light fa-hospital-user fa-2x"></i>
                            <div class="fw-500 fsLaptop">Refakatçı</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button  class="bg-transparent border-0 get_izinli" data-id="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">

                            <i class="fa-light fa-person-circle-check fa-2x"></i>
                            <div class="fw-500 fsLaptop">İzinli</div>
                         </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 get_temizlik" data-id="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">

                            <i class="fa-light fa-bed-front fa-2x"></i>
                            <div class="fw-500 fsLaptop">Yatak Temizlik</div>
                        </button>
                    </div>
                    <div class="text-center " >
                        <button class="bg-transparent border-0 olumformu" onclick="get_olum('<?php echo $hasta_protokol ?>')">
                            <i class="fa-light fa-book-skull fa-2x"></i>
                            <div class="fw-500 fsLaptop">Ölüm Raporu</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 taburcu" onclick="get_taburcu('<?php echo $hasta_protokol ?>')"  >

                            <i class="fa-light fa-wheelchair fa-2x"></i>
                            <div class="fw-500 fsLaptop">Taburculuk</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 dissevk" data-id="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">

                            <i class="fa-light fa-person-ski-lift fa-2x"></i>
                            <div class="fw-500 fsLaptop">Sevk</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 hastaemanet" data-id="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">

                            <i class="fa-light fa-user-shield fa-2x"></i>
                            <div class="fw-500 fsLaptop">Hasta Emanet</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <div class="easyui-panel border-0"  style="background-color:#DBE2EF;overflow:unset">
                            <button class="bg-transparent border-0 easyui-menubutton"   data-options="menu:'#mm-<?php echo $uniq_rand; ?>'">
                                <i class="fa-light fa-file-waveform fa-xl"></i>

                            </button>
                        </div>
                        <div id="mm-<?php echo $uniq_rand; ?>" >
                            <div data-options="iconCls:'fa-light fa-bed-pulse'"
                                 class="menu-<?php echo $uniq_rand; ?>" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">YB İzlem Formu</div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastaozellikliizlem" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Özellikli İzlem Formu</div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastaguvenlicerrahiadsh" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Güvenli Cerrahi Kontrol Listesi ADSH </div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastaguvenlicerrahi" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Güvenli Cerrahi Kontrol Listesi</div>
                            <div data-options="iconCls:'fal fa-paste'" class="hastaharizmiislem" data-id="<?php echo $hasta_protokol ?>">Harizmi II Düşme Riski Ölçeği</div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastaitakiislem" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">İtaki II Düşme Riski Ölçeği</div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastanutrisyon" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">NütrisyoneL Risk Skoru</div>
                            <div data-options="iconCls:'fal fa-paste'"
                                 class="hastastrong" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Strong Kids Çocuk Nutrisyon</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 yatakhareket" data-id="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">
                            <i class="fa-light fa-bed-pulse fa-2x"></i>
                            <div class="fw-500 fsLaptop">Yatak Hareketleri</div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 ">
                <h6 class=" mb-0 text-center mt-1">Hasta İşlemleri</h6>
                <div class="d-flex justify-content-evenly my-1">
                    <div class="text-center">
                        <button class="bg-transparent border-0 tanigetir" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" protokolno="<?php echo $hasta_protokol ?>"
                                tip="<?php echo islemtanimgetirname("Klinik"); ?>">

                            <i class="fa-light fa-user-doctor-message fa-2x"></i>
                            <div class="fw-500 fsLaptop">Tanı</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 epikriz" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" protokolno="<?php echo $hasta_protokol ?>">

                            <i class="fa-light fa-clipboard-medical fa-2x"></i>
                            <div  class="fw-500 fsLaptop">Epikriz</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 anamnez" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" protokolno="<?php echo $hasta_protokol ?>"
                                data-servis="<?php echo $HASTAKAYiTGETiR["service_id"] ?>" data-hekim="<?php echo $HASTAKAYiTGETiR["service_doctor"] ?>">

                            <i class="fa-light fa-clipboard-question fa-2x"></i>
                            <div class="fw-500 fsLaptop">Anamnez</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 kanbankasiislem" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" patient_id="<?php echo $HASTAKAYiTGETiR["patient_id"] ?>" protokolno="<?php echo $hasta_protokol ?>">

                            <i class="fa-light fa-kidneys fa-2x"></i>
                            <div class="fw-500 fsLaptop">Kan Bank. İstem</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 konsultasyonislem" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" patient_id="<?php echo $HASTAKAYiTGETiR["patient_id"] ?>" protokolno="<?php echo $hasta_protokol ?>">
                            <i class="fa-light fa-people-arrows fa-2x"></i>
                            <div class="fw-500 fsLaptop">Konsültasyon</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <div class="easyui-panel border-0"  style="background-color:#DBE2EF;overflow:unset">
                            <button class="bg-transparent border-0 easyui-menubutton"   data-options="menu:'#mm2'">
                                <i class="fal fa-paste fa-xl"></i>
                            </button>
                        </div>
                        <div id="mm2" >
                            <div  data-options="iconCls:'fa-light fa-flask-vial'"
                                 class=" labislem1" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Laboratuvar Sonuç</div>
                            <div data-options="iconCls:'fa-light fa-radiation'"
                                 class="hastaozellikliizlem" data-id="<?php echo $hasta_protokol ?>"
                                 data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">Röntgen Sonuç</div>

                        </div>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 evrakislem" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal" protokolno="<?php echo $hasta_protokol ?>">

                            <i class="fa-light fa-folder-open fa-2x"></i>
                            <div class="fw-500 fsLaptop">Evraklar</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 hemsiregozlem" protokolno="<?php echo $hasta_protokol ?>" data-bs-target="#modal-<?php echo $uniq_rand; ?>" data-bs-toggle="modal">
                            <i class="fa-light fa-scarecrow fa-2x"></i>
                            <div class="fw-500 fsLaptop">Skor İşlemleri</div>
                        </button>
                    </div>
                    <div class="text-center">
                        <button class="bg-transparent border-0 " protokolno="<?php echo $hasta_protokol ?>" >
                            <a href="/" class="text-dark"><i class="fa-light fa-hospital fa-2x"></i></a>
                            <div class="fw-500 fsLaptop">Çıkış</div>
                        </button>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $(".hemsiregozlem").click(function () {
                var protokolno = $(this).attr('protokolno');
                // $.get("ajax/skor_hesaplama.php?islem=skor_hesaplama", {protokolno: protokolno}, function (getVeri) {
                //     $('#modal-tanim-icerik').html(getVeri);
                //
                // });

                // $.get("ajax/skor_hesaplama.php?islem=glaskow_koma_skoru", {protokolno: protokolno}, function (getVeri) {
                //     $('#modal-tanim-icerik').html(getVeri);
                //
                // });

                // $.get("ajax/skor_hesaplama.php?islem=saps_skoru", {protokolno: protokolno}, function (getVeri) {
                //     $('#modal-tanim-icerik').html(getVeri);
                // });

                // $.get("ajax/skor_hesaplama.php?islem=prism_skoru", {protokolno: protokolno}, function (getVeri) {
                //     $('#modal-tanim-icerik').html(getVeri);
                // });


                $.get("ajax/skor_hesaplama.php?islem=body_skoru", {protokolno: protokolno}, function (getVeri) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);
                });
            });




            $(".epikriz").click(function () {
                var protokolno = $(this).attr('protokolno');
                $.get("ajax/yatis/yatismodalbody.php?islem=epikrizbody", {protokolno: protokolno}, function (getVeri) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

                });
            });

            $(".evrakislem").click(function () {
                var protokolno = $(this).attr('protokolno');
                $.get("ajax/yatis/yatismodalbody.php?islem=evrakislem", {protokolno: protokolno}, function (getVeri) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

                });
            });

            $(".anamnez").click(function () {
                var protokol = $(this).attr('protokolno');
                var servis = $(this).data('servis');
                var hekim = $(this).data('hekim');
                $.get("ajax/anamnezislem.php?islem=anamnezbody", {protokol: protokol,servis: servis,hekim: hekim}, function (getVeri) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

                });
            });


            $(".tanigetir").click(function () {
                var protokolno = $(this).attr('protokolno');
                // var tcno = $(this).attr('tcno');
                var tip = $(this).attr('tip');
                var modul='yatis'
                // var yatisprotokolno2 = $(this).attr('yatisprotokolno');

                $.get("ajax/tani/tanilistesi.php?islem=tanilistesigetir", {protokolno: protokolno,tip: tip,modul:modul}, function (e) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(e);
                });

            });


            $(".kanbankasiislem").click(function () {
                var protokolno = $(this).attr('protokolno');
                var patient_id = $(this).attr('patient_id');
                $.get("ajax/kan-merkezi/kanmerkezimodallar.php?islem=kan-taleb", {protokolno: protokolno,patient_id:patient_id}, function (getVeri) {
                    $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);
                });
            });
        });
    </script>
<!--    <div class="Ana_windows---><?php //echo $protokolno; ?><!--"  id="Ana_windows---><?php //echo $protokolno; ?><!--"></div>-->


    <script>
        // $('.Ana_windows-'+unikey).window({
        //     onClose: function () {
        //         $('.Ana_windows-'+unikey).html("");
        //     },
        //     cache: true,
        //     modal: true,
        //     fit: true,
        //     closed: true,
        //     iconCls: 'icon-save',
        // });

        function get_refakat(id) {
            var win=$('.windows-<?php echo $hastaya_uniqid; ?>');
            win.window('setTitle', 'Refakatçı İşlemleri');

            $('#windows-<?php echo $hastaya_uniqid; ?>').window('open');
            $('#windows-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/yatis/yatismodalbody.php?islem=yatisrefakatci&getir='+id+'');
        }

        function get_olum(id) {
            var win=$('.windows50-<?php echo $hastaya_uniqid; ?>');
            win.window('setTitle', 'Ölüm Formu');

            $('#windows50-<?php echo $hastaya_uniqid; ?>').window('open');
            $('#windows50-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/yatis/yatismodalbody.php?islem=olumformbody&getir='+id+'&hastaya_uniqid=<?php echo $hastaya_uniqid; ?>');
        }

        function get_taburcu(id) {
            var win=$('.windows50-<?php echo $hastaya_uniqid; ?>');
            win.window('setTitle', 'Taburcu İşlem Formu');

            $('#windows50-<?php echo $hastaya_uniqid; ?>').window('open');
            $('#windows50-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/yatis/yatismodalbody.php?islem=taburcubody&getir='+id+'&hastaya_uniqid=<?php echo $hastaya_uniqid; ?>');
        }


    </script>

    <script type="text/javascript">


        function arasinial_js(str, birinci, ikinci) {
            var boluma = str.split(birinci);
            var bolum = boluma[1].split(ikinci);
            return bolum[0];
        }




        $(".konsultasyonislem").click(function () {
            var getir = $(this).attr('protokolno');
            $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonbody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".get_izinli").click(function () {
            var getir = $(this).data('id');

            $.get("ajax/yatis/yatismodalbody.php?islem=yatisizinli", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".get_temizlik").click(function () {
            var getir = $(this).data('id');

            $.get("ajax/yatis/yatismodalbody.php?islem=yataktemizlik", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastaemanet").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/yatis/yatismodalbody.php?islem=yatisemanet", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        //$("body").off("click", ".hastayogumbakimizlem").on("click", ".hastayogumbakimizlem", function(e){
        $(".menu-<?php echo $uniq_rand; ?>").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/yogum-bakim-izlem/yogum_bakim_izlem.php?islem=yogumbakimizlembody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);
            });
        });

        $(".hastaozellikliizlem").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/ozellikli_izlem/ozellikli_izlem.php?islem=ozellikliizlembody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastaharizmiislem").click(function () {
            var protokolno = $(this).data('id');
            //alert(getir);

            var win=$('.windowsfull-<?php echo $hastaya_uniqid; ?>')
            win.window('setTitle', 'Harizmi Düşme Risk Formu');

            $('#windowsfull-<?php echo $hastaya_uniqid; ?>').window('open');
            $('#windowsfull-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/harizmi_2_dusme_riski_olcegi/harizmi_2_dusme_riski_olcegi.php?islem=harizmiolcek2body&getir='+protokolno+'');


        });

        $(".hastaitakiislem").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/harizmi_2_dusme_riski_olcegi/itaki_2_dusme_riski_olcegi.php?islem=itakiolcekbody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastanutrisyon").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/nutrisyonel_risk_skoru/nutrisyonel_risk_skoru.php?islem=nutrisyonelbody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastastrong").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/nutrisyonel_risk_skoru/strong_kids_cocuk_nutrisyon.php?islem=strongbody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastaguvenlicerrahi").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi.php?islem=guvenlicerrahibody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".hastaguvenlicerrahiadsh").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/guvenli-cerrahi/guvenli-cerrahi-adsh.php?islem=guvenlicerrahibody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

        $(".yatakhareket").click(function () {
            var getir = $(this).data('id');
            //alert(getir);
            $.get("ajax/yatis/yatak_hareketleri_liste.php?islem=yatakhareketibody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);
            });
        });

        //$(".refakatcibodyguncelle").click(function () {
        //    var getir = $(this).attr('id');
        //    $.get("ajax/yatis/yatisislem.php?islem=refakatciguncelle", {getir: getir}, function (getVeri) {
        //        $("#modal-tanim-icerik-<?php //echo $uniq_rand; ?>//").html(getVeri);
        //
        //    });
        //});

        //$(".taburcu").click(function () {
        //    var getir = $(this).data('id');
        //    $.get("ajax/yatis/yatismodalbody.php?islem=taburcubody", {getir: getir}, function (getVeri) {
        //        $("#modal-tanim-icerik-<?php //echo $uniq_rand; ?>//").html(getVeri);
        //
        //    });
        //});


        //$(".olumformu").click(function () {
        //    var getir = $(this).data('id');
        //    $.get("ajax/yatis/yatismodalbody.php?islem=olumformbody", {getir: getir}, function (getVeri) {
        //        $("#modal-tanim-icerik-<?php //echo $uniq_rand; ?>//").html(getVeri);
        //
        //    });
        //});

        $(".dissevk").click(function () {
            var getir = $(this).data('id');
            $.get("ajax/yatis/yatismodalbody.php?islem=dissevkbody", {getir: getir}, function (getVeri) {
                $("#modal-tanim-icerik-<?php echo $uniq_rand; ?>").html(getVeri);

            });
        });

    </script>
     <div class="row" style="margin-left: 0px;padding-left: 0px; padding-right: 0px;">
         <div class="easyui-accordion" style="width:100%;margin:0px;padding:0px;">
             <div title="Hasta Detay: <?php echo ucfirst(strtolower($hastaadisoyadi)) ?>" data-options="iconCls:'icon-ok'" style="overflow:unset;padding:10px;">
                <div style="display: flex">
                    <div class="row">
                        <div id="p1" class="easyui-panel" title="Hasta Fotoğraf" style="width:100%;padding:5px">
                            <div id="live_camera">
                                <img id="sonfotograf"
                                     src="<?php if ($patientsGETiR["photo"] != '') {
                                         echo $patientsGETiR["photo"];
                                     } else {

                                         if ($patientsGETiR["gender"] == 'E') {
                                             echo "assets/img/dummy-user.jpeg";
                                         } elseif ($patientsGETiR["gender"] == 'K') {
                                             echo "assets/img/bdummy-user.jpeg";
                                         }
                                     } ?>"
                                     alt="dummy user" width="100px" height="100px">
                            </div>
                            <div class="row col-xl-12" align="center">
                                <div onclick="cift_tikla()"  class="col-xl-6" >
                                    <i class="fa-light fa-camera-retro " ></i>
                                </div>
                                <div id="gizle" style="display: block;" class="col-xl-6" align="center">
                                    <div onclick="capture_web_snapshot()">
                                        <i class="fa-light fa-user-check " ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="p" class="easyui-panel" title="Hasta Bilgileri" style="width:100%;padding:5px">
                            <div class="row">
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Hasta Adı</label>
                                    <input class="form-control  hasta-bilgi-input" type="text" disabled
                                           value="<?php echo ucwords($patientsGETiR["patient_name"] . " " . $patientsGETiR["patient_surname"]) ?> (<?php echo $YAS ?>)"
                                    >
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Kur Kodu</label>
                                    <?php $rowkuruk = $patientsGETiR["social_assurance"];
                                    $SOSYALGUVENCE = tek("SELECT * FROM transaction_definitions WHERE definition_code='{$patientsGETiR["social_assurance"]}'");
                                    $KURUMGETiR = tek("SELECT * FROM transaction_definitions WHERE definition_code='{$patientsGETiR["institution"]}'");
                                    ?>

                                    <input class="form-control hasta-bilgi-input" type="text" disabled
                                           value="<?php echo $KURUMGETiR["definition_name"] . " / " . $SOSYALGUVENCE["definition_name"] ?>"
                                    >

                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">TC Kimlik No</label>
                                    <input class="form-control hasta-bilgi-input" type="text" disabled value="<?php echo $patientsGETiR["tc_id"] ?>">
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Protokol No</label>
                                    <input class="form-control hasta-bilgi-input" type="text"  disabled value=" <?php echo $HASTAKAYiTGETiR["protocol_number"]; ?>">
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Anne Adı</label>
                                    <input class="form-control hasta-bilgi-input" type="text" disabled value="<?php echo $patientsGETiR["mother"] ?>" >
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Takip No</label>
                                    <input class="form-control hasta-bilgi-input" type="text" disabled value="<?php echo $HASTAKAYiTGETiR["provision_number"] ?>">
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">D. Tarihi</label>
                                    <input class="form-control hasta-bilgi-input" type="text" disabled
                                           value="<?php
                                           $dogum_tarihi=nettarih($patientsGETiR["birth_date"]);
                                           $split = explode(" ",$dogum_tarihi);
                                           echo $split[0];

                                           ?>" >
                                </div>
                                <div class="col-xl-6 d-flex align-items-center mb-1">
                                    <label class="col-xl-3">Yatış Tarihi</label>
                                    <input class="form-control hasta-bilgi-input" type="text" disabled value="<?php echo nettarih($HASTAKAYiTGETiR["hospitalized_accepted_datetime"]); ?>">
                                </div>
                                <div class="col-xl-6 d-flex align-items-center ">
                                    <label class="col-xl-3">Kan Grubu</label>
                                    <input class="form-control hasta-bilgi-input" type="text" value="<?php $kan=$patientsGETiR["blood_group"];echo islemtanimgetirid($kan); ?>">
                                </div>
                                <div class="col-xl-6 d-flex align-items-center ">
                                    <label class="col-xl-3">Hasta Notu</label>
                                    <textarea  cols="40" rows="1"><?php echo $patientsGETiR["reminders"]?></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="p" class="easyui-panel" title="Yatış Detay" style="width:100%;padding:5px">
                            <div class="row hospitalizationDetail">
                                <div class="row">
                                    <div class="col-xl-6 d-flex align-items-center mb-1" >
                                        <input class="form-control" name="yatisyap[id]" type="hidden" value="<?php echo $HASTAKAYiTGETiR['id']; ?>">
                                        <label class="col-md-3">Birim</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php echo birimgetirid($HASTAKAYiTGETiR['service_id']) ?>">
                                    </div>
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Doktor</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php echo kullanicigetir($HASTAKAYiTGETiR['service_doctor']) ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Bina</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php $bina=singularactive("hospital_building","id",$HASTAKAYiTGETiR['building_id']); echo $bina['building_name'] ?>">
                                    </div>
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Kat</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php $bina=singularactive("hospital_floor","id",$HASTAKAYiTGETiR['floor_id']); echo $bina['floor_name'] ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Oda</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php $bina=singularactive("hospital_room","id",$HASTAKAYiTGETiR['room_id']); echo $bina['room_name'] ?>">
                                    </div>
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Yatak</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php $bina=singularactive("hospital_bed","id",$HASTAKAYiTGETiR['bed_id']); echo $bina['bed_name'] ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 d-flex align-items-center mb-1">
                                        <label class="col-md-3">Y. Türü  </label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php $bina=singularactive("transaction_definitions","id",$HASTAKAYiTGETiR['bed_type']); echo $bina['definition_name'] ?>">
                                    </div>
                                    <div class="col-xl-6 d-flex align-items-center mb-1" >
                                        <label class="col-xl-3">Yatış Günü</label>
                                        <?php
                                        $yatis_suresi=$HASTAKAYiTGETiR['hospitalized_accepted_datetime'];
                                        $günü=ikitariharasindakigunfark($simdikitarih , $yatis_suresi);
                                        ?>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php  echo 'Yatışın '.$günü.'. günü'; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 d-flex align-items-center mb-1" >
                                        <label class="col-xl-3" title="Yatış tahmini gün sayısı">Y. T. Gün</label>
                                        <input class="form-control hasta-bilgi-input" type="text" disabled
                                               value="<?php
                                               if (intval($HASTAKAYiTGETiR['hospitalization_day']>0)){
                                                   echo $HASTAKAYiTGETiR['hospitalization_day'].' gün';
                                               }else{
                                                   echo '0. gün';
                                               }
                                               ?>">
                                    </div>
                                </div>
                                <script>

                                    $("body").off("click", ".yatisislemup").on("click", ".yatisislemup", function(e){
                                        var gonderilenform = $("#formytisupdate").serialize();
                                        document.getElementById("formytisupdate").reset();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax/yatis/yatisislem.php?islem=yatisislemup',
                                            data: gonderilenform,
                                            success: function (e) {
                                                $('#sonucyaz').html(e);

                                            }
                                        });

                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="p" class="easyui-panel" title="Hastaya Eklenen Figurler" style="width:100%;padding:5px">
                            <div style="display: flex">
                            <?php
                            $bolumgetir = "select patient_figure.protocol_number as protokol_numarasi,figure_file as figur_adresi,figure_name as figur_adi
                                                        from patient_registration inner join patient_figure on patient_registration.protocol_number=patient_figure.protocol_number
                                                        where patient_registration.protocol_number='$hasta_protokol' and patient_registration.status=1 and patient_figure.status=1";

                            $hello=verilericoklucek($bolumgetir);
                            $sayi=0;
                            foreach ((array) $hello as $fi){ ?>

                                <div align="center" class="mt-1">
                                    <p class="mt-2 text-danger" style="font-size:10px">
                                        <img title="<?php echo $fi['figur_adi'] ?>" src="<?php echo $fi['figur_adresi'] ?>"  alt="yeni hasta" width="10%">
                                        <?php echo $fi['figur_adi'] ?>
                                    </p>
                                </div>

                            <?php  }  ?>
                        </div>
                        </div>
                    </div>
                </div>
             </div>
         </div>
         <script>
             function cift_tikla() {
                 var x = document.getElementById("gizle");
                 x.style.display = "block";
                 Webcam.set({
                     width: 100,
                     height: 100,
                     image_format: 'jpeg',
                     jpeg_quality: 90
                 });

                 Webcam.attach('#live_camera');
             }

             function capture_web_snapshot() {
                 Webcam.snap(function (site_url) {
                     $(".image-tag").val(site_url);
                     document.getElementById('live_camera').innerHTML =
                         '<img id="sonfotograf" width="95%;" src="'+site_url+'"/>';
                 });
             }
         </script>
     </div>

    <script>
        $("body").off("click", "#hasta-detay-toggle").on("click", "#hasta-detay-toggle", function (e) {


            if($(this).hasClass('collapsed')){

                $(this).html("<?php echo $hastaadisoyadi; ?> ");

                $("#dom-701").removeClass('border-dark');
                $("#dom-701").removeClass('border');

            }else {
                $("#dom-701").addClass('border-dark');
                $("#dom-701").addClass('border');
                $(this).html("<?php echo $hastaadisoyadi; ?>");
            }
        });
    </script>
    <div class="row">
        <?php
        $anamnez= singularactive("anamnesis", "protocol_id",$hasta_protokol);
       if($anamnez['drug_allergy']!=''){ ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Hastanın Alerjisi var!</strong>Hastanın <?php echo $anamnez['drug_allergy']; ?> alerjisi var.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>
    </div>
    <div class="card p-3">
        <ul class="nav nav-pills  row" id="pills-tab" role="tablist">
            <li class="nav-item col-xl-3" role="presentation">
                <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 active up-btn" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home<?php echo $random_sayi; ?>" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Geçmiş İşlemler</button>
            </li>
            <li class="nav-item col-xl-3" role="presentation">
                <?php
//                $islemtanimlari = singularactive("transaction_definitions", "definition_name",'LAB'); ?>
                <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 istemislem1<?php echo $random_sayi; ?>" hizmet_tur="<?php echo 'hep'; ?>" protokolno="<?php echo $hasta_protokol ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile<?php echo $random_sayi; ?>" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Hizmetler</button>
            </li>
            <li class="nav-item col-xl-3" role="presentation">
                <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 ilacsarfbtn<?php echo $random_sayi; ?>" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact<?php echo $random_sayi; ?>" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">İlaç Sarf</button>
            </li>
            <li class="nav-item col-xl-3" role="presentation">
                <button class="nav-link tablist<?php echo $random_sayi; ?> w-100 orderislem<?php echo $random_sayi; ?>" id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled<?php echo $random_sayi; ?>" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">E-Order</button>
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
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home<?php echo $random_sayi; ?>" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                geçmiş işlem Ekranı:Hastanın poliklinikte ilk ve önceki gelişine ait tanı/ön tanı, tetkik vb görüntüleme.
            </div>
            <div class="tab-pane fade" id="pills-profile<?php echo $random_sayi; ?>" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                <div class="hizmetbtn">
                 <?php
                 $islemtanimlarilab = singularactive("transaction_definitions", "definition_name",'LAB');
                 $islemtanimlarirad = singularactive("transaction_definitions", "definition_name",'RAD');

                 $laboratuvarinsert=yetkisorgula($kullanicid, "laboratuvarinsert");
                 $radyolojiinsert=yetkisorgula($kullanicid, "radyolojiinsert");
                 ?>
                    <div class="row">
                        <div class="col-md-10">
                           <?php if ($laboratuvarinsert==1){ ?>
                            <button type="button"  protokolno="<?php echo $hasta_protokol ?>" hizmet_tur="<?php echo 'lab'; ?>"
                                    class="btn hizmetclick  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; } else if ($izin['id']!=''){echo 'alertizin';} else { echo 'istemislem'.$random_sayi; } ?>"><i class="fas fa-vial"></i>&nbsp;
                                Laboratuvar</button>
                            <?php }
                           if ($radyolojiinsert==1){ ?>
                            <button type="button" ihsan="rad" protokolno="<?php echo $hasta_protokol ?>" hizmet_tur="<?php echo 'rad'; ?>"
                                    class="btn hizmetclick   <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; } else if ($izin['id']!=''){echo 'alertizin';} else { echo 'istemislem'.$random_sayi; } ?>"><i class="fas fa-radiation"></i>&nbsp;
                                Röntgen</button>
                            <?php } if ($radyolojiinsert==1){ ?>
                               <button type="button" ihsan="rad" protokolno="<?php echo $hasta_protokol ?>" hizmet_tur="<?php echo 'hizmet'; ?>"
                                       class="btn   hizmetclick btnhizmetrgb <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; } else if ($izin['id']!=''){echo 'alertizin';} else { echo 'istemislem'.$random_sayi; } ?>"><i class="fa-solid fa-chalkboard-user"></i>&nbsp;
                                   Hizmetler</button>
                            <?php } ?>



                            <script>
                                $(".hizmetclick").click(function () {
                                    $('.hizmetclick').removeClass("text-white");
                                    $('.hizmetclick').removeClass("up-btn");
                                    $(this).addClass("text-white");
                                    $(this).addClass("up-btn");

                                });
                            </script>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>

                </div>
                <div id="hastaistemlericerik<?php echo $hasta_protokol; ?>">

                </div>


            </div>
            <div class="tab-pane fade" id="pills-contact<?php echo $random_sayi; ?>" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                <div class="ilacsarfbody<?php echo $random_sayi; ?>">

                </div>
            </div>
            <div class="tab-pane fade" id="pills-disabled<?php echo $random_sayi; ?>" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">

            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $uniq_rand; ?>" protokolno="<?php echo $hasta_protokol ?>"
                    class="btn btn-primary   btn-sm tedavigiristedavigiris "><i class="fas fa-file-signature"></i>Tedavi giriş</button>


            <div class="orderlistesibody<?php echo $random_sayi; ?> mt-3">


            </div>

                <script>
                    $(document).ready(function () {
                        $(".orderislem<?php echo $random_sayi; ?>").click(function () {
                            var protokolno="<?php echo $hasta_protokol ?>";
                            $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {protokolno:protokolno}, function (getVeri) {
                                $('.orderlistesibody<?php echo $random_sayi; ?>').html(getVeri);
                            });
                        });

                        $(".ilacsarfbtn<?php echo $random_sayi; ?>").click(function () {
                            var protokolno="<?php echo $hasta_protokol ?>";
                            var hastalarid="<?php echo $hasta_id ?>";
                            $.get("ajax/ilac_sarf/ilacsarflistesi.php?islem=hasta_ilacsarfi", {protokolno:protokolno,hastalarid:hastalarid}, function (getVeri) {
                                $('.ilacsarfbody<?php echo $random_sayi; ?>').html(getVeri);
                            });
                        });

                        $(".tedavigiris<?php echo $random_sayi; ?>").click(function () {
                            var protokolno = $(this).attr('protokolno');
                            $.get("ajax/yatis/yatismodalbody.php?islem=tedavigirisbody", {protokolno: protokolno}, function (getVeri) {
                                $('#modal-tanim-icerik').html(getVeri);
                            });
                        });

                        $( "#order-tab<?php echo $random_sayi; ?>" ).one( "click", function() {
                            setTimeout(function () {
                                $('#tableorder').DataTable({
                                    "responsive": true
                                });
                            }, 500);

                        });



                    });


                </script>
            </div>
        </div>
        <script>
            $(".istemislem<?php echo $random_sayi; ?>").click(function () {

                var protokolno = $(this).attr('protokolno');
                var hizmet_tur = $(this).attr('hizmet_tur');

                $.get("ajax/istem/istemmodal.php?islem=hastayaeklenenhizmetler", {"protokolno": protokolno,"hizmet_tur":hizmet_tur}, function (getVeri) {
                    $('#hastaistemlericerik<?php echo $hasta_protokol; ?>').html(getVeri);
                });

            });
            $(".istemislem1<?php echo $random_sayi; ?>").click(function () {

                $('#hizmettablesec<?php echo $hasta_protokol ?>').DataTable().destroy();
                var protokolno = $(this).attr('protokolno');
                var hizmet_tur = $(this).attr('hizmet_tur');

                $.get("ajax/istem/istemmodal.php?islem=hastayaeklenenhizmetler", {"protokolno": protokolno,"hizmet_tur":hizmet_tur}, function (getVeri) {
                    $('#hastaistemlericerik<?php echo $hasta_protokol; ?>').html(getVeri);
                });
                $('.hizmetclick').removeClass("text-white");
                $('.hizmetclick').removeClass("up-btn");

            });

            // $(".rontgenislem").click(function () {
            //     var protokolno = $(this).attr('protokolno');
            //     $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getVeri) {
            //         $('#hastaistemlericerik').html(getVeri);
            //     });
            // });
        </script>
    </div>
</div>

    <style>
        .hasta-bilgi-input{
            padding:2px;
            padding-left:10px;
        }
    </style>

<?php }
elseif ($islem =="tedavisilmebody") {
    $id=$_GET['getir'];
    $order=singular("patient_order","id",$id);
    $yatis=singular("yatis","yatis_protokol",$order['patient_protokol']);
    $patients=singular("patients","tc_id",$yatis['tc_kimlik_no']);

    ?>

    <div class="modal-dialog modal-lg" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ORDER SiLME iŞLEMi - <?php echo $patients["patient_name"]." ".$patients["patient_surname"]; ?></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="javascript:void(0);" id="formoderdel">
                    <div class="mb-3">
                        <div class="alert alert-danger" role="alert">
                            Order iptal etmek için emin misiniz ?
                        </div>

                        <input class="form-control" name="id" type="text" value="<?php echo $_GET['getir']; ?>">


                    </div>
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Neden iptal etmek istediğinizi açiklar
                            misiniz ?</label>
                        <textarea class="form-control" name="delete_detail"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                        <button class="btn btn-success w-md justify-content-end btnordersilme" style="margin-bottom:4px"
                                type="submit" data-dismiss="modal" >iptal işlemini Onayla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {


            $(".btnordersilme").on("click", function () {
                var gonderilenform = $("#formoderdel").serialize();

                document.getElementById("formoderdel").reset();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=btnorderdelete',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $("#order-tab<?php echo $random_sayi; ?>").trigger("click");
                    }
                });

            });


        });

    </script>

<?php }
elseif ($islem=="tedaviuygulabody"){
    $id=$_GET['getir'];
    $order= singular("patient_order","id",$id);
    $yatis=singular("yatis","yatis_protokol",$order['patient_protokol']);
    $patients=singular("patients","tc_id",$yatis['tc_kimlik_no']);
    ?>

    <div class="modal-dialog modal-lg" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ORDER UYGULAMA iŞLEMi - <?php echo $patients["patient_name"]." ".$patients["patient_surname"]; ?></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formorderuygulama" action="javascript:void(0);" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mx-3">
                                <div class="mb-3">
                                    <label class="col-md-4">Hemşire</label>
                                    <select class="form-select" name="practitioner_nurse" >
                                        <option value="">Uygulayan hemşireyi seçiniz..</option>
                                        <?php $bolumgetir = 'select * from users WHERE status!=2 AND title=2069 ORDER BY name_surname ASC' ;

                                        $hello=verilericoklucek($bolumgetir);
                                        foreach ((array) $hello as $value){ ?>

                                            <option  <?php //if($cihaz['LABORATUVAR_iD']==$value["iD"]) echo"selected"; ?>
                                                    value="<?php echo $value["id"]; ?>" ><?php echo $value['name_surname']; ?></option>

                                        <?php  } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mx-3">
                                <div class="mb-3">
                                    <label for="basicpill-firstname-input" class="col-md-12">Uygulama Saati</label>
                                    <input class="form-control" type="time"  name="app_time" id="example-text-input">
                                    <input class="form-control" type="text"  name="order_id" value="<?php echo $id ?>" id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mx-3">
                                <div class="mb-3">
                                    <label for="basicpill-firstname-input" class="col-md-12">Uygulama Tarihi</label>
                                    <input class="form-control" type="date"  name="application_date" id="example-text-input">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <input type="submit"   value="Kaydet" class="btn btn-success  w-md justify-content-end tedaviuygulamainsert"/>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $(".tedaviuygulamainsert").on("click", function(){
                //form reset-->

                var gonderilenform = $("#formorderuygulama").serialize();

                document.getElementById("formorderuygulama").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/yatis/yatisislem.php?islem=tedaviuygulama',
                    data:gonderilenform,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        $("#order-tab<?php echo $random_sayi; ?>").trigger("click");
                    }
                });

            });

        });
    </script>

<?php }
elseif ($islem == "tedavigirisbody") {

    $yatis = singular("yatis", "yatis_protokol", $_GET["protokolno"]);
    $patients = singular("patients", "tc_id", $yatis['tc_kimlik_no']);
    $ameliyat = singular("ameliyat", "patient_protocol_number", $_GET["getir"]);
    $taburcu = singular("patient_discharge", "admission_protocol",$yatis["yatis_protokol"]);

    ?>

    <div class="modal-content">
            <div >
                <div class="modal-header">
                    <h4 class="modal-title">ODER iŞLEMLERi - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h4>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form   id="requestform" action="javascript:void(0);" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="col-md-4">ORDER iŞLEM TÜRÜ</label>
                                    <select class="form-select orderturu" name="operation_type">
                                        <option value="">Order işlem türü seçiniz..</option>
                                        <?php
                                        $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='ORDER_ISLEM_TURU'");
                                        foreach ((array) $hello as $rowa) {
                                            ?>
                                            <option <?php //if($_GET['secili']==$rowa["iLCE_NO"]){ echo "selected"; } ?>
                                                    value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <script>
                                    $(".orderturu").change(function () {
                                        var ordertipi = $(this).val();
                                        var protokol ="<?php echo $_GET["protokolno"]; ?>";
                                        $.get("ajax/yatis/yatismodalbody.php?islem=ordertipisecim", {protokol:protokol,ordertipi:ordertipi}, function (g) {
                                            $('.orderbody').html(g);

                                        });
                                    })
                                </script>
                                <div class="mx-3 orderbody">




                                </div>
                            </div>


                            <div class="modal fade bd-example-modal-lg " id="yatisyap" role="dialog">
                                <div class="yatismodalbodyy"></div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

<?php }
elseif($islem=="ordertipisecim"){
    $secim=$_GET["ordertipi"];

    $hastakayit = singularactive("patient_registration", "id", $_GET["protokol"]);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    $taburcu = singularactive("patient_discharge", "admission_protocol",$hastakayit["id"]);
    $islemtanim = singularactive("transaction_definitions", "id",$secim);
    $tanimadi=$islemtanim['definition_name']   ?>
    <?php if ($tanimadi=='DIREKTIF_TAKIP'){ ?>
        <div class="mb-2 row">

            <div class="col-md-6">

                <input type="hidden" class="form-control" name="patient_protokol"
                       value="<?php echo $hastakayit['id'] ?>" id="basicpill-firstname-input">
                <input type="hidden" name="transaction_date" value="<?php echo $simdikitarih; ?>">
                <input type="hidden" name="operation_type" value="29144">
                <input type="hidden" class="form-control" name="type_of_treatment" value="28984">
            </div>

        </div>

        <div class="mb-2 row">

            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Başlangiç Tarihi</label>
                <input type="date" class="form-control" name="starting_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" name="end_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Açiklama</label>
                <textarea class="form-control"  name="explanation"></textarea>
            </div>
        </div>
        <div class="mb-2 row">
            <?php $saygetir=0;
            $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='DIREKTIF_TAKIP'");
            foreach ((array) $hello as $rowa) { $saygetir++;
                ?>
                <div class="col-md-3">
                    <input class="form-check-input"  name="directive_id[]"
                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formCheck12<?php echo $saygetir; ?>" >
                    <label class="form-check-label" for="formCheck12<?php echo $saygetir; ?>">
                        <?php echo $rowa['definition_name']; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div align="right">
            <input class="btn btn-success  deriktifinsert" style="margin-bottom:4px" data-dismiss="modal"
                   type="submit"  value="Kaydet"/>
        </div>


        <script>

            $(".deriktifinsert").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#requestform").serialize();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=direktifinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var $portokol="<?php echo $hastakayit['id']; ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {$portokol: $portokol}, function (getVeri) {
                            $('.orderlistesibody').html(getVeri);
                        });



                    }
                });

            });
        </script>

    <?php }else if($tanimadi=='ILAÇ'){ ?>


        <input type="hidden" class="form-control" name="order[patient_registrationid]" value="<?php echo $_GET["protokol"]; ?>">
        <input type="hidden" class="form-control" name="order[patientid]" value="<?php echo $patients["id"]; ?>">
        <input type="hidden" class="form-control" name="order[request_time]" value="<?php echo $simdikitarih; ?>">
        <input type="hidden" class="form-control" name="order[doctorid]" value="<?php echo $hastakayit["service_doctor"]; ?>">
        <input type="hidden" name="order[insert_datetime]" value="<?php echo $simdikitarih; ?>">
        <input type="hidden" name="order[insert_userid]" value="<?php echo $_SESSION['id']; ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="col-md-4">Depo</label>
                    <select class="form-select depo" name="order[request_warehouseid]" >
                        <option value="">Depo seçiniz..</option>
                        <?php
                        $servisid=$hastakayit["service_id"];
                        $hello=verilericoklucek("SELECT * FROM warehouses where unitid='$servisid'");
                        foreach ((array) $hello as $rowa) {
                            ?>
                            <option <?php //if($_GET['secili']==$rowa["iLCE_NO"]){ echo "selected"; } ?>
                                    value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["warehouse_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="col-md-4">Malzeme Tipi</label>
                    <select class="form-select malzemetipi" name="order[stock_type]">
                        <option value="">Malzeme tipi seçiniz..</option>
                        <?php
                        $servisid=$hastakayit["service_id"];
                        $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='MALZEME_TIPI'");
                        foreach ((array) $hello as $rowa) {
                            ?>
                            <option <?php //if($_GET['secili']==$rowa["iLCE_NO"]){ echo "selected"; } ?>
                                    value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>


        <script>
            $(".malzemetipi").change(function () {
                var malzemetipi = $(this).val();
                var depo = $(".depo").val();
                var protokol ="<?php echo $_GET["protokol"]; ?>";
                $.ajax({
                    type: "POST",
                    url: "ajax/yatis/yatislistesi.php?islem=malzemelistesisecim",
                    data: {"malzemetipi": malzemetipi,"depo":depo,protokol:protokol},
                    success: function (e) {
                        $(".ilacisteklist").html(e);
                    }
                })
            })
        </script>
        <div class="mb-3">
            <label for="basicpill-firstname-input" class="form-label">Doktor Açiklamasi</label>
            <textarea class="form-control " name="order[stock_request_doctor_description]" ></textarea>
        </div>

        <div class="ilacisteklist">

        </div>


    <?php }else if($tanimadi=='Diğer'){ ?>

        <div class="mb-2 row">

            <div class="col-md-6">

                <input type="hidden" class="form-control" name="patient_protokol"
                       value="<?php echo $hastakayit['id'] ?>" id="basicpill-firstname-input">
                <input type="hidden" name="transaction_date" value="<?php echo $simdikitarih; ?>">
                <input type="hidden" name="operation_type" value="29144">
                <input type="hidden" class="form-control" name="type_of_treatment" value="28984">
            </div>

        </div>

        <div class="mb-2 row">

            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Başlangiç Tarihi</label>
                <input type="date" class="form-control" name="starting_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" name="end_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Açiklama</label>
                <textarea class="form-control"  name="explanation"></textarea>
            </div>
        </div>
        <div class="mb-2 row">
            <?php $saygetir=0;
            $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='ORDERTURU_DIGER'");
            foreach ((array) $hello as $rowa) { $saygetir++;
                ?>
                <div class="col-md-3">
                    <input class="form-check-input"  name="directive_id[]"
                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formCheck12<?php echo $saygetir; ?>" >
                    <label class="form-check-label" for="formCheck12<?php echo $saygetir; ?>">
                        <?php echo $rowa['definition_name']; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div align="right">
            <input class="btn btn-success  digerinsert" style="margin-bottom:4px"
                   type="submit"  value="Kaydet"/>
        </div>


        <script>

            $(".digerinsert").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#requestform").serialize();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=direktifinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        var portokol="<?php echo $hastakayit['id']; ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {portokol: portokol}, function (getVeri) {
                            $('.orderlistesibody').html(getVeri);
                        });
                    }
                });

            });
        </script>


    <?php }else if($tanimadi=='Diyet'){ ?>




    <?php }else if($tanimadi=='Bakım/Temizlik'){ ?>

        <div class="mb-2 row">

            <div class="col-md-6">

                <input type="hidden" class="form-control" name="patient_protokol" value="<?php echo $hastakayit['id'] ?>" id="basicpill-firstname-input">
                <input type="hidden" name="transaction_date" value="<?php echo $simdikitarih; ?>">
                <input type="hidden" name="operation_type" value="29144">
                <input type="hidden" class="form-control" name="type_of_treatment" value="28984">
            </div>

        </div>

        <div class="mb-2 row">

            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Başlangiç Tarihi</label>
                <input type="date" class="form-control" name="starting_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-3">
                <label for="basicpill-firstname-input" class="form-label">Bitiş Tarihi</label>
                <input type="date" class="form-control" name="end_date"
                       id="basicpill-firstname-input">
            </div>
            <div class="col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Açiklama</label>
                <textarea class="form-control"  name="explanation"></textarea>
            </div>
        </div>
        <div class="mb-2 row">
            <?php $saygetir=0;
            $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='BAKIM/TEMIZLIK'");
            foreach ((array) $hello as $rowa) { $saygetir++;
                ?>
                <div class="col-md-3">
                    <input class="form-check-input"  name="directive_id[]"
                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formCheck12<?php echo $saygetir; ?>" >
                    <label class="form-check-label" for="formCheck12<?php echo $saygetir; ?>">
                        <?php echo $rowa['definition_name']; ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div align="right">
            <input class="btn btn-success  bakimtemizlikinsert" style="margin-bottom:4px"
                   type="submit"  value="Kaydet"/>
        </div>


        <script>

            $(".bakimtemizlikinsert").off().on("click", function () {
                //form reset-->
                var gonderilenform = $("#requestform").serialize();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/yatis/yatisislem.php?islem=direktifinsert',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        var $yportokol="<?php echo $hastakayit['id']; ?>";
                        $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {$portokol: $portokol}, function (getVeri) {
                            $('.orderlistesibody').html(getVeri);
                        });


                    }
                });

            });
        </script>


    <?php }?>






<?php }

elseif ($islem == "yatisrefakatci") {
    if($_GET['refakatciid']){
        $refakatci = singularactive("patient_companion","id",$_GET['refakatciid']);
        $hastakayit = singularactive("patient_registration", "protocol_number", $refakatci['patient_protocol_number']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }else{
        $hastakayit = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
        $hastaadisoyadi=ucwords($patients['patients_name'].' '.$patients['patients_surname']);
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);

    ?>

        <div style="display: flex;">
<!--            liste alanı-->
            <div class="panel panel-htop" style="width:100%;height:100%">
            <div class="panel-header" style="height:20%">
                <div class="panel-title">Refakatçı Listesi</div>
            </div>
            <div class="easyui-panel panel-body panel-body-nobottom" >
                <div class="refakatcilistesi-<?php echo $protokolno; ?>" style="padding:5px;">

                    <table class="easyui-datagrid"  id="refakatci_table" style="height:190px"
                            sortOrder="asc"
                           rownumbers="true" pagination="true"  
                           data-options="singleSelect:true,collapsible:true,remoteSort:false,multiSort:true,
                           url:'ajax/yatis/yatislistesi.php?islem=refakatcilistesi&protokol_no=<?php echo $protokolno; ?>',method:'get'">
                        <thead>
                        <tr>
                            <th data-options="field:'refakatci_id',width:50,align:'center',sortable:true">No</th>
                            <th data-options="field:'refakatci_adi_soyadi',width:150,align:'center',sortable:true">Adi Soyadi</th>
                            <th data-options="field:'hasta_adi_soyadi',width:150,align:'center',sortable:true">Hasta Adi Soyadi</th>
                            <th data-options="field:'yakinlik',width:105,align:'center',sortable:true">Yakinlik</th>
                            <th data-options="field:'giris_tarihi',width:150,align:'center',sortable:true">T. Giriş</th>
                            <th data-options="field:'cikis_tarihi',width:150,align:'center',sortable:true">T. Çikiş</th>
                            <th data-options="field:'refakatci_durum',width:150,align:'center',sortable:true" formatter="r_durum">R. Durum</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

                <script>
                    function r_durum(val,row) {
                        var refakatci_status='';
                        if (val) {
                            refakatci_status='Çıkış Yaptı';
                        }
                        else{
                            refakatci_status='Giriş Yaptı'
                        }
                        return refakatci_status;
                    }
                </script>

        </div>
<!--            form alanı-->
            <div class="panel panel-htop" style="width:100%;height:100%;">
                <div class="panel-header">
                    <div class="panel-title">Refakatçı Formu</div>
                </div>
                <div class="easyui-panel panel-body panel-body-nobottom">
                    <div id="refakatcibodyupdateq-<?php echo $protokolno; ?>" style="padding:5px;height:170px" >

                        <div style="display: flex;margin-top:10px">
                            <input class="easyui-textbox " readonly
                                   value="<?php echo $hastakayit['protocol_number'] ?>"
                                   labelWidth=200 style="width:100%" data-options="label:'Hasta Protokol No:'">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="easyui-textbox" readonly value="<?php echo $patients['tc_id'];  ?>"
                                   labelWidth=200 style="width:100%" data-options="label:'Hasta T.C Numarası:'">
                        </div>
                        <form id="formrefakat" action="javascript:void(0);">

                            <div style="display: flex;margin-top:5px">

                                <input class="easyui-datetimebox refakatciempty"   min="<?php echo $hastakayit['hospitalized_accepted_datetime']; ?>" name="refakatci[login_datetime]"
                                       value="<?php  echo $simdikitarih; ?>"
                                       data-options="label:'Refakatcı Giriş Tarihi:'"  labelWidth=200 style="width:100%;">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="easyui-datetimebox " name="refakatci[exit_datetime]"  min="<?php echo $hastakayit['hospitalized_accepted_datetime']; ?>"
                                       value="<?php echo $simdikitarih; ?>"
                                       labelWidth=200 style="width:100%;" data-options="label:'Refakatcı Çikiş Tarihi:'">

                            </div>
                                <input class="refakatciempty" hidden name="refakatci[patient_protocol_number]"
                                       value="<?php echo $hastakayit['protocol_number']; ?>"
                                       style="width:100%" >

                                <input   name="refakatci[id]" hidden style="width:100%">

                            <div style="display: flex;margin-top:5px">

                                <input class="easyui-textbox refakatciempty" name="refakatci[companion_name_surname]"
                                        labelWidth=200
                                       style="width:100%" data-options="label:'Refakatcı Adi Soyadi'">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="easyui-numberbox refakatciempty" name="refakatci[companion_tc]"
                                        labelWidth=200
                                       style="width:100%" data-options="label:'Refakatçı T.C. No',min:10000000000,max:90000000000">
                            </div>
                            <div style="display: flex;margin-top:5px">

                                <input class="easyui-numberbox refakatciempty" name="refakatci[companion_phone]"
                                        labelWidth=200
                                       style="width:100%" data-options="label:'Refakatçı Telefon',min:5000000000,max:5999999999">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <select class="easyui-combobox" name="refakatci[companion_proximity]" data-options="panelHeight:'auto'" label="Refakatçı Yakinlik:"  style="width:100%;" labelWidth=200>
                                    <?php $bolumgetir = "select * from transaction_definitions WHERE definition_type='YAKINLIK'";
                                    $hello=verilericoklucek("$bolumgetir");
                                    foreach ($hello as $value) {  ?>

                                        <option value="<?php echo $value["id"]; ?>"><?php echo $value['definition_name']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-header" style="height:30px">
                    <div class="panel-tool">
                        <?php
                        $refakatciinsert=yetkisorgula($kullanicid, "refakatciinsert");
                        $refakatciupdate=yetkisorgula($kullanicid, "refakatciupdate");
                        $refakatcidelete=yetkisorgula($kullanicid, "refakatcidelete");
                         ?>
                            <?php if ($refakatciinsert==1){ ?>
                                <a href="javascript:void(0)" style="margin-right:10px;" title="Yeni Kayıt" class="icon-add update_btn panel-tool-a
                                <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'refakatyeni'; } ?>"
                                ></a>
                            <?php }
                            if ($refakatcidelete==1){ ?>
                                <a href="javascript:void(0)" style="margin-right:10px;" title="İptal Et" data-id="<?php echo $refakatci["id"]; ?>" class="icon-cancel panel-tool-a update_btn
                                  <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'refakatdelet'; } ?>"
                                ></a>

                            <?php }
                            if ($refakatciupdate==1){ ?>
                                <a href="javascript:void(0)" style="margin-right:10px;" title="Düzenle" class="icon-edit panel-tool-a update_btn
                                     <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'refakatupdate'; } ?>"
                                ></a>
                            <?php }
                             if ($refakatciinsert==1){ ?>
                            <a href="javascript:void(0)" style="margin-right:10px;" title="Kaydet" class="icon-save   panel-tool-a insert_btn
                                    <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else{ echo 'refakatinsert'; } ?>">
                               </a>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(".update_btn").prop('hidden', true);

    </script>

    <script>
        $(document).ready(function () {

            $("body").off("click", ".refakatinsert").on("click", ".refakatinsert", function (e) {
                //form reset-->
                var say=0;
                $('.refakatciempty').filter(function() {
                    if (this.value==''){
                        say++;
                        $(this).css("background-color", "yellow");
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formrefakat").serialize();

                    $('#formrefakat').form('clear');
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=refakatinsert',
                        data: gonderilenform,
                        success: function (e) {
                            $('#refakatci_table').datagrid('reload');

                            $('#formrefakat').form('load',{
                                'refakatci[patient_protocol_number]':"<?php echo $hastakayit['protocol_number']; ?>",
                                'refakatci[login_datetime]':"<?php echo $simdikitarih; ?>",
                                'refakatci[exit_datetime]':"<?php echo $simdikitarih; ?>"
                            });

                        }
                    });
                }
            });

            setTimeout(function() {
                $('#refakatci_table').datagrid({
                    onClickRow: function(index,field,value){
                        var row = $('#refakatci_table').datagrid('getSelected');
                        var giris=row.giris_tarihi;
                        var cikis=row.cikis_tarihi;
                        var new_giris = moment(giris).format('MM/DD/YYYY H:mm:ss');

                        if (row!=null){
                            $('#formrefakat').form('load',{
                                'refakatci[id]':row.refakatci_id,
                                'refakatci[login_datetime]':new_giris,
                                'refakatci[exit_datetime]':row.cikis_tarihi,
                                'refakatci[companion_name_surname]':row.refakatci_adi_soyadi,
                                'refakatci[companion_tc]':row.refakatci_tc,
                                'refakatci[companion_phone]':row.refakatci_tel,
                                'refakatci[companion_proximity]':row.yakinlik_id
                            });

                            $(".insert_btn").prop('hidden', true);
                            $(".update_btn").prop('hidden', false);
                        }
                    }
                });
            }, 1000);




            $(".refakatupdate").on("click", function () {

                var say=0;
                $('.refakatciempty').filter(function() {
                    if (this.value==''){
                        say++;
                        $(this).css("background-color", "yellow");
                    }
                });

                if (say>0) {
                    alertify.error('Boş alanları doldurun');
                }else{
                    var gonderilenform = $("#formrefakat").serialize();

                    $('#formrefakat').form('clear');
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=refakatupdate',
                        data: gonderilenform,
                        success: function (e) {
                            $('#refakatci_table').datagrid('reload');

                            $('#formrefakat').form('load',{
                                'refakatci[patient_protocol_number]':"<?php echo $hastakayit['protocol_number']; ?>",
                                'refakatci[login_datetime]':"<?php echo $simdikitarih; ?>",
                                'refakatci[exit_datetime]':"<?php echo $simdikitarih; ?>"
                            });

                            $(".insert_btn").prop('hidden', false);
                            $(".update_btn").prop('hidden', true);
                        }
                    });
                }

            });

            $(".refakatdelet").click(function () {
                var row =$('#refakatci_table').datagrid('getSelected');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                    "<input class='form-control' type='text' id='delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                    var delete_detail = $('#delete_detail').val();
                    var id=row.refakatci_id;


                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=refakatdelet',
                        data: {"id":id, "delete_detail": delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);


                            $('#refakatci_table').datagrid('reload');

                            $('#formrefakat').form('load',{
                                'refakatci[patient_protocol_number]':"<?php echo $hastakayit['protocol_number']; ?>",
                                'refakatci[login_datetime]':"<?php echo $simdikitarih; ?>",
                                'refakatci[exit_datetime]':"<?php echo $simdikitarih; ?>"
                            });

                            $('.alertify').remove();
                        }
                    });

                }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});

            });

            $(".refakatyeni").click(function () {
                $('#formrefakat').form('clear');
                $('#refakatci_table').datagrid('reload');

                $('#formrefakat').form('load',{
                    'refakatci[patient_protocol_number]':"<?php echo $hastakayit['protocol_number']; ?>",
                    'refakatci[login_datetime]':"<?php echo $simdikitarih; ?>",
                    'refakatci[exit_datetime]':"<?php echo $simdikitarih; ?>"
                });

                $(".insert_btn").prop('hidden', false);
                $(".update_btn").prop('hidden', true);


            });




            $('#refakatci_table').datagrid({
                onLoadSuccess: function(){

                    $(".pagination-load").click(function () {
                        $('#formrefakat').form('clear');
                         //$('#refakatci_table').datagrid('reload');

                        $('#formrefakat').form('load',{
                            'refakatci[patient_protocol_number]':"<?php echo $hastakayit['protocol_number']; ?>",
                            'refakatci[login_datetime]':"<?php echo $simdikitarih; ?>",
                            'refakatci[exit_datetime]':"<?php echo $simdikitarih; ?>"
                        });

                        $(".insert_btn").prop('hidden', false);
                        $(".update_btn").prop('hidden', true);
                    });

                }
            });




            $(".kpssorgu").on("click", function () {

                var tc_id = $('.tckimlikno').val();

                alertify.success(tc_id + "Kimlik Numarasını Sorguluyorum");
                $.get("ajax/webservisleri/kpssorgula.php", {tc_id: tc_id}, function (getVeri) {

                    if (getVeri != 0) {

                        alertify.success(tc_id + "Sorgulamam tamamlandı");
                        alert(getVeri);
                        var Ad = arasinial_js(getVeri, "<Ad>", "</Ad>");
                        var Soyad = arasinial_js(getVeri, "<Soyad>", "</Soyad>");

                        document.getElementById("adisoyaddegeri").value = Ad + ' ' + Soyad;

                    } else {

                        alertify.alert("Hata", "Sorgulama yaptığınız TC Kimlik numarası hatalı olabilir veya sistem yanıt vermiyor.");

                    }

                });

            });

        });
    </script>

<?php }
elseif ($islem == "yatisizinli") {
    $hastakayit = singularactive("patient_registration", "protocol_number", $_GET["getir"]);
    if ($hastakayit['mother_tc_identity_number']==''){
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    }else{
        $ANNETC=$hastakayit['mother_tc_id'];
        $DOGUMSiRA=$hastakayit['birth_order'];
        $hastalarid=$hastakayit['patient_id'];
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    }

    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>


    <div class="modal-dialog modal-xxl">
        <!-- Modal content-->

        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">Hasta İzin İşlemleri- <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row">
                <div class="col-md-5 px-3 pt-1 pb-1">
                    <div class="card ">
                        <div class="card-header " style="height: 4vh;"  >Hasta İzin Listesi</div>
                        <div class="izinlilistesi-<?php echo $protokolno; ?> mt-1">

                        </div>
                    </div>
                </div>
                <div class="col-md-7 ">
                     <div class="card">
                         <div class="card-header" style="height: 4vh;">
                             <div class="col-12 row">
                                 <div class="col-md-9  ">
                                     <h5 style="font-size: 13px;">Hasta İzin Kayıt</h5>
                                 </div>
                                 <div class="col-md-3 " align="right">
                                     <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                 </div>
                             </div>
                         </div>
                        <div id="izinlibodyupdate-<?php echo $protokolno; ?>">

                        </div>
                     </div>
                </div>
            </div>



        </div>
    </div>

    <script>

        $(document).ready(function () {
            var getir = "<?php echo $_GET["getir"] ?>";
            $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir:getir}, function (e) {
                $("#izinlibodyupdate-<?php echo $protokolno; ?>").html(e);
                $.get("ajax/yatis/yatislistesi.php?islem=izinlistesi", {getir: getir}, function (g) {
                    $(".izinlilistesi-<?php echo $protokolno; ?>").html(g);
                });
            });

        });

    </script>
<?php }

elseif ($islem == "yataktemizlik") {
    $hastakayit = singularactive("patient_registration", "protocol_number", $_GET["getir"]);
    if ($hastakayit['mother_tc_identity_number']==''){
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    }else{
        $ANNETC=$hastakayit['mother_tc_id'];
        $DOGUMSiRA=$hastakayit['birth_order'];
        $hastalarid=$hastakayit['patient_id'];
        $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);


    ?>


    <div class="modal-dialog modal-xxl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title">Yatak Temizleme İşlemleri- <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row ">
                <div class="col-md-5 px-3 pt-1 pb-1">
                    <div class="card ">
                        <div class="card-header " style="height: 4vh;" >Hasta Yatak Temizlik Listesi</div>
                        <div class="temizliklistesi-<?php echo $protokolno ?>">

                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                     <div class="card" >
                         <div class="card-header" style="height: 4vh;">
                             <div class="col-12 row">
                                 <div class="col-md-9 ">
                                     <h5 style="font-size: 13px;">Hasta Yatak Temizlik Kayıt</h5>
                                 </div>
                                 <div class="col-md-3 " align="right">
                                     <p style="font-size: 13px;">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?> Taburcu  <?php }elseif ($izin['id']!=''){ ?> İzinli <?php }else{ ?>Yatışta <?php } ?></b></p>
                                 </div>
                             </div>
                         </div>
                        <div id="temizbodyupdate-<?php echo $protokolno ?>">

                        </div>
                     </div>
                </div>

            </div>

        </div>
    </div>

    <script>

        $(document).ready(function () {
            var getir = "<?php echo $_GET["getir"] ?>";
            $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {getir:getir}, function (e) {
                $("#temizbodyupdate-<?php echo $protokolno ?>").html(e);
                $.get("ajax/yatis/yatislistesi.php?islem=temizliklistesi", {getir: getir}, function (g) {
                    $(".temizliklistesi-<?php echo $protokolno ?>").html(g);
                });
            });

        });

    </script>
<?php } if($islem == "servisde-bulunan-yatklari-getir"){  $service_id = $_POST["service_id"]; ?>

    <div class="yatak-oda-detay">

        <div class="col-12 row">
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-primary text-white">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px;" class="hizmet_adet">Dolu Yatak</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-duotone fa-bed-pulse fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-primary text-white" style="opacity:0.5">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px;" class="hizmet_adet">Boş Yatak</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-light fa-bed-front fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-danger text-white">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px; " class="hizmet_adet">Dolu Oda</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-duotone fa-house-chimney-medical fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-danger text-white" style="opacity:0.5">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px; " class="hizmet_adet">Boş Oda</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-sharp fa-regular fa-house-chimney-medical fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-warning text-white">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px; " class="hizmet_adet">Rezerve Yatak</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-duotone fa-bed-front fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-xl-2 text-center mt-2">
                <button class="border-0 bg-transparent w-100 h-50">
                    <div class="card shadow py-3 h-100 justify-content-center bg-info text-white">
                        <div class="row align-items-center">
                            <div class="col-xl-8">
                                <b style=" font-size: 12px; " class="hizmet_adet">Rezerve Oda</b>
                            </div>
                            <div class="col-xl-4">
                                <i class="fa-sharp fa-regular fa-house-laptop fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </button>
            </div>

        </div>
    </div>
    <div id="yatakrezervasyonicerik">
        <div class="row">
            <input type="hidden" class="bina_sec" name="building_id">
            <input type="hidden" class="kat_sec" name="floor_id">
            <input type="hidden" class="oda_sec" name="room_id">
            <input type="hidden" class="yatak_sec" name="bed_id">
        </div>

        <div class="mt-2" style="overflow:auto; height:60vh;">

            <?php $sql=verilericoklucek("select * from hospital_room inner join hospital_bed  on hospital_room.id = hospital_bed.room_id where hospital_bed.hospital_service_id = $service_id and hospital_room.status=1");
            if(!$sql){ ?>
                <div class="alert alert-danger">Servise Henüz Yatak Tanımlaması Yapılmamış.Lütfen Bilgi İşlem Dairesi İle İletişime Geçiniz!</div>
          <?php exit(); }

            foreach ((array) $sql as $value){ ?>
                <div class="card mb-1 " >

                    <div class="card-header p-1 bg-danger" style="font-size: 12px;<?php if($value['availability']==2){ ?> opacity:0.5; <?php } ?>">
                        <div class="row">
                            <div class="col-md-6">
                            <?php
                            if ($value['availability']==2){

                              echo  '<i title="Boş Oda" class="fa-sharp fa-regular fa-house-chimney-medical fa-2x"></i>';

                            }elseif ($value['availability']==1){
                                echo  '<i title="Dolu Oda" class="fa-duotone fa-house-chimney-medical fa-2x"></i>';
                            }
                            ?>
                                Oda Adı: <?php echo $value['room_name']; ?>
                            </div>
                            <div class="col-md-6" align="right">Oda Türü: <?php echo islemtanimgetirid($value['room_type']); ?></div>
                        </div>
                    </div>

                    <div class="card-body" style="cursor: pointer !important;">
                        <div class="row" >
                            <?php $room_id=$value['room_id'];
                            $bolum = 'select * , hospital_bed.id as yatak_id,hospital_bed.bed_type as yatak_tur,hospital_bed.bed_name as yatak_name
                            from hospital_bed
                                     left join patient_registration on patient_registration.bed_id = hospital_bed.id
                                     left join patients on patients.id = patient_registration.patient_id
                            where hospital_bed.status = 1 and hospital_bed.room_id='.$room_id;
                            $hel=verilericoklucek($bolum);
                            foreach ((array) $hel as $val){ ?>
                            <div class="col-md-2 py-2 py-3 text-white mx-2 rounded <?php if($val['full_status']!=1 && $val['full_status']!=3){ ?> odayatak <?php } ?>
                                <?php
                                if ($val['full_status']==0 || $val['full_status']==1){
                                    echo 'bg-primary';
                                }elseif ($val['full_status']==3){
                                    echo 'bg-warning';
                                }
                                ?>" style="
                                 <?php
                                 if ($val['full_status']==0){
                                     echo 'opacity:0.5;';
                                 }
                                 ?>" yatak_tur="<?php echo $val['yatak_tur'] ?>"  oda_id="<?php echo $room_id ?>" yatak_id="<?php echo $val['yatak_id'] ?>"
                                 yatak_name="<?php echo $val['yatak_name'] ?>" oda_name="<?php echo $value['room_name'] ?>" bina_id="<?php echo $value['building_id']; ?>" kat_id="<?php echo $value['floor_id'] ?>">

                                <div align="center" style="font-size: 12px;">
                                    <?php if ($val['full_status'] == 1) {  ?>
                                        <i title="Dolu Yatak" class="fa-duotone fa-bed-pulse fa-2x"></i>  <br>
                                    <?php echo $val['patient_name']." ".$val['patient_surname']."<br>";
                                        echo '<hr class="m-1">';
                                        if($val['gender']=="K"){
                                            echo "Kadın";
                                        }else{
                                            echo "Erkek";
                                        }
                                        echo '<hr class="m-1">';
                                    }
                                    elseif ($val['full_status'] == 0) { ?>

                                        <i title="Boş Yatak" class="fa-light fa-bed-front fa-2x"></i>

                                    <?php }
                                    elseif ($val['full_status'] == 3) { ?>
                                        <i title="Rezerve Yatak" id="yatak-icon-dom" class="fa-duotone fa-bed-front fa-2x"></i>
                                    <?php } ?>

                                </div>

                                <p class="text-center" ><?php echo $val['bed_name']; ?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
</div>

    <script>
        $("body").off("click", ".odayatak").on("click", ".odayatak", function (e) {

            $('.odayatak').removeClass("bg-success");
            $(this).addClass("bg-success");


            // $('.odayatak').find("#yatak-icon-dom").removeClass("fa-check");
            // $(this).find("#yatak-icon-dom").addClass("fa-check");
            //
            // var oda_id=$(this).attr("oda_id");
            // var yatak_id=$(this).attr("yatak_id");
            // var bina_id=$(this).attr("bina_id");
            // var kat_id=$(this).attr("kat_id");
            // $(".bina_sec").val(bina_id);
            // $(".kat_sec").val(kat_id);
            // $(".oda_sec").val(oda_id);
            // $(".yatak_sec").val(yatak_id);


            var oda_id=$(this).attr("oda_id");
            var yatak_id=$(this).attr("yatak_id");
            var yatak_tur=$(this).attr("yatak_tur");
            var bina_id=$(this).attr("bina_id");
            var kat_id=$(this).attr("kat_id");
            var yatak_name=$(this).attr("yatak_name");
            var oda_name=$(this).attr("oda_name");
            $("#bina_id").val(bina_id);
            $("#kat_id").val(kat_id);
            $("#oda_id").val(oda_id);
            // $("#oda_name").val(oda_name);
            $('#oda_name').textbox('setValue', oda_name);
            $("#yatak_id").val(yatak_id);
            $("#yataktur_id").val(yatak_tur);
            // $("#yatak_name").val(yatak_name);
            $('#yatak_name').textbox('setValue', yatak_name);
        });

    </script>


<?php }  ?>