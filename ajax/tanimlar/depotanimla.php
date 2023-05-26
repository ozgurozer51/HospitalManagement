<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];

?>
    <style>
        .form-label{
            color : black !important;
        }
    </style>
<?php

if ($islem == "listeyi-getir") { ?>

  <div class="depo-tanim">
    <div class="card">

    <div class="card-header px-2 text-white fw-bold" style="background-color:#3F72AF;height:39px;">
            <div class="row">
                <div class="col-md-8">Depolar</div>
                <div class="col-md-4">
                    <div class="text-center  me-3 depodetaygetir" title="Depo Tanımla" style="float: right;" data-bs-target="#depo-detay-modal"  data-bs-toggle="modal">
                        <i class="fa fa-plus fa-2x" ></i>
                    </div>
                </div>
            </div>
        </div>
    <div class="card-body">
        <table class="table table-bordered display nowrap w-100" id="depoListTable">
    <thead>
    <tr>

        <th>Depo Adı</th>
        <th>Depo Türü</th>
        <th>MKYS Kodu</th>
        <th>Bina Adı</th>
        <th>Birim Adı</th>
        <th>durumu</th>
        <th>işlemler</th>


    </tr>
    </thead>
            <tbody>
            <?php

            $depolist = verilericoklucek("select * from warehouses");
            foreach ($depolist as $getir) {
                $depoturu = singular("transaction_definitions", "id", $getir["warehouse_type"]);
                $binainfo = singular("hospital_building", "id", $getir["buildingid"]);
                $birimler = explode(",", $getir["unitid"]);
                ?>

                <tr  style="cursor: pointer;">

                    <td><?php echo $getir["warehouse_name"] ?></td>
                    <td><?php echo $depoturu["definition_name"] ?></td>
                    <td><?php echo $getir["mkys_code"] ?></td>
                    <td><?php echo $binainfo["building_name"] ?></td>
                    <td><?php $birimsay=count($birimler);
                        $say=0;
                        foreach ($birimler as $brm) {
                            $say++;
                            $biriminfo = singular("units", "id", $brm);

                            echo $biriminfo["department_name"];
                            if ($birimsay>$say){
                                echo ", ";
                            }
                        } ?></td>
                    <td id="<?php echo $getir["id"]; ?>"  class='tetkikgrupgetir' align="center"><?php  if ($getir["status"]=='1'){ ?> <b style="color: green">Aktif</b> <?php }else {?> <b style="color: darkred">Pasif</b>  <?php } ?></td>

                    <td><button id='<?php echo $getir["id"]; ?>'  type="button" class="btn btn-danger btn-sm  depo-sil-buton"><i class="fa-solid fa-trash"></i></button>
                        <button  depo-id='<?php echo $getir["id"]; ?>' type="button" data-bs-target="#depo-detay-modal" data-bs-toggle="modal" class="btn btn-success btn-sm depodetaygetir">  <i class="fa-solid fa-edit"></i></button></td>
                </tr>
            <?php } ?>
            </tbody>

    </table>

    </div>
    </div>
        </div>

    <div class="modal fade" id="depo-detay-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id='depo-tanim-icerik'> </div>
    </div>

    <script>
        $('#depoListTable').DataTable({

            //"processing": true,
            "scrollY": false,
            "scrollX": false,
            "paging":true,
            'Visible': true,
            "responsive":true,
            "pageLength": 50,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $(document).on("click",".depodetaygetir",function() {
            var depoId = $(this).attr('depo-id');


            $.get( "ajax/tanimlar/depotanimla.php?islem=depo-detay-modal", { depoId:depoId },function(getveri){
                $("#depo-tanim-icerik").html(getveri);
            });
        });
    </script>
    <script>
        $(document).on('click', '.depo-sil-buton', function () {
            var id = $(this).attr('id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Branş Silme Nedeni..'></textarea>", function(){
                var delete_detail = $('#delete_detail').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/depotanimla.php?islem=depo-sil',
                    data: {
                        id,
                        delete_detail,

                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get( "ajax/tanimlar/depotanimla.php?islem=listeyi-getir", {  },function(getveri){
                            $('.depo-tanim:first').html(getveri);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Tetkik Grubu Silme İşlemini Onayla"});
        });
    </script>

    <?php } if($islem=="depo-detay-modal"){
    $depoInfo = singular("warehouses", "id", $_GET["depoId"]);  ?>

    <form class="modal-content" id="formdepo" action="javascript:void(0);">
        <div class="modal-header  text-white" style="background-color: #3F72AF">
            <?php if ($_GET["depoId"]){ ?>
                <h4 class="modal-title ">Depo Düzenle</h4>
            <?php  }else{ ?>
                <h4 class="modal-title ">Depo Tanımla</h4>
            <?php } ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="col-12">
                <div class="row">

                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-4 form-label">depo adı</label>
                        <div class="col-sm-8">
                        <input type="text" class="form-control"  name="warehouse_name" value="<?php echo $depoInfo["warehouse_name"]; ?>" >
                        <?php   if ($_GET["depoId"] != "") { ?>
                            <input type="hidden" class="form-control" name="id"  value="<?php echo $_GET["depoId"]; ?>" >
                        <?php } ?>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-4 form-label">mkys kodu</label>
                        <div class="col-sm-8">
                            <input class="form-control" name="mkys_code" type="text" id="example-text-input"
                                   value="<?php echo $depoInfo['mkys_code']; ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-4 form-label">depo türü</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="warehouse_type" id="w_type_select">
                                <?php
                                $depogonder = verilericoklucek("select * from transaction_definitions where definition_type='DEPO_TURU'");
                                foreach ($depogonder as $row) {
                                    ?>
                                    <option <?php if ($depoInfo['warehouse_type'] == $row["id"]) {
                                        echo "selected";
                                    } ?>
                                            value="<?php echo $row["id"]; ?>"><?php echo $row["definition_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-4 form-label">birim adı</label>
                        <div class="col-sm-8">

                            <select class="form-select birim2"  name="unitid" multiple style="width: 100% !important;">

                                <?php $birimler = verilericoklucek("select * from units where status='1'");
                                $birimler2 = explode(",", $depoInfo['unitid']);

                                foreach ($birimler as $birimlist) {
                                    foreach ($birimler2 as $brm) { ?>
                                        <option <?php if ($brm == $birimlist["id"]) { echo "selected"; } } ?> value="<?php echo $birimlist["id"]; ?>"><?php echo $birimlist["department_name"]; ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="example-text-input" class="col-sm-4 form-label">bina adı</label>
                        <div class="col-sm-8">
                            <select class="form-select" class="bina" name="buildingid" >
                                <?php  $binalar = verilericoklucek("select * from hospital_building where status='1'");  ?>
                                <option value="" selected="selected">seçim yapmadınız</option>
                                <?php  foreach ($binalar as $binalist) { ?>
                                    <option <?php if ($depoInfo['buildingid'] == $binalist["id"]) { echo "selected";} ?> value="<?php echo $binalist["id"]; ?>"><?php echo $binalist["building_name"]; ?></option>
                                <?php }  ?>
                            </select>
                        </div>
                    </div>
                </div>

                </div>
            </div>

        <div class="modal-footer">
            <?php if ($_GET["depoId"]){ ?>
                <input type="submit" class="btn  w-md justify-content-end btn-update btn-sm" id="w_guncelle_buton"  data-bs-dismiss="modal"  value="Düzenle"/>
            <?php  }else{ ?>
                <input type="submit" class="btn  w-md justify-content-end btn-update btn-sm" id="w_ekle_buton" data-bs-dismiss="modal"  value="Kaydet"/>
            <?php } ?>
            <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
        </div>
    </form>

    <script>
        $("#w_ekle_buton").click(function(){
            var gonderilenform = $("#formdepo").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/depotanimla.php?islem=w-ekle-islem',
                data: gonderilenform,
                success: function (e) {
                    $("#tetkiksonuc").html(e);
                    $.get("ajax/tanimlar/depotanimla.php?islem=listeyi-getir", {}, function (e) {
                        $('.depo-tanim:first').html(e);
                    });
                }
            });

        });
        $("#w_guncelle_buton").click(function(){
            var gonderilenform = $("#formdepo").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/depotanimla.php?islem=w-duzenle-islem',
                data: gonderilenform,
                success: function (e) {
                    $("#tetkiksonuc").html(e);
                    $.get("ajax/tanimlar/depotanimla.php?islem=listeyi-getir", {}, function (e) {
                        $('.depo-tanim:first').html(e);
                    })
                }
            });
        });

        $(".birim2").select2({
            allowclear: true
        });

    </script>

    <?php } if($islem=="w-duzenle-islem"){
    $id=$_POST["id"];
    $_POST["update_userid"]=1; //session bilgisi
    $_POST["update_datetime"]=$simdikitarih;
    $depoupdate=direktguncelle("warehouses","id",$id,$_POST,"id");
    //var_dump($depoupdate);

    if ($depoupdate==1) { ?>

        <script type="text/javascript">
            alertify.success("güncelleme başarılı");
            $('.modal-backdrop').remove();
        </script>

        <?php }else{ ?>

        <script type="text/javascript">
            alertify.alert("güncelleme başarısız");
            $('.modal-backdrop').remove();
        </script>
        <?php } }

if($islem=="w-ekle-islem"){
    $yenidepo=direktekle("warehouses",$_POST);
    var_dump($yenidepo);

    if ($yenidepo==1) {
        echo "eklendi";  ?>
        <script type="text/javascript">
            alertify.success("kayıt başarılı");
            $('.modal-backdrop').remove();
        </script>
        <?php }else{ ?>

        <script type="text/javascript">
            alertify.alert("kayıt başarısız");
            $('.modal-backdrop').remove();
        </script>

        <?php }

} if($islem=="depo-sil"){
    $detay=$_POST["cancel_detail"];
    $id=$_POST["id"];
    $deposil=canceldetail("warehouses","id",$id,$detay);
    if($deposil){ ?>
        <script>
            alertify.set('notifier','delay',8);
            alertify.success('silme işlemi başarılı')
        </script>

        <?php }else{ ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Silme İşlemi Başarısız')
        </script>
        <?php }

}if ($_GET["islem"] == "cep_birim_sec") {
    $wtype = $_GET["wtype"];
    $wtypeinfo=singular("transaction_definitions","id","$wtype");
    if ($wtypeinfo["definition_name"] != "Cep") {
        exit();
    }
    $birimler = verilericoklucek("select * from units where status='1'");

    foreach ($birimler as $birimlist) { ?>
        <option value="<?php echo $birimlist["id"]; ?>"><?php echo $birimlist["department_name"]; ?></option>
    <?php }

}
