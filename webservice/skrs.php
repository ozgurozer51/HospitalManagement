 
<?php

function un_escape($string)

{

    $string = preg_replace("/%u0130/", "İ", $string);
    $string = preg_replace("/&#x/", "O", $string);
    $string = preg_replace("/6;Y/", "U", $string);
    $string = preg_replace("/&#x130;/", "İ", $string);
    $string = preg_replace("/O130;/", "İ", $string);
    $string = preg_replace("/&#x15E;/", "Ş", $string);
    $string = preg_replace("/O15E;/", "Ş", $string);
    $string = preg_replace("/&#x11E;/", "Ğ", $string);
    $string = preg_replace("/O11E;/", "Ğ", $string);
    $string = preg_replace("/&#xD6;/", "Ö", $string);
    $string = preg_replace("/OD6;/", "Ö", $string);
    $string = preg_replace("/&#xC2;/", "A", $string);
    $string = preg_replace("/OC7;/", "Ç", $string);
    $string = preg_replace("/ODC;/", "Ü", $string);

    $string = preg_replace("/ı/", "ı", $string);

    $string = preg_replace("/ğ/", "ğ", $string);

    $string = preg_replace("/Ğ/", "Ğ", $string);

    $string = preg_replace("/ş/", "ş", $string);

    $string = preg_replace("/Ş/", "Ş", $string);

    $string = preg_replace("/%FC/", "ü", $string);

    $string = preg_replace("/%DC/", "ü", $string);

    $string = preg_replace("/%F6/", "ö", $string);

    $string = preg_replace("/%D6/", "Ö", $string);

    $string = preg_replace("/%E7/", "ç", $string);

    $string = preg_replace("/%C7/", "Ç", $string);

    return $string;

}
include "../controller/fonksiyonlar.php";
header("Content-Type: text/html; charset=UTF-8");
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$istemci = curl_init();
curl_setopt($istemci, CURLOPT_URL, 'https://skrs.saglik.gov.tr/Anasayfa/SkrsCodeSystemList');
curl_setopt($istemci, CURLOPT_RETURNTRANSFER, 1); 
 
$ham_veri = curl_exec($istemci);  

// var_dump($ham_veri);
preg_match_all('#<tr role="row" class="odd">(.*?)</tr>#si', $ham_veri, $satirlar);

//var_dump($ham_veri);

for($i=0;$i<=500; $i++){

    preg_match_all('#<td>(.*?)</td>#si', $satirlar[0][$i], $detaylar);
    $ADI=$detaylar[0][0];
    $KODU=$detaylar[0][1];
    $SON_DEGISIKLIK=$detaylar[0][2];
    preg_match_all('#<span>(.*?)</span>#si', $detaylar[0][3], $spandetay);
    $deger= str_replace('<span>','',$spandetay[0]);
    $ADI= str_replace('<td>','',$ADI);
    $ADI= str_replace('</td>','',$ADI);
    $KODU= str_replace('<td>','',$KODU);
    $KODU= str_replace('</td>','',$KODU);
    $SON_DEGISIKLIK= str_replace('<td>','',$SON_DEGISIKLIK);
    $SON_DEGISIKLIK= str_replace('</td>','',$SON_DEGISIKLIK);
    $degers= str_replace('</span>','',$deger);

    foreach($degers as $icerikler) { 
        $ADI = un_escape($ADI);

        $_POST["definitions_name"]=$ADI;
        $_POST["system_code"]=$KODU;
        $_POST["update_datetime"]=$SON_DEGISIKLIK;
        $bolunmus =explode(".", $icerikler);

        $_POST["reference_code"]= mb_convert_encoding($bolunmus[0], "UTF-8", "auto");
        $_POST["definitions_explain"]= mb_convert_encoding($bolunmus[1], "UTF-8", "auto"); 
   $eklendi=     direktekle("skrs_definitions",$_POST);
    var_dump($eklendi);
    }
}
?>