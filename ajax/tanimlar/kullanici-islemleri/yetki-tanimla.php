<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$kulidsi=$_GET["yetkiverilecekkullanici"];

?>

<div class="sonuc_yaz"></div>

<style>
    .modal-body{
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
</style>

<div class="modal fade" id="yetkidetaygetir" role="dialog">
    <div class="modal-dialog modal-fullscreen" >
    <div id='yetkibilgisiicerik'></div>
    </div>
</div>

<div class="card">
    <div class="card-header p-2 text-white">kullanıcı listesi</div>
    <div class="card-body">
        <table id="user-table" class="table table-striped table-sm table-hover table-bordered nowrap display w-100" style="font-size: 13px;">
            <thead>
            <tr>
                <th>tc kimlik</th>
                <th>adı soyadı</th>
                <th>bölümü</th>
                <th>personel tipi</th>
                <th>Durumu</th>
                <th>i̇şlem</th>
            </tr>
            </thead>
            <tbody>

            <?php $sql = verilericoklucek("select users.id as kullanici_id,
                   users.department,
                   users.personnel_type,
                   users.status,
                   users.name_surname,
                   users.tc_id,
                   units.id,
                   units.department_name,
                   authority_group.id,
                   authority_group.group_name
            from users
                     inner join units on units.id = users.department
                     inner join authority_group on authority_group.id = users.personnel_type
            where users.status = '1'");

            foreach ($sql as $row) { ?>
                <tr>
                    <td><?php echo $row["tc_id"]; ?></td>
                    <td><?php echo $row["name_surname"]; ?></td>
                    <td><?php echo $row["department_name"]; ?></td>
                    <td><?php echo $row["group_name"]; ?></td>
                    <td><?php if ($row["status"] == '1') { echo butontanimla("Aktif", "1"); } else { echo butontanimla("pasif", "0"); } ?></td>
                    <td><button class="yetkibilgisigetir btn btn-dark text-white btn-sm"  id="<?php echo $row["kullanici_id"]; ?>" data-bs-toggle="modal" data-bs-target="#yetkidetaygetir" adi="<?php echo $row["name_surname"]; ?>">Yetki</button>
                        <button class="btn btn-danger btn-sm text-white kullanici_kasa_yetki_clk" id="<?php echo $row["kullanici_id"]; ?>" data-bs-toggle="modal" data-bs-target="#kullanici_kasa_yetki_id" adi="<?php echo $row["name_surname"]; ?>">Kasa Yetki</button>
                        <button class="btn btn-primary btn-sm text-white birim_yetki_clk" id="<?php echo $row["kullanici_id"]; ?> " data-bs-toggle="modal" data-bs-target="#kullanici_birim_yetki" adi="<?php echo $row["name_surname"]; ?>">Birim Yetki</button>
                        <button class="btn btn-warning btn-sm text-white grup_yetki_clk" id="<?php echo $row["kullanici_id"]; ?> " data-bs-toggle="modal" data-bs-target="#modal-getir" adi="<?php echo $row["name_surname"]; ?>">Grup Yetki</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<div class="modal fade" id="kullanici_kasa_yetki_id" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80% !important; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kasa-yetki-header"><span id="adi"></span> -  kasa yetkileri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close">
                    <span  aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div id="kullanici_kasa_yetki_listesi"> </div>
                <div id="kasalar"> </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">kapat</button>
                <button type="button" class="btn btn-primary"   data-bs-dismiss="modal">tamam</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"  id="kullanici_birim_yetki" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80% !important; max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kasa-yetki-header"><span id="adi-2"></span> -  birim yetkileri</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="close">
                    <span  aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">

                <div id="kullanici-birimleri"></div>
                <div id="birimler"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">kapat</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="yetki-tanim">
    <div class="modal-dialog"  style="width: 95%; max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yetki Tanım Oluştur/Düzenle</h5>
                <button type="button" class="btn-close btn-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

           <div class="yetki-tanim-icerik"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">kapat</button>
            </div>
        </div>
    </div>
</div>

<div class="yetki-tanim-alert">
    <form id="yetki_form">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">yetki adı:</label>
            <input type="text" class="form-control" id="yetki_adi" placeholder="yetki adı">
        </div>
        <div class="form-group">
            <label for="message-text" class="col-form-label">açıklama:</label>
            <input class="form-control" id="yetki_aciklama" type="text" placeholder="açıklama" />
        </div>
    </form>
</div>


<script>
$(document).ready(function () {

    var getir = $('.yetki-tanim-alert').html();
    $('.yetki-tanim-alert').remove();
    $(document).on('click', '.yetkitanimclk', function () {
        alertify.confirm(getir, function(){
            $.ajax({
                type: 'post',
                url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yeni-yetki-tanim-olustur',
                data: {
                    authority:$('#yetki_adi').val(),
                    explanation:$('#yetki_aciklama').val(),
                },
                success: function (e) {
                    $(".sonuc_yaz").html(e);
                    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
                        $('#yetki_tanimlari_getir').html(e);
                    });
                }
            });
        }, function(){ alertify.warning('yetki tanımlama i̇şleminden vazgeçtiniz')}).set({labels:{ok: "onayla", cancel: "vazgeç"}}).set({title:"yeni yetki tanımla"});
    });

    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
        $('#yetki_tanimlari_getir').html(e);
    });

    $(document).on('click', '#aktif_pasif_clk', function() {
        $.ajax({
            type: 'POST',
            url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yetki_aktif_pasif_et',
            data: {
                id:$(this).attr('yetki-id'),
                status:$(this).attr('durum'),
            },
            success: function (e) {
                $(".sonuc_yaz").html(e);
                $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
                    $('#yetki_tanimlari_getir').html(e);
                });
            }
        });
    });

    $(".yetkibilgisigetir").click(function () {
        var kulid = $(this).attr('id');
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici_yetkisi_getir", {kullaniciidsi: kulid , adi:$(this).attr('adi') }, function (e) {
            $('#yetkibilgisiicerik').html(e);
        });
    });

//kullanıcının kasa yetkilerini görüntüle-------------------------------------------------------------------------------
    $(".kullanici_kasa_yetki_clk").click(function () {
        var kullanici_id = $(this).attr('id');
        $('#adi').html($(this).attr('adi'));
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici_kasa_yetki", {kullanici_id:kullanici_id}, function (e) {
            $('#kullanici_kasa_yetki_listesi').html(e);
        });
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kasalar", {kullanici_id:kullanici_id }, function (e) {
            $('#kasalar').html(e);
        });
    });

//kullanıcıya kasa yetkisi tanımla--------------------------------------------------------------------------------------
    $(document).on('click', '#kullanici_kasa_tanim_clk', function () {
        $(this).prop("disabled" , true);
        var kasa_id = $(this).attr('kasa-id');
        var kullanici_id = $(this).attr('kullanici-id');
        $.ajax({
            type: 'post',
            url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=kasa_yetkisi_ekle&kisi-adi=' + $('#adi').html(),
            data: { safe_id:kasa_id,userid:kullanici_id },
            success: function (e) {
                $('.sonuc_yaz').html(e);
                $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici_kasa_yetki", {kullanici_id:kullanici_id}, function (e) {
                    $('#kullanici_kasa_yetki_listesi').html(e);
                });
            }
        });
    });

//kullanıcının kasa yetkisini aktif pasif et----------------------------------------------------------------------------
    $(document).on('click', '#pasif-aktif-clk', function () {
        var durum;
        if($(this).is(':checked')){ durum = 1; }else { durum = 0; }

        $.ajax({
            type: 'post',
            url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=kullanici-kasa-yetki-update&kisi-adi='+ $('#adi').html(),
            data: { id:$(this).attr('kasa-yetki-id'),status:durum },
            success: function (e) {
                $('.sonuc_yaz').html(e);

            }
        });
    });

//--yetkigrup  i̇şlemlerini görüntüle-----------------------------------------------------------------------
    $(document).on('click', '.grup_yetki_clk', function () {
        var kullanici_id = $(this).attr('id');
        $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici-grup-yetki-listesi", { kullanici_id:kullanici_id },function(getveri){
            $('#modal-tanim-icerik').html(getveri);
        });
    });

//--kullanıcının brim yetki i̇şlemlerini görüntüle-----------------------------------------------------------------------
    $(document).on('click', '.birim_yetki_clk', function () {
        $('#adi-2').html($(this).attr('adi'));
        var kullanici_id = $(this).attr('id');
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullaniciya-kayitli-olmayan-birimleri-getir", {kullanici_id:kullanici_id }, function (e) {
            $('#birimler').html(e);
        });
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullaniciya-eklenmis-birimler", {kullanici_id:kullanici_id }, function (e) {
            $('#kullanici-birimleri').html(e);
        });
    });

//kullanıcıya birim ekleme i̇şlemi---------------------------------------------------------------------------------------
    $(document).on('click', '#birimn-ekle-clk', function () {
        $(this).prop("disabled" , true);
        var birim_id = $(this).attr('birim-id');
        var kullanici_id = $('#birim-tanim-icin-kullanici-id').val();
        $.ajax({
            type: 'post',
            url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=birim_yetkisi_ekle&kisi-adi=' + $('#adi-2').html(),
            data: { unit_id:birim_id,userid:kullanici_id},
            success: function (e) {
                $('.sonuc_yaz').html(e);
                $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullaniciya-eklenmis-birimler", {kullanici_id:kullanici_id}, function (e) {
                    $('#kullanici-birimleri').html(e);
                });
            }
        });
    });

//birim aktif pasif etme i̇şlemleri--------------------------------------------------------------------------------------
    $(document).on('click', '#pasif-aktif-birim-clk', function () {
        var durum;
        if($(this).is(':checked')){ durum = 1; }else { durum = 0; }
        $.ajax({
            type: 'post',
            url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=kullanici-birim-yetki-update&kisi-adi='+ $('#adi-2').html(),
            data: { id:$(this).attr('birim-yetki-id'),status:durum },
            success: function (e) {
                $('.sonuc_yaz').html(e);
            }
        });
    });

    $('#user-table').DataTable({
        "scrollX": true,
        "scrollY": '50vh',
        "lengthChange": false,
        "pageLength": 25,
        "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
         "buttons": [
            {
                text: 'Yetki Tanım Oluştur/Düzenle',
                className:'btn btn-success btn-sm text-white',
                titleAttr:'Yeni Yetki Tanımı Oluşturmak İçin Tıklayınız...',

                action: function( e, dt, node, config ) {
                    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli-yetki-işlemleri", {}, function (e) {
                        $('.yetki-tanim-icerik').html(e);
                    });
                },
                attr:  {
                    'data-bs-toggle':"modal",
                    'data-bs-target':"#yetki-tanim",
                }
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
        },
    });

});
</script>