<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];
$tetkikGrupId=$_GET["tetkikGrupId"];

if($islem=="tetkik-grup-modal"){

    $tetkikGrupInfo = singular("process_group", "id", $_GET["tetkikGrupId"]); ?>

    <form class="modal-content" id="formtetkikgrup" action="javascript:void(0);">
        <div class="modal-header  text-white" style="background-color: #3F72AF">
            <?php if ($_GET["tetkikGrupId"]){ ?>
                <h4 class="modal-title ">Hizmet Grup Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Hizmet Grup Tanımla</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">


                <div class="row">

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Hizmet Adı</label>
                    </div>
                    <div class="col-10">

                        <input type="text" class="form-control"  name="group_name" value="<?php echo $tetkikGrupInfo["group_name"]; ?>" >
                        <?php   if ($_GET["tetkikGrupId"] != "") { ?>
                            <input type="hidden" class="form-control" name="id"  value="<?php echo $_GET["tetkikGrupId"]; ?>" >
                        <?php } ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Ana ID</label>
                    </div>
                    <div class="col-10">
                        <input class="form-control mt-1" type="text" name="parent_id" value=""<?php echo $tetkikGrupInfo["parent_id"]; ?>"/>
                    </div>
            </div>




        </div>
        <div class="modal-footer">
            <?php if ($_GET["tetkikGrupId"]){ ?>
                <input type="submit" class="btn  w-md justify-content-end btn-update btn-sm" id="tetkik_grup_guncelle_buton"  data-bs-dismiss="modal"  value="Düzenle"/>
            <?php  }else{ ?>
                <input type="submit" class="btn btn-success btn-sm" id="tetkik_grup_ekle_buton" data-bs-dismiss="modal"  value="Kaydet"/>
            <?php } ?>
            <button type="button"  class="btn btn-sm btn-danger" data-bs-dismiss="modal" >Kapat</button>
        </div>
    </form>

    <script>
        $("body").off("click", "#tetkik_grup_ekle_buton").on("click", "#tetkik_grup_ekle_buton", function(e){
            var gonderilenform = $("#formtetkikgrup").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/tetkikler/tetkik-sql.php?islem=tetkik-grup-ekle-islem',
                data: gonderilenform,
                success: function (e) {
                    $("#sonucyaz").html(e);
                    $.get("ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik-grup-liste", {}, function (e) {
                        $('.tetkik-grup-liste').html(e);
                    });
                }
            });
        });

        $("body").off("click", "#tetkik_grup_guncelle_buton").on("click", "#tetkik_grup_guncelle_buton", function(e){
            var gonderilenform = $("#formtetkikgrup").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/tetkiktanimla.php?islem=tetkik-grup-duzenle-islem',
                data: gonderilenform,
                success: function (e) {
                    $("#tetkiksonuc").html(e);
                    $.get("ajax/tanimlar/tetkiktanimla.php?islem=listeyi-getir", {}, function (e) {
                        $('.sayfa-icerik').html(e);
                    });
                }
            });
        });
    </script>

<?php } if($islem=="tetkik-detay-modal"){  $tetkikDetayId =$_GET["tetkikDetayId"];
    $row= tek("select * from transaction_detail where id='$tetkikDetayId'");   ?>

    <form class="modal-content" id="formtetkikdetay" action="javascript:void(0);">
        <div class="modal-header  text-white">
            <?php if ($_GET["tetkikDetayId"]){ ?>
                <h4 class="modal-title ">Tetkik  Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Tetkik  Tanımla</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="row">
                <div class="col-2">
                    <label  class="form-label text-dark">Hizmet Adı</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" name="transaction_name" value="<?php echo $row["transaction_name"]; ?>">
                    <?php if ($_GET["tetkikGrupId"] != "") { ?>
                        <input type="hidden" class="form-control mt-1" name="transaction_id" value="<?php echo $_GET["tetkikGrupId"]; ?>">
                    <?php }
                    if ($tetkikDetayId != "") { ?>
                        <input type="hidden" class="form-control mt-1" name="id"value="<?php echo $tetkikDetayId; ?>" ><?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="form-label text-dark">Ücreti</label>
                </div>
                <div class="col-10">
                <input type="number" class="form-control mt-1" name="cost" value="<?php echo $row["cost"]; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label class="form-label text-dark">Özel Kod</label>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control mt-1" name="special_code" value="<?php echo $row["special_code"]; ?>">
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <?php if ($tetkikDetayId){ ?>
                <input type="submit" class="btn  w-md justify-content-end btn-success btn-sm" id="tetkik_detay_guncelle_buton"  data-bs-dismiss="modal"  value="Düzenle"/>
            <?php  }else{ ?>
                <input type="submit" class="btn  w-md justify-content-end btn-success btn-sm" id="tetkik_detay_ekle_buton" data-bs-dismiss="modal"  value="Kaydet"/>
            <?php } ?>
            <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
        </div>
    </form>

    <script>
        $("body").off("click", "#tetkik_detay_ekle_buton").on("click", "#tetkik_detay_ekle_buton", function(e){
            var gonderilenform = $("#formtetkikdetay").serialize();
            var tetkikGrupId ="<?php echo $tetkikGrupId; ?>";
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/tetkikler/tetkik-sql.php?islem=tetkik-detay-ekle-islem',
                data: gonderilenform,
                success: function (e) {
                    $("#sonucyaz").html(e);
                    $.get( "ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik_detay_list", { tetkikGrupId:tetkikGrupId },function(getveri){
                        $('#tetkik-detay-list').html(getveri);
                    });
                }
            });

        });

        $("body").off("click", "#tetkik_detay_guncelle_buton").on("click", "#tetkik_detay_guncelle_buton", function(e){
            var gonderilenform = $("#formtetkikdetay").serialize();
            var tetkikGrupId ="<?php echo $tetkikGrupId; ?>";
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/tetkikler/tetkik-sql.php?islem=tetkik-detay-duzenle-islem',
                data: gonderilenform,
                success: function (e) {
                    //$("#tetkiksonuc").html(e);
                    $("#sonucyaz").html(e);
                    $.get( "ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik_detay_list", { tetkikGrupId:tetkikGrupId },function(getveri){
                        $('#tetkik-detay-list').html(getveri);
                    });
                }
            });
        });

    </script>
<?php
}
