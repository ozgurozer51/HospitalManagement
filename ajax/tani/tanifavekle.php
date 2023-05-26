<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$_POST["userid"]=$_GET["taniekleyendoktor"];
$_POST["diagnosis_id"]=$_GET["taniid"];


if($_GET["islem"] == "ekle") {
    direktekle("users_diagnosis_favorites", $_POST);

}else if($_GET["islem"]=="cikar") {

   $sql = tek("UPDATE users_diagnosis_favorites SET status='2' WHERE insert_userid='$_SESSION[id]' and diagnosis_id='$_GET[islem_id]'");

  } if($_GET["islem"]=="sikcikar") {
    $kullanici_ids=$_GET["kullanici_id"];
    $sorbakalim=tek("select * from users_diagnosis_favorites where insert_userid='$kullanici_ids'  and status!='2' and id='{$_GET["islem_id"]}'");
    if($sorbakalim){
        $islemsonuc=silme("users_diagnosis_favorites","id",$_GET["islem_id"]);
    }
}

if($_GET["islem"]=="favori_listem") {
    $protokolno=$_GET["protokolno"];
    $hastalarid=$_GET["hastalarid"];
    $modul=$_GET["modul"];
    $tip=$_GET["tip"];
    ?>

    <div class="card">

    <div class="card-body">

    <table  id="tani-listesi" class="table table-striped table-sm display nowrap table-bordered" style="font-size: 13px!important; background:white;width: 100%;">
        <thead>
        <tr>
            <th class="sik-kullanilan-trigger"></th>
            <th>Tanı Kodu</th>
            <th>Tanı Adı</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $kullanicid=$_SESSION['id'];
        $refakatcicrud=yetkisorgula($kullanicid, "taniinsert");
        $hasya_kayit=singularactive("patient_registration","protocol_number",$protokolno);
        if ($hasya_kayit['outpatient_id']==''){
            $birimadi=$hasya_kayit['service_id'];
        }else{
            $birimadi=$hasya_kayit['outpatient_id'];
        }

        $sql=verilericoklucek("SELECT diagnoses.id as tani_id, users_diagnosis_favorites.id as fav_id, 
                                 users_diagnosis_favorites.diagnosis_id as favori_id,
                                 *
                               FROM diagnoses
                                        inner join users_diagnosis_favorites on diagnoses.id = users_diagnosis_favorites.diagnosis_id
                               where users_diagnosis_favorites.status = 1
                                 AND users_diagnosis_favorites.insert_userid = '$_SESSION[id]'");
        foreach ((array) $sql as $rowa) {
            $TANIKOASADU=$rowa["tani_id"];
            $cssekle="";
            if($TANIKOASADU!='') {
                $cssekle="color: #e83e8c;";
                $islem_idsi=trim($rowa["fav_id"]);
                $islem="cikar";
            } ?>

            <tr class="tanimlaekle">
                <td id="favsonuc<?php echo $rowa["id"]; ?>" ><i islem="<?php echo $islem; ?>" islem_id="<?php  echo $rowa["favori_id"]; ?>" taniekleyendoktor="<?php echo $kullanicid; ?>"  taniid="<?php echo $rowa["id"]; ?>" style=" font-size: 20px;<?php echo $cssekle; ?>" class="mdi mdi-star-plus tani-fav-islem"></i></td>
                <td><?php echo $rowa["diagnoses_id"]; ?></td>
                <td><?php echo $rowa["diagnoses_name"]; ?></td>
                <td><?php if ($refakatcicrud==1){ ?>
                        <button tanikodu="<?PHP ECHO $rowa["diagnoses_id"]; ?>"  tip="<?PHP ECHO $tip; ?>" tani_id="<?PHP ECHO $rowa["tani_id"]; ?>"
                                taniadi="<?PHP ECHO $rowa["diagnoses_name"]; ?>"  taniekleyendoktor="<?PHP ECHO $kullanicid; ?>"
                                protokolno="<?PHP ECHO $protokolno; ?>"  hastalarid="<?PHP ECHO $hastalarid; ?>"   type="button" id="taniekle-fav"
                                class="taniekle-fav btn btn-sm btn-danger waves-effect waves-light w-sm">Ekle</button>
                    <?php } ?>
                </td>
            </tr>

        <?php } ?>

        </tbody>
    </table>
    </div>
    </div>



    <script>
        $(document).ready(function() {

            var table2 = $('#tani-listesi').DataTable({
                "paging":false,
                "searching":false,
                "info":false,
                "pageLenght":false,
                "scrollX":true,
                "scrollY":"55vh"
            });

                $("body").off("click", ".tani-fav-islem").on("click", ".tani-fav-islem", function (e) {
                var taniid = $(this).attr('taniid');
                var islem = $(this).attr('islem');
                var islem_id = $(this).attr('islem_id');
                var taniekleyendoktor = $(this).attr('taniekleyendoktor');
                $.get( "ajax/tani/tanifavekle.php", {taniid:taniid,taniekleyendoktor:taniekleyendoktor,islem:islem,islem_id:islem_id  },function(getveri) {

                    if(islem=="ekle"){
                        alertify.message("tanı favorilere eklendi");
                    }else{

                        $('.tani-main').find("[islem_id='" +islem_id+ "").css("color" ,  "#000000");
                        $('.tani-main').find("[islem_id='" +islem_id+ "").attr("islem" ,  "ekle");


                        alertify.error("tanı favorilerden çıkartıldı");
                    }

                    var islemturu='favori_listem';
                    var protokolno="<?php echo $protokolno ?>";
                    var hastalarid="<?php echo $hastalarid ?>";
                    var modul="<?php echo $modul ?>";
                    $.get("ajax/tani/tanifavekle.php", {"islem":islemturu,"protokolno":protokolno,"hastalarid":hastalarid,"modul":modul },function(e){
                        $('#livesearcasdah').html(e);
                    });

                });

                    $(this).closest("tr").remove();
            });

                $("body").off("click", ".taniekle-fav").on("click", ".taniekle-fav", function (e) {


                var protokolno = $(this).attr('protokolno');
                var tanikodu = $(this).attr('tanikodu');
                var taniekleyendoktor = $(this).attr('taniekleyendoktor');
                var hastalarid = $(this).attr('hastalarid');
                var tani_id = $(this).attr('tani_id');
                var tip = $(this).attr('tip');
                var modul="<?php echo $modul; ?>";
                    if (modul=='ayaktan'){
                        var diagnosis_modul =1;
                    }else{
                        var diagnosis_modul =2;
                    }
                    var birim="<?php echo $birimadi; ?>";
                $.get( "ajax/tani/taniislem.php?islem=taniekle", {tip:tip,protokolno:protokolno,tanikodu:tanikodu,taniekleyendoktor:taniekleyendoktor,hastalarid:hastalarid,tani_id:tani_id,diagnosis_modul:diagnosis_modul,birim:birim},function(e){
                    $('.sonucyaz').html(e);
<!--                        --><?php
//                    if($modul!='ayaktan'){ ?>
//
//                        $.get("ajax/tani/tanilistesi.php?islem=tanilistesi", {tip:tip,protokolno:protokolno,"modul":modul },function(e){
//                            $('#ontaniicerik').html(e);
//                            $('.inputreset').val('');
//                            var islemturu='favori_listem';
//                            var protokolno="<?php //echo $protokolno ?>//";
//                            var hastalarid="<?php //echo $hastalarid ?>//";
//                            var modul="<?php //echo $modul ?>//";
//                            $.get("ajax/tani/tanifavekle.php", {"userid":userid,"islem":islemturu,"protokolno":protokolno,"hastalarid":hastalarid,"modul":modul,"tip":tip },function(e){
//                                $('#livesearcasdah').html(e);
//                            });
//
//
//                        });
//                        <?php //}else{ ?>
//                        $.get("ajax/tani/tanilistesi.php?islem=plktanilistesi", {tip:tip,protokolno:protokolno,"modul":modul,"tip":tip },function(e){
//                            $('#ontaniicerik').html(e);
//                            $('.inputreset').val('');
//                            var islemturu='favori_listem';
//                            var protokolno="<?php //echo $protokolno ?>//";
//                            var hastalarid="<?php //echo $hastalarid ?>//";
//                            var modul="<?php //echo $modul ?>//";
//                            $.get("ajax/tani/tanifavekle.php", {"userid":userid,"islem":islemturu,"protokolno":protokolno,"hastalarid":hastalarid,"modul":modul,"tip":tip },function(e){
//                                $('#livesearcasdah').html(e);
//                            });
//
//
//                        });
//
//                        <?php //} ?>

                });

                    table_poliklinik.ajax.url(table_poliklinik.ajax.url()).load();

            });

        });
    </script>

<?php }  ?>
