<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_ihlal_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_breach_definitions",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('İhlal Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('İhlal Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_ihlal_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_breach_definitions", "id", $id, $_POST);

    if ($sql == 1){?>
        <script>
            alertify.success('İhlal Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('İhlal Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_ihlal_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_breach_definitions", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('İhlal Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('İhlal Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_ihlal_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_breach_definitions','id',$id,$date,$user);

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

else if ($islem=="sql_ihlal_kullanici_tanimla"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_breach_definitions_user",$_POST);

    if ($sql == 1) {?>
        <script>
            alertify.success('Kullanıcı Tanımlama İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Kullanıcı Tanımlama İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_ihlal_tanimli_kullanici_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_breach_definitions_user", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Tanımlı Kullanıcıyı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Tanımlı Kullanıcıyı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_ihlal_tanimli_kullanici_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_breach_definitions_user','id',$id,$date,$user);

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

        <div class="col-4">

            <div class="card">
                <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tetkik Listesi</b></div>
                <div class="card-body " style="height: 84vh;">


                    <table id="tetkik-tanimlari-list-datatb1" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
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
                            <tr style="cursor: pointer;" id="<?php echo $rowa["lab_analysisid"]; ?>" class="tetkik_sec">
                                <td><?php echo $rowa["group_name"]; ?></td>
                                <td><?php echo $rowa["sut_code"]; ?></td>
                                <td><?php echo $rowa["analysis_name"]; ?></td >
                                <td align="center"><?php if($rowa["analysis_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>

            <script>
                $(document).ready(function(){
                    $('#tetkik-tanimlari-list-datatb1').DataTable( {
                        scrollY: '69vh',
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
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=ihlal_tanim_liste",{ "getir":getir },function(getveri){
                            $('#ihlal-tanim').html(getveri);
                        });
                    });
                });
            </script>

        </div>

        <div class="col-8">
            <div id="ihlal-tanim">

                <div class="warning-definitions mt-5">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">SOL TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>İhlal Tanımı Yapmak İçin Tetkik Seçiniz.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

<?php }

else if($islem=="ihlal_tanim_liste"){
    $tetkik_id = $_GET['getir'];?>
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı İhlaller</b></div>
        <div class="card-body " style="height: 45vh;">

            <script>
                $('#tetkik-ihlal-list-datatb').DataTable( {
                    scrollY: '32vh',
                    scrollX: true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'İhlal Tanımla',
                            className: 'btn btn-success btn-sm btn-kaydet mx-1',

                            action: function ( e, dt, button, config ) {
                                var getir="<?php echo $tetkik_id?>";

                                $('.tanimlamalar_w40_h35').window('setTitle', 'İhlal Tanımla');
                                $('.tanimlamalar_w40_h35').window('open');
                                $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=modal-ihlal-tanimla&getir=' + getir + '');
                            }
                        },

                        {
                            text: 'Kullanıcı Tanımla',
                            className: 'btn btn-success btn-sm btn-kullanici-tanimla mx-1',

                            action: function ( e, dt, button, config ) {
                                var getir=$('.ihlal_tanim_secq-kaldir').attr('id');

                                $('.tanimlamalar_w40_h45').window('setTitle', 'Kullanıcı Tanımla');
                                $('.tanimlamalar_w40_h45').window('open');
                                $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=modal_kullanici_tanimla&getir=' + getir + '');
                            }
                        },
                    ],
                });
            </script>

            <table id="tetkik-ihlal-list-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kural Kodu</th>
                    <th>Kural Adı</th>
                    <th>Kural Mesajı</th>
                    <th>Kural İşlevi</th>
                    <th>Sonuç</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_breach_definitions where lab_breach_definitions.analysis_id=$tetkik_id");
                foreach ((array)$sql as $rowa){ ?>
                    <tr style="cursor: pointer;" id="<?php echo $rowa["id"];?>" class="ihlal_tanim_secq">
                        <td><?php echo $rowa["id"];?></td >
                        <td><?php echo $rowa["rule_code"];?></td >
                        <td><?php echo $rowa["rule_name"];?></td >
                        <td><?php echo $rowa["rule_message"];?></td >
                        <td><?php echo labtanimgetirid($rowa["rule_function"]);?></td >
                        <td><?php  if($rowa["numeric_comment"]==1){echo "Sayısal Sonuç";}else{echo "Yorumsal Sonuç";}?></td >
                        <td align="center"><?php if($rowa["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square ihlal_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>

                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle ihlal_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash ihlal_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
        </div>

    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Kullanıcılar</b></div>
        <div class="card-body" style="height: 36vh;">
            <div id="ihlal_kullanici_listesi">
                <div class="warning-definitions mt-3">
                    <div class="alert-dismissible fade show  mb-0 text-center" role="alert">
                        <i class="mdi mdi-alert-outline d-block display-4 mt-2 mb-3 text-warning"></i>
                        <h5 class="text-warning">ÜST TARAFTAN SEÇİM YAPINIZ</h5>
                        <p>İhlal'e Tanımlanmış Kullanıcıları Görmek İçin Yukarıdan İhlal Seçiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on("click",".ihlal_tanim_secq",function() {
            $('.btn-kullanici-tanimla').attr('disabled', false);

            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.ihlal_tanim_secq-kaldir').removeClass("text-white");
            $('.ihlal_tanim_secq-kaldir').removeClass("ihlal_tanim_secq-kaldir");
            $('.ihlal_tanim_secq').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white ihlal_tanim_secq-kaldir");

            var getir=$('.ihlal_tanim_secq-kaldir').attr('id');
            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=kullanici_listesi",{ "getir":getir },function(getveri){
                $('#ihlal_kullanici_listesi').html(getveri);
            });
        });
    </script>


    <script>
        $("body").off("click", ".ihlal_tanim_duzenle").on("click", ".ihlal_tanim_duzenle", function(e){
            var getir = $(this).attr('id');


            $('.tanimlamalar_w40_h35').window('setTitle', 'İhlal Tanımı Düzenle');
            $('.tanimlamalar_w40_h35').window('open');
            $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=modal-ihlal-tanim-duzenle&getir=' + getir + '&tetkik='+ <?php echo $tetkik_id;?> +'');
        });

        $("body").off("click", ".ihlal_tanim_sil").on("click", ".ihlal_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='İhlal Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=ihlal_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                                $('#ihlal-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".ihlal_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"İhlal Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".ihlal_tanim_aktif").on("click",".ihlal_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=ihlal_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                        $('#ihlal-tanim').html(e);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-ihlal-tanimla"){
    $tetkik_id = $_GET['getir'];?>

    <div class="mx-3">
        <form id="ihlal_tanimla_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="analysis_id" id="analysis_id" value="<?php echo $tetkik_id;?>">

            <div class="row mt-3">
                <div class="col-5">
                    <label>Kural Kodu</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_code" id="rule_code">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural Adı</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_name" id="rule_name">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural Mesajı</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_message" id="rule_message">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural İşlevi</label>
                </div>
                <div class="col-7">
                    <select class="form-select" name="rule_function" id="rule_function" >
                        <option selected disabled class="text-white bg-danger">Kural işlevi seçiniz.</option>
                        <?php
                        $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KURAL_ISLEVI'");
                        foreach ($sql as $rowa) {?>
                            <option value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group row mt-3">
                    <div class="col-sm-5">

                    </div>
                    <div class="col-sm-7">
                        <input type="radio" class="mx-1" name="numeric_comment" value="1">Sayısal Sonuç
                        <input type="radio" class="mx-1" name="numeric_comment" value="0">Yorumsal Sonuç
                    </div>
                </div>
            </div>

        </form>



        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_ihlal_tanim_ekle">Kaydet</a>
            </div>
        </div>
    </div>

    <script>

        $("body").off("click", "#btn_ihlal_tanim_ekle").on("click", "#btn_ihlal_tanim_ekle", function(e){
            var ihlal_tanimla_form = $("#ihlal_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimi_ekle',
                data:ihlal_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);

                    $('.tanimlamalar_w40_h35').window('close');
                    var getir = "<?php echo $tetkik_id;?>";
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=ihlal_tanim_liste",{ "getir":getir },function(e){
                        $('#ihlal-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>

<?php }

else if($islem=="modal-ihlal-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $tetkik_id = $_GET['tetkik_id'];
    $lab_ihlaltanim=singularactive("lab_breach_definitions","id",$gelen_veri); ?>


    <div class="mx-3">
        <form id="ihlal_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_ihlaltanim["id"];?>">

            <div class="row mt-3">
                <div class="col-5">
                    <label>Kural Kodu</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_code" id="rule_code" value="<?php echo $lab_ihlaltanim["rule_code"];?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural Adı</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_name" id="rule_name" value="<?php echo $lab_ihlaltanim["rule_name"];?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural Mesajı</label>
                </div>
                <div class="col-7">
                    <input type="text" class="form-control" name="rule_message" id="rule_message" value="<?php echo $lab_ihlaltanim["rule_message"];?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-5">
                    <label>Kural İşlevi</label>
                </div>
                <div class="col-7">
                    <select class="form-select" name="rule_function" id="rule_function" >
                        <option selected disabled class="text-white bg-danger">Kural işlevi seçiniz.</option>
                        <?php
                        $sql = verilericoklucek("select * from lab_definitions where status='1' and definition_type='KURAL_ISLEVI'");
                        foreach ($sql as $rowa) {?>
                            <option <?php if($lab_ihlaltanim["rule_function"]==$rowa["id"]){ echo "selected"; }?> value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group row mt-3">
                    <div class="col-sm-5">

                    </div>
                    <div class="col-sm-7">
                        <input type="radio" class="mx-1" name="numeric_comment" value="1" <?php if($lab_ihlaltanim['numeric_comment']==1){ ?> checked <?php } ?>>Sayısal Sonuç
                        <input type="radio" class="mx-1" name="numeric_comment" value="0" <?php if($lab_ihlaltanim['numeric_comment']==0){ ?> checked <?php } ?>>Yorumsal Sonuç
                    </div>
                </div>
            </div>

        </form>



        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-ihlal-tanimi-duzenle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>

        $("body").off("click", "#btn-ihlal-tanimi-duzenle").on("click", "#btn-ihlal-tanimi-duzenle", function(e){
            var ihlal_duzenle_form = $("#ihlal_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimi_duzenle',
                data:ihlal_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h35').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=ihlal_tanim_liste",{ "getir":<?php echo $lab_ihlaltanim["analysis_id"];?> },function(e){
                        $('#ihlal-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>
<?php }


else if($islem=="kullanici_listesi"){
    $ihlal_tanimid = $_GET['getir'];?>

    <script>
        $(document).ready(function(){
            $('#ihlal_tanim_kullanici_datatb').DataTable( {
                scrollY: '22vh',
                scrollX: true,
                "info":true,
                "paging":false,
                "searching":true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
            });
        });
    </script>

    <table id="ihlal_tanim_kullanici_datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" >
        <thead>
        <tr>
            <th>Kural Kodu</th>
            <th>Kullanıcı Adı</th>
            <th>Durum</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>
        <?php $sql = verilericoklucek("select lab_breach_definitions.id as breach_definitions_id,lab_breach_definitions_user.status as definitions_user_status,lab_breach_definitions_user.id as definitions_userid ,lab_breach_definitions.status as breach_definitions_status, users.id as userid , users.status as users_status,lab_breach_definitions_user.*,lab_breach_definitions.*,users.* from lab_breach_definitions_user,lab_breach_definitions,users where lab_breach_definitions_user.breach_definitionsid=lab_breach_definitions.id and lab_breach_definitions_user.users_id=users.id and breach_definitionsid=$ihlal_tanimid");
        foreach ((array)$sql as $rowa){ ?>
            <tr style="cursor: pointer;" id="<?php echo $rowa["id"];?>">
                <td><?php echo $rowa["rule_code"];?></td>
                <td><?php echo $rowa["name_surname"];?></td>
                <td align="center"><?php if($rowa["definitions_user_status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                <td align="center">
                    <?php if($rowa['definitions_user_status']=='0'){ ?>
                        <i class="fa-solid fa-recycle tanimli_kullanici_aktiflestir"
                           title="Aktif Et" id="<?php echo $rowa["definitions_userid"]; ?>" alt="icon" ></i>

                    <?php }else{ ?>

                        <i class="fa fa-trash tanimli_kullanici_sil" title="İptal" id="<?php echo $rowa["definitions_userid"]; ?>" alt="icon"></i>

                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
        $("body").off("click", ".tanimli_kullanici_sil").on("click", ".tanimli_kullanici_sil", function(e){
            var id = $(this).attr("id");
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Tanımlı Kullanıcı Silme Nedeni..'></textarea>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimli_kullanici_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime,
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=kullanici_listesi",{ "getir":<?php echo $ihlal_tanimid;?> },function(getveri){
                                $('#ihlal_kullanici_listesi').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".ihlal_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Tanımlı Kullanıcı Silme İşlemini Onayla"});
        });

        $("body").off("click",".tanimli_kullanici_aktiflestir").on("click",".tanimli_kullanici_aktiflestir", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_tanimli_kullanici_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=kullanici_listesi",{ "getir":<?php echo $ihlal_tanimid;?> },function(getveri){
                        $('#ihlal_kullanici_listesi').html(getveri);
                    });
                }
            });
        });
    </script>
<?php }


else if($islem=="modal_kullanici_tanimla"){
    $ihlal_id = $_GET['getir']; ?>

    <div class="mt-2">

    <table id="kullanici-listesi-datatb" class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;">
        <thead>
        <tr>
            <th>Adı Soyadı</th>
            <th>Ünvanı</th>
            <th>Bölümü</th>
        </tr>
        </thead>
        <tbody>
        <?php $sql = verilericoklucek("select * from users where status='1'");
        foreach ((array)$sql as $rowa ) {?>
            <tr style="cursor: pointer;" id="<?php echo $rowa["id"]; ?>" class="kullanici_sec">
                <td><?php echo $rowa["name_surname"]; ?></td>
                <td><?php echo islemtanimgetirid($rowa["title"]); ?></td>
                <td><?php echo birimgetirid($rowa["department"]); ?></td >
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $('#kullanici-listesi-datatb').DataTable( {
                    scrollY: '25vh',
                    scrollX: true,
                    "info":true,
                    "paging":false,
                    "searching":true,
                    "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},
                });
            }, 100);
        });
        $(".kullanici_sec").click(function () {
            $(this).css('background-color') != 'rgb(147,203,198)' ;
            $('.kullanici_sec-kaldir').removeClass("text-white");
            $('.kullanici_sec-kaldir').removeClass("kullanici_sec-kaldir");
            $('.kullanici_sec').css({"background-color": "rgb(255, 255, 255)"});
            $(this).css({"background-color": "rgb(147,203,198)"});
            $(this).addClass("text-white kullanici_sec-kaldir");

            var users_id=$('.kullanici_sec-kaldir').attr('id');
            $('#users_id').val(users_id);
        });
    </script>

    <form id="ihlal_kullanici_tanimla_form" action="javascript:void(0);">
        <input class="form-control" hidden type="text" name="breach_definitionsid" id="breach_definitionsid" value="<?php echo $ihlal_id;?>">
        <input class="form-control" hidden type="text" name="users_id" id="users_id">
    </form>

    <div class="row" align="right">
        <div class="col-7"></div>
        <div class="col-5">
            <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-ihlal-kullanici-tanimla">Kaydet</a>
        </div>
    </div>

    </div>

    <script>
        $("body").off("click", "#btn-ihlal-kullanici-tanimla").on("click", "#btn-ihlal-kullanici-tanimla", function(e){
            var ihlal_kullanici_tanimla_form = $("#ihlal_kullanici_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=sql_ihlal_kullanici_tanimla',
                data:ihlal_kullanici_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);


                    document.getElementById("ihlal_kullanici_tanimla_form").reset();

                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ihlaltanimlama.php?islem=kullanici_listesi",{ "getir":<?php echo $ihlal_id;?> },function(getveri){
                        $('#ihlal_kullanici_listesi').html(getveri);
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