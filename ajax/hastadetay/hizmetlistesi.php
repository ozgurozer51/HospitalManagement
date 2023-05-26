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
$hizmetislem = $_GET['hizmetislem'];
$kullanicininidsi=$_SESSION["id"];
$hizlipaketlistele=yetkisorgula($kullanicininidsi,"hizlipaketlistele");
$hizlipaketolustur=yetkisorgula($kullanicininidsi,"hizlipaketolustur");

if($hizmetislem=="labhizmetlistesi"){
    $protokolno=$_GET['protokolno'];
    $kullanicibilgileri=singularactive("patient_registration","id",$protokolno); ?>
    <div class="ihsan">

        <table class="table table-bordered border-primary w-100" id="labhizmet<?php echo $protokolno; ?>">

            <thead>
            <tr>
                <th>İ̇şlem Tarihi</th>
                <th>Tetkik Kodu</th>
                <th>Adı</th>
                <th>Adet</th>
                <th>İ̇şlem Fiyat</th>
                <th>Hizmet Bedeli</th>
                <th>Paket</th>
                <th>İ̇stem DR</th>
                <th>İ̇şlem DR</th>
                <th>Sonuç</th>
                <th>İ̇şlem</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $hastaistemlerigetir = "select * from patient_prompts where patient_prompts.group_id='".lab_grup_id."' and protocol_number='{$_GET["protokolno"]}' and status='1'";

            $hello=verilericoklucek($hastaistemlerigetir);
            foreach ($hello as $rowa) {

                $toplamadet=$toplamadet+$rowa["piece"];
                $toplamucret=$toplamucret+$rowa["fee"];
                $toplamhizmet_bedeli=$toplamhizmet_bedeli+$rowa["service_fee"];
                $odeme_yapildi=$rowa["payment_completed"];

                ?>
                <tr>
                    <td><?php echo nettarih($rowa["request_date"]); ?></td>
                    <td><?php echo $rowa["budget_code"]; ?></td>
                    <td><?php echo $rowa["request_name"]; ?></td>
                    <td><?php echo $rowa["piece"]; ?></td>
                    <td><?php echo $rowa['fee'] ?></td>
                    <td class="text-center"><?php if($rowa["package_id"]==""){ echo "0 ₺";}else{echo $rowa["service_fee"]." ₺"; } ?></td>
                    <td><?php if($rowa["package_id"]!=""){ echo "evet";} ?></td>
                    <td><?php  $istemiyapankullanicigetir=tekil("users","id",$rowa["request_userid"]); echo $istemiyapankullanicigetir["name_surname"]; ?></td>
                    <td><?php echo $rowa["request_userid"]; ?></td>
                    <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
                    <td><button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["hekim"],$kullanicininidsi); ?> type="button" islem="sil" protokolno="<?php echo $protokolno; ?>"   islem_id="<?php echo $rowa["id"]; ?>" class="istemsillab btn btn-danger waves-effect waves-light">
                                <i class="fa fa-trash"></i>
                            </button></td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

    <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">
        <thead>
        <tr >
            <th> </th>
            <th> </th>
            <th> </th>
            <th>Toplam Adet</th>
            <th>Toplam Ücret</th>
            <th>Toplam Hizmet Bedeli</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        </thead>
        <tbody>

        <tr class="text-danger fw-bold">
            <td >Toplam</td>
            <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $toplamadet; ?></td>
            <td><?php echo $toplamucret;  echo " ";  echo sistem_para_birimi;?> </td>
            <td><?php echo $toplamhizmet_bedeli; echo " "; echo sistem_para_birimi; ?></td>
            <td><?php if($rowa["package_id"]!=""){ echo "evet";} ?></td>
            <td><?php echo $rowa["request_userid"]; ?></td>
            <td><?php echo $rowa["request_userid"]; ?></td>
            <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
            <td></td>
        </tr>
        </tbody>

    </table>

    <script>
        $('#labhizmet<?php echo $protokolno; ?>').DataTable({
            "scrollX": true,
            "scrollY": '45vh',
            "lengthChange": false,
            "pageLength": 25,
            "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                <?php if ($hizlipaketlistele){ ?>
                {
                    text: '<i class="fas fa-vial"></i> Hızlı Paket Listele',
                    className:'btn kapat-btn btn-sm text-white',
                    titleAttr:'Hastaya İstem Paketi Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {

                        var ihsan="<?php echo lab_grup_id; ?>";
                        $.get("ajax/hastahizlipaketlistele.php?islem=lab", {protokolno:<?php echo $_GET["protokolno"]; ?>,ihsan:ihsan}, function (getveri) {
                            $('#hastahizlipaketlisteleislem<?php echo $_GET["ihsan"]; ?>').html(getveri);
                        });

                    },
                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketlerigetir<?php echo $_GET["ihsan"]; ?>",
                    }
                },
                <?php }if($hizlipaketolustur){ ?>
                {
                    text: '<i class="fas fa-vial"></i> Hızlı Paket Oluştur',
                    className:'btn yeni-btn btn-sm text-white mx-1',
                    titleAttr:'Hızlı Paket Oluşturmak İçin Tıklayınız...',

                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketolustur<?php echo $_GET["ihsan"]; ?>",
                    }
                },
                <?php } ?>
                {
                    text: '<i class="fas fa-vial"></i> İstem Ekle',
                    className:'btn up-btn btn-sm text-white mx-1',
                    titleAttr:'Hastaya İstem Eklemek İçin Tıklayınız...',

                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#yeniistemekle<?php echo $_GET["ihsan"]; ?>",
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $("body").off("click", ".istemsillab").on("click", ".istemsillab", function (e) {

            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');
            var protokolno = $(this).attr('protokolno');
            $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {islem_id: islem_id, islem: islem, protokolno: protokolno }, function (getveri) {

                $('#hastaistemlericerik').html(getveri);

            });
        });
    </script>
 <?php }
if($hizmetislem=="radhizmetlistesi"){
    $protokolno=$_GET['protokolno'];
    $kullanicibilgileri=singularactive("patient_registration","id",$protokolno); ?>
    <div class="ihsan mt-2" >
        <table class="table table-bordered border-primary  w-100" id="radhizmet">

            <thead>
            <tr>
                <th>İşlem Tarihi</th>
                <th>Tetkik kodu</th>
                <th>Adı</th>
                <th>Adet</th>
                <th>İşlem Fiyat</th>
                <th>Hizmet Bedeli</th>
                <th>Paket</th>
                <th>İstem DR</th>
                <th>İşlem DR</th>
                <th>Sonuç</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody>
            <?php  $hastaistemlerigetir = "SELECT patient_prompts.id AS hastaistemlerid,patient_prompts.*,transaction_definitions.* FROM patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}'  and patient_prompts.status='1' AND patient_prompts.group_id='".rad_grup_id."' AND transaction_definitions.definition_name='RAD'";
            //var_dump($hastaistemlerigetir);
            $hello=verilericoklucek($hastaistemlerigetir);
            foreach ($hello as $rowa) {
            $rowa["UCRET"] = number_format($rowa["UCRET"], 2, ',', '.');
                $TOPLAMADET=$TOPLAMADET+$rowa["piece"];
                $TOPLAMUCRET=$TOPLAMUCRET+$rowa["fee"];
                $TOPLAMHIZMET_BEDELI=$TOPLAMHIZMET_BEDELI+$rowa["service_fee"];
                $ODEME_YAPILDI=$rowa["payment_completed"];

                ?>
                <tr >
                    <td><?php echo nettarih($rowa["request_date"]); ?></td>
                    <td><?php echo $rowa["budget_code"]; ?></td>
                    <td><?php echo $rowa["request_name"]; ?></td>
                    <td><?php echo $rowa["piece"]; ?></td>
                    <td><?php echo $rowa["fee"]; ?></td>
                    <td><?php echo $rowa["service_fee"]; ?></td>
                    <td><?php if($rowa["package_id"]!=""){ echo "EVET";} ?></td>
                    <td><?php  $ISTEMIYAPANKULLANICIGETIR=singular("users","id",$rowa["action_doer_userid"]); ECHO $ISTEMIYAPANKULLANICIGETIR["name_surname"]; ?></td>
                    <td><?php echo $rowa["action_doer_userid"]; ?></td>
                    <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
                    <td><?php if($ODEME_YAPILDI!=1){?><button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?> type="button" islem="sil" protokolno="<?PHP ECHO $protokolno; ?>"   islem_id="<?php echo $rowa["hastaistemlerid"]; ?>" class="istemsilrad btn btn-sm btn-danger"><i class="fa fa-trash fa-1x"></i></button><?PHP } ?></td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

    <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">

        <thead>
        <tr class="text-danger fw-bold">
            <th> </th>
            <th> </th>
            <th> </th>
            <th>Toplam Adet</th>
            <th>Toplam Ücret</th>
            <th>Toplam Hizmet Bedeli</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td style=" FONT-SIZE: 15px; font-weight: bold; ">TOPLAM</td>
            <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $TOPLAMADET; ?></td>
            <td><?php echo $TOPLAMUCRET;  echo " ";  echo sistem_para_birimi;?> </td>
            <td><?php echo $TOPLAMHIZMET_BEDELI; echo " "; echo sistem_para_birimi; ?></td>
            <td><?php if($rowa["package_id"]!=""){ echo "EVET";} ?></td>
            <td><?php echo $rowa["action_doer_userid"]; ?></td>
            <td><?php echo $rowa["action_doer_userid"]; ?></td>
            <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
        </tr>
        </tbody>

    </table>

    <script>
        $('#radhizmet').DataTable({
            "scrollX": true,
            "scrollY": '45vh',
            "lengthChange": false,
            "pageLength": 25,
            "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                <?php if ($hizlipaketlistele){ ?>
                {
                    text: '<i class="fas fa-radiation"></i> Hızlı Paket Listele',
                    className:'btn kapat-btn btn-sm text-white ',
                    titleAttr:'Hastaya İstem Paketi Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {

                        var radid="<?php echo rad_grup_id; ?>";
                        $.get("ajax/hastahizlipaketlistele.php?islem=rad", {protokolno: <?php echo $_GET["protokolno"]; ?>,ihsan:radid}, function (getVeri) {

                            $('#hastahizlipaketlisteleislem<?PHP ECHO $_GET["ihsan"] ;?>').html(getVeri);
                        });
                    },
                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketlerigetir<?PHP ECHO $_GET["ihsan"] ;?>",
                    }
                },
                <?php }if($hizlipaketolustur){ ?>
                {
                    text: '<i class="fas fa-radiation"></i> Hızlı Paket Oluştur',
                    className:'btn yeni-btn btn-sm text-white mx-1',
                    titleAttr:'Hızlı Paket Oluşturmak İçin Tıklayınız...',

                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketolustur<?php echo $_GET["ihsan"]; ?>",
                    }
                },
                <?php } ?>
                {
                    text: '<i class="fas fa-radiation"></i> İstem Ekle',
                    className:'btn up-btn btn-sm text-white mx-1',
                    titleAttr:'Hastaya İstem Eklemek İçin Tıklayınız...',

                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#yeniistemekle<?php echo $_GET["ihsan"]; ?>",
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

            $("body").off("click", ".istemsilrad").on("click", ".istemsilrad", function (e) {

            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');
            var protokolno = $(this).attr('protokolno');
            $.get("ajax/hastadetay/hastaradyolojilistele.php", {islem_id: islem_id, islem: islem, protokolno: protokolno }, function (getVeri) {

                $('#hastaistemlericerik').html(getVeri);

            });
        });
    </script>
 <?php }
if($hizmetislem=="istemhizmetlistesi"){
    $protokolno=$_GET['protokolno'];
    $kullanicibilgileri=singularactive("patient_registration","protocol_number",$protokolno);
    $protokolno=$kullanicibilgileri['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>
    <div class="ihsan" >
        <table class="table table-bordered border-primary  w-100" id="istemhizmet<?php echo $protokolno; ?>">

            <thead>
            <tr>
                <th>İ̇şlem Tarihi</th>
                <th>Tetkik Kodu</th>
                <th>Adı</th>
                <th>Adet</th>
                <th>İ̇şlem Fiyat</th>
                <th>Hizmet Bedeli</th>
                <th>Paket</th>
                <th>İ̇stem DR</th>
                <th>i̇şlem DR</th>
                <th>Sonuç</th>
                <th>İ̇şlem</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $istemlersilyetkisi=yetkisorgula($kullanicininidsi,"istemlersilyetkisi");
            //                        if($_GET["ihsan"]=="istemler"){
            //                            $hastaistemlerigetir="select * from patient_prompts where protocol_number='{$_GET["protokolno"]}' and  status!='2' ";
            //                        }else  if($_GET["ihsan"]=="lab"){
            //                            $hastaistemlerigetir=" select * from patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}' and  patient_prompts.group_id=".lab_grup_id." and patient_prompts.status!='2' and transaction_definitions.definition_name='lab'";
            //                        }else  if($_GET["ihsan"]=="rad"){
            //                            $hastaistemlerigetir=" select * from patient_prompts,transaction_definitions where patient_prompts.protocol_number='{$_GET["protokolno"]}'   and patient_prompts.group_id=".rad_grup_id." and patient_prompts.status!='2' and transaction_definitions.definition_name='rad'";
            //                        }
            $hastaistemlerigetir="select * from patient_prompts where protocol_number='{$_GET["protokolno"]}' and  status='1' ";

            $hello=verilericoklucek($hastaistemlerigetir);
            foreach ($hello as $rowa) {

                $toplamadet=$toplamadet+$rowa["piece"];
                $toplamucret=$toplamucret+$rowa["fee"];
                $toplamhizmet_bedeli=$toplamhizmet_bedeli+$rowa["service_fee"];
                $odeme_yapildi=$rowa["payment_completed"];

                ?>

                <tr>
                    <td><?php echo nettarih($rowa["request_date"]); ?></td>
                    <td><?php echo $rowa["budget_code"]; ?></td>
                    <td><?php echo $rowa["request_name"]; ?></td>
                    <td><?php echo $rowa["piece"]; ?></td>
                    <td><?php echo $rowa["fee"]; ?></td>
                    <td><?php echo $rowa["service_fee"]; ?></td>
                    <td><?php if($rowa["package_id"]!=""){ echo "evet";} ?></td>
                    <td><?php  $istemiyapankullanicigetir=singular("users","id",$rowa["request_userid"]); echo $istemiyapankullanicigetir["name_surname"]; ?></td>
                    <td><?php echo $rowa["action_doer_userid"]; ?></td>
                    <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
                    <?php  if($rowa["request_userid"]==$kullanicininidsi or $istemlersilyetkisi!=""){?>
                        <td><?php if($odeme_yapildi!='1'){?><button <?php hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi); ?> type="button" islem="sil" protokolno="<?php echo $protokolno; ?>"   islem_id="<?php echo $rowa["id"]; ?>" class="istemsil btn sil-btn btn-sm"><i class="fa fa-trash"></i></button><?php } ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>
    <table class="table table-bordered border-primary mb-0" style=" background: #eff0f2; ">

        <thead>
        <tr>
            <th> </th>
            <th> </th>
            <th> </th>
            <th>Toplam Adet</th>
            <th>Toplam Ücret</th>
            <th>Toplam Hizmet Bedeli</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        </thead>
        <tbody>

        <tr class="text-danger fw-bold">
            <td >Toplam</td>
            <td>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;</td>
            <td><?php echo $toplamadet; ?></td>
            <td><?php echo $toplamucret;  echo " ";  echo sistem_para_birimi;?> </td>
            <td><?php echo $toplamhizmet_bedeli; echo " "; echo sistem_para_birimi; ?></td>
            <td><?php if($rowa["package_id"]!=""){ echo "evet";} ?></td>
            <td><?php echo $rowa["request_userid"]; ?></td>
            <td><?php echo $rowa["islemi_yapan_kullanici_id"]; ?></td>
            <td><?php echo $rowa["request_resulted_datetime"]; ?></td>
        </tr>
        </tbody>

    </table>


<?php

    $istem=tek("select * from transaction_definitions where definition_name='istemler' and definition_type='HIZMET_PAKET_TURLERI'");
    $istemid=$istem['id'];

?>

    <script>
        $('#istemhizmet<?php echo $protokolno; ?>').DataTable({
            "scrollX": true,
            "scrollY": '45vh',
            "lengthChange": false,
            "pageLength":20,
            "dom": "<'row'<'col-sm-12 col-md-6 'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "buttons": [
                <?php if ($hizlipaketlistele){ ?>
                {
                    text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Listele',
                    className:'btn kapat-btn btn-sm text-white',
                    titleAttr:'Hastaya İstem Paketi Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {
                        <?php if ($taburcu['discharge_status']!=1 && $izin['id']==''){ ?>
                        var ihsan="<?php echo $istemid ?>";
                        $.get("ajax/hastahizlipaketlistele.php?islem=istemler", {protokolno:<?php echo $_GET["protokolno"]; ?>,ihsan:ihsan}, function (getveri) {
                            $('#hastahizlipaketlisteleislem<?php echo $_GET["ihsan"]; ?>').html(getveri);
                        });
                        <?php }else if ($izin['id']!=''){  ?>
                        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php }else if ($taburcu['discharge_status']==1){ ?>

                        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');

                        <?php } ?>
                    },
                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                       <?php } ?>
                        <?php if ($taburcu['discharge_status']!=1 && $izin['id']==''){ ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketlerigetir<?php echo $_GET["ihsan"]; ?>",
                        <?php } ?>
                    }
                },
                <?php }if($hizlipaketolustur){ ?>
                {
                    text: '<i class="fas fa-code-pull-request"></i> Hızlı Paket Oluştur',
                    className:'btn yeni-btn btn-sm text-white mx-1',
                    titleAttr:'Hızlı Paket Oluşturmak İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {
                        <?php if ($taburcu['discharge_status']==1){ ?>
                        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php } else if ($izin['id']!=''){?>
                        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php } ?>
                    },
                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php } ?>
                        <?php if ($taburcu['discharge_status']!=1 && $izin['id']==''){ ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hizlipaketolustur<?php echo $_GET["ihsan"]; ?>",
                        <?php } ?>
                    }
                },
                <?php } ?>
                {
                    text: '<i class="fas fa-code-pull-request"></i> İstem Ekle',
                    className:'btn up-btn btn-sm text-white mx-1',
                    titleAttr:'Hastaya İstem Eklemek İçin Tıklayınız...',

                    action: function( e, dt, node, config ) {
                        <?php if ($taburcu['discharge_status']==1){ ?>
                        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php } else if ($izin['id']!=''){?>
                        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
                        <?php } ?>
                    },
                    attr:  {
                        <?php
                        $yetkivarmi=hekimeaitolayankayitsorgula($kullanicibilgileri["doctor"],$kullanicininidsi);
                        if ($yetkivarmi=='disabled'){ ?>
                        'disabled':"disabled",
                        <?php }
                         else if ($taburcu['discharge_status']!=1 && $izin['id']==''){ ?>
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#yeniistemekle<?php echo $_GET["ihsan"]; ?>",
                        <?php } ?>
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $("body").off("click", ".istemsil").on("click", ".istemsil", function (e) {
            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');
            var protokolno = $(this).attr('protokolno');

            <?php if ($taburcu['discharge_status']==1){ ?>
            alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
            <?php } else if ($izin['id']!=''){?>
            alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
            <?php }else{ ?>
            $.get("ajax/hastadetay/hastaistemlerilistele.php", {islem_id: islem_id, islem: islem, protokolno: protokolno }, function (getveri) {

                $('#hastaistemlericerik').html(getveri);

            });
            <?php } ?>

        });
    </script>

 <?php }
?>