<?php
include "../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$islem=$_GET['islem'];

if ($islem=="hastaneekle"){
    if($_POST) {
        $_POST["definition_type"]="KURUMLAR";
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $birimtanimla = direktekle("transaction_definitions", $_POST, "kurumkaydet","id");
        var_dump($birimtanimla);
        if ($birimtanimla == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }
    }

}elseif ($islem=="hastaneupdate"){
    if($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];
        $birimguncelle = direktguncelle("transaction_definitions","id", $_POST["id"],$_POST, "kurumguncelle", "id");
        var_dump($birimguncelle);
        if ($birimguncelle == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }

    }
}elseif ($islem=="hastanesilme"){
    if ($_POST){
        $id=$_POST['id'];
        unset($_POST['id']);
        $delete_detail='kurum iptal işlemi';
        $delete_userid=$_SESSION['id'];
        $delete_datetime=$simdikitarih;
        $doktortanimla = canceldetail("transaction_definitions","id", $id,$delete_detail,$delete_userid,$delete_datetime);
        var_dump($doktortanimla);
        if ($doktortanimla == 1) { ?>
            <script>
                alertify.success('Işlem Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.error('Işlem Başarısız');
            </script>

        <?php }
    }
}elseif($islem=="hsinif-aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('transaction_definitions','id',$id,$date,$user);
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

        <div class="hasta-sinif-tanim">

    <div class="card">

        <div class="card-body bg-white">
            <table id="sosyalguvencetab" class="table table-striped table-bordered" style=" background:white;width: 100%;">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Skrs Kodu</th>
                    <th>İçerik</th>
                    <th>Son Değişiklik Tarihi</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KURUMLAR'");
                foreach ($sql as $row){ ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["definition_code"]; ?></td>
                        <td><?php echo $row["definition_name"]; ?></td>
                        <td><?php echo nettarih($row["insert_datetime"]); ?></td>
                        <td align="center" title="Durum"
                            <?php if ($row['status']){ ?>
                                hastane-id="<?php echo $row["id"]; ?>"
                                data-bs-target="#modal-getir"  data-bs-toggle="modal" class='hastaneguncelle' id="hastaneguncelle" <?php } ?> ><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>

                        <td align="center">
                            <i class="fa fa-pen-to-square " title="Düzenle" id="hastaneguncelle"
                                <?php if ($row['status']){ ?> hastane-id="<?php echo $row["id"]; ?>"   data-bs-target="#hasta-sinif-modal" data-bs-toggle="modal"  <?php } ?>
                               alt="icon"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle hsinifaktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash"
                                   title="İptal" hastane-id="<?php echo $row["id"]; ?>" id="hastanedeletemodal" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>

<div class="modal fade" id="hasta-sinif-modal" aria-hidden="true" >
    <div class="modal-dialog modal-lg" id="hasta-sinif-modal-icerik" style="margin-top: 90px;">
    </div>
</div>

    <script type="text/javascript">
        $('#sosyalguvencetab').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            dom: '<"clear">lfrtip',
            // initComplete: function () {
            //     $('.dataTables_filter input[type="search"]').css({ 'width': '1240px'});
            // }
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [{
                    text: 'Sosyal Güvence Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hasta-sinif-modal",
                    },
                    action: function ( e, dt, button, config ) {
                        $.get( "ajax/tanimlar/hastasiniftanimla.php?islem=modal-icerik",function(get){
                            $('#hasta-sinif-modal-icerik').html(get);
                        });
                    }

                }],
        });



        $("body").off("click", "#hastaneguncelle").on("click", "#hastaneguncelle", function(e){
            var getir = $(this).attr('hastane-id');
            $.get( "ajax/tanimlar/hastasiniftanimla.php?islem=modal-icerik", { guvenceid:getir },function(getveri){
                $('#hasta-sinif-modal-icerik').html(getveri);
            });
        });
        $("body").off("click",".hsinifaktif").on("click",".hsinifaktif", function (e) {
            var getir = $(this).attr('id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/hastasiniftanimla.php?islem=hsinif-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.hasta-sinif-tanim:first').load("ajax/tanimlar/hastasiniftanimla.php?islem=listeyi-getir");
                }
            });
        });


    </script>


    <script>
        $("body").off("click", "#hastanedeletemodal").on("click", "#hastanedeletemodal", function(e){
            var id = $(this).attr('hastane-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Sosyal Güvence Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/hastakurumtanimla.php?islem=hastanesilme',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/hastakurumtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.hasta-sinif-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Sosyal Güvence Silme İşlemini Onayla"});
        });
    </script>

<?php }elseif ($islem=="modal-icerik"){
    ?>
    <div class="modal-dialog"  >
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php  if ($_GET["guvenceid"] != "") {
                    echo '<h4 class="modal-title">Kurum düzenle</h4>';
                    $birimbilgisi = singular("transaction_definitions", "id", $_GET["guvenceid"]);
                    extract($birimbilgisi);
                } else { echo '<h4 class="modal-title">Kurum ekle</h4>'; }  ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formbina" action="javascript:void(0);" >
                <div class="modal-body">
                    <div class="row">

                        <div class="col-3">
                            <label for="basicpill-firstname-input" class="form-label">Skrs Kodu</label>
                        </div>

                        <div class="col-9">
                                <input type="number" class="form-control" name="definition_code" value="<?php echo $definition_code; ?>" id="basicpill-firstname-input">
                                <?php if ($birimbilgisi["id"]){ ?>
                                    <input type="hidden" class="form-control" name="id" value="<?php echo $birimbilgisi["id"]; ?>" id="basicpill-firstname-input">
                                <?php  } ?>
                        </div>

                    </div>
                    <div class="row mt-1">

                        <div class="col-3">
                            <label for="basicpill-firstname-input" class="form-label">Kurum Adı</label>
                        </div>

                        <div class="col-9">
                            <input type="text" class="form-control" name="definition_name" value="<?php echo $definition_name; ?>" id="basicpill-firstname-input">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["guvenceid"]){ ?>
                        <input class="btn btn-success hastaneupdate btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn hastaneinsert btn-sm btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", ".hastaneupdate").on("click", ".hastaneupdate", function(e){
                var gonderilenform = $("#formbina").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/hastakurumtanimla.php?islem=hastaneupdate',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/hastakurumtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.hasta-sinif-tanim:first').html(e);
                        });
                    }
                });
            });
            $("body").off("click", ".hastaneinsert").on("click", ".hastaneinsert", function(e){
                var gonderilenform = $("#formbina").serialize();
                document.getElementById("formbina").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/hastakurumtanimla.php?islem=hastaneekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/hastakurumtanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.hasta-sinif-tanim:first').html(e);
                        });
                    }
                });
            });

        });

    </script>

<?php }  ?>
