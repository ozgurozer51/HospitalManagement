<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_test_grup_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_test_groups",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Test Grup Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Grup Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_test_grup_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_test_groups", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Test Grup Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Grup Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_test_grup_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_test_groups", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Test Grup Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Test Grup Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_test_grup_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_test_groups','id',$id,$date,$user);

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

    <div class="mx-2 mt-2" id="test-grup-tanim">

        <script>
                    $('#test-grup-tanimla-tab').DataTable( {
                        "processing": true,
                        "responsive":true,
                        "scrollY": true,
                        "autoWidth": false,
                        "scrollX": true,
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                        },
                        "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        buttons: [
                            {
                                text: 'Test Grup Tanımla',
                                className: 'btn btn-success btn-sm btn-kaydet',

                                action: function ( e, dt, button, config ) {
                                    $('.tanimlamalar_w40_h45').window('setTitle', 'Test Grup Tanımla');
                                    $('.tanimlamalar_w40_h45').window('open');
                                    $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=modal-test-grup-tanimla');
                                }
                            }
                        ],
                    } );
                </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="test-grup-tanimla-tab">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Laboratuvar</th>
                        <th>Grup Kodu</th>
                        <th>Grup Adı</th>
                        <th>Rapor Tipi</th>
                        <th>Grup Alt Metin</th>
                        <th>Grup Üst Metin</th>
                        <th>Barkod Metni</th>
                        <th>Açıklama</th>
                        <th>Grup Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select * from lab_test_groups");
                    foreach ((array)$sql as $row){ ?>
                        <tr style="cursor: pointer;">
                        <td><?php echo $row["id"]; ?> </td>
                        <td><?php echo labtanimgetirid($row["lab_id"]); ?> </td>
                        <td><?php echo $row["group_code"];?> </td >
                        <td><?php echo $row["group_name"];?> </td >
                        <td><?php echo labtanimgetirid($row["report_type"]);?> </td >
                        <td><?php echo $row["group_up_text"];?> </td >
                        <td><?php echo $row["group_sub_text"];?> </td >
                        <td><?php echo $row["barcode_text"];?> </td >
                        <td><?php echo $row["group_explain"];?> </td >
                        <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square test_grup_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle test_grup_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash test_grup_tanim_sil" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    </div>

    <script>
        $("body").off("click", ".test_grup_tanim_duzenle").on("click", ".test_grup_tanim_duzenle", function(e){
            var getir = $(this).attr('id');
            $('.tanimlamalar_w40_h45').window('setTitle', 'Test Grup Tanımı Düzenle');
            $('.tanimlamalar_w40_h45').window('open');
            $('.tanimlamalar_w40_h45').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=modal-test-grup-tanim-duzenle&getir=' + getir + '');
        });

        $("body").off("click", ".test_grup_tanim_sil").on("click", ".test_grup_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Test Grup Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=sql_test_grup_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=listeyi-getir",function(e){
                                $('#test-grup-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".test_grup_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Test Grup Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".test_grup_tanim_aktif").on("click",".test_grup_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=sql_test_grup_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#test-grup-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=listeyi-getir");
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-test-grup-tanimla"){?>

    <div class="mx-3">
        <form id="test_grup_tanimla_form" action="javascript:void(0);">

            <div class="col-12  mt-4 ">
                <div class="row">
                    <div class="col-4">
                        <label>Laboratuvar</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="lab_id" > <option selected disabled class="text-white bg-danger">Laboratuvar Seçiniz..</option> <?php $bolumgetir = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TANIM' and lab_definitions.status='1'"); foreach ($bolumgetir as $value){ ?>
                                <option  value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Kodu</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_code" id="group_code">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_name" id="group_name">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Rapor Tipi</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="report_type" > <option selected disabled class="text-white bg-danger">Rapor Tipi Seçiniz..</option> <?php $bolumgetir = verilericoklucek("select * from lab_definitions where definition_type='TEST_GRUP_RAPOR_TURU' and lab_definitions.status='1'"); foreach ($bolumgetir as $value){ ?>
                                <option  value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Üst Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_up_text" id="group_up_text">
                    </div>
                </div>


                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Alt Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_sub_text" id="group_sub_text">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Barkod Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="barcode_text" id="barcode_text">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Açıklama</label>
                    </div>
                    <div class="col-8">
                        <textarea class="form-control" name="group_explain" id="group_explain"></textarea>
                    </div>
                </div>

            </div>

        </form>



        <div class="mt-4"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-grup-tanimi-ekle">Kaydet</a>
            </div>
        </div>
    </div>

    <script>

        $("body").off("click", "#btn-test-grup-tanimi-ekle").on("click", "#btn-test-grup-tanimi-ekle", function(e){
            var test_grup_tanimla_form = $("#test_grup_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=sql_test_grup_tanimi_ekle',
                data:test_grup_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=listeyi-getir",function(e){
                        $('#test-grup-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });

    </script>

<?php }

else if($islem=="modal-test-grup-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_test_grup=singularactive("lab_test_groups","id",$gelen_veri);?>

    <div class="mx-3">
        <form id="tanimli_test_grup_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_test_grup["id"];?>">

            <div class="col-12  mt-4 ">
                <div class="row">
                    <div class="col-4">
                        <label>Laboratuvar</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="lab_id" > <option selected disabled class="text-white bg-danger">Laboratuvar Seçiniz..</option> <?php $bolumgetir = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TANIM' and lab_definitions.status='1'"); foreach ($bolumgetir as $value){ ?>
                                <option <?php if($lab_test_grup["lab_id"]==$value["id"]){ echo "selected"; } ?> value="<?php echo $value["id"]; ?>" ><?php echo $value['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Kodu</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_code" id="group_code" value="<?php echo $lab_test_grup["group_code"];?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_name" id="group_name" value="<?php echo $lab_test_grup["group_name"];?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Rapor Tipi</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="report_type" id="report_type"> <option selected disabled class="text-white bg-danger">Rapor Tipi Seçiniz..</option> <?php $bolumgetir = verilericoklucek("select * from lab_definitions where definition_type='TEST_GRUP_RAPOR_TURU' and lab_definitions.status='1'"); foreach ($bolumgetir as $item){ ?>
                                <option  <?php if($lab_test_grup["report_type"]==$item["id"]){ echo "selected"; } ?>  value="<?php echo $item["id"]; ?>" ><?php echo $item['definition_name']; ?></option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Üst Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_up_text" id="group_up_text" value="<?php echo $lab_test_grup["group_up_text"];?>">
                    </div>
                </div>


                <div class="row mt-1">
                    <div class="col-4">
                        <label>Grup Alt Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="group_sub_text" id="group_sub_text" value="<?php echo $lab_test_grup["group_sub_text"];?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Barkod Metni</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="barcode_text" id="barcode_text" value="<?php echo $lab_test_grup["barcode_text"];?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Açıklama</label>
                    </div>
                    <div class="col-8">
                        <textarea class="form-control" name="group_explain" id="group_explain"><?php echo $lab_test_grup["group_explain"];?></textarea>
                    </div>
                </div>

            </div>

        </form>



        <div class="mt-4"></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-test-grup-tanim-duzenle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>

        $("body").off("click", "#btn-test-grup-tanim-duzenle").on("click", "#btn-test-grup-tanim-duzenle", function(e){
            var tanimli_test_grup_duzenle_form = $("#tanimli_test_grup_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=sql_test_grup_tanimi_duzenle',
                data:tanimli_test_grup_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h45').window('close');
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/testgruptanimla.php?islem=listeyi-getir",function(e){
                           $('#test-grup-tanim').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h45').window('close');
        });

    </script>
<?php } ?>


