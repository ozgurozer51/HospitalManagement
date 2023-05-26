<?php
include "../../../controller/fonksiyonlar.php";
$islem=$_GET['islem'];


if ($islem=="listeyi-getir"){ ?>

        <div class="kat-tanim">
    <div class="card">

    <div class="card-body bg-white">
    <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="kat-table">

        <thead class="table-light">
        <tr>
            <th >Kat No</th>
            <th >Kat Adı</th>
            <th >Bina Adı</th>
            <th >Durum</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>

        <?php $say=0;
        $sql = verilericoklucek("select * from hospital_floor");
        foreach ($sql as $row){
            $binaid=$row['building_id'];
            $binabilgi=singular("hospital_building","id",$binaid); ?>

            <tr>
                <td><?php echo $row['id'] ?></td>
                <td ><?php echo $row["floor_name"] ?></td>
                <td><?php echo $binabilgi['building_name'] ?></td>
                <td><?php  if ($row["status"]=='1'){ ?> <b style="color: green">Aktif</b> <?php }elseif ($row["status"]=='0'){?> <b style="color: darkred">Pasif</b>  <?php } ?></td>
                <td align="center">

                    <i class="fa fa-pen-to-square kat-guncelle" title="Düzenle"<?php if ($row['status']){ ?>  kat-id="<?php echo $row["id"]; ?>"   data-bs-target="#kat-modal" data-bs-toggle="modal"  <?php } ?>alt="icon"></i>

                    <?php if($row['status']=='0'){ ?>
                        <i class="fa-solid fa-recycle kat-aktif" title="Aktif Et" kat-id="<?php echo $row["id"]; ?>" alt="icon" ></i>

                    <?php }else{ ?>

                        <i class="fa fa-trash kat-delete-modal" title="İptal" kat-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                    <?php } ?>
                </td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
    </div>
</div>
        </div>

        <div class="modal fade" id="kat-modal" aria-hidden="true" style="margin-top: 100px !important;">
            <div class="modal-dialog" id="kat-modal-icerik">
            </div>
        </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#kat-table').DataTable({
                "scrollY": true,
                "scrollY": "60vh",
                "autoWidth": false,
                "scrollX": true,
                "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        text: 'Kat Ekle',
                        className: 'btn btn-success btn-sm btn-kaydet',
                        attr:  {
                            'data-bs-toggle':"modal",
                            'data-bs-target':"#kat-modal",
                        },
                        action: function ( e, dt, button, config ) {
                            $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=kat-tanimla",function(get){
                                $('#kat-modal-icerik').html(get);
                            });
                        }
                    }
                ],

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                }
            });

        $("body").off("click", ".kat-guncelle").on("click", ".kat-guncelle", function(e){
                    var getir = $(this).attr('kat-id');
                $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=kat-tanimla", { getir:getir },function(getveri){
                    $('#kat-modal-icerik').html(getveri);
                });
            });
        });

        $("body").off("click",".kat-aktif").on("click",".kat-aktif", function (e) {
            var getir = $(this).attr('kat-id');
            //alert(getir);
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=kat-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.kat-tanim:first').load("ajax/tanimlar/organizasyon-islemleri/kat-tanimla.php?islem=listeyi-getir");
                }
            });
        });

        $("body").off("click", ".kat-delete-modal").on("click", ".kat-delete-modal", function(e){
            var id = $(this).attr('kat-id');
            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kat Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text' >", function(){
                var delete_detail = $('#delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=kat-sil',
                    data: {
                        id,
                        delete_detail,

                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/kat-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.kat-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok:"Onayla", cancel: "Vazgeç"}}).set({title:"Kat Silme İşlemini Onayla"});
        });
    </script>

<?php }





