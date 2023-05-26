<?php include "../../../../controller/fonksiyonlar.php";

date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

if(is_numeric($_GET['department'])){
    $sql = sql_select("select * from units where id=$_GET[department]");
    $_GET['department'] = $sql[0]['department_name'];
 }

if(is_numeric($_GET['doctor'])){
    $sql = sql_select("select * from users where id=$_GET[doctor]");
    $_GET['doctor'] = $sql[0]['name_surname'];
 } ?>

<div class="rapor-form-hasta-bilgileri">
    <div class="ms-2">
    <input class="easyui-textbox" id="hasta-adi-soyadi-<?php echo $uniqid; ?>" value="<?php echo $_GET['patient_name']; ?>" readonly style="width:100%" labelWidth=150 data-options="label:'Adı Soyadı:'">
    <input class="easyui-textbox" id="hasta-tc-no-<?php echo $uniqid; ?>" value="<?php echo $_GET['patient_tc']; ?>"   readonly style="width:100%" labelWidth=150 data-options="label:'T.C. Kimlik No:'">
    <input class="easyui-textbox" id="hasta-protokol-no-<?php echo $uniqid; ?>" name="protocol_no" value="<?php echo $_GET['protocol_no']; ?>"  readonly style="width:100%" labelWidth=150 data-options="label:'Protokol No:'">
    <input class="easyui-textbox" id="hasta-birim-id-<?php echo $uniqid; ?>" value="<?php echo $_GET['department']; ?>" readonly style="width:100%" labelWidth=150 data-options="label:'Birimi'">
    <input class="easyui-textbox" id="hasta-doktoru-<?php echo $uniqid; ?>" value="<?php echo $_GET['doctor']; ?>" readonly style="width:100%" labelWidth=150 data-options="label:'Doktor:'">
    <input value="<?php echo $_GET['patient_id']; ?>" id="hasta-id" name="patient_id" hidden>
    <input class="easyui-textbox" id="hasta-rapor-tarihi-<?php echo $uniqid; ?>" name="insert_datetime" value="<?php echo $simdikitarih; ?>" readonly style="width:100%" labelWidth=150 data-options="label:'Tarih:'">
</div>
</div>

