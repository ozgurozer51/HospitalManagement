<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_ornek_tipi_tanim_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_definitions",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Örnek Tipi Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Örnek Tipi Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_ornek_tipi_tanim_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_definitions", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Örnek Tipi Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Örnek Tipi Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_ornek_tipi_tanim_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_definitions", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Örnek Tipi Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Örnek Tipi Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_ornek_tipi_tanim_aktiflestir"){
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


    <div class="mt-2 mx-2" id="ornek-tipi-tanim">


                <script>
                    $('#ornek-tipi-tanimla-tab').DataTable( {

                        "responsive":true,
                        "scrollY": true,
                        "autoWidth": true,
                        "scrollX": true,
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                        },
                        "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        buttons: [
                            {
                                text: 'Örnek Tipi Tanımla',
                                className: 'btn btn-success btn-sm btn-kaydet',

                                action: function ( e, dt, button, config ) {
                                    // $.get("ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=modal-ornek-tipi-tanimla",function(get){
                                    //     $('#lab_modal_lg_icerik').html(get);
                                    // });

                                    $('.tanimlamalar_w40_h20').window('setTitle', 'Örnek Tipi Tanımla');
                                    $('.tanimlamalar_w40_h20').window('open');
                                    $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=modal-ornek-tipi-tanimla');
                                }
                            }
                        ],
                    });
                </script>

                <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="ornek-tipi-tanimla-tab">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Örnek Tipi</th>
                        <th>Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select * from lab_definitions where  definition_type='ORNEK_TIPI'");
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

            $('.tanimlamalar_w40_h20').window('setTitle', 'Örnek Tipi Tanımı Düzenle');
            $('.tanimlamalar_w40_h20').window('open');
            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=modal-ornek-tipi-tanim-duzenle&getir=' + getir + '');
        });

        $("body").off("click", ".ornek_tipi_tanim_sil").on("click", ".ornek_tipi_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Örnek Tipi Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=sql_ornek_tipi_tanim_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=listeyi-getir", function(getveri){
                                $('#ornek-tipi-tanim').html(getveri);
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
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Örnek Tipi Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".ornek_tipi_tanim_aktif").on("click",".ornek_tipi_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=sql_ornek_tipi_tanim_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=listeyi-getir", function(getveri){
                        $('#ornek-tipi-tanim').html(getveri);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-ornek-tipi-tanimla"){?>

    <div class="mx-3">
        <form id="ornek_tipi_tanimla_form" action="javascript:void(0);">

            <div class="col-12 row mt-4 ">
                <div class="col-4">
                    <label>Örnek Tipi</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="definition_name" id="definition_name">
                    <input type="text" class="form-control" hidden name="definition_type" id="definition_type" value="ORNEK_TIPI">
                </div>
            </div>

        </form>



        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-ornek-tipi-ekle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn-ornek-tipi-ekle").on("click", "#btn-ornek-tipi-ekle", function(e){
                var ornek_tipi_tanimla_form = $("#ornek_tipi_tanimla_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=sql_ornek_tipi_tanim_ekle',
                    data:ornek_tipi_tanimla_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=listeyi-getir", function(getveri){
                            $('#ornek-tipi-tanim').html(getveri);
                        });

                        $('.tanimlamalar_w40_h20').window('close');
                    }
                });
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h20').window('close');
        });
    </script>

<?php }

else if($islem=="modal-ornek-tipi-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_tanimlari=singularactive("lab_definitions","id",$gelen_veri);?>


    <div class="mx-3">
        <form id="ornek_tipi_tanim_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_tanimlari["id"];?>">

            <div class="col-12 row mt-4 ">
                <div class="col-4">
                    <label>Örnek Tipi</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="definition_name" id="definition_name" value="<?php echo $lab_tanimlari["definition_name"];?>">
                </div>
            </div>

        </form>



        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-ornek-tipi-tanim-duzenle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>

        $("body").off("click", "#btn-ornek-tipi-tanim-duzenle").on("click", "#btn-ornek-tipi-tanim-duzenle", function(e){
            var ornek_tipi_tanim_duzenle_form = $("#ornek_tipi_tanim_duzenle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=sql_ornek_tipi_tanim_duzenle',
                data:ornek_tipi_tanim_duzenle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/ornektipitanimla.php?islem=listeyi-getir", function(getveri){
                        $('#ornek-tipi-tanim').html(getveri);
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


