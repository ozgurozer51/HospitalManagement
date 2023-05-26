<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

$islem=$_GET['islem'];
if ($islem=="ililce"){
    if($_POST['ilid']!="") {
        $ilid = $_POST['ilid'];
    }else {
        $ilid = $_GET['ilid'];
    }
 
    $hello=verilericoklucek("select * from district where province_number={$ilid}");
    foreach ($hello as $rowa) { ?>
        <option <?php if($_GET['secili']==$rowa["id"]){ echo "selected"; } ?> value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["district_name"]; ?></option>
    <?php }
}

?>