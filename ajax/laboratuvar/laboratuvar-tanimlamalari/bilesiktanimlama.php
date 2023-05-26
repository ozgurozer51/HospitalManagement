<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_bilesik_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_compound_definition",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Bileşik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bileşik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_bilesik_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_compound_definition", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Bileşik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bileşik Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_bilesik_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_compound_definition", "id", $id, $detay, $kullanici, $tarih);
    var_dump($sql);

    if ($sql == 1){?>
        <script>
            alertify.success('Bileşik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bileşik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_bilesik_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_compound_definition','id',$id,$date,$user);

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

if ($islem=="sql_zorunlu_tetkik_tanimla"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_compound_mandatory_test",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Zorunlu Tetkik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Zorunlu Tetkik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_zorunlu_tetkik_tanim_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_compound_mandatory_test", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Zorunlu Tetkik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Zorunlu Tetkik Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_zorunlu_tetkik_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_compound_mandatory_test", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success(' Zorunlu Tetkik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Zorunlu Tetkik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_zorunlu_tetkik_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_compound_mandatory_test','id',$id,$date,$user);

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
<div id="bilesik-tanim">
    <div class="col-12 row">


        <div class="col-6">

            <div class="card">
                <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bileşik Tanımları</b></div>
                <div class="card-body " style="height: 84vh;">

                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bilesik-tanimla-tab">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bileşik Adı</th>
                            <th>Test Grubu</th>
                            <th>Tetkik Adı</th>
                            <th>Bileşik Durumu</th>
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sql = verilericoklucek("select lab_compound_definition.id as bilesikid , lab_compound_definition.status as bilesik_durum ,lab_compound_definition.*,lab_analysis.*,lab_test_groups.* from lab_compound_definition, lab_analysis ,lab_test_groups where lab_compound_definition.analysis_id=lab_analysis.id and lab_compound_definition.test_groupsid=lab_test_groups.id ");
                        foreach ((array)$sql as $row){ ?>
                            <tr style="cursor: pointer;" id="<?php echo $row["bilesikid"]; ?>" test-groupid="<?php echo $row["test_group"]; ?>" class="bilesik-sec">
                                <td><?php echo $row["bilesikid"]; ?> </td>
                                <td><?php echo $row["compound_name"]; ?> </td >
                                <td><?php echo $row["group_name"]; ?> </td >
                                <td><?php echo $row["analysis_name"]; ?> </td >
                                <td align="center"><?php if($row["bilesik_durum"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                                <td align="center">
                                    <i class="fa fa-pen-to-square bilesik_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["bilesikid"]; ?>"></i>
                                    <?php if($row['bilesik_durum']=='0'){ ?>
                                        <i class="fa-solid fa-recycle bilesik_tanim_aktif"
                                           title="Aktif Et" id="<?php echo $row["bilesikid"]; ?>" alt="icon" ></i>
                                    <?php }else{ ?>
                                        <i class="fa fa-trash bilesik_tanim_sil" title="İptal" id="<?php echo $row["bilesikid"]; ?>" alt="icon"></i>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>



                    <script>
                        $('#bilesik-tanimla-tab').DataTable( {
                            scrollY: '69vh',
                            scrollX: true,
                            "info":true,
                            "paging":false,
                            "searching":true,
                            "language": {
                                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                            buttons: [
                                {
                                    text: 'Bileşik Tanımla',
                                    className: 'btn btn-sm btn-success btn-sm btn-kaydet',

                                    action: function ( e, dt, button, config ) {

                                        $('.tanimlamalar_w40_h25').window('setTitle', 'Bileşik Tanımla');
                                        $('.tanimlamalar_w40_h25').window('open');
                                        $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=modal-bilesik-tanimla');
                                    }
                                }
                            ],
                        });

                        $(".bilesik-sec").click(function () {
                            $(this).css('background-color') != 'rgb(147,203,198)' ;
                            $('.bilesik-sec-kaldir').removeClass("text-white");
                            $('.bilesik-sec-kaldir').removeClass("bilesik-sec-kaldir");
                            $('.bilesik-sec').css({"background-color": "rgb(255, 255, 255)"});
                            $(this).css({"background-color": "rgb(147,203,198)"});
                            $(this).addClass("text-white bilesik-sec-kaldir");

                            var bilesik_id=$('.bilesik-sec-kaldir').attr('id');
                            var test_groupsid=$('.bilesik-sec-kaldir').attr('test-groupid');
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=bilesik_zorunlu_tetkikler_list",{ bilesik_id:bilesik_id,test_groupsid:test_groupsid },function(getveri){
                                $('#bilesik_zorunlu_tetkik_tanim').html(getveri);
                            });
                        });

                        $("body").off("click", ".bilesik_tanim_duzenle").on("click", ".bilesik_tanim_duzenle", function(e){
                            var getir = $(this).attr('id');

                            $('.tanimlamalar_w40_h25').window('setTitle', 'Bileşik Tanımı Düzenle');
                            $('.tanimlamalar_w40_h25').window('open');
                            $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=modal-bilesik-tanim-duzenle&getir=' + getir + '');
                        });

                        $("body").off("click", ".bilesik_tanim_sil").on("click", ".bilesik_tanim_sil", function(e){
                            var id = $(this).attr("id");

                            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Bileşik Tanımı Silme Nedeni..'></textarea>", function(){

                                var delete_detail = $('#delete_detail').val();
                                var delete_datetime = $('#delete_datetime').val();
                                if (delete_detail != '') {
                                    $.ajax({
                                        type: 'POST',
                                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_bilesik_tanimi_sil',
                                        data: {
                                            id,
                                            delete_detail,
                                            delete_datetime
                                        },
                                        success: function (e) {
                                            $("#sonucyaz").html(e);
                                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=listeyi-getir",function(e){
                                                $('#bilesik-tanim').html(e);
                                            });
                                            $('.alertify').remove();
                                        }
                                    });
                                } else if (delete_detail == '') {
                                    alertify.warning("Silme Nedeni Giriniz.");
                                    setTimeout(() => {
                                        $(".bilesik_tanim_sil").trigger("click");
                                    }, "10")
                                }
                            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Bileşik Tanımı Silme İşlemini Onayla"});
                        });

                        $("body").off("click",".bilesik_tanim_aktif").on("click",".bilesik_tanim_aktif", function (e) {
                            var getir = $(this).attr('id');

                            $.ajax({
                                type:'POST',
                                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_bilesik_tanimi_aktiflestir',
                                data:{getir},
                                success:function(e){
                                    $('#sonucyaz').html(e);
                                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=listeyi-getir",function(e){
                                        $('#bilesik-tanim').html(e);
                                    });
                                }
                            });
                        });
                    </script>

                </div>
            </div>

        </div>

        <div class="col-6">
            <div id="bilesik_zorunlu_tetkik_tanim">

                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>Bileşen Tanımlarının Zorunlu Testlerini Görmek İçin Tetkik Seçiniz.</p>
                    </div>
                </div>

            </div>
        </div>

        </div>
    </div>
<?php }


else if($islem=="bilesik_zorunlu_tetkikler_list"){
    $bilesik_id = $_GET['bilesik_id'];?>

        <input type="text" hidden id="bilesik-id" value="<?php echo $bilesik_id;?>">
    <script>
        var bilesik_id = $('#bilesik-id').val();

        $('#zorunlu-tetkik-tab').DataTable( {
            scrollY: '69vh',
            scrollX: true,
            "info":true,
            "paging":false,
            "searching":true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Zorunlu Tetkik Tanımla',
                    className: 'btn btn-success btn-sm btn-kaydet',

                    action: function ( e, dt, button, config ) {


                        $('.tanimlamalar_w40_h25').window('setTitle', 'Zorunlu Tetkik Tanımla');
                        $('.tanimlamalar_w40_h25').window('open');
                        $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=modal-zorunlu-tetkik-tanimla&bilesik_id=' + bilesik_id + '');
                    }
                }
            ],
        });

    </script>
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Zorunlu Tetkik Listesi</b></div>
        <div class="card-body " style="height: 84vh;">

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="zorunlu-tetkik-tab">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Test Grubu</th>
                    <th>SUT Kodu</th>
                    <th>Tetkik Adı</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_compound_mandatory_test.id as zorunlubilesikid , lab_compound_mandatory_test.status as zorunlubilesik_durum ,lab_compound_mandatory_test.*,lab_analysis.*,lab_test_groups.* from lab_compound_mandatory_test, lab_analysis ,lab_test_groups  where lab_compound_mandatory_test.analysis_id=lab_analysis.id  and lab_compound_mandatory_test.compound_id=$bilesik_id and lab_compound_mandatory_test.test_groupsid=lab_test_groups.id");
                foreach ((array)$sql as $row){ ?>
                    <tr style="cursor: pointer;" id="<?php echo $row["zorunlubilesikid"]; ?>" >
                        <td><?php echo $row["zorunlubilesikid"]; ?> </td>
                        <td><?php echo $row["group_name"]; ?> </td>
                        <td><?php echo $row["sut_code"]; ?> </td>
                        <td><?php echo $row["analysis_name"]; ?> </td >
                        <td align="center"><?php if($row["zorunlubilesik_durum"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square bilesik_zorunlu_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["zorunlubilesikid"];?>"></i>

                            <?php if($row['zorunlubilesik_durum']=='0'){ ?>
                                <i class="fa-solid fa-recycle bilesik_zorunlu_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $row["zorunlubilesikid"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash bilesik_zorunlu_tanim_sil" title="İptal" id="<?php echo $row["zorunlubilesikid"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>



            <script>
                $("body").off("click", ".bilesik_zorunlu_tanim_duzenle").on("click", ".bilesik_zorunlu_tanim_duzenle", function(e){
                    var getir = $(this).attr('id');

                    $('.tanimlamalar_w40_h25').window('setTitle', 'Laboratuvar Tanımı Düzenle');
                    $('.tanimlamalar_w40_h25').window('open');
                    $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=modal-zorunlu-tetkik-tanim-duzenle&getir=' + getir + '');

                });

                $("body").off("click", ".bilesik_zorunlu_tanim_sil").on("click", ".bilesik_zorunlu_tanim_sil", function(e){
                    var id = $(this).attr("id");
                    alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Zorunlu Tetkik Tanımı Silme Nedeni..'></textarea>", function(){

                        var delete_detail = $('#delete_detail').val();
                        var delete_datetime = $('#delete_datetime').val();
                        if (delete_detail != '') {
                            $.ajax({
                                type: 'POST',
                                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_zorunlu_tetkik_tanimi_sil',
                                data: {
                                    id,
                                    delete_detail,
                                    delete_datetime
                                },
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=bilesik_zorunlu_tetkikler_list",{bilesik_id:<?php echo $bilesik_id;?>},function(getveri){
                                        $('#bilesik_zorunlu_tetkik_tanim').html(getveri);
                                    });
                                    $('.alertify').remove();
                                }
                            });
                        } else if (delete_detail == '') {
                            alertify.warning("Silme Nedeni Giriniz.");
                            setTimeout(() => {
                                $(".bilesik_zorunlu_tanim_sil:first").trigger("click");
                            }, "10")
                        }
                    }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Zorunlu Tetkik Tanımı Silme İşlemini Onayla"});
                });

                $("body").off("click",".bilesik_zorunlu_tanim_aktif").on("click",".bilesik_zorunlu_tanim_aktif", function (e) {
                    var getir = $(this).attr('id');

                    $.ajax({
                        type:'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_zorunlu_tetkik_tanimi_aktiflestir',
                        data:{getir},
                        success:function(e){
                            $('#sonucyaz').html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=bilesik_zorunlu_tetkikler_list",{bilesik_id:<?php echo $bilesik_id;?>},function(getveri){
                                $('#bilesik_zorunlu_tetkik_tanim').html(getveri);
                            });
                        }
                    });
                });

            </script>


        </div>
    </div>

<?php }

else if($islem=="modal-bilesik-tanimla"){?>

        <div class="mt-3 mx-2">

            <form id="bilesik_tanim_ekle_form" action="javascript:void(0);">

                    <div class="row">
                        <div class="col-5">
                            <label>Bileşik Adı</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="compound_name" id="example-text-input">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-5">
                            <label>Test Grup</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select" name="test_groupsid" id="testgroupid" >
                                <option selected disabled class="text-white bg-danger">Birim seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from lab_test_groups where lab_test_groups.status='1'");
                                foreach ($sql as $item){ ?>
                                    <option value="<?php echo $item["id"]; ?>" ><?php echo $item["group_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-5">
                            <label>Tetkik Adı</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select" id="analysisid" name="analysis_id">
                                <option selected disabled class="text-white bg-danger">Tetkik seçmek için önce test grubu seçiniz.</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        $("#testgroupid").change(function () {
                            var testgrupid = $(this).val();
                            $.ajax({
                                type: "POST",
                                url: "ajax/selectbox.php?islem=tetkikgetir",
                                data: {"testgrupid": testgrupid},
                                success: function (e) {
                                    $("#analysisid").html(e);
                                }
                            });
                        });
                    </script>


            </form>

            <div class="row mt-3" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-bilesik-tanimi-ekle">Kaydet</a>
                </div>
            </div>
        </div>






    <script>

        $("body").off("click", "#btn-bilesik-tanimi-ekle").on("click", "#btn-bilesik-tanimi-ekle", function(e){
            var bilesik_tanim_ekle_form = $("#bilesik_tanim_ekle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_bilesik_tanimi_ekle',
                data:bilesik_tanim_ekle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h25').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=listeyi-getir",function(e){
                        $('#bilesik-tanim').html(e);
                    });
                }
            });
        });
        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });


    </script>

<?php }

else if($islem=="modal-bilesik-tanim-duzenle"){
    $bilesik_id = $_GET['getir'];
    $lab_bilesik=singularactive("lab_compound_definition","id",$bilesik_id);?>

        <div class="mt-3 mx-2">

            <form id="tup_tanim_duzenle_form" action="javascript:void(0);">


                <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_bilesik["id"];?>">

                <div class="row">
                    <div class="col-5">
                        <label>Bileşik Adı</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control" type="text"  name="compound_name" id="example-text-input" value="<?php echo $lab_bilesik["compound_name"]; ?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <label>Test Grup</label>
                    </div>
                    <div class="col-7">
                        <select class="form-select" name="test_groupsid" id="testgroupid" >
                            <option selected disabled class="text-white bg-danger">Test grubu seçiniz.</option>
                            <?php $sql = verilericoklucek("select * from lab_test_groups where lab_test_groups.status='1'");
                            foreach ($sql as $item){ ?>
                                <option <?php if($lab_bilesik["test_groupsid"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item["group_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <label>Tetkik Adı</label>
                    </div>
                    <div class="col-7">
                        <select class="form-select" id="analysisid" name="analysis_id">
                            <option selected disabled class="text-white bg-danger">Tetkik seçmek için önce test grubu seçiniz.</option>
                            <?php $sql = verilericoklucek("select * from lab_analysis where lab_analysis.status='1'");
                            foreach ($sql as $item){ ?>
                                <option <?php if($lab_bilesik["analysis_id"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item["analysis_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <script>
                    $("#testgroupid").change(function () {
                        var testgrupid = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/selectbox.php?islem=tetkikgetir",
                            data: {"testgrupid": testgrupid},
                            success: function (e) {
                                $("#analysisid").html(e);
                            }
                        });
                    });
                </script>

            </form>

            <div class="row mt-3" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-bilesik-tanim-duzenle">Kaydet</a>
                </div>
            </div>

        </div>


    <script>

        $("body").off("click", "#btn-tup-tanim-duzenle").on("click", "#btn-bilesik-tanim-duzenle", function(e){
            var tup_tanim_duzenle_form = $("#tup_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_bilesik_tanimi_duzenle ',
                data:tup_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);

                    $('.tanimlamalar_w40_h25').window('close');

                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=listeyi-getir",function(e){
                        $('#bilesik-tanim').html(e);
                    });
                }
            });
           });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });

    </script>
<?php }

else if($islem=="modal-zorunlu-tetkik-tanimla"){
    $bilesik_id = $_GET['bilesik_id'];
    $test_groupsid = $_GET['test_groupsid'];
    $lab_bilesik_tablo=singularactive("lab_compound_definition","id",$bilesik_id); ?>

        <div class="mt-2 mx-2">

            <form id="zorunlu_tetkik_tanimla_form" action="javascript:void(0);">

                <input type="text" hidden class="form-control" name="compound_id" value="<?php echo $bilesik_id;?>">

                <div class="row">
                    <div class="col-5">
                        <label>Bileşik Adı</label>
                    </div>
                    <div class="col-7">
                        <input type="text" disabled class="form-control" name="compound_id" value="<?php echo $lab_bilesik_tablo["compound_name"];?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <label>Test Grup</label>
                    </div>
                    <div class="col-7">
                        <select class="form-select" name="test_groupsid" id="testgroupid1" >
                            <option selected disabled class="text-white bg-danger">Birim seçiniz.</option>
                            <?php $sql = verilericoklucek("select * from lab_test_groups where lab_test_groups.status='1'");
                            foreach ($sql as $item){ ?>
                                <option value="<?php echo $item["id"]; ?>" ><?php echo $item["group_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-5">
                        <label>Tetkik Adı</label>
                    </div>
                    <div class="col-7">
                        <select class="form-select" name="analysis_id" id="analysisid1">
                            <option selected disabled class="text-white bg-danger">Tetkik seçmek için önce test grubu seçiniz.</option>
                        </select>
                    </div>
                </div>
                <script>
                    $("#testgroupid1").change(function () {
                        var testgrupid = $(this).val();
                        $.ajax({
                            type: "POST",
                            url: "ajax/selectbox.php?islem=tetkikgetir",
                            data: {"testgrupid": testgrupid},
                            success: function (e) {
                                $("#analysisid1").html(e);
                            }
                        });
                    });
                </script>

            </form>


            <div class="row mt-3" align="right">
                <div class="col-7"></div>
                <div class="col-5">
                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-zorunlu-tetkik-tanimla">Kaydet</a>
                </div>
            </div>

        </div>



    <script>

        $("body").off("click", "#btn-zorunlu-tetkik-tanimla").on("click", "#btn-zorunlu-tetkik-tanimla", function(e){
        var zorunlu_tetkik_tanimla_form = $("#zorunlu_tetkik_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_zorunlu_tetkik_tanimla',
                data:zorunlu_tetkik_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h25').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=bilesik_zorunlu_tetkikler_list",{bilesik_id:<?php echo $bilesik_id;?>},function(getveri){
                        $('#bilesik_zorunlu_tetkik_tanim').html(getveri);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });

    </script>
<?php }

else if($islem=="modal-zorunlu-tetkik-tanim-duzenle"){
    $zorunlu_tetkikid = $_GET['getir'];
    $zorunlu_tetkik=singularactive("lab_compound_mandatory_test","id",$zorunlu_tetkikid);
    $lab_bilesik=singularactive("lab_compound_definition","id",$zorunlu_tetkik["compound_id"]);?>?>


    <div class="mt-2 mx-2">

        <form id="zorunlu_tetkik_tanim_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" value="<?php echo $zorunlu_tetkik["id"];?>">

            <div class="row">
                <div class="col-5">
                    <label>Bileşik Adı</label>
                </div>
                <div class="col-7">
                    <input type="text" disabled class="form-control" name="compound_id" value="<?php echo $lab_bilesik["compound_name"];?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Test Grup</label>
                </div>
                <div class="col-7">
                    <select class="form-select" name="test_groupsid" id="testgroupid2">
                        <option selected disabled class="text-white bg-danger">Test grubu seçiniz.</option>
                        <?php $sql = verilericoklucek("select * from lab_test_groups where lab_test_groups.status='1'");
                        foreach ($sql as $item){ ?>
                            <option <?php if($zorunlu_tetkik["test_groupsid"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item["group_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Tetkik Adı</label>
                </div>
                <div class="col-7">
                    <select class="form-select" name="analysis_id" id="analysisid2">
                        <option selected disabled class="text-white bg-danger">Tetkik seçmek için önce test grubu seçiniz.</option>
                        <?php $sql = verilericoklucek("select * from lab_analysis where lab_analysis.status='1'");
                        foreach ($sql as $item){ ?>
                            <option <?php if($zorunlu_tetkik["analysis_id"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item["analysis_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <script>
                $("#testgroupid2").change(function () {
                    var testgrupid = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "ajax/selectbox.php?islem=tetkikgetir",
                        data: {"testgrupid": testgrupid},
                        success: function (e) {
                            $("#analysisid2").html(e);
                        }
                    });
                });
            </script>

        </form>


        <div class="row mt-3" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-zorunlu-tetkik-tanim-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-zorunlu-tetkik-tanim-duzenle").on("click", "#btn-zorunlu-tetkik-tanim-duzenle", function(e){
            var zorunlu_tetkik_tanim_duzenle_form = $("#zorunlu_tetkik_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=sql_zorunlu_tetkik_tanim_duzenle',
                data:zorunlu_tetkik_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h25').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bilesiktanimlama.php?islem=bilesik_zorunlu_tetkikler_list",{bilesik_id:<?php echo $zorunlu_tetkik["compound_id"];?>},function(getveri){
                        $('#bilesik_zorunlu_tetkik_tanim').html(getveri);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });

    </script>
<?php } ?>


