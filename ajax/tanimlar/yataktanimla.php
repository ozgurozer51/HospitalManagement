<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$islem=$_GET['islem'];

if ($islem=="yatakekle"){
        $yatissekle = direktekle("hospital_bed",$_POST);
        if ($yatissekle == 1) { ?>
            <script>
                alertify.success('Yatak Ekleme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Yatak Ekleme Başarısız');
            </script>
        <?php }

}elseif ($islem=="yatakupdate"){
        $id=$_POST['id'];
        unset($_POST['id']);
        $sql = direktguncelle("hospital_bed","id",$id,$_POST);
        if ($sql == 1) { ?>
            <script>
                alertify.success('Yatak Güncelleme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('İşlem Başarısız');
            </script>
        <?php }

}elseif ($islem=="yataksilme"){
        $id=$_POST['id'];
        unset($_POST['id']);
        $detay=$_POST['delete_detail'];

        $sql=canceldetail("hospital_bed","id",$id,$detay);
        if ($sql == 1) { ?>
            <script>
                alertify.success('Yatak Silme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Yatak Silme Başarısız');
            </script>
        <?php }

}elseif($islem=="yatak-aktiflestir"){
    $id = $_POST['getir'];
    $sql = backcancel('hospital_bed','id',$id);
    if ($sql == 1) { ?>
        <script>
            alertify.success('Aktifleştirme Başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.error('Işlem Başarısız');
        </script>
    <?php }

} elseif ($islem=="listeyi-getir"){ ?>
        <div class="yatak-tanim">
<div class="card">

    <div class="card-body bg-white">

    <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="examplSe1">

        <thead class="table-light">
        <tr>
            <th >Yatak No</th>
            <th >Yatak Adı</th>
            <th >Yatak Türü</th>
            <th >Oda Adı</th>
            <th >Kat Adı</th>
            <th >Bina Adı</th>
            <th >Durum</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>

        <?php $hello = verilericoklucek("SELECT hospital_bed.id as yatakid,
                                                hospital_bed.status as durum,
                                                hospital_building.building_name as bina,
                                   hospital_room.*,hospital_bed.*,hospital_floor.* FROM hospital_bed 
                                inner join hospital_room on hospital_bed.room_id=hospital_room.id 
                                inner join hospital_floor on hospital_room.floor_id=hospital_floor.id
                                inner join hospital_building on hospital_floor.building_id=hospital_building.id");
        foreach ($hello as $row) { ?>
            <tr>
                <td><?php echo $row['yatakid'] ?></td>
                <td ><?php echo $row["bed_name"] ?></td>
                <td ><?php echo islemtanimgetirid($row["bed_type"]);  ?></td>
                <td><?php echo $row['room_name'] ?></td>
                <td ><?php echo $row["floor_name"] ?></td>
                <td ><?php echo $row["bina"] ?></td>
                <td><?php  if ($row["durum"]=='1'){ ?><b style="color: green">Aktif</b><?php }elseif ($row["durum"]=='0'){?><b style="color: darkred">Pasif</b><?php }else{ echo "hata"; } ?></td>

                <td align="center">
                    <i class="fa fa-pen-to-square yatakguncelle" title="Düzenle"
                        <?php if ($row['durum']){ ?> yatak-id="<?php echo $row["yatakid"]; ?>"   data-bs-target="#yatak-modal" data-bs-toggle="modal"  <?php } ?>alt="icon"></i>
                    <?php if($row['durum']=='0'){ ?>
                        <i class="fa-solid fa-recycle yatakaktif" title="Aktif Et" id="<?php echo $row["yatakid"]; ?>" alt="icon" ></i>
                    <?php }else{ ?>
                        <i class="fa fa-trash yatakdeletemodal" title="İptal" yatak-id="<?php echo $row["yatakid"]; ?>" alt="icon"></i>
                    <?php } ?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    </div>
</div>
        </div>

    <div class="modal fade" id="yatak-modal" aria-hidden="true" style="margin-top: 90px;">
        <div class="modal-dialog" id="yatak-modal-icerik">
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#examplSe1').DataTable({
                "scrollY": "60vh",
                "autoWidth": false,
                "scrollX": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        text: 'Yatak Ekle',
                        className: 'btn btn-success btn-sm btn-kaydet',
                        attr:  {
                            'data-bs-toggle':"modal",
                            'data-bs-target':"#yatak-modal",
                        },
                        action: function ( e, dt, button, config ) {

                            $.get( "ajax/tanimlar/yataktanimla.php?islem=modal-icerik",function(get){
                                $('#yatak-modal-icerik').html(get);
                            });
                        }

                    }],
            });
        });

        $("body").off("click", ".yatakguncelle").on("click", ".yatakguncelle", function(e){
            var getir = $(this).attr('yatak-id');
            $.get( "ajax/tanimlar/yataktanimla.php?islem=modal-icerik", { getir:getir },function(getVeri){
                $('#yatak-modal-icerik').html(getVeri);
            });
        });

        $("body").off("click",".yatakaktif").on("click",".yatakaktif", function (e) {
            var getir = $(this).attr('id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/yataktanimla.php?islem=yatak-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.yatak-tanim:first').load("ajax/tanimlar/yataktanimla.php?islem=listeyi-getir");
                }
            });
        });

        $("body").off("click", ".yatakdeletemodal").on("click", ".yatakdeletemodal", function(e){
            var id = $(this).attr('yatak-id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control form-control-xs' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Yatak Silme Nedeni..'></textarea>" +
                "<input class='form-control form-control-xs' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/yataktanimla.php?islem=yataksilme',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/yataktanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.yatak-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Yatak Silme İşlemini Onayla"});
        });
    </script>



<?php }elseif ($islem=="modal-icerik"){
    $degerid=$_GET["getir"];
    $row= tek("SELECT hospital_bed.id as yatakid,hospital_bed.*,hospital_room.*,hospital_floor.*,hospital_building.* FROM hospital_bed
    inner join hospital_room on hospital_bed.room_id=hospital_room.id
    inner join hospital_floor on hospital_room.floor_id=hospital_floor.id 
    inner join hospital_building on hospital_floor.building_id=hospital_building.id
    where hospital_bed.id='$degerid'");  ?>


    <div class="modal-dialog"  >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php if ($_GET["getir"]){ ?>

                    <h4 class="modal-title ">Yatak Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Yatak Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formyatakupdate" action="javascript:void(0);" >
                <div class="modal-body">

                    <div class="row">
                        <div class="col-3">
                            <label class="col-form-label">Servis:</label>
                        </div>
                        <div class="col-9">
                            <select class="form-select form-select-xs " id="hospital_service_id">
                                <option value="">Bina Seçiniz</option>
                                <?php $sql = verilericoklucek("SELECT * FROM units WHERE status!='0' and unit_type=1");
                                foreach ($sql as $rowa) { ?>
                                    <option value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["department_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-3">
                            <label class="col-form-label">Bina:</label>
                        </div>
                        <div class="col-9">
                            <select class="form-select form-select-xs " id="bina">
                                <option value="">Bina Seçiniz</option>
                                <?php $sql = verilericoklucek("SELECT * FROM hospital_building WHERE status!='0'");
                                foreach ($sql as $rowa) { ?>
                                    <option value="<?php echo $rowa["id"]; ?>" <?php if ($row['building_id'] == $rowa['id']) echo "selected"; ?>><?php echo $rowa["building_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                            <label class="col-form-label">Kat:</label>
                        </div>
                        <div class="col-9">
                            <select class="form-select form-select-xs " id="kat">
                                <?php $katid = $row['id'];
                                if ($katid) {
                                    $sql = verilericoklucek("select * from hospital_floor where status!='0'");
                                    foreach ($sql as $rowa) { ?>
                                        <option value="<?php echo $rowa["id"]; ?>" <?php if ($row['floor_id'] == $rowa['id']) echo "selected"; ?>><?php echo $rowa["floor_name"]; ?></option>
                                    <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                            <label class="col-form-label">Oda: </label>
                        </div>
                        <div class="col-9">
                            <select class="form-select form-select-xs " name="room_id" id="oda">
                                <option class="bg-danger text-white" selected disabled></option>
                                <?php $odaid = $row['id'];
                                if ($odaid) {
                                    $sql = verilericoklucek("select * from hospital_room where status!='0'");
                                    foreach ($sql as $rowa) { ?>
                                        <option value="<?php echo $rowa["id"]; ?>" <?php if ($row['id'] == $rowa['id']) echo "selected"; ?>><?php echo $rowa["room_name"]; ?></option>
                                    <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                            <label class="col-form-label">Yatak Türü:</label>
                        </div>
                        <div class="col-9">
                            <select class="form-select form-select-xs " name="bed_type">
                                <?php $sql = verilericoklucek("select * from transaction_definitions where status!='0' and definition_type='YATAK_ADI'");
                                foreach ($sql as $rowa) { ?>
                                    <option value="<?php echo $rowa["id"]; ?>" <?php if ($row['bed_type'] == $rowa['id']) echo "selected"; ?>><?php echo $rowa["definition_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                            <label class="col-form-label">Yatak Adı:</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control form-control-xs" type="text" name="bed_name" value="<?php echo $row["bed_name"] ?>">
                            <?php if ($_GET['getir']) { ?>
                                <input class="form-control form-control-xs" type="hidden" name="id" value="<?php echo $_GET['getir']; ?>">
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success w-md justify-content-end yatakupdate btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Güncelle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn  w-md justify-content-end btn-success btn-sm yatakinsert"  data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                    <button type="button" class="btn btn-default btn-sm"  data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                </div>

            </form>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#bina").change(function () {
                var binano = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "ajax/tanimlar/yataktanimla.php?islem=binaidgetir",
                    data: {binano: binano},
                    success: function (e) {
                        $("#kat").html(e);
                    }
                });
            });

            $("#kat").change(function () {
                var katid = $(this).val();
                var binaid = $("#bina").val();
                $.ajax({
                    type: "POST",
                    url: "ajax/tanimlar/yataktanimla.php?islem=katget",
                    data: {katid: katid , binaid:binaid},
                    success: function (e) {
                        $("#oda").html(e);
                    }
                })
            })

            $("body").off("click", ".yatakupdate").on("click", ".yatakupdate", function(e){
                var gonderilenform = $("#formyatakupdate").serialize();
                document.getElementById("formyatakupdate").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/yataktanimla.php?islem=yatakupdate',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/tanimlar/yataktanimla.php?islem=listeyi-getir", { },function(e){
                            $('.yatak-tanim:first').html(e);

                        });
                    }
                });

            });
            $("body").off("click", ".yatakinsert").on("click", ".yatakinsert", function(e){
                var gonderilenform = $("#formyatakupdate").serialize();
                document.getElementById("formyatakupdate").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/yataktanimla.php?islem=yatakekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/tanimlar/yataktanimla.php?islem=listeyi-getir", { },function(e){
                            $('.yatak-tanim:first').html(e);

                        });
                    }
                });

            });

        });

    </script>

<?php } else if($islem=="binaidgetir"){
    $binano=$_POST['binano'];
    echo " <option value=''>kat seçiniz..</option>";
    $sql = verilericoklucek("select * from hospital_floor where building_id=$binano and status='1' order by floor_name");
    foreach ($sql as $value) {
        echo '<option value="'.$value['id'].'">'.$value['floor_name'].'</option>';
    }
}

else if($islem=="katget"){
    $katid = $_POST['katid'];
    $binaid  = $_POST['binaid'];

    if ($binaid) {
        $ek_sql = " and building_id=".$binaid. " ";
    } else {
        $ek_sql = " ";
    } ?>

    <option class="bg-danger text-white" selected disabled>oda seçiniz..</option>

    <?php  $sql = verilericoklucek("select * from hospital_room where floor_id=$katid $ek_sql and  status='1' and availability!='0' order by room_name");

    foreach ($sql as $value) { ?>
        <option value="<?php echo $value['id']?>"  ><?php echo $value['room_name']; ?></option>
    <?php   } }



