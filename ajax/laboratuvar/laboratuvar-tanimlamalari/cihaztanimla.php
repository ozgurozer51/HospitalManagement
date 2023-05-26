<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_cihaz_tanimi_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_devices",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Cihaz Tanımı Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaz Tanımı Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_cihaz_tanimi_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_devices", "id", $id, $_POST);
    if ($sql == 1){?>
        <script>
            alertify.success('Cihaz Tanımı Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaz Tanımı Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_cihaz_tanimi_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_devices", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Cihaz Tanımı Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Cihaz Tanımı Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_cihaz_tanimi_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_devices','id',$id,$date,$user);

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


    <div class="mx-2 mt-2" id="cihaz-tanim">

        <script>
                    $('#cihaz-tanimla-tab').DataTable( {
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
                                text: 'Cihaz Tanımla',
                                className: 'btn btn-success btn-sm btn-kaydet',

                                action: function ( e, dt, button, config ) {
                                    $('.tanimlamalar_w40_h35').window('setTitle', 'Cihaz Tanımla');
                                    $('.tanimlamalar_w40_h35').window('open');
                                    $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=modal-cihaz-tanimla');
                                }
                            }
                        ],
                    } );
                </script>

        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="cihaz-tanimla-tab">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cihaz Adı</th>
                        <th>Cihaz Modeli</th>
                        <th>Çalışma Şekli</th>
                        <th>Kablo Tipi</th>
                        <th>Seri Numarası</th>
                        <th>Firma Adı</th>
                        <th>Cihaz Durumu</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql = verilericoklucek("select * from lab_devices");
                    foreach ((array)$sql as $row){ ?>
                        <tr style="cursor: pointer;">
                            <td><?php echo $row["id"]; ?> </td>
                            <td><?php echo $row["device_name"]; ?> </td >
                            <td><?php echo $row["device_model"]; ?> </td >
                            <td><?php echo labtanimgetirid($row["mode_of_study"]); ?> </td >
                            <td><?php echo $row["cable_type"]; ?> </td >
                            <td><?php echo $row["serial_number"]; ?> </td >
                            <td><?php echo $row["company_name"]; ?> </td >
                            <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                            <td align="center">
                                <i class="fa fa-pen-to-square cihaz_tanim_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>"></i>
                                <?php if($row['status']=='0'){ ?>
                                    <i class="fa-solid fa-recycle cihaz_tanim_aktif" title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>
                                <?php }else{ ?>
                                    <i class="fa fa-trash cihaz_tanim_sil" title="İptal" id="<?php echo $row["id"]; ?>" alt="icon"></i>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    </div>

    <script>
        $("body").off("click", ".cihaz_tanim_duzenle").on("click", ".cihaz_tanim_duzenle", function(e){
            var getir = $(this).attr('id');

            $('.tanimlamalar_w40_h35').window('setTitle', 'Cihaz Tanımı Tanımla');
            $('.tanimlamalar_w40_h35').window('open');
            $('.tanimlamalar_w40_h35').window('refresh', 'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=modal-cihaz-tanim-duzenle&getir=' + getir + '');
        });

        $("body").off("click", ".cihaz_tanim_sil").on("click", ".cihaz_tanim_sil", function(e){
            var id = $(this).attr("id");

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Cihaz Tanımı Silme Nedeni..'></textarea>", function(){

                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=sql_cihaz_tanimi_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=listeyi-getir",function(e){
                                $('#cihaz-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".cihaz_tanim_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Cihaz Tanımı Silme İşlemini Onayla"});
        });

        $("body").off("click",".cihaz_tanim_aktif").on("click",".cihaz_tanim_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=sql_cihaz_tanimi_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#cihaz-tanim:first').load("ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=listeyi-getir");
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-cihaz-tanimla"){?>

    <div class="mx-3">
        <form id="cihaz_tanimla_form" action="javascript:void(0);">

            <div class="col-12  mt-4 ">
                <div class="row">
                    <div class="col-4">
                        <label>Cihaz Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="device_name" id="device_name">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Cihaz Modeli</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="device_model" id="device_model">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Çalışma Şekli</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="mode_of_study" id="mode_of_study" >
                            <option selected disabled class="text-white bg-danger">Çalışma şekli seçiniz.</option>
                            <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='CIHAZ_CALISMA_SEKLI' and lab_definitions.status='1'");
                            foreach ($sql as $item){ ?>
                                <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Kablo Tipi</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="cable_type" id="cable_type">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Seri Numarası</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="serial_number" id="serial_number">
                    </div>
                </div>


                <div class="row mt-1">
                    <div class="col-4">
                        <label>Firma Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="company_name" id="company_name">
                    </div>
                </div>
            </div>

        </form>



        <div class="mt-4 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaz-tanimi-ekle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>

        $("body").off("click", "#btn-cihaz-tanimi-ekle").on("click", "#btn-cihaz-tanimi-ekle", function(e){
            var cihaz_tanimla_form = $("#cihaz_tanimla_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=sql_cihaz_tanimi_ekle',
                data:cihaz_tanimla_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimlamalar_w40_h35').window('close');
                       $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=listeyi-getir",function(e){
                           $('#cihaz-tanim').html(e);
                       });
                }
            });
        });
        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });

    </script>

<?php }

else if($islem=="modal-cihaz-tanim-duzenle"){
    $gelen_veri = $_GET['getir'];
    $lab_cihazlari=singularactive("lab_devices","id",$gelen_veri);?>

    <div class="mx-3">
        <form id="tanimli_cihaz_duzenle_form" action="javascript:void(0);">

            <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $lab_cihazlari["id"];?>">

            <div class="col-12  mt-4 ">
                <div class="row">
                    <div class="col-4">
                        <label>Cihaz Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="device_name" id="device_name" value="<?php echo $lab_cihazlari["device_name"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Cihaz Modeli</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="device_model" id="device_model" value="<?php echo $lab_cihazlari["device_model"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Çalışma Şekli</label>
                    </div>
                    <div class="col-8">
                        <select class="form-select" name="mode_of_study" id="mode_of_study" >
                            <option selected disabled class="text-white bg-danger">Çalışma şekli seçiniz.</option>
                            <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='CIHAZ_CALISMA_SEKLI' and lab_definitions.status='1'");
                            foreach ($sql as $item){ ?>
                                <option <?php if($lab_cihazlari["mode_of_study"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Kablo Tipi</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="cable_type" id="cable_type" value="<?php echo $lab_cihazlari["cable_type"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <label>Seri Numarası</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="serial_number" id="serial_number" value="<?php echo $lab_cihazlari["serial_number"]; ?>">
                    </div>
                </div>


                <div class="row mt-1">
                    <div class="col-4">
                        <label>Firma Adı</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" name="company_name" id="company_name" value="<?php echo $lab_cihazlari["company_name"]; ?>">
                    </div>
                </div>
            </div>

        </form>



        <div class="mt-4 "></div>

        <div class="row" align="right">
            <div class="col-7"></div>
            <div class="col-5">
                <a class="easyui-linkbutton btn_window_kapat" data-options="iconCls:'icon-cancel'" style="width:80px">Kapat</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="width:150px" id="btn-cihaz-tanim-duzenle">Kaydet</a>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn-cihaz-tanim-duzenle").on("click", "#btn-cihaz-tanim-duzenle", function(e){
                var tanimli_cihaz_duzenle_form = $("#tanimli_cihaz_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=sql_cihaz_tanimi_duzenle ',
                    data:tanimli_cihaz_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('.tanimlamalar_w40_h35').window('close');
                        $.get( "ajax/laboratuvar/laboratuvar-tanimlamalari/cihaztanimla.php?islem=listeyi-getir",function(e){
                            $('#cihaz-tanim').html(e);
                        });
                    }
                });
            });
        });

        $("body").off("click", ".btn_window_kapat").on("click", ".btn_window_kapat", function(e){
            $('.tanimlamalar_w40_h35').window('close');
        });
    </script>
<?php } ?>


