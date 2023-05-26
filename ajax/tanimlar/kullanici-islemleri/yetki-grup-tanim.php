<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
?>


<div class="yetki-grup-tanim">
<div class="card tanimdetay">
 
    <div class="card-body bg-white">
        <table class="table table-bordered table-hover w-100 nawrap display"  id="grupuser-table">

            <thead>
            <tr>
                <th >Grup No</th>
                <th >Grup Adı</th>
                <th >Yetki Adı</th>
                <th >Durum</th>
                <th >İşlem</th>
            </tr>
            </thead>

            <tbody>
            <?php  $say=0;
            $sql = verilericoklucek("select group_name,group_id,status from staff_group GROUP BY group_name,group_id,status");
            foreach ($sql as $row) { ?>

                <tr class="parmak-aktif">
                    <td><?php echo $row['group_id'] ?></td>
                    <td ><?php echo $row["group_name"] ?></td>
                    <td >
                        <?php
                        $say = 0;
                        $groupid=$row['group_id'];
                        if ($groupid){
                            $satirsay=tek("select count(group_id) as say from staff_group where group_id='$groupid'");
                            $getirsay=$satirsay['say'];
                            $demo = "select authority from staff_group inner join authority_definition u on u.id = staff_group.authority_id where staff_group.group_id='$groupid'";
                            $usergetir = verilericoklucek($demo);
                            foreach ($usergetir as $getir) { ?>
                                <?php $say++;
                                $namesurname=$getir["authority"];
                                if ($say < $getirsay) {
                                    $namesurname.= ',';
                                }
                                echo $namesurname; ?>
                            <?php  } }?>
                    </td>
                    <td align="center" title="Durum" <?php if ($row['status']){ ?>grupuser-id="<?php echo $row["group_id"]; ?>" data-bs-target="#yetki-grup-ekle-modal"  data-bs-toggle="modal" class='grup-user-guncelle' id="grup-user-guncelle" <?php } ?> ><?php if($row["status"]=='1'){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                    <td align="center">
                            <i class="fa fa-pen-to-square grup-user-guncelle" title="Düzenle" id="grup-user-guncelle"<?php if ($row['status']){ ?>  grupuser-id="<?php echo $row["group_id"]; ?>"   data-bs-target="#yetki-grup-ekle-modal" data-bs-toggle="modal"  <?php } ?>alt="icon"></i><?php if($row['status']=='0'){ ?>
                            <i class="fa-solid fa-recycle yetki-grup-aktif" title="aktif et" grupuser-id="<?php echo $row["group_id"]; ?>" alt="icon" ><?php }else{ ?>
                            <i class="fa fa-trash grup-user-delete-modal" title="İptal"<?php if ($row['status']){ ?> grupuser-id="<?php echo $row["group_id"]; ?>"   <?php } ?>alt="icon" ><?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<div class="modal fade modal-xl" id="yetki-grup-ekle-modal"  aria-hidden="true">
    <div class="modal-dialog" id="yetki-grup-ekle-icerik"></div>
</div>

<script type="text/javascript">
    $("body").off("click",".yetki-grup-aktif").on("click",".yetki-grup-aktif", function (e) {
        var getir = $(this).attr('grupuser-id');
        $.ajax({
            type:'POST',
            url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yetki-grup-aktiflestir',
            data:{getir},
            success:function(e){
                $('#sonucyaz').html(e);
                $('.panel-htop').load("ajax/tanimlar/kullanici-islemleri/yetki-grup-tanim.php?islem=listeyi-getir");
            }
        });
    });

    $('#grupuser-table').DataTable({
        "responsive": true,
        "scrollY": "60vh",
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
        },
        "lengthChange": false,
        "info": false,

        "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [{
            text: 'Grup Yetki Ekle',
            className: 'btn btn-success btn-sm btn-kaydet',
            attr:  {
                'data-bs-toggle':"modal",
                'data-bs-target':"#yetki-grup-ekle-modal",
            },
            action: function ( e, dt, button, config ) {
                $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-modal.php?islem=grup-yetki-tanim",function(get){
                    $("#yetki-grup-ekle-icerik").html(get);
                });
            }
        }],
    });

    $("body").off("click", "#grup-user-guncelle").on("click", "#grup-user-guncelle", function(e){
        var getir = $(this).attr('grupuser-id');
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-modal.php?islem=grup-yetki-tanim", { getir:getir },function(e){
            $('#yetki-grup-ekle-icerik').html(e);
        });
    });

    $("body").off("click", ".grup-user-delete-modal").on("click", ".grup-user-delete-modal", function(e){
        var id = $(this).attr('grupuser-id');
        alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
            "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='grupuser Silme Nedeni..'></textarea>" , function(){
            var delete_detail = $('#delete_detail').val();

            $.ajax({
                type: 'POST',
                url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=grup-user-sil',
                data: {id,delete_detail},
                success: function (e) {
                    $(".sonucyaz").html(e);
                    $.get("ajax/tanimlar/kullanici-islemleri/yetki-grup-tanim.php?islem=listeyi-getir", {}, function (e) {
                        $('.yetki-grup-tanim:first').html(e);
                    });
                    $('.alertify').remove();
                }
            });
        }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"grupuser Silme İşlemini Onayla"});
    });
</script>
