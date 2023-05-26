<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_bakteriye_tanimli_antibiyotik_etki_grubu_tanimla"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_restricted_notification_definitions",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Bakteriye Antibiyotik Etki Grubu Tanımlama İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Antibiyotik Etki Grubu Tanımlama İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_bakteriye_tanimli_antibiyotik_etki_grubu_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_restricted_notification_definitions", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Bakteriye Tanımlı Antibiyotik Etki Grubu Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Tanımlı Antibiyotik Etki Grubu Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_bakteriye_tanimli_antibiyotik_etki_grubu_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_restricted_notification_definitions", "id", $id, $detay, $kullanici, $tarih);
    if ($sql == 1){?>
        <script>
            alertify.success('Bakteriye Tanımlı Antibiyotik Etki Grubu Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Bakteriye Tanımlı Antibiyotik Etki Grubu Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_bakteriye_tanimli_antibiyotik_etki_grubu_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_restricted_notification_definitions','id',$id,$date,$user);

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

            <div class="col-5">

                <div class="card">
                    <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bakteri Tanımları</b></div>
                    <div class="card-body " style="height: 84vh;">

                        <script>
                            $('#bakteri_tanimlari_datatable').DataTable( {
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
                                        text: 'Antibiyotik Etki Grubu Tanımla',
                                        className: 'btn btn-success btn-sm btn-atnibiyotik-etki-grup',
                                        attr:  {
                                            'disabled':true,

                                        },
                                        action: function ( e, dt, button, config ) {
                                            var getir=$('.bakteri-tanim-sec-kaldir').attr('id');
                                            $('.tanimlamalar_w40_h50').window('setTitle', 'Antibiyotik Etki Grubu Tanımla');
                                            $('.tanimlamalar_w40_h50').window('open');
                                            $('.tanimlamalar_w40_h50').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=modal-antibiyotik-ekti-grubu-tanimla&getir='+ getir +'');
                                        }
                                    }
                                ],
                            } );
                        </script>

                        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri_tanimlari_datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bakteri Kodu</th>
                                <th>Bakteri Adı</th>
                                <th>Açıklama</th>
                                <th>Durumu</th>
                                <th>İşlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sql = verilericoklucek("select * from lab_bacteria_definition");
                            foreach ((array)$sql as $row){ ?>
                                <tr style="cursor: pointer;" class="bakteri-tanim-sec" id="<?php echo $row["id"]; ?>">
                                    <td><?php echo mb_strtoupper($row["id"]);?></td >
                                    <td><?php echo mb_strtoupper($row["bacteria_code"]);?></td >
                                    <td><?php echo mb_strtoupper($row["bacteria_name"]);?></td >
                                    <td><?php echo mb_strtoupper($row["bacteria_description"]);?></td >
                                    <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                                    <td align="center">
                                        <i class="fa fa-circle-info bakteri_tanimli_antibiyotik_detay" title="Detay" alt="icon" id="<?php echo $row["id"]; ?>"></i>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    $(".bakteri-tanim-sec").click(function () {

                        $('.btn-atnibiyotik-etki-grup').attr('disabled', false);

                        $(this).css('background-color') != 'rgb(147,203,198)' ;
                        $('.bakteri-tanim-sec-kaldir').removeClass("text-white");
                        $('.bakteri-tanim-sec-kaldir').removeClass("bakteri-tanim-sec-kaldir");
                        $('.bakteri-tanim-sec').css({"background-color": "rgb(255, 255, 255)"});
                        $(this).css({"background-color": "rgb(147,203,198)"});
                        $(this).addClass("text-white bakteri-tanim-sec-kaldir");

                        var getir=$('.bakteri-tanim-sec-kaldir').attr('id');

                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{ "getir":getir },function(getveri){
                            $('#bakteriye_tanimli_antibiyotik_liste').html(getveri);
                        });
                    });

                    $("body").off("click", ".bakteri_tanimli_antibiyotik_detay").on("click", ".bakteri_tanimli_antibiyotik_detay", function(e){
                        var getir = $(this).attr('id');
                        $('.tanimlamalar_w40_h45').window('setTitle', 'Bakterinin Duyarlı Olduğu Antibiyotik Listesi');
                        $('.tanimlamalar_w40_h45').window('open');
                        $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_detay_liste&getir='+ getir +'');
                    });
                </script>

            </div>

            <div class="col-7">
                <div id="bakteriye_tanimli_antibiyotik_liste">

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

else if($islem=="bakteri_tanimli_antibiyotik_detay_liste"){
    $gelen_veri = $_GET['getir'];
    $lab_bakteri_tanim=singularactive("lab_bacteria_definition","id",$gelen_veri)?>

    <div class="mt-1 mx-1">
        <h5>Bakteri Adı: <?php echo $lab_bakteri_tanim["bacteria_name"];?></h5>

        <script>
            $(document).ready(function(){

                $('#bakteri-tanimli-antibiyotik-datatable').DataTable( {
                    "scrollY": '25vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });

            });
        </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri-tanimli-antibiyotik-datatable">
            <thead>
            <tr>
                <th>Kodu</th>
                <th>Antibiyotik Adı</th>
                <th>Antibiyotik Profili</th>
                <th>Açıklama</th>
                <th>Durumu</th>
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
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }

else if($islem=="modal-antibiyotik-ekti-grubu-tanimla"){
    $gelen_veri = $_GET['getir'];
    $lab_bakteri_tanim=singularactive("lab_bacteria_definition","id",$gelen_veri);?>

    <div class="mt-1 mx-1">

        <h5>Bakteri Adı: <?php echo $lab_bakteri_tanim["bacteria_name"];?></h5>

        <script>
            $(document).ready(function(){
                $('#bakteriye_tanimli_antibiyotik_tanimla_datatable').DataTable( {
                    "scrollY": '20vh',
                    "scrollX": true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });
            });
        </script>

        <table class="table table-sm table-bordered w-100 display nowrap " style="font-size: 13px;" id="bakteriye_tanimli_antibiyotik_tanimla_datatable">
            <thead>
            <tr>
                <th>id</th>
                <th>Kodu</th>
                <th>Antibiyotik Adı</th>
                <th>Antibiyotik Profili</th>
                <th>Açıklama</th>
                <th>Durumu</th>
            </tr>
            </thead>
            <tbody>
            <?php $sql = verilericoklucek("select lab_antibiotic_for_bacteria.id as bacteri_antibiyotikid, lab_antibiotic_for_bacteria.status as bakteri_antibiyotik_status, lab_antibiotic_for_bacteria.*, lab_antibiotic_definition.* from lab_antibiotic_for_bacteria, lab_antibiotic_definition where lab_antibiotic_for_bacteria.antibiotic_dentificationid = lab_antibiotic_definition.id and lab_antibiotic_for_bacteria.bacteria_dentificationid = $gelen_veri and lab_antibiotic_definition.id!= all (select lab_antibiotic_definition.id from lab_restricted_notification_definitions, lab_antibiotic_definition where lab_restricted_notification_definitions.antibiotic_definitionid = lab_antibiotic_definition.id and lab_restricted_notification_definitions.bacteria_definitionid = $gelen_veri)");
            foreach ((array)$sql as $row){
                $antibiyotik_profili=singular("lab_antibiotic_profile","id",$row["antibiotic_profile"]);?>
                <tr style="cursor: pointer;" id="<?php echo $row["id"]; ?>" class="antibiyotik-sec">
                    <td><?php echo mb_strtoupper($row["id"]); ?></td>
                    <td><?php echo mb_strtoupper($row["antibiotic_code"]); ?></td>
                    <td><?php echo mb_strtoupper($row["antibiotic_name"]); ?></td>
                    <td><?php echo mb_strtoupper($antibiyotik_profili["antibiotic_profile_name"]); ?></td>
                    <td><?php echo mb_strtoupper($row["antibiotic_description"]); ?></td>
                    <td align="center"><?php if($row["bakteri_antibiyotik_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <script>
            $(".antibiyotik-sec").click(function () {
                $(this).css('background-color') != 'rgb(147,203,198)' ;
                $('.antibiyotik-sec-kaldir').removeClass("text-white");
                $('.antibiyotik-sec-kaldir').removeClass("antibiyotik-sec-kaldir");
                $('.antibiyotik-sec').css({"background-color": "rgb(255, 255, 255)"});
                $(this).css({"background-color": "rgb(147,203,198)"});
                $(this).addClass("text-white antibiyotik-sec-kaldir");

                var getir=$('.antibiyotik-sec-kaldir').attr('id');
                $('#antibiotic_definitionid').val(getir);
            });
        </script>

        <form id="antibiyotik_ekti_grubu_ekle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="bacteria_definitionid" value="<?php echo $gelen_veri ;?>">
            <input type="text" hidden class="form-control" name="antibiotic_definitionid" id="antibiotic_definitionid">

            <div class="row mt-3">

                <div class="col-4">
                    <label>Antibiyotik Etki Grubu</label>
                </div>

                <div class="col-8">
                    <select class="form-select" name="antibiotic_action_group" id="antibiyotik_etki_grubu" title="A grubu antibiyotikleri en etkili antibiyotik grubu olmak üzere, D grubna kadar etki bakımından azalarak derecelendirilir.">
                        <option selected disabled class="text-white bg-danger">Antibiyotik Grubu Seçiniz.</option>
                        <?php $sql= verilericoklucek("select * from lab_definitions where definition_type='ANTIBIYOTIK_ETKI_GRUP' and lab_definitions.status='1'");
                        foreach ($sql as $item){ ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                        <?php }?>

                    </select>
                </div>

            </div>

        </form>

        <div class="mt-3 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-antibiyotik-ekti-grubu-ekle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-antibiyotik-ekti-grubu-ekle").on("click", "#btn-antibiyotik-ekti-grubu-ekle", function(e){
            var antibiyotik_ekti_grubu_ekle_form = $("#antibiyotik_ekti_grubu_ekle_form").serialize();
            var antibiyotik_etki_grubu = $("#antibiyotik_etki_grubu").val();
            var antibiotic_definitionid = $("#antibiotic_definitionid").val();
            if (antibiotic_definitionid != '' && antibiyotik_etki_grubu != null) {
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_tanimla',
                    data:antibiyotik_ekti_grubu_ekle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);

                        $('.tanimlamalar_w40_h50').window('close');

                        var getir = "<?php echo $gelen_veri;?>";
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                            $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                        });


                    }
                });
            } else if (antibiotic_definitionid == '') {
                alertify.warning("Tanımlı antibiyotik seçiniz.");
            }else if (antibiyotik_etki_grubu == null) {
                alertify.warning("Antibiyotik etki grubu seçiniz.");
            }
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h50').window('close');
        });
    </script>

<?php }

else if($islem=="bakteri_tanimli_antibiyotik_listesi"){
    $gelen_veri = $_GET['getir'];?>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bakteriye Etkili Antibiyotik Grubu Listesi</b></div>
        <div class="card-body " style="height: 84vh;">

            <select class="form-select etki_grubu" id="antibiotic_action_group" title="A grubu antibiyotikleri en etkili antibiyotik grubu olmak üzere, D grubna kadar etki bakımından azalarak derecelendirilir.">
                <option selected disabled class="text-white bg-danger">Antibiyotik Grubu Seçiniz.</option>
                <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='ANTIBIYOTIK_ETKI_GRUP' and lab_definitions.status='1'");
                foreach ($sql as $item){ ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                <?php }?>
            </select>

            <script>
                $(".etki_grubu").change(function () {
                    var etki_grubu =$(this).val();
                    var getir ="<?php echo $gelen_veri;?>";

                    $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteriye_tanimli_antibiyotik_grubu_liste_filtre",{"getir":getir,"etki_grubu":etki_grubu},function(getVeri){
                        $('#bakteriye_tanimli_antibiyotik_liste').html(getVeri);
                    });
                });
            </script>

            <div class="mt-3"></div>

            <script>
                $(document).ready(function(){
                    $('#bakteriye_tanimli_antibiyotik_grubu_datatable').DataTable( {
                        "scrollY": '67h',
                        "scrollX": true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                });
            </script>

            <table table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteriye_tanimli_antibiyotik_grubu_datatable">
                <thead>
                <tr>
                    <th>Antibiyotik Etki Grubu</th>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Antibiyotik Profili</th>
                    <th>Açıklama</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_restricted_notification_definitions.id as kisitli_bildirimid, lab_restricted_notification_definitions.status as kisitli_bildirim_status,lab_restricted_notification_definitions.*,lab_antibiotic_definition.* from lab_restricted_notification_definitions,lab_antibiotic_definition where lab_restricted_notification_definitions.antibiotic_definitionid=lab_antibiotic_definition.id and lab_restricted_notification_definitions.bacteria_definitionid=$gelen_veri");
                foreach ((array)$sql as $row){
                    $antibiyotik_profili=singular("lab_antibiotic_profile","id",$row["antibiotic_profile"]); ?>
                    <tr style="cursor: pointer;" id="<?php echo $row["kisitli_bildirimid"];?>" >
                        <td><?php echo mb_strtoupper(labtanimgetirid($row["antibiotic_action_group"]));?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_code"]);?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_name"]);?></td>
                        <td><?php echo mb_strtoupper($antibiyotik_profili["antibiotic_profile_name"]);?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_description"]);?></td>
                        <td align="center"><?php if($row["kisitli_bildirim_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square bakteriye_tanimli_antibiyotik_grubu_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["kisitli_bildirimid"]; ?>"></i>
                            <?php if($row['kisitli_bildirim_status']=='0'){ ?>
                                <i class="fa-solid fa-recycle bakteriye_tanimli_antibiyotik_grubu_aktif" title="Aktif Et" id="<?php echo $row["kisitli_bildirimid"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash bakteriye_tanimli_antibiyotik_grubu_sil" title="İptal" id="<?php echo $row["kisitli_bildirimid"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>



            <script>
                $("body").off("click", ".bakteriye_tanimli_antibiyotik_grubu_duzenle").on("click", ".bakteriye_tanimli_antibiyotik_grubu_duzenle", function(e){
                    var getir = $(this).attr('id');


                    $('.tanimlamalar_w40_h50').window('setTitle', 'Bakteriye Etkili Antibiyotik Grubu Düzenle');
                    $('.tanimlamalar_w40_h50').window('open');
                    $('.tanimlamalar_w40_h50').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=modal-antibiyotik-ekti-grubu-tanim-duzenle&getir=' + getir + '');
                });

                $("body").off("click", ".bakteriye_tanimli_antibiyotik_grubu_sil").on("click", ".bakteriye_tanimli_antibiyotik_grubu_sil", function(e){
                    var id = $(this).attr("id");

                    alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Bakteriye Tanımlı Antibiyotik Grubu Silme Nedeni..'></textarea>", function(){

                        var delete_detail = $('#delete_detail').val();
                        var delete_datetime = $('#delete_datetime').val();
                        if (delete_detail != '') {
                            $.ajax({
                                type: 'POST',
                                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_sil',
                                data: {
                                    id,
                                    delete_detail,
                                    delete_datetime
                                },
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var getir = "<?php echo $gelen_veri;?>";
                                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                                        $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                                    });
                                    $('.alertify').remove();
                                }
                            });
                        } else if (delete_detail == '') {
                            alertify.warning("Silme Nedeni Giriniz.");
                            setTimeout(() => {
                                $(".bakteriye_tanimli_antibiyotik_grubu_sil").trigger("click");
                            }, "10")
                        }
                    }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Bakteriye Tanımlı Antibiyotik Grubu Silme İşlemini Onayla"});
                });

                $("body").off("click",".bakteriye_tanimli_antibiyotik_grubu_aktif").on("click",".bakteriye_tanimli_antibiyotik_grubu_aktif", function (e) {
                    var getir = $(this).attr('id');

                    $.ajax({
                        type:'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_aktiflestir',
                        data:{getir},
                        success:function(e){
                            $('#sonucyaz').html(e);
                            var getir = "<?php echo $gelen_veri;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                                $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                            });
                        }
                    });
                });
            </script>

        </div>
    </div>
<?php }

else if($islem=="modal-antibiyotik-ekti-grubu-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $kisitli_bildirim_tanim=singularactive("lab_restricted_notification_definitions","id",$gelen_veri);
    $bakteri_tanimid=$kisitli_bildirim_tanim["bacteria_definitionid"];
    $bakteri_tanim=singularactive("lab_bacteria_definition","id",$kisitli_bildirim_tanim["bacteria_definitionid"]);?>


    <div class="mt-1 mx-1">

        <h5>Bakteri Adı: <?php echo $bakteri_tanim["bacteria_name"];?></h5>

        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $('#bakteri_tanimli_antibiyotik_tanimi_duzenle_datatable').DataTable( {
                        "scrollY": '20vh',
                        "scrollX": true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                },10);
            });
        </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri_tanimli_antibiyotik_tanimi_duzenle_datatable">
            <thead>
            <tr>
                <th>Kodu</th>
                <th>Antibiyotik Adı</th>
                <th>Antibiyotik Profili</th>
                <th>Açıklama</th>
                <th>Durumu</th>
            </tr>
            </thead>
            <tbody>
            <?php $sql = verilericoklucek("select lab_antibiotic_for_bacteria.id as bacteri_antibiyotikid, lab_antibiotic_for_bacteria.status as bakteri_antibiyotik_status, lab_antibiotic_for_bacteria.*, lab_antibiotic_definition.* from lab_antibiotic_for_bacteria, lab_antibiotic_definition where lab_antibiotic_for_bacteria.antibiotic_dentificationid = lab_antibiotic_definition.id and lab_antibiotic_for_bacteria.bacteria_dentificationid = $bakteri_tanimid and lab_antibiotic_definition.id!= all (select lab_antibiotic_definition.id from lab_restricted_notification_definitions, lab_antibiotic_definition where lab_restricted_notification_definitions.antibiotic_definitionid = lab_antibiotic_definition.id and lab_restricted_notification_definitions.bacteria_definitionid = $bakteri_tanimid)");
            foreach ((array)$sql as $row){
                $antibiyotik_profili=singular("lab_antibiotic_profile","id",$row["antibiotic_profile"]);?>
                <tr style="cursor: pointer;" id="<?php echo $row["antibiotic_dentificationid"];?>" class="antibiyotik-gr-sec">
                    <td><?php echo mb_strtoupper($row["antibiotic_code"]); ?></td>
                    <td><?php echo mb_strtoupper($row["antibiotic_name"]); ?></td>
                    <td><?php echo mb_strtoupper($antibiyotik_profili["antibiotic_profile_name"]); ?></td>
                    <td><?php echo mb_strtoupper($row["antibiotic_description"]); ?></td>
                    <td align="center"><?php if($row["bakteri_antibiyotik_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <script>
            $(".antibiyotik-gr-sec").click(function () {
                $(this).css('background-color') != 'rgb(147,203,198)' ;
                $('.antibiyotik-gr-sec-kaldir').removeClass("text-white");
                $('.antibiyotik-gr-sec-kaldir').removeClass("antibiyotik-gr-sec-kaldir");
                $('.antibiyotik-gr-sec').css({"background-color": "rgb(255, 255, 255)"});
                $(this).css({"background-color": "rgb(147,203,198)"});
                $(this).addClass("text-white antibiyotik-gr-sec-kaldir");

                var getir=$('.antibiyotik-gr-sec-kaldir').attr('id');
                $('#antibiotic_definitionid').val(getir);
            });
        </script>

        <form id="antibiyotik_ekti_grubu_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" value="<?php echo $kisitli_bildirim_tanim["id"] ;?>">
            <input type="text" hidden class="form-control" name="antibiotic_definitionid" id="antibiotic_definitionid">

            <div class="row mt-3">

                <div class="col-4">
                    <label>Antibiyotik Etki Grubu</label>
                </div>

                <div class="col-8">
                    <select class="form-select" name="antibiotic_action_group" id="antibiyotik_etki_grubu_duzenle" title="A grubu antibiyotikleri en etkili antibiyotik grubu olmak üzere, D grubna kadar etki bakımından azalarak derecelendirilir.">
                        <option selected disabled class="text-white bg-danger">Antibiyotik Grubu Seçiniz.</option>
                        <?php $sql= verilericoklucek("select * from lab_definitions where definition_type='ANTIBIYOTIK_ETKI_GRUP' and lab_definitions.status='1'");
                        foreach ($sql as $item){?>
                            <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                        <?php } ?>


                    </select>
                </div>

            </div>

        </form>

        <div class="mt-3 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-antibiyotik-ekti-grubu-duzenle">Kaydet</a>
            </div>
        </div>

    </div>

    <script>

        $("body").off("click", "#btn-antibiyotik-ekti-grubu-duzenle").on("click", "#btn-antibiyotik-ekti-grubu-duzenle", function(e){
                var antibiyotik_ekti_grubu_duzenle_form = $("#antibiyotik_ekti_grubu_duzenle_form").serialize();
                var antibiyotik_etki_grubu_duzenle = $("#antibiyotik_etki_grubu_duzenle").val();
                var antibiotic_definitionid = $("#antibiotic_definitionid").val();
                if (antibiotic_definitionid != '' && antibiyotik_etki_grubu_duzenle != null) {
                    $.ajax({
                        type:'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_duzenle',
                        data:antibiyotik_ekti_grubu_duzenle_form,
                        success:function(e){
                            $('#sonucyaz').html(e);

                            $('.tanimlamalar_w40_h50').window('close');

                            var getir = " <?php echo $kisitli_bildirim_tanim["bacteria_definitionid"];?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                                $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                            });
                        }
                    });
                } else if (antibiotic_definitionid == '') {
                    alertify.warning("Tanımlı antibiyotik seçiniz.");
                }else if (antibiyotik_etki_grubu_duzenle == null) {
                    alertify.warning("Antibiyotik etki grubu seçiniz.");
                }
            });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h50').window('close');
        });
    </script>

<?php }

else if($islem=="bakteriye_tanimli_antibiyotik_grubu_liste_filtre"){
    $gelen_veri=$_GET["getir"];
    $etki_grubu=$_GET["etki_grubu"];?>
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Bakteriye Etkili Antibiyotik Grubu Listesi</b></div>
        <div class="card-body " style="height: 84vh;">

            <select class="form-select etki_grubu1" id="antibiotic_action_group" title="A grubu antibiyotikleri en etkili antibiyotik grubu olmak üzere, D grubna kadar etki bakımından azalarak derecelendirilir.">
                <option selected disabled class="text-white bg-danger">Antibiyotik Grubu Seçiniz.</option>
                <option class="iptal_filtreleme" value="400">Tümünü Göster.</option>
                <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='ANTIBIYOTIK_ETKI_GRUP' and lab_definitions.status='1'");
                foreach ($sql as $item){ ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                <?php }?>
            </select>

            <script>
                $(".etki_grubu1").change(function () {
                    var etki_grubu =$(this).val();
                    var getir ="<?php echo $gelen_veri;?>";
                    if($(this).val()=='400'){
                        $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(getVeri){
                            $('#bakteriye_tanimli_antibiyotik_liste').html(getVeri);
                        });
                    }else{
                        $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteriye_tanimli_antibiyotik_grubu_liste_filtre",{"getir":getir,"etki_grubu":etki_grubu},function(getVeri){
                            $('#bakteriye_tanimli_antibiyotik_liste').html(getVeri);
                        });
                    }
                });


            </script>

            <div class="mt-3"></div>

            <script>
                $(document).ready(function(){
                    $('#bakteri-antibiyotik-etki-datatb').DataTable( {
                        "scrollY": '67vh',
                        "scrollX": true,
                        "info":true,
                        "paging":false,
                        "searching":true,
                        "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                    });
                });
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="bakteri-antibiyotik-etki-datatb">
                <thead>
                <tr>
                    <th>Antibiyotik Etki Grubu</th>
                    <th>Kodu</th>
                    <th>Antibiyotik Adı</th>
                    <th>Antibiyotik Profili</th>
                    <th>Açıklama</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select lab_restricted_notification_definitions.id as kisitli_bildirimid, lab_restricted_notification_definitions.status as kisitli_bildirim_status,lab_restricted_notification_definitions.*,lab_antibiotic_definition.* from lab_restricted_notification_definitions,lab_antibiotic_definition where lab_restricted_notification_definitions.antibiotic_definitionid=lab_antibiotic_definition.id and lab_restricted_notification_definitions.bacteria_definitionid=$gelen_veri and lab_restricted_notification_definitions.antibiotic_action_group=$etki_grubu");
                foreach ((array)$sql as $row){
                    $antibiyotik_profili=singular("lab_antibiotic_profile","id",$row["antibiotic_profile"]); ?>
                    <tr style="cursor: pointer;" id="<?php echo $row["kisitli_bildirimid"];?>" >
                        <td><?php echo mb_strtoupper(labtanimgetirid($row["antibiotic_action_group"]));?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_code"]);?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_name"]);?></td>
                        <td><?php echo mb_strtoupper($antibiyotik_profili["antibiotic_profile_name"]);?></td>
                        <td><?php echo mb_strtoupper($row["antibiotic_description"]);?></td>
                        <td align="center"><?php if($row["kisitli_bildirim_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square bakteriye_tanimli_antibiyotik_grubu_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["kisitli_bildirimid"]; ?>" ></i>
                            <?php if($row['kisitli_bildirim_status']=='0'){ ?>
                                <i class="fa-solid fa-recycle bakteriye_tanimli_antibiyotik_grubu_aktif" title="Aktif Et" id="<?php echo $row["kisitli_bildirimid"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash bakteriye_tanimli_antibiyotik_grubu_sil" title="İptal" id="<?php echo $row["kisitli_bildirimid"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>



            <script>
                $("body").off("click", ".bakteriye_tanimli_antibiyotik_grubu_duzenle").on("click", ".bakteriye_tanimli_antibiyotik_grubu_duzenle", function(e){
                    var getir = $(this).attr('id');
                    $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=modal-antibiyotik-ekti-grubu-tanim-duzenle",{getir:getir},function(getVeri){
                        $('#lab_modal_lg_icerik').html(getVeri);
                    });
                });

                $("body").off("click", ".bakteriye_tanimli_antibiyotik_grubu_sil").on("click", ".bakteriye_tanimli_antibiyotik_grubu_sil", function(e){
                    var id = $(this).attr("id");

                    alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Bakteriye Tanımlı Antibiyotik Grubu Silme Nedeni..'></textarea>", function(){

                        var delete_detail = $('#delete_detail').val();
                        var delete_datetime = $('#delete_datetime').val();
                        if (delete_detail != '') {
                            $.ajax({
                                type: 'POST',
                                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_sil',
                                data: {
                                    id,
                                    delete_detail,
                                    delete_datetime
                                },
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    var getir = "<?php echo $gelen_veri;?>";
                                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                                        $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                                    });
                                    $('.alertify').remove();
                                }
                            });
                        } else if (delete_detail == '') {
                            alertify.warning("Silme Nedeni Giriniz.");
                            setTimeout(() => {
                                $(".bakteriye_tanimli_antibiyotik_grubu_sil").trigger("click");
                            }, "10")
                        }
                    }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Bakteriye Tanımlı Antibiyotik Grubu Silme İşlemini Onayla"});
                });

                $("body").off("click",".bakteriye_tanimli_antibiyotik_grubu_aktif").on("click",".bakteriye_tanimli_antibiyotik_grubu_aktif", function (e) {
                    var getir = $(this).attr('id');

                    $.ajax({
                        type:'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=sql_bakteriye_tanimli_antibiyotik_etki_grubu_aktiflestir',
                        data:{getir},
                        success:function(e){
                            $('#sonucyaz').html(e);
                            var getir = "<?php echo $gelen_veri;?>";
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kisitlibildirimtanimlari.php?islem=bakteri_tanimli_antibiyotik_listesi",{"getir":getir},function(e){
                                $('#bakteriye_tanimli_antibiyotik_liste').html(e);
                            });
                        }
                    });
                });
            </script>

        </div>
    </div>
<?php } ?>