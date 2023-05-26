<?php
include "../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$islem=$_GET['islem'];

if ($islem=="radekle"){
    if($_POST) {
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $_POST['status'] = 1;
        $_POST['unit_type'] = 3;

        $sql = direktekle("units",$_POST);
        var_dump($sql);
        if ($sql == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }
    }

}elseif ($islem=="radupdate"){
    if($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];
        $id=$_POST['id'];
        unset($_POST['id']);

        $sql = direktguncelle("units","id",$id,$_POST);
        var_dump($sql);
        if ($sql == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }

    }
}elseif ($islem=="radsilme"){
    if ($_POST){
        $id=$_POST['id'];
        unset($_POST['id']);
        $detay=$_POST['delete_detail'];
        $silme=$_SESSION["id"];
        $tarih=$_POST['delete_datetime'];

        $sql=canceldetail('units','id',$id,$detay,$silme,$tarih);
        var_dump($sql);
        if ($sql == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }
    }
}elseif($islem=="rad-aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('units','id',$id,$date,$user);
    //var_dump($sql);
    if ($sql == 1) { ?>
        <script>
            alertify.success('Aktifleştirme Başarılı');
        </script>

    <?php } else { ?>
        <script>
            alertify.error('Işlem Başarısız');
        </script>
    <?php }
}
elseif ($islem=="listeyi-getir"){ ?>

        <div class="radyoloji-tanim">
    <div class="card">


        <div class="card-body bg-white">
            <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="rad-table">
                <thead class="table-light">
                <tr>
                    <th >Birim No</th>
                    <th >Hastane</th>
                    <th >Bina</th>
                    <th >Birim Adı</th>
                    <th >Durum</th>
                    <th >İşlem</th>
                </tr>
                </thead>
                <tbody>

                <?php  $say=0;
                $sql = verilericoklucek("select department_name,hospital_name,building_name,units.id as birimid,units.status as durum from units 
                inner join hospital on units.hospital_id = hospital.id 
                inner join hospital_building on units.hospital_buildingid = hospital_building.id where unit_type=3");
                foreach ($sql as $row) { ?>
                    <tr>
                        <td><?php echo $row['birimid'] ?></td>
                        <td><?php echo $row['hospital_name'] ?></td>
                        <td ><?php echo $row["building_name"] ?></td>
                        <td><?php echo $row["department_name"] ?></td>
                        <td align="center" title="Durum" <?php if ($row['durum']){ ?>rad-id="<?php echo $row["birimid"]; ?>" data-bs-target="#modal-getir"  data-bs-toggle="modal" class='radguncelle' <?php } ?> ><?php if($row["durum"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center"><i class="fa fa-pen-to-square radguncelle mx-1" title="Düzenle"<?php if ($row['durum']){ ?> rad-id="<?php echo $row["birimid"]; ?>"   data-bs-target="#radyoloji-modal" data-bs-toggle="modal"  <?php } ?>alt="icon"></i><?php if($row['durum']=='0'){ ?><i class="fa-solid fa-recycle radaktif" title="Aktif Et" id="<?php echo $row["birimid"]; ?>" alt="icon" ></i><?php }else{ ?><i class="fa fa-trash raddeletemodal" title="İptal" rad-id="<?php echo $row["birimid"]; ?>" alt="icon"></i><?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>

<div class="modal fade" id="radyoloji-modal" aria-hidden="true" style="margin-top: 85px !important;">
    <div class="modal-dialog" id="radyoloji-modal-icerik">
    </div>
</div>

    <script type="text/javascript">
        $('#rad-table').DataTable({
            "responsive": true,
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [{
                    text: 'Radyoloji Birim Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#radyoloji-modal",
                    },

                    action: function ( e, dt, button, config ) {
                        var secilen=$(".btntanimla-dom").attr('id');
                        $.get( "ajax/tanimlar/radyolojibirimtanimla.php?islem=modal-icerik",function(get){
                            $('#radyoloji-modal-icerik').html(get);
                        });
                    }
                }],

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });


        $("body").off("click", ".radguncelle").on("click", ".radguncelle", function(e){
            var getir = $(this).attr('rad-id');
            $.get( "ajax/tanimlar/radyolojibirimtanimla.php?islem=modal-icerik", { getir:getir },function(getveri){
                $('#radyoloji-modal-icerik').html(getveri);
            });
        });

        $("body").off("click",".radaktif").on("click",".radaktif", function (e) {
            var getir = $(this).attr('id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/radyolojibirimtanimla.php?islem=rad-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.radyoloji-tanim:first').load("ajax/tanimlar/radyolojibirimtanimla.php?islem=listeyi-getir");
                }
            });
        });

        $("body").off("click", ".raddeletemodal").on("click", ".raddeletemodal", function(e){
            var id = $(this).attr('rad-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Birim Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/radyolojibirimtanimla.php?islem=radsilme',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/radyolojibirimtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.radyoloji-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({radels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"rad Silme İşlemini Onayla"});
        });
    </script>

<?php }elseif ($islem=="modal-icerik"){
    $rad=singular("units","id",$_GET["getir"]);?>
    <div class="modal-dialog"  >
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php if ($_GET['getir']){ ?>
                    <h4 class="modal-title ">Radyoloji Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Radyoloji Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-radel="Close"></button>
            </div>
            <form   id="formrad" action="javascript:void(0);" >
                <div class="modal-body">

                            <div class="row">
                                <div class="col-2">
                                    <label class="form-label">Hastane:</label>
                                </div>
                                <div class="col-10">
                                    <select class="form-select" name="hospital_id" id="hastane">
                                        <option class="bg-danger text-white" disabled selected>Hastane Seçiniz</option>
                                        <?php  $sql= verilericoklucek("SELECT * FROM hospital WHERE status!='0'");
                                        foreach ($sql as $rowa) { ?>
                                            <option value="<?php echo $rowa["id"]; ?>" <?php if($rad['hospital_id'] == $rowa['id']) echo"selected"; ?>><?php echo $rowa["hospital_name"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>

                        <div class="row mt-1">
                                <div class="col-2">
                                    <label class="form-label">Bina:</label>
                                </div>
                            <div class="col-10">
                                    <select class="form-select" name="hospital_buildingid" id="bina">
                                        <option value="">Bina Seçiniz:</option>
                                        <?php  $hastaneid=$rad['hospital_id'];
                                        if ($hastaneid){
                                            $sql = verilericoklucek("select * from hospital_building where hospital_id=$hastaneid and status='1' order by building_name");
                                            foreach ($sql as $rowa) {?>
                                                <option value="<?php echo $rowa["id"]; ?>" <?php if($rad['hospital_buildingid'] == $rowa['id']) echo"selected"; ?>><?php echo $rowa["building_name"]; ?></option>
                                            <?php } } ?>
                                    </select>
                                </div>
                            </div>

                        <div class="row mt-1">
                            <div class="col-2">
                                <label for="example-text-input" class="col-form-radel">Birim Adı</label>
                            </div>
                            <div class="col-10">
                                <input class="form-control" type="text" name="department_name" value="<?php echo $rad["department_name"] ?>" id="example-text-input">
                                <?php if ($_GET['getir']) { ?>
                                    <input class="form-control" type="hidden" name="id" value="<?php echo $_GET['getir']; ?>">
                                <?php } ?>
                            </div>
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success  radupdate btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn  radinsert btn-sm btn-success" id="brans-kaydet" data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>

            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#hastane").change(function () {
                var binano = $(this).val();
                $.ajax({
                    type: "post",
                    url: "ajax/tanimlar/radyolojibirimtanimla.php?islem=hastaneidgetir",
                    data: {"binano": binano},
                    success: function (e) {
                        $("#bina").html(e);
                    }
                })
            })

        });

        $(document).ready(function(){
            $("body").off("click", ".radupdate").on("click", ".radupdate", function(e){
                var gonderilenform = $("#formrad").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/radyolojibirimtanimla.php?islem=radupdate',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/radyolojibirimtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.radyoloji-tanim:first').html(e);
                        });
                    }
                });
            });
            $("body").off("click", ".radinsert").on("click", ".radinsert", function(e){
                var gonderilenform = $("#formrad").serialize();
                document.getElementById("formrad").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/radyolojibirimtanimla.php?islem=radekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/radyolojibirimtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.radyoloji-tanim:first').html(e);
                        });
                    }
                });
            });

        });

    </script>

<?php }
elseif($islem=="hastaneidgetir"){
    $binano=$_POST['binano'];
    echo " <option value=''>bina seçiniz..</option>";
    $bolumgetir = "select * from hospital_building where hospital_id=$binano and status='1' order by building_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="'.$value['id'].'">'.$value['building_name'].'</option>';

    }
}



?>
