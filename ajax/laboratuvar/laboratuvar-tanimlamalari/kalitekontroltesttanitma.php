<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_kalite_kontrol_test_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_define_quality_control_test",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Kalite Kontrol Test Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Kalite Kontrol Test Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_kalite_kontrol_test_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_define_quality_control_test", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Kalite Kontrol Test Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Kalite Kontrol Test Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_kalite_kontrol_test_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_define_quality_control_test", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Kalite Kontrol Test Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Kalite Kontrol Test Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_kalite_kontrol_test_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_define_quality_control_test','id',$id,$date,$user);

    if ($sql == 1) { ?>
        <script>
            alertify.success('Aktifleştirme İşlemi Başarılı');
        </script>

    <?php } else { ?>
        <script>
            alertify.error('Aktifleştirme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="listeyi-getir"){ ?>

    <div class="col-12">
        <div class="card">
            <div class="card-body row" style="height: 43vh;">
                <div class="col-4">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Cihazlar</b></div>
                    <div class="mt-3"></div>

                    <script>
                        $(document).ready(function(){
                            setTimeout(function() {
                                $('#kalite_kontrol_cihaz_listesi_datatb').DataTable( {
                                    "scrollY": '21vh',
                                    "scrollX": true,
                                    "info":true,
                                    "paging":false,
                                    "searching":true,
                                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                                });
                            },10);
                        });
                    </script>

                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kalite_kontrol_cihaz_listesi_datatb" style="font-size: 13px;">
                        <thead>
                        <tr>
                            <th>Cihaz Adı</th>
                            <th>Durum</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sql = verilericoklucek("select * from lab_devices");
                        foreach ((array)$sql as $rowa ) {?>
                            <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kalite_tanimli_cihaz_sec">
                                <td><?php echo mb_strtoupper($rowa["device_name"]); ?></td>
                                <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <script>
                        $(".kalite_tanimli_cihaz_sec").click(function () {
                            $(this).css('background-color') != 'rgb(147,203,198)' ;
                            $('.kalite_tanimli_cihaz_sec-kaldir').removeClass("text-white");
                            $('.kalite_tanimli_cihaz_sec-kaldir').removeClass("kalite_tanimli_cihaz_sec-kaldir");
                            $('.kalite_tanimli_cihaz_sec').css({"background-color": "rgb(255, 255, 255)"});
                            $(this).css({"background-color": "rgb(147,203,198)"});
                            $(this).addClass("text-white kalite_tanimli_cihaz_sec-kaldir");

                            var getir=$('.kalite_tanimli_cihaz_sec-kaldir').attr('id');

                            $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=liste_tanimli_kalite_kontrol",{"getir":getir},function(getVeri){
                                $('#tanimli-kalite-kontrol-listesi').html(getVeri);
                            });

                            $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=lot_secimi_uyari_mesaj",function(getVeri){
                                $('#tanimli-kalite-kontrol-lot-listesi').html(getVeri);
                            });

                            $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=bos_ekran",function(getVeri){
                                $('#kalite-kontrol-test-tanitma').html(getVeri);
                            });

                        });
                    </script>
                </div>

                <div class="col-4">
                    <div id="tanimli-kalite-kontrol-listesi">

                        <div class="warning-definitions mt-5">
                            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                <h5 class="text-warning">YAN TARAFTAN SEÇİM YAPINIZ</h5>
                                <p>Cihaza Tanımlı Kontrol Tanımlarını Görmek İçin Cihaz Seçiniz Seçiniz.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-4">
                    <div id="tanimli-kalite-kontrol-lot-listesi"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12 row mt-2">

        <div id="kalite-kontrol-test-tanitma"></div>

    </div>
<?php }

else if($islem=="lot_secimi_uyari_mesaj"){?>

    <div class="warning-definitions mt-5">
        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
            <h5 class="text-warning">YAN TARAFTAN SEÇİM YAPINIZ</h5>
            <p>Kontrol Tanımına Tanımlı Lot Tanımlarını Görmek İçin Kontrol Tanımı Seçiniz Seçiniz.</p>
        </div>
    </div>

<?php }

else if($islem=="bos_ekran"){?>

<?php }

else if($islem=="lot_test_tanimlama_uyari_mesaj"){?>

    <div class="warning-definitions mt-5">
        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
            <h5 class="text-warning">ÜST TARAFTAN SEÇİM İŞLEMLERİNİ YAPINIZ</h5>
            <p>Test Tanıtma İşlemi İçin Sol Taraftan Seçim İşlemlerini Yapınız</p>
        </div>
    </div>

<?php }

else if ($islem=="liste_tanimli_kalite_kontrol"){
    $gelen_veri=$_GET["getir"];
    $cihaz_tablo=singularactive("lab_devices","id",$gelen_veri);?>
    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b> <?php echo strtoupper($cihaz_tablo["device_name"]);?> - Cihazına Tanımlı Kontrol Tanımları</b></div>

    <div class="mt-3"></div>
    <script>
        $(document).ready(function(){
            setTimeout(function() {
                $('#tanimli_kalite_kontrol_datatb').DataTable( {
                    "scrollY": '21vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });
            },10);
        });
    </script>

    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tanimli_kalite_kontrol_datatb" style="font-size: 13px;">
        <thead>
        <tr>
            <th>Kontrol Adı</th>
            <th>Durumu</th>
        </tr>
        </thead>
        <tbody>
        <?php $sql = verilericoklucek("select * from lab_device_quality_control where device_id=$gelen_veri");
        foreach ((array)$sql as $row){?>
            <tr style="cursor: pointer;" id="<?php echo $row["id"];?>" class="czh_kalite_kontrol_sec">
                <td><?php echo mb_strtoupper($row["control_name"]); ?></td>
                <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
        $(".czh_kalite_kontrol_sec").click(function () {
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.czh_kalite_kontrol_sec-kaldir').removeClass("text-white");
            $('.czh_kalite_kontrol_sec-kaldir').removeClass("czh_kalite_kontrol_sec-kaldir");
            $('.czh_kalite_kontrol_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white czh_kalite_kontrol_sec-kaldir");

            var getir=$('.czh_kalite_kontrol_sec-kaldir').attr('id');

            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=liste_tanimli_kalite_kontrol_lot",{ "getir":getir },function(getveri){
                $('#tanimli-kalite-kontrol-lot-listesi').html(getveri);
            });

            $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=lot_test_tanimlama_uyari_mesaj",function(getVeri){
                $('#kalite-kontrol-test-tanitma').html(getVeri);
            });


        });
    </script>
<?php }


else if($islem=="liste_tanimli_kalite_kontrol_lot"){
    $gelen_veri=$_GET["getir"];
    $cihaz_tablo=singularactive("lab_device_quality_control","id",$gelen_veri);?>

    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b> <?php echo strtoupper($cihaz_tablo["control_name"]);?> - Kontrol Tanımına Tanımlı Lot Tanımları</b></div>

    <div class="mt-3"></div>

    <script>
        $(document).ready(function(){
            setTimeout(function() {
                $('#tanimli_kalite_kontrol_lot_datatb').DataTable( {
                    "scrollY": '21vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });
            },10);
        });
    </script>

    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tanimli_kalite_kontrol_lot_datatb" style="font-size: 13px;">
        <thead>
        <tr>
            <th>Lot Adı</th>
            <th>Durumu</th>
        </tr>
        </thead>
        <tbody>
        <?php $sql = verilericoklucek("select * from lab_device_quality_lot where control_id=$gelen_veri");
        foreach ((array)$sql as $row){?>
            <tr style="cursor: pointer;" id="<?php echo $row["id"];?>" class="czh_kalite_kontrol_lot_sec">
                <td><?php echo mb_strtoupper($row["lot_name"]); ?></td>
                <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
        $(".czh_kalite_kontrol_lot_sec").click(function () {
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.czh_kalite_kontrol_lot_sec-kaldir').removeClass("text-white");
            $('.czh_kalite_kontrol_lot_sec-kaldir').removeClass("czh_kalite_kontrol_lot_sec-kaldir");
            $('.czh_kalite_kontrol_lot_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white czh_kalite_kontrol_lot_sec-kaldir");

            var getir=$('.czh_kalite_kontrol_lot_sec-kaldir').attr('id');

            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=kalite-kontrol-test-tanitma-liste",{ "getir":getir },function(getveri){
                $('#kalite-kontrol-test-tanitma').html(getveri);
            });
        });
    </script>
<?php }
else if($islem=="kalite-kontrol-test-tanitma-liste"){
    $gelen_veri=$_GET["getir"];?>

        <input type="text" hidden id="lot_id" value="<?php echo $gelen_veri;?>">

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Kalite Kontrol Tanımlı Testler</b></div>
        <div class="card-body" style="height: 40vh;">


            <script>

                var getir=$("#lot_id").val();

                $('#kalite_kontrol_test_tanim_datatb').DataTable( {
                    "scrollY": '26vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Test Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet',

                            action: function ( e, dt, button, config ) {
                                alert(getir);


                                $('.tanimlamalar_w90_h55').window('setTitle', 'Kalite Kontrol Test Tanımla');
                                $('.tanimlamalar_w90_h55').window('open');
                                $('.tanimlamalar_w90_h55').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=modal-kalite-kontrol-test-tanimla&getir='+getir+'');
                            }
                        }
                    ],
                });

            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kalite_kontrol_test_tanim_datatb" style="font-size: 13px;">
                <thead>
                <tr>
                    <th>Tetkik Adı</th>
                    <th>Parametre Adı</th>
                    <th>Min</th>
                    <th>Max</th>
                    <th>Ortalama</th>
                    <th>SD</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_define_quality_control_test where lot_id=$gelen_veri");
                foreach ((array)$sql as $rowa){
                    $parametre=singular("lab_test_parameter","id",$rowa["parameter_id"]);
                    $tetkik=singular("lab_analysis","id",$parametre["analysis_id"]);
                    ?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"];?>" class="kalite_kontrol_test_tanim_sec">
                        <td><?php echo mb_strtoupper($tetkik["analysis_name"]);?></td>
                        <td><?php echo mb_strtoupper($parametre["parameter_name"]);?></td>
                        <td><?php echo mb_strtoupper($rowa["minimum"]);?></td>
                        <td><?php echo mb_strtoupper($rowa["maximum"]);?></td>
                        <td><?php echo mb_strtoupper($rowa["average"]);?></td>
                        <td><?php echo mb_strtoupper($rowa["sd_standard_deviation"]);?></td>
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square kalite_kontrol_test_tanim_duzenle" title="Düzenle" alt="icon" analysis-id="<?php echo $tetkik["id"];?>" parametre-id="<?php echo $parametre["id"];?>" id="<?php echo $rowa["id"]; ?>"></i>

                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle kalite_kontrol_test_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash kalite_kontrol_test_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>



    <script>
        $("body").off("click", ".kalite_kontrol_test_tanim_duzenle").on("click", ".kalite_kontrol_test_tanim_duzenle", function(e){
            var getir = $(this).attr('id');
            var analysis_id = $(this).attr('analysis-id');
            var parametre_id = $(this).attr('parametre-id');
            var lot_id = "<?php echo $gelen_veri?>";
            $('#test-tanim-parametre-sec').remove();


            $('.tanimlamalar_w90_h55').window('setTitle', 'Kalite Kontrol Test Tanım Düzenle');
            $('.tanimlamalar_w90_h55').window('open');
            $('.tanimlamalar_w90_h55').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=modal-kalite-kontrol-test-duzenle&getir='+getir+'&analysis_id='+analysis_id+'&parametre_id='+parametre_id+'&lot_id='+lot_id+'');
        });

        $("body").off("click", ".kalite_kontrol_test_tanim_sil").on("click", ".kalite_kontrol_test_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kalite Kontrol Test Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=sql_kalite_kontrol_test_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $gelen_veri?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=kalite-kontrol-test-tanitma-liste",{ "getir":getir},function(e){
                                $('#kalite-kontrol-test-tanitma').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kalite_kontrol_test_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Kalite Kontrol Test Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".kalite_kontrol_test_tanim_aktif").on("click",".kalite_kontrol_test_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=sql_kalite_kontrol_test_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=kalite-kontrol-test-tanitma-liste",{ "getir":getir},function(e){
                        $('#kalite-kontrol-test-tanitma').html(e);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-kalite-kontrol-test-tanimla"){
    $gelen_veri=$_GET["getir"];?>

    <input type="text" hidden id="gelen_lot_id" value="<?php echo $gelen_veri;?>">

    <div class="col-12 mt-2">
        <div class="card">
            <div class="card-body row">
                <div class="col-4">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tetkik Listesi</b></div>
                    <div class="mt-3"></div>
                    <script>
                        $(document).ready(function(){
                            $('#tetkik-tanimlari-list-datatable').DataTable( {
                                "scrollY": '20vh',
                                "scrollX": true,
                                "info":true,
                                "paging":false,
                                "searching":true,
                                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                            });
                        });
                    </script>

                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tetkik-tanimlari-list-datatable" style="font-size: 13px;">
                        <thead>
                        <tr>
                            <th title="Test Grubu">Grubu</th>
                            <th>SUT Kodu</th>
                            <th>Tetkik Adı</th>
                            <th>Durum</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id");
                        foreach ((array)$sql as $rowa ) {?>
                            <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="analysis_sec">
                                <td><?php echo mb_strtoupper($rowa["group_name"]); ?></td>
                                <td><?php echo mb_strtoupper($rowa["sut_code"]); ?></td>
                                <td><?php echo mb_strtoupper($rowa["analysis_name"]); ?></td >
                                <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <script>
                        $(".analysis_sec").click(function () {
                            $(this).css('background-color') != 'rgb(147,203,198)' ;
                            $('.analysis_sec-kaldir').removeClass("text-white");
                            $('.analysis_sec-kaldir').removeClass("analysis_sec-kaldir");
                            $('.analysis_sec').css({"background-color": "rgb(255, 255, 255)"});
                            $(this).css({"background-color": "rgb(147,203,198)"});
                            $(this).addClass("text-white analysis_sec-kaldir");

                            var getir=$('.analysis_sec-kaldir').attr('id');
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=test_tanimlama_parametre_listesi",{ "getir":getir },function(getveri){
                                $('#test-tanim-parametre-sec').html(getveri);
                            });
                        });
                    </script>

                </div>

                <div class="col-5">
                    <div id="test-tanim-parametre-sec">

                        <div class="warning-definitions mt-5">
                            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                                <p>Kalite Kontrol Test Tanıtma İşlemi İçin Parametre Seçiniz.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-3">

                    <div hidden id="test-tanim-form">
                        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Test Tanım Formu</b></div>

                        <div class="mt-3"></div>

                        <form id="kalite_kontrol_test_tanimla_form" action="javascript:void(0);">

                            <input type="text" hidden class="form-control form-control-sm" name="lot_id" value="<?php echo $gelen_veri;?>">

                            <input type="text" hidden class="form-control form-control-sm" id="parametre_id" name="parameter_id">

                            <div class="row">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Min. Değer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" name="minimum">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group row mt-2">
                                    <label class="col-sm-3 col-form-label">Max. Değer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" name="maximum">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Ortalama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" name="average">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" title="Sapma Değeri">SD</label>
                                    <div class="col-sm-9">
                                        <input type="text" title="Sapma Değeri" class="form-control form-control-sm" name="sd_standard_deviation">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-4"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-kalite-kontrol-test-tanimla">Kaydet</a>
            </div>
        </div>
    </div>


    <script>

        $("body").off("click", "#btn-kalite-kontrol-test-tanimla").on("click", "#btn-kalite-kontrol-test-tanimla", function(e){
            var kalite_kontrol_test_tanimla_form = $("#kalite_kontrol_test_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=sql_kalite_kontrol_test_ekle',
                data:kalite_kontrol_test_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = $("#gelen_lot_id").val();
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=kalite-kontrol-test-tanitma-liste",{ "getir":getir},function(e){
                        $('#kalite-kontrol-test-tanitma').html(e);
                    });
                    $('.tanimlamalar_w90_h55').window('close');
                }
            });

        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w90_h55').window('close');
        });
    </script>

<?php }

else if($islem=="modal-kalite-kontrol-test-duzenle"){
    $gelen_veri=$_GET["getir"];
    $lot_id=$_GET["lot_id"];
    $analysis_id=$_GET["analysis_id"];
    $parametre_id=$_GET["parametre_id"];
    $lab_kalite_kontrol_test=singularactive("lab_define_quality_control_test","id",$gelen_veri); ?>

    <input type="text" hidden id="duzenle_lot_id" value="<?php echo $lot_id;?>">
    <input type="text" hidden id="tetkik_id" value="<?php echo $analysis_id;?>">

    <div class="col-12">
        <div class="card">
            <div class="card-body row">
                <div class="col-4">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tetkik Listesi</b></div>
                    <div class="mt-3"></div>
                    <script>
                        $(document).ready(function(){
                            $('#tetkik-tanimlari-list-datatable1').DataTable( {
                                "scrollY": '20vh',
                                "scrollX": true,
                                "info":true,
                                "paging":false,
                                "searching":true,
                                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                            });
                            var tetkik=$("#tetkik_id").val();

                            $('#tetkik-data-select'+tetkik).addClass('bg-yesil text-white');

                            var getir=$('.bg-yesil').attr('tetkik-id');
                            var parametre_id = "<?php echo $parametre_id;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=test_tanimlama_parametre_listesi",{ "getir":getir,"parametre_id":parametre_id},function(getveri){
                                $('#test-tanim-parametre-sec').html(getveri);
                            });

                        });
                    </script>

                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tetkik-tanimlari-list-datatable1" style="font-size: 13px;">
                        <thead>
                        <tr>
                            <th>ıd</th>
                            <th title="Test Grubu">Grubu</th>
                            <th>SUT Kodu</th>
                            <th>Tetkik Adı</th>
                            <th>Durum</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id");
                        foreach ((array)$sql as $rowa ) { ?>
                            <tr style="cursor: pointer;" id="tetkik-data-select<?php echo $rowa["lab_analysisid"]; ?>" tetkik-id="<?php echo $rowa["lab_analysisid"]; ?>" class="analysis_sec1">
                                <td><?php echo mb_strtoupper($rowa["lab_analysisid"]); ?></td>
                                <td><?php echo mb_strtoupper($rowa["group_name"]); ?></td>
                                <td><?php echo mb_strtoupper($rowa["sut_code"]); ?></td>
                                <td><?php echo mb_strtoupper($rowa["analysis_name"]); ?></td >
                                <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <script>

                        $(".analysis_sec1").click(function () {
                            $(".analysis_sec1").removeClass("text-white bg-yesil");
                            $(this).addClass("text-white bg-yesil");

                            var getir=$('.bg-yesil').attr('tetkik-id');

                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=test_tanimlama_parametre_listesi",{ "getir":getir},function(getveri){
                                $('#test-tanim-parametre-sec').html(getveri);
                            });
                        });

                    </script>

                </div>

                <div class="col-5">
                    <div id="test-tanim-parametre-sec">

                        <div class="warning-definitions mt-5">
                            <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                                <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                                <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                                <p>Kalite Kontrol Test Tanıtma İşlemi İçin Parametre Seçiniz.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-3">

                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Test Tanım Formu</b></div>

                    <div class="mt-3"></div>

                    <form id="kalite_kontrol_test_tanim_duzenle_form" action="javascript:void(0);">

                        <input type="text" hidden class="form-control form-control-sm" name="id" value="<?php echo $lab_kalite_kontrol_test["id"];?>">

                        <input type="text" hidden class="form-control form-control-sm" id="parametre_id1" name="parameter_id">

                        <div class="row">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Min. Değer</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" name="minimum" value="<?php echo $lab_kalite_kontrol_test["minimum"];?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group row mt-2">
                                <label class="col-sm-3 col-form-label">Max. Değer</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" name="maximum" value="<?php echo $lab_kalite_kontrol_test["maximum"];?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Ortalama</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" name="average" value="<?php echo $lab_kalite_kontrol_test["average"];?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" title="Sapma Değeri">SD</label>
                                <div class="col-sm-9">
                                    <input type="text" title="Sapma Değeri" class="form-control form-control-sm" name="sd_standard_deviation" value="<?php echo $lab_kalite_kontrol_test["sd_standard_deviation"];?>">
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <div class="mt-4"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-kalite-kontrol-test-duzenle">Kaydet</a>
            </div>
        </div>
    </div>



    <script>

        $("body").off("click", "#btn-kalite-kontrol-test-duzenle").on("click", "#btn-kalite-kontrol-test-duzenle", function(e){
            var kalite_kontrol_test_tanim_duzenle_form = $("#kalite_kontrol_test_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=sql_kalite_kontrol_test_duzenle',
                data:kalite_kontrol_test_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    var duzenle_lot_id = $("#duzenle_lot_id").val();
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontroltesttanitma.php?islem=kalite-kontrol-test-tanitma-liste",{ "getir":duzenle_lot_id},function(e){
                        $('#kalite-kontrol-test-tanitma').html(e);
                    });

                    $('.tanimlamalar_w90_h55').window('close');
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w90_h55').window('close');
        });

    </script>

<?php }

else if($islem=="test_tanimlama_parametre_listesi"){
    $gelen_veri = $_GET["getir"];
    $parametre_id = $_GET["parametre_id"];?>
    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Parametreler</b></div>

    <div class="mt-3"></div>

    <script>
        $(document).ready(function(){
            $('#test_tanimlama_parametre_liste_datatable').DataTable( {
                "scrollY": '20vh',
                "scrollX": true,
                "info":true,
                "paging":false,
                "searching":true,
                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            });

            var parametre_id="<?php echo $parametre_id;?>";

            $('#parameter-data-select'+parametre_id).addClass('bg-yesil-2 text-white');
            $('#parametre_id1').val(parametre_id);

        });
    </script>

    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="test_tanimlama_parametre_liste_datatable" style="font-size: 13px;">
        <thead>
        <tr>
            <th title="Parameter Adı">Adı</th>
            <th title="Rapora Basıcalak Ad">Rap. Bas. Ad</th>
            <th title="Kısa Adı">K. Adı</th>
            <th title="İletişim No">İletişim</th>
            <th title="Birim">Birim</th>
            <th title="Alt Limit Numerik">Alt Lim. Num.</th>
            <th title="Üst Limit Numerik">Üst Lim. Num.</th>
            <th title="Alt Limit Text">Alt Lim. Text</th>
            <th title="Üst Limit Text">Üst Lim. Text</th>
            <th title="Özel Referanslar">Özel Ref.</th>
            <th>Durum</th>
        </tr>
        </thead>
        <tbody>
        <?php $sql = verilericoklucek("select * from lab_test_parameter where lab_test_parameter.analysis_id='$gelen_veri'");
        foreach ((array)$sql as $rowa){ ?>
            <tr style="cursor: pointer;" id="parameter-data-select<?php echo $rowa["id"];?>" data-id="<?php echo $rowa["id"];?>" class="test_tanim_parametre_sec">
                <td><?php echo $rowa["parameter_name"]; ?></td >
                <td><?php echo $rowa["report_print_name"]; ?></td >
                <td><?php echo $rowa["short_name"]; ?></td >
                <td><?php echo $rowa["contact_no"]; ?></td >
                <td><?php echo labtanimgetirid($rowa["unit"]); ?></td >
                <td><?php echo $rowa["sub_limit_numeric"]; ?></td >
                <td><?php echo $rowa["up_limit_numeric"]; ?></td >
                <td><?php echo $rowa["sub_limit_text"]; ?></td >
                <td><?php echo $rowa["up_limit_text"]; ?></td >
                <td><?php echo $rowa["special_referances"]; ?></td >
                <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
        $(".test_tanim_parametre_sec").click(function () {
            $('#test-tanim-form').attr('hidden', false);
            $(".test_tanim_parametre_sec").removeClass("text-white bg-yesil-2");
            $(this).addClass("text-white bg-yesil-2");
            console.log()

            var getir=$('.bg-yesil-2').attr('data-id');
            $('#parametre_id').val(getir);
            $('#parametre_id1').val(getir);
        });
    </script>
<?php } ?>
