<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_parametre_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_test_parameter",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Parametre Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Parametre Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_parametre_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_test_parameter", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Parametre Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Parametre Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_parametre_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_test_parameter", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Parametre Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Parametre Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_parametre_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_test_parameter','id',$id,$date,$user);

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

    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>

    <div class="row">

        <div class="col-4">

            <div class="card">
                <div class="card-header p-1"style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tetkik Listesi</b></div>
                <div class="card-body " style="height: 84vh;">


                    <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tetkik-tanimlari-list-datatb">
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
                    $('#tetkik-tanimlari-list-datatb').DataTable( {
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
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=parametre_tanim_liste",{ "getir":getir },function(getveri){
                            $('#parametre-tanim').html(getveri);
                        });
                    });
                });
            </script>

        </div>

        <div class="col-8">
            <div id="parametre-tanim">

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

<?php }

else if($islem=="parametre_tanim_liste"){
    $tetkik_id = $_GET['getir'];?>
        <input type="text" hidden id="tetkik_id" value="<?php echo $tetkik_id;?>">
    <div class="card">
        <div class="card-header p-1" style="background-color: #e7f0ff !important;"><label style="color: black"><b>Tanımlı Parametreler</b></div>
        <div class="card-body " style="height: 84vh;">

            <script>
                var tetkik_id = $('#tetkik_id').val();

                $('#tetkik-parametre-list-datatb').DataTable( {
                    scrollY: '69vh',
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
                            text: 'Parametre Tanımla',
                            className: 'btn btn-sm btn-success btn-sm btn-kaydet',

                            action: function ( e, dt, button, config ) {

                                $('.tanimlamalar_w70_h45').window('setTitle', 'Parametre Tanımla');
                                $('.tanimlamalar_w70_h45').window('open');
                                $('.tanimlamalar_w70_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=modal-parametre-tanimla&getir='+ tetkik_id +'');
                            }
                        }
                    ],
                } );
            </script>

            <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="tetkik-parametre-list-datatb">
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
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from lab_test_parameter where lab_test_parameter.analysis_id='$tetkik_id'");
                foreach ((array)$sql as $rowa){ ?>
                    <tr  data-toggle="modal"  data-target="#parametre" id="<?php echo $rowa["id"] ?>">
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
                        <td align="center">
                            <i class="fa fa-pen-to-square parametre_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $rowa["id"]; ?>"></i>

                            <?php if($rowa['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle parametre_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $rowa["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash parametre_tanim_sil" title="İptal" id="<?php echo $rowa["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>


    <script>
        $("body").off("click", ".parametre_tanim_duzenle").on("click", ".parametre_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            var tetkik = $('#tetkik_id').val();


            $('.tanimlamalar_w70_h45').window('setTitle', 'Parametre Tanımı Düzenle');
            $('.tanimlamalar_w70_h45').window('open');
            $('.tanimlamalar_w70_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=modal-parametre-tanim-duzenle&getir=' + getir + '&tetkik='+ tetkik +'');
        });

        $("body").off("click", ".parametre_tanim_sil").on("click", ".parametre_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Parametre Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=sql_parametre_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=parametre_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                                $('#parametre-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".laboratuvar_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Parametre Silme İşlemini Onayla"});
        });

        $("body").off("click",".parametre_tanim_aktif").on("click",".parametre_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=sql_parametre_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=parametre_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                        $('#parametre-tanim').html(e);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-parametre-tanimla"){
    $tetkik_id = $_GET['getir'];
    $tetkiktablo=singular("lab_analysis","id",$tetkik_id);?>

        <div class="mt-2 mx-2">
            <div class="row">
                <label><b><?php echo mb_strtoupper($tetkiktablo["analysis_name"]);?></b></label>
            </div>

            <form id="parametre_tanimla_form" action="javascript:void(0);">


                <div class="row mt-3">
                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label>Parametre Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="parameter_name" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Rapora Basılacak Ad</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="report_print_name" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Kısa Adı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="short_name" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>İletişim No</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="contact_no" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Birim</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" name="unit">
                                    <option selected disabled class="text-white bg-danger">Birim seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where lab_definitions.status='1' and definition_type='PARAMETRE_BIRIM'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item["id"]; ?>" ><?php echo $item["definition_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Alt Limit Numerik</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="sub_limit_numeric" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Üst Limit Numerik</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="up_limit_numeric" id="example-text-input">
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label>Alt Limit Text</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="sub_limit_text" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Üst Limit Text</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="up_limit_text" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Özel Referanslar</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" name="special_referances" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Ana Parametre</label>
                            </div>
                            <div class="col-7">
                                Evet<input type="radio" class="col-md-3" name="main_parameter" value="1">
                                Hayır<input type="radio" class="col-md-3" name="main_parameter" value="0">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Cut Off</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="number"  name="cut_off" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Cut Off Yön</label>
                            </div>
                            <div class="col-7">
                                +<input type="radio" class="col-md-4" name="cut_off_yon" value="1">
                                -<input type="radio" class="col-md-4"  name="cut_off_yon" value="2">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Formül Bilgisi</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="formula_information" id="example-text-input">
                            </div>
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="row">
                            <div class="col-5">
                                <label>Ölçülebilir Alt Limit</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="measurable_sub_limit" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Ölçülebilir Üst Limit</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="measurable_up_limit" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Birim Kat Sayısı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="number"  name="unit_floor_number" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Ondalık Kat Sayı</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="measurable_sub_limit" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-5">
                                <label>Test Referansları</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text"  name="test_referance_class" id="example-text-input">
                            </div>
                        </div>

                        <input class="form-control" type="text" hidden name="analysis_id" id="example-text-input" value="<?php echo $tetkik_id; ?>">

                    </div>
                </div>

            </form>
        </div>



    <div class="row mt-2" align="right">
        <div class="col-7"></div>
        <div class="col-5">
            <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-parametre-tanimi-ekle">Kaydet</a>
        </div>
    </div>

    <script>

        $("body").off("click", "#btn-parametre-tanimi-ekle").on("click", "#btn-parametre-tanimi-ekle", function(e){

            var parametre_tanimla_form = $("#parametre_tanimla_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=sql_parametre_tanimi_ekle',
                data:parametre_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);

                    $('.tanimlamalar_w70_h45').window('close');

                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=parametre_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                        $('#parametre-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w70_h45').window('close');
        });

    </script>

<?php }

else if($islem=="modal-parametre-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $tetkik_id = $_GET['tetkik'];
    $lab_parametre=singularactive("lab_test_parameter","id",$gelen_veri);
    $tetkiktablo=singular("lab_analysis","id",$tetkik_id);?>

    <div class="mt-2 mx-2">

        <div class="row">
            <label><b><?php echo mb_strtoupper($tetkiktablo["analysis_name"]);?></b></label>
        </div>

        <form id="parametre_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_parametre["id"];?>"/>

            <div class="row mt-3">
                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Parametre Adı</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="parameter_name" id="example-text-input" value="<?php echo $lab_parametre["parameter_name"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Rapora Basılacak Ad</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="report_print_name" id="example-text-input" value="<?php echo $lab_parametre["report_print_name"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Kısa Adı</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="short_name" id="example-text-input" value="<?php echo $lab_parametre["short_name"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>İletişim No</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="contact_no" id="example-text-input" value="<?php echo $lab_parametre["contact_no"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Birim</label>
                        </div>
                        <div class="col-7">
                            <select class="form-select" name="unit">
                                <option selected disabled class="text-white bg-danger">Birim seçiniz.</option>
                                <?php $sql = verilericoklucek("select * from lab_definitions where lab_definitions.status='1' and definition_type='PARAMETRE_BIRIM'");
                                foreach ($sql as $item){ ?>
                                    <option <?php if($lab_parametre["unit"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item["id"]; ?>" ><?php echo $item["definition_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Alt Limit Numerik</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="sub_limit_numeric" id="example-text-input" value="<?php echo $lab_parametre["sub_limit_numeric"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Üst Limit Numerik</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text" name="up_limit_numeric" id="example-text-input" value="<?php echo $lab_parametre["up_limit_numeric"]?>">
                        </div>
                    </div>

                </div>

                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Alt Limit Text</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text" name="sub_limit_text" id="example-text-input" value="<?php echo $lab_parametre["sub_limit_text"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Üst Limit Text</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text" name="up_limit_text" id="example-text-input" value="<?php echo $lab_parametre["up_limit_text"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Özel Referanslar</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text" name="special_referances" id="example-text-input" value="<?php echo $lab_parametre["special_referances"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Ana Parametre</label>
                        </div>
                        <div class="col-7">
                            Evet<input type="radio" class="col-md-3" name="main_parameter" value="1" <?php if($lab_parametre['main_parameter']==1){ ?> checked <?php } ?>>
                            Hayır<input type="radio" class="col-md-3" name="main_parameter" value="0" <?php if($lab_parametre['main_parameter']==0){ ?> checked <?php } ?>>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Cut Off</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="number"  name="cut_off" id="example-text-input" value="<?php echo $lab_parametre["cut_off"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Cut Off Yön</label>
                        </div>
                        <div class="col-7">
                            +<input type="radio" class="col-md-4" name="cut_off_yon" value="1" <?php if($lab_parametre['cut_off_yon']==1){ ?> checked <?php } ?>>
                            -<input type="radio" class="col-md-4"  name="cut_off_yon" value="2" <?php if($lab_parametre['cut_off_yon']==2){ ?> checked <?php } ?>>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Formül Bilgisi</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="formula_information" id="example-text-input" value="<?php echo $lab_parametre["formula_information"]?>">
                        </div>
                    </div>

                </div>

                <div class="col-4">

                    <div class="row">
                        <div class="col-5">
                            <label>Ölçülebilir Alt Limit</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="measurable_sub_limit" id="example-text-input" value="<?php echo $lab_parametre["measurable_sub_limit"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Ölçülebilir Üst Limit</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="measurable_up_limit" id="example-text-input" value="<?php echo $lab_parametre["measurable_up_limit"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Birim Kat Sayısı</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="number"  name="unit_floor_number" id="example-text-input" value="<?php echo $lab_parametre["unit_floor_number"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Ondalık Kat Sayı</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="measurable_sub_limit" id="example-text-input" value="<?php echo $lab_parametre["measurable_sub_limit"]?>">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-5">
                            <label>Test Referansları</label>
                        </div>
                        <div class="col-7">
                            <input class="form-control" type="text"  name="test_referance_class" id="example-text-input" value="<?php echo $lab_parametre["test_referance_class"]?>">
                        </div>
                    </div>

                </div>
            </div>

    </form>

    </div>

    <div class="row mt-2" align="right">
        <div class="col-7"></div>
        <div class="col-5">
            <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-parametre-tanim-duzenle">Kaydet</a>
        </div>
    </div>

    <script>

        $("body").off("click", "#btn-parametre-tanim-duzenle").on("click", "#btn-parametre-tanim-duzenle", function(e){
            var parametre_duzenle_form = $("#parametre_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=sql_parametre_tanimi_duzenle ',
                data:parametre_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);

                    $('.tanimlamalar_w70_h45').window('close');

                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/parametretanimla.php?islem=parametre_tanim_liste",{ "getir":<?php echo $tetkik_id;?> },function(e){
                        $('#parametre-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w70_h45').window('close');
        });

    </script>
<?php } ?>