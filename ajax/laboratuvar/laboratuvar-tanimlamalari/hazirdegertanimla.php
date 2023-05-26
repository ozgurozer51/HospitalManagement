<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_hazir_deger_tanim_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_definitions",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Hazır Değer Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Hazır Değer Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_hazir_deger_tanim_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_definitions", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Hazır Değer Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Hazır Değer Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_hazir_deger_tanim_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_definitions", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Hazır Değer Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Hazır Değer Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_hazir_deger_tanim_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_definitions','id',$id,$date,$user);

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

    <style>
        .modal-body {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>

    <div class="mt-2 mx-2" id="hazir-deger-tanim">

                <script>
                    $('#hazir-deger-tanimla-tab').DataTable( {
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
                                text: 'Hazır Değer Tanımla',
                                className: 'btn btn-success btn-sm btn-kaydet',

                                action: function ( e, dt, button, config ) {

                                    $('.tanimlamalar_w40_h20').window('setTitle', 'Hazır Değer Tanımla');
                                    $('.tanimlamalar_w40_h20').window('open');
                                    $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=modal-hazir-deger-tanimla');
                                }
                            }
                        ],
                    });
                </script>

                <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="hazir-deger-tanimla-tab">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hazır Değer</th>
                        <th>Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select * from lab_definitions where  definition_type='HAZIR_DEGER'");
                    foreach ((array)$sql as $row){ ?>
                        <tr style="cursor: pointer;"">
                        <td><?php echo $row["id"]; ?> </td>
                        <td><?php echo $row["definition_name"]; ?> </td >
                        <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square ornek_tipi_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle ornek_tipi_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash ornek_tipi_tanim_sil" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    </div>

    <script>
        $("body").off("click", ".ornek_tipi_tanim_duzenle").on("click", ".ornek_tipi_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h20').window('setTitle', 'Hazır Değer Tanımı Düzenle');
            $('.tanimlamalar_w40_h20').window('open');
            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=modal-hazir-deger-tanim-duzenle&getir='+ getir +'');
        });

        $("body").off("click", ".ornek_tipi_tanim_sil").on("click", ".ornek_tipi_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Hazır Değer Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=sql_hazir_deger_tanim_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=listeyi-getir", function(getveri){
                                $('#hazir-deger-tanim').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".ornek_tipi_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Hazır Değer Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".ornek_tipi_tanim_aktif").on("click",".ornek_tipi_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=sql_hazir_deger_tanim_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=listeyi-getir", function(getveri){
                        $('#hazir-deger-tanim').html(getveri);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-hazir-deger-tanimla"){?>

    <div class="mx-3">

        <div class="col-12 row mt-4">

            <div class="col-4">
                <label>Hazır Değer</label>
            </div>

            <div class="col-8">
                <form id="hazir_deger_tanimla_form">
                    <input type="text" class="form-control" id="definition_name" name="definition_name"/>
                    <input type="text" class="form-control" hidden id="definition_type" name="definition_type" value="HAZIR_DEGER" />
                </form>
            </div>

        </div>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_hazir_deger_tanimla">Kaydet</a>
            </div>
        </div>


    </div>


    <script>

        $("body").off("click", "#btn_hazir_deger_tanimla").on("click", "#btn_hazir_deger_tanimla", function(e){

            // var definition_name =$("#definition_name").val();
            // var definition_type =$("#definition_type").val();
            // alert(definition_name+' '+definition_type)

            var hazir_deger_tanimla = $("#hazir_deger_tanimla_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=sql_hazir_deger_tanim_ekle',
                data:hazir_deger_tanimla,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=listeyi-getir", function(getveri){
                        $('#hazir-deger-tanim').html(getveri);
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

else if($islem=="modal-hazir-deger-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_tanimlari=singularactive("lab_definitions","id",$gelen_veri);?>

    <div class="mx-3">

        <div class="col-12 row mt-4">

            <div class="col-4">
                <label>Hazır Değer</label>
            </div>

            <div class="col-8">
                <form id="hazir_deger_tanimi_duzenle_form">
                    <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_tanimlari["id"];?>">
                    <input type="text" class="form-control" title="Örn: Yetersi numune" name="definition_name" id="definition_name" value="<?php echo $lab_tanimlari["definition_name"];?>">
                </form>
            </div>

        </div>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_hazir_deger_tanimi_duzenle">Kaydet</a>
            </div>
        </div>


    </div>




    <script>

        $("body").off("click", "#btn_hazir_deger_tanimi_duzenle").on("click", "#btn_hazir_deger_tanimi_duzenle", function(e){

            var hazir_deger_tanimi_duzenle = $("#hazir_deger_tanimi_duzenle_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=sql_hazir_deger_tanim_duzenle',
                data:hazir_deger_tanimi_duzenle,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/hazirdegertanimla.php?islem=listeyi-getir", function(getveri){
                        $('#hazir-deger-tanim').html(getveri);
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


