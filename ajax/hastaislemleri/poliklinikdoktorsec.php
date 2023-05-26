<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

$gunceldate = date('Y-m-d');
$today_start_time = date('Y-m-d') . " 00:00:00";
$today_end_time = date('Y-m-d') . " 23:59:00";


$islem=$_GET['islem'];
if($islem=="poliklinikdoktor"){
    $poliklinikid=$_POST['poliklinikid'];
    echo '<option>seçi̇ni̇z</option>';
    $bolumgetir = 'select * from users where department='.$poliklinikid;

    $onkayitverilericek=verilericoklucek($bolumgetir);
    foreach ($onkayitverilericek as $value) {
        ?>
        <option <?php if($value["auto_assign_list"]==1){ echo "selected "; } ?> value="<?php echo $value['id']; ?>"><?php echo $value['name_surname']; ?>(<?php echo drhastasayisigetir("patient_registration","doctor",$value['id'],"insert_datetime",$gunceldate); ?>)</option>
        <?php
    }
}

?>