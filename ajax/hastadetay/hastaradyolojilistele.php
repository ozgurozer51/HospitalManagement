<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanicininidsi=$_SESSION["id"];
$protokolno=$_GET["protokolno"];
$KULLANICI_ID=$_SESSION["id"];
$KULLANICIBILGILERI=singular("patient_registration","id",$protokolno);
$hasta_bilgileri=singular("patients","id",$KULLANICIBILGILERI["patient_id"]);
$islemtanimlarirad = singularactive("transaction_definitions", "definition_name",'RAD');
if($_GET["ihsan"]==""){
    $_GET["ihsan"]=rad_grup_id;
}
//var_dump($_POST);
$TANISORGULA=tek("SELECT * FROM patient_record_diagnoses WHERE protocol_number='$protokolno' and status='1' ORDER BY id DESC");
if($TANISORGULA["id"]=="")
{
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="mdi mdi-block-helper me-2"></i>
                                            Tetkik  ekleyebilmeniz için tanı girmeniz  gerekmektedir.. Lütfen giriş işlemini tamamladıktan sonra tekrar deneyiniz
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
}
else{
    if($_POST["islem"]=="TANIMLA"){
        $_POST["userid"]=$KULLANICI_ID;
        $_POST["insert_datetime"]=$gunceltarih;
        $_POST["status"]=1;
        $_POST["package_type"]=rad_grup_id;
        unset($_POST["islem"]);
        $EKLEMAN=direktekle("users_request_package",$_POST);
        var_dump($EKLEMAN);
        if($EKLEMAN==1){
            $SONIDSQL=tek("SELECT * FROM users_request_package WHERE userid='$KULLANICI_ID' ORDER BY id DESC");
            $SONID=$SONIDSQL["id"];
            $GUNCELLE=guncelle("UPDATE users_request_package_detail SET users_request_packageid='$SONID' WHERE userid='$KULLANICI_ID' AND status='1' AND users_request_packageid  IS NULL");
            var_dump($GUNCELLE);
            if($GUNCELLE==1){?>
                <script>  alertify.success("Ekleme başarılı");</script>
                <?php
            }else{
                ?>

                <script>  alertify.error("Ekleme başarısız.");</script>
                <?php
            }
        }
    }
    if($_GET["islem"]=="sil"){

        $silmeislemibaslat=  silme("patient_prompts","id",$_GET["islem_id"]);
        var_dump($silmeislemibaslat);
       if ($silmeislemibaslat){ ?>
        <script>  alertify.success("Silme işlemi başarılı ");</script>
        <?php } else{ ?>
           <script>  alertify.error("Silme işlemi başarısız ");</script>
     <?php   }
    }
    ?>
    <div id="ihsancunedioglu">

        <div class="modal fade bd-example-modal" id="hizlipaketlerigetir<?PHP ECHO $_GET["ihsan"] ;?>" role="dialog">
            <div class="modal-dialog modal-xl" >
                <div id="hastahizlipaketlisteleislem<?PHP ECHO $_GET["ihsan"] ;?>">
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal" id="hizlipaketolustur<?PHP ECHO $_GET["ihsan"] ;?>" role="dialog">

            <div class="modal-dialog" style=" width: 75%; max-width: 75%; ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hazır Liste Tanımla</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" >
                        <div class="card-body">

                            <form id="gonderilenform<?PHP ECHO $_GET["ihsan"] ;?>">
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Liste Adı</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="package_name" placeholder="Liste Adı" id="example-text-input">
                                        <input class="form-control" type="hidden" name="islem" value="TANIMLA" id="example-text-input">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="example-text-input" class="col-md-2 col-form-label">Liste Açıklaması</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" type="text" name="package_explain" placeholder="Liste Adı" id="example-text-input"></textarea>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <center>
                                    <h4>Radyoloji Tetkik Listesi</h4>
                                </center>

                                <script>
                                    function showResult(str) {
                                        var protokolno = $(this).attr('protokolno');
                                        $.get( "ajax/istemara.php?q="+str+"&protokolno=<?php echo $protokolno; ?>&patient=<?php echo $hasta_bilgileri["id"]; ?>&tip=<?php echo $_GET["tip"]; ?>&islemcik=rad", { protokolno:protokolno },function(getVeri){
                                            $('#hizlipaketlerigetirtablee<?PHP ECHO $_GET["ihsan"] ;?>').html(getVeri);
                                        });
                                    }
                                </script>
                                <input autocomplete="off"  onkeyup="showResult(this.value)" style=" height: 45px; padding: 20px; " required type="text" class="form-control"
                                       placeholder="Tanı kodu veya tanı adıyla arayınız"
                                       name="tani_adi" id="basicpill-firstname-input">

                                <table id="hizlipaketlerigetirtablee<?PHP ECHO $_GET["ihsan"] ;?>" class="table table-striped table-bordered"    >
                                    <thead>
                                    <tr>
                                        <th>ICD 10 KODU</th>
                                        <th>Adı </th>
                                        <th>Ücret</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php


                                    $sql ="select count(DISTINCT process_name),processes.id as himet_id,processes.process_name as himet_adi,processes_price.price as hizmet_ucret,
                                           official_code as himet_kodu,processes.process_group_id as hizmet_grup_id from processes
                                             inner join process_group on processes.process_group_id = process_group.id
                                             inner join processes_price on processes.id = processes_price.processes_id
                                    where process_group.id='".rad_grup_id."' group by process_name,himet_id,hizmet_ucret,himet_kodu,hizmet_grup_id order by process_name fetch first 7 rows only";

                                    $hello=verilericoklucek($sql);
                                    foreach ($hello as $row) {

//                                        $fiyatgetir=kurumagoreistemucreti($hasta_bilgileri["institution"],$hasta_bilgileri["social_assurance"],$row["id"]);
                                        ?>
                                        <tr>

                                            <th><?php echo $row["himet_kodu"]; ?></th>
                                            <th><?php echo $row["himet_adi"]; ?></th>
                                            <td><?php echo $row["hizmet_ucret"]; ?></td>
                                            <th><button type="button" islem="ekle"  KULLANICI_ID="<?php echo $KULLANICI_ID; ?>"  islem_id="<?PHP ECHO $row["himet_id"]; ?>" class="sagaekle<?PHP ECHO $_GET["ihsan"] ;?> btn up-btn waves-effect waves-light">Ekle</button></th>

                                        </tr>
                                    <?PHP } ?>
                                    </tbody>
                                </table></div>
                            <div class="col-md-6">
                                <center>
                                    <h4>Listeniz</h4>
                                </center>
                                <table id="kullaniciisteklistesi<?PHP ECHO $_GET["ihsan"] ;?>" class="table table-striped table-bordered"    >
                                    <thead>
                                    <tr>
                                        <th>Adı </th>
                                        <th>ICD 10 KODU</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php
                                    if ($_GET["ihsan"]!=''){
                                    $sql ="SELECT * FROM users_request_package_detail WHERE userid='$KULLANICI_ID' AND package_type='{$_GET["ihsan"]}' AND  status='1' AND  users_request_packageid IS NULL ";

                                    $hello=verilericoklucek($sql);
                                    foreach ($hello as $row) {
                                        $ISTEMBILGILERI=singular("processes","id",$row["request_id"]);
                                        ?>
                                        <tr>
                                            <th><?PHP ECHO $ISTEMBILGILERI["transaction_name"]; ?></th>
                                            <th><?PHP ECHO $ISTEMBILGILERI["icd_on_code"]; ?></th>
                                            <th><button type="button" islem="SIL" islem_id="<?PHP ECHO $row["id"]; ?>"  KULLANICI_ID="<?php echo $KULLANICI_ID; ?>"   class="sagaekle<?PHP ECHO $_GET["ihsan"] ;?> btn sil-btn waves-effect waves-light">Sil</button></th>
                                        </tr>
                                    <?PHP }
                                    } ?>
                                    </tbody>
                                </table></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>
                        <button type="button"  id="paketiolustur<?PHP ECHO $_GET["ihsan"] ;?>" class=" btn up-btn waves-effect waves-light">Paketi Oluştur</button>
                    </div></FORM>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->


        </div>
        <div class="modal fade bd-example-modal" id="yeniistemekle<?PHP ECHO $_GET["ihsan"] ;?>" role="dialog">

            <div class="modal-dialog" style=" width: 75%; max-width: 75%; ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Radyoloji Listesi</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" >
                        <div class="card-body">

                            <div class="col-md-12 row">
                                <div class="col-md-12">
                                    <script>
                                        function showResults(str) {
                                            var protokolno = $(this).attr('protokolno');
                                            $.get( "ajax/istemara.php?q="+str+"&protokolno=<?php echo $protokolno; ?>&patients=<?php echo $hasta_bilgileri["id"]; ?>&tip=tekliekle&islemcik=<?PHP ECHO 'rad' ;?>&tip=tekliekle&ihsan=<?PHP ECHO rad_grup_id; ?>", { protokolno:protokolno },function(getVeri){
                                                $('#yenipaketeklelistele<?PHP ECHO $_GET["ihsan"] ;?>').html(getVeri);
                                            });
                                        }
                                    </script>
                                    <input autocomplete="off"  onkeyup="showResults(this.value)" style=" height: 45px; padding: 20px; " required type="text" class="form-control"
                                           placeholder="Tanı kodu veya tanı adıyla arayınız"
                                           name="tani_adi" id="basicpill-firstname-input">

                                    <table id="yenipaketeklelistele<?PHP ECHO $_GET["ihsan"] ;?>" class="table table-striped table-bordered"    >
                                        <thead>
                                        <tr>
                                            <th>ICD 10 KODU</th>
                                            <th>Adı </th>
                                            <th>Ücret</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <?php


                                        $sql ="select count(DISTINCT process_name),processes.id as hizmet_id,processes.process_name as hizmet_adi,processes_price.price as hizmet_ucret,
                                           official_code as hizmet_kodu,processes.process_group_id as hizmet_grup_id from processes
                                             inner join process_group on processes.process_group_id = process_group.id
                                             inner join processes_price on processes.id = processes_price.processes_id
                                        where process_group.id='".rad_grup_id."' group by process_name,hizmet_id,hizmet_ucret,hizmet_kodu,hizmet_grup_id order by process_name fetch first 7 rows only";

                                        $hello=verilericoklucek($sql);
                                        foreach ((array) $hello as $row) {

                                            $fiyatgetir=kurumagoreistemucreti($hasta_bilgileri["institution"],$hasta_bilgileri["social_assurance"],$row["id"]);
                                            ?>
                                            <tr>

                                                <th><?php echo $row["hizmet_kodu"]; ?></th>
                                                <th><?php echo $row["hizmet_adi"]; ?></th>
                                                <td><?php echo $row["hizmet_ucret"]; ?></td>
                                                <th><button type="button" islem="tekekle"  icnd="rad" kullanici_id="<?php echo $KULLANICI_ID; ?>"    grup_id="<?PHP ECHO $row["hizmet_grup_id"]; ?>"   protokolno="<?php echo $protokolno; ?>" tip="tekliekle"   id="<?PHP ECHO $row["hizmet_id"]; ?>"    class="istemekle<?PHP ECHO $_GET["ihsan"]; ?> btn up-btn waves-effect waves-light">Ekle</button></th>

                                            </tr>
                                        <?PHP } ?>
                                        </tbody>
                                    </table></div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>

                    </div></FORM>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->


        </div>

        <div class="table-responsive">

<!--            <div class="row">-->
<!--                <div class="col-9">-->
<!--                    --><?php
//                    $hizlipaketlistele=yetkisorgula($kullanicininidsi,"hizlipaketlistele");
//                    $hizlipaketolustur=yetkisorgula($kullanicininidsi,"hizlipaketolustur");
//                    if($hizlipaketlistele){ ?>
<!--                        <button type="button" --><?php //hekimeaitolayankayitsorgula($KULLANICIBILGILERI["doctor"],$kullanicininidsi); ?><!-- data-bs-toggle="modal" data-bs-target="#hizlipaketlerigetir--><?PHP //ECHO $_GET["ihsan"] ;?><!--"  protokolno="--><?php //echo $_GET["protokolno"]; ?><!--" class="hizlipaketlistelebutonistem btn btn-warning waves-effect waves-light">-->
<!--                            <i class="bx bx-error font-size-16 align-middle me-2"></i> Hızlı Paket Listele-->
<!--                        </button>-->
<!--                    --><?php //}    if($hizlipaketolustur){ ?>
<!--                        <button type="button" --><?php //hekimeaitolayankayitsorgula($KULLANICIBILGILERI["doctor"],$kullanicininidsi); ?><!--   data-bs-toggle="modal" data-bs-target="#hizlipaketolustur--><?PHP //ECHO $_GET["ihsan"] ;?><!--"   class="btn btn-warning waves-effect waves-light" style=" color: #fff; background-color: #1f3e46; border-color: #1f3e46; "> <i class="bx bx-error font-size-16 align-middle me-2"></i> Hızlı Paket Oluştur </button>-->
<!--                    --><?php //} ?>
<!--                </div>-->
<!---->
<!--                <div class="col-3">-->
<!---->
<!---->
<!--                    <button --><?php //hekimeaitolayankayitsorgula($KULLANICIBILGILERI["doctor"],$kullanicininidsi); ?><!-- type="button" data-bs-toggle="modal" data-bs-target="#yeniistemekle--><?PHP //ECHO $_GET["ihsan"] ;?><!--" class="btn btn-dark waves-effect waves-light">-->
<!--                        Radyoloji Ekle-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->

            <div class="istemlistebody">
                <script>
                    var protokolno="<?php  echo $protokolno; ?>";
                    var ihsan="<?php  echo $_GET['ihsan']; ?>";
                    $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=radhizmetlistesi", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {

                        $('.istemlistebody').html(getveri);

                    });
                </script>
            </div>
<!--            <div class="ihsan" style=" height: 20em; overflow: auto; ">-->
<!--                <table class="table table-bordered border-primary mb-0">-->
<!---->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th>İşlem Tarihi</th>-->
<!--                        <th>Tetkik kodu</th>-->
<!--                        <th>Adı</th>-->
<!--                        <th>Adet</th>-->
<!--                        <th>İşlem Fiyat</th>-->
<!--                        <th>Hizmet Bedeli</th>-->
<!--                        <th>Paket</th>-->
<!--                        <th>İstem DR</th>-->
<!--                        <th>İşlem DR</th>-->
<!--                        <th>Sonuç</th>-->
<!--                        <th>İşlem</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    --><?php
//                    $hastaistemlerigetir = "SELECT patient_prompts.id AS hastaistemlerid,patient_prompts.*,transaction_definitions.* FROM patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}'  and patient_prompts.status='1' AND patient_prompts.group_id='".rad_grup_id."' AND transaction_definitions.definition_name='RAD'";
////var_dump($hastaistemlerigetir);
//                    $hello=verilericoklucek($hastaistemlerigetir);
//                    foreach ($hello as $rowa) {
////            $rowa["UCRET"] = number_format($rowa["UCRET"], 2, ',', '.')
//                        $TOPLAMADET=$TOPLAMADET+$rowa["piece"];
//                        $TOPLAMUCRET=$TOPLAMUCRET+$rowa["fee"];
//                        $TOPLAMHIZMET_BEDELI=$TOPLAMHIZMET_BEDELI+$rowa["service_fee"];
//                        $ODEME_YAPILDI=$rowa["payment_completed"];
//
//                        ?>
<!--                        <tr --><?php //if($ODEME_YAPILDI==1){?><!-- style="background: #3980c0;color: white;" --><?PHP //} ?><!-- >-->
<!--                            <td>--><?php //echo nettarih($rowa["request_date"]); ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["budget_code"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["request_name"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["piece"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["fee"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["service_fee"]; ?><!--</td>-->
<!--                            <td>--><?php //if($rowa["package_id"]!=""){ echo "EVET";} ?><!--</td>-->
<!--                            <td>--><?php // $ISTEMIYAPANKULLANICIGETIR=singular("users","id",$rowa["action_doer_userid"]); ECHO $ISTEMIYAPANKULLANICIGETIR["name_surname"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["action_doer_userid"]; ?><!--</td>-->
<!--                            <td>--><?php //echo $rowa["request_resulted_datetime"]; ?><!--</td>-->
<!--                            <td>--><?php //if($ODEME_YAPILDI!=1){?><!--<button --><?php //hekimeaitolayankayitsorgula($KULLANICIBILGILERI["doctor"],$kullanicininidsi); ?><!-- type="button" islem="sil" protokolno="--><?PHP //ECHO $protokolno; ?><!--"   islem_id="--><?php //echo $rowa["hastaistemlerid"]; ?><!--" class="istemsilrad btn btn-danger waves-effect waves-light">-->
<!--                                        <i class="bx bx-block font-size-16 align-middle me-2"></i>-->
<!--                                    </button>--><?PHP //} ?><!--</td>-->
<!--                        </tr>-->
<!--                    --><?php //} ?>
<!--                    </tbody>-->
<!---->
<!--                </table>-->
<!--            </div>-->
<!--            <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">-->
<!---->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th> </th>-->
<!--                    <th> </th>-->
<!--                    <th> </th>-->
<!--                    <th>Toplam Adet</th>-->
<!--                    <th>Toplam Ücret</th>-->
<!--                    <th>Toplam Hizmet Bedeli</th>-->
<!--                    <th> </th>-->
<!--                    <th> </th>-->
<!--                    <th> </th>-->
<!--                    <th> </th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!---->
<!--                <tr>-->
<!--                    <td style=" FONT-SIZE: 15px; font-weight: bold; ">TOPLAM</td>-->
<!--                    <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>-->
<!--                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>-->
<!--                    <td>--><?php //echo $TOPLAMADET; ?><!--</td>-->
<!--                    <td>--><?php //echo $TOPLAMUCRET;  echo " ";  echo sistem_para_birimi;?><!-- </td>-->
<!--                    <td>--><?php //echo $TOPLAMHIZMET_BEDELI; echo " "; echo sistem_para_birimi; ?><!--</td>-->
<!--                    <td>--><?php //if($rowa["package_id"]!=""){ echo "EVET";} ?><!--</td>-->
<!--                    <td>--><?php //echo $rowa["action_doer_userid"]; ?><!--</td>-->
<!--                    <td>--><?php //echo $rowa["action_doer_userid"]; ?><!--</td>-->
<!--                    <td>--><?php //echo $rowa["request_resulted_datetime"]; ?><!--</td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!---->
<!--            </table>-->


        </div>

        <script>

            $(document).ready(function () {

                $(".istemekle<?PHP ECHO $_GET["ihsan"];?>").click(function () {
                    var protokolno = $(this).attr('protokolno');
                    var id = $(this).attr('id');
                    var tip = $(this).attr('tip');
                    var icnd = $(this).attr('icnd');
                    var grup_id = $(this).attr('grup_id');
                    $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getVeri) {
                 
                        if(getVeri) {
                         
                            alert('liste: '+icnd);
                            console.log("ihsan" +getVeri);
                            if(icnd=="istemler") {

                                $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:<?PHP ECHO $_GET["ihsan"];?>}, function (getveri) {

                                    $('.istemlistebody').html(getveri);

                                });
                            }else if(icnd=="lab") {
                                $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=labhizmetlistesi", {protokolno: protokolno,ihsan:grup_id}, function (getveri) {

                                    $('.istemlistebody').html(getveri);

                                });
                            }else if(icnd=="rad") {
                                $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=radhizmetlistesi", {protokolno: protokolno,ihsan:grup_id}, function (getveri) {

                                    $('.istemlistebody').html(getveri);

                                });
                            }
                            $('.modal-backdrop').remove();

                        }else{
                            alertify.error("Eklerken hata oluştu! : " + getVeri);

                        }

                    });
                });

                //$(".hizlipaketlistelebutonistem").click(function () {
                //    alert('geldi');
                //    var protokolno = $(this).attr('protokolno');
                //    var radid="<?php //echo rad_grup_id; ?>//";
                //    $.get("ajax/hastahizlipaketlistele.php?islem=rad", {protokolno: protokolno,ihsan:radid}, function (getVeri) {
                //
                //        $('#hastahizlipaketlisteleislem<?PHP //ECHO $_GET["ihsan"] ;?>//').html(getVeri);
                //    });
                //});

                $(".paketekle").click(function () {
                    var protokolno = $(this).attr('protokolno');
                    var tcno = $(this).attr('tcno');
                    var tip = $(this).attr('tip');
                    $.get("ajax/tanilistesigetir.php", {protokolno: protokolno, tcno: tcno, tip: tip}, function (getVeri) {
                        $('#tanigeldi').html(getVeri);
                    });
                });


                $(".gruppaketekle").click(function () {
                    var protokolno = $(this).attr('protokolno');
                    var id = $(this).attr('id');
                    var tip = $(this).attr('tip');
                    $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip}, function (getVeri) {

                        $("#hizlipaketlerigetir<?PHP ECHO $_GET["ihsan"] ;?> .close").click();
                        $('.modal-backdrop').remove();

                        // $('#hastaistemlericerik').html(getVeri);
                        $.get( "ajax/hastaistemlerilistele.php", { protokolno:protokolno },function(getVeri){

                            $('#hastaistemlericerik').html(getVeri);

                        });
                    });
                });

                // $(".istemsilrad").click(function () {
                //     var islem_id = $(this).attr('islem_id');
                //     var islem = $(this).attr('islem');
                //     var protokolno = $(this).attr('protokolno');
                //     $.get("ajax/hastadetay/hastaradyolojilistele.php", {islem_id: islem_id, islem: islem, protokolno: protokolno }, function (getVeri) {
                //
                //         $('#hastaistemlericerik').html(getVeri);
                //
                //     });
                // });


                $(".sagaekle<?PHP ECHO $_GET["ihsan"] ;?>").click(function () {
                    var kullanici_id = $(this).attr('KULLANICI_ID');
                    var islem_id = $(this).attr('islem_id');
                    var islem = $(this).attr('islem');
                    $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:"<?php echo rad_grup_id; ?>"}, function (getVeri) {
                        $('#kullaniciisteklistesi<?PHP ECHO $_GET["ihsan"] ;?>').html(getVeri);

                    });
                });


                $("#paketiolustur<?PHP ECHO $_GET["ihsan"] ;?>").on("click", function(){ // buton idli elemana tıklandığında
                    var gonderilenform<?PHP ECHO $_GET["ihsan"] ;?> = $("#gonderilenform<?PHP ECHO $_GET["ihsan"] ;?>").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı

                    $.ajax({
                        url:'ajax/hastadetay/hastaradyolojilistele.php?protokolno=<?php echo $protokolno; ?>', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST', // post metodu ile
                        data:gonderilenform<?PHP ECHO $_GET["ihsan"] ;?>, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                        success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                            $('#hastaistemlericerik').html(e);
                            $("#hizlipaketolustur<?PHP ECHO $_GET["ihsan"] ;?> .close").click();
                            $('.modal-backdrop').remove();


                        }
                    });
                });
            });
        </script></div>
<?php } ?>