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
        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];
        $tanim_kodu=$_POST["definition_code"];

        if($tanim_kodu!="")
        {
            $sorguvarmi= tek("select * from transaction_definitions where definition_code='$tanim_kodu' and status!='2'");
            if($sorguvarmi){
                 ?>
                    <script>
                        alertify.error('Eklemek istediğiniz kurum kodu sistem tanımlı. başka bir kurum kodu giriniz');
                    </script>

                <?php
            }
            else{
                $birimtanimla=direktekle("transaction_definitions", $_POST);
                if ($birimtanimla == 1) { ?>
                    <script>
                        alertify.success('Işlem Başarılı');
                    </script>
                <?php   }
                else { ?>
                    <script>
                        alertify.error('Işlem Başarısız');
                    </script>

                <?php }
            }
        }

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
} elseif($islem=="hkurum-aktiflestir"){
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
  <div class="hasta-kurum-tanim">
    <div class="card">
        <div class="card-header px-2 text-white fw-bold" style="background-color:#3F72AF;height:39px;">
            <div class="row">
                <div class="col-md-8">Bağlı Kurum Listesi</div>

            </div>

        </div>
        <div class="card-body bg-white">
            <table id="kuruktab" class="table table-striped table-bordered" style=" background:white;width: 100%;">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Kurum Kodu</th>
                    <th>Kurum Adı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
                </thead>
                <tbody style="cursor: pointer;">
                <?php
                //sosyal güvence tanimlari skrs kodu
                $sql = verilericoklucek("select * from transaction_definitions where definition_type='SOSYALGUVENCE'");
                foreach ($sql as $row){
                    $kurum = singular("transaction_definitions", "definition_code", $row["definition_supplement"]);?>
                    <tr >
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo  $kurum["definition_name"] ?></td>
                        <td><?php echo  $row["definition_name"] ?></td>
                        <td align="center" title="Durum"
                            <?php if ($row['status']){ ?>
                                hastane-id="<?php echo $row["id"]; ?>"
                                data-bs-target="#modal-getir"  data-bs-toggle="modal" class='hastaneguncelle' id="hastaneguncelle" <?php } ?> ><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>

                        <td align="center">
                            <i class="fa fa-pen-to-square " title="Düzenle" id="hastaneguncelle"
                                <?php if ($row['status']){ ?> hastane-id="<?php echo $row["id"]; ?>"   data-bs-target="#hasta-kurum-modal" data-bs-toggle="modal"  <?php } ?>
                               alt="icon"></i>

                            <?php if($row['status']=='0'){ ?>
                                <i class="fa-solid fa-recycle hkurumaktif"
                                   title="Aktif Et" id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                            <?php }else{ ?>

                                <i class="fa fa-trash " id="hastanedeletemodal"
                                   title="İptal" hastane-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>


<div class="modal fade modal-lg" id="hasta-kurum-modal" aria-hidden="true" style="margin-top: 80px;">
    <div class="modal-dialog" id="hasta-kurum-modal-icerik">
    </div>
</div>


    <script type="text/javascript">

        $('#kuruktab thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#kuruktab thead');

        var table = $('#kuruktab').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Kurum Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#hasta-kurum-modal",
                    },
                    action: function ( e, dt, button, config ) {

                        $.get( "ajax/tanimlar/hastakurumtanimla.php?islem=modal-icerik",function(get){
                            $('#hasta-kurum-modal-icerik').html(get);
                        });
                    }
                }],

            initComplete: function () {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('change', function (e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function (e) {
                                e.stopPropagation();

                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            },
        });

        $("body").off("click", "#hastaneguncelle").on("click", "#hastaneguncelle", function(e){

            var getir = $(this).attr('hastane-id');
            $.get( "ajax/tanimlar/hastakurumtanimla.php?islem=modal-icerik", { guvenceid:getir },function(getveri){
                $('#hasta-kurum-modal-iceri').html(getveri);
            });
        });
        $("body").off("click",".hkurumaktif").on("click",".hkurumaktif", function (e) {
            var getir = $(this).attr('id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/hastakurumtanimla.php?islem=hkurum-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.hasta-kurum-tanim:first').load("ajax/tanimlar/hastakurumtanimla.php?islem=listeyi-getir");
                }
            });
        });


        $("body").off("click", "#hastanedeletemodal").on("click", "#hastanedeletemodal", function(e){
            var id = $(this).attr('hastane-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kurum Silme Nedeni..'></textarea>" +
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
                            $('.hasta-kurum-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Kurum Silme İşlemini Onayla"});
        });
    </script>

<?php }elseif ($islem=="modal-icerik"){
   ?>
    <div class="modal-dialog"  >
        <!-- modal content-->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php  if ($_GET["guvenceid"] != "") {
                    echo '<h4 class="modal-title">Kurum Düzenle</h4>';
                    $birimbilgisi = singular("transaction_definitions", "id", $_GET["guvenceid"]);
                    extract($birimbilgisi);
                } else { echo '<h4 class="modal-title">Kurum Ekle</h4>'; }  ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formbina" action="javascript:void(0);" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">Sosyal Güvence</label>
                                <div class="col-md-12">
                                    <select class="form-select" name="definition_supplement" >
                                    <?php $hello=verilericoklucek("SELECT * FROM transaction_definitions where definition_type='KURUMLAR'");
                                    foreach ($hello as $rowa) {
                                        ?>
                                        <option <?php if($birimbilgisi['definition_supplement']==$rowa["definition_code"]){ echo "selected"; } ?> value="<?php echo $rowa["definition_code"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">Kurum Kodu</label>
                                <div class="col-md-12">
                                    <input class="form-control" type="number" name="definition_code" value="<?php echo $birimbilgisi['definition_code']; ?>" id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-3 col-form-label">Kurum Adı</label>
                                <div class="col-md-12">
                                    <input class="form-control" type="text"  name="definition_name" value="<?php echo $birimbilgisi['definition_name']; ?>" id="example-text-input">
                                    <input class="form-control" type="hidden" value="SOSYALGUVENCE"  name="definition_type" id="example-text-input">
                                    <?php if ($birimbilgisi['id']){ ?>
                                        <input class="form-control" type="hidden" value="<?php echo $birimbilgisi['id']; ?>"  name="id" id="example-text-input">
                                    <?php } ?>
<!--                                    <input class="form-control" type="hidden" value="--><?php //echo $_GET["tanim_kodu"]; ?><!--"  name="definition_supplement" id="example-text-input">-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default " class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["guvenceid"]){ ?>
                        <input class="btn btn-success  hastaneupdate " style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn   hastaneinsert  btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
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
                            $('.hasta-kurum-tanim:first').html(e);
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
                            $('.hasta-kurum-tanim:first').html(e);
                        });
                    }
                });
            });

        });

    </script>

<?php }  ?>
