
<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];
?>

<?php
if($islem=="modal-icerik"){
    $bransbilgisi = singular("branch", "id", $_GET["bransid"]);
    extract($bransbilgisi); ?>

        <form class="modal-content" id="formbrans" action="javascript:void(0);">
            <div class="modal-header  text-white" style="background-color: #3F72AF">
                <?php if ($id){ ?>
                    <h4 class="modal-title ">Branş Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Branş Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">Durumu:</label>
                    </div>
                    <div class="col-10">
                        <select name="status" class="form-control">
                            <option value="1">aktif</option>
                            <option value="0">pasif</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">Branş Adı:</label>
                    </div>
                    <div class="col-10">
                        <input type="text" class="form-control" name="branch_name" value="<?php echo $branch_name; ?>">
                        <?php if ($_GET["bransid"] != "") { ?>
                            <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["bransid"]; ?>">
                        <?php } ?>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">Branş Kodu:</label>
                    </div>
                    <div class="col-10">
                        <input type="text" class="form-control" name="branch_code" value="<?php echo $branch_code; ?>">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <?php if ($id){ ?>
                    <input type="submit" class="btn  w-md justify-content-end btn-success btn-sm" id="bransguncelle"  data-bs-dismiss="modal"  value="Düzenle"/>
                <?php  }else{ ?>
                    <input type="submit" class="btn  w-md justify-content-end btn-success btn-sm" id="brans-kaydet" data-bs-dismiss="modal"  value="Kaydet"/>
                <?php } ?>
                <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
            </div>
        </form>

    <script>
                $("body").off("click", "#brans-kaydet").on("click", "#brans-kaydet", function(e){
                var gonderilenform = $("#formbrans").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/branstanimla.php?islem=brans-ekle',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/branstanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.brans-tanim:first').html(e);
                        });
                    }
                });

            });


                $("body").off("click", "#bransguncelle").on("click", "#bransguncelle", function(e){
                var gonderilenform = $("#formbrans").serialize();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/branstanimla.php?islem=brans-duzenle',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/branstanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.brans-tanim:first').html(e);
                        });
                    }
                });
            });
    </script>

<?php } if($islem=="listeyi-getir"){ ?>

        <div class="brans-tanim">
    <div class="card">

        <div class="card-body bg-white">
            <table id="branslar" class="table table-striped table-bordered w-100 display nawrap" >
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Branş Kodu</th>
                    <th>Branş Adı</th>
                    <th>Branş Durum</th>
                    <th>Işlem</th>
                </tr>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select * from branch ");
                foreach ($sql as $row){ ?>
                    <tr  >
                        <td <?php if ($row['status']){ ?> class='bransbilgisigetir'  brans-id="<?php echo $row["id"]; ?>" data-bs-target="#modal-getir" data-bs-toggle="modal" <?php } ?> > <?php if($row["status"]==0){?><del><?php } ?><?php echo $row["id"]; ?></td>
                        <td <?php if ($row['status']){ ?> class='bransbilgisigetir'  brans-id="<?php echo $row["id"]; ?>" data-bs-target="#modal-getir" data-bs-toggle="modal" <?php } ?> > <?php if($row["status"]==0){?><del><?php } ?><?php echo $row["branch_code"]; ?></td>
                        <td <?php if ($row['status']){ ?> class='bransbilgisigetir'  brans-id="<?php echo $row["id"]; ?>" data-bs-target="#modal-getir" data-bs-toggle="modal" <?php } ?> > <?php if($row["status"]==0){?><del><?php } ?><?php echo $row["branch_name"]; ?></td>
                        <td align="center"  <?php if ($row['status']){ ?> class='bransbilgisigetir'  brans-id="<?php echo $row["id"]; ?>" data-bs-target="#modal-getir" data-bs-toggle="modal" <?php } ?> title="Durum"><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>

                        <td align="center">
                            <i class="fa fa-pen-to-square brans-bilgisi-getir" title="Düzenle"
                                <?php if ($row['status']){ ?>  brans-id="<?php echo $row["id"]; ?>"   data-bs-target="#brans-modal" data-bs-toggle="modal"  <?php } ?>
                               alt="icon"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle bransaktif"
                                   title="Aktif Et" brans-id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash bransdeletemodal"
                                   title="İptal" brans-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>

<div class="modal fade modal-lg" id="brans-modal" role="dialog" aria-hidden="true" style="margin-top: 70px !important;">
    <div class="modal-dialog" id="brans-modal-icerik" role="document">

    </div>
</div>



    <script>
        Table = $('#branslar').DataTable({
            "scrolly": true,
            "autowidth": false,
            "scrollx": true,
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Branş Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#brans-modal",
                    },
                    action: function ( e, dt, button, config ) {

                        $.get("ajax/tanimlar/branstanimla.php?islem=modal-icerik",function(get){
                            $('#brans-modal-icerik').html(get);
                        });

                    }

                }
            ],
        });

        $(document).on("click",".brans-bilgisi-getir",function() {
            var bransid = $(this).attr('brans-id');
            $.get( "ajax/tanimlar/branstanimla.php?islem=modal-icerik", { bransid:bransid },function(getveri){
                $('#brans-modal-icerik').html(getveri);
            });
        });

        $("body").off("click",".bransaktif").on("click",".bransaktif", function (e) {
            var getir = $(this).attr('brans-id');
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/branstanimla.php?islem=bransaktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.brans-tanim:first').load("ajax/tanimlar/branstanimla.php?islem=listeyi-getir");
                }
            });
        });


    </script>

    <script>
        $("body").off("click", ".bransdeletemodal").on("click", ".bransdeletemodal", function(e){
            var id = $(this).attr('brans-id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Branş Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/branstanimla.php?islem=brans-sil',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/branstanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.brans-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Branş Silme İşlemini Onayla"});
        });
    </script>


<?php }
if ($islem=="brans-duzenle"){
    $_POST['update_datetime'] = $simdikitarih;
    $_POST['update_userid'] = $KULLANICI_ID;
    $ID=$_POST['id'];
    unset($_POST['id']);
    $sql = direktguncelle("branch","id",$ID,$_POST);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Branş Güncelleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Branş Güncelleme Başarısız');
        </script>

    <?php }
}

if ($islem=="brans-ekle"){

    $_POST['insert_datetime'] = $simdikitarih;
    $_POST['insert_userid'] = $KULLANICI_ID;

    $sql=direktekle("branch",$_POST);
    var_dump($sql);
    if ($sql == 1) { ?>
        <script>
            alertify.success('Branş Ekleme Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.error('Branş Ekleme Başarısız');
        </script>

    <?php }
}
if ($islem=="brans-sil"){

        $id=$_POST['id'];
        $detay=$_POST['delete_detail'];
        $silme=$KULLANICI_ID;
        $tarih=$_POST['delete_datetime'];
        $sql=canceldetail("branch","id",$id,$detay,$silme,$tarih);

        if ($sql == 1) { ?>
            <script>
                alertify.set('notifier','delay', 8);
                alertify.success('Branş Silme Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.set('notifier','delay', 8);
                alertify.error('Branş Silme Başarısız');
            </script>

        <?php }
}
if ($islem=="bransaktiflestir"){
    //var_dump($_POST['getir']);
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('branch','id',$id,$date,$user);
    var_dump($sql);
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
?>
