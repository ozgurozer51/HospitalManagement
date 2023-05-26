<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_cihaz_kalite_kontrol_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_device_quality_control",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Cihaza Kalite Konrtol Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Kalite Konrtol Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_cihaz_kalite_kontrol_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_device_quality_control", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Cihaza Tanımlı Kalite Konrtol Tanımını Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Tanımlı Kalite Konrtol Tanımını Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_cihaz_kalite_kontrol_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_device_quality_control", "id", $id, $detay, $kullanici, $tarih);
    if ($sql == 1){?>
        <script>
            alertify.success('Cihaza Tanımlı Kalite Konrtol Tanımını Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Tanımlı Kalite Konrtol Tanımını Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_cihaz_kalite_kontrol_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_device_quality_control','id',$id,$date,$user);
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

else if ($islem=="sql_cihaz_kalite_kontrol_lot_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_device_quality_lot",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Lot Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Lot Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_cihaz_kalite_kontrol_lot_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_device_quality_lot", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Lot Tanımını Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Lot Tanımını Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_cihaz_kalite_kontrol_lot_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_device_quality_lot", "id", $id, $detay, $kullanici, $tarih);
    if ($sql == 1){?>
        <script>
            alertify.success('Lot Tanımını Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Lot Tanımını Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_cihaz_kalite_kontrol_lot_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_device_quality_lot','id',$id,$date,$user);
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

    <div id="bakteri-tanim">
        <div class="col-12 row">

            <div class="col-4">

                <div class="card">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Cihaz Listesi</b></div>
                    <div class="card-body " style="height: 84vh;">
                        <script>
                            $('#cihaz-listesi-datatable').DataTable( {
                                "scrollY": '69vh',
                                "scrollX": true,
                                "info":true,
                                "paging":false,
                                "searching":true,
                                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                                "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                                    "<'row'<'col-sm-12'tr>>" +
                                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                                buttons: [
                                    {
                                        text: 'Kontrol Tanımla',
                                        className: 'btn btn-success btn-sm btn-kotnrol-tanimla',
                                        attr:  {
                                            'disabled':true,
                                        },
                                        action: function ( e, dt, button, config ) {
                                            var getir=$('.cihaz_tanim_sec-kaldir').attr('id');

                                            $('.tanimlamalar_w40_h20').window('setTitle', 'Cihaza Kontrol Tanımla');
                                            $('.tanimlamalar_w40_h20').window('open');
                                            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=kontrol_tanimla_modal&getir='+ getir +'');
                                        }
                                    }
                                ],
                            } );
                        </script>

                        <table id="cihaz-listesi-datatable" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
                            <thead>
                            <tr>
                                <th>Cihaz Adı</th>
                                <th>Durum</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sql = verilericoklucek("select * from lab_devices");
                            foreach ((array)$sql as $rowa ) {?>
                                <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="cihaz_tanim_sec">
                                    <td><?php echo mb_strtoupper($rowa["device_name"]); ?></td>
                                    <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    $(".cihaz_tanim_sec").click(function () {
                        $('.btn-kotnrol-tanimla').attr('disabled', false);
                        $(this).css('background-color') != 'rgb(147,203,198)' ;
                        $('.cihaz_tanim_sec-kaldir').removeClass("text-white");
                        $('.cihaz_tanim_sec-kaldir').removeClass("cihaz_tanim_sec-kaldir");
                        $('.cihaz_tanim_sec').css({"background-color": "rgb(255, 255, 255)"});
                        $(this).css({"background-color": "rgb(147,203,198)"});
                        $(this).addClass("text-white cihaz_tanim_sec-kaldir");

                        var getir=$('.cihaz_tanim_sec-kaldir').attr('id');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_listesi",{ "getir":getir },function(getveri){
                            $('#kontrol-tanim-listesi').html(getveri);
                        });
                    });
                </script>
                
            </div>

            <div class="col-4">

                <div id="kontrol-tanim-listesi">
                    <div class="warning-definitions mt-5">
                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                            <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                            <p>Cihaza Tanımlı Kontrol Tanımları Liste ve Lot Tanımı Ekleme İşlemleri İçin Cihaz Seçiniz.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-4">

                <div id="lot-tanim-listesi">
                    <div class="warning-definitions mt-5">
                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                            <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                            <p>Lot Tanımı Listesi İçin Kontrol Tanımı Seçiniz.</p>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
<?php } 
else if ($islem=="kontrol_tanimla_modal"){
    $gelen_veri = $_GET['getir'];?>

    <div class=" mt-3 mx-1">

        <form id="cihaza_kalite_kontrol_tanimla_form" action="javascript:void(0);">

            <input type="text" hidden name="device_id" value="<?php echo $gelen_veri?>">


            <div class="row">

                <div class="col-4">
                    <label>Kontrol Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="control_name"/>
                </div>

            </div>


        </form>

        <div class="mt-5"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaza-kalite-kontrol-tanimla">Kaydet</a>
            </div>
        </div>

    </div>


    <script>

        $("body").off("click", "#btn-cihaza-kalite-kontrol-tanimla").on("click", "#btn-cihaza-kalite-kontrol-tanimla", function(e){

                var cihaza_kalite_kontrol_tanimla_form = $("#cihaza_kalite_kontrol_tanimla_form").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_tanimi_ekle',
                    data:cihaza_kalite_kontrol_tanimla_form,
                    success:function(e){

                        $('#sonucyaz').html(e);

                        var getir = "<?php echo $gelen_veri;?>"

                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_listesi",{ "getir":getir},function(e){
                            $('#kontrol-tanim-listesi').html(e);
                        });

                        $('.tanimlamalar_w40_h20').window('close');
                    }
                });

            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });

    </script>
<?php }

else if($islem=="cihaza_tanimli_kontrol_listesi"){
    $gelen_veri=$_GET["getir"];?>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Cihaz Tanımlı Kontrol Tanım Listesi</b></div>
        <div class="card-body " style="height: 84vh;">
            <script>
                $('#kontrol-tanim-listesi-datatable').DataTable( {
                    "scrollY": '69vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Lot Tanımla',
                            className: 'btn btn-success btn-sm btn-lot-tanimla',
                            attr:  {
                                'disabled':true,

                            },
                            action: function ( e, dt, button, config ) {
                                var gotur=$('.kontrol_tanim_sec-kaldir').attr('id');

                                $('.tanimlamalar_w40_h20').window('setTitle', 'Lot Tanımla');
                                $('.tanimlamalar_w40_h20').window('open');
                                $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=modal_lot_tanimla&gotur='+ gotur +'');
                            }
                        }
                    ],
                } );
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kontrol-tanim-listesi-datatable">
                <thead>
                <tr>
                    <th>Kontrol Adı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_device_quality_control where device_id=$gelen_veri");
                foreach ((array)$sql as $rowa ) {?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kontrol_tanim_sec">
                        <td><?php echo mb_strtoupper($rowa["control_name"]); ?></td>
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square kontrol_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>
                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle kontrol_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash kontrol_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(".kontrol_tanim_sec").click(function () {
            $('.btn-lot-tanimla').attr('disabled', false);

            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.kontrol_tanim_sec-kaldir').removeClass("text-white");
            $('.kontrol_tanim_sec-kaldir').removeClass("kontrol_tanim_sec-kaldir");
            $('.kontrol_tanim_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white kontrol_tanim_sec-kaldir");

            var getir=$('.kontrol_tanim_sec-kaldir').attr('id');
            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_lot_listesi",{ "getir":getir },function(getveri){
                $('#lot-tanim-listesi').html(getveri);
            });
        });
    </script>


    <script>
        $("body").off("click", ".kontrol_tanim_duzenle").on("click", ".kontrol_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h20').window('setTitle', 'Kontrol Tanımı Düzenle');
            $('.tanimlamalar_w40_h20').window('open');
            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=kontrol_tanim_duzenle_modal&getir='+ getir +'');
        });

        $("body").off("click", ".kontrol_tanim_sil").on("click", ".kontrol_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Cihaz Tanımlı Kontrol Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $gelen_veri?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_listesi",{ "getir":getir},function(e){
                                $('#kontrol-tanim-listesi').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kontrol_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Cihaz Tanımlı Kontrol Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".kontrol_tanim_aktif").on("click",".kontrol_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_listesi",{ "getir":getir},function(e){
                        $('#kontrol-tanim-listesi').html(e);
                    });
                }
            });
        });
    </script>

<?php }

else if ($islem=="kontrol_tanim_duzenle_modal"){
    $gelen_veri = $_GET['getir'];
    $lab_kalite_kontrol=singularactive("lab_device_quality_control","id",$gelen_veri);?>


    <div class=" mt-3 mx-1">

        <form id="cihaza_kalite_kontrol_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" value="<?php echo $lab_kalite_kontrol["id"];?>">


            <div class="row">

                <div class="col-4">
                    <label>Kontrol Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="control_name" value="<?php echo mb_strtoupper($lab_kalite_kontrol["control_name"]);?>"/>
                </div>

            </div>


        </form>

        <div class="mt-5"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaza-kalite-kontrol-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>
        $("body").off("click", "#btn-cihaza-kalite-kontrol-duzenle").on("click", "#btn-cihaza-kalite-kontrol-duzenle", function(e){
                var cihaza_kalite_kontrol_duzenle_form = $("#cihaza_kalite_kontrol_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_tanimi_duzenle',
                    data:cihaza_kalite_kontrol_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        var getir = "<?php echo $lab_kalite_kontrol["device_id"];?>"
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_listesi",{ "getir":getir},function(e){
                            $('#kontrol-tanim-listesi').html(e);
                        });
                        $('.tanimlamalar_w40_h20').window('close');
                    }
                });
            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });
    </script>
<?php }

else if ($islem=="modal_lot_tanimla"){
    $gelen_veri = $_GET['gotur'];?>


    <div class=" mt-3 mx-1">

        <form id="lot_tanimla_form" action="javascript:void(0);">


            <input type="text" hidden name="control_id" value="<?php echo $gelen_veri;?>">


            <div class="row">

                <div class="col-4">
                    <label>Lot Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="lot_name">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Lis No</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="lis_no">
                </div>

            </div>


        </form>

        <div class="mt-3"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-lot-tanimla">Kaydet</a>
            </div>
        </div>

    </div>


    <script>

        $("body").off("click", "#btn-lot-tanimla").on("click", "#btn-lot-tanimla", function(e){
            var lot_tanimla_form = $("#lot_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_lot_tanimi_ekle',
                data:lot_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri;?>"
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_lot_listesi",{ "getir":getir},function(e){
                        $('#lot-tanim-listesi').html(e);
                    });

                    $('.tanimlamalar_w40_h20').window('close');
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });

    </script>
<?php }

else if($islem=="cihaza_tanimli_kontrol_lot_listesi"){
      $gelen_veri=$_GET["getir"]; ?>
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Kontrole Tanımlı Lot Tanım Listesi</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>
                $('#kontrol-lot-tanim-listesi-datatable').DataTable( {
                    "scrollY": '69vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });
            </script>

            <table id="kontrol-lot-tanim-listesi-datatable" class="table table-sm table-bordered w-100 display nowrap" >
                <thead>
                <tr>
                    <th>Lot Adı</th>
                    <th>Lis. No</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_device_quality_lot where control_id=$gelen_veri");
                foreach ((array)$sql as $rowa ) {?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"];?>">
                        <td><?php echo mb_strtoupper($rowa["lot_name"]);?></td>
                        <td><?php echo mb_strtoupper($rowa["lis_no"]);?></td>
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square lot_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>
                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle lot_tanim_aktif" title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash lot_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        $("body").off("click", ".lot_tanim_duzenle").on("click", ".lot_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h20').window('setTitle', 'Lot Tanımı Düzenle');
            $('.tanimlamalar_w40_h20').window('open');
            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=modal_lot_tanim_duzenle&getir='+ getir +'');
        });

        $("body").off("click", ".lot_tanim_sil").on("click", ".lot_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kontrole Tanımlı Lot Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_lot_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $gelen_veri?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_lot_listesi",{ "getir":getir},function(e){
                                $('#lot-tanim-listesi').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".lot_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Kontrole Tanımlı Lot Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".lot_tanim_aktif").on("click",".lot_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_lot_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_lot_listesi",{ "getir":getir},function(e){
                        $('#lot-tanim-listesi').html(e);
                    });
                }
            });
        });
    </script>
<?php }

else if ($islem=="modal_lot_tanim_duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_kalite_kontrol_lot=singularactive("lab_device_quality_lot","id",$gelen_veri);?>

    <div class=" mt-3 mx-1">

        <form id="lot_tanim_duzenle_form" action="javascript:void(0);">


            <input type="text" hidden class="form-control" name="id" value="<?php echo $lab_kalite_kontrol_lot["id"];?>">


            <div class="row">

                <div class="col-4">
                    <label>Lot Adı</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="lot_name" value="<?php echo strtoupper($lab_kalite_kontrol_lot["lot_name"]);?>">
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-4">
                    <label>Lis No</label>
                </div>

                <div class="col-8">
                    <input type="text" class="form-control" name="lis_no" value="<?php echo strtoupper($lab_kalite_kontrol_lot["lis_no"]);?>">
                </div>

            </div>


        </form>

        <div class="mt-3"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-lot-tanim-duzenle">Kaydet</a>
            </div>
        </div>

    </div>


    <script>

        $("body").off("click", "#btn-lot-tanim-duzenle").on("click", "#btn-lot-tanim-duzenle", function(e){
            var lot_tanim_duzenle_form = $("#lot_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=sql_cihaz_kalite_kontrol_lot_tanimi_duzenle',
                data:lot_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $lab_kalite_kontrol_lot["control_id"];?>"
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kalitekontrolvelottanitma.php?islem=cihaza_tanimli_kontrol_lot_listesi",{ "getir":getir},function(e){
                        $('#lot-tanim-listesi').html(e);
                    });

                    $('.tanimlamalar_w40_h20').window('close');
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });

    </script>
<?php } ?>