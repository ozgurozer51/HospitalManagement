<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem = $_GET["islem"];

if ($islem == "sonuc_onaylama_hasta_bilgileri_form") {
    $git = $_GET['service_requestsid'];
    $service_requestsid = trim($git);

    $pp = tek("select distinct pp.service_requestsid,
                pp.patient_id,
                pp.protocol_number
from patient_prompts as pp where  pp.service_requestsid='$service_requestsid'
group by pp.service_requestsid, pp.patient_id, pp.protocol_number");

    $hasta_kayit = singularactive("patient_registration", "protocol_number", $pp["protocol_number"]);
    $hasta_id = $hasta_kayit["patient_id"];

    $hasta = singularactive("patients", "id", $hasta_id);
    $dogum = $hasta['birth_date'];

    $YAS = ikitariharasindakiyilfark($simdikitarih, $dogum);
    $sosyalguvence = tek("select * from transaction_definitions where definition_code='{$hasta["social_assurance"]}'");
    $kurumgetir = tek("select * FROM transaction_definitions where definition_code='{$hasta["institution"]}'");
    $oda = tek("select * FROM hospital_room where id='{$hasta_kayit["room_id"]}'");
    $yatak = tek("select * FROM hospital_bed where id='{$hasta_kayit["bed_id"]}'");
    ?>

    <div class="row px-1">

        <h6 class="contentTitle mt-2">Hasta Bilgileri</h6>

        <div class="col-4 mt-1">

            <div class="row">
                <div class="col-5">
                    <label>Protokol No</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="protokol_no"
                           value="<?php echo $pp["protocol_number"]; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Adı Soyadı</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="adi_soyadi"
                           value="<?php echo ucwords($hasta["patient_name"] . " " . $hasta["patient_surname"]) ?>(<?php echo $YAS ?>)">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>TC Kimlik No</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="tc_id"
                           value="<?php echo $hasta["tc_id"]; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Kurum</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="krum"
                           value="<?php echo $kurumgetir["definition_name"] . " / " . $sosyalguvence["definition_name"]; ?>">
                </div>
            </div>


            <div class="row">
                <div class="col-5">
                    <label>Kan Grubu</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="kan"
                           value="<?php echo islemtanimgetirid($hasta["blood_group"]); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Müracaat Tarihi</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="muracaat_tarihi"
                           value="<?php echo nettarih($hasta_kayit["insert_datetime"]); ?>">
                </div>
            </div>

        </div>

        <div class="col-4 mt-1">

            <div class="row">
                <div class="col-5">
                    <label>Birim</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="birim" value="<?php
                    if ($hasta_kayit["service_id"] == null) {
                        echo birimgetirid($hasta_kayit["outpatient_id"]);
                    } else {
                        echo birimgetirid($hasta_kayit["service_id"]);
                    } ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Doktor</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="doktor" value="<?php
                    if ($hasta_kayit["outpatient_id"] == null) {
                        echo kullanicigetirid($hasta_kayit["service_doctor"]);
                    } else {
                        echo kullanicigetirid($hasta_kayit["doctor"]);
                    } ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Vaka Türü</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="vaka_turu"
                           value="<?php echo islemtanimgetirid($hasta_kayit["reason_arrival"]); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Kabul Şekli</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="kabul_sekli"
                           value="<?php echo islemtanimgetirid($hasta_kayit["patient_admission_type"]); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-5">
                    <label>Oda/Yatak</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="oda_yatak"
                           value="<?php echo $oda["room_name"] . " / " . $yatak["bed_name"]; ?>">
                </div>
            </div>


            <div class="row">
                <div class="col-5">
                    <label>Yatış Tarihi</label>
                </div>
                <div class="col-7">
                    <input readonly class="form-control form-control-sm" type="text" id="yatis_tarihi"
                           value="<?php echo nettarih($hasta_kayit["admission_start_date"]); ?>">
                </div>
            </div>

        </div>

        <div class="col-4 mt-1">

            <div align="center">
                <img style="width: 50%;" class="rounded h-75 mt-2  img-thumbnail"
                     src="<?php if ($hasta["photo"] != '') {
                         echo $hasta["photo"];
                     } else {
                         if ($hasta["gender"] == 'E') {
                             echo "assets/img/dummy-user.jpeg";
                         } elseif ($hasta["gender"] == 'K') {
                             echo "assets/img/bdummy-user.jpeg";
                         }
                     } ?>">
            </div>

        </div>

    </div>

<?php }

else if ($islem == "laboratuvar_sonuc_ekrani_buttonlari") {
    $pp_id = $_GET['patient_promptsid'];
    $patient_prompts_tb = singularactive("patient_prompts", "id", $pp_id);
    $service_requestsid = $patient_prompts_tb['service_requestsid'];
    $analysis_result_status = $patient_prompts_tb['analysis_result_status'];?>

    <input type="text" hidden id="patient_promptsid" value="<?php echo $pp_id; ?>">
    <input type="text" hidden id="service_requestsid" value="<?php echo $service_requestsid; ?>">

    <div class="col-12 mt-1 px-2" align="left">

        <?php $sql = tek("select * from lab_analysis_results where patient_promptsid=$pp_id");
        if ($sql["patient_promptsid"] == null) {
            ?>

            <button title="Sonuç Gir" onclick="get_forms()" class="btn btn-sm btn-success "><i class="fa-sharp fa-solid fa-memo"></i> <b>Sonuç Gir</b></button>
            <button onclick="gecmis_sonuclar()" class="btn btn-sm btn-success btn_gecmis_sonuclar"><i class="fa-sharp fa-solid fa-memo"></i> <b>Geçmiş Sonuçlar</b></button>

        <?php } else {

            if ($patient_prompts_tb['analysis_result_status'] == 0){?>

                <button class="btn btn-sm btn-success btn_lbrnt_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Laborant Onay</b></button>

                <button class="btn btn-sm btn-success btn_uzm_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman Onay</b></button>

                <button onclick="tekrar_sonuc_gir()" class="btn btn-sm btn-success btn_tekrar_calisilan_sonuclar"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tekrar Sonuçlar</b></button>

            <?php }

            else if($patient_prompts_tb['analysis_result_status'] == 1){ ?>

                <button class="btn btn-sm btn-success btn_lbrnt_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Laborant Onay İptal</b></button>

                <button class="btn btn-sm btn-success btn_uzm_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman Onay</b></button>

                <button onclick="tekrar_sonuc_gir()" class="btn btn-sm btn-success btn_tekrar_calisilan_sonuclar"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tekrar Sonuçlar</b></button>

            <?php }
            else if($patient_prompts_tb['analysis_result_status'] == 2){ ?>

                <button class="btn btn-sm btn-success btn_uzm_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman Onay İptal</b></button>

                <button onclick="tekrar_sonuc_gir()"  class="btn btn-sm btn-success btn_tekrar_calisilan_sonuclar"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tekrar Sonuçlar</b></button>

            <?php } ?>

            <button onclick="gecmis_sonuclar()" class="btn btn-sm btn-success btn_gecmis_sonuclar"><i class="fa-sharp fa-solid fa-memo"></i> <b>Geçmiş Sonuçlar</b></button>

            <button class="btn btn-sm btn-success btn_rapor_cikart"><i class="fa-sharp fa-solid fa-print"></i> <b>Rapor</b></button>

        <?php } ?>


    </div>

    <script>
        var service_requestsid = $('#service_requestsid').val();
        function get_forms() {
            var pp_id = $('#patient_promptsid').val();

            $('.w1').window('open');
            $('.w1').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=sonuc_girisi_modal&patient_promptsid=' + pp_id + '');
        }

        function gecmis_sonuclar() {
            var pp_id = $('#patient_promptsid').val();

            $('.w2').window('open');
            $('.w2').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=gecmis_tetkik_sonuclari&patient_promptsid=' + pp_id + '');
        }
        function tekrar_sonuc_gir() {
            var pp_id = $('#patient_promptsid').val();

            $('.w3').window('open');
            $('.w3').window('refresh', 'ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=tekrar_sonuc_gir&patient_promptsid=' + pp_id + '');
        }


        $("body").off("click", ".btn_lbrnt_onay").on("click", ".btn_lbrnt_onay", function (e) {
            var patient_promptsid = $('#patient_promptsid').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_laborant_onay',
                data: {"patient_promptsid": patient_promptsid},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    function format(d) {
                        // console.log(d[0].split('/')[1]);
                        return (
                            '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                            '<thead>'+
                            '<tr>' +
                            '<th>Alt Parametre</th>' +
                            '<th>Tekrar Çalışma Sayısı</th>' +
                            '<th>Sonuç</th>' +
                            '<th>Birim</th>' +
                            '<th>Referans Aralığı</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
                            '</table>'
                        );
                    }

                    $(document).ready(function () {

                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                            if (result != 2) {
                                var json = JSON.parse(result);
                                test_dt.rows([]).clear().draw();
                                json.forEach(function (item) {
                                    var islem_tarih = item.sonuc_tarihi;
                                    var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                    if (islem_tarihi == "Invalid date") {
                                        islem_tarihi = "";
                                    }

                                    var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                    var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                    if (tarih_onaylayan_laborant == "Invalid date") {
                                        tarih_onaylayan_laborant = "";
                                    }

                                    var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                    var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                    if (uzman_onay_tarihi == "Invalid date") {
                                        uzman_onay_tarihi = "";
                                    }

                                    var basilacak = "";
                                    if (item.tetkik_sonuc_durum == 0){
                                        basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                    }else if (item.tetkik_sonuc_durum == 1){
                                        basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                    }else if (item.tetkik_sonuc_durum == 2){
                                        basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                    }

                                    let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                    $(row).attr("data-id",item.idsi);
                                });
                            }

                        });

                        $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                            var id = $(this).attr("data-id");
                            var tr = $(this).closest('tr');
                            var row = test_dt.row(tr);
                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                row.child(format(row.data())).show();
                                tr.addClass('shown');
                            }

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                if (result != 2){
                                    var json = JSON.parse(result);
                                    json.forEach(function (item){

                                        var tektrar_calisma = item.tektrar_calisma_sayisi;
                                        if (tektrar_calisma == null){
                                            tektrar_calisma = "";
                                        }


                                        $(".eklenecek" + id).append(
                                            "<tr>"+
                                            "<td>"+item.parametre_adi+"</td>" +
                                            "<td>"+tektrar_calisma+"</td>" +
                                            "<td>"+item.tetkik_sonucu+"</td>" +
                                            "<td>"+item.birimadi+"</td>" +
                                            "<td>"+item.alt_limit+' - '+item.ust_limit+"</td>" +
                                            "</tr>");
                                    });
                                }
                            });
                        });

                    });

                    $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                        var classvarmi=$(this).hasClass('ekle');
                        if (classvarmi) {
                            $(this).removeClass('fa-circle-minus');
                            $(this).addClass('fa-circle-plus');
                            $(this).removeClass('ekle');
                            $(this).removeClass('kapali');
                            $(this).addClass('acik');


                        } else {
                            $(this).addClass('fa-circle-minus');
                            $(this).removeClass('fa-circle-plus');
                            $(this).addClass('ekle');
                            // $(this).removeAttr("style")
                            $(this).removeClass('acik');
                            $(this).addClass('kapali');
                        }

                    });

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                        $('#sonuc_onaylama_buttons').html(getveri);
                    })
                }
            });
        });

        $("body").off("click", ".btn_lbrnt_onay_iptal").on("click", ".btn_lbrnt_onay_iptal", function (e) {
            var patient_promptsid = $('#patient_promptsid').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_laborant_onay_iptal',
                data: {"patient_promptsid": patient_promptsid},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    function format(d) {
                        // console.log(d[0].split('/')[1]);
                        return (
                            '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                            '<thead>'+
                            '<tr>' +
                            '<th>Alt Parametre</th>' +
                            '<th>Tekrar Çalışma Sayısı</th>' +
                            '<th>Sonuç</th>' +
                            '<th>Birim</th>' +
                            '<th>Referans Aralığı</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
                            '</table>'
                        );
                    }

                    $(document).ready(function () {

                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                            if (result != 2) {
                                var json = JSON.parse(result);
                                test_dt.rows([]).clear().draw();
                                json.forEach(function (item) {
                                    var islem_tarih = item.sonuc_tarihi;
                                    var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                    if (islem_tarihi == "Invalid date") {
                                        islem_tarihi = "";
                                    }

                                    var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                    var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                    if (tarih_onaylayan_laborant == "Invalid date") {
                                        tarih_onaylayan_laborant = "";
                                    }

                                    var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                    var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                    if (uzman_onay_tarihi == "Invalid date") {
                                        uzman_onay_tarihi = "";
                                    }

                                    var basilacak = "";
                                    if (item.tetkik_sonuc_durum == 0){
                                        basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                    }else if (item.tetkik_sonuc_durum == 1){
                                        basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                    }else if (item.tetkik_sonuc_durum == 2){
                                        basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                    }

                                    let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                    $(row).attr("data-id",item.idsi);
                                });
                            }

                        });

                        $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                            var id = $(this).attr("data-id");
                            var tr = $(this).closest('tr');
                            var row = test_dt.row(tr);
                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                row.child(format(row.data())).show();
                                tr.addClass('shown');
                            }

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                if (result != 2){
                                    var json = JSON.parse(result);
                                    json.forEach(function (item){

                                        var tektrar_calisma = item.tektrar_calisma_sayisi;
                                        if (tektrar_calisma == null){
                                            tektrar_calisma = "";
                                        }


                                        $(".eklenecek" + id).append(
                                            "<tr>"+
                                            "<td>"+item.parametre_adi+"</td>" +
                                            "<td>"+tektrar_calisma+"</td>" +
                                            "<td>"+item.tetkik_sonucu+"</td>" +
                                            "<td>"+item.birimadi+"</td>" +
                                            "<td>"+item.alt_limit+' - '+item.ust_limit+"</td>" +
                                            "</tr>");
                                    });
                                }
                            });
                        });

                    });

                    $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                        var classvarmi=$(this).hasClass('ekle');
                        if (classvarmi) {
                            $(this).removeClass('fa-circle-minus');
                            $(this).addClass('fa-circle-plus');
                            $(this).removeClass('ekle');
                            $(this).removeClass('kapali');
                            $(this).addClass('acik');


                        } else {
                            $(this).addClass('fa-circle-minus');
                            $(this).removeClass('fa-circle-plus');
                            $(this).addClass('ekle');
                            // $(this).removeAttr("style")
                            $(this).removeClass('acik');
                            $(this).addClass('kapali');
                        }

                    });

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                        $('#sonuc_onaylama_buttons').html(getveri);
                    })
                }
            });
        });

        $("body").off("click", ".btn_uzm_onay").on("click", ".btn_uzm_onay", function (e) {
            var patient_promptsid = $('#patient_promptsid').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_sonuc_uzman_onay',
                data: {"patient_promptsid": patient_promptsid},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    function format(d) {
                        // console.log(d[0].split('/')[1]);
                        return (
                            '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                            '<thead>'+
                            '<tr>' +
                            '<th>Alt Parametre</th>' +
                            '<th>Tekrar Çalışma Sayısı</th>' +
                            '<th>Sonuç</th>' +
                            '<th>Birim</th>' +
                            '<th>Referans Aralığı</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
                            '</table>'
                        );
                    }

                    $(document).ready(function () {

                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                            if (result != 2) {
                                var json = JSON.parse(result);
                                test_dt.rows([]).clear().draw();
                                json.forEach(function (item) {
                                    var islem_tarih = item.sonuc_tarihi;
                                    var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                    if (islem_tarihi == "Invalid date") {
                                        islem_tarihi = "";
                                    }

                                    var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                    var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                    if (tarih_onaylayan_laborant == "Invalid date") {
                                        tarih_onaylayan_laborant = "";
                                    }

                                    var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                    var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                    if (uzman_onay_tarihi == "Invalid date") {
                                        uzman_onay_tarihi = "";
                                    }

                                    var basilacak = "";
                                    if (item.tetkik_sonuc_durum == 0){
                                        basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                    }else if (item.tetkik_sonuc_durum == 1){
                                        basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                    }else if (item.tetkik_sonuc_durum == 2){
                                        basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                    }

                                    let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                    $(row).attr("data-id",item.idsi);
                                });
                            }

                        });

                        $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                            var id = $(this).attr("data-id");
                            var tr = $(this).closest('tr');
                            var row = test_dt.row(tr);
                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                row.child(format(row.data())).show();
                                tr.addClass('shown');
                            }

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                if (result != 2){
                                    var json = JSON.parse(result);
                                    json.forEach(function (item){

                                        var tektrar_calisma = item.tektrar_calisma_sayisi;
                                        if (tektrar_calisma == null){
                                            tektrar_calisma = "";
                                        }


                                        $(".eklenecek" + id).append(
                                            "<tr>"+
                                            "<td>"+item.parametre_adi+"</td>" +
                                            "<td>"+tektrar_calisma+"</td>" +
                                            "<td>"+item.tetkik_sonucu+"</td>" +
                                            "<td>"+item.birimadi+"</td>" +
                                            "<td>"+item.alt_limit+' - '+item.ust_limit+"</td>" +
                                            "</tr>");
                                    });
                                }
                            });
                        });

                    });

                    $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                        var classvarmi=$(this).hasClass('ekle');
                        if (classvarmi) {
                            $(this).removeClass('fa-circle-minus');
                            $(this).addClass('fa-circle-plus');
                            $(this).removeClass('ekle');
                            $(this).removeClass('kapali');
                            $(this).addClass('acik');


                        } else {
                            $(this).addClass('fa-circle-minus');
                            $(this).removeClass('fa-circle-plus');
                            $(this).addClass('ekle');
                            // $(this).removeAttr("style")
                            $(this).removeClass('acik');
                            $(this).addClass('kapali');
                        }

                    });

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                        $('#sonuc_onaylama_buttons').html(getveri);
                    })
                }
            });
        });

        $("body").off("click", ".btn_uzm_onay_iptal").on("click", ".btn_uzm_onay_iptal", function (e) {
            var patient_promptsid = $('#patient_promptsid').val();
            $.ajax({
                type: 'POST',
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_sonuc_uzman_onay_iptal',
                data: {"patient_promptsid": patient_promptsid},
                success: function (e) {
                    $('#sonucyaz').html(e);

                    function format(d) {
                        // console.log(d[0].split('/')[1]);
                        return (
                            '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                            '<thead>'+
                            '<tr>' +
                            '<th>Alt Parametre</th>' +
                            '<th>Tekrar Çalışma Sayısı</th>' +
                            '<th>Sonuç</th>' +
                            '<th>Birim</th>' +
                            '<th>Referans Aralığı</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
                            '</table>'
                        );
                    }

                    $(document).ready(function () {

                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                            if (result != 2) {
                                var json = JSON.parse(result);
                                test_dt.rows([]).clear().draw();
                                json.forEach(function (item) {
                                    var islem_tarih = item.sonuc_tarihi;
                                    var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                    if (islem_tarihi == "Invalid date") {
                                        islem_tarihi = "";
                                    }

                                    var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                    var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                    if (tarih_onaylayan_laborant == "Invalid date") {
                                        tarih_onaylayan_laborant = "";
                                    }

                                    var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                    var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                    if (uzman_onay_tarihi == "Invalid date") {
                                        uzman_onay_tarihi = "";
                                    }

                                    var basilacak = "";
                                    if (item.tetkik_sonuc_durum == 0){
                                        basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                    }else if (item.tetkik_sonuc_durum == 1){
                                        basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                    }else if (item.tetkik_sonuc_durum == 2){
                                        basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                    }

                                    let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                    $(row).attr("data-id",item.idsi);
                                });
                            }

                        });

                        $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                            var id = $(this).attr("data-id");
                            var tr = $(this).closest('tr');
                            var row = test_dt.row(tr);
                            if (row.child.isShown()) {
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                row.child(format(row.data())).show();
                                tr.addClass('shown');
                            }

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                if (result != 2){
                                    var json = JSON.parse(result);
                                    json.forEach(function (item){

                                        var tektrar_calisma = item.tektrar_calisma_sayisi;
                                        if (tektrar_calisma == null){
                                            tektrar_calisma = "";
                                        }


                                        $(".eklenecek" + id).append(
                                            "<tr>"+
                                            "<td>"+item.parametre_adi+"</td>" +
                                            "<td>"+tektrar_calisma+"</td>" +
                                            "<td>"+item.tetkik_sonucu+"</td>" +
                                            "<td>"+item.birimadi+"</td>" +
                                            "<td>"+item.alt_limit+' - '+item.ust_limit+"</td>" +
                                            "</tr>");
                                    });
                                }
                            });
                        });

                    });

                    $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                        var classvarmi=$(this).hasClass('ekle');
                        if (classvarmi) {
                            $(this).removeClass('fa-circle-minus');
                            $(this).addClass('fa-circle-plus');
                            $(this).removeClass('ekle');
                            $(this).removeClass('kapali');
                            $(this).addClass('acik');


                        } else {
                            $(this).addClass('fa-circle-minus');
                            $(this).removeClass('fa-circle-plus');
                            $(this).addClass('ekle');
                            // $(this).removeAttr("style")
                            $(this).removeClass('acik');
                            $(this).addClass('kapali');
                        }

                    });

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                        $('#sonuc_onaylama_buttons').html(getveri);
                    })
                }
            });
        });

    </script>

<?php }

else if ($islem == "laboratuvar_sonuc_ekrani_buttonlari_disabled") { ?>

    <div class="col-12 mt-1 px-2" align="left">

        <button title="Manuel Sonuç Girişi" disabled class="btn btn-sm btn-success"><i
                    class="fa-sharp fa-solid fa-memo"></i> <b>Sonuç Gir</b></button>

        <button disabled class="btn btn-sm btn-success btn_lbrnt_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Laborant
                Onay</b></button>

        <button disabled class="btn btn-sm btn-success btn_lbrnt_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i>
            <b>Laborant
                Onay İptal</b></button>

        <button disabled class="btn btn-sm btn-success btn_uzm_onay"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman
                Onay</b></button>

        <button disabled class="btn btn-sm btn-success btn_uzm_onay_iptal"><i class="fa-sharp fa-solid fa-memo"></i> <b>Uzman
                Onay
                İptal</b></button>

        <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-memo"></i> <b>Geçmiş
                Sonuçlar</b></button>

        <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-memo"></i> <b>Tekrar
                Sonuçlar</b></button>

        <button disabled class="btn btn-sm btn-success"><i class="fa-sharp fa-solid fa-print"></i> <b>Rapor</b>
        </button>

    </div>

<?php }

else if ($islem == "sonuc_girisi_modal") {
    $patient_promptsid = $_GET['patient_promptsid'];
    $patient_prompts_tb = singularactive("patient_prompts", "id", $patient_promptsid);
    $service_requestsid = $patient_prompts_tb['service_requestsid'];
    $benzersiz = uniqid(); ?>
    <div class="col-12">


        <input type="text" id="ppid" hidden value="<?php echo $patient_promptsid; ?>">
        <input type="text" id="service_requestid" hidden value="<?php echo $service_requestsid; ?>">

        <h5><?php echo $patient_prompts_tb["request_name"]; ?></h5>
        <table id="<?php echo $benzersiz ?>" class="table table-sm table-bordered w-100 display nowrap"
               style="font-size: 13px;">
            <thead>
            <tr>
                <th>Parametre Adı</th>
                <th>Birim</th>
                <th>Referans Aralığı</th>
                <th>Sonuç</th>
                <th>Açıklama</th>

            </tr>
            </thead>
        </table>
        <div class="row" align="right">
            <div class="col-8"></div>
            <div class="col-4">
                <button type="button" class="btn btn-danger sonuc_gir_kapat">Kapat</button>
                <button type="button" class="btn btn-success test_sonuc_kayit">Kaydet</button>
            </div>

        </div>
    </div>
    <script>
        var service_request = $('#service_requestid').val();

        var table_sonuc_gir = $('#<?php echo $benzersiz?>').DataTable({
            scrollY: '35vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
        })

        var pp_id = $('#patient_promptsid').val();
        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_sonuc_gir_table", {'pp_id': pp_id}, function (result) {
            if (result != 2) {
                var json = JSON.parse(result);
                table_sonuc_gir.rows([]).clear().draw();
                json.forEach(function (item) {
                    table_sonuc_gir.row.add([item.parametre_adi, item.birimadi, item.alt_limit + ' - ' + item.ust_limit, '<input type="text" name="lab_analysis_result" class="sonuc' + item.parametre_id + ' doluluk_kontrol sonuc" data-id="' + item.parametre_id + '" pp-id="' + item.ppid + '" id="sonuc"/>', ' <input type="text"  name="result_notification" class="aciklama' + item.parametre_id + '  aciklama" id="aciklama"/>']).draw(false);
                });
            }

        });

        $("body").off("keypress", ".sonuc").on("keypress", ".sonuc", function () {
            if ($(this).hasClass("alinacak")) {

            } else {
                $(this).addClass("alinacak");
            }
        });

        $("body").off("keypress", ".aciklama").on("keypress", ".aciklama", function () {
            if ($(this).hasClass("aciklama_val")) {

            } else {
                $(this).addClass("aciklama_val");
            }
        });


        $("body").off("click", ".test_sonuc_kayit").on("click", ".test_sonuc_kayit", function (e) {

            var parametreid = [];
            var pp_id = [];
            var aciklamalar = [];
            var sonuclar = [];

            $(".alinacak").each(function () {
                var parametre_id = $(this).attr("data-id");
                var sonuc = $(this).val();
                var ppid = $(this).attr("pp-id");
                pp_id.push(ppid);
                sonuclar.push(sonuc);
                parametreid.push(parametre_id);
            });

            $(".aciklama_val").each(function () {
                var value = $(this).val();
                aciklamalar.push(value);
            });



            // console.log(pp_id);
            // console.log(aciklamalar);
            // console.log(parametreid);
            // console.log(sonuclar);
            // console.log(tekrar_calismalar);

            $(".doluluk_kontrol").each(function () {
                if ($(this).val() == "") {
                    $(this).removeClass("sonuc_var");
                    $(this).addClass("sonuc_yok");
                } else {
                    $(this).removeClass("sonuc_yok");
                    $(this).addClass("sonuc_var");
                }
            });
            if (!$(".doluluk_kontrol").hasClass("sonuc_yok")) {
                $.ajax({
                    url: 'ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_sonuc_gir',
                    type: 'POST',
                    data: {
                        assay_parameterid: parametreid,
                        patient_promptsid: pp_id,
                        lab_analysis_result: sonuclar,
                        result_notification: aciklamalar,
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $('.w1').window('close');

                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                            $('#sonuc_onaylama_buttons').html(getveri);
                        })

                        function format(d) {
                            // console.log(d[0].split('/')[1]);
                            return (
                                '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Alt Parametre</th>' +
                                '<th>Sonuç</th>' +
                                '<th>Birim</th>' +
                                '<th>Referans Aralığı</th>' +
                                '<th>Tekrar Çalışma Sayısı</th>' +
                                '<th>Tekrar Çalışılma Nedeni</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody class="eklenecek' + d[0].split('/')[1] + '"></tbody>' +
                                '</table>'
                            );
                        }

                        $(document).ready(function () {

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_request}, function (result) {
                                if (result != 2) {
                                    var json = JSON.parse(result);
                                    test_dt.rows([]).clear().draw();
                                    json.forEach(function (item) {
                                        var islem_tarih = item.sonuc_tarihi;
                                        var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                        if (islem_tarihi == "Invalid date") {
                                            islem_tarihi = "";
                                        }

                                        var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                        var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                        if (tarih_onaylayan_laborant == "Invalid date") {
                                            tarih_onaylayan_laborant = "";
                                        }

                                        var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                        var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                        if (uzman_onay_tarihi == "Invalid date") {
                                            uzman_onay_tarihi = "";
                                        }

                                        var basilacak = "";
                                        if (item.tetkik_sonuc_durum == 0){
                                            basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                        }else if (item.tetkik_sonuc_durum == 1){
                                            basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                        }else if (item.tetkik_sonuc_durum == 2){
                                            basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                        }

                                        let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                        $(row).attr("data-id",item.idsi);
                                    });
                                }

                            });

                            $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                                var id = $(this).attr("data-id");
                                var tr = $(this).closest('tr');
                                var row = test_dt.row(tr);
                                if (row.child.isShown()) {
                                    row.child.hide();
                                    tr.removeClass('shown');
                                } else {
                                    row.child(format(row.data())).show();
                                    tr.addClass('shown');
                                }

                                $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                    if (result != 2){
                                        var json = JSON.parse(result);
                                        json.forEach(function (item){

                                            var tektrar_calisma = item.tektrar_calisma_sayisi;
                                            if (tektrar_calisma == null){
                                                tektrar_calisma = "";
                                            }


                                            var yeniden_calisilma_neden = item.yeniden_calisilma_nedeni;
                                            if (yeniden_calisilma_neden == null) {
                                                yeniden_calisilma_neden = "";
                                            }


                                            $(".eklenecek" + id).append(
                                                "<tr>" +
                                                "<td>" + item.parametre_adi + "</td>" +
                                                "<td>" + item.tetkik_sonucu + "</td>" +
                                                "<td>" + item.birimadi + "</td>" +
                                                "<td>" + item.alt_limit + ' - ' + item.ust_limit + "</td>" +
                                                "<td>" + tektrar_calisma + "</td>" +
                                                "<td>" + yeniden_calisilma_neden + "</td>" +
                                                "</tr>");
                                        });
                                    }
                                });
                            });

                        });

                        $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                            var classvarmi=$(this).hasClass('ekle');
                            if (classvarmi) {
                                $(this).removeClass('fa-circle-minus');
                                $(this).addClass('fa-circle-plus');
                                $(this).removeClass('ekle');
                                $(this).removeClass('kapali');
                                $(this).addClass('acik');


                            } else {
                                $(this).addClass('fa-circle-minus');
                                $(this).removeClass('fa-circle-plus');
                                $(this).addClass('ekle');
                                // $(this).removeAttr("style")
                                $(this).removeClass('acik');
                                $(this).addClass('kapali');
                            }

                        });
                    }
                });
            } else {
                alertify.warning("Lütfen Sonuç Giriniz");
            }
        });

        $("body").off("click", ".sonuc_gir_kapat").on("click", ".sonuc_gir_kapat", function (e) {
            $('.w1').window('close');
        });
    </script>


<?php }

else if($islem=="gecmis_tetkik_sonuclari"){
    $patient_promptsid = $_GET['patient_promptsid'];
    $patient_prompts = singularactive("patient_prompts", "id", $patient_promptsid);
    $service_requestsid = $patient_prompts['service_requestsid'];
    $patient_service_requests = singularactive("patient_service_requests", "id", $service_requestsid);
    $patient_id = $patient_service_requests['patient_id'];
    $patients = singularactive("patients", "id", $patient_id);
    $patient_name = $patients['patient_name'];
    $patient_surname = $patients['patient_surname'];?>

        <input type="text" id="patient_id" hidden value="<?php echo $patient_id?>"/>

    <div class="col-12 row">
        <div class="col-4 mt-2">

            <div class="card">
                <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b><?php echo mb_convert_case($patient_name.' '.$patient_surname,MB_CASE_TITLE,"UTF-8"); ?> - Hastasına Ait Geçmiş Tetkik İstemleri</b></label></div>
            </div>
            <div class="mt-3"></div>
            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="hasta_gecmis_tetkik_istemleri_datatable">
                <thead>
                <tr>
                    <th>İstem Tarihi ve Saati</th>
                    <th>TC Kimlik No</th>
                    <th>Adı Soyadaı</th>
                    <th title="İstek Yapılan Birim">Birim</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="col-8 mt-2">

            <div class="card">
                <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Sonuçlar</b></label></div>
            </div>
            <div class="mt-3"></div>

            <div id="gecmis_sonuclari_goster">
                <table id="gecmis_sonuclari_goster_datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Drum</th>
                        <th>Tetkik Adı</th>
                        <th>İşlem Tarihi</th>
                        <th>Laborant Onay Tarihi</th>
                        <th>Onaylayan Laborant</th>
                        <th>Uzman Onay Tarihi</th>
                        <th>Onaylayan Uzman</th>
                        <th>Açıklama</th>
                        <th>Cihaz Adı</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

    <script>
        var patient_id = $('#patient_id').val();

        var hasta_gecmis_tetkik_istemleri = $('#hasta_gecmis_tetkik_istemleri_datatable').DataTable({
            deferRender: true,
            scrollY: '51vh',
            scrollX: true,
            "info":true,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            "fnRowCallback": function (nRow, aData) {
                $(nRow).addClass("gecmis_tetkik_sec");
            },
        })

        var gecmis_sonuclari_goster = $('#gecmis_sonuclari_goster_datatb').DataTable({
            deferRender: true,
            scrollY: '51vh',
            scrollX: true,
            "info":true,
            "paging":false,
            "searching":true,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            "fnRowCallback": function (nRow, aData) {
                $(nRow).addClass("treeTable_icerik1");
                $(nRow).addClass("sec_detay_liste1");
            },
        });

        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=hasta_gecmis_tetkik_istemler", {'patient_id': patient_id}, function (result) {
            if (result != 2) {
                var json = JSON.parse(result);
                hasta_gecmis_tetkik_istemleri.rows([]).clear().draw();
                json.forEach(function (item) {
                    var gecmis_istem_tarihi = item.istek_tarihi;
                    var gecmis_istek_tarihi = moment(gecmis_istem_tarihi).format('DD/MM/YY H:mm:ss');
                    if (gecmis_istek_tarihi == "Invalid date") {
                        gecmis_istek_tarihi = "";
                    }
                    let row = hasta_gecmis_tetkik_istemleri.row.add([gecmis_istek_tarihi,item.tc_id,item.hasta_adi+' '+item.hasta_soyadi,item.birim]).draw(false).node();
                    $(row).attr("data-id",item.istem_id);
                });
            }

        });

        $("body").off("click",".gecmis_tetkik_sec").on("click",".gecmis_tetkik_sec",function (){
            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.gecmis_tetkik_sec-kaldir').removeClass("text-white");
            $('.gecmis_tetkik_sec-kaldir').removeClass("gecmis_tetkik_sec-kaldir");
            $('.gecmis_tetkik_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white gecmis_tetkik_sec-kaldir");


            var service_requestsid = $(this).attr("data-id")

            function format(d) {
                // console.log(d[0].split('/')[1]);
                return (
                    '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                    '<thead>'+
                    '<tr>' +
                    '<th>Alt Parametre</th>' +
                    '<th>Sonuç</th>' +
                    '<th>Birim</th>' +
                    '<th>Referans Aralığı</th>' +
                    '<th>Tekrar Çalışma Sayısı</th>' +
                    '<th>Tekrar Çalışılma Nedeni</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody class="eklenecek'+ d[0].split('/')[1] +'"></tbody>'+
                    '</table>'
                );
            }

            $(document).ready(function () {

                $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_requestsid}, function (result) {
                    if (result != 2) {
                        var json = JSON.parse(result);
                        gecmis_sonuclari_goster.rows([]).clear().draw();
                        json.forEach(function (item) {
                            var islem_tarih = item.sonuc_tarihi;
                            var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                            if (islem_tarihi == "Invalid date") {
                                islem_tarihi = "";
                            }

                            var onay_tarihi_laborant = item.laborant_onay_tarihi;
                            var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                            if (tarih_onaylayan_laborant == "Invalid date") {
                                tarih_onaylayan_laborant = "";
                            }

                            var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                            var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                            if (uzman_onay_tarihi == "Invalid date") {
                                uzman_onay_tarihi = "";
                            }

                            var basilacak = "";
                            if (item.tetkik_sonuc_durum == 0){
                                basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                            }else if (item.tetkik_sonuc_durum == 1){
                                basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                            }else if (item.tetkik_sonuc_durum == 2){
                                basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                            }

                            let row = gecmis_sonuclari_goster.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                            $(row).attr("data-id",item.idsi);
                        });
                    }

                });

                $("body").off("click",".treeTable_icerik1").on("click",".treeTable_icerik1",function (){
                    var id = $(this).attr("data-id");
                    var tr = $(this).closest('tr');
                    var row = gecmis_sonuclari_goster.row(tr);
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }

                    $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                        if (result != 2){
                            var json = JSON.parse(result);
                            json.forEach(function (item){

                                var tektrar_calisma = item.tektrar_calisma_sayisi;
                                if (tektrar_calisma == null){
                                    tektrar_calisma = "";
                                }


                                var yeniden_calisilma_neden = item.yeniden_calisilma_nedeni;
                                if (yeniden_calisilma_neden == null) {
                                    yeniden_calisilma_neden = "";
                                }


                                $(".eklenecek" + id).append(
                                    "<tr>" +
                                    "<td>" + item.parametre_adi + "</td>" +
                                    "<td>" + item.tetkik_sonucu + "</td>" +
                                    "<td>" + item.birimadi + "</td>" +
                                    "<td>" + item.alt_limit + ' - ' + item.ust_limit + "</td>" +
                                    "<td>" + tektrar_calisma + "</td>" +
                                    "<td>" + yeniden_calisilma_neden + "</td>" +
                                    "</tr>");
                            });
                        }
                    });
                });

            });

            $("body").off("click",".iconudegistir1").on("click",".iconudegistir1",function (){
                var classvarmi=$(this).hasClass('ekle');
                if (classvarmi) {
                    $(this).removeClass('fa-circle-minus');
                    $(this).addClass('fa-circle-plus');
                    $(this).removeClass('ekle');
                    $(this).removeClass('kapali');
                    $(this).addClass('acik');


                } else {
                    $(this).addClass('fa-circle-minus');
                    $(this).removeClass('fa-circle-plus');
                    $(this).addClass('ekle');
                    // $(this).removeAttr("style")
                    $(this).removeClass('acik');
                    $(this).addClass('kapali');
                }

            });
        });

    </script>




<?php }

else if($islem=="tekrar_sonuc_gir"){
    $patient_promptsid1 = $_GET['patient_promptsid'];
    $patient_prompts_tb1 = singularactive("patient_prompts", "id", $patient_promptsid1);
    $service_requestsid1 = $patient_prompts_tb1['service_requestsid'];
    $benzersiz1 = uniqid(); ?>

    <div class="col-12">
        <input type="text" id="ppid" hidden value="<?php echo $patient_promptsid1; ?>">
        <input type="text" id="service_requestid" hidden value="<?php echo $service_requestsid1; ?>">

        <h5><?php echo $patient_prompts_tb1["request_name"]; ?></h5>
        <table id="<?php echo $benzersiz1 ?>" class="table table-sm table-bordered w-100 display nowrap"
               style="font-size: 13px;">
            <thead>
            <tr>
                <th>Parametre Adı</th>
                <th>Birim</th>
                <th>Referans Aralığı</th>
                <th>Son Girilen Sonuç</th>
                <th>Tekrar Çalışılma Sonuç</th>
                <th>Tekrar Çalışılma Nedeni</th>

            </tr>
            </thead>
        </table>

        <div class="row" align="right">
            <div class="col-8"></div>
            <div class="col-4">
                <button type="button" class="btn btn-danger sonuc_gir_kapat">Kapat</button>
                <button type="button" class="btn btn-success test_sonuc_kayit">Kaydet</button>
            </div>

        </div>
    </div>
    <script>
        var service_request = $('#service_requestid').val();

        var tekrar_sonuc_gir_table = $('#<?php echo $benzersiz1?>').DataTable({
            scrollY: '40vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
        })

        var pp_id = $('#patient_promptsid').val();
        $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler", {'patient_promptsid': pp_id}, function (result) {
            if (result != 2) {
                var json = JSON.parse(result);
                tekrar_sonuc_gir_table.rows([]).clear().draw();
                json.forEach(function (item) {
                    tekrar_sonuc_gir_table.row.add([item.parametre_adi, item.birimadi, item.alt_limit + ' - ' + item.ust_limit,item.tetkik_sonucu, '<input type="text" name="lab_analysis_result" class="sonuc' + item.parametre_id + ' doluluk_kontrol sonuc" data-id="' + item.parametre_id + '" pp-id="' + item.ppid + '" id="sonuc" />', ' <input type="text"  name="reason_for_rework" class="tekrar_calisma_neden' + item.parametre_id + '  tekrar_calisma_neden "  id="tekrar_calisma_neden"/>']).draw(false);
                });
            }

        });

        $("body").off("keypress", ".sonuc").on("keypress", ".sonuc", function () {
            if ($(this).hasClass("alinacak")) {

            } else {
                $(this).addClass("alinacak");
            }
        });

        $("body").off("keypress", ".tekrar_calisma_neden").on("keypress", ".tekrar_calisma_neden", function () {
            if ($(this).hasClass("tekrar_calisma_neden_val")) {

            } else {
                $(this).addClass("tekrar_calisma_neden_val");
            }
        });



        $("body").off("click", ".test_sonuc_kayit").on("click", ".test_sonuc_kayit", function (e) {

            var parametreid = [];
            var pp_id = [];
            var tekrar_calisma_neden = [];
            var sonuclar = [];

            $(".alinacak").each(function () {
                var parametre_id = $(this).attr("data-id");
                var sonuc = $(this).val();
                var ppid = $(this).attr("pp-id");
                pp_id.push(ppid);
                sonuclar.push(sonuc);
                parametreid.push(parametre_id);
            });

            $(".tekrar_calisma_neden_val").each(function () {
                var value = $(this).val();
                tekrar_calisma_neden.push(value);
            });

            // console.log(pp_id);
            // console.log(tekrar_calisma_neden);
            // console.log(parametreid);
            // console.log(sonuclar);


            $(".doluluk_kontrol").each(function () {
                if ($(this).val() == "") {
                    $(this).removeClass("sonuc_var");
                    $(this).addClass("sonuc_yok");
                } else {
                    $(this).removeClass("sonuc_yok");
                    $(this).addClass("sonuc_var");
                }
            });

            $(".tekrar_calisma_neden").each(function () {
                if ($(this).val() == "") {
                    $(this).removeClass("sonuc_var");
                    $(this).addClass("sonuc_yok");
                } else {
                    $(this).removeClass("sonuc_yok");
                    $(this).addClass("sonuc_var");
                }
            });
            if (!$(".doluluk_kontrol").hasClass("sonuc_yok") && !$(".tekrar_calisma_neden").hasClass("sonuc_yok")) {
                $.ajax({
                    url: 'ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_sonucu_tekrar_gir',
                    type: 'POST',
                    data: {
                        assay_parameterid: parametreid,
                        patient_promptsid: pp_id,
                        lab_analysis_result: sonuclar,
                        reason_for_rework: tekrar_calisma_neden,
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $('.w3').window('close');


                        $.get("ajax/laboratuvar/numune-sonuc-onaylama/modal-sonuconaylama.php?islem=laboratuvar_sonuc_ekrani_buttonlari_disabled", function (getveri) {
                            $('#sonuc_onaylama_buttons').html(getveri);
                        })

                        function format(d) {
                            // console.log(d[0].split('/')[1]);
                            return (
                                '<table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">' +
                                '<thead>' +
                                '<tr>' +
                                '<th>Alt Parametre</th>' +
                                '<th>Sonuç</th>' +
                                '<th>Birim</th>' +
                                '<th>Referans Aralığı</th>' +
                                '<th>Tekrar Çalışma Sayısı</th>' +
                                '<th>Tekrar Çalışılma Nedeni</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody class="eklenecek' + d[0].split('/')[1] + '"></tbody>' +
                                '</table>'
                            );
                        }

                        $(document).ready(function () {

                            $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay", {'service_requestsid': service_request}, function (result) {
                                if (result != 2) {
                                    var json = JSON.parse(result);
                                    test_dt.rows([]).clear().draw();
                                    json.forEach(function (item) {
                                        var islem_tarih = item.sonuc_tarihi;
                                        var islem_tarihi = moment(islem_tarih).format('DD/MM/YY H:mm:ss');
                                        if (islem_tarihi == "Invalid date") {
                                            islem_tarihi = "";
                                        }

                                        var onay_tarihi_laborant = item.laborant_onay_tarihi;
                                        var tarih_onaylayan_laborant = moment(onay_tarihi_laborant).format('DD/MM/YY H:mm:ss');
                                        if (tarih_onaylayan_laborant == "Invalid date") {
                                            tarih_onaylayan_laborant = "";
                                        }

                                        var onay_tarihi_uzman = item.lab_uzman_onay_tarihi;
                                        var uzman_onay_tarihi = moment(onay_tarihi_uzman).format('DD/MM/YY H:mm:ss');
                                        if (uzman_onay_tarihi == "Invalid date") {
                                            uzman_onay_tarihi = "";
                                        }

                                        var basilacak = "";
                                        if (item.tetkik_sonuc_durum == 0){
                                            basilacak = "<i title='Sonuç Çıkmadı' class='fa-solid fa-circle-xmark fa-lg  '  style='color: #e80c4d; margin-left: 35%; margin-right: 35%; ' ></i>";
                                        }else if (item.tetkik_sonuc_durum == 1){
                                            basilacak = "<i title='Laborant Onayladı' class='fa-solid fa-circle-check fa-lg col-9' style='color: deepskyblue; margin-left: 35%; margin-right: 35%;'></i>";
                                        }else if (item.tetkik_sonuc_durum == 2){
                                            basilacak = "<i title='Uzman Onayladı - Sonuç Çıktı' class='fa-solid fa-circle-check fa-lg col-9' style='color: green; margin-left: 35%; margin-right: 35%;'></i>";
                                        }

                                        let row = test_dt.row.add(["<i class='fa-duotone fa-circle-plus fa-lg iconudegistir acik' data-id='/"+ item.idsi +"/'></i>",basilacak, item.tetkik_adi, islem_tarihi, tarih_onaylayan_laborant, item.onaylayan_laborant, uzman_onay_tarihi, item.onaylayan_uzman, item.sonuc_aciklama, item.cihaz_adi]).draw(false).node();
                                        $(row).attr("data-id",item.idsi);
                                    });
                                }

                            });

                            $("body").off("click",".treeTable_icerik").on("click",".treeTable_icerik",function (){
                                var id = $(this).attr("data-id");
                                var tr = $(this).closest('tr');
                                var row = test_dt.row(tr);
                                if (row.child.isShown()) {
                                    row.child.hide();
                                    tr.removeClass('shown');
                                } else {
                                    row.child(format(row.data())).show();
                                    tr.addClass('shown');
                                }

                                $.get("ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_sonuc_onay_numune_listesi_detay_parametreler",{patient_promptsid:id},function (result){
                                    if (result != 2){
                                        var json = JSON.parse(result);
                                        json.forEach(function (item){

                                            var tektrar_calisma = item.tektrar_calisma_sayisi;
                                            if (tektrar_calisma == null){
                                                tektrar_calisma = "";
                                            }


                                            var yeniden_calisilma_neden = item.yeniden_calisilma_nedeni;
                                            if (yeniden_calisilma_neden == null) {
                                                yeniden_calisilma_neden = "";
                                            }


                                            $(".eklenecek" + id).append(
                                                "<tr>" +
                                                "<td>" + item.parametre_adi + "</td>" +
                                                "<td>" + item.tetkik_sonucu + "</td>" +
                                                "<td>" + item.birimadi + "</td>" +
                                                "<td>" + item.alt_limit + ' - ' + item.ust_limit + "</td>" +
                                                "<td>" + tektrar_calisma + "</td>" +
                                                "<td>" + yeniden_calisilma_neden + "</td>" +
                                                "</tr>");
                                        });
                                    }
                                });
                            });

                        });

                        $("body").off("click",".iconudegistir").on("click",".iconudegistir",function (){
                            var classvarmi=$(this).hasClass('ekle');
                            if (classvarmi) {
                                $(this).removeClass('fa-circle-minus');
                                $(this).addClass('fa-circle-plus');
                                $(this).removeClass('ekle');
                                $(this).removeClass('kapali');
                                $(this).addClass('acik');


                            } else {
                                $(this).addClass('fa-circle-minus');
                                $(this).removeClass('fa-circle-plus');
                                $(this).addClass('ekle');
                                // $(this).removeAttr("style")
                                $(this).removeClass('acik');
                                $(this).addClass('kapali');
                            }

                        });
                    }
                });
            } else {
                alertify.warning("Sonuç Veya Tekrar Nedeni Boş Kalamaz");
            }
        });

        $("body").off("click", ".sonuc_gir_kapat").on("click", ".sonuc_gir_kapat", function (e) {
            $('.w3').window('close');
        });
    </script>


<?php }
else if($islem=='modal_tup_getir'){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_tup_sec_datatb">
            <thead>
            <tr>
                <th>Tüp Adı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_tup_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_tup_sec = $('#kriter_paneli_tup_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tup_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('tup_id', aData['tup_id'])
                    .attr('tup_adi', aData['tup_adi'])
                    .attr('class', 'tup_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'tup_adi'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".tup_sec").on("click", ".tup_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.tup_sec-kaldir').removeClass("text-white");
            $('.tup_sec-kaldir').removeClass("tup_sec-kaldir");
            $('.tup_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white tup_sec-kaldir");

        });

        $("body").off("click", ".btn_tup_sec").on("click", ".btn_tup_sec", function (e) {


            var tup_adi = $('.tup_sec-kaldir').attr('tup_adi');
            var tup_id = $('.tup_sec-kaldir').attr('tup_id');
            $('#tup_grubu').val(tup_adi);
            $('#tup_grubu').attr("tup-id", tup_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php }

else if($islem=="modal_cihaz_getir"){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_cihaz_sec_datatb">
            <thead>
            <tr>
                <th>Cihaz Adı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_cihaz_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_cihaz_sec = $('#kriter_paneli_cihaz_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_cihaz_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('cihaz_id', aData['cihaz_id'])
                    .attr('cihaz_adi', aData['cihaz_adi'])
                    .attr('class', 'cihaz_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'cihaz_adi'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".cihaz_sec").on("click", ".cihaz_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.cihaz_sec-kaldir').removeClass("text-white");
            $('.cihaz_sec-kaldir').removeClass("cihaz_sec-kaldir");
            $('.cihaz_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white cihaz_sec-kaldir");

        });

        $("body").off("click", ".btn_cihaz_sec").on("click", ".btn_cihaz_sec", function (e) {


            var cihaz_adi = $('.cihaz_sec-kaldir').attr('cihaz_adi');
            var cihaz_id = $('.cihaz_sec-kaldir').attr('cihaz_id');
            $('#cihaz_adi').val(cihaz_adi);
            $('#cihaz_adi').attr("cihaz-id", cihaz_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php }

else if($islem=="modal_tetkik_getir"){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_tetkik_sec_datatb">
            <thead>
            <tr>
                <th>Tetkik Adı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_tetkik_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_tetkik_sec = $('#kriter_paneli_tetkik_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_tetkik_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('tetkik_id', aData['tetkik_id'])
                    .attr('tetkik_adi', aData['tetkik_adi'])
                    .attr('class', 'tetkik_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'tetkik_adi'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".tetkik_sec").on("click", ".tetkik_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.tetkik_sec-kaldir').removeClass("text-white");
            $('.tetkik_sec-kaldir').removeClass("tetkik_sec-kaldir");
            $('.tetkik_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white tetkik_sec-kaldir");

        });

        $("body").off("click", ".btn_tetkik_sec").on("click", ".btn_tetkik_sec", function (e) {


            var tetkik_adi = $('.tetkik_sec-kaldir').attr('tetkik_adi');
            var tetkik_id = $('.tetkik_sec-kaldir').attr('tetkik_id');
            $('#secilen_tetkik').val(tetkik_adi);
            $('#secilen_tetkik').attr("tetkik-id", tetkik_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php }

else if($islem=="modal_doktor_getir"){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_doktor_sec_datatb">
            <thead>
            <tr>
                <th>Adı Soyadı</th>
                <th>Ünvanı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_doktor_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_doktor_sec = $('#kriter_paneli_doktor_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_doktor_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('kullanici_id', aData['kullanici_id'])
                    .attr('kullanici_adi', aData['kullanici_adi'])
                    .attr('class', 'doktor_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'kullanici_adi'},
                {data: 'unvani'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".doktor_sec").on("click", ".doktor_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.doktor_sec-kaldir').removeClass("text-white");
            $('.doktor_sec-kaldir').removeClass("doktor_sec-kaldir");
            $('.doktor_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white doktor_sec-kaldir");

        });

        $("body").off("click", ".btn_doktor_sec").on("click", ".btn_doktor_sec", function (e) {


            var kullanici_adi = $('.doktor_sec-kaldir').attr('kullanici_adi');
            var kullanici_id = $('.doktor_sec-kaldir').attr('kullanici_id');
            $('#secilen_doktor').val(kullanici_adi);
            $('#secilen_doktor').attr("kullanici-id", kullanici_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php }

else if($islem=="modal_birim_getir"){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_birim_sec_datatb">
            <thead>
            <tr>
                <th>Birim Adı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_birim_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_birim_sec = $('#kriter_paneli_birim_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_birim_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('birim_id', aData['birim_id'])
                    .attr('birim_adi', aData['birim_adi'])
                    .attr('class', 'birim_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'birim_adi'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".birim_sec").on("click", ".birim_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.birim_sec-kaldir').removeClass("text-white");
            $('.birim_sec-kaldir').removeClass("birim_sec-kaldir");
            $('.birim_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white birim_sec-kaldir");

        });

        $("body").off("click", ".btn_birim_sec").on("click", ".btn_birim_sec", function (e) {


            var birim_adi = $('.birim_sec-kaldir').attr('birim_adi');
            var birim_id = $('.birim_sec-kaldir').attr('birim_id');
            $('#isteyen_birim').val(birim_adi);
            $('#isteyen_birim').attr("birim-id", birim_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php }

else if($islem=="modal_laboratuvar_getir"){?>
    <div class="col-12">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kriter_paneli_laboratuvar_sec_datatb">
            <thead>
            <tr>
                <th>Laboratuvar Adı</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="mt-2"></div>
    <div class="row" align="right">
        <div class="col-8"></div>
        <div class="col-4">
            <button type="button" class="btn btn-danger btn_window_kapat">Kapat</button>
            <button type="button" class="btn btn-success btn_laboratuvar_sec">Kaydet</button>
        </div>

    </div>
    <script>
        var kriter_paneli_laboratuvar_sec = $('#kriter_paneli_laboratuvar_sec_datatb').DataTable({
            scrollY: '37vh',
            scrollX: true,
            "info": false,
            "paging": false,
            "searching": false,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/laboratuvar/numune-sonuc-onaylama/sql-sonuconaylama.php?islem=sql_laboratuvar_listesi',
                processing: true,
                method: 'GET',
                dataSrc: '',
            },

            "fnRowCallback": function (nRow, aData) {
                $(nRow)
                    .attr('lab_id', aData['lab_id'])
                    .attr('lab_adi', aData['lab_adi'])
                    .attr('class', 'lab_sec')
            },

            "initComplete": function (settings, json) {
            },

            columns: [
                {data: 'lab_adi'},
            ],
        })

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function (e) {
            $('.kriter_paneli_window').window('close');
        });

        $("body").off("click", ".lab_sec").on("click", ".lab_sec", function (e) {

            $(this).css('background-color') != 'rgb(147,203,198)';
            $('.lab_sec-kaldir').removeClass("text-white");
            $('.lab_sec-kaldir').removeClass("lab_sec-kaldir");
            $('.lab_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white lab_sec-kaldir");

        });

        $("body").off("click", ".btn_laboratuvar_sec").on("click", ".btn_laboratuvar_sec", function (e) {


            var lab_adi = $('.lab_sec-kaldir').attr('lab_adi');
            var lab_id = $('.lab_sec-kaldir').attr('lab_id');
            $('#secilen_laboratuvar').val(lab_adi);
            $('#secilen_laboratuvar').attr("lab-id", lab_id);

            $('.kriter_paneli_window').window('close');
        });
    </script>
<?php } ?>