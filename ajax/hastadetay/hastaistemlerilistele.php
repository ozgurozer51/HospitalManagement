<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$kullanicininidsi=$_SESSION["id"];
$labid=lab_grup_id;
$radid=rad_grup_id;
$istem=tek("select * from transaction_definitions where definition_name='istemler' and definition_type='HIZMET_PAKET_TURLERI'");
$istemid=$istem['id'];
if($kullanicininidsi){
    $protokolno=$_GET["protokolno"];
    $kullanici_id=$_SESSION["id"];
    $kullanicibilgileri=singular("patient_registration","id",$protokolno);
    if($_GET["ihsan"]==""){
        $_GET["ihsan"]='istemler';
    }

    $tanisorgula=tek("select * from patient_record_diagnoses where protocol_number='$protokolno' and status='1' order by id desc");
    if($tanisorgula["id"]=="")
    {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="mdi mdi-block-helper me-2"></i>
                                            i̇stem ekleyebilmeniz için tanı girmeniz  gerekmektedir. lütfen giriş işlemini tamamladıktan sonra tekrar deneyiniz
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                                        </div>';
    }
    else{
        if($_POST["islem"]=="tanimla"){
            $_POST["userid"]=$kullanici_id;
            $_POST["insert_datetime"]=$gunceltarih;
            $_POST["status"]='1';
            $_POST["package_type"]=$istemid;
            unset($_POST["islem"]);

            $kayitvarmisorbaim=tek("select * from users_request_package where insert_datetime='$gunceltarih' and status='1' and userid='$kullanici_id' and package_type='{$_POST["package_type"]}' order by id desc");
            if($kayitvarmisorbaim){

            }else{
                $ekleman=direktekle("users_request_package",$_POST);
                var_dump($ekleman);
                if($ekleman){
                    $sonidsql=tek("select * from users_request_package where userid='$kullanici_id' order by id desc");
                    $sonid=$sonidsql["id"];
                    ?>

                    <script>  alertify.success("kullanıcılar i̇stem paketi eklendi");</script>
                    <?php
                    $guncelle=guncelle("update users_request_package_detail set users_request_packageid='$sonid' where userid='$kullanici_id' and status='1' and users_request_packageid  is null");

                    var_dump($guncelle);
                    if($guncelle){?>

                        <script>  alertify.success("ekleme başarılı");</script>
                        <?php
                    }else{
                        ?>

                        <script>  alertify.error("ekleme başarısız. ");</script>
                        <?php
                    }
                }
            }
        }
        if($_GET["islem"]=="sil"){
            //canceldetail($tablo,$sutun,$id,$detay,$silme,$tarih)
            $delete_detail="istem iptal islemi";
            $delete_userid=$_SESSION['id'];
            $delete_datetime=$simdikitarih;
            $silmeislemibaslat=canceldetail("patient_prompts","id",$_GET["islem_id"],$delete_detail,$delete_userid,$delete_datetime);
            if ($silmeislemibaslat) { ?>
                <script>
                    alertify.success('Silme işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.danger('Silme işlemi Başarisiz');
                </script>
            <?php }
        }
        ?>
        <script>
            function showresults(str) {
                var protokolno = $(this).attr('protokolno');
                $.get( "ajax/istemara.php?q="+str+"&protokolno=<?php echo $protokolno; ?>&patientid=<?php echo $kullanicibilgileri["patient_id"]; ?>&tip=tekliekle&islemcik=istemler", { protokolno:protokolno },function(getveri){
                    $('#yenipaketeklelistelec').html(getveri);
                });

            }
        </script>
        <div id="ihsancunedioglu">

            <script>
                $(document).ready(function() {
                    //$('#hizlipaketlerigetirtable<?php //echo $_GET["ihsan"] ;?>//').datatable( {
                    //    pagelength: 20,
                    //    "language": {
                    //        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    //    },
                    //
                    //} );
                } );
            </script>
            <div class="modal fade bd-example-modal" id="hizlipaketlerigetir<?php echo $_GET["ihsan"] ;?>" role="dialog">
                <div class="modal-dialog modal-xl" >
                    <div id="hastahizlipaketlisteleislem<?php echo $_GET["ihsan"] ;?>"></div>
                </div>
            </div>


            <div class="modal fade modal-lg" id="hizlipaketolustur<?php echo $_GET["ihsan"] ;?>" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"   >
                <div class="modal-dialog"  style="width: 96%; max-width: 96%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Hazır Liste Tanımla</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" >
                            <div class="card-body">

                                <form id="gonderilenform">
                                    <div class="mb-3 row">
                                        <label for="example-text-input" class="col-md-2 col-form-label">Liste Adı</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="package_name" placeholder="Liste adı giriniz.." id="example-text-input">
                                            <input class="form-control" type="hidden" name="islem" value="tanimla" id="example-text-input">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="example-text-input" class="col-md-2 col-form-label">Liste Açıklaması</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" type="text" name="package_explain" placeholder="Liste açıklaması giriniz.." id="example-text-input"></textarea>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-12 row">
                                <div class="col-md-4">
                                    <center>
                                        <h4>Tanımladığınız İ̇stem Listesi</h4>
                                    </center>

                                    <table id="hizlipaketlerigetirtable<?php echo $_GET["islem"] ;?>" class="table table-striped table-bordered"   >
                                        <thead>
                                        <tr>
                                            <th>Paket Adı </th>
                                            <th>Paket Açıklama</th>
                                            <th>Eklenme Tarihi</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <?php

                                        $kullanici_id=$_SESSION["id"];

                                        $sql ="select * from users_request_package where userid='$kullanici_id' and status='1'";

                                        $hello=verilericoklucek($sql);
                                        foreach ($hello as $row) {
                                            ?>
                                            <tr>

                                                <th ><?php echo $row["package_name"]; ?></th>
                                                <td  ><?php echo $row["package_explain"]; ?></td>
                                                <td ><?php echo nettarih($row["insert_datetime"]); ?></td>
                                                <td  ><button type="button" tip="grup_ekle" icnd="istemler" ihsan="<?php echo $_GET["islem"]; ?>" id="<?php echo $row["id"]; ?>" protokolno="<?php echo $protokolno; ?>"  class="istemduzenle btn up-btn waves-effect waves-light">
                                                        Düzenle
                                                    </button></td>

                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table></div>
                                <div class="col-md-4">
                                    <center>
                                        <h4>İ̇stem Ve Tetkik Listesi</h4>
                                    </center>

                                    <script>
                                        function showresult(str) {
                                            var protokolno = $(this).attr('protokolno');
                                            $.get( "ajax/istemara.php?q="+str+"&protokolno=<?php echo $protokolno; ?>&patientid=<?php echo $kullanicibilgileri["patient_id"]; ?>&tip=sagaekle&islemcik=istemler", { protokolno:protokolno },function(getveri){
                                                $('#hizlipaketlerigetirtablee<?php echo $_GET["ihsan"] ;?>').html(getveri);
                                            });
                                        }
                                    </script>
                                    <input autocomplete="off"  onkeyup="showresult(this.value)" style=" height: 45px; padding: 20px; " required type="text" class="form-control"
                                           placeholder="tanı kodu veya tanı adıyla arayınız"
                                           name="tani_adi" id="basicpill-firstname-input">

                                    <table id="hizlipaketlerigetirtablee<?php echo $_GET["ihsan"] ;?>" class="table table-striped table-bordered"    >
                                        <thead>
                                        <tr>
                                            <th>İcd 10 Kodu</th>
                                            <th>Adı </th>
                                            <th>Ücret</th>
                                            <th>İ̇şlem</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <?php


                                        $sql ="select count(DISTINCT process_name),processes.id as himet_id,processes.process_name as himet_adi,processes_price.price as hizmet_ucret,
                                           official_code as himet_kodu,processes.process_group_id as hizmet_grup_id from processes
                                             inner join process_group on processes.process_group_id = process_group.id
                                             inner join processes_price on processes.id = processes_price.processes_id
                                    where process_group.id in ($labid,$radid) group by process_name,himet_id,hizmet_ucret,himet_kodu,hizmet_grup_id order by process_name fetch first 7 rows only";

                                        $hello=verilericoklucek($sql);
                                        foreach ($hello as $row) {

                                            $fiyatgetir=kurumagoreistemucreti($kullanicibilgileri["institution"],$kullanicibilgileri["social_assurance"],$row["id"]);
                                            ?>
                                            <tr>

                                                <th><?php echo $row["himet_kodu"]; ?></th>
                                                <th><?php echo $row["himet_adi"]; ?></th>
                                                <td><?php echo $row["hizmet_ucret"]; ?></td>
                                                <th><button type="button" islem="ekle"  kullanici_id="<?php echo $kullanici_id; ?>"  islem_id="<?php echo $row["himet_id"]; ?>" tipi="<?php echo $row["hizmet_grup_id"]; ?>" class="sagaekle btn up-btn waves-effect waves-light">ekle</button></th>

                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table></div>

                                <div class="col-md-4">
                                    <center>
                                        <h4>Listeniz</h4>
                                    </center>
                                    <table id="kullaniciisteklistesi<?php echo $_GET["ihsan"];?>" class="table table-striped table-bordered"    >
                                        <thead>
                                        <tr>
                                            <th>Adı </th>
                                            <th>İcd 10 Kodu</th>
                                            <th>İ̇şlem</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <?php

                                        $sql ="select * from users_request_package_detail where userid='$kullanici_id' and package_type in ($labid,$radid) and  status='1' and  users_request_packageid is null ";

                                        $hello=verilericoklucek($sql);
                                        foreach ($hello as $row) {
                                            $istembilgileri=singularactive("processes","id",$row["request_id"]);
                                            ?>
                                            <tr>

                                                <th><?php echo $istembilgileri["process_name"]; ?></th>
                                                <th><?php echo $istembilgileri["official_code"]; ?></th>
                                                <th><button type="button" islem="sil" islem_id="<?php echo $row["id"]; ?>"  kullanici_id="<?php echo $kullanici_id; ?>"   class="sagaekle btn sil-btn waves-effect waves-light">Sil</button></th>

                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>
                            <button type="button"  id="paketiolustur" class=" btn up-btn waves-effect waves-light" data-bs-dismiss="modal">Paketi Oluştur</button>
                        </div></form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->


            </div>
            <div class="modal fade bd-example-modal" id="yeniistemekle<?php echo $_GET["ihsan"] ;?>" role="dialog">

                <div class="modal-dialog" style=" width: 75%; max-width: 75%; ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">İstem Listesi</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" >
                            <div class="card-body">

                                <div class="col-md-12 row">
                                    <div class="col-md-12">

                                        <input autocomplete="off"  onkeyup="showresults(this.value)" style=" height: 45px; padding: 20px; " required type="text" class="form-control"
                                               placeholder="aaatanı kodu veya tanı adıyla arayınız"
                                               name="tani_adi" id="basicpill-firstname-input">

                                        <table id="yenipaketeklelistelec" class="table table-striped table-bordered"    >
                                            <thead>
                                            <tr>
                                                <th>İcd 10 Kodu</th>
                                                <th>Adı </th>
                                                <th>Ücret</th>
                                                <th>İ̇şlem</th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php



                                            $sql ="select count(DISTINCT process_name),processes.id as hizmet_id,processes.process_name as hizmet_adi,processes_price.price as hizmet_ucret,
                                           official_code as hizmet_kodu,processes.process_group_id as hizmet_grup_id from processes
                                             inner join process_group on processes.process_group_id = process_group.id
                                             inner join processes_price on processes.id = processes_price.processes_id
                                    where process_group.id in ($labid,$radid) group by process_name,hizmet_id,hizmet_ucret,hizmet_kodu,hizmet_grup_id order by process_name fetch first 7 rows only";

                                            $hello=verilericoklucek($sql);
                                            foreach ($hello as $row) {

                                                $fiyatgetir=kurumagoreistemucreti($kullanicibilgileri["institution"],$kullanicibilgileri["social_assurance"],$row["id"]);
                                                ?>
                                                <tr>

                                                    <th><?php echo $row["hizmet_kodu"]; ?></th>
                                                    <th><?php echo $row["hizmet_adi"]; ?></th>
                                                    <td class="fw-bold text-center"><?php echo $row["hizmet_ucret"]." ₺"; ?></td>
                                                    <th><button type="button" islem="tekekle"    kullanici_id="<?php echo $kullanici_id; ?>"  grup_id="<?php echo $row["hizmet_grup_id"]; ?>"   protokolno="<?php echo $protokolno; ?>" tip="tekliekle"   id="<?php echo $row["hizmet_id"]; ?>"    class="istemekle<?php echo $_GET["ihsan"] ;?> btn up-btn waves-effect waves-light">Ekle</button></th>

                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table></div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>

                        </div></form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->


            </div>

            <div class="table-responsive">

<!--                <div class="row">-->
<!--                    <div class="col-9">-->
<!--                        --><?php
//                        $hizlipaketlistele=yetkisorgula($kullanicininidsi,"hizlipaketlistele");
//                        $hizlipaketolustur=yetkisorgula($kullanicininidsi,"hizlipaketolustur");
//                        if($hizlipaketlistele){ ?>
<!--                            <button  --><?php //hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?><!--  type="button" data-bs-toggle="modal" data-bs-target="#hizlipaketlerigetir--><?php //echo $_GET["ihsan"] ;?><!--"  protokolno="--><?php //echo $_GET["protokolno"]; ?><!--" class="hizlipaketlistelebuton--><?php //echo $_GET["ihsan"] ;?><!-- btn btn-warning waves-effect waves-light">-->
<!--                                <i class="bx bx-error font-size-16 align-middle me-2"></i> hızlı paket listele-->
<!--                            </button>-->
<!--                        --><?php //}    if($hizlipaketolustur){ ?>
<!--                            <button type="button"  --><?php //hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?><!--  data-bs-toggle="modal" data-bs-target="#hizlipaketolustur--><?php //echo $_GET["ihsan"] ;?><!--"   class="btn btn-warning waves-effect waves-light" style=" color: #fff; background-color: #1f3e46; border-color: #1f3e46; "> <i class="bx bx-error font-size-16 align-middle me-2"></i> hızlı paket oluştur </button>-->
<!--                        --><?php //} ?>
<!--                    </div>-->
<!---->
<!--                    <div class="col-3">-->
<!---->
<!---->
<!--                        <button --><?php //hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?><!-- type="button" data-bs-toggle="modal" data-bs-target="#yeniistemekle--><?php //echo $_GET["ihsan"] ;?><!--" class="btn btn-dark waves-effect waves-light">-->
<!--                            i̇stem ekle-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->

                <div class="istemlistebody mt-2">
                    <script>
                        var protokolno="<?php  echo $protokolno; ?>";
                        var ihsan="<?php  echo $_GET['ihsan']; ?>";
                        $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {

                            $('.istemlistebody').html(getveri);

                        });
                    </script>
                </div>
<!--                <div class="ihsan" style=" height: 20em; overflow: auto; ">-->
<!--                    <table class="table table-bordered border-primary mb-0">-->
<!---->
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th>i̇şlem tarihi</th>-->
<!--                            <th>tetkik kodu</th>-->
<!--                            <th>adı</th>-->
<!--                            <th>adet</th>-->
<!--                            <th>i̇şlem fiyat</th>-->
<!--                            <th>hizmet bedeli</th>-->
<!--                            <th>paket</th>-->
<!--                            <th>i̇stem dr</th>-->
<!--                            <th>i̇şlem dr</th>-->
<!--                            <th>sonuç</th>-->
<!--                            <th>i̇şlem</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
<!--                        <tbody>-->
<!--                        --><?php
//                        $istemlersilyetkisi=yetkisorgula($kullanicininidsi,"istemlersilyetkisi");
////                        if($_GET["ihsan"]=="istemler"){
////                            $hastaistemlerigetir="select * from patient_prompts where protocol_number='{$_GET["protokolno"]}' and  status!='2' ";
////                        }else  if($_GET["ihsan"]=="lab"){
////                            $hastaistemlerigetir=" select * from patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}' and  patient_prompts.group_id=".lab_grup_id." and patient_prompts.status!='2' and transaction_definitions.definition_name='lab'";
////                        }else  if($_GET["ihsan"]=="rad"){
////                            $hastaistemlerigetir=" select * from patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}'   and patient_prompts.group_id=".rad_grup_id." and patient_prompts.status!='2' and transaction_definitions.definition_name='rad'";
////                        }
//                        $hastaistemlerigetir="select * from patient_prompts where protocol_number='{$_GET["protokolno"]}' and  status='1' ";
//
//                        $hello=verilericoklucek($hastaistemlerigetir);
//                        foreach ($hello as $rowa) {
//
//                            $toplamadet=$toplamadet+$rowa["piece"];
//                            $toplamucret=$toplamucret+$rowa["fee"];
//                            $toplamhizmet_bedeli=$toplamhizmet_bedeli+$rowa["service_fee"];
//                            $odeme_yapildi=$rowa["payment_completed"];
//
//                            ?>
<!--                            <tr --><?php //if($odeme_yapildi==1){?><!-- style="background: #ffffff;color: black;" --><?php //}else{ ?><!-- style="background-color:#ff0000; color:white;" --><?php //} ?><!--  ?> -->
<!--                                <td>--><?php //echo nettarih($rowa["request_date"]); ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["budget_code"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["request_name"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["piece"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["fee"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["service_fee"]; ?><!--</td>-->
<!--                                <td>--><?php //if($rowa["package_id"]!=""){ echo "evet";} ?><!--</td>-->
<!--                                <td>--><?php // $istemiyapankullanicigetir=singular("users","id",$rowa["request_userid"]); echo $istemiyapankullanicigetir["name_surname"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["action_doer_userid"]; ?><!--</td>-->
<!--                                <td>--><?php //echo $rowa["request_resulted_datetime"]; ?><!--</td>-->
<!--                                --><?php // if($rowa["request_userid"]==$kullanicininidsi or $istemlersilyetkisi!=""){?>
<!--                                    <td>--><?php //if($odeme_yapildi!=1){?><!--<button --><?php //hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?><!-- type="button" islem="sil" protokolno="--><?php //echo $protokolno; ?><!--"   islem_id="--><?php //echo $rowa["id"]; ?><!--" class="istemsil btn btn-danger waves-effect waves-light">-->
<!--                                                <i class="bx bx-block font-size-16 align-middle me-2"></i>-->
<!--                                            </button>--><?php //} ?><!--</td>-->
<!--                                --><?php //} ?>
<!--                            </tr>-->
<!--                        --><?php //} ?>
<!--                        </tbody>-->
<!---->
<!--                    </table>-->
<!--                </div>-->
<!--                <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">-->
<!---->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th> </th>-->
<!--                        <th> </th>-->
<!--                        <th> </th>-->
<!--                        <th>toplam adet</th>-->
<!--                        <th>toplam ücret</th>-->
<!--                        <th>toplam hizmet bedeli</th>-->
<!--                        <th> </th>-->
<!--                        <th> </th>-->
<!--                        <th> </th>-->
<!--                        <th> </th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!---->
<!--                    <tr>-->
<!--                        <td style=" font-size: 15px; font-weight: bold; ">toplam</td>-->
<!--                        <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>-->
<!--                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>-->
<!--                        <td>--><?php //echo $toplamadet; ?><!--</td>-->
<!--                        <td>--><?php //echo $toplamucret;  echo " ";  echo sistem_para_birimi;?><!-- </td>-->
<!--                        <td>--><?php //echo $toplamhizmet_bedeli; echo " "; echo sistem_para_birimi; ?><!--</td>-->
<!--                        <td>--><?php //if($rowa["package_id"]!=""){ echo "evet";} ?><!--</td>-->
<!--                        <td>--><?php //echo $rowa["request_userid"]; ?><!--</td>-->
<!--                        <td>--><?php //echo $rowa["islemi_yapan_kullanici_id"]; ?><!--</td>-->
<!--                        <td>--><?php //echo $rowa["request_resulted_datetime"]; ?><!--</td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!---->
<!--                </table>-->


            </div>

            <script>

                $(document).ready(function () {
                    //$(".hizlipaketlistelebuton<?php //echo $_GET["ihsan"] ;?>//").click(function () {
                    //    var protokolno = $(this).attr('protokolno');
                    //    var ihsan="<?php //echo $istemid ?>//";
                    //    $.get("ajax/hastahizlipaketlistele.php?islem=istemler", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {
                    //        $('#hastahizlipaketlisteleislem<?php //echo $_GET["ihsan"] ;?>//').html(getveri);
                    //    });
                    //});


                    $("body").off("click", ".paketekle").on("click", ".paketekle", function (e) {
                        var protokolno = $(this).attr('protokolno');
                        var tcno = $(this).attr('tcno');
                        var tip = $(this).attr('tip');
                        $.get("ajax/tanilistesigetir.php", {
                            protokolno: protokolno,
                            tcno: tcno,
                            tip: tip
                        }, function (getveri) {
                            $('#tanigeldi').html(getveri);
                        });
                    });

                    $("body").off("click", ".istemekle<?php echo $_GET["ihsan"] ;?>").on("click", ".istemekle<?php echo $_GET["ihsan"] ;?>", function (e) {
                        var protokolno = $(this).attr('protokolno');
                        var id = $(this).attr('id');
                        var tip = $(this).attr('tip');
                        var ihsan='<?php echo $_GET["ihsan"]; ?>';
                        var grup_id = $(this).attr('grup_id');
                        $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {
                            if(getveri) {
                                alertify.success("kullanıcılar i̇stem paketi eklendi");

                                $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:ihsan}, function (getveri) {
                                    $('.istemlistebody').html(getveri);
                                });
                            }else{
                                alertify.error("eklerken hata oluştu! : " + getveri);
                            }
                        });
                    });

                        $("body").off("click", ".gruppaketekle").on("click", ".gruppaketekle", function (e) {
                        var protokolno = $(this).attr('protokolno');
                        var id = $(this).attr('id');
                        var tip = $(this).attr('tip');
                        $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip}, function (getveri) {

                            $("#hizlipaketlerigetir<?php echo $_GET["ihsan"] ;?> .close").click();
                            $('.modal-backdrop').remove();

                            // $('#hastaistemlericerik').html(getveri);
                            $.get( "ajax/hastadetay/hastaistemlerilistele.php", { protokolno:protokolno },function(getveri){

                                $('#hastaistemlericerik').html(getveri);

                            });
                        });
                    });

                    // $(".istemsil").click(function () {
                    //     var islem_id = $(this).attr('islem_id');
                    //     var islem = $(this).attr('islem');
                    //     var protokolno = $(this).attr('protokolno');
                    //     $.get("ajax/hastadetay/hastaistemlerilistele.php", {islem_id: islem_id, islem: islem, protokolno: protokolno }, function (getveri) {
                    //
                    //         $('#hastaistemlericerik').html(getveri);
                    //
                    //     });
                    // });


                        $("body").off("click", ".sagaekle").on("click", ".sagaekle", function (e) {
                        var kullanici_id = $(this).attr('kullanici_id');
                        var islem_id = $(this).attr('islem_id');
                        var islem = $(this).attr('islem');
                        var tip=$(this).attr('tipi');
                        $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:tip}, function (getveri) {
                            $('#kullaniciisteklistesi<?php echo $_GET["ihsan"] ;?>').html(getveri);

                        });
                    });


                        $("body").off("click", "#paketiolustur").on("click", "#paketiolustur", function (e) {
                            var gonderilenform = $("#gonderilenform").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
                        $.ajax({
                            url:'ajax/hastadetay/hastaistemlerilistele.php?protokolno=<?php echo $protokolno; ?>', // serileştirilen değerleri ajax.php dosyasına
                            type:'post', // post metodu ile
                            data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                            success:function(e){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                                $('#hastaistemlericerik').html(e);
                                $("#hastaistemlericerik .close").click();
                                $('.modal-backdrop').remove();


                            }
                        });

                    });
                });
            </script>
        </div>
        </div>
        </div>
    <?php }} ?>