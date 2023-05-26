
<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$protokolno=$_GET["protokolno"];
$hastakayit=singularactive("patient_registration","id",$protokolno);
if ($hastakayit['doctor']!=''){
    $kayit_bilgileri=singularactive("patient_registration","id",$protokolno);
}else{
    $kayit_bilgileri=singularactive("patient_registration","id",$hastakayit['hospitalization_protocol']);
}
$hasta_bilgileri=singularactive("patients","id",$hastakayit["patient_id"]);
$kurum=$hasta_bilgileri["institution"];
$alt_kurum=$hasta_bilgileri["social_assurance"];

if($_GET["tip"]=="grup_ekle") {

    $sql ="select * from users_request_package_detail where users_request_packageid='{$_GET["id"]}' and status='1' ";
    $hello=verilericoklucek($sql);
    foreach ($hello as $row) {
     
        $istem_id=$row["request_id"];
        $istem_bilgileri=tek("select * from processes where id=$istem_id fetch first 1 rows only");
        $istem_bilgileri_ucret=tek("select * from processes_price where processes_id=$istem_id fetch first 1 rows only");



        $fiyatgetir=kurumagoreistemucreti($kurum,$alt_kurum,$istem_id);

        $bolunmus = explode("-", $fiyatgetir);
        $genelucreti=$bolunmus[0];
        $hizmetbedeli=$bolunmus[1];
        if($hizmetbedeli==""){
            $hizmetbedeli=0;
        }
        $_POST["group_id"]=$istem_bilgileri["process_group_id"];
        $istek_tarihi =date('y-m-d h:i:s');
        $_POST["request_date"]=$istek_tarihi;
        $_POST["budget_code"]=$istem_bilgileri["official_code"];
        $_POST["request_name"]=$istem_bilgileri["process_name"];
        $_POST["request_userid"]=$_SESSION["id"];
        $_POST["action_doer_userid"]=$_SESSION["id"];
        $_POST["doctor_id"]=$kayit_bilgileri["doctor"];
        $_POST["patient_id"]=$hasta_bilgileri["id"];
        $_POST["protocol_number"]=$protokolno;
        $_POST["study_id"]=$istem_id;
        $_POST["status"]="0";
        $_POST["package_id"]=$_GET["id"];
        $_POST["fee"]=$istem_bilgileri_ucret['price'];
        $_POST["service_fee"]=$hizmetbedeli;
        $_POST["piece"]=1;
        $baksen=direktekle("patient_prompts",$_POST);

        var_dump($baksen);
       

    }
    if($baksen){

        return 1;
    }
}
else if($_GET["tip"]=="grupistemekle") {

    $sql ="select * from transaction_detail where transaction_id='{$_GET["id"]}' and status='1' ";
    $hello=verilericoklucek($sql);
    foreach ($hello as $istem_bilgileri) {

        $fiyatgetir=kurumagoreistemucreti($kurum,$alt_kurum,$istem_bilgileri["id"]);
        $bolunmus = explode("-", $fiyatgetir);
        $genelucreti=$bolunmus[0];
        $hizmetbedeli=$bolunmus[1];
        if($hizmetbedeli==""){
            $hizmetbedeli=0;
        }
        $istek_tarihi =date('y-m-d h:i:s');
$_POST["request_date"]=$istek_tarihi;
$_POST["request_name"]=$istem_bilgileri["transaction_name"];
        $_POST["request_userid"]=$_SESSION["id"];
        $_POST["status"]="0";
$_POST["doctor_id"]=$kayit_bilgileri["doctor"];
        $_POST["group_id"]=$istem_bilgileri["transaction_id"];
$_POST["patient_id"]=$hasta_bilgileri["tc_id"];
$_POST["protocol_number"]=$protokolno;
$_POST["study_id"]=$istem_bilgileri["id"];
$_POST["package_id"]=$_GET["id"];
        $_POST["fee"]=$genelucreti;
        $_POST["service_fee"]=$hizmetbedeli;
$_POST["piece"]=1;
$baksen=direktekle("patient_prompts",$_POST);

    }
    if($baksen){
        return 1;
    }
}
else if($_GET["tip"]=="tekliekle") {

    $istem_id=$_GET["id"];

    $sql = singular("patient_registration","protocol_number",$_GET['protokolno'] );



    $istem_bilgileri=tek("select * from processes where id=$istem_id fetch first 1 rows only");
    $istem_bilgileri_ucret=tek("select * from processes_price where processes_id=$istem_id fetch first 1 rows only");
    $fiyatgetir=kurumagoreistemucreti($kurum,$alt_kurum,$istem_id);
    $bolunmus = explode("-", $fiyatgetir);
    $genelucreti=$bolunmus[0];
    $hizmetbedeli=$bolunmus[1];

    if($hizmetbedeli==""){
        $hizmetbedeli=0;
    }
    $istek_tarihi =date('Y-m-d H:i:s');
    $_POST["request_date"]=$istek_tarihi;
    $_POST["request_name"]=$istem_bilgileri["process_name"];
    $_POST["budget_code"]=$istem_bilgileri["official_code"];
    $_POST["request_userid"]=$_SESSION["id"];
    $_POST["action_doer_userid"]=$_SESSION["id"];
    $_POST["doctor_id"]=$kayit_bilgileri["doctor"];
    $_POST["patient_id"]=$hasta_bilgileri["id"];
    $_POST["status"]="0";
    $_POST["protocol_number"]=$protokolno;
    $_POST["group_id"]=$_GET["grup_id"];
    $_POST["study_id"]=$istem_id;
    $_POST["package_id"]="";
    $_POST["fee"]=$istem_bilgileri_ucret['price'];
    $_POST["service_fee"]=$hizmetbedeli;
    $_POST["patient_tc"]=$sql['tc_id'];
    $_POST["piece"]=1;
//    $_POST["prompts_type"]=1;
    $baksen=direktekle("patient_prompts",$_POST);
    var_dump($baksen);
    if($baksen==1){ ?>
        <script>
            alertify.success("işlem başarılı");
        </script>
   <?php  }else{ ?>
        <script>
            alertify.error("işlem başarısız");
        </script>
   <?php }

}