<?php
include "../../controller/fonksiyonlar.php";

if($_GET["tc_id"]) {
    $gelendeger = kpssorgula($_GET["tc_id"]);
    var_dump($gelendeger);
    $ihsan=arasinial($gelendeger,"<TCKimlikNo>","<TCKimlikNo>",1);
    if($ihsan!=""){
        echo  $gelendeger;
    } else{
        echo "0";
    }
}
?>
