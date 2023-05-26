
<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanicininidsi=$_SESSION["id"];
$protokolno=$_GET["protokolno"];
$yapilacak_islem=$_GET["yapilacak_islem"];
$kullanici_id=$_SESSION["id"];
$kullanicibilgileri=tekil("patient_registration","id",$protokolno);
//var_dump($_SESSION);
if($yapilacak_islem=="sil"){
    $islem_id=$_GET["islem_id"];
kesinsil("users_request_package_detail","users_request_packageid",$islem_id,"userid='$kullanici_id' and ");
kesinsil("users_request_package","id",$islem_id,"userid='$kullanici_id' and ");
}
?>
<?php if($_GET["islem"]!=""){ ?>

<script>
    $(document).ready(function() {
        $('#hizlipaketlerigetirtable<?php echo $_GET["islem"] ;?>').DataTable( {
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
            }

        });
    });
    //$(".istemekle<?php echo $_GET["islem"] ;?>").click(function () {
    $("body").off("click", ".istemekle<?php echo $_GET["islem"] ;?>").on("click", ".istemekle<?php echo $_GET["islem"] ;?>", function(e){
        var protokolno = $(this).attr('protokolno');
        var id = $(this).attr('id');
        var tip = $(this).attr('tip');
        var icnd = $(this).attr('icnd');
        var grup_id = $(this).attr('grup_id');
        $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, tip: tip,grup_id:grup_id}, function (getveri) {


            if(getveri) {
                $('#sonucyaz').html(getveri);

                if(icnd=="istemler") {
                    $("#hizlipaketlerigetir .close").click();
                    $.get("ajax/hastadetay/hastaistemlerilistele.php", {protokolno: protokolno}, function (getveri) {

                        $('#hastaistemlericerik<?php echo $protokolno; ?>').html(getveri);

                    });
                }else if(icnd=="lab") {
                    $("#yeniistemeklelab .close").click();
                    $.get("ajax/hastadetay/hastalaboratuvarlistele.php", {protokolno: protokolno}, function (getveri) {

                        $('#hastaistemlericerik<?php echo $protokolno; ?>').html(getveri);

                    });
                }else if(icnd=="rad") {
                    $("#yeniistemeklerad .close").click();
                    $.get("ajax/hastadetay/hastaradyolojilistele.php", {protokolno: protokolno}, function (getveri) {

                        $('#hastaistemlericerik<?php echo $protokolno; ?>').html(getveri);

                    });
                }
                $("#modalkapat" ).trigger( "click" );

            }else{
                alertify.error("eklerken hata oluştu! : " + getveri);

            }

        });
    });

    $(".islemsil").click(function () {
 var agree=confirm("<?php echo "bu_icerigi_silmek_istediginize_eminmisiniz"; ?>");
 if (agree) {
            var islem_id = $(this).attr('id');
            var yapilacak_islem = "sil";
            var islem="<?php echo $_GET["islem"]; ?>";
            if (islem=='rad'){
                var ihsan = "<?php echo rad_grup_id; ?>";
            }
            else if(islem == 'lab'){
                var ihsan = "<?php echo lab_grup_id; ?>";
            }else{
               <?php $istem=tek("select * from transaction_definitions where definition_name='istemler' and definition_type='HIZMET_PAKET_TURLERI'");
                $istemid=$istem['id']; ?>
                var ihsan="<?php echo $istemid; ?>";
            }
            var protokolno = $(this).attr('protokolno');
            $.get("ajax/hastahizlipaketlistele.php?islem=<?php echo $_GET["islem"] ;?>", {islem_id: islem_id, protokolno: protokolno,yapilacak_islem:yapilacak_islem,ihsan:ihsan }, function (getveri) {
                $('#hastahizlipaketlisteleislem<?php echo $_GET["islem"] ;?>').html(getveri);
            });
     $("#modalkapat" ).trigger( "click" );
        } else { return false ;}

    });
</script>
<?php } ?>

    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tanımladığınız hazır listeler</h4>
            <button type="button" class="btn-close btn-close-white" id="modalkapat" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" >
            <table id="hizlipaketlerigetirtable<?php echo $_GET["islem"] ;?>" class="table table-striped table-bordered"   >
                <thead>
                <tr>
                    <th> &nbsp;</th>
                    <th>Paket Adı </th>
                    <th>Paket Açıklama</th>
                    <th>Eklenme Tarihi</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>

                <tbody>

                <?php
                $istem=tek("select * from transaction_definitions where definition_name='istemler' and definition_type='HIZMET_PAKET_TURLERI'");
                $istemid=$istem['id'];
                if ($istemid==$_GET["ihsan"]){
                    $sql ="select * from users_request_package where userid='$kullanici_id' and status='1' ";
                }else{
                    $sql ="select * from users_request_package where userid='$kullanici_id' and status='1' and package_type='{$_GET["ihsan"]}' ";
                }
                $hello=verilericoklucek($sql);
                foreach ($hello as $row) {
                    ?>
                    <tr>
                        <td id='<?php echo $row["id"]; ?>'><button   id='<?php echo $row["id"]; ?>'  protokolno='<?php echo $protokolno; ?>'  style=" line-height: 1px;" type="button" class="btn btn-danger waves-effect waves-light islemsil"><i class="fa fa-trash"></i></button></td>
                        <td><?php echo $row["package_name"]; ?></td>
                        <td><?php echo $row["package_explain"]; ?></td>
                        <td><?php echo nettarih($row["insert_datetime"]); ?></td>
                        <td><button type="button" tip="grup_ekle" icnd="<?php echo $_GET["islem"]; ?>" ihsan="<?php echo $_GET["islem"]; ?>" id="<?php echo $row["id"]; ?>" protokolno="<?php echo $protokolno; ?>"  class="istemekle<?php echo $_GET["islem"] ;?> btn up-btn btn-sm"><i class="bx bx-check-double font-size-16 align-middle me-2"></i> Ekle </button></td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn kapat-btn" data-bs-dismiss="modal">Kapat</button>
        </div>
    </div><!-- /.modal-content -->

