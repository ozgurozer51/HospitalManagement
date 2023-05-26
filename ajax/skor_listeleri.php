<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid = $_SESSION['id'];
//$simdikitarih = date('d-m-Y H:i:s');
//$dizi = explode(" ", $simdikitarih);
//$tarih = $dizi['0'];
//$saat = $dizi['1'];
$islem = $_GET['islem'];

if($islem=="apache_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0" id="apachtable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Vücut Isısı (°C)</th>
            <th>Ortalama Kan Basıncı(mmHg)</th>
            <th>Kalp Hızı(nabız/dk)</th>
            <th>Solunum Hızı (dk)</th>
            <th>Serum Sodyum (mmol/L)</th>
            <th>Serum Potasyum (mmol/L) (mEq/L)</th>
            <th>Glasgow Koma Skore</th>
            <th>Kronik Organ Yetmezilği</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_apache2 where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo skortanimgetir($rowa["body_temperature"]); ?></td>
                <td><?php echo skortanimgetir($rowa["blood_pressure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["heart_rate"]); ?></td>
                <td><?php echo skortanimgetir($rowa["respiratory_rate"]); ?></td>
                <td><?php echo skortanimgetir($rowa["serum_sodium"]); ?></td>
                <td><?php echo skortanimgetir($rowa["serum_pt"]); ?></td>
                <td><?php echo skortanimgetir($rowa["glasgow"]); ?></td>
                <td><?php echo skortanimgetir($rowa["chronic_organ_failure"]); ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm apachedelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#apachtable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".apachedelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=apachedelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);


                        $.get("ajax/skor_listeleri.php?islem=apache_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.apache_listesi').html(getVeri);

                        });

                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="glaskow_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="glaskowtable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Göz Açıklığı</th>
            <th>Motor Yanıt</th>
            <th>Sözel Cevap</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_glaskow where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo skortanimgetir($rowa["eye_aperture"]); ?></td>
                <td><?php echo skortanimgetir($rowa["engine_response"]); ?></td>
                <td><?php echo skortanimgetir($rowa["verbal_response"]); ?></td>
                <td>

                    <button type="button" class="btn sil-btn   btn-sm glaskowdelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>

                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#glaskowtable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });
        $(".glaskowdelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=glaskowdelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);

                        $.get("ajax/skor_listeleri.php?islem=glaskow_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.glaskow_listesi').html(getVeri);
                        });

                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="spas_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="spastable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Yatış Şekli</th>
            <th>Glasgow</th>
            <th>Sistolik Kan Basıncı</th>
            <th>Kalp Hızı</th>
            <th>Vücut Isısı</th>
            <th>İdrar Çıkışı</th>
            <th>Serum Ure veya BUN</th>
            <th>HCO3</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_spas where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo skortanimgetir($rowa["sleeping_style"]); ?></td>
                <td><?php echo skortanimgetir($rowa["glasgow"]); ?></td>
                <td><?php echo skortanimgetir($rowa["blood_pressure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["heart_rate"]); ?></td>
                <td><?php echo skortanimgetir($rowa["body_temperature"]); ?></td>
                <td><?php echo skortanimgetir($rowa["urine_output"]); ?></td>
                <td><?php echo skortanimgetir($rowa["serum_ure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["hco3"]); ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm spasdelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#spastable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".spasdelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=spasdelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);


                        $.get("ajax/skor_listeleri.php?islem=spas_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.spas_listesi').html(getVeri);

                        });

                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="prism_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="prismtable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Sistolik Kan Basıncı (mmHg)</th>
            <th>Diastolik Kan Basıncı</th>
            <th>Kalp Hızı</th>
            <th>Solunum Sayısı</th>
            <th>Kalsiyum</th>
            <th>Potasyum (mEq/L)</th>
            <th>Glukoz</th>
            <th>HCO3</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_prism where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo skortanimgetir($rowa["systolic_blood_pressure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["diastolic_blood_pressure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["heart_rate"]); ?></td>
                <td><?php echo skortanimgetir($rowa["respiration_rate"]); ?></td>
                <td><?php echo skortanimgetir($rowa["calcium"]); ?></td>
                <td><?php echo skortanimgetir($rowa["potassium"]); ?></td>
                <td><?php echo skortanimgetir($rowa["glucose"]); ?></td>
                <td><?php echo skortanimgetir($rowa["hco3"]); ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm prismdelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#prismtable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".prismdelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=prismedelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);


                        $.get("ajax/skor_listeleri.php?islem=prism_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.prism_listesi').html(getVeri);
                        });

                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="snap2_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="snapstable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Ortalama Arter Basinci</th>
            <th>Minimum sicaklik</th>
            <th>P02 (mmHg ) / FIO2 (%)</th>
            <th>Plazma pH Asgari</th>
            <th>Coklu nobetler</th>
            <th>Diurez (ml / kg saat)</th>
            <th>SNAP-PE ||</th>
            <th>SNAP ||</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_snaps2 where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo skortanimgetir($rowa["mean_arterial_pressure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["minimum_temperature"]); ?></td>
                <td><?php echo skortanimgetir($rowa["p02"]); ?></td>
                <td><?php echo skortanimgetir($rowa["plasma_ph"]); ?></td>
                <td><?php echo skortanimgetir($rowa["multiple_seizures"]); ?></td>
                <td><?php echo skortanimgetir($rowa["diurez"]); ?></td>
                <td><?php echo $rowa["snap2_conclusion_detail"]; ?></td>
                <td><?php echo $rowa["snap2_conclusion"]; ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm snapsdelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#snapstable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".snapsdelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=snapsdelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);


                        $.get("ajax/skor_listeleri.php?islem=snap2_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.snapii_listesi').html(getVeri);
                        });

                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="crib2_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="cribtable">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Cinsiyet</th>
            <th>Gebelik (haftalık)</th>
            <th>Doğum ağırlığı (g)</th>
            <th>Ilk Ateş(°C)</th>
            <th>Baz fazlalığı (mmol/L)</th>
            <th>CRIB II</th>
            <th>Tahmini Ölüm Oranı</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_crib where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo $rowa["gender"]; ?></td>
                <td><?php echo skortanimgetir($rowa["pregnancy"]); ?></td>
                <td><?php echo $rowa["birth_weight"]; ?></td>
                <td><?php echo skortanimgetir($rowa["first_fire"]); ?></td>
                <td><?php echo skortanimgetir($rowa["base_excess"]); ?></td>
                <td><?php echo $rowa["crib_conclusion"]; ?></td>
                <td><?php echo $rowa["crib_conclusion_detail"]; ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm cribdelete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#cribtable').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".cribdelete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=cribdelete',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/skor_listeleri.php?islem=crib2_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.crib_listesi').html(getVeri);

                        });
                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }

else if($islem=="euroscore2_listesi"){
    $protokolgetirme=$_GET['protokolno'];
//    $hastakayit = singular("patient_registration", "id", $protokolgetirme);  ?>

    <table class="table table-bordered border-primary mb-0 w-100" id="euroscore2table">
        <thead>
        <tr>
            <th>Eklendiği Tarih</th>
            <th>Yaş</th>
            <th>Cinsiyet</th>
            <th>Böbrek yetmezliği</th>
            <th>Ekstrakardiyak arteriyopati</th>
            <th>Hareket zayıflığı</th>
            <th>Kalp ameliyatı öyküsü</th>
            <th>EuroSCORE II</th>
            <th>İşlemler</th>
        </tr>
        </thead>

        <tbody>
        <?php  $kullanicid=$_SESSION['id'];
//        $evrakupdate=yetkisorgula($kullanicid, "evrakupdate");
//        $evrakdelete=yetkisorgula($kullanicid, "evrakdelete");

        $hello = verilericoklucek("SELECT * FROM score_euroscore2 where protokol_number='$protokolgetirme' AND status='1'");
        foreach ((array) $hello as $rowa) { ?>
            <tr>
                <td><?php echo nettarih($rowa["insert_datetime"]); ?></td>
                <td><?php echo $rowa["age"]; ?></td>
                <td><?php echo skortanimgetir($rowa["gender"]); ?></td>
                <td><?php echo skortanimgetir($rowa["kidney_failure"]); ?></td>
                <td><?php echo skortanimgetir($rowa["extracardiac_arteriopathy"]); ?></td>
                <td><?php echo skortanimgetir($rowa["weakness_movement"]); ?></td>
                <td><?php echo skortanimgetir($rowa["heart_surgery_history"]); ?></td>
                <td><?php echo $rowa["conclusion_euroscore2"]; ?></td>
                <td>
                    <button type="button" class="btn sil-btn   btn-sm euroscore2delete" id="<?php echo $rowa["id"]; ?>" protokolno="<?php echo $rowa["protokol_number"]; ?>" >Sil</button>
                </td>

            </tr>
        <?php } ?>
        </tbody>

    </table>
    <script>
        $('#euroscore2table').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },


        });

        $(".euroscore2delete").click(function () {
            var id =$(this).attr('id');
            var protokolno=$(this).attr('protokolno');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function(){

                var delete_detail = $('#personel_delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=euroscore2cancel',
                    data: {id:id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/skor_listeleri.php?islem=euroscore2_listesi", {protokolno: protokolno}, function (getVeri) {
                            $('.euroscore2_listesi').html(getVeri);

                        });
                    }
                });

            }, function(){ alertify.warning('Silme işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Silme işlemini Onayla"});
        });
    </script>
<?php }








?>