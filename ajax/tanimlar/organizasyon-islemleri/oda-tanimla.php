<?php
include "../../../controller/fonksiyonlar.php";
$islem=$_GET['islem'];

if ($islem=="listeyi-getir"){ ?>

        <div class="oda-tanim">
<div class="card">

    <div class="card-body bg-white">
        <table class="table table-bordered table-sm table-hover mt-2" id="oda-table">
        <thead class="table-light">
        <tr>
            <th >Oda No</th>
            <th >Oda Adı</th>
            <th >Kat Adı</th>
            <th >Bina Adı</th>
            <th >Durum</th>
            <th >İşlem</th>
        </tr>
        </thead>
        <tbody>
          <?php $hello = verilericoklucek("SELECT hospital_room.id as odaid,hospital_room.status as durum,hospital_room.*,hospital_floor.*,hospital_building.* FROM hospital_room 
    inner join hospital_floor on hospital_room.floor_id=hospital_floor.id 
    inner join hospital_building on hospital_floor.building_id=hospital_building.id");
            foreach ($hello as $row) { ?>
            <tr >
                <td><?php echo $row['odaid'] ?></td>
                <td ><?php echo $row["room_name"] ?></td>
                <td ><?php echo $row["floor_name"] ?></td>
                <td><?php echo $row['building_name'] ?></td>
                <td align="center"><?php  if ($row["durum"]=='1'){ ?> <b style="color: green">Aktif</b> <?php }elseif ($row["durum"]=='0'){?> <b style="color: darkred">Pasif</b>  <?php } ?></td>
                <td align="center">
                    <i class="fa fa-pen-to-square oda-guncelle" title="Düzenle"
                        <?php if ($row['durum']){ ?>  oda-id="<?php echo $row["odaid"]; ?>"   data-bs-target="#oda-modal-getir" data-bs-toggle="modal"  <?php } ?>
                       alt="icon"></i>

                    <?php if($row['durum']=='0'){ ?>

                        <i class="fa-solid fa-recycle oda-aktif" title="Aktif Et" oda-id="<?php echo $row["odaid"]; ?>" alt="icon" ></i>
                    <?php }else{ ?>

                        <i class="fa fa-trash oda-delete-modal" title="İptal" oda-id="<?php echo $row["odaid"]; ?>" alt="icon"></i>
                    <?php } ?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    </div>
</div>
        </div>


    <div class="modal fade" id="oda-modal-getir" aria-hidden="true" style="margin-top: 70px !important;">
        <div class="modal-dialog" id="oda-modal-icerik">
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#oda-table').DataTable({
                "scrollY": "60vh",
                "autoWidth": false,
                "scrollX": true,
                "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        text: 'Oda Ekle',
                        className: 'btn btn-success btn-sm btn-kaydet',
                        attr:  {
                            'data-bs-toggle':"modal",
                            'data-bs-target':"#oda-modal-getir",
                               },
                        action: function ( e, dt, button, config ) {
                            $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=oda-tanim",function(get){
                                $('#oda-modal-icerik').html(get);
                            });
                        }
                    }],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                }
            });


            $("body").off("click",".oda-guncelle").on("click",".oda-guncelle", function (e) {
            var getir = $(this).attr('oda-id');
            $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=oda-tanim", { getir:getir },function(getveri){
                $('#oda-modal-icerik').html(getveri);

            });
        });

        $("body").off("click",".oda-aktif").on("click",".oda-aktif", function (e) {
            var getir = $(this).attr('oda-id');
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=oda-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.oda-tanim:first').load("ajax/tanimlar/organizasyon-islemleri/oda-tanimla.php?islem=listeyi-getir");
                }
            });
        });

            $("body").off("click", ".oda-delete-modal").on("click", ".oda-delete-modal", function(e){
            var id = $(this).attr('oda-id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Oda Silme Nedeni..'></textarea>" , function(){
                var delete_detail = $('#delete_detail').val();
                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=oda-sil',
                    data: {
                        id,
                        delete_detail,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/oda-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.oda-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Oda Silme İşlemini Onayla"});
        });

        });
    </script>


<?php } elseif ($islem == "oda-servis") {
    $katid = $_POST['katid']; ?>

    <option value="">birim seçiniz</option>
    <?php $kullanicininidsi = $_SESSION ["id"];
    $yetkilioldugumbirimler = birimyetkiselect($kullanicininidsi);

    $bolumgetir = "select * from units where unit_type = '1' and hospital_floorid=$katid  $yetkilioldugumbirimler order by depatrment_name asc ";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["depatrment_name"]; ?></option>
    <?php }
} elseif ($islem == "bina-id-getir") {
    $binano = $_POST['binano'];
    echo " <option value=''>kat seçiniz..</option>";
    $bolumgetir = "select * from hospital_floor where building_id=$binano and status='1' order by floor_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="' . $value['id'] . '">' . $value['floor_name'] . '</option>';
    }
} ?>