<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

    $kurumid=$_POST['kurumid'];

    $bolumgetir = "select * from transaction_definitions where definition_type='SOSYALGUVENCE' and status='1' and definition_supplement='$kurumid'";
$hello = verilericoklucek($bolumgetir);
foreach ($hello as $value) {
        echo '<option value="'.$value['definition_code'].'">'.$value['definition_name'].'</option>';
    }

?>