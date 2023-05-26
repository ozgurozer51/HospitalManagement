<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem = $_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];

if ($islem == "listeyi-getir") {
    ?>

    <div class="row">
        <div class="col-lg-4">
            <div class="card" style=" height: 100%; ">
                <div class="card-header px-2 text-white fw-bold" style="background-color:#3F72AF;height:39px;">
                    <div class="row">
                        <div class="col-md-8">Tektik Grubu</div>
                    </div>

                </div>
                <div class="card-body">

                    <div id='hizmetfiyatsonuc'></div>

                    <div class="table-responsive" style=" height: 38rem; overflow: auto; ">

                        <table class="table table-bordered nowrap display w-100 " id="tetkikler"
                               style="cursor: pointer;">
                            <thead>
                            <tr>
                                <th>İşlem Adı</th>
                                <th>Tetkik Grub Adı</th>
                                <th>Hedef Grubu</th>
                                <th>Birimi</th>
                                <th>Açıklama</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $sql = "select tdt.transaction_name as islemadi,tdt.id as tdetid, tdt.unit_id as birimid,tdt.transaction_explain as tetkikack,tdf.definition_name as grubadi,tdf.definition_supplement as hedefgrup from transaction_detail as tdt inner join transaction_definitions as tdf on tdt.transaction_id=tdf.id where tdf.definition_type='TETKIK_GRUBU'and tdt.status='1' and tdf.status='1'";
                            $sql = verilericoklucek($sql);
                            foreach ($sql as $row) {
                                ?>
                                <tr class="tfiyatgetir" id="<?php echo $row["tdetid"]; ?>">
                                    <td><?php echo $row["islemadi"]; ?></td>
                                    <td><?php echo $row["grubadi"]; ?></td>
                                    <td><?php if ($row["hedefgrup"] != '') {
                                            echo islemtanimgetir($row["hedefgrup"]);
                                        } ?></td>
                                    <td><?php echo $row["birimid"] ?></td>
                                    <td><?php echo $row["tetkikack"]; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card" id='tetkik-fiyat-list'>
                <span style="font-size: 20px;padding: 30px;">Sol taraftan i̇şlem seçiniz.</span>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".tfiyatgetir").click(function () {
                var tetkikId = $(this).attr('id');
                $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik_fiyat_list", {tetkikId: tetkikId}, function (getveri) {
                    $('#tetkik-fiyat-list').html(getveri);
                });
            });

            $('#tetkikler').DataTable({
                "lengthChange": false,
                "processing": true,
                "scrollY": false,
                "scrollX": false,
                "paging": true,
                'Visible': true,
                "responsive": true,
                "pageLength": 50,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        });
    </script>


    <?php
}
//seçilen tetkiklere tanımlanmış fiyat listesi******************************************************************
else if ($islem == "tetkik_fiyat_list"){
$tetkikId = $_GET["tetkikId"];
?>
<div class="card">
    <div class="card-header px-2 text-white fw-bold" style="background-color:#3F72AF;height:39px;">
        <div class="row">
            <div class="col-md-8">Tetkik Fiyat İşlemleri</div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered display nowrap w-100" id="tFiyatTable">
            <thead>
            <tr>
                <th>Özel Kod</th>
                <th>Kurum</th>
                <th>Ayaktan ücret</th>
                <th>Yatan Acil Ücret</th>
                <th>Ayaktan fark Ücreti</th>
                <th>Yatan Acil Fark Ücreti</th>
                <th>Özel Hizmet Bedeli</th>
                <th>Alt Kurum</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = verilericoklucek("select * from transaction_details_costs where process_detail_id='$tetkikId'");
            foreach ($sql as $row) { ?>
                <tr>
                    <td><?php echo $row["special_code"] ?></td>
                    <td><?php echo $row["institution_id"] ?></td>
                    <td><?php echo $row["standing_cost"] ?></td>
                    <td><?php echo $row["lying_urgent_cost"] ?></td>
                    <td><?php echo $row["diffrence_standing_cost"] ?></td>
                    <td><?php echo $row["diffrence_standing_urgent_cost"] ?></td>
                    <td><?php echo $row["special_service_cost"] ?></td>
                    <td><?php echo $row["sub_institution"] ?></td>
                    <td class='tetkikgrupgetir' align="center"><?php if ($row["status"] == '1') { ?> <b style="color: green">Aktif</b> <?php } else { ?> <b style="color: darkred">Pasif</b>  <?php } ?></td>
                    <td>
                        <button tetkik-id="<?php echo $tetkikId; ?>" id='<?php echo $row["id"]; ?>' type="button"
                                data-bs-target="#tetkik-fiyat-modal" data-bs-toggle="modal"
                                class="btn btn-success btn-sm tfiyatgetir"><i class="fa-solid fa-edit"></i></button>
                        <button tetkik-id="<?php echo $tetkikId; ?>" id='<?php echo $row["id"]; ?>' type="button"
                                class="btn btn-danger btn-sm  tetkikfiyat-sil-buton"><i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade modal-lg" id="tetkik-fiyat-modal" aria-hidden="true" data-bs-backdrop="static" style="margin-top: 90px !important;">
        <div class="modal-dialog" id="modal-tetkik-fiyat-icerik">></div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tFiyatTable').DataTable({

                //"processing": true,
                "scrollY": false,
                "scrollX": true,
                "paging": true,
                'Visible': true,
                "responsive": true,
                "pageLength": 50,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        text: 'Tektik Fiyat Tanımla',
                        className: 'btn btn-success btn-sm btn-kaydet',
                        attr: {
                            'data-bs-toggle': "modal",
                            'data-bs-target': "#tetkik-fiyat-modal",
                        },
                        action: function (e, dt, button, config) {
                            var process_detail_id ="<?php echo $_GET['tetkikId'] ?>";
                            $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik-fiyat-modal", {
                                process_detail_id: process_detail_id,
                            }, function (getveri) {
                                $("#modal-tetkik-fiyat-icerik").html(getveri);
                            });
                        }
                    }
                ],
            });
        });

        $(document).on("click", ".tfiyatgetir", function () {
            var tFiyatId = $(this).attr('id');
            var tetkikId = $(this).attr('tetkik-id');

            //alert(tetkikDetayId);
            $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik-fiyat-modal", {
                tFiyatId: tFiyatId,
                tetkikId: tetkikId
            }, function (getVeri) {
                $("#modal-tetkik-fiyat-icerik").html(getVeri);
            });
        });

        $(document).on('click', '.tetkikfiyat-sil-buton', function () {
            var id = $(this).attr('id');
            var tetkikId = "<?php echo $tetkikId; ?>"

            alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Branş Silme Nedeni..'></textarea>", function () {
                var delete_detail = $('#delete_detail').val();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik-fiyat-sil',
                    data: {
                        id,
                        delete_detail,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik_fiyat_list", {tetkikId: tetkikId}, function (getveri) {
                            $('#tetkik-fiyat-list').html(getveri);
                        });
                        $('.alertify').remove();
                    }
                });
            }, function () {
                alertify.message('Silme İşleminden Vazgeçtiniz')
            }).set({labels: {ok: "Onayla", cancel: "Vazgeç"}}).set({title: "Tetkik Grubu Silme İşlemini Onayla"});
        });
    </script>


    <?php }  else if ($islem == "tetkik-fiyat-modal") {
        $process_detail_id=$_GET['process_detail_id'];
        $tFiyatId = $_GET["tFiyatId"];
        $tFiyatInfo = tek("select * from transaction_details_costs  where id='$tFiyatId'");
        $tetkikId = $_GET["tetkikId"];
        $tetkikInfo = singular("transaction_detail", "id", $tetkikId);    ?>


        <form class="modal-content" id="formtetkikfiyat" action="javascript:void(0);">
            <div class="modal-header  text-white" style="background-color: #3F72AF">
                <?php if ($_GET["tFiyatId"]){ ?>
                    <h4 class="modal-title ">Tetkik Fiyat Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Tetkik Fiyat Tanımla</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">İşlem Adı</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control" disabled value="<?php echo $tetkikInfo["transaction_name"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Özel Kod</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control" name="special_code" value="<?php echo $tFiyatInfo["special_code"]; ?>">
                        <?php if ($_GET["tFiyatId"] != "") { ?>
                            <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["tFiyatId"]; ?>">
                        <?php } ?>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Kurum</label>
                    </div>
                    <div class="col-9">
                        <select name="institution_id" class="form-control">
                            <option class="text-white bg-danger" disabled selected>seçim yapınız</option>
                            <?php $hedefgrup = "select * from transaction_definitions where definition_type='KURUMLAR' and status='1'";
                            $sql = verilericoklucek($hedefgrup);
                            foreach ($sql as $row) { ?>
                                <option <?php if ($tFiyatInfo["institution_id"] == $row["id"]) {echo "selected"; } ?> value="<?php echo $row["id"]; ?>"><?php echo $row["definition_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Ayaktan Ücreti</label>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" name="standing_cost" value="<?php echo $tFiyatInfo["standing_cost"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Yatan Acil Ücreti</label>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" name="lying_urgent_cost" value="<?php echo $tFiyatInfo["lying_urgent_cost"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Ayaktan Fark Ücreti</label>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" name="diffrence_standing_cost" value="<?php echo $tFiyatInfo["diffrence_standing_cost"]; ?>">
                    </div>
                </div>


                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Yatan Acil Fark Ücreti</label>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" name="diffrence_standing_urgent_cost" value="<?php echo $tFiyatInfo["diffrence_standing_urgent_cost"]; ?>">
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-3">
                        <label for="basicpill-firstname-input" class="form-label text-dark">Yatan Acil Fark Ücreti</label>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" name="diffrence_standing_urgent_cost" value="<?php echo $tFiyatInfo["diffrence_standing_urgent_cost"]; ?>">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php if ($_GET["tFiyatId"]){ ?>
                    <input type="submit" tetkik-id="<?php echo $tetkikId; ?>" class="btn  w-md justify-content-end btn-success btn-sm" id="tetkik_fiyat_guncelle_buton"  data-bs-dismiss="modal"  value="Düzenle"/>
                <?php  }else{ ?>
                    <input type="submit" tetkik-id="<?php echo $tetkikId; ?>" class="btn  w-md justify-content-end btn-success btn-sm" id="tetkik_fiyat_ekle_buton" data-bs-dismiss="modal"  value="Kaydet"/>
                <?php } ?>
                <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
            </div>
        </form>


        <script>
            $("#tetkik_fiyat_ekle_buton").click(function () {
                var gonderilenform = $("#formtetkikfiyat").serialize();
                var tetkikId = $(this).attr("tetkik-id");
                // gonderilenform += "&process_detail_id=" + tetkikId;
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=hizmet-fiyat-ekle-islem',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik_fiyat_list", {tetkikId: tetkikId}, function (e) {
                            $('#tetkik-fiyat-list').html(e);
                        })
                    }
                });
            });

            $("#tetkik_fiyat_guncelle_buton").click(function () {
                var gonderilenform = $("#formtetkikfiyat").serialize();
                var tetkikId = $(this).attr("tetkik-id");
                $.ajax({
                    type: 'POST',
                    url: 'ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=hizmet-fiyat-duzenle-islem',
                    data: gonderilenform,
                    success: function (e) {
                        $("#sonucyaz").html(e);
                        $.get("ajax/tanimlar/tetkikler/hizmetfiyatislem.php?islem=tetkik_fiyat_list", {tetkikId: tetkikId}, function (e) {
                            $('#tetkik-fiyat-list').html(e);
                        })
                    }
                });
            });

        </script>


    <?php }  if ($islem == "hizmet-fiyat-ekle-islem") {
        $_POST['insert_datetime'] = $simdikitarih;
        $_POST['insert_userid'] = $KULLANICI_ID;

        $sql = direktekle("transaction_details_costs", $_POST);

        if ($sql == 1) { ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.success('Ekleme Başarılı');
            </script>
        <?php }
        else { ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.error('Ekleme Başarısız');
            </script>

        <?php } }

    else if ($islem == "hizmet-fiyat-duzenle-islem") {
        $_POST['update_datetime'] = $simdikitarih;
        $_POST['update_userid'] = $KULLANICI_ID;
        $ID = $_POST['id'];
        unset($_POST['id']);
        $sql = direktguncelle("transaction_details_costs", "id", $ID, $_POST);

        if ($sql == 1) { ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.success('Güncelleme Başarılı');
            </script>
        <?php } else { ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.error('Güncelleme Başarısız');
            </script>

        <?php } }

    else if ($islem == "tetkik-fiyat-sil") {
        $detay = $_POST["cancel_detail"];
        $tarih = $simdikitarih;
        $silen_kisi = $KULLANICI_ID;
        $id = $_POST["id"];
        $tgrupsil = canceldetail("transaction_details_costs", "id", $id, $detay, $silen_kisi, $tarih);
        if ($tgrupsil) { ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.success('Silme Başarılı')
            </script>
        <?php }else{ ?>
            <script>
                alertify.set('notifier', 'delay', 8);
                alertify.success('Silme İşlemi Başarısız')
            </script>
            <?php
        }

    }  ?>
