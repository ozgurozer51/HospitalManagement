<?php include "../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="sql_laboratuvar_ekle"){
    $_POST["insert_datetime"] = $simdikitarih;
    $_POST["insert_userid"] = $kullanici_id;
    $sql= direktekle("lab_unit",$_POST);
    if ($sql == 1) {?>
        <script>
            alertify.success('Laboratuvar Ekleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Laboratuvar Ekleme İşlemi Başarısız');
        </script>
    <?php }
}


else if ($islem=="sql_laboratuvar_duzenle"){
    $id=$_POST['id'];
    unset($_POST['id']);

    $_POST["update_datetime"] = $simdikitarih;
    $_POST["update_userid"] = $kullanici_id;

    $sql=direktguncelle("lab_unit", "id", $id, $_POST);

    if ($sql == 1){?>
        <script>
            alertify.success('Laboratuvar Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Laboratuvar Güncelleme İşlemi Başarısız');
        </script>
    <?php }
}

else if($islem=="sql_laboratuvar_sil") {
    $id= $_POST["id"];
    $tarih = $_POST["delete_datetime"]=$simdikitarih;
    $kullanici = $_POST['delete_userid']=$kullanici_id;
    $detay = $_POST['delete_detail'];

    $sql = canceldetail("lab_unit", "id", $id, $detay, $kullanici, $tarih);

    if ($sql == 1){?>
        <script>
            alertify.success('Laboratuvar Silme İşlemi Başarılı');
        </script>
    <?php   } else {?>
        <script>
            alertify.error('Laboratuvar Silme İşlemi Başarısız');
        </script>
    <?php }
}

else if ($islem=="sql_laboratuvar_aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('lab_unit','id',$id,$date,$user);
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

    <div class="card">
        <div class="card-body bg-white">

            <div id="laboratuvar-tanim">
                <script>
                    $('#laboratuvartanimlatab').DataTable( {
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
                                text: 'Laboratuvar Ekle',
                                className: 'btn btn-success btn-sm btn-kaydet',
                                attr:  {
                                    'data-bs-toggle':"modal",
                                    'data-bs-target':"#laboratuvar-tanimla-modal",
                                },
                                action: function ( e, dt, button, config ) {
                                    $.get("ajax/tanimlar/laboratuvarbirimtanimla.php?islem=modal-laboratuvar-tanimla",function(get){
                                        $('#laboratuvar-tanimla-modal-icerik').html(get);
                                    });
                                }
                            }
                        ],
                    } );
                </script>

                <table id="laboratuvartanimlatab" class="table table-striped table-bordered w-100 display nawrap" >
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Labaratuvar Adı</th>
                        <th>Grup</th>
                        <th>Tür</th>
                        <th>Branş Grup</th>
                        <th>USS Klinik</th>
                        <th>Uzmanlık</th>
                        <th>Laboratuvar Durum</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql=verilericoklucek("select * from lab_unit");
                    foreach ((array)$sql as $row ) {?>
                        <tr style="cursor: pointer;">
                        <td><?php echo $row["id"]; ?> </td>
                        <td><?php echo mb_strtoupper($row["lab_name"]); ?> </td >
                        <td><?php $lab_grup= tablogetir("lab_definitions",$row["lab_group"]); echo mb_strtoupper($lab_grup['definition_name']); ?></td >
                        <td><?php $lab_turu= tablogetir("lab_definitions",$row["lab_type"]); echo mb_strtoupper($lab_turu["definition_name"]); ?></td >
                        <td><?php $brans_grup= tablogetir("branch",$row["branch_group"]); echo mb_strtoupper($brans_grup["branch_name"]); ?></td >
                        <td><?php $uss_klinik= tablogetir("branch",$row["uss_clinicid"]); echo mb_strtoupper($uss_klinik["branch_name"]); ?></td >
                        <td><?php $uzmanlik= tablogetir("branch",$row["expertise_id"]); echo mb_strtoupper($uzmanlik["branch_code"]." - ".$uzmanlik["branch_name"]); ?></td >
                        <td align="center"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center">
                            <i class="fa fa-pen-to-square laboratuvar_duzenle" title="Düzenle" alt="icon" id="<?php echo $row["id"]; ?>" data-bs-target="#laboratuvar-duzenle-modal" data-bs-toggle="modal"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle laboratuvar_aktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash laboratuvar_sil" id="laboratuvar_sil_701" title="İptal" data-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="laboratuvar-tanimla-modal"  aria-hidden="true">
        <div class="modal-dialog modal-lg" id='laboratuvar-tanimla-modal-icerik'> </div>
    </div>

    <div class="modal fade" id="laboratuvar-duzenle-modal"  aria-hidden="true">
        <div class="modal-dialog modal-lg" id='laboratuvar-duzenle-modal-icerik'> </div>
    </div>

    <script>
        $("body").off("click", ".laboratuvar_duzenle").on("click", ".laboratuvar_duzenle", function(e){
            var getir = $(this).attr('id');
            $.get("ajax/tanimlar/laboratuvarbirimtanimla.php?islem=modal-laboratuvar-duzenle",{getir:getir},function(getVeri){
                $('#laboratuvar-duzenle-modal-icerik').html(getVeri);
            });
        });

        $("body").off("click", "#laboratuvar_sil_701").on("click", "#laboratuvar_sil_701", function(e){

            var id = $(this).attr("data-id");
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div><textarea class='form-control'  id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Laboratuvar Silme Nedeni..'></textarea>", function(){


                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                if (delete_detail != '') {
                    $.ajax({
                        type: 'POST',
                        url:'ajax/tanimlar/laboratuvarbirimtanimla.php?islem=sql_laboratuvar_sil',
                        data: {
                            id,
                            delete_detail,
                            delete_datetime
                        },
                        success: function (e) {
                            $("#sonucyaz").html(e);
                            $.get( "ajax/tanimlar/laboratuvarbirimtanimla.php?islem=listeyi-getir",function(e){
                                $('#laboratuvar-tanim').html(e);
                            });
                            $('.alertify').remove();
                        }
                    });
                } else if (delete_detail == '') {
                    alertify.warning("Silme Nedeni Giriniz.");
                    setTimeout(() => {
                        $(".laboratuvar_sil").trigger("click");
                    }, "10")
                }
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set('movable', false,{labels:{ok: "Onayla", cancel: "Vazgeç"  }}).set({title:"Laboratuvar Silme İşlemini Onayla"});
        });

        $("body").off("click",".laboratuvar_aktif").on("click",".laboratuvar_aktif", function (e) {
            var getir = $(this).attr('id');

            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/laboratuvarbirimtanimla.php?islem=sql_laboratuvar_aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('#laboratuvar-tanim:first').load("ajax/tanimlar/laboratuvarbirimtanimla.php?islem=listeyi-getir");
                }
            });
        });
    </script>

<?php }

else if($islem=="modal-laboratuvar-tanimla"){?>
    <style>
        .form-label{
            color: black !important;
        }
    </style>
    <div class="modal-dialog"  >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <h4 class="modal-title ">Laboratuvar Ekle</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="laboratuvar_ekle_form" action="javascript:void(0);">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Labaratuvar Adı</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="lab_name" id="lab_name">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row ">
                            <label class="col-sm-3 col-form-label">Grup</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="lab_group" id="lab_group" >
                                    <option selected disabled class="text-white bg-danger">Laboratuvar grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TANIM' and lab_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tür</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="lab_type" id="lab_type" >
                                    <option selected disabled class="text-white bg-danger">Laboratuvar türü seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TURU' and lab_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Branş Grup</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="branch_group" id="branch_group" >
                                    <option selected disabled class="text-white bg-danger">Branş grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">USS Klinik</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="uss_clinicid" id="uss_clinicid" >
                                    <option selected disabled class="text-white bg-danger">USS Klinik kodu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Uzmanlık</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="expertise_id" id="expertise_id" >
                                    <option selected disabled class="text-white bg-danger">Uzmanlık seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option value="<?php echo $item['id']; ?>"><?php echo $item['branch_code']." - ".mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm"  data-bs-dismiss="modal">Kapat</button>
                <button id="btn-laboratuvar-ekle" type="button" class="btn btn-success btn-sm"  data-bs-dismiss="modal">Kaydet</button>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn-laboratuvar-ekle").on("click", "#btn-laboratuvar-ekle", function(e){
            var laboratuvar_ekle_form = $("#laboratuvar_ekle_form").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/laboratuvarbirimtanimla.php?islem=sql_laboratuvar_ekle',
                data:laboratuvar_ekle_form,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/tanimlar/laboratuvarbirimtanimla.php?islem=listeyi-getir",function(e){
                        $('#laboratuvar-tanim').html(e);
                    });
                }
            });
            });
        });
    </script>

<?php }

else if($islem=="modal-laboratuvar-duzenle"){
    $gelen_veri = $_GET['getir'];
    $laboratuvarbirim=singularactive("lab_unit","id",$gelen_veri);?>

    <style>
        .form-label{
            color: black !important;
        }
    </style>

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header text-white" style="background-color: #3F72AF">
                <h4 class="modal-title ">Laboratuvar Düzenle</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="laboratuvar_duzenle_form" action="javascript:void(0);">

                <div class="modal-body">
                    <input type="text" hidden class="form-control" name="id" id="id" value="<?php echo $laboratuvarbirim["id"];?>">

                    <div class="row">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Labaratuvar Adı</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="lab_name" id="lab_name" value="<?php echo mb_strtoupper($laboratuvarbirim["lab_name"]);?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row ">
                            <label class="col-sm-3 col-form-label">Grup</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="lab_group" id="lab_group" >
                                    <option selected disabled class="text-white bg-danger">Laboratuvar grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TANIM' and lab_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($laboratuvarbirim["lab_group"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tür</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="lab_type" id="lab_type" >
                                    <option selected disabled class="text-white bg-danger">Laboratuvar türü seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from lab_definitions where definition_type='LABORATUVAR_TURU' and lab_definitions.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($laboratuvarbirim["lab_type"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['definition_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Branş Grup</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="branch_group" id="branch_group" >
                                    <option selected disabled class="text-white bg-danger">Branş grubu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($laboratuvarbirim["branch_group"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">USS Klinik</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="uss_clinicid" id="uss_clinicid" >
                                    <option selected disabled class="text-white bg-danger">USS Klinik kodu seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($laboratuvarbirim["uss_clinicid"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Uzmanlık</label>
                            <div class="col-sm-7">
                                <select class="form-select" name="expertise_id" id="expertise_id" >
                                    <option selected disabled class="text-white bg-danger">Uzmanlık seçiniz.</option>
                                    <?php $sql = verilericoklucek("select * from branch where branch.status='1'");
                                    foreach ($sql as $item){ ?>
                                        <option <?php if($laboratuvarbirim["expertise_id"]==$item["id"]){ echo "selected"; } ?> value="<?php echo $item['id']; ?>"><?php echo $item['branch_code']." - ".mb_strtoupper($item['branch_name']); ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>

            </form>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm"  data-bs-dismiss="modal">Kapat</button>
                <button id="btn-laboratuvar-duzenle" type="button" class="btn btn-success btn-sm"  data-bs-dismiss="modal">Kaydet</button>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", "#btn-laboratuvar-duzenle").on("click", "#btn-laboratuvar-duzenle", function(e){
                var laboratuvar_duzenle_form = $("#laboratuvar_duzenle_form").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/laboratuvarbirimtanimla.php?islem=sql_laboratuvar_duzenle',
                    data:laboratuvar_duzenle_form,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/tanimlar/laboratuvarbirimtanimla.php?islem=listeyi-getir",function(e){
                            $('#laboratuvar-tanim').html(e);
                        });
                    }
                });
            });
        });
    </script>
<?php } ?>


