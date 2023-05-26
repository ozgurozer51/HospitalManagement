
<script>
    $(".alerttaburcu").on("click", function () {
        alertify.alert('Hasta taburcu olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
    });
    $(".alertizin").on("click", function () {
        alertify.alert('Hasta izinli olduğu için işlem yapilamaz').setHeader('<em>Hasta işlemleri</em> ');
    });
</script>


<?php
include "../../controller/fonksiyonlar.php";
session_start();
ob_start();
$kullanici_id = $_SESSION['id'];
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$islem=$_GET["islem"];

//KAN TALEP KARŞILA MODAL
if ($islem=="modalkantalepkarsilama"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$gelen_veri);
    $kantalep_id=$verigetir["blood_demandid"];
    $kan_turu=$verigetir["desired_blood_type"];
    $kan_grubu=$verigetir["desired_blood_group"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN TALEP KARŞILAMA İŞLEMİ - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">

                    <div class="col-md-12 row">
                        <div class="col-8">

                            <script>
                                $(document).ready(function(){
                                    $('#kan_talep_karsila_stok').DataTable( {
                                        "responsive":true,
                                        "language": {
                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                        },
                                    });
                                });
                            </script>

                            <table class="table m-0 border border-dark nowrap display w-100" id="kan_talep_karsila_stok" >
                                <thead>
                                <tr>
                                    <th class="border border-dark">#</th>
                                    <th class="border border-dark">GİRİŞ TARİHİ</th>
                                    <th class="border border-dark">KAN STOK DURUM</th>
                                    <th class="border border-dark">KAN GRUBU</th>
                                    <th class="border border-dark">KAN TÜRÜ</th>
                                    <th class="border border-dark">ISBT ÜNİTE NUMARASI</th>
                                    <th class="border border-dark">SON KULLANMA TARİHİ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_type='$kan_turu' and blood_stock.blood_group='$kan_grubu' and blood_stock.blood_stock_status='28970' and blood_stock.status='1'");
                                foreach ($kanstokliste as $rowa ) {
                                    $blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_group=$rowa["blood_group"];
                                    $blood_type=$rowa["blood_type"]; ?>
                                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kanstoksec1">
                                        <td class="border border-dark" ><input class="form-check-input kanstoksecradiobutton1" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                        <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                        <td class="border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            $(".kanstoksec1").off().click(function () {
                                $(".kanstoksecradiobutton1").prop("checked", false);
                                var getir = $(this).attr('id');
                                $('[id="' + getir + '"]').prop("checked", true);
                                $('#blood_stockid').val(getir);
                            });
                        </script>


                        <div class="col-4">
                            <form id="kantalepkarsilaform">
                                <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">
                            <div class="col-12 row">
                                <div class="col-4">
                                    <label  for="recipient-name" class="col-form-label ">Karşılama Tarihi:</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control " disabled type="text" id="blood_out_time" name="blood_out_time" value="<?php echo nettarih($simdikitarih)?>" >
                                    <input class="form-control"  type="text" hidden id="blood_out_time" name="blood_out_time" value="<?php echo $simdikitarih?>" >
                                    <input type="text" class="form-control" hidden name="patient_id" value="<?php echo $verigetir["patient_id"]?>"  id="basicpill-firstname-input">
                                    <input type="text" class="form-control" hidden name="patient_referenceid" value="<?php echo $verigetir["patient_protocol_no"]?>"  id="basicpill-firstname-input">
                                    <input type="text"  class="form-control" hidden  id="blood_stockid" name="blood_stockid">
                                </div>
                            </div>

                            <div class="col-12 row mt-2">
                                <div class="col-4">
                                    <label  for="recipient-name" class="col-form-label">Karşılayan Personel:</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" disabled type="text" id="blood_exit_personnelid" name="blood_exit_personnelid" value="<?php echo kullanicigetirid($kullanici_id);?>" >
                                    <input class="form-control" hidden type="text" id="blood_exit_personnelid" name="blood_exit_personnelid" value="<?php echo $kullanici_id;?>" >
                                </div>
                            </div>

                            <div class="col-12 row mt-2">
                                <div class="col-4">
                                    <label  for="recipient-name"  class="col-form-label">Teslim Edilen Personel:</label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select " name="blood_deliver_receiver_person" id="blood_deliver_receiver_person" title="kan talebi i̇çin aciliyet seviyesini belirtiniz...">
                                        <option selected disabled class="text-white bg-danger">Teslim Edilen Personeli Seçiniz.</option>
                                        <?php $sql = verilericoklucek("select * from users where status='1'");
                                        foreach ($sql as $item){ ?>
                                            <option value="<?php echo $item['id']; ?>"><?php echo $item['name_surname']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                    <button id="btnkantalepkarsila" type="button" class="btn btn-success" ">Karşıla</button>
                </div>
            </div>


    <script>
        $(document).ready(function(){
            $("#btnkantalepkarsila").on("click", function(){
                var kantalepkarsilaform = $("#kantalepkarsilaform").serialize();
                var blood_deliver_receiver_person = $("#blood_deliver_receiver_person").val();
                var blood_stockid = $("#blood_stockid").val();
                if (blood_deliver_receiver_person != null && blood_stockid != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantalepkarsila', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST',
                        data:kantalepkarsilaform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantalepkarsilamodalicerik.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (blood_stockid == '') {
                    alertify.warning("Stoktan kan seçiniz.");
                } else if (blood_deliver_receiver_person == null) {
                    alertify.warning("Teslim edilen personeli seçiniz.");
                }
            });
        });
    </script>


<?php }

//KAN TALEP REZERVE ET MODAL
else if($islem=="modalkantaleprezerveet"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kan_turu=$verigetir["desired_blood_type"];
    $kan_grubu=$verigetir["desired_blood_group"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN REZERVE İŞLEMİ - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <div class="col-md-12 row">
                        <div class="col-8">

                            <script>
                                $(document).ready(function(){
                                    $('#kan_talep_rezerve_et_stok').DataTable( {
                                        "responsive":true,
                                        "language": {
                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                        },
                                    });
                                });
                            </script>

                            <table class="table m-0 border border-dark nowrap display w-100" id="kan_talep_rezerve_et_stok" >
                                <thead>
                                <tr>
                                    <th class=" border border-dark">#</th>
                                    <th class=" border border-dark">GİRİŞ TARİHİ</th>
                                    <th class=" border border-dark">KAN STOK DURUM</th>
                                    <th class=" border border-dark">KAN GRUBU</th>
                                    <th class=" border border-dark">KAN TÜRÜ</th>
                                    <th class=" border border-dark">ISBT ÜNİTE NUMARASI</th>
                                    <th class=" border border-dark">SON KULLANMA TARİHİ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_type='$kan_turu' and blood_stock.blood_group='$kan_grubu' and blood_stock.blood_stock_status='28970' and blood_stock.status='1'");
                                foreach ($kanstokliste as $rowa ) {
                                    $blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_group=$rowa["blood_group"];
                                    $blood_type=$rowa["blood_type"]; ?>
                                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kanstoksec2">
                                        <td class=" border border-dark"><input class="form-check-input kanstoksecradiobutton2" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                                        <td class=" border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                        <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                        <td class=" border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                        <td class=" border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <script>
                            $(".kanstoksec2").off().click(function () {
                                $(".kanstoksecradiobutton2").prop("checked", false);
                                var getir = $(this).attr('id');
                                $('[id="' + getir + '"]').prop("checked", true);
                                $('#blood_stockid').val(getir);
                            });
                        </script>

                        <div class="col-4">
                            <form id="kantaleprezerveetform">
                                <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">
                            <div class="col-12 row">
                                <div class="col-4">
                                    <label  for="recipient-name" class="col-form-label">Rezerve Eden Personel:</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" disabled type="text" id="the_reserving_userid" name="the_reserving_userid" value="<?php echo kullanicigetirid($kullanici_id);?>" >
                                    <input class="form-control" hidden type="text" id="the_reserving_userid" name="the_reserving_userid" value="<?php echo $kullanici_id;?>" >
                                </div>
                            </div>

                            <div class="col-12 row mt-2">
                                <div class="col-4">
                                    <label  for="recipient-name" class="col-form-label">Rezerve Tarihi:</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="datetime-local" id="reserved_date" name="reserved_date" >
                                    <input type="hidden" class="form-control" name="patient_id" value="<?php echo $verigetir["hasta_id"]?>"  id="basicpill-firstname-input">
                                    <input type="hidden" class="form-control" name="patient_referenceid" value="<?php echo $verigetir["hasta_protokol_no"]?>"  id="basicpill-firstname-input">
                                    <input type="hidden" class="form-control"  id="blood_stockid" name="blood_stockid">
                                </div>
                            </div>
                            </div>
                            </form>
                        </div>

                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="btnkantaleprezerveet" type="button" class="btn btn-success ">Rezerve et</button>
                </div>
            </div>

    <script>
        $(document).ready(function(){
            $("#btnkantaleprezerveet").on("click", function(){
                var kantaleprezerveetform = $("#kantaleprezerveetform").serialize();
                var reserved_date = $("#reserved_date").val();
                var blood_stockid = $("#blood_stockid").val();
                if (reserved_date !='' && blood_stockid !='' ) {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantaleprezerveet',
                        type:'POST',
                        data:kantaleprezerveetform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantaleprezerveetmodal.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (blood_stockid == '') {
                    alertify.warning("Stoktan kan seçiniz.");
                } else if (reserved_date == '') {
                    alertify.warning("Rezerve tarihi giriniz.");
                }
            });
        });
    </script>
<?php }

//KAN TALEP REDDET MODAL
else if($islem=="modalkantalepreddet"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN TALEP RET İŞLEMİ - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <form id="kantalepreddetform">
                    <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">

                    <div class="col-12 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Ret Tarihi:</label>
                        </div>
                        <div class="col-6">
                            <input class="form-control" disabled type="text" id="date_of_refusal" name="date_of_refusal" value="<?php echo nettarih($simdikitarih)?>" >
                            <input class="form-control" hidden type="text" id="date_of_refusal" name="date_of_refusal" value="<?php echo $simdikitarih?>" >
                        </div>
                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Reddeden Personel:</label>
                        </div>
                        <div class="col-6">
                            <input class="form-control" disabled type="text" id="refusal_userid" name="refusal_userid" value="<?php echo kullanicigetirid($kullanici_id); ?>" >
                            <input class="form-control" hidden type="text" id="refusal_userid" name="refusal_userid" value="<?php echo $kullanici_id; ?>" >
                        </div>
                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-4">
                            <label  for="recipient-name"  class="col-form-label">Ret Nedeni:</label>
                        </div>
                        <div class="col-6">
                            <select class="form-select"  name="blood_demand_rejection_reason" id="blood_demand_rejection_reason" required>
                                <option selected disabled class="text-white bg-danger">Ret Nedeni Seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_TALEP_RET_NEDENI' and transaction_definitions.status='1'");
                                foreach ($sql as $item){?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    </form>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="btnkantalepreddet" type="button" class="btn btn-success" >Reddet</button>
                </div>
            </div>

    <script>
        $(document).ready(function(){
            $("#btnkantalepreddet").on("click", function(){
                var kantalepreddetform = $("#kantalepreddetform").serialize();
                var blood_demand_rejection_reason = $("#blood_demand_rejection_reason").val();
                if (blood_demand_rejection_reason != null) {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantalepreddet',
                        type:'POST',
                        data:kantalepreddetform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantalepreddettmodal.close').click();
                            $('.modal-backdrop').remove();;
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (blood_demand_rejection_reason == null) {
                    alertify.warning("Ret nedeni seçiniz.");
                }
            });
        });
    </script>
<?php }

//KAN TALEP KARŞILAMA GERİ AL MODAL
else if ($islem=="modalkantalepkarsilamagerial"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kan_turu=$verigetir["desired_blood_type"];
    $kan_grubu=$verigetir["desired_blood_group"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    $kanstok=singularactive("blood_stock","blood_demandid",$gelen_veri);
    $verigetiruc=singularactive("blood_out","blood_demandid",$kantalep_id);  ?>

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KARŞILAMA İŞLEMİ GERİ AL - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);   ?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <script>
                        $(document).ready(function(){
                            $('#modalkarsilamagerialtable').DataTable( {
                                "responsive":true,
                                "searching": false,
                                "paging": false,
                                "language": {
                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                },
                            });
                        });
                    </script>

                    <div class="col-md-12 row">
                        <table class="table table table-bordered nowrap display w-100" id="modalkarsilamagerialtable" >
                                <thead>
                                <tr>
                                    <th class="border border-dark">GİRİŞ TARİHİ</th>
                                    <th class="border border-dark">KAN STOK DURUM</th>
                                    <th class="border border-dark">KAN GRUBU</th>
                                    <th class="border border-dark">KAN TÜRÜ</th>
                                    <th class="border border-dark">ISBT ÜNİTE NUMARASI</th>
                                    <th class="border border-dark">SON KULLANIM TARİHİ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_type='$kan_turu' and blood_stock.blood_group='$kan_grubu' and blood_stock.blood_demandid='$gelen_veri' and blood_stock.status='1'");
                                foreach ($kanstokliste as $rowa ) {
                                    $blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_group=$rowa["blood_group"];
                                    $blood_type=$rowa["blood_type"]; ?>
                                    <tr>
                                        <td class="border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                        <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                        <td class="border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                    </div>

                    <form id="kantalepkarsilagerialform">
                        <input type="hidden" name="id" value="<?php echo $verigetiruc["id"]; ?>">
                        <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">
                            <div class="col-12 row mt-2">
                                <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name" class="col-form-label ">Karşılama Tarihi:</label>
                                </div>
                                <div class="col-7">
                                    <input class="form-control " disabled type="text" id="blood_out_time" name="blood_out_time" value="<?php echo nettarih($verigetiruc["blood_out_time"]);?>" >
                                    <input type="text" hidden class="form-control"  id="blood_stockid" name="blood_stockid" value="<?php echo $kanstok["id"]?>">
                                </div>
                                </div>
                                <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name" class="col-form-label">Karşılayan Personel:</label>
                                </div>
                                <div class="col-7">
                                    <input class="form-control" disabled type="text" id="blood_exit_personnelid" name="blood_exit_personnelid" value="<?php echo kullanicigetirid($verigetiruc['blood_exit_personnelid']);?>">
                                </div>
                                </div>
                            </div>

                            <div class="col-12 row mt-2">
                                <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name"  class="col-form-label">Teslim Edilen Personel:</label>
                                </div>
                                <div class="col-7">
                                    <select disabled class="form-select" name="blood_deliver_receiver_person" id="blood_deliver_receiver_person" title="kan talebi i̇çin aciliyet seviyesini belirtiniz...">
                                        <option selected disabled class="text-white bg-danger">Teslim edilen personeli seçiniz.</option>
                                        <?php $sql = verilericoklucek("select * from users where status='1'");
                                        foreach ($sql as $item){ ?>
                                            <option <?php if($verigetiruc["blood_deliver_receiver_person"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['name_surname']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                </div>
                                <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name"  class="col-form-label">Geri Alma Nedeniniz ?</label>
                                </div>
                                <div class="col-7">
                                    <textarea class="form-control" id="delete_detail" name="delete_detail" rows="1" required placeholder="İşlemi Geri Alma Nedeniniz ?" title="Kan Talep Karşılama İşlemi Geri Alma Nedeni.."></textarea>
                                </div>
                                </div>
                            </div>
                    </form>


                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="btnkantalepkarsilagerial" type="button" class="btn btn-success">Geri Al</button>
                </div>
            </div>

    <script>
        $(document).ready(function(){
            $("#btnkantalepkarsilagerial").on("click", function(){
                var kantalepkarsilagerialform = $("#kantalepkarsilagerialform").serialize();
                var delete_detail = $("#delete_detail").val();
                if (delete_detail != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantalepkarsilagerial', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST',
                        data:kantalepkarsilagerialform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantalepkarsilagerialmodal.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("İşlemi geri alma nedenini giriniz.");
                }
            });
        });
    </script>
<?php }

//KAN TALEP REZERVE İŞLEMİNİ GERİ AL MODAL
else if($islem=="modalkantaleprezervegerial"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kan_turu=$verigetir["desired_blood_type"];
    $kan_grubu=$verigetir["desired_blood_group"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    $kanstok=singularactive("blood_stock","blood_demandid",$gelen_veri);
    $verigetiruc=singularactive("blood_out","blood_demandid",$kantalep_id);
    ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">REZERVE İŞLEMİNİ GERİ AL- <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <div class="modal-body ">
                        <script>
                            $(document).ready(function(){
                                $('#modalrezervegerialtable').DataTable( {
                                    "responsive":true,
                                    "searching": false,
                                    "paging": false,
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                    },
                                });
                            });
                        </script>

                        <div class="col-md-12 row">
                            <table class="table table table-bordered nowrap display w-100" id="modalrezervegerialtable" >
                                <thead>
                                <tr>
                                    <th class="border border-dark">GİRİŞ TARİHİ</th>
                                    <th class="border border-dark">KAN STOK DURUMU</th>
                                    <th class="border border-dark">KAN GRUBU</th>
                                    <th class="border border-dark">KAN TÜRÜ</th>
                                    <th class="border border-dark">ISBT ÜNİTE NUMARASI</th>
                                    <th class="border border-dark">SON KULLANMA TARİHİ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_type='$kan_turu' and blood_stock.blood_group='$kan_grubu' and blood_stock.blood_demandid='$gelen_veri' and blood_stock.status='1'");
                                foreach ((array) $kanstokliste as $rowa ) {$blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_group=$rowa["blood_group"];
                                    $blood_type=$rowa["blood_type"]; ?>
                                    <tr>
                                        <td class="border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                        <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                        <td class="border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <form id="kantaleprezervegerialform">
                            <input type="hidden" name="id" value="<?php echo $verigetiruc["id"]; ?>">
                            <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">
                        <div class="col-12 row mt-2">
                            <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name" class="col-form-label">Rezerve Tarihi:</label>
                                </div>
                                <div class="col-7">
                                    <input class="form-control " disabled type="text" id="reserved_date" name="reserved_date" value="<?php echo nettarih($verigetiruc["reserved_date"]);?>" >
                                    <input type="text"  class="form-control" hidden id="blood_stockid" name="blood_stockid" value="<?php echo $kanstok["id"]?>">
                                </div>
                            </div>
                            <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name" class="col-form-label">Rezerve Eden Personel:</label>
                                </div>
                                <div class="col-7">
                                    <input class="form-control" disabled type="text" id="the_reserving_userid" name="the_reserving_userid" value="<?php echo kullanicigetirid($verigetiruc["the_reserving_userid"]); ?>" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-6 row">
                                <div class="col-5">
                                    <label  for="recipient-name"  class="col-form-label">Geri Alma Nedeniniz ?</label>
                                </div>
                                <div class="col-7">
                                    <textarea required class="form-control" id="delete_detail" name="delete_detail" rows="1" placeholder="İşlemi Geri Alma Nedeniniz ?" title="kan talep rezerve i̇şlemi geri alma nedeni.."></textarea>
                                </div>
                            </div>
                        </div>

                        </form>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="btnkantaleprezervegerial" type="button" class="btn btn-success" >Geri Al</button>
                </div>
            </div>

    <script>
        $(document).ready(function(){
            $("#btnkantaleprezervegerial").on("click", function(){
                var kantaleprezervegerialform = $("#kantaleprezervegerialform").serialize();
                var delete_detail = $("#delete_detail").val();
                if (delete_detail != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantaleprezervegerial', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST',
                        data:kantaleprezervegerialform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantaleprezervegerialmodal.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Geri alma nedenini giriniz.");
                }
            });
        });
    </script>
<?php }

//KAN TALEP RET İŞLEMİNİ GERİ AL MODAL
else if($islem=="modalkantalepreddetgerial"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    $verigetiruc=singularactive("blood_demand_detail","blood_demandid",$kantalep_id);
    ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RET İŞLEMİNİ GERİ AL - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <form id="kantalepreddetgerialform">
                        <input type="hidden" name="id" value="<?php echo $verigetiruc["id"]; ?>">
                        <input type="hidden" name="blood_demandid" value="<?php echo $verigetiruc["blood_demandid"]; ?>">

                        <div class="col-12 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Ret Tarihi:</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" disabled type="text" id="date_of_refusal" name="date_of_refusal" value="<?php echo nettarih($verigetiruc["date_of_refusal"]); ?>" >
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Reddeden Personel:</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" disabled type="text" id="refusal_userid" name="refusal_userid" value="<?php echo kullanicigetirid($verigetiruc["refusal_userid"]);?>"  min="1" max="100" >
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-4">
                                <label  for="recipient-name"  class="col-form-label">Ret Nedeni:</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" disabled type="text" id="blood_demand_rejection_reason" name="blood_demand_rejection_reason" value="<?php if ($verigetiruc["blood_demand_rejection_reason"]!=''){echo islemtanimgetirid($verigetiruc["blood_demand_rejection_reason"]); } ?>" >
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-4">
                                <label  for="recipient-name"  class="col-form-label">Geri Alma Nedeniniz:</label>
                            </div>
                            <div class="col-6">
                                <textarea required class="form-control" id="delete_detail" name="delete_detail" rows="1" placeholder="İ̇şlemi Geri Alma Nedeniniz ?" title="kan talep geri alma nedeni.."></textarea>
                            </div>
                        </div>
                    </form>
                    </div>

                    <div class="modal-footer">
                         <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                         <button id="btnkantalepreddetgerial" type="button" class="btn btn-success">Geri Al</button>
                    </div>
            </div>

    <script>
        $(document).ready(function(){
            $("#btnkantalepreddetgerial").on("click", function(){
                var kantalepreddetgerialform = $("#kantalepreddetgerialform").serialize();
                var delete_detail = $("#delete_detail").val();
                if (delete_detail != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantalepreddetgerial', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST',
                        data:kantalepreddetgerialform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantalepreddetgerialmodal.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", {getir:<?php echo $kantalep_id; ?>  },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Geri alma nedenini giriniz.");
                }
            });
        });
    </script>

    <!-- kan talep rezerve i̇şlemi̇ni̇ karşilama -->
    <!-------------------------------------------------------------------------------------------------------------------------->
<?php } else if ($islem=="modalkantaleprezervekarsilama"){
    $gelen_veri = $_GET['getir'];
    $verigetir=singularactive("blood_demand","blood_demandid",$_GET["getir"]);
    $kantalep_id=$verigetir["blood_demandid"];
    $kan_turu=$verigetir["desired_blood_type"];
    $kan_grubu=$verigetir["desired_blood_group"];
    $kantalephastakodu=$verigetir["patient_id"];
    $verigetiriki=singularactive("patients","id",$kantalephastakodu);
    $kanstok=singularactive("blood_stock","blood_demandid",$gelen_veri);
    ?>
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">REZERVE İŞLEMİNİ KARŞILA - <?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?></h4>
            <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body ">

            <div class="col-md-12 row">
                <div class="col-8">

                    <script>
                        $(document).ready(function(){
                            $('#rezerve_kan_karsila_tablo').DataTable( {
                                "responsive":true,
                                "searching": false,
                                "paging": false,
                                "language": {
                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                },
                            });
                        });
                    </script>

                    <table class="table m-0 border border-dark nowrap display w-100" id="rezerve_kan_karsila_tablo" >
                        <thead>
                        <tr>
                            <th class="border border-dark">GİRİŞ TARİHİ</th>
                            <th class="border border-dark">KAN STOK DURUM</th>
                            <th class="border border-dark">KAN GRUBU</th>
                            <th class="border border-dark">KAN TÜRÜ</th>
                            <th class="border border-dark">ISBT ÜNİTE NUMARASI</th>
                            <th class="border border-dark">SON KULLANMA TARİHİ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_type='$kan_turu' and blood_stock.blood_group='$kan_grubu' and blood_stock.blood_demandid=$gelen_veri and blood_stock.status='1'");
                        foreach ($kanstokliste as $rowa ) {
                            $blood_stock_status=$rowa["blood_stock_status"];
                            $blood_group=$rowa["blood_group"];
                            $blood_type=$rowa["blood_type"]; ?>
                            <tr>
                                <td class="border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                <td class="border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                <td class="border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-4">
                    <form id="kantaleprezervekarsilamaform">
                        <input type="hidden" name="blood_demandid" value="<?php echo $kantalep_id; ?>">
                        <div class="col-12 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label ">Karşılama Tarihi:</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" disabled type="text" id="blood_out_time" name="blood_out_time" value="<?php echo nettarih($simdikitarih)?>" >

                                <input class="form-control" hidden type="text" id="blood_out_time" name="blood_out_time" value="<?php echo $simdikitarih;?>" >

                                <input type="" class="form-control" hidden name="patient_id" value="<?php echo $verigetir["patient_id"]?>"  id="basicpill-firstname-input">

                                <input type="hidden" class="form-control" name="patient_referenceid" value="<?php echo $verigetir["patient_protocol_no"]?>"  id="basicpill-firstname-input">

                                <input type="text"  hidden class="form-control"  id="blood_stockid" name="blood_stockid" value="<?php echo $kanstok["id"]?>">
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Karşılayan Personel:</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" disabled type="text" id="blood_exit_personnelid" name="blood_exit_personnelid" value="<?php echo kullanicigetirid($kullanici_id);?>">

                                <input class="form-control" hidden type="text" id="blood_exit_personnelid" name="blood_exit_personnelid" value="<?php echo $kullanici_id;?>">
                            </div>
                        </div>

                        <div class="col-12 row mt-2">
                            <div class="col-4">
                                <label  for="recipient-name"  class="col-form-label">Teslim Edilen Personel:</label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" name="blood_deliver_receiver_person" id="blood_deliver_receiver_person" title="kan talebi i̇çin aciliyet seviyesini belirtiniz...">
                                    <option selected disabled class="text-white bg-danger">Teslim edilen personeli seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from users where status=1");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name_surname']; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
            <button id="btnkantaleprezervekarsilama" type="button" class="btn btn-success">Karşıla</button>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#btnkantaleprezervekarsilama").on("click", function(){
                var kantaleprezervekarsilamaform = $("#kantaleprezervekarsilamaform").serialize();
                var blood_deliver_receiver_person = $("#blood_deliver_receiver_person").val();
                if (blood_deliver_receiver_person != null) {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sqlkantaleprezervekarsila',
                        type:'POST',
                        data:kantaleprezervekarsilamaform,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#kantaleprezervekarsilamamodal.close').click();
                            $('.modal-backdrop').remove();

                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepkarsilama", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepkarsilamalistesi').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kantalepistem", { getir:<?php echo $kantalep_id; ?> },function(getveri){
                                $('#kantalepistemlerilistesi').html(getveri);
                            });
                        }
                    });
                } else if (blood_deliver_receiver_person == null) {
                    alertify.warning("Teslim edilen personel seçiniz.");
                }
            });
        });
    </script>
<?php }

//KAN TÜRÜ TANIMLAMA EKLE
else if($islem=="modal_kan_turu_ekle"){
    $verigetir=singularactive("transaction_definitions","id",$_GET["getir"]);
    $islemtanimid=$verigetir["id"]
    ?>

    <form id="kan_turu_ekle_form">
        <div class="modal-dialog">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN TÜRÜ EKLE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Yeni Kan Türü:</label>
                        <input type="text" class="form-control" name="definition_name" id="definition_name" placeholder="Yeni kan türü tanım adını giriniz.." />
                        <input type="text" hidden class="form-control" name="definition_type" id="definition_type" value="KAN_TURU"/>
                    </div>

                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="button_kan_turu_ekle" type="button" class="btn btn-success" data-bs-dismiss="modal">Ekle</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#button_kan_turu_ekle").on("click", function(){
                var kan_turu_ekle_form = $("#kan_turu_ekle_form").serialize();
                var definition_name = $("#definition_name").val();
                if (definition_name != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_ekle',
                        type:'POST',
                        data:kan_turu_ekle_form,
                        success:function(e){
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_turu_tablosu", function(getveri){
                                $('#tanimlamalar_tablosu').html(getveri);
                            });
                        }
                    });
                } else if (definition_name == '') {
                    alert("Yeni kan türünü giriniz.");
                }
            });
        });
    </script>

<?php  }

//KAN TÜRÜ TANIMLAMA DÜZENLE
else if($islem=="modal_kan_turu_duzenle"){
    $gelen_veri = $_GET['getir'];
    $islemtanimkanturu=singularactive("transaction_definitions","id",$_GET["getir"]);
    $islemtanimid=$islemtanimkanturu["id"]
    ?>

    <form id="kan_turu_duzenle_form">
        <div class="modal-dialog">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN TÜRÜ DÜZENLE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" value="<?php echo $islemtanimid; ?>">

                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Düzenlemek İstediğinizden Eminmisiniz:</label>
                        <input type="text" disabled class="form-control" name="definition_name" id="definition_name" value="<?php echo $islemtanimkanturu["definition_name"];?>" />
                    </div>
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Düzenlenen Kan Türünü:</label>
                        <input type="text" class="form-control" name="definition_name" id="definition_name" value="<?php echo $islemtanimkanturu["definition_name"];?>" />
                    </div>


                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="button_kan_turu_duzenle"type="button" class="btn btn-success" data-bs-dismiss="modal">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#button_kan_turu_duzenle").on("click", function(){
                var kan_turu_duzenle_form = $("#kan_turu_duzenle_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_duzenle', // serileştirilen değerleri ajax.php dosyasına
                    type:'POST',
                    data:kan_turu_duzenle_form,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_turu_tablosu", function(getveri){
                            $('#tanimlamalar_tablosu').html(getveri);
                        });
                    }
                });
            });
        });
    </script>

<?php  }

//RET NEDENİ TANIMLAMA EKLE MODAL
else if($islem=="modal_ret_nedeni_ekle"){
    $verigetir=singularactive("transaction_definitions","id",$_GET["getir"]);
    $islemtanimid=$verigetir["id"] ?>

    <form id="ret_nedeni_ekle_form">
        <div class="modal-dialog">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RET NEDENİ EKLE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Yeni Ret Nedeni:</label>
                        <input type="text" class="form-control" name="definition_name" id="definition_name" placeholder="Yeni ret nedeni giriniz.." />
                        <input type="text" hidden class="form-control" name="definition_type" id="definition_type" value="KAN_TALEP_RET_NEDENI"  />
                    </div>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="button_ret_nedeni_ekle" type="button" class="btn btn-success" data-bs-dismiss="modal">Ekle</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function(){
            $("#button_ret_nedeni_ekle").on("click", function(){
                var ret_nedeni_ekle_form = $("#ret_nedeni_ekle_form").serialize();
                var definition_name = $("#definition_name").val();
                if (definition_name != '') {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_ekle', // serileştirilen değerleri ajax.php dosyasına
                        type:'POST',
                        data:ret_nedeni_ekle_form,
                        success:function(e){
                            $("#sonucyaz").html(e);
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=ret_nedenleri_liste", { },function(getveri){
                                $('#tanimlamalar_tablosu').html(getveri);
                            });
                        }
                    });
                } else if (definition_name == '') {
                    alert("Yeni ret nedeni giriniz.");
                }
            });
        });
    </script>

<?php }

//TANIMLAMALAR RED NEDENİ DÜZENLE MODAL
else if($islem=="modal_ret_nedeni_duzenle"){
    $gelen_veri = $_GET['getir'];
    $islemtanimkanturu=singularactive("transaction_definitions","id",$_GET["getir"]);
    $islemtanimid=$islemtanimkanturu["id"] ?>

    <form id="ret_nedeni_duzenle_form">
        <div class="modal-dialog">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RET NEDENİ DÜZENLE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $islemtanimid; ?>">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Düzenlemek İstediğinizden Eminmisiniz:</label>
                        <input type="text" disabled class="form-control" name="definition_name" id="definition_name" value="<?php echo $islemtanimkanturu["definition_name"];?>" />
                    </div>
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Düzenlenen Ret Nedeni:</label>
                        <input type="text" class="form-control" name="definition_name" id="definition_name" value="<?php echo $islemtanimkanturu["definition_name"];?>" />
                    </div>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="button_ret_nedeni_duzenle"  type="button" class="btn btn-success" data-bs-dismiss="modal">Kaydet</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#button_ret_nedeni_duzenle").on("click", function(){
                var ret_nedeni_duzenle_form = $("#ret_nedeni_duzenle_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_tanimlama_duzenle', // serileştirilen değerleri ajax.php dosyasına
                    type:'POST',
                    data:ret_nedeni_duzenle_form,
                    success:function(e){
                        $("#sonucyaz").html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=ret_nedenleri_liste", { },function(getveri){
                            $('#tanimlamalar_tablosu').html(getveri);
                        });
                    }
                });

            });
        });
    </script>

<?php }

//DONÖR EKLE MODAL
else if($islem=="modal_donor_ekle"){
    $verigetir=singularactive("blood_donor","id",$_GET["getir"]);
    $donor_id=$verigetir["id"]?>

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DONÖR EKLE</h4>
                <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-3">
                    <div class="col-md-11" >
                        <input class="form-control tckimlikno"   required type="number"  id="tc_id" name="tc_id" placeholder="T.C. Kimlik Numarası Giriniz..." title="T.C. Kimlik Numarası Giriniz" maxlength="11">
                    </div>
                    <div class="col-md-1 ">
                        <button type="button" class="btn tcbtnsorgula">
                            <img src="assets/icons/search.png" alt="icon" width="35px"></button>
                    </div>
                </div>

                <script type="text/javascript">
                        $(".tcbtnsorgula").click(function(){
                            var tc_id = $('#tc_id').val();
                            $.ajax({
                                url:'ajax/kan-merkezi/kanmerkezisql.php?islem=tc_kimlik_ara',
                                type:'POST',
                                data:{tc_id:tc_id},
                                success:function(e){
                                    $("#sonucyaz").html(e);
                                    isJson(e);
                                    function isJson(e) {
                                        try {
                                            const obj = JSON.parse(e);
                                            if (obj && typeof obj === `object`) {
                                                $('#donor_registrationid').val(obj.id);
                                                $('#tc_id_form').val(obj.tc_id);
                                                $('#name_surname').val(obj.name_surname);
                                                $('#blood_group').val(obj.blood_group);
                                                $('#mother').val(obj.mother);
                                                $('#father').val(obj.father);
                                                $('#phone_number').val(obj.phone_number);
                                                return true;
                                            }else {
                                                alertify.warning('Donör ilk kez geliyor.');
                                            }
                                        } catch (e) {
                                            alert("Bilinmeyen bir hata oluştu.");
                                            return false;
                                        }
                                        return false;
                                    }
                                }
                            });
                        });
                </script>

                <br>

                <form id="donor_ekle_form" >
                    <div class="col-12 row">
                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">T.C Kimlik No:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control"  type="text" id="tc_id_form" name="kayit[tc_id]">
                                <input class="form-control" hidden type="text" id="donor_registrationid" name="donor[donor_registrationid]">
                                <input class="form-control" hidden type="text" id="blood_donor_approval_status_form" name="donor[blood_donor_approval_status]" value="28934">
                                <input class="form-control" hidden type="text" id="blood_donation_time" name="donor[blood_donation_time]" value="<?php echo $simdikitarih ?>">
                            </div>
                        </div>
                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Ad Soyad:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="text" id="name_surname" name="kayit[name_surname]">
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Kan Grubu:</label>
                            </div>

                            <div class="col-8">
                                <select class="form-select" name="kayit[blood_group]" id="blood_group" >
                                    <option selected disabled class="text-white bg-danger">Kan grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_GRUBU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="col-12 row mt-2">

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Anne Adı:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="text" id="mother" name="kayit[mother]">
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Baba Adı:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="text" id="father" name="kayit[father]">
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Telefon:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="text" id="phone_number" name="kayit[phone_number]">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-4 row">
                            <div class="col-4">
                                <label for="recipient-name" class="col-form-label">Nabız:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="number" required id="pulse" name="donor[pulse]" min="1" max="3">
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Boy:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control"  type="number" required id="height" name="donor[height]"  min="1" max="3" >
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Kilo:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control"  type="text" required id="weight" name="donor[weight]"  min="1" max="3" >
                            </div>
                        </div>

                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Ateş:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control"  type="text" id="fever" required name="donor[fever]"   >
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Bağışçı Türü:</label>
                            </div>

                            <div class="col-8">
                                <select class="form-select" required id="donor_type" name="donor[donor_type]" >
                                    <option selected disabled class="text-white bg-danger">Kan bağışında bulunma nedenini seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='BAGISCI_TURU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Bağış Türü:</label>
                            </div>

                            <div class="col-8">
                                <select class="form-select" required name="donor[donated_blood_type]" id="donated_blood_type">
                                    <option selected disabled class="text-white bg-danger">Bağışlanan kan türü seçiniz.</option>
                                    <?php
                                    $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_TURU' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-4 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Acil Durumu:</label>
                            </div>

                            <div class="col-8">
                                <select class="form-select" required name="donor[blood_donation_emergency_status]" id="blood_donation_emergency_status" >
                                    <option selected disabled class="text-white bg-danger">Aciliyet durumu seçiniz.</option>
                                    <?php
                                    $sql = verilericoklucek("select * from transaction_definitions where definition_type='DONOR_BAGIS_ACILIYET_DURUM' and transaction_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                <button id="btn_donor_ekle" type="button" class="btn btn-success">Ekle</button>
            </div>
        </div>

    <script>
        $("#btn_donor_ekle").on("click", function () {
            var kontrolet = $("#donor_ekle_form").serialize();
            var pulse = $("#pulse").val();
            var height = $("#height").val();
            var weight = $("#weight").val();
            var fever = $("#fever").val();
            var donor_type = $("#donor_type").val();
            var donated_blood_type = $("#donated_blood_type").val();
            var blood_donation_emergency_status = $("#blood_donation_emergency_status").val();
            if (pulse != '' && height != '' && weight != ''  && fever != '' && donor_type != null && donated_blood_type != null && blood_donation_emergency_status != null) {
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_ekle',
                    type:'POST',
                    data:kontrolet,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('#donor_ekle_modal.close').click();
                        $('.modal-backdrop').remove();
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem",function(getveri){
                            $('#bagisci_kabul_islemleri').html(getveri);
                        });
                    }
                });
            } else if (pulse == '') {
                alertify.warning("Nabız bilgisi giriniz.");
            } else if (height == '') {
                alertify.warning("Boy bilgisi giriniz");
            } else if (weight == '') {
                alertify.warning("Kilo bilgisi giriniz.");
            } else if (fever == '') {
                alertify.warning("Ateş Bilgisi Giriniz.");
            } else if (donor_type == null) {
                alertify.warning("Kan bağışında bulunma nedenini seçiniz.");
            } else if (donated_blood_type == null) {
                alertify.warning("Bağışlanan kan türü seçiniz.");
            } else if (blood_donation_emergency_status == null) {
                alertify.warning("Aciliyet durumu seçiniz.");
            }
        });
    </script>
<?php }

//DONÖR KABUL ET MODAL
else if($islem=="modal_donor_kabul_et"){
    $gelen_veri = $_GET['getir'];
    $kan_bagisci_tablo=singularactive("blood_donor","id",$gelen_veri);
    $donor_kayit_tablo=singularactive("blood_donor_registration","id",$kan_bagisci_tablo["donor_registrationid"]);
    $kan_bagisci_id=$kan_bagisci_tablo["id"];
    ?>
    <div id='donor_kabul_et'></div>
    <form id="donor_kabul_et_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">DONÖR KABUL ET</h4>
                <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?php echo $kan_bagisci_id; ?>">

                <div class="col-12 row">
                    <div class="col-6 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">T.C Kimlik No:</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" id="donor_patientid" name="donor_patientid"
                                   value="<?php echo $donor_kayit_tablo["tc_id"]; ?>">
                        </div>
                    </div>
                    <div class="col-6 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Ad Soyad:</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" id="donor_patientid" name="donor_patientid" value="<?php echo mb_strtoupper($donor_kayit_tablo["name_surname"]);?>">
                        </div>
                    </div>
                </div>

                <div class="col-12 row mt-2">
                    <div class="col-6 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Bağışçı Türü:</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" id="donor_type" name="donor_type" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["donor_type"]); ?>">
                        </div>
                    </div>
                    <div class="col-6 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Bağış Türü:</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" id="donated_blood_type" name="donated_blood_type" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["donated_blood_type"]); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-12 row mt-2">
                    <div class="col-6 row">
                        <div class="col-4">
                            <label  for="recipient-name" class="col-form-label">Acil Durum:</label>
                        </div>

                        <div class="col-8">
                            <input class="form-control" disabled type="text" id="blood_donation_emergency_status" name="blood_donation_emergency_status" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["blood_donation_emergency_status"]); ?>" >
                            <input class="form-control" hidden type="text" id="acceptance_date" name="acceptance_date" value="<?php echo $simdikitarih; ?>"  min="1" max="100" >
                            <input class="form-control" hidden type="text" id="blood_donor_approval_status" name="blood_donor_approval_status" value="28935">
                            <input class="form-control" hidden type="text" id="accept_user_user" name="accept_user_user" value="<?php echo $kullanici_id?>">
                        </div>
                    </div>

                </div>


            </div>
    </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                <button id="btn_donor_kabul_et" type="button" class="btn btn-success" data-bs-dismiss="modal">Kabul Et</button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#btn_donor_kabul_et").on("click", function(){
                var donor_kabul_et_form = $("#donor_kabul_et_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_kabul_et',
                    type:'POST',
                    data:donor_kabul_et_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem",function(getveri){
                            $('#bagisci_kabul_islemleri').html(getveri);
                        });
                    }
                });
            });
        });
    </script>

<?php }

//DONÖR REDDET MODAL
else if($islem=="modal_donor_reddet"){
    $gelen_veri = $_GET['getir'];
    $kan_bagisci_tablo=singularactive("blood_donor","id",$gelen_veri);
    $donor_kayit_tablo=singularactive("blood_donor_registration","id",$kan_bagisci_tablo["donor_registrationid"]);
    $kan_bagisci_id=$kan_bagisci_tablo["id"];
    ?>
    <div id='donor_reddet'></div>
    <form id="donor_reddet_form">
        <div class="modal-dialog modal-lg">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DONÖR REDDET</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">

                    <input type="hidden" name="id" value="<?php echo $kan_bagisci_id;?>">

                    <div class="col-12 row">
                        <div class="col-6 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">T.C Kimlik No:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" disabled type="text" id="donor_patientid" name="donor_patientid"
                                       value="<?php echo $donor_kayit_tablo["tc_id"]; ?>">
                            </div>
                        </div>
                        <div class="col-6 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Ad Soyad:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" disabled type="text" id="donor_patientid" name="donor_patientid" value="<?php echo mb_strtoupper($donor_kayit_tablo["name_surname"]);?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-6 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Bağışçı Türü:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" disabled type="text" id="donor_type" name="donor_type" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["donor_type"]);?>">
                            </div>
                        </div>
                        <div class="col-6 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Bağış Türü:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" disabled type="text" id="donated_blood_type" name="donated_blood_type" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["donated_blood_type"]);?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 row mt-2">
                        <div class="col-6 row">
                            <div class="col-4">
                                <label  for="recipient-name" class="col-form-label">Acil Durum:</label>
                            </div>

                            <div class="col-8">
                                <input class="form-control" disabled type="text" id="blood_donation_emergency_status" name="blood_donation_emergency_status" value="<?php echo islemtanimgetirid($kan_bagisci_tablo["blood_donation_emergency_status"]);?>" >
                                <input class="form-control" hidden type="text" id="rejection_date" name="rejection_date" value="<?php echo $simdikitarih; ?>"  min="1" max="100" >
                                <input class="form-control" hidden type="text" id="blood_donor_approval_status" name="blood_donor_approval_status" value="28936">
                                <input class="form-control" hidden type="text" id="refusal_user" name="refusal_user" value="<?php echo $kullanici_id ?>">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                    <button id="btn_donor_reddet" type="button" class="btn btn-success" data-bs-dismiss="modal">Reddet</button>
                </div>
            </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#btn_donor_reddet").on("click", function(){
                var donor_reddet_form = $("#donor_reddet_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_reddet',
                    type:'POST',
                    data:donor_reddet_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem",function(getveri){
                            $('#bagisci_kabul_islemleri').html(getveri);
                        });
                    }
                });
            });
        });
    </script>
<?php }

//DONÖR REZERVE ET MODAL
else if($islem=="modal_donor_rezerve_et"){
    $gelen_veri = $_GET['getir'];
    $geldim_veri = $_GET['geldim']; ?>

    <div id='donor_rezerve'><?php var_dump(var_dump($_POST)); ?></div>
    <form id="donor_rezerve_form">
        <div class="modal-dialog" style="width: 90%; max-width: 95%; ">
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DONÖR REZERVE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <div class="col-12 row">
                        <div class="col-7">

                            <script>
                                $(document).ready(function(){
                                    $('#donorrezervetablo').DataTable( {
                                        "responsive":true,
                                        "language": {
                                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                        },
                                    });
                                } );
                            </script>

                            <div class="table-responsive" id="donorrezervelistesi">
                                <table class="table m-0 table table-bordered nowrap display w-100" id="donorrezervetablo">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ÖNCELİK</th>
                                        <th>AD SOYAD</th>
                                        <th>İSTEM TARİHİ</th>
                                        <th>DURUM</th>
                                        <th>İSTENİLEN ADET</th>
                                        <th>DOKTOR</th>
                                        <th>SERVİS</th>
                                        <th>PROTOKOL NO</th>
                                        <th>DOĞUM TARİHİ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $say=0;
                                    $kantalepliste=verilericoklucek("select * from blood_demand,patients where blood_demand.patient_id=patients.id and blood_demand.blood_demand_status='28831' and blood_demand.status='1' ");
                                    foreach ($kantalepliste as $rowa ) {
                                        $blood_urgency_level=$rowa["blood_urgency_level"];
                                        $blood_demand_status=$rowa["blood_demand_status"]; ?>
                                        <tr style="cursor: pointer;" id="<?php echo $rowa["blood_demandid"]; ?>" hasta-id="<?php echo $rowa["patient_id"]; ?>" hasta-protokol-no="<?php echo $rowa["patient_protocol_no"]; ?>" class="kantalepsec">
                                            <td><input class="form-check-input kantalepsecradiobutton" type="radio"  id="<?php echo $rowa["blood_demandid"]; ?>"></td>
                                            <td><?php if ($blood_urgency_level!=''){echo islemtanimgetirid($blood_urgency_level); }  ?></td>
                                            <td><?php echo strtoupper(hastalaradi($rowa["patient_id"])), " ", strtoupper(hastalarsoyadi($rowa["patient_id"]));?></td>
                                            <td><?php $parca = explode("t", nettarih($rowa["blood_demand_time"]));echo nettarih($rowa["blood_demand_time"])." ".$parca[1] ?></td>
                                            <td <?php if($rowa["blood_demand_status"]=="28832" /*onaylandı*/ ) {?> style="color: green; font-weight: bold;
 " <?php } else if ($rowa["blood_demand_status"]=="28861" /*rezerve*/ ){?> style="color:#e3c211; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28862"/*karşılandı*/ ){?> style="color:#20df20; font-weight: bold; " <?php } else if ($rowa["blood_demand_status"]=="28863"/*reddedildi*/ ){?> style="color: red; font-weight: bold; " <?php } ?>><?php if ($blood_demand_status!=''){echo islemtanimgetirid($blood_demand_status); } ?></td>
                                            <td><?php echo $rowa["blood_amount"];?></td>
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
                                    $(".kantalepsec").click(function () {
                                        $(".kantalepsecradiobutton").prop("checked", false);
                                        var geldim = $(this).attr('id');
                                        $('[id="' + geldim + '"]').prop("checked", true);
                                        $('#blood_demandid').val(geldim);
                                        var hastageldi = $(this).attr('hasta-id');
                                        $('[hasta-id="' + hastageldi + '"]').prop("checked", true);
                                        $('#patient_id').val(hastageldi);
                                        var protokolgeldi = $(this).attr('hasta-protokol-no');
                                        $('[hasta-protokol-no="' + protokolgeldi + '"]').prop("checked", true);
                                        $('#patient_referenceid').val(protokolgeldi);

                                        $.get("ajax/kan-merkezi/kanmerkezimodallar.php?islem=modal_donor_rezerve_et", {geldim: geldim }, function (getveri) {
                                            $('#modal_donor_rezerve_et').html(getveri);
                                        });
                                    });
                                </script>

                            </div>
                        </div>

                        <div class="col-5 ">
                            <div class="col-8 row">
                                    <div class="col-5">
                                        <label  for="recipient-name" class="col-form-label">Rezerve Tarihi:</label>
                                    </div>

                                    <div class="col-7">
                                        <input class="form-control" type="datetime-local" id="reserved_date" name="reserved_date" value="" >
                                    </div>
                            </div>
                            <div class="col-8 row mt-2">
                                    <div class="col-5">
                                        <label  for="recipient-name" class="col-form-label">Rezerve Eden Personel:</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control" disabled type="text" id="the_reserving_userid" name="the_reserving_userid" value="<?php echo kullanicigetirid($kullanici_id); ?>">
                                        <input class="form-control" hidden type="text" id="the_reserving_userid" name="the_reserving_userid"value="<?php echo $kullanici_id; ?>">
                                        <input class="form-control" hidden  type="text" id="blood_out_donor_id" name="blood_out_donor_id" value="<?php echo $gelen_veri?>">
                                        <input class="form-control" hidden  type="text" id="blood_demandid" name="blood_demandid">
                                        <input class="form-control" hidden  type="text" id="patient_id" name="patient_id">
                                        <input class="form-control" hidden  type="text" id="patient_referenceid" name="patient_referenceid">
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                     <button id="btn_donor_rezerve" type="button" class="btn btn-success">Rezerve Et</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function(){
            $("#btn_donor_rezerve").on("click", function () {
                var donro_rezerve_kontrolet = $("#donor_rezerve_form").serialize();
                var reserved_date = $("#reserved_date").val();
                var blood_demandid = $("#blood_demandid").val();
                if (reserved_date != '' && blood_demandid != '' ) {
                    $.ajax({
                        url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_rezerve',
                        type:'POST',
                        data:donro_rezerve_kontrolet,
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $('#donor_rezerve_et_modal.close').click();
                            $('.modal-backdrop').remove();
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem", { },function(getveri){
                                $('#bagisci_kabul_islemleri').html(getveri);
                            });
                            $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_rezerve_liste", { getir:<?php echo $gelen_veri; ?> },function(getveri){
                                $('#bagisci_rezerve_listesi').html(getveri);
                            });
                        }
                    });
                } else if (blood_demandid == '') {
                    alertify.warning("Rezerve edilecek hastayı seçiniz.");
                } else if (reserved_date == '') {
                    alertify.warning("Rezerve tarihi giriniz.");
                }
            });
        });
    </script>
<?php }

//DONÖR KABUL ET GERİ AL MODAL
else if($islem=="modal_donor_kabul_geri_al"){
    $gelen_veri = $_GET['getir'];
    $kan_bagisci_tablo=singularactive("blood_donor","id",$gelen_veri);
    $donor_kayit_tablo=singularactive("blood_donor_registration","id",$kan_bagisci_tablo["donor_registrationid"]);
    $kan_bagisci_id=$kan_bagisci_tablo["id"]; ?>
    <form id="donor_kabul_geri_al_form">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DONÖR KABUL İŞLEMİNİ GERİ AL</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <input type="hidden" name="id" value="<?php echo $kan_bagisci_id; ?>">
                    <div class="row">
                        <label  for="recipient-name" class="col-form-label text-danger"><b> <?php echo mb_strtoupper($donor_kayit_tablo["name_surname"]); ?> İÇİN KABUL İŞLEMİNİ GERİ ALMAK İSTEDİĞİNİZDEN EMİN MİSİNİZ ?</b></label>
                        <input class="form-control" hidden type="text" id="blood_donor_approval_status" name="blood_donor_approval_status" value="28934">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Hayır</button>
                        <button id="btn_donor_kabul_geri_al" type="button" class="btn btn-success" data-bs-dismiss="modal">Evet</button>
                    </div>

                </div>
            </div>
    </form>

    <script>
        $(document).ready(function(){
            $("#btn_donor_kabul_geri_al").on("click", function(){
                var donor_kabul_geri_al_form = $("#donor_kabul_geri_al_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_kabul_geri_al',
                    type:'POST',
                    data:donor_kabul_geri_al_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem", { },function(getveri){
                            $('#bagisci_kabul_islemleri').html(getveri);
                        });
                    }
                });
            });
        });
    </script>

<?php }

//DONÖR RET GERİ AL MODAL
else if($islem=="modal_donor_ret_geri_al"){ ?><?php
    $gelen_veri = $_GET['getir'];
    $kan_bagisci_tablo=singularactive("blood_donor","id",$gelen_veri);
    $donor_kayit_tablo=singularactive("blood_donor_registration","id",$kan_bagisci_tablo["donor_registrationid"]);
    $kan_bagisci_id=$kan_bagisci_tablo["id"]; ?>
    <form id="donor_ret_geri_al_form">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">DONÖR RET İŞLEMİNİ GERİ AL</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <input type="hidden" name="id" value="<?php echo $kan_bagisci_id; ?>">
                    <div class="row">
                        <label  for="recipient-name" class="col-form-label text-danger"><b>  <?php echo mb_strtoupper($donor_kayit_tablo["name_surname"]); ?> İÇİN RET İŞLEMİNİ GERİ ALMAK İSTEDİĞİNİZDEN EMİN MİSİNİZ ?</b></label>
                        <input class="form-control" hidden type="text" id="blood_donor_approval_status" name="blood_donor_approval_status" value="28934">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Hayır</button>
                        <button id="btn_donor_ret_geri_al" type="button" class="btn btn-success" data-bs-dismiss="modal">Evet</button>
                    </div>

                </div>
            </div>
    </form>
    <script>
        $(document).ready(function(){
            $("#btn_donor_ret_geri_al").on("click", function(){
                var donor_ret_geri_al_form = $("#donor_ret_geri_al_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_donor_ret_geri_al',
                    type:'POST',
                    data:donor_ret_geri_al_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=bagisci_kabul_islem", { },function(getveri){
                            $('#bagisci_kabul_islemleri').html(getveri);
                        });
                    }
                });
            });
        });
    </script>

<?php }

//KAN STOK GÜNCELLE MODAL
else if($islem=="modal_kan_stok_duzenle"){
    $gelen_veri = $_GET['getir'];
    $kan_stok_tablo=singularactive("blood_stock","id",$gelen_veri);
    $kan_stok_id=$kan_stok_tablo["id"];
    ?>
    <form id="kan_stok_duzenle_form">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KAN STOK DÜZENLE</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <input type="hidden" name="id" value="<?php echo $kan_stok_id; ?>">
                    <div class="col-md-12 row mt-2">
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">Giriş Tarihi:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input class="form-control" type="datetime-local" id="blood_stock_entry_date" name="blood_stock_entry_date" value="<?php echo $kan_stok_tablo["blood_stock_entry_date"];?>">
                        </div>
                    </div>
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">Stok Durumu:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <select class="form-select" name="blood_stock_status" id="blood_stock_status" >
                                <option selected  class="text-white bg-danger">Kan stok durumu seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_STOK_DURUMU' and transaction_definitions.status='1'");
                                foreach ($sql as $item){ ?>
                                    <option <?php if($kan_stok_tablo["blood_stock_status"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mt-2">
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">Kan Grubu:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <select class="form-select" name="blood_group" id="blood_group" >
                                <option selected  class="text-white bg-danger">Kan grubu seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_GRUBU' and transaction_definitions.status='1'");
                                foreach ($sql as $item) { ?>
                                    <option <?php if($kan_stok_tablo["blood_group"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        </div>
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">Kan Türü:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <select class="form-select" name="blood_type" id="blood_type" >
                                <option selected  class="text-white bg-danger">Kan türü seçiniz.</option>
                                <?php
                                $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_TURU' and transaction_definitions.status='1'");
                                foreach ($sql as $item){?>
                                    <option <?php if($kan_stok_tablo["blood_type"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mt-2">
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">ISBT Ünite Numarası:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input class="form-control" type="text" id="isbt_unite_number" name="isbt_unite_number" value="<?php echo $kan_stok_tablo["isbt_unite_number"];?>">
                        </div>
                        </div>
                        <div class="col-6 row">
                        <div class="col-md-4 mt-2">
                            <label  for="recipient-name" class="col-form-label">Son Kullanım Tarihi:</label>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input class="form-control" type="date" id="expiration_date" name="expiration_date" value="<?php echo $kan_stok_tablo["expiration_date"];?>">
                        </div>
                        </div>
                        </div>
                    </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Hayır</button>
                    <button id="btn_kan_stok_duzenle" type="button" class="btn btn-success" data-bs-dismiss="modal">Evet</button>
                </div></div>
            </div>
    </form>

    <script>
        $(document).ready(function(){
            $("#btn_kan_stok_duzenle").on("click", function(){
                var kan_stok_duzenle_form = $("#kan_stok_duzenle_form").serialize();
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_kan_stok_duzenle', // serileştirilen değerleri ajax.php dosyasına
                    type:'POST',
                    data:kan_stok_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_stok_tablo", { getir:<?php echo $gelen_veri; ?> },function(getveri){
                            $('#kan_stok_tablosu').html(getveri);
                        });
                    }
                });
            });
        });
    </script>

<?php }

//KURUMA KAN ÇIKIŞ MODAL
else if($islem=="modal_kurum_kan_cikis"){
    $gelen_veri = $_GET['getir'];
    $kan_stok_tablo = singularactive("blood_stock","id",$gelen_veri);
    ?>
    <div id='kurum_kan_cikisi_a'></div>
        <div class="modal-dialog" style=" width: 98%; max-width: 98%; ">>
            <!-- modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">KURUM KAN ÇIKIŞ</h4>
                    <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body ">
                    <div class="row">
                        <div class="col-6">
                            <script>
                                $(document).ready(function(){
                                        $('#kan_stok_table').DataTable( {
                                            "responsive": true,
                                                  "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                  },
                                        } );
                                });
                            </script>
                            <table class="table m-0  border border-dark nowrap display w-100" id="kan_stok_table" >
                                <thead>
                                <tr>
                                    <th class="border border-dark">#</th>
                                    <th class="border border-dark">GİRİŞ TARİHİ</th>
                                    <th class="border border-dark">KAN STOK DURUM</th>
                                    <th class="border border-dark">KAN GRUBU</th>
                                    <th class="border border-dark">KAN TÜRÜ</th>
                                    <th class="border border-dark">ISBT ÜNİTE NUMARASI</th>
                                    <th class="border border-dark">SON KULLANIM TARİHİ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $say=0;
                                $kanstokliste=verilericoklucek("select * from blood_stock where blood_stock.blood_stock_status='28970' and blood_stock.status='1'");
                                foreach ($kanstokliste as $rowa ) {
                                    $blood_stock_status=$rowa["blood_stock_status"];
                                    $blood_group=$rowa["blood_group"];
                                    $blood_type=$rowa["blood_type"]; ?>
                                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kanstoksec3">
                                        <td class="border border-dark" ><input class="form-check-input kanstoksecradiobutton3" type="radio"  id="<?php echo $rowa["id"]; ?>"></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["blood_stock_entry_date"]);?></td>
                                        <td class="border border-dark"><?php if ($blood_stock_status!=''){echo islemtanimgetirid($blood_stock_status); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_group!=''){echo islemtanimgetirid($blood_group); } ?></td>
                                        <td class="border border-dark"><?php if ($blood_type!=''){echo islemtanimgetirid($blood_type); } ?></td>
                                        <td class="border border-dark"><?php echo $rowa["isbt_unite_number"];?></td>
                                        <td class="border border-dark"><?php echo nettarih($rowa["expiration_date"]);?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <script>
                                $(".kanstoksec3").off().click(function () {
                                    $(".kanstoksecradiobutton3").prop("checked", false);
                                    var getir = $(this).attr('id');
                                    $('[id="' + getir + '"]').prop("checked", true);
                                    $('#blood_stockid').val(getir);
                                });
                            </script>
                        </div>

                        <div class="col-6">
                            <form id="kurum_kan_cikis_form">
                            <div class="col-md-12 row mt-1">
                                <div class="col-6 row">
                                    <div class="col-5">
                                        <label  for="recipient-name" class="col-form-label">Karşılama Tarihi:</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control" type="text" disabled id="delivery_date" name="delivery_date" value="<?php echo nettarih($simdikitarih);?>"  >
                                        <input class="form-control" type="text" hidden id="delivery_date" name="delivery_date" value="<?php echo $simdikitarih;?>"  >
                                        <input class="form-control" type="text" hidden id="blood_stockid" name="blood_stockid"  >
                                    </div>
                                </div>

                                <div class="col-6 row">
                                    <div class="col-6">
                                        <label  for="recipient-name" class="col-form-label">Teslim Eden Personel:</label>
                                    </div>
                                <div class="col-6">
                                        <input class="form-control" type="text"disabled id="delivery_personnel" name="delivery_personnel" value="<?php echo kullanicigetirid($kullanici_id);?>" >
                                        <input class="form-control" type="text"hidden id="delivery_personnel" name="delivery_personnel" value="<?php echo $kullanici_id;?>" >
                                </div>
                                </div>
                            </div>

                            <div class="col-md-12 row mt-2">
                                <div class="col-6 row">
                                    <div class="col-5">
                                        <label  for="recipient-name" class="col-form-label">Karşılanan Kurum:</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control" type="text" disabled id="delivered_institution" name="delivered_institution" value="" >
                                    </div>
                                </div>

                                <div class="col-6 row">
                                    <div class="col-6">
                                        <label  for="recipient-name" class="col-form-label">Teslim Alan Personel:</label>
                                    </div>
                                    <div class="col-6">
                                    <input class="form-control" type="text" id="delivery_receiver_personnel" name="delivery_receiver_personnel" value="" >
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Hayır</button>
                    <input id="btn_kurum_kan_cikis" type="button" class="btn btn-success w-md justify-content-end" value="Evet"/>
                </div>
            </div>
    <script>
        $(document).ready(function(){
            $("#btn_kurum_kan_cikis").on("click", function(){
                var kurum_kan_cikis_form = $("#kurum_kan_cikis_form").serialize();
                var blood_stockid = $("#blood_stockid").val();
                var delivery_receiver_personnel = $("#delivery_receiver_personnel").val();
                if (blood_stockid != '' && delivery_receiver_personnel != '' ) {
                $.ajax({
                    url:'ajax/kan-merkezi/kanmerkezisql.php?islem=sql_kan_cikis_kurum',
                    type:'POST',
                    data:kurum_kan_cikis_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('#kurum_kan_cikis_modal_icerik.close').click();
                        $('.modal-backdrop').remove();
                        $.get( "ajax/kan-merkezi/kanmerkezitablolar.php?islem=kan_cikis_kurum_tablosu", { },function(getveri){
                            $('#kan_cikis_kurum_tablo').html(getveri);
                        });
                    }
                });
                } else if (blood_stockid == '') {
                  alertify.warning("Karşılama işlemi için stoktan kan seçiniz.");
                } else if (delivery_receiver_personnel == '') {
                  alertify.warning("Kanı teslim alacak personeli giriniz.");
                }
            });
        });
    </script>

<?php }

//KAN TALEP MODAL
else if($islem=="kan-taleb"){
    if (!is_numeric($_GET['patient_id'])){ ?>

    <script type="text/javascript">
        alertify.alert("uyari", "seçim yapmadınız!!");
        $('.modal-backdrop').remove();
    </script>

    <?php  exit(); }
$protokolno=$_GET['protokolno'];
$kullanicid=$_SESSION['id'];
$gelen_veri = $_GET['getir'];
$verigetir = singularactive("blood_demand", "id", $_GET["patient_id"]);
$kantalep_id = $verigetir["id"];
$verigetiriki = singularactive("patients", "id", $_GET["patient_id"]);   ?>


<form id="kan_talep_form" action="javascript:void(0);">
    <div class="modal-dialog" style="width:99%; max-width:99%;">
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kan Talep</h4>
                <button type="button" class="btn-close btn-close-white btn_vazgec" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="card">
                <div class="card-header p-2 d-flex justify-content-center" style="background-color: white !important;">
                    <div class="btn-group center">
                        <?php

                        $taburcu = singularactive("patient_discharge", "admission_protocol",$protokolno);
                        $izin = singularactive("patient_permission","protocol_number",$protokolno);
                        $kanbankasiinsert=yetkisorgula($kullanicid, "kanbankasiinsert");
                        $kanbankasiupdate=yetkisorgula($kullanicid, "kanbankasiupdate");
                        $kanbankasidelete=yetkisorgula($kullanicid, "kanbankasidelete");
                        if ($kanbankasiupdate==1){ ?>
                        <button type="button" class="btn btn-primary <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else{ echo 'kan_talep_duzenle_json_getir'; } ?>"
                                title="hastanın kan talebini güncell"><i class="fas fa-edit"></i> Düzenle</button>
                        <?php }if ($kanbankasiupdate==1){ ?>
                        <button type="button" class="btn btn-info <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'kan_talep_duzenle_onay'; } ?>"
                                title="hastanın kan talebi güncellemesini onayla" disabled><i class="fas fa-check"></i> Onayla</button>
                        <?php } if ($kanbankasidelete==1){ ?>
                        <button type="button" class="btn btn-danger <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'kan_talep_sil'; } ?>"
                                title="hasta kan talebini sil"><i class="fas fa-trash px-1"></i> Sil</button>
                        <?php } if ($kanbankasiinsert==1){ ?>
                        <button type="button" class="btn btn-success <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'hasta_yeni_kan_talep'; } ?>"
                                title="hastaya yeni kan talebinde bulun"><i class="fas fa-calendar-plus"></i> Yeni</button>
                        <?php } if ($kanbankasiinsert==1){ ?>
                        <button type="button" class="btn btn-warning" id=" <?php if ($taburcu['discharge_status']==1){ echo 'alerttaburcu'; }else if ($izin['id']!=''){echo 'alertizin';}else { echo 'hasta_yeni_kan_talep_gonder'; } ?>"
                                title="hastaya yeni kan talebini gönder" disabled><i class="fas fa-save"></i> Kaydet</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="hasta_kan_talep_listesi">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-8">
            <div class="card">
                <div class="card-header text-white p-1">Kan Talep Form</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Ad Soyad:</label>
                                <input class="form-control" disabled type="text" id="patient_id" value="<?php echo strtoupper($verigetiriki["patient_name"])," ",strtoupper($verigetiriki["patient_surname"]);?>" min="1" max="100">
                                <input type="hidden" class="form-control" value="<?php echo $verigetiriki["id"] ?>">
                                <input type="hidden" class="form-control" id="patient_protocol_no" value="<?php echo $_GET["patient_protocol_id"]; ?>">
                                <input type="hidden" class="form-control" id="kan_talep_id" value="">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Hasta Kodu:</label>
                                <input class="form-control hasta_id" disabled type="text" id="patient_id" hasta-id="<?php echo $_GET["patient_id"]; ?>" min="1" max="100" value="<?php echo $_GET["patient_id"]; ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Talep Zamanı:</label>
                                <input class="form-control" type="datetime-local" id="blood_demand_time" name="blood_demand_time" min="1" max="100">
                            </div>
                        </div>
                    </div>

<!------------------------------------------------------------------------------------------------------------------------->
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="blood_demand_description" class="col-form-label">Kan Talep Açıklama:</label>
                                <textarea class="form-control is-invalid" id="blood_demand_description" name="blood_demand_description" type="text" rows="1" placeholder="Kan talep açıklama" title="sağlık tesisinde kan talep edilen kişi için yapılan açıklama bilgisidir..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="blood_demand_reason" class="col-form-label">Kan Talep Nedeni:</label>
                                <textarea class="form-control" id="blood_demand_reason" name="blood_demand_reason" rows="1" placeholder="Kan talep nedeni" title="kan talep nedeni açıklayınız..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="blood_want_unitid" class="col-form-label">Kan istiyen Birim:</label>
                                 <?php $sql = verilericoklucek("select *
                                                                from patient_registration
                                                                inner join units on patient_registration.service_id = units.id where patient_registration.protocol_number =$_GET[protokolno]"); ?>
                                <input class="form-control" type="text" value="<?php echo $sql[0]['department_name']; ?>" disabled id="blood_want_unitid" name="blood_want_unitid">
                                <input type="hidden" value="<?php echo $sql[0]['service_id']; ?>" id="blood_want_unitid" name="blood_want_unitid">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="requester_physicianid" class="col-form-label">Kan İ̇stiyen Hekim:</label>
                                <?php $sql = verilericoklucek("select * from users where id='$kullanici_id'"); ?>
                                <input class="form-control" value="<?php echo $sql[0]['name_surname']; ?>" type="text" disabled>
                                <input type="hiden" id="requester_physicianid" name="requester_physicianid" value="<?php echo $sql[0]['id']; ?>">
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="desired_blood_group" class="col-form-label">İstenilen Kan Grubu:</label>
                                <input class="form-control" disabled type="text" value="<?php echo islemtanimgetirid($verigetiriki["blood_group"]); ?>">
                                <input type="hidden" class="form-control" name="desired_blood_group" value="<?php echo $verigetiriki["blood_group"]; ?>">
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-4">
                            <div class="row">
                                <div class="col-md-8 col-xs-8">
                                    <div class="form-group">
                                        <label for="desired_blood_type" class="col-form-label">Kan Türü:</label>
                                        <select class="form-select desired_blood_type" id="desired_blood_type" name="istenilen_kan_turu">
                                            <option disabled selected class="text-white bg-danger">Seçiniz</option>
                                            <?php $tur_getir = verilericoklucek("select * from transaction_definitions where status=1 and definition_type = 'KAN_TURU'");
                                            foreach ($tur_getir as $item){ ?>
                                                <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group">
                                        <label for="blood_amount" class="col-form-label">Torba:</label>
                                        <input class="form-control" type="number" id="blood_amount" name="blood_amount" min="1" max="100" title="i̇stenilen miktar bilgisidir">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

<!------------------------------------------------------------------------------------------------------------------------>

                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Talep Aciliyet Seviyesi:</label>
                                <select class="form-select" name="blood_urgency_level" id="blood_urgency_level" title="kan talebi i̇çin aciliyet seviyesini belirtiniz...">
                                    <option selected disabled class="text-white bg-danger">Aciliyet Seviyesi Seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_ACILIYET_SEVIYESI' and transaction_definitions.status='1'");
                                    foreach ($sql as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Acil Açıklama:</label>
                                <textarea class="form-control" id="blood_urgent_description" name="blood_urgent_description" rows="1" placeholder="Kan Acil Açıklama" title="kan acil açıklama belirtiniz..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-md-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Talep Özel Durumu:</label>
                                <input class="form-control" type="text" id="blood_demand_special_status" name="blood_demand_special_status" placeholder="Kan Talep Özel Durum" title="sağlık tesisinde kan talep edilen hasta için özel durum bilgisidir...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Endikasyon Türü:</label>
                                <select class="form-select" name="blood_indication_type" id="blood_indication_type" title="kişiye kan transfüzyonu yapılacağı zaman kanın hangi amaçla kişiye verileceğini ifade eder. örneğin kanamaya bağlı kan takviyesi, bağışıklık sistemi güçlendirme, dolaşım sisteminde oksijen miktarını artırma vb...">
                                    <option selected disabled class="text-white bg-danger">kan endikasyon türü seçiniz.</option>
                                 <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAN_ENDIKASYON_TURU' and transaction_definitions.status='1'");
                                    foreach ($sql as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Planlanan Transfüzyon Zamanı:</label>
                                <input class="form-control" type="date" id="scheduled_transfusion_time" name="scheduled_transfusion_time" placeholder="Planlanan Transfuzyon Zamanı" title="kan ürünü için planlanan transfüzyon zamanı bilgisidir....">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Transfüzyon Süresi:</label>
                                <input class="form-control" type="time" id="scheduled_transfusion_interval" name="scheduled_transfusion_interval" placeholder="Planlanan Transfuzyon Süresi" title="planlanan kan ürünü transfüzyon süresinin dakika cinsinden bilgisidir...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Kan Antikor Durumu:</label>
                                <input class="form-control" type="text" id="blood_antibody_status" name="blood_antibody_status" placeholder="Antikor Durumu" title="Kişinin kanında herhangi bir kan gurubuna karşı antikor varlığına ilişkin bilgiyi ifade eder.">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="transplantasyon_gecirme_durumu" class="col-form-label">Transplantasyon Geçirme Durumu:</label>
                                <input class="form-control" type="number" id="transplant_pass_state" name="transplant_pass_state" placeholder="Transpantasyon Geçirme Durumu" title="sağlık hizmetini almak için sağlık tesisine başvuran kişinin tıbbi öyküsünde transplantasyon geçirme durumuna ilişkin bilgiyi ifade eder...">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tranfüzyon Geçirme Durumu:</label>
                                <input class="form-control" type="number" id="transfusion_pass_state" name="transfusion_pass_state" placeholder="Transpantasyon Geçirme Durumu" title="sağlık hizmetini almak için sağlık tesisine başvuran kişinin tıbbi öyküsünde transfüzyon geçirme durumuna ilişkin bilgiyi ifade eder...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Transfuzyon Reaksiyon Durumu:</label>
                                <input class="form-control" type="number" id="transfusion_reaction_state" name="transfusion_reaction_state" placeholder="Transfüzyon Reaksiyon Durumu" title="sağlık hizmetini almak için sağlık tesisine başvuran kişinin tıbbi öyküsünde transfüzyon sonrası reaksiyon durumuna ilişkin bilgiyi ifade eder...">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Fetomaternal Uyuşmazlık Durumu:</label>
                                <input class="form-control" type="number" id="fetomaternal_conflict_state" name="fetomaternal_conflict_state" placeholder="Fetomaternal Uyuşmazlık Durumu" title="fetusun (anne karnındaki doğmamış bebek) anne ile olan kan uyuşmazlığı olup olmadığını ifade eden durum bilgisidir...">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Hematokrit Oranı:</label>
                                <input class="form-control" type="text" id="hematocrit_ratio" name="hematocrit_ratio" placeholder="Hematokrit Oranı" title="hematokrit (hct) / hemoglobin (hgb) oranı bilgisidir...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Trombosit Sayısı:</label>
                                <input class="form-control" type="text" id="platelet_num" name="platelet_num" placeholder="Trombosit Sayısı" tittle="kişiden alınan kan örneğinde sayımı yapılan trombosit sayısı bilgisidir...">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Cros-Match Yapılma Durumu:</label>
                                <select class="form-select" name="cross_match_do_state" id="cross_match_do_state" title="kan ürününe cross-match işleminin uygulanıp uygulanmayacağına ilişkin bilgiyi ifade eder...">
                                    <option selected disabled class="text-white bg-danger">Cros-Match Yapılma Durumu Seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='CROSS_MATCH_YAPILMA_DURUMU' and transaction_definitions.status='1'");
                                    foreach ($sql as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Gebelik Geçirme Durumu:</label>
                                <select class="form-select" name="pregnancy_status" id="pregnancy_status" title="ebelik geçirme durumu olup olmadığını i̇fade eder...">
                                    <option selected disabled class="text-white bg-danger">Gebelik Geçirme Durumu Seçiniz.
                                    </option>
                                    <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='GEBELIK_GECIRME_DURUMU' and transaction_definitions.status='1'");
                                    foreach ($sql as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['definition_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
                 <button type="button" class="btn btn-danger btn_vazgec" data-bs-dismiss="modal">Kapat</button>
                <input type="hidden" class="form-control" id="blood_demand_status"  name="blood_demand_status" value="28831">
                <input type="hidden" class="form-control" id="service_confirmation_status"  name="service_confirmation_status" value="29006">
            </div>
        </div>
    </div>
</form>

            <script>
                $(document).ready(function () {

//ameliyat listesi malzeme bilgileri için POST gönder--------------------------------------------------------------------
                    var patient_id = <?php echo $_GET['patient_id']; ?>;
                    $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=hasta_kan_talep_listesi", {patient_id:patient_id }, function (e) {
                        $('.hasta_kan_talep_listesi').html(e);
                    });

//hastaya yeni kan talebi i̇çin formu sıfırla-------------------------------------------------------------------
                    $(".hasta_yeni_kan_talep").off().on("click", function () {

                        $('#hasta_yeni_kan_talep_gonder').prop("disabled" , false);
                        $('.kan_talep_duzenle_json_getir').prop("disabled" , true);
                        $('.kan_talep_sil').prop("disabled" , true);
                        $('#blood_demand_time').val('');
                        $('#blood_demand_description').val('');
                        $('#blood_demand_reason').val('');
                        $('#blood_amount').val('');
                        $('#blood_urgency_level').val('');
                        $('#blood_urgent_description').val('');
                        $('#blood_demand_special_status').val('');
                        $('#blood_indication_type').val('');
                        $('#scheduled_transfusion_time').val('');
                        $('#scheduled_transfusion_interval').val('');
                        $('#blood_antibody_status').val('');
                        $('#transplant_pass_state').val('');
                        $('#transfusion_pass_state').val('');
                        $('#transfusion_reaction_state').val('');
                        $('#fetomaternal_conflict_state').val('');
                        $('#hematocrit_ratio').val('');
                        $('#trombosit_orani').val('');
                        $('#platelet_num').val('');
                        $('#cross_match_do_state').val('');
                        $('#pregnancy_status').val('');
                    });

//yeni kan talebi gönder------------------------------------------------------------------------------------------------
                    $("#hasta_yeni_kan_talep_gonder").off().on("click", function () {

                        var kan_talep_form_gonder = $('#kan_talep_form').serialize();
                        var hasta_protokol_no = $('#patient_protocol_no').val();
                        var hasta_id = $('.hasta_id').attr('hasta-id');
                        var patient_id = <?php echo $_GET['patient_id']; ?>

                        kan_talep_form_gonder += "&patient_protocol_no=" + hasta_protokol_no;
                        kan_talep_form_gonder += "&patient_id=" + hasta_id;

                        $.ajax({
                            type: 'POST',
                            url: 'ajax/kan-merkezi/kanmerkezisql.php?islem=ameliyat_kan_talep_bilgileri_kayit',
                            data: kan_talep_form_gonder,
                            success: function (e) {
                                $('.sonuc_yaz').html(e);
                                $.get("ajax/kan-merkezi/kanmerkezitablolar.php?islem=hasta_kan_talep_listesi", {patient_id: patient_id}, function (e) {
                                    $('.hasta_kan_talep_listesi').html(e);
                                });
                            },
                            error: function (e2) {
                                $('.sonuc_yaz').html(e2);
                            },
                            warning: function (e3) {
                                $('.sonuc_yaz').html(e3);
                            }
                        });

                    });

                });
            </script>

<div class="sonuc_yaz"></div>
<?php } ?>

            <style>
                .modal-body {
                    max-height: calc(108vh - 200px);
                    overflow-y: auto;
                }
            </style>

            <script>
                $(document).ready(function(){
                    $(".btn_vazgec").on("click", function(){
                        alertify.warning('İşlemden Vazgeçtiniz');
                    });
                });
            </script>
