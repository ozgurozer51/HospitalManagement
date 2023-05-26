<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
?>
<?php
if($islem=="listeyi-getir"){ ?>

    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>

    <div class="servis-tanim">

    <div class="card">
        
        <div class="card-body bg-white">
            <script>
                $('#servislertab').DataTable( {
                    "processing": true,
                    "responsive":true,
                    "scrollY": true,
                    "autoWidth": false,
                    "scrollX": true,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Servis Ekle',
                            className: 'btn btn-success btn-sm btn-kaydet',
                            attr:  {
                                'data-bs-toggle':"modal",
                                'data-bs-target':"#modal-getir-fullscreen",
                            },
                            action: function ( e, dt, button, config ) {
                                var secilen=$(".btntanimla-dom").attr('id');
                                $.get( "ajax/tanimlar/"+secilen+".php?islem=modal-icerik",function(get){
                                    $('#modal-tanim-icerik-fullscreen').html(get);
                                });
                            }

                        }
                    ],
                } );
            </script>
            <table id="servislertab" class="table table-striped table-bordered w-100 display nawrap" >
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Servis Adı</th>
                    <th>Servis Sıra No</th>
                    <th>Servis Durum</th>
                    <th>İşlem</th>

                </tr>
                </thead>
                <tbody>

                <?php $sql = verilericoklucek('select * from units where unit_type="1"');
                foreach ($sql as $row){ ?>
                    <tr>
                        <td class='bolumbilgisigetir' id="<?php echo $row["id"]; ?>" data-bs-target="#bolumdetaygetir" data-bs-toggle="modal" ><?php echo $row["id"]; ?></td>

                        <td<?php if ($row['status']){ ?> class='bolumbilgisigetir'  id="<?php echo $row["id"]; ?>" data-bs-target="#bolumdetaygetir" data-bs-toggle="modal"<?php } ?>> <?php if($row["status"]==0){?><del><?php } ?> <?php echo $row["department_name"]; ?></td>

                        <td  <?php if ($row['status']){ ?> class='bolumbilgisigetir' id="<?php echo $row["id"]; ?>" data-bs-target="#bolumdetaygetir" data-bs-toggle="modal"<?php } ?>><?php if($row["status"]==0){?><del><?php } ?>  <?php echo $row["department_order_no"]; ?></td>

                        <td align="center" id="<?php echo $row["id"]; ?>" data-bs-target="#bolumdetaygetir" title="Durum" data-bs-toggle="modal" class='bolumbilgisigetir'><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>



                        <td align="center">
                            <i class="fa fa-pen-to-square servis_duzenle_modal" title="Düzenle"
                                <?php if ($row['status']){ ?> id="<?php echo $row["id"]; ?>"   data-bs-target="#modal-getir-fullscreen" data-bs-toggle="modal"  <?php } ?>
                               alt="icon"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle servisaktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash servis_sil_open_pop" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    </div>


    <div class="modal fade" id="modal-getir-fullscreen"  aria-hidden="true">
        <div class="modal-dialog" style=" width: 98%; max-width: 98%; " id='modal-tanim-icerik-fullscreen'> </div>
    </div>

    <script>
        $(document).ready(function() {
            $("body").off("click", ".servis_duzenle_modal").on("click", ".servis_duzenle_modal", function(e){
                var bolumid = $(this).attr('id');
                $.get( "ajax/tanimlar/servistanimla.php?islem=modal-icerik", { birimbilgisi:bolumid },function(getveri){
                    $('#modal-tanim-icerik-fullscreen').html(getveri);
                });
            });

            $("body").off("click",".servisaktif").on("click",".servisaktif", function (e) {
                var getir = $(this).attr('id');

                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/servistanimla.php?islem=servis-aktiflestir',
                    data:{getir},
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('.servis-tanim:first').load("ajax/tanimlar/servistanimla.php?islem=listeyi-getir");
                    }
                });
            });

        } );

        $("body").off("click", ".servis_sil_open_pop").on("click", ".servis_sil_open_pop", function(e){
            var id = $(this).attr('id');

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Poliklinik Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/polikliniktanimla.php?islem=polikliniksil',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        console.log(e);
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/polikliniktanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.servis-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Poliklinik Silme İşlemini Onayla"});
        });
    </script>

<?php } else if($islem=="modal-icerik"){ ?>
    <style>
        .form-label{
            color: black !important;
        }
    </style>
    <div class="modal-dialog" style=" width: 98%; max-width: 98%; ">
        <!-- modal content-->
        <form class="modal-content" id="form_birim" action="javascript:void(0);">
            <div class="modal-header text-white" >
                <?php if ($_GET["birimbilgisi"] != "") {
                    echo '<h4 class="modal-title">Servis Düzenle</h4>';
                    $birimbilgisi = singular("units", "id", $_GET["birimbilgisi"]);

                    extract($birimbilgisi);
                } else { echo '<h4 class="modal-title">Servis Ekle</h4>';} ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <input placeholder=" " type="hidden"  class="form-control" name="unit_type" value="0">
                <?php if ($_GET["birimbilgisi"]){ ?>
                    <input placeholder=" " type="hidden" class="form-control" name="units_id" value="<?php echo $_GET["birimbilgisi"]; ?>">
                <?php } ?>

                <div class="row">

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="status">Durumu::</label>
                            </div>
                            <div class="col-7">
                                <select name="status" id="status" class="form-select">
                                    <option <?php if ($status == 1) {echo "selected";} ?> value="1">Aktif</option>
                                    <option <?php if ($status == 0) {echo "selected";} ?> value="0">Pasif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="department_name">Birim Adı::</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" id="department_name" name="department_name" value="<?php echo $department_name; ?>" placeholder="Birim Adı">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="department_order_no">Birim Sıra No::</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="number" class="form-control" id="department_order_no" name="department_order_no" value="<?php echo $department_order_no; ?>">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="p_strt_normal_inspection">Normal Muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="p_strt_normal_inspection" id="p_strt_normal_inspection" class="form-control">
                                    <option class="bg-danger text-white" disabled>Hesap açılırken aktarılacak i̇şlem</option>
                                    <?php $bolumgetir = "SELECT transaction_detail.id AS islemdetayid ,transaction_detail.transaction_name AS islemadi,
       transaction_detail.*,transaction_definitions.* FROM transaction_definitions,transaction_detail WHERE 
        transaction_definitions.definition_type='ISLEM_GRUBU' AND transaction_definitions.id=transaction_detail.transaction_id AND transaction_definitions.status='1'";
                                    $bilgigetir = verilericoklucek($bolumgetir);
                                    foreach ($bilgigetir as $rowa) { ?>
                                        <option  <?php if ($p_strt_normal_inspection == $rowa["islemdetayid"]) {echo "selected";} ?>value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option><?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="off_work_inspection">Mesai Dışı Muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="off_work_inspection" id="off_work_inspection" class="form-control">
                                    <option class="text-white bg-danger" disabled>Mesai dışı aktarılacak i̇şlem</option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option  <?php if ($off_work_inspection == $rowa["islemdetayid"]) {echo "selected";} ?>value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option><?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="middle_child">Orta Çocuk Muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="middle_child" id="middle_child" class="form-control">
                                    <option class="text-white bg-danger" disabled>Orta çocuk aktarılacak i̇şlem</option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option <?php if ($middle_child == $rowa["islemdetayid"]) {
                                            echo "selected";
                                        } ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="great_child">Büyük çocuk muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="great_child" id="great_child" class="form-control">
                                    <option class="text-white bg-danger" disabled>büyük çocuk aktarılacak i̇şlem
                                    </option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option <?php if ($great_child == $rowa["islemdetayid"]) {echo "selected";} ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="over_age_of_sixtyfive">65 Yaş Üstü Muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="over_age_of_sixtyfive" id="over_age_of_sixtyfive" class="form-control" >
                                    <option class="text-white bg-danger" disabled>65 Yaş üstü aktarılacak i̇şlem</option>
                                    <?php foreach ($bilgigetir as $rowa) {?>
                                        <option  <?php if ($over_age_of_sixtyfive == $rowa["islemdetayid"]) {echo "selected"; } ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="top_teen_inspection">İlk 10 Muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="top_teen_inspection"    class="form-control" >
                                    <option class="text-white bg-danger" disabled>İlk 10 muayene aktarılacak i̇şlem</option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option  <?php if ($top_teen_inspection == $rowa["islemdetayid"]) {echo "selected"; } ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="health_board_auto_inspection">Sağlık kurulu otomatik
                                    muayene:</label>
                            </div>
                            <div class="col-7">
                                <select name="health_board_auto_inspection" class="form-control">
                                    <option class="text-white bg-danger" disabled>Sağlık kurulu aktarılacak i̇şlem
                                    </option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option <?php if ($health_board_auto_inspection == $rowa["islemdetayid"]) {echo "selected";} ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="examination_code" >Tetkik Kodu:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" name="examination_code" value="<?php echo $examination_code; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="bed_list_transferred_auto" >O. Aktarılacak Yatak İşlemi:</label>
                            </div>
                            <div class="col-7">
                                <select name="bed_list_transferred_auto" id="bed_list_transferred_auto"   class="form-control" >
                                    <option class="text-white bg-danger" selected disabled>Aktarılacak yatak i̇şlemi</option>
                                    <?php foreach ($bilgigetir as $rowa) {?>
                                        <option  <?php if ($bed_list_transferred_auto == $rowa["islemdetayid"]) {echo "selected"; } ?> value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="row mt-1">

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="intens_care_bed_operat_trans_auto">O. Aktarılacak Y.Bakım Yatak İşlemi:</label>
                            </div>
                            <div class="col-7">
                                <select name="intens_care_bed_operat_trans_auto" id="intens_care_bed_operat_trans_auto"
                                        class="form-control">
                                    <option class="text-white bg-danger" selected disabled>Yoğum bakım yatak i̇şlemi
                                    </option>
                                    <?php foreach ($bilgigetir as $rowa) { ?>
                                        <option  <?php if ($intens_care_bed_operat_trans_auto == $rowa["islemdetayid"]) {echo "selected";} ?>value="<?php echo $rowa["islemdetayid"]; ?>"><?php echo $rowa["islemadi"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="iso_9002_prosess_no">ISO 9002 Prosess No:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" name="iso_9002_prosess_no" id="iso_9002_prosess_no" value="<?php echo $iso_9002_prosess_no; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="iso_performance">ISO Performans:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" name="iso_performance" id="iso_performance" value="<?php echo $iso_performance; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="staff_y">Kadro Y.:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" name="staff_y" id="staff_y" value="<?php echo $staff_y; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="available_y">Mevcut Y.:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="text" class="form-control" name="available_y" id="available_y" value="<?php echo $available_y; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label class="col-form-label" for="appointment_number">Randevu Sayısı:</label>
                            </div>
                            <div class="col-7">
                                <input placeholder=" " type="number" class="form-control" name="appointment_number" id="appointment_number" value="<?php echo $appointment_number; ?>">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-5"
                            <label class="col-form-label" for="morning_appointment_number">Sabah Randevu Sayısı:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="number" class="form-control" name="morning_appointment_number" id="morning_appointment_number" value="<?php echo $morning_appointment_number; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="plk_book">PLK Defteri:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="plk_book" id="plk_book" value="<?php echo $plk_book; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="plk_service">PLK Servis:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="plk_service" id="plk_service" value="<?php echo $plk_service; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="gss_clinic_code">GSS Klinik Kodu:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="gss_clinic_code" id="gss_clinic_code" value="<?php echo $gss_clinic_code; ?>">
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="panel">PANO:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="panel" id="panel" value="<?php echo $panel; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="co">CO:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="co" id="co" value="<?php echo $co; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="sms_department">SMS'deki Bölüm :</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="sms_department" id="sms_department" value="<?php echo $sms_department; ?>">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="tig_department_code">TIG Bölüm Kodu:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="number" class="form-control" name="tig_department_code" id="tig_department_code" value="<?php echo $tig_department_code; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="department_type">Bölüm Tipi:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="department_type" id="department_type" value="<?php echo $department_type; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="department_sub_type">Bölüm Alt Tipi:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="department_sub_type" id="department_sub_type" value="<?php echo $department_sub_type; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="main_department">Ana Bölüm:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="main_department" id="main_department" value="<?php echo $main_department; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="online_appointment">Online Randevu:</label>
                        </div>
                        <div class="col-7">
                            <select name="online_appointment" id="online_appointment" class="form-control">
                                <option <?php if ($online_appointment == 0) {echo "selected";} ?> value="0">Pasif</option>
                                <option <?php if ($online_appointment == 1) {echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="tc_auto_non_discount">TC'den Oto İndirimi:</label>
                        </div>
                        <div class="col-7">
                            <select name="tc_auto_non_discount" id="tc_auto_non_discount" class="form-control">
                                <option <?php if ($tc_auto_non_discount == 0) {echo "selected"; } ?> value="0">Pasif</option>
                                <option <?php if ($tc_auto_non_discount == 1) { echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="deed_daily_status">SNET Günlük:</label>
                        </div>
                        <div class="col-7">
                            <select name="deed_daily_status" id="deed_daily_status" class="form-control">
                                <option <?php if ($deed_daily_status == 0) {echo "selected";} ?> value="0">Pasif</option>
                                <option <?php if ($deed_daily_status == 1) {echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="sgk_difference_ceiling">SGK Fark Tavan:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" id="sgk_difference_ceiling" name="sgk_difference_ceiling" value="<?php echo $sgk_difference_ceiling; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="sgk_difference_ceiling_two">SGK Fark Tavan İki:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="sgk_difference_ceiling_two" id="sgk_difference_ceiling_two" value="<?php echo $sgk_difference_ceiling_two; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="epicrisis_appear">Epikriz Görünsün:</label>
                        </div>
                        <div class="col-7">
                            <select name="epicrisis_appear" id="epicrisis_appear" class="form-control"><option <?php if ($epicrisis_appear == 0) {echo "selected";} ?> value="0">Görünmesin</option>
                                <option <?php if ($epicrisis_appear == 1) {echo "selected";} ?> value="1">Görünsün</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="protocol_appear">Protokol Görünsün:</label>
                        </div>
                        <div class="col-7">
                            <select name="protocol_appear" id="protocol_appear" class="form-control">
                                <option <?php if ($protocol_appear == 0) {echo "selected";} ?> value="0">Görünmesin</option>
                                <option <?php if ($protocol_appear == 1) {echo "selected";} ?> value="1">Görünsün</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="lying_oto_archive">Yatan Oto Arşiv :</label>
                        </div>
                        <div class="col-7">
                            <select name="lying_oto_archive" id="lying_oto_archive" class="form-control">
                                <option <?php if ($lying_oto_archive == 0) {echo "selected";} ?> value="0">Pasif</option>
                                <option <?php if ($lying_oto_archive == 1) {echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="outpatient_difference">Ayaktanda Fark :</label>
                        </div>
                        <div class="col-7">
                            <select name="outpatient_difference" id="outpatient_difference" class="form-control">
                                <option <?php if ($outpatient_difference == 0) {echo "selected";} ?> value="0">Alınmasın</option>
                                <option <?php if ($outpatient_difference == 1) {echo "selected";} ?> value="1">Alınsın</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="inpatient_difference">Yatanda Fark :</label>
                        </div>
                        <div class="col-7">
                            <select name="inpatient_difference" id="inpatient_difference" class="form-control">
                                <option <?php if ($inpatient_difference == 0) {echo "selected";} ?> value="0">Alınmasın</option>
                                <option <?php if ($inpatient_difference == 1) {echo "selected";} ?> value="1">Alınsın</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="inquire_at_mhrs">MHRS Sorgusu :</label>
                        </div>
                        <div class="col-7">
                            <select name="inquire_at_mhrs" id="inquire_at_mhrs" class="form-control">
                                <option <?php if ($inquire_at_mhrs == 0) {echo "selected";} ?> value="0">Sorgulanmasın</option><option <?php if ($inquire_at_mhrs == 1) {echo "selected";} ?> value="1">Sorgulansın</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="ster_barcod_control">Ster. Barkod Kontrol:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="ster_barcod_control" id="ster_barcod_control" value="<?php echo $ster_barcod_control; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="department_age" >Bölüm Yaşı :</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="number" class="form-control" name="department_age" id="department_age" value="<?php echo $department_age; ?>"  id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="account_close_alert">Hesap Kapatma Uyarısı:</label>
                        </div>
                        <div class="col-7">
                            <select name="account_close_alert" id="account_close_alert" class="form-control">
                                <option <?php if ($account_close_alert == 0) {echo "selected";} ?> value="0">Pasif</option>
                                <option <?php if ($account_close_alert == 1) {echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="ministry_of_health_clinic_code">Sağlık Bakanlığı Klinik Kodu:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="ministry_of_health_clinic_code" id="ministry_of_health_clinic_code" value="<?php echo $ministry_of_health_clinic_code; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="ministry_of_health_yb_clinic_code">Sağlık Bakanlığı Y.B. K. Kodu:</label>
                        </div>
                        <div class="col-7">
                            <input placeholder=" " type="text" class="form-control" name="ministry_of_health_yb_clinic_code" id="ministry_of_health_yb_clinic_code" value="<?php echo $ministry_of_health_yb_clinic_code; ?>" id="basicpill-firstname-input">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="sending_dr_compulsory">Gönderen DR. Zorunlu:</label>
                        </div>
                        <div class="col-7">
                            <select name="sending_dr_compulsory" id="sending_dr_compulsory" class="form-control">
                                <option <?php if ($sending_dr_compulsory == 0) {echo "selected";} ?> value="0">Pasif</option>
                                <option <?php if ($sending_dr_compulsory == 1) {echo "selected";} ?> value="1">Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="diagnosis_compulsory">Tanı:</label>
                        </div>
                        <div class="col-7">
                            <select name="diagnosis_compulsory" id="diagnosis_compulsory" class="form-control">
                                <option <?php if ($diagnosis_compulsory == 0) {echo "selected";} ?> value="0">Zorunlu değil</option>
                                <option <?php if ($diagnosis_compulsory == 1) {echo "selected";} ?> value="1">Zorunlu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="hospital_buildingid">Bina:</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select" id="bina" name="hospital_buildingid" id="hospital_buildingid">
                                <option class="text-white bg-danger" selected disabled>Bina Seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from hospital_building where status='1'");
                                foreach ($sql as $rowa) { ?>
                                    <option <?php if ($hospital_buildingid == $rowa["id"]) echo "selected"; ?>value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["building_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-1">

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="hospital_floorid">Kat:</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select" id="kat hospital_floorid" name="hospital_floorid">
                                <?php if ($hospital_buildingid) {
                                    $sql = verilericoklucek("select * from hospital_floor where status='1' and building_id=$hospital_buildingid");
                                    foreach ($sql as $rowa) { ?>
                                        <option <?php if ($hospital_floorid == $rowa["id"]) echo "selected"; ?>value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["floor_name"]; ?></option>
                                    <?php } } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="vein_trace_required">Damar İzi:</label>
                        </div>
                        <div class="col-7">
                            <select name="vein_trace_required" id="vein_trace_required" class="form-control">
                                <option <?php if ($vein_trace_required == 0) {echo "selected";} ?> value="0">Değil</option>
                                <option <?php if ($vein_trace_required == 1) {echo "selected";} ?> value="1">>Zorunlu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label class="col-form-label" for="show_on_appointment">Randevuda:</label>
                        </div>
                        <div class="col-7">
                            <select name="show_on_appointment" id="show_on_appointment" class="form-control" >
                                <option <?php if ($show_on_appointment == 0) {echo "selected";} ?> value="0">Görünmesin</option>
                                <option <?php if ($show_on_appointment == 1) {echo "selected";} ?> value="1">Görünsün</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            </div>

            <div class="modal-footer">
                <?php if ($_GET["birimbilgisi"] != '') { ?>
                    <input class="btn btn-success btn-sm w-md justify-content-end" id="birimguncelle" type="submit" data-bs-dismiss="modal"  value="Düzenle"/>
                    <?php } else { ?>
                    <input class="btn w-md justify-content-end btn-success btn-sm " id="birimkaydet" type="submit"  data-bs-dismiss="modal" value="Kaydet"/>
                <?php }
                ?>
                <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
            </div>
        </form>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#bina19").change(function () {
                    var binano = $(this).val();

                    $.ajax({
                        type: "post",
                        url: "ajax/tanimlar/servistanimla.php?islem=binaidgetir",
                        data: {"binano": binano},
                        success: function (e) {
                            $("#kat19").html(e);
                        }
                    })
                })
            });

            $("body").off("click", "#birimkaydet").on("click", "#birimkaydet", function(e){
                    var gonderilenform = $("#form_birim").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/servistanimla.php?islem=servisekle',
                    data: gonderilenform,
                    success: function (e) {
                        console.log(e);
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/servistanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.servis-tanim:first').html(e);
                        });
                    }
                });
            });

            $("body").off("click", "#birimguncelle").on("click", "#birimguncelle", function(e){
                var gonderilenform = $("#form_birim").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/servistanimla.php?islem=servisguncelle',
                    data: gonderilenform,
                    success: function (e) {
                        console.log(e);
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/servistanimla.php?islem=listeyi-getir",{}, function (e) {
                            $('.servis-tanim:first').html(e);
                        });
                    }
                });
            });
        </script>

    </div>
    </div>

<?php }  else if($_GET["islem"]=="servisekle") {
    $_POST['insert_datetime'] = $simdikitarih;
    $_POST['insert_userid'] = $KULLANICI_ID;
    $birimtanimla = direktekle("units", $_POST);
    var_dump($birimtanimla);
    if ($birimtanimla == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Servis Ekleme Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Servis Ekleme Başarısız');
        </script>
    <?php } }
else if($_GET["islem"]=="servisguncelle") {
    $_POST['update_datetime'] = $simdikitarih;
    $_POST['update_userid'] = $KULLANICI_ID;
    $id=$_POST['units_id'];
    unset($_POST['units_id']);
    $birimguncelle = direktguncelle("units","id",$id,$_POST );
    var_dump($birimguncelle);
    if ($birimguncelle == 1) { ?>
        <script>
            alertify.success('Servis Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('Servis Güncelleme Başarısız');
        </script>
    <?php }
}
else if ($islem=="servissil"){
    $id=$_POST['id'];
    $detay=$_POST['delete_detail'];
    $silme=$KULLANICI_ID;
    $tarih=$_POST['delete_datetime'];
    $sql=canceldetail("units","id",$id,$detay,$silme,$tarih);
    if ($sql == 1) {?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Servis Silme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Servis Silme Başarısız');
        </script>
    <?php }
}
elseif($islem=="binaidgetir"){
    $binano=$_POST['binano'];
    echo " <option value=''>kat seçiniz..</option>";
    $bolumgetir = "select * from hospital_floor where building_id=$binano and status='1' order by floor_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="'.$value['id'].'">'.$value['floor_name'].'</option>';

    }
}

elseif($islem=="katget"){
    $katid=$_POST['katid']; ?>
    <option value=''>oda seçiniz..</option>
    <?php $bolumgetir = "select * from hospital_room where floor_id=$katid and status='1' and availability='0' order by room_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) { ?>
        <option value="<?php echo $value['id']?>"  ><?php echo $value['room_name']; ?></option>
    <?php   }
}elseif ($islem=="servis-aktiflestir"){
    //var_dump($_POST['getir']);
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('units','id',$id,$date,$user);
    var_dump($sql);
    if ($sql == 1) { ?>
        <script>
            alertify.success('Aktifleştirme Başarılı');
        </script>

    <?php } else { ?>
        <script>
            alertify.error('Işlem Başarısız');
        </script>
    <?php }


}
?>
