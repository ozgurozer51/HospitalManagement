<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

$now_datetime_start = date('Y-m-d') . " 00:00:00";
$now_datetime_end = date('Y-m-d') . " 23:59:59";

$yetkilioldugupoliklinikler = birimyetkiselect($_SESSION['id']);

if($_POST['muayene_start_date'] && $_POST['muayene_end_date'] ){

    $sql_sorgu = "SELECT patient_registration.*,
       patient_registration.status as status,
       patients.*,
       units.*,
       users.*,
       branch.*,
       patient_registration.id     AS hastakayitid,
       patient_registration.protocol_number as protokol_no,   
       patients.tc_id as kimlik_no,
       users.id as doktor_id
FROM patient_registration
         inner JOIN patients ON patient_registration.tc_id = patients.tc_id
         inner JOIN users ON users.id = patient_registration.doctor
         inner JOIN units ON units.id = patient_registration.outpatient_id
         inner join branch on branch.branch_code = users.dr_bras_code
                                $yetkilioldugupoliklinikler  AND
                              (patient_registration.insert_datetime between '$_POST[muayene_start_date]' AND '$_POST[muayene_end_date]')
              AND patient_registration.examination_start_time IS NULL AND patient_registration.status='1' ";
}else {
    $sql_sorgu = "SELECT patient_registration.*,
       patient_registration.status as status,
       patients.*,
       units.*,
       users.*,
       branch.*,
       patient_registration.id     AS hastakayitid,
       patient_registration.protocol_number as protokol_no,
       patients.tc_id as kimlik_no,
       users.id as doktor_id
FROM patient_registration
         inner JOIN patients ON patient_registration.tc_id = patients.tc_id
         inner JOIN users ON users.id = patient_registration.doctor
         inner JOIN units ON units.id = patient_registration.outpatient_id 
         inner join branch on branch.branch_code = users.dr_bras_code                
                             $yetkilioldugupoliklinikler AND
                             patient_registration.insert_datetime >= '$now_datetime_start' AND
                             patient_registration.examination_start_time IS NULL AND patient_registration.status='1'";
} ?>


<input type="text" class="form-control w-100 mt-1" id="muayene-olacak-hasta-ara" placeholder="Ara">

    <table id="muayene-olacaklar" class="table table-bordered nowrap table-sm display w-100 bg-white" style="font-size: 13px !important;">
        <thead>
            <th scope="col">Sıra</th>
            <th scope="col">Hasta</th>
            <th scope="col">Tc</th>
            <th scope="col">Durumu</th>
        </thead>
        <tbody>
        <?php $sql=verilericoklucek($sql_sorgu);

           foreach ($sql as $str) {

            $sql2 = tek("select * from patient_record_diagnoses where protocol_number=$str[protokol_no]  and status=1 fetch first 1 rows only ")
            ?>

            <tr class="hasta-sec btntanimla easyui-linkbutt0on" id="hastadetay/hastakabuldetay" poliklinik-id="<?php echo $str["outpatient_id"]; ?>"  hasta-id="<?php echo $str['hastakayitid']; ?>" tc-kimlik="<?php echo $str['tc_id']; ?>" protokol-no="<?php echo $str['protokol_no']; ?>" doktor-id="<?php echo $str['doktor_id']; ?>" doktor-adi="<?php echo $str['name_surname']; ?>" kayit-id="<?php echo $str['hastakayitid']; ?>"  brans="<?php echo $str['branch_name']; ?>" cagirma-sayisi="<?php echo $sonuc; ?>" deger="<?php echo $str["hastakayitid"]; ?>" id-2="<?php echo $str['hastakayitid']; ?>" birim="<?php echo $str['department_name']; ?>" adi="<?php echo $str["patient_name"] . " " . $str["patient_surname"]; ?>">
                <td> <?php echo $str["row_number"]; ?></td>
                <td class="hastadetay"><?php if ($str["baby"]) {echo $str["birth_order"] . ". BEBEK  ";echo $str["patient_surname"];} else { ?><?php echo $str["patient_name"] . " " . $str["patient_surname"];} ?></td>
                <td><?php echo $str['kimlik_no']; ?></td>
                <td><?php if($sql2['id']){ echo "Kabul Edilmiş";  }else{ echo "Beklemede"; } ?></td>
            </tr>

        <?php } ?>

        </tbody>
    </table>

    <div class="modal fade" id="cagrikaydinigetir"  aria-hidden="true">
        <div class="modal-dialog  modal-lg" id="cagridetayicerik">
        </div>
    </div>

<div class="modal fade modal-xl" id="tani-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-body" id="tani-modal-icerik"></div>
    </div>
</div>

<div class="modal fade modal-lg" id="modal-tani" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="tani-icerik"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
       var table = $('#muayene-olacaklar').DataTable({
             "pageLenght":false,
             "paging":false,
             "info":false,
            "scrollX": true,
            "visible":false,
            "colReorder":true,
            "scrollY": '50vh',
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"
            },
        });

        $('#muayene-olacak-hasta-ara').on( 'keyup', function () {
            table.search( this.value ).draw();
        });

        $("#muayene-start-date").change(function () {
            if ($("#muayene-end-date").val()) {
                var muayene_end_date = $("#muayene-end-date").val();
                var muayene_start_date = $("#muayene-start-date").val();
                $.ajax({
                    type: 'POST',
                    url: "ajax/muayenelistesi.php",
                    data: {muayene_start_date, muayene_end_date},
                    success: function (e) {
                        $('#bekleyenhastalistesigetir:first').html(e);
                    }
                });
            }
        });

        $( "#muayene-end-date" ).change(function() {
            if ($("#muayene-start-date").val()) {
                var muayene_end_date = $("#muayene-end-date").val();
                var muayene_start_date = $("#muayene-start-date").val();
                $.ajax({
                    type: 'POST',
                    url: "ajax/muayenelistesi.php",
                    data: {muayene_start_date, muayene_end_date},
                    success: function (e) {
                        $('#bekleyenhastalistesigetir:first').html(e);
                    }
                });
            }
        });

            $("body").off("click", "#muayene-iptal").on("click", "#muayene-iptal", function (e) {
                var select_status = $('.bg-yesil').length;
                if (select_status==0){
                    alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
                }else {
                    alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='silme_detay' placeholder='Silme Nedeni Giriniz'>", function () {
                        var hasta_kayit_id = $('.bg-yesil').attr("kayit-id");
                        var silme_detay = $('#silme_detay').val();

                        $.ajax({
                            type: 'POST',
                            url: "ajax/hastaislemleri/hasta-islemleri-sql.php?islem=muayene-bekleyen-hasta-iptali",
                            data: {hasta_kayit_id,silme_detay},
                            success: function (e) {
                                $.get( "ajax/muayenelistesi.php", { },function(getveri){
                                    $('#bekleyenhastalistesigetir').html(getveri);
                                });
                            }
                        });
                        $('.alertify').remove();

                    }, function () {
                        alertify.warning('Silme İşleminden Vazgeçtiniz')
                    }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme İşlemini Onayla"});
                }
        });

        $("body").off("click", ".cagirmalistesinigetir").on("click", ".cagirmalistesinigetir", function (e) {
            var tc_kimlik = $(this).attr('tc_kimlik');
            var poliklinikid = $(this).attr('poliklinikid');

            $.get("ajax/cagrikaydidetaylari.php", { tc_kimlik: tc_kimlik, poliklinikid: poliklinikid }, function (getVeri) {
                $('#cagridetayicerik').html(getVeri);
            });
        });

        $("body").off("click", ".disekrancagir").on("click", ".disekrancagir", function (e) {

            var tcno = $(".bg-yesil").attr('tc-kimlik');
            var poliklinikid = $(".bg-yesil").attr('poliklinik-id');
            var protokolno = $(".bg-yesil").attr('protokol-no');
            var hastaninidsi = $(".bg-yesil").attr('hasta-id');

            if (tcno && protokolno) {

            $.get("siramatik/sorgula.php?islem=hastayicagir", {
                tcno: tcno,
                protokolno: protokolno,
                poliklinikid: poliklinikid
            }, function (e) {

                alertify.success("Hasta dış ekrandan çağrıldı");
                var number = e.trim();
                Number(number);
                $('#cagrilma-sayisi').html(number);
                $('.bg-yesil').attr("cagirma-sayisi", number);
            });
        }else {
                alertify.alert('Uyarı', 'İşlem Yapmak İçin Önce Hasta Seçimi Yapmalısınız!');
            }

        });

    });
</script>

<script type="text/javascript" src="assets/easyui/jquery.easyui.min.js"></script>