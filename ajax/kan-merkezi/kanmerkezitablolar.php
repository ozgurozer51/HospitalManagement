<?php
include "../../controller/fonksiyonlar.php";
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$islem=$_GET["islem"];

//KAN TALEP TABLOSU
if($islem=="kantalepistem"){
    $gelen_veri = $_GET['getir'];
    ?>
    <div class="col-m-4">

        <div class="col-m-2 mt-1 ">
            <button class="btn btn-pink kantalep_ekle" hidden type="button" data-target="#kantalep_ekle_modal" data-toggle="modal">
                <img src="assets/icons/Rh+.png" alt="icon" width="30px">
                <label for="">Kan Talep Et</label>
            </button>
        </div>
        <div class="col-md-12 mt-2">
            <select class=" kan_talep_durum form-select" name="blood_demand_status" id="blood_demand_status" title="kan talebi i̇çin aciliyet seviyesini belirtiniz...">
                <option selected disabled class="text-white bg-danger">Durum seçiniz..</option>
                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_TALEP_DURUM' and transaction_definitions.status='1'");
                foreach ($sql as $item){ ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="col-12 mt-2 row">
    <div class="col-md-6 flex-column  ">
        <label for="">Başlangıç Tarihi</label>
        <input class="form-control" type="date" id="blood_demand_time" name="blood_demand_time">
    </div>
    <div class="col-md-6 flex-column ">
        <label for="">Bitiş Tarihi</label>
        <input class="form-control" type="date" id="blood_demand_time" name="blood_demand_time">
    </div>
    </div>

    <div class="col-m-11 mt-2" >
        <div class="card institution p-8">
            <div class="secilentab"></div>
            <div class="card-body mx-1 ">
            <div class="kan_talep_durum_filtre">
                <table class="table table table-bordered nowrap display w-100" id="istemdurumufiltreleme">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>ÖNCELİK</th>
                        <th>AD SOYAD</th>
                        <th>İSTEM TARİHİ</th>
                        <th>DURUM</th>
                        <th>DOKTOR</th>
                        <th>SERVİS</th>
                        <th>PROTOKOL NO</th>
                        <th>DOĞUM TARİHİ</th>
                    </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                    <?php
                    $kantalepliste=verilericoklucek("select * from blood_demand,patients where blood_demand.patient_id=patients.id and blood_demand.status='1'");
                    foreach ($kantalepliste as $rowa ) {
                        $kanaciliyetseviyesi=$rowa["blood_urgency_level"];
                        $kantalepdurum=$rowa["blood_demand_status"]; ?>
                        <tr style="cursor: pointer;" id="<?php echo $rowa["blood_demandid"]; ?>" class="kantalepsec">
                            <td><input class="form-check-input kantalepsecradiobutton" type="radio"  id="<?php echo $rowa["blood_demandid"]; ?>"></td>
                            <td><?php if ($kanaciliyetseviyesi!=''){echo islemtanimgetirid($kanaciliyetseviyesi); } ?></td>
                            <td><?php echo hastalaradi($rowa["patient_id"]), " ", hastalarsoyadi($rowa["patient_id"]);?></td>
                            <td><?php $parca = explode("t", nettarih($rowa["blood_demand_time"])); echo nettarih($rowa["blood_demand_time"])." ".$parca[1] ?></td>
                            <td <?php if($rowa["blood_demand_status"]=="28832" /*onaylandı*/ ) {?> style="color: green; font-weight: bold;
 " <?php } else if ($rowa["blood_demand_status"]=="28861" /*rezerve*/ ){?> style="color:#e3c211; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28862"/*karşılandı*/ ){?> style="color:#20df20; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28863"/*reddedildi*/ ){?> style="color: red; font-weight: bold; " <?php } ?>><?php if ($kantalepdurum!=''){echo islemtanimgetirid($kantalepdurum); } ?></td>
                            <td><?php echo kullanicigetirid($rowa["requester_physicianid"]); ?></td>
                            <td><?php echo birimgetirid($rowa["blood_want_unitid"]); ?></td>
                            <td><?php echo $rowa["patient_protocol_no"]; ?></td>
                            <td><?php $dparca = explode(" ", nettarih($rowa["birth_date"]));
                                echo $dparca[0]; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>


    <script>
        // KAN TALEP SEÇ RADİO BUTTON
        $(".kantalepsec").click(function () {
            $(".kantalepsecradiobutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", {getir: getir}, function (getveri) {
                $('#kantalepkarsilamalistesi').html(getveri);
            });
        });

        $(document).ready(function(){
            // KAN TALEP FİLTRELEME
            $(".kan_talep_durum").change(function () {
                var kantalepdurum =$(this).val();
                $.ajax({
                    type: "post",
                    url: "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_durum_filtre",
                    data: {"kantalepdurum": kantalepdurum},
                    success: function (e) {
                        $(".kan_talep_durum_filtre").html(e);
                    }
                });
            });

            // KAN TALEP TABLOSU DATATABLE
            $('#istemdurumufiltreleme').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
            $('[id="' + <?php echo $gelen_veri ?> + '"]').prop("checked", true);
        });
    </script>
<?php }

//KAN TALEP DURUM FİLTRELEYİNCE GELEN TABLO
else if ($islem=="kan_durum_filtre"){
    $gelen_veri = $_POST['kantalepdurum'];
    ?>
    <table class="table m-0 table table-bordered nowrap display w-100" id="durumufiltreleme">
        <thead>
        <tr>
            <th>#</th>
            <th>ÖNCELİK</th>
            <th>AD SOYAD</th>
            <th>İSTEM TARİHİ</th>
            <th>DURUM</th>
            <th>DOKTOR</th>
            <th>SERVİS</th>
            <th>PROTOKOL NO</th>
            <th>DOĞUM TARİHİ</th>
        </tr>
        </thead>
        <tbody style="cursor: pointer;">
        <?php
        $say=0;
        $kantalepliste=verilericoklucek("select * from blood_demand,patients where blood_demand.patient_id=patients.id and blood_demand.blood_demand_status='$gelen_veri' and blood_demand.status='1' ");
        foreach ($kantalepliste as $rowa ) {
            $kanaciliyetseviyesi=$rowa["blood_urgency_level"];
            $kantalepdurum=$rowa["blood_demand_status"];
            ?>
            <tr style="cursor: pointer;" id="<?php echo $rowa["blood_demandid"]; ?>" class="kantalepsec">
                <td><input class="form-check-input kantalepsecradiobutton" type="radio"  id="<?php echo $rowa["blood_demandid"]; ?>"></td>
                <td><?php if ($kanaciliyetseviyesi!=''){echo islemtanimgetirid($kanaciliyetseviyesi); } ?></td>
                <td><?php echo hastalaradi($rowa["patient_id"]), " ", hastalarsoyadi($rowa["patient_id"]);?></td>
                <td><?php $parca = explode("t", nettarih($rowa["blood_demand_time"])); echo nettarih($rowa["blood_demand_time"])." ".$parca[1] ?></td>
                <td <?php if($rowa["blood_demand_status"]=="28832" /*onaylandı*/ ) {?> style="color: green; font-weight: bold;
 " <?php } else if ($rowa["blood_demand_status"]=="28861" /*rezerve*/ ){?> style="color:#e3c211; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28862"/*karşılandı*/ ){?> style="color:#20df20; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28863"/*reddedildi*/ ){?> style="color: red; font-weight: bold; " <?php } ?>><?php if ($kantalepdurum!=''){echo islemtanimgetirid($kantalepdurum); } ?></td>
                <td><?php echo kullanicigetirid($rowa["requester_physicianid"]); ?></td>
                <td><?php echo birimgetirid($rowa["blood_want_unitid"]); ?></td>
                <td><?php echo $rowa["patient_protocol_no"]; ?></td>
                <td><?php $dparca = explode(" ", nettarih($rowa["birth_date"]));
                        echo $dparca[0]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script>
        // KAN TALEP SEÇ RADİO BUTTON
        $(".kantalepsec").click(function () {
            $(".kantalepsecradiobutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", {getir: getir}, function (getveri) {
                $('#kantalepkarsilamalistesi').html(getveri);
            });
        });

        //KAN TALEP DURUM FİLTRELEYİNCE GELEN TABLO DATATABLE
        $(document).ready(function(){
            $('#durumufiltreleme').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
            $('[id="' + <?php echo $gelen_veri ?> + '"]').prop("checked", true);
        } );
    </script>
<?php }

//KAN TALEP KARŞILAMA BUTTONLARI
else if ($islem=="kantalepkarsilama"){
    $gelen_veri = $_GET['getir'];
    ?>
    <div class="row">
        <div class="col-4">
            <div class="mb-3">
                <?php  $gelendeger = singularactive("blood_demand","blood_demandid",$gelen_veri);

                //DURUMU BEKLİYOR İSE
                if ($gelendeger['blood_demand_status']=='28831'){?>

                    <button type="button" data-bs-target="#kantalepkarsilamodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="kantalepkarsila btn btn-pink ">
                    <img src="assets/icons/Handshake-Heart.png"  width="30px"> Karşıla
                    </button>

                    <button type="button" data-bs-target="#kantaleprezerveetmodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="kantaleprezerveet btn btn-add ">
                        <img src="assets/icons/calendar.png"  width="30px"> Rezerve Et
                    </button>

                    <button type="button" data-bs-target="#kantalepreddettmodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="kantalepreddet btn btn-red "> <img src="assets/icons/Close-Window.png"  width="30px">  Reddet </button>

                <?php }

                //DURUMU KARŞILANDI İSE
                else if($gelendeger['blood_demand_status']=='28862'){ ?>

                    <button type="button" data-bs-target="#kantalepkarsilagerialmodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal" class="kantalepkarsilagerial btn btn-red ">
                        <img src="assets/icons/Handshake-Heart.png"  width="30px"> Karşılama Geri Al
                    </button>

                <?php }

                //DURUMU REZERVE EDİLDİ İSE
                else if($gelendeger['blood_demand_status']=='28861'){ ?>

                    <button type="button" data-bs-target="#kantaleprezervekarsilamamodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="kantaleprezervekarsilama btn btn-pink "><img src="assets/icons/Handshake-Heart.png"  width="30px">  Karşıla</button>

                    <button type="button" data-bs-target="#kantaleprezervegerialmodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="kantaleprezervegerial btn btn-red ">
                        <img src="assets/icons/calendar.png"  width="30px"> Rezerve Geri Al
                    </button>

                <?php }

                //DURUMU REDDEDİLDİ İSE
                else if($gelendeger['blood_demand_status']=='28863'){ ?>

                    <button type="button" data-bs-target="#kantalepreddetgerialmodal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal" class="kantalepreddetgerial btn btn-red ">
                        <img src="assets/icons/Close-Window.png"  width="30px"> Ret Geri Al
                    </button>

                <?php } ?>

                <!-- KAN TALEP KARŞILA MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kantalepkarsilamodal" aria-hidden="true" >
                    <div class="modal-dialog" style="width: 90%; max-width: 95%;" id="kantalepkarsilamodalicerik"> </div>
                </div>

                <!-- KAN TALEP KARŞILA GERİ AL MODAL -->
                <div class="modal fade" id="kantalepkarsilagerialmodal" data-bs-backdrop="static" aria-hidden="true" >
                    <div class="modal-dialog modal-xl" id="kantalepkarsilagerialmodalicerik" > </div>
                </div>

                <!-- KAN TALEP REZERVE ET MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kantaleprezerveetmodal" aria-hidden="true" >
                    <div class="modal-dialog" style="width: 90%; max-width: 95%;" id="kantaleprezerveetmodalicerik" > </div>
                </div>

                <!-- KAN TALEP REZERVE İŞLEMİNİ KARŞILA MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kantaleprezervekarsilamamodal" aria-hidden="true" >
                    <div class="modal-dialog" style="width: 90%; max-width: 95%;" id="kantaleprezervekarsilamamodalicerik" > </div>
                </div>

                <!-- KAN TALEP REZERVE GERİ AL MODAL -->
                <div class="modal fade" id="kantaleprezervegerialmodal" data-bs-backdrop="static" aria-hidden="true" >
                    <div class="modal-dialog modal-xl"  id="kantaleprezervegerialmodalicerik" > </div>
                </div>

                <!-- KAN TALEP REDDET MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kantalepreddettmodal" aria-hidden="true" >
                    <div class="modal-dialog modal-lg"  id="kantalepreddetmodalicerik" > </div>
                </div>

                <!-- KAN TALEP REDDET GERİ AL MODAL -->
                <div class="modal fade" id="kantalepreddetgerialmodal" data-bs-backdrop="static" aria-hidden="true" >
                    <div class="modal-dialog modal-lg"  id="kantalepreddetgerialmodalicerik" > </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-m-8"
    <div class="row justify-content-between w-80 mx-auto mt-3">
        <div class="card institution p-8">
            <div class="card-body mx-1">
                <div class="secilentab"></div>
                <table class="table m-0 table table-bordered nowrap display w-100" id="kantalepcikislistesi" >
                    <thead class="table-light">
                    <tr>
                        <th>KAN GRUBU</th>
                        <th>KAN TÜRÜ</th>
                        <th>TEKTİK</th>
                        <th>İSTEM YAPAN PERSONEL</th>
                        <th>KARŞILAYAN PERSONEL</th>
                        <th>KARŞILAMA TARİHİ</th>
                        <th>RET TARİHİ</th>
                        <th>RET NEDENİ</th>
                        <th>REZERVE TARİHİ</th>
                        <th>TALEP DURUMU</th>
                        <th>ONAY DURUMU</th>
                        <th>ONAY TARİHİ</th>
                    </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                    <?php $kantalepliste=verilericoklucek("select * from blood_demand,patients where blood_demand.patient_id=patients.id and blood_demand.blood_demandid='$gelen_veri' and blood_demand.status='1' ");
                    foreach ((array) $kantalepliste as $rowa ) {
                        $kancikis = tek("select * from blood_out where blood_demandid='$gelen_veri' and blood_out.status='1'");
                        $kantalepdetay = tek("select * from blood_demand_detail where blood_demandid='$gelen_veri' and blood_demand_detail.status='1'");
                        $desired_blood_group=$rowa["desired_blood_group"];
                        $desired_blood_type=$rowa["desired_blood_type"];
                        $blood_demand_rejection_reason=$kantalepdetay["blood_demand_rejection_reason"];
                        $blood_demand_status=$rowa["blood_demand_status"];
                        $service_confirmation_status=$rowa["service_confirmation_status"]; ?>
                        <tr>
                            <td><?php if ($desired_blood_group!=''){echo islemtanimgetirid($desired_blood_group); } ?></td>
                            <td><?php if ($desired_blood_type!=''){echo islemtanimgetirid($desired_blood_type); } ?></td>
                            <td><?php echo "TEKTİK İSMİ"; ?></td>
                            <td><?php echo kullanicigetirid($rowa["staff_asking_for_blood"]);  ?></td>
                            <td><?php echo kullanicigetirid($kancikis["blood_exit_personnelid"]); ?></td>
                            <td><?php  $parca = explode("T", nettarih($kancikis["blood_out_time"])); echo nettarih($kancikis["blood_out_time"])." ".$parca[1] ?></td>
                            <td><?php  $parca = explode("T", nettarih($kantalepdetay["date_of_refusal"])); echo nettarih($kantalepdetay["date_of_refusal"])." ".$parca[1] ?></td>
                            <td><?php if ($blood_demand_rejection_reason!=''){echo islemtanimgetirid($blood_demand_rejection_reason); } ?></td>
                            <td><?php $parca = explode("T", nettarih($kancikis["reserved_date"])); echo  nettarih($kancikis["reserved_date"])." ".$parca[1] ?></td>
                            <td><?php if ($blood_demand_status!=''){echo islemtanimgetirid($blood_demand_status); } ?></td>
                            <td><?php if ($service_confirmation_status!=''){echo islemtanimgetirid($service_confirmation_status); } ?></td>
                            <td><?php  $parca = explode("T", nettarih($rowa["blood_demand_approval_date"])); echo nettarih($rowa["blood_demand_approval_date"])." ".$parca[1] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            // KAN TALEP KARŞILA MODAL SCRIPT
            $(".kantalepkarsila").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantalepkarsilama", { getir:getir },function(getveri){
                    $('#kantalepkarsilamodalicerik').html(getveri);
                });
            });

            // KAN TALEP KARŞILA GERİ AL MODAL SCRIPT
            $(".kantalepkarsilagerial").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantalepkarsilamagerial", { getir:getir },function(getveri){
                    $('#kantalepkarsilagerialmodalicerik').html(getveri);
                });
            });

            // KAN TALEP REZERVE ET MODAL SCRIPT
            $(".kantaleprezerveet").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantaleprezerveet", { getir:getir },function(getveri){
                    $('#kantaleprezerveetmodalicerik').html(getveri);
                });
            });

            // KAN TALEP REZERVE İŞLEMİNİ KARŞILA MODAL SCRIPT
            $(".kantaleprezervekarsilama").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantaleprezervekarsilama", { getir:getir },function(getveri){
                    $('#kantaleprezervekarsilamamodalicerik').html(getveri);
                });
            });

            // KAN TALEP REZERVE İŞLEMİNİ GERİ AL MODAL SCRIPT
            $(".kantaleprezervegerial").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantaleprezervegerial", { getir:getir },function(getveri){
                    $('#kantaleprezervegerialmodalicerik').html(getveri);
                });
            });

            // KAN TALEP REDDET MODAL SCRIPT
            $(".kantalepreddet").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantalepreddet", { getir:getir },function(getveri){
                    $('#kantalepreddetmodalicerik').html(getveri);
                });
            });

            // KAN TALEP REDDET GERİ AL MODAL SCRIPT
            $(".kantalepreddetgerial").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modalkantalepreddetgerial", { getir:getir },function(getveri){
                    $('#kantalepreddetgerialmodalicerik').html(getveri);
                });
            });

            // KAN TALEP İÇERİK TABLOSU DATATABLE SCRIPT
            $('#kantalepcikislistesi').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            } );
        });
    </script>
<?php }

//TANIMLAMALAR KAN TÜRÜ TABLOSU
else if($islem=="kan_turu_tablosu"){?>
    <div class="col-xl-4">
        <div class="card institutionOther p-3">
            <div class="row">
                <div class="col-m-4">
                    <div class="col-m-2 mt-1 ">
                    </div>
                </div>
                <div class="col-m-4">
                    <div class="d-flex justify-content-end align-items-end ">
                        <div class="text-center d-flex flex-column me-1">
                            <button class="btn btn-add1 kan_turu_ekle" data-bs-target="#kan_turu_ekle_modal" data-bs-toggle="modal">
                                <img src="assets/icons/Add.png" alt="icon" width="20px">Ekle
                            </button>
                        </div>
                        <div class="text-center d-flex flex-column me-1 ">
                            <button class="btn btn-edit kan_turu_duzenle" disabled data-bs-target="#kan_turu_duzenle_modal"    data-bs-toggle="modal">
                                <img src="assets/icons/Edit-text-file.png" alt="icon" width="20px">Düzenle
                            </button>
                        </div>
                        <div class="text-center d-flex flex-column me-1 " >
                            <button disabled class="btn btn-red kan_turu_sil" >
                                <img src="assets/icons/Delete.png" alt="icon" width="20px">Sil
                            </button>
                        </div>
                    </div>
                </div>


                <!-- KAN TÜRÜ EKLE MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kan_turu_ekle_modal" aria-hidden="true" >
                    <div class="modal-dialog" id="kan_turu_ekle_modal_icerik" > </div>
                </div>

                <!-- KAN TÜRÜ DÜZENLE MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="kan_turu_duzenle_modal" aria-hidden="true" >
                    <div class="modal-dialog" id="kan_turu_duzenle_modal_icerik" > </div>
                </div>

                <div class="col-m-11" >
                    <h6 class="contentTitle">Kayıtlı Tanım Listesi</h6>
                    <div class="card institution p-8">
                            <div class="secilentab"></div>
                            <table class="table m-0 table table-bordered nowrap display w-100" id="islemtanimkanturulistesi" >
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>TANIM ADI</th
                                </tr>
                                </thead>
                                <tbody style="cursor: pointer;">
                                <?php
                                $say=0;
                                $kanturulistesi=verilericoklucek("select * from transaction_definitions where transaction_definitions.definition_type='KAN_TURU' and transaction_definitions.status='1'");
                                foreach ($kanturulistesi as $rowa ) { ?>
                                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kanturusec">
                                        <td><input class="form-check-input kanturusecbutton" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                                        <td><?php echo $rowa["definition_name"];?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            // KAN TÜRÜ TABLO DATATABLE
            $('#islemtanimkanturulistesi').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            } );
        });

        // KAN TÜRÜ EKLE MODAL SCRIPT
        $(".kan_turu_ekle").click(function(){
            var getir = $(this).attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_kan_turu_ekle", { getir:getir },function(getveri){
                $('#kan_turu_ekle_modal_icerik').html(getveri);
            });
        });

        // KAN TÜRÜ DÜZENLE MODAL SCRIPT
        $(".kan_turu_duzenle").click(function(){
            var getir = $('.kanturusecbutton:checked').attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_kan_turu_duzenle", { getir:getir },function(getveri){
                $('#kan_turu_duzenle_modal_icerik').html(getveri);
            });
        });

        // KAN TÜRÜ SİL ALERTIFY
        $(document).on('click', '.kan_turu_sil', function () {
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kan Türü Silme Nedeni..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var id = $('.kanturusecbutton:checked').attr('id');
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_turu_tablosu", function(getveri){
                                $('#tanimlamalar_tablosu').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kan_turu_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Kan Türü Silme İşlemini Onayla"});
        });

        // KAN TÜRÜ TABLODA RADİO BUTTON SEÇ
        $(".kanturusec").click(function () {
            $(".kanturusecbutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $('.kan_turu_duzenle').prop("disabled" , false);
            $('.kan_turu_sil').prop("disabled" , false);
        });
    </script>

<?php }

//TANIMLAMALAR RET NEDENİ TABLOSU
else if($islem=="ret_nedenleri_liste"){?>
    <div class="col-xl-4">
        <div class="card institutionOther p-3" >
            <div class="row">
                <div class="col-m-4">
                    <div class="col-m-2 mt-1 ">
                    </div>
                </div>
                <div class="col-m-4">
                    <div class="d-flex justify-content-end align-items-end ">
                        <div class="text-center d-flex flex-column me-1">
                            <button class="btn btn-add1 ret_nedeni_ekle" data-bs-target="#ret_nedeni_ekle_modal" data-bs-toggle="modal"">
                                <img src="assets/icons/Add.png" alt="icon" width="20px">Ekle
                            </button>
                        </div>
                        <div class="text-center d-flex flex-column me-1">
                            <button class="btn btn-edit ret_nedeni_duzenle" disabled data-bs-target="#ret_nedeni_duzenle_modal" data-bs-toggle="modal">
                                <img src="assets/icons/Edit-text-file.png" alt="icon" width="20px">Düzenle
                            </button>
                        </div>
                        <div class="text-center d-flex flex-column me-1">
                            <button class="btn btn-red ret_nedeni_sil" disabled>
                                <img src="assets/icons/Delete.png" alt="icon" width="20px">Sil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RET NEDENİ EKLE MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="ret_nedeni_ekle_modal" aria-hidden="true" >
                    <div class="modal-dialog" id="ret_nedeni_ekle_modal_icerik" > </div>
                </div>

                <!-- RET NEDENİ DÜZENLE MODAL -->
                <div class="modal fade" data-bs-backdrop="static" id="ret_nedeni_duzenle_modal" aria-hidden="true" >
                    <div class="modal-dialog" id="ret_nedeni_duzenle_modal_icerik" > </div>
                </div>

                <div class="col-m-11" >
                    <h6 class="contentTitle">Kayıtlı Tanım Listesi</h6>
                    <div class="card institution p-8">
                            <div class="secilentab"></div>
                            <table class="table m-0 table table-bordered nowrap display w-100" id="islem_tanim_ret_nedeni_listesi" >
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>TANIM ADI</th>
                                </tr>
                                </thead>
                                <tbody style="cursor: pointer;">
                                <?php
                                $say=0;
                                $retnedenistesi=verilericoklucek("select * from transaction_definitions where transaction_definitions.definition_type='KAN_TALEP_RET_NEDENI' and transaction_definitions.status='1'");
                                foreach ($retnedenistesi as $rowa ) { ?>
                                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="retnedenisec">
                                        <td><input class="form-check-input retnedenisecbutton" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                                        <td><?php echo $rowa["definition_name"];?></td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // RET NEDENİ TABLO RADİO BUTTON SEÇ
        $(".retnedenisec").click(function () {
            $(".retnedenisecbutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $('.ret_nedeni_duzenle').prop("disabled" , false);
            $('.ret_nedeni_sil').prop("disabled" , false);
        });

        // RET NEDENİ EKLE
        $(".ret_nedeni_ekle").click(function(){
            var getir = $(this).attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_ret_nedeni_ekle", { getir:getir },function(getveri){
                $('#ret_nedeni_ekle_modal_icerik').html(getveri);
            });
        });

        // RET NEDENİ DÜZENLE
        $(".ret_nedeni_duzenle").click(function(){
            var getir = $('.retnedenisecbutton:checked').attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_ret_nedeni_duzenle", { getir:getir },function(getveri){
                $('#ret_nedeni_duzenle_modal_icerik').html(getveri);
            });
        });

        // RET NEDENİ SİL
        $(document).on('click', '.ret_nedeni_sil', function () {
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Ret Nedeni Silme Nedeni..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var id = $('.retnedenisecbutton:checked').attr('id');
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=ret_nedenleri_liste", { },function(getveri){
                                $('#tanimlamalar_tablosu').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".ret_nedeni_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Ret Nedeni Silme İşlemini Onayla"});
        });

        $(document).ready(function(){
            // RET NEDENİ TABLO DATATABLE
            $('#islem_tanim_ret_nedeni_listesi').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            } );
        });
    </script>
<?php }

//DONÖR TABLOSU
else if($islem=="bagisci_kabul_islem"){
    $gelen_veri = $_GET['getir'];
    ?>
    <div class="row">
        <div class="col-m-4">
            <div class="col-m-2 mt-1 " id="donorislemleributton">
                <button class="btn btn-pink donor_ekle" data-bs-target="#donor_ekle_modal" data-bs-toggle="modal">
                    <img src="assets/icons/Blood-Donation.png" alt="icon" width="30px">
                    <label for="">Donör Ekle</label>
                </button>
            </div>
        </div>

        <!-- DONÖR EKLE MODAL -->
        <div class="modal fade" data-bs-backdrop="static" id="donor_ekle_modal" aria-hidden="true" >
            <div class="modal-dialog modal-xl" id="donor_ekle_modal_icerik" > </div>
        </div>

        <div class="card institutionOther p-2 fw-bold mt-1" style="background: #FFB3B3; width: 100%;">Donör Listesi</div>
        <div class="col>
            <input type="text" class="form-control mt-1" placeholder="Listede Ara" >
        </div>

        <div class="col-m-11 mt-1" >
            <div class="card institution p-8">
                    <div class="secilentab"></div>
                <div class="card-body mx-1">
                    <table class="table m-0 table table-bordered display nowrap w-100" id="kanbagisciliste" >
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>T.C. KİMLİK NO</th>
                            <th>AD-SOYAD</th>
                            <th>BAĞIŞÇI TÜRÜ</th>
                            <th>BAĞIŞ TÜRÜ</th>
                            <th>ACİL DURUMU</th>
                            <th>ONAY DURUMU</th>
                        </tr>
                        </thead>
                        <tbody style="cursor: pointer;">
                        <?php
                        $say=0;
                        $kanbagiscilistesi=verilericoklucek("select blood_donor.id as blooddonorid ,blood_donor.*,blood_donor_registration.* from blood_donor,blood_donor_registration where blood_donor.donor_registrationid=blood_donor_registration.id and blood_donor.status='1'");
                        foreach ($kanbagiscilistesi as $rowa ) {
                            $donor_type=$rowa["donor_type"];
                            $donated_blood_type=$rowa["donated_blood_type"];
                            $blood_donation_emergency_status_type=$rowa["blood_donation_emergency_status"];
                            $blood_donor_approval_status_type=$rowa["blood_donor_approval_status"];
                            $say++; ?>
                            <tr style="cursor: pointer;" id="<?php echo $rowa["blooddonorid"]; ?>" class="kanbagiscisec" >
                                <td><input class="form-check-input kanbagiscisecbutton" type="radio"  id="<?php echo $rowa["blooddonorid"]; ?>"> </td>
                                <td><?php echo $rowa["tc_id"];?></td>
                                <td><?php echo mb_strtoupper($rowa["name_surname"]);?></td>
                                <td><?php if ($donor_type!=''){echo mb_strtoupper(islemtanimgetir($donor_type)); } ?></td>
                                <td><?php if ($donated_blood_type!=''){echo mb_strtoupper(islemtanimgetir($donated_blood_type)); } ?></td>
                                <td><?php if ($blood_donation_emergency_status_type!=''){echo mb_strtoupper(islemtanimgetir($blood_donation_emergency_status_type)); } ?></td>
                                <td <?php if($rowa["blood_donor_approval_status"]=="28935" /*kabul edildi*/ ) {?> style="color: green; font-weight: bold;
 " <?php } else if ($rowa["blood_donor_approval_status"]=="28936" /*reddedildi*/ ){?> style="color:red; font-weight: bold; " <?php }?>><?php if ($blood_donor_approval_status_type!=''){echo mb_strtoupper(islemtanimgetir($blood_donor_approval_status_type)); } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <script>
        // DONÖR TABLOSU RADİO BUTTON SEÇ
        $(".kanbagiscisec").click(function () {
            $(".kanbagiscisecbutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=donor_islemleri_buttonlar",{getir: getir},function(getveri){
                $('#donorislemleributton').html(getveri);
            });
            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_rezerve_liste",{getir: getir},function(getveri){
                $('#bagisci_rezerve_listesi').html(getveri);
            });
        });

        // DONOR EKLE
        $(".donor_ekle").click(function(){
            var getir = $(this).attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_ekle", { getir:getir },function(getveri){
                $('#donor_ekle_modal_icerik').html(getveri);
            });
        });

        $(document).ready(function(){
            // DONOR TABLOSU DATATABLE
            $('#kanbagisciliste').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            } );
            $('[id="' + <?php echo $gelen_veri ?> + '"]').prop("checked", true);
        });
    </script>
<?php }

//DONÖR REZERVE HASTA LİSTESİ
else if($islem=="bagisci_rezerve_liste"){
    $gelen_veri = $_GET['getir']; ?>

    <div class="card institutionOther p-2 fw-bold mt-5" style="background: #FFB3B3; width: 100%;">Rezerve Hasta Listesi</div>
    <div class="col-xl-12">
        <div class="card p-3 mt-1">
            <div class="col-m-8"
            <div class="row justify-content-between w-80 mx-auto">
                <div class="card institution p-8 ">
                        <div class="secilentab"></div>
                        <table class="table m-0 table table-bordered nowrap display w-100" id="rezerve_list
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>HASTA AD-SOYAD</th>
                                <th>REZERVE TARİHİ</th>
                            </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                            <?php
                            $say=0;
                            $kantalepliste=verilericoklucek("select * from blood_out where blood_out.blood_out_donor_id='{$gelen_veri}' and blood_out.status='1' ");
                            foreach ($kantalepliste as $rowa ) {
                                $kanbagisci =tek("select * from blood where blood.id='{$gelen_veri}' and blood.status='1'");
                                $say++; ?>
                                <tr>
                                    <td><?php echo $say?></td>
                                    <td><?php echo hastalaradi($rowa["patient_id"])," ", hastalarsoyadi($rowa["patient_id"]);?></td>
                                    <td><?php echo nettarih($rowa["reserved_date"]);?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            // DONOR REZERVE HASTA TABLOSU DATATABLE
            $('#rezerve_liste').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            } );
        });
    </script>
<?php }

//DONÖR İŞLEMLERİ BUTTONLARI
else if ($islem=="donor_islemleri_buttonlar"){
    $gelen_veri=$_GET['getir'];

    $gelendeger=singularactive("blood_donor","id",$gelen_veri);

        if ($gelendeger['blood_donor_approval_status']=='28934'){?>

            <button class="btn btn-pink donor_ekle" data-bs-target="#donor_ekle_modal" data-bs-toggle="modal">
                <img src="assets/icons/Blood-Donation.png" alt="icon" width="30px">
                <label for="">Donör Ekle</label>
            </button>

            <button type="button" data-bs-target="#modal_donor_kabul_et" data-bs-toggle="modal" class="btn btn-ok donor_kabul_et">
                <img src="assets/icons/Ok.png" alt="icon" width="30px">
                <label for="">Kabul Et</label>
            </button>

            <button type="button" data-bs-target="#donor_reddet_modal" data-bs-toggle="modal" class="btn btn-add donor_reddet">
                <img src="assets/icons/Close-Window.png" alt="icon" width="30px">
                <label for="">Reddet</label>
            </button>

            <button type="button"  id="<?php echo $_GET['getir'];?>" class="donor_sil btn btn-red">
                <img src="assets/icons/Delete.png" alt="icon" width="30px">
                <label for="">Donör Sil </label>
            </button>

        <?php }

        else if ($gelendeger['blood_donor_approval_status']=='28935'){ ?>
            <button class="btn btn-pink donor_ekle" data-bs-target="#donor_ekle_modal" data-bs-toggle="modal">
                <img src="assets/icons/Blood-Donation.png" alt="icon" width="30px">
                <label for="">Donör Ekle</label>
            </button>

            <button type="button" data-bs-target="#donor_rezerve_et_modal" data-bs-toggle="modal" class="btn btn-add donor_rezerve_et" "><img src="assets/icons/calendar.png" width="30px">
                <label for="">Rezerve Et</label>
            </button>

            <button type="button" data-bs-target="#tektik_ekle_modal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal" class="btn btn-ok tektik_ekle">
                <img src="assets/icons/Add.png" alt="icon" width="30px">
                <label for="">Tektik Ekle</label>
            </button>

            <button type="button" data-bs-target="#donor_kabul_geri_al_modal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal"  class="donor_kabul_geri_al btn btn-red ">
                <img src="assets/icons/Close.png" alt="icon" width="30px">
                <label for="">Kabul İşlemini Geri Al</label></button>

        <?php }

        else if ($gelendeger['blood_donor_approval_status']=='28936'){ ?>
            <button class="btn btn-pink donor_ekle" data-bs-target="#donor_ekle_modal" data-bs-toggle="modal">
                <img src="assets/icons/Blood-Donation.png" alt="icon" width="30px">
                <label for="">Donör Ekle</label>
            </button>

            <button type="button" data-bs-target="#donor_ret_geri_al_modal" id="<?php echo $_GET['getir']; ?>" data-bs-toggle="modal" class="btn btn-red donor_ret_geri_al">
                <img src="assets/icons/Close.png" alt="icon" width="30px">
                <label for="">Ret İşlemi Geri Al</label>
            </button>

        <?php } ?>


    <!-- DONÖR EKLE MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="donor_ekle_modal" aria-hidden="true" >
        <div class="modal-dialog modal-xl" id="donor_ekle_modal_icerik" > </div>
    </div>

    <!-- DONÖR KABUL ET MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="modal_donor_kabul_et" aria-hidden="true" >
        <div class="modal-dialog modal-lg" id="modal_donor_kabul_et_icerik" > </div>
    </div>

    <!-- DONÖR REDDET MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="donor_reddet_modal" aria-hidden="true" >
        <div class="modal-dialog modal-lg" id="donor_reddet_modal_icerik" > </div>
    </div>

    <!-- DONÖR REZERVE ET MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="donor_rezerve_et_modal" aria-hidden="true" >
        <div class="modal-dialog modal-xl" style="width: 95%; max-width: 95%; " id="donor_rezerve_et_modal_icerik" > </div>
    </div>

    <!-- DONÖR KABUL GERİ AL MODAL  -->
    <div class="modal fade data-bs-backdrop="static" id="donor_kabul_geri_al_modal" aria-hidden="true" >
        <div class="modal-dialog modal-lg" id="donor_kabul_geri_al_modal_icerik" > </div>
    </div>

    <!-- DONÖR REDDET GERİ AL MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="donor_ret_geri_al_modal" aria-hidden="true" >
        <div class="modal-dialog modal-lg" id="donor_ret_geri_al_modal_icerik" > </div>
    </div>

    <script type="text/javascript">

            // DONÖR EKLE MODAL SCRIPT
            $(".donor_ekle").click(function(){
                var getir = $(this).attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_ekle", { getir:getir },function(getveri){
                    $('#donor_ekle_modal_icerik').html(getveri);
                });
            });

            // DONÖR KABUL ET MODAL SCRIPT
            $(".donor_kabul_et").click(function(){
                var getir = $('.kanbagiscisecbutton:checked').attr('id')
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_kabul_et", { getir:getir },function(getveri){
                    $('#modal_donor_kabul_et_icerik').html(getveri);
                });
            });

            // DONÖR REDDET MODAL SCRIPT
            $(".donor_reddet").click(function(){
                var getir = $('.kanbagiscisecbutton:checked').attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_reddet", { getir:getir },function(getveri){
                    console.log(getveri);
                    $('#donor_reddet_modal_icerik').html(getveri);
                });
            });

            // DONÖR REZERVE ET MODAL SCRIPT
            $(".donor_rezerve_et").click(function(){
                var getir = $('.kanbagiscisecbutton:checked').attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_rezerve_et", { getir:getir },function(getveri){
                    $('#donor_rezerve_et_modal_icerik').html(getveri);
                });
            });

            // DONÖR KABUL GERİ AL MODAL SCRIPT
            $(".donor_kabul_geri_al").click(function(){
                var getir = $('.kanbagiscisecbutton:checked').attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_kabul_geri_al", { getir:getir },function(getveri){
                    $('#donor_kabul_geri_al_modal_icerik').html(getveri);
                });
            });

            // DONÖR REDDET GERİ AL MODAL SCRIPT
            $(".donor_ret_geri_al").click(function(){
                var getir = $('.kanbagiscisecbutton:checked').attr('id');
                $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_ret_geri_al", { getir:getir },function(getveri){
                    $('#donor_ret_geri_al_modal_icerik').html(getveri);
                });
            });

        // DONÖR SİL ALERTIFY
        $(document).on('click', '.donor_sil', function () {
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Donörü Silme Nedeni..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var id=$('.donor_sil').attr("id");
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem",function(getveri){
                                $('#bagisci_kabul_islemleri').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".donor_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Donör Silme Silme İşlemini Onayla"});
        });
    </script>

<?php }

//KAN STOK EKLEME FORMU
else if($islem=="kan_stok_eklem_islemi"){ ?>
    <div class="row">
        <div class="col-m-12"
        <div class="row justify-content-between  mx-auto mt-3">
            <div class="card institution p-12">
                <div class="row mx-2">
                    <form action="javascript:void(0);" id="kan_stok_ekle_form">
                        <div class="col-md-12 row mt-2">

                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">Giriş Tarihi:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <input class="form-control" type="datetime-local" id="blood_stock_entry_date" name="blood_stock_entry_date">
                            </div>

                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">Stok Durumu:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <select class="form-select" name="blood_stock_status" id="blood_stock_status" >
                                    <option selected disabled class="text-white bg-danger">Kan stok durumu seçiniz.</option>
                                    <?php
                                    $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_STOK_DURUMU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-12 row mt-2">

                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">Kan Grubu:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <select class="form-select" name="blood_group" id="blood_group" >
                                    <option selected disabled class="text-white bg-danger">Kan grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_GRUBU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">Kan Türü:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <select class="form-select" name="blood_type" id="blood_type" >
                                    <option selected disabled class="text-white bg-danger">Kan türü seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_TURU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-12 row mt-2">
                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">ISBT Ünite Numarası:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <input class="form-control" type="text" id="isbt_unite_number" name="isbt_unite_number">
                            </div>

                            <div class="col-md-4 mt-2">
                                <label  for="recipient-name" class="col-form-label">Son Kullanım Tarihi:</label>
                            </div>
                            <div class="col-md-8 mt-2">
                                <input class="form-control" type="date" id="expiration_date" name="expiration_date"  >
                            </div>
                        </div>
                        <div class="col-m-6 d-flex align-items-center mb-2">
                        </div>
                        <div class="col-m-4 mt-2">
                            <div class="d-flex justify-content-end align-items-end ">
                                <div class="text-center d-flex flex-column me-3">
                                    <button class="btn btn-add" id="btn_kan_stok_ekle">
                                        <img src="assets/icons/Add.png" alt="icon" width="30px">
                                    </button>
                                    <label for="">Ekle</label>
                                </div>
                                <div class="text-center d-flex flex-column me-3">
                                    <button class="btn btn-red form_sifirla" type="reset" >
                                        <img src="assets/icons/Close.png" alt="icon" width="30px">
                                    </button>
                                    <label for="">İptal</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // DONÖR EKLEME FORMU SIFIRLA
            $(".form_sifirla").on("click", function(){
                alertify.warning('Kan Stok Eklem Formu Temizlendi');
            });

            // DONÖR EKLE
            $("#btn_kan_stok_ekle").on("click", function () {
                var kan_stok_ekle_kontrolet = $("#kan_stok_ekle_form").serialize();
                var blood_stock_entry_date = $("#blood_stock_entry_date").val();
                var blood_stock_status = $("#blood_stock_status").val();
                var blood_group = $("#blood_group").val();
                var blood_type = $("#blood_type").val();
                var isbt_unite_number = $("#isbt_unite_number").val();
                var expiration_date = $("#expiration_date").val();
                if (blood_stock_entry_date != '' && blood_stock_status != null && blood_group != null  && blood_type != null && isbt_unite_number != '' && expiration_date != '' ) {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_kan_stok_ekle',
                        type:'POST',
                        data:kan_stok_ekle_kontrolet,
                        success:function(e){
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_stok_tablo", { },function(getveri){
                                $('#kan_stok_tablosu').html(getveri);
                            });
                        }
                    });
                } else if (blood_stock_entry_date == '') {
                    alertify.warning("Giriş tarihi giriniz.");
                } else if (blood_stock_status == null) {
                    alertify.warning("Kan stok durumu seçiniz.");
                } else if (blood_group == null) {
                    alertify.warning("Kan grubu seçiniz.");
                } else if (blood_type == null) {
                    alertify.warning("Kan türü seçiniz.");
                } else if (isbt_unite_number == '') {
                    alertify.warning("İSTB Ünite numarası giriniz.");
                } else if (expiration_date == '') {
                    alertify.warning("Son kullanma tarihi giriniz.");
                }
            });
        });
    </script>
<?php }

//KAN STOK TABLOSU
else if($islem=="kan_stok_tablo"){
    $gelen_veri = $_GET['getir']
    ?>
    <div class="col-m-4"></div>
    <div class="col-m-8">
        <div class="d-flex justify-content-end align-items-end ">
            <div class="text-center d-flex flex-column me-0 mx-1">
                <button class="btn btn-add kan_stok_duzenle" disabled data-bs-target="#kan_stok_duzenle_modal"  data-bs-toggle="modal">
                    <img src="assets/icons/Edit-text-file.png" alt="icon" width="20px">Düzenle
                </button>
            </div>
            <div class="text-center d-flex flex-column me-1 mx-1">
                <button class="btn btn-red kan_stok_sil" disabled>
                    <img src="assets/icons/Delete.png" alt="icon" width="20px">Sil
                </button>
            </div>
        </div>
    </div>

    <!-- KAN STOK DÜZENLE MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="kan_stok_duzenle_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" id='kan_stok_duzenle_modal_icerik'></div>
    </div>

    <div class="col-m-12 mt-2" >
        <div class="card institution p-8">
                <div class="secilentab"></div>
            <div class="card-body ">
                <table  class="table table-bordered table-hover display nowrap w-100 mx-3" id="talep_kan_tablo">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>GİRİŞ TARİHİ</th>
                        <th>KAN STOK DURUM</th>
                        <th>KAN GRUBU</th>
                        <th>KAN TÜRÜ</th>
                        <th>ISBT ÜNİTE NUMARASI</th>
                        <th>S. K. TARİHİ</th>
                    </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                    <?php  $say=0;
                    $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.status='1'");
                    foreach ($kanstokliste as $rowa ) {
                        $blood_stock_status=$rowa["blood_stock_status"];
                        $blood_group=$rowa["blood_group"];
                        $blood_type=$rowa["blood_type"]; ?>
                        <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kanstoksec">
                            <td><input class="form-check-input kanstoksecradiobutton" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                            <td><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                            <td><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                            <td><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                            <td><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                            <td><?php echo $rowa["isbt_unite_number"];?></td>
                            <td><?php echo nettarih($rowa["expiration_date"]);?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        #talep_kan_tablo {
            width: 100px;
        }
    </style>

    <script type="text/javascript">
        $('#talep_kan_tablo thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#talep_kan_tablo thead');
        var table = $('#talep_kan_tablo').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function () {
                var api = this.api();
                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html(colIdx != 0 ? '<input type="text" placeholder="' + title + '" />' : '');
                        // On every keypress in this input
                        $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('change', function (e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function (e) {
                                e.stopPropagation();
                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
                },
        });
    </script>

    <script>
        // KAN STOK TABLOSU RADİO BUTTON
        $(".kanstoksec").click(function () {
            $(".kanstoksecradiobutton").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            $('.kan_stok_duzenle').prop("disabled" , false);
            $('.kan_stok_sil').prop("disabled" , false);
        });

        // KAN STOK DÜZENLE MODAL SCRIPT
        $(".kan_stok_duzenle").click(function(){
            var getir = $('.kanstoksecradiobutton:checked').attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_kan_stok_duzenle", { getir:getir },function(getveri){
                console.log(getveri);
                $('#kan_stok_duzenle_modal_icerik').html(getveri);
            });
        });

        // KAN STOK SİL ALERTIFY
        $(document).on('click', '.kan_stok_sil', function () {
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Stoktan Kan Silme Nedeni..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var id = $('.kanstoksecradiobutton:checked').attr('id');
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_kan_stok_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_stok_tablo",function(getveri){
                                $('#kan_stok_tablosu').html(getveri);
                            })
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kan_stok_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Stoktan Kan Silme İşlemini Onayla"});
        });

        $(document).ready(function(){

            $('[id="' + <?php echo $gelen_veri ?> + '"]').prop("checked", true);
        });
    </script>

    <div class="col-md-8">
        <div class="card"></div>
    </div>
<?php }

//KAN ÇIKIŞ KURUM TABLOSU
else if($islem=="kan_cikis_kurum_tablosu") { ?>
    <div class="col-m-4">
        <div class="col-m-2 mt-1 " id="kurum_kan_cikis_gerial_buttonlar">
            <button class="btn btn-pink kurum_kan_cikis" data-bs-target="#kurum_kan_cikis_modal" data-bs-toggle="modal">
                <img src="assets/icons/Logout.png" alt="icon" width="30px">
                <label for="">Kuruma Çıkış</label>
            </button>
            <button type="button"  class="btn btn-red kurum_kan_cikis_sil" >
                <img src="assets/icons/Close-Window.png" alt="icon" width="30px">
                <label for="">Çıkış Geri Al</label>
            </button>
        </div>
        </div>

    <!-- KURUM KAN ÇIKIŞ MODAL -->
    <div class="modal fade" data-bs-backdrop="static" id="kurum_kan_cikis_modal" aria-hidden="true">
        <div class="modal-dialog"  style=" width: 98%; max-width: 98%; " id="kurum_kan_cikis_modal_icerik" > </div>
    </div>

<!--    <div class="col mt-2">-->
<!--        <input type="text" class="form-control" id="tableSearch" placeholder="Listede Ara" >-->
<!--    </div>-->

    <div class="col-m-11 mt-2" >
        <div class="card institution p-8">
                <div class="secilentab"></div>
            <div class="card-body mx-1">
                <table class="table table-bordered table-hover" id="DataTable_kurum_kan_cikis" style="background: white; width: 100%;" >
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>KARŞILAMA TARİHİ</th>
                        <th>KARŞILANAN KURUM</th>
                        <th>TESLİM EDEN PERSONEL</th>
                        <th>TESLİM ALAN PERSONEL</th>
                        <th>KAN TÜRÜ</th>
                        <th>KAN GRUBU</th>
                        <th>ISBT NUMARASI</th>
                        <th>SON KULLANMA TARİHİ</th>
                    </tr>
                    </thead>
                    <tbody style="cursor: pointer;">
                    <?php
                    $say=0;
                    $kancikiskurum=verilericoklucek("select blood_exit_institution.id as bloodexitinstitutionid,blood_exit_institution.*,blood_stock.* from blood_exit_institution inner join blood_stock on blood_exit_institution.blood_stockid=blood_stock.id where blood_exit_institution.status='1' and blood_stock.status='1'");
                    foreach ($kancikiskurum as $rowa ) {
                        $blood_type=$rowa["blood_type"];
                        $blood_group=$rowa["blood_group"];
                        ?>
                        <tr style="cursor: pointer;"  id="<?php echo $rowa["bloodexitinstitutionid"]; ?>" class="kurumkancikissec">
                            <td><input class="form-check-input kurumkancikissecbtn" type="radio"  blood_stockid="<?php echo $rowa["blood_stockid"];?>" id="<?php echo $rowa["bloodexitinstitutionid"]; ?>"></td>
                            <td><?php echo nettarih($rowa["delivery_date"]);?></td>
                            <td><?php echo $rowa["delivered_institution"];?></td>
                            <td><?php echo kullanicigetirid($rowa["delivery_personnel"]);?></td>
                            <td><?php echo strtoupper($rowa["delivery_receiver_personnel"]);?></td>
                            <td><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                            <td><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                            <td><?php echo $rowa["isbt_unite_number"];?></td>
                            <td><?php echo nettarih($rowa["expiration_date"]);?></td>

                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // KURUM KAN ÇIKIŞ TABLOSU RADİO BUTTON
        $('.kurum_kan_cikis_sil').hide();
        $(".kurumkancikissec").click(function () {
            $(".kurumkancikissecbtn").prop("checked", false);
            var getir = $(this).attr('id');
            $('[id="' + getir + '"]').prop("checked", true);
            var blood_stockid = $(".kurumkancikissecbtn:checked").attr('blood_stockid');
            $('[blood_stockid="' + blood_stockid + '"]').prop("checked", true);
            $('.kurum_kan_cikis_sil').show();
        });

        // KURUM KAN ÇIKIŞ MODAL
        $(".kurum_kan_cikis").click(function(){
            var getir = $('.kurumicinkanstoksecbtn:checked').attr('id');
            $.get( "ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_kurum_kan_cikis", { getir:getir },function(getveri){
                $('#kurum_kan_cikis_modal_icerik').html(getveri);
            });
        });

        // KURUM KAN ÇIKIŞ GERİ AL
        $(document).on('click', '.kurum_kan_cikis_sil', function () {
            alertify.confirm("<div class='alert alert-danger'>Kuruma kan çıkış işlemini geri alma nedeni belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Kuruma kan çıkışı işlemini geri alma nedeni?' title='Kuruma Kan Çıkış İşlemini Geri Alma Nedeniniz..'></textarea><input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var id = $('.kurumkancikissecbtn').attr('id');
                var blood_stockid = $('.kurumkancikissecbtn').attr('blood_stockid');
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_kan_cikis_kurum_sil',
                        data: {
                            id,
                            blood_stockid,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_cikis_kurum_tablosu",function(getveri){
                                $('#kan_cikis_kurum_tablo').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("İşlemi Geri Alma Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kurum_kan_cikis_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Geri Alma İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Kuruma Kan Çıkışı Geri Alma İşlemini Onayla"});
        });

        $(document).ready(function(){
            // KURUM KAN ÇIKIŞ TABLOSU DATATABLE
            $('#DataTable_kurum_kan_cikis').DataTable( {
                "responsive":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
    </script>
<?php }

//HASTA KAN TALEP LİSTESİ
else if($islem == "hasta_kan_talep_listesi"){
    $patient_id = $_GET['patient_id'];
    if (!is_numeric($patient_id)) { exit(); } ?>

    <div id="silme_formu" class="form-group">
        <label class="alert alert-danger w-100">Silme Nedeninizi Açıklayınız...</label>
        <input class="form-control" id="delete_detail" type="text" placeholder="silme nedeninizi açıklayınız">
        <button class="btn btn-danger mt-1 kan_talep_sil_function" type="button">Gönder</button>
    </div>

    <table class="table table-hower table-bordered nowrap display w-100" id="kan_kalep_listesi" >
        <thead>
        <tr>
            <th>SEÇ</th>
            <th>İSTEYEN HEKİM</th>
            <th>TALEP ZAMANI</th>
            <th>TALEP AÇIKLAMA</th>
            <th>ONAY DURUMU</th>
            <th>KAN TALEP ONAY DURUMU</th>
            <th>ONAYLA</th>
        </tr>
        </thead>
        <tbody style="cursor: pointer;">
        <?php $sql = verilericoklucek("select blood_demand.*,transaction_definitions.*,users.* from blood_demand
                     left join transaction_definitions on blood_demand.blood_demand_status = transaction_definitions.id
                     left join users on blood_demand.requester_physicianid = users.id
                     where blood_demand.status='1' and blood_demand.patient_id='$patient_id'");

        foreach ($sql as $item) { ?>

            <tr class="talep_sec" kan-talep-id="<?php echo $item['blood_demandid']; ?>">
                <td><input class="form-check-input kan_talep_sec" type="radio" kan-talep-id="<?php echo $item['blood_demandid']; ?>" hasta-protokol-no="<?php echo $patient_id; ?>"></td>
                <td><?php echo $item['name_surname']; ?></td>
                <td><?php echo $item['blood_demand_time']; ?></td>
                <td><?php echo $item['blood_demand_description']; ?></td>
                <td><?php echo $item['blood_demand_approval_date']; ?></td>
                <td><?php echo $item['definition_name']; ?></td>
                <td><input class="form-check-input kan_talep_onayla" type="checkbox" <?php if ($item['service_confirmation_status'] !=1 ) {  } else if($item["service_confirmation_status"]==1) { echo "disabled checked"; } ?> kan-talep-id="<?php echo $item['blood_demandid']; ?>" /></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="deneme"></div>

    <script>
        $(document).ready(function () {
            $('#kan_kalep_listesi').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'p>>",
                "scrollX" : true,
                "scrollY" : true,
                "searching": false,
                "lengthchange": false,
                "autowidth": true,
                "autoheight": false,
                "idisplaylength": 50,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
            $('.kan_talep_duzenle_json_getir').prop("disabled", "disabled");
            $('.kan_talep_sil').prop("disabled", "disabled");
            $('.kan_talep_duzenle_onay').prop("disabled", "disabled");
            $('#silme_formu').hide();

            $("body").off().on("click", ".kan_talep_onayla", function(){

                if (confirm('kan talep onaylanmasını teyit ediyor musunuz')) {
                    $(this).prop("checked" , true);
                    $(this).prop("disabled" , true);
                    var kan_talep_id = $(this).attr("kan-talep-id");
                    var servis_onay_durum = 1;
                    $.ajax({
                        type: 'post',
                        url: 'ajax/kan-merkezi/kanmerkezisql.php?islem=hasta_kan_talep_onaylanmasını_teyit_et',
                        data: {kan_talep_id: kan_talep_id , servis_onay_durum : servis_onay_durum },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                        } ,
                        error: function (error) {
                            $("#sonucyaz").html(error);
                        }
                    });
                } else {
                    $(this).prop("checked" , false);
                }
            });

//kan talep listesi seçim-----------------------------------------------------------------------------------------------
            $(".talep_sec").on("click", function () {
                $("#hasta_yeni_kan_talep_gonder").prop("disabled" , true);
                $('.talep_listesi_dom').css({"background-color": "rgb(255, 255, 255)"});
                $('.talep_listesi_dom').removeClass("text-white");
                $(this).css({"background-color": "rgb(57, 180, 150"});
                $(this).addClass("text-white");
                $(this).addClass("talep_listesi_dom");
                $(".kan_talep_sec").prop("checked", false);
                var kan_talep_id = $(this).attr("kan-talep-id");
                $('[kan-talep-id="' + kan_talep_id + '"]').prop("checked", true);
                $('.kan_talep_duzenle_json_getir').prop("disabled", false);
                $('.kan_talep_sil').prop("disabled", false);
            });
//kan talebini sil form göster------------------------------------------------------------------------------------------
            $(".kan_talep_sil").on("click", function () {
                $('#silme_formu').show();
            });
//kan talebini sil ajax-------------------------------------------------------------------------------------------------
            $(".kan_talep_sil_function").on("click", function () {
                $('#silme_formu').hide();
                var kan_talep_id = $('.kan_talep_sec:checked').attr("kan-talep-id");
                var delete_detail = $('#delete_detail').val();
                var patient_id = <?php echo $_GET['patient_id']; ?>;
                $.ajax({
                    type: 'post',
                    url: 'ajax/kan-merkezi/kanmerkezisql.php?islem=kan_talep_sil',
                    data: {id: kan_talep_id, delete_detail: delete_detail},
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        alertify.success('Kan talep silme başarılı');
                        $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=hasta_kan_talep_listesi", {patient_id: patient_id}, function (e) {
                            $('.hasta_kan_talep_listesi').html(e);
                        });
                    }
                });
            });
//seçilen kan talebini düzenlemek i̇çin kan kan talep bilgilerini json getir---------------------------------------------
            $(".kan_talep_duzenle_json_getir").off().on("click", function () {
                $('#silme_formu').hide();
                var kan_talep_id = $(".kan_talep_sec:checked").attr("kan-talep-id");
                $('.kan_talep_duzenle_onay').prop("disabled", false);
                $.ajax({
                    type: 'post',
                    url: 'ajax/kan-merkezi/kanmerkezisql.php?islem=kan_talep_düzenle_json_getir',
                    data: {kan_talep_id: kan_talep_id},
                    success: function (e) {
                        var gelen_kan_talep_bilgileri = JSON.parse(e);
                        $('#blood_demand_time').val(gelen_kan_talep_bilgileri[0].blood_demand_time);
                        $('#blood_demand_description').val(gelen_kan_talep_bilgileri[0].blood_demand_description);
                        $('#blood_demand_reason').val(gelen_kan_talep_bilgileri[0].blood_demand_reason);
                        $('#blood_amount').val(gelen_kan_talep_bilgileri[0].blood_amount);
                        $('#blood_urgency_level').val(gelen_kan_talep_bilgileri[0].blood_urgency_level);
                        $('#blood_urgent_description').val(gelen_kan_talep_bilgileri[0].blood_urgent_description);
                        $('#blood_demand_special_status').val(gelen_kan_talep_bilgileri[0].blood_demand_special_status);
                        $('#blood_indication_type').val(gelen_kan_talep_bilgileri[0].blood_indication_type);
                        $('#scheduled_transfusion_time').val(gelen_kan_talep_bilgileri[0].scheduled_transfusion_time);
                        $('#scheduled_transfusion_interval').val(gelen_kan_talep_bilgileri[0].scheduled_transfusion_interval);
                        $('#blood_antibody_status').val(gelen_kan_talep_bilgileri[0].blood_antibody_status);
                        $('#transplant_pass_state').val(gelen_kan_talep_bilgileri[0].transplant_pass_state);
                        $('#transfusion_pass_state').val(gelen_kan_talep_bilgileri[0].transfusion_pass_state);
                        $('#transfusion_reaction_state').val(gelen_kan_talep_bilgileri[0].transfusion_reaction_state);
                        $('#fetomaternal_conflict_state').val(gelen_kan_talep_bilgileri[0].fetomaternal_conflict_state);
                        $('#hematocrit_ratio').val(gelen_kan_talep_bilgileri[0].hematocrit_ratio);
                        $('#trombosit_orani').val(gelen_kan_talep_bilgileri[0].trombosit_orani);
                        $('#platelet_num').val(gelen_kan_talep_bilgileri[0].platelet_num);
                        $('#cross_match_do_state').val(gelen_kan_talep_bilgileri[0].cross_match_do_state);
                        $('#pregnancy_status').val(gelen_kan_talep_bilgileri[0].pregnancy_status);
                        $('#blood_demandid').val(gelen_kan_talep_bilgileri[0].blood_demandid);
                    }
                });
            });
//kan talebi düzenlemesini onayla --------------------------------------------------------------------------------------
            $(".kan_talep_duzenle_onay").off().on("click", function () {

                $(this).prop("disabled", true);
                $('#silme_formu').hide();
                var patient_id = <?php echo $_GET['patient_id']; ?>;
                var kan_talep_guncelle_form = $('#kan_talep_form').serialize();
                var kan_talep_id = $(".kan_talep_sec:checked").attr("kan-talep-id");
                kan_talep_guncelle_form += "&kan_talep_id=" + kan_talep_id;
                $.ajax({
                    type: 'post',
                    url: 'ajax/kan-merkezi/kanmerkezisql.php?islem=kan_talep_guncelle',
                    data: kan_talep_guncelle_form,
                    success: function (e) {
                        $('.sonuc-yaz').html(e);
                        $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=hasta_kan_talep_listesi", {patient_id: patient_id}, function (e) {
                            $('.hasta_kan_talep_listesi').html(e);
                        });
                    }
                });
            });
        });
    </script>
<?php }
//TANIMLAMALAR DOLAP-RAF TABLOSU
else if($islem=="dolap_raf_tablosu"){?>
    <center><h6>DOLAP - RAF KISMI GELECEK</h6></center>
<?php }?>
