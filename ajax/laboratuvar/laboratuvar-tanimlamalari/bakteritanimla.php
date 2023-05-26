<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_bakteri_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_bacteria_definition",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Bakteri Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteri Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_bakteri_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_bacteria_definition", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Bakteri Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteri Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_bakteri_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_bacteria_definition", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Bakteri Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteri Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_bakteri_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_bacteria_definition','id',$id,$date,$user);

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

if ($islem=="sql_bakteri_antibiyotik_eslestirme_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_antibiotic_for_bacteria",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Bakteriye Tanımlı Antibiyotik Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Tanımlı Antibiyotik Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_bakteri_antibiyotik_eslestirme_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_antibiotic_for_bacteria", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Bakteriye Tanımlı Antibiyotik Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Tanımlı Antibiyotik Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_bakteri_antibiyotik_eslestirme_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_antibiotic_for_bacteria", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Bakteriye Tanımlı Antibiyotik Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Tanımlı Antibiyotik Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_bakteri_antibiyotik_eslestirme_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_antibiotic_for_bacteria','id',$id,$date,$user);

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

            <div class="col-6">

                <div class="card">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bakteri Tanımları</b></div>
                    <div class="card-body " style="height: 84vh;">

                        <script>
                            $('#bakteri-tanim-datatb').DataTable( {
                                " scrollY": '69vh',
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
                                        text: 'Bakteri Tanımla',
                                        className: 'btn btn-success btn-sm btn-kaydet',

                                        action: function ( e, dt, button, config ) {

                                            $('.tanimlamalar_w40_h25').window('setTitle', 'Bakteri Tanımla');
                                            $('.tanimlamalar_w40_h25').window('open');
                                            $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=modal-bakteri-tanimla');

                                        }
                                    }
                                ],
                            } );
                        </script>

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri-tanim-datatb">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bakteri Kodu</th>
                                <th>Bakteri Adı</th>
                                <th>Açıklama</th>
                                <th>Rapor Sırası</th>
                                <th>Durumu</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sql = verilericoklucek("select * from lab_bacteria_definition");
                            foreach ((array)$sql as $row){ ?>
                                <tr style="cursor: pointer;" class="bakteri-tanim-sec" id="<?php echo $row["id"]; ?>">
                                    <td><?php echo $row["id"];?></td>
                                    <td><?php echo mb_strtoupper($row["bacteria_code"]); ?></td >
                                    <td><?php echo mb_strtoupper($row["bacteria_name"]); ?></td >
                                    <td><?php echo mb_strtoupper($row["bacteria_description"]); ?></td >
                                    <td><?php echo mb_strtoupper($row["order_of_report"]); ?></td >
                                    <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                                    <td align="center">
                                        <i class="fa fa-pen-to-square bakteri_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>"></i>
                                        <?php if($row['status']=='0'){ ?>
                                            <i class="fa-solid fa-recycle bakteri_tanim_aktif"
                                               title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>
                                        <?php }else{ ?>
                                            <i class="fa fa-trash bakteri_tanim_sil" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <script>
                    $(".bakteri-tanim-sec").click(function () {
                        $(this).css('background-color') != 'rgb(147,203,198)' ;
                        $('.bakteri-tanim-sec-kaldir').removeClass("text-white");
                        $('.bakteri-tanim-sec-kaldir').removeClass("bakteri-tanim-sec-kaldir");
                        $('.bakteri-tanim-sec').css({"background-color": "rgb(255, 255, 255)"});
                        $(this).css({"background-color": "rgb(147,203,198)"});
                        $(this).addClass("text-white bakteri-tanim-sec-kaldir");

                        var getir=$('.bakteri-tanim-sec-kaldir').attr('id');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=bakteri_tanimli_antibiyotik_liste",{ "getir":getir },function(getveri){
                            $('#bakteri_antibiyotik_tanim').html(getveri);
                        });
                    });

                    $("body").off("click", ".bakteri_tanim_duzenle").on("click", ".bakteri_tanim_duzenle", function(e){
                        var getir = $(this).attr('id');

                        $('.tanimlamalar_w40_h25').window('setTitle', 'Bakteri Tanımı Düzenle');
                        $('.tanimlamalar_w40_h25').window('open');
                        $('.tanimlamalar_w40_h25').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=modal-bakteri-tanim-duzenle&getir='+ getir +'');

                    });

                    $("body").off("click", ".bakteri_tanim_sil").on("click", ".bakteri_tanim_sil", function(e){
                        var id = $(this).attr("id");

                        alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Bakteri Tanımı Silme Nedeni..'></textarea>", function(){

                            var delete_detail = $('#delete_detail').val();
                            var delete_datetime = $('#delete_datetime').val();
                            if (delete_detail != '') {
                                $.ajax({
                                    type: 'POST',
                                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_tanimi_sil',
                                    data: {
                                        id,
                                        delete_detail,
                                        delete_datetime
                                    },
                                    success: function (e) {
                                        $("#sonucyaz").html(e);
                                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=listeyi-getir",function(e){
                                            $('#bakteri-tanim').html(e);
                                        });
                                        $('.alertify').remove();
                                    }
                                });
                            } else if (delete_detail == '') {
                                alertify.warning("Silme Nedeni Giriniz.");
                                setTimeout(() => {
                                    $(".bakteri_tanim_sil").trigger("click");
                                }, "10")
                            }
                        }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Bakteri Tanımı Silme İşlemini Onayla"});
                    });

                    $("body").off("click",".bakteri_tanim_aktif").on("click",".bakteri_tanim_aktif", function (e) {
                        var getir = $(this).attr('id');

                        $.ajax({
                            type:'POST',
                            url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_tanimi_aktiflestir',
                            data:{getir},
                            success:function(e){
                                $('#sonucyaz').html(e);
                                $('#bakteri-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=listeyi-getir");
                            }
                        });
                    });
                </script>


            </div>

            <div class="col-6">
                <div id="bakteri_antibiyotik_tanim">

                    <div class="warning-definitions mt-5">
                        <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                            <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                            <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                            <p>Bakteriye Antibiyotik Tanımlama İşlemleri İçin Tetkik Seçiniz.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php }

else if($islem=="modal-bakteri-tanimla"){?>

    <div class="mx-3">

        <div class="col-12 row mt-3">

            <form id="bakteri_tanimla_form">

                <div class="row">
                    <div class="col-4">
                        <label>Bakteri Kodu</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="bacteria_code" name="bacteria_code"/>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Bakteri Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="bacteria_name" name="bacteria_name"/>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Bakteri Açıklama</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="bacteria_description" name="bacteria_description"/>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Rapor Sırası</label>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" id="order_of_report" name="order_of_report"/>
                    </div>
                </div>

            </form>

            <div class="row mt-2" align="right">
                <div class="col-7"></div>
                <div class="col-5" >

                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_bakteri_tanimi_tanimla">Kaydet</a>
                </div>
            </div>

        </div>


    </div>

    <script>
        $("body").off("click", "#btn_bakteri_tanimi_tanimla").on("click", "#btn_bakteri_tanimi_tanimla", function(e){

            var bakteri_tanimla = $("#bakteri_tanimla_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_tanimi_ekle',
                data:bakteri_tanimla,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=listeyi-getir",function(e){
                        $('#bakteri-tanim').html(e);
                    });
                    $('.tanimlamalar_w40_h25').window('close');
                }
            });
        });


        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });
    </script>

<?php }

else if($islem=="modal-bakteri-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_bakteri_tanim=singularactive("lab_bacteria_definition","id",$gelen_veri);?>


    <div class="mx-3">

        <div class="col-12 row mt-3">

            <form id="bakteri_tanimi_duzenle_form">

                <input type="text" hidden class="form-control" name="id" value="<?php echo $lab_bakteri_tanim["id"];?>">

                <div class="row">
                    <div class="col-4">
                        <label>Bakteri Kodu</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="bacteria_code" value="<?php echo mb_strtoupper($lab_bakteri_tanim["bacteria_code"]);?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Bakteri Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="bacteria_name" value="<?php echo mb_strtoupper($lab_bakteri_tanim["bacteria_name"]);?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Bakteri Açıklama</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="bacteria_description" value="<?php echo mb_strtoupper($lab_bakteri_tanim["bacteria_description"]);?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-4">
                        <label>Rapor Sırası</label>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control" name="order_of_report" value="<?php echo $lab_bakteri_tanim["order_of_report"];?>">
                    </div>
                </div>

            </form>

            <div class="row mt-2" align="right">
                <div class="col-7"></div>
                <div class="col-5" >

                    <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_bakteri_tanimi_duzenle">Kaydet</a>
                </div>
            </div>

        </div>


    </div>

    <script>
        $("body").off("click", "#btn_bakteri_tanimi_duzenle").on("click", "#btn_bakteri_tanimi_duzenle", function(e){

            var bakteri_tanimi_duzenle = $("#bakteri_tanimi_duzenle_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_tanimi_duzenle',
                data:bakteri_tanimi_duzenle,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=listeyi-getir",function(e){
                        $('#bakteri-tanim').html(e);
                    });
                    $('.tanimlamalar_w40_h25').window('close');
                }
            });
        });


        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h25').window('close');
        });
    </script>

<?php }

else if($islem=="bakteri_tanimli_antibiyotik_liste"){
    $gelen_veri = $_GET['getir'];?>

        <input type="text" hidden id="gelen-veri" value="<?php echo $gelen_veri;?>">

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bakterinin Duyarlı Olduğu Antibiyotikler Listesi</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>

                var getir = $("#gelen-veri").val();

                $('#bakteri-tanimli-antibiyotik-datatb').DataTable( {
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Antibiyotik Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet',

                            action: function ( e, dt, button, config ) {

                                $('.tanimlamalar_w40_h45').window('setTitle', 'Antibiyotik Tanımla');
                                $('.tanimlamalar_w40_h45').window('open');
                                $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=modal-bakteri-antibiyotik-tanimla&getir=' + getir + '');
                            }
                        }
                    ],
                });
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri-tanimli-antibiyotik-datatb">
                <thead>
                <tr>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Antibiyotik Profili</th>
                    <th>Açıklama</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_antibiotic_for_bacteria.id as bacteri_antibiyotikid, lab_antibiotic_for_bacteria.status as bakteri_antibiyotik_status, lab_antibiotic_for_bacteria.*,lab_antibiotic_definition.* from lab_antibiotic_for_bacteria,lab_antibiotic_definition where lab_antibiotic_for_bacteria.antibiotic_dentificationid=lab_antibiotic_definition.id and lab_antibiotic_for_bacteria.bacteria_dentificationid=$gelen_veri");
                foreach ((array)$sql as $row){
                    $antibiyotik_profili=singular("lab_antibiotic_profile","id",$row["antibiotic_profile"]);?>
                    <tr style="cursor: pointer;" id="<?php echo $row["bacteri_antibiyotikid"]; ?>" >
                        <td><?php echo mb_strtoupper($row["antibiotic_code"]); ?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_name"]); ?></td>
                        <td><?php echo mb_strtoupper($antibiyotik_profili["antibiotic_profile_name"]); ?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_description"]); ?></td>
                        <td align="center"><?php if($row["bakteri_antibiyotik_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square bakteriye_tanimli_antibiyotik_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["bacteri_antibiyotikid"]; ?>"></i>
                            <?php if($row['bakteri_antibiyotik_status']=='0'){ ?>
                                <i class="fa-solid fa-recycle bakteriye_tanimli_antibiyotik_tanim_aktif" title="Aktif Et" id="<?php echo $row["bacteri_antibiyotikid"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash bakteriye_tanimli_antibiyotik_tanim_sil" title="İptal" id="<?php echo $row["bacteri_antibiyotikid"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $("body").off("click", ".bakteriye_tanimli_antibiyotik_tanim_duzenle").on("click", ".bakteriye_tanimli_antibiyotik_tanim_duzenle", function(e){
            var getir = $(this).attr('id');


            $('.tanimlamalar_w40_h45').window('setTitle', 'Antibiyotik Tanımla');
            $('.tanimlamalar_w40_h45').window('open');
            $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=modal-bakteri-antibiyotik-tanim-duzenle&getir=' + getir + '');
        });

        $("body").off("click", ".bakteriye_tanimli_antibiyotik_tanim_sil").on("click", ".bakteriye_tanimli_antibiyotik_tanim_sil", function(e){
            var id = $(this).attr("id");
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Antibiyotik Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_antibiyotik_eslestirme_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=bakteri_tanimli_antibiyotik_liste",{getir:<?php echo $gelen_veri;?>},function(getveri){
                                $('#bakteri_antibiyotik_tanim').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".bakteriye_tanimli_antibiyotik_tanim_sil:first").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Antibiyotik Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".bakteriye_tanimli_antibiyotik_tanim_aktif").on("click",".bakteriye_tanimli_antibiyotik_tanim_aktif", function (e) {
            var getir = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_antibiyotik_eslestirme_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=bakteri_tanimli_antibiyotik_liste",{getir:<?php echo $gelen_veri;?>},function(getveri){
                        $('#bakteri_antibiyotik_tanim').html(getveri);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-bakteri-antibiyotik-tanimla"){
    $gelen_veri = $_GET['getir'];?>


    <div class="mt-2">

        <script>
            $('#antibiyotik_tanimla_datatable').DataTable( {
                scrollY: '25vh',
                scrollX: true,
                "info":true,
                "paging":false,
                "searching":true,
                "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            });
        </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="antibiyotik_tanimla_datatable">
            <thead>
            <tr>
                <th>Kodu</th>
                <th>Antibiyotik Adı</th>
                <th>Antibiyotik Profili</th>
                <th>Açıklama</th>
                <th>Rapor Sırası</th>
            </tr>
            </thead>
            <tbody>
            <?php $sql = verilericoklucek("select * from lab_antibiotic_definition where lab_antibiotic_definition.id NOT IN (select antibiotic_dentificationid from lab_antibiotic_for_bacteria where bacteria_dentificationid=$gelen_veri) and status='1'");
            foreach ((array)$sql as $rowa ) {
                $antibiyotik_progili=singular("lab_antibiotic_profile","id",$rowa["antibiotic_profile"]);?>
                <tr style="cursor: pointer;" class="bakteri-antibiyotik-sec" id="<?php echo $rowa["id"];?>">
                    <td><?php echo mb_strtoupper($rowa["antibiotic_code"]); ?></td>
                    <td><?php echo mb_strtoupper($rowa["antibiotic_name"]); ?></td>
                    <td><?php echo mb_strtoupper($antibiyotik_progili["antibiotic_profile_name"]); ?></td>
                    <td><?php echo mb_strtoupper($rowa["antibiotic_description"]); ?></td>
                    <td><?php echo mb_strtoupper($rowa["order_of_report"]); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <script>
            $(".bakteri-antibiyotik-sec").click(function () {
                $(this).css('background-color') != 'rgb(147,203,198)' ;
                $('.bakteri-antibiyotik-sec-kaldir').removeClass("text-white");
                $('.bakteri-antibiyotik-sec-kaldir').removeClass("bakteri-antibiyotik-sec-kaldir");
                $('.bakteri-antibiyotik-sec').css({"background-color": "rgb(255, 255, 255)"});
                $(this).css({"background-color": "rgb(147,203,198)"});
                $(this).addClass("text-white bakteri-antibiyotik-sec-kaldir");

                var user_id=$('.bakteri-antibiyotik-sec-kaldir').attr('id');
                $('#antibiotic_dentificationid').val(user_id);
            });
        </script>

        <form id="bakteriye_antibiyotik_tanimla_form" action="javascript:void(0);">
            <input class="form-control" hidden type="text" name="bacteria_dentificationid" value="<?php echo $gelen_veri;?>">
            <input class="form-control" hidden type="text" name="antibiotic_dentificationid" id="antibiotic_dentificationid">
        </form>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-bakteriye-antibiyotik-tanimi-ekle">Kaydet</a>
            </div>
        </div>

</div>

    <script>
        $("body").off("click", "#btn-bakteriye-antibiyotik-tanimi-ekle").on("click", "#btn-bakteriye-antibiyotik-tanimi-ekle", function(e){
            var bakteriye_antibiyotik_tanimla_form = $("#bakteriye_antibiyotik_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_antibiyotik_eslestirme_ekle',
                data:bakteriye_antibiyotik_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    var getir = "<?php echo $gelen_veri;?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=bakteri_tanimli_antibiyotik_liste",{getir:getir},function(getveri){
                        $('#bakteri_antibiyotik_tanim').html(getveri);
                    });
                    $('.tanimlamalar_w40_h45').window('close');
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });
    </script>
<?php }

else if($islem=="modal-bakteri-antibiyotik-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $bekteriye_tanimli_antibiyotik=singularactive("lab_antibiotic_for_bacteria","id",$gelen_veri);
    $bakteri_id= $bekteriye_tanimli_antibiyotik["bacteria_dentificationid"]; ?>



    <div class="mt-2">
            <script>
                $(document).ready(function(){
                    setTimeout(function(){
                        $('#antibiyotik-listesi-datatb1').DataTable( {
                            scrollY: '25vh',
                            scrollX: true,
                            "info":true,
                            "paging":false,
                            "searching":true,
                            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                        });
                    }, 100);
                });
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="antibiyotik-listesi-datatb1">
                <thead>
                <tr>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Antibiyotik Profili</th>
                    <th>Açıklama</th>
                    <th>Rapor Sırası</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_antibiotic_definition where lab_antibiotic_definition.id NOT IN (select antibiotic_dentificationid from lab_antibiotic_for_bacteria where bacteria_dentificationid=$bakteri_id) and status='1'");
                foreach ((array)$sql as $rowa ) {
                    $antibiyotik_progili=singular("lab_antibiotic_profile","id",$rowa["antibiotic_profile"]);?>
                    <tr style="cursor: pointer;" class="bakteri-antibiyotik-sec" id="<?php echo $rowa["id"];?>">
                        <td><?php echo mb_strtoupper($rowa["antibiotic_code"]); ?></td>
                        <td><?php echo mb_strtoupper($rowa["antibiotic_name"]); ?></td>
                        <td><?php echo mb_strtoupper($antibiyotik_progili["antibiotic_profile_name"]); ?></td>
                        <td><?php echo mb_strtoupper($rowa["antibiotic_description"]); ?></td>
                        <td><?php echo mb_strtoupper($rowa["order_of_report"]); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <script>
                $(".bakteri-antibiyotik-sec").click(function () {
                    $(this).css('background-color') != 'rgb(147,203,198)' ;
                    $('.bakteri-antibiyotik-sec-kaldir').removeClass("text-white");
                    $('.bakteri-antibiyotik-sec-kaldir').removeClass("bakteri-antibiyotik-sec-kaldir");
                    $('.bakteri-antibiyotik-sec').css({"background-color": "rgb(255, 255, 255)"});
                    $(this).css({"background-color": "rgb(147,203,198)"});
                    $(this).addClass("text-white bakteri-antibiyotik-sec-kaldir");

                    var user_id=$('.bakteri-antibiyotik-sec-kaldir').attr('id');
                    $('#antibiotic_dentificationid1').val(user_id);
                });
            </script>

            <form id="bakteriye_tanimli_antibiyotik_duzenle_form" action="javascript:void(0);">
                <input class="form-control" hidden type="text" name="id" value="<?php echo $bekteriye_tanimli_antibiyotik["id"];?>">
                <input class="form-control" hidden type="text" name="antibiotic_dentificationid" id="antibiotic_dentificationid1">
            </form>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_bakteri_tanimli_antibiyotik_duzenle">Kaydet</a>
            </div>
        </div>
        </div>



    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn_bakteri_tanimli_antibiyotik_duzenle").on("click", "#btn_bakteri_tanimli_antibiyotik_duzenle", function(e){
                var bakteriye_tanimli_antibiyotik_duzenle_form = $("#bakteriye_tanimli_antibiyotik_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=sql_bakteri_antibiyotik_eslestirme_duzenle',
                    data:bakteriye_tanimli_antibiyotik_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);

                        var getir = "<?php echo $bekteriye_tanimli_antibiyotik["bacteria_dentificationid"];?>";

                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/bakteritanimla.php?islem=bakteri_tanimli_antibiyotik_liste",{getir:getir},function(getveri){
                            $('#bakteri_antibiyotik_tanim').html(getveri);
                        });
                        
                        $('.tanimlamalar_w40_h45').window('close');
                    }
                });
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });
    </script>
<?php } ?>