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
$islem = $_GET['islem'];


if($islem=="evraklistesi"){
    $protokolgetirme=$_GET['getir'];
    $kullanicininidsi = $_SESSION["id"];
    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0" id="evraktable-<?php echo $protokolgetirme; ?>">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Evrağin Adi</th>
            <th>İndirme Linki</th>
            <th>İşlem</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM patients_document where protocol_id='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo $rowa["document_name"]; ?></td>

                <td class="text-center">
                    <?php if ($evrakupdate==1){ ?>
                    <a  download=" "   href="<?php echo 'yeni/../'.$rowa["document_file_path"]; ?>" >
                        <button type="button" class="btn kps-btn ">  <i class="fa fa-download" aria-hidden="true"></i> İndir</button></a>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($evrakdelete==1){ ?>
                    <button type="button" <?php //hekimeaitolayankayitsorgula($hastakayit["service_doctor"], $kullanicininidsi); ?>protokolno="<?PHP echo $protokolgetirme; ?>" islem_id="<?php echo $rowa["id"]; ?>" class="dosyasil btn sil-btn waves-effect waves-light"><i class="fa fa-trash-can"></i></button>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $("#evraktable-<?php echo $protokolgetirme; ?>").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $(".dosyasil").click(function(){
            var protokolno = $(this).attr('protokolno');
            var islem_id = $(this).attr('islem_id');
            $.get( "ajax/yatis/yatismodalbody.php?islem=evraksilbody", {protokolno:protokolno,islem_id:islem_id },function(getVeri1){
                $('.evrakbody').html(getVeri1);

            });
        });
    </script>
<?php }

elseif($islem =="klinik-listesi"){
    $klinik=$_POST['klinik'];
    $klinik_kodu=$_POST['klinik_kodu'];
    $sqk_ekle='';


$offset='';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : '';
$order = isset($_POST['order']) ? strval($_POST['order']) : '';




$result = array();

    if ($klinik!=''){
        $sqk_ekle.="  and ( upper(units.department_name) LIKE '%$klinik%' or lower(units.department_name) LIKE '%$klinik%') ";
    }
     if ($klinik_kodu!=''){
        $sqk_ekle.="  and units.id=$klinik_kodu ";
    }

  if ($page!=1){
   $offset = ($page-1)*$rows;
   $sqk_ekle.=" LIMIT $rows offset $offset ";
    }else{
        $sqk_ekle.=" LIMIT  $rows ";
    }

    $sql_metin="select units.department_name as klinik, units.id as klinik_kodu
                      from units
                               inner join users_outhorized_units on users_outhorized_units.unit_id = units.id
                      where users_outhorized_units.status = 1
                        and units.status = 1
                        and users_outhorized_units.userid = $_SESSION[id] and units.unit_type = 1 $sqk_ekle";


   $sql = sql_select($sql_metin);

     $sqlcount = sql_select("select units.department_name as klinik, units.id as klinik_kodu
                      from units
                               inner join users_outhorized_units on users_outhorized_units.unit_id = units.id
                      where users_outhorized_units.status = 1
                        and units.status = 1
                        and users_outhorized_units.userid = $_SESSION[id] and units.unit_type = 1");


$result["total"] = count($sqlcount);
$result["rows"] = $sql;

echo json_encode($result);
//echo $sql_metin;


}

elseif($islem =="doktor-listesi"){
    $birim_id=$_POST['birim_id'];
    $doktor_kodu=$_POST['doktor_kodu'];
    $doktor=$_POST['doktor'];
    $sqk_ekle='';

$offset='';
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : '';
$order = isset($_POST['order']) ? strval($_POST['order']) : '';
$result = array();



    if ($birim_id!=''){
        $sqk_ekle.=" and users.department=$birim_id ";
    }
     if ($doktor!=''){
        $sqk_ekle.="  and ( upper(users.name_surname) LIKE '%$doktor%' or lower(users.name_surname) LIKE '%$doktor%') ";
    }
     if ($doktor_kodu!=''){
        $sqk_ekle.="  and users.id=$doktor_kodu ";
    }

    if ($page!=1){
        $offset = ($page-1)*$rows;
        $sqk_ekle.=" LIMIT $rows offset $offset ";
    }else{
        $sqk_ekle.=" LIMIT  $rows ";
    }


    $sql_metin="select users.insert_datetime as kayit_tarihi ,users.id as doktor_id ,units.id as birim_id, * from 
         users inner join units on units.id = users.department where users.status=1 and users.name_surname !='' $sqk_ekle";

   $sql = sql_select($sql_metin);

     $sqlcount = sql_select("select users.insert_datetime as kayit_tarihi ,users.id as doktor_id ,units.id as birim_id, * from 
         users inner join units on units.id = users.department where users.status=1 and users.name_surname !=''");

$result["total"] = count($sqlcount);
$result["rows"] = $sql;

echo json_encode($result);

//echo $sql_metin;
}



elseif($islem=="epikrizlistesi"){
   ///var_dump($_GET); ?>

    <table class="table table-bordered border-dark table-hover table-sm" style=" background:white;width: 100%; font-size: 13px;" id="tableepikriz-<?php echo $_GET["getir"]; ?>">

        <thead class="table-light">
        <tr>
            <th>Oluşturulma Tarihi</th>
            <th>Doktor</th>
            <th>Birim</th>
        </tr>
        </thead>
        <tbody>

        <?php $id=$_GET["getir"];
        $demo = "Select * from epicrisis WHERE protocol_id='$id' AND status='1'";
        $hello=verilericoklucek("$demo");
        foreach ((array) $hello as $row) { ?>
                
            <tr id="<?php echo $row["id"]; ?>" class="epikrizbodyguncelle epikrizbtn ">

                <td><?php echo $row['insert_datetime']; ?></td>
                <td><?php echo kullanicigetirid($row['servis_doktor']);; ?></td>
                <td><?php echo birimgetirid($row['servis_id']); ?></td>

            </tr>
            <?php //}?>
        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $("#tableepikriz-<?php echo $_GET["getir"]; ?>").DataTable({
            "responsive": true,
            "searching":false,
            "paging":false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            dom: '<"clear">lfrtip',
        });

        $(".epikrizbodyguncelle").click(function () {
            var getir = $(this).attr('id');

            $.get("ajax/yatis/yatismodalbody.php?islem=epkrizyeniden", {epikrizid: getir}, function (getVeri) {
                $('.epikrizbody-'+<?php echo $id; ?>).html(getVeri);

            });
        });
        $(".epikrizbodyguncelle").click(function () {
            $(".epikrizguncellesil").prop("disabled", false);
            $(".epikrizkaydet").prop("disabled", true);
        });


        $(".epikrizbtn").on("click", function(){
            $('.epikriz-dom').css({"background-color":"rgb(255, 255, 255)"});
            $('.epikriz-dom').removeClass("text-white");
            $(this).css({"background-color":"rgb(57, 180, 150"});
            $(this).addClass("text-white");
            $(this).addClass("epikriz-dom");

        });
    </script>

<?php }
elseif($islem=="sevklistesi"){ ?>

    <table class="table border table-bordered border-dark table-hover nowrap display w-100" style="font-size:13px" id="tablesevk-<?php echo $_GET["getir"]; ?>">
        <thead>
        <tr>
            <th>Oluşturulma Tarihi</th>
            <th>Doktor</th>
            <th>Birim</th>
        </tr>
        </thead>
        <tbody>

        <?php $id=$_GET["getir"];
        $demo = "Select * from patient_reference WHERE protocol_id='$id' AND status='1'";
        $hello=verilericoklucek($demo);
        foreach ((array) $hello as $row) { ?>

            <tr id="<?php echo $row["id"]; ?>" class="sevkbodyguncelle sevkbtn">
                <td><?php echo $row['insert_datetime']; ?></td>
                <td><?php echo kullanicigetirid($row['service_doctor']);; ?></td>
                <td><?php echo birimgetirid($row['service_id']); ?></td>
            </tr>
            <?php //}?>
        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">

        $("#tablesevk-<?php echo $_GET["getir"]; ?>").DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "paging":false,
            "info": false,
            dom: '<"clear">lfrtip',
        });
        $(".sevkbodyguncelle").click(function () {
            let tiklananSatir = $(this);
            if (!tiklananSatir.hasClass("aktif-satir")) {
                var getir = $(this).attr('id');
                $(".sevkguncellesil").prop('hidden', false);
                $(".sevkkaydet").prop('hidden', true);
                $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {sevkid: getir}, function (getVeri) {
                    $('.sevkbody-'+<?php echo $id; ?>).html(getVeri);

                });

                $('.sevkbodyguncelle').each(function () {
                    $(this).removeClass('aktif-satir');
                });
                tiklananSatir.addClass('aktif-satir');


            } else {
                $(".sevkguncellesil").prop('hidden', true);
                $(".sevkkaydet").prop('hidden', false);

                var getir = "<?php echo $id; ?>";
                $.get("ajax/yatis/yatismodalbody.php?islem=sevkyeniden", {getir: getir}, function (getVeri) {
                    $('.sevkbody-'+<?php echo $id; ?>).html(getVeri);

                });
                tiklananSatir.removeClass('aktif-satir');
            }

        });

    </script>

<?php } elseif ($islem == "refakatcilistesi") {
    $protokolno=$_GET['protokol_no'];
      $sqk_ekle1='';

    $result = array();
    $offset='';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : '';
    $order = isset($_POST['order']) ? strval($_POST['order']) : '';

    if ($sort!=''&& $order!=''){
        $sqk_ekle1.=" ORDER BY $sort $order ";
    }

    if ($page!=1){
   $offset = ($page-1)*$rows;
   $sqk_ekle1.=" LIMIT $rows offset $offset ";
    }else{
        $sqk_ekle1.=" LIMIT  $rows ";
    }



     $sql_metin="Select patient_companion.id as refakatci_id,patient_companion.companion_name_surname as refakatci_adi_soyadi,patient_companion.companion_proximity as yakinlik_id,
       concat(patient_name,' ',patient_surname) as hasta_adi_soyadi,definition_name as yakinlik,companion_tc as refakatci_tc,
       login_datetime as giris_tarihi,exit_datetime as cikis_tarihi,companion_status as refakatci_durum,companion_phone as refakatci_tel
        from patient_companion inner join patient_registration on
    patient_registration.protocol_number=(patient_companion.patient_protocol_number)::integer  inner join patients on
    patient_registration.patient_id = patients.id inner join transaction_definitions on
    patient_companion.companion_proximity=transaction_definitions.id
WHERE patient_companion.patient_protocol_number='{$protokolno}' AND patient_companion.status='1' $sqk_ekle1";


       $sql = sql_select($sql_metin);

         $sqlcount = sql_select("select * from patient_companion  WHERE patient_protocol_number='{$protokolno}' AND status='1'");


    $result["total"] = count($sqlcount);
    $result["rows"] = $sql;
    if ($result["total"] > 0){
    echo json_encode($result);
    //echo $sql_metin;
    }else{
        echo 2;
    }
    ?>

<?php }

elseif ($islem == "izinlistesi") { ?>

    <table class="table table-bordered border-dark"
               style=" background:white;width: 100%;font-size: 13px" id="izintab-<?php echo $_GET["getir"]; ?>">

            <thead class="table-light">
            <tr>
                <th>İşlem Tarihi</th>
                <th>Protokol</th>
                <th>İzin Başlangiç</th>
                <th>İzin Bitiş</th>
            </tr>
            </thead>
            <tbody>


            <?php
            $say = 0;
            $protocol_number=$_GET["getir"];
            $demo = "Select * from patient_permission WHERE protocol_number=$protocol_number AND status='1'";
            $hello=verilericoklucek("$demo");
            foreach ((array) $hello as $row) {
                $hastakayit = singular("patient_registration", "protocol_number", $row['patient_protocol_number']);
                $patients = singular("patients", "id", $hastakayit['patient_id']);
//                if ($hastakayit['anne_tc_kimlik_numarasi']==''){
//                    $patients = singular("patients", "id", $hastakayit['patient_id']);
//                }else{
//                    $ANNETC=$hastakayit['anne_tc_kimlik_numarasi'];
//                    $dogumsira=$hastakayit['dogum_sirasi'];
//                    $patients = singular("patients", "id", $hastakayit['patient_id']);
//                }
                ?>
                <tr id="<?php echo $row["id"]; ?>" class="izinbodyguncelle">

                    <td><?php echo $row['insert_datetime']; ?></td>
                    <td><?php echo $row['protocol_number']; ?></td>
                    <td><?php echo $row['permission_start_date']; ?></td>
                    <td><?php echo $row['permission_end_date']; ?></td>

                </tr>
                <?php //}?>
            <?php } ?>
            </tbody>
        </table>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#izintab-<?php echo $_GET["getir"]; ?>").DataTable({
                "scrollX": true,
                "scrollY": '15vh',
                "lengthChange": false,
                "pageLength": 25,
                "paging":false,
                "info":false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                dom: '<"clear">lfrtip',
            });

            $(".izinbodyguncelle").click(function () {
                var getir = $(this).attr('id');
                let tiklananSatir = $(this);
                if (!tiklananSatir.hasClass("aktif-satir")) {

                    $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {izinid:getir}, function (e) {
                        $('#izinlibodyupdate-'+<?php echo $protocol_number; ?>).html(e);
                    });

                    $('.izinbodyguncelle').each(function () {
                        $(this).removeClass('aktif-satir');
                    });
                    tiklananSatir.addClass('aktif-satir');


                } else {

                    var getir ="<?php echo $protocol_number; ?>";
                    $.get("ajax/yatis/yatismodalbody.php?islem=izingeribody", {getir:getir}, function (e) {
                        $('#izinlibodyupdate-'+<?php echo $protocol_number; ?>).html(e);
                    });

                    tiklananSatir.removeClass('aktif-satir');


                }

            });



        });

    </script>

<?php }

elseif ($islem == "temizliklistesi") { ?>

    <table class="table table-bordered border-dark"
               style=" background:white;width: 100%; font-size:13px" id="temizliktab-<?php echo $_GET["getir"]; ?>">

            <thead class="table-light">
            <tr>
                <th>İşlem Tarihi</th>
                <th>Protokol</th>
                <th>Yatak</th>
                <th></th>
            </tr>
            </thead>
            <tbody>


            <?php
            $say = 0;
            $protocol_number=$_GET["getir"];
            $demo = "Select * from bed_cleaning WHERE protokol_no=$protocol_number AND status='1'";
            $hello=verilericoklucek("$demo");
            foreach ((array) $hello as $row) {
                $hastakayit = singular("patient_registration", "protocol_number", $row['protokol_no']);
                $patients = singular("patients", "id", $hastakayit['patient_id']);
                $yatak = singular("hospital_bed", "id", $row['bed_id']);
                ?>
                <tr id="<?php echo $row["id"]; ?>" class="temizlikbodyguncelle">

                    <td><?php echo $row['insert_datetime']; ?></td>
                    <td><?php echo $row['protokol_no']; ?></td>
                    <td><?php echo $yatak['bed_name']; ?></td>
                    <td>
                        <?php
                        if ($row['bed_status']==1){ ?>
                            <i class="fa-solid  fa-person-circle-check" title="<?php echo $row['employee_userid']; ?>"></i>
                      <?php  } else if ($row['bed_status']==2){ ?>
                            <i class="fa fa-broom" title="Yatak Temizlenmedi.."></i>
                      <?php  }?>
                    </td>

                </tr>
                <?php //}?>
            <?php } ?>
            </tbody>
        </table>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#temizliktab-<?php echo $_GET["getir"]; ?>").DataTable({
                "scrollX": true,
                "scrollY": '15vh',
                "lengthChange": false,
                "pageLength": 25,
                "paging":false,
                "info":false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                dom: '<"clear">lfrtip',
            });

            $(".temizlikbodyguncelle").click(function () {
                var getir = $(this).attr('id');
                let tiklananSatir = $(this);
                if (!tiklananSatir.hasClass("aktif-satir")) {

                    $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {temizlikid:getir}, function (e) {
                        $('#temizbodyupdate-'+<?php echo $protocol_number; ?>).html(e);
                    });

                    $('.temizlikbodyguncelle').each(function () {
                        $(this).removeClass('aktif-satir');
                    });
                    tiklananSatir.addClass('aktif-satir');


                } else {
                    var getir ="<?php echo $protocol_number; ?>";
                    $.get("ajax/yatis/yatismodalbody.php?islem=temizlikgeribody", {getir:getir}, function (e) {
                        $('#temizbodyupdate-'+<?php echo $protocol_number; ?>).html(e);
                    });
                    tiklananSatir.removeClass('aktif-satir');


                }

            });



        });

    </script>

<?php }




elseif($islem=="emanetlistesi"){
    ?>

    <table class="table table-bordered  border-dark table-sm table-hover " style=" background:white;font-size:13px" id="emanettab-<?php echo $_GET["getir"]; ?>">

        <thead class="table-light">
        <tr>
            <th>Emanet No</th>
            <th>Hasta Adi Soyadi</th>
            <th>Emanet Adi</th>
            <th>Açiklama</th>
            <th>Alma Tarihi</th>
            <th>Teslim Tarihi</th>

        </tr>
        </thead>
        <tbody>


        <?php
        $id=$_GET["getir"];
        $say = 0;
        $demo = "Select * from patient_trust WHERE status='1' AND patient_id=$id" ;
        $hello=verilericoklucek("$demo");
        foreach ((array) $hello as $row) {
            $Yatis = singular("patient_registration", "protocol_number",$id);
            if ($Yatis['anne_tc_kimlik_numarasi']==''){
                $patients = singular("patients", "id", $Yatis['patient_id']);
            }else{
//                $ANNETC=$Yatis['anne_tc_kimlik_numarasi'];
//                $DOGUMSiRA=$Yatis['dogum_sirasi'];
//                $patients = tek("SELECT * FROM patients WHERE mother_tc_identity_number='$ANNETC' AND birth_order='$DOGUMSiRA'");
            }
            ?>
            <tr   <?php if ($row["trust_status"]){?> style="background: aqua" <?php }else{ ?> id="<?php echo $row["id"]; ?>" class="modalemanet1" <?php } ?> >

                <td><?php echo $row['id']; ?></td>
                <td><?php echo $patients["patient_name"] . " " . $patients["patient_surname"]; ?></td>
                <td><?php echo $row["escrow_name"]; ?></td>
                <td><?php echo $row["escrow_description"]; ?></td>
                <td><?php echo $row["trust_datetime"]; ?></td>
                <td><?php echo $row["trust_delivery_datetime"]; ?></td>

            </tr>

        <?php } ?>
        </tbody>
    </table>
    <div align="right">

    </div>
    <table class="table   table-bordered table-striped table-nowrap align-middle mb-1 mt-1  rounded-pill" style="left:0px;  bottom:0% !important; position:absolute;width: 50%" >
        <tbody>
        <tr>
            <td style="width:35%;">
                Teslim Edildi.
            </td>
            <td style="width:15%;">
                <div style=" background-color: aqua;padding: 10px; "></div>
            </td>
            <td style="width:35%;">
                Teslim edilmedi
            </td>
            <td style="width:15%;">
                <div style=" background-color: #ffffff;padding: 10px; "></div>
            </td>

        </tr>
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#emanettab-<?php echo $_GET["getir"]; ?>").DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "lengthChange": false,
                "info": false,
                "paging":false,
                scrollY:'25vh',
                dom: '<"clear">lfrtip',
            });
        });
        $(".modalemanet1").click(function () {



            var getir = $(this).attr('id');
            let tiklananSatir = $(this);
            var protokol_number="<?php echo $id; ?>";
            if (!tiklananSatir.hasClass("aktif-satir")) {
                $('.aktif-satir').removeClass('aktif-satir');
                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {emanetid: getir}, function (getVeri) {
                    $('.emanetbody-'+protokol_number).html(getVeri);

                });
                tiklananSatir.addClass('aktif-satir');


            } else {
                $.get("ajax/yatis/yatismodalbody.php?islem=emanetbodygeri", {getir:protokol_number}, function (g) {
                    $('.emanetbody-'+protokol_number).html(g);
                });
                tiklananSatir.removeClass('aktif-satir');


            }


        });
    </script>

<?php }

elseif ($islem =="orderlist") {
    $protokolno=$_GET['protokolno']; ?>
    <table class="table table-bordered border-dark nowrap display w-100 " id="tableorder">
        <thead>
        <tr>
            <th>Tür</th>
            <th>Tedavi Adi</th>
            <th>Tedavi Saati</th>
            <th>ilaç Kullanim Türü</th>
            <th>Doz</th>
            <th>Açiklama</th>
            <th></th>
            <th>Ehu Onay</th>
            <th>ilaç Kullanim Şekli</th>
            <th>Tedavi Türü</th>
            <th>Başlangiç Tarihi</th>
            <th>Bitiş Tarihi</th>
            <th>Onay status</th>
            <th>Red Açiklama</th>
            <th>Ekleme Tarihi</th>
            <th>Ekleyen</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $hello = verilericoklucek("SELECT patient_order.id as orderid,order_application.id as uygulamaid,patient_order.*,order_application.* 
FROM patient_order left join order_application on order_application.order_id=patient_order.id where patient_protokol='protokolno' AND patient_order.status='1'");
        foreach ((array) $hello as $rowa) {
            $stokkart=singularactive("stock_card","id",$rowa["medicine_id"]);
            ?>
            <tr <?php
            if($rowa["uygulamaid"]!=''){  ?>
                style=" background-color: #2db21d;color:white;"
            <?php }
            else if($rowa["approval_status"]=='1'){  ?>
                style=" background-color: #d6ece7;  "
            <?php }
            else if($rowa["approval_status"]=='0'){ ?>
                style=" background-color: #fee0e3; "
            <?php } ?>>
                <td><?php echo islemtanimgetirid($rowa["operation_type"]); ?></td>
                <td><?php if($stokkart["stock_name"]!=''){
                        echo $stokkart["stock_name"];
                    }else{
                        $parcala=explode(",",$rowa["directive_id"]);

                        foreach ((array) $parcala as $value) {
                            echo islemtanimgetirid($value);
                            echo "<br>";
                        }



                    }

                    ?></td>
                <td><?php echo $rowa["transaction_hour"]; ?></td>
                <td><?php echo islemtanimgetirid($rowa["type_of_drug_use"]); ?></td>
                <td><?php echo $rowa["dose"]; ?></td>
                <td><?php echo $rowa["drug_disclosure"]; ?></td>
                <td>
                    <button type="button" data-toggle="modal" data-target="#yatisyapguncelle" orderid="<?php echo $rowa["orderid"]; ?>"
                            class="btn btn-success   btn-sm orderuygulama "><i class="fas fa-check"></i></button>
                    <button type="button" data-toggle="modal" data-target="#yatisyapguncelle" orderid="<?php echo $rowa["orderid"]; ?>"
                            class="btn btn-danger   btn-sm ordersil"><i class="far fa-trash-alt" ></i></button>
                </td>
                <td><?php echo $rowa["ehu_approval"]; ?></td>
                <td><?php echo islemtanimgetirid($rowa["drug_use"]); ?></td>
                <td><?php echo islemtanimgetirid($rowa["type_of_treatment"]); ?></td>
                <td><?php echo $rowa["starting_date"]; ?></td>
                <td><?php echo $rowa["end_date"]; ?></td>
                <td><?php if ($rowa["approval_status"]){
                        echo "ONAYLANDi";
                    }else{
                        echo "ONAYLANMADi";
                    }
                    ?></td>
                <td><?php echo $rowa["cancellation_notice"]; ?></td>
                <td><?php echo kullanicigetirid($rowa["adding"]); ?></td>
                <td><?php echo $rowa["add_date"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="row mt-2">
        <div class="col-md-5">

        </div>
        <div class="col-md-7">
            <table class="table  table-bordered table-striped table-nowrap align-middle mb-0">
                <tbody>
                <tr>
                    <td style="width: 100px;">
                        Beklemede
                    </td>
                    <td style="width: 180px;">
                        <div style=" background-color: #fee0e3;padding: 10px; "></div>
                    </td>
                    <td style="width: 100px;">
                        Kabul edilmiş
                    </td>
                    <td style="width: 180px;">
                        <div style=" background-color: #d6ece7;padding: 10px; "></div>
                    </td>
                    <td style="width: 100px;">
                        Uygulandi
                    </td>
                    <td style="width: 180px;" class="">
                        <div style=" background-color: #2db21d;padding: 10px; " class=""></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

    <script>

        // $('#tableorder').DataTable({
        //     "responsive": true,
        //     "language": { "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json" },
        // });

        // $(document).on('click', '.orderuygulama', function () {
        //     var getir = $(this).attr('orderid');
        //     $.get( "ajax/yatis/yatismodalbody.php?islem=tedaviuygulabody", { getir:getir },function(getVeri){
        //         $('#yatismodalguncelle').html(getVeri);
        //     });
        // });
        //
        // $(document).on('click', '.ordersil', function () {
        //         var getir = $(this).attr('orderid');
        //     $.get( "ajax/yatis/yatismodalbody.php?islem=tedavisilmebody", { getir:getir },function(getVeri){
        //         $('#yatismodalguncelle').html(getVeri);
        //     });
        // });
    </script>

<?php }
elseif($islem=="malzemelistesisecim"){
    $depo=$_POST['depo'];
    $malzemetipi=$_POST['malzemetipi'];
    $yatisprotokol=$_POST['yatisprotokol'];


    ?>
    <div class="row"><h5>STOKLAR</h5> </div>
    <div class="table-responsive">
    <table class="table mb-5" id="tableStockList3">
        <thead class="table-light">

        <tr>

            <th>Malzeme Adi</th>
            <th>Jenerik Adi (Açiklama)</th>
            <th>Jenerik Kodu</th>
            <td>Toplam Miktari</td>
            <td>istek Miktari</td>
            <td>ekle</td>

        </tr>
        </thead>
        <tbody>
        <?php
        $depoStokList = verilericoklucek("SELECT stock_receipt_move.stock_cardid AS stokkartid FROM stock_receipt 
        INNER JOIN stock_receipt_move ON stock_receipt.id=stock_receipt_move.stock_receiptid
            WHERE stock_receipt.status=1 AND stock_receipt.stock_type='$malzemetipi' AND stock_receipt_move.status=1 
            GROUP BY stock_receipt_move.stock_cardid");
        foreach ((array) $depoStokList as $depostok) {
            $stokkartid = $depostok["stokkartid"];
            //$depoid=$depostok["DEPOid"];
            $depoHareketTur = verilericoklucek("SELECT * FROM stock_receipt INNER JOIN stock_receipt_move ON stock_receipt.id=stock_receipt_move.stock_receiptid
                                    WHERE stock_receipt.status=1 AND stock_receipt_move.status=1 AND stock_receipt.warehouseid='3' AND stock_receipt_move.stock_cardid='$stokkartid'");
            $sum = 0;
            foreach ((array) $depoHareketTur as $depoHTur) {
                if ($depoHTur["move_type"] == '1') {
                    //1 ise depoya gelen miktardir
                    $sum = $sum + $depoHTur["stock_move_amount"];
                } elseif ($depoHTur["move_type"] == '2') {
                    //2 ise depodan çikmiştir
                    $sum = $sum - $depoHTur["stock_move_amount"];
                }
            }
            $stockList = verilericoklucek("SELECT * FROM stock_card WHERE id='$stokkartid' AND status=1");
            foreach ((array) $stockList as $sl) {
                $okodu = $sl["measurement_code"];

                $olcuKodu = islemtanimgetirid($okodu);

                ?>
                <tr  >

                    <td class="madi" data-id-2="<?php echo $stokkartid; ?>"
                        malzemeadi="<?php echo $sl["stock_name"]; ?>"><?php echo $sl["stock_name"]; ?></td>
                    <td class="aadi" data-id-3="<?php echo $stokkartid; ?>"
                        atcadi="<?php echo $sl["atc_name"]; ?>"><?php echo $sl["atc_name"]; ?></td>
                    <td class="akodu" data-id-11="<?php echo $stokkartid; ?>"
                        test="<?php echo $sl["atc_code"]; ?>"> <?php echo $sl["atc_code"]; ?> </td>
                    <td class="sum" data-id-8="<?php echo $stokkartid; ?>"
                        toplam="<?php echo $sum; ?>"><?php echo $sum; ?></td>
                    <td class="istekmiktari"><input type="number" class="form-control" id="miktar_input"
                                                    input-id="<?php echo $stokkartid; ?>" min="0"
                                                    max="<?php echo $sum; ?>" placeholder="" name="istek_miktari"
                        ></td>
                    <td class="">
                        <button stokkartid="<?php echo $stokkartid; ?>" id=""
                                class="stok_istek_btn btn btn-sm btn-primary">Ekle
                        </button>
                    </td>
                </tr>
                <?php
            }
        }

        ?>
        </tbody>
    </table>

    <div class="row"><h5>iSTENECEK STOKLAR</h5> </div>
    <div class="table-responsive">
        <table class="table mb-3" id="request-stock-list">
            <thead class="table-light">
            <tr>
                <th>Malzeme Adi</th>
                <th>Jenerik Adi (Açiklama)</th>
                <th>Jenerik Kodu</th>
                <th>Toplam Miktari</th>
                <th>istek Miktari</th>
                <th>işlem</th>
            </tr>
            </thead>
            <form action="javascript:void(0);">
                <tbody class="stok_istek">
                <!-- Data table hatasi aliyorum. buraya çok fazla stok isteği eklenebileceği için tabloda sayfalama gerekli -->


                </tbody>
            </form>
        </table>
    </div>

    <div class="row mb-3 mt-3">
        <div class="col-md-6"></div>
        <div class="col-md-3" align="right">
            <input type="checkbox" id="istemdisabledbtn" style="border-color: red" name="order[stock_request_status]" value="1">
            <label for="html">istek gönderilsin mi</label>
        </div>
        <div class="col-md-3" align="right">
            <div id="aciklamaekledetay"></div>
            <button class="toplu_istek_ekle_btn btn  btn-success" disabled data-dismiss="modal">
                istekleri  Kaydet
            </button>
        </div>
    </div>

    <script>
        $('#tableStockList3').DataTable({

            "responsive": true,
            "binfo": false,
            "pageLength": 3,
            "lengthChange": false
        })
        $("#istemdisabledbtn").on("click", function () {
            $('.toplu_istek_ekle_btn').prop('disabled', function(i, v) { return !v; });
            //$( ".toplu_istek_ekle_btn" ).prop( "disabled", false );
        });
    </script>



    <script>
        $('.stok-detay-belirt-form').hide();



        $("#request-stock-list").on('click', '.delete_istek', function () {
            $(this).closest('tr').remove();
            var input_sil =  $(this).attr('data-id-10');
            $('[stokkartid-detay="'+ input_sil +'" ]').remove();

        });

        $(document).on('click', '.stok_istek_btn', function () {

            //var srequestid = $(this).attr('data-id');
            var stokkartid = $(this).attr('stokkartid');

            var istekmiktari = $('[input-id="' + stokkartid + '"]').val();
            var malzemeadi = $('[data-id-2="' + stokkartid + '"]').attr('malzemeadi');
            var atcadi = $('[data-id-3="' + stokkartid + '"]').attr('atcadi');
            var atckodu = $('[data-id-11="' + stokkartid + '"]').attr('test');

            //alert(atckodu);
            var tasinirnumarasi = $('[data-id-4="' + stokkartid + '"]').attr('tasinirnumarasi');
            var mkysmalzemekodu = $('[data-id-5="' + stokkartid + '"]').attr('mkysmalzemekodu');
            var olcukoduisim = $('[data-id-6="' + stokkartid + '"]').attr('olcukoduisim');
            //var skt = $('[data-id-7 = "' + srequestid + '"]').attr('skt');
            var toplam = $('[data-id-8 = "' + stokkartid + '"]').attr('toplam');

            let oncekiEklenen = "",
                ilacVarMi = false;

            if ($('.request-stock').length == 0) {
                $(".stok_istek").append("<tr class='request-stock' data-id-9='" + stokkartid + "'><input type='hidden' name='deneme[]' stock_cardid='" + stokkartid + "' atckodu='" + atckodu + "' " +
                    "istek_miktari='" + istekmiktari + "' />" +
                    "<td> " + malzemeadi + " </td>  <td> " + atcadi + " </td> <td> " + atckodu + " </td> <td> " + toplam + " </td> <td istek-toplam='" + stokkartid + "'> " + istekmiktari + " </td> <td> <button class='btn btn-danger delete_istek' data-id-10='" + stokkartid + "' type='button'>Sil</button> </td> </tr>");

            } else {
                $('.request-stock').each(function(e) {
                    if ($(this).attr('data-id-9') == stokkartid) {
                        ilacVarMi = true;
                        oncekiEklenen = $(this);
                    }
                });

                if (ilacVarMi) {
                    console.log(oncekiEklenen);
                } else {
                    $(".stok_istek").append("<tr class='request-stock' data-id-9='" + stokkartid + "'><input type='hidden' name='deneme[]' stock_cardid='" + stokkartid + "' atckodu='" + atckodu + "' " +
                        "istek_miktari='" + istekmiktari + "' />" +
                        "<td> " + malzemeadi + " </td>  <td> " + atcadi + " </td> <td> " + atckodu + " </td> <td> " + toplam + " </td> <td istek-toplam='" + stokkartid + "'> " + istekmiktari + " </td> <td> <button class='btn btn-danger delete_istek' data-id-10='" + stokkartid + "' type='button'>Sil</button> </td> </tr>");

                }
            }

            // var formm = $('.stok-detay-belirt-form').html();

            alertify.confirm("<div class='row'><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label'>Başlangiç Tarihi</label>" +
                " <input class='form-control' type='date'   id='baslangic_tarihi'>" +
                "</div><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label mt-1'>Bitiş Tarih</label>" +
                "<input class='form-control' type='date' id='Bitis_tarihi'>" +
                "</div></div> " +
                "<div class='row'><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label mt-1'>ilaç Saati</label>" +
                "<input class='form-control' type='time' id='ilac_saat'>" +
                "</div><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label mt-1'>ilaç Dozu</label>" +
                "<input class='form-control' type='number' id='doz'>" +
                "</div></div> " +
                "<div class='row'><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label mt-1'>Kullanim Şekli</label>" +
                "<select class='form-select'  id='kullanim_sekli'> " +
                "<option value=''>ilaç kullanim şekli seçiniz.</option>" +
                <?php
                $sqle =verilericoklucek("SELECT * FROM transaction_definitions WHERE definition_type='ILAC_KULLANIM_SEKLI'");
                foreach ((array) $sqle as $row ) {
                // var_dump($rowa);
                ?>
                "<option value='<?php echo $row["id"]; ?>' ><?php echo $row['definition_name']; ?></option>" +
                <?php } ?>
                " </select>" +
                "</div><div class='col-md-6'> " +
                "<label for='basicpill-firstname-input' class='form-label mt-1'>Tedavi Türü</label>" +
                "<select class='form-select'  id='tedavi_turu'> " +
                "<option value=''>Tedavi türünü seçiniz..</option>" +
                <?php
                $sql = verilericoklucek("SELECT * FROM transaction_definitions WHERE definition_type='TEDAVI_TURU'");
                foreach ((array) $sql as $rowa ) {
                // var_dump($rowa);
                ?>
                "<option value='<?php echo $rowa["id"]; ?>' ><?php echo $rowa['definition_name']; ?></option>" +
                <?php } ?>
                " </select>" +
                "</div></div> " +

                "<label for='basicpill-firstname-input' class='form-label mt-1'>Kullanim Tipi</label>" +
                "<select class='form-select'  id='ilackullanimtipi'> " +
                "<option value=''>ilaç kullanim tipini seçiniz..</option>" +
                <?php
                $sql = verilericoklucek("SELECT * FROM transaction_definitions WHERE definition_type='ILAC_KULLANIM_TIPI'");
                foreach ((array) $sql as $rowa ) {
                // var_dump($rowa);
                ?>
                "<option value='<?php echo $rowa["id"]; ?>' ><?php echo $rowa['definition_name']; ?></option>" +
                <?php } ?>
                " </select>" +


                "<label for='basicpill-firstname-input' class='form-label mt-1'>Açiklama</label>" +
                "<textarea  class='form-control' type='text'   id='aciklama'></textarea>"  , function(){

                var BASLANGiC_TARiHi = $('#baslangic_tarihi').val();
                var BiTiS_TARiHi = $('#Bitis_tarihi').val();
                var DOZ = $('#doz').val();
                var KULLANiM_SEKLi = $('#kullanim_sekli').val();
                var TEDAVi_TURU = $('#tedavi_turu').val();
                var ACiKLAMA = $('#aciklama').val();
                var iLAC_SAAT = $('#ilac_saat').val();
                var iLAC_KULLANiM_TiPi = $('#ilackullanimtipi').val();


                $("#aciklamaekledetay").append("<input type='text' id='aciklama-detay-input' name='aciklama-detay[]' ilackullanimtipi-val='" +iLAC_KULLANiM_TiPi+ "'  ilac-saat-val='" +iLAC_SAAT+ "' class='" + stokkartid + "' stokkartid-detay='" + stokkartid + "' baslangic_tarihi='"+ BASLANGiC_TARiHi +"' Bitis_tarihi='"+ BiTiS_TARiHi +"' doz='"+ DOZ +"' kullanim_sekli='"+ KULLANiM_SEKLi +"' tedavi_turu='" + TEDAVi_TURU + "' aciklama='"+ ACiKLAMA +"' />");



            }, function(){ alertify.warning('işlem Yapmaktan Vazgeçtiniz')}).set({labels:{ok: "Kaydet", cancel: "Vazgeç"}}).set({title:"işlem Detay"});

        });






    </script>


    <script>

        $(".toplu_istek_ekle_btn").off().on("click", function () {


            var stockrequest = $("#requestform").serialize();

            $.ajax({
                type: 'POST',
                url: 'ajax/yatis/yatisislem.php?islem=stock-request-save',
                data: stockrequest,
                success: function (veri) {
                    //$("#sonucyaz").html(veri);
                    // alert(veri);
                    var veri2 = veri.trim()
                    var stock_request_id =veri2;
                    var wanted_stock_card_id = [];
                    var wanted_medicine_generic_code = [];
                    var requested_amount = [];

                    $("input[name='deneme[]']").off().each(function () {
                        wanted_stock_card_id.push($(this).attr('stock_cardid'));
                        requested_amount.push($(this).attr('istek_miktari'));
                        wanted_medicine_generic_code.push($(this).attr('atckodu'));
                    });


                    var baslangic_tarihi_dizi = [];
                    var bitis_tarihi_dizi = [];
                    var doz_dizi = [];
                    var kullanim_sekli_dizi = [];
                    var tedavi_turu_dizi = [];
                    var aciklama_dizi = [];
                    var stok_kart_id_dizi = [];
                    var ilac_saat = [];
                    var ilac_kullanim_tipi = [];
                    var yatisprotokol="<?php echo $yatisprotokol; ?>";
                    var istemid=veri2;

                    $("input[name='aciklama-detay[]']").off().each(function () {
                        baslangic_tarihi_dizi.push($(this).attr('baslangic_tarihi'));
                        bitis_tarihi_dizi.push($(this).attr('bitis_tarihi'));
                        doz_dizi.push($(this).attr('doz'));
                        kullanim_sekli_dizi.push($(this).attr('kullanim_sekli'));
                        tedavi_turu_dizi.push($(this).attr('tedavi_turu'));
                        aciklama_dizi.push($(this).attr('aciklama'));
                        stok_kart_id_dizi.push($(this).attr('stokkartid-detay'));
                        ilac_saat.push($(this).attr('ilac-saat-val'));
                        ilac_kullanim_tipi.push($(this).attr('ilackullanimtipi-val'));

                    });

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/yatis/yatisislem.php?islem=multi_request_insert',
                        data: {
                            wanted_stock_cardid: wanted_stock_card_id,
                            requested_amount: requested_amount,
                            stock_requestid: stock_request_id,
                            wanted_medicine_generic_code: wanted_medicine_generic_code,
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);

                            $.ajax({
                                type: 'POST',
                                url: 'ajax/yatis/yatisislem.php?islem=order_insert',
                                data: {
                                    stok_kart_id_dizi: stok_kart_id_dizi,
                                    bitis_tarihi_dizi:bitis_tarihi_dizi,
                                    baslangic_tarihi_dizi:baslangic_tarihi_dizi,
                                    doz_dizi:doz_dizi,
                                    kullanim_sekli_dizi:kullanim_sekli_dizi,
                                    tedavi_turu_dizi:tedavi_turu_dizi,
                                    aciklama_dizi:aciklama_dizi,
                                    yatisprotokol:yatisprotokol,
                                    ilac_saat:ilac_saat,
                                    ilac_kullanim_tipi:ilac_kullanim_tipi,
                                    request_id:istemid,
                                },
                                success: function (ce) {
                                    $("#sonucyaz").html(ce);
                                    var $yportokol="<?php echo $yatisprotokol; ?>";
                                    $.get("ajax/yatis/yatislistesi.php?islem=orderlist", {$yportokol: $yportokol}, function (getVeri) {
                                        $('.orderlistesibody').html(getVeri);
                                    });

                                }

                            });

                        }

                    });


                }



            });




        });
    </script>



    <?php
}
elseif ($islem == "figurlerigetir"){
     $metin='';
     $protokol_numarasi=$_POST['protokol_numarasi'];

    $bolumgetir = "select figure_file as figur_adresi,figure_name as figur_adi from patient_figure where protocol_number=$protokol_numarasi and status=1";

    $hello=verilericoklucek($bolumgetir);
    $sayi=0;
    $verisay=count($hello);
    if ($verisay>0){
        foreach ((array) $hello as $value){
        $figur_adresi=$value['figur_adresi'];
        $figur_adi=$value['figur_adi'];
      $metin.='<img src="'.$figur_adresi.'" width="20px" style="margin-left:5px;margin-right:5px;" height="20px"/>';
//        $metin.=$figur_adi;
        }
        echo $metin;
    }
    else{
        echo 'figur yok';
    }

}
elseif ($islem == "yatissorgulasql"){
    $birimid = $_GET['birimid'];
    $baslangic = $_GET['baslangic'];
    $bitis = $_GET['bitis'];
    $doktor = $_GET['doktorid'];
    $isim = $_GET['hastaisim'];
    $soyisim = $_GET['hastasoyisim'];
    $tc = $_GET['tcnumarasi'];
    $yatis_tur = $_GET['yatis_tur'];
    $hastaya_uniqid = $_GET['hastaya_uniqid'];
    $sqk_ekle1='';


        if ($baslangic==$bitis && $doktor=='' && $isim=='' && $soyisim==''&& $tc=='' && $birimid!=''){
            //echo 'tüm hastalar gelsin';
            $sqk_ekle1.=" AND service_id='$birimid' ";
        }
        else if($baslangic!=$bitis && $doktor=='' && $isim=='' && $soyisim==''&& $tc=='' && $birimid!=''){
            //echo 'tarih aralığı gelsin';
            $sqk_ekle1.= " service_id='$birimid'  AND hospitalized_accepted_datetime BETWEEN  '$baslangic'  AND '$bitis' ";
        }
        else if($baslangic==$bitis && $doktor!='' && $isim=='' && $soyisim==''&& $tc=='' && $birimid==''){
            //echo  'doktor seçimi ile hasta getirme';
            $sqk_ekle1.= " AND service_doctor='$doktor' ";
        }
        else if($baslangic==$bitis && $doktor!='' && $isim=='' && $soyisim==''&& $tc=='' && $birimid!=''){
           // echo  'servis doktor  seçimi ile hasta getirme';
            $sqk_ekle1.= " AND service_doctor='$doktor' AND service_id='$birimid' ";
        }
        else if($baslangic==$bitis && $doktor=='' && $isim!='' && $soyisim==''&& $tc=='' && $birimid==''){
            //echo 'hasta isimle   hasta getirme';
            $sqk_ekle1.= " and ( upper(patients.patient_name) LIKE '%$isim%' or lower(patients.patient_name) LIKE '%$isim%') ";
        }
        else if($baslangic==$bitis && $doktor=='' && $isim=='' && $soyisim!=''&& $tc=='' && $birimid==''){
            //echo 'hasta soyisimle   hasta getirme';
            $sqk_ekle1.= " and ( upper(patients.patient_surname) LIKE '%$soyisim%' or lower(patients.patient_surname) LIKE '%$soyisim%') ";
        }
        else if($baslangic==$bitis && $doktor=='' && $isim!='' && $soyisim!=''&& $tc=='' && $birimid==''){
            //echo 'hasta isim ve soyisim ile   hasta getirme';
            $sqk_ekle1.= " and ( upper(patients.patient_surname) LIKE '%$soyisim%' or lower(patients.patient_surname) LIKE '%$soyisim%' or upper(patients.patient_name) LIKE '%$isim%' or lower(patients.patient_name) LIKE '%$isim%') ";
        }
        else if($baslangic==$bitis && $doktor=='' && $isim=='' && $soyisim==''&& $tc!='' && $birimid==''){

            if (strlen($tc)==11){
                //echo  'hasta tc   hasta getirme';
                $demo = " and patients.tc_id='$tc' ";
            }else{
                //echo 'hasta protokol ile   hasta getirme';
                $demo = " and protocol_number=$tc ";
            }

        }


 $sql_metin="select patient_registration.protocol_number as protokol_numarasi,concat(patient_name,' ',patient_surname) as adi_soyadi,
       transaction_definitions.definition_name as yatis_tur,users.name_surname as doktor_adi_soyadi,hospitalization_demand as yatis_status
from patient_registration inner join patients on patient_registration.patient_id=patients.id
                          inner join transaction_definitions on patient_registration.hospitalization_demand=(transaction_definitions.definition_supplement)::smallint
                          inner join users on patient_registration.service_doctor=users.id
WHERE  definition_type='YATIS_DURUM' AND hospitalization_demand='$yatis_tur' AND patient_registration.status='1' AND patients.status='1' $sqk_ekle1";


$sql = sql_select($sql_metin);

 if ($sql > 0) {

        echo json_encode($sql);

    } else {

        echo 2;

    }
  }

elseif ($islem=="yatissorgula"){
    $birimid = $_GET['birimid'];
    $baslangic = $_GET['baslangic'];
    $bitis = $_GET['bitis'];
    $doktor = $_GET['doktorid'];
    $isim = $_GET['hastaisim'];
    $soyisim = $_GET['hastasoyisim'];
    $tc = $_GET['tcnumarasi'];
    $yatis_tur = $_GET['yatis_tur'];
    $hastaya_uniqid = $_GET['hastaya_uniqid'];

    ?>

  <table class="table table-bordered table-sm table-hover" style=" background:white;width: 99%;" id="yatislistesi-<?php echo $hastaya_uniqid;?>">

        <thead class="table-light">
        <tr>
            <th>Figur</th>
            <th>Yatiş No</th>
            <th>Adi Soyadi</th>
            <th>Yatiş status</th>
            <th>Doktor</th>
        </tr>
        </thead>

    </table>

    <script >
    var datatable_yukseklik=ekran_yukseklik-100;

    var yatisdatatable=$('#yatislistesi-<?php echo $hastaya_uniqid;?>').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            lengthChange:false,
            paging:false,
            scrollY: datatable_yukseklik,
            scrollX: true,
             ajax: {
                url: 'ajax/yatis/yatislistesi.php?islem=yatissorgulasql' +
                                    '&birimid=<?php echo $birimid; ?>&baslangic=<?php echo $baslangic; ?>&bitis=<?php echo $bitis; ?>'+
                                    '&doktorid=<?php echo $doktor; ?>&hastaisim=<?php echo $isim; ?>&hastasoyisim=<?php echo $soyisim; ?>&'
                                    +'tcnumarasi=<?php echo $tc; ?>&yatis_tur=<?php echo $yatis_tur; ?>&hastaya_uniqid=<?php echo $hastaya_uniqid; ?>',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },
             "fnRowCallback": function (nRow, aData) {

                $(nRow)
                    .attr('protokol_no',aData['protokol_numarasi'])
                    .attr('adi_soyadi',aData['adi_soyadi'])
                    .attr('yatis_tur',aData['yatis_tur'])
                    .attr('class','yatislisteclick')
            },

            "initComplete": function (settings, json) { },

            columns:[
                {
                    data:null,
                    render:function (data){
                       // var protokol_numarasi=data.protokol_numarasi;
                       //  alert(protokol_numarasi);
                       //  $.ajax({
                       //      type: "POST",
                       //      url: "/ajax/yatis/yatislistesi.php?islem=figurlerigetir",
                       //      timeout: 500,
                       //      data: {"protokol_numarasi": protokol_numarasi},
                       //      success: function (e) {
                       //         $("."+protokol_numarasi).html(e);
                       //      }
                       //  });
                       //
                       // return '<div align="center" class="'+protokol_numarasi+'"></div>';
                        return data.protokol_numarasi;
                    }
                },
                {data:'protokol_numarasi'},
                {data:'adi_soyadi'},
                {data:'yatis_tur'},
                {data:'doktor_adi_soyadi'},

            ],
    });

    $('#yatislistesi-<?php echo $hastaya_uniqid;?> tbody').on('contextmenu', 'tr', function(e) {
    e.preventDefault();

    // Sağ tıklanan satırın verisini alın
    var rowData = yatisdatatable.row(this).data();

     var protokol_numarasi=rowData[1];
     $(".protokol_numarasi_alma").val(protokol_numarasi);
     $('#yatisMenu').menu('show', {
                left: e.pageX,
                top: e.pageY
      });

  });




    $("body").off("click", ".yatislisteclick").on("click", ".yatislisteclick", function (e) {
        var protokol_no = $(this).attr('protokol_no');
        var adi_soyadi = $(this).attr('adi_soyadi');
        var yatis_tur = $(this).attr('yatis_tur');

      if (yatis_tur!='Yatiş bekliyor'){

            var tab_class =protokol_no;
            var tabs_count = $('.' + tab_class).length;
            if (tabs_count == 0) {
                $.get("ajax/yatis/yatismodalbody.php?islem=hastabilgi&hastaya_uniqid=<?php echo $hastaya_uniqid; ?>", { protokolno:protokol_no},function(get){
                    $('#modul-ana-tab').tabs('add',{
                        iconCls: tab_class + ' fa fa-bed-front text-danger',
                        title: adi_soyadi,
                        content: '<div class="mt-1">'+get+'</div>',
                        closable: true
                    });
                });

            } else {
                $("." + tab_class).trigger("click");
            }
            $('#panel_servis').panel('setTitle', 'Yatışta Olan Hastalar');

      }else if (yatis_tur=='Yatiş bekliyor'){
            var hastaya_uniqid="<?php echo $hastaya_uniqid; ?>";

            var win=$('.windows-<?php echo $hastaya_uniqid; ?>')
            win.window('setTitle', 'Yatış Kabul');


            $('#windows-<?php echo $hastaya_uniqid; ?>').window('open');
            $('#windows-<?php echo $hastaya_uniqid; ?>').window('refresh', 'ajax/yatis/yatismodalbody.php?islem=yatisyapbody&getir='+protokol_no+'&hastaya_uniqid='+hastaya_uniqid+'');


     }

    });



    </script>
     <style>
         .dataTables_wrapper .dataTables_filter input {
            margin-right:20px;
         }
     </style>


<?php }

elseif ($islem == "figursorgula") {
    $protokolno=$_GET['protokolno'];
    $figur_name=$_GET['figur_name'];

    $demo = "select * from patient_figure  WHERE protocol_number={$protokolno} AND figure_name='$figur_name' AND status='1'";
    $say=0;
    $hello = verilericoklucek($demo);
    foreach ((array) $hello as $row) {
        if ($row['id']!=''){
            $say++;
        }
    }


    if ($say>0){
        echo 1;
    }else{
        echo 0;
    }

}


elseif ($islem == "yatisiptalolan") {
    $birimid = $_GET['birimid'];
    ?>

    <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="yatisiptal">

        <thead class="table-light">
        <tr>
            <th>Yatiş No</th>
            <th>Adi Soyadi</th>
            <th>Yatiş iptal Detayı</th>
            <th>Doktor</th>
        </tr>
        </thead>
        <tbody>


        <?php
        $say = 0;

        $demo = "SELECT * from  patient_registration  WHERE service_id=$birimid AND status='2' ";
        $hello = verilericoklucek($demo);
        foreach ((array) $hello as $row) {

            if($row["anne_tc_kimlik_numarasi"]){

                $HASTABiLGiLERi=singular("patients","mother_tc_identity_number",$row["anne_tc_kimlik_numarasi"]);

                $hastaadisoyadi=$HASTABiLGiLERi["patient_name"] . " " . $HASTABiLGiLERi["patient_surname"];
            }else{
                $HASTABiLGiLERi=singular("patients","id",$row["patient_id"]);


                $hastaadisoyadi=$HASTABiLGiLERi["patient_name"] . " " . $HASTABiLGiLERi["patient_surname"];
            }
            $say++;



            ?>
            <tr>


                <td><?php echo $row['id'] ?></td>
                <td><?php echo $hastaadisoyadi; ?></td>
                <td><?php echo $row['delete_detail'] ?></td>
                <td><?php echo kullanicigetirid($row['service_doctor']); ?></td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#yatisiptal').DataTable({
               "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                scrollY: ekran_yukseklik-80,
                paging: false,
                scrollX: true,
            });
            // $(".yatisliste").click(function(){
            //
            //     var yatisprotokolno = $(this).attr('protokolno');
            //
            //     $.get("ajax/yatis/yatismodalbody.php?islem=hastabilgi", { yatisprotokolno:yatisprotokolno },function(e){
            //         $('#hastabilgibody').html(e);
            //         $.get("ajax/yatis/yatismodalbody.php?islem=yatanhastaislemlerim", { yatisprotokolno:yatisprotokolno },function(getir){
            //             $('.yatanhastaislemleri').html(getir);
            //
            //         });
            //     });
            //
            // });
        });
    </script>
<?php }

elseif ($islem == "hastayapilanhizmet") {
    $birimid = $_GET['birimid'];
    $kullanicininidsi=$_SESSION["id"];
    ?>

        <table class="table table-bordered border-primary mb-0 w-100" style="font-size:13px" id="hastalaristemler">

            <thead>
            <tr>
                <th>işlem Tarihi</th>
                <th>Hasta Adı Soyadı</th>
                <th>Tetkik Kodu</th>
                <th>Adı</th>
                <th>Adet</th>
                <th>istem Dr</th>
                <th>işlem Dr</th>
                <th>Sonuç</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $istemlersilyetkisi=yetkisorgula($kullanicininidsi,"istemlersilyetkisi");
            $hastaistemlerigetir="select * from patient_prompts inner join patient_registration on patient_prompts.protocol_number=patient_registration.protocol_number
                where patient_registration.service_id=$birimid and  patient_registration.status='1'";

            $hello=verilericoklucek($hastaistemlerigetir);
            foreach ($hello as $rowa) {
                $hastalarid=$rowa['patient_id'];
                $kullanicibilgileri=singularactive("patients","id",$hastalarid);
                $adi_soyadi=$kullanicibilgileri['patient_name'].' '.$kullanicibilgileri['patient_surname']
//                $toplamadet=$toplamadet+$rowa["piece"];
//                $toplamucret=$toplamucret+$rowa["fee"];
//                $toplamhizmet_bedeli=$toplamhizmet_bedeli+$rowa["service_fee"];
//                $odeme_yapildi=$rowa["payment_completed"];

                ?>
                <tr>
                    <td><?php echo nettarih($rowa["request_date"]); ?></td>
                    <td><?php echo $adi_soyadi; ?></td>
                    <td><?php echo $rowa["request_code"]; ?></td>
                    <td><?php echo $rowa["request_name"]; ?></td>
                    <td><?php echo $rowa["piece"]; ?></td>
                    <td><?php  $istemiyapankullanicigetir=singular("users","id",$rowa["request_userid"]); echo $istemiyapankullanicigetir["name_surname"]; ?></td>
                    <td><?php echo $rowa["action_doer_userid"]; ?></td>
                    <td><?php echo $rowa["request_resulted_datetime"]; ?></td>

                </tr>
            <?php } ?>
            </tbody>

        </table>
        <div style="display: none;">
        <div class="tüm-hizmetler-yazdir">
            <div class="text-center borer-701">
                <p class="fw-bold fs-5"><?php echo hastane_adi; ?></p>
            </div>
            <div class="page-header">
              <h1>Hasta Hizmet Listesi</h1>
            </div>
        </div>
   </div>
    <script>
        $(document).ready(function () {
            var hastane_adi='<?php echo hastane_adi; ?>';
            $('#hastalaristemler').DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                scrollY:ekran_yukseklik-80,
                paging: false,
                scrollX: true,
                dom: '<"container-fluid"<"row"<"col"B><"col"l><"col"f>>>rtip',
                buttons: [
                    'excel',
                 {
                         extend: 'print',
                         text: '<i class="fas fa-print"></i> Yazdır',
                         className:'btn btn-info',
                         autoPrint: true,
                         title: "",
                         exportOptions: {
                             columns: [0, 1 ,2,3,4,5,6,7]
                         },
                         orientation: 'landscape',
                         pageSize: 'LEGAL',
                         customize: function(win) {
                             var print_body = $('.tüm-hizmetler-yazdir').html();
                             $(win.document.body).prepend(print_body);
                             $(win.document.body).css("color" , "dark");
                             $(win.document.body).find( 'table' ).addClass( 'compact' ).css( 'font-size', 'inherit' );
                         }
                  },
                    {
                        extend: 'pdf',
                        text: '<i class="fa-regular fa-file-pdf"></i> PDF',
                        className:'btn btn-success',
                        title:hastane_adi,
                        exportOptions: {
                            columns: [0, 1 ,2,3,4,5,6,7]
                        },
                        customize: function(pdfDocument) {
                            var print_body = $('.tüm-hizmetler-yazdir').text();
                            pdfDocument.content[0].text = print_body.trim();
                            pdfDocument.content[1].table.widths = [60,60,60,60,60,60,60,60];
                        }
                    }
                ]
            });

        });
    </script>


<?php }


?>