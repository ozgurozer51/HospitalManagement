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
$kullanicid = $_SESSION['id'];
$islem = $_GET['islem'];

if ($islem == "konsultasyonbody") {

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["protocol_no"]);

    if ($hastakayit['mother_tc_identity_number'] == '') {
        $patients = singular("patients", "id", $hastakayit['patient_id']);
    } else {
        $hastalarid = $hastakayit['patient_id'];
        $patients = tek("SELECT * FROM patients WHERE id='$hastalarid'");
    }

    $protokolno = $hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol", $protokolno);
    $izin = singularactive("patient_permission", "protocol_number", $protokolno);
    $TANiSORGULA = tek("SELECT id FROM patient_record_diagnoses WHERE protocol_number='{$protokolno}' and status='1' ORDER BY id DESC");
    if ($TANiSORGULA["id"] == "") { ?>

        <div class="warning-definitions mt-5">
            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                <h5 class="text-warning">Tani Uyarisi</h5>
                <p>Konsültasyon işlemi yapmak için tanı giriniz..</p>
            </div>
        </div>

        <?php exit(); } ?>

    <div class="easyui-layout" data-options="fit:true">

        <form id="konsultasyon-form" action="javascript:void(0);">

        <div data-options="region:'west',split:true ,  hideCollapsedContent:false" title="KONSÜLTASYON LİSTESİ" style="width:23%; height:100%; padding:2px">

            <table id="table-konsultasyon" class="easyui-datagrid" data-options="singleSelect:true , showRefresh:true, fit:true , pagination:true , pageSize:50 " style="width:100%; height:100%; font-size: 13px;" url="ajax/konsultasyon/konsultasyon.php?islem=konsultasyon-listesi&protocol_no=<?php echo $_GET['protocol_no']; ?>&patient_id=<?php echo $_GET['patient_id']; ?>" iconCls="icon-save" rownumbers="true">
                <thead field="id">
                <tr>
                    <th field="name_surname">Kullanıcı</th>
                    <th field="insert_datetime">Eklenme Tarihi</th>
                </tr>
                </thead>
            </table>
        </div>

        <div data-options="region:'center'" title="KONSÜLTASYON İSTEK NOTU" style="width:35%; height:100%; padding:2px">

                <input type="hidden" name="transaction_reference_number" value="">
                <input class="easyui-textbox" style="width:100%; padding: 5px;" label="Tarih/Saat:" name="insert_datetime" readonly labelWidth="150" value="<?php echo nettarih($simdikitarih); ?>" readonly>

                <div style="display: flex;">
                    <label style="width: 150px;">Konsültasyon D.</label>
                    <input class="easyui-radiobutton konsultasyonempty mt-2" label="Acil:" labelWidth="75" value="1" id="id_infor" name="consultation_type">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="easyui-radiobutton konsultasyonempty mt-2" label="Yerinde:" labelWidth="75" value="2" id="operat" name="consultation_type">
                </div>

                <input class="easyui-textbox birim-listesi"  name="department_name"    label="Birim:" labelWidth="150" style="width:100%;">
                <input class="easyui-textbox doktor-listesi" name="name_surname"  label="Doktor:" labelWidth="150" style="width:100%;">
                <input class="easyui-textbox" name="request_reason" style="width:100%; height:100px;" data-options="multiline:true" label="Konsültasyon Sebebi:" labelWidth="150">
                <input type="hidden" class="form-control btnbirimsec_id" name="new_unit">

            <footer>
                <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'">Onayla</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'">Güncelle</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-cancel'">Sil</a>
                </div>
            </footer>

        </div>

<!--------------------------------------------------------------------------------------------------------------------->

        <div data-options="region:'east' , split:true , hideCollapsedContent:false" title="KONSÜLTASYON SONUÇ NOTU" style="width:35%; height: 100%; padding:2px;">

                <input class="easyui-textbox" label="Tarhi/Saat:" labelWidth="150" value="" readonly style="width: 100%;">
                <input class="easyui-textbox" label="Konsültasyon Durumu:" labelWidth="150" value="" readonly style="width: 100%;">
                <input class="easyui-textbox" label="İsteyen Birim:" labelWidth="150" value="" readonly style="width: 100%;">
                <input class="easyui-textbox" label="Tanı:" labelWidth="150" value="" readonly style="width: 100%; height: 50px;">
                <input class="easyui-textbox" label="Konsültasyon Notu:" multiline="true" labelWidth="150" value="" readonly style="width:100%; height:100px;">

            <footer>
                <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'">Onayla</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-edit'">Güncelle</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-cancel'">Sil</a>
                </div>
            </footer>

        </div>

        </form>
    </div>

    <div class="w-doktor-listesi">

        <div class="warning-definitions mt-5" id="konst-birm-alert">
            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                <h5 class="text-warning">Uyarı</h5>
                <p>Önce Birim Seçiniz..</p>
            </div>
        </div>

        <table id="doktorlar-konsultasyon">
            <thead field="id">
            <tr>
                <th field="name_surname">ADI</th>
                <th field="tc_id">TC</th>
            </tr>
            </thead>
        </table>

    </div>

    <div class="w-birim-listesi" id="w-birim-listesi">

        <table id="birimler-konsultasyon" class="easyui-datagrid" data-options="singleSelect:true , showRefresh:true, fit:true , pagination:true , pageSize:50 " style="width:100%; height:100%;" url="ajax/konsultasyon/konsultasyon.php?islem=yetkili-birim-listesi" rownumbers="true">
            <thead field="id">
            <tr>
                <th field="id">İd</th>
                <th field="department_name">Birim Adı</th>
            </tr>
            </thead>
        </table>

    </div>

    <input class="secilen-birim-id" type="text">
    <input class="secilen-doktor-id" type="text">

    <script>
        $(document).ready(function () {
            $('#table-konsultasyon').datagrid({
                onSelect: function (index, row) {
                    $('#konsultasyon-form').form('load', 'ajax/konsultasyon/konsultasyon.php?islem=hastaya-eklenen-konsultasyon-getir&consultation_id='+row.consultation_id+'');
                }
            });

            $('.w-birim-listesi').dialog({
                onClose: function () {
                },
                cache: true,
                modal: true,
                height: '100%',
                width: 600,
                closed: true,
                iconCls: 'fa-solid fa-list',
            });

            $('#birimler-konsultasyon').datagrid({

                onSelect: function (index, row) {
                    birim_id = row.id;
                    $('.secilen-birim-id').val(row.id);
                    $('.birim-listesi').textbox('setValue', row.department_name);
                    $('.w-birim-listesi').dialog('close');

                    $('#konst-birm-alert').remove();

                    $('#doktorlar-konsultasyon').datagrid({
                        url: 'ajax/konsultasyon/konsultasyon.php?islem=birime-yetkili-doktorlar&birim_id=' + birim_id + '',
                        singleSelect: true,
                        fit: true,
                        pagination: false,
                        showRefresh: true,
                        autoLoad: true,
                        onBeforeLoad: function () {
                            var opts = $(this).datagrid('options');
                            return opts.autoLoad;
                        },

                        onSelect: function (index, row) {
                            $('.w-doktor-listesi').dialog('close');
                            $('.doktor-listesi').textbox('setValue', row.name_surname);
                            $('.secilen-doktor-id').val(row.id);
                        }
                    });

                }
            });

            $('.birim-listesi').textbox({
                buttonText: "<i class='fa-solid fa-braille'></i>",
                disabled: false,
                onClickButton: function () {

                    $('.w-birim-listesi').dialog({
                        title: 'Yetkili Bulunulan Doktor Listesi',
                        closed: false
                    });

                }
            });

            $('.w-doktor-listesi').dialog({
                onClose: function () {

                },
                cache: true,
                modal: true,
                height: '100%',
                width: 600,
                closed: true,
                iconCls: 'fa-solid fa-list',
            });

            $('.doktor-listesi').textbox({
                buttonText: "<i class='fa-solid fa-braille'></i>",
                onClickButton: function () {

                    $('.w-doktor-listesi').dialog({
                        title: 'Seçilen Birime Yetkili Doktorlar',
                        closed: false
                    });

                }
            });

            $(".konsultasyonpdf").off().on("click", function () {
                $('#formkonsultasyonpdf').css("display", "block");
                var element = $('#formkonsultasyonpdf').html();
                var file_name = 'konsultasyon-' + "<?php echo '(' . $patients['patient_name'] . '-' . $patients['patient_surname'] . ')'; ?>"
                var opt = {
                    filename: file_name + '.pdf',
                    image: {type: 'jpeg', quality: 0.98},
                    html2canvas: {scale: 2},
                    jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
                };
                html2pdf(element, opt);
                $('#formkonsultasyonpdf').css("display", "none");
            });

            $(".konsultasyoninsert").off().on("click", function () {
                var say = 0;
                $('.konsultasyonempty').filter(function () {
                    if (this.value == '') {
                        say++;
                    }
                });

                if (say > 0) {
                    alertify.error('Boş alanları doldurun');
                } else {
                    var gonderilenform = $("#formkonsultasyon").serialize();
                    document.getElementById("formkonsultasyon").reset();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/konsultasyon/konsultasyon.php?islem=konsultasyoninsert',
                        data: gonderilenform,
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $_GET["getir"] ?>";

                        }
                    });
                }
            });

            $(".konsultasyonupdate").off('click').on("click", function () {
                var gonderilenform = $("#formkonsultasyon").serialize();
                var getir = "<?php echo $_GET["getir"]; ?>";
                $.ajax({
                    type: 'POST',
                    url: 'ajax/konsultasyon/konsultasyon.php?islem=konsultasyonupdate',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonlistesi", {getir: getir}, function (g) {
                            $(".konsultasyonlistesi-<?php echo $protokolno; ?>").html(g);
                        });
                    }
                });
            });

            $(".konsultasyoninsertsonuc").off('click').on("click", function () {
                var gonderilenform = $("#formkonsultasyonsonuc").serialize();
                var getir = "<?php echo $_GET["getir"]; ?>";
                $.ajax({
                    type: 'POST',
                    url: 'ajax/konsultasyon/konsultasyon.php?islem=konsultasyoninsertsonuc',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonlistesi", {getir: getir}, function (g) {
                            $(".konsultasyonlistesi-<?php echo $protokolno; ?>").html(g);
                        });
                        $(".konsultasyonbtn").prop('hidden', true);
                        $(".guncellesilkonsultasyon").prop('hidden', true);
                        $(".konsultasyonkaydet").prop('hidden', false);
                        $(".konsultasyoninsertsonuc").prop('hidden', true);
                        var protokol = "<?php echo $_GET["getir"]; ?>";
                        $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonyeniden", {getir: protokol}, function (getVeri) {
                            $(".konsultasyonbody-<?php echo $protokolno; ?>").html(getVeri);
                        });
                    }
                });
            });

            var getir = "<?php echo $_GET["getir"] ?>";
            $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonlistesi", {getir: getir}, function (g) {
                $(".konsultasyonlistesi-<?php echo $protokolno; ?>").html(g);
            });
            var protokol = "<?php echo $_GET["getir"]; ?>";
            $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonyeniden", {getir: protokol}, function (getVeri) {
                $(".konsultasyonbody-<?php echo $protokolno; ?>").html(getVeri);
            });


            $(".konsultasyonyeniden").click(function () {
                $(".guncellesilkonsultasyon").prop('hidden', true);
                $(".konsultasyonkaydet").prop('hidden', false);
                var getir = $(this).attr('id');
                $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonyeniden", {getir: getir}, function (getVeri) {
                    $(".konsultasyonbody-<?php echo $protokolno; ?>").html(getVeri);

                    $(".konsultasyonbtn").prop('hidden', true);
                    $(".konsultasyonbodyguncelle").removeClass('aktif-satir');
                });
            });


            $(".konsultasyondelete").click(function () {
                var id = $(".aktif-satir").attr('id');
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='Silme Nedeni Giriniz'>", function () {

                    var delete_detail = $('#personel_delete_detail').val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/konsultasyon/konsultasyon.php?islem=konsultasyonbtndelete',
                        data: {id: id, delete_detail: delete_detail},
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $_GET["getir"]; ?>";
                            $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonlistesi", {getir: getir}, function (g) {
                                $(".konsultasyonlistesi-<?php echo $protokolno; ?>").html(g);
                                $.get("ajax/konsultasyon/konsultasyon.php?islem=konsultasyonyeniden", {getir: getir}, function (getVeri) {
                                    $(".konsultasyonbody-<?php echo $protokolno; ?>").html(getVeri);
                                });
                                $(".guncellesilkonsultasyon").prop('hidden', true);
                                $(".konsultasyonkaydet").prop('hidden', false);
                                $("#konsultasyon123").trigger("click");
                            });
                        }
                    });

                }, function () {
                    alertify.warning('Silme işleminden Vazgeçtiniz')
                }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Silme işlemini Onayla"});
            });

        });
    </script>

<?php } elseif ($islem == "konsultasyon-listesi") {

    echo json_encode(sql_select("
        Select users.name_surname, consultation.insert_datetime  , consultation.id as consultation_id
from consultation
         left join users on users.id = consultation.insert_userid
WHERE consultation.patient_id =$_GET[patient_id] AND consultation.status='1' "));


} elseif ($islem == "konsultasyonyeniden") {

    if ($_GET['konsultasyonid'] != '') {
        $konsultasyonbilgi = singular("consultation", "id", $_GET["konsultasyonid"]);
        $yatis = singularactive("patient_registration", "protocol_number", $konsultasyonbilgi['protocol_number']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    } else {
        $yatis = singularactive("patient_registration", "protocol_number", $_GET['getir']);
        $patients = singularactive("patients", "id", $yatis['patient_id']);
    }
    $y_birim = $yatis['service_id'];
    $y_doktor = $yatis['service_doctor'];
    $birim = singularactive("units", "id", $y_birim);
    $protokol_no = $yatis['protocol_number'];
    if ($y_birim == '') {
        $now_unit = $yatis['outpatient_id'];
        $now_doctor = $yatis['doctor'];
    } else {
        $now_unit = $yatis['service_id'];
        $now_doctor = $yatis['service_doctor'];
    } ?>

    <?php $random_sayi = uniqid(); ?>
    <div class="modal fade " id="modal-<?php echo $random_sayi; ?>">
        <div class="modal-dialog" id="modal-tanim-icerik-<?php echo $random_sayi; ?>">Ana Modal</div>
    </div>

    <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6">

        </div>
    </div>

    <form id="formkonsultasyonpdf" style="display: none" enctype="text/plain" class="form-control">
        <div class="m-1">
            <div class="border border-dark">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h6>Konsültasyon Formu</h6>
                </div>
            </div>
            <div class="border border-dark mt-2 px-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Adı Soyadı:</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['patient_name'] . ' ' . $patients['patient_surname']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">T.C. Kimlik No:</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['tc_id']; ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Baba Adı:</label>
                            <div class="col-md-6">
                                <h6><?php echo $patients['father']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Yaş/Cinsiyet:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    $simdi = explode(" ", $simdikitarih);
                                    $simditarih = $simdi['0'];
                                    $dogum = $patients['birth_date'];
                                    $YAS = ikitariharasindakiyilfark($simdikitarih, $dogum);
                                    echo $YAS . ' / ' . $patients['gender'];
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Protokol Numarası:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $yatis['protocol_number'] ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Form Numarası:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php echo $konsultasyonbilgi['transaction_reference_number']; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İsteyen Birim:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    echo birimgetirid($konsultasyonbilgi['now_unit']);
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İsteyen Doktor:</label>
                            <div class="col-md-6">
                                <h6><?php echo kullanicigetirid($konsultasyonbilgi['now_doctor']); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İstenen Birim:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    echo birimgetirid($konsultasyonbilgi['new_unit']);
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">İstenen Doktor:</label>
                            <div class="col-md-6">
                                <h6><?php echo kullanicigetirid($konsultasyonbilgi['new_doctor']); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border border-dark mt-2 px-3">
                <div align="center">
                    <h5>Konsültasyon İstek</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Konsültasyon Durumu:</label>
                            <div class="col-md-6">
                                <h6><?php if ($konsultasyonbilgi['consultation_type'] == 1) {
                                        echo 'Acil';
                                    } elseif ($konsultasyonbilgi['consultation_type'] == 2) {
                                        echo 'Yerinde';
                                    } else {
                                        echo 'Belirtilmemiş';
                                    } ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <label class="form-label col-md-6">Konsültasyon İstek T.:</label>
                            <div class="col-md-6">
                                <h6>
                                    <?php
                                    if ($konsultasyonbilgi['insert_datetime'] != '') {
                                        echo nettarih($konsultasyonbilgi['insert_datetime']);
                                    } else {
                                        echo $simdikitarih;
                                    }

                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="form-label col-md-12">Konsültasyon İstek Nedeni:</label>
                    <div class="col-md-12">
                        <h6><?php echo $konsultasyonbilgi['request_reason']; ?></h6>
                    </div>
                </div>
                <div align="right" style="padding-right:200px;margin-top:20px;margin-bottom:50px">
                    <div>
                        <label for="basicpill-firstname-input" class="form-label">Konsültasyon İsteyen Dr.</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label">İmza/Kaşe</label>
                    </div>

                </div>
            </div>
            <div class="border border-dark mt-2 px-3">
                <div align="center">
                    <h5>Konsültasyon Sonuç</h5>
                </div>
                <div class="row">
                    <label class="form-label col-md-12">Tanı:</label>
                    <div class="col-md-12">
                        <ul>
                            <?php
                            if ($konsultasyonbilgi['id']) {
                                $metin = '';
                                $hello = verilericoklucek("SELECT * FROM patient_record_diagnoses inner join diagnoses on patient_record_diagnoses.diagnosis_id = diagnoses.id
                            where patient_record_diagnoses.protocol_number='{$protokol_no}' and patient_record_diagnoses.status='1'");
                                foreach ((array)$hello as $rowa) {
                                    echo "<li><h6>";
                                    echo '* ';
                                    echo $rowa['diagnosis_code'] . '-' . $rowa['diagnoses_name'];
                                    echo "</h6></li>";
                                    echo "&#13;&#10;";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="form-label col-md-3">Konsültasyon Sonuç T.:</label>
                            <div class="col-md-9">
                                <h6>
                                    <?php
                                    if ($konsultasyonbilgi['update_datetime'] != '') {
                                        echo nettarih($konsultasyonbilgi['update_datetime']);
                                    } else {
                                        echo $simdikitarih;
                                    }

                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="form-label col-md-12">Konsültasyon Notu:</label>
                    <div class="col-md-12">
                        <h6><?php echo $konsultasyonbilgi['consultation_conclusion']; ?></h6>
                    </div>
                </div>
                <div align="right" style="padding-right:200px;margin-top:20px;margin-bottom:50px">
                    <div>
                        <label for="basicpill-firstname-input" class="form-label">Konsültasyon Dr.</label>
                    </div>
                    <div>
                        <label for="basicpill-firstname-input" class="form-label">İmza/Kaşe</label>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <form id="formkonsultasyonprint" style="display: none" enctype="text/plain" class="form-control">
        <div>
            <div style="width:100%;border:1px solid;margin-bottom:10px;">
                <div align="center">
                    <h4>Mersin Devlet Hastanesi</h4>
                </div>
                <div align="center">
                    <h5>Konsültasyon Formu</h5>
                </div>
            </div>
            <table style="width:100%;border:1px solid;">
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Adı Soyadı:</td>
                    <td><?php echo $patients['patient_name'] . ' ' . $patients['patient_surname']; ?></td>
                    <td style="font-weight: bold;">T.C. Kimlik No:</td>
                    <td><?php echo $patients['tc_id']; ?></td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Baba Adı:</td>
                    <td><?php echo $patients['father']; ?></td>
                    <td style="font-weight: bold;">Anne Adı:</td>
                    <td><?php echo $patients['mother']; ?></td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Yaş:</td>
                    <td><?php
                        $simdi = explode(" ", $simdikitarih);
                        $simditarih = $simdi['0'];
                        $dogum = $patients['birth_date'];
                        $YAS = ikitariharasindakiyilfark($simdikitarih, $dogum);
                        echo $YAS;
                        ?>
                    </td>
                    <td style="font-weight: bold;">Cinsiyet:</td>
                    <td><?php
                        echo $patients['gender'];
                        ?>
                    </td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">Protokol Numarası:</td>
                    <td><?php echo $yatis['protocol_number'] ?></td>
                    <td style="font-weight: bold;">Form Numarası:</td>
                    <td><?php echo $konsultasyonbilgi['transaction_reference_number']; ?></td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İsteyen Birim:</td>
                    <td><?php echo birimgetirid($konsultasyonbilgi['now_unit']); ?></td>
                    <td style="font-weight: bold;">İsteyen Doktor:</td>
                    <td><?php echo kullanicigetirid($konsultasyonbilgi['now_doctor']); ?></td>
                </tr>
                <tr style="margin-top:2px;">
                    <td style="font-weight: bold;">İstenen Birim:</td>
                    <td><?php echo birimgetirid($konsultasyonbilgi['new_unit']); ?></td>
                    <td style="font-weight: bold;">İstenen Doktor:</td>
                    <td><?php echo kullanicigetirid($konsultasyonbilgi['new_doctor']); ?></td>
                </tr>
            </table>

            <table class="table table-border" style="margin-top:5px;border:1px solid; width:100%">
                <tr>
                    <td colspan="4" style="font-weight: bold;text-align: center">Konsültasyon İstek:</td>
                </tr>
                <tr>
                    <td colspan="1" style="font-weight: bold;width:200px;">Konsültasyon Durumu:</td>
                    <td colspan="3"><?php if ($konsultasyonbilgi['consultation_type'] == 1) {
                            echo 'Acil';
                        } elseif ($konsultasyonbilgi['consultation_type'] == 2) {
                            echo 'Yerinde';
                        } else {
                            echo 'Belirtilmemiş';
                        } ?></td>
                    <td colspan="1" style="font-weight: bold;width:200px;">Konsültasyon İstek T.:</td>
                    <td colspan="3"><?php echo $konsultasyonbilgi['insert_datetime']; ?></td>
                </tr>

                <tr>
                    <td colspan="8" style="font-weight: bold; ">Konsültasyon İstek Nedeni:</td>
                </tr>
                <tr>
                    <td colspan="8"><?php echo $konsultasyonbilgi['request_reason'] ?></td>
                </tr>
                <tr style="height:20px">
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="1" style="font-weight: bold;">Konsültasyon İsteyen Dr.</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="1" style="font-weight: bold;">İmza/Kaşe</td>
                </tr>
                <tr style="height:40px">
                    <td></td>
                </tr>
            </table>

            <table style="margin-top:5px;border:1px solid; width:100%">
                <tr>
                    <td colspan="4" style="font-weight: bold;text-align: center">Konsültasyon Sonuç:</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; ">Tanı:</td>
                </tr>
                <?php
                if ($konsultasyonbilgi['id']) {
                    $metin = '';
                    $hello = verilericoklucek("SELECT * FROM patient_record_diagnoses inner join diagnoses on patient_record_diagnoses.diagnosis_id = diagnoses.id
                            where patient_record_diagnoses.protocol_number='{$protokol_no}' and patient_record_diagnoses.status='1'");
                    foreach ((array)$hello as $rowa) {
                        echo "<tr><td>";
                        echo '* ';
                        echo $rowa['diagnosis_code'] . '-' . $rowa['diagnoses_name'];
                        echo "</td></tr>";
                    }
                }
                ?>
                <tr style="margin-top:2px;">
                    <td colspan="1" style="font-weight: bold;width:200px;">Konsültasyon Sonuç T.:</td>
                    <td colspan="1"><?php echo $konsultasyonbilgi['update_datetime']; ?></td>
                </tr>
                <tr>
                    <td colspan="6" style="font-weight: bold; ">Konsültasyon Notu:</td>
                </tr>
                <tr>
                    <td colspan="6"><?php echo $konsultasyonbilgi['consultation_conclusion'] ?></td>
                </tr>
                <tr style="height:20px">
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="1" style="font-weight: bold; ">Konsültasyon Dr.</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="1" style="font-weight: bold; ">İmza/Kaşe</td>
                </tr>
                <tr style="height:40px">
                    <td></td>
                </tr>
            </table>
        </div>
    </form>

<?php } elseif ($islem == "yetkili-birim-listesi") {

    $sql = sql_select("select units.department_name, units.id
                      from units where units.status = 1 and units.unit_type = 0");
    echo json_encode($sql);

} elseif ($islem == "birime-yetkili-doktorlar") {

    echo json_encode(sql_select("select users.name_surname , users.id , users.tc_id
    from users
             inner join users_outhorized_units on users_outhorized_units.userid = users.id
    where users_outhorized_units.status = 1 and users_outhorized_units.unit_id=$_GET[birim_id]"));

} elseif ($islem == "konsultasyoninsert") {
    if ($_POST) {
        $yatisprotokol = $_POST['protocol_number'];
        $hastakayit = singular("patient_registration", "protocol_number", $yatisprotokol);
        $hasta_id = $hastakayit['patient_id'];
        $hastalar = singular("patients", "id", $hasta_id);
        $_POST["hastakayitlar"]["hospitalization_protocol"] = $yatisprotokol;

        $_POST["hastakayitlar"]["outpatient_id"] = $_POST['new_unit'];
        $_POST["hastakayitlar"]["row_number"] = $hastakayit['row_number'];
        $_POST["hastakayitlar"]["provision_number"] = $hastakayit['provision_number'];
        $_POST["hastakayitlar"]["patient_admission_type"] = $hastakayit['patient_admission_type'];
        $_POST["hastakayitlar"]["doctor"] = $_POST['new_doctor'];
        $_POST["hastakayitlar"]["patient_id"] = $hastakayit['patient_id'];
        $_POST["hastakayitlar"]["tc_id"] = $hastalar['tc_id'];
        $hasta_kayit_say = tek("select  MAX(protocol_number) as protokol  from patient_registration  ");
        $_POST["hastakayitlar"]["protocol_number"] = intval($hasta_kayit_say['protokol']) + 1;
        $_POST['protocol_number'] = intval($hasta_kayit_say['protokol']) + 1;
        $ekle1 = groupdirektekle("patient_registration", $_POST['hastakayitlar'], "", "hastakayitlar");
        var_dump("hastakayıtekle :" . $ekle1);
        $hizmetgetir = tek("select process_role.*,processes_price.price from 
        process_role inner join processes_price on process_role.id=processes_price.processes_id 
        where process_role.official_code='520010'");
        $_POST["hizmetler"]["request_date"] = $simdikitarih;
        $_POST["hizmetler"]["request_code"] = $hizmetgetir['official_code'];
        $_POST["hizmetler"]["request_name"] = $hizmetgetir['process_name'];
        $_POST["hizmetler"]["piece"] = 1;
        $_POST["hizmetler"]["fee"] = $hizmetgetir['price'];
        $_POST["hizmetler"]["doctor_id"] = $_POST['new_doctor'];
        $_POST["hizmetler"]["study_id"] = $hizmetgetir['id'];
        $_POST["hizmetler"]["request_userid"] = $_POST['new_doctor'];
        $_POST["hizmetler"]["protocol_number"] = $_POST['protocol_number'];
        $_POST["hizmetler"]["group_id"] = $hizmetgetir['process_group_id'];
        $_POST["hizmetler"]["result_status"] = 1;
        $_POST["hizmetler"]["patient_tc"] = $hastalar['tc_id'];
        $_POST["hizmetler"]["payment_completed"] = 0;
        $_POST["hizmetler"]["patient_id"] = $hastakayit['patient_id'];
        $_POST["hizmetler"]["prompts_type"] = 3;
        $_POST["hizmetler"]["unit_id"] = $_POST['new_unit'];

        $_POST["hastaistem"]["service_request_type"] = 3;
        $_POST["hastaistem"]["protocol_id"] = $_POST["protocol_number"];
        $_POST["hastaistem"]["patient_id"] = $hastakayit['patient_id'];
        $_POST["hastaistem"]["prompt_status"] = 1;

        $hasistem = groupdirektekle("patient_service_requests", $_POST['hastaistem'], "", "hastaistem");
        var_dump("hizmetlergrup :" . $hasistem);

        $_POST["hizmetler"]["service_requestsid"] = islemtanimsoneklenen("patient_service_requests");
        $_POST["hizmetler"]["analysis_result_status"] = 0;


        $hizmetler = groupdirektekle("patient_prompts", $_POST['hizmetler'], "", "hizmetler");
        var_dump("hizmetler :" . $hizmetler);
        unset($_POST['hastakayitlar']);
        unset($_POST['hizmetler']);
        unset($_POST['hastaistem']);

        if ($ekle1) {
            $yatissekle = direktekle("consultation", $_POST);
            var_dump("konsultasyon :" . $yatissekle);

            echo '----------------------------------';
            var_dump($_POST);
            if ($yatissekle) { ?>
                <script>
                    alertify.success('işlemi Başarili');
                </script>
            <?php } else { ?>
                <script>
                    alertify.error('işlemi Başarisiz');
                </script>

            <?php }
        } else {
            ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

            <?php
        }
    }
} elseif ($islem == "konsultasyonupdate") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("consultation", "id", $id, $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "konsultasyoninsertsonuc") {
    if ($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];


        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("consultation", "id", $id, $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
} elseif ($islem == "konsultasyonbtndelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];
        $konsultasyon = singularactive("consultation", "id", $id);
        $protokolno = $konsultasyon['protocol_number'];
        $vezneguncelle = canceldetail("consultation", "id", $id, $detay, $silme, $tarih);
        $vezneguncelle2 = canceldetail("patient_registration", "protocol_number", $protokolno, $detay, $silme, $tarih);
        var_dump('islem1:' . $vezneguncelle);
        var_dump('islem2:' . $vezneguncelle2);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}else if($islem == 'hastaya-eklenen-konsultasyon-getir'){

    $sql = sql_select("
         select  consultation.*  , units.department_name , users.name_surname    
         from consultation
                  inner join units on units.id = consultation.now_unit
                  inner join users on users.id = consultation.now_doctor
         where consultation.id =$_GET[consultation_id]");

    $sql_r = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($sql), ENT_NOQUOTES));
    echo $sql_r;

}

