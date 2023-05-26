<?php
include "../../../controller/fonksiyonlar.php";
$islem=$_GET['islem'];

if ($islem=="listeyi-getir"){ ?>
    <div class="bina-tanim">
    <div class="card">
        
        <div class="card-body bg-white">
            <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="bina-table">
                <thead class="table-light">
                <tr>
                    <th >Bina No</th>
                    <th >Skrs Kuruk Kodu</th>
                    <th >Bina Adı</th>
                    <th >Bina Adresi</th>
                    <th >Durum</th>
                    <th >İşlem</th>
                </tr>
                </thead>
                <tbody>

                <?php  $say=0;
                $sql = verilericoklucek("select * from hospital_building");
                foreach ($sql as $row) { ?>
                    <tr>
                        <td><?php echo $row['id'] ?></td>
                        <td><?php echo $row['skrs_institution_code'] ?></td>
                        <td ><?php echo $row["building_name"] ?></td>
                        <td><?php echo $row["building_address"] ?></td>
                        <td align="center" title="Durum"<?php if ($row['status']){ ?> bina-id="<?php echo $row["id"]; ?>"data-bs-target="#bina-tanim-modal"  data-bs-toggle="modal" class="bina-guncelle" <?php } ?> ><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center"><i class="fa fa-pen-to-square bina-guncelle" title="Düzenle"<?php if ($row['status']){ ?>  bina-id="<?php echo $row["id"]; ?>"   data-bs-target="#bina-tanim-modal" data-bs-toggle="modal"  <?php } ?>alt="icon"></i><?php if($row['status']=='0'){ ?><i class="fa-solid fa-recycle bina-aktif" title="Aktif Et" bina-id="<?php echo $row["id"]; ?>" alt="icon" ></i><?php }else{ ?><i class="fa fa-trash bina-delete-modal" title="İptal"<?php if ($row['status']){ ?> bina-id="<?php echo $row["id"]; ?>"   <?php } ?>></i><?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="modal fade" id="bina-tanim-modal"  aria-hidden="true" style="margin-top: 100px !important;">
        <div class="modal-dialog modal-lg" id="bina-tanim-icerik"></div>
    </div>


    <script type="text/javascript">
        $('#bina-table').DataTable({
            "responsive": true,
            "paging": false,
            "scrollY": "60vh",
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [
                {
                    text: 'Bina Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#bina-tanim-modal",
                    },

                    action: function ( e, dt, button, config ) {

                        $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=bina-tanim-icerik",function(get){
                            $('#bina-tanim-icerik').html(get);
                        });
                    }

                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });


       $("body").off("click", ".bina-guncelle").on("click", ".bina-guncelle", function(e){
                var getir = $(this).attr('bina-id');
            $.get( "ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=bina-tanim-icerik", { getir:getir },function(getveri){
                $('#bina-tanim-icerik').html(getveri);
            });
        });

        $("body").off("click",".bina-aktif").on("click",".bina-aktif", function (e) {
            var getir = $(this).attr('bina-id');
            $.ajax({
                type:'POST',
                url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=bina-aktiflestir',
                data:{getir},
                success:function(e){
                    $('#sonucyaz').html(e);
                    $('.bina-tanim:first').load("ajax/tanimlar/organizasyon-islemleri/bina-tanimla.php?islem=listeyi-getir");
                }
            });
        });

            $("body").off("click", ".bina-delete-modal").on("click", ".bina-delete-modal", function(e){
            var id = $(this).attr('bina-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Bina Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text'>", function(){

                 var delete_detail = $('#delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=bina-sil',
                    data: {
                        id,
                        delete_detail,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/bina-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.bina-tanim:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Bina Silme İşlemini Onayla"});
        });
    </script>

<?php } ?>
