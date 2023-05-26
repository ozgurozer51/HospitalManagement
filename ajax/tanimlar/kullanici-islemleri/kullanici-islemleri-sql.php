<?php
include "../../../controller/fonksiyonlar.php";
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$islem=$_GET['islem'];

if ($islem == "yeni-yetki-tanim-ekle-birim") {
    $_POST['explanation'] = $_POST['yetki'] . "  birimi i̇çin yetki";
    $sql2= direktekle("authority_definition", $_POST);
    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.success('yetki tanım ekleme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.error('yetki tanım ekleme i̇şleminde hata oluştu');
        </script>
    <?php } ?>
<?php exit(); }
//----yeni yetki tanımla----------------------------------------------------------------------------------------------->

if ($islem == "yeni-yetki-tanim-olustur") {
    $sql2 = direktekle("authority_definition", $_POST);
    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.success('yetki tanım ekleme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.error('yetki tanım ekleme i̇şleminde hata oluştu');
        </script>
  <?php } exit(); }

//yetkiyi aktif ve pasif etme------------------------------------------------------------------------------------------>
if ($islem == "yetki_aktif_pasif_et") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $sql2 = direktguncelle("authority_definition", "id", $id, $_POST);

    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.success('yetki düzenleme başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 5);
            alertify.error('yetki düzenleme başarısız');
        </script>
        <?php } exit(); }

if ($islem == "kasa_yetkisi_ekle") {
    $_POST['authorizing_userid']  = $_SESSION['id'];
    $_POST['authorizing_datetime'] = $simdikitarih;
    $sql2 = direktekle("users_outhorized_safes", $_POST);

    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.success('<?php echo $_GET['kisi-adi']; ?> kullanıcısına kasa yetkisi verme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.error('<?php echo $_GET['kisi-adi']; ?> kullanıcısına kasa yetkisi verme i̇şlemi başarısız');
        </script>
    <?php } exit(); }

//---kasa yetkisini aktif/pasif etme----------------------------------------------------------------------------------->
if ($islem == "kullanici-kasa-yetki-update") {

    $id = $_POST['id'];
    unset($_POST['id']);
    $sql2 = direktguncelle("users_outhorized_safes", "id", $id, $_POST);

    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.success('<?php echo $_GET['kisi-adi']; ?> kullanıcısına kasa yetki düzenleme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.error('<?php echo $_GET['kisi-adi']; ?> kullanıcısına kasa yetki düzenleme i̇şlemi başarısız');
        </script>
    <?php } exit(); }

//--birim aktif pasif etme i̇şlemleri----------------------------------------------------------------------------------->
if ($islem == "kullanici-birim-yetki-update") {

    $id = $_POST['id'];
    unset($_POST['id']);
    $sql2 = direktguncelle("users_outhorized_units", "id", $id, $_POST);
    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.success('<?php echo $_GET['kisi-adi']; ?> kullanıcısına birim yetki düzenleme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.error('<?php echo $_GET['kisi-adi']; ?> kullanıcısına birim yetki düzenleme i̇şlemi başarısız');
        </script>

<!--kullanıcıya yeni birim yetkisi ekle-------------------------------------------------------------------------------->
<?php } exit(); } if ($islem == "birim_yetkisi_ekle") {
    $_POST['authorizing_userid']  = $_SESSION['id'];
    $_POST['authorizing_datetime'] = $simdikitarih;
    $sql2 = direktekle("users_outhorized_units", $_POST);
    if ($sql2) { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.success('<?php echo $_GET['kisi-adi']; ?> kullanıcısına birim yetkisi verme i̇şlemi başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 10);
            alertify.error('<?php echo $_GET['kisi-adi']; ?> kullanıcısına birim yetkisi verme i̇şlemi başarısız');
        </script>

    <?php } exit(); }


if ($islem=="grup-yetki-ekle"){
    $_POST["authorizing_datetime"] = $simdikitarih;
    $_POST["authorizing_userid"] = $_SESSION["id"];
    $_POST["userid"] = $_POST['kullaniciid'];
    $grupid=$_POST['grupyetkiid'];
    unset($_POST['grupyetkiid']);
    unset($_POST['kullaniciid']);

    $sql = verilericoklucek("select authority_id from staff_group where group_id='$grupid' ");

    foreach ($sql as $selected) {
        $yetkiid=$selected['authority_id'];
        $kullaniciidsi=$_POST["userid"];
        $_POST['authority_id'] = $selected['authority_id'];

        $verigetir= tek("select * from authority_of_pool where userid='$kullaniciidsi' and authority_id='$yetkiid' and status='1'");
        if($verigetir){
            $yetkibilgisi=singular("authoritiy_definition","id",$verigetir["authority_id"]);  ?>
            <script>
                alertify.error('eklemek istediğiniz yetki zaten kullanıcıda mevcut!');
            </script>
        <?php   }else {
            $sql = direktekle("authority_of_pool",$_POST);
            ?><script>
            alertify.success('İşlem Başarılı');
            </script> <?php
        }
    }

exit(); }
if ($islem=="grup-user-ekle"){
        $grupid=$_POST['usersid'];
        $row =tek("select  MAX(group_id) as id  from staff_group ");
        $_POST['group_id']=$row["id"]+1;
        unset($_POST['usersid']);
        foreach ($grupid as $selected) {
            $_POST['authority_id'] = $selected;
            $sql = direktekle("staff_group",$_POST);
        }

}elseif ($islem=="grup-user-update"){
        $id=$_POST['id'];
        unset($_POST['id']);

        $sql = verilericoklucek("select count(*) as say,id from staff_group where group_id=$id group by id");
        foreach ($sql as $row) {
            $idsutun=$row['id'];
            kesinsil("staff_group", "id", $idsutun);
        }
        $_POST["group_id"] = $id;
        $grupid=$_POST['usersid'];
        unset($_POST['usersid']);
        foreach ($grupid as $selected) {
            $_POST['authority_id'] = $selected;
            $sql = direktekle("staff_group",$_POST);
        }

}elseif ($islem=="grup-user-sil"){
        $id=$_POST['id'];
        unset($_POST['id']);
        $sql = verilericoklucek("select count(*) as say,id from staff_group where group_id=$id group by id");
        foreach ($sql as $row) {
            $idsutun=$row['id'];
            $detay=$_POST['delete_detail'];
            $sql=canceldetail('staff_group','id',$idsutun,$detay);
        }
      }

if ($islem=="yetki-grup-aktiflestir"){
    $id = $_POST['getir'];
    $sql = verilericoklucek("select count(*) as say,id from staff_group where group_id=$id group by id");
    foreach ($sql as $row) {
        $idsutun=$row['id'];
        $sql = backcancel('staff_group','id',$idsutun);
    } ?>

    <?php  exit(); }

if($islem=="oturum-sonlandir") {
    $_POST["desistence_datetime"] = date('Y-m-d H:i:s');
    direktguncelle("users_session_logs","id",$_GET["islemid"],$_POST);

}if ($islem=="user-insert"){
        $sql = direktekle("users",$_POST);

}else if ($islem=="user-update"){
    $id=$_POST['id'];
    $_POST["username"]=karaktertemizle($_POST["name_surname"]);
    $_POST["eployee_type"]=1;
    unset($_POST['id']);
    $sql = direktguncelle("users","id",$id,$_POST);

}else if ($islem=="user-delete"){
    $id=$_POST['id'];
    unset($_POST['id']);
    $detay=$_POST['delete_detail'];
    $sql=canceldetail('users','id',$id,$detay);
}

if($islem=="yetki-al-ver"){
   $id = $_GET["id"];
   unset($_GET["id"]);
   $_POST['status'] = $_GET['status'];

  $sql =  direktguncelle("authority_of_pool","id",$id,$_POST);


}if($islem == "user-insert-authority"){
    $_POST['authorizing_userid'] = $_SESSION['id'];
    $_POST['authorizing_datetime'] = $simdikitarih;

    $sql = direktekle("authority_of_pool",$_POST);
}




if ($sql=="1") { ?>
    <script>
        alertify.set('notifier', 'delay', 5);
        alertify.success('İşlem Başarılı');
    </script>
<?php } else{ ?>
    <script>
        alertify.set('notifier', 'delay', 5);
        alertify.error('İşlem Başarısız');
    </script>
<?php } ?>



