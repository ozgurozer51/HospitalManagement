<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y-m-d h:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid=$_SESSION['id'];
$anamnezinsert=yetkisorgula($kullanicid, "anamnezinsert");
$anamnezupdate=yetkisorgula($kullanicid, "anamnezupdate");
$anamnezdelete=yetkisorgula($kullanicid, "anamnezdelete");

$islem = $_GET['islem']; ?>

    <script type="text/javascript">
        $(".alerttaburcu").off().on("click", function () {
            alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
        });
        $(".alertizin").on("click", function () {
            alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
        });
    </script>

<?php
if ($islem == "anamnezbody") {
$protokol=$_GET['protokol'];

    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol);
    $hastalar = singularactive("patients", "id", $hastakayit['patient_id']);
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
?>

    <script>
        var protokol ="<?php echo $_GET["protokol"]; ?>";
        var servis ="<?php echo $_GET["servis"]; ?>";
        var hekim ="<?php echo $_GET["hekim"]; ?>";
        $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
            $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);
        });
    </script>


    <div id="icerikanemnez-<?php echo $protokolno; ?>"> </div>







<?php
    $protokol= $_GET["protokol"];
    $hastakayit = singular("patient_registration", "id",$protokol);
    $anemnez = tek("select * from anamnesis where protocol_id='$protokol' and status='1'");
    $taburcu = singular("patient_discharge", "admission_protocol",$protokol);
    $izin = singularactive("patient_permission","protocol_number",$protokol);
    $random_sayi = uniqid();
    ?>

    <div id="exp-tt">Hasta Durum:<b> <?php if ($taburcu['discharge_status'] == 1) { ?> Taburcu  <?php } elseif ($izin['id'] != '') { ?> İzinli <?php } else { ?>Yatışta <?php } ?></b></div>

    <div class="easyui-tabs" data-options="tools:'#exp-tt' , border:false" style="width:100%;height:100%;">

            <div title="Anamnez Bilgisi" style="padding:10px">
                <div class="row">
                    <div class="col-md-12">
                        <div class="anemnezbody">
                            <form id="formanemnez"  action="javascript:void(0);">
                                <div class="mb-2 row">

                                    <div class="col-md-6">

                                        <input type="hidden" class="form-control" name="protocol_id" value="<?php echo $_GET['protokol']; ?>" id="basicpill-firstname-input">
                                        <?php if ($anemnez['id']!=''){ ?>

                                            <input type="hidden" class="form-control" name="id" value="<?php echo $anemnez['id']; ?>" id="basicpill-firstname-input">
                                        <?php } ?>
                                    </div>

                                </div>
                                <div class="mb-2 row">
                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control" value="<?php echo $_GET['servis']; ?>" name="servis_id" id="basicpill-firstname-input">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" class="form-control" value="<?php echo $_GET['hekim']; ?>" name="servis_doctor" id="basicpill-firstname-input">
                                    </div>

                                </div>
                                <div class="mb-2 row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <b class="mb-2">Akciğer Hastalıkları</b>
                                            <?php $saygetir=0;
                                            $hello=verilericoklucek("select * from transaction_definitions where definition_type='LUNG_DISEASES'");
                                            foreach ($hello as $rowa) { $saygetir++; ?>
                                                <div class="col-md-4">
                                                    <input class="form-check-input"  name="lung_diseases[]"
                                                        <?php
                                                        $bolunmus =explode(",", $anemnez['lung_diseases']);
                                                        if (in_array($rowa['id'],$bolunmus)) {
                                                            echo "checked";
                                                        } ?>

                                                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formcheck1<?php echo $saygetir; ?>" >
                                                    <label class="form-check-label" for="formcheck1<?php echo $saygetir; ?>">
                                                        <?php echo $rowa['definition_name']; ?>
                                                    </label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <b class="mb-2">Alışkanlık</b>
                                            <?php $saygetir2=0;
                                            $hello=verilericoklucek("select * from transaction_definitions where definition_type='HABITS'");
                                            foreach ($hello as $rowa) { $saygetir2++;
                                                ?>
                                                <div class="col-md-4">
                                                    <input class="form-check-input" name="habits[]"

                                                        <?php
                                                        $bolunmus =explode(",", $anemnez['habits']);
                                                        if (in_array($rowa['id'],$bolunmus)) {
                                                            echo "checked";
                                                        } ?>
                                                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formcheck2<?php echo $saygetir2; ?>">
                                                    <label class="form-check-label" for="formcheck2<?php echo $saygetir2; ?>">
                                                        <?php echo $rowa['definition_name'];?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <b class="mb-2">Diğer Hastalıklar</b>
                                            <?php $saygetir3=0;
                                            $hello=verilericoklucek("select * from transaction_definitions where definition_type='OTHER_DISEASES'");
                                            foreach ($hello as $rowa) { $saygetir3++;
                                                ?>
                                                <div class="col-md-4">
                                                    <input class="form-check-input" name="other_diseases[]"

                                                        <?php
                                                        $bolunmus =explode(",", $anemnez['other_diseases']);
                                                        if (in_array($rowa['id'],$bolunmus)) {
                                                            echo "checked";
                                                        } ?>

                                                           value="<?php echo $rowa['id'];?>" type="checkbox" id="formcheck3<?php echo $saygetir3; ?>">
                                                    <label class="form-check-label" for="formcheck3<?php echo $saygetir3; ?>">
                                                        <?php echo $rowa['definition_name'];?>
                                                    </label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <footer>
                    <div style="float: right;">

                        <?php $idal = $anemnez['id']; ?>
                        <?php if ($taburcu['discharge_status'] == 1) { ?>

                            <a class="easyui-linkbutton alerttaburcu" data-options="iconCls:'icon-add'">Kaydet</a>

                        <?php } else if ($izin['id'] != '') { ?>

                            <a class="easyui-linkbutton alertizin" data-options="iconCls:'icon-add'">Kaydet</a>

                        <?php } else { ?>
                            <?php if ($anemnez['id'] == '' && $anamnezinsert == 1) { ?>

                                <a class="easyui-linkbutton anemnezinsert" data-options="iconCls:'icon-add'">Kaydet</a>

                    <?php } else if ($anamnezupdate == 1) { ?>
                                <input type="hidden" name="id" value="<?php echo $idal; ?>">

                                <a class="easyui-linkbutton anemnezupdate" data-options="iconCls:'icon-add'">Kaydet</a>

                            <?php } ?>
                        <?php } ?>
                    </div>
                </footer>

            </div>

        <div title="Özgeçmiş/Soygeçmiş" style="padding:10px">


            <div class="ozsoygecmisyeni">
                <form id="formozsoygecmis" action="javascript:void(0);">
                    <div class="row">
                        <div class="mb-2 row">

                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="protocol_id"
                                       value="<?php echo $_GET['protokol']; ?>" id="basicpill-firstname-input">
                            </div>

                        </div>
                        <div class="mb-2 row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control"
                                       value="<?php echo $_GET['servis']; ?>" name="servis_id" id="basicpill-firstname-input">
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control"
                                       value="<?php echo $_GET['hekim']; ?>" name="servis_doctor" id="basicpill-firstname-input">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3">Özgeçmiş</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Özgeçmiş giriniz"  name="curriculum_vitae"><?php echo $anemnez['curriculum_vitae']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3">Soygeçmiş</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Soygeçmiş giriniz"  name="family_history"><?php echo $anemnez['family_history']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3">Öykü</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Öykü giriniz" name="story"><?php echo $anemnez['story']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3" title="Fiziki Muayene">F. Muayene</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Fiziki Muayene giriniz" name="examination"><?php echo $anemnez['examination']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input"  class="form-label col-md-3">Sonuç</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" placeholder="Sonuç Muayene giriniz" name="conclusion"><?php echo $anemnez['conclusion']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <footer>

                <div style="float: right;">

                <?php if ($taburcu['discharge_status']==1){ ?>
                    <a class="easyui-linkbutton alerttaburcu" data-options="iconCls:'icon-add'">Kaydet</a>
                <?php } else if ($izin['id']!=''){ ?>
                    <a class="easyui-linkbutton alertizin" data-options="iconCls:'icon-add'">Kaydet</a>
                <?php } else{ ?>
                    <?php if ($anemnez['id']=='' && $anamnezinsert==1){ ?>
                        <a class="easyui-linkbutton ozsoygecmisinsert" data-options="iconCls:'icon-add'">Kaydet</a>
                    <?php }else if ($anamnezupdate==1){ ?>
                        <input type="hidden" name="id" value="<?php echo $anemnez['id']; ?>">
                        <a class="easyui-linkbutton ozsoygecmisupdate" data-options="iconCls:'icon-add'">Kaydet</a>
                    <?php  } ?>
                <?php  } ?>

                </div>

            </footer>

        </div>

        <div title="Alerjiler" data-options="closable:false" style="padding:10px">

                <form id="formalerji" action="javascript:void(0);">
                    <div class="row">

                        <div class="mb-2 row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" name="protocol_id" value="<?php echo $_GET['protokol']; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" value="<?php echo $_GET['servis']; ?>" name="servis_id" id="basicpill-firstname-input">
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" value="<?php echo $_GET['hekim']; ?>" name="servis_doctor" id="basicpill-firstname-input">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3">İ̇laç Alerji</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control"  name="drug_allergy"><?php echo $anemnez['drug_allergy']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="row mx-2">
                                    <label for="basicpill-firstname-input" class="form-label col-md-3">Besin Alerji</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control"  name="food_allergy"><?php echo $anemnez['food_allergy']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            <footer>

                <div style="float: right;">

                <?php if ($taburcu['discharge_status']==1){ ?>
                    <a class="easyui-linkbutton alerttaburcu" data-options="iconCls:'icon-add'">Kaydet</a>
                <?php } else if ($izin['id']!=''){ ?>
                    <a class="easyui-linkbutton alertizin" data-options="iconCls:'icon-add'">Kaydet</a>
                <?php } else{ ?>
                    <?php if ($anemnez['id']=='' && $anamnezinsert==1){ ?>
                        <a class="easyui-linkbutton alerjiinsert" data-options="iconCls:'icon-add'">Kaydet</a>
                    <?php }else if($anamnezupdate==1){ ?>
                        <input type="hidden" name="id" value="<?php echo $anemnez['id']; ?>">
                        <a class="easyui-linkbutton alerjiupdate" data-options="iconCls:'icon-add'">Kaydet</a>
                    <?php  } ?>
                <?php  } ?>
            </footer>

        </div>

        </div>
    </div>

                <script>
                    $(document).ready(function () {
                        $(".ozsoygecmisinsert").off().on("click", function () {
                            var gonderilenform = $("#formozsoygecmis").serialize();
                            $.ajax({
                                type: 'post',
                                url: 'ajax/anamnezislem.php?islem=ozsoygecmisinsert',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);

                                    });

                                }
                            });

                        });

                        $(".ozsoygecmisupdate").on("click", function () {
                            //form reset-->

                            var gonderilenform = $("#formozsoygecmis").serialize();

                            $.ajax({
                                type: 'post',
                                url: 'ajax/anamnezislem.php?islem=ozsoygecmisupdate',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);

                                    });

                                }
                            });

                        });
                    });


                    $(document).ready(function () {
                        $(".alerjiinsert").off().on("click", function () {
                            //form reset-->
                            var gonderilenform = $("#formalerji").serialize();

                            $.ajax({
                                type: 'post',
                                url: 'ajax/anamnezislem.php?islem=alerjiinsert',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);
                                    });
                                }
                            });

                        });

                        $(".alerjiupdate").on("click", function () {
                            var gonderilenform = $("#formalerji").serialize();
                            $.ajax({
                                type: 'post',
                                url: 'ajax/anamnezislem.php?islem=alerjiupdate',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);

                                    });

                                }
                            });

                        });

                    });

                    $(document).ready(function () {
                        $(".anemnezinsert").off().on("click", function () {
                            var gonderilenform = $("#formanemnez").serialize();
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/anamnezislem.php?islem=aneminsert',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);
                                    });
                                }
                            });
                        });

                        $(".anemnezupdate").on("click", function () {
                            var gonderilenform = $("#formanemnez").serialize();
                            $.ajax({
                                type: 'post',
                                url: 'ajax/anamnezislem.php?islem=anamnezupdate',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var protokol ="<?php echo $_GET["protokol"]; ?>";
                                    var servis ="<?php echo $_GET["servis"]; ?>";
                                    var hekim ="<?php echo $_GET["hekim"]; ?>";
                                    $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
                                        $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);
                                    });
                                }
                            });
                        });
                    });
                </script>


<?php } elseif ($islem == "aneminsert") {
    if ($_POST) {
        $checked_count = count($_POST['lung_diseases']);
        $say=0;
        foreach($_POST['lung_diseases'] as $selected){
            $lung_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $lung_diseases.=',';
            }

        }
        unset($_POST['lung_diseases']);



        $checked_count = count($_POST['habits']);
        $say=0;
        foreach($_POST['habits'] as $selected){
            $habits.=$selected;
            $say++;
            if ($say<$checked_count){
                $habits.=',';
            }
        }
        unset($_POST['habits']);


        $checked_count = count($_POST['other_diseases']);
        $say=0;
        foreach($_POST['other_diseases'] as $selected){
            $other_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $other_diseases.=',';
            }

        }
        unset($_POST['other_diseases']);


        $_POST['lung_diseases']=$lung_diseases;
        $_POST['habits']=$habits;
        $_POST['other_diseases']=$other_diseases;
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "anamnezupdate") {
    if ($_POST) {
        $checked_count = count($_POST['lung_diseases']);
        $say=0;
        foreach($_POST['lung_diseases'] as $selected){
            $lung_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $lung_diseases.=',';
            }

        }
        unset($_POST['lung_diseases']);



        $checked_count = count($_POST['habits']);
        $say=0;
        foreach($_POST['habits'] as $selected){
            $habits.=$selected;
            $say++;
            if ($say<$checked_count){
                $habits.=',';
            }
        }
        unset($_POST['habits']);


        $checked_count = count($_POST['other_diseases']);
        $say=0;
        foreach($_POST['other_diseases'] as $selected){
            $other_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $other_diseases.=',';
            }

        }
        unset($_POST['other_diseases']);


        $_POST['lung_diseases']=$lung_diseases;
        $_POST['habits']=$habits;
        $_POST['other_diseases']=$other_diseases;



        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        var_dump($sonuc);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "ozsoygecmisinsert") {
    if ($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "ozsoygecmisupdate") {
    if ($_POST) {

        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "alerjiinsert") {
    if ($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "alerjiupdate") {
    if ($_POST) {

        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "anemnezicerik"){ ?>


<?php }






?>