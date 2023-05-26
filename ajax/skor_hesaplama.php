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

if ($islem == "skor_hesaplama") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>
        <form id="formapache" action="javascript:void(0);">
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Yaş</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" name="protokol_number"
                                   value="<?php echo $hastakayit['protocol_number']; ?>">
                            <input type="hidden" class="form-control" name="patient_number"
                                   value="<?php echo $hastakayit['patient_id']; ?>">
                            <select class="form-select apache_change" name="age" id="yas">
                                <option data-id="" value="">Yaş seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yas' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Ateş">Vücut Isısı
                            (°C)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="body_temperature" id="vucut_isi">
                                <option data-id="" value="">Vücut Isısı (°C) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sıcaklık' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3"
                               title="Ortalama arter basıncı(mmHg)">Ortalama Kan
                            Basıncı(mmHg)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="blood_pressure"
                                    id="kan_basinci">
                                <option data-id="" value="">Ortalama Kan Basıncı(mmHg) seçiniz..
                                </option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Ortalama arter basıncı(mmHg)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3"
                               title="Kalp hızı (nabız / dakika)">Kalp
                            Hızı(nabız/dk)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="heart_rate" id="kalp_hizi">
                                <option data-id="" value="">Kalp hızı (nabız / dakika) seçiniz..
                                </option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kalp hızı (nabız / dakika)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Solunum hızı (dk)">Solunum
                            Hızı (dk)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="respiratory_rate"
                                    id="solum_hizi">
                                <option data-id="" value="">Solunum hızı (dk) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Solunum hızı (/ dak)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3"
                               title="FiO2≥0.5 olduğundan Alveolar arteriyel gradyan DO2">FIO2
                            >= 0,5 ise (A-a) O2</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="fi02" id="fi02">
                                <option data-id="" value="">FIO2 >= 0,5 ise (A-a) O2 seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='FiO2≥0.5 olduğundan Alveolar arteriyel gradyan DO2' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="FiO2 <0.5 yani PaO2">FIO2
                            < 0,5 ise PaO2</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="pa02" id="pa02">
                                <option data-id="" value="">FIO2 < 0,5 ise PaO2 seçiniz..</option>

                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='FiO2 <0.5 yani PaO2' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Arteriyel pH (seçim)">Arteriyel
                            pH</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="artelial" id="arteriyel">
                                <option data-id="" value="">Arteriyel pH seçiniz..</option>
                                <?php $skorsicaklik = singular("score_physiology", "physiological_values", 'Arteriyel pH (seçim)'); ?>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Arteriyel pH (seçim)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Venöz HCO3(mEq/L)">AKG yok
                            ise Serum HCO3-(mmol/L)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="no_akg_serum" id="akg">
                                <option data-id="" value="">Serum HCO3-(mmol/L) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Venöz HCO3(mEq / L)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Sevk Türü">Serum Sodyum
                            (mmol/L)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="serum_sodium" id="sr_sodyum">
                                <option data-id="" value="">Serum Sodyum (mmol/L) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sodyum (mEq / L)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Potasyum (mEq/L)">Serum
                            kreatinin (mg/dL)Akut böbrek yetmezliği var</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="serum_kre_yes"
                                    id="serum_krevar">
                                <option data-id="" value="">Serum Potasyum (mmol/L) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Serum kreatinin (mg / dL)Akut böbrek yetmezliği var' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3"
                               title="Serum kreatinin (mg / dL)Akut böbrek yetmezliği">Serum
                            kreatinin (mg/dL)Akut böbrek yetmezliği yok</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="serum_kre_no"
                                    id="serum_kreyok">
                                <option  data-id="" value="">Serum Kreatin seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Serum kreatinin (mg / dL)Akut böbrek yetmezliği yok' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Hematokrit (%)">Hematokrit
                            (%)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="hematokrit" id="hematokrit">
                                <option data-id="" value="">Hematokrit (%) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Hematokrit (%)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Sevk Türü">W.B.C (x103/
                            mm3 )</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="wbc" id="wbc">
                                <option  data-id="" value="">W.B.C (x103/ mm3 ) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Lökosit (/ mm3 x 1000)Gerçek Glasgow Koma skoru(GK' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Potasyum (mEq/L)">Serum
                            Potasyum (mmol/L) (mEq/L)</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="serum_pt" id="serpos">
                                <option  data-id="" value="">Serum Potasyum (mmol/L) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Potasyum (mEq / L)' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Potasyum (mEq/L)">Glasgow
                            Koma Skore</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="glasgow" id="glas">
                                <option data-id="" value="">Glasgow Koma Skore seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Glasgow Koma Skore' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 row">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3" title="Hematokrit (%)">Kronik
                            Organ Yetmezilği</label>
                        <div class="col-md-9">
                            <select class="form-select apache_change" name="chronic_organ_failure"
                                    id="kronik">
                                <option data-id="" value="">Kronik Organ Yetmezilği seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kronik Organ Yetmezilği' AND definition_module='APACHE II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control apache" name="conclusion_apache2">
                <input type="hidden" class="form-control olumoran" name="death_rate">
            </div>
        </form>

            <div class="modal-footer py-0">
                <div class="row mx-1">
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-6 mt-2">Ölüm Oranı</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="olumoran">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-4 mt-2">Apache II</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="apache">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="apachebtn">
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $("#apachebtn").on("click", function () {
                    var protokolno="<?php echo $protokol_no; ?>";
                    var gonderilenform = $("#formapache").serialize();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/skorislem.php?islem=apache',
                        data: gonderilenform,
                        success: function (e) {
                            $('#sonucyaz').html(e);
                            $.get("ajax/skor_listeleri.php?islem=apache_listesi", {protokolno: protokolno}, function (getVeri) {
                                $('.apache_listesi').html(getVeri);

                            });
                        }
                    });
                });
                $(".apache_change").change(function () {
                    //form reset--> attr('id')
                    var yas =($("#yas option:selected").attr("data-id")!='' || $("#yas option:selected").attr("data-id")!=null) ? Number($("#yas option:selected").attr("data-id")) : 0;
                    var vucut_isi =($("#vucut_isi option:selected").attr("data-id")!='' || $("#vucut_isi option:selected").attr("data-id")!=null ) ? Number($("#vucut_isi option:selected").attr("data-id")) : 0;
                    var kan_basinci =($("#kan_basinci option:selected").attr("data-id")!='' || $("#kan_basinci option:selected").attr("data-id")!=null) ? Number($("#kan_basinci option:selected").attr("data-id")) : 0;
                    var kalp_hizi =($("#kalp_hizi option:selected").attr("data-id")!='' || $("#kalp_hizi option:selected").attr("data-id")!=null) ? Number($("#kalp_hizi option:selected").attr("data-id")) : 0;
                    var solum_hizi =($("#solum_hizi option:selected").attr("data-id")!='' || $("#solum_hizi option:selected").attr("data-id")!=null) ? Number($("#solum_hizi option:selected").attr("data-id")) : 0;
                    var fi02 =($("#fi02 option:selected").attr("data-id")!='' || $("#fi02 option:selected").attr("data-id")!=null) ? Number($("#fi02 option:selected").attr("data-id")) : 0;
                    var pa02 = ($("#pa02 option:selected").attr("data-id")!='' || $("#pa02 option:selected").attr("data-id")!=null) ? Number($("#pa02 option:selected").attr("data-id")) : 0;
                    var arteriyel =($("#arteriyel option:selected").attr("data-id")!='' || $("#arteriyel option:selected").attr("data-id")!=null) ? Number($("#arteriyel option:selected").attr("data-id")) : 0;
                    var sr_sodyum =($("#sr_sodyum option:selected").attr("data-id")!='' || $("#sr_sodyum option:selected").attr("data-id")!=null) ? Number($("#sr_sodyum option:selected").attr("data-id")) : 0;
                    var serum_krevar =($("#serum_krevar option:selected").attr("data-id")!='' || $("#serum_krevar option:selected").attr("data-id")!=null) ? Number($("#serum_krevar option:selected").attr("data-id")) : 0;
                    var serum_kreyok =($("#serum_kreyok option:selected").attr("data-id")!='' || $("#serum_kreyok option:selected").attr("data-id")!=null) ? Number($("#serum_kreyok option:selected").attr("data-id")) : 0;
                    var serpos =($("#serpos option:selected").attr("data-id")!='' || $("#serpos option:selected").attr("data-id")!=null) ? Number($("#serpos option:selected").attr("data-id")) : 0;
                    var glas =($("#glas option:selected").attr("data-id")!='' || $("#glas option:selected").attr("data-id")!=null) ? Number($("#glas option:selected").attr("data-id")) : 0;
                    var kronik =($("#kronik option:selected").attr("data-id")!='' || $("#kronik option:selected").attr("data-id")!=null) ? Number($("#kronik option:selected").attr("data-id")) : 0;

                     var sonuc = yas + vucut_isi + kan_basinci + kalp_hizi + solum_hizi + fi02 + pa02 + arteriyel + sr_sodyum + serum_krevar + serum_kreyok + serpos + glas + kronik;
                    // var sonuc = yas + vucut_isi + kan_basinci+kalp_hizi+solum_hizi+fi02+pa02+arteriyel+sr_sodyum+serum_kreyok+serpos+glas+kronik+serum_krevar ;
                    $('#apache').val(sonuc);
                    $('.apache').val(sonuc);

                    var x = -3.517 + (sonuc * 0.146);
                    var y = Math.pow(2.71, x);
                    var z = y / (1 + y);
                    var r = z * 100;
                    // var olumoran = Math.ceil(r);
                    var olumoran = r.toFixed(2);
                    $('#olumoran').val('%' + olumoran);
                    $('.olumoran').val(olumoran);
                    // var gonderilenform = $("#formapache").serialize();
                    // $.ajax({
                    //     type: 'POST',
                    //     url: 'ajax/skorislem.php?islem=apache',
                    //     data: gonderilenform,
                    //     success: function (e) {
                    //         $('#sonucyaz').html(e);
                    //     }
                    // });

                });
            </script>


        </div>
    </div>

<?php }
else if ($islem == "glaskow_koma_skoru") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>


            <div class="skorbody">
                <form id="formglaskow" action="javascript:void(0);">
                    <div class=" row mt-1">
                        <div class="col-md-6">
                            <div class="row mx-2">
                                <label class="form-label col-md-3">Göz Açıklığı</label>
                                <div class="col-md-9">
                                    <input type="hidden" class="form-control" name="protokol_number"
                                           value="<?php echo $hastakayit['protocol_number']; ?>">
                                    <input type="hidden" class="form-control" name="patient_number"
                                           value="<?php echo $hastakayit['patient_id']; ?>">
                                    <select class="form-select glaskow_change" name="eye_aperture" id="goz">
                                        <option data-id="" value="">Göz Açıklığı seçiniz..</option>
                                        <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Göz Açıklığı' AND definition_module='Glasgow'";

                                        $hello = verilericoklucek($bolumgetir);
                                        foreach ((array)$hello as $value) { ?>

                                            <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mx-2">
                                <label class="form-label col-md-3">Motor Yanıt</label>
                                <div class="col-md-9">
                                    <select class="form-select glaskow_change" name="engine_response" id="motor">
                                        <option data-id="" value="">Motor Yanıt seçiniz..</option>
                                        <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Motor Yanıt' AND definition_module='Glasgow'";

                                        $hello = verilericoklucek($bolumgetir);
                                        foreach ((array)$hello as $value) { ?>

                                            <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" row mt-1">
                        <div class="col-md-6">
                            <div class="row mx-2">
                                <label class="form-label col-md-3">Sözel Cevap </label>
                                <div class="col-md-9">
                                    <select class="form-select glaskow_change" name="verbal_response" id="sozel">
                                        <option data-id="" value="">Sözel Cevap seçiniz..</option>
                                        <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sözel Cevap' AND definition_module='Glasgow'";

                                        $hello = verilericoklucek($bolumgetir);
                                        foreach ((array)$hello as $value) { ?>

                                            <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" class="form-control glaskow"
                                   name="galskow_conclusion">
                        </div>
                    </div>

                </form>
            </div>


            <div class="modal-footer py-0">
                <div class="row mx-1">
                    <div class="col-md-8">
                        <div class="row">
                            <label class="form-label col-md-8 mt-2">Glaskow Koma Skoru</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="glaskow">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="glaskowbtn">
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $("#glaskowbtn").on("click", function () {
                    var protokolno="<?php echo $protokol_no; ?>";
                    var gonderilenform = $("#formglaskow").serialize();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/skorislem.php?islem=glaskow',
                        data: gonderilenform,
                        success: function (e) {
                            $('#sonucyaz').html(e);
                            $.get("ajax/skor_listeleri.php?islem=glaskow_listesi", {protokolno: protokolno}, function (getVeri) {
                                $('.glaskow_listesi').html(getVeri);

                            });
                        }
                    });

                });
                $(".glaskow_change").change(function () {
                    //form reset-->
                    var goz =($("#goz option:selected").attr("data-id")!='' || $("#goz option:selected").attr("data-id")!=null) ? Number($("#goz option:selected").attr("data-id")) : 0;
                    var motor =($("#motor option:selected").attr("data-id")!='' || $("#motor option:selected").attr("data-id")!=null) ? Number($("#motor option:selected").attr("data-id")) : 0;
                    var sozel =($("#sozel option:selected").attr("data-id")!='' || $("#sozel option:selected").attr("data-id")!=null) ? Number($("#sozel option:selected").attr("data-id")) : 0;

                    var sonuc = goz + motor + sozel;
                    $('#glaskow').val(sonuc);
                    $('.glaskow').val(sonuc);

                    // var gonderilenform = $("#formglaskow").serialize();
                    // $.ajax({
                    //     type: 'POST',
                    //     url: 'ajax/skorislem.php?islem=glaskow',
                    //     data: gonderilenform,
                    //     success: function (e) {
                    //         $('#sonucyaz').html(e);
                    //     }
                    // });

                });
            </script>


        </div>
    </div>

<?php }
else if ($islem == "saps_skoru") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>

            <form id="formsaps" action="javascript:void(0);">
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Yatış Şekli</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" name="protokol_number"
                                       value="<?php echo $hastakayit['protocol_number']; ?>">
                                <input type="hidden" class="form-control" name="patient_number"
                                       value="<?php echo $hastakayit['patient_id']; ?>">
                                <select class="form-select saps_change" name="sleeping_style"
                                        id="sleeping_style">
                                    <option data-id=""  value="">Yatış Şekli seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yatış Şekli'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Kronik Hastalık</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="chronic_disease"
                                        id="chronic_disease">
                                    <option data-id=""  value="">Kronik Hastalık seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kronik Hastalık'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Glasgow</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="glasgow" id="glasgow">
                                    <option data-id=""  value="">Glasgow seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Glasgow'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Yaş</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="age" id="age">
                                    <option data-id=""  value="">Yaş seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yaş'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Sistolik Kan Basıncı</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="blood_pressure"
                                        id="blood_pressure">
                                    <option data-id=""  value="">Sistolik Kan Basıncı seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sistolik Kan Basıncı'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Kalp Hızı</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="heart_rate" id="heart_rate">
                                    <option data-id=""  value="">Kalp Hızı seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kalp Hızı'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Vücut Isısı</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="body_temperature"
                                        id="body_temperature">
                                    <option  data-id=""  value="">Vücut Isısı seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Vücut Isısı'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">MV veya CPAP</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="mv_cpap" id="mv_cpap">
                                    <option data-id=""  value="">MV veya CPAP var ise PaO2/FIO2(mmHg)
                                        seçiniz..
                                    </option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='MV veya CPAP var ise PaO2/FIO2(mmHg)'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">İdrar Çıkışı</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="urine_output"
                                        id="urine_output">
                                    <option data-id=""  value="">İdrar Çıkışı seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='İdrar Çıkışı'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Serum Ure veya BUN</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="serum_ure" id="serum_ure">
                                    <option data-id=""  value="">Serum Ure veya BUN seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Serum Ure veya BUN'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">WBC</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="wbc" id="wbc">
                                    <option data-id=""  value="">WBC seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='WBC'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Potasyum</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="potassium" id="potassium">
                                    <option data-id=""  value="">Potasyum seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Potasyum'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Sodyum</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="sodium" id="sodium">
                                    <option data-id=""  value="">Sodyum seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sodyum'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">HCO3</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="hco3" id="hco3">
                                    <option data-id=""  value="">HCO3 seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='HCO3'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Bilirubin</label>
                            <div class="col-md-9">
                                <select class="form-select saps_change" name="bilirubin" id="bilirubin">
                                    <option data-id=""  value="">Bilirubin seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Bilirubin'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" class="form-control saps2" name="conclusion_saps">
                    </div>
                </div>
                <div class="card mt-1">
                    <h4 class="card-title p-2 text-white bg-primary">SAPS II (genişletilmiş)</h4>
                    <div class="mx-3">
                        <div class="card-body mx-0 ">
                            <div class=" row mt-1">
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Yaş</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" id="age1">
                                                <option data-id=""  value="">Yaş seçiniz..</option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yaş2'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Cinsiyet</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" name="gender" id="gender">
                                                <option data-id=""  value="">Cinsiyet seçiniz..</option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Cinsiyet'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row mt-1">
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Y.B.Ö.H.K.S.</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" name="sick_departure"
                                                    id="sick_departure">
                                                <option data-id=""  value="">Yoğun Bakım Öncesi Hastanede Kalış Süresi
                                                    seçiniz..
                                                </option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yoğun Bakım Öncesi Hastanede Kalış Süresi'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Y.B.Ö.H.Y.B.</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" name="bed_section" id="bed_section">
                                                <option data-id=""  value="">Yoğun bakım öncesi hastanın yattığı bölüm
                                                    seçiniz..
                                                </option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Yoğun bakım öncesi hastanın yattığı bölüm'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row mt-1">
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Klinik Kategori</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" name="clinic_categori"
                                                    id="clinic_categori">
                                                <option data-id=""  value="">Klinik Kategori seçiniz..</option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Klinik Kategori'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mx-2">
                                        <label class="form-label col-md-3">Zehirlenme</label>
                                        <div class="col-md-9">
                                            <select class="form-select saps_change" name="poisoning" id="poisoning">
                                                <option data-id="" value="">Zehirlenme seçiniz..</option>
                                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Zehirlenme'";

                                                $hello = verilericoklucek($bolumgetir);
                                                foreach ((array)$hello as $value) { ?>

                                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" class="form-control saps_death" name="conclusion_saps_death">
                        </div>
                    </div>
                </div>

            </form>

            <div class="modal-footer py-0">
                <div class="row mx-1">
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-4 mt-2"> SAPS II</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="saps2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <label class="form-label col-md-8 mt-2">Beklenen Mortalite</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="saps_death">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="sapsbtn">
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $("#sapsbtn").on("click", function () {
                    var protokolno="<?php echo $protokol_no; ?>";
                    var gonderilenform = $("#formsaps").serialize();
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/skorislem.php?islem=spas',
                        data: gonderilenform,
                        success: function (e) {
                            $('#sonucyaz').html(e);
                            $.get("ajax/skor_listeleri.php?islem=spas_listesi", {protokolno: protokolno}, function (getVeri) {
                                $('.spas_listesi').html(getVeri);

                            });
                        }
                    });
                });
                $(".saps_change").change(function () {

                    //form reset-->
                    var sleeping_style =($("#sleeping_style option:selected").attr("data-id")!='' || $("#sleeping_style option:selected").attr("data-id")!=null) ? Number($("#sleeping_style option:selected").attr("data-id")) : 0;
                    var chronic_disease =($("#chronic_disease option:selected").attr("data-id")!='' || $("#chronic_disease option:selected").attr("data-id")!=null) ? Number($("#chronic_disease option:selected").attr("data-id")) : 0;
                    var glasgow =($("#glasgow option:selected").attr("data-id")!='' || $("#glasgow option:selected").attr("data-id")!=null) ? Number($("#glasgow option:selected").attr("data-id")) : 0;
                    var age =($("#age option:selected").attr("data-id")!='' || $("#age option:selected").attr("data-id")!=null) ? Number($("#age option:selected").attr("data-id")) : 0;
                    var blood_pressure =($("#blood_pressure option:selected").attr("data-id")!='' || $("#blood_pressure option:selected").attr("data-id")!=null) ? Number($("#blood_pressure option:selected").attr("data-id")) : 0;
                    var heart_rate =($("#heart_rate option:selected").attr("data-id")!='' || $("#heart_rate option:selected").attr("data-id")!=null) ? Number($("#heart_rate option:selected").attr("data-id")) : 0;
                    var body_temperature =($("#body_temperature option:selected").attr("data-id")!='' || $("#body_temperature option:selected").attr("data-id")!=null) ? Number($("#body_temperature option:selected").attr("data-id")) : 0;
                    var mv_cpap =($("#mv_cpap option:selected").attr("data-id")!='' || $("#mv_cpap option:selected").attr("data-id")!=null) ? Number($("#mv_cpap option:selected").attr("data-id")) : 0;
                    var urine_output =($("#urine_output option:selected").attr("data-id")!='' || $("#urine_output option:selected").attr("data-id")!=null) ? Number($("#urine_output option:selected").attr("data-id")) : 0;
                    var serum_ure =($("#serum_ure option:selected").attr("data-id")!='' || $("#serum_ure option:selected").attr("data-id")!=null) ? Number($("#serum_ure option:selected").attr("data-id")) : 0;
                    var wbc =($("#wbc option:selected").attr("data-id")!='' || $("#wbc option:selected").attr("data-id")!=null) ? Number($("#wbc option:selected").attr("data-id")) : 0;
                    var potassium =($("#potassium option:selected").attr("data-id")!='' || $("#potassium option:selected").attr("data-id")!=null) ? Number($("#potassium option:selected").attr("data-id")) : 0;
                    var sodium =($("#sodium option:selected").attr("data-id")!='' || $("#sodium option:selected").attr("data-id")!=null) ? Number($("#sodium option:selected").attr("data-id")) : 0;
                    var hco3 =($("#hco3 option:selected").attr("data-id")!='' || $("#hco3 option:selected").attr("data-id")!=null) ? Number($("#hco3 option:selected").attr("data-id")) : 0;
                    var bilirubin =($("#bilirubin option:selected").attr("data-id")!='' || $("#bilirubin option:selected").attr("data-id")!=null) ? Number($("#bilirubin option:selected").attr("data-id")) : 0;
                    var age1 =($("#age1 option:selected").attr("data-id")!='' || $("#age1 option:selected").attr("data-id")!=null) ? Number($("#age1 option:selected").attr("data-id")) : 0;
                    var gender =($("#gender option:selected").attr("data-id")!='' || $("#gender option:selected").attr("data-id")!=null) ? Number($("#gender option:selected").attr("data-id")) : 0;
                    var sick_departure =($("#sick_departure option:selected").attr("data-id")!='' || $("#sick_departure option:selected").attr("data-id")!=null) ? Number($("#sick_departure option:selected").attr("data-id")) : 0;
                    var bed_section =($("#bed_section option:selected").attr("data-id")!='' || $("#bed_section option:selected").attr("data-id")!=null) ? Number($("#bed_section option:selected").attr("data-id")) : 0;
                    var clinic_categori =($("#clinic_categori option:selected").attr("data-id")!='' || $("#clinic_categori option:selected").attr("data-id")!=null) ? Number($("#clinic_categori option:selected").attr("data-id")) : 0;
                    var poisoning =($("#poisoning option:selected").attr("data-id")!='' || $("#poisoning option:selected").attr("data-id")!=null) ? Number($("#poisoning option:selected").attr("data-id")) : 0;

                    var sonuc = sleeping_style + chronic_disease + glasgow + age + blood_pressure + heart_rate + body_temperature
                        + mv_cpap + urine_output + serum_ure + wbc + potassium + sodium + hco3 + bilirubin;
                    // var sonuc = sleeping_style;

                    $('#saps2').val(sonuc);
                    $('.saps2').val(sonuc);
                    var sonuc2 = age1 + gender + sick_departure + bed_section + clinic_categori + poisoning;
                    var deger = 0.0742;
                    var sonislem = (deger * sonuc) + sonuc2;
                    var degerlog1 = -14.4761;
                    var degerlog2 = 0.0844;
                    var degerlog3 = 6.6158;
                    var logithesap = Math.log((sonislem + 1));
                    var logit = degerlog1 + (degerlog2 * sonislem) + degerlog3 * (logithesap);
                    var e = 2.71;
                    var mortalite = Math.pow(e, logit);
                    var taban = 1 + mortalite;
                    var sonuclog = (mortalite / taban) * 100;

                    var yuzde = sonuclog.toFixed(1);
                    $('#saps_death').val('%' + yuzde);
                    $('.saps_death').val(yuzde);

                    //
                    // var gonderilenform = $("#formsaps").serialize();
                    // $.ajax({
                    //     type: 'POST',
                    //     url: 'ajax/skorislem.php?islem=spas',
                    //     data: gonderilenform,
                    //     success: function (e) {
                    //         $('#sonucyaz').html(e);
                    //     }
                    // });

                });
            </script>


        </div>
    </div>

<?php }
else if ($islem == "prism_skoru") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>

            <form id="formprism" action="javascript:void(0);">
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Sistolik Kan Basıncı (mmHg)</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" name="protokol_number"
                                       value="<?php echo $hastakayit['protocol_number']; ?>" id="protokol_number">
                                <input type="hidden" class="form-control" name="patient_number"
                                       value="<?php echo $hastakayit['patient_id']; ?>" id="patient_number">
                                <select class="form-select" name="systolic_blood_pressure"
                                        id="systolic_blood_pressure" onChange="CalcPAS(this.form)">
                                    <option value="0">Sistolik Kan Basıncı seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Sistolik Kan Basıncı (mmHg)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Diastolik Kan Basıncı</label>
                            <div class="col-md-9">
                                <select class="form-select" name="diastolic_blood_pressure"
                                        id="diastolic_blood_pressure" onChange="CalcPAD(this.form)">
                                    <option value="0">Diastolik Kan Basıncı (mmHg) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Diastolik Kan Basıncı (mmHg)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Kalp Hızı</label>
                            <div class="col-md-9">
                                <select class="form-select" name="heart_rate" id="heart_rateprism" onChange="CalcFC(this.form)">
                                    <option value="0">Kalp Hızı (beats/ min) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kalp Hızı (beats/ min)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Solunum Sayısı</label>
                            <div class="col-md-9">
                                <select class="form-select" name="respiration_rate" id="respiration_rate" onChange="CalcFR(this.form)">
                                    <option value="0">Solunum Sayısı (breaths/ min) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Solunum Sayısı (breaths/ min)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Pa O2 / FI O2</label>
                            <div class="col-md-9">
                                <select class="form-select" name="pao2_fio2"
                                        id="pao2_fio2" onChange="CalcPAO(this.form)">
                                    <option value="0">Pa O2 / FI O2 (mmHg) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Pa O2 / FI O2 (mmHg)'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Pa CO2</label>
                            <div class="col-md-9">
                                <select class="form-select" name="pa_co2" id="pa_co2" onChange="CalcPCO(this.form)">
                                    <option value="0">Pa CO2 (mmHg) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Pa CO2 (mmHg)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">PT / PTT</label>
                            <div class="col-md-9">
                                <select class="form-select" name="pt_ptt"
                                        id="pt_ptt" onChange="CalcPT(this.form)">
                                    <option value="0">PT / PTT seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='PT / PTT' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Total Bilirubin</label>
                            <div class="col-md-9">
                                <select class="form-select" name="total_bilirubin" id="total_bilirubin" onChange="CalcBILI(this.form)">
                                    <option value="0">Total Bilirubin seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Total Bilirubin' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Kalsiyum</label>
                            <div class="col-md-9">
                                <select class="form-select" name="calcium"
                                        id="calcium" onChange="CalcCA(this.form)">
                                    <option value="0">Kalsiyum seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kalsiyum' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Potasyum (mEq/L)</label>
                            <div class="col-md-9">
                                <select class="form-select" name="potassium" id="potassiumprism" onChange="CalcKA(this.form)">
                                    <option value="0">Potasyum (mEq/L) seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Potasyum (mEq/L)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Glukoz</label>
                            <div class="col-md-9">
                                <select class="form-select" name="glucose" id="glucose" onChange="CalcGLY(this.form)">
                                    <option value="0">Glukoz seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Glukoz' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">HCO3</label>
                            <div class="col-md-9">
                                <select class="form-select" name="hco3" id="hco3prism" onChange="CalcHCO(this.form)">
                                    <option value="0">Potasyum seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='HCO3- (mEq/L)' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Pupil Reaksiyonu</label>
                            <div class="col-md-9">
                                <select class="form-select" name="pupil" id="pupil" onChange="CalcPUP(this.form)">
                                    <option value="0">Pupil Reaksiyonu seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Pupil Reaksiyonu' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mx-2">
                            <label class="form-label col-md-3">Glasgow</label>
                            <div class="col-md-9">
                                <select class="form-select" name="glasgow" id="glasgowprism" onChange="CalcGLAS(this.form)">
                                <option value="0">Glasgow seçiniz..</option>
                                    <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Glasgow' AND definition_module='PRISM'";

                                    $hello = verilericoklucek($bolumgetir);
                                    foreach ((array)$hello as $value) { ?>

                                        <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row mt-1">
                    <div class="col-md-3">
                        <div class="row">
                            <label class="form-label col-md-4 mt-2"> PRISM</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control prism" name="conclusion_prism" id="conclusion_prism">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="form-label col-md-8 mt-2">Beklenen Mortalite</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control prism_death" name="conclusion_prism_death" id="conclusion_prism_death">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="form-label col-md-8 mt-2" title="Postoperatif (kardiyak cerrahi hariç) Beklenen Ölüm Oranı">P. Beklenen Ölüm Oranı</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control prism" name="conclusion_prism_death2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label class="form-label col-md-4 mt-2">Kaç Aylık</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control " name="y" value="0" id="prism_death">
                            </div>
                            <div class="col-md-4 ">
                                <input type=button name=Bouton value="Hesapla" onClick="CalcMorta(form); CalcMort(form)" class=" kps-btn btn-sm mt-1 p-1 rounded-1">
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal-footer py-0">
                <div class="row mx-1">
                    <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="prismbtn">
                </div>
            </div>

    <script language=javascript>
        function CalcPAS(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcPAD(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcFC(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcFR(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcPAO(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcPCO(form) {
            form.conclusion_prism.value =CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcPT(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcKA(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcGLAS(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcBILI(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcGLY(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }

        function CalcHCO(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcPUP(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }
        function CalcCA(form) {
            form.conclusion_prism.value = CalcAPA(form)
            form.conclusion_prism_death.value = CalcMorta(form)
            form.conclusion_prism_death2.value = CalcMort(form)
        }


        function CalcAPA(form) {

            z =eval(Number($("#systolic_blood_pressure").val()))
            z = z + eval(Number($("#diastolic_blood_pressure").val()))
            z = z + eval(Number($("#heart_rateprism").val()))
            z = z + eval(Number($("#respiration_rate").val()))
            z = z + eval(Number($("#pao2_fio2").val()))
            z = z + eval(Number($("#pa_co2").val()))
            z = z + eval(Number($("#pt_ptt").val()))
            z = z + eval(Number($("#total_bilirubin").val()))
            z = z + eval(Number($("#calcium").val()))
            z = z + eval(Number($("#potassiumprism").val()))
            z = z + eval(Number($("#glucose").val()))
            z = z + eval(Number($("#hco3prism").val()))
            z = z + eval(Number($("#pupil").val()))
            z = z + eval(Number($("#glasgowprism").val()))

            return ''+z
        }

        function CalcMorta(form) {
            z = eval(form.conclusion_prism.value)
            z = (0.207 * z)- 4.782
            z = eval(form.y.value * "-0.005" +"+z")
            if (form.y.value>228) {alert("upper limit for age used in implementation will be 19th birthday")}
            z = Math.exp(z) / (1 + Math.exp(z))
            z = Fmt(100 * z) + " %"
            form.conclusion_prism_death.value = z
            return z
        }

        function CalcMort(form) {
            z = eval(form.conclusion_prism.value)
            z = (0.207 * z)- 4.782
            z = eval(form.y.value * "-0.005" +"+z")
            z = z - 0.433
            z = Math.exp(z) / (1 + Math.exp(z))
            z = Fmt(100 * z) + " %"
            form.conclusion_prism_death2.value = z
            return z
        }

        function Fmt(x) {
            var v
            if(x>=0) { v=''+(x+0.05) } else { v=''+(x-0.05) }
            return v.substring(0,v.indexOf('.')+2)
        }
    </script>
            <script type="text/javascript">
                // function erreur(text)
                // {
                //     $.jGrowl(text, { position: 'center' });
                // }

                // function clearChamp(id, valeur)
                // {
                //     if (document.getElementById(id).value ==valeur)
                //         document.getElementById(id).value = '';
                //     else if (!document.getElementById(id).value)
                //         document.getElementById(id).value = valeur;
                // }
                //
                // function clearChamp2(id, valeur)
                // {
                //     if (document.getElementById(id).value == valeur)
                //     {
                //         document.getElementById(id).value = '';
                //         //document.getElementById(id).type = 'password';
                //     }
                //     else if (!document.getElementById(id).value)
                //     {
                //         document.getElementById(id).value = valeur;
                //         //document.getElementById(id).type = 'text';
                //     }
                // }
            </script>

            <script type="text/javascript">
                $("#prismbtn").on("click", function () {
                    var systolic_blood_pressure=$("#systolic_blood_pressure option:selected").attr("data-id");
                    var diastolic_blood_pressure=$("#diastolic_blood_pressure option:selected").attr("data-id");
                    var heart_rate=$("#heart_rateprism option:selected").attr("data-id");
                    var respiration_rate=$("#respiration_rate option:selected").attr("data-id");
                    var pao2_fio2=$("#pao2_fio2 option:selected").attr("data-id");
                    var pa_co2=$("#pa_co2 option:selected").attr("data-id");
                    var pt_ptt=$("#pt_ptt option:selected").attr("data-id");
                    var total_bilirubin=$("#total_bilirubin option:selected").attr("data-id");
                    var calcium=$("#calcium option:selected").attr("data-id");
                    var potassium=$("#potassiumprism option:selected").attr("data-id");
                    var glucose=$("#glucose option:selected").attr("data-id");
                    var hco3=$("#hco3prism option:selected").attr("data-id");
                    var pupil=$("#pupil option:selected").attr("data-id");
                    var glasgow=$("#glasgowprism option:selected").attr("data-id");
                    var conclusion_prism_death=$("#conclusion_prism_death").val();
                    var conclusion_prism=$("#conclusion_prism").val();
                    var protokol_number=$("#protokol_number").val();
                    var patient_number=$("#patient_number").val();

                    $.ajax({
                        type: 'POST',
                        url: 'ajax/skorislem.php?islem=prism',
                        data: {systolic_blood_pressure:systolic_blood_pressure,diastolic_blood_pressure:diastolic_blood_pressure,
                            heart_rate:heart_rate,respiration_rate:respiration_rate,pao2_fio2:pao2_fio2,pa_co2:pa_co2,pt_ptt:pt_ptt,
                        total_bilirubin:total_bilirubin,calcium:calcium,potassium:potassium,glucose:glucose,hco3:hco3,pupil:pupil,
                            glasgow:glasgow,conclusion_prism_death:conclusion_prism_death,conclusion_prism:conclusion_prism,
                        protokol_number:protokol_number,patient_number:patient_number},
                        success: function (e) {
                            $('#sonucyaz').html(e);
                        }
                    });

                });
            </script>


        </div>
    </div>

<?php }
else if ($islem == "body_skoru") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    $taburcu = singularactive("patient_discharge", "admission_protocol",$protokol_no);
    $izin = singularactive("patient_permission","protocol_number",$protokol_no);
    ?>
    <div class="modal-dialog modal-xxl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header mb-1">
                <h4 class="modal-title col-md-8">Skor
                    İşlemleri-<?php echo strtoupper($patients['patient_name'] . "  " . $patients['patient_surname']); ?></h4>
                <div class="col-md-3" align="right">
                    <p class="pt-2">Hasta Durum:<b> <?php if ($taburcu['discharge_status']==1){ ?>
                                Taburcu <?php }elseif ($izin['id']!=''){ ?> İzinli <?php  }else{ ?>
                                Yatışta <?php  } ?></b></p>
                </div>
                <button type="button" class="btn-close btn-close-white col-md-1" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body mb-1">
                <div class="row">
                    <div class="col-md-12">

                        <div class="mx-1">


                                    <ul class="nav nav-pills mb-3 d-flex flex-row w-100" id="pills-tab" role="tablist">
                                        <tbody>
                                        <tr>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 active up-btn" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#apache_tab" type="button" role="tab" aria-controls="pills-home" aria-selected="true">APACHE II</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation ">
                                                    <button class="nav-link w-100 "  protokolno="<?php  ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#glaskow_tab" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">GLASKOW</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#spas_tab" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">SAPS II</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#prism_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">PRISM</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#snapii_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">SNAPS ||</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#crib_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">CRIB</button>
                                                </li>

                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#euroscore_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">EUROSCORE</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#euroscore2_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">EUROSCORE ||</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#triss_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">TRISS</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#murray_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">MURRAY</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#sofa_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">SOFA</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#mods_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">MODS</button>
                                                </li>
                                            </th>
                                            <th>
                                                <li class="nav-item mx-1" role="presentation">
                                                    <button class="nav-link w-100 " id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#absi_tab" type="button" role="tab" aria-controls="pills-disabled" aria-selected="false">ABSI</button>
                                                </li>
                                            </th>
                                        </tr>
                                        </tbody>
                                    </ul>
                                    <script>
                                        $(".nav-link").click(function () {
                                            $('.nav-link').removeClass("text-white");
                                            $('.nav-link').removeClass("up-btn");
                                            $(this).addClass("text-white");
                                            $(this).addClass("up-btn");

                                        });
                                    </script>
                                    <div class="tab-content" id="pills-tabContent">
                                        <script>
                                            var protokolno="<?php echo $protokol_no; ?>";
                                            $.get("ajax/skor_hesaplama.php?islem=skor_hesaplama", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_apache').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=apache_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.apache_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=glaskow_koma_skoru", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_glaskow').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=glaskow_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.glaskow_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=saps_skoru", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_spas').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=spas_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.spas_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=prism_skoru", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_prism').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=prism_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.prism_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=snap2_skor", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_snapii').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=snap2_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.snapii_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=crib2", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_crib').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=crib2_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.crib_listesi').html(getVeri);

                                                });
                                            });

                                            $.get("ajax/skor_hesaplama.php?islem=euroscore2", {protokolno: protokolno}, function (getVeri) {
                                                $('.get_euroscore2').html(getVeri);
                                                $.get("ajax/skor_listeleri.php?islem=euroscore2_listesi", {protokolno: protokolno}, function (getVeri) {
                                                    $('.euroscore2_listesi').html(getVeri);

                                                });
                                            });
                                        </script>

                                        <div class="tab-pane fade show active" id="apache_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_apache">

                                            </div>
                                            <div class="apache_listesi">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade " id="glaskow_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_glaskow">

                                            </div>
                                            <div class="glaskow_listesi">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="spas_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_spas">

                                            </div>
                                            <div class="spas_listesi">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="prism_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_prism">

                                            </div>
                                            <div class="prism_listesi">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="snapii_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_snapii">
                                                get_snapii
                                            </div>
                                            <div class="snapii_listesi">
                                                snapii_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="crib_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_crib">
                                                crib_tab
                                            </div>
                                            <div class="crib_listesi">
                                                crib_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="euroscore_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_euroscore">
                                                euroscore_tab
                                            </div>
                                            <div class="euroscore_listesi">
                                                euroscore_tab
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="euroscore2_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_euroscore2">
                                                euroscore2_tab
                                            </div>
                                            <div class="euroscore2_listesi">
                                                euroscore2_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="triss_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_triss">
                                                triss_tab
                                            </div>
                                            <div class="triss_listesi">
                                                triss_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="murray_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_murray">
                                                murray_tab
                                            </div>
                                            <div class="murray_listesi">
                                                murray_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="sofa_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_sofa">
                                                sofa_tab
                                            </div>
                                            <div class="sofa_listesi">
                                                sofa_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="mods_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_mods">
                                                mods_tab
                                            </div>
                                            <div class="mods_listesi">
                                                mods_listesi
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="absi_tab" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="get_absi">
                                                absi_tab
                                            </div>
                                            <div class="absi_listesi">
                                                absi_listesi
                                            </div>
                                        </div>



                                    </div>


                            </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

<?php }
else if ($islem == "snap2_skor") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>


    <div class="skorbody">
        <form id="formsnaps" action="javascript:void(0);">
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Ortalama Arter Basinci</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" name="protokol_number"
                                   value="<?php echo $hastakayit['protocol_number']; ?>">
                            <input type="hidden" class="form-control" name="patient_number"
                                   value="<?php echo $hastakayit['patient_id']; ?>">
                            <select class="form-select snap2_change" name="mean_arterial_pressure" id="mean_arterial_pressure">
                                <option data-id="" value="">Ortalama Arter Basinci seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Ortalama Arter Basinci' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Minimum sicaklik</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="minimum_temperature" id="minimum_temperature">
                                <option data-id="" value="">Minimum sicaklik seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Minimum sicaklik' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">P02 (mmHg ) / FIO2 (%)</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="p02" id="p02">
                                <option data-id="" value="">P02 (mmHg ) / FIO2 (%) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='P02 (mmHg ) / FIO2 (%)' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Plazma pH Asgari</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="plasma_ph" id="plasma_ph">
                                <option data-id="" value="">Plazma pH Asgari seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='plazma pH asgari' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Coklu nobetler</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="multiple_seizures" id="multiple_seizures">
                                <option data-id="" value="">Coklu nobetler seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Coklu nobetler' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Diurez (ml / kg  saat)</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="diurez" id="diurez">
                                <option data-id="" value="">Diurez (ml / kg  saat) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Diurez (ml / kg � saat)' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Apgar skoru</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="apgar" id="apgar">
                                <option data-id="" value="">Apgar skoru seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Apgar skoru' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Dogum agirligi</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="birth_weight" id="birth_weight">
                                <option data-id="" value="">Dogum agirligi seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Dogum agirligi' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Hypotrophy ( yardım )</label>
                        <div class="col-md-9">
                            <select class="form-select snap2_change" name="hypotrophy" id="hypotrophy">
                                <option data-id="" value="">Hypotrophy ( yardım ) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Hypotrophy ( yardım )' AND definition_module='SNAP-II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>



            </div>
            <div class="col-md-6">
                <input type="hidden" class="form-control snap2"
                       name="snap2_conclusion">
                <input type="hidden" class="form-control snappe2"
                       name="snap2_conclusion_detail">
                <input type="hidden" class="form-control gestational"
                       name="gestational">
            </div>
        </form>
    </div>


    <div class="modal-footer py-0">
        <div class="row mx-1">
            <div class="col-md-7">
                <div class="row mx-4">
                    <label class="form-label col-md-3">Gestasyonel yaş</label>
                    <div class="col-md-7">
                        <select class="form-select gestationalgetir" name="gestational" id="gestational">
                            <option data-id="" value="">Gestasyonel yas (hafta) seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Gestasyonel yas (hafta)' AND definition_module='SNAP-II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2 ">
                        <input type="text" id="gestationalsonuc" class="w-50 mt-2 px-2">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <label class="form-label col-md-8 mt-2">SNAP ||</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="snap2">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <label class="form-label col-md-8 mt-2">SNAP-PE ||</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="snappe2">
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="snap2btn">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#snap2btn").on("click", function () {
            var protokolno="<?php echo $protokol_no; ?>";
            var gonderilenform = $("#formsnaps").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/skorislem.php?islem=snaps',
                data: gonderilenform,
                success: function (e) {
                    $('#sonucyaz').html(e);
                    $.get("ajax/skor_listeleri.php?islem=snap2_listesi", {protokolno: protokolno}, function (getVeri) {
                        $('.snapii_listesi').html(getVeri);
                    });
                }
            });

        });
        $(".gestationalgetir").change(function () {
            $("#gestationalsonuc").val($(this).val());
            $(".gestational").val($(this).val());
        });
        $(".snap2_change").change(function () {
            //form reset-->
            var mean_arterial_pressure =($("#mean_arterial_pressure option:selected").attr("data-id")!='' || $("#mean_arterial_pressure option:selected").attr("data-id")!=null) ? Number($("#mean_arterial_pressure option:selected").attr("data-id")) : 0;
            var minimum_temperature =($("#minimum_temperature option:selected").attr("data-id")!='' || $("#minimum_temperature option:selected").attr("data-id")!=null) ? Number($("#minimum_temperature option:selected").attr("data-id")) : 0;
            var p02 =($("#p02 option:selected").attr("data-id")!='' || $("#p02 option:selected").attr("data-id")!=null) ? Number($("#p02 option:selected").attr("data-id")) : 0;
            var plasma_ph =($("#plasma_ph option:selected").attr("data-id")!='' || $("#plasma_ph option:selected").attr("data-id")!=null) ? Number($("#plasma_ph option:selected").attr("data-id")) : 0;
            var multiple_seizures =($("#multiple_seizures option:selected").attr("data-id")!='' || $("#multiple_seizures option:selected").attr("data-id")!=null) ? Number($("#multiple_seizures option:selected").attr("data-id")) : 0;
            var diurez =($("#diurez option:selected").attr("data-id")!='' || $("#diurez option:selected").attr("data-id")!=null) ? Number($("#diurez option:selected").attr("data-id")) : 0;
            var apgar =($("#apgar option:selected").attr("data-id")!='' || $("#apgar option:selected").attr("data-id")!=null) ? Number($("#apgar option:selected").attr("data-id")) : 0;
            var birth_weight =($("#birth_weight option:selected").attr("data-id")!='' || $("#birth_weight option:selected").attr("data-id")!=null) ? Number($("#birth_weight option:selected").attr("data-id")) : 0;
            var hypotrophy =($("#hypotrophy option:selected").attr("data-id")!='' || $("#hypotrophy option:selected").attr("data-id")!=null) ? Number($("#hypotrophy option:selected").attr("data-id")) : 0;

            var sonuc = mean_arterial_pressure + minimum_temperature + p02+plasma_ph+multiple_seizures+diurez;
            var sonuc2 = apgar + birth_weight+hypotrophy ;
            var sonuc3=sonuc+sonuc2;
            $('#snap2').val(sonuc);
            $('.snap2').val(sonuc);
            $('#snappe2').val(sonuc3);
            $('.snappe2').val(sonuc3);



        });
    </script>


    </div>
    </div>

<?php }
else if ($islem == "crib2") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>


    <div class="skorbody">
        <form id="formcrib" action="javascript:void(0);">
            <div class=" row mt-1">
                <div class="col-md-1">
                    <div class="row mx-1">
                        <label class="form-label col-md-10">Cinsiyet E:</label>
                        <div class="col-md-2">
                            <input type="checkbox" value="1"  name="gender" id="secilen">
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Gebelik (haftalık)</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" name="protokol_number"
                                   value="<?php echo $hastakayit['protocol_number']; ?>">
                            <input type="hidden" class="form-control" name="patient_number"
                                   value="<?php echo $hastakayit['patient_id']; ?>">
                            <select class="form-select glaskow_change" name="pregnancy" id="gebelik">
                                <option data-id="" value="">Gebelik (haftalık) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Gestation (weeks)' AND definition_module='CRIB II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mx-2">
                        <label class="form-label col-md-4">Doğum ağırlığı (g)</label>
                        <div class="col-md-8">
                            <input type="number" style="margin-left: 24px" class="form-control " min="500" max="2250" name="birth_weight" id="dogumagirlik">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="row mx-2">
                    <input type="submit" class="form-control kps-btn cribbtnhesap" value="Hesapla">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="row mx-2">
                        <input type="number"  class="form-control"  id="gebesonuc">
                    </div>
                </div>
            </div>
            <div class=" row mt-1">
                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Ilk  Ateş(°C)</label>
                        <div class="col-md-9">
                            <select class="form-select crib_change" name="first_fire" id="ates">
                                <option data-id="" value="">Ilk  Ateş(°C) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Temperature at admission (°C)' AND definition_module='CRIB II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mx-2">
                        <label class="form-label col-md-3">Baz fazlalığı (mmol/L)</label>
                        <div class="col-md-9">
                            <select class="form-select crib_change" name="base_excess" id="excess">
                                <option data-id="" value="">Baz fazlalığı (mmol/L) seçiniz..</option>
                                <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Base excess (mmol/L)' AND definition_module='CRIB II'";

                                $hello = verilericoklucek($bolumgetir);
                                foreach ((array)$hello as $value) { ?>

                                    <option data-id="<?php echo $value["definition_value"]; ?>" value="<?php echo $value["id"]; ?>"><?php echo $value['definition_key']; ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" class="form-control crib" name="crib_conclusion">
                <input type="hidden" class="form-control crib2" name="crib_conclusion_detail">

            </div>

        </form>
    </div>


    <div class="modal-footer py-0">
        <div class="row mx-1">
            <div class="col-md-4">
                <div class="row">
                    <label class="form-label col-md-8 mt-2">Tahmini Ölüm Oranı</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="crib2">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="form-label col-md-8 mt-2">CRIB II</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="crib">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="cribbtn">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#cribbtn").on("click", function () {
            var protokolno="<?php echo $protokol_no; ?>";
            var gonderilenform = $("#formcrib").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/skorislem.php?islem=crib',
                data: gonderilenform,
                success: function (e) {
                    $('#sonucyaz').html(e);
                    $.get("ajax/skor_listeleri.php?islem=crib2_listesi", {protokolno: protokolno}, function (getVeri) {
                        $('.crib_listesi').html(getVeri);

                    });
                }
            });

        });

    </script>
    <script language=javascript>
        $(".cribbtnhesap").on("click", function () {

            if ($('#secilen').is(':checked')) {
                var secilen=1;
            }else {
                var secilen=0;
            }

            var gebelik=$("#gebelik option:selected").attr("data-id")
           var t = $('#dogumagirlik').val();

              if (secilen){ //erkek bebek
                 if (t>=1501 && gebelik== 32 ){$('#gebesonuc').val(0)}
                 if (t>=1251 && t<=1500 && gebelik== 32 ){$('#gebesonuc').val(1)}
                 if (t>=1001 && t<=1250 && gebelik== 32 ){$('#gebesonuc').val(3)}
                 if (t>=751 && t<=1000 && gebelik== 32 ){$('#gebesonuc').val(6)}
                 if (t>=2501 && gebelik== 31 ){$('#gebesonuc').val(1)}
                 if (t>=1751 && t<=2500 && gebelik== 31 ){$('#gebesonuc').val(0)}
                 if (t>=1501 && t<=1750 && gebelik== 31 ){$('#gebesonuc').val(1)}
                 if (t>=1251 && t<=1500 && gebelik== 31 ){$('#gebesonuc').val(2)}
                 if (t>=1001 && t<=1250 && gebelik== 31 ){$('#gebesonuc').val(3)}
                 if (t>=751 && t<=1000 && gebelik== 31 ){$('#gebesonuc').val(6)}
                 if (t>=501 && t<=750 && gebelik== 31 ){$('#gebesonuc').val(8)}
                 if (t>=2251 && gebelik== 30 ){$('#gebesonuc').val(3)}
                 if (t>=2001 && t<=2250 && gebelik== 30 ){$('#gebesonuc').val(2)}
                 if (t>=1751 && t<=2000 && gebelik== 30 ){$('#gebesonuc').val(1)}
                 if (t>=1501 && t<=1750 && gebelik== 30 ){$('#gebesonuc').val(2)}
                 if (t>=1251 && t<=1500 && gebelik== 30 ){$('#gebesonuc').val(3)}
                 if (t>=1001 && t<=1250 && gebelik== 30 ){$('#gebesonuc').val(4)}
                 if (t>=751 && t<=1000 && gebelik== 30 ){$('#gebesonuc').val(6)}
                 if (t>=501 && t<=750 && gebelik== 30 ){$('#gebesonuc').val(8)}
                 if (t>=1251 && gebelik== 29 ){$('#gebesonuc').val(3)}
                 if (t>=1001 && t<=1250 && gebelik== 29 ){$('#gebesonuc').val(5)}
                 if (t>=751 && t<=1000 && gebelik== 29 ){$('#gebesonuc').val(6)}
                 if (t>=501 && t<=750 && gebelik== 29 ){$('#gebesonuc').val(8)}
                 if (t>=1251 && gebelik== 28 ){$('#gebesonuc').val(5)}
                 if (t>=1001 && t<=1250 && gebelik== 28 ){$('#gebesonuc').val(6)}
                 if (t>=751 && t<=1000 && gebelik== 28 ){$('#gebesonuc').val(7)}
                 if (t>=501 && t<=750 && gebelik== 28 ){$('#gebesonuc').val(8)}
                 if (t>=251 && t<=500 && gebelik== 28 ){$('#gebesonuc').val(1)}
                 if (t>=1251 && gebelik== 27 ){$('#gebesonuc').val(6)}
                 if (t>=751 && t<=1250 && gebelik== 27 ){$('#gebesonuc').val(7)}
                 if (t>=501 && t<=750 && gebelik== 27 ){$('#gebesonuc').val(9)}
                 if (t>=251 && t<=500 && gebelik== 27 ){$('#gebesonuc').val(1)}
                 if (t>=751 && gebelik== 26 ){$('#gebesonuc').val(8)}
                 if (t>=501 && t<=750 && gebelik== 26 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 26 ){$('#gebesonuc').val(1)}
                 if (t>=1001  && gebelik== 25 ){$('#gebesonuc').val(9)}
                 if (t>=751 && t<=1000 && gebelik== 25 ){$('#gebesonuc').val(1)}
                 if (t>=501 && t<=750 && gebelik== 25 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 25 ){$('#gebesonuc').val(1)}
                 if (t>=1001  && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=751 && t<=1000 && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=501 && t<=750 && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=751  && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=501 && t<=750 && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=501  && gebelik== 22 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 22 ){$('#gebesonuc').val(1)}
             }
             else{

                 if (t>=1501 && gebelik== 32 ){$('#gebesonuc').val(0)}
                 if (t>=1251 && t<=1500 && gebelik== 32 ){$('#gebesonuc').val(1)}
                 if (t>=1001 && t<=1250 && gebelik== 32 ){$('#gebesonuc').val(3)}
                 if (t>=751 && t<=1000 && gebelik== 32 ){$('#gebesonuc').val(5)}
                 if (t>=2501 && gebelik== 31 ){$('#gebesonuc').val(1)}
                 if (t>=1501 && t<=2500 && gebelik== 31 ){$('#gebesonuc').val(0)}
                 if (t>=1251 && t<=1500 && gebelik== 31 ){$('#gebesonuc').val(1)}
                 if (t>=1001 && t<=1250 && gebelik== 31 ){$('#gebesonuc').val(3)}
                 if (t>=751 && t<=1000 && gebelik== 31 ){$('#gebesonuc').val(5)}
                 if (t>=501 && t<=750 && gebelik== 31 ){$('#gebesonuc').val(7)}
                 if (t>=2251 && gebelik== 30 ){$('#gebesonuc').val(2)}
                 if (t>=1501 && t<=2250 && gebelik== 30 ){$('#gebesonuc').val(1)}
                 if (t>=1251 && t<=1500 && gebelik== 30 ){$('#gebesonuc').val(2)}
                 if (t>=1001 && t<=1250 && gebelik== 30 ){$('#gebesonuc').val(3)}
                 if (t>=751 && t<=1000 && gebelik== 30 ){$('#gebesonuc').val(5)}
                 if (t>=501 && t<=750 && gebelik== 30 ){$('#gebesonuc').val(7)}
                 if (t>=1251 && gebelik== 29 ){$('#gebesonuc').val(3)}
                 if (t>=1001 && t<=1250 && gebelik== 29 ){$('#gebesonuc').val(4)}
                 if (t>=751 && t<=1000 && gebelik== 29 ){$('#gebesonuc').val(5)}
                 if (t>=501 && t<=750 && gebelik== 29 ){$('#gebesonuc').val(7)}
                 if (t>=1251 && gebelik== 28 ){$('#gebesonuc').val(4)}
                 if (t>=1001 && t<=1250 && gebelik== 28 ){$('#gebesonuc').val(5)}
                 if (t>=751 && t<=1000 && gebelik== 28 ){$('#gebesonuc').val(6)}
                 if (t>=501 && t<=750 && gebelik== 28 ){$('#gebesonuc').val(8)}
                 if (t>=251 && t<=500 && gebelik== 28 ){$('#gebesonuc').val(1)}
                 if (t>=1501 && gebelik== 27 ){$('#gebesonuc').val(6)}
                 if (t>=1251 && t<=1500 && gebelik== 27 ){$('#gebesonuc').val(5)}
                 if (t>=1001 && t<=1250 && gebelik== 27 ){$('#gebesonuc').val(6)}
                 if (t>=751 && t<=1000 && gebelik== 27 ){$('#gebesonuc').val(7)}
                 if (t>=501 && t<=750 && gebelik== 27 ){$('#gebesonuc').val(8)}
                 if (t>=251 && t<=500 && gebelik== 27 ){$('#gebesonuc').val(1)}
                 if (t>=1001 && gebelik== 26 ){$('#gebesonuc').val(7)}
                 if (t>=751 && t<=1000 && gebelik== 26 ){$('#gebesonuc').val(8)}
                 if (t>=501 && t<=750 && gebelik== 26 ){$('#gebesonuc').val(9)}
                 if (t>=251 && t<=500 && gebelik== 26 ){$('#gebesonuc').val(1)}
                 if (t>=1001  && gebelik== 25 ){$('#gebesonuc').val(8)}
                 if (t>=751 && t<=1000 && gebelik== 25 ){$('#gebesonuc').val(9)}
                 if (t>=501 && t<=750 && gebelik== 25 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 25 ){$('#gebesonuc').val(1)}
                 if (t>=751  && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=501 && t<=750 && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 24 ){$('#gebesonuc').val(1)}
                 if (t>=751  && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=501 && t<=750 && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 23 ){$('#gebesonuc').val(1)}
                 if (t>=501  && gebelik== 22 ){$('#gebesonuc').val(1)}
                 if (t>=251 && t<=500 && gebelik== 22 ){$('#gebesonuc').val(1)}
             }
        });
        $(".crib_change").change(function () {
            var ates =($("#ates option:selected").attr("data-id")!='' || $("#ates option:selected").attr("data-id")!=null) ? Number($("#ates option:selected").attr("data-id")) : 0;
            var excess =($("#excess option:selected").attr("data-id")!='' || $("#excess option:selected").attr("data-id")!=null) ? Number($("#excess option:selected").attr("data-id")) : 0;
            var gebesonuc=$('#gebesonuc').val();
            var sonuc=Number(ates)+Number(excess)+Number(gebesonuc);
            var islem1=sonuc*0.45;
            var islem2=islem1-6.476;
            var islem3 = Math.exp(islem2)/(1 + Math.exp(islem2))
            var islemson = Fmt(100 * islem3)+" %"
            $(".crib").val(sonuc);
            $("#crib").val(sonuc);
            $(".crib2").val(islemson);
            $("#crib2").val(islemson);


        });
        // function CalcMORT(form) {
        //     z = eval(form.zcrib.value)
        //     z = z*0.45
        //     z = z- 6.476
        //     z = Math.exp(z)/(1 + Math.exp(z))
        //     z = Fmt(100 * z)+" %"
        //     form.zmort.value= z
        //     return z
        // }


        </script>

    </div>
    </div>

<?php }
else if ($islem == "euroscore2") {
    $protokol_no = $_GET['protokolno'];
    $hastakayit = singularactive("patient_registration", "protocol_number", $protokol_no);
    $patients = singularactive("patients", "id", $hastakayit['patient_id']);
    ?>

    <form id="formprism" action="javascript:void(0);">
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Yaş</label>
                    <div class="col-md-9">
                        <input type="hidden" class="form-control" name="protokol_number"
                               value="<?php echo $hastakayit['protocol_number']; ?>" id="protokol_number">
                        <input type="hidden" class="form-control" name="patient_number"
                               value="<?php echo $hastakayit['patient_id']; ?>" id="patient_number">

                        <input type="number" min="0" max="150" class="form-control" placeholder="Yaş giriniz.." name="age" id="ageeuro2">

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Cinsiyet</label>
                    <div class="col-md-9">
                        <select class="form-select" name="sex"
                                id="gendereuro2" onChange="CalcSEX(this.form)">
                            <option value="0">Cinsiyet seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Cinsiyet' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Böbrek yetmezliği</label>
                    <div class="col-md-9">
                        <select class="form-select" name="creat" id="kidney_failure" onchange="CalcCREAT(this.form)">
                            <option value="0">Böbrek yetmezliği seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Böbrek yetmezliği' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Ekstrakardiyak arteriyopati</label>
                    <div class="col-md-9">
                        <select class="form-select" name="arterio" id="extracardiac_arteriopathy" onChange="CalcARTERIO(this.form)">
                            <option value="0">Ekstrakardiyak arteriyopati seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Ekstrakardiyak arteriyopati' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) {

                                ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Hareket zayıflığı</label>
                    <div class="col-md-9">
                        <select class="form-select" name="neuro"
                                id="weakness_movement" onChange="CalcNEURO(this.form)">
                            <option value="0">Hareket zayıflığı seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Hareket zayıflığı' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) {
                                echo "ihsancnd";
                                var_dump($value);
                                ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Kalp ameliyatı öyküsü</label>
                    <div class="col-md-9">
                        <select class="form-select" name="redux" id="heart_surgery_history" onChange="CalcREDUX(this.form)">
                            <option value="0">Kalp ameliyatı öyküsü seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kalp ameliyatı öyküsü' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Kronik akciğer hastalığı</label>
                    <div class="col-md-9">
                        <select class="form-select" name="copd"
                                id="chronic_lung_disease" onChange="CalcCOPD(this.form)">
                            <option value="0">Kronik akciğer hastalığı seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kronik akciğer hastalığı' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Aktif endokardit</label>
                    <div class="col-md-9">
                        <select class="form-select" name="endo" id="active_endocarditis" onChange="CalcENDO(this.form)">
                            <option value="0">Aktif endokardit seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Aktif endokardit' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Kritik preoperatif durum</label>
                    <div class="col-md-9">
                        <select class="form-select" name="critic"
                                id="critical_preoperative_situation" onChange="CalcCRITIC(this.form)">
                            <option value="0">Kritik preoperatif seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Kritik preoperatif durum' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">İnsulin bağımlı diyabet</label>
                    <div class="col-md-9">
                        <select class="form-select" name="newtest" id="insulin_dependent_diabetes" onChange="CalcNEWTEST(this.form)">
                            <option value="0">İnsulin bağımlı diyabet seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='İnsulin bağımlı diyabet' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">NYHA</label>
                    <div class="col-md-9">
                        <select class="form-select" name="septum" id="nyha" onChange="CalcSEPTUM(this.form)">
                            <option value="0">NYHA seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='NYHA' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">CCS class 4 angina</label>
                    <div class="col-md-9">
                        <select class="form-select" name="angor" id="ccs_class_4" onChange="CalcANGOR(this.form)">
                            <option value="0">CCS class 4 angina seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='CCS class 4 angina' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">LV fonksiyonu</label>
                    <div class="col-md-9">
                        <select class="form-select" name="lvef" id="lv_function" onChange="CalcLVEF(this.form)">
                            <option value="0">LV fonksiyonu seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='LV fonksiyonu' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Geçirilmiş MI</label>
                    <div class="col-md-9">
                        <select class="form-select" name="idm" id="past_mi" onChange="CalcIDM(this.form)">
                            <option value="0">Geçirilmiş mi seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Geçirilmiş MI' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Pulmoner hipertansiyon</label>
                    <div class="col-md-9">
                        <select class="form-select" name="pap" id="pulmonary_hypertension" onChange="CalcPAP(this.form)">
                            <option value="0">Pulmoner hipertansiyon seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Pulmoner hipertansiyon' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">	Aciliyet</label>
                    <div class="col-md-9">
                        <select class="form-select" name="urg" id="urgency" onChange="CalcURG(this.form)">
                            <option value="0">Aciliyet seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Aciliyet' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class=" row mt-1">
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Müdahelenin ağırlığı</label>
                    <div class="col-md-9">
                        <select class="form-select" name="autre" id="weight_intervention" onChange="CalcAUTRE(this.form)">
                            <option value="0">Müdahelenin ağırlığı seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Müdahelenin ağırlığı' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row mx-2">
                    <label class="form-label col-md-3">Torasik Aorta cerrahisi</label>
                    <div class="col-md-9">
                        <select class="form-select" name="tho" id="thoracic_aorta_surgery" onChange="CalcTHO(this.form)">
                            <option value="0">Torasik Aorta cerrahisi seçiniz..</option>
                            <?php $bolumgetir = "select * from saps_definition WHERE status='1' AND definition_name='Torasik Aorta cerrahisi' AND definition_module='EuroSCORE II'";

                            $hello = verilericoklucek($bolumgetir);
                            foreach ((array)$hello as $value) { ?>

                                <option data-id="<?php echo $value["id"]; ?>" value="<?php echo $value["definition_value"]; ?>"><?php echo $value['definition_key']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="zsex">
        <input type="hidden" id="zage">
        <input type="hidden" id="zurg">
        <input type="hidden" id="zcreat">
        <input type="hidden" id="zlvef">
        <input type="hidden" id="zcopd">
        <input type="hidden" id="ztho">
        <input type="hidden" id="zarterio">
        <input type="hidden" id="zneuro">
        <input type="hidden" id="zendo">

        <input type="hidden" id="zcritic">
        <input type="hidden" id="znewtest">
        <input type="hidden" id="zangor">
        <input type="hidden" id="zidm">
        <input type="hidden" id="zpap">
        <input type="hidden" id="zredux">

        <input type="hidden" id="zseptum">
        <input type="hidden" id="zautre">

        <select hidden="" onchange="CalcSEX(this.form);CalcURG(this.form);CalcCREAT(this.form);CalcLVEF(this.form);CalcCOPD(this.form);
                    CalcTHO(this.form);CalcARTERIO(this.form);CalcNEURO(this.form);CalcENDO(this.form);CalcCRITIC(this.form);CalcANGOR(this.form);CalcIDM(this.form);
                    CalcPAP(this.form);CalcREDUX(this.form);CalcSEPTUM(this.form);CalcAUTRE(this.form)" name="logadd" size="1">
            <option value="1" hidden="">EuroSCORE II</option>
            <option value="1" selected="selected" hidden="">2011 EuroSCORE II</option>
        </select>
        <div class=" row mt-1">
            <div class="col-md-3">
                <div class="row">
                    <label class="form-label col-md-4 mt-2"> EuroSCORE II</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="zmort" id="conclusion_euroscore2">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <input type="submit" class="btn kps-btn" onclick="CalcMorteuro2(form)" value="Hesapla">
                </div>
            </div>

        </div>
    </form>

    <div class="modal-footer py-0">
        <div class="row mx-1">
            <input type="submit" class="form-control up-btn btn-sm" value="Kaydet" id="euroscore2btn">
        </div>
    </div>

    <script language="javascript">

        function CalcSEX(form) {

            i = form.sex[form.sex.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=1 } else { k=0 } }
            form.zsex.value = k

        }

        function CalcURG(form) {

            i = form.urg[form.urg.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zurg.value  = k

        }
        function CalcCREAT(form) {
            i = form.creat[form.creat.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zcreat.value  = k

        }
        function CalcLVEF(form) {

            i = form.lvef[form.lvef.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.8) { k=3 } else { { if(i>0.1) { k=1 } else { k=0 } } } }
            form.zlvef.value   = k


        }
        function CalcCOPD(form) {
            i = form.copd[form.copd.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=1 } else { k=0 } }
            form.zcopd.value  = k


        }
        function CalcTHO(form) {

            i = form.tho[form.tho.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=3 } else { k=0 } }
            form.ztho.value  = k


        }
        function CalcARTERIO(form) {

            i = form.arterio[form.arterio.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zarterio.value  = k

        }
        function CalcNEURO(form) {

            i = form.neuro[form.neuro.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zneuro.value   = k

        }
        function CalcENDO(form) {

            i = form.endo[form.endo.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=3 } else { k=0 } }
            form.zendo.value    = k

        }
        function CalcCRITIC(form) {

            i = form.critic[form.critic.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=3 } else { k=0 } }
            form.zcritic.value = k

        }function CalcNEWTEST(form) {

            i = form.newtest[form.newtest.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=3 } else { k=0 } }
            form.znewtest.value  = k

        }
        function CalcANGOR(form) {

            i = form.angor[form.angor.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zangor.value  = k

        }
        function CalcIDM(form) {

            i = form.idm[form.idm.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zidm.value  = k

        }

        function CalcPAP(form) {

            i = form.pap[form.pap.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zpap.value   = k

        }

        function CalcREDUX(form) {

            i = form.redux[form.redux.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=3 } else { k=0 } }
            form.zredux.value    = k

        }

        function CalcSEPTUM(form) {

            i = form.septum[form.septum.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=4 } else { k=0 } }
            form.zseptum.value    = k

        }

        function CalcAUTRE(form) {

            i = form.autre[form.autre.selectedIndex].value
            j = eval(form.logadd.value)
            if(j>0.5) { k=i } else { if(i>0.1) { k=2 } else { k=0 } }
            form.zautre.value    = k
        }



        function CalcMorteuro2(form) {
            j = eval(form.logadd.value)
            if(j>0.5)
            {
                z = eval(form.zsex.value)
                z = z + eval(form.zurg.value)
                z = z + eval(form.zcreat.value)
                z = z + eval(form.zlvef.value)
                z = z + eval(form.zcopd.value)
                z = z + eval(form.ztho.value)
                z = z + eval(form.zarterio.value)
                z = z + eval(form.zneuro.value)
                z = z + eval(form.zendo.value)
                z = z + eval(form.zcritic.value)
                z = z + eval(form.znewtest.value)
                z = z + eval(form.zangor.value)
                z = z + eval(form.zidm.value)
                z = z + eval(form.zpap.value)
                z = z + eval(form.zredux.value)
                z = z + eval(form.zseptum.value)
                z = z + eval(form.zautre.value)
                t = eval(form.age.value)
                if (form.age.value==0) {alert("Age is missing")}
                if (form.age.value>90) {alert("Of over 20,000 patients in the EuroSCORE database, only 21 patients were aged over 90 - therefore the risk model may not be accurate in these patients. Please exercise clinical discretion in interpreting the score. The oldest patient in the EuroSCORE database was 95 - EuroSCORE II is not validated in patients over this age.")}
                t=t+1
                t = t*.0285181
                if (t<=1.711086) {t=.0285181}
                else {t = t-60*.0285181}
                form.zage.value= Fmt(t)
                z = t + z
                z = z-5.324537
                z = Math.exp(z) / (1 + Math.exp(z))
                z = Fmt(100 * z) + " %"
                form.zmort.value= z
                return z
                return t
            }
            else
            {
                z = eval(form.zsex.value)
                z = z + eval(form.zurg.value)
                z = z + eval(form.zcreat.value)
                z = z + eval(form.zlvef.value)
                z = z + eval(form.zcopd.value)
                z = z + eval(form.ztho.value)
                z = z + eval(form.zarterio.value)
                z = z + eval(form.zneuro.value)
                z = z + eval(form.zendo.value)
                z = z + eval(form.zcritic.value)
                z = z + eval(form.znewtest.value)
                z = z + eval(form.zangor.value)
                z = z + eval(form.zidm.value)
                z = z + eval(form.zpap.value)
                z = z + eval(form.zredux.value)
                z = z + eval(form.zseptum.value)
                z = z + eval(form.zautre.value)
                b = eval(form.age.value)
                if (b>104.9)
                {
                    c=10
                }
                else if (b>99.9)
                {
                    c=9
                }
                else if (b>94.9)
                {
                    c=8
                }
                else if (b>89.9)
                {
                    c=7
                }
                else if (b>84.9)
                {
                    c=6
                }
                else if (b>79.9)
                {
                    c=5
                }
                else if (b>74.9)
                {
                    c=4
                }
                else if (b>69.9)
                {
                    c=3
                }
                else if (b>64.9)
                {
                    c=2
                }
                else if (b>59.9)
                {
                    c=1
                }
                else
                {
                    c=0
                }
                form.zage.value= c
                z=z+c
                form.zmort.value= z

            }

        }

        function Fmt(x) {
            var v
            if(x>=0) { v=''+(x+0.005)} else { v=''+(x-0.005) }
            return v.substring(0,v.indexOf('.')+3)
        }

    </script>


    <script type="text/javascript">
        $("#euroscore2btn").on("click", function () {
            var age=$("#ageeuro2").val();
            var gender=$("#gendereuro2 option:selected").attr("data-id");
            var kidney_failure=$("#kidney_failure option:selected").attr("data-id");
            var extracardiac_arteriopathy=$("#extracardiac_arteriopathy option:selected").attr("data-id");
            var weakness_movement=$("#weakness_movement option:selected").attr("data-id");
            var heart_surgery_history=$("#heart_surgery_history option:selected").attr("data-id");
            var chronic_lung_disease=$("#chronic_lung_disease option:selected").attr("data-id");
            var active_endocarditis=$("#active_endocarditis option:selected").attr("data-id");
            var critical_preoperative_situation=$("#critical_preoperative_situation option:selected").attr("data-id");
            var insulin_dependent_diabetes=$("#insulin_dependent_diabetes option:selected").attr("data-id");
            var nyha=$("#nyha option:selected").attr("data-id");
            var ccs_class_4=$("#ccs_class_4 option:selected").attr("data-id");
            var lv_function=$("#lv_function option:selected").attr("data-id");
            var past_mi=$("#past_mi option:selected").attr("data-id");
            var pulmonary_hypertension=$("#pulmonary_hypertension option:selected").attr("data-id");
            var urgency=$("#urgency option:selected").attr("data-id");
            var weight_intervention=$("#weight_intervention option:selected").attr("data-id");
            var thoracic_aorta_surgery=$("#thoracic_aorta_surgery option:selected").attr("data-id");


            //var conclusion_prism_death=$("#conclusion_prism_death").val();
            var conclusion_euroscore2=$("#conclusion_euroscore2").val();
            var protokol_number=$("#protokol_number").val();
            var patient_number=$("#patient_number").val();
            if (conclusion_euroscore2!=''){
                $.ajax({
                    type: 'POST',
                    url: 'ajax/skorislem.php?islem=euroscore2',
                    data: {age:age,gender:gender,kidney_failure:kidney_failure,extracardiac_arteriopathy:extracardiac_arteriopathy,weakness_movement:weakness_movement,
                        heart_surgery_history:heart_surgery_history,chronic_lung_disease:chronic_lung_disease,active_endocarditis:active_endocarditis,critical_preoperative_situation:critical_preoperative_situation,
                        insulin_dependent_diabetes:insulin_dependent_diabetes,nyha:nyha,ccs_class_4:ccs_class_4,lv_function:lv_function,past_mi:past_mi,pulmonary_hypertension:pulmonary_hypertension,
                        urgency:urgency,weight_intervention:weight_intervention,thoracic_aorta_surgery:thoracic_aorta_surgery,conclusion_euroscore2:conclusion_euroscore2,protokol_number:protokol_number,patient_number:patient_number},
                    success: function (e) {
                        $('#sonucyaz').html(e);
                        $.get("ajax/skor_listeleri.php?islem=euroscore2_listesi", {protokolno: protokol_number}, function (getVeri) {
                            $('.euroscore2_listesi').html(getVeri);

                        });

                    }
                });
            }else{
                alertify.warning("Hesaplama butonuna tıklayınız..")
            }


        });
    </script>


    </div>
    </div>

<?php }
?>




