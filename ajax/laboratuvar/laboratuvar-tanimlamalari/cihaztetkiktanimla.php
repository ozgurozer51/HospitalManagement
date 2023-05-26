<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_cihaza_tetkik_tanimla"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_device_defined_assays",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Cihaza Tetkik Tanımlama İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Tetkik Tanımlama İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_cihaza_tanimli_tetkik_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_device_defined_assays", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Cihaza Tanımlı Tetkik Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Tanımlı Tetkik Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_cihaza_tanimli_tetkik_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_device_defined_assays", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Cihaza Tanımlı Tetkik Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaza Tanımlı Tetkik Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_cihaza_tanimli_tetkik_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_device_defined_assays','id',$id,$date,$user);

    if ($sql == 1) {?>
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


    <div class="row">

        <div class="col-5">

            <div class="card">
                <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Cihaz Listesi</b></div>
                <div class="card-body " style="height: 84vh;">

                    
                    <table id="cihaz_listesi_datatable" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                        <thead>
                        <tr>
                            <th>Cihaz Adı</th>
                            <th>Durum</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sql = verilericoklucek("select * from lab_devices");
                        foreach ((array)$sql as $rowa ) {?>
                            <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="cihaz_sec">
                                <td><?php echo $rowa["device_name"]; ?></td>
                                <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <script>

                    var cihaz_listesi = $('#cihaz_listesi_datatable').DataTable( {
                        scrollY: '69vh',
                        scrollX: true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });

                    $(".cihaz_sec").click(function () {
                        $(this).css('background-color') != 'rgb(147,203,198)' ;
                        $('.cihaz_sec-kaldir').removeClass("text-white");
                        $('.cihaz_sec-kaldir').removeClass("cihaz_sec-kaldir");
                        $('.cihaz_sec').css({"background-color": "rgb(255, 255, 255)"});
                        $(this).css({"background-color": "rgb(147,203,198)"});
                        $(this).addClass("text-white cihaz_sec-kaldir");

                        var getir=$('.cihaz_sec-kaldir').attr('id');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=cihaz_tetkik_tanim_liste",{ "getir":getir },function(getveri){
                            $('#cihaz-tetkik-tanim').html(getveri);
                        });
                    });

            </script>

        </div>

        <div class="col-7">
            <div id="cihaz-tetkik-tanim">

                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>Parametre İşlemlerini Yapmak İçin Tetkik Seçiniz.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

<?php } else if($islem=="cihaz_tetkik_tanim_liste"){
    $cihaz_id = $_GET['getir'];?>

    <input type="text" hidden  id="cihazin_id" value="<?php echo $cihaz_id;?>">

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Cihaza Tanımlı Tetkikler</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>
                var getir = $('#cihazin_id').val();
                var cihaza_tanimli_tetkikler = $('#cihaza_tanimli_tetkikler_datatable').DataTable( {
                    scrollY: '69vh',
                    scrollX: true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Cihaza Tetkik Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet',

                            action: function ( e, dt, button, config ) {

                                $('.tanimlamalar_w40_h45').window('setTitle', 'Cihaza Tetkik Tanımla');
                                $('.tanimlamalar_w40_h45').window('open');
                                $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=modal_cihaza_tetkik_tanimla&getir=' + getir + '');
                            }
                        }
                    ],
                } );
            </script>


            <table id="cihaza_tanimli_tetkikler_datatable" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                <thead>
                <tr>
                    <th>Cihaz Adı</th>
                    <th>Test Grubu</th>
                    <th>HBYS No</th>
                    <th>Tetkik Adı</th>
                    <th>Tüp Adı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_devices.id as lab_deviceid, lab_analysis.id as lab_analysisid, lab_analysis.status as analysis_status,lab_devices.status as devices_status ,lab_devices.* ,lab_analysis.*,lab_device_defined_assays.* from lab_device_defined_assays,lab_analysis,lab_devices where lab_device_defined_assays.device_id=lab_devices.id and lab_device_defined_assays.analysis_id=lab_analysis.id and lab_device_defined_assays.device_id=$cihaz_id");
                foreach ((array)$sql as $rowa){
                    $test_grup=singular("lab_test_groups","id",$rowa["test_group"]);?>
                    <tr  style="cursor: pointer;" id="<?php echo $rowa["id"] ?>">
                        <td><?php echo $rowa["device_name"]; ?></td >
                        <td><?php echo $test_grup["group_name"]; ?></td >
                        <td><?php echo $rowa["lab_analysisid"]; ?></td >
                        <td><?php echo $rowa["analysis_name"]; ?></td >
                        <td><?php echo labtanimgetirid($rowa["tube_container_type"]); ?></td >
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square cihaz_tetkik_tanim_duzenle" title="Düzenle" alt="icon" cihaz-id="<?php echo $cihaz_id?>" id="<?php echo $rowa["id"]; ?>"></i>

                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle cihaz_tetkik_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash cihaz_tetkik_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <script>
        $("body").off("click", ".cihaz_tetkik_tanim_duzenle").on("click", ".cihaz_tetkik_tanim_duzenle", function(e){
            var getir = $(this).attr('id');
            var cihazid = $(this).attr('cihaz-id');


            $('.tanimlamalar_w40_h45').window('setTitle', 'Cihaza Tanımlı Tetkik Düzenle');
            $('.tanimlamalar_w40_h45').window('open');
            $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=modal_cihaza_tanimli_tetkik_duzenle&getir=' + getir + '&cihazid='+cihazid+'');
        });

        $("body").off("click", ".cihaz_tetkik_tanim_sil").on("click", ".cihaz_tetkik_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Tanımlı Tetkik Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=sql_cihaza_tanimli_tetkik_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            var getir = "<?php echo $cihaz_id;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=cihaz_tetkik_tanim_liste",{ "getir":getir },function(e){
                                $('#cihaz-tetkik-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".cihaz_tetkik_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Tanımlı Tetkik Silme İşlemini Onayla"});
        });

        $("body").off("click",".cihaz_tetkik_tanim_aktif").on("click",".cihaz_tetkik_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=sql_cihaza_tanimli_tetkik_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $cihaz_id;?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=cihaz_tetkik_tanim_liste",{ "getir":getir },function(e){
                        $('#cihaz-tetkik-tanim').html(e);
                    });
                }
            });
        });
    </script>
<?php }

else if($islem=="modal_cihaza_tetkik_tanimla"){
    $cihaz_id = $_GET['getir'];?>

        <div class="mt-2">

                       <table id="cihaza_tetkik_tanimla_datatable" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                           <thead>
                           <tr>
                               <th title="Test Grubu">Grubu</th>
                               <th>SUT Kodu</th>
                               <th>Tetkik Adı</th>
                               <th>Durum</th>
                           </tr>
                           </thead>
                           <tbody>
                           <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id and lab_analysis.id NOT IN (select analysis_id from lab_device_defined_assays where device_id=$cihaz_id)");
                           foreach ((array)$sql as $rowa ) {?>
                               <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="tetkik_sec">
                                   <td><?php echo $rowa["group_name"]; ?></td>
                                   <td><?php echo $rowa["sut_code"]; ?></td>
                                   <td><?php echo $rowa["analysis_name"]; ?></td >
                                   <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                               </tr>
                           <?php } ?>
                           </tbody>
                       </table>

                       <script>

                                  var cihaza_tetkik_tanimla = $('#cihaza_tetkik_tanimla_datatable').DataTable( {
                                       scrollY: '25vh',
                                       scrollX: true,
                                       "info":true,
                                       "paging":false,
                                       "searching":true,
                                       "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                                   });


                           $(".tetkik_sec").click(function () {
                               $(this).css('background-color') != 'rgb(147,203,198)' ;
                               $('.tetkik_sec-kaldir').removeClass("text-white");
                               $('.tetkik_sec-kaldir').removeClass("tetkik_sec-kaldir");
                               $('.tetkik_sec').css({"background-color": "rgb(255, 255, 255)"});
                               $(this).css({"background-color": "rgb(147,203,198)"});
                               $(this).addClass("text-white tetkik_sec-kaldir");

                               var getir=$('.tetkik_sec-kaldir').attr('id');
                               $('#analysis_id').val(getir);
                           });
                       </script>

                <form id="cihaza_tetkik_tanimla_form" action="javascript:void(0);">
                    <input class="form-control" hidden type="text" name="device_id" id="device_id" value="<?php echo $cihaz_id;?>">
                    <input class="form-control" hidden type="text" name="analysis_id" id="analysis_id">
                </form>

            <div class="row" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaz-tetkik-tanimi-ekle">Kaydet</a>
                </div>
            </div>

        </div>

    <script>
        $("body").off("click", "#btn-cihaz-tetkik-tanimi-ekle").on("click", "#btn-cihaz-tetkik-tanimi-ekle", function(e){
            var cihaza_tetkik_tanimla_form = $("#cihaza_tetkik_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=sql_cihaza_tetkik_tanimla',
                data:cihaza_tetkik_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=cihaz_tetkik_tanim_liste",{ "getir":<?php echo $cihaz_id;?> },function(e){
                        $('#cihaz-tetkik-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });
    </script>
<?php }

else if($islem=="modal_cihaza_tanimli_tetkik_duzenle"){
    $gelen_veri = $_GET['getir'];
    $cihaza_tanimli_tetkik=singularactive("lab_device_defined_assays","id",$gelen_veri);
    $cihazid = $_GET['cihazid'];?>

        <div class="mt-2">

            <table id="cihaza_tanimli_tetkik_duzenle_datatable" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                <thead>
                <tr>
                    <th title="Test Grubu">Grubu</th>
                    <th>SUT Kodu</th>
                    <th>Tetkik Adı</th>
                    <th>Durum</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_analysis.id as lab_analysisid ,lab_analysis.status as analysis_status ,lab_analysis.*,lab_test_groups.* from lab_analysis,lab_test_groups where lab_analysis.test_group=lab_test_groups.id and lab_analysis.id NOT IN (select analysis_id from lab_device_defined_assays where device_id=$cihazid)");
                foreach ((array)$sql as $rowa ) {?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="tetkik_duzenle_sec">
                        <td><?php echo $rowa["group_name"]; ?></td>
                        <td><?php echo $rowa["sut_code"]; ?></td>
                        <td><?php echo $rowa["analysis_name"]; ?></td >
                        <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <script>

                var cihaza_tanimli_tetkik_duzenle = $('#cihaza_tanimli_tetkik_duzenle_datatable').DataTable( {
                    scrollY: '25vh',
                    scrollX: true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });


                $(".tetkik_duzenle_sec").click(function () {
                    $(this).css('background-color') != 'rgb(147,203,198)' ;
                    $('.tetkik_duzenle_sec-kaldir').removeClass("text-white");
                    $('.tetkik_duzenle_sec-kaldir').removeClass("tetkik_duzenle_sec-kaldir");
                    $('.tetkik_duzenle_sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white tetkik_duzenle_sec-kaldir");

                    var getir=$('.tetkik_duzenle_sec-kaldir').attr('id');
                    $('#analysis_id').val(getir);
                });
            </script>

            <form id="cihaz_tetkik_tanim_duzenle_form" action="javascript:void(0);">
                <input hidden class="form-control" type="text" name="id" id="id" value="<?php echo $gelen_veri; ?>">
                <input hidden class="form-control" type="text" name="analysis_id" id="analysis_id">
            </form>

            <div class="row" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaz-tetkik-tanimi-duzenle">Kaydet</a>
                </div>
            </div>

        </div>

    <script>

        $("body").off("click", "#btn-cihaz-tetkik-tanimi-duzenle").on("click", "#btn-cihaz-tetkik-tanimi-duzenle", function(e){
            var cihaz_tetkik_tanim_duzenle_form = $("#cihaz_tetkik_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=sql_cihaza_tanimli_tetkik_duzenle',
                data:cihaz_tetkik_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);

                    var getir = "<?php echo $cihaza_tanimli_tetkik["device_id"]?>";

                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztetkiktanimla.php?islem=cihaz_tetkik_tanim_liste",{ "getir":getir },function(e){
                        $('#cihaz-tetkik-tanim').html(e);
                    });

                    $('.tanimlamalar_w40_h45').window('close');
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });
    </script>
<?php } ?>