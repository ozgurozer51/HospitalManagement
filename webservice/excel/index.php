<?php

include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();

require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');

date_default_timezone_set('UTC');


try
{
    $Spreadsheet = new SpreadsheetReader("dosyalar/ICD10Listesi.xlsx");
    $Sheets = $Spreadsheet -> Sheets();
    foreach ($Sheets as $Index => $Name)
    {
        $Spreadsheet -> ChangeSheet($Index);
        foreach ($Spreadsheet as $Key => $Row)
        {
//            $deger= $Row[1];
//            $_POST["definitions_name"]="MESLEKLER";
            $_POST["diagnoses_name"]=$Row[0];
            $_POST["diagnoses_id"]=$Row[1];
            $_POST["top_code"]=$Row[2];
            $_POST["diagnoses_level"]=$Row[3];
            $_POST["insert_datetime"]=$Row[6];
            $_POST["update_datetime"]=$Row[7];


            if($Row[4]=="Yok"){
                $_POST["pregnancy_risk"]=0;
            }else{
                $_POST["pregnancy_risk"]=1;
            }

            if($Row[5]=="Aktif"){
                $_POST["status"]=1;
            }else{
                $_POST["status"]=2;
            }
//            $_POST["system_code"]="c3eaf407-b302-5fdd-e043-14031b0a2484";
//            $_POST["reference_code"]=$Row[1];

            $ihsan= direktekle("diagnoses",$_POST);

            var_dump($ihsan);

        }
    }
}
catch (Exception $E)
{
    echo $E -> getMessage();
}
?>