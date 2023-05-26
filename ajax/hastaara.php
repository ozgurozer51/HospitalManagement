
<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
//yetkisorgula($_SESSION["USERNAME"],"islemdetayekle");
$q=$_GET["q"];






if(strlen($q)<3){
    echo "En az 3 karakter veya rakam girmeniz gerekmektedir";
}else{
    if(is_numeric($q)){
        $sqlsorgusu="SELECT * FROM patients WHERE tc_id ='$q' and status='1' ";
//    var_dump($sqlsorgusu);
        if(!tckimlik_dogrulama($q)){

            echo "Doğru bir kimlik numarası giriniz";
            exit();
        }

    } else{

        if (preg_match('/\s/',$q) ){
            $bol =explode(" ", $q);
            $isim= $bol[0];
            $soyisim=$bol[1];
        } else {
            $isim = $q;
            $soyisim = $q;
        }

        $sqsl="";

        if(isset($isim)){
            $sql="  (lower(patient_name) LIKE '%$isim%' or upper(patient_name) like '%$isim%') ";
        }

        if(isset($isim) and isset($soyisim)){
            $sqsl.=" or ";
        }

        if(isset($soyisim)){
            $sqsl.="  (upper(patient_surname) LIKE '%$soyisim%' or lower(patient_surname) like '%$soyisim%') ";
        }

        $sqlsorgusu="SELECT * FROM patients WHERE $sql $sqsl ";

  var_dump($sqlsorgusu);

    }  ?>

    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
        <tbody>
        <?php
        if(tek($sqlsorgusu)){
            $ameliyathanegetir = verilericoklucek($sqlsorgusu);
            foreach ($ameliyathanegetir as $row) {
                ?>

                <tr style="border-width: 2px;border-color: black;" >
                    <td>
                        <a class="font-size-15 mb-1"><a href="#" onClick="sistemden_tc_sorgula(<?PHP ECHO $row["tc_id"]; ?>)"><?PHP ECHO $row["patient_name"]; ?> <?PHP ECHO $row["patient_surname"]; ?></a></h6>
                        <p class="text-muted font-size-13 mb-0"><b>TC KİMLİK : </b><?PHP ECHO $row["tc_id"]; ?>  </p>
                    </td>

                    <td>
                        Baba Adı : <?PHP ECHO $row["father"]; ?> <br/>
                        Anne Adı : <?PHP ECHO $row["mother"]; ?> <br/>
                    </td>
                    <td>
                        Adres: <?PHP ECHO $row["address"]; ?> <br/>
                    </td>

                </tr>
            <?php } ?>
        <?php }else {
            echo "Aradığınız kriterlerde hasta bulunmamıştır. Yeni hasta eklemek için enter tuşuna basınız";

        }?>

        </tbody>
    </table>
<?php } ?> 