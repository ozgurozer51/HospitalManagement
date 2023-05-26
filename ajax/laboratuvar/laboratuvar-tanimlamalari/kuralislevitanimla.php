<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_kural_islevi_tanim_ekle"){
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


else if ($islem=="sql_kural_islevi_tanim_duzenle"){
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

else if($islem=="sql_kural_islevi_tanim_sil") {
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

else if ($islem=="sql_kural_islevi_tanim_aktiflestir"){
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

    <div class="mx-2 mt-2" id="kural-islevi-tanim">



                <script>
                    $('#kural-islevi-tanimla-tab').DataTable( {
                        "processing": true,
                        "scrollY": true,
                        "scrollX": true,
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                        },
                        "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                            "<'row'<'col-sm-12'tr>>" +
                            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        buttons: [
                            {
                                text: 'Kural İşlevi Tanımla',
                                className: 'btn btn-success btn-sm btn-kaydet',

                                action: function ( e, dt, button, config ) {

                                    $('.tanimlamalar_w40_h20').window('setTitle', 'Kural İşlevi Tanımla');
                                    $('.tanimlamalar_w40_h20').window('open');
                                    $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=modal-kural-islevi-tanimla');
                                }
                            }
                        ],
                    });
                </script>

                <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="kural-islevi-tanimla-tab">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kural İşlevi</th>
                        <th>Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select * from lab_definitions where  definition_type='KURAL_ISLEVI'");
                    foreach ((array)$sql as $row){ ?>
                        <tr style="cursor: pointer;"">
                        <td><?php echo $row["id"]; ?> </td>
                        <td><?php echo $row["definition_name"]; ?> </td >
                        <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square kural_islevi_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>" data-bs-target="lab_modal_lg" data-bs-toggle="modal"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle kural_islevi_tanim_aktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>
                            <?php }else{ ?>
                                <i class="fa fa-trash kural_islevi_tanim_sil" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>
                            <?php } ?>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    </div>

    <script>
        $("body").off("click", ".kural_islevi_tanim_duzenle").on("click", ".kural_islevi_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h20').window('setTitle', 'Kural İşlevi Tanımı Düzenle');
            $('.tanimlamalar_w40_h20').window('open');
            $('.tanimlamalar_w40_h20').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=modal-kural-islevi-duzenle&getir='+ getir +'');
        });

        $("body").off("click", ".kural_islevi_tanim_sil").on("click", ".kural_islevi_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kural İşlevi Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=sql_kural_islevi_tanim_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=listeyi-getir", function(getveri){
                                $('#kural-islevi-tanim').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".kural_islevi_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Kural İşlevi Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".kural_islevi_tanim_aktif").on("click",".kural_islevi_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=sql_kural_islevi_tanim_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=listeyi-getir", function(getveri){
                        $('#kural-islevi-tanim').html(getveri);
                    });
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-kural-islevi-tanimla"){?>

    <div class="mx-3">

        <div class="col-12 row mt-4">

            <div class="col-4">
                <label>Kural İşlevi</label>
            </div>

            <div class="col-8">
                <form id="kural_islevi_tanimla_form">
                    <input type="text" class="form-control" id="definition_name" name="definition_name"/>
                    <input type="text" class="form-control" hidden id="definition_type" name="definition_type" value="KURAL_ISLEVI" />
                </form>
            </div>

        </div>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_kural_islevi_ekle">Kaydet</a>
            </div>
        </div>


    </div>


    <script>

        $("body").off("click", "#btn_kural_islevi_ekle").on("click", "#btn_kural_islevi_ekle", function(e){

            // var definition_name =$("#definition_name").val();
            // var definition_type =$("#definition_type").val();
            // alert(definition_name+' '+definition_type)

            var kural_islevi_tanimla = $("#kural_islevi_tanimla_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=sql_kural_islevi_tanim_ekle',
                data:kural_islevi_tanimla,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=listeyi-getir", function(getveri){
                        $('#kural-islevi-tanim').html(getveri);
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

else if($islem=="modal-kural-islevi-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_tanimlari=singularactive("lab_definitions","id",$gelen_veri);?>

    <div class="mx-3">

        <div class="col-12 row mt-4">

            <div class="col-4">
                <label>Kural İşlevi</label>
            </div>

            <div class="col-8">
                <form id="kural_islevi_tanim_duzenle_form">
                    <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_tanimlari["id"];?>">
                    <input type="text" class="form-control" name="definition_name" id="definition_name" value="<?php echo $lab_tanimlari["definition_name"];?>">
                </form>
            </div>

        </div>

        <div class="mt-5 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn_kural_islevi_duzenle">Kaydet</a>
            </div>
        </div>


    </div>




    <script>

        $("body").off("click", "#btn_kural_islevi_duzenle").on("click", "#btn_kural_islevi_duzenle", function(e){

            var kural_islevi_tanim_duzenle = $("#kural_islevi_tanim_duzenle_form").serialize();

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=sql_kural_islevi_tanim_duzenle',
                data:kural_islevi_tanim_duzenle,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/kuralislevitanimla.php?islem=listeyi-getir", function(getveri){
                        $('#kural-islevi-tanim').html(getveri);
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


