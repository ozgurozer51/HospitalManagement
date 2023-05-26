<?php
include "../../controller/fonksiyonlar.php";

$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$islem=$_GET['islem'];

if ($islem=="taniekle"){
    if($_POST) {
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $_POST['status'] = 1;

        $sql = direktekle("diagnoses",$_POST);
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

}elseif ($islem=="taniupdate"){
    if($_POST) {
        $_POST["update_datetime"] = $simdikitarih;
        $_POST["update_userid"] = $_SESSION["id"];
        $id=$_POST['id'];
        unset($_POST['id']);

        $sql = direktguncelle("diagnoses","id",$id,$_POST);
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
}elseif ($islem=="tanisilme"){
    if ($_POST){
        $id=$_POST['id'];
        unset($_POST['id']);
        $detay=$_POST['delete_detail'];
        $silme=$_SESSION["id"];
        $tarih=$_POST['delete_datetime'];

        $sql=canceldetail('diagnoses','id',$id,$detay,$silme,$tarih);
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
}elseif($islem=="tani-aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('diagnoses','id',$id,$date,$user);
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
        <div class="icd-tanim">

    <div class="card">
    
        <div class="card-body bg-white">
            <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="tanitablo">

                <thead class="table-light">
                <tr>
                    <th >Tanı No</th>
                    <th >Tanı Kodu</th>
                    <th >Tanı Adı</th>
                    <th >Durum</th>
                    <th >İşlem</th>
                </tr>
                </thead>
                <tbody>

                <?php  $say=0;
                $demo = "select * from diagnoses";
                $ameliyathanegetir = verilericoklucek($demo);
                foreach ($ameliyathanegetir as $row) { ?>

                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['diagnoses_id'] ?></td>
                        <td ><?php echo $row["diagnoses_name"] ?></td>
                        <td align="center" title="Durum"
                            <?php if ($row['status']){ ?>
                                tani-id="<?php echo $row["id"]; ?>"
                                data-bs-target="#modal-getir"  data-bs-toggle="modal" class='taniguncelle' <?php } ?> ><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>

                        <td align="center">
                            <i class="fa fa-pen-to-square taniguncelle" title="Düzenle"
                                <?php if ($row['status']){ ?> tani-id="<?php echo $row["id"]; ?>"   data-bs-target="#icd-modal" data-bs-toggle="modal"  <?php } ?>
                               alt="icon"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle taniaktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash tanideletemodal"
                                   title="İptal" tani-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
        </div>

<div class="modal fade modal-lg" id="icd-modal"  aria-hidden="true" style="margin-top: 90px;">
    <div class="modal-dialog" id="icd-modal-icerik">
    </div>
</div>

    <script type="text/javascript">
        $('#tanitablo').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Tanı Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#icd-modal",
                    },
                    action: function ( e, dt, button, config ) {
                        $.get( "ajax/tanimlar/icdtanimayarislem.php?islem=modal-icerik",function(get){
                            $('#icd-modal-icerik').html(get);
                        });
                    }

                }
            ],
        });

        $("body").off("click", ".taniguncelle").on("click", ".taniguncelle", function(e){
            var getir = $(this).attr('tani-id');
            $.get( "ajax/tanimlar/icdtanimayarislem.php?islem=modal-icerik", { getir:getir },function(getveri){
                $('#icd-modal-icerik').html(getveri);
            });
        });

        $("body").off("click",".taniaktif").on("click",".taniaktif", function (e) {
            var getir = $(this).attr('id');
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/icdtanimayarislem.php?islem=tani-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.icd-tanim:first').load("ajax/tanimlar/icdtanimayarislem.php?islem=listeyi-getir");
                }
            });
        });

        $("body").off("click", ".tanideletemodal").on("click", ".tanideletemodal", function(e){
            var id = $(this).attr('tani-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Birim Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/icdtanimayarislem.php?islem=tanisilme',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/icdtanimayarislem.php?islem=listeyi-getir", {}, function (e) {
                            $('.icd-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"tani Silme İşlemini Onayla"});
        });
    </script>

<?php }elseif ($islem=="modal-icerik"){
    $tani=singular("diagnoses","id",$_GET["getir"]);?>
    <div class="modal-dialog"  >
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php if ($_GET['getir']){ ?>
                    <h4 class="modal-title ">Tanı Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Tanı Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formtani" action="javascript:void(0);" >
                <div class="modal-body">
                    <div class="mx-2">

                            <div class="row">
                                <div class="col-2">
                                    <label  class="col-form-label">Tanı Adı:</label>
                                </div>
                                <div class="col-10">
                                    <input class="form-control" type="text" name="diagnoses_name" value="<?php echo $tani["diagnoses_name"]?>" id="example-text-input">
                                    <?php if ($_GET['getir']){ ?>
                                        <input class="form-control" type="hidden" name="id" value="<?php echo  $_GET['getir'];?>">
                                    <?php } ?>
                                </div>
                            </div>

                        <div class="row mt-1">
                            <div class="col-2">
                                <label class="col-form-label">Tanı Kodu:</label>
                            </div>
                            <div class="col-10">
                                <input class="form-control" type="text" name="diagnoses_id" value="<?php echo $tani["diagnoses_id"]?>">
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success  taniupdate btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn taniinsert btn-sm btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
                $("body").off("click", ".taniupdate").on("click", ".taniupdate", function(e){

                    var gonderilenform = $("#formtani").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/icdtanimayarislem.php?islem=taniupdate',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/icdtanimayarislem.php?islem=listeyi-getir", {}, function (e) {
                            $('.icd-tanim:first').html(e);
                        });
                    }
                });
            });
                $("body").off("click", ".taniinsert").on("click", ".taniinsert", function(e){
                var gonderilenform = $("#formtani").serialize();
                document.getElementById("formtani").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/icdtanimayarislem.php?islem=taniekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/icdtanimayarislem.php?islem=listeyi-getir", {}, function (e) {
                            $('.icd-tanim:first').html(e);
                        });
                    }
                });
            });

        });

    </script>

<?php }
?>
