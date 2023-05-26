<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$islem=$_GET["islem"];
$simdikitarih = date('Y-m-d H:i:s');

session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];

if ($islem=="kasaek"){
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $KULLANICI_ID;

        $sql = direktekle("safe",$_POST);
        if ($sql == 1) { ?>
            <script>
                alertify.set('notifier','delay', 8);
                alertify.success('Kasa Ekleme İşlemi Başarılı');
            </script>
        <?php   } else { ?>
            <script>
                alertify.set('notifier','delay', 8);
                alertify.error('Kasa Ekleme İşlemi Başarısız');
            </script>

        <?php } }

else if ($islem=="kasaupdate"){
    $id=$_POST["id"];
    unset($_POST["id"]);
    $_POST["update_datetime"]=$simdikitarih;
    $_POST["update_userid"]=$KULLANICI_ID;
    unset($_POST["id"]);
    $sql=direktguncelle("safe","id",$id,$_POST);

    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Kasa Güncelleme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Kasa Güncelleme İşlemi Başarısız');
        </script>

    <?php }
}
else if($islem=="kasa-sil"){
    $id=$_POST['id'];
    $detay=$_POST['delete_detail'];
    $silme=$KULLANICI_ID;
    $tarih=$simdikitarih;
    $sql=canceldetail("safe","id",$id,$detay,$silme,$tarih);
    if ($sql == 1) { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.success('Kasa Silme İşlemi Başarılı');
        </script>
    <?php   } else { ?>
        <script>
            alertify.set('notifier','delay', 8);
            alertify.error('Kasa Silme İşlemi Başarısız');
        </script>

    <?php } }

else  if ($islem=="modal-icerik"){
    $kasaid = $_GET['getir'];
    $kasagetir=singular("safe","id",$kasaid); ?>

    <div class="modal-dialog"  >
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php if ($_GET['getir']){ ?>
                    <h4 class="modal-title ">Vezne Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Vezne Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formkasa" action="javascript:void(0);" >
                <div class="modal-body">
                    <?php if ($kasaid){ ?>
                        <input type="hidden" name="id"  value="<?php echo $kasaid ?>"/>
                  <?php  }?>


                    <div class="row">
                        <div class="col-3">
                            <label class="form-label">Kasa Adı:</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control" name="safe_name"
                                   value="<?php echo $kasagetir['safe_name'] ?>" id="basicpill-firstname-input">
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                            <label for="basicpill-firstname-input" class="form-label">Kasa Kodu</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control" name="safe_id" value="<?php echo $kasagetir['safe_id'] ?>" id="basicpill-firstname-input">
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-3">
                        <label class="form-label">Bina:</label>
                        </div>
                        <div class="col-9">

                            <select class="form-select col-lg-6"  name="building" id="bina" >
                                <?php  $sql = verilericoklucek("select * from transaction_definitions where definition_type='BINA'");
                                foreach ($sql as $row){
                                    extract($row);  ?>
                                    <option <?php if ($kasagetir['building'] ==$id ) {  echo "selected"; } ?> value="<?php echo $id ?>" ><?php echo $definition_name ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success  vezneupdate btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn   vezneinsert btn-sm btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#bina").change(function () {
                var binano = $(this).val();
                $.ajax({
                    type: "post",
                    url: "ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=binaidgetir",
                    data: {"binano": binano},
                    success: function (e) {
                        $("#kat").html(e);
                    }
                })
            })

        });
    </script>
    <script>
        $(document).ready(function(){
            $("body").off("click", ".vezneupdate").on("click", ".vezneupdate", function(e){
                var gonderilenform = $("#formkasa").serialize();
                $.ajax({
                    type:'post',
                    url:'ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=kasaupdate',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=listeyi-getir", { },function(e){
                            $('.vezne-tanim').html(e);

                        });
                    }
                });

            });

            $("body").off("click", ".vezneinsert").on("click", ".vezneinsert", function(e){
                var gonderilenform = $("#formkasa").serialize();
                document.getElementById("formkasa").reset();
                $.ajax({
                    type:'post',
                    url:'ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=kasaek',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get( "ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=listeyi-getir", { },function(e){
                            $('.vezne-tanim').html(e);

                        });
                    }
                });

            });

        });

    </script>
<?php }

else if ($islem=="listeyi-getir"){ ?>

        <div class="vezne-tanim">
<div class="card">
  
    <div class="card-body bg-white">
    <table id="kasatab" class="table  table-bordered table-hover w-100 display nowrap" style="background:white;width: 100%;">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kasa Kodu</th>
            <th scope="col">Kasa Adı</th>
            <th scope="col">Tarih</th>
            <th scope="col">Durum</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody style="cursor:pointer;">
        <?php  $say = 0;
        $sql = verilericoklucek("select * from safe");
        foreach ($sql as $row) {
            ++$say; ?>
            <tr>
                <td><?php echo $say ?></td>
                <td ><?php echo $row["safe_id"] ?></td>
                <td ><?php echo $row["safe_name"] ?></td>
                <td ><?php echo $row["insert_datetime"] ?></td>
                <td align="center" title="Durum"
                    <?php if ($row['status']){ ?>
                        vezne-id="<?php echo $row["id"]; ?>"
                        data-bs-target="#modal-getir"  data-bs-toggle="modal" class='vezneguncelle' id="vezneguncelle" <?php } ?> ><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                <td align="center">
                    <i class="fa fa-pen-to-square vezneguncelle" title="Düzenle"
                        <?php if ($row['status']){ ?> vezne-id="<?php echo $row["id"]; ?>"   data-bs-target="#vezne-modal" data-bs-toggle="modal"  <?php } ?>
                       alt="icon"></i>

                    <?php if($row['status']=='0'){ ?>
                        <i class="fa-solid fa-recycle vezneaktif"
                           title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                    <?php }else{ ?>

                        <i class="fa fa-trash veznedeletemodal"
                           title="İptal" vezne-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                    <?php } ?>
                </td>
            </tr>

        <?php } ?>

        </tbody>
    </table>
    </div>
</div>
        </div>

<div class="modal fade" id="vezne-modal"  role="dialog" aria-hidden="true" style="margin-top: 80px !important;">
    <div class="modal-dialog" id="vezne-modal-icerik" role="document">
    </div>
</div>


    <script type="text/javascript">
        $('#kasatab').DataTable({
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            dom: '<"clear">lfrtip',
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Vezne Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#vezne-modal",
                    },
                    action: function ( e, dt, button, config ) {

                        $.get( "ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=modal-icerik",function(get){
                            $('#vezne-modal-icerik').html(get);
                        });
                    }

                }
            ],
        });



        $(document).on('click', '.vezneguncelle', function () {
            var getir = $(this).attr('vezne-id');
            $.get( "ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=modal-icerik", { getir:getir },function(getveri){
                $('#vezne-modal-icerik').html(getveri);
            });
        });

        $("body").off("click",".vezneaktif").on("click",".vezneaktif", function (e) {
            var getir = $(this).attr('id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=vezne-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.vezne-tanim').load("ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=listeyi-getir");
                }
            });
        });


    </script>


    <script>
        $("body").off("click", ".veznedeletemodal").on("click", ".veznedeletemodal", function(e){
            var id = $(this).attr('vezne-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='vezne Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' id='delete_datetime' name='delete_datetime' value='<?php echo $simdikitarih?>'>", function(){
                var delete_detail = $('#delete_detail').val();
                var delete_datetime = $('#delete_datetime').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=kasa-sil',
                    data: {
                        id,
                        delete_detail,
                        delete_datetime,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/vezne-tanimlari/vezne-tanimlari.php?islem=listeyi-getir", {}, function (e) {
                            $('.vezne-tanim').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"vezne Silme İşlemini Onayla"});
        });
    </script>
<?php }

elseif($islem=="binaidgetir"){
    $binano=$_POST['binano'];
    echo " <option value=''>Kat seçiniz..</option>";
    $bolumgetir = "select * from hospital_floor where building_id=$binano and status='1' order by floor_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="'.$value['id'].'">'.$value['floor_name'].'</option>';

    }
}elseif($islem=="vezne-aktiflestir"){
    $id = $_POST['getir'];
    $date= $simdikitarih;
    $user=$_SESSION["id"];
    $sql = backcancel('safe','id',$id,$date,$user);
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


 ?>
