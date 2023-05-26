<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$q=$_GET["q"];
$protokolno=$_GET["protokolno"];
$tip=$_GET["tip"];
//$islemtanimlari = singularactive("transaction_definitions", "definition_name",'LAB');
//$_GET["ihsan"]=$islemtanimlari['id'];
$islemcik=$_GET["islemcik"];
$kullanicininidsi=$_SESSION["id"];
$patientsid=$_GET["patientsid"];
$kullanici_id=$_SESSION["id"];
if($kullanici_id){
if(strlen($q)<2){
    echo "en az 2 karakter veya rakam girmeniz gerekmektedir";
}else{

    ?>

    <!-- alertifyjs css -->
    <table  id="livesearch" class="table table-striped table-bordered" style=" background:white;width: 100%;">
        <thead style=" background:white;width: 100%;">
        <tr>
            <th>İcd 10 Kodu</th>
            <th>Adi</th>
            <th>Ücret</th>
            <th>İ̇şlem</th>

        </tr>
        </thead>
        <tbody  >
        <?php
        if($islemcik=="istemler"){
            $radid=rad_grup_id;
            $labid=lab_grup_id;
            $sql="and process_group.id in($radid,$labid)";
        }else  if($islemcik=="lab"){
            $sql=" and process_group.id='".lab_grup_id."'";
        }else  if($islemcik=="rad"){
            $sql=" and process_group.id='".rad_grup_id."'";
        }
    $kullanicibilgigetir=singularactive("patients","id",$patientsid);
 

        $hello=verilericoklucek("select count(DISTINCT process_name),processes.id as hizmet_id,processes.process_name as hizmet_adi,processes_price.price as hizmet_ucret,
           official_code as hizmet_kodu,processes.process_group_id as hizmet_grup_id from processes
             inner join process_group on processes.process_group_id = process_group.id
             inner join processes_price on processes.id = processes_price.processes_id
         where   (( lower(processes.process_name) like '%$q%' or  upper(processes.process_name) like '%$q%' ) or 
        ( lower(processes.official_code) like '%$q%' or upper(processes.official_code) like '%$q%')) and  processes.status='1' $sql 
        group by process_name,hizmet_id,hizmet_ucret,hizmet_kodu,hizmet_grup_id order by process_name fetch first 7 rows only");
        foreach ($hello as $row) {

//            $fiyatgetir=kurumagoreistemucreti($kullanicibilgigetir["institution"],$kullanicibilgigetir["social_assurance"],$rowa["id"]);
             ?>
            <tr  class="tanimlaekle">

                <th><?php echo $row["hizmet_kodu"]; ?></th>
                <th><?php echo $row["hizmet_adi"]; ?></th>
                <td class="fw-bold text-center"><?php echo $row["hizmet_ucret"]." ₺"; ?></td>
                <th>
                    <?php
                    if ($tip=='tekliekle'){ ?>
                    <button type="button" islem="tekekle"  icnd="<?php echo $_GET['islemcik'] ?>" kullanici_id="<?php echo $kullanici_id; ?>"
                            grup_id="<?PHP ECHO $row["hizmet_grup_id"]; ?>"   protokolno="<?php echo $protokolno; ?>" tip="tekliekle"   id="<?PHP ECHO $row["hizmet_id"]; ?>"
                            class="istemekle<?PHP ECHO $_GET["ihsan"] ;?> btn up-btn waves-effect waves-light">Ekle</button>
                    <?php }else{ ?>
                        <button  islem="ekle" icnd="<?php echo $row["hizmet_grup_id"]; ?>" islemcik="<?php echo $_GET['islemcik'] ?>"  kullanici_id="<?php echo $kullanici_id; ?>" protokolno="<?php echo $protokolno; ?>" tip="tekliekle"
                                 islem_id="<?php echo $row["hizmet_id"]; ?>" grup_id="<?php echo $row["hizmet_grup_id"]; ?>"  type="button"   class="sagaekle<?php echo $islemcik; ?>  btn up-btn waves-effect waves-light w-sm">
                            Ekle
                        </button>
                    <?php } ?>
                </th>
            </tr>

        <?php } ?>

        </tbody>
    </table>


    <!-- notification init -->

    <script>

        $(document).ready(function () {


            $(".sagaekle<?php echo $islemcik;   ?>").click(function () {
                var kullanici_id = $(this).attr('kullanici_id');
                var islem_id = $(this).attr('islem_id');
                var islem = $(this).attr('islem');
                var icnd = $(this).attr('icnd');
                var islemcik=$(this).attr('islemcik');
                $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:icnd,islemcik:islemcik}, function (getveri) {
                   if (islemcik!='istemler'){
                       $('#kullaniciisteklistesi'+icnd).html(getveri);
                   }
                   else{
                       
                       $('#kullaniciisteklistesiistemler').html(getveri);
                   }


                });
            });


            //$(".istemekle<?PHP //ECHO $_GET["ihsan"] ;?>//").click(function () {
            //    var protokolno = $(this).attr('protokolno');
            //    var id = $(this).attr('islem_id');
            //    var tip = $(this).attr('tip');
            //    var grup_id = $(this).attr('grup_id');
            //    var icnd = $(this).attr('icnd');
            //    // $.get("ajax/hastadetay/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {
            //    $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {
            //        if(getveri) {
            //            alertify.message("kullanıcılar i̇stem paketi eklendi");
            //
            //
            //            if(icnd=="istemler") {
            //                $("#hizlipaketlerigetir .close").click();
            //                $.get("ajax/hastadetay/hastaistemlerilistele.php", {protokolno: protokolno}, function (getveri) {
            //
            //                    $('#hastaistemlericerik').html(getveri);
            //
            //                });
            //            }else if(icnd=="lab") {
            //                $("#yeniistemeklelab .close").click();
            //                $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {protokolno: protokolno}, function (getveri) {
            //
            //                    $('#hastalaboratuvaricerik').html(getveri);
            //
            //                });
            //            }else if(icnd=="rad") {
            //                $("#yeniistemeklerad .close").click();
            //                $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getveri) {
            //
            //                    $('#hastaradyolojiicerik').html(getveri);
            //
            //                });
            //            }
            //            $('.modal-backdrop').remove();
            //
            //
            //
            //        }else{
            //            alertify.error("eklerken hata oluştu! : " + getveri);
            //
            //        }
            //
            //    });
            //});
            $(".istemekle<?PHP ECHO $_GET["ihsan"] ;?>").click(function () {
                var protokolno = $(this).attr('protokolno');
                var id = $(this).attr('id');
                var tip = $(this).attr('tip');
                var icnd = $(this).attr('icnd');
                var grup_id = $(this).attr('grup_id');
                $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getVeri) {
                    if(getVeri) {
                        $('#sonucyaz').html(getVeri);
                        alertify.success("users istem Paketi Eklendi");


                        if(icnd=="istemler") {
                            $.get("ajax/hastadetay/hizmetlistesi.php?hizmetislem=istemhizmetlistesi", {protokolno: protokolno,ihsan:icnd}, function (getveri) {

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



                    }else{
                        alertify.error("Eklerken hata oluştu! : " + getVeri);

                    }

                });
            });

        });
    </script>
<?php } } ?>