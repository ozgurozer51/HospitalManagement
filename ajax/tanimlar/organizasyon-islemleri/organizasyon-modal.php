<?php
include "../../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islem=$_GET['islem'];

if($islem=="hastane-ekle-modal"){
$bina=singular("hospital","id",$_GET["getir"]);?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header text-white">
            <?php if ($_GET['getir']){ ?>
                <h4 class="modal-title ">Hastane Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Hastane Kaydet</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formbina" action="javascript:void(0);" >
            <div class="modal-body">
                <div class="mx-2">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-2">
                            <label for="example-text-input" class="col-form-label">Hastane Adı</label>
                            </div>
                            <div class="col-10">
                            <input class="form-control" type="text" name="hospital_name" value="<?php echo $bina["hospital_name"]?>" id="example-text-input">
                            <?php if ($_GET['getir']){ ?>
                                <input class="form-control" type="hidden" name="id" value="<?php echo  $_GET['getir'];?>">
                            <?php } ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" class="btn-close" data-bs-dismiss="modal">Kapat</button>
                <?php if ($_GET["getir"]){ ?>
                    <input class="btn btn-success  hastane-update btn-sm"  type="button" data-bs-dismiss="modal" value="Düzenle"/>
                <?php  }else{ ?>
                    <input type="button" class="btn  hastane-insert btn-sm btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
                <?php } ?>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("body").off("click", ".hastane-update").on("click", ".hastane-update", function(e){
            var gonderilenform = $("#formbina").serialize();
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=hastane-update',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimdetay:first').load("ajax/tanimlar/organizasyon-islemleri/hastane-tanimla.php?islem=listeyi-getir");
                }
            });
        });

        $("body").off("click", ".hastane-insert").on("click", ".hastane-insert", function(e){
            var gonderilenform = $("#formbina").serialize();
            document.getElementById("formbina").reset();
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=hastane-ekle',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.tanimdetay:first').load("ajax/tanimlar/organizasyon-islemleri/hastane-tanimla.php?islem=listeyi-getir");
                }
            });
        });

    });
</script>


<?php } if ($islem=="bina-tanim-icerik"){
    $bina=singular("hospital_building","id",$_GET["getir"]); ?>

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <?php if ($_GET['getir']){ ?>
                    <h4 class="modal-title ">Bina Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Bina Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formbina" action="javascript:void(0);">
                <div class="modal-body">
                    <div class="mx-2">

                        <div class="row mt-1">
                            <div class="col-md-2 col-xs-2 col-lg-2">
                                <label class="form-check-label ">Hastane:</label>
                            </div>
                            <div class="col-md-10 col-xs-10 col-lg-10">
                                <select class="form-select" name="hospital_id">
                                    <option value="">Hastane Seçiniz</option>
                                    <?php $sql = "SELECT * FROM hospital WHERE status='1'";
                                    $hello = verilericoklucek($sql);
                                    foreach ($hello as $rowa) { ?>
                                        <option value="<?php echo $rowa["id"]; ?>" <?php if ($bina['hospital_id'] == $rowa['id']) echo "selected"; ?>><?php echo $rowa["hospital_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-2 col-xs-2 col-lg-2">
                                <label class="col-form-label">Skrs_kodu:</label>
                            </div>
                            <div class="col-md-10 col-xs-10 col-lg-10">
                                <input class="form-control" type="number" name="skrs_institution_code" value="<?php echo $bina["skrs_institution_code"] ?>" id="example-text-input">
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-2 col-xs-2 col-lg-2">
                                <label for="example-text-input" class="col-form-label">Bina Adı:</label>
                            </div>
                            <div class="col-md-10 col-xs-10 col-lg-10">
                                <input class="form-control" type="text" name="building_name" value="<?php echo $bina["building_name"] ?>" id="example-text-input">
                                <?php if ($_GET['getir']) { ?>
                                    <input class="form-control" type="hidden" name="id" value="<?php echo $_GET['getir']; ?>">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-2 col-xs-2 col-lg-2">
                                <label for="example-text-input" class="col-form-label">Bina Adresi:</label>
                            </div>
                            <div class="col-md-10 col-xs-10 col-lg-10">
                                <input class="form-control" type="text" name="building_address" value="<?php echo $bina["building_address"] ?>">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" class="btn-close" data-bs-dismiss="modal">Kapat</button>
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success  bina-update btn-sm" style="margin-bottom:4px"  type="button" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="button" class="btn  bina-insert btn-sm btn-success" id="brans-kaydet" data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", ".bina-update").on("click", ".bina-update", function(e){
                var gonderilenform = $("#formbina").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=bina-update',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/bina-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.bina-tanim:first').html(e);
                        });
                    }
                });
            });

            $("body").off("click", ".bina-insert").on("click", ".bina-insert", function(e){
                var gonderilenform = $("#formbina").serialize();
                document.getElementById("formbina").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=bina-ekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/bina-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.bina-tanim:first').html(e);
                        });
                    }
                });
            });

        });

    </script>


<?php }if($islem=="kat-tanimla"){
$kat=singular("hospital_floor","id",$_GET["getir"]);
$bina=singular("hospital_building","id",$kat["building_id"]);  ?>

<div class="modal-dialog"  >
    <!-- modal content-->
    <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #3F72AF">
            <?php if ($_GET["getir"]){ ?>
                <h4 class="modal-title ">Kat Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Kat Kaydet</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form   id="formkat" action="javascript:void(0);" >
            <div class="modal-body">


                        <div class="row">
                            <div class="col-2">
                            <label class="form-label">Bina</label>
                            </div>

                            <div class="col-10">
                            <select class="form-select" name="building_id" id="bina">
                                <?php $sql = verilericoklucek("select * from hospital_building where status!='0'"); ?>
                                <option class="bg-danger text-white" selected disabled>Kat Seçiniz</option>
                               <?php foreach ($sql as $rowa){ ?>
                                    <option value="<?php echo $rowa["id"]; ?>" <?php if($kat['building_id'] == $rowa['id']){ echo"selected"; } ?>> <?php echo $rowa["building_name"]; ?> </option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-2">
                            <label for="example-text-input" class="col-form-label">Kat Adı</label>
                            </div>

                            <div class="col-10">
                            <input class="form-control" type="text" name="floor_name" value="<?php echo $kat["floor_name"]?>" id="example-text-input">
                            <input class="form-control" type="hidden" name="id" value="<?php echo  $_GET['getir'];?>" id="example-text-input">
                        </div>
                        </div>
            </div>
            <div class="modal-footer">
                <?php if ($_GET["getir"]){ ?>
                    <input class="btn btn-success kat-update btn-sm" type="button"  data-bs-dismiss="modal" value="Güncelle"/>
                <?php  }else{ ?>
                    <input type="button" class="btn  btn-success btn-sm kat-insert" data-bs-dismiss="modal"  value="Kaydet"/>
                <?php } ?>
                <button type="button" class="btn btn-danger btn-sm"  data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("body").off("click", ".kat-update").on("click", ".kat-update", function(e){
            var gonderilenform = $("#formkat").serialize();
            $.ajax({
                type:'post',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=kat-update',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/tanimlar/organizasyon-islemleri/kat-tanimla.php?islem=listeyi-getir", { },function(e){
                        $('.kat-tanim:first').html(e);
                    });
                }
            });
        });
        
        $("body").off("click", ".kat-insert").on("click", ".kat-insert", function(e){
            var gonderilenform = $("#formkat").serialize();
            document.getElementById("formkat").reset();
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=kat-ekle',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/tanimlar/organizasyon-islemleri/kat-tanimla.php?islem=listeyi-getir", { },function(e){
                        $('.kat-tanim:first').html(e);
                    });
                }
            });

        });

    });

</script>

<?php } if($islem=="oda-tanim"){
$degerid=$_GET["getir"];
$row= tek("SELECT hospital_floor.id as katid,hospital_floor.*,hospital_building.*,hospital_room.* FROM hospital_room
inner join hospital_floor on hospital_room.floor_id=hospital_floor.id
inner join hospital_building on hospital_floor.building_id=hospital_building.id where hospital_room.id='$degerid'");  ?>

<div class="modal-dialog"  >
    <!-- modal content-->
    <div class="modal-content">

        <div class="modal-header text-white" style="background-color: #3F72AF">
            <?php if ($_GET["getir"]){ ?>
                <h4 class="modal-title ">Oda Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Oda Kaydet</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form   id="formodaupdate" action="javascript:void(0);" >
            <div class="modal-body">

                        <div class="row">
                            <div class="col-2">
                            <label class="form-label">Bina:</label>
                            </div>
                            <div class="col-10">
                            <select class="form-select" name="building_id" id="bina-sec">
                                <option class="bg-danger text-white" selected disabled>Bina Seçiniz..</option>
                                <?php $sql=verilericoklucek("select * from hospital_building where status!='0'");
                                foreach ($sql as $rowa) { ?>
                                    <option  value="<?php echo $rowa["id"]; ?>" <?php if($row['building_id'] == $rowa['id']) echo"selected"; ?>><?php echo $rowa["building_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-2">
                            <label class="col-md-6">Kat:</label>
                            </div>
                            <div class="col-10">
                                <select class="form-select" name="floor_id" id="kat145">
                                <?php  $katid=$row['katid'];
                                if ($katid){
                                    $hello=verilericoklucek("select * from hospital_floor where status!='0'");
                                    foreach ($hello as $rowa) { ?>
                                        <option value="<?php echo $rowa["id"]; ?>" <?php if($row['floor_id'] == $rowa['id']) echo"selected"; ?>><?php echo $rowa["floor_name"]; ?></option>
                                    <?php } } ?>
                            </select>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-2">
                            <label class="form-label">Oda Türü:</label>
                            </div>
                            <div class="col-10">
                            <select class="form-select" name="room_type">
                                <option value="">oda türü seçiniz..</option>
                                <?php $hello=verilericoklucek("select * from transaction_definitions  where status!='0' and definition_type='ODA_TURU'");
                                foreach ($hello as $rowa) {
                                    ?>
                                    <option value="<?php echo $rowa["id"]; ?>" <?php if($row['room_type'] == $rowa['id']) echo"selected"; ?> ><?php echo $rowa["definition_name"]; ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>

                <div class="row mt-1">
                    <div class="col-2">
                        <label class="form-label">Oda Adı:</label>
                    </div>
                    <div class="col-10">
                        <input class="form-control" type="text" name="room_name"
                               value="<?php echo $row["room_name"] ?>">
                        <?php if ($_GET['getir']) { ?>
                            <input class="form-control" type="hidden" name="id" value="<?php echo $_GET['getir']; ?>">
                        <?php } ?>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">
                <?php if ($_GET["getir"]){ ?>
                    <input class="btn btn-success oda-update btn-sm"  type="button"  data-bs-dismiss="modal" value="Güncelle"/>
                <?php  }else{ ?>
                    <input type="submit" class="btn btn-success btn-sm oda-insert"  data-bs-dismiss="modal"  value="Kaydet"/>
                <?php } ?>
                <button type="button" class="btn btn-danger btn-sm"  data-bs-dismiss="modal">Kapat</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){

        $("#bina-sec").change(function () {
            var binano = $(this).val();

            $.ajax({
                type: "post",
                url: "ajax/tanimlar/organizasyon-islemleri/oda-tanimla.php?islem=bina-id-getir",
                data: {binano: binano},
                success: function (e) {
                    $("#kat145").html(e);
                }
            })
        })

        $("body").off("click", ".oda-update").on("click", ".oda-update", function(e){
            var gonderilenform = $("#formodaupdate").serialize();
            $.ajax({
                type:'post',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=oda-update',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get("ajax/tanimlar/organizasyon-islemleri/oda-tanimla.php?islem=listeyi-getir", { },function(e){
                        $('.oda-tanim:first').html(e);
                    });
                }
            });
        });

        $("body").off("click", ".oda-insert").on("click", ".oda-insert", function(e){
            var gonderilenform = $("#formodaupdate").serialize();
            document.getElementById("formodaupdate").reset();
            $.ajax({
                type:'post',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=oda-ekle',
                data:gonderilenform,
                success:function(e){
                    $('#sonucyaz').html(e);
                    $.get( "ajax/tanimlar/organizasyon-islemleri/oda-tanimla.php?islem=listeyi-getir", { },function(e){
                        $('.oda-tanim:first').html(e);
                    });
                }
            });
        });
    });
</script>

<?php } ?>

