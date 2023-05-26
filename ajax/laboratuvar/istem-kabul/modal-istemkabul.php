
<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="modal_istem_kabul_hasta_kart"){
    $id = $_GET['id'];

    $hasta_hizmet_istem=singularactive("patient_service_requests","id",$id);
    $protokol=$hasta_hizmet_istem["protocol_id"];

    $hasta_kayit=singularactive("patient_registration","protocol_number",$protokol);
    $hasta_id=$hasta_kayit["patient_id"];

    $hasta=singularactive("patients","id",$hasta_id);
    $dogum=$hasta['birth_date'];

    $YAS = ikitariharasindakiyilfark($simdikitarih,$dogum);?>

    <div class="col-12 row mt-3">
                <div class="col-3 align-self-center">

                    <div class="card border-0 bg-transparent">
                        <div class="text-center">
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
                <div class="col-9 align-self-center">

                    <h6 class="contentTitle">Hasta Bilgileri</h6>
                    <div class="card bg-transparent patientInformation p-2" style="height:85%;">
                        <div class="row">

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Hasta Adı</label>
                                <input class="form-control" type="text" readonly value="<?php echo ucwords($hasta["patient_name"] . " " . $hasta["patient_surname"]) ?> (<?php echo $YAS ?>)">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Kur Kodu</label>
                                <?php
                                $sosyalguvence = tek("select * from transaction_definitions where definition_code='{$hasta["social_assurance"]}'");
                                $kurumgetir = tek("select * FROM transaction_definitions where definition_code='{$hasta["institution"]}'");
                                ?>
                                <input class="form-control" type="text" readonly value="<?php echo $kurumgetir["definition_name"] . " / " . $sosyalguvence["definition_name"] ?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">TC Kimlik No</label>
                                <input class="form-control" type="text" readonly value="<?php echo $hasta["tc_id"] ?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Protokol No</label>
                                <input class="form-control" type="text"  readonly value=" <?php echo $hasta_kayit["protocol_number"]; ?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Telefon</label>
                                <input class="form-control" type="text" readonly value="<?php echo $hasta["phone_number"];?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Anne Adı</label>
                                <input class="form-control" type="text" readonly value="<?php echo ucwords($hasta["mother"]); ?>" >
                            </div>

<!--                            <div class="col-xl-6 d-flex align-items-center mb-2">-->
<!--                                <label class="col-xl-3">Takip No</label>-->
<!--                                <input class="form-control" type="text" readonly value="--><?php //echo $hasta_kayit["provision_number"] ?><!--">-->
<!--                            </div>-->

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Doğum Tarihi</label>
                                <input class="form-control" type="text" readonly value="<?php echo duztarih($hasta["birth_date"]); ?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Yatış Tarihi</label>
                                <input class="form-control" type="text" readonly value="<?php echo nettarih($hasta_kayit["hospitalized_accepted_datetime"]);?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Kan Grubu</label>
                                <input class="form-control" type="text" readonly value="<?php echo islemtanimgetirid($hasta["blood_group"]);?>">
                            </div>

                            <div class="col-xl-6 d-flex align-items-center mb-2">
                                <label class="col-xl-3">Hasta Notu</label>
                                <textarea class="form-control " readonly rows="1"><?php echo $hasta["reminders"]?></textarea>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


<?php }

else if ($islem=="modal_istem_kabul_randevu"){
    $id = $_GET['id']; ?>


    <div class="col-12 row mt-2">
                <div class="col-6">

                    <div id="istem-kabul-rendevulu-hasta-liste"></div>
                    <script>
                        $.get( "/ajax/laboratuvar/istem-kabul/tablo-istemkabul.php?islem=randevu_tablo",function(getveri){
                            $('#istem-kabul-rendevulu-hasta-liste').html(getveri);
                        });
                    </script>

                </div>
                <div class="col-6">

                    <div class="mx-5" id="calendar"></div>

                    <script>
                        $.get( "/ajax/laboratuvar/istem-kabul/calendar-istem-kabul.php?islem=istem-kabul-calendar",function(getveri){
                            $('#calendar').html(getveri);
                        });
                    </script>

                </div>
            </div>


<?php } ?>