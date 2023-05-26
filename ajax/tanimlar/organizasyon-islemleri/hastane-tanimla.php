<?php
include "../../../controller/fonksiyonlar.php";


if ($_GET['islem'] == "listeyi-getir") { ?>
    <div class="tanimdetay">
        <div class="card">

            <div class="card-body bg-white">
                <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;"
                       id="hastane-table">

                    <thead class="table-light">
                    <tr>
                        <th>Hastane No</th>
                        <th>Hastane Adı</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $say = 0;
                    $sql = verilericoklucek("select * from hospital");
                    foreach ($sql as $row) { ?>
                        <tr>
                            <td><?php echo $row['id'] ?></td>
                            <td><?php echo $row["hospital_name"] ?></td>
                            <td align="center" title="Durum"
                                <?php if ($row['status']) { ?>
                                    hastane-id="<?php echo $row["id"]; ?>"
                                    data-bs-target="#modal-getir"  data-bs-toggle="modal" class='hastaneguncelle' id="hastaneguncelle" <?php } ?> ><?php if ($row["status"] == 1) {
                                    echo "<span style='color:green;'>Aktif</span>";
                                } else {
                                    echo "<span style='color: red;'>Pasif</span>";
                                } ?></td>
                            <td>
                                <i class="fa fa-pen-to-square" title="Düzenle" id="hastaneguncelle"
                                    <?php if ($row['status']) { ?>  hastane-id="<?php echo $row["id"]; ?>"   data-bs-target="#modal-getir" data-bs-toggle="modal"  <?php } ?>
                                   alt="icon"></i>
                                <?php if ($row['status'] == '0') { ?>
                                    <i class="fa-solid fa-recycle hastaneaktif ml-1"
                                       title="Aktif Et" hastane-id="<?php echo $row["id"]; ?>" alt="icon"></i>

                                <?php } else { ?>
                                    <i class="fa fa-trash hastanedeletemodal"
                                       title="İptal"
                                        <?php if ($row['status']) { ?> hastane-id="<?php echo $row["id"]; ?>"   <?php } ?>></i>
                                <?php } ?>

                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-getir" data-bs-backdrop="static" aria-hidden="true"  style="margin-top: 120px !important;">
        <div class="modal-dialog modal-lg " id='modal-tanim-icerik'></div>
    </div>

    <div class="modal fade" id="hastane-modal-getir" data-bs-backdrop="static"  aria-hidden="true"  style="margin-top: 120px !important;">
        <div class="modal-dialog modal-lg" id="hastane-modal-tanim-icerik"></div>
    </div>

    <script type="text/javascript">


        $('#hastane-table').DataTable({
            "responsive": true,
            "paging":false,
            "scrollY": '60vh',
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,
            "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                text: 'Hastane Ekle',
                className: 'btn btn-success btn-sm btn-kaydet',
                attr: {
                    'data-bs-toggle': "modal",
                    'data-bs-target': "#hastane-modal-getir",
                },
                action: function (e, dt, button, config) {
                    var secilen = $(".btntanimla-dom").attr('id');
                    $.get("ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=hastane-ekle-modal", function (get) {
                        $('#hastane-modal-tanim-icerik').html(get);
                    });
                }
            }],
        });

        $("body").off("click", "#hastaneguncelle").on("click", "#hastaneguncelle", function (e) {
            var getir = $(this).attr('hastane-id');
            $.get("ajax/tanimlar/organizasyon-islemleri/organizasyon-modal.php?islem=hastane-ekle-modal", {getir: getir}, function (getveri) {
                $('#modal-tanim-icerik').html(getveri);
            });
        });

        $("body").off("click", ".hastaneaktif").on("click", ".hastaneaktif", function (e) {
            var getir = $(this).attr('hastane-id');
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=hastane-aktiflestir',
                data: {getir},
                success: function (e) {
                    $('#sonucyaz').html(e);
                    $('.tanimdetay:first').load("ajax/tanimlar/organizasyon-islemleri/hastane-tanimla.php?islem=listeyi-getir");


                }
            });
        });

        $("body").off("click", ".hastanedeletemodal").on("click", ".hastanedeletemodal", function (e) {
            var id = $(this).attr('hastane-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Hastane Silme Nedeni..'></textarea>" +
                "<input class='form-control' hidden type='text'>", function () {
                var delete_detail = $('#delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/organizasyon-islemleri/organizasyon-sql.php?islem=hastane-sil',
                    data: {
                        id,
                        delete_detail,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/organizasyon-islemleri/hastane-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.tanimdetay:first').html(e);
                        });
                        $('.alertify').remove();
                    }
                });

            }, function () {
                alertify.warning('Silme İşleminden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Hastane Silme İşlemini Onayla"});
        });
    </script>

<?php }