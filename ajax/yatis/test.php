<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];

//$rowlar=singularactive("process_role","official_code",'612490');
//$hello =$rowlar['role_code'] ;
//$icerik = explode(",",$hello);
//
//function arr_filtre($item){
//    return trim($item);
//}
//
//$icerik = array_map('arr_filtre',$icerik);
//foreach ($icerik as $row) {
//
//     if ($row=='P612501'){
//         echo 'var: '.$row;
//     }
//
//}




//$yeni_icerik=array();
//foreach ($icerik as $row) {
//
//    $yeni_icerik=trim($row);
//
//}
//print_r(count($yeni_icerik));
//
//foreach ($yeni_icerik as $row) {
//
//     if ($row=='P612501'){
//         echo 'var: '.$row;
//     }else{
//         echo 'yok ki';
//     }
//
//}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Template_plain.dwt" codeOutsideHTMLIsLocked="false" -->
<head>



    <!-- this code from stackoverflow http://stackoverflow.com/questions/2255291/print-the-contents-of-a-div -->

    <script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js" > </script>
    <script type="text/javascript">

        function PrintElem(elem)
        {
            Popup($(elem).html());
        }

        function Popup(data)
        {
            var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>my div</title>');
            /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();
            mywindow.close();

            return true;
        }

    </script>

    <!-- end code from stack overflow -->

    </style></head>

<body>

<p>Complete this form</p>

<div id="container" style="border: 2px solid; width: 600px; padding: 5px;">


    <form>
        Name:<br/>
        <input type="text" name="F_Name" size=60>
        <br/><br/>
        Street Address:<br/>
        <input type="text" name="F_Address" size=30>
        City:
        <input type="text" name="F_City" size=12>
        State:
        <input type="text" name="F_State" size=2>
        Zip Code:
        <input type="text" name="F_ZipCode" size=5">
        <br/><br/>
        <input type="reset" value="Reset">
        <br/><br/>

    </form>
    <!-- end #container -->
</div> <!-- end #container -->

<input type="button" value="Print Form" onclick="PrintElem('#container')" />


</body>
</html>
























