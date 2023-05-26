<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
?>


<style>
    body {
        overflow-x: hidden !important; /* Hide horizontal scrollbar */
        overflow-y: hidden !important;
    }

    .scroll-style {
        height: 100vh;
        overflow-y: scroll; /* Add the ability to scroll */
    }

    .scroll-style::-webkit-scrollbar {
        display: none;
    }

    .scroll-style {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    #sol-scroll-style {
        height: 100vh;
        overflow-y: scroll; /* Add the ability to scroll */
    }

    #sol-scroll-style::-webkit-scrollbar {
        display: none;
    }

    #sol-scroll-style {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }


</style>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <link href='assets/fullcalendar5/lib/main.css' rel='stylesheet'/>
    <script src='assets/fullcalendar5/lib/main.js'></script>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
</head>
<body>

<!--Yeni Randevu Talebinde Bulun--------------------------------------------------------------------------------------->
<div class="modal fade" id="yeni_randevu" data-bs-backdrop="static" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="width: 80% !important; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header py-3 px-4 border-bottom-0">
                <h5 class="modal-title" id="modal-title">Yatan Hasta Listesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body p-4">

                <div id="hasta_listesi"></div>

                <div class="row mt-2">
                    <div class="d-flex justify-content-end">
                        <button type="button" data-bs-dismiss="modal"  class="btn btn-outline-danger me-1" data-bs-dismiss="modal">Kapat</button>
                        <button type="button" data-bs-dismiss="modal" id="hasta_secimi_onayla" class="btn btn-success">Seçimi Onayla</button>
                    </div>
                </div>

            </div>
        </div> <!-- end modal-content-->
    </div> <!-- end modal dialog-->
</div>

<!--Randevu Talebini Güncelle------------------------------------------------------------------------------------------>
<div class="modal fade" id="randevu_guncelle" data-bs-backdrop="static" role="dialog" >
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header py-3 px-4 border-bottom-0">
                <h5 class="modal-title" id="modal-title"><span id="GET_NAME"></span> - Kişisinin Randevu Talebini Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body p-4">
                <form class="needs-validation" name="event-form" id="form-event" novalidate>
                    <div class="row">

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Birim Seçiniz</label>
                                <select class="form-select" id="DEPARTMENT_ID_UPDATE" >
                                    <option class="bg-danger text-white" selected>Seçiniz</option>
                                    <?php $AMELIYAT_BIRIMLERI = verilericoklucek("SELECT
                                    operating_rooms.*,
                                    operating_rooms.id as operating_rooms_id,  
                                    units.id,
                                    units.department_name
                                    FROM operating_rooms 
                                    left join units on units.id=operating_rooms.unit_id WHERE operating_rooms.status='1'");
                                    foreach ($AMELIYAT_BIRIMLERI AS $item) { ?>
                                        <option value="<?php echo $item['operating_rooms_id']; ?>"><?php echo $item['department_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Randevu Tarihi Seçiniz</label>
                                <input type="datetime-local" id="APPOINTMENT_DATE_UPDATE" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <div class="mt-2">
                        <div class="d-flex justify-content-end">
                            <input  type="hidden" id="APPOINTMENT_ID">
                            <input type="hidden" id="UPDATE_APPOINTMENT_DEPARTMENT_RAM"/>
                            <button type="button" class="btn btn-outline-danger" id="APPOINTMENT_DELETE">Bu Randevu Talebini Sil</button>
                            <button type="button" data-bs-dismiss="modal" id="update-modal-close" class="btn btn-outline-danger mx-2">Kapat</button>
                            <button type="button" data-bs-dismiss="modal" class="btn btn-success" id="randevu_guncelle_clk">Güncelle</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-3 col-xs-3 col-sm-3 mt-2" id="sol-scroll-style">

        <div class="card">
            <div class="card-header bg-primary text-white p-1">İşlem</div>
            <div class="card-body">
                <button type="button" class="btn btn-success mt-2" id="yeni_randevu_modal" data-bs-toggle="modal" data-bs-target="#yeni_randevu" ><i class="fas fa-plus"></i> Yatan Hasta Listesi</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white p-1">Birime Kayıtlı Randevuları Görüntüle</div>
            <div class="card-body">

                <label class="form-label" for="AMELIYAT_BIRIMI">Ameliyat Birimi Seçiniz</label>
                <select class="form-select" id="AMELIYAT_BIRIMI">
                    <option class="bg-danger text-white" selected disabled>Seçinz</option>
                    <?php $AMELIYAT_BIRIMLERI = verilericoklucek("SELECT
                         operating_rooms.*,
                         operating_rooms.id as operating_rooms_id,  
                         units.id,
                         units.department_name
                         FROM operating_rooms 
                         left join units on units.id=operating_rooms.unit_id WHERE operating_rooms.status='1'");
                    foreach ($AMELIYAT_BIRIMLERI AS $item) { ?>
                        <option value="<?php echo $item['operating_rooms_id']; ?>"><?php echo $item['department_name']; ?></option>
                    <?php } ?>
                </select>

            </div>
        </div>

        <div class="card" id="hasta_bilgileri_form">
            <div class="card-header bg-info text-white p-2">Hasta Bilgileri</div>
            <div class="card-body">

                <div class="form-group">
                    <div class="form-label">Hasta Tc No:</div>
                    <input class="form-control" type="text" id="PATIENT_TC" disabled>
                </div>

                <div class="form-group">
                    <div class="form-label">Hasta Protokol No:</div>
                    <input class="form-control" type="text" id="PATIENT_PROTOCOL_NO" disabled>
                </div>

                <div class="form-group">
                    <div class="form-label">Hasta Adı:</div>
                    <input class="form-control" type="text" id="PATIENT_NAME" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Randevu Tarihi Seçinz</label>
                    <input type="datetime-local" id="APPOINTMENT_DATE" class="form-control" required>
                </div>

                <div class="form-label">
                    <label class="form-label">Birim Seçiniz</label>
                    <select class="form-select change_dom" id="DEPARTMENT_ID" required>
                        <option class="bg-danger text-white" value="" selected disabled>Seçiniz</option>
                        <?php $sql = verilericoklucek("SELECT
                              operating_rooms.*,
                              operating_rooms.id as operating_rooms_id,  
                              units.id,
                              units.department_name
                              FROM operating_rooms 
                              left join units on units.id=operating_rooms.unit_id WHERE operating_rooms.status='1'");

                        foreach ($sql AS $item) { ?>
                            <option value="<?php echo $item['operating_rooms_id']; ?>"><?php echo $item['department_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end"><button type="button" class="btn btn-info mt-2" id="randevu_kayit" disabled><i class="fa fa-calendar"></i>  Randevu Oluştur</button></div>

            </div>
        </div>

    </div>


    <div class="col-md-9 col-xs-9 col-sm-9 bg-white mt-2 scroll-style" id="calendar"></div>

</div>


</body>
</html>

<div id="sonuc_goster"></div>
<div id="celandar-response-print"></div>


<script>

    $('#appointment_delete_alert').hide();
    $('#hasta_bilgileri_form').hide();

    $.get("ajax/ameliyathane/celander-response.php?", {department_id:0  }, function (e) {
        $('#celandar-response-print').html(e);
    });

    //Randevu Oluştur Button Dom--------------------------------------------------------------------------------------------
    $(".change_dom").change(function () {
        if(($("#APPOINTMENT_DATE").val()) != ''){
            $('#randevu_kayit').prop('disabled' , false);
        }
    });

    //Randevu Oluştur Button Dom--------------------------------------------------------------------------------------------
    $('#APPOINTMENT_DATE').change(function(){
        if(($(".change_dom").val()) != null){
            $('#randevu_kayit').prop('disabled' , false);
        }
    });

    //Randevu Güncelle Button Dom-------------------------------------------------------------------------------------------
    $("#DEPARTMENT_ID_UPDATE").change(function () {
        if(($("#APPOINTMENT_DATE_UPDATE").val()) != ''){
            $('#randevu_guncelle_clk').prop('disabled' , false);
        }
    });

    //Randevu Güncelle Button Dom-------------------------------------------------------------------------------------------
    $('#APPOINTMENT_DATE_UPDATE').change(function(){
        if(($("#DEPARTMENT_ID_UPDATE").val()) != 'Seçiniz'){
            $('#randevu_guncelle_clk').prop('disabled' , false);
        }
    });

    //Seçilen Hasta Bilgilerini Getir---------------------------------------------------------------------------------------
    $("#hasta_secimi_onayla").on("click", function () {
        $('#hasta_bilgileri_form').show();

        $('#PATIENT_PROTOCOL_NO').val($('.HASTA_SEC_AMELIYAT_KAYIT:checked').attr('protokol-id'));
        $('#PATIENT_TC').val($('.HASTA_SEC_AMELIYAT_KAYIT:checked').attr('hasta-tc'));
        $('#PATIENT_NAME').val($('.HASTA_SEC_AMELIYAT_KAYIT:checked').attr('hasta-adi'));

    });

    //Tüm Hasta Listesini Görüntlilemek İçin Çağır--------------------------------------------------------------------------
    $("#yeni_randevu_modal").on("click", function () {
        setTimeout(() => {
            $.get("ajax/ameliyathane-modul/ameliyathanelistesi.php?islem=ameliyat_hasta_listesi", {}, function (e) {
                $('#hasta_listesi').html(e);
            });
        }, "500")
    });

    //Yeni Randevu Kayıdı Başlat--------------------------------------------------------------------------------------------
    $("#randevu_kayit").on("click", function () {
        $('#randevu_kayit').prop('disabled' , "disabled");

        var department_id = $('#DEPARTMENT_ID').val();
        var  appointment_date = $('#APPOINTMENT_DATE').val();
        var patient_protocol_id = $(".HASTA_SEC_AMELIYAT_KAYIT:checked").attr('protokol-id');
        var patient_id = $(".HASTA_SEC_AMELIYAT_KAYIT:checked").attr('hasta-id');
        var patient_tc_id = $(".HASTA_SEC_AMELIYAT_KAYIT:checked").attr('hasta-tc');

        $.ajax({
            type: 'POST',
            url: 'ajax/ameliyathane-modul/ameliyathanefunction.php?islem=operation_appointment_add',
            data: {patient_id:patient_id , department_id:department_id,appointment_datetime:appointment_date , patient_protocol_id:patient_protocol_id , patient_tc_id:patient_tc_id },
            success: function (e) {
                $('#sonuc_goster').html(e);
                $.get("ajax/ameliyathane-modul/celander-response.php", {department_id: department_id}, function (e) {
                    $('#celandar-response-print').html(e);
                });
            }
        });

        $('#APPOINTMENT_DATE').val('');
        $('#DEPARTMENT_ID').val('');
        $('#hasta_bilgileri_form').hide();
    });

    //Birime Kayıtlı Ameliyat Randevuları Getir-----------------------------------------------------------------------------
    $("#AMELIYAT_BIRIMI").change(function () {
        var department_id = $(this).val();
        $.get("ajax/ameliyathane-modul/celander-response.php", {department_id: department_id}, function (e) {
            $('#celandar-response-print').html(e);
        });
    });

    $("#APPOINTMENT_DELETE").on("click", function () {

        alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_silme_detay' placeholder='Silme Nedeni Giriniz'>", function () {

            var reason_for_deletion = $('#personel_silme_detay').val();
            var patient_appointment_id = $('#APPOINTMENT_ID').val();
            var department_id = $('#UPDATE_APPOINTMENT_DEPARTMENT_RAM').val();

            $.ajax({
                type: 'POST',
                url: 'ajax/ameliyathane-modul/ameliyathanefunction.php?islem=appointment-delete',
                data: { delete_detail:reason_for_deletion,id:patient_appointment_id },
                success: function (e) {
                    $('#sonuc_goster').html(e);
                    $.get("ajax/ameliyathane-modul/celander-response.php", {department_id: department_id}, function (e) {
                        $('#celandar-response-print').html(e);
                        setTimeout(function() {
                            $("#update-modal-close").trigger("click");
                        }, 300);

                    });
                }
            });
            $('.alertify').remove();

        }, function () {
            alertify.warning('Silme İşleminden Vazgeçtiniz')
        }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme İşlemini Onayla"});

    });

    //Randevu Talebi Güncelle-----------------------------------------------------------------------------------------------
    $("#randevu_guncelle_clk").on("click", function () {
        var appointment_id = $('#APPOINTMENT_ID').val();
        var appointment_date = $('#APPOINTMENT_DATE_UPDATE').val();
        var department_id = $('#DEPARTMENT_ID_UPDATE').val();
        $.ajax({
            type: 'POST',
            url: 'ajax/ameliyathane-modul/ameliyathanefunction.php?islem=appointment_update',
            data: { id:appointment_id , appointment_datetime:appointment_date  , department_id:department_id },
            success: function (e) {
                $('#sonuc_goster').html(e);
                $.get("ajax/ameliyathane-modul/celander-response.php", {department_id: department_id}, function (e) {
                    $('#celandar-response-print').html(e);
                });
            }
        });



    });



</script>

<input type="hidden" id="randevu_guncelle_modal" data-bs-toggle="modal" data-bs-target="#randevu_guncelle" />
<input type="hidden" id="PAIENT_PROTOCOL_GET_ID">
