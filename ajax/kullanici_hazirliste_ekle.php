<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$_post["request_id"]=$_GET["islem_id"];
$_post["userid"]=$_GET["kullanici_id"];
$_post["package_type"]=$_GET["tip"];
$_post["status"]=1;
if($_GET["islem"]=="ekle") {

    $sor=  tek("select * from users_request_package_detail where request_id='{$_GET["islem_id"]}' and package_type='{$_GET["tip"]}' and  userid='{$_GET["kullanici_id"]}' and status='1' and users_request_packageid is null");
if($sor["id"]=="") { ?>

<?php  $ekle=  direktekle("users_request_package_detail", $_post);
    var_dump($ekle);
    if ($ekle==1){ ?>
        <script>  alertify.success("ekleme başarılı");</script>
  <?php  }else{ ?>
        <script>  alertify.error("ekleme başarısız");</script>
   <?php }
}else{?>

  <script>  alertify.error("eklemeye çalıştığınız tanıyı daha önce eklediğiniz için eklenmemiştir");</script>
<?php
}}else{
   // canceldetail($tablo,$sutun,$id,$detay,$silme,$tarih)
    $delete_detail='paket içerik silme';
    $delete_userid=$_SESSION['id'];
    $delete_datetime=$simdikitarih;
    canceldetail("users_request_package_detail","id",$_post["request_id"],$delete_detail,$delete_userid,$delete_datetime);
}
?>
<table id="kullaniciisteklistesi" class="table table-striped table-bordered"    >
    <thead>
    <tr>
        <th>adı  </th>
        <th>icd 10 kodu</th>
        <th>i̇şlem</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($_GET['islemcik']){
        $labid=lab_grup_id;
        $radid=rad_grup_id;
        $sql =verilericoklucek("select * from users_request_package_detail where userid='{$_GET["kullanici_id"]}' and package_type in ($labid,$radid) and  status='1'  and  users_request_packageid is null  ");
    }else{
        $sql =verilericoklucek("select * from users_request_package_detail where userid='{$_GET["kullanici_id"]}' and package_type='{$_GET["tip"]}' and  status='1'  and  users_request_packageid is null  ");
    }

    foreach ($sql as $row){
        $istembilgileri=singular("processes","id",$row["request_id"]);  ?>
        <tr>
            <th><?php echo $istembilgileri["process_name"];  ?></th>
            <th><?php echo $istembilgileri["official_code"]; ?></th>
            <th><button type="button" islem="sil" islem_id="<?php echo $row["id"]; ?>" kullanici_id="<?php echo $_GET["kullanici_id"]; ?>"    class="sagaeklesil btn sil-btn waves-effect waves-light"> Sil</button></th>
        </tr>
    <?php } ?>
    </tbody>
    
</table>
<script>

    $(document).ready(function () {
        $(".sagaeklesil").click(function () {
            var kullanici_id = $(this).attr('kullanici_id');
            var islem_id = $(this).attr('islem_id');
            var islem = $(this).attr('islem');

            $.get("ajax/kullanici_hazirliste_ekle.php", {kullanici_id: kullanici_id,islem_id:islem_id,islem:islem,tip:"<?php echo $_GET["tip"]; ?>"}, function (getveri) {
                $('#kullaniciisteklistesi').html(getveri);
            });
        });

    });
</script>