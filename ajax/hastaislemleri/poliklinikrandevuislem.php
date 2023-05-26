<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kullanicininidsi = $_SESSION["id"];
$hastabilgilerigetir = singular("patients", "tc_id", $_GET['tc_id']);
if ($_GET["islem"] == "anaekran") { ?>

<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>

<link href='assets/fullcalendar5/lib/main.css' rel='stylesheet'/>
    <script src='assets/fullcalendar5/lib/main.js'></script>

<div class="easyui-layout" fit="true" style="width:100%;height:100%;">

        <div data-options="region:'west',split:true" title="Kriter Paneli"  style="width:20%; height: 100%;">


            <select class="form-select" required name="birim_kodu" id="poliklinik" style="width: 100%;">
                <option value="">Poliklinik Seçiniz</option>
                <?php $yetkilioldugumbirimler = birimyetkiselect($kullanicininidsi);
                $bolumgetir = "select * from units where unit_type='0' $yetkilioldugumbirimler order by department_name asc ";
                $hello = verilericoklucek($bolumgetir);
                foreach ($hello as $rowa) { ?>
                    <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                <?php } ?>
            </select>

            <select class="form-select mt-1" required name="hekim_kodu" id="doktor" style="width: 100%;">
                <option value="">Üst seçimi yapın</option>
            </select>

            <div id="hasta_bilgileri_form">
                            <form id="gonderilenformcc">
                                <div class="row mt-1">
                                    <div class="col-md-4 col-xs-4 col-lg-4">
                                        <label for="example-text-input" class="col-form-label">TC NO:</label>
                                    </div>
                                    <div class="col-md-8 col-xs-8 col-lg-8">
                                        <input class="form-control" style=" width: 100%; " type="text" name="tc_id" id="hasta_tc_no" value="<?php echo $hastabilgilerigetir["tc_id"]; ?>">
                                        <input class="form-control" type="hidden" id="doktoridaa" name="doctor_id">
                                        <input class="form-control" type="hidden" id="birimkodeu" name="unit_code">
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-4 col-xs-4 col-lg-4">
                                        <label for="example-text-input" class="col-form-label">Telefon :</label>
                                    </div>
                                    <div class="col-md-8 col-xs-8 col-lg-8">
                                        <input class="form-control" style=" width: 100%; " type="text" name="telephone" value="<?php echo $hastabilgilerigetir["phone_number"]; ?>">
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-4 col-xs-4 col-lg-4">
                                        <label for="example-text-input" class="col-form-label">Ad Soyad:</label>
                                    </div>
                                    <div class="col-md-8 col-xs-8 col-lg-8">
                                        <input class="form-control" style=" width: 100%; " type="text" id="hasta_adi" name="name_surname" value="<?php echo $hastabilgilerigetir["patient_name"]; ?> <?php echo $hastabilgilerigetir["patient_surname"]; ?>">
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-4 col-xs-4 col-lg-4">
                                        <label for="example-text-input" class="col-form-label">Tarih :</label>
                                    </div>
                                    <div class="col-md-8 col-xs-8 col-lg-8">
                                        <input type="datetime-local" style=" width: 100%; " id="appointment_date" name="appointment_time" class="form-control" required>
                                    </div>
                                </div>
                                <br/>

                                <button class="easyui-linkbutton" type="button" iconCls="icon-ok" id="randevu_kayits">Kaydet</button>
                                <button data-bs-dismiss="modal" type="button" iconCls="icon-cancel" class="easyui-linkbutton">Kapat</button>

                            </form>

        </div>
        </div>

            <div data-options="region:'center',title:'Takvim'" style="width: 80%; height: 100%;">
            <div class="calendar-scroll" id="calendar">
                <div style=" padding: 15%; " class="alert alert-warning alert-dismissible fade show  mb-0 text-center"
                     role="alert">
                    <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                    <h5 class="text-warning">sol taraftan seçim yapınız</h5>
                    <p>sol taraftan birim ve doktor seçimi yapınız</p>
                </div>
            </div>
        </div>
        </div>



        <script type="text/javascript">
            $(document).ready(function () {
                $("#poliklinik").change(function () {
                    var poliklinikid = $(this).val();
                    $.ajax({
                        type: "post",
                        url: "ajax/hastaislemleri/poliklinikdoktorsec.php?islem=poliklinikdoktor",
                        data: {"poliklinikid": poliklinikid},
                        success: function (e) {
                            document.getElementById("birimkodeu").value = poliklinikid;
                            $("#doktor").html(e);
                        }
                    })
                });

                $("#doktor").change(function () {
                    var poliklinikid = $(this).val();
                    $.get("ajax/hastaislemleri/poliklinikrandevuislem.php?islem=takvimigoruntule", {doktorid: poliklinikid}, function (getveri) {
                        $('#calendar').html(getveri);
                        document.getElementById("doktoridaa").value = poliklinikid;
                    });
                });

                $("#randevu_kayits").on("click", function () { // buton idli elemana tıklandığında
                    var gonderilenformcc = $("#gonderilenformcc").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
                    $.ajax({
                        url: 'ajax/hastaislemleri/poliklinikrandevuislem.php?islem=randevuekle', // serileştirilen değerleri ajax.php dosyasına
                        type: 'post', // post metodu ile
                        data: gonderilenformcc, // yukarıda serileştirdiğimiz gonderilenform değişkeni
                        success: function (e) { // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                            if (e == 1) {
                                alertify.success("randevu kaydı");
                                var doktoridaa = document.getElementById("doktoridaa").value;
                                $.get("ajax/hastaislemleri/poliklinikrandevuislem.php?islem=takvimigoruntule", {doktorid: doktoridaa}, function (getveri) {
                                    $('#calendar').html(getveri);
                                    $.get("ajax/hastaislemleri/randevulistesi.php", {tc_id:<?php echo $_GET["tc_id"]; ?> }, function (getVerssi) {
                                        $('#hastabazlirandevulistesiicerik').html(getVerssi);
                                    });
                                });
                            }
                            if (e == 2) {

                                alertify.error("randevu işlemi başarısız. eklemek istediğiniz saatte başka bir hasta bulunmaktadır");
                                var doktoridaa = document.getElementById("doktoridaa").value;
                                $.get("ajax/hastaislemleri/poliklinikrandevuislem.php?islem=takvimigoruntule", {doktorid: doktoridaa}, function (getveri) {
                                    $('#calendar').html(getveri);
                                });
                            } else {
                                console.log(e);
                            }
                        }
                    });
                });
            });
        </script>


        <?php }if ($_GET["islem"] == "randevuekle"){

            $doktorbilgisi = tek("select * from patient_appointments where appointment_time='{$_POST["appointment_time"]}' and unit_code='{$_POST["unit_code"]}' and doctor_id='{$_POST["doctor_id"]}'   ");
//      var_dump($doktorbilgisi);
            if ($doktorbilgisi["id"] == "") {
                $_POST["insert_userid"] = $kullanicininidsi;
                $kayitsonuc = direktekle("patient_appointments", $_POST);
                if ($kayitsonuc) {
                    echo "1";
                }
            } else {
//2 ise bu saate kayıtlı bir hasta vardır demek.
                echo "2";
            }
        }if ($_GET["islem"] == "takvimigoruntule"){

        $doktorbilgisi = singular("users", "id", $_GET["doktorid"]);  ?>

     <center> <h5>Randevu Listesi- <?php echo $doktorbilgisi["name_surname"]; ?></h5></center>

        <div class="col-md-9 col-xs-9 col-sm-9 bg-white" id="calendar"></div>

<!--randevu talebini güncelle------------------------------------------------------------------------------------------>
        <div class="modal fade" id="randevu_guncelle" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title"><span id="get_name"></span> kişisinin randevu talebini
                            güncelle</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true"></button>
                    </div>

                    <div class="modal-body p-4">
                        <form class="needs-validation" name="event-form" id="form-event" novalidate>
                            <div class="row">

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">birim seçiniz</label>
                                        <select class="form-select" id="department_id_update">
                                            <option class="bg-danger text-white" selected>seçiniz</option>
                                            <?php $ameliyat_birimleri = verilericoklucek("select * from operating_rooms where status='1'");
                                            foreach ($ameliyat_birimleri as $item) { ?>
                                                <option value="<?php echo $item['operating_id']; ?>"><?php echo $item['unit_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">randevu tarihi seçiniz</label>
                                        <input type="datetime-local" id="appointment_date_update" class="form-control" required>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-2">
                                <div class="d-flex justify-content-end">
                                    <input type="hidden" id="appointment_id">
                                    <button type="button" class="btn btn-outline-danger" id="appointment_delete">bu randevu talebini sil</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-danger mx-2">kapat</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-success" id="randevu_guncelle_clk">güncelle</button>
                                </div>
                            </div>

                        </form>

                        <script>
                            var calendarEl = document.getElementById('calendar');
                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                editable: false,
                                navLinks: true,
                                eventLimit: true,
                                timeZone: 'local',
                                themeSystem: 'bootstrap5',

                                selectable: false,
                                locale: 'tr',
                                buttonText: {
                                    today: 'Bugün',
                                    month: 'Ay',
                                    week: 'Hafta',
                                    day: 'Gün',
                                    list: 'Liste',
                                    listMonth: 'Aylık Liste',
                                    listYear: 'Yıllık Liste',
                                    listWeek: 'Haftalık Liste',
                                    listDay: 'Günlük Liste'
                                },

                                initialView: 'dayGridMonth',
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                                },

                                events: [
                                    <?php $data = verilericoklucek("SELECT * FROM patient_appointments WHERE doctor_id='{$_GET["doktorid"]}'");
                                    foreach ($data as $item){ ?>
                                    {
                                        departmentid: '<?php echo $item['unit_code']; ?>',
                                        id: '<?php echo $item['id']; ?>',
                                        title: '<?php echo $item['name_surname']; ?>',
                                        start: '<?php echo $item['appointment_time']; ?>',
                                        allDay: false,
                                        className: "bg-success text-white",
                                        status: 'checked in'
                                    },
                                    <?php } ?>
                                ],

                                eventClick: function (info) {
                                    $("#randevu_guncelle_modal").trigger("click");
                                    $('#APPOINTMENT_DATE_UPDATE').val(info.event.start);
                                    $('#GET_NAME').html('');
                                    $('#GET_NAME').append(info.event.title);
                                    $('#APPOINTMENT_ID').val(info.event.id);
                                    $('#randevu_guncelle_clk').prop('disabled', "disabled");
                                    $('#calendar').fullCalendar('updateEvent', info);
                                }

                            });

                            calendar.render();
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="randevu_guncelle_modal" data-bs-target="#randevu_guncelle" data-bs-toggle="modal" data-bs-dismiss="modal"/>

    </div>

    <?php } ?>