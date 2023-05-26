<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y-m-d h:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid=$_SESSION['id'];
$anamnezinsert=yetkisorgula($kullanicid, "anamnezinsert");
$anamnezupdate=yetkisorgula($kullanicid, "anamnezupdate");
$anamnezdelete=yetkisorgula($kullanicid, "anamnezdelete");

$islem = $_GET['islem']; ?>

    <script type="text/javascript">
        $(".alerttaburcu").off().on("click", function () {
            alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
        });
        $(".alertizin").on("click", function () {
            alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
        });
    </script>

<?php
if ($islem == "anamnezbody") {
    $protokol=$_GET['protokol'];
//    $sonuc = singular("yatis", "protokol_no", $_GET["protokol"]);
//    if (!$sonuc){
//        $yatis = singular("yatis", "yatis_protokol", $_GET["protokol"]);
//    }else{
//        $yatis = singular("yatis", "protokol_no", $_GET["protokol"]);
//    }
//    if ($yatis['anne_tc_kimlik_numarasi']==''){
//        $hastalar = singular("patients", "tc_id", $yatis['tc_kimlik_no']);
//    }else{
//        $annetc=$yatis['anne_tc_kimlik_numarasi'];
//        $dogumsira=$yatis['dogum_sirasi'];
//        $hastalar = tek("select * from patients where mother_tc_identity_number='$annetc' and birth_order='$dogumsira'");
//    }

//$protokol= $_GET["protokol"];
//$anemnez = tek("select * from anamnesis where protocol_id='$protokol' and situation!='2'");
//$taburcu = singular("patient_discharge", "admission_protocol",$yatis["yatis_protokol"]);

    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol);
    $hastalar = singularactive("patients", "id", $hastakayit['patient_id']);
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>
    <script>
        var protokol ="<?php echo $_GET["protokol"]; ?>";
        var servis ="<?php echo $_GET["servis"]; ?>";
        var hekim ="<?php echo $_GET["hekim"]; ?>";
        $.get("ajax/anamnezislem.php?islem=anemnezicerik", {protokol: protokol,servis: servis,hekim: hekim}, function (getveri) {
            $("#icerikanemnez-<?php echo $_GET["protokol"]; ?>").html(getveri);
        });
    </script>
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">

        <div class="modal-content">

            <div class="modal-header p-1">
                <h5 class="pt-2">Anamnez İşlemleri - <?php echo $hastalar['patient_name'] . "  " . $hastalar['patient_surname']; ?></h5>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <h5 class="pt-2">Hasta
                    Durum:<b> <?php if ($taburcu['discharge_status'] == 1) { ?> Taburcu  <?php } elseif ($izin['id'] != '') { ?> İzinli <?php } else { ?>Yatışta <?php } ?></b>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="icerikanemnez-<?php echo $protokolno; ?>">



                </div>
            </div>

        </div>
    </div>


<?php }
elseif ($islem == "aneminsert") {
    if ($_POST) {
        $checked_count = count($_POST['lung_diseases']);
        $say=0;
        foreach($_POST['lung_diseases'] as $selected){
            $lung_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $lung_diseases.=',';
            }

        }
        unset($_POST['lung_diseases']);



        $checked_count = count($_POST['habits']);
        $say=0;
        foreach($_POST['habits'] as $selected){
            $habits.=$selected;
            $say++;
            if ($say<$checked_count){
                $habits.=',';
            }
        }
        unset($_POST['habits']);


        $checked_count = count($_POST['other_diseases']);
        $say=0;
        foreach($_POST['other_diseases'] as $selected){
            $other_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $other_diseases.=',';
            }

        }
        unset($_POST['other_diseases']);


        $_POST['lung_diseases']=$lung_diseases;
        $_POST['habits']=$habits;
        $_POST['other_diseases']=$other_diseases;
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "anamnezupdate") {
    if ($_POST) {
        $checked_count = count($_POST['lung_diseases']);
        $say=0;
        foreach($_POST['lung_diseases'] as $selected){
            $lung_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $lung_diseases.=',';
            }

        }
        unset($_POST['lung_diseases']);



        $checked_count = count($_POST['habits']);
        $say=0;
        foreach($_POST['habits'] as $selected){
            $habits.=$selected;
            $say++;
            if ($say<$checked_count){
                $habits.=',';
            }
        }
        unset($_POST['habits']);


        $checked_count = count($_POST['other_diseases']);
        $say=0;
        foreach($_POST['other_diseases'] as $selected){
            $other_diseases.=$selected;
            $say++;
            if ($say<$checked_count){
                $other_diseases.=',';
            }

        }
        unset($_POST['other_diseases']);


        $_POST['lung_diseases']=$lung_diseases;
        $_POST['habits']=$habits;
        $_POST['other_diseases']=$other_diseases;



        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        var_dump($sonuc);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "ozsoygecmisinsert") {
    if ($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "ozsoygecmisupdate") {
    if ($_POST) {

        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "alerjiinsert") {
    if ($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("anamnesis", $_POST);

        if ($yatissekle == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "alerjiupdate") {
    if ($_POST) {

        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("anamnesis","id", $id, $_POST);
        if ($sonuc == 1){ ?>
            <script>
                alertify.success('i̇şlemi başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('i̇şlemi başarısız');
            </script>

        <?php }

    }
}
elseif ($islem == "anemnezicerik"){
    
    $protokol= $_GET["protokol"];
    $hastakayit = singular("patient_registration", "id",$protokol);
    $figurler = tek("select * from patient_figure where protocol_number='$protokol' and status='1'");
    $taburcu = singular("patient_discharge", "admission_protocol",$protokol);
    $izin = singularactive("patient_permission","protocol_number",$protokol);
    $random_sayi = uniqid();
    ?>
<?php }






?>