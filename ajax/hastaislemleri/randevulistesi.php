<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanicininidsi=$_SESSION["id"];
$tc_id=$_GET["tc_id"];
//
//if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(@$_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
//{
//    // Eğer Ajax işlemi dışında dosyaya erişim sağlanırsa hata verdirelim
//    die("Erişim engellendi!");
//}
if($_GET["islem"]=="randevuyusil"){
    $_POST["status"]=4;
    //Silindi
    direktguncelle("patient_appointments","id",$_GET["iptalid"],$_POST);

}
if($_GET["islem"]=="randevuiptalet"){
    $_POST["status"]=2;
    //İptal edildi
    direktguncelle("patient_appointments","id",$_GET["iptalid"],$_POST);

}
if($_GET["islem"]=="randevuiptalgerial"){
    $_POST["status"]="0";
    //İptal geri al
    direktguncelle("patient_appointments","id",$_GET["iptalid"],$_POST);

}
if($_GET["islem"]=="randevuyagelmedi"){
    $_POST["status"]=3;
    //İptal geri al
    direktguncelle("patient_appointments","id",$_GET["iptalid"],$_POST);

}

?>
<div id="doktorhastalistesinigetir" class="table-responsive mb-4" style="height: 465px;overflow: auto;padding: 10px;">
  <?php 
        if(!empty($tc_id)){?>
    <button type="button" style=" padding: 11px; " data-toggle="modal"   class="randevulariyazdir  btn btn-success waves-effect waves-light">
        <i class="mdi mdi-calendar-account  "></i>  Randevuları yazdır
    </button>
    <br/>
    <br/>
    <?php } ?>
    <table id="randevuciktial" class="table table-striped table-bordered"
           style=" background:white;width: 100%;">
        <thead>
        <tr>
            <th scope="col">Birim </th>
            <th scope="col">Adı Soyadı</th>
            <th scope="col">Saati</th>
            <th scope="col">İşlem</th>

        </tr>
        </thead>
        <tbody>

        <?php
        if($tc_id){
            $sql="AND tc_id='$tc_id' order by appointment_time DESC";
        }
        $yetkilioldugupoliklinikler = birimyetkisisorgula($kullanicininidsi,"randevu");
        //var_dump($yetkilioldugupoliklinikler);
        $hastalist = "SELECT  * FROM patient_appointments $yetkilioldugupoliklinikler  AND appointment_time like '%$nettarih%' AND status!=4 $sql ";
        $onkayitverilericek=verilericoklucek($hastalist);
        foreach ($onkayitverilericek as $str) {
        //var_dump($str);
        $DRBILGISI = singular("users", "id", $str["doctor_id"]);
        $BIRIMBILGISI = singular("units", "id", $str["unit_code"]);
        //var_dump($str);
        $sil="";
        $colors="";
        if($str["status"]==2){
            $sil="<del >";
            $colors='style=" color: #963b46; background-color: #fee0e3; border-color: #fed0d5; "';
        }
        if($str["status"]==3){
            $colors='style=" background: yellow; "';
        }
        ?>

        <tr  <?php echo $colors; ?>>

            <td><?php echo $sil; ?><?php echo $BIRIMBILGISI["department_name"]; ?>/<?php echo $DRBILGISI["name_surname"]; ?> 
            </td>


            <td class="hastadetay">
                <?php echo $sil; ?>   <?php echo $str["name_surname"]; ?> 
            </td>

            <td class="hastadetay">
                <?php echo $sil; ?>    <?php $SAATAYIR=explode("T",$str["appointment_time"]); echo $SAATAYIR[1]; ?>
            </td>

            <td class="hastadetay">

                <div class="btn-group" role="group">
                    <button style="--bs-btn-font-size: 12px;padding: 5px;" id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        İşlem <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?php if($str["status"]!=2){ ?>
                            <li><a class="dropdown-item randevuiptalet" id="<?php echo $str['id']; ?>" href="#">İptal Et</a></li>
                            <li><a class="dropdown-item randevuyagelmedi" id="<?php echo $str['id']; ?>" href="#">Gelmedi</a></li>
                            <li><a class="dropdown-item randevuyusil" href="#" id="<?php echo $str['id']; ?>">Randevuyu Sil</a></li>
                        <?php }else{ ?>
                            <li><a class="dropdown-item randevuiptalgerial" id="<?php echo $str['id']; ?>" href="#">İptali geri al</a></li>
                            <?php
                        }?>
                    </ul>
                </div>
</div>

    </td>


    </tr>

<?php } ?>
</tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){

        $(".randevulariyazdir").click(function(){
            var win = window.open('ajax/hastaislemleri/randevulariyazdir.php?islem=randevuciktial&kullanicininidsi=<?php echo $kullanicininidsi; ?>','barkoccikti', 'fullscreen="yes"');
            setTimeout(function() { win.close();}, 2000);
        });

        $(".randevuiptalgerial").click(function(){
            var id = $(this).attr('id');
            var kullanicininidsi = "<?PHP ECHO $kullanicininidsi; ?>";
            $.get( "ajax/hastaislemleri/randevulistesi.php?islem=randevuiptalgerial", { iptalid:id,kullanicininidsi:kullanicininidsi },function(getVeri){
                $('#hastabazlirandevulistesiicerik').html(getVeri);
                alertify.success("Geri alma işlemi başarılı");
            });
        } )
        $(".randevuyagelmedi").click(function(){
            var id = $(this).attr('id');
            var kullanicininidsi = "<?PHP ECHO $kullanicininidsi; ?>";
            $.get( "ajax/hastaislemleri/randevulistesi.php?islem=randevuyagelmedi", { iptalid:id,kullanicininidsi:kullanicininidsi },function(getVeri){
                $('#hastabazlirandevulistesiicerik').html(getVeri);
                alertify.success("Gelmedi olarak işaretlendi");
            });
        } )
        $(".randevuiptalet").click(function(){
            var id = $(this).attr('id');
            var kullanicininidsi = "<?PHP ECHO $kullanicininidsi; ?>";
            alertify.confirm("Randevu iptal onayı", "İptal etmek istediğinize emin misiniz ?", function() {
                alertify.success("İptal işlemi başarılı");

                $.get( "ajax/hastaislemleri/randevulistesi.php?islem=randevuiptalet", { iptalid:id,kullanicininidsi:kullanicininidsi },function(getVeri){
                    $('#hastabazlirandevulistesiicerik').html(getVeri);

                });
            }, function() {
                alertify.error("İptal işleminden vazgeçildi")
            })

        });
        $(".randevuyusil").click(function(){
            var id = $(this).attr('id');
            var kullanicininidsi = "<?PHP ECHO $kullanicininidsi; ?>";
            alertify.confirm("Randevu silme onayı", "Silmek etmek istediğinize emin misiniz ?", function() {
                alertify.success("Silme işlemi başarılı");

                $.get( "ajax/hastaislemleri/randevulistesi.php?islem=randevuyusil", { iptalid:id,kullanicininidsi:kullanicininidsi },function(getVeri){
                    $('#hastabazlirandevulistesiicerik').html(getVeri);

                });
            }, function() {
                alertify.error("İptal işleminden vazgeçildi")
            })

        });
    });

</script>
